<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyBeneficiaryRequest;
use App\Http\Requests\StoreBeneficiaryRequest;
use App\Http\Requests\UpdateBeneficiaryRequest;
use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class BeneficiaryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('beneficiary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiaries = Beneficiary::with(['category', 'media'])->get();

        return view('admin.beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiary_categories = BeneficiaryCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.beneficiaries.create', compact('beneficiary_categories'));
    }

    public function store(StoreBeneficiaryRequest $request)
    {
        $beneficiary = Beneficiary::create($request->all());

        if ($beneficiary->active && ! $beneficiary->approved_at) {
            $beneficiary->approved_at = now();
            $beneficiary->save();
        }

        if ($request->input('photo', false)) {
            $beneficiary->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($request->input('logo_square', false)) {
            $beneficiary->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo_square'))))->toMediaCollection('logo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $beneficiary->id]);
        }

        return redirect()->route('admin.beneficiaries.index');
    }

    public function edit(Beneficiary $beneficiary)
    {
        abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiary_categories = BeneficiaryCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $beneficiary->load('category', 'media');

        return view('admin.beneficiaries.edit', compact('beneficiary', 'beneficiary_categories'));
    }

    public function update(UpdateBeneficiaryRequest $request, Beneficiary $beneficiary)
    {
        $beneficiary->update($request->all());

        if ($beneficiary->active && ! $beneficiary->approved_at) {
            $beneficiary->approved_at = now();
            $beneficiary->save();
        }

        if (! $beneficiary->active) {
            $beneficiary->approved_at = null;
            $beneficiary->save();
        }

        if ($request->input('photo', false)) {
            if (! $beneficiary->photo || $request->input('photo') !== $beneficiary->photo->file_name) {
                if ($beneficiary->photo) {
                    $beneficiary->photo->delete();
                }
                $beneficiary->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($beneficiary->photo) {
            $beneficiary->photo->delete();
        }

        if ($request->input('logo_square', false)) {
            if (! $beneficiary->logo_square || $request->input('logo_square') !== $beneficiary->logo_square->file_name) {
                if ($beneficiary->logo_square) {
                    $beneficiary->logo_square->delete();
                }
                $beneficiary->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo_square'))))->toMediaCollection('logo');
            }
        } elseif ($beneficiary->logo_square) {
            $beneficiary->logo_square->delete();
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $beneficiary->id]);
        }

        return redirect()->route('admin.beneficiaries.index');
    }

    public function show(Beneficiary $beneficiary)
    {
        abort_if(Gate::denies('beneficiary_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiary->load('category', 'media');

        return view('admin.beneficiaries.show', compact('beneficiary'));
    }

    public function destroy(Beneficiary $beneficiary)
    {
        abort_if(Gate::denies('beneficiary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiary->delete();

        return back();
    }

    public function massDestroy(MassDestroyBeneficiaryRequest $request)
    {
        $beneficiaries = Beneficiary::find(request('ids'));

        foreach ($beneficiaries as $beneficiary) {
            $beneficiary->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('beneficiary_create') && Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Beneficiary();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

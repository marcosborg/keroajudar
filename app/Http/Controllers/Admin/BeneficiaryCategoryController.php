<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyBeneficiaryCategoryRequest;
use App\Http\Requests\StoreBeneficiaryCategoryRequest;
use App\Http\Requests\UpdateBeneficiaryCategoryRequest;
use App\Models\BeneficiaryCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeneficiaryCategoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('beneficiary_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiaryCategories = BeneficiaryCategory::with(['media'])->get();

        return view('admin.beneficiaryCategories.index', compact('beneficiaryCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('beneficiary_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.beneficiaryCategories.create');
    }

    public function store(StoreBeneficiaryCategoryRequest $request)
    {
        $beneficiaryCategory = BeneficiaryCategory::create($request->all());

        if ($request->input('image', false)) {
            $beneficiaryCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        return redirect()->route('admin.beneficiary-categories.index');
    }

    public function edit(BeneficiaryCategory $beneficiaryCategory)
    {
        abort_if(Gate::denies('beneficiary_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.beneficiaryCategories.edit', compact('beneficiaryCategory'));
    }

    public function update(UpdateBeneficiaryCategoryRequest $request, BeneficiaryCategory $beneficiaryCategory)
    {
        $beneficiaryCategory->update($request->all());

        if ($request->input('image', false)) {
            if (! $beneficiaryCategory->image || $request->input('image') !== $beneficiaryCategory->image->file_name) {
                if ($beneficiaryCategory->image) {
                    $beneficiaryCategory->image->delete();
                }
                $beneficiaryCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($beneficiaryCategory->image) {
            $beneficiaryCategory->image->delete();
        }

        return redirect()->route('admin.beneficiary-categories.index');
    }

    public function show(BeneficiaryCategory $beneficiaryCategory)
    {
        abort_if(Gate::denies('beneficiary_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiaryCategory->load('beneficiaries');

        return view('admin.beneficiaryCategories.show', compact('beneficiaryCategory'));
    }

    public function destroy(BeneficiaryCategory $beneficiaryCategory)
    {
        abort_if(Gate::denies('beneficiary_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiaryCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyBeneficiaryCategoryRequest $request)
    {
        $beneficiaryCategories = BeneficiaryCategory::find(request('ids'));

        foreach ($beneficiaryCategories as $beneficiaryCategory) {
            $beneficiaryCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

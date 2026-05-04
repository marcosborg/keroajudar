<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAdvertisementRequest;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AdvertisementsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('advertisement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::with(['media'])->orderBy('type')->orderBy('sort_order')->get();

        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        abort_if(Gate::denies('advertisement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Advertisement::TYPES;

        return view('admin.advertisements.create', compact('types'));
    }

    public function store(StoreAdvertisementRequest $request)
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        unset($data['logo']);

        $advertisement = Advertisement::create($data);

        if ($request->input('logo', false)) {
            $advertisement->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
        }

        return redirect()->route('admin.advertisements.index');
    }

    public function edit(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Advertisement::TYPES;

        return view('admin.advertisements.edit', compact('advertisement', 'types'));
    }

    public function update(UpdateAdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        unset($data['logo']);

        $advertisement->update($data);

        if ($request->input('logo', false)) {
            if (! $advertisement->logo || $request->input('logo') !== $advertisement->logo->file_name) {
                if ($advertisement->logo) {
                    $advertisement->logo->delete();
                }
                $advertisement->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($advertisement->logo) {
            $advertisement->logo->delete();
        }

        return redirect()->route('admin.advertisements.index');
    }

    public function show(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertisements.show', compact('advertisement'));
    }

    public function destroy(Advertisement $advertisement)
    {
        abort_if(Gate::denies('advertisement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisement->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertisementRequest $request)
    {
        $advertisements = Advertisement::find(request('ids'));

        foreach ($advertisements as $advertisement) {
            $advertisement->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

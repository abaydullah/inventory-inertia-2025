<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banners = BannerResource::collection(Banner::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%");
        })->paginate(10)->withQueryString());
        return inertia()->render('Banner/Index',['banners' => $banners,'filters' => $request->only(['search'])]);
    }


    public function store(StoreRequest $request)
    {
        $banner = Banner::create($this->storeData($request));

        if (!$banner){
            return back()->with('error', 'Banner Not Created Successfully');
        }

        return back()->with('success', 'Banner Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $banner = Banner::find($id);
        $banner->update($this->storeData($request,$banner));

        return back()->with('success', 'Banner Updated Successfully');

    }
    public function destroy($id)
    {
        $banner = Banner::find($id);
        if (Storage::disk('public')->exists($banner->banner)){
            Storage::disk('public')->delete($banner?->banner);
        }
        $banner->delete();

        return back()->with('success', 'Banner deleted Successfully');

    }

    public function storeData($request,$banner = null): array
    {
        if ($request->banner){
            if ($banner !== null && Storage::disk('public')->exists($banner->banner)){
                Storage::disk('public')->delete($banner?->banner);
            }
            return $request->only('title', 'link')

                + ['banner' => $request->banner->storePublicly(
                    'banners',
                    ['disk' => 'public']
                )]+[
                    'type' => $request->banner->extension()
                ];
        }
        return $request->only('title', 'link');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendImage;
use Illuminate\Http\Request;

class FrontendImageController extends Controller
{
    public function index()
    {
        $data['pageTitle'] = 'Frontend Images';
        $data['navFrontendImagesActiveClass'] = 'active';
        $data['images'] = FrontendImage::orderBy('section')->orderBy('order')->get();

        return view('backend.frontend_images.index')->with($data);
    }

    public function edit(FrontendImage $image)
    {
        $data['pageTitle'] = 'Edit Image';
        $data['navFrontendImagesActiveClass'] = 'active';
        $data['image'] = $image;

        return view('backend.frontend_images.edit')->with($data);
    }

    public function update(Request $request, FrontendImage $image)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:4096'
        ]);

        $data = $request->only(['label', 'description']);

        if ($request->hasFile('image')) {
            $oldImage = $image->image;
            $filename = uploadImage($request->image, filePath('frontend'), null, $oldImage);
            $data['image'] = $filename;
        }

        $image->update($data);

        return redirect()->route('admin.frontend.images.index')
            ->with('success', 'Image updated successfully.');
    }
}

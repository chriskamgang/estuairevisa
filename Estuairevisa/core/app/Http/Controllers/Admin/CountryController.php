<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{

    public function index()
    {
        $data['pageTitle'] = 'Country List';
        $data['countries'] = Country::latest()->paginate();
        return view('backend.country.index')->with($data);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:jpg,png,jpeg',
            'status' => 'required|in:0,1',
            'is_slider' => 'required|in:0,1'
        ]);

        $data['image'] = uploadImage($request->image, filePath('country'));
        Country::create($data);

        return redirect()->route('admin.country.index')->with('success', 'Country created successfully.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'sometimes|file|mimes:jpg,png,jpeg',
            'status' => 'required|in:0,1',
            'is_slider' => 'required|in:0,1'
        ]);

        $country = Country::findOrFail($id);
        if($request->image){
            $data['image'] = uploadImage($request->image, filePath('country'),old:$country->image);
        }


        $country->update($data);

        return redirect()->route('admin.country.index')->with('success', 'Country updated successfully.');
    }

    public function delete($id)
    {

        $country = Country::findOrFail($id);
        $country->delete();
        
        return redirect()->route('admin.country.index')->with('success', 'Country deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VisaFileField;

class VisaFileFieldController extends Controller
{

    public function index()
    {
        $data['pageTitle'] = 'Visa Field';
        $data['fields'] = VisaFileField::latest()->paginate();

        return view('backend.visa_field.index')->with($data);
    }

    public function create()
    {
        $data['pageTitle'] = "Create Field";
        return view('backend.visa_field.create')->with($data);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'label' => 'required',
            'name' => 'required|unique:visa_file_fields',
            'short_description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg',
            'status' => 'required|in:0,1'
        ]);

        $data['image'] = uploadImage($request->image, filePath('field'));

        VisaFileField::create($data);

        return redirect()->route('admin.field.index')->with('success', 'Field created successfully.');
    }


    public function edit($id)
    {
        $data['pageTitle'] = "Field Update";
        $data['field'] = VisaFileField::findOrFail($id);
        return view('backend.visa_field.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'label' => 'required',
            'name' => 'required|unique:visa_file_fields,name,'.$id,
            'short_description' => 'required',
            'image' => 'sometimes|file|mimes:jpg,png,jpeg',
            'status' => 'required|in:0,1'
        ]);

        $field = VisaFileField::findOrFail($id);

        if($request->image){
            $data['image'] = uploadImage($request->image, filePath('field'),old:$field->image);
        }
      
        $field->update($data);
        return redirect()->route('admin.field.index')->with('success', 'Field updated successfully.');
    }

    public function destroy($id)
    {

        $country = VisaFileField::findOrFail($id);
        $country->delete();

        return redirect()->route('admin.field.index')->with('success', 'Field deleted successfully.');
    }
}

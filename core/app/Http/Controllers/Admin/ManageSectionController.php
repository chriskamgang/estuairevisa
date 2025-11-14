<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SectionData;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManageSectionController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Sections';

        $sections = [];

        $jsonUrl = resource_path('views/') . 'sections.json';

        $sections = array_filter(json_decode(file_get_contents($jsonUrl), true));


        return view('backend.frontend.sections', compact('pageTitle', 'sections'));
    }

    public function section(Request $request)
    {
        $search = $request->search;
        $data['navManagePagesActiveClass'] = 'active';

        $data['pageTitle'] = "Manage {$request->name} Section";
        $data['section_name'] = $request->name;
        $data['section'] = $this->getJsonData($request->name);

        $data['content'] = SectionData::where('key', "$request->name.content")->first();

        $data['elements'] = SectionData::when($search, function ($query) use ($search) {
            return $query->where('data->heading', 'LIKE', '%' . $search . '%');
        })->where('key', "$request->name.element")->latest()->paginate(50);

        return view('backend.frontend.index')->with($data);
    }

    public function sectionContentUpdate(Request $request)
    {

        $section = $this->getJsonData($request->name)['content'];

        $rules = [];

        foreach ($section as $key => $sec) {

            if ($sec == 'file') {
                $rules += [
                    $key => 'sometimes|required|image|mimes:jpg,jpeg,png,webp|max:4096'
                ];
            } elseif ($sec == 'text') {
                $rules += [
                    $key => 'required'
                ];
            } elseif ($sec == 'textarea') {
                $rules += [
                    $key => 'required'
                ];
            } elseif ($sec == 'textarea_nic') {
                $rules += [
                    $key => 'required'
                ];
            }
        }
        $data = [];

        $data = $request->validate($rules);

        $content = SectionData::where('key', "$request->name.content")->first();



        if (in_array('file', array_values($section))) {
            $key = array_search('file', $section);

            if ($request->hasFile($key)) {
                $filename = uploadImage($request->$key, filePath($request->name), null, $content->data->$key ?? '');

                $data[$key] = $filename;
            } else {
                $data[$key] = $content->data->$key;
            }
        }

        if (!$content) {
            SectionData::create([
                'key' => "$request->name.content",
                'data' => $data
            ]);
        } else {
            $content->data = $data;

            $content->save();
        }


        clearcache();


        $notify[] = ['success', "{$request->name} Created Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function sectionElement(Request $request)
    {
        $pageTitle = ucwords($request->name) . " Element";

        $section = $this->getJsonData($request->name)['element'];

        return view('backend.frontend.element', compact('pageTitle', 'section'));
    }


    public function sectionElementCreate(Request $request)
    {


        $section = $this->getJsonData($request->section)['element'];


        $rules = [];

        foreach ($section as $key => $sec) {

            if ($sec == 'file') {
                $rules += [
                    $key => 'required|image|mimes:jpg,jpeg,png,webp|max:4096'
                ];
            } elseif ($sec == 'text') {
                $rules += [
                    $key => 'required'
                ];
            } elseif ($sec == 'textarea' || $sec == 'textarea_nic') {
                $rules += [
                    $key => 'required'
                ];
            } elseif ($sec == 'icon') {
                $rules += [
                    $key => 'required'
                ];
            }
        }

        $data = $request->validate($rules);

        if (array_key_exists('unique', $section)) {

            $uniqueField = $section['unique'];

            $isDataFound = SectionData::whereJsonContains("data->$uniqueField", $request->$uniqueField)->first();

            if ($isDataFound) {

                $notify[] = ['error', "Already has a {$request->section} {$uniqueField}"];

                return redirect()->back()->withNotify($notify);
            }
        }


        if (array_key_exists('slug', $section)) {
            $data['slug'] = Str::slug($request->slug);
        }


        if (in_array('file', array_values($section))) {
            $key = array_search('file', $section);

            if ($key && $request->hasFile($key)) {

                $filename = uploadImage($request->$key, filePath($request->section));

                $data[$key] = $filename;
            }
        }

        SectionData::create([
            'key' => "$request->section.element",
            'data' => $data
        ]);

        clearcache();

        $notify[] = ['success', "{$request->section} Created Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function editElement($name, SectionData $element)
    {
        $pageTitle = 'Edit Element';

        $section = $this->getJsonData($name)['element'];

        return view('backend.frontend.edit', compact('pageTitle', 'element', 'section', 'name'));
    }

    public function updateElement($name, SectionData $element, Request $request)
    {


        $section = $this->getJsonData($request->name)['element'];



        $rules = [];

        foreach ($section as $key => $sec) {
            if ($sec == 'file') {
                $rules += [
                    $key => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:4096'
                ];
            } elseif ($sec == 'text') {
                $rules += [
                    $key => 'required'
                ];
            } elseif ($sec == 'textarea' || $sec == 'textarea_nic') {
                $rules += [
                    $key => 'required'
                ];
            } elseif ($sec == 'icon') {
                $rules += [
                    $key => 'required'
                ];
            }
        }


        $data = $request->validate($rules);


        if (array_key_exists('unique', $section)) {
            $uniqueField = $section['unique'];

            $isDataFound = SectionData::where('id', '!=', $element->id)->whereJsonContains("data->$uniqueField", $request->$uniqueField)->first();

            if ($isDataFound) {

                $notify[] = ['error', "Already has a {$request->section} {$uniqueField}"];

                return redirect()->back()->withNotify($notify);
            }
        }

        $image = array_search('file', $section);


        if ($image && in_array('file', array_values($section))) {

            if ($request->hasFile($image)) {

                $filename = uploadImage($request->$image, filePath($request->name), null, $element->data->$image ?? '');

                $data[$image] = $filename;
            }
        }

        if ($image && !array_key_exists($image, $data)) {
            $data[$image] = $element->data->$image;
        }

        if (array_key_exists('slug', $section)) {
            $data['slug'] = Str::slug($request->slug);
        }

        $element->update([
            'data' => $data
        ]);

        clearcache();

        $notify[] = ['success', "{$name} Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function deleteElement($name, SectionData $element)
    {

        $section = $this->getJsonData($name)['element'];

        if (in_array('file', array_values($section))) {

            $image = array_search('file', $section);


            unlink(filePath($name) . '/' . $element->data->$image);
        }

        $element->delete();

        clearcache();

        $notify[] = ['success', "{$name} Deleted Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    private function getJsonData($name)
    {

        $jsonUrl = resource_path('views/') . 'sections.json';

        $sections = json_decode(file_get_contents($jsonUrl), true);

        return $sections[$name] ?? [];
    }
}

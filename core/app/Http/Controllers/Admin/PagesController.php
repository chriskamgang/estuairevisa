<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        $data['pageTitle'] = 'Manage Pages';
        $data['navManagePagesActiveClass'] = '';
        $data['subNavPagesActiveClass'] = 'active';
        $data['languages'] = Language::all();
        $data['pages'] = Page::when($request->search, function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        });

        if (isset($filter['language'])) {
            $lanugage = Language::where('short_code', $filter['language'])->first();
            $data['pages'] = $data['pages']->where('language_id', $lanugage->id)->paginate();
        } else {

            $data['pages'] = $data['pages']->where('language_id', selectedLanguage()->id)->paginate();
        }

        return view('backend.frontend.pages')->with($data);
    }

    public function pageCreate()
    {
        $pageTitle = 'Create Page';
        $languages = Language::all();
        return view('backend.frontend.pages_create', compact('pageTitle', 'languages'));
    }

    public function pageInsert(Request $request)
    {

        $request->validate([
            'page' => 'required',
            'seo_description' => 'required',
            'status' => 'required|in:0,1',
            'language_id' => 'required',
            'is_breadcrumb' => 'required|in:0,1'
        ]);
        
        
        $exist = Page::where('language_id',$request->language_id)->where('name',$request->page)->first();
        
        if($exist)
        {
            return back()->with('error','Already page available');
        }

        Page::create([
            'name' => $request->page,
            'seo_description' => $request->seo_description,
            'language_id' => $request->language_id,
            'slug' => Str::slug($request->page),
            'status' => $request->status,
            'is_breadcrumb' => $request->is_breadcrumb
        ]);

        $notify[] = ['success', 'Page Created Successfully'];

        return redirect()->route('admin.frontend.pages')->withNotify($notify);
    }

    public function pageEdit(Request $request, Page $page)
    {
        $pageTitle = "Edit Page";
        $languages = Language::all();
        return view('backend.frontend.page_edit', compact('pageTitle', 'page', 'languages'));
    }

    public function pageUpdate(Request $request, Page $page)
    {

        $request->validate([
            'page' => 'required',
            'seo_description' => 'required',
            'language_id' => 'required',
            'status' => 'required|in:0,1',
            'is_breadcrumb' => 'required|in:0,1'

        ]);

        $page->update([
            'name' => $request->page,
            'slug' => Str::slug($request->page),
            'seo_description' => $request->seo_description,
            'status' => $request->status,
            'language_id' => $request->language_id,
            'is_breadcrumb' => $request->is_breadcrumb
        ]);

        $notify[] = ['success', 'Page Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function pageDelete(Request $request, Page $page)
    {
        if ($page->is_default == 1) {
            $notify[] = ['error', 'Default can not deleted'];
            return back()->withNotify($notify);
        }
        
        $page->delete();

        $notify[] = ['success', 'Page Deleted Successfully'];

        return back()->withNotify($notify);
    }


    public function pageContent($id)
    {
        $data['page'] = Page::findOrFail($id);

        $version = "1.0.0";

        $jsonUrl = resource_path('views/all.json');

        if (!file_exists($jsonUrl)) {
            abort(500, 'Configuration file all.json not found');
        }

        $sections = json_decode(file_get_contents($jsonUrl), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(500, 'Invalid JSON in all.json: ' . json_last_error_msg());
        }

        foreach ($sections as $key => $section) {

            if (!isset($section['no_selection'])) {
                try {
                    $viewPath = 'backend.frontend.sections.' . $key;
                    if (view()->exists($viewPath)) {
                        $data['contents'][$key] = [
                            'html' => view($viewPath)->render(),
                            'icon' => $section['others']['icon'] ?? ""
                        ];
                    } else {
                        Log::warning("View not found for section: {$key}");
                    }
                } catch (\Exception $e) {
                    Log::error("Error rendering section {$key}: " . $e->getMessage());
                }
            }
        }


        $data['version'] = $version;

        $components = !empty($data['page']->components) ? json_decode($data['page']->components, true) : [];

        $components = str_replace("{base_url}", url('/'), json_encode($components));
        $data['components'] = json_decode($components, true);

        $styles = !empty($data['page']->styles) ? json_decode($data['page']->styles, true) : [];
        $styles = str_replace("{base_url}", url('/'), json_encode($styles));
        $data['styles'] = json_decode($styles, true);

        return view('backend.frontend.content', $data);
    }

    public function saveContent(Request $request)
    {
        $data = Page::findOrFail($request->id);

        // Replace with 'Base URL' Shortcode
        $html = str_replace(url('/'), "{base_url}", $request->html);
        $html = "<div class='pagebuilder-content'>" . $html . "</div>";

     
    
        $html = $this->replace_content_inside_delimiters("<visa-section>", "</visa-section>", '[pagebuilder-visa][/pagebuilder-visa]', $html);
        $html = $this->replace_content_inside_delimiters("<airline-section>", "</airline-section>", '[pagebuilder-airline][/pagebuilder-airline]', $html);
        $html = $this->replace_content_inside_delimiters("<destination-section>", "</destination-section>", '[pagebuilder-destination][/pagebuilder-destination]', $html);
        $html = $this->replace_content_inside_delimiters("<how_work-section>", "</how_work-section>", '[pagebuilder-how_work][/pagebuilder-how_work]', $html);
        $html = $this->replace_content_inside_delimiters("<featured-section>", "</featured-section>", '[pagebuilder-featured][/pagebuilder-featured]', $html);
        $html = $this->replace_content_inside_delimiters("<allblog-section>", "</allblog-section>", '[pagebuilder-allblog][/pagebuilder-allblog]', $html);
        $html = $this->replace_content_inside_delimiters("<allproperty-section>", "</allproperty-section>", '[pagebuilder-allproperty][/pagebuilder-allproperty]', $html);
        $html = $this->replace_content_inside_delimiters("<contact-section>", "</contact-section>", '[pagebuilder-contact][/pagebuilder-contact]', $html);
        $html = $this->replace_content_inside_delimiters("<faq-section>", "</faq-section>", '[pagebuilder-faq][/pagebuilder-faq]', $html);
        $html = $this->replace_content_inside_delimiters("<testimonial-section>", "</testimonial-section>", '[pagebuilder-testimonial][/pagebuilder-testimonial]', $html);
        $html = $this->replace_content_inside_delimiters("<whychooseus-section>", "</whychooseus-section>", '[pagebuilder-whychooseus][/pagebuilder-whychooseus]', $html);

        $data->html = $html;

        $css = str_replace(url('/'), "{base_url}", $request->css);
        $data->css = $css;

        $components = str_replace(url('/'), "{base_url}", $request->components);
        $data->components = $components;

        $styles = str_replace(url('/'), "{base_url}", $request->styles);
        $data->styles = $styles;

        $data->save();

        return "success";
    }

    public function replace_content_inside_delimiters($start, $end, $new, $html)
    {
        $startDelimiterLength = strlen($start);
        $endDelimiterLength = strlen($end);
        $startFrom = $contentStart = $contentEnd = 0;
        $contents = [];
        while (false !== ($contentStart = strpos($html, $start, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($html, $end, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $content = substr($html, $contentStart, $contentEnd - $contentStart);
            $contents[] =  $content;
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        if (!empty($contents)) {
            foreach ($contents as $content) {
                if (!empty($content)) {
                    $html = str_replace($content, $new, $html);
                }
            }
        }

        return $html;
    }


    public function uploadPbImage(Request $request)
    {
        $files = $request->file('files');
        $assets = [];

        foreach ($files as $key => $file) {
            $directory = "asset/frontend/img/pagebuilder/";
            @mkdir($directory, 0775, true);
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);


            $path = url($directory . $filename);
            $name = $file->getClientOriginalName();

            $assets[] = [
                'name' => $name,
                'type' => 'image',
                'src' =>  $path,
                'height' => 350,
                'width' => 250
            ];
        }

        return response()->json(['data' => $assets]);
    }

    public function removePbImage(Request $request)
    {
        $path = str_replace(url('/') . '/', '', $request->path);
        @unlink($path);
    }

    public function uploadPbTui(Request $request)
    {
        $image = $request->base_64;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid() . '.' . 'png';

        $path = 'asset/frontend/img/pagebuilder/' . $imageName;
        \File::put($path, base64_decode($image));

        $assets[] = [
            'name' => $imageName,
            'type' => 'image',
            'src' =>  url($path),
            'height' => 350,
            'width' => 250
        ];

        return response()->json(['data' => $assets]);
    }
}

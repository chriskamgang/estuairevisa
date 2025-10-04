<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display the menu management view.
     */
    public function index()
    {

        $data['pageTitle'] = 'Menu Management';
        $data['languages'] = Language::all();


        if (isset(request()->lang)) {

            $Id = request()->lang;

            $data['headerMenus'] = Menu::where('menu_type', 'headers')->whereHas('page', function ($q) use ($Id) {
                $q->where('language_id', $Id);
            })->get();


            $data['quickLink'] = Menu::where('menu_type', 'footers')->where('footer_part_type', 'quick_link')->whereHas('page', function ($q) use ($Id) {
                $q->where('language_id', $Id);
            })->get();


            $data['company'] = Menu::where('menu_type', 'footers')->where('footer_part_type', 'company')->whereHas('page', function ($q) use ($Id) {
                $q->where('language_id', $Id);
            })->get();


            $data['pages'] = Page::where('status', 1)->where('language_id', $Id)->get();

        } else {
            $data['headerMenus'] = Menu::headers()->get();
            $data['quickLink'] = Menu::quickLink()->get();
            $data['company'] = Menu::company()->get();
            $data['pages'] = Page::where('status', 1)->where('language_id', selectedLanguage()->id)->get();
        }



        return view('backend.menu.index', $data);
    }

    /**
     * Store header menus in the database.
     */
    public function headerStore(Request $request,$langId=0)
    {
        

        
        DB::beginTransaction();

        try {
            // Clear existing header menus
            
            if($langId == 0){
                $langId = selectedLanguage()->id;
                
            }
            
            Menu::where('menu_type', 'headers')->where('language_id', $langId)->delete();

            $pages = explode(',', $request->header_menus);
            // Save new header menus
            foreach ($pages as $pageId) {
                Menu::create([
                    'page_id' => $pageId,
                    'language_id' => $langId,
                    'menu_type' => 'headers',
                ]);
            }

            DB::commit();
            
            if($langId != 0){
                return redirect()->route('admin.menu',['lang'=>$langId])->with('success', 'Header menu successfully saved.');
            }
            
            return redirect()->route('admin.menu')->with('success', 'Header menu successfully saved.');
            
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.menu')
                ->with('error', 'Failed to save header menu. Please try again.');
        }
    }

    /**
     * Store footer menus in the database.
     */
    public function footerStore(Request $request,$langId=0)
    {
        DB::beginTransaction();

        try {
            
            $tId = $langId;
    
            if($langId == 0){
                $langId = selectedLanguage()->id;
            }
            

            // Clear existing footer menus
            Menu::where('menu_type', 'footers')->where('language_id', $langId)->delete();

            // Save company footer menus
            $companyIds = $this->normalizeMenuIds($request->footer_company_menus);
            $this->saveFooterMenus($companyIds, 'company',$langId);

            // Save quick link footer menus
            $quickLinkIds = $this->normalizeMenuIds($request->footer_quick_link);
            $this->saveFooterMenus($quickLinkIds, 'quick_link',$langId);

            DB::commit();
            
            if($tId == 0){
                return redirect()->route('admin.menu')->with('success', 'Footer menus successfully saved.');
            }

            return redirect()->route('admin.menu',['lang'=>$langId])->with('success', 'Footer menus successfully saved.');
            
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.menu')
                ->with('error', 'Failed to save footer menu. Please try again.');
        }
    }

    /**
     * Normalize menu IDs to ensure array conversion.
     */
    private function normalizeMenuIds($menuIds)
    {
        if (is_array($menuIds)) {
            return $menuIds;
        }

        if (is_string($menuIds)) {
            return array_filter(array_map('trim', explode(',', $menuIds)));
        }

        return [];
    }

    /**
     * Save footer menus to the database.
     */
    private function saveFooterMenus(array $menuIds, string $footerPartType,$langId)
    {

        foreach ($menuIds as $pageId) {
            Menu::create([
                'page_id' => $pageId,
                'language_id' => $langId,
                'menu_type' => 'footers',
                'footer_part_type' => $footerPartType,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Contact;
use App\Models\Payment;
use App\Models\SectionData;
use \App\Models\Refferal;
use Auth;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {

        $pageTitle = 'Home';
        $page = Page::where('name', 'home')->where('language_id',selectedLanguage()->id)->firstOrFail();
        return view('frontend.home', compact('pageTitle', 'page'));
    }

    public function page(Request $request)
    {

        $page = Page::where('slug', $request->pages)->where('language_id',selectedLanguage()->id)->firstOrFail();

        if($page->slug == 'home'){
            return redirect()->route('home');
        }




        $pageTitle = "{$page->name}";

        $htmlData = $page->page_content;

        $data = $this->processHtmlData($htmlData);
        $processedHtml = $data['content'];
        $hasSection = $data['shortcode_count'] > 0 ? true : false;

        return view('frontend.pages', compact('pageTitle', 'processedHtml','page','hasSection'));
    }

    public function processHtmlData($htmlData)
    {
        $pattern = '/\[\[(.*?)\]\]/';
        $shortcodeCount = 0; // Initialize the counter
     
        // Replace the shortcodes with section content
        $processedContent = preg_replace_callback($pattern, function ($matches) use (&$shortcodeCount) {
            $shortcode = $matches[1];
            $shortcodeCount++; // Increment the counter for each match
            $html = view('frontend.sections.' . $shortcode)->render();

            return $html ?? '';
        }, $htmlData);

        // Return the processed content and the shortcode count
        return [
            'content' => $processedContent,
            'shortcode_count' => $shortcodeCount,
        ];
    }

    public function contactSend(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        Contact::create($data);
        
        
        sendGeneralMail($data);
        

        $notify[] = ['success', 'Contact With us successfully'];

        return back()->withNotify($notify);
    }


    public function blog($id)
    {

        $pageTitle = "Recent Blog";
        $data = SectionData::where('key', 'blog.element')->where('id', $id)->first();
        $comments = Comment::with('user')->where('blog_id', $id)->latest()->paginate();
        $recentblog = SectionData::where('key', 'blog.element')->latest()->limit(6)->paginate();

        $shareComponent = \Share::page(
            url()->current(),
            'Share',
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();


        return view('frontend.pages.blog', compact('pageTitle', 'data', 'comments', 'recentblog', 'shareComponent'));
    }

    public function blogComment(Request $request, $id)
    {

        $request->validate([
            'comment' => 'required',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'blog_id' => $id,
            'comment' => $request->comment,
        ]);

        $notify[] = ['success', 'Comment Post Successfully'];

        return back()->withNotify($notify);
    }

    public function affiliate()
    {
        $pageTitle = "Affiliates";
        $invest = Refferal::where('type', 'invest')->first();
        $commission = Refferal::where('type', 'interest')->first();
        $sections = Page::where('name', 'affiliate')->first();
        return view('frontend.pages.affiliate', compact('pageTitle', 'invest', 'commission', 'sections'));
    }


    public function allblog()
    {
        $pageTitle = 'Blog';
        $blogs = SectionData::where('key', 'blog.element')->paginate(6);
        return view('frontend.pages.allblog', compact('pageTitle', 'blogs'));
    }


    public function changeLang($lang)
    {
        App::setLocale($lang);

        session()->put('locale', $lang);

        return redirect()->back()->with('success', __('Successfully Changed Language'));
    }

    public function paymentLog()
    {
        $pageTitle = 'Payment Log';

        $transactions = Payment::where('user_id', auth()->id())->latest()->paginate();

        return view('frontend.user.payment_log', compact('pageTitle', 'transactions'));
    }
}

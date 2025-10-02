<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Language;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Skip database queries if migrations haven't run yet
        try {
            $general = GeneralSetting::first();
            $default = Language::where('is_default',1)->first();
        } catch (\Exception $e) {
            $general = null;
            $default = null;
        }

        View::composer('backend.layout.sidebar', function ($view) {
            $deactiveUser = User::where('status',0)->count();
            $view->with('deactiveUser',  $deactiveUser);
        });


        View::composer('backend.layout.navbar', function ($view) {
            $notifications = auth()->guard('admin')->user()->unreadNotifications->filter(function($notification){
                return $notification->data['type'] == "others";
            });
            $view->with('notifications',  $notifications);
        });

        View::composer('backend.layout.navbar', function ($view) {
            $pendingTicketNotifications = auth()->guard('admin')->user()->unreadNotifications->filter(function($notification){
                return $notification->data['type'] == "ticket";
            });
            $view->with('pendingTicketNotifications',  $pendingTicketNotifications);
        });


        View::composer('backend.layout.navbar', function ($view) {
            $pendingpayment = Payment::where('payment_status',2)->get();
            $view->with('pendingpayment',  $pendingpayment);
        });

        View::composer('backend.layout.navbar', function ($view) {
            $pendingPaymentNotification = auth()->guard('admin')->user()->unreadNotifications->filter(function ($notification) {
                return $notification->data['type'] === 'deposit' || $notification->data['type'] == 'payment';
            });
            $view->with('pendingPaymentNotification',  $pendingPaymentNotification);
        });

        View::composer('backend.layout.navbar', function ($view) {
            $pendingDeposit = Deposit::where('payment_status',0)->get();
            $view->with('pendingDeposit',  $pendingDeposit);
        });

  

        View::composer('backend.layout.navbar', function ($view) {
            $pendingTicket= Ticket::where('status',2)->get();
            $view->with('pendingTicket', $pendingTicket);
        });

        Paginator::useBootstrap();

        view()->share('general', $general);
        view()->share('default', $default);

        $urlSections = [];

        $jsonUrl = resource_path('views/').'sections.json';

        $urlSections = array_filter(json_decode(file_get_contents($jsonUrl),true));

        try {
            $pages = Page::where('name','!=','home')
                    ->where('status',1)->get();
            $language_top = Language::latest()->get();
        } catch (\Exception $e) {
            $pages = collect();
            $language_top = collect();
        }

        view()->share('pages',$pages);
        view()->share('urlSections',$urlSections);
        view()->share('language_top', $language_top);

    }
}

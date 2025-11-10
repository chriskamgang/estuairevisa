 <?php

    use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
    use App\Http\Controllers\Admin\Auth\LoginController;
    use App\Http\Controllers\Auth\SocialAuthController;
    use App\Http\Controllers\Admin\Auth\ResetPasswordController;
    use App\Http\Controllers\Admin\EmailTemplateController;
    use App\Http\Controllers\Admin\GeneralSettingController;
    use App\Http\Controllers\Admin\HomeController;
    use App\Http\Controllers\Admin\ManageGatewayController;
    use App\Http\Controllers\Admin\LanguageController;
    use App\Http\Controllers\Admin\ManageSectionController;
    use App\Http\Controllers\Admin\ManageUserController;
    use App\Http\Controllers\Admin\PagesController;
    use App\Http\Controllers\Admin\PlanController;
    use App\Http\Controllers\Admin\VisaApplyController as BackendVisaApplyContoller;

    use App\Http\Controllers\Gateway\coinpayments\ProcessController as CoinpaymentsProcessController;
    use App\Http\Controllers\TicketController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Gateway\voguepay\ProcessController as VoguepayProcessController;
    use App\Http\Controllers\PaymentController as ControllersPaymentController;
    use App\Http\Controllers\SiteController;
    use App\Http\Controllers\Admin\DynamicGatewayController;
    use App\Http\Controllers\Admin\AdminController;
    use App\Http\Controllers\Admin\CountryController;
    use App\Http\Controllers\Admin\ReportController;
    use App\Http\Controllers\Admin\MenuController;
    use App\Http\Controllers\Admin\TicketController as AdminTicketController;
    use App\Http\Controllers\Admin\VisaFileFieldController;
    use App\Http\Controllers\Auth\ForgotPasswordController as AuthForgotPasswordController;
    use App\Http\Controllers\Auth\LoginController as AuthLoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\DepositController;
    use App\Http\Controllers\Gateway\flutterwave\ProcessController as FlutterwaveProcessController;
    use App\Http\Controllers\Gateway\mollie\ProcessController;
    use App\Http\Controllers\Gateway\nowpayments\ProcessController as NowpaymentsProcessController;
    use App\Http\Controllers\Gateway\paghiper\ProcessController as PaghiperProcessController;
    use App\Http\Controllers\Gateway\paystack\ProcessController as PaystackProcessController;
    use App\Http\Controllers\Gateway\paytm\ProcessController as PaytmProcessController;
    use App\Http\Controllers\Gateway\razorpay\ProcessController as RazorpayProcessController;
    use App\Http\Controllers\Gateway\vouguepay\ProcessController as VouguepayProcessController;
    use App\Http\Controllers\Gateway\freemopay\ProcessController as FreemopayProcessController;
    use App\Http\Controllers\Gateway\orangemoney\ProcessController as OrangemoneyProcessController;
    use App\Http\Controllers\Gateway\mtnmoney\ProcessController as MtnmoneyProcessController;
    use App\Http\Controllers\LoginSecurityController;
    use App\Http\Controllers\VisaApplyController;
    use App\Http\Controllers\VisaController;
  
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.login');
        });

        Route::get('login', [LoginController::class, 'loginPage'])->name('login');

        Route::post('login', [LoginController::class, 'login']);

        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
        Route::post('password/reset', [ForgotPasswordController::class, 'sendResetCodeEmail']);
        Route::post('password/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.code');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
        Route::post('password/reset/change', [ResetPasswordController::class, 'reset'])->name('password.change');


        Route::middleware(['admin', 'demo'])->group(function () {

            Route::get('pendingList', [AdminTicketController::class, 'pendingList'])->name('ticket.pendingList');
            Route::post('ticket/reply', [AdminTicketController::class, 'reply'])->name('ticket.reply');
            Route::resource('ticket', AdminTicketController::class);

            Route::get('dashboard', [HomeController::class, 'dashboard'])->name('home');

            Route::get('logout', [LoginController::class, 'logout'])->name('logout');

            Route::get('profile', [AdminController::class, 'profile'])->name('profile');
            Route::post('profile', [AdminController::class, 'profileUpdate'])->name('profile.update');

            Route::post('change/password', [AdminController::class, 'changePassword'])->name('change.password');

            Route::get('login/user/{id}', [ManageUserController::class, 'loginAsUser'])->name('login.user');

            Route::get('menus', [MenuController::class, 'index'])->name('menu');
            Route::post('menus/header/{id?}', [MenuController::class, 'headerStore'])->name('menu.header.store');
            Route::post('menus/footer/{id?}', [MenuController::class, 'footerStore'])->name('menu.footer.store');


            Route::get('general/setting', [GeneralSettingController::class, 'index'])->name('general.setting');

            Route::post('general/setting', [GeneralSettingController::class, 'generalSettingUpdate']);
            Route::get('general/preloader', [GeneralSettingController::class, 'preloader'])->name('general.preloader');

            Route::post('general/preloader', [GeneralSettingController::class, 'preloaderUpdate']);

            Route::get('general/analytics', [GeneralSettingController::class, 'analytics'])->name('general.analytics');
            Route::post('general/analytics', [GeneralSettingController::class, 'analyticsUpdate']);

            Route::get('users', [ManageUserController::class, 'index'])->name('user');

            Route::get('users/details/{user}', [ManageUserController::class, 'userDetails'])->name('user.details');
            Route::post('users/update/{user}', [ManageUserController::class, 'userUpdate'])->name('user.update');
            Route::post('users/balance/{user}', [ManageUserController::class, 'userBalanceUpdate'])->name('user.balance.update');
            Route::post('users/mail/{user}', [ManageUserController::class, 'sendUserMail'])->name('user.mail');
            Route::get('users/search', [ManageUserController::class, 'index'])->name('user.search');
            Route::get('users/disabled', [ManageUserController::class, 'disabled'])->name('user.disabled');

            Route::get('user/{status}', [ManageUserController::class, 'userStatusWiseFilter'])->name('user.filter');
            
            
            Route::get('contact/list',[ManageUserController::class,'contactList'])->name('contact.list');

            Route::controller(BackendVisaApplyContoller::class)->group(function () {


                Route::post('visa/status/change/{file}', 'changeStatus')->name('visa.change.status');
                Route::get('visa/{order}/details', 'details')->name('visa.details');

                Route::get('visa/list/{type?}', 'list')->name('visa.list');
                Route::get('visa/file/download/{file}', 'download')->name('visa.download');
            });

            Route::get('general/cookie/consent', [GeneralSettingController::class, 'cookieConsent'])->name('general.cookie');
            Route::post('general/cookie/consent', [GeneralSettingController::class, 'cookieConsentUpdate']);

            Route::get('general/google/recaptcha', [GeneralSettingController::class, 'recaptcha'])->name('general.recaptcha');
            Route::post('general/google/recaptcha', [GeneralSettingController::class, 'recaptchaUpdate']);


            Route::get('general/socialite/', [GeneralSettingController::class, 'socialite'])->name('general.socialite');
            Route::post('general/socialite', [GeneralSettingController::class, 'socialiteUpdate']);


            Route::get('general/seo/manage', [GeneralSettingController::class, 'seoManage'])->name('general.seo');
            Route::post('general/seo/manage', [GeneralSettingController::class, 'seoManageUpdate']);

            Route::get('cacheclear', [GeneralSettingController::class, 'cacheClear'])->name('general.cacheclear');

            Route::get('gateway/bank', [ManageGatewayController::class, 'bank'])->name('payment.bank');
            Route::post('gateway/bank', [ManageGatewayController::class, 'bankUpdate']);

            Route::get('gateway/paypal', [ManageGatewayController::class, 'paypal'])->name('payment.paypal');
            Route::post('gateway/paypal', [ManageGatewayController::class, 'paypalUpdate']);

            Route::get('gateway/orangemoney', [ManageGatewayController::class, 'orangeMoney'])->name('payment.orangemoney');
            Route::post('gateway/orangemoney', [ManageGatewayController::class, 'orangeMoneyUpdate']);

            Route::get('gateway/mtnmoney', [ManageGatewayController::class, 'mtnMoney'])->name('payment.mtnmoney');
            Route::post('gateway/mtnmoney', [ManageGatewayController::class, 'mtnMoneyUpdate']);

            Route::resource('gateway', DynamicGatewayController::class)->except(['show']);



            Route::get('deposit/log', [ManageGatewayController::class, 'depositLog'])->name('deposit.log');
            Route::get('deposit/all', [ManageGatewayController::class, 'depositAll'])->name('deposit.all');
            Route::get('deposit/rejected', [ManageGatewayController::class, 'depositRejected'])->name('deposit.rejected');
            Route::get('deposit/payments/{trx}', [ManageGatewayController::class, 'depositDetails'])->name('deposit.trx');
            Route::post('deposit/payments/accept/{trx}', [ManageGatewayController::class, 'depositAccept'])->name('deposit.accept');
            Route::post('deposit/payments/reject/{trx}', [ManageGatewayController::class, 'depositReject'])->name('deposit.reject');


            Route::get('manual/payments', [ManageGatewayController::class, 'manualPayment'])->name('manual');
            Route::get('manual/payments/{trx}', [ManageGatewayController::class, 'manualPaymentDetails'])->name('manual.trx');
            Route::post('manual/payments/accept/{trx}', [ManageGatewayController::class, 'manualPaymentAccept'])->name('manual.accept');
            Route::post('manual/payments/reject/{trx}', [ManageGatewayController::class, 'manualPaymentReject'])->name('manual.reject');

            Route::get('{status}/payments', [ManageGatewayController::class, 'manualPayment'])->name('manual.status');

          
            Route::get('email/config', [EmailTemplateController::class, 'emailConfig'])->name('email.config');
            Route::post('email/config', [EmailTemplateController::class, 'emailConfigUpdate']);

            Route::get('email/templates', [EmailTemplateController::class, 'emailTemplates'])->name('email.templates');

            Route::get('email/templates/{template}', [EmailTemplateController::class, 'emailTemplatesEdit'])->name('email.templates.edit');
            Route::post('email/templates/{template}', [EmailTemplateController::class, 'emailTemplatesUpdate']);

            Route::get('language', [LanguageController::class, 'index'])->name('language.index');
            Route::post('language', [LanguageController::class, 'store']);
            Route::post('language/edit/{id}', [LanguageController::class, 'update'])->name('language.edit');
            Route::post('language/delete/{id}', [LanguageController::class, 'delete'])->name('language.delete');
            Route::get('language/translator/{lang}', [LanguageController::class, 'transalate'])->name('language.translator');
            Route::post('language/translator/{lang}', [LanguageController::class, 'transalateUpate']);
            Route::get('language/import', [LanguageController::class, 'import'])->name('language.import');

            Route::get('export/{lang}', [LanguageController::class, 'export'])->name('export');
            Route::post('import/{lang}', [LanguageController::class, 'import'])->name('import');

            Route::get('changeLang', [LanguageController::class, 'changeLang'])->name('changeLang');
            Route::get('subscribers', [HomeController::class, 'subscribers'])->name('subscribers');

            Route::get('pages/index', [PagesController::class, 'index'])->name('frontend.pages');
            Route::get('pages/create', [PagesController::class, 'pageCreate'])->name('frontend.pages.create');
            Route::get('pages/content/{id}', [PagesController::class, 'pageContent'])->name('frontend.pages.content');
            Route::post('/pages/content/save', [PagesController::class, 'saveContent'])->name('frontend.pagebuilder.save');
            Route::post('pages/create', [PagesController::class, 'pageInsert']);
            Route::get('pages/edit/{page}', [PagesController::class, 'pageEdit'])->name('frontend.pages.edit');
            Route::post('pages/edit/{page}', [PagesController::class, 'pageUpdate']);
            Route::get('pages/search', [PagesController::class, 'index'])->name('frontend.search');
            Route::post('pages/delete/{page}', [PagesController::class, 'pageDelete'])->name('frontend.pages.delete');

            Route::post('/upload/pagebuilder', [PagesController::class, 'uploadPbImage'])->name('frontend.pb.upload');
            Route::post('/remove/img/pagebuilder', [PagesController::class, 'removePbImage'])->name('frontend.pb.remove');
            Route::post('/upload/tui/pagebuilder', [PagesController::class, 'uploadPbTui'])->name('frontend.pb.tui.upload');


            Route::resource('plans', PlanController::class);

            // Frontend Images Management
            Route::get('frontend/images', [App\Http\Controllers\Admin\FrontendImageController::class, 'index'])->name('frontend.images.index');
            Route::get('frontend/images/{image}/edit', [App\Http\Controllers\Admin\FrontendImageController::class, 'edit'])->name('frontend.images.edit');
            Route::put('frontend/images/{image}', [App\Http\Controllers\Admin\FrontendImageController::class, 'update'])->name('frontend.images.update');

            Route::get('country', [CountryController::class, 'index'])->name('country.index');
            Route::post('country', [CountryController::class, 'store'])->name('country.store');
            Route::post('country/{id}/update', [CountryController::class, 'update'])->name('country.update');
            Route::post('country/{id}/delete', [CountryController::class, 'delete'])->name('country.delete');


            Route::resource('visa/field', VisaFileFieldController::class);


            Route::get('manage/section', [ManageSectionController::class, 'index'])->name('frontend.section');

            Route::get('manage/section/{name}', [ManageSectionController::class, 'section'])->name('frontend.section.manage');
            Route::post('manage/section/{name}', [ManageSectionController::class, 'sectionContentUpdate']);


            Route::get('manage/element/{name}', [ManageSectionController::class, 'sectionElement'])->name('frontend.element');

            Route::get('manage/element/{name}/search', [ManageSectionController::class, 'section'])->name('frontend.element.search');

            Route::post('manage/element/{name}', [ManageSectionController::class, 'sectionElementCreate']);
            Route::get('edit/{name}/element/{element}', [ManageSectionController::class, 'editElement'])->name('frontend.element.edit');
            Route::post('edit/{name}/element/{element}', [ManageSectionController::class, 'updateElement']);

            Route::post('delete/{name}/element/{element}', [ManageSectionController::class, 'deleteElement'])->name('frontend.element.delete');

            Route::get('blog-category', [ManageSectionController::class, 'blogCategory'])->name('frontend.blog');
            Route::post('blog-category', [ManageSectionController::class, 'blogCategoryStore']);
            Route::post('blog-category/{blog}', [ManageSectionController::class, 'blogCategoryUpdate'])->name('frontend.blog.update');
            Route::post('blog-category/delete/{blog}', [ManageSectionController::class, 'blogCategoryDelete'])->name('frontend.blog.delete');

            Route::get('faq-category', [ManageSectionController::class, 'faqCategory'])->name('frontend.faq');
            Route::post('faq-category', [ManageSectionController::class, 'faqCategoryStore']);
            Route::post('faq-category/{faq}', [ManageSectionController::class, 'faqCategoryUpdate'])->name('frontend.faq.update');
            Route::post('faq-category/delete/{faq}', [ManageSectionController::class, 'faqCategoryDelete'])->name('frontend.faq.delete');

            Route::get('transaction-log', [HomeController::class, 'transaction'])->name('transaction');
          

            Route::get('/mark-as-read/{type?}', [HomeController::class, 'markNotification'])->name('markNotification');

            Route::get('payment-report', [ReportController::class, 'paymentReport'])->name('payment.report');
        });
    });


    Route::name('user.')->group(function () {

        Route::middleware('guest')->group(function () {
            Route::get('register/{reffer?}', [RegisterController::class, 'index'])->name('register')->middleware('reg_off');
            Route::post('register/{reffer?}', [RegisterController::class, 'register'])->middleware('reg_off');

            Route::get('login', [AuthLoginController::class, 'index'])->name('login');
            Route::post('login', [AuthLoginController::class, 'login']);

            Route::get('google/login', [SocialAuthController::class, 'redirectToGoogle'])->name('google');
            Route::get('google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');

            Route::get('facebook/login', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook');
            Route::get('facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('facebook.callback');


            Route::get('forgot/password', [AuthForgotPasswordController::class, 'index'])->name('forgot.password');
            Route::post('forgot/password', [AuthForgotPasswordController::class, 'sendVerification']);
            Route::get('verify/code', [AuthForgotPasswordController::class, 'verify'])->name('auth.verify');
            Route::post('verify/code', [AuthForgotPasswordController::class, 'verifyCode']);
            Route::get('reset/password', [AuthForgotPasswordController::class, 'reset'])->name('reset.password');
            Route::post('reset/password', [AuthForgotPasswordController::class, 'resetPassword']);

            Route::get('verify/email', [AuthLoginController::class, 'emailVerify'])->name('email.verify');
            Route::post('verify/email', [AuthLoginController::class, 'emailVerifyConfirm'])->name('email.verify.confirm');
        });

        Route::middleware(['auth', 'inactive', 'is_email_verified'])->group(function () {


            Route::get('2fa', [LoginSecurityController::class, 'show2faForm'])->name('2fa');

            Route::post('2fa/generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
            Route::post('2fa/enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
            Route::post('2fa/disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
            Route::post('2fa/2faVerify', function () {
                return redirect(URL()->previous());
            })->name('2faVerify')->middleware('2fa');




            Route::get('authentication-verify', [AuthForgotPasswordController::class, 'verifyAuth'])->name('authentication.verify')->withoutMiddleware('is_email_verified');

            Route::post('authentication-verify/email', [AuthForgotPasswordController::class, 'verifyEmailAuth'])->name('authentication.verify.email')->withoutMiddleware('is_email_verified');

            Route::post('authentication-verify/sms', [AuthForgotPasswordController::class, 'verifySmsAuth'])->name('authentication.verify.sms')->withoutMiddleware('is_email_verified');


            Route::middleware('2fa')->group(function () {

                Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
                Route::post('dashboard', [UserController::class, 'transferMoney']);
                Route::post('logout', [RegisterController::class, 'signOut'])->name('logout')->withoutMiddleware('2fa');

                Route::get('profile/setting', [UserController::class, 'profile'])->name('profile');
                Route::post('profile/setting', [UserController::class, 'profileUpdate'])->name('profileupdate');
                Route::get('profile/change/password', [UserController::class, 'changePassword'])->name('change.password');
                Route::post('profile/change/password', [UserController::class, 'updatePassword'])->name('update.password');

                // Firebase Push Notifications
                Route::post('save-fcm-token', [UserController::class, 'saveFCMToken'])->name('save.fcm.token');

                Route::resource('ticket', TicketController::class);
                Route::post('ticket/reply', [TicketController::class, 'reply'])->name('ticket.reply');
                Route::get('ticket/reply/status/change/{id}', [TicketController::class, 'statusChange'])->name('ticket.status-change');

                Route::get('ticket/status/{status}', [TicketController::class, 'ticketStatus'])->name('ticket.status');

                Route::get('ticket/attachement/{id}', [TicketController::class, 'ticketDownload'])->name('ticket.download');
                Route::get('referral', [UserController::class, 'referral'])->name('referral');

                Route::get('gateways', [ControllersPaymentController::class, 'gateways'])->name('gateways');

                Route::post('paynow/{id}', [ControllersPaymentController::class, 'paynow'])->name('paynow');

                Route::get('gateways/{id}/details', [ControllersPaymentController::class, 'gatewaysDetails'])->name('gateway.details');


                Route::post('gateways/{id}/details', [ControllersPaymentController::class, 'gatewayRedirect']);

                Route::get('vouguepay/success', [VouguepayProcessController::class, 'returnSuccess'])->name('vouguepay.redirect');

                Route::get('coinpayments', [CoinpaymentsProcessController::class, 'coinPay'])->name('coin.pay');


                Route::get('payment-success', [ProcessController::class, 'paymentSuccess'])->name('payment.success');

                Route::get('nowpay-success', [NowpaymentsProcessController::class, 'ipn'])->name('nowpay.success');

                Route::get('flutter-success', [FlutterwaveProcessController::class, 'returnSuccess'])->name('flutter.success');

                Route::get('paystack-success', [PaystackProcessController::class, 'returnSuccess'])->name('paystack.success');

                Route::post('razor/success', [RazorpayProcessController::class, 'returnSuccess'])->name('razor.success');
                Route::post('paghiper/success', [PaghiperProcessController::class, 'returnSuccess'])->name('paghiper.success');

                Route::get('transaction/log', [UserController::class, 'transactionLog'])->name('transaction.log');

                Route::get('deposit', [DepositController::class, 'deposit'])->name('deposit');
                Route::get('deposit/log', [DepositController::class, 'depositLog'])->name('deposit.log');
                Route::get('payment/log', [SiteController::class, 'paymentLog'])->name('payment.log');


                Route::controller(VisaController::class)->group(function () {
                    Route::get('visa/all', 'all')->name('visa.all');
                    Route::get('visa/{order}/details', 'details')->name('visa.details');
                    Route::get('visa/{order}/resubmit', 'reSubmit')->name('visa.resubmit');
                    Route::post('visa/{order}/resubmit', 'reSubmitStore');
                    Route::post('visa/payment','visaPayment')->name('visa.payment');
                });
            });
        });
    });

    Route::get('/', [SiteController::class, 'index'])->name('home');

    Route::get('changeLang/{lang}', [SiteController::class, 'changeLang'])->name('changeLang');
    Route::get('blogs', [SiteController::class, 'allblog'])->name('allblog');
    Route::get('blog/{id}/{slug}', [SiteController::class, 'blog'])->name('blog');
    Route::post('blog/comment/{id}', [SiteController::class, 'blogComment'])->name('blogcomment');
    Route::post('subscribe', [DashboardController::class, 'subscribe'])->name('subscribe');
    Route::post('contact', [SiteController::class, 'contactSend'])->name('contact');



    Route::controller(VisaApplyController::class)->group(function () {
        Route::get('visa/applay/start/{id}', 'startApplay')->name('visa.applay.start');
        Route::get('visa/applay/infos', 'applayInfos')->name('visa.applay.infos');
        Route::get('visa/applay/documents', 'applyDocuments')->name('visa.applay.documents');
        Route::post('visa/applay/documents', 'submitDocument')->name('visa.applay.submit.documents');
        Route::post('visa/applay/info/submit', 'infoSubmit')->name('visa.applay.info.submit');
        Route::get('visa/applay/checkout', 'checkout')->name('visa.applay.checkout');
        Route::get('cart', 'cart')->name('visa.cart');
        Route::get('track/order', 'track')->name('visa.track');
        Route::get('visa/applay/plan/change/{id}','planChange')->name('visa.plan.change');
        Route::get('visa/search/countries','planSearchByCountry')->name('visa.country.search');
        Route::get('cart/remove/{trx}', 'removeCart')->name('visa.cart.remove');
        Route::get('visa/applay/placeorder', 'placeorder')->middleware(['auth', 'inactive', 'is_email_verified'])->name(('visa.placeorder'));
    });

    // Routes Freemopay (Orange Money & MTN Mobile Money avec API)
    Route::prefix('payment')->name('freemopay.')->group(function () {
        Route::get('freemopay/waiting', [FreemopayProcessController::class, 'waiting'])->name('waiting');
        Route::post('freemopay/callback', [FreemopayProcessController::class, 'callback'])->name('callback');
        Route::post('freemopay/check-status', [FreemopayProcessController::class, 'checkStatus'])->name('check.status');
    });

    // Routes Orange Money Manuel
    Route::prefix('payment')->name('orangemoney.')->group(function () {
        Route::post('orangemoney/submit-proof', [OrangemoneyProcessController::class, 'submitProof'])->name('submit.proof');
    });

    // Routes MTN Mobile Money Manuel
    Route::prefix('payment')->name('mtnmoney.')->group(function () {
        Route::post('mtnmoney/submit-proof', [MtnmoneyProcessController::class, 'submitProof'])->name('submit.proof');
    });


    Route::get('{pages}', [SiteController::class, 'page'])->name('pages');

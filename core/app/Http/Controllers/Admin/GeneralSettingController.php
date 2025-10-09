<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GeneralSettingController extends Controller
{

    public function socialite()
    {
        $data['pageTitle'] = 'Socialite Setting';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavSocialiteActiveClass'] = 'active';
        $data['general'] = GeneralSetting::first();
        return view('backend.setting.socialite')->with($data);
    }

    public function socialiteUpdate(Request $request)
    {

        $setting = GeneralSetting::first();
        $setting->google_client_id = $request->google_client_id;
        $setting->google_client_secret = $request->google_client_secret;
        $setting->google_callback = $request->google_callback;
        $setting->google_status = $request->google_status;
        $setting->facebook_client_id = $request->facebook_client_id;
        $setting->facebook_client_secret = $request->facebook_client_secret;
        $setting->facebook_callback = $request->facebook_callback;
        $setting->facebook_status = $request->facebook_status;
        $setting->save();

        return back()->with('success','Setting updated');
    }


    public function index()
    {
        $data['pageTitle'] = 'General Setting';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavGeneralSettingsActiveClass'] = 'active';
        $data['general'] = GeneralSetting::first();
        $data['timezone'] = json_decode(file_get_contents(resource_path('views/backend/setting/timezone.json')));
        return view('backend.setting.general_setting')->with($data);
    }

    public function generalSettingUpdate(Request $request)
    {

        $general = GeneralSetting::first();

        $request->validate([
            'sitename' => 'required',
            'signup_bonus' => 'gte:0',
            'site_currency' => 'required|max:10',
            'logo' => [Rule::requiredIf(function () use ($general) {
                return $general == null;
            }), 'image', 'mimes:jpg,png,jpeg'],
            'icon' => [Rule::requiredIf(function () use ($general) {
                return $general == null;
            }), 'image', 'mimes:jpg,png,jpeg'],
            'login_image' => [Rule::requiredIf(function () use ($general) {
                return $general == null;
            }), 'image', 'mimes:jpg,png,jpeg'],

            'secondary_logo' => [Rule::requiredIf(function () use ($general) {
                return $general == null;
            }), 'image', 'mimes:jpg,png,jpeg'],
        ]);

        if ($request->has('logo')) {

            $logo = 'logo' . '.' . $request->logo->getClientOriginalExtension();

            $request->logo->move(filePath('logo'), $logo);
        }

        if ($request->has('secondary_logo')) {

            $secondary_logo = 'secondary_logo' . '.' . $request->secondary_logo->getClientOriginalExtension();

            $request->secondary_logo->move(filePath('logo'), $secondary_logo);
        }

        if ($request->has('icon')) {

            $icon = 'icon' . '.' . $request->icon->getClientOriginalExtension();

            $request->icon->move(filePath('icon'), $icon);
        }
        if ($request->has('login_image')) {

            $login_image = 'login_image' . '.' . $request->login_image->getClientOriginalExtension();

            $request->login_image->move(filePath('login'), $login_image);
        }

        if ($request->has('frontend_login_image')) {

            $frontend_login_image = 'frontend_login_image' . '.' . $request->frontend_login_image->getClientOriginalExtension();

            $request->frontend_login_image->move(filePath('frontendlogin'), $frontend_login_image);
        }



        GeneralSetting::updateOrCreate([
            'id' => 1
        ], [
            'sitename' => $request->sitename,
            'signup_bonus' => $request->signup_bonus,
            'site_currency' => $request->site_currency,
            'user_reg' => $request->user_reg == 'on' ? 1 : 0,
            'is_email_verification_on' => $request->is_email_verification_on == 'on' ? 1 : 0,
            'is_sms_verification_on' => $request->is_sms_verification_on == 'on' ? 1 : 0,
            'logo' => isset($logo) ? ($logo ?? '') : GeneralSetting::first()->logo,
            'favicon' => isset($icon) ? ($icon ?? '') : GeneralSetting::first()->favicon,
            'secondary_logo' => isset($secondary_logo) ? ($secondary_logo ?? '') : GeneralSetting::first()->secondary_logo,
            'primary_color' =>  $request->primary_color ?? '',
            'login_image' => isset($login_image) ? ($login_image ?? '') : GeneralSetting::first()->login_image,
            'copyright' => $request->copyright,
            'is_referral_active' => $request->is_referral_active,
            'referral_amount_type' => $request->referral_amount_type,
            'referral_amount' => $request->referral_amount,
            'whatsapp_notification' => $request->whatsapp_notification == 'on' ? 1 : 0,
            'ultramsg_instance_id' => $request->ultramsg_instance_id,
            'ultramsg_token' => $request->ultramsg_token


        ]);

        $this->setEnv([
            'NEXMO_KEY' => $request->sms_username,
            'NEXMO_SECRET' => $request->sms_password,
            'APP_TIMEZONE' => $request->timezone
        ]);

        $notify[] = ['success', 'General setting has been updated.'];
        return back()->withNotify($notify);
    }


    public function setEnv(array $values)
    {

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {

                $str .= "\n";
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }

    public function preloader()
    {
        $data['pageTitle'] = 'Preloader Setting';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavPreloaderActiveClass'] = 'active';

        $data['general'] = GeneralSetting::first();

        return view('backend.setting.preloader')->with($data);
    }

    public function preloaderUpdate(Request $request)
    {
        $general = GeneralSetting::first();

        $request->validate([
            'preloader_status' => 'required',
        ]);


        $general->preloader_status = $request->preloader_status;

        $general->save();



        $notify[] = ['success', "Preloader Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function analytics()
    {
        $data['pageTitle'] = 'Google Analytics Setting';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavAnalyticsActiveClass'] = 'active';
        $data['general'] = GeneralSetting::first();

        return view('backend.setting.analytics')->with($data);
    }

    public function analyticsUpdate(Request $request)
    {
        $general = GeneralSetting::first();

        $data = $request->validate([
            'analytics_key' => 'required',
            'analytics_status' => 'required'
        ]);

        $general->update($data);


        $notify[] = ['success', "Analytics Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function cookieConsent()
    {
        $data['pageTitle'] = 'Cookie Consent';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavCookieActiveClass'] = 'active';
        $data['cookie'] = GeneralSetting::first();

        return view('backend.setting.cookie')->with($data);
    }

    public function cookieConsentUpdate(Request $request)
    {
        $data = $request->validate([
            'allow_modal' => 'required|integer',
            'button_text' => 'required|max:100',
            'cookie_text' => 'required',
            'cookie_link' => 'required'
        ]);

        GeneralSetting::updateOrCreate(['id' => 1], $data);

        $notify[] = ['success', "Cookie Consent Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function recaptcha()
    {
        $data['pageTitle'] = 'Google Recaptcha';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavRecaptchaActiveClass'] = 'active';

        $data['recaptcha'] = GeneralSetting::first();



        return view('backend.setting.recaptcha')->with($data);
    }

    public function recaptchaUpdate(Request $request)
    {
        $data = $request->validate([
            'allow_recaptcha' => 'required',
            'recaptcha_key' => 'required',
            'recaptcha_secret' => 'required'
        ]);

        GeneralSetting::updateOrCreate(['id' => 1], $data);

        $notify[] = ['success', "Recaptcha Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }



    public function seoManage()
    {
        $data['pageTitle'] = 'Manage SEO';
        $data['navGeneralSettingsActiveClass'] = 'active';
        $data['subNavSEOManagerActiveClass'] = 'active';
        $data['seo'] = GeneralSetting::first();

        return view('backend.setting.seo')->with($data);
    }

    public function seoManageUpdate(Request $request)
    {

        $general = GeneralSetting::first();

        $data = $request->validate([
            'seo_description' => 'required',
        ]);

        $general->update($data);

        $notify[] = ['success', "Seo Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }

    public function databaseBackup()
    {
        $mysqlHostName      = env('DB_HOST');
        $mysqlUserName      = env('DB_USERNAME');
        $mysqlPassword      = env('DB_PASSWORD');
        $DbName             = env('DB_DATABASE');

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();

        $output = '';
        foreach ($result as $table) {

            $show_table_query = "SHOW CREATE TABLE " . $table[0] . "";
            $statement = $connect->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();

            foreach ($show_table_result as $show_table_row) {
                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table[0] . "";
            $statement = $connect->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();

            for ($count = 0; $count < $total_row; $count++) {
                $single_result = $statement->fetch(\PDO::FETCH_ASSOC);

                $table_column_array = array_keys($single_result);
                $table_value_array = array_values($single_result);
                $output .= "\nINSERT INTO $table[0] (";
                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);
    }

    public function cacheClear()
    {

       clearcache();

        return back()->with('success', 'Caches cleared successfully!');
    }
}

<?php

use App\Models\EmailTemplate;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\SectionData;
use Illuminate\Support\Facades\Artisan;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\User;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

function getUserWithChildren($userId)
{
    $user = \App\Models\User::find($userId);

    if (!$user) {
        return [];
    }

    $user->children = \App\Models\User::where('reffered_by', $user->username)
        ->get()
        ->map(function ($child) {
            return getUserWithChildren($child->id); // Recursively fetch children
        });

    return $user;
}


function makeDirectory($path)
{
    if (file_exists($path)) {
        return true;
    }

    return mkdir($path, 0755, true);
}

function removeFile($path)
{
    return file_exists($path) && is_file($path) ? unlink($path) : false;
}


function replaceBaseUrl($content)
{
    $content = str_replace("{base_url}", url('/'), $content);
    return $content;
}


function referMoney($user, $amount)
{
    $setting = GeneralSetting::first();

    if ($setting->is_referral_active) {
        $parent = User::where('username', $user->reffered_by)->first();
        if ($parent) {
            $bonus = $setting->referral_amount;

            if ($setting->referral_amount_type == 'percentage') {
                $bonus = ($setting->referral_amount / 100) * $amount;
            }

            $parent->balance += $bonus;
            $parent->save();

            Transaction::create([
                'trx' => strtoupper(Str::random()),
                'gateway_id' => 0,
                'amount' => $bonus,
                'currency' => $setting->site_currency,
                'details' => 'Referral Bonus from ' . $user->username,
                'charge' => 0,
                'type' => '+',
                'gateway_transaction' => "",
                'user_id' => $parent->id,
                'payment_status' => 1,
            ]);

            sendMail('REFERRAL_COMMISSION', [
                'amount' => $bonus,
                'currency' => $setting->site_currency
            ], $parent);

            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function convertHtml($content)
{

    $content = str_replace("{base_url}", url('/'), $content);



    $content = str_replace("[pagebuilder-visa][/pagebuilder-visa]", nonEditableRender("visa"), $content);
    $content = str_replace("[pagebuilder-airline][/pagebuilder-airline]", nonEditableRender("airline"), $content);
    $content = str_replace("[pagebuilder-destination][/pagebuilder-destination]", nonEditableRender("destination"), $content);
    $content = str_replace("[pagebuilder-how_work][/pagebuilder-how_work]", nonEditableRender("how_work"), $content);
    $content = str_replace("[pagebuilder-featured][/pagebuilder-featured]", nonEditableRender("featured"), $content);
    $content = str_replace("[pagebuilder-allblog][/pagebuilder-allblog]", nonEditableRender("all_blog"), $content);
    $content = str_replace("[pagebuilder-blog][/pagebuilder-blog]", nonEditableRender("blog"), $content);
    $content = str_replace("[pagebuilder-contact][/pagebuilder-contact]", nonEditableRender("contact"), $content);
    $content = str_replace("[pagebuilder-faq][/pagebuilder-faq]", nonEditableRender("faq"), $content);
    $content = str_replace("[pagebuilder-testimonial][/pagebuilder-testimonial]", nonEditableRender("testimonial"), $content);
    $content = str_replace("[pagebuilder-whychooseus][/pagebuilder-whychooseus]", nonEditableRender("why_choose_us"), $content);

    return $content;
}


function nonEditableRender($file)
{
    return view("backend.frontend.not_editable.{$file}");
}






function getMenus($type)
{
    $language = selectedLanguage();

    if ($type == "headers") {
        return Menu::headers()->whereHas('page', function ($query) use ($language) {
            $query->where('language_id', $language->id);
        })->get();
    }

    if ($type == 'company') {
        return Menu::company()->whereHas('page', function ($query) use ($language) {
            $query->where('language_id', $language->id);
        })->get();
    }

    if ($type == 'quick_link') {
        return Menu::quickLink()->whereHas('page', function ($query) use ($language) {
            $query->where('language_id', $language->id);
        })->get();
    }
}

function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{
    $path = makeDirectory($location);
   
    if (!$path) {
        throw new Exception('Directory could not be created.');
    }

    // Remove old files if provided
    if (!empty($old)) {
        removeFile($location . '/' . $old);
    }

    $extension = strtolower($file->getClientOriginalExtension());
    $filename = 'image-' . Str::uuid() . '.' . $extension;
    
    $image = Image::make($file);

    // Resize if size is given
    if (!empty($size)) {

        [$width, $height] = explode('x', strtolower($size));
        $canvas = Image::canvas($width, $height);

        $resized = $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $canvas->insert($resized, 'center');
        $canvas->save($location . '/' . $filename, 90, $extension); // <-- format & quality
    } else {
        $image->save($location . '/' . $filename, 90, $extension);
    }

    // Create thumbnail
    if (!empty($thumb)) {
        [$tWidth, $tHeight] = explode('x', strtolower($thumb));
        Image::make($file)
            ->resize($tWidth, $tHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($location . '/thumb_' . $filename, 75, $extension);
    }

    return $filename;
}

function menuActive($routeName)
{

    $class = 'active';

    if (is_array($routeName)) {
        foreach ($routeName as $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}

function verificationCode($length)
{
    if ($length == 0) {
        return 0;
    }

    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

function gatewayImagePath()
{
    return 'asset/images/gateways';
}

function filePath($folder_name)
{
    return 'asset/images/' . $folder_name;
}

function frontendFormatter($key)
{
    return ucwords(str_replace('_', ' ', $key));
}

function getFile($folder_name, $filename)
{

    if (file_exists(filePath($folder_name) . '/' . $filename) && $filename != null) {

        return asset('asset/images/' . $folder_name . '/' . $filename);
    }

    return asset('asset/images/placeholder.png');
}

function variableReplacer($code, $value, $template)
{
    return str_replace($code, $value, $template);
}

function sendGeneralMail($data)
{
    $general = GeneralSetting::first();



    if ($general->email_method == 'php') {

        $headers = "From: $general->sitename <$general->site_email> \r\n";
        $headers .= "Reply-To: $general->sitename <$general->site_email> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";


        @mail($data['email'], $data['subject'], $data['message'], $headers);
    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $general->email_config->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $general->email_config->smtp_username;
            $mail->Password = $general->email_config->smtp_password;
            if ($general->email_config->smtp_encryption == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port = $general->email_config->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($general->site_email, $general->sitename);
            $mail->addAddress($data['email'], $data['name']);
            $mail->addReplyTo($general->site_email, $general->sitename);
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body = $data['message'];
            $mail->send();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}

function sendMail($key, array $data, $user)
{

    $general = GeneralSetting::first();

    $template = EmailTemplate::where('name', $key)->first();

    $message = variableReplacer('{username}', $user->username, $template->template);
    $message = variableReplacer('{sent_from}', $general->sitename, $message);

    foreach ($data as $key => $value) {
        $message = variableReplacer("{" . $key . "}", $value, $message);
    }

    if ($general->email_method == 'php') {

        $headers = "From: $general->sitename <$general->site_email> \r\n";
        $headers .= "Reply-To: $general->sitename <$general->site_email> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";

        @mail($user->email, $template->subject, $message, $headers);
    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $general->email_config->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $general->email_config->smtp_username;
            $mail->Password = $general->email_config->smtp_password;
            if ($general->email_config->smtp_encryption == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port = $general->email_config->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($general->site_email, $general->sitename);
            $mail->addAddress($user->email, $user->username);
            $mail->addReplyTo($general->site_email, $general->sitename);
            $mail->isHTML(true);
            $mail->Subject = $template->subject;
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
function content($key)
{
    return Cache::remember("section_data_content_{$key}", now()->addMinutes(10), function () use ($key) {
        return SectionData::where('key', $key)->first();
    });
}

function element($key, $take = 10)
{
    return Cache::remember("section_data_element_{$key}_take_{$take}", now()->addMinutes(10), function () use ($key, $take) {
        return SectionData::where('key', $key)->take($take)->get();
    });
}

function clearcache()
{
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
}



function singleMenu($routeName)
{
    $class = 'active';

    if (request()->routeIs($routeName)) {
        return $class;
    }

    return '';
}

function arrayMenu($routeName)
{
    $class = 'd-block';
    if (is_array($routeName)) {
        foreach ($routeName as $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    }
}
function languageSelection($code, $action = "selected")
{
    // Use a static variable to avoid multiple queries for the default language.
    static $defaultLanguage = null;

    // Retrieve the default language short code once.
    if ($defaultLanguage === null) {
        $defaultLanguage = Language::where('is_default', 1)->value('short_code');
    }

    // Check the session for the current locale or fallback to the default.
    $currentLocale = session('locale', $defaultLanguage);

    return $currentLocale === $code ? $action : null;
}

function selectedLanguage()
{
    $default = Language::where('is_default', 1)->first();

    if (session()->has('locale')) {
        return Language::where('short_code', session('locale'))->first();
    } else {
        return $default;
    }
}

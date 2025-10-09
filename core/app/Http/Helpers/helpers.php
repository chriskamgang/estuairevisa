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

/**
 * Envoie un message WhatsApp via UltraMsg API
 *
 * @param string $phoneNumber Numéro de téléphone au format international (ex: +237676781795)
 * @param string $message Message à envoyer
 * @return array Résultat de l'envoi
 */
function sendWhatsApp($phoneNumber, $message)
{
    // Récupérer la configuration depuis les paramètres généraux
    $settings = \App\Models\GeneralSetting::first();

    // Vérifier si les notifications WhatsApp sont activées
    if (!$settings || !$settings->whatsapp_notification) {
        return ['success' => false, 'error' => 'WhatsApp notifications disabled'];
    }

    $instanceId = $settings->ultramsg_instance_id;
    $token = $settings->ultramsg_token;

    // Vérifier que les identifiants sont configurés
    if (!$instanceId || !$token) {
        \Illuminate\Support\Facades\Log::error('Configuration UltraMsg manquante');
        return ['success' => false, 'error' => 'UltraMsg not configured'];
    }

    try {
        $response = \Illuminate\Support\Facades\Http::asForm()
            ->post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                'token' => $token,
                'to' => $phoneNumber,
                'body' => $message,
                'priority' => 10
            ]);

        if ($response->successful()) {
            \Illuminate\Support\Facades\Log::info('WhatsApp envoyé', [
                'phone' => $phoneNumber,
                'response' => $response->json()
            ]);
            return ['success' => true, 'data' => $response->json()];
        } else {
            \Illuminate\Support\Facades\Log::error('Erreur envoi WhatsApp', [
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return ['success' => false, 'error' => $response->body()];
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Exception WhatsApp', [
            'phone' => $phoneNumber,
            'error' => $e->getMessage()
        ]);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Send Firebase Cloud Messaging (FCM) push notification using V1 API
 *
 * @param mixed $user User object or user ID
 * @param string $title Notification title
 * @param string $body Notification body/message
 * @param array $data Additional data to send with notification
 * @param string|null $url URL to open when notification is clicked
 * @return array
 */
function sendFCMNotification($user, $title, $body, $data = [], $url = null)
{
    try {
        // Get user object if ID is provided
        if (is_numeric($user)) {
            $user = \App\Models\User::find($user);
        }

        // Check if user exists and has FCM token
        if (!$user || !$user->fcm_token || !$user->push_notification_enabled) {
            \Illuminate\Support\Facades\Log::info('FCM notification skipped', [
                'user_id' => $user->id ?? null,
                'reason' => !$user ? 'User not found' : (!$user->fcm_token ? 'No FCM token' : 'Notifications disabled')
            ]);
            return ['success' => false, 'error' => 'User not configured for push notifications'];
        }

        // Get credentials file path
        $credentialsPath = config('firebase.credentials');

        if (!file_exists($credentialsPath)) {
            \Illuminate\Support\Facades\Log::error('Firebase credentials file not found', ['path' => $credentialsPath]);
            return ['success' => false, 'error' => 'Firebase credentials not configured'];
        }

        // Load service account credentials
        $credentials = json_decode(file_get_contents($credentialsPath), true);

        // Get OAuth 2.0 access token
        $accessToken = getFirebaseAccessToken($credentials);

        if (!$accessToken) {
            \Illuminate\Support\Facades\Log::error('Failed to get Firebase access token');
            return ['success' => false, 'error' => 'Failed to authenticate with Firebase'];
        }

        // Prepare FCM endpoint
        $clickUrl = $url ?? url('/');
        $projectId = config('firebase.project_id');

        // Prepare notification payload (FCM V1 format)
        $message = [
            'message' => [
                'token' => $user->fcm_token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'webpush' => [
                    'notification' => [
                        'icon' => url('/asset/images/logo/logo.png'),
                        'badge' => url('/asset/images/logo/icon.png'),
                        'tag' => 'estuairevisa-' . time(),
                    ],
                    'fcm_options' => [
                        'link' => $clickUrl
                    ]
                ],
                'data' => array_merge([
                    'url' => $clickUrl,
                    'timestamp' => now()->toIso8601String()
                ], $data),
            ]
        ];

        // Send FCM request using V1 API
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

        if ($response->successful()) {
            $result = $response->json();

            \Illuminate\Support\Facades\Log::info('FCM notification sent successfully', [
                'user_id' => $user->id,
                'title' => $title,
                'message_name' => $result['name'] ?? null
            ]);

            return [
                'success' => true,
                'message_name' => $result['name'] ?? null,
                'data' => $result
            ];
        } else {
            \Illuminate\Support\Facades\Log::error('FCM notification failed', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'error' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => $response->body()
            ];
        }

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Exception sending FCM notification', [
            'user_id' => $user->id ?? null,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Get Firebase OAuth 2.0 access token using service account
 *
 * @param array $credentials Service account credentials
 * @return string|null
 */
function getFirebaseAccessToken($credentials)
{
    try {
        // JWT Header
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        // JWT Payload
        $now = time();
        $payload = [
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600 // 1 hour expiry
        ];

        // Encode header and payload
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

        // Create signature
        $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;
        $privateKey = openssl_pkey_get_private($credentials['private_key']);
        openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $signatureInput . '.' . $base64UrlSignature;

        // Exchange JWT for access token
        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        if ($response->successful()) {
            $result = $response->json();
            return $result['access_token'] ?? null;
        }

        \Illuminate\Support\Facades\Log::error('Failed to get access token', [
            'status' => $response->status(),
            'error' => $response->body()
        ]);

        return null;

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Exception getting Firebase access token', [
            'error' => $e->getMessage()
        ]);
        return null;
    }
}

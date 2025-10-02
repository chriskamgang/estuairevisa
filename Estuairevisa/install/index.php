<?php
require_once 'lib/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softnir Installer</title>
    <link rel="stylesheet" href="src/style.css">
</head>

<body>
    <div class="nir-installer">
        <div class="nir-welcome-box">
            <div class="nir-welcome-box-left">
                <p class="theme-color">WELCOME TO</p>
                <h2 class="text-white h4 mb-18">Softnir Auto Installer</h2>

                <div class="info-block">
                    <p class="caption">PROJECT NAME</p>
                    <h3 class="title">VisaNir</h3>
                </div>
                <div class="info-block">
                    <p class="caption">VERSION</p>
                    <h3 class="title">1.0.0</h3>
                </div>
                <div class="info-block">
                    <p class="caption">AUTHOR</p>
                    <h3 class="title">Softnir</h3>
                </div>

                <div class="nir-welcome-box-left-bottom">
                    <p>
                        <span class="theme-color">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.25em" height="1.25em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M15 2.5V4c0 1.414 0 2.121.44 2.56C15.878 7 16.585 7 18 7h1.5"/><path d="M4 16V8c0-2.828 0-4.243.879-5.121C5.757 2 7.172 2 10 2h4.172c.408 0 .613 0 .797.076c.183.076.328.22.617.51l3.828 3.828c.29.29.434.434.51.618c.076.183.076.388.076.796V16c0 2.828 0 4.243-.879 5.121C18.243 22 16.828 22 14 22h-4c-2.828 0-4.243 0-5.121-.879C4 20.243 4 18.828 4 16m4-5h8m-8 3h8m-8 3h4.17"/></g></svg>
                        </span>
                        Please read the licence documentation before installation  <a href="https://codecanyon.net/licenses/faq" class="theme-color"> Read More</a>
                    </p>
                    <p class="mb-3">
                        <span class="theme-color">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.25em" height="1.25em" viewBox="0 0 24 24"><path fill="currentColor" d="M11.5 3C7.36 3 4 6.36 4 10.5S7.36 18 11.5 18H13v2.3c3.64-2.3 6-6.08 6-9.8C19 6.36 15.64 3 11.5 3m1 13.5h-2v-2h2zm0-3.5h-2c0-3.25 3-3 3-5c0-1.1-.9-2-2-2s-2 .9-2 2h-2c0-2.21 1.79-4 4-4s4 1.79 4 4c0 2.5-3 2.75-3 5" opacity="0.3"/><path fill="currentColor" d="M11.5 1C6.26 1 2 5.26 2 10.5c0 5.07 3.99 9.23 9 9.49v3.6l1.43-.69C17.56 20.43 21 15.45 21 10.5C21 5.26 16.74 1 11.5 1M13 20.3V18h-1.5C7.36 18 4 14.64 4 10.5S7.36 3 11.5 3S19 6.36 19 10.5c0 3.73-2.36 7.51-6 9.8m-2.5-5.8h2v2h-2zm1-10.5c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5c0-2.21-1.79-4-4-4"/></svg>
                        </span>
                        If you need any help with installation then contact with us <a href="mailto:info@softnir.com" class="theme-color">info@softnir.com</a>
                    </p>
                </div>

                <span class="nir-welcome-box-main-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 2048 2048"><path fill="currentColor" d="m2038 1488l-124 51q12 62 0 122l124 51l-49 119l-124-52q-17 25-38 47t-48 39l52 124l-119 49l-51-124q-30 6-61 6t-61-6l-51 124l-119-49l52-124q-51-35-86-86l-124 52l-49-119l124-51q-12-61 0-122l-124-51l49-119l124 52q35-51 86-86l-52-124l119-49l51 124q62-12 122 0l51-124l119 49l-52 124q25 17 47 38t39 48l124-52zm-365 289q37-15 63-42t41-62t14-72t-14-74q-14-36-41-63t-63-41q-35-15-73-15q-39 0-73 15q-36 14-63 41t-41 63q-31 73 0 146q14 36 41 63t63 41q73 31 146 0m375-1649v896h-128V640H128v1024h896v128H0V128zm-128 384V256H128v256zM896 1408v-140q-31-11-60-34l-121 69l-64-110l120-70q-3-16-3-35q0-18 3-35l-120-70l64-110l121 69q29-23 60-34V768h128v140q17 6 31 14t29 20l121-69l64 110l-120 70q3 17 3 35q0 19-3 35l120 70l-64 110l-121-69q-14 11-28 19t-32 15v140zm-32-320q0 40 28 68t68 28t68-28t28-68t-28-68t-68-28t-68 28t-28 68"/></svg>
                </span>
            </div>
            <div class="nir-welcome-box-right">
                <h2 class="top-title mb-4">Installation Process</h2>
                    <p>You can install yourself simply in 3 steps without any coding knowledge</p>
                <div class="process-box">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M22 13.5c0 1.76-1.3 3.22-3 3.46V20a2 2 0 0 1-2 2h-3.8v-.3a2.7 2.7 0 0 0-2.7-2.7c-1.5 0-2.7 1.21-2.7 2.7v.3H4a2 2 0 0 1-2-2v-3.8h.3C3.79 16.2 5 15 5 13.5s-1.21-2.7-2.7-2.7H2V7a2 2 0 0 1 2-2h3.04c.24-1.7 1.7-3 3.46-3s3.22 1.3 3.46 3H17a2 2 0 0 1 2 2v3.04c1.7.24 3 1.7 3 3.46M17 15h1.5a1.5 1.5 0 0 0 1.5-1.5a1.5 1.5 0 0 0-1.5-1.5H17V7h-5V5.5A1.5 1.5 0 0 0 10.5 4A1.5 1.5 0 0 0 9 5.5V7H4v2.12c1.76.68 3 2.38 3 4.38s-1.25 3.7-3 4.38V20h2.12a4.7 4.7 0 0 1 4.38-3c2 0 3.7 1.25 4.38 3H17z"/></svg>
                    </div>
                    <div class="content">
                        <h3 class="h6">Extensions</h3>
                        <p>You will see the server requirements, you need to fill the server requirements and
                             enable the extensions then click on the Next Step button.</p>
                    </div>
                </div><!-- process-box end -->

                <div class="process-box">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M11.5 21H7a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v.5"/><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0-2 0m-3-5V7a4 4 0 1 1 8 0v4m-1 8l2 2l4-4"/></g></svg>
                    </div>
                    <div class="content">
                        <h3 class="h6">Permissions</h3>
                        <p>Will show file permissions on your server.</p>
                    </div>
                </div><!-- process-box end -->

                <div class="process-box">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 14c-2.42 0-4.7-.6-6-1.55V9.64c1.47.83 3.61 1.36 6 1.36s4.53-.53 6-1.36v2.44c.33-.05.66-.08 1-.08s.67.03 1 .08V7c0-2.21-3.58-4-8-4S4 4.79 4 7v10c0 2.21 3.59 4 8 4h.29a7.4 7.4 0 0 1-.29-2c-3.87 0-6-1.5-6-2v-2.23c1.61.78 3.72 1.23 6 1.23c.24 0 .47 0 .7-.03c.4-.83.95-1.56 1.62-2.16c-.74.12-1.52.19-2.32.19m0-9c3.87 0 6 1.5 6 2s-2.13 2-6 2s-6-1.5-6-2s2.13-2 6-2m10.7 14.6v-1l1.1-.8c.1-.1.2-.2.1-.3l-1-1.7c0-.1-.2-.1-.3-.1l-1.2.5c-.3-.2-.6-.4-.9-.5l-.2-1.3c0-.1-.1-.2-.2-.2h-2c-.2 0-.3.1-.3.2l-.2 1.3c-.3.2-.5.3-.8.5l-1.2-.5c-.1 0-.2 0-.3.1l-1 1.7c0 .1 0 .2.1.3l1.1.8v1l-1.1.8c-.1.1-.2.2-.1.3l1 1.7c.1.1.2.1.3.1l1.2-.5c.2.2.5.4.8.5l.2 1.3c.1.1.2.2.3.2h2c.1 0 .2-.1.2-.2l.2-1.3c.3-.2.5-.3.8-.5l1.2.4c.1 0 .2 0 .3-.1l1-1.7c.1-.1.1-.2 0-.2zm-3.7.9c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5s1.5.7 1.5 1.5s-.7 1.5-1.5 1.5"/></svg>
                    </div>
                    <div class="content">
                        <h3 class="h6">Database Setup</h3>
                        <p>You will need to fill in the database information and click on the Install Now button.</p>
                    </div>
                </div><!-- process-box end -->

                <div class="text-end">
                    <a href="extension.php" class="btn">Accept & Next Step</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
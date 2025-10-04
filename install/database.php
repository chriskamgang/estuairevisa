<?php
require_once 'lib/config.php';
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


<?php


if ($_POST) {

    if(importDatabase($_POST)){
        if(updateAdminCredentials($_POST)){
            envUpdateAfterInstalltion($_POST);
            file_put_contents('installed', 'installed');
            
            message($_SERVER);
            
            if (!headers_sent()) {
                header('Location: finish.php');
                exit;
            } else {
                echo "<script>window.location.href = 'finish.php';</script>";
                echo '<noscript><meta http-equiv="refresh" content="0;url=finish.php"></noscript>';
                exit;
            }
        };
    };
}



?>

<body>
    <div class="nir-installer">
        <div class="nir-installer-box">
            <div class="nir-installer-box-header">
                <h2 class="text-white"> Database Setup</h2>
                <p>You will need to fill in the database information and click on the Install Now button.</p>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 14c-2.42 0-4.7-.6-6-1.55V9.64c1.47.83 3.61 1.36 6 1.36s4.53-.53 6-1.36v2.44c.33-.05.66-.08 1-.08s.67.03 1 .08V7c0-2.21-3.58-4-8-4S4 4.79 4 7v10c0 2.21 3.59 4 8 4h.29a7.4 7.4 0 0 1-.29-2c-3.87 0-6-1.5-6-2v-2.23c1.61.78 3.72 1.23 6 1.23c.24 0 .47 0 .7-.03c.4-.83.95-1.56 1.62-2.16c-.74.12-1.52.19-2.32.19m0-9c3.87 0 6 1.5 6 2s-2.13 2-6 2s-6-1.5-6-2s2.13-2 6-2m10.7 14.6v-1l1.1-.8c.1-.1.2-.2.1-.3l-1-1.7c0-.1-.2-.1-.3-.1l-1.2.5c-.3-.2-.6-.4-.9-.5l-.2-1.3c0-.1-.1-.2-.2-.2h-2c-.2 0-.3.1-.3.2l-.2 1.3c-.3.2-.5.3-.8.5l-1.2-.5c-.1 0-.2 0-.3.1l-1 1.7c0 .1 0 .2.1.3l1.1.8v1l-1.1.8c-.1.1-.2.2-.1.3l1 1.7c.1.1.2.1.3.1l1.2-.5c.2.2.5.4.8.5l.2 1.3c.1.1.2.2.3.2h2c.1 0 .2-.1.2-.2l.2-1.3c.3-.2.5-.3.8-.5l1.2.4c.1 0 .2 0 .3-.1l1-1.7c.1-.1.1-.2 0-.2zm-3.7.9c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5s1.5.7 1.5 1.5s-.7 1.5-1.5 1.5"/></svg>
                </span>
            </div>
            <div class="nir-installer-box-body">
                <div class="nir-installer-box-body-inner">
                    <div class="install-steps">
                        <div class="single-step done">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"/></svg>
                            </span>
                            <p>Extensions</p>
                        </div>
                        <div class="single-step done">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z"/></svg>
                            </span>
                            <p>Permissions</p>
                        </div>
                        <div class="single-step active">
                            <span>3</span>
                            <p>Database</p>
                        </div>
                    </div>

                    <form  action="" method="POST" class="database-form">
                        <div class="mb-3">
                            <label>Site Url</label>
                            <input type="text" name="url" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Database name </label>
                            <input type="text" class="form-control" name="db_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Database host </label>
                            <input type="text" class="form-control" name="db_host" required placeholder="Database Host without http or https">
                        </div>
                        
                        <div class="mb-3">
                            <label>Database User Name </label>
                            <input type="text" class="form-control" name="db_username" required>
                        </div>
                        
                        <div class="mb-8">
                            <label>Database Password </label>
                            <input type="text" class="form-control" name="db_pass" >
                        </div>

                        <h3 class="mb-3">Set Admin Credentials</h3>

                        <div class="mb-3">
                            <label>Username </label>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label>Password </label>
                            <input type="text" class="form-control" name="password" required>
                        </div>

                        <div class="mb-4">
                            <label>Email </label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        
                        <div class="bottom-btns">
                            <a href="permission.php" class="btn secondary-btn">Back</a>
                            <button type="submit" class="btn">Install Now</button>
                        </div>
                    </form>
                    

                </div>
            </div>
        </div>
    </div>

</body>
</html>
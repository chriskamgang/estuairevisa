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

<body>
    <div class="nir-installer">
        <div class="nir-installer-box">
            <div class="nir-installer-box-header">
                <h2 class="text-white"> File Permission</h2>
                <p>You need to fill the server file  permissions on your server then click on the Next Step button.</p>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none"><path fill="currentColor" fill-opacity="0.25" d="M5 5a2 2 0 0 1 2-2h4.75a.25.25 0 0 1 .25.25V8a2 2 0 0 0 2 2h4.75a.25.25 0 0 1 .25.25V19a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2z"/><path fill="currentColor" d="M13 8V3.604a.25.25 0 0 1 .427-.177l5.146 5.146a.25.25 0 0 1-.177.427H14a1 1 0 0 1-1-1"/><path stroke="currentColor" stroke-linecap="round" d="M8.5 13.5h6m-6 3h5"/></g></svg>
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
                        <div class="single-step active">
                            <span>2</span>
                            <p>Permissions</p>
                        </div>
                        <div class="single-step">
                            <span>3</span>
                            <p>Database</p>
                        </div>
                    </div>

                    
                    <ul class="list check-list">
                        <?php $isPermissionIsMissing = false; ?>
                        <?php foreach ($config['permissions'] as $permissions) { ?>
                            <li>
                                <span><?= $permissions ?> Required Permission: 0755</span>
                                <?php if (isFolderPermissionAvailable($permissions)) { ?>
                                    <i class="gg-check-o"> </i>

                                <?php } else { ?>
                                    <?php $isPermissionIsMissing = true; ?>
                                    <i class="gg-close-o"></i>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="bottom-btns">
                        <a href="extension.php" class="btn secondary-btn">Back</a>
                        <?php if (!$isPermissionIsMissing) { ?>
                            <a href="database.php" class="btn">Next Step</a>
                        <?php } else { ?>
                            <a href="#0" class="btn disbale-btn">Next Step</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
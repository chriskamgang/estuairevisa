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
                <h2 class="text-white"> Server Requirements</h2>
                <p> You need to fill the server requirements and enable the extensions then click on the Next Step button.</p>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 17c0-1.886 0-2.828.586-3.414S4.114 13 6 13h12c1.886 0 2.828 0 3.414.586S22 15.114 22 17s0 2.828-.586 3.414S19.886 21 18 21H6c-1.886 0-2.828 0-3.414-.586S2 18.886 2 17ZM2 6c0-1.886 0-2.828.586-3.414S4.114 2 6 2h12c1.886 0 2.828 0 3.414.586S22 4.114 22 6s0 2.828-.586 3.414S19.886 10 18 10H6c-1.886 0-2.828 0-3.414-.586S2 7.886 2 6Z" opacity="0.5"/><path stroke-linecap="round" d="M11 6h7M6 6h2m3 11h7M6 17h2"/></g></svg>
                </span>
            </div>
            <div class="nir-installer-box-body">
                <div class="nir-installer-box-body-inner">
                    <div class="install-steps">
                        <div class="single-step active">
                            <span>1</span>
                            <p>Extensions</p>
                        </div>
                        <div class="single-step">
                            <span>2</span>
                            <p>Permissions</p>
                        </div>
                        <div class="single-step">
                            <span>3</span>
                            <p>Database</p>
                        </div>
                    </div>

                    
                    <ul class="list check-list">
                        <?php if (phpVersionCheck()) { ?>
                            <li>
                                <span>Required <b>PHP version 8.2 or higher</b></span>
                                <i class="gg-check-o"></i>
                            </li>
                        <?php } else { ?>
                            <li>
                                <span>Required <b>PHP version 8.2 or higher</b></span>
                                <i class="gg-close-o"></i>
                            </li>
                        <?php } ?>
                        <?php $isExtestionIsDisabled = false; ?>
                        <?php foreach ($config['extensions'] as $extension) { ?>
                            <li>
                                <span>Required <b><?= strtoupper($extension) ?></b> PHP Extension</span>
                                <?php if (checkExtenstion($extension)) { ?>
                                    <i class="gg-check-o"> </i>

                                <?php } else { ?>
                                    <?php $isExtestionIsDisabled = true; ?>
                                    <i class="gg-close-o"></i>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="bottom-btns">
                        <a href="index.php" class="btn secondary-btn">Back</a>
                        <?php if (!$isExtestionIsDisabled) { ?>
                            <a href="permission.php" class="btn">Next Step</a>
                        <?php } else { ?>
                            <a href="javascript:void(0)" class="btn disbale-btn">Next Step</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
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

    <div class="nir-installer style-two">
        <div class="nir-thank-box">
            <div class="nir-thank-box-left">
                <i class="gg-check-o-big mb-5"></i>
                <h2 class="mb-2">VisaNir Successfully Installed</h2>
                <p class="mb-5">You can now access the VisaNir Website.</p>

                <?php
                    if (file_exists('./installed')) {
                        ?>
                            <a href="<?= getBaseURL() ?>" class="btn">Visit Home Page</a>
                        <?php
                    }
                ?>
            </div>
            <div class="nir-thank-box-right"></div>
        </div>
    </div>

</body>
</html>
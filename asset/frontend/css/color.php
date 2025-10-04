<?php

header("Content-Type:text/css");

$primary_color = '#' . $_GET['primary_color'];
?>

:root {
    --primary: <?php echo $primary_color; ?>;
}

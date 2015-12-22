<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
    <div class="centercontent tables">
        <?php
            include(VIEWPATH.'deploy/'.$this->router->method.'.php');
        ?>
    </div>
<?php
    include(VIEWPATH.'public/footer.php');
?>


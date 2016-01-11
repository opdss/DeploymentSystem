<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
    <div class="centercontent tables">
        <?php
            include(VIEWPATH.'deploy/'.(isset($tplVars['template']) ? $tplVars['template'] : $this->router->method).'.php');
        ?>
    </div>
<?php
    include(VIEWPATH.'public/footer.php');
?>


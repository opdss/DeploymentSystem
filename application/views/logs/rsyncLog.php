<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
    <div class="centercontent">
        <div id="contentwrapper" class="contentwrapper lineheight21">
            <p><?php echo nl2br($tplVars['logInfo']['rsyncLog'])?></p>
        </div>
    </div>
<?php
    include(VIEWPATH.'public/footer.php');
?>
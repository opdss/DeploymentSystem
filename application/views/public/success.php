<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
    <div class="centercontent">
        <div id="contentwrapper" class="contentwrapper lineheight21">
            <div class="notibar msgsuccess">
                <a class="close"></a>
                <p><?php echo empty($tplVars['message']) ? $message : $tplVars['message'];?></p>
            </div>
        </div>
    </div>
<?php
    include(VIEWPATH.'public/footer.php');
?>
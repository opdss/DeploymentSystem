<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
    <div class="centercontent">
        <?php
            include(VIEWPATH.'user/'.$this->router->method.'.php');
        ?>
    </div>
    <script type="text/javascript" src="<?php echo base_url('source/js/custom/elements.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/custom/list.js')?>"></script>
<?php
    include(VIEWPATH.'public/footer.php');
?>

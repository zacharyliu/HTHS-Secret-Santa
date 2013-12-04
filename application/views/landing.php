<?php
/**
 * multipurpose landing page
 */
?>
<style>
    html, body {
        height: 100%;
        padding-bottom: 0px;
    }
</style>
<link href="<?php echo base_url('/css/home.css') ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('/js/snowstorm-min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/js/home.js') ?>"></script>
<div class="sky">
<div class="home-container row">
    <div class="container" style="">

        <div class="landing-container">
            <div class="col-md-12">
                <div class="col-md-1">
                    <div class="landing-icon"><?php echo $icon ?></div>
                </div>
                <div class="col-md-11">
                    <div class="landing-header"><?php echo $header ?></div>
                    <div class="landing-subheader"><?php echo $subheader ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
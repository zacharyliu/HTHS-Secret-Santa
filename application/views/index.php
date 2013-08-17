<link href="/css/home.css" rel="stylesheet">
<script src="<?php echo base_url('/js/jquery.quovolver.js') ?>"></script>
    <script>
        $(document).ready(function () {

            $('blockquote').quovolver(500, 6000);

        });
    </script>
<div class="home-container">
<div class="row">
    <div class="logo-container"></div>
</div>
<div style="margin:0px 30px 0px 30px;">
    <div style="width:550px;height:100px;margin-top:20px;margin-bottom:75px;margin-left:auto;margin-right:auto;">
        <blockquote>
            <p>Kindness in words creates confidence. Kindness in thinking creates profoundness. Kindness in giving
                creates love."</p>
            <cite>&ndash; Lao Tzu</cite>
        </blockquote>
        <blockquote>
            <p>I have found that among its other benefits, giving liberates the soul of the giver."</p>
            <cite>&ndash; Maya Angelou</cite>
        </blockquote>
        <blockquote>
            <p>If you wait until you can do everything for everybody, instead of something for somebody, you'll end up
                not doing nothing for nobody."</p>
            <cite>&ndash; Malcom Bane</cite>
        </blockquote>
        <blockquote>
            <p>To know even one life has breathed easier because you have lived. This is to have succeeded."</p>
            <cite>&ndash; Ralph Waldo Emerson</cite>
        </blockquote>
        <blockquote>
            <p>The best way to find yourself is to lose yourself in the service of others."</p>
            <cite>&ndash; Ralph Waldo Emerson</cite>
        </blockquote>
        <blockquote>
            <p>The value of a man resides in what he gives and not in what he is capable of receiving."</p>
            <cite>&ndash; Albert Einstein</cite>
        </blockquote>
        <blockquote>
            <p>Make all you can, save all you can, give all you can."</p>
            <cite>&ndash; John Wesley</cite>
        </blockquote>
        <blockquote>
            <p>No person was ever honored for what he received. He was honored for what he gave."</p>
            <cite>&ndash; Calvin Coolidge</cite>
        </blockquote>
        <blockquote>
            <p>Sometimes a small thing you do can mean everything in another person's life."</p>
        </blockquote>
    </div>
    <p><b>Thanks to everyone for making the 2012 HTHS Secret Santa so successful! Please take the time to fill out this
            brief <a
                href="https://docs.google.com/a/ctemc.org/spreadsheet/viewform?formkey=dF9sRUlSUm1mdFdnV3VEWFE3YUhEeXc6MQ#gid=0">survey</a>,
            so that we know what to improve for next year!</b></p>
    <br/>

    <p>Welcome to the signup page for the 2012 HTHS Secret Santa gift exchange. To register,
        click <a href="<?php echo base_url('login') ?>"> here</a>. Exchange partners will be assigned
        randomly, and encrypted using the industry-standard ROT-26 encryption scheme (alternately known as the 2-ROT-13)
        for privacy. To avoid disappointment, please be sure that you are
        committed to giving a gift before signing up.</p>

    <br/>

    <p>Registration ends: <i> TBD</i></p>

    <p>Partner Assignments: <i> TBD </i></p>

    <p>Gift Exchange: <i> Friday, 12/20 </i></p>

    <h1 style="text-align:center;">Time Until Gift Exchange</h1>
    <div style="margin:0 auto 0 auto;"><?php echo $timer; ?></div>

    <div class="home-footer">

        <div class="row">
            <div class="col-md-12">
                <div class="section-header register-header">There are currently <?php $total = $this->datamod->countUsers(); echo $total ?> secret santas.</div>
                <div class="section-subheader register-subheader">You're one click away from making it <?php echo $total+1?>.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="login">
                    <button type="button" class="btn register-btn">Sign up for the secret santa.</button>
                </a>
            </div>
        </div>

    </div>
</div>
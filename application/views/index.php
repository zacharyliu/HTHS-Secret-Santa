<link href="/css/home.css" rel="stylesheet">
<script src="<?php echo base_url('/js/jquery.quovolver.js') ?>"></script>
<script type="text/javascript" src="/js/jquery.stellar.min.js"></script>
<script type="text/javascript" src="/js/snowstorm-min.js"></script>
<script>
    $(document).ready(function () {

        $('blockquote').quovolver(500, 6000);
        $.stellar();
        $(document).off('.carousel.data-api')//carousel collision with stellar plugin
    });
</script>
    <div class="home-container slide row" data-slide="1" data-stellar-background-ratio="0.5">

        <div style="margin:0px 30px 0px 30px;">
            <div class="subsection-icon" style="font-size:65px;line-height:65px;">&#xF06B;</div>
            <div class="section-header">Everybody likes gifts... right?</div>
            <div class="subsection-text">HTHS Secret Santa is a fully fledged, feature filled application that makes it
                easy to exchange
                gifts with your friends and enemies.
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="subsection-icon">&#xF004;</div>
                    <div class="subsection-header">Spread the love!</div>
                    <div class="subsection-text">Studies show that generosity can help reduce stress. Make a difference
                        in someone's day while
                        keeping the
                        holiday spirit alive.
                    </div>
                </div>
                <div class="col-md-4">
                    <div
                        style="">
                        <blockquote>
                            <p>Kindness in words creates confidence. Kindness in thinking creates profoundness. Kindness
                                in
                                giving
                                creates love."</p>
                            <small> Lao Tzu</small>
                        </blockquote>
                        <blockquote>
                            <p>I have found that among its other benefits, giving liberates the soul of the giver."</p>
                            <small> Maya Angelou</small>
                        </blockquote>
                        <blockquote>
                            <p>If you wait until you can do everything for everybody, instead of something for somebody,
                                you'll end
                                up
                                not doing nothing for nobody."</p>
                            <small> Malcom Bane</small>
                        </blockquote>
                        <blockquote>
                            <p>To know even one life has breathed easier because you have lived. This is to have
                                succeeded."</p>
                            <small> Ralph Waldo Emerson</small>
                        </blockquote>
                        <blockquote>
                            <p>The best way to find yourself is to lose yourself in the service of others."</p>
                            <small> Ralph Waldo Emerson</small>
                        </blockquote>
                        <blockquote>
                            <p>The value of a man resides in what he gives and not in what he is capable of
                                receiving."</p>
                            <small> Albert Einstein</small>
                        </blockquote>
                        <blockquote>
                            <p>Make all you can, save all you can, give all you can."</p>
                            <small> John Wesley</small>
                        </blockquote>
                        <blockquote>
                            <p>No person was ever honored for what he received. He was honored for what he gave."</p>
                            <small> Calvin Coolidge</small>
                        </blockquote>
                        <blockquote>
                            <p>Sometimes a small thing you do can mean everything in another person's life."</p>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="subsection-icon">&#xF066;</div>
                    <div class="subsection-header">Keep it small!</div>
                    <div class="subsection-text">Personalized, inexpensive gifts are preferred over super expensive
                        ones, so you won't have to break
                        out
                        your life savings.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="subsection-icon">&#xF0EB;</div>
                    <div class="subsection-header">Freedom to decide.</div>
                    <div class="subsection-text">Our unique group system enables you to join a group with the whole
                        school, or just with a few of
                        your
                        friends.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="subsection-icon">&#xF074;</div>
                    <div class="subsection-header">Flexibility first.</div>
                    <div class="subsection-text">Commit as much or as little as you would like. Join and leave groups at
                        your discretion until partners are assigned.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="subsection-icon">&#xF091;</div>
                    <div class="subsection-header">Everybody wins!</div>
                    <div class="subsection-text">Partners are assigned randomly, so you'll learn something new about
                        someone and make new friends, all while exchanging gifts.
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="subsection-icon">&#xF0C3;</div>
                    <div class="subsection-header">Built by High Tech, for High Tech.</div>
                    <div class="subsection-text">We've redesigned the look and feel with added functionality that will
                        make your Secret Santa
                        experience even more enjoyable.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="subsection-icon">&#xF017;</div>
                <div class="subsection-header">Important Dates are coming up.</div>
                <div class="subsection-text">
                    Registration ends: <i> TBD</i><br/>
                    Partner Assignments: <i> TBD </i><br/>
                    Gift Exchange: <i> Friday, 12/20 </i><br/>
                </div>
            </div>

        </div>
    </div>
    <div class="home-footer slide row" data-slide="2" data-stellar-background-ratio="0.5">

        <div class="row">
            <div class="col-md-12">
                <h1 style="text-align:center;color:#FFFFFF;">Time Until Gift Exchange</h1>

                <div style="margin:0 auto 0 auto;"><?php echo $timer; ?></div>
                <div class="section-header register-header">There are
                    currently <?php $total = $this->datamod->countUsers();
                    echo $total ?> secret santas.
                </div>
                <div class="section-subheader register-subheader">You're one click away from making
                    it <?php echo $total + 1 ?>.
                </div>
            </div>
        </div>
        <div class="row">
            <a href="/login"><button class="btn register-btn">Sign up for the secret santa.
            </button></a>
        </div>
    </div>

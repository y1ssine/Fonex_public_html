<?php
if ( have_posts() ):
    while ( have_posts() ):
        the_post();
        ?>

            <section class="kopa-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="upside-page-content">
                                <?php
                                    the_content();
                                    if ( comments_open() ) :
                                        comments_template();
                                    endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    <?php
    endwhile;
endif;


    <footer class="site-footer">

        <div class="footer-widgets clearfix">
            <?php if (is_active_sidebar('footer1')) { ?>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer1'); ?>
                </div>
            <?php } ?>

            <?php if (is_active_sidebar('footer2')) { ?>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer2'); ?>
                </div>
            <?php } ?>

            <?php if (is_active_sidebar('footer3')) { ?>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer3'); ?>
                </div>
            <?php } ?>

            <?php if (is_active_sidebar('footer4')) { ?>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer4'); ?>
                </div>
            <?php } ?>
        </div>

        <nav class="site-nav">
            <?php
                $args = array(
                    'theme_location' => 'footer'
                );
                wp_nav_menu();
            ?>
        </nav>

        <p><?php bloginfo('name');?> - &copy; <?php echo date('Y'); ?></p>
    </footer>
</div> <!-- div.container -->
<?php wp_footer(); ?>
</body>
</html>

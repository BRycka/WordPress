    <footer class="site-footer">

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

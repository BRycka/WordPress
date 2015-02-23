<div class="secondary-column">
    <?php if (function_exists('hello_world')) {
        hello_world(array('first' => 'tekstas', 'second' => 'iš kodo', 'forth' => 'test', 'fifth' => 'penktas'));
    }
    if (function_exists('mypoll_get_poll')) {
        echo mypoll_get_poll(array('id' => 1));
    } else {
//        echo "Function: mypoll_get_poll() not found...";
    }
    ?>
    <?php dynamic_sidebar('sidebar1'); ?>
</div>

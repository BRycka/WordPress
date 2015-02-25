<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 17/02/15
 * Time: 12:11
 */
/*
 * Plugin Name: Hello World by BRycka
 * Description: A simple Hello World plugin.
 * Version: 1.0.0
 * Author: Ričardas Baltulis
 * License: GPL2
 */

/*  2015  Ričardas Baltulis  (email : BRycka121@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//[helloworld]
function hello_world($atts)
{
    $hello = get_option('hello_global');
    $a = shortcode_atts(array(
        'first' => 'Hello',
        'second' => 'World',
        'third' => '!',
        'forth' => $hello,
        'fifth' => ''
    ), $atts);
    echo "Hello World! <br/>";
    echo "first: ".$a['first']."<br/>";
    echo "second: ".$a['second']."<br/>";
    echo "third: ".$a['third']."<br/>";
    echo "forth: ".$a['forth']."<br/>";
    if ($a['fifth']) {
        echo "fifth: ".$a['fifth']."<br/>";
    }
    echo $hello;
}

add_shortcode('helloworld', 'hello_world');

function admin_hello_options()
{
    echo '<h2>Hello World Plugin</h2>';
    if ($_REQUEST['submit']) {
        update_hello_options();
    }
    print_hello_form();
}

function update_hello_options()
{
    if ($_REQUEST['hello_global']) {
        update_option('hello_global', $_REQUEST['hello_global']);
    }
}

function print_hello_form()
{
    $default_greeting = get_option('hello_global');
    echo '<form method="post"><label for="hello_global">Greeting: <input type="text" name="hello_global" value="'.$default_greeting.'"/></label><br/><input type="submit" name="submit" value="Submit"/></form>';
}
function modify_wp_settings_menu()
{
    // parameters: page_title, menu_title, capability, menu_slug, function
    add_options_page('Hello World', 'Hello World', 'manage_options', __FILE__, 'admin_hello_options');
    // $page_title
    // (string) (required) The text to be displayed in the title tags of the page when the menu is selected
    // Default: None
    // $menu_title
    // (string) (required) The text to be used for the menu
    // Default: None
    // $capability
    // (string) (required) The capability required for this menu to be displayed to the user.
    // Default: None
    // $menu_slug
    // (string) (required) The slug name to refer to this menu by (should be unique for this menu).
    // Default: None
    // $function
    // (callback) (optional) The function to be called to output the content for this page.
    // Default: ' '

}

add_action('admin_menu', 'modify_wp_settings_menu');
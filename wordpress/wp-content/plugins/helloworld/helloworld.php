<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 17/02/15
 * Time: 12:11
 */
/*
 * Plugin Name: Hello World
 * Description: A simple Hello World plugin.
 * Version: 1.0
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
    $a = shortcode_atts(array(
        'first' => 'Hello',
        'second' => 'World',
        'third' => '!'
    ), $atts);
    echo "Hello World! <br/>";
    echo "first: ".$a['first']."<br/>";
    echo "second: ".$a['second']."<br/>";
    echo "third: ".$a['third']."<br/>";
}

add_shortcode('helloworld', 'hello_world');


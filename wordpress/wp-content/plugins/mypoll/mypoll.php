<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 19/02/15
 * Time: 15:37
 */

/*
 * Plugin Name: My Poll
 * Description: My Poll plugin
 * Version: 1.0
 * Author: Ričardas Baltulis
 * Author URI:
 * License: GPL2
 *
 * My Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * My Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with My Plugin. If not, see {URI to Plugin License}.
 */

/**
 * Activation code
 */
function mypoll_create_tables()
{
    global $wpdb;
    $questions_table_name = $wpdb->prefix."mypoll_questions";
    $answers_table_name = $wpdb->prefix."mypoll_answers";
    $votes_table_name = $wpdb->prefix."mypoll_votes";
    $voters_table_name = $wpdb->prefix."mypoll_voters";
    $charset_collate = $wpdb->get_charset_collate(); // Get correct charset

    $sql[0] = "CREATE TABLE $questions_table_name (
      id int(9) NOT NULL AUTO_INCREMENT,
      poll_name varchar(25) NOT NULL,
      question varchar(255) NOT NULL,
      PRIMARY KEY id (id)
    ) $charset_collate";

    $sql[1] = "CREATE TABLE $answers_table_name (
      id int(9) NOT NULL AUTO_INCREMENT,
      question_id int(9) NOT NULL,
      answer varchar(255) NOT NULL,
      PRIMARY KEY id (id),
      KEY question_id (question_id)
    ) $charset_collate";

    $sql[2] = "CREATE TABLE $votes_table_name (
      id int(9) NOT NULL AUTO_INCREMENT,
      question_id int(9) NOT NULL,
      answer_id int(9) NOT NULL,
      votes int(9) NOT NULL,
      PRIMARY KEY id (id),
      KEY question_id (question_id),
      KEY answer_id (answer_id)
    ) $charset_collate";

    $sql[3] = "CREATE TABLE $voters_table_name (
      question_id int(9) NOT NULL,
      answer_id int(9) NOT NULL,
      user_ip varchar(45) NOT NULL,
      KEY question_id (question_id),
      KEY answer_id (answer_id)
    ) $charset_collate";

    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    foreach ($sql as $query) {
        dbDelta($query);
    }
}

register_activation_hook(__FILE__, 'mypoll_create_tables');
/**
 * End of deactivation
 */

/**
 * Scripts
 */
function mypoll_scripts()
{
    wp_register_script('mypoll-js', plugin_dir_url(__FILE__).'js/script.js');
    wp_enqueue_script('mypoll-js');
    wp_register_style('mypoll-admin-css', plugin_dir_url(__FILE__).'css/mypoll_admin_style.css');
    wp_enqueue_style('mypoll-admin-css');
}

add_action('admin_enqueue_scripts', 'mypoll_scripts');

function mypoll_plugin_resources()
{
    wp_register_style('mypoll-css', plugin_dir_url(__FILE__).'css/mypoll_style.css');
    wp_enqueue_style('mypoll-css');
}

add_action('wp_enqueue_scripts', 'mypoll_plugin_resources');
/**
 * End of scripts
 */


/**
 * My Poll Select page + Parent menu item
 */
function mypoll_add_admin_menu_item()
{
    // Atts: $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position
    add_menu_page('My Poll', 'My Poll', 'manage_options', 'my-poll', 'mypoll_admin_poll_page', '', '23.56');
}

add_action('admin_menu', 'mypoll_add_admin_menu_item');

function mypoll_admin_poll_page()
{
    ?>
    <div class="wrap">
        <h2>Select Poll</h2>
        <?php $polls = mypoll_get_poll_list(); ?>
        <form method="post">
            <select name="mypoll_select_poll">
                <?php foreach ($polls as $poll) { ?>
                    <option value="<?php echo $poll->id; ?>" <?php if (isset($_POST['mypoll_select_poll']) && $_POST['mypoll_select_poll']==$poll->id) { ?> selected <?php } ?> >
                        <?php echo $poll->poll_name; ?>
                    </option>
                <?php } ?>
            </select>
            <input type="submit" name="mypoll_select_poll_submit" value="select"/>
        </form>
        <hr>
        <?php
        if (isset($_POST['mypoll_select_poll_submit'])) {
            $poll = mypoll_get_poll_by_id((int)$_POST['mypoll_select_poll']);
            ?>
            <form method="post">
                <table id="editPollTable">
                    <tr>
                        <td>Poll name</td>
                        <td><input type="text" name="mypoll_name" value="<?php echo $poll['name']; ?>"/></td>
                        <td><a href="#" onclick="mypoll_add_field_to_edit()">+Add answer</a>
                        <td><input type="submit" onclick="return confirm('All votes will be reset!')" name="mypoll_edit_poll" value="Save"/></td>
                        <td><input type="submit" onclick="return confirm('Warning! You will delete poll: <?php echo $poll['name']; ?>!')" name="mypoll_delete" value="Delete Poll"/></td>
                        <td>Short code: <input type="text" value="[mypoll_poll id=<?php echo $_POST['mypoll_select_poll']; ?>]" readonly/></td>
                        <td><input type="submit" name="mypoll_clear" onclick="return confirm('All IP\'s on this poll will be cleared!')" value="Clear IP's"/></td>
                    </tr>
                    <tr>
                        <td>Poll question</td>
                        <td><textarea name="mypoll_question"><?php echo $poll['question']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Answers:</td>
                    </tr>
                    <?php $answers_count=0; foreach ($poll['answers'] as $answer) { ?>
                        <tr>
                            <td></td>
                            <td>
                                <input
                                    type="text"
                                    name="<?php echo 'mypoll_answer'.++$answers_count; ?>"
                                    value="<?php echo $answer['answer']; ?>"
                                    <?php if ($answers_count == 1 || $answers_count == 2) {
                                        ?>required <?php
                                    } ?>
                                />
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <input type="hidden" name="mypoll_question_id" value="<?php echo $_POST['mypoll_select_poll']; ?>"/>
                <input type="hidden" id="hidden" name="mypoll_total_answers" value="<?php echo $answers_count; ?>"/>
            </form>
            <?php
        }
        ?>
    </div>
    <?php
}
/**
 * End of My Poll Select page + Parent menu item
 */

/**
 * Submenu items
 */
function mypoll_add_submenu_items()
{
    add_submenu_page('my-poll', 'Create Poll', 'Create', 'manage_options', 'create', 'mypoll_admin_create_page');
}
add_action('admin_menu', 'mypoll_add_submenu_items');

function mypoll_admin_create_page()
{
    ?>
    <div class="wrap">
        <h2>Create new Poll</h2>
        <form method="post">
            <table id="newPollTable">
                <tr>
                    <td>Poll name*</td>
                    <td><input type="text" name="mypoll_name" required/></td>
                    <td><a href="#" onclick="mypoll_add_field()">+Add answer</a></td>
                    <td><input type="submit" name="mypoll_create_submit" value="Create"/></td>
                </tr>
                <tr>
                    <td>Question*</td>
                    <td><textarea name="mypoll_question" required></textarea></td>
                </tr>
                <tr>
                    <td>Answer #1*</td>
                    <td><input type="text" name="mypoll_answer1" required/></td>
                </tr>
                <tr>
                    <td>Answer #2*</td>
                    <td><input type="text" name="mypoll_answer2" required/></td>
                </tr>
<!--                <tr>-->
<!--                    <td id="fieldLabel"></td>-->
<!--                    <td id="fieldInput"></td>-->
<!--                </tr>-->
            </table>
            <input type="hidden" name="mypoll_total_answers" id="hidden" value="2"/>
            <table>
                <tr>
                    <td colspan="2">
                        <span style="color: red; font-size: 14px;">*</span><span style="font-size: 12px; font-weight: bold;"> - required fields</span>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

/**
 * End of submenu items
 */


/**
 * @param $name
 * @param $question
 * @return string
 * Database code
 */

function mypoll_get_poll_list()
{
    global $wpdb;
    $table_names = array(
        'questions' => $wpdb->prefix."mypoll_questions",
        'answers' => $wpdb->prefix."mypoll_answers",
    );
    $poll = $wpdb->get_results("SELECT id, poll_name FROM {$table_names['questions']}");
    return $poll;
}

function mypoll_get_poll_by_id($id)
{
    global $wpdb;
    $table_names = array(
        'questions' => $wpdb->prefix."mypoll_questions",
        'answers' => $wpdb->prefix."mypoll_answers",
    );
    $question = $wpdb->get_row("SELECT id, poll_name, question FROM {$table_names['questions']} WHERE id = $id");
    $answers = $wpdb->get_results("SELECT id, answer From {$table_names['answers']} WHERE question_id = $id");
    $poll = array('question_id' => $question->id, 'name' => $question->poll_name, 'question' => $question->question, 'answers' => array());
    $i = 0;
    foreach ($answers as $answer) {
        $poll['answers'][$i]['answer'] = $answer->answer;
        $poll['answers'][$i]['answer_id'] = $answer->id;
        $i++;
    }
    return $poll;
}

if (isset($_POST['mypoll_create_submit']) || isset($_POST['mypoll_edit_poll'])) {
//    mypoll_insert_data();
    mypoll_create_poll_array();
}

if (isset($_POST['mypoll_answer'])) {
    mypoll_update_poll_votes();
}

if (isset($_POST['mypoll_clear'])) {
    mypoll_clear_ip();
}

if (isset($_POST['mypoll_delete'])) {
    mypoll_delete_poll();
}

function mypoll_delete_poll()
{
    global $wpdb;
    $voters_table_name = $wpdb->prefix."mypoll_voters";
    $votes_table_name = $wpdb->prefix."mypoll_votes";
    $questions_table_name = $wpdb->prefix."mypoll_questions";
    $answers_table_name = $wpdb->prefix."mypoll_answers";
    $wpdb->delete(
        $questions_table_name,
        array(
            'id' => $_POST['mypoll_question_id']
        )
    );
    $wpdb->delete(
        $answers_table_name,
        array(
            'question_id' => $_POST['mypoll_question_id']
        )
    );
    $wpdb->delete(
        $votes_table_name,
        array(
            'question_id' => $_POST['mypoll_question_id']
        )
    );
    $wpdb->delete(
        $voters_table_name,
        array(
            'question_id' => $_POST['mypoll_question_id']
        )
    );
}

function mypoll_clear_ip()
{
    global $wpdb;
    $voters_table_name = $wpdb->prefix."mypoll_voters";
    $wpdb->delete(
        $voters_table_name,
        array(
            'question_id' => $_POST['mypoll_question_id']
        )
    );
}

function mypoll_update_poll_votes()
{
    global $wpdb;
    $voters_table_name = $wpdb->prefix."mypoll_voters";
    $votes_table_name = $wpdb->prefix."mypoll_votes";
    $question_id = $_POST['mypoll_question_id'];
    $answer_id = $_POST['mypoll_answer'];
    $user_ip = $_POST['mypoll_user_ip'];
    if ($wpdb->get_row("SELECT * FROM $voters_table_name WHERE question_id = {$question_id} AND user_ip = '$user_ip'")) {
        return false;
    } else {
        $wpdb->insert(
            $voters_table_name,
            array(
                'question_id' => $question_id,
                'answer_id' => $answer_id,
                'user_ip' => $user_ip
            ),
            array(
                '%d',
                '%d',
                '%s'
            )
        );
        $votes = $wpdb->get_row("SELECT votes FROM $votes_table_name WHERE question_id = {$question_id} AND answer_id = {$answer_id}");
            $votes->votes++;
            $wpdb->update(
                $votes_table_name,
                array(
                    'votes' => $votes->votes,
                ),
                array(
                    'question_id' => $question_id,
                    'answer_id' => $answer_id
                )
            );
    }

}

function mypoll_create_poll_array()
{
    if (isset($_POST['mypoll_create_submit'])) {
        $poll = array('poll_name' => $_POST['mypoll_name'], 'question' => $_POST['mypoll_question'], 'answers' => array());
        for ($i = 1; $i<$_POST['mypoll_total_answers']+1; $i++) {
            $poll['answers'][$i]['answer'] = $_POST['mypoll_answer'.$i];
            $poll['answers'][$i]['answer_id'] = $_POST['mypoll_total_answers'];
        }
        mypoll_insert_data($poll);
    } elseif (isset($_POST['mypoll_edit_poll'])) {
        $poll = array('mypoll_question_id' => $_POST['mypoll_question_id'], 'poll_name' => $_POST['mypoll_name'], 'question' => $_POST['mypoll_question'], 'answers' => array());
        for ($i = 1; $i<$_POST['mypoll_total_answers']+1; $i++) {
            $poll['answers'][$i]['answer'] = $_POST['mypoll_answer'.$i];
            $poll['answers'][$i]['answer_id'] = $_POST['mypoll_total_answers'];
        }
        mypoll_update_data($poll);
    }
}

function mypoll_update_data($poll)
{
//    var_dump($poll);
//    die;
    if (is_array($poll) && !empty($poll)) {
        global $wpdb;
        $questions_table_name = $wpdb->prefix."mypoll_questions";
        $answers_table_name = $wpdb->prefix."mypoll_answers";
        $votes_table_name = $wpdb->prefix."mypoll_votes";
        $voters_table_name = $wpdb->prefix."mypoll_voters";
        $find_if_exist = $wpdb->get_results("SELECT * FROM $questions_table_name WHERE poll_name = '".$poll['poll_name']."'");
        $current_poll_name = $wpdb->get_row("SELECT poll_name FROM $questions_table_name WHERE id = '".$poll['mypoll_question_id']."'");
        if (count($find_if_exist) < 1 || $poll['poll_name'] == $current_poll_name->poll_name) {
            $wpdb->delete(
                $answers_table_name,
                array(
                    'question_id' => $poll['mypoll_question_id']
                )
            );
            $wpdb->delete(
                $votes_table_name,
                array(
                    'question_id' => $poll['mypoll_question_id']
                )
            );
            $wpdb->delete(
                $voters_table_name,
                array(
                    'question_id' => $poll['mypoll_question_id']
                )
            );
            $wpdb->update(
                $questions_table_name,
                array(
                    'poll_name' => $poll['poll_name'],
                    'question' => $poll['question']
                ),
                array(
                    'id' => $poll['mypoll_question_id']
                )
            );
            foreach ($poll['answers'] as $answer){
                if ($answer['answer'] != null) {
                    $wpdb->insert(
                        $answers_table_name,
                        array(
                            'question_id' => $poll['mypoll_question_id'],
                            'answer' => $answer['answer']
                        ),
                        array(
                            '%d',
                            '%s'
                        )
                    );
                    $wpdb->insert(
                        $votes_table_name,
                        array(
                            'question_id' => $poll['mypoll_question_id'],
                            'answer_id' =>$wpdb->insert_id,
                            'votes' => 0
                        )
                    );
                }
            }
        }
    }
}

function mypoll_insert_data($poll)
{
    if (is_array($poll) && !empty($poll)) {
        global $wpdb;
        $questions_table_name = $wpdb->prefix."mypoll_questions";
        $answers_table_name = $wpdb->prefix."mypoll_answers";
        $votes_table_name = $wpdb->prefix."mypoll_votes";
        $find_if_exist = $wpdb->get_results("SELECT * FROM $questions_table_name WHERE poll_name = '".$poll['poll_name']."'");
        if (count($find_if_exist) < 1) {
            $wpdb->insert(
                $questions_table_name,
                array(
                    'poll_name' => $poll['poll_name'],
                    'question' => $poll['question']
                ),
                array(
                    '%s',
                    '%s'
                )
            );

            $last_inserted_id = $wpdb->insert_id;

            foreach ($poll['answers'] as $answer) {
                if ($answer['answer'] != null) {
                    $wpdb->insert(
                        $answers_table_name,
                        array(
                            'question_id' => $last_inserted_id,
                            'answer' => $answer['answer']
                        ),
                        array(
                            '%d',
                            '%s'
                        )
                    );
                    $wpdb->insert(
                        $votes_table_name,
                        array(
                            'question_id' => $last_inserted_id,
                            'answer_id' => $wpdb->insert_id,
                            'votes' => 0
                        ),
                        array(
                            '%d',
                            '%d',
                            '%d'
                        )
                    );
                }
            }

//            wp_redirect('admin.php?page=my-poll');
            return true;
        } else {
            return "Poll already exist";
        }
    }
}

/**
 * End of database code
 */


/**
 * Short codes
 */

function mypoll_get_total_votes($id)
{
    global $wpdb;
    $votes_table_name = $wpdb->prefix."mypoll_votes";
    $votes = $wpdb->get_results("SELECT votes From $votes_table_name WHERE question_id = $id");
    $sum = 0;
    foreach ($votes as $vote) {
        $sum += $vote->votes;
    }
    return $sum;
}

function mypoll_get_max_votes_count($id)
{
    global $wpdb;
    $votes_table_name = $wpdb->prefix."mypoll_votes";
    $votes = $wpdb->get_results("SELECT votes From $votes_table_name WHERE question_id = $id");
    $i = 0;
    foreach ($votes as $vote) {
        $array[$i] = $vote->votes;
        $i++;
    }
    $max = max($array);
    return $max;
}

function mypoll_get_votes($id)
{
    global $wpdb;
    $votes_table_name = $wpdb->prefix."mypoll_votes";
    $votes = $wpdb->get_row("SELECT votes FROM $votes_table_name WHERE answer_id = $id");
    return $votes;
}

function mypoll_get_poll($atts)
{
    global $wpdb;
    $voters_table_name = $wpdb->prefix."mypoll_voters";
    $questions_table_name = $wpdb->prefix."mypoll_questions";
    $a = shortcode_atts(array(
        'id' => ''
    ), $atts);
    if ($a['id'] != '') {
        if ($wpdb->get_row("SELECT id FROM $questions_table_name WHERE id={$a['id']}")) {
            $poll = mypoll_get_poll_by_id($a['id']);
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $output = '';
            $question_id = $poll['question_id'];
            if (!$wpdb->get_row("SELECT * FROM $voters_table_name WHERE question_id = $question_id AND user_ip = '$ip'")) {
                $output .= '<div class="wrap">'
                    .$poll["name"].'<br/>'
                    .$poll["question"].'
                            <form method="post">
                                <ul>
                            ';
                foreach ($poll['answers'] as $answer) {
                    $output .= '<li><input type="radio" name="mypoll_answer" value="'.$answer['answer_id'].'"/>'.$answer['answer'].'</li>
                                    <input type="hidden" name="mypoll_question_id" value="'.$poll['question_id'].'"/>
                                    <input type="hidden" name="mypoll_user_ip" value="'.$ip.'"/>';
                }
                $output .= '</ul><input type="submit" value="Vote"/></form></div>';
            } else {
                $max_vote_of_question = mypoll_get_max_votes_count($poll['question_id']);
                $total_votes = mypoll_get_total_votes($poll['question_id']);
                $output .=
                    '<div class="wrap">
                        <table>
                            <tr><td colspan="3"><div class="mypoll_name">'.$poll["name"].'</div></td></tr>
                            <tr><td colspan="3"><div class="mypoll_question">'.$poll["question"].'</div></td></tr>';
                foreach ($poll['answers'] as $answer) {
                    $votes = mypoll_get_votes($answer["answer_id"])->votes;
                    $output .= '<tr>
                                    <td>'.$answer["answer"].'</td>
                                    <td style="width:100px;">
                                        <div style="height: 15px; background-color: red; width: '.$votes*100/$max_vote_of_question.'%;">
                                            <div class="votes">'.$votes.'</div>
                                        </div>
                                    </td>
                                    <td>'.round($votes*100/$total_votes, 2).' %</td>
                                </tr>';
                }
                $output .= '<tr>
                                <td colspan="2"></td>
                                <td style="font-size: 11px;">Total votes: '.$total_votes.'</td>
                            </tr>
                        </table>
                    </div>';
            }
            return $output;
        }
        return false;
    }
}

function mypoll_register_shortcodes()
{
    add_shortcode('mypoll_poll', 'mypoll_get_poll');
}

add_action('init', 'mypoll_register_shortcodes');
/**
 * End of short codes
 */

/**
 * Deactivation code
 */
function mypoll_drop_tables()
{
    global $wpdb;
    $table_names = array(
        'questions' => $wpdb->prefix."mypoll_questions",
        'answers' => $wpdb->prefix."mypoll_answers",
        'votes' => $wpdb->prefix."mypoll_votes",
        'voters' => $wpdb->prefix."mypoll_voters"
    );

    foreach ($table_names as $name) {
        $sql = "DROP TABLE $name";
        $wpdb->query($sql);
    }
}

register_deactivation_hook(__FILE__, 'mypoll_drop_tables');
/**
 * End of deactivation
 */

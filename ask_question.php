<?php
/*
Plugin Name: Ask Question
Version: 0.1
Author: 
Description: add ask question link to your posts
*/
$plugin_url=trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__)) );
add_action('wp_print_scripts', 'ADD_ScriptsAction');
function ADD_ScriptsAction()
{
  echo '<script type="text/javascript" src="'.get_option('siteurl').'/wp-includes/js/jquery/jquery.js"></script>';
  echo '<script type="text/javascript" src="'.get_option('siteurl').'/wp-includes/js/thickbox/thickbox.js"></script>';
  echo '<script type="text/javascript" src="'.get_option('siteurl').'/wp-includes/js/jquery/jquery.form.js"></script>';
 //echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/../../wp-includes/js/jquery/jquery.js"></script>';
 //echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/../../wp-includes/js/thickbox/thickbox.js"></script>';
 // echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/../../wp-includes/js/jquery/jquery.form.js"></script>';

}

$prefix = 'dbt_';
$saveddata = array();
$meta_box = array(
    'id' => 'my-meta-box',
    'title' => 'Ask a question',
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
         array(
            'name' => 'Checkbox',
            'id' => $prefix . 'checkbox',
            'type' => 'checkbox'
        )
    )
);
add_action('admin_menu', 'mytheme_add_box');

// Add meta box
function mytheme_add_box() {
    global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box','page', $meta_box['context'], $meta_box['priority']);
}
function mytheme_show_box() {
    global $meta_box, $post;
    foreach ($meta_box['fields'] as $field) {
    $meta = get_post_meta($post->ID, $field['id'], true);
    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />ask question';
    }
}
add_action('save_post', 'mytheme_save_data');
// Save data from meta box
function mytheme_save_data($post_id) {
    global $saveddata;
    global $meta_box;
    $options = array();
    if ( $the_post = wp_is_post_revision($post_id) )
        $post_id = $the_post;
     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
            $options[0] = $post_id;
            $options[1] = $new;
            update_option('askquestion'.$postid,$options);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
            $options[0] = $post_id;
            $options[1] = $new;
            update_option('askquestion'.$postid,$options);
        }
    }
}
add_action('wp_head', 'head_fun');
function head_fun($postid)
{
  echo '<link rel="stylesheet" href="'.get_option('siteurl').'/wp-includes/js/thickbox/thickbox.css" type="text/css" />';
  $options = get_option('askquestion'.$postid);
  if (is_single() || is_page()){
    if($options[1] == 'on'){
      add_filter('the_content','add_content');
      }
      else
      {
        remove_filter('the_content','add_content');
      }
      }
}
function add_content($content)
{
 global $post;
$ID = $post->post_author;
update_option('cpid',$ID);
update_option('cpostid',$post->ID);
 return $content. '<li id="login" style = "list-style:none"><a href = "'.WP_PLUGIN_URL.'/ask_question/ask_form.php?height=400&width=600&modal=false" class="thickbox" title = "Ask question to the writer" >Ask Question</a></li>';
}
function thickbox_image_paths() {

    wp_reset_query();
		$thickbox_path = get_option('siteurl') . '/wp-includes/js/thickbox/';
		echo "<script type=\"text/javascript\">\n";
		echo "	var tb_pathToImage = \"${thickbox_path}loadingAnimation.gif\";\n";
		echo "	var tb_closeImage = \"${thickbox_path}tb-close.png\";\n";
		echo "</script>\n";
}
add_action('wp_footer', 'thickbox_image_paths');



















































































































/*add_action('admin_menu', 'admin_menu');
function admin_menu()
{
// custom panel for edit post
add_meta_box( 'Ask Question', 'Ask Question', 'draw_panel', 'post', 'normal', 'high' );
// custom panel for edit page
//$id,$title,$callback,$type,$context,$priority,$args
add_meta_box( 'Ask Question', 'Ask Question','draw_panel', 'page', 'normal', 'high' );
}
function draw_panel()
{
  echo '<input type="checkbox" name="askquestion" id="askquestion"', $meta ? ' checked="checked"' : '', ' />ask question';
}
add_action('wp_print_scripts', 'ADD_ScriptsActions');
function ADD_ScriptsActions()
{
 echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/../../wp-includes/js/jquery/jquery.js"></script>';
 echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/../../wp-includes/js/thickbox/thickbox.js"></script>';
 echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/../../wp-includes/js/jquery/jquery.form.js"></script>';
 //echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/ask_question/ask_question.js"></script>';
}
$saveddata = array();
$flag = 0;
$postid = 0;
require_once(ABSPATH . WPINC . '/pluggable.php');
$current_user = wp_get_current_user();

function testPostId() {
        global $post;
        global $postid;
        $postid = $post->ID;
       
}
// post_id OK
add_action('admin_head', 'testPostId');


add_action('save_post', 'save_function',1,2);
function save_function($post_id,$post) {
  global $flag;
  global $saveddata;
  global $postid;

 if ( $the_post = wp_is_post_revision($post_id) )
	$post_id = $the_post;
  if ($flag == 0) {
		 $old = get_post_meta($post_id, 'askquestion', true);
        $new = $_POST['askquestion'];
        if ($new && $new != $old) {
            update_post_meta($post_id, 'askquestion', $new);
        }

}
$flag = 1;
}*/
?>
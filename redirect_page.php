<?php
include "../../../wp-load.php";
$postid = get_option('cpostid');
$redirect =get_option('siteurl').'/?p='.$postid;
echo get_option('siteurl').'/?p='.$postid;
?>
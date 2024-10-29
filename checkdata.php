<?php
include "../../../wp-load.php";
$useremail = $_GET['email'];
$case = $_GET['case'];
if($case == 'validate'){
if ( is_email( $useremail ) ) {
      echo 'email address is valid';
}
else{
  echo 'not valid';
}
}
elseif($case == 'send'){
  $name = $_GET['name'];
  $phone = $_GET['phone'];
  $message = $_GET['message'];
  $postid = get_option('cpostid');
  $post = get_post( $postid );
  $headers= "From: ".$name." ".$useremail."\n" ."Content-Type: text/html; charset=\"" .get_option('blog_charset') . "\"\n";
  $to = $_GET['too'];
  $user_info = get_userdata($to);
  $tom = $user_info->user_email;
  $sendingmessage = $name." sent you some questions regarding ".$post->post_title."<br />".$name." questions:  <br />".$message;
  $sent = wp_mail( $tom, 'Questions by '.$name.' regarding '.$post->post_title , $sendingmessage, $headers );
  if($sent){
    //echo 'your mail sent to '.$tom;
    echo 'sent';
  }
  else{
    echo 'failed to send';
    }
}
?>
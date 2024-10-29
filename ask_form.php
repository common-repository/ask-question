<?php
include "../../../wp-load.php";
$ID = get_option('cpid');
$postid = get_option('cpostid');
//$user_info = get_userdata($ID);
//$to = $user_info->user_email;
?>
<form name="askform" id="askform" method="post" action="<?php echo get_option('siteurl').'/wp-content/plugins/ask_question/checkdata.php'; ?>" >
  <br>
Name: <input type="text" name="name" size="30" maxlength="64" value="Enter your name here!" /><br /><br />
E-Mail: <input type="text" name="email" size="30" maxlength="64" value="Enter your e-mail here!" /><br><br />
Phone:<input type="text" name="phone" size="40" maxlength="74" value="Enter your phone number here!" /><br><br />
<p>message:</p><textarea id="area" title ="your questions" rows="5" cols="60" >Enter your question here</textarea><br><br />
<input type="hidden" id="to" name="to" value= <?php echo $ID; ?> />
<input  type="submit" value="Send"/><br><br />

</form>
<div class="error" style="color: blue"></div>
<script type="text/javascript" >
  jQuery(document).ready(function() {
    var options = {
        beforeSubmit:  validateForm,
        success : showResponse,
        error: showerror
    }
           jQuery('form#askform').ajaxForm(options);
});

function validateForm(formData, jqForm, options) {

     jQuery('div.error').hide();
    for (var i=0; i < formData.length; i++) {
        if (!formData[i].value) {
          jQuery('div.error').show().append("all fields must contain data");
            return false;
        }
    } 
    if(jQuery('#area').val()== ''){
      jQuery('div.error').empty();
      jQuery('div.error').hide();
      jQuery('div.error').show().append("all fields must contain data");
      return false;
    }
     var user_info;
     user_info= jQuery.ajax({ type: "GET", url: "wp-content/plugins/ask_question/checkdata.php?email="+formData[1].value+"&case=validate", async: false }).responseText;
  
    if(user_info == 'email address is valid'){
      user_info= jQuery.ajax({ type: "GET", url: "wp-content/plugins/ask_question/checkdata.php?email="+formData[1].value+"&case=send"+"&name="+formData[0].value+"&phone="+formData[2].value+"&too="+jQuery('form#askform :hidden').fieldValue()+"&message="+jQuery('#area').val(), async: false }).responseText;
      //alert(user_info);
      //+"&message="+formData[3].value
     if(user_info == 'sent'){
       return true;
    }
      else{
            jQuery('div.error').empty();
            jQuery('div.error').hide();
            jQuery('div.error').show().append("Failed to send E-Mail");
            return false;
      }

    }
    else  if(user_info == 'not valid'){
      jQuery('div.error').empty();
      jQuery('div.error').show().append("Invalid E-Mail");
      return false;
    }
}
function showResponse(responseText, statusText){
  $location = jQuery.ajax({ type: "GET", url: "wp-content/plugins/ask_question/redirect_page.php", async: false }).responseText;
  window.location = $location ;
}
function showerror( xhr, ajaxOptions, thrownError){
                 alert(xhr.status+'----'+xhr.statusText);
                 alert(xhr.responseText);
                 alert(thrownError);
}
</script>

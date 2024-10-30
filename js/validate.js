// JavaScript Document

 jQuery(document).ready(function(){
	jQuery('#lead_form').submit(function(){
        
		var first_name = jQuery("#first_name").val();
		var email = jQuery("#email").val();
		var atpos=email.indexOf("@");
		var dotpos=email.lastIndexOf(".");
		
				
		if(first_name=="")
		{
			alert("Please enter your first name");
			jQuery("#last_name").focus();
			return false;
		}
		
		
		else if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
		  {
		  alert("Please enter a valid e-mail address");
		  return false;
		  }
		else{
			return true;
		}
    });
	});
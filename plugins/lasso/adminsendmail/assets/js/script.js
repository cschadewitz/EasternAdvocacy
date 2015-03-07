function saveAndSend()
{
	$(this).request('on',{
        data: {
			"name" : $('#name').val(),
    	    "zip" : $('#zip').val(),
            "type" : $('#type').val(),
            "email" : $('#email').val()
        },
        success: function(data){
            if(!emailError(data))
                $('form.subscriber_form').fadeSlideToggle();
        }
    }); return false;
}
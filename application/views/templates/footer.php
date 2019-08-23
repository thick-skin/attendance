</div>
<div style="height: 20vh;"></div>
<script>
	$(document).ready(function () {
  
  	$("button#login").click(function () {
 		btn = $(this).attr('name');
 		$("input#hiddenLogin").val(btn);
  	});
	
	$("form#login").submit(function(e) {
  	e.preventDefault();

  	var me = $(this);

        //perform ajax
        $.ajax({
          url: me.attr('action'),
          type: 'post',
          data: me.serialize(),
          dataType: 'json',
          success: function(response, data) {
            if (response.success == true) {
            	window.location.href = "<?php echo base_url(); ?>home";
            }else if(response.error === true) {
              console.log(response);
                   // error message and remove class
                   $('div#loginmessage').append('<p class="alert alert-danger">'+'Incorrect Details'+'</p>');
                   $('div#loginerr').removeClass('has-error')
                   .removeClass('has-success');
                   $('.text-danger').remove();

              // reset the form
              //me[0].reset();

              // close the message after seconds
              $('.alert-danger').delay(500).show(10, function() {
                $(this).delay(2000).hide(10, function() {
                  $(this).remove();
                });
              });
            }else{
              $.each(response.messages, function(key, value) {
                var element = $('input#' + key);
                element.closest('div#loginerr')
                .removeClass('has-error')
                .addClass(value.length > 0 ? 'has-error' : 'has-success')
                .find('.text-danger')
                .remove();
                element.after(value);
              });
            }
          }
        });
      });
	});
</script>
</body>
</html>
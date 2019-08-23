$(document).ready(function() {
$("#voter-login").submit(function(e) {
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
              var html = '';

              html += '<div class="well bg-info"><h3>Welcome!<br>'+
                      'Please note that you are now logged in.</h3>'+
                      '<strong>Read This:</strong>'+
                      '<p>Be sure of the candidates you choose before submitting.<br>'+
                      'You will not be able to login and vote again once you click the vote button.<br>'+
                      'Click the <b>Continue</b> button to continue or <br>'+
                      'Click the <b>Cancel</b> button to cancel and log out.</p>'+
                      '<a class="btn btn-sm btn-success" href="<?php echo base_url(); ?>vote/index">Continue</a> '+
                      '<a class="btn btn-sm btn-danger" href="<?php echo base_url(); ?>users/logoutVoter">Cancel</a>';
            
          $('div.modal-body').html(html);
           
            }else if(response.error === true) {
              console.log(response);
                   // error message and remove class
              $('#votermessage').append('<p class="alert alert-danger">'+'Incorrect RegNo/VoteKey!'+'</p>');
              $('div#vote-err').removeClass('has-error')
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
                var element = $('#' + key);
                element.closest('div#vote-err')
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
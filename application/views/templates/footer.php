</div>
<div style="height: 20vh;"></div>
<script>
	$(document).ready(function () {

		$('#flashdata').delay(500).show(10, function() {
      $(this).delay(2000).hide(10, function() {
        $(this).remove();
      });
    });
  
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

///////////////// CHANGE PASSWORD ////////////////////////////////////////////
function updatePassword(url, lecturerId) {
	$("form#changePwd").submit(function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);

        //perform ajax
        $.ajax({
          url: url,
          dataType: 'json',
          type: 'post',
          data : formData,
          contentType : false,
          processData : false,
          success: function(response) {
            if (response.status == true) {
              console.log(response);
              // success message and remove class
              $('div#the-message').append('<p class="alert alert-success">'+'Password updated'+'</p>');
              $('div#pwdErr').removeClass('has-error')
                       .removeClass('has-success');
              $('.text-danger').remove();

              // reset the form
              $("form#changePwd")[0].reset();

              // close the message after seconds
              $('p.alert-success').delay(500).show(10, function() {
              $(this).delay(3000).hide(10, function() {
              $(this).remove();
              $("#myGuide").modal('hide');
              });
            });
            }else{
              $.each(response.messages, function(key, value) {
                var element = $('#' + key);
                element.closest('div#pwdErr')
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
}

$('a.changePwd').click(function (e) {
      e.preventDefault();
      var url = $(this).attr('href');
      var lecturerId = $(this).attr('data');

      var head = '';
        head += '<div>'+
                '<h4>Change password</h4>'+
                '<div id="the-message"></div>'+
                '<form id="changePwd" action="'+url+'" method="POST">'+
                '<div id="pwdErr" class="form-group">'+
                '<input type="password" class="form-control" name="oldpwd" id="oldpwd" placeholder="Old password" autofocus="autofocus">'+
                '</div>'+
                '<div id="pwdErr" class="form-group">'+
                '<input type="password" class="form-control" name="newpwd" id="newpwd" placeholder="New password">'+
                '</div>'+
                '<div id="pwdErr" class="form-group">'+
                '<input type="password" class="form-control" name="confirmnewpwd" id="confirmnewpwd" placeholder="Confirm new password">'+
                '</div>'+
                '<button type="submit" class="btn btn-primary">Save</button>'+
                '</form>'+
                '</div>';
      $("#myGuide").modal('show');
      $("#myGuide").find('.modal-body').html(head); 

      updatePassword(url, lecturerId);
   });
///////////////// PASSWORD END //////////////////////////////////////////////

///////////////// RESTART ATTENDANCE/////////////////////////////////////////
$('a#restart').click(function (e) {
      e.preventDefault();
       var url = $(this).attr('href');
      // var lecturerId = $(this).attr('data');

      var head = '';
        head += '<div>'+
                '<h3 class="text-info">Going ahead with this operation will remove all attendance data in the database. To continue, follow the instructions below.</h3>'+
                '<div>'+
                '<p>To clear all attendance details excluding student details write \"RESET\" and click the Reset button.'+
                '<p>To clear all attendance details including student details write \"RESETALL\" and click the Reset All button.'+
                '</div>'+
                '<form id="changePwd" action="'+url+'" method="POST">'+
                '<div class="form-group">'+
                '<input type="text" class="form-control" name="reset" id="reset" placeholder="What do you want to do?">'+
                '</div>'+
                '<button data-dismiss="modal" class="btn btn-success" style="margin-right: 10px;">Cancel</button>'+
                '<button type="submit" class="btn btn-warning" style="margin-right: 10px;">Reset</button>'+
                '<button type="submit" class="btn btn-danger">Reset All</button>'+
                '</form>'+
                '</div>';
      $("#myGuide").modal('show');
      $("#myGuide").find('.modal-body').html(head); 

      //updatePassword(url, lecturerId);
   });
//////////////// END RESTART //////////////////////////////////////////////

/////////////////	IF YOU WANT TO ADD LOCATION///////////////////////////////

	// $('a.start').click(function (e) {
	// 	e.preventDefault();
	// 	$("div.bodycon").html('<i class="fa fa-circle-o-notch fa-spin" style="color:silver; display: block; position: fixed; z-index: 1031; top: 50%; right: 50%; margin-top: -..px; margin-right: -..px; font-size:50px;"></i>');

	// 	var me = $(this).attr('href');

	// 		if (navigator.geolocation) {
	// 			navigator.geolocation.getCurrentPosition(showPosition);
	// 		} else { 
	// 			x.innerHTML = "Geolocation is not supported by this browser.";
	// 		}

	// 	function showPosition(position) {
	// 		var latitude = position.coords.latitude;
	// 		var longitude = position.coords.longitude;

	// 		toggleAttendance(me, latitude, longitude);
	// 	}
	// });

	// $('a.end').click(function (e) {
	// 	e.preventDefault();

	// 	var me = $(this).attr('href');

	// 	var latitude = 0;
	// 	var longitude = 0;

	// 	toggleAttendance(me, latitude, longitude);
	// });

	// function toggleAttendance(me, latitude, longitude) {
   
 //      $.ajax({
 //        type: 'ajax',
 //        method: 'get',
 //        url: me,
 //        data: {latitude: latitude, longitude: longitude},
 //        async: false,
 //        dataType: 'json',
 //        success: function(response) {
 //            if (response.status == true) {
 //            	window.location.href = "<?php // echo base_url(); ?>users/timetable";
 //            }
 //                            },
 //                            error: function() {
 //                              alert('Could not get location!');
 //                            }
 //                          });
 //    }

 ///////////END OF LOCATION CODE////////////////////////////////////////////////
	});
</script>
</body>
</html>
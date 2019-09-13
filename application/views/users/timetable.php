<?php if ($this->session->flashdata('editSuccess')) {
      echo '<p class="alert alert-success" id="flashdata">'.$this->session->flashdata('editSuccess').'</p>';
    } ?>
<div style="text-align: center;">
  <h4>Semester Timetable</h4>
</div>
<div class="col-sm-offset-1 col-sm-10">
  <div class="table-responsive" style="text-align: center; border-radius: 0; box-shadow: 0 0 2px 1px grey; background-color: white;">
<table class="well table table-bordered" style="background-color: white;">
    <thead>
      <tr>
        <th>Ongoing</th>
        <th>Course</th>
        <th>Lecturer</th>
        <th>Date / Time</th>
        <th>Venue</th>
        <th>Info</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($courses as $course): ?>
      <tr>
        <td id="ongoing" style="color: lime;">
          <?php if ($course['ongoing'] != 0): ?>
            <i class="glyphicon glyphicon-bell"></i>
            <p>Yes</p>
            <!-- This makes sure that the otp only shows when the lecturer is logged in-->
            <?php if ($this->session->userdata('lecturer_name') == $course['lecturer_name']): ?>
              <h4 style="color: black;">OTP: <?php echo $course['otp']; ?></h4>
              <h6 id="noInClass" style="color: black;">Present: <b id="noInClass">0</b></h6>
            <?php endif; ?>
          <?php endif ?>
        </td>
        <td>
        <!-- add a disable class to course links that are not ongoing at the moment -->
          <a href="#" class="btn btn-block <?php if($this->session->userdata('lecturer_name') != $course['lecturer_name'] || !$this->session->userdata('otp_set')){echo "disabled";} ?>" id="log" data-toggle="modal" data-target="#myLog" data1="<?php echo $course['id']; ?>">
          <?php echo $course['course_title']; ?>
          <p><small><?php echo $course['course_code']; ?></small></p>
        </a>
        <?php if ($this->session->userdata('log_in')): ?>
          <a href="<?php echo base_url(); ?>users/editCourse/<?php echo $course['id']; ?>" id="edit-courses" title="Edit date/venue" data="<?php echo $course['course_code']; ?>" data1="<?php echo $course['date_time']; ?>" data2="<?php echo $course['venue']; ?>"><i class="text-info glyphicon glyphicon-edit"></i></a>
        <?php endif; ?>
        </td>
        <td><p><?php echo $course['lecturer_name']; ?></p></td>
        <td><p><?php echo $course['date_time']; ?></p></td>
        <td><p><?php echo $course['venue']; ?></p></td>
        <td class="text-danger"><p><?php echo $course['information']; ?></p></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
<div class="modal fade" id="myLog" role="dialog">
  <div class="modal-dialog" style="top: 30vh; z-index: 9999 !important;">

    <div class="modal-content">
      <div class="modal-header">
      <button style="color: red;" type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <div id="the-message"></div>
      <?php echo form_open('attendance/markAttendance', array("id" => "markAttendance")); ?>
          <div id="attErr" class="form-group">
            <input type="text" class="form-control" name="regno" id="regno" placeholder="Registration Number" autofocus="autofocus">
          </div>
          <div id="attErr" class="form-group">
            <input type="text" class="form-control" name="otp" id="otp" placeholder="One-Time password">
          </div>
          <input type="hidden" name="hiddenCourseId" id="hiddenCourseId">
          <button type="submit" class="btn btn-primary">Mark Attendance</button>
        </form>
      </div>
    </div>                
  </div>              
</div>

<script>
  $(document).ready(function () {
    var noInClass = 0;
// The lines below gets the id of the course clicked and saves it in the hidden input value
    $('a#log').click(function () {
      var data1 = $(this).attr('data1');
      $('#hiddenCourseId').val(data1);
    });
    
    //////////////////////////////STUDENT MARKS ATTENDANCE HERE////////////////////////////////////////
$("#markAttendance").submit(function(e) {
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
              console.log(response);
              // success message and remove class
      //I had problems here because i didn't add div to the #err and #the-message previously
              $('div#the-message').append('<p class="alert alert-success">'+'Attendance taken!'+'</p>');
              $('div#attErr').removeClass('has-error')
                       .removeClass('has-success');
              $('.text-danger').remove();

              // reset the form
              me[0].reset();

              // close the message after seconds
              $('.alert-success').delay(500).show(10, function() {
              $(this).delay(3000).hide(10, function() {
              $(this).remove();
              });
            });
              noInClass += 1;
              $('b#noInClass').remove();
              $('h6#noInClass').append('<b id="noInClass">'+noInClass+'</b>');
            }else if(response.error === true) {
              console.log(response);
                   // error message and remove class
                   $('div#the-message').append('<p class="alert alert-danger">'+'Attendance previously marked!'+'</p>');
                   $('div#attErr').removeClass('has-error')
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
                element.closest('div#attErr')
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
/////////////////////MARK ATTENDANCE ENDS HERE////////////////////////////

//////////////EDIT DATE TIME BEINS HERE/////////////////////
function updateCourse() {
  $("#editCourse").submit(function (e) {
    
    $("form#editCourse input").css('border-color', 'silver');
          
          $("form#editCourse input").each(function () {
            if($(this).val() == ''){
              e.preventDefault();
              $(this).css('border-color', '#cc0000');
            }
          }); 
              
      });
}

$('div.table-responsive').on('click', 'a#edit-courses', function (ev) {
      ev.preventDefault();
      var url = $(this).attr('href');
      var course_code = $(this).attr('data');
      var date = $(this).attr('data1');
      var venue = $(this).attr('data2');

      var head = '';
        head += '<div>'+
                '<h3>'+course_code+'</h3>'+
                '<form id="editCourse" action="'+url+'" method="POST">'+
                '<div id="editErr" class="form-group">'+
                '<input type="text" class="form-control" name="date" id="date" value="'+date+'">'+
                '</div>'+
                '<div id="editErr" class="form-group">'+
                '<input type="text" class="form-control" name="venue" id="venue" value="'+venue+'">'+
                '</div>'+
                '<button type="submit" class="btn btn-primary">Update</button>'+
                '</form>'+
                '</div>';
      $("#myLog").modal('show');
      $("#myLog").find('.modal-body').html(head); 

      updateCourse();     
      // $.ajax({
      //   type: 'ajax',
      //   method: 'get',
      //   url: '<?php //echo base_url(); ?>vote/voteDetails',
      //   data: {candidate: candidate},
      //   async: false,
      //   dataType: 'json',
      //   success: function(data){
      //     var html =  '<table id="deptVotes" class="table table-bordered table-responsive">'+
      //                 '<tr>'+
      //                 '<th>Department</th>'+
      //                 '<th>No. of votes</th>'+
      //                 '</tr>';
      //       var i;
      //       for (i = 0; i < data.length; i++) {
      //     html += '<tr>'+
      //             '<td>'+data[i].dept+'</td>'+
      //             '<td>'+data[i].total+'</td>'+
      //             '</tr>';
      //       }
      //       html += '</table>';

      // $("#voteDetails").find('.modal-body').html(html);
      //   },
      //   error: function () {
      //     alert('Could not get details');
      //   }
      // });

    });
///////////////EDIT ENDS HERE///////////////////////////////
  });
</script>
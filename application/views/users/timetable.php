<div style="text-align: center;">
  <h4>Semester Timetable</h4>
</div>
<div class="col-sm-offset-1 col-sm-10">
  <div class="table-responsive" style="text-align: center; border-radius: 0; box-shadow: 0 0 2px 1px grey; background-color: white;">
<table class="well table table-bordered" style="background-color: white;">
    <thead>
      <tr>
        <th>Ongoing</th>
        <th>Course(Click to mark attendance)</th>
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
            <?php if ($this->session->userdata('lecturer_in')): ?>
              <h6 id="noInClass" style="color: black;">Present: <b id="noInClass">0</b></h6>
            <?php endif; ?>
          <?php endif ?>
        </td>
        <td>
        <!-- add a disable class to course links that are not ongoing at the moment -->
          <a href="#" class="btn btn-block <?php if(!$this->session->userdata('lecturer_in') || $course['ongoing'] == 0){echo "disabled";} ?>" id="log" data-toggle="modal" data-target="#myLog" data1="<?php echo $course['id']; ?>">
          <?php echo $course['course_code']; ?>
          <p><small><?php echo $course['course_title']; ?></small></p>
        </a>
        </td>
        <td><p><?php echo $course['lecturer_name']; ?></p></td>
        <td><p><?php echo $course['date_time']; ?></p><p>Thursdays / 2:00pm</p></td>
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
      <div class="modal-body">
      <button style="color: red;" type="button" class="close" data-dismiss="modal">&times;</button>
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
  });
</script>
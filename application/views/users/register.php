<div class="row">
  <div class="col-sm-12">

    <ul class="nav nav-tabs nav-tabs-justified" style="font-weight: bolder;">
      <li class="active"><a data-toggle="tab" href="#regv">Register student</a></li>
      <li><a data-toggle="tab" href="#regc">Register lecturer</a></li>
    </ul>
    <div class="well tab-content" style="margin-top: 5vh; box-shadow: 2px 2px 2px grey;">
     <div id="regv" class="tab-pane fade in active">
      <h3>Register Student</h3><br><br>
      <div id="the-message"></div>
      <?php echo form_open('users/regStudent', array("id" => "studentform")); ?>
      <div id="err" class="form-group">
        <small id="Fullname" style="color:green;"></small>
        <input class="class form-control" type="text" name="fullname" id="fullname" placeholder="Fullname"><br>
      </div>

      <div id="err" class="form-group">
        <small id="RegNumber" style="color:green;"></small>
        <input class="class form-control" type="text" name="regno" id="regno" placeholder="RegNumber"><br>
      </div>

      <div id="err" class="form-group">
        <label class="radio-inline"><input type="radio" name="gender" id="gender" value="Male"><b>Male</b></label>
        <label class="radio-inline"><input type="radio" name="gender" id="gender" value="Female"><b>Female</b></label>
        <input type="hidden" id="hiddengender" name="hiddengender">
      </div>

      <button type="submit" class="btn btn-info">Submit</button>

      <?php echo form_close(); ?>
    </div>
    <div id="regc" class="tab-pane fade">
      <h3>Register Lecturer</h3><br><br>
     <div id="the-message"></div>
     <?php echo form_open('users/regLecturer', array("id" => "lecturerform")); ?>

     <div id="err" class="form-group">
      <small id="Lecturer-name" style="color:green;"></small>
      <input class="class form-control" type="text" name="lecturername" id="lecturername" placeholder="Lecturer-name"><br>
    </div>

    <div id="err" class="form-group">
      <select name="course" id="course" class="form-control">
        <option value="Select-Course">Select-Course</option>
        <?php foreach ($courses as $course):?>
          <?php if ($course['lecturer_name'] == 'No lecturer'): ?>
          <option id="<?php echo $course['course_code'] ?>" value="<?php echo $course['course_code'] ?>" data="<?php echo $course['course_code']; ?>"><?php echo $course['course_code'] ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>
  <input type="hidden" id="hidden" name="hidden">

    <div class="form-group row">
      <div id="err" class="col-xs-6">
        <small id="Password" style="color:green;"></small>
        <input class="class form-control" type="text" name="pword" id="pword" placeholder="Password">
      </div>
      <div id="err" class="col-xs-6">
        <small id="ConfirmPassword" style="color:green;"></small>
        <input class="class form-control" type="text" name="confirmpassword" id="confirmpassword" placeholder="ConfirmPassword">
      </div>
    </div>

    <button type="submit" class="btn btn-info">Submit</button>

    <?php echo form_close(); ?>
  </div>
</div>
</div>
</div>
<script>
  $(document).ready(function () {

    placeholderDisappearsOnFocus();

    function placeholderDisappearsOnFocus() {

      var place;

      $('input.class').focus(function () {
        place = $(this).attr('placeholder');

        $('small#'+place).wrap('<i> <b>');
        $('small#'+place).text(place);
        $(this).attr({placeholder:''});

      });
      $('input.class').blur(function () {
        place = place;
        $(this).attr({placeholder:place});
        $('small#'+place).text('');
      });

    }

    $('input#gender').click(function () {
        var gender = $(this).val();

        $('input#hiddengender').val(gender);
       });

    $('select#course').change(function () {
        var id = $(this).val();
        var course_id = $('option#'+id).attr('data');

        $('input#hidden').val(course_id);
       });

    $("#studentform, #lecturerform").submit(function(e) {
        e.preventDefault();

        var me = $(this);
        var formData = new FormData($(this)[0]);

        //perform ajax
        $.ajax({
          url: me.attr('action'),
          dataType: 'json',
          type: 'post',
          data : formData,
          contentType : false,
          processData : false,
          success: function(response) {
            if (response.status == true) {
              console.log(response);
              // success message and remove class
      //I had problems here because i didn't add div to the #err and #the-message previously
              $('div#the-message').append('<p class="alert alert-success">'+'Registered'+'</p>');
              $('div#err').removeClass('has-error')
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
            }else{
              $.each(response.messages, function(key, value) {
                var element = $('#' + key);
                element.closest('div#err')
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
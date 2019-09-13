<h2 class="text-primary"><?php echo $reg_number ?> ATTENDANCE RECORD</h2>
<div class="table-responsive">
<?php if ($courses && $atlogs): ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Course code</th>
        <th>Lecturer</th>
        <th>Times held</th>
        <th>Times attended</th>
        <th>Percentage</th>
        <th>Exam Qualified?</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($atlogs as $atlog): ?>
        <?php foreach ($courses as $course): ?>
          <?php if ($atlog['course_id'] == $course['id']): 
            $perc = round(($atlog['times_attended']/$course['times_held'])*100);
            ?>
            <tr>
              <td><?php echo $course['course_code']; ?></td>
              <td><p><?php echo $course['lecturer_name']; ?></p></td>
              <td><p><?php echo $course['times_held']; ?></p></td>
              <td><p><?php echo $atlog['times_attended']; ?></p></td>
              <td><p><?php echo $perc; ?>%</p></td>
              <td>
                <?php if ($perc >= 75): ?>
                  <i class="glyphicon glyphicon-check"></i>
                  <?php else: ?>
                    <i class="glyphicon glyphicon-unchecked"></i>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
      NO ATTENDANCE DETAILS
      <?php endif; ?>
    </div>
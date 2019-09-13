<div class="row">
	<div class="col-sm-8">
		<h3>Students Attendance Records For <?php echo $times_held ?> Classes Held</h3>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Reg No.</th>
						<th>Classes present</th>
						<th>Percentage</th>
						<th>Present Last Class?</th>
					</tr>
				</thead>
				<tbody class="bg-info">
					<?php if ($details): ?>
		<em class="text-info">(Note that this table only contains names of students that have attended the class at least once, arranged from the highest to the least attendance!!)</em>
					<?php foreach ($details as $detail): ?>
					<tr>
						<td>
						<?php foreach ($students as $student){
							if ($student['reg_number'] == $detail['student_id']){
								echo $student['fullname'];
							} 
						} ?>
						</td>
						<td><?php echo $detail['student_id']; ?></td>
						<td><?php echo $detail['times_attended']; ?></td>
						<td><?php echo round(($detail['times_attended']/$times_held)*100); ?>%</td>
						<td><?php if ($detail['date'] == $date_lastheld): ?>
							<i class="glyphicon glyphicon-check"></i>
							<?php else: ?>
							<i class="glyphicon glyphicon-unchecked"></i>
						<?php endif; ?></td>
					</tr>
					<?php endforeach; ?>
						<?php else: ?>
					<tr>
						<td>No attendance details</td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-4">
		<h3>Info on the course</h3>
		<?php echo form_open('users/updateInfo', array("id" => "formInfo")); ?>
			<div class="form-group">
				<textarea class="form-control" name="info" id="info" maxlength="150" size="150"><?php echo $information; ?></textarea>
				<div id="err"></div>
			</div>
			<button type="submit" class="btn btn-info">Post Info</button>
		</form>
		<script>
			$(document).ready(function () {
			$("#formInfo").submit(function (e) {
				$("textarea#info").css('border-color', 'silver');
				$("#infoErr").remove();
	          	if($("textarea#info").val() == ''){
					e.preventDefault();

					var formurl = $(this).attr('action');

	              	$("textarea#info").css('border-color', '#cc0000');
	          		$("#err").append('<small class="text-danger" id="infoErr">Write \"No info\" if there is none to be passed!</small>');
	           }
	          	
			});
		});
		</script>
	</div>
</div>
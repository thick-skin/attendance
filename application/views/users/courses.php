<div style="height:5vh;"><h3>Course upload</h3></div>
<?php echo form_open('users/courses', array("id" => "courseform")); ?>
<div class="table-responsive">
<table class="table table-bordered" id="applyList">
	<thead>
		<tr>
			<th>#</th>
			<th>Course code</th>
			<th>Course title</th>
			<th>Day / time</th>
			<th>Venue</th>
			<th>
				<button type="button" id="add" class="btn btn-primary">ADD</button>
			</th>
		</tr>
	</thead>
	<tbody>
	</tbody>

</table>
</div>

<div style="height: 10vh;">
  <button type="button" class="btn btn-danger" id="deleteall">DELETE ALL</button>
  <button type="submit" id="submit" class="btn btn-success">SUBMIT</button>
</div>
</form>
<script>
	function createRow(id) {
		var newrow = [
		id,
		'<div class="form-group">'+
            	'<input type="text" class="form-control code" name="code[]" placeholder="(In capital letters)">'+
        '</div>',
		'<div class="form-group">'+
            	'<input type="text" class="form-control title" name="title[]" placeholder="No abbreviations">'+
        '</div>',
        '<div class="form-group">'+
            	'<input type="text" class="form-control date" name="date[]" placeholder="Format(day / time)">'+
        '</div>',
        '<div class="form-group">'+
            	'<input type="text" class="form-control venue" name="venue[]" placeholder="Capital letters">'+
        '</div>',
		'<button type="button" class="addButton btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i></button>'+
		'<button type="button" class="deleteButton btn btn-xs btn-danger"><i class="glyphicon glyphicon-minus"></i></button>'
		];

		return '<tr><td>'+newrow.join('</td><td>')+'</td></tr>';
	}

	function renumberRows() {
		$('table#applyList tbody tr').each(function(index) {
			$(this).children('td:first').text(index+1);
		});
	}

	$('button#add').click(function() {
		var lastvalue = 1 + parseInt($('table#applyList tbody').children('tr:last').children('td:first').text());
		$('table#applyList tbody').append(createRow(lastvalue));
		renumberRows();
	});

	$('table#applyList').on('click','.addButton',function() {
		$(this).closest('tr').after(createRow(0));
		renumberRows();
	}).on('click','.deleteButton',function() {
		$(this).closest('tr').remove();
		renumberRows();
	});
	$('#deleteall').click(function() {
		$('#applyList tbody tr').remove();
	});

	$("#courseform").submit(function(e) {
        e.preventDefault();

        var me = $(this);

        var good2go;
        var countdata = 0;
        $(".code").each(function () {
          countdata = countdata + 1;
        });

        if (countdata > 0) {
          good2go = 'yes';
          $("table#applyList input").css('border-color', 'silver');
          
          $("table#applyList input").each(function () {
            if($(this).val() == ''){
              $(this).css('border-color', '#cc0000');
              good2go = 'no';
            }
          });
        }else{
          good2go = 'no';
          alert("Please add at least one field");
        }

        if (good2go == 'yes') {

         $.ajax({
            url: me.attr('action'),
            method: 'POST',
            data: me.serialize(),
            success: function(data) {
              $('#applyList tbody tr').remove();
              $('#applyList tbody').append('<div class="well" id="an_alert"><h2>Data inserted successfully</h2></div>');

              $('#an_alert').delay(1000).show(10, function() {
              $(this).delay(5000).hide(10, function() {
              $(this).remove();
              });
            });
             }
           });
        }
      });
</script>
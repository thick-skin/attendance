<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <input type="text" class="form-control" id="filterInput" placeholder="Search Reg. Number">
    </div>
    <div class="pre-scrollable">
      <div class="table-responsive" style="box-shadow: 2px 1px grey;">
        <table class="well pre-scrollable table table-striped table-hover table-condensed" style="background-color: white;">
          <thead>
            <tr>
              <th>Name</th>
              <th>Reg number</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody id="regno">
            <?php foreach ($students as $student): ?>
              <tr id="regnum">
                <td><p><?php echo $student['fullname']; ?></p></td>
                <td><b><?php echo $student['reg_number']; ?></b></td>
                <td><a id="studentid" href="#" data="<?php echo $student['id']; ?>" data2="<?php echo $student['reg_number']; ?>">View</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
  // Get input element
  let filterInput = document.querySelector('#filterInput');
  // Add event listener
  filterInput.addEventListener('keyup', filterNames);

  function filterNames() {
    // Get value of input
    let filterValue = document.querySelector("#filterInput").value;
    
    // Get names ul
    let ul = document.querySelector("#regno");
    // Get li from ul
    let li = ul.querySelectorAll("tr#regnum");
    // Loop through list-group-item
    for (var i = 0; i < li.length; i++) {
      let a = li[i].getElementsByTagName('b')[0];
      // If matched
      if (a.innerHTML.indexOf(filterValue) > -1) {
        li[i].style.display = '';
      } else {
        li[i].style.display = 'none';
      }
    }
  }
</script>
</div>
<div class="col-sm-8">
  <div class="well text-center" id="atlog" style="background-color: white; box-shadow: 0 0 2px 2px rgb(100,200,200);">
    DISPLAY DETAILS
  </div>
</div>
<script>
  $(document).ready(function () {

    $('a#studentid').click(function () {
      var value = $(this).attr('data');
      var regno = $(this).attr('data2');
      //alert(value);
      $("div#atlog").html('<i class="fa fa-circle-o-notch fa-spin" style="color:silver; font-size:50px;"></i>');
      $('div#atlog').load('<?php echo base_url(); ?>users/atlog/'+value+'/'+regno+'', {
        value: value,
        regno: regno
      });
    });
  });
</script>
</div>
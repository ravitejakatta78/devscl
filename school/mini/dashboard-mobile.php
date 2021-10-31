<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Schools';
$subtitle = 'Schools List';
$result = [];
$toggleaddbutton = 1;
$breadarrow = 1


?>

<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?= SCHOOL_NAME; ?> | <?= $pagetitle; ?></title>
        <?php require_once('../layout/headerscripts.php'); ?>

</head>

<body>
    <div id="wrapper">
        <?php require_once('../layout/sidebar.php'); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php require_once('../layout/header.php'); ?>
       
        <div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-sm-3 mb-3 " style="width: 33%;">
      <div class="p-4  border bg-light" style="width:100px;height: 120px;"><i class="fa fa-book fa-4x" aria-hidden="true"></i> Subjects</div>
    </div>
    <div class="col-sm-3 mb-3" style="width: 33%;">
      <div class="p-4  border bg-light" style="width:100px;height: 120px;"><i class="fa fa-group fa-4x" aria-hidden="true"></i> Staff</div>
    </div>
    <div class="col-sm-3 mb-3" style="width: 33%;">
      <div class="p-4  border bg-light" style="width:100px;height: 120px;"><i class="fas fa-user-graduate fa-4x" aria-hidden="true"></i> Students</div>
    </div>
    <div class="col-sm-3 mb-3" style="width: 33%;">
      <div class="p-4 border bg-light" style="width:100px;height: 120px;"><i class="fa fa-clock-o fa-4x" aria-hidden="true"></i> Attendance</div>
    </div>
    <div class="col-sm-3 mb-3" style="width: 33%;">
      <div class="p-4 border bg-light" style="width:100px;height: 120px;"><i class="fa fa-check fa-4x" aria-hidden="true"></i> Exams</div>
    </div>
    <div class="col-sm-3 mb-3" style="width: 33%;">
      <div class="p-4 border bg-light" style="width:100px;height: 120px;"><i class="fas fa-rupee-sign fa-4x"></i> Fees</div>
    </div>
    <div class="col-sm-3 mb-3" style="width: 33%;">
      <div class="p-4 border bg-light" style="width:100px;height: 120px;"><i class="fas fa-calendar-alt fa-4x" aria-hidden="true"></i> Schedules</div>
    </div>
    

    
  </div>
</div>

        <?php require_once('../layout/footer.php');?>
    </div>
    <!-- page wrapper -->

    </div>
    <!-- wrapper -->
 <?php require_once('../layout/footerscripts.php'); ?>





 




<script>

$("#addschoolsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addschoolform').find('input,select,select2,textarea').each(function(){
            if($(this).prop('required')){
                
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});

		if(err_value == 0)
		{
			$("#addschoolsubmitid").hide();
			$("#addschoolform").submit();
           	
		}
		
	});

    $(document).ready(function() {
    $("#updateschoolsubmitid").click(function(){

		var user_input_value;
		var err_value = 0
		$('#updateschoolform').find('input,select,textarea').each(function(){
			if($(this).prop('required')){
                user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});

		if(err_value == 0)
		{
			$("#updateschoolsubmitid").hide();
			$("#updateschoolform").submit();
           	
		}
		
	});
    });
    function editschoolpopup(school_id)
    {
        $.ajax({
            type: 'post',
            url: '../ajax/commonajax.php',
            data: {
                action: 'get-school-details',
                school_id: school_id
            },
            success: function(response) { 
                var res = JSON.parse(response);
                $("#update_school_name").val(res['school_name']);
                $("#update_email").val(res['email']);
                $("#update_address").val(res['address']);
                $("#update_landline").val(res['landline']);
                $("#update_mobile").val(res['mobile']);
                $("#update_registration_number").val(res['registration_number']);
                
            }
        });
        $("#updateModal").modal('show');
    }

</script>    

</body>
</html>

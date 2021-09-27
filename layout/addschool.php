<!DOCTYPE html>
<html>
<?php
require_once('../common.php');
$pagetitle = 'Schools';
$subtitle = 'Schools List';
$toggleaddbutton = 1;
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

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <?php require_once('../layout/header.php'); 
        require_once('../layout/breadcrumb.php'); ?>
        <div class="alert alert-success alert-dismissable" id="success-alert" style="display:none">
           <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            A wonderful serenity has taken possession. <a class="alert-link" href="#">Alert Link</a>.
        </div>  
        <?php require_once('../layout/footer.php');?>
    </div>
    <!-- page wrapper -->

    </div>
    <!-- wrapper -->
 <?php require_once('../layout/footerscripts.php'); ?>

 <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Add School</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="addschoolform" autocomplete="off">  
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="school_name" class="col-md-3">School Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="school_name" name="school_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="address" name="address" automcomplete="off" required/></textarea>
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "registration_number" class = "col-md-3 control-label">Registration Number :</label>
                    <div class = "col-md-9">
                        <input type="text" class="form-control" id="registration_number" name="registration_number" automcomplete="off" required/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="email" class="col-md-3">Email</label>
                    <div class="col-md-9">
                    <input type="email" class="form-control" id="email" name="email" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "mobile" class = "col-md-3 control-label">Phone Number :</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="mobile" name="mobile" automcomplete="off" />
                    </div>
                </div>            

                <div class = "form-group row">
                    <label for = "landline" class = "col-md-3 control-label">Landline :</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="landline" name="landline" automcomplete="off" />
                    </div>
                </div>
            </div>
        </div> 
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addschoolsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>
$("#addschoolsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addschoolform').find('input,select,select2').each(function(){
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

</script>    

</body>
</html>

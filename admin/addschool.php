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

/*school adding */
if(!empty($_POST['addsclsubmit']) &&  $_POST['randcheck'] == $_SESSION['rand'])
{
    $addarr['call'] = 'POST';
    $addarr['params'] = ['usersid' => $userid,'action' => 'add-school'
    ,'school_name' => $_POST['school_name']
    ,'registration_number' => $_POST['registration_number']
    ,'address' => $_POST['address']
    ,'email' => $_POST['email']
    ,'mobile' => $_POST['mobile']
    ,'landline' => $_POST['landline']
 ];
 $result = json_decode(apicall($addarr),true);
    
}

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
        <?php require_once('../layout/header.php'); 
        require_once('../layout/breadcrumb.php'); 
        $arr['call'] = 'GET';
        $arr['params'] = ['usersid' => $userid,'action' => 'get-school-list'];
        $school_list_json = apicall($arr);
        $school_list_arr = json_decode($school_list_json,true);
        $school_list = $school_list_arr['school_list'];
           ?>
        <div class="wrapper wrapper-content ">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-content">
                        <?php if(!empty($result)){
                            $notification_class = ($result['status'] == '1')  ? 'success' : 'danger'; 
                            ?> 
                            <div class="alert alert-<?= $notification_class; ?> alert-dismissable" id="success-alert">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?= $result['message']; ?>. 
                    </div>        
                            <?php  } ?>
                    
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    <tr>
                    <th scope="col" class="sort" data-sort="name">ID</th>
                    <th scope="col" class="sort" data-sort="name">School Name</th>
                    <th scope="col" class="sort" data-sort="budget">Address</th>
                    <th scope="col" class="sort" data-sort="status">Email</th>
                    <th scope="col" class="sort" data-sort="completion">Mobile Number</th>
                    <th scope="col" class="sort" data-sort="completion">Registration Number</th>
                    <th scope="col" class="sort" data-sort="completion">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                $schoolscount = count($school_list);
                for($i=0;$i < $schoolscount ; $i++ ){ ?> 
                <tr>
                    <td><?= ($i+1) ;?></td>
                    <td><?= $school_list[$i]['school_name'] ;?></td>
                    <td><?= $school_list[$i]['address'] ;?></td>
                    <td><?= $school_list[$i]['email'] ;?></td>
                    <td><?= $school_list[$i]['mobile'] ;?></td>
                    <td><?= $school_list[$i]['registration_number'] ;?></td>
                    <td class="icons"><a onclick="editschoolpopup('<?= $school_list[$i]['id'];?>')"><span class="fa fa-pencil"></span></a> 
                    	</td>
                </tr>
                <?php } ?>
                    </tbody>

                    </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
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
            <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
            ?>
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
                        <input type="hidden" class="form-control" id="addsclsubmit" name="addsclsubmit" value="<?= $addvalue ?? 1; ?>" />
                        <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
                    </div>
                </div>
            </div>
        </div> 
        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addschoolsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>



 <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Update School</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="updateschoolform" autocomplete="off">  
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="school_name" class="col-md-3">School Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_school_name" name="school_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="update_address" name="address" automcomplete="off" required/></textarea>
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "registration_number" class = "col-md-3 control-label">Registration Number :</label>
                    <div class = "col-md-9">
                        <input type="text" class="form-control" id="update_registration_number" name="registration_number" automcomplete="off" required/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="email" class="col-md-3">Email</label>
                    <div class="col-md-9">
                    <input type="email" class="form-control" id="update_email" name="email" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "mobile" class = "col-md-3 control-label">Phone Number :</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="update_mobile" name="mobile" automcomplete="off" />
                    </div>
                </div>            

                <div class = "form-group row">
                    <label for = "landline" class = "col-md-3 control-label">Landline :</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="update_landline" name="landline" automcomplete="off" />
                    <input type="hidden" class="form-control" id="updatesclsubmit" name="updatesclsubmit" value="1" />
                </div>
                </div>
            </div>
        </div> 
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateschoolsubmitid">Update</button>
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

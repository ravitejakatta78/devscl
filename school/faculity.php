<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Faculity';
$subtitle = 'Faculity List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');

if(!empty($_POST['addfacsubmit'])){
    $faculityadd = [];
    $faculityadd['faculity_name'] = $_POST['faculity_name'];
    $faculityadd['qualification'] = $_POST['qualification'];
    $faculityadd['subject_id'] = $_POST['subject_id'];
    $faculityadd['school_id'] = $school_id;
    $faculityadd['email'] = $_POST['email'];
    $faculityadd['mobile'] = $_POST['mobile'];
    $faculityadd['gender'] = $_POST['gender'];
    $faculityadd['address'] = $_POST['address'];
    $faculityadd['status'] = 1;
    $faculityadd['created_on'] = date('Y-m-d H:i:s');
    $faculityadd['updated_on'] = date('Y-m-d H:i:s');
    $faculityadd['created_by'] = $user_name;
    $faculityadd['updated_by'] = $user_name;

    $faculity_id = insertIDQuery($faculityadd,'faculity');

    if(!empty($faculity_id)){
        $result = ['status' => '1', 'message' => 'Added Successfully'];
    }
    else{
        $result = ['status' => '0', 'message' => 'Error While Adding Faculity'];
    }
}

$subject_list = runloopQuery("select * from subjects where school_id = '".$school_id."'");

$faculity_list = runloopQuery("select f.*,s.subject_name from faculity as f inner join subjects as s
on f.subject_id = s.id where f.school_id = '".$school_id."'");
//echo "<pre>";print_r($faculity_list);exit;

?>
<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>INSPINIA | Dashboard</title>
        <?php require_once('../layout/headerscripts.php'); ?>

</head>

<body>
    <div id="wrapper">
        <?php require_once('../layout/sidebar.php'); ?>
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <?php require_once('../layout/header.php'); 
            require_once('../layout/breadcrumb.php');
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
                                                <th scope="col" class="sort" data-sort="name">Faculity Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Qualification</th>
                                                <th scope="col" class="sort" data-sort="status">Email</th>
                                                <th scope="col" class="sort" data-sort="completion">Mobile Number</th>
                                                <th scope="col" class="sort" data-sort="completion">Subject</th>
                                                <th scope="col" class="sort" data-sort="completion">Gender</th>
                                                <th scope="col" class="sort" data-sort="completion">Address</th>
                                                <th scope="col" class="sort" data-sort="completion">update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($f=0;$f<count($faculity_list);$f++) { ?>
                                                <tr>
                                                    <td><?php echo $f+1; ?></td>
                                                    <td><?php echo $faculity_list[$f]['faculity_name']; ?></td>
                                                    <td><?php echo $faculity_list[$f]['qualification']; ?></td>   
                                                    <td><?php echo $faculity_list[$f]['email']; ?></td>
                                                    <td><?php echo $faculity_list[$f]['mobile']; ?></td>
                                                    <td><?php echo $faculity_list[$f]['subject_name']; ?></td>
                                                    <td><?php echo $faculity_list[$f]['gender']; ?></td>
                                                    <td><?php echo $faculity_list[$f]['address']; ?></td>
                                                    <td><button onclick="update_faculity('<?php echo $faculity_list[$f]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                        <i class="fa fa-pencil"></button></td>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Add Faculity</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <form method="post" action="" id="addfaculityform" autocomplete="off">  
            <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
            ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="faculity_name" class="col-md-3">Faculity Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="faculity_name" name="faculity_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="qualification" class="col-md-3">Qualification</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="qualification" name="qualification" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="address" name="address" automcomplete="off" required/></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subject_id" class="col-md-3">Subject</label>
                    <div class="col-md-9">
                        <select id="subject_id" name="subject_id">
                            <option value="">Select Subject</option>
                            <?php for($s=0;$s<count($subject_list);$s++) { ?>
                                <option value="<?php echo $subject_list[$s]['id']; ?>"><?php echo $subject_list[$s]['subject_name']; ?></option>
                            <?php } ?>
                        </select>
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
                    <label for = "mobile" class = "col-md-3 control-label">Mobile Number</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="mobile" name="mobile" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "gender" class = "col-md-3 control-label">Gender</label>
                    <div class = "col-md-9">
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                </div>                          
            </div>
        </div>
        <input type="hidden" id="addfacsubmit" name="addfacsubmit" value="1"/> 
        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addfaculitysubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div> <!--add faculity over-->

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Update Faculity</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <form method="post" action="" id="updatefaculityform" autocomplete="off">  
            <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
            ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="faculity_name" class="col-md-3">Faculity Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="updatefaculity_name" name="updatefaculity_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="qualification" class="col-md-3">Qualification</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_qualification" name="update_qualification" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="update_address" name="update_address" automcomplete="off" required/></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subject_id" class="col-md-3">Subject</label>
                    <div class="col-md-9">
                        <select id="update_subject_id" name="update_subject_id">
                            <option value="">Select Subject</option>
                            <?php for($s=0;$s<count($subject_list);$s++) { ?>
                                <option value="<?php echo $subject_list[$s]['id']; ?>"><?php echo $subject_list[$s]['subject_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="email" class="col-md-3">Email</label>
                    <div class="col-md-9">
                    <input type="email" class="form-control" id="update_email" name="update_email" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "mobile" class = "col-md-3 control-label">Mobile Number</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="update_mobile" name="update_mobile" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "gender" class = "col-md-3 control-label">Gender</label>
                    <div class = "col-md-9">
                        <select id="update_gender" name="update_gender">
                            <option value="">Select Gender</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                </div>                          
            </div>
        </div>
        <input type="hidden" id="update_facsubmit" name="update_facsubmit"/> 
        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatefaculitysubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
    $("#addfaculitysubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addfaculityform').find('input,select,select2').each(function(){
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
			$("#addfaculitysubmitid").hide();
			$("#addfaculityform").submit();
           	
		}
		
	});

    function update_faculity(faculiyid) {
        var action = 'faculity';
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data :{faculiyid:faculiyid,action:action},
        }).done(function(msg) {
            var faculity_list_array = JSON.parse(msg);
            $("#updatefaculity_name").val(faculity_list_array['faculity_name']);
            $("#update_qualification").val(faculity_list_array['qualification']);
            $("#update_address").val(faculity_list_array['address']);
            $("#update_subject_id").val(faculity_list_array['subject_id']);
            $("#update_email").val(faculity_list_array['email']);
            $("#update_mobile").val(faculity_list_array['mobile']);
            $("#update_gender").val(faculity_list_array['gender']);
        })
        $("#updateModal").modal('show');
        $("#update_facsubmit").val(faculiyid);
    }
    $("#updatefaculitysubmitid").click(function() {
        $("#updatefaculityform").submit();
    });
</script>
</body>
</html>

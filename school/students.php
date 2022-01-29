<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Students';
$subtitle = 'Students List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');
$classes = runloopQuery("select * from classes where school_id = '".$school_id."'");
$path = '../../school_docs/'.$school_id.'/student_image/';

//echo "<pre>";print_r($classes);exit;
if(!empty($_POST['addstudentsubmit'])) {
    $parentDetails = runQuery("select * from parents where phone = '".$_POST['phone']."'");
    if(!empty($parentDetails)){
        $parent_id = $parentDetails['id']; 
    }else{
        $parents['parent_name'] = $_POST['parent_name'];
        $parents['parent_type'] = $_POST['parent_type'];
        $parents['occupation'] = $_POST['occupation'];
        $parents['email'] = $_POST['email'];
        $parents['phone'] = $_POST['phone'];
        $parents['status'] = 1;
        $parents['created_by'] = $user_name;
        $parents['updated_by'] = $user_name;
        $parents['created_on'] = date('Y-m-d H:i:s');
        $parents['updated_on'] = date('Y-m-d H:i:s');
        $parents['reg_date'] = date('Y-m-d');
        $parent_id = insertIDQuery($parents,'parents');    
    }

    if(!empty($parent_id)) {
        $students['school_id'] = $school_id;
        $students['status'] = 1;
        $students['role_id'] = 4;
        $students['first_name'] = $_POST['first_name'];
        $students['last_name'] = $_POST['last_name'];
        $students['gender'] = $_POST['gender'];
        $students['dob'] = $_POST['dob'];
        $students['address'] = $_POST['address'];
        $students['roll_number'] = $_POST['roll_number'];
        $students['blood_group'] = $_POST['blood_group'];
        $students['religion'] = $_POST['religion'];
        $students['student_class'] = $_POST['student_class'];
        $students['admission_id'] = $_POST['admission_id'];
        $students['parent_id'] = $parent_id;
        if(!empty($_FILES['student_img']['name'])){
            $new_name = upload_student_pic($_FILES['student_img'],$path);
            $students['student_img'] = $new_name;   
        }
        $students['created_by'] = $user_name;
        $students['updated_by'] = $user_name;
        $students['created_on'] = date('Y-m-d H:i:s');
        $students['updated_on'] = date('Y-m-d H:i:s');
        $students['reg_date'] = date('Y-m-d');

        $student_id = insertIDQuery($students,'students');
        if(!empty($student_id)) {
            $result = ['status' => '1', 'message' => 'Added Student Successfully'];
        }
        else{
            $result = ['status' => '0', 'message' => 'Error While Adding'];   
        }
    }
}

if(!empty($_POST['updatestudentsubmit'])){
    $students_list = runQuery("select * from students where id = '".$_POST['updatestudentsubmit']."'");
    //echo "<pre>";print_r($students_list);exit;
    $updatestudents['school_id'] = $school_id;
    $updatestudents['status'] = 1;
    $updatestudents['role_id'] = 4;
    $updatestudents['first_name'] = $_POST['update_first_name'];
    $updatestudents['last_name'] = $_POST['update_last_name'];
    $updatestudents['gender'] = $_POST['update_gender'];
    $updatestudents['dob'] = $_POST['update_dob'];
    $updatestudents['address'] = $_POST['update_address'];
    $updatestudents['roll_number'] = $_POST['update_roll_number'];
    $updatestudents['blood_group'] = $_POST['update_blood_group'];
    $updatestudents['religion'] = $_POST['update_religion'];
    $updatestudents['student_class'] = $_POST['update_student_class'];
    $updatestudents['admission_id'] = $_POST['update_admission_id'];
    $updatestudents['updated_by'] = $user_name;
    $updatestudents['updated_on'] = date('Y-m-d H:i:s');
    $updatecondition['id'] = $_POST['updatestudentsubmit'];

    if(!empty($_FILES['update_student_img']['name'])){
        $new_name = upload_student_pic($_FILES['update_student_img'],$path);
        if(!empty($students_list['student_img'])){
            unlink($path.'/'.$students_list['student_img']);
        }
        $updatestudents['student_img'] = $new_name;   
    }
    updateQuery($updatestudents,'students',$updatecondition);
    
    if(!empty($_POST['update_parent_type'])){
        $updateparents['parent_name'] = $_POST['update_parent_name'];
        $updateparents['parent_type'] = $_POST['update_parent_type'];
        $updateparents['occupation'] = $_POST['update_occupation'];
        $updateparents['email'] = $_POST['update_email'];
        $updateparents['phone'] = $_POST['update_phone'];
        $updateparents['status'] = 1;
        $updateparents['updated_by'] = $user_name;
        $updateparents['updated_on'] = date('Y-m-d H:i:s');
        $updatedon['id'] = $students_list['parent_id'];
        updateQuery($updateparents,'parents',$updatedon);
    }
}

$student_details = runloopQuery("select s.*,p.phone,c.class_name from students as s inner join parents as p
on s.parent_id = p.id inner join classes as c on s.student_class = c.id where s.school_id = '".$school_id."'");
//echo "<pre>";print_r($student_details);exit;

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
                                                <th scope="col" class="sort" data-sort="name">Student Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Class</th>
                                                <th scope="col" class="sort" data-sort="completion">Mobile Number</th>
                                                <th scope="col" class="sort" data-sort="completion">Student Image</th>
                                                <th scope="col" class="sort" data-sort="completion">Update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($s=0;$s<count($student_details);$s++) { ?>
                                                <tr>
                                                    <td><?php echo $s+1; ?></td>
                                                    <td><?php echo $student_details[$s]['first_name']; ?> <?php echo $student_details[$s]['last_name']; ?></td>
                                                    <td><?php echo $student_details[$s]['class_name']; ?></td>
                                                    <td><?php echo $student_details[$s]['phone']; ?></td>
                                                    <td><img src="<?php echo $path.$student_details[$s]['student_img']; ?>" height="100" width="100">                                                 
                                                    </td>
                                                    <td><button onclick="update_student('<?php echo $student_details[$s]['id']; ?>')" type="button" class="btn btn-sm btn-white">
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
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Add Student</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <form method="post" action="" id="addstudentsform" autocomplete="off" enctype="multipart/form-data">  
            <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
            ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="first_name" class="col-md-3">First Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="first_name" name="first_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gender" class="col-md-3">Gender</label>
                    <div class="col-md-9">
                        <select class="form-control" id="gender" name="gender" automcomplete="off" required/>
                            <option value="">Select Gender</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="address" name="address" automcomplete="off" required/></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="blood_group" class="col-md-3">Blood Group</label>
                    <div class="col-md-9">
                        <select class="form-control" id="blood_group" name="blood_group" automcomplete="off" />
                            <option value="">Select Blood Group</option>
                            <option value="1">A+ve</option>
                            <option value="2">A-ve</option>
                            <option value="3">B+ve</option>
                            <option value="4">B-ve</option>
                            <option value="5">AB+ve</option>
                            <option value="6">O+ve</option>
                            <option value="7">O-ve</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="student_class" class="col-md-3">Student Class</label>
                    <div class="col-md-9">
                        <select class="form-control" id="student_class" name="student_class" automcomplete="off" required/>
                            <option value="">Select Class</option>
                            <?php for($c=0;$c<count($classes);$c++) { ?>
                                <option value="<?php echo $classes[$c]['id']; ?>"><?php echo $classes[$c]['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="parent_name" class="col-md-3">Parent Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="parent_name" name="parent_name" automcomplete="off" requried/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="occupation" class="col-md-3">Occupation</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="occupation" name="occupation" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "phone" class = "col-md-3 control-label">Phone Number</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="phone" name="phone" automcomplete="off" requried/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="last_name" class="col-md-3">Last Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="last_name" name="last_name" automcomplete="off" requried/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dob" class="col-md-3">DOB</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="dob" name="dob" automcomplete="off" requried/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="roll_number" class="col-md-3">Roll Number</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="roll_number" name="roll_number" automcomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="religion" class="col-md-3">Religion</label>
                    <div class="col-md-9">
                        <select class="form-control" id="religion" name="religion" automcomplete="off" requried/>
                            <option value="">Select Religion</option>
                            <option value="1">Hindu</option>
                            <option value="2">Christian</option>
                            <option value="3">Muslim</option>
                            <option value="4">Others</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="admission_id" class="col-md-3">Admission Id</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="admission_id" name="admission_id" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "student_img" class = "col-md-3 control-label">Student Image</label>
                    <div class = "col-md-9">
                        <input type="file" class="form-control" id="student_img" name="student_img" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "parent_type" class = "col-md-3 control-label">Parent Type</label>
                    <div class = "col-md-9">
                        <select class="form-control" id="parent_type" name="parent_type" automcomplete="off" required/>
                            <option value="">Select Parent Type</option>
                            <option value="1">Father</option>
                            <option value="2">Mother</option>
                            <option value="3">Gaurdian</option>
                        </select>
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "email" class = "col-md-3 control-label">Email</label>
                    <div class = "col-md-9">
                        <input type="email" class="form-control" id="email" name="email" automcomplete="off" />
                    </div>
                </div>
                 <input type="hidden" class="form-control" id="addsstudentubmit" name="addstudentsubmit" value="1" />
                    <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
            </div>
        </div> 
        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addstudentsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Update Student</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <form method="post" action="" id="updatestudentsform" autocomplete="off" enctype="multipart/form-data">  
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="update_first_name" class="col-md-3">First Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_first_name" name="update_first_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_gender" class="col-md-3">Gender</label>
                    <div class="col-md-9">
                        <select class="form-control" id="update_gender" name="update_gender" automcomplete="off" required/>
                            <option value="">Select Gender</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_address" class="col-md-3">Address</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="update_address" name="update_address" automcomplete="off" required/></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_blood_group" class="col-md-3">Blood Group</label>
                    <div class="col-md-9">
                        <select class="form-control" id="update_blood_group" name="update_blood_group" automcomplete="off" />
                            <option value="">Select Blood Group</option>
                            <option value="1">A+ve</option>
                            <option value="2">A-ve</option>
                            <option value="3">B+ve</option>
                            <option value="4">B-ve</option>
                            <option value="5">AB+ve</option>
                            <option value="6">O+ve</option>
                            <option value="27">O-ve</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_student_class" class="col-md-3">Student Class</label>
                    <div class="col-md-9">
                        <select class="form-control" id="update_student_class" name="update_student_class" automcomplete="off" required/>
                            <option value="">Select Class</option>
                            <?php for($c=0;$c<count($classes);$c++) { ?>
                                <option value="<?php echo $classes[$c]['id']; ?>"><?php echo $classes[$c]['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_parent_name" class="col-md-3">Parent Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_parent_name" name="update_parent_name" automcomplete="off" requried/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_occupation" class="col-md-3">Occupation</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_occupation" name="update_occupation" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "update_phone" class = "col-md-3 control-label">Phone Number</label>
                    <div class = "col-md-9">
                    <input type="text" class="form-control" id="update_phone" name="update_phone" automcomplete="off" requried/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="update_last_name" class="col-md-3">Last Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_last_name" name="update_last_name" automcomplete="off" requried/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_dob" class="col-md-3">DOB</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="update_dob" name="update_dob" automcomplete="off" requried/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_roll_number" class="col-md-3">Roll Number</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_roll_number" name="update_roll_number" automcomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_religion" class="col-md-3">Religion</label>
                    <div class="col-md-9">
                        <select class="form-control" id="update_religion" name="update_religion" automcomplete="off" requried/>
                            <option value="">Select Religion</option>
                            <option value="1">Hindu</option>
                            <option value="2">Christian</option>
                            <option value="3">Muslim</option>
                            <option value="4">Others</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_admission_id" class="col-md-3">Admission Id</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="update_admission_id" name="update_admission_id" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "update_student_img" class = "col-md-3 control-label">Student Image</label>
                    <div class = "col-md-9">
                        <input type="file" class="form-control" id="update_student_img" name="update_student_img" automcomplete="off" />
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "update_parent_type" class = "col-md-3 control-label">Parent Type</label>
                    <div class = "col-md-9">
                        <select class="form-control" id="update_parent_type" name="update_parent_type" automcomplete="off" required/>
                            <option value="">Select Parent Type</option>
                            <option value="1">Father</option>
                            <option value="2">Mother</option>
                            <option value="3">Gaurdian</option>
                        </select>
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "update_email" class = "col-md-3 control-label">Email</label>
                    <div class = "col-md-9">
                        <input type="email" class="form-control" id="update_email" name="update_email" automcomplete="off" />
                    </div>
                </div>
                 <input type="hidden" class="form-control" id="updatesstudentubmit" name="updatestudentsubmit" />
            </div>
        </div> 
        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatestudentsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#addstudentsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addstudentsform').find('input,select,select2').each(function(){
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
			$("#addstudentsubmitid").hide();
			$("#addstudentsform").submit();
           	
		}
		
	});

    function update_student(student_id) {
        var action = "updateStudent";
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data :{student_id:student_id,action:action},
        }).done(function(msg) {
            var students_array = JSON.parse(msg);  
            $("#update_first_name").val(students_array['first_name']);
            $("#update_last_name").val(students_array['last_name']);
            $("#update_gender").val(students_array['gender']);
            $("#update_dob").val(students_array['dob']);
            $("#update_address").val(students_array['address']);
            $("#update_roll_number").val(students_array['roll_number']);
            $("#update_blood_group").val(students_array['blood_group']);
            $("#update_religion").val(students_array['religion']);
            $("#update_student_class").val(students_array['student_class']);
            $("#update_admission_id").val(students_array['admission_id']);
            $("#update_parent_name").val(students_array['parent_name']);
            $("#update_occupation").val(students_array['occupation']);
            $("#update_parent_type").val(students_array['parent_type']);
            $("#update_phone").val(students_array['phone']);
            $("#update_email").val(students_array['email']);
        })
        $("#updatesstudentubmit").val(student_id);
        $("#updateModal").modal('show');
    }
    $("#updatestudentsubmitid").click(function() {
        $("#updatestudentsform").submit();
    });
</script>
</body>
</html>

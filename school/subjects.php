<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Subjects';
$subtitle = 'Subjects List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');

if(!empty($_POST['addsubjectsub'])) {
    $subjects = [];
    $subjects['school_id'] = $school_id;
    $subjects['subject_name'] = $_POST['subject_name'];
    $subjects['status'] = 1;
    $subjects['created_on'] = date('Y-m-d H:i:s A');
    $subjects['updated_on'] = date('Y-m-d H:i:s A');
    $subjects['created_by'] = date('Y-m-d H:i:s A');
    $subjects['created_by'] = $user_name;
    $subjects['updated_by'] = $user_name;

   $subject_id = insertIDQuery($subjects,'subjects');
   if(!empty($subject_id)) {
        $result = ['status' => '1', 'message' => 'Added Successfully'];
   }
   else{
    $result = ['status' => '0', 'message' => 'Error While Adding Subject'];
   }
   
}

if(!empty($_POST['updatesubjectsub'])) {
    $updatesubject = [];
    $updatecondition = [];
    $updatesubject['subject_name'] = $_POST['updatesubject_name'];
    $updatesubject['updated_on'] = date('Y-m-d H:i:s A');
    $updatesubject['updated_by'] = $user_name;
    $updatecondition['id'] = $_POST['updatesubjectsub'];
    $update = updateQuery($updatesubject,'subjects',$updatecondition); 
    
    $result = ['status' => '1', 'message' => 'Updated Successfully'];
    
}

$subject_list = runloopQuery("select * from subjects where school_id = '".$school_id."'");
//echo "<pre>";print_r($subject_list);exit;

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
                                                <th scope="col" class="sort" data-sort="name">Subject Name</th>
                                                <th scope="col" class="sort" data-sort="name">Update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($subject_list);$i++) { ?>
                                                <tr>
                                                    <td><?php echo $i+1; ?></td>
                                                    <td><?php echo $subject_list[$i]['subject_name']; ?></td>
                                                    <td><button onclick="updatesubject('<?php echo $subject_list[$i]['id']?>')" type="button" class="btn btn-sm btn-white"><i class="fa fa-pencil"></i></button></td>
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
            <h3 class="modal-title" id="exampleModalLabel">Add Subject</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="addsubjectform" autocomplete="off">  
        <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="subject_name" class="col-md-3">Subject Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="subject_name" name="subject_name" automcomplete="off" required/>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" id="addsubjectsub" name="addsubjectsub" value="1"/>
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addsubjectsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div> <!--add subject over-->

<!--update subject start-->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Update Subject</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="updatesubjectform" autocomplete="off">  
        <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="subject_name" class="col-md-3">Subject Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="updatesubject_name" name="updatesubject_name" automcomplete="off" required/>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" id="updatesubjectsub" name="updatesubjectsub" />
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatesubjectsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>

$("#addsubjectsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addsubjectform').find('input,select,select2').each(function(){
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
			$("#addsubjectsubmitid").hide();
			$("#addsubjectform").submit();
           	
		}
		
	});

    function updatesubject(subject_id){
        var action = 'subjectname';
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data : {subject_id:subject_id,action:action},
        }).done(function(msg) {
            //alert(msg);     
            var subjectlist_array = JSON.parse(msg);
            $("#updatesubject_name").val(subjectlist_array['subject_name']);
        });
        $("#updateModal").modal('show');
        $("#updatesubjectsub").val(subject_id);
    }

    $("#updatesubjectsubmitid").click(function(){
        $("#updatesubjectform").submit();
    });

</script>
</body>
</html>

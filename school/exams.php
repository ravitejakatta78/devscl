<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Exams';
$subtitle = 'Exams List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');
$classes = runloopQuery("select * from classes where school_id = '".$school_id."'");
$subjects = runloopQuery("select * from subjects where school_id = '".$school_id."'");
$subjects_list = array_column($subjects,'subject_name','id');
//echo "<pre>";print_r($subjects_list);exit;

if(!empty($_POST['addexamsub'])){
    $exams['exam_name'] = $_POST['exam_name'];
    $exams['exam_start_date'] = $_POST['exam_start_date'];
    $exams['exam_end_date'] = $_POST['exam_end_date'];
    $exams['class_id'] = $_POST['class_id'];
    $exams['school_id'] = $school_id;
    $exams['created_on'] = date('Y-m-d H:i:s');
    $exams['created_by'] = $user_name;
    $exams['updated_on'] = date('Y-m-d H:i:s');
    $exams['updated_by'] = $user_name;
    $exam_id = insertIDQuery($exams,'exams');

    if($exam_id) {
        addExams($_POST['exam_date'],$_POST['subject_id'],$exam_id,$school_id,$user_name);
    }

    if(!empty($exam_id)){
        $result = ['status' => '1', 'message' => 'Added Successfully'];   
    }
    else{
        $result = ['status' => '0', 'message' => 'Error While Adding'];
    }
}

if(!empty($_POST['subject_id'])){
    $add_exam = $_POST['add_exam'];
    addExams($_POST['exam_date'],$_POST['subject_id'],$add_exam,$school_id,$user_name);
}

function addExams($examdate,$subject,$examid,$school_id,$user_name) {
    if(!empty(array_filter($_POST['subject_id']))){
        for($e=0;$e<count($_POST['subject_id']);$e++){
            $exam_details['exam_date'] = $_POST['exam_date'][$e];
            $exam_details['subject_id'] = $_POST['subject_id'][$e];
            $exam_details['exam_id'] = $examid;
            $exam_details['created_on'] = date('Y-m-d H:i:s');
            $exam_details['created_by'] = $user_name;
            $exam_details['updated_on'] = date('Y-m-d H:i:s');
            $exam_details['updated_by'] = $user_name; 
            insertQuery($exam_details,'exam_details');
        }  
    }
}

$exam_table = runloopQuery("select e.*,c.class_name from exams as e inner join classes as c
on e.class_id = c.id where e.school_id = '".$school_id."'"); 
//echo "<pre>";print_r($exam_table);exit;
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
                                                <th scope="col" class="sort" data-sort="name">class Name</th>
                                                <th scope="col" class="sort" data-sort="name">Exam Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Starting Date</th>
                                                <th scope="col" class="sort" data-sort="status">Ending Date</th>
                                                <th scope="col" class="sort" data-sort="status">Add Exam</th>
                                                <th scope="col" class="sort" data-sort="status">Exams Details</th>
                                                <th scope="col" class="sort" data-sort="status">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($exam_table);$i++) { ?>
                                                <tr>
                                                    <td><?php echo $i+1; ?></td>
                                                    <td><?php echo $exam_table[$i]['class_name']; ?></td>
                                                    <td><?php echo $exam_table[$i]['exam_name']; ?></td>
                                                    <td><?php echo $exam_table[$i]['exam_start_date']; ?></td>
                                                    <td><?php echo $exam_table[$i]['exam_end_date']; ?></td>
                                                    <td><button onclick="add_subject('<?php echo $exam_table[$i]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                    <i class="fa fa-plus"></button></td>
                                                    <td><button onclick="display_exams('<?php echo $exam_table[$i]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                    <i class="fa fa-eye"></button></td>
                                                    <td><button onclick="delete_exams('<?php echo $exam_table[$i]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                    <i class="fa fa-trash"></button></td>
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
        <h3 class="modal-title" id="exampleModalLabel">Add Exam</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <form method="post" action="" id="examform" autocomplete="off">  
            <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
            ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="exam_name" class="col-md-3">Exam Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="exam_name" name="exam_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="exam_start_date" class="col-md-3">Exam Starting Date</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" id="exam_start_date" name="exam_start_date" automcomplete="off" required/>
                    </div>
                </div>
                <div class = "form-group row">
                    <label for = "exam_end_date" class = "col-md-3 control-label">Exam Ending Date</label>
                    <div class = "col-md-9">
                        <input type="date" class="form-control" id="exam_end_date" name="exam_end_date" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="class_id" class="col-md-3">Class</label>
                    <div class="col-md-9">
                        <select class="form-control" id="class_id" name="class_id" automcomplete="off" required/>
                            <option value="">Select Class</option>
                                <?php for($c=0;$c<count($classes);$c++) { ?>
                                    <option value="<?php echo $classes[$c]['id']; ?>"><?php echo $classes[$c]['class_name']; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            </div>   
        </div> 
        <table id="tblAddRow" class="table table-bordered table-striped mt-2">
						<thead>
							<tr>
							    <th>Exam date</th>
                                <th>Subject</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							    <td>
								<input type="date" name="exam_date[]" id="exam_date" class="form-control">
								</td>
                                <td>
                                    <select name="subject_id[]" id="subject_id" class="form-control">
                                    <option value="">Select Subject</option>
                                    <?php for($s=0;$s<count($subjects);$s++) { ?>
                                        <option value="<?php echo $subjects[$s]['id']; ?>"><?php echo $subjects[$s]['subject_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
							</tr>
						</tbody>
					</table>

					<div class="modal-footer">
							<button id="btnAddRow" class="btn btn-success" type="button" >Add Row</button>
					</div>
            <input type="hidden" id="addexamsub" name="addexamsub" value="1"/>
            <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addexamsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addexamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Add Exam</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <form method="post" action="" id="addexamform" autocomplete="off">   
        <table id="tblAddRowMore" class="table table-bordered table-striped mt-2">
						<thead>
							<tr>
							    <th>Exam date</th>
                                <th>Subject</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							    <td>
								<input type="date" name="exam_date[]" id="exam_date" class="form-control">
								</td>
                                <td>
                                    <select name="subject_id[]" id="subject_id" class="form-control">
                                    <option value="">Select Subject</option>
                                    <?php for($s=0;$s<count($subjects);$s++) { ?>
                                        <option value="<?php echo $subjects[$s]['id']; ?>"><?php echo $subjects[$s]['subject_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
							</tr>
						</tbody>
					</table>

					<div class="modal-footer">
							<button id="btnAddRowMore" class="btn btn-success" type="button" >Add Row</button>
					</div>
            <input type="hidden" id="addsubjectsub" name="addsubjectsub" value="2"/>
            <input type="hidden" id="add_exam" name="add_exam" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="add_examsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="displayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Display Exams</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <table  class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    <th>Exam</th>
                    <th>Exam Date</th>
                    <th>Subject Name</th>
                </tr>
            </thead>
            <tbody id="examsTable">
            </tbody>
        </table>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
    $("#addexamsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#examform').find('input,select,select2').each(function(){
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
			$("#addexamsubmitid").hide();
			$("#examform").submit();
           	
		}
		
	});
    $('#tblAddRow,#tblAddRowMore tbody tr').find('td').parent() 
    		.append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');


		// Add row the table
		$('#btnAddRow,#btnAddRowMore').on('click', function() {
		    var lastRow = $('#tblAddRow tbody tr:last').html();
		    //alert(lastRow);
		    $('#tblAddRow,#tblAddRowMore tbody').append('<tr>' + lastRow + '</tr>');
		    $('#tblAddRow,#tblAddRowMore tbody tr:last input').val('');
		});

        // Delete row on click in the table
		$('#tblAddRow').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRow tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});

        $('#tblAddRowMore').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRowMore tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});

        function add_subject(exm_id) {
            //alert(exm_id);
            $("#addexamModal").modal('show');
            $("#add_exam").val(exm_id);
        }
        $("#add_examsubmitid").click(function(){
            $("#addexamform").submit();
        });

        function display_exams(e_id) {
            var subjects_list_json = '<?php echo json_encode($subjects_list); ?>';
            var subjects_list = JSON.parse(subjects_list_json);
            var action = "exams";
            var request = $.ajax({
                url : "../ajax/commonajax.php",
                type : "POST",
                data : {e_id:e_id,action:action},
            }).done(function(msg){
                //alert(msg);
                var exams_list = JSON.parse(msg);
                $("#examsTable").html('');
                for(var i=0;i<exams_list.length;i++) {
                    $("#examsTable").append("<tr>\
                    <td>"+exams_list[i]['exam_name']+"</td>\
                    <td>"+exams_list[i]['exam_date']+"</td>\
                    <td>"+subjects_list[exams_list[i]['subject_id']]+"</td>\
                    </tr>");
                } 
            })
            $("#displayModal").modal('show');
        }

        function delete_exams(d_id) {
            var text = "Are You Sure To Delete";
            if (confirm(text) == true) {
                var action = "deleteExam";
                var request = $.ajax({
                    url : "../ajax/commonajax.php",
                    type : "POST",
                    data : {d_id:d_id,action:action},
                }).done(function(msg){
                    location.reload();
                }) 
            } else {
                text = "You canceled!";
            }
        }
</script>
</body>
</html>

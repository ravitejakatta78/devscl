<?php
session_start();

require_once('../common.php');

$userid = current_userid();
if(empty($userid)){
    header("Location: ../login.php");
}
$pagetitle = 'Classes';
$subtitle = 'Classes List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');

if(!empty($_POST['addclasssub'])) {

    $addclass['class_name'] = $_POST['class_name'];
    $addclass['teacher_id'] = $_POST['teacher_id'];
    $addclass['school_id'] = $school_id;
    $addclass['status'] = 1;
    $addclass['created_on'] = date('Y-m-d H:i:s');
    $addclass['updated_on'] = date('Y-m-d H:i:s');
    $addclass['updated_by'] = $user_name;
    $addclass['created_by'] = $user_name;

    $class_id = insertIDQuery($addclass,'classes');

    if($class_id) {
        addSections($_POST['section_name'],$_POST['faculity_id'],$class_id,$school_id,$user_name);
    }

    if(!empty($class_id)) {
        $result = ['status' => '1', 'message' => 'Added Successfully'];
    }
    else{
        $result = ['status' => '0', 'message' => 'Error While Adding'];
    }
}

if(!empty($_POST['addsectionsub'])) {
    $add_class_id = $_POST['add_class_id'];
    addSections($_POST['section_name'],$_POST['faculity_id'],$add_class_id,$school_id,$user_name);
}

function addSections($sectionname,$faculityArray,$cl_id,$school_id,$user_name){
    if(!empty(array_filter($sectionname))) {
        for($i=0;$i<count($sectionname);$i++) {
            $sections['section_name'] = $sectionname[$i];
            $sections['teacher_id'] = $faculityArray[$i];
            $sections['class_id'] = $cl_id;
            $sections['school_id'] = $school_id;
            $sections['section_status'] = 1;
            $sections['created_by'] = $user_name;
            $sections['created_on'] = date('Y-m-d H:i:s');
            $sections['updated_on'] = date('Y-m-d H:i:s');
            $sections['updated_by'] = $user_name;
            $result = insertQuery($sections,'class_sections');
        }
    }
}

if(!empty($_POST['updateclasssub'])) {
    $updateclass = [];
    $updatecondition = [];
    $updateclass['class_name'] = $_POST['updateclass_name'];
    $updateclass['teacher_id'] = $_POST['updateteacher_id'];
    $updateclass['school_id'] = $school_id;
    $updateclass['status'] = 1;
    $updateclass['updated_on'] = date('Y-m-d H:i:s');
    $updateclass['updated_by'] = $user_name;
    $updatecondition['id'] = $_POST['updateclasssub'];

    updateQuery($updateclass,'classes',$updatecondition);
    $result = ['status' => '1', 'message' => 'Updated Successfully'];
}

$faculity_details = runloopQuery("select * from faculity where school_id = '".$school_id."'");
//echo "<pre>";print_r($faculity_details);exit;
$faculity_list = array_column($faculity_details,'faculity_name','id');
//echo "<pre>";print_r($faculity_list);exit;
$classes_list = runloopQuery("select c.*,f.faculity_name from classes as c inner join faculity as f
on c.teacher_id = f.id where c.school_id = '".$school_id."'");
//echo "<pre>";print_r($classes_list);exit;
?>

<!DOCTYPE html>
<html>
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
                                                <th scope="col" class="sort" data-sort="name">Class Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Faculity Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Sections</th>
                                                <th scope="col" class="sort" data-sort="budget">Add Section</th>
                                                <th scope="col" class="sort" data-sort="budget">Update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($c=0;$c<count($classes_list);$c++) { ?>
                                                <tr>
                                                    <td><?php echo $c+1; ?></td>
                                                    <td><?php echo $classes_list[$c]['class_name']; ?></td>
                                                    <td><?php echo $classes_list[$c]['faculity_name']; ?></td>
                                                    <td><button onclick="display_sections('<?php echo $classes_list[$c]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                    <i class="fa fa-eye"></button></td>
                                                    <td><button onclick="add_sections('<?php echo $classes_list[$c]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                    <i class="fa fa-plus"></button></td>
                                                    <td><button onclick="update_class('<?php echo $classes_list[$c]['id']; ?>')" type="button" class="btn btn-sm btn-white">
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
            <h3 class="modal-title" id="exampleModalLabel">Add Class</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="addclassform" autocomplete="off">  
        <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="class_name" class="col-md-3">Class Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="class_name" name="class_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="class_name" class="col-md-3">Faculity Name</label>
                    <div class="col-md-9">
                        <select id="teacher_id" name="teacher_id"  class="form-control" required>
                            <option value="">Select Faculity</option>
                            <?php for($f=0;$f<count($faculity_details);$f++) { ?>
                                <option value="<?php echo $faculity_details[$f]['id']; ?>"><?php echo $faculity_details[$f]['faculity_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <table id="tblAddRow" class="table table-bordered table-striped mt-2">
						<thead>
							<tr>
							    <th>Section Name</th>
                                <th>Faculity</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							    <td>
								<input type="text" name="section_name[]" id="section_name" class="form-control">
								</td>
                                <td>
                                    <select name="faculity_id[]" id="faculity_id" class="form-control">
                                    <option value="">Select Faculity</option>
                                    <?php for($f=0;$f<count($faculity_details);$f++) { ?>
                                        <option value="<?php echo $faculity_details[$f]['id']; ?>"><?php echo $faculity_details[$f]['faculity_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
							</tr>
						</tbody>
					</table>

					<div class="modal-footer">
							<button id="btnAddRow" class="btn btn-success" type="button" >Add Row</button>
					</div>
            <input type="hidden" id="addclasssub" name="addclasssub" value="1"/>
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addclasssubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div> <!--add class over-->

<div class="modal fade" id="addsectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Sections</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="addsectionform" autocomplete="off">
                <table id="tblAddRowMore" class="table table-bordered table-striped mt-2">
						<thead>
							<tr>
							    <th>Section Name</th>
                                <th>Faculity</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							    <td>
								<input type="text" name="section_name[]" id="section_name" class="form-control">
								</td>
                                <td>
                                    <select name="faculity_id[]" id="faculity_id" class="form-control">
                                    <option value="">Select Faculity</option>
                                    <?php for($f=0;$f<count($faculity_details);$f++) { ?>
                                        <option value="<?php echo $faculity_details[$f]['id']; ?>"><?php echo $faculity_details[$f]['faculity_name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </td>
							</tr>
						</tbody>
			    </table>

					<div class="modal-footer">
							<button id="btnAddRowMore" class="btn btn-success" type="button" >Add Row</button>
					</div> 
                    <input type="hidden" id="addsectionsub" name="addsectionsub" value="2"/>
                    <input type="hidden" id="add_class_id" name="add_class_id" >
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addsectionsubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Add Class</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="updateclassform" autocomplete="off">  
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="class_name" class="col-md-3">Class Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="updateclass_name" name="updateclass_name" automcomplete="off" required/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="class_name" class="col-md-3">Faculity Name</label>
                    <div class="col-md-9">
                        <select id="updateteacher_id" name="updateteacher_id"  class="form-control" required>
                            <option value="">Select Faculity</option>
                            <?php for($f=0;$f<count($faculity_details);$f++) { ?>
                                <option value="<?php echo $faculity_details[$f]['id']; ?>"><?php echo $faculity_details[$f]['faculity_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" id="updateclasssub" name="updateclasssub" />
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateclasssubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Sections Table</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <table class="table table-bordered table-striped mt-2">
            <tr>
                <th>S.No</th>
                <th>Class Name</th>
                <th>Section Name</th>
                <th>Faculity Name</th>
            </tr>
            <tbody id="section_body">
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
    $("#addclasssubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addclassform').find('input,select,select2').each(function(){
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
			$("#addclasssubmitid").hide();
			$("#addclassform").submit();
           	
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
		$('#tblAddRowMore').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRowMore tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});

        $('#tblAddRow').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRow tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});

    function update_class(classid){
        var action = "class";
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data :{classid:classid,action:action},
        }).done(function(msg) {
            var classes_array = JSON.parse(msg);
            $("#updateclass_name").val(classes_array['class_name']);
            $("#updateteacher_id").val(classes_array['teacher_id']);
        })
        $("#updateModal").modal('show');
        $("#updateclasssub").val(classid);
    }

    $("#updateclasssubmitid").click(function(){
        $("#updateclassform").submit();
    });

    function display_sections(c_id) {
        var faculity_list_json = '<?= json_encode($faculity_list); ?>';
        var faculity_list = JSON.parse(faculity_list_json);
        var action = "sections";
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data :{c_id:c_id,action:action},
        }).done(function(msg) {
            var section_array = JSON.parse(msg);
            $("#section_body").html('');
            for(var i=0;i<section_array.length;i++) {
                $("#section_body").append("<tr>\
                    <td>"+(i+1)+"</td>\
                    <td>"+section_array[i]['class_name']+"</td>\
                    <td>"+section_array[i]['section_name']+"</td>\
                    <td>"+faculity_list[section_array[i]['faculity_id']]+"</td>\
                </tr>");

            }
            $("#sectionModal").modal('show');
        })
    }

    function add_sections(cls_id) {
       // alert(cls_id);
        $("#addsectionModal").modal('show');
        $("#add_class_id").val(cls_id);
    }
    $("#addsectionsubmitid").click(function(){
        $("#addsectionform").submit();
    });

</script>
</body>
</html>

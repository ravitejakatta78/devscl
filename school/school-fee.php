<?php
session_start();

require_once('../common.php');

$userid = current_userid();
if(empty($userid)){
    header("Location: ../login.php");
}
$pagetitle = 'School Fee';
$subtitle = 'Fee List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');

if(!empty($_POST['addfee'])){
    $fee['school_id'] = $school_id;
    $fee['class_id'] = $_POST['class_id'];
    $fee['fee_type'] = $_POST['fee_type'];
    $fee['fee_amount'] = $_POST['fee_amount'];
    $fee['fee_status'] = 1;
    $fee['created_by'] = $user_name;
    $fee['created_on'] = date('Y-m-d');
    $fee['updated_by'] = $user_name;
    $fee['updated_on'] = date('Y-m-d H:i:s');
    insertQuery($fee,'school_fee');
}

if(!empty($_POST['updatefee'])){
    $updateFee['school_id'] = $school_id;
    $updateFee['class_id'] = $_POST['update_class_id'];
    $updateFee['fee_type'] = $_POST['update_fee_type'];
    $updateFee['fee_amount'] = $_POST['update_fee_amount'];
    $updateFee['fee_status'] = 1;
    $updateFee['created_by'] = $user_name;
    $updateFee['created_on'] = date('Y-m-d');
    $updateFee['updated_by'] = $user_name;
    $updateFee['updated_on'] = date('Y-m-d H:i:s');
    $updateFeeCondition['id'] = $_POST['updatefee'];
    updateQuery($updateFee,'school_fee',$updateFeeCondition);
}
$class = runloopQuery("select * from classes where school_id = '".$school_id."'");
//echo "<pre>";print_r($class);exit;
$schoolFee = runloopQuery("select sf.*,c.class_name from school_fee as sf inner join classes as c
on sf.class_id = c.id where sf.school_id = '".$school_id."'");
//echo "<pre>";print_r($schoolFee);exit;

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
                                                <th scope="col" class="sort" data-sort="budget">Fee Type</th>
                                                <th scope="col" class="sort" data-sort="status">Amount</th>
                                                <th scope="col" class="sort" data-sort="status">Update Fee</th>
                                                <th scope="col" class="sort" data-sort="status">Delete Fee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($schoolFee);$i++) { ?>
                                                <tr>
                                                    <td><?php echo $i+1; ?></td>
                                                    <td><?php echo $schoolFee[$i]['class_name']; ?></td>
                                                    <td><?php echo FEE_TYPE[$schoolFee[$i]['fee_type']]; ?></td>
                                                    <td><?php echo $schoolFee[$i]['fee_amount']; ?></td>
                                                    <td><button onclick="update_fee('<?php echo $schoolFee[$i]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                    <i class="fa fa-plus"></button></td>
                                                    <td><button onclick="delete_fee('<?php echo $schoolFee[$i]['id']; ?>')" type="button" class="btn btn-sm btn-white">
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
            <h3 class="modal-title" id="exampleModalLabel">Add School Fee</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="addfeeform" autocomplete="off">  
        <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="class_id" class="col-md-3">Class Name</label>
                    <div class="col-md-9">
                        <select id="class_id" name="class_id" class="form-control" required/>
                            <option value="">Select Class</option>
                            <?php for($c=0;$c<count($class);$c++) { ?>
                                <option value="<?php echo $class[$c]['id']; ?>"><?php echo $class[$c]['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fee_amount" class="col-md-3">Fee Amount</label>
                    <div class="col-md-9">
                        <input type="text" id="fee_amount" name="fee_amount" class="form-control" required/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="fee_type" class="col-md-3">Fee Type</label>
                    <div class="col-md-9">
                        <select id="fee_type" name="fee_type" class="form-control" required/>
                            <option value="">Select Fee Type</option>
                            <?php foreach(FEE_TYPE as $key => $val) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $val;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

            <input type="hidden" id="addfee" name="addfee" value="1"/>
            <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addfeesubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div> <!--add fee over-->

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Update School Fee</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="modal-body">
        <form method="post" action="" id="updatefeeform" autocomplete="off">  
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="update_class_id" class="col-md-3">Class Name</label>
                    <div class="col-md-9">
                        <select id="update_class_id" name="update_class_id" class="form-control" required/>
                            <option value="">Select Class</option>
                            <?php for($c=0;$c<count($class);$c++) { ?>
                                <option value="<?php echo $class[$c]['id']; ?>"><?php echo $class[$c]['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="update_fee_amount" class="col-md-3">Fee Amount</label>
                    <div class="col-md-9">
                        <input type="text" id="update_fee_amount" name="update_fee_amount" class="form-control" required/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="update_fee_type" class="col-md-3">Fee Type</label>
                    <div class="col-md-9">
                        <select id="update_fee_type" name="update_fee_type" class="form-control" required/>
                            <option value="">Select Fee Type</option>
                            <?php foreach(FEE_TYPE as $key => $val) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $val;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

            <input type="hidden" id="updatefee" name="updatefee" />
            
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatefeesubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div> <!--add fee over-->
<script>
    $("#addfeesubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addfeeform').find('input,select,select2').each(function(){
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
			$("#addfeesubmitid").hide();
			$("#addfeeform").submit();
           	
		}
		
	});

    function update_fee(feeId){
        //alert(feeId);
        var action = 'fee';
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data : {feeId:feeId,action:action},
        }).done(function(msg) {
            //alert(msg);
            var fee_array = JSON.parse(msg);
            $("#update_class_id").val(fee_array['class_id']);
            $("#update_fee_amount").val(fee_array['fee_amount']);
            $("#update_fee_type").val(fee_array['fee_type']);
        })
        $("#updateModal").modal('show');
        $("#updatefee").val(feeId);
    }
    $("#updatefeesubmitid").click(function(){
        $("#updatefeeform").submit();
    });

    function delete_fee(fee_id) {
        var text = "Are You Sure To Delete";
        if (confirm(text) == true) {
            var action = "deleteFee";
            var request = $.ajax({
                url : "../ajax/commonajax.php",
                type : "POST",
                data : {fee_id:fee_id,action:action},
            }).done(function(msg){
                location.reload();
            })
        }
        else {
            text = "You canceled!";
        }
    }
</script>
</body>
</html>

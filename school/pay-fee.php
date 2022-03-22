<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'School Fee';
$subtitle = 'Pay Fee List';
$toggleaddbutton = 1;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');

if(!empty($_POST['payfee'])){
    $paidFee['class_id'] = $_POST['class_id'];
    $paidFee['student_id'] = $_POST['student_id'];
    $paidFee['amount'] = $_POST['amount'];
    $paidFee['paid_date'] = $_POST['paid_date'];
    $paidFee['status'] = 1;
    $paidFee['fee_type'] = $_POST['fee_type'];
    $paidFee['school_id'] = $school_id;
    $paidFee['created_on'] = date('Y-m-d H:i:s');
    $paidFee['created_by'] = $user_name;
    $paidFee['updated_on'] = date('Y-m-d H:i:s');
    $paidFee['updated_by'] = $user_name;
    insertQuery($paidFee,'student_paid_fee');
}
$class = runloopQuery("select * from classes where school_id = '".$school_id."'");

$paidFeeDetails = runloopQuery ("select spf.*,c.class_name,s.first_name,s.last_name from student_paid_fee as spf 
inner join classes as c on spf.class_id = c.id 
inner join students as s on spf.student_id = s.id order by spf.id desc");
 //echo "<pre>";print_r($paidFeeDetails);exit;

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
                                                <th scope="col" class="sort" data-sort="name">Class Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Student Name</th>
                                                <th scope="col" class="sort" data-sort="status">Fee Type</th>
                                                <th scope="col" class="sort" data-sort="completion">Paid Amount</th>
                                                <th scope="col" class="sort" data-sort="completion">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($paidFeeDetails);$i++) { ?>
                                                <tr>
                                                    <td><?php echo $i+1; ?></td>
                                                    <td><?php echo $paidFeeDetails[$i]['class_name']; ?></td>
                                                    <td><?php echo $paidFeeDetails[$i]['first_name']; ?> <?php echo $paidFeeDetails[$i]['last_name']; ?>
                                                    </td>
                                                    <td><?php echo FEE_TYPE[$paidFeeDetails[$i]['fee_type']]; ?></td>
                                                    <td><?php echo $paidFeeDetails[$i]['amount']; ?></td>
                                                    <td><button onclick="delete_fee('<?php echo $paidFeeDetails[$i]['id']; ?>')" type="button" class="btn btn-sm btn-white">
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Paid School Fee</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        <div class="modal-body">
        <form method="post" action="" id="payfeeform" autocomplete="off">  
        <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="class_id" class="col-md-3">Class Name</label>
                    <div class="col-md-9">
                        <select id="class_id" name="class_id" class="form-control" onchange="getStudents()" required/>
                            <option value="">Select Class</option>
                            <?php for($c=0;$c<count($class);$c++) { ?>
                                <option value="<?php echo $class[$c]['id']; ?>"><?php echo $class[$c]['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fee_type" class="col-md-3">Fee Type</label>
                    <div class="col-md-9">
                        <select id="fee_type" name="fee_type" class="form-control"  onchange="getfeeamount()" required/>
                            <option value="">Select Fee Type</option>
                            <?php foreach(FEE_TYPE as $key => $val) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $val;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="amount" class="col-md-3">paid Amount</label>
                    <div class="col-md-9">
                        <input type="text" name="amount" id="amount" class="form-control">
                    </div>
                </div>    
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="student_id" class="col-md-3">Students</label>
                    <div class="col-md-9">
                        <select id="student_id" name="student_id" class="form-control">
                            <option value="">Select Student</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fee_amount" class="col-md-3">Fee Amount</label>
                    <div class="col-md-9">
                        <input type="text" name="fee_amount" id="fee_amount" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="paid_date" class="col-md-3">Paid Date</label>
                    <div class="col-md-9">
                        <input type="date" name="paid_date" id="paid_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

            <input type="hidden" id="payfee" name="payfee" value="1"/>
            <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
        </form> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="payfeesubmitid">Save changes</button>
      </div>
    </div>
  </div>
</div> <!--add fee over-->
<script>
    function getStudents() {
        var class_id = $("#class_id").val();
        //alert(class_id);
        var action = "payFee";
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data : {class_id:class_id,action,action},
        }).done(function(msg) {
            //alert(msg);
            var class_array = JSON.parse(msg);
            $("#student_id").html('');
            $("#student_id").append("<option value=''>Select Student</option>");
            for(var s=0;s<class_array.length;s++) {
                $("#student_id").append("<option value='"+class_array[s]['id']+"'>"+class_array[s]['first_name']+" "+class_array[s]['last_name']+"</option>");
            }
        });
    }
    function getfeeamount() {
        var fee_type = $("#fee_type").val();
        //alert(fee_type);
        var classId = $("#class_id").val();
        //alert(classId);
        var action = "feeType";
        var request = $.ajax({
            url : "../ajax/commonajax.php",
            type : "POST",
            data : {fee_type:fee_type,classId:classId,action:action},
        }).done(function(msg){
            //alert(msg);
            var feeAmount = JSON.parse(msg);
            $("#fee_amount").val(feeAmount['fee_amount']);
        })
    }

    $("#payfeesubmitid").click(function() {
        $("#payfeeform").submit();
    });

    function delete_fee(paidId){
        var text = "Are You Sure To Delete";
        if (confirm(text) == true) {
            var action = "deletePaidFee";
            var request = $.ajax({
                url : "../ajax/commonajax.php",
                type : "POST",
                data : {paidId:paidId,action:action},
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

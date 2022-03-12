<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Attendance';
$subtitle = 'Students Attendance List';
$toggleaddbutton = 0;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];

$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');
$classId = runQuery("select * from classes where id = '".$_POST['classId']."'");
//echo "<pre>";print_r($classId);exit;
$students = runloopQuery("select c.*,s.first_name,s.last_name from classes as c inner join students as s
on c.id = s.student_class where c.id = '".$classId['id']."'");
//echo "<pre>";print_r($students);exit;

//date picker dates
$sdate = !empty($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
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
                            <div class="ibox-title pb-4">
                                <h5>6th Class</h5>
                                <div class="ibox-tools">
                                    <form class="form-inline" method="POST" >
                                        <div class="form-group">
                                            <div class="input-group mb-3 mr-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                </div>
                                                <input type="date" class="form-control" id="sdate" name="sdate" placeholder="End Date" value="<?= $sdate ; ?>">
                                            </div>
                                        </div>


                                        <div class="col">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-add btn-sm btn-search" value="Search">
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="ibox-content ">
                                <?php if(!empty($result)){
                                    $notification_class = ($result['status'] == '1')  ? 'success' : 'danger'; 
                                    ?> 
                                    <div class="alert alert-<?= $notification_class; ?> alert-dismissable" id="success-alert">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?= $result['message']; ?>. 
                                    </div>        
                                <?php  } ?>
                            
                                <div class="table-responsive">


                                    <table class="table table-striped table-bordered table-hover" >
                                        <thead>
                                            <tr>
                                                <th scope="col" class="sort" data-sort="budget">
                                                    <input type="checkbox" id="checkAll" name="checkAll" checked>
                                                </th>
                                                <th scope="col" class="sort" data-sort="name">Student Name</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($students);$i++){?>
                                                <tr>
                                                    <td>  <input type="checkbox" id="attendance" name="attendance" checked></td>
                                                    <td><?php echo $students[$i]['first_name'];?>
                                                    <?php echo $students[$i]['last_name']; ?></td>

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
<script>
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    </script>
</body>
</html>

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
$subtitle = 'Classes List';
$toggleaddbutton = 0;
$result = [];
//$result = ['status' => '1', 'message' => 'Added Successfully'];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');

$classes = runloopQuery("select * from classes where school_id = '".$school_id."'");
//echo "<pre>";print_r($classes);exit;


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
                                <h5>Attendance</h5>
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
                                                <th  scope="col" class="sort" data-sort="name">Attendance</th>
                                                <th scope="col" class="sort" data-sort="budget">Add Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php for($c=0;$c<count($classes);$c++) { ?>
                                            <tr>
                                                <td><?php echo $c+1; ?></td>
                                                <td><?php echo $classes[$c]['class_name']; ?></td>
                                                <td>0/100</td>
                                                <td><button onclick="addAttendance('<?php echo $classes[$c]['id']; ?>')" type="button" class="btn btn-sm btn-white">
                                                <i class="fa fa-plus"></button></td>
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
        function addAttendance(classId){
            //alert(classId);
            var form=document.createElement('form');
            form.setAttribute('method','post');
            form.setAttribute('action','show-student-attendance.php');

            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("name", "classId");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("value", classId);
            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

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
$subtitle = 'Exam Students List';
$toggleaddbutton = 0;
$result = [];

$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');
$exam_details = runQuery("select * from exams where id = '".$_POST['examId']."'");
//echo "<pre>";print_r($exam_details);exit;
$students = runloopQuery("select * from students where student_class = '".$exam_details['class_id']."'");

echo "<pre>";print_r($students);exit;




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
                                <h3 class="pb-3"><?php echo $exam_details['exam_name']; ?></h3>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                                        <thead>
                                            <tr>
                                                <th scope="col" class="sort" data-sort="name">ID</th>
                                                <th scope="col" class="sort" data-sort="name">Student Name</th>
                                                <th scope="col" class="sort" data-sort="name">Status</th>
                                                <th scope="col" class="sort" data-sort="name">Update Marks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($students);$i++) { ?>
                                                <tr>
                                                    <td><?php echo $i+1; ?></td>
                                                    <td><?php echo $students[$i]['first_name']; ?> <?php echo $students[$i]['last_name']; ?>
                                                    </td>
                                                    <td></td>
                                                    <td><button onclick="updateMarks('<?php echo $students[$i]['id']; ?>',
                                                    '<?php echo $exam_details['id']; ?>')">
                                                    </button></td>
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
        function updateMarks(studentId,examId)
        {
            //alert(examId);
        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','save-exam-marks.php');

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("name", "studentId");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("value", studentId);
        form.appendChild(hiddenField);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("name", "examId");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("value", examId);
        form.appendChild(hiddenField);

        document.body.appendChild(form);
        form.submit();
        }
    </script>

</body>
</html>

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
$subtitle = 'Save Exam Marks';
$toggleaddbutton = 0;
$result = [];

$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');
//echo $_POST['studentId'];exit;
$student = runQuery("select * from students where id = '".$_POST['studentId']."'");
//echo "<pre>";print_r($student);exit;
$exam = runQuery("select * from exams where id = '".$_POST['examId']."'");
//echo "<pre>";print_r($exam);exit;

if(!empty($_POST['subject_marks'])){
    print_r($_POST['subject_marks']);exit; 
}

$subjects = runloopQuery("select * from subjects where school_id = '".$school_id."'");
//echo "<pre>";print_r($subjects);exit;


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
                                    <div class="card">
                                        <div class="card-header">
                                            <?php echo $student['first_name'];?> <?php echo $student['last_name'];?> -
                                            <?php echo $exam['exam_name'];?>
                                        </div>
                                        <div class="card-body">
                                            <form>
                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-2 col-form-label"><h3>Subject Name</h3></label>
                                                    <label for="staticEmail" class="col-sm-2 col-form-label"><h3>Marks</h3></label>
                                                </div>
                                                <?php for($i=0;$i<count($subjects);$i++) { ?>
                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $subjects[$i]['subject_name']; ?></label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="inputPassword" placeholder="Marks" name="subject_marks[]">
                                                        <input type="hidden" class="form-control" name="subject_id[]" id="subject_id">
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <input type="hidden" name="studentId" value="<?php echo $_POST['studentId']; ?>">
                                                <input type="hidden" name="examId" value="<?php echo $_POST['examId']; ?>">
                                                <input type="submit" value="Save Marks" class="btn btn-primary">
                                            </form>
                                            
                                        </div>
                                    </div>
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
         function submitmarks(){
             $("#marks").submit();
         }
    </script>

</body>
</html>

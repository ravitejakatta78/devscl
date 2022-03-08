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
    if($_POST['examMarksCondition'] == 2) {
    $marks_summary['exam_id'] = $_POST['examId'];
    $marks_summary['student_id'] = $_POST['studentId'];
    $marks_summary['exam_status'] = 1; 
    $marks_summary['total_marks'] = array_sum($_POST['subject_marks']);
    $marks_summary['reg_date'] = date('Y-m-d H:i:s');
    $marks_summary['created_by'] = $userid;
    $marks_summary['updated_on'] = date('Y-m-d H:i:s');
    $marks_summary['updated_by'] = $userid;

    $summary_id = insertIDQuery($marks_summary,'student_marks_summary');

    if(!empty($summary_id)){
        for($m=0;$m<count($_POST['subject_id']);$m++){
            $marks['summary_marks_id'] = $summary_id;
            $marks['subject_id'] = $_POST['subject_id'][$m];
            $marks['marks'] = $_POST['subject_marks'][$m];
            $marks['reg_date'] = date('Y-m-d H:i:s');
            $marks['created_by'] = $userid;
            $marks['updated_on'] = $userid;
            $marks['updated_by'] = date('Y-m-d H:i:s');

            $marks = insertQuery($marks,'student_marks_details');
        }
    }
    }
    else{
        $update_marks_summary['exam_id'] = $_POST['examId'];
        $update_marks_summary['student_id'] = $_POST['studentId'];
        $update_marks_summary['exam_status'] = 1; 
        $update_marks_summary['total_marks'] = array_sum($_POST['subject_marks']);
        $update_marks_summary['reg_date'] = date('Y-m-d H:i:s');
        $update_marks_summary['created_by'] = $userid;
        $update_marks_summary['updated_on'] = date('Y-m-d H:i:s');
        $update_marks_summary['updated_by'] = $userid;
        $updatecondition['id'] = $_POST['subject_marks'];
        updateQuery($update_marks_summary,'student_marks_summary',$updatecondition);
    }
    
    header('location: exams.php');
}

$subjects = runloopQuery("select ed.*,s.id,s.subject_name from exam_details as ed inner join subjects as s
on ed.subject_id = s.id where ed.exam_id = '".$exam['id']."'");
//echo "<pre>";print_r($subjects);exit;
$summary = runQuery("select * from student_marks_summary where exam_id = '".$_POST['examId']."' and
student_id = '".$_POST['studentId']."'");
//echo "<pre>";print_r($summary);exit;
$examMarksCondition = (!empty($summary)) ? 1 : 2;
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
                                            <form method="post">
                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-2 col-form-label"><h3>Subject Name</h3></label>
                                                    <label for="staticEmail" class="col-sm-2 col-form-label"><h3>Marks</h3></label>
                                                </div>
                                                <?php for($i=0;$i<count($subjects);$i++) { 
                                                    if($examMarksCondition == 1) {
                                                        $marksList = runQuery("select * from student_marks_details where summary_marks_id and
                                                        subject_id = '".$summary['id']."' and '".$subjects[$i]['id']."'");
                                                        $marks_list = $marksList['marks'];
                                                    }
                                                    else{
                                                        $marks_list = NULL;
                                                    }
                                                    ?>
                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-2 col-form-label"><?php echo $subjects[$i]['subject_name']; ?></label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="inputPassword" placeholder="Marks" name="subject_marks[]" value = "<?php echo $marks_list; ?>">
                                                        <input type="hidden" class="form-control" name="subject_id[]" id="subject_id" value="<?php echo $subjects[$i]['id']; ?>">
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <input type="hidden" name="studentId" value="<?php echo $_POST['studentId']; ?>">
                                                <input type="hidden" name="examId" value="<?php echo $_POST['examId']; ?>">
                                                <input type="text" name="examMarksCondition" value="<?php echo $examMarksCondition; ?>">
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
    </script>

</body>
</html>

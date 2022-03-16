<?php
include("../common.php");
if(!empty($_POST['action'])){
    $action = $_POST['action'];
    switch($action){
        case 'get-school-details':
            $sql = runQuery("select * from schools where ID = '".$_POST['school_id']."'");
            $resp = !empty($sql) ? $sql : [];
            echo json_encode($resp);
        break;
        case 'subjectname':
            $sql = runQuery("select * from subjects where id = '".$_POST['subject_id']."'");
            echo json_encode($sql);
        break;
        case 'faculity': 
            $sql = runQuery("select * from faculity where id = '".$_POST['faculiyid']."'");
            echo json_encode($sql);
        break;
        case 'class':
            $sql = runQuery("select * from classes where id = '".$_POST['classid']."'");
            echo json_encode($sql);
        break;
        case 'sections':
            $sql = runloopQuery("select c.teacher_id,cs.teacher_id as faculity_id,class_name,section_name
             from classes as c inner join class_sections as cs on c.id = cs.class_id 
            where c.id = '".$_POST['c_id']."'");
            echo json_encode($sql);
        break;
        case 'updateStudent':
            $sql = runQuery("select * from students as s inner join parents as p on s.parent_id = p.id
            where s.id = '".$_POST['student_id']."'");
            echo json_encode($sql);
        break;
        case 'exams':
            $sql = runloopQuery("select e.exam_name,ed.* from exam_details as ed inner join exams as e
            on ed.exam_id = e.id where e.id = '".$_POST['e_id']."'");
            echo json_encode($sql);
        break;
        case 'deleteExam' :
            $deleteExam['id'] = $_POST['d_id'];
            $deleteExamDetails['exam_id'] = $_POST['d_id'];
            deleteQuery($deleteExam,'exams');
            deleteQuery($deleteExamDetails,'exam_details');
            echo "1";
        break;
        case 'marks' :
            $sql = runloopQuery("select sms.*,smd.marks,s.subject_name from student_marks_summary as sms inner join
            student_marks_details as smd on sms.id = smd.summary_marks_id inner join subjects as s on s.id = smd.subject_id
            where sms.student_id = '".$_POST['student_id']."' and sms.exam_id = '".$_POST['exam_id']."'");
            echo json_encode($sql);
        break;
        case 'fee':
            $sql = runQuery("select * from school_fee where id = '".$_POST['feeId']."'");
            echo json_encode($sql);
        break;
        case 'deleteFee' :
            $deleteFee['id'] = $_POST['fee_id'];
            deleteQuery($deleteFee,'school_fee');
            echo "1";
        break;
        default:
        echo json_encode([]);
        break; 
    }

}
else{
    echo json_encode([]);
}

?>
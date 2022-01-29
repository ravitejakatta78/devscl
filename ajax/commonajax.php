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
        default:
        echo json_encode([]);
        break; 
    }

}
else{
    echo json_encode([]);
}

?>
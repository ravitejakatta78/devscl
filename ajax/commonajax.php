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
        default:
        echo json_encode([]);
        break; 
    }

}
else{
    echo json_encode([]);
}

?>
<?php
include("../common.php");
include("../helper/Utility.php");

if(!empty($_POST['action'])){
    $action = $_POST['action'];
    switch($action){
        case 'get-school-details':
            $sql = runQuery("select * from schools where ID = '".$_POST['school_id']."'");
            $resp = !empty($sql) ? $sql : [];
            echo json_encode($resp);
        break;
        case 'update-subject-status':
            $model = new Utility;
            echo $model->update_subject_status($_POST);
            //Utility::update_subject_status($_POST);
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
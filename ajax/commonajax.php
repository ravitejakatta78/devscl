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
        default:
        echo json_encode([]);
        break; 
    }

}
else{
    echo json_encode([]);
}

?>
<?php

require_once('../common.php');

class Utility {
    public  function update_subject_status($params){
        if(!empty(@$params['usersid'])){
            $whereupdatecolumn['id'] = $params['subject_id'];
            $subject_details = runQuery("select * from subjects where id = '".$params['subject_id']."'");
            if($subject_details['status'] == '1'){
                $updatecolumns['status'] = 0;
            }
            else{
                $updatecolumns['status'] = 1;
            }
            $result = updateQuery($updatecolumns,'subjects',$whereupdatecolumn);
        }
    }
}

?>
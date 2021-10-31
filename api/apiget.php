<?php
include("../common.php");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json'); 

function validate_users($authtoken,$usersid){
	

        if($authtoken){
     
        // if decode succeed, show user details
        try { 
            //$decoded = JWT::decode($jwttoken, $key, array('HS256'));
            
            $originaltoken = currentusertoken($usersid);
            $verifytoken = strcmp($originaltoken,$authtoken);
            // set response code
            
      
             return $verifytoken;
        }
     
           catch (Exception $e){
         
                // set response code
                http_response_code(401);
                //echo $e->getMessage();
            }
        }
    }

    $usersid = $_GET['usersid'];
        
if(!empty($usersid)){
    $headerslist = apache_request_headers();
    if(!empty($headerslist['Authorization'])){
        $usertoken = validate_users($headerslist['Authorization'],$usersid);
        if(empty($usertoken)){
            $userdetails = runQuery("select * from users where ID = '".$usersid."'");
	        if(!empty($userdetails['id'])){
                $action = mysqli_real_escape_string($conn,trim($_GET['action']));
                if(!empty($action)){ 
                    switch($action){ 
                        case 'get-school-list':
                            $school_list = runloopQuery("select * from schools order by id desc");
                            $payload = array('status'=>'1','school_list' => $school_list,'message'=>'Schools List');
                          break;
                        case 'get-subjects-list':
                            $subject_list = runloopQuery("select * from subjects where school_id = '".$_GET['school_id']."'order by id desc");
                            $payload = array('status'=>'1','subject_list' => $subject_list,'message'=>'Subjects List');
                            break;  
                          default:
                            $payload = array('status'=>'0','message'=>'Please specify a valid action');
                           break;
                        }
                    }
                    else{
                        $payload = array('status'=>'0','message'=>'Please specify a valid action');
                    }        
            }
            else{
	            $payload = array("status"=>'0',"text"=>"Invalid user");
            }
        }
        else{
            $payload = array('status'=>'0','message'=>'Invalid Authorization Token');    
        }
    }
    else{
        $payload = array('status'=>'0','message'=>'Invalid Authorization');    
    }
    //currentusertoken($usersid);
}
else{
	 
	$payload = array('status'=>'0','message'=>'Invalid users details');
}
echo json_encode($payload);
?>
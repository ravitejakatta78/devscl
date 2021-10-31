<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include("../common.php");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

$usersid = $_POST['usersid'];

if(!empty($usersid)){
    
$headerslist = apache_request_headers();
if(!empty($headerslist['Authorization'])){
    $usertoken = validate_users($headerslist['Authorization'],$usersid);
    if(empty($usertoken)){
        $userdetails = runQuery("select * from users where ID = '".$usersid."'");
        if(!empty($userdetails['id'])){
            $action = mysqli_real_escape_string($conn,trim($_POST['action']));
                     

            if(!empty($action)){ 
                switch($action){ 
                    
                    case 'add-school':
                        if( !empty($_POST['school_name']) && !empty($_POST['registration_number']) )
                        {
                            $insertarr['school_name'] =  mysqli_real_escape_string($conn,trim($_POST['school_name']));
                            $insertarr['registration_number'] =  mysqli_real_escape_string($conn,trim($_POST['registration_number']));
                            $insertarr['address'] =  mysqli_real_escape_string($conn,trim($_POST['address']));
                            $insertarr['email'] =  mysqli_real_escape_string($conn,trim($_POST['email']));
                            $insertarr['mobile'] =  mysqli_real_escape_string($conn,trim($_POST['mobile']));
                            $insertarr['landline'] =  mysqli_real_escape_string($conn,trim($_POST['landline']));
                            $insertarr['created_by'] = $userdetails['first_name'];
                            $lastid = insertIDQuery($insertarr,'schools');
                            
                            if($lastid){

                                $userarr['school_id'] = $lastid;
                                $userarr['user_name'] = 'SCH'.str_pad($lastid,4,"0",STR_PAD_LEFT); 
                                $userarr['user_password'] = md5('1234');
                                
                                $userarr['user_email'] = mysqli_real_escape_string($conn,trim($_POST['email'])) ?? '';
                                $userarr['user_mobile'] = mysqli_real_escape_string($conn,trim($_POST['mobile'])) ?? '';
                                $userarr['role_id'] = _SCHOOL_ADMIN;
                                $userarr['user_status'] = _ACTIVE;
                                $userarr['created_by'] = $userdetails['first_name'];
                                $result = insertQuery($userarr,'users');
                                $payload = array('status'=>_SUCCESS ,'message' => 'School Added Successfully');
								
                            }
								else{
									$payload = array('status'=>_ERROR,'message'=>'Error While Adding School Details.');
								}
                        }
                        else{
                            $payload = array('status'=>_ERROR,'message' => 'Missing Required Parameters');    
                        }
                    break;
                    case 'add-subject':
 
                        if( !empty($_POST['subject_name']) )
                        {
   
                            $insertarr['school_id'] =  mysqli_real_escape_string($conn,trim($_POST['school_id']));
                            $insertarr['subject_name'] =  mysqli_real_escape_string($conn,trim($_POST['subject_name']));
                            $insertarr['status'] =  mysqli_real_escape_string($conn,trim($_POST['status']));
                            $insertarr['created_by'] = $userdetails['first_name'];
                            $result = insertQuery($insertarr,'subjects');
                            if(!$result){
                                $payload = array('status'=>_SUCCESS,'message' => 'Subject Added Successfully');
								}
								else{
									$payload = array('status'=>_ERROR,'message'=>'Error While Adding Subject Details.');
								}
                        }
                        else{
                            $payload = array('status'=>_ERROR,'message' => 'Missing Required Parameters');    
                        }    
                    break;
                      default:
                        $payload = array('status'=>_ERROR,'message'=>'Please specify a valid action');
                       break;
                    }
                }
                else{
                    $payload = array('status'=>_ERROR,'message'=>'Please specify a valid action');
                }        
        }
        else{
            $payload = array("status"=>_ERROR,"text"=>"Invalid user");
        }
    }
    else{
        $payload = array('status'=>_ERROR,'message'=>'Invalid Authorization Token');    
    }
}
else{
    $payload = array('status'=>_ERROR,'message'=>'Invalid Authorization');    
}
//currentusertoken($usersid);
}
else{
 
$payload = array('status'=>_ERROR,'message'=>'Invalid users details');
}
echo json_encode($payload);
?>
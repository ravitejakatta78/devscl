<?php
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
                            $result = insertQuery($insertarr,'schools');
							//$result = updateQuery($insertarr,'schools',$insertWhereArray);
                            if(!$result){
                                $payload = array('status'=>'1','message' => 'School Added Successfully');
								}
								else{
									$payload = array('status'=>'0','message'=>'Error While Adding School Details.');
								}
                        }
                        else{
                            $payload = array('status'=>'0','message' => 'Missing Required Parameters');    
                        }
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
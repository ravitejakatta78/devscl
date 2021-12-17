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

$usersid = !empty(@$_POST['usersid']) ? $_POST['usersid'] : '';
    
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
                                $payload = array('status'=>'200','message' => 'School Added Successfully');
								}
								else{
									$payload = array('status'=>'400','message'=>'Error While Adding School Details.');
								}
                        }
                        else{
                            $payload = array('status'=>'400','message' => 'Missing Required Parameters');    
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
    if(!empty($_POST['action']) &&  @$_POST['action'] == 'login'){
        if( !empty($_POST['username']) && !empty($_POST['password']) ) {
            $mymailid = mysqli_real_escape_string($conn,$_POST['username']);	
            $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 	
            $row = runQuery("SELECT u.id as usersid, user_password, u.user_name ,u.first_name ,u.last_name 
            , user_email as email ,user_mobile as mobile, u.role_id, u.gender, school_name, s.address as school_address
            , s.email school_email 
            , s.mobile school_mobile 
            FROM users u inner join schools s on u.school_id = s.id WHERE u.user_name = '".$mymailid."'");
            if(!empty($row['usersid'])){	
                if(($row['user_password']) == md5($mypassword) ){
                    $userArray = [];
                    $userArray = $row;
                    $userArray['role_name'] = ROLES[$row['role_id']];
                    $userArray['gender'] = GENDER[$row['gender']];
                    $userArray['token'] = currentusertoken($userArray['usersid']);
                    unset($userArray['user_password']);
                    $payload = array('status'=>'200','user_details'=>$userArray);
                } else {			
                    $payload = array('status'=>'400','message'=>'Your login password is invalid');
                }		
            }
            else {	
                $payload = array('status'=>'400','message'=>'Invalid Username');	
            }	
        }
        else{
            $payload = array('status'=>'400','message'=>'Please provide Username and Password');
        }
    }    
    else{
        $payload = array('status'=>'0','message'=>'Invalid users details');
    } 

}
echo json_encode($payload);
?>
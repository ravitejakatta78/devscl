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
                            $school_list = [];
                            $school_list = runQuery("select * from schools where id = '".$userdetails['school_id']."'");
                            $payload = array('status'=>'200','school_list' => $school_list,'message'=>'School Details');
                        break;
                        case 'classList':
                            $classList = [];
                            $classList = runloopQuery("select c.id class_id, class_name, f.faculity_name from classes c inner join faculity f on f.id  = c.teacher_id where c.school_id = {$userdetails['school_id']}  AND c.status = ".ACTIVE );
                            $payload = array('status'=>'200','classList' => $classList,'message'=>'Class Details');
                        break;
                        case 'faculityList':
                            $faculityList = [];
                            $faculityList = runloopQuery("select f.*,subject_name from faculity f 
                            inner join subjects s on f.subject_id  = s.id 
                            where f.school_id = {$userdetails['school_id']}  AND f.status = ".ACTIVE );
                            $payload = array('status'=>'200','faculityList' => $faculityList,'message'=>'Faculity Details');
                        break;
                        case 'studentList':
                            $studentList = [];
                            $studentList = "select s.* from students s 
                            inner join classes c on s.student_class  = c.id 
                            where s.school_id = {$userdetails['school_id']}  AND s.status = ".ACTIVE ;
                            if(!empty($_GET['class_id'])){
                                $studentList .= " and c.id = {$_GET['class_id']} ";
                            }
                            if(!empty($_GET['student_id'])){
                                $studentList .= " and s.id = {$_GET['student_id']} ";
                            }
                            $studentList = runloopQuery($studentList);

                            $payload = array('status'=>'200','studentList' => $studentList,'message'=>'Student Details');
                        break;
                        case 'subjectList':
                            $subjectList = [];
                            $subjectList = runloopQuery("select * from subjects where school_id = '".$userdetails['school_id']."'");
                            $payload = array('status'=>'200','studentList' => $subjectList,'message'=>'Subject Details');
                        break;    
                        default:
                            $payload = array('status'=>'400','message'=>'Please specify a valid action');
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
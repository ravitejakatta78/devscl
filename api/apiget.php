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
                            $faculitySql = runloopQuery("select f.*,subject_name from faculity f 
                            inner join subjects s on f.subject_id  = s.id 
                            where f.school_id = {$userdetails['school_id']}  AND f.status = ".ACTIVE );
                            foreach($faculitySql as $faculity) {
                                $faculitySingle['id'] = $faculity['id'];
                                $faculitySingle['faculity_name'] = $faculity['faculity_name'];
                                $faculitySingle['address'] = $faculity['address'];
                                $faculitySingle['qualification'] = $faculity['qualification'];
                                $faculitySingle['subject_id'] = $faculity['subject_id'];
                                $faculitySingle['school_id'] = $faculity['school_id'];
                                $faculitySingle['status'] = $faculity['status'];
                                $faculitySingle['email'] = $faculity['email'];
                                $faculitySingle['mobile'] = $faculity['mobile'];
                                $faculitySingle['gender'] = $faculity['gender'];
                                $faculitySingle['faculity_pic'] = SERVER_URL.'/school_docs/'.$faculity['school_id'].'/faculity_docs/'.$faculity['faculity_pic'];
                                $faculityList[] = $faculitySingle;
                            }
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
                            $payload = array('status'=>'200','subjectList' => $subjectList,'message'=>'Subject Details');
                        break;
                        case 'examList':
                            $examList = [];
                            $examList = "select e.id exam_id, c.class_name, e.class_id, e.exam_name, e.exam_start_date
                            , e.exam_end_date from exams e inner join classes c on e.class_id = c.id 
                            where e.school_id = '".$userdetails['school_id']."' "; 
                            if(!empty($_GET['class_id'])){
                                $examList .= " and e.class_id = '".$_GET['class_id']."' ";    
                            }
                            if(!empty($_GET['id'])){
                                $examList .= " and e.id = '".$_GET['id']."'";
                            }
                            $examList .= " order by e.id desc";
                            $examList = runloopQuery($examList);
                            $payload = array('status'=>'200','examList' => $examList,'message'=>'Exam List');
                        break;
                        case 'examScheduleList':
                            $examScheduleList = [];
                            $examScheduleList = runloopQuery("select ed.exam_date,subject_name from exam_details ed inner join subjects s on ed.subject_id = s.id 
                            where ed.exam_id = '".$_GET['exam_id']."'"); 
                            
                            $payload = array('status'=>'200', 'examScheduleList' => $examScheduleList
                            , 'exam_name' => $_GET['exam_name'], 'class_name' => $_GET['class_name']
                            , 'message'=>'Exam List');
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
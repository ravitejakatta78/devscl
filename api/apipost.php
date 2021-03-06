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
                    case 'add-faculity':
                        if(!empty($_POST['faculity_name']) && !empty($_POST['qualification'])){
                            $faculityadd = [];
                            $faculityadd['faculity_name'] = $_POST['faculity_name'];
                            $faculityadd['qualification'] = $_POST['qualification'];
                            $faculityadd['subject_id'] = $_POST['subject_id'];
                            $faculityadd['school_id'] = $userdetails['school_id'];
                            $faculityadd['email'] = $_POST['email'];
                            $faculityadd['mobile'] = $_POST['mobile'];
                            $faculityadd['gender'] = $_POST['gender'];
                            $faculityadd['address'] = $_POST['address'];
                            $faculityadd['status'] = 1;
                            $faculityadd['created_on'] = date('Y-m-d H:i:s');
                            $faculityadd['updated_on'] = date('Y-m-d H:i:s');
                            $faculityadd['created_by'] = $userdetails['user_name'];
                            $faculityadd['updated_by'] = $userdetails['user_name'];
                            if(!empty($_FILES['faculity_pic']['name'])){
                                $new_name = uploadProfilePic($_FILES['faculity_pic'],$userdetails['school_id']);
                                $faculityadd['faculity_pic'] = $new_name;
                            }
                            $faculity_id = insertIDQuery($faculityadd,'faculity');
                            if(!empty($faculity_id)){
                                $payload = ['status' => '1', 'message' => 'Added Faculity Successfully'];
                            }
                            else{
                                $payload = ['status' => '0', 'message' => 'Error While Adding Faculity'];
                            }
                        }
                        else{
                            $payload = ['status' => '0', 'message' => 'Please Provide Required Parameters'];
                        }
                    break;
                    case 'add-student':
                        $parentDetails = runQuery("select * from parents where phone = '".$_POST['phone']."'");
                        if(!empty($parentDetails)){
                            $parent_id = $parentDetails['id']; 
                        }else{
                            $parents['parent_name'] = $_POST['parent_name'];
                            $parents['parent_type'] = $_POST['parent_type'];
                            $parents['occupation'] = $_POST['occupation'];
                            $parents['email'] = $_POST['email'];
                            $parents['phone'] = $_POST['phone'];
                            $parents['status'] = 1;
                            $parents['created_by'] = $userdetails['user_name'];;
                            $parents['updated_by'] = $userdetails['user_name'];;
                            $parents['created_on'] = date('Y-m-d H:i:s');
                            $parents['updated_on'] = date('Y-m-d H:i:s');
                            $parents['reg_date'] = date('Y-m-d');
                            $parent_id = insertIDQuery($parents,'parents');    
                        }

                        if(!empty($parent_id)) {
                            $students['school_id'] = $userdetails['school_id'];
                            $students['status'] = 1;
                            $students['role_id'] = 4;
                            $students['first_name'] = $_POST['first_name'];
                            $students['last_name'] = $_POST['last_name'];
                            $students['gender'] = $_POST['gender'];
                            $students['dob'] = $_POST['dob'];
                            $students['address'] = $_POST['address'];
                            $students['roll_number'] = $_POST['roll_number'];
                            $students['blood_group'] = $_POST['blood_group'];
                            $students['religion'] = $_POST['religion'];
                            $students['student_class'] = $_POST['student_class'];
                            $students['admission_id'] = $_POST['admission_id'];
                            $students['parent_id'] = $parent_id;
                            if(!empty($_FILES['student_img']['name'])){
                                $path = '../../school_docs/'.$userdetails['school_id'].'/student_image/';
                                $new_name = upload_student_pic($_FILES['student_img'],$path);
                                $students['student_img'] = $new_name;   
                            }
                            $students['created_by'] = $userdetails['user_name'];
                            $students['updated_by'] = $userdetails['user_name'];
                            $students['created_on'] = date('Y-m-d H:i:s');
                            $students['updated_on'] = date('Y-m-d H:i:s');
                            $students['reg_date'] = date('Y-m-d');

                            $student_id = insertIDQuery($students,'students');
                            if(!empty($student_id)) {
                                $payload = ['status' => '1', 'message' => 'Added Student Successfully'];
                            }
                            else{
                                $payload = ['status' => '0', 'message' => 'Error While Adding'];   
                            }
                        }else{
                            $payload = ['status' => '0', 'message' => 'Error While Adding Parent Details'];
                        }
                    break;
                    case 'addExam':
                        if(!empty($_POST['exam_name']) && $_POST['exam_start_date'] && $_POST['exam_end_date']){
                            $exams['exam_name'] = $_POST['exam_name'];
                            $exams['exam_start_date'] = $_POST['exam_start_date'];
                            $exams['exam_end_date'] = $_POST['exam_end_date'];
                            $exams['class_id'] = $_POST['class_id'];
                            $exams['school_id'] = $userdetails['school_id'];
                            $exams['created_on'] = date('Y-m-d H:i:s');
                            $exams['created_by'] = $userdetails['user_name'];
                            $exams['updated_on'] = date('Y-m-d H:i:s');
                            $exams['updated_by'] = $userdetails['user_name'];
                            $exam_id = insertIDQuery($exams,'exams');
                            
                            if($exam_id) {
                                if(!empty($_POST['subject_id'])){
                                    for($e=0;$e<count($_POST['subject_id']);$e++){
                                        $exam_details['exam_date'] = $_POST['exam_date'][$e];
                                        $exam_details['subject_id'] = $_POST['subject_id'][$e];
                                        $exam_details['exam_id'] = $exam_id;
                                        $exam_details['created_on'] = date('Y-m-d H:i:s');
                                        $exam_details['created_by'] = $userdetails['user_name'];
                                        $exam_details['updated_on'] = date('Y-m-d H:i:s');
                                        $exam_details['updated_by'] = $userdetails['user_name']; 
                                        insertQuery($exam_details,'exam_details');
                                    }  
                                }
                                $payload = ['status' => '1', 'message' => 'Exam Added Successfully'];
                            }
                            else{
                                $payload = ['status' => '0', 'message' => 'Error While Adding Exam'];
                            }
                            
                         }
                        else{
                            $payload = ['status' => '0', 'message' => 'Please Provide All Manadotory Fields'];
                        }
                    break;
                    case 'deleteExam':
                        if(!empty($_POST['examId'])){
                            deleteQuery(['id' => $_POST['examId']],'exams');
                            deleteQuery(['exam_id' => $_POST['examId']],'exam_details');

                            $payload = ['status' => '1', 'message' => 'Exam Deleted Successfully'];
                        }
                        else{
                            $payload = ['status' => '0', 'message' => 'Error While Deleting Exam'];
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
<?php
$base_directory = basename(__DIR__) ;
$site_url = 'http://192.168.0.170/'.$base_directory.'/';

date_default_timezone_set('Asia/Kolkata');

ini_set('display_errors', 'On');

ini_set('log_errors', 'On');

define('SITE_URL',$site_url);

define('SCHOOL_NAME','INSPINIA');

define('SCHOOL_TOKEN','$CH0O1');

const _ACTIVE = 1;
const _INACTIVE = 2;

const _SUCCESS = 1;
const _ERROR = 0;

/* Roles */
const _ADMIN = 1;
const _SCHOOL_ADMIN = 2;
const _FACULITY = 3;
const _PARENT = 4;

include('dbconfig.php');

function insertQuery($query,$table){

	include('dbconfig.php');

//	$query['reg_date'] = date('Y-m-d H:i:s');

	$keys = array_keys($query);



$sql = "INSERT INTO ".$table." SET ";



for($e=0;$e<sizeof($keys);$e++) {



$sql .=  $keys[$e].'="'.$query[$keys[$e]].'"';



if($e != sizeof($keys)-1) { $sql .= ','; }



}

//echo $sql;exit;

$result = mysqli_query($conn,$sql);

if(!$result) {

	return $conn->error;

}

}


function insertIDQuery($query,$table){

	include('dbconfig.php');

	//$query['reg_date'] = date('Y-m-d H:i:s');

	$keys = array_keys($query);



$sql = "INSERT INTO ".$table." SET ";



for($e=0;$e<sizeof($keys);$e++) {



$sql .=  $keys[$e].'="'.$query[$keys[$e]].'"';



if($e != sizeof($keys)-1) { $sql .= ','; }



}



$result = mysqli_query($conn,$sql);

if($result) {

	return $conn->insert_id;

}

}



function updateQuery($query,$table,$wherec){

	include('dbconfig.php');

	

	$arr_keys = array_keys( $query );

	

	$second_arr_keys = array_keys( $wherec );

	

	$sql = "UPDATE ".$table." SET ";

	

	for($y=0;$y<sizeof($arr_keys);$y++) {

		

		$sql .=  $arr_keys[$y].' = "'.$query[$arr_keys[$y]].'"';



        if($y != sizeof($arr_keys)-1) { $sql .= ','; }

		

	}

	

	$sql .= " WHERE ";

	

	for($x=0;$x<sizeof($second_arr_keys);$x++) {

		

		$sql .=  $second_arr_keys[$x].'="'.$wherec[$second_arr_keys[$x]].'"';



        if($x != sizeof($second_arr_keys)-1) { $sql .= ' AND '; }

		

	}

	$result = mysqli_query($conn,$sql);



	if(!$result) {

return $conn->error;

	}
}

function deleteQuery($wherec,$table){

	include('dbconfig.php');

 $second_arr_keys = array_keys( $wherec );



        $sql = "DELETE FROM ".$table." ";



        $sql .= " WHERE ";



        for($x=0;$x<sizeof($second_arr_keys);$x++) {



            $sql .=  $second_arr_keys[$x].'="'.$wherec[$second_arr_keys[$x]].'"';



            if($x != sizeof($second_arr_keys)-1) { $sql .= ' AND '; }



        }

$result = mysqli_query($conn,$sql);

     

	if(!$result) {



		return $conn->error;

	}

    }

function runQuery($query){

	include('dbconfig.php');

$data = array();

$result = $conn->query($query);



    // output data of each row

    while($row = $result->fetch_assoc()) {

       $data = $row;

    }

      return $data;

}

function runloopQuery($query){

	include('dbconfig.php');

$data = array();

$result = $conn->query($query);



    // output data of each row

    while($row = $result->fetch_assoc()) {

       $data[] = $row;

    }

      return $data;

}

function current_userid(){
	$value = "";
    if(!empty($_SESSION['sessionusersid'])){
	    return $value = $_SESSION['sessionusersid'] ?: "";
	}
}

function getUserDetails($column_name = null){
    $current_userid = @$_SESSION['sessionusersid'];
    $details = [];
    if(!empty($current_userid)){
        $user_complete_details = runQuery("select * from users where id = '".$current_userid."'");
        if(!empty($column_name)){
            $details = $user_complete_details[$column_name];
        }
        else{
            $details = $user_complete_details;
        }
    }
    return $details;
}

function getSchoolDetails($column_name = null){
    $details = [];
    $school_id = getUserDetails('school_id');
    if(!empty($school_id)){
        if(!empty($column_name)){
            $school_complete_details = runQuery("select * from schools where id = '".$school_id."'");
            $details = $school_complete_details[$column_name];
        }
        else{
            $details = runQuery("select * from schools where id = '".$school_id."'");
        }
    }
    return $details;
}

function currentusertoken($usersid = null) {
   $usersid = !empty($usersid) ? $usersid : current_userid();
   $plaintoken = SCHOOL_TOKEN.'-'.$usersid;
   return hash('sha256', $plaintoken);
}

function apicall($arr)
{
    $base_directory = basename(__DIR__) ;
    if($arr['call'] == 'GET')
    {
        $url_path = SITE_URL.'api/apiget.php?';
    }else{
        $url_path = SITE_URL.'api/apipost.php';
    }
    $getdata = http_build_query($arr['params']);
    $opts = array('http' =>
     array(
        'method'  => $arr['call'],
        'header' => array( 
            'Content-Type: application/x-www-form-urlencoded',
            "Authorization: ".currentusertoken() 
        ), 
        'content' => $getdata
        )
    );
    //echo "<pre>";    print_r($opts);exit;
    $context  = stream_context_create($opts);
    if($arr['call'] == 'GET')
    {
        $result = file_get_contents($url_path.$getdata, false, $context);
    }else{
        $result = file_get_contents($url_path, false, $context);
    }
    return $result;
}

function logger($log){
    $log_file = 'logs/'.date('Y-m-d').'-'.'log.txt';
    $basepath = $_SERVER['DOCUMENT_ROOT'].'/'.basename(__DIR__).'/' ;
    $complete_path_log_file = $basepath.$log_file;
    if(!file_exists($log_file)){
        
        $file_handle = fopen($basepath.$log_file, 'w');
    }

    $ip = $_SERVER['REMOTE_ADDR']; //client IP
    $time = date('Y-m-d H:i',time());

    $contents = file_get_contents($complete_path_log_file);
    $contents .= "$ip\t$time\t$log\r";
    
    file_put_contents($complete_path_log_file,$contents);
}


?>
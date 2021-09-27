<?php
date_default_timezone_set('Asia/Kolkata');

ini_set('display_errors', 'On');

ini_set('log_errors', 'On');

define('SITE_URL','http://localhost/devs1/');

define('SCHOOL_NAME','INSPINIA');

define('SCHOOL_TOKEN','$CH0O1');

include('dbconfig.php');

function insertQuery($query,$table){

	include('dbconfig.php');

	$query['reg_date'] = date('Y-m-d H:i:s');

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

	$query['reg_date'] = date('Y-m-d H:i:s');

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

function currentusertoken($usersid = null) {
   $usersid = !empty($usersid) ? $usersid : current_userid();
   $plaintoken = SCHOOL_TOKEN.'-'.$usersid;
   return hash('sha256', $plaintoken);
}

function apicall($arr)
{
    
    if($arr['call'] == 'GET')
    {
        $url_path = 'http://localhost/devs1/api/apiget.php?';
    }else{
        $url_path = 'http://localhost/devs1/api/apipost.php';
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
    
    $context  = stream_context_create($opts);
    if($arr['call'] == 'GET')
    {
        $result = file_get_contents($url_path.$getdata, false, $context);
    }else{
        $result = file_get_contents($url_path, false, $context);
    }
    return $result;
}
?>
<!DOCTYPE html>
<html>
<?php
session_start();

require_once('../common.php');

$userid = current_userid(); 
if(empty($userid)){
	header("Location: ../login.php");
}
$pagetitle = 'Schools';
$subtitle = 'Add Subject';
$result = [];
$toggleaddbutton = 1;
$breadarrow = 1;

if(!empty($_POST['addsubject']) &&  $_POST['randcheck'] == $_SESSION['rand'])
{
   
    $addarr['call'] = 'POST';
    $addarr['params'] = ['usersid' => $userid
    ,'action' => 'add-subject'
    ,'school_id' => getUserDetails('school_id')
    ,'subject_name' => $_POST['subject_name']
    ,'status' => 1
    ];
   $result = json_decode(apicall($addarr),true);
   $url = SITE_URL."school/subject-mobile.php";
   $result_status = $result['status'];
   $result_message = $result['message'];
   $res = "<form name='fr' action='$url' method='POST'>
   <input type='hidden' name='resstatus' value='$result_status'>
   <input type='hidden' name='resmessage' value='$result_message'>
   </form>
   <script >
   document.fr.submit();
   </script>";
   die($res);

}



?>

<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?= SCHOOL_NAME; ?> | <?= $pagetitle; ?></title>
        <?php require_once('../layout/headerscripts.php'); ?>

</head>

<body>
    <div id="wrapper">
        <?php require_once('../layout/sidebar.php'); ?>

      <div id="page-wrapper" class="gray-bg">
          <?php require_once('../layout/header.php'); ?>

          <div class="wrapper wrapper-content">
          <div>
            <a href="<?= SITE_URL.'school/subject-mobile.php'?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
          </div>
          <form role="form" class="mt-4" id="addsubjectform" method="POST">
            <?php
              $rand=rand();
              $_SESSION['rand']=$rand;
            ?>
                <div class="form-group"><input type="text" id="subject_name" name="subject_name" 
                placeholder="Enter Subject Name" class="form-control" autocomplete="off"></div>
                <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
                <input type="hidden" class="form-control" id="addsubject" name="addsubject" value="1" />

                
          </form>
          <div>
                    <button class="btn btn-sm btn-primary float-right m-t-n-xs" type="submit" id="addsubjectid" ><strong>Add Subject</strong></button>
                </div>
       </div>

          <?php require_once('../layout/footer.php');?>
      </div>
      <!-- page wrapper -->

    </div>
    <!-- wrapper -->
 <?php require_once('../layout/footerscripts.php'); ?>
<script>
$("#addsubjectid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addsubjectid').find('input').each(function(){
            if($(this).prop('required')){
                
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});

		if(err_value == 0)
		{
			$("#addsubjectid").hide();
			$("#addsubjectform").submit();
           	
		}
		
	});
  </script>
</body>
</html>
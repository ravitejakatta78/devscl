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
$subtitle = 'Subjects List';
$toggleaddbutton = 1;
$breadarrow = 1;
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
          <?php require_once('../layout/header.php'); 
          $arr['call'] = 'GET';
          $arr['params'] = ['usersid' => $userid,'school_id' => getUserDetails('school_id'),'action' => 'get-subjects-list'];
          $subjects_list_json = apicall($arr);
          $subjects_list_arr = json_decode($subjects_list_json,true);
          $subjects_list = $subjects_list_arr['subject_list'];
?>

          <div class="wrapper wrapper-content">
          <?php if(!empty($_POST['resstatus'])){

                            $notification_class = ($_POST['resstatus'] == '1')  ? 'success' : 'danger'; 
                            ?> 
                            <div class="alert alert-<?= $notification_class; ?> alert-dismissable" id="success-alert">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?= $_POST['resmessage']; ?>. 
                    </div>        
                            <?php  } ?>
                            
          <div>
          <a href="<?= SITE_URL.'school/add-subject-mobile.php'?>"><button class="btn btn-sm btn-primary float-right m-t-n-xs" style="float:right">Add Subject</button></a>
          </div>
		  <table class="table mt-5">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Subject Name</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php for($i=0;$i<count($subjects_list);$i++) { ?>
      <tr>
      <th scope="row"><?= ($i+1); ?></th>
      <td><?= $subjects_list[$i]['subject_name']; ?></td>
      <td>
        <label class="switch">
					  <input type="checkbox" id="subject_status"  <?php if($subjects_list[$i]['status'] == '1' ) { echo "checked"; } ?> 
            onchange="changeSubjectStatus(<?= $userid; ?>,<?= $subjects_list[$i]['id']; ?>)">
					  <span class="slider round"></span>
				</label>
      </td>
    </tr>
    <?php } ?> 
  </tbody>
</table>
          </div>

          <?php require_once('../layout/footer.php');?>
      </div>
      <!-- page wrapper -->

    </div>
    <!-- wrapper -->
 <?php require_once('../layout/footerscripts.php'); ?>

</body>
</html>

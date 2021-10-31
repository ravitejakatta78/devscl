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
$subtitle = 'Schools List';
$result = [];
$toggleaddbutton = 1;
$breadcrumbtitles = 1;

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
        require_once('../layout/breadcrumb.php'); 
        
           ?>
        <div class="wrapper wrapper-content ">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-content">

                    

                    </div>
                </div>
            </div>
            </div>
        </div>          


        <?php require_once('../layout/footer.php');?>
    </div>
    <!-- page wrapper -->

    </div>
    <!-- wrapper -->
<?php require_once('../layout/footerscripts.php'); ?>
</body>
</html>

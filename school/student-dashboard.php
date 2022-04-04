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
$toggleaddbutton = 1;
$result = [];
$school_id = user_details($userid,'school_id');
$user_name = user_details($userid,'user_name');
$students = runQuery("select s.first_name,s.last_name,s.address,p.parent_name,p.email,p.phone from 
students as s inner join parents as p on s.parent_id = p.id where s.id = '4'");
//echo "<pre>";print_r($students);exit;
$feeDetails = runloopQuery("select * from student_paid_fee where student_id = '4'");
//echo "<pre>";print_r($feeDetails);exit;
?>
<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>INSPINIA | Dashboard</title>
        <?php require_once('../layout/headerscripts.php'); ?>
        <link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="../assets/css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'>

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">



</head>

<body>
    <div id="wrapper">
        <?php require_once('../layout/sidebar.php'); ?>
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <?php require_once('../layout/header.php'); ?>
            <div class="wrapper wrapper-content ">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox ">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="widget lazur-bg p-lg">
                                                    <h2>
                                                        Parent : <?php echo $students['parent_name']; ?>
                                                    </h2>
                                                    <ul class="list-unstyled m-t-md">
                                                        <li>
                                                            <span class="fa fa-envelope m-r-xs"></span>
                                                            <label>Email:</label>
                                                            <?php echo $students['email']; ?> 
                                                        </li>
                                                        <li>
                                                            <span class="fa fa-home m-r-xs"></span>
                                                            <label>Address:</label>
                                                            <?php echo $students['address']; ?>                                                            
                                                        </li>
                                                        <li>
                                                            <span class="fa fa-phone m-r-xs"></span>
                                                            <label>Contact:</label>
                                                            <?php echo $students['phone']; ?>
                                                        </li>
                                                    </ul>    
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="ibox-content m-b-sm border-bottom">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <select>
                                                                <option>All</option>
                                                                <option>Exam 1</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <table class="table">
                                                        <tr>
                                                            <th>Subject 1</th>
                                                            <th>Subject 2</th>
                                                            <th>Subject 3</th>
                                                            <th>Subject 4</th>
                                                            <th>Subject 5</th>
                                                            <th>Subject 6</th>
                                                        </tr>
                                                        <tr>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                        </tr>
                                                        <tr>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                        </tr>
                                                        <tr>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                        </tr>
                                                        <tr>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                        </tr>
                                                        <tr>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                            <td>90</td>
                                                        </tr>

                                                    </table>


                                                </div>

                                            </div>
                                            <div class="col-md-12">
                                                <div class="ibox ">
                                                    <div class="ibox-title">
                                                        <h5>Exam Line Chart </h5>
                                                        <div class="ibox-tools">
                                                            <a class="collapse-link">
                                                                <i class="fa fa-chevron-up"></i>
                                                            </a>
                                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                                <i class="fa fa-wrench"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-user">
                                                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                                                </li>
                                                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                                                </li>
                                                            </ul>
                                                            <a class="close-link">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="ibox-content">
                                                        <div id="morris-marks-chart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="ibox-content text-center lazur-bg ">
                                                    <h1><?php echo $students['first_name']; ?> <?php echo $students['last_name']; ?></h1>
                                                    <div class="m-b-sm">
                                                        <img alt="image" class="rounded-circle" src="../assets/img/a8.jpg">
                                                    </div>
                                                    <p class="font-bold">Consectetur adipisicing</p>


                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="ibox ">
                                                    <div class="ibox-title">
                                                        <h5>Attendance </h5>
                                                        <div class="ibox-tools">
                                                            <a class="collapse-link">
                                                                <i class="fa fa-chevron-up"></i>
                                                            </a>
                                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                                <i class="fa fa-wrench"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-user">
                                                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                                                </li>
                                                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                                                </li>
                                                            </ul>
                                                            <a class="close-link">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="ibox-content">
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="ibox">
                                                    <div class="ibox-content">
                                                        <h5>Fee Details</h5>
                                                        <table class="table table-stripped small m-t-md">
                                                            <tbody>
                                                                <?php for($i=0;$i<count($feeDetails);$i++) { ?>
                                                                    <tr>
                                                                        <td class="no-borders">
                                                                        <i class="fa fa-circle text-danger"></i>
                                                                        </td>
                                                                        <td  class="no-borders">
                                                                        <?php echo $feeDetails[$i]['amount']; ?>
                                                                        </td>
                                                                        <td>
                                                                        <?php echo $feeDetails[$i]['paid_date']; ?>
                                                                        </td>
                                                                        <td>
                                                                        <?php echo FEE_TYPE[$feeDetails[$i]['fee_type']]; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>






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
    <script src="../assets/js/plugins/fullcalendar/moment.min.js"></script>
    <?php require_once('../layout/footerscripts.php'); ?>
    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Full Calendar -->
    <script src="../assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
    <!-- Morris -->
    <script src="../assets/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="../assets/js/plugins/morris/morris.js"></script>
    <script src="../assets/js/demo/morris-demo.js"></script>

    <script>

        $(document).ready(function() {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

            /* initialize the external events
             -----------------------------------------------------------------*/


            $('#external-events div.external-event').each(function() {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1111999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });


            /* initialize the calendar
             -----------------------------------------------------------------*/
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                drop: function() {
                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                events: [
                    {
                        title: 'All Day Event',
                        start: new Date(y, m, 1)
                    },
                    {
                        title: 'Long Event',
                        start: new Date(y, m, d-5),
                        end: new Date(y, m, d-2)
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d-3, 16, 0),
                        allDay: false
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d+4, 16, 0),
                        allDay: false
                    },
                    {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false
                    },
                    {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        allDay: false
                    },
                    {
                        title: 'Birthday Party',
                        start: new Date(y, m, d+1, 19, 0),
                        end: new Date(y, m, d+1, 22, 30),
                        allDay: false
                    },
                    {
                        title: 'Click for Google',
                        start: new Date(y, m, 28),
                        end: new Date(y, m, 29),
                        url: 'http://google.com/'
                    }
                ]
            });

            Morris.Line({
                element: 'morris-marks-chart',
                data: [{ y: '2006', a: 100, b: 90 },
                    { y: '2007', a: 75, b: 65 },
                    { y: '2008', a: 50, b: 40 },
                    { y: '2009', a: 75, b: 65 },
                    { y: '2010', a: 50, b: 40 },
                    { y: '2011', a: 75, b: 65 },
                    { y: '2012', a: 100, b: 90 } ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Series A', 'Series B'],
                hideHover: 'auto',
                resize: true,
                lineColors: ['#54cdb4','#1ab394'],
            });
        });

    </script>
</body>
</html>

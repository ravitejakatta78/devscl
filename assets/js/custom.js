$(document).ready(function() {
    $('.dataTables-example').DataTable({
        pageLength: 10,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        language: { search: '', searchPlaceholder: "Search..." },
        buttons: [
            { extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
             customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ]

    });






 $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
    $("#success-alert").slideUp(500);
 });

});

function changeSubjectStatus(userid,subject_id,checkedval){
    
    var action = 'update-subject-status';
    
     $.ajax({
        url: "../ajax/commonajax.php",
        type: "POST",
        data: {usersid :userid,subject_id:subject_id,action:action},
      }).done(function(msg) {
        
      });
      
}


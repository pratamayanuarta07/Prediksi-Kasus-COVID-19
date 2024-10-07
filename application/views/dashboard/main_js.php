<script type="text/javascript">

(function () {
    'use strict';
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation');
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener(
            'submit',
            function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            },
            false);
        });
      })();
      
      
    //   $(document).ready( function () {
    //       $('#dataset').DataTable({
    //           lengthMenu: [[5, 50, 100, -1],[5, 50, 100, "All"]]
    //         });
    //     });

  //   $('.connectedSortable').sortable({
  //     placeholder         : 'sort-highlight',
  //     connectWith         : '.connectedSortable',
  //     handle              : '.card-header, .nav-tabs',
  //     forcePlaceholderSize: true,
  //     zIndex              : 999999
  //   })
  // $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')


// var timeFormat = 'DD/MM/YYYY';

// var ctx = document.getElementById('myChart').getContext('2d');
// var chart = new Chart(ctx, {
//   type: 'line',
//   data: { 
//     datasets: [s1]
//   },
//   options: {
//     responsive: true,
//     scales: {
//       xAxes: [{
//         type: 'time',
//         time:{
//           parser: timeFormat,
//           tooltipFormat: 'll'
//         },
//         scaleLabel: {
//           display:     true,
//           labelString: 'Date'
//         }
//       }],
//       yAxes: [{
//             gridLines: {
//               drawBorder: false
//             },
//             scaleLabel: {
//               display: true,
//               labelString: 'Palm oil price ($)'
//             }
//           }]
//     }
//   }
// });

</script>
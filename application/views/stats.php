<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

<div class="container">
   <div class="row" style="margin-top:60px;">
      <div class="col-md-4">
         <div class="statCart Statcolor01">
            <i class="fa fa-users" aria-hidden="true"></i>
            <h1 class="count"><?= $CustomerNumber; ?></h1><br>
            <span><?= label('Customers'); ?></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="statCart Statcolor02">
            <i class="fa fa-archive" aria-hidden="true"></i>
            <h1 class="count"><?= $ProductNumber; ?></h1><br>
            <span><?= label('Product'); ?> (<?= label('in'); ?> <?= $CategoriesNumber; ?> <?= label('Categories'); ?>)</span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="statCart Statcolor03">
            <i class="fa fa-money" aria-hidden="true"></i>
            <h2 style="display: inline"><span class="count"><?= $TodaySales; ?></span> <?= $this->setting->currency; ?></h2><br>
            <span><?= label('TodaySale'); ?></span>
         </div>
      </div>
   </div>
   <div class="col-md-2">
      <input type="submit" value="Calcular" class="cancelBtn btn btn-picker" style="margin-top: 1.8em; align-content:center;">
   </div>
   <!-- <div class="row" style="margin-top:50px;">
      <div class="col-md-8">
         <div class="statCart">
            <h3><?= label('monthlyStats'); ?></h3>
            <div style="width:100%">
               <canvas id="canvas" height="330" width="750"></canvas>
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="statCart">
            <h3><?= label('TopProducts'); ?></h3>
            <div id="canvas-holder">
               <?= count($Top5product) >= 5 ? '<canvas id="chart-area2" width="230" height="230" />' : '<h3 style="margin: 50px 0">' . label("EmptyList") . '</h3>'; ?>
            </div>
         </div> 
      </div>
   </div> -->
   <?php if (count($Top5product) >= 5) { ?>
      <div class="row" style="margin-top: 50px;">
         <div class="col-md-12">
            <div class="statCart">
               <div class="col-md-2">
                  <h4>
                     <center><?= label('TopProducts'); ?></center>
                  </h4>
               </div>
               <div class="col-md-2">
                  <h2>
                     <center><span class="label label-default" style="background-color: #F3565D;"><?= $Top5product[0]->name; ?></span></center>
                  </h2>
               </div>
               <div class="col-md-2">
                  <h2>
                     <center><span class="label label-default" style="background-color: #FC9D9B;"><?= $Top5product[1]->name; ?></span></center>
                  </h2>
               </div>
               <div class="col-md-2">
                  <h2>
                     <center><span class="label label-default" style="background-color: #FACDAE;"><?= $Top5product[2]->name; ?></span></center>
                  </h2>
               </div>
               <div class="col-md-2">
                  <h2>
                     <center><span class="label label-default" style="background-color: #9FC2C4;"><?= $Top5product[3]->name; ?></span></center>
                  </h2>
               </div>
               <div class="col-md-2">
                  <h2>
                     <center><span class="label label-default" style="background-color: #8297A8;"><?= $Top5product[4]->name; ?></span></center>
                  </h2>
               </div>
               <div style="clear:both;"></div>
            </div>
         </div>
      </div>
   <?php } ?>
   <!-- ************************************************************************************************** -->

   <div class="row rangeStat" style="margin-top:50px;">
      <h3 class="col-sm-12"><?= label('Comision por Venta'); ?></h3>
      <form action="" method="get">
         <!-- formulario para calcular reportes -->
         <form action="" method="get">
            <div class="col-md-5">
               <div class="form-group">
                  <label for="waiterSelect"><?= label('Selecciona Camarero'); ?></label>
                  <select class="js-select-options form-control" id="waiterSelect" name="waiterSelect">
                     <!-- php code to select waiter id from table waiter -->
                     <option value="0"><?= label('sin Camarero'); ?></option>
                     <?php
                     $query = "SELECT * FROM zarest_waiters";
                     $conn = mysqli_connect("localhost", "root", "", "puntorestaurant");
                     $result = mysqli_query($conn, $query);
                     while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                     }
                     mysqli_close($conn);
                     ?>

                  </select>
               </div>
            </div>

            <div class="col-md-5">
               <div class="form-group">
                  <label><?= label('Comision %'); ?></label>
                  <input type="number" class="js-input-options form-control" id="waiterComision" name="waiterComision" style="height: 2em;">
               </div>
            </div>
            <div class="col-md-2">
               <input type="submit" value="Calcular" class="cancelBtn btn btn-picker" style="margin-top: 1.8em; align-content:center;">
            </div>
         </form>
   </div>

   <?php
   // si no esta vacio el select de camareros y la comision
   if (isset($_GET['waiterSelect']) && !empty($_GET['waiterSelect']) && isset($_GET['waiterComision']) && !empty($_GET['waiterComision'])) {
      $camarero = $_GET['waiterSelect'];
      $comision = $_GET['waiterComision'];

      // seleccionar la suma del atributo total de la tabla sales donde el camarero sea igual al seleccionado
      $query = "SELECT SUM(total) as total FROM zarest_sales WHERE waiter_id = '$camarero'";
      // conexion a la base de datos
      $conn = mysqli_connect("localhost", "root", "", "puntorestaurant");
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_array($result);
      $total = $row['total'];
      // total 2 decimales
      $totalD = number_format($total, 2, '.', '');
      // calcular la comision
      $comision = "" + ($total * $comision / 100);
      // mostrar solo 2 decimales
      $comision = number_format($comision, 2, '.', '');

      // lanzar una alerta en html centrada con el resultado
      echo "<div class='alert alert-success' role='alert' style='text-align: center;'>La comision es de: $comision mxn </div>";

      // generar documento pdf con la comision desde un boton en html lance alerta javascript alert
      echo "<a href='javascript:void(0)' onclick='genearPDF()'><i class='cancelBtn btn btn-picker'>Generar pdf</i></a>";

      //query para obtener el nombre del camarero
      $query1 = "SELECT name FROM zarest_waiters WHERE id = '$camarero'";
      $result1 = mysqli_query($conn, $query1);
      $row1 = mysqli_fetch_array($result1);
      $camareroName = $row1['name'];

      
      mysqli_close($conn);
   }

   ?>

   <script>
      function genearPDF() {
         var doc = new jsPDF();
         // agregar el nombre del camarero, la venta total y la comision y la fecha
         doc.text(20, 10, 'Camarero: <?php echo $camareroName; ?>');
         doc.text(20, 15, 'Venta: <?php echo $totalD; ?>');
         doc.text(20, 20, 'Comision: <?php echo $comision; ?>');
         // venta mas comision
         doc.text(20, 25, 'Fecha: <?php echo date("d/m/Y"); ?>');
         doc.save('Comision.pdf');
      }
   </script>

   <script>
      /******* Range date picker *******/
      $(function() {
         $('input[name="daterange"]').daterangepicker();
         $('input[name="daterangeP"]').daterangepicker();
         $('input[name="daterangeR"]').daterangepicker();
         var d = new Date().getFullYear();
         $('#ProductRange').val('01/01/' + d + ' - 12/31/' + d);
         $('#CustomerRange').val('01/01/' + d + ' - 12/31/' + d);
         $('#RegisterRange').val('01/01/' + d + ' - 12/31/' + d);

      });
      /************************ Chart Data *************************/
      var randomScalingFactor = function() {
         return Math.round(Math.random() * 100)
      };
      var lineChartData = {
         labels: ["<?= label('January'); ?>", "<?= label('February'); ?>", "<?= label('March'); ?>", "<?= label('April'); ?>", "<?= label('May'); ?>", "<?= label('June'); ?>", "<?= label('July'); ?>", "<?= label('August'); ?>", "<?= label('September'); ?>", "<?= label('October'); ?>", "<?= label('November'); ?>", "<?= label('December'); ?>"],
         datasets: [{
               label: "<?= label('Expences'); ?>",
               backgroundColor: "rgba(255,99,132,0.2)",
               borderColor: "#FE9375",
               pointBackgroundColor: "#FE9375",
               pointBorderColor: "#fff",
               pointHoverBackgroundColor: "#fff",
               pointHoverBorderColor: "#FE9375",
               data: [<?= $monthlyExp[0]->january; ?>, <?= $monthlyExp[0]->feburary; ?>, <?= $monthlyExp[0]->march; ?>, <?= $monthlyExp[0]->april; ?>, <?= $monthlyExp[0]->may; ?>, <?= $monthlyExp[0]->june; ?>, <?= $monthlyExp[0]->july; ?>, <?= $monthlyExp[0]->august; ?>, <?= $monthlyExp[0]->september; ?>, <?= $monthlyExp[0]->october; ?>, <?= $monthlyExp[0]->november; ?>, <?= $monthlyExp[0]->december; ?>]
            },
            {
               label: "<?= label('Revenue'); ?>",
               backgroundColor: "#2AC4C0",
               borderColor: "#26a5a2",
               pointBackgroundColor: "#2AC4C0",
               pointBorderColor: "#fff",
               pointHoverBackgroundColor: "#fff",
               pointHoverBorderColor: "#fff",
               data: [<?= $monthly[0]->january; ?>, <?= $monthly[0]->feburary; ?>, <?= $monthly[0]->march; ?>, <?= $monthly[0]->april; ?>, <?= $monthly[0]->may; ?>, <?= $monthly[0]->june; ?>, <?= $monthly[0]->july; ?>, <?= $monthly[0]->august; ?>, <?= $monthly[0]->september; ?>, <?= $monthly[0]->october; ?>, <?= $monthly[0]->november; ?>, <?= $monthly[0]->december; ?>]
            }
         ]
      }
      window.onload = function() {

         // Chart.defaults.global.gridLines.display = false;

         var ctx = document.getElementById("canvas").getContext("2d");
         window.myLine = new Chart(ctx, {
            type: 'line',
            data: lineChartData,
            options: {
               scales: {
                  xAxes: [{
                     gridLines: {
                        display: false
                     }
                  }],
                  yAxes: [{
                     gridLines: {
                        display: true
                     }
                  }]
               },
               scaleFontSize: 9,
               tooltipFillColor: "rgba(0, 0, 0, 0.71)",
               tooltipFontSize: 10,
               responsive: true
            }
         });

         /********************* pie **********************/
         <?php if (count($Top5product) >= 5) { ?>


            var pieData = {
               labels: [
                  "<?= $Top5product[0]->name; ?>",
                  "<?= $Top5product[1]->name; ?>",
                  "<?= $Top5product[2]->name; ?>",
                  "<?= $Top5product[3]->name; ?>",
                  "<?= $Top5product[4]->name; ?>"
               ],
               datasets: [{
                  data: [<?= $Top5product[0]->totalquantity; ?>, <?= $Top5product[1]->totalquantity; ?>, <?= $Top5product[2]->totalquantity; ?>, <?= $Top5product[3]->totalquantity; ?>, <?= $Top5product[4]->totalquantity; ?>],
                  backgroundColor: [
                     "#F3565D",
                     "#FC9D9B",
                     "#FACDAE",
                     "#9FC2C4",
                     "#8297A8"
                  ],
                  hoverBackgroundColor: [
                     "#3e5367",
                     "#95a5a6",
                     "#f5fbfc",
                     "#459eda",
                     "#2dc6a8"
                  ],
                  hoverBorderWidth: [5, 5, 5, 5, 5]
               }]
            };

            Chart.defaults.global.legend.display = false;

            var ctx2 = document.getElementById("chart-area2").getContext("2d");
            window.myPie = new Chart(ctx2, {
               type: 'doughnut',
               data: pieData
            });



            $('.count').each(function(index) {
               var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
               $(this).prop('count', 0).animate({
                  Counter: $(this).text()
               }, {
                  duration: 2000,
                  easing: 'swing',
                  step: function(now) {
                     $(this).text(parseFloat(now).toFixed(size));
                  }
               });
            });


         <?php } ?>
      }


      /********************************** Get repports functions ************************************/


      function getWaiterReport() {
         var waiter_id = $('#waiterSelect').find('option:selected').val();
         var Range = $('#waiterRange').val();
         var start = Range.slice(6, 10) + '-' + Range.slice(0, 2) + '-' + Range.slice(3, 5);
         var end = Range.slice(19, 23) + '-' + Range.slice(13, 15) + '-' + Range.slice(16, 18);
         // ajax delete data to database
         $.ajax({
            url: "<?php echo site_url('reports/getWaiterReport') ?>/",
            type: "POST",
            data: {
               waiter_id: waiter_id,
               start: start,
               end: end
            },
            success: function(data) {
               $('#statsSection').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert('Error deleting data');
            }
         });
      }

      function getCustomerReport() {
         var client_id = $('#customerSelect').find('option:selected').val();
         var Range = $('#CustomerRange').val();
         var start = Range.slice(6, 10) + '-' + Range.slice(0, 2) + '-' + Range.slice(3, 5);
         var end = Range.slice(19, 23) + '-' + Range.slice(13, 15) + '-' + Range.slice(16, 18);
         // ajax delete data to database
         $.ajax({
            url: "<?php echo site_url('reports/getCustomerReport') ?>/",
            type: "POST",
            data: {
               client_id: client_id,
               start: start,
               end: end
            },
            success: function(data) {
               $('#statsSection').html(data);
               $('#stats').modal('show');
               var table = $('#Table').DataTable({
                  dom: 'T<"clear">lfrtip',
                  tableTools: {
                     'bProcessing': true
                  }
               });
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert("error");
            }
         });

      }

      function getProductReport() {
         var product_id = $('#productSelect').find('option:selected').val();
         var Range = $('#ProductRange').val();
         var start = Range.slice(6, 10) + '-' + Range.slice(0, 2) + '-' + Range.slice(3, 5);
         var end = Range.slice(19, 23) + '-' + Range.slice(13, 15) + '-' + Range.slice(16, 18);
         // ajax set data to database
         $.ajax({
            url: "<?php echo site_url('reports/getProductReport') ?>/",
            type: "POST",
            data: {
               product_id: product_id,
               start: start,
               end: end
            },
            success: function(data) {
               $('#statsSection').html(data);
               $('#stats').modal('show');
               var table = $('#Table').DataTable({
                  dom: 'T<"clear">lfrtip',
                  tableTools: {
                     'bProcessing': true
                  }
               });
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert("error");
            }
         });
      }

      function getRegisterReport() {
         var store_id = $('#StoresSelect').find('option:selected').val();
         var Range = $('#RegisterRange').val();
         var start = Range.slice(6, 10) + '-' + Range.slice(0, 2) + '-' + Range.slice(3, 5);
         var end = Range.slice(19, 23) + '-' + Range.slice(13, 15) + '-' + Range.slice(16, 18);
         // ajax set data to database
         $.ajax({
            url: "<?php echo site_url('reports/getRegisterReport') ?>/",
            type: "POST",
            data: {
               store_id: store_id,
               start: start,
               end: end
            },
            success: function(data) {
               $('#statsSection').html(data);
               $('#stats').modal('show');
               var table = $('#Table').DataTable({
                  dom: 'T<"clear">lfrtip',
                  tableTools: {
                     'bProcessing': true
                  }
               });
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert("error");
            }
         });
      }

      function getStockReport() {
         var stock_id = $('#StockSelect').find('option:selected').val();
         // ajax set data to database
         $.ajax({
            url: "<?php echo site_url('reports/getStockReport') ?>/",
            type: "POST",
            data: {
               stock_id: stock_id
            },
            success: function(data) {
               $('#statsSection').html(data);
               $('#stats').modal('show');
               var table = $('#Table').DataTable({
                  dom: 'T<"clear">lfrtip',
                  tableTools: {
                     'bProcessing': true
                  }
               });
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert("error");
            }
         });
      }

      function getyearstats(direction) {
         var currentyear = parseInt($('.statYear').text());
         var year = direction === 'next' ? currentyear - 1 : currentyear + 1;

         $.ajax({
            url: "<?php echo site_url('reports/getyearstats') ?>/" + year,
            type: "POST",
            success: function(data) {
               $('#statyears').html(data);
               $('.statYear').text(year);
               $('[data-toggle="tooltip"]').tooltip();
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert("error");
            }
         });
      }

      function RegisterDetails(id) {
         $.ajax({
            url: "<?php echo site_url('reports/RegisterDetails') ?>/" + id,
            type: "POST",
            success: function(data) {
               $('#RegisterDetails').html(data);
               $('#stats').modal('hide');
               $('#RegisterDetail').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
               alert("error");
            }
         });
      }

      function CloseRegisterDetails() {
         $('#RegisterDetail').modal('hide');
         $('#stats').modal('show');
      }

      function delete_register(id) {
         swal({
               title: '<?= label("Areyousure"); ?>',
               text: '<?= label("Deletemessage"); ?>',
               type: "warning",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: '<?= label("yesiam"); ?>',
               closeOnConfirm: false
            },
            function() {
               $.ajax({
                  url: "<?php echo site_url('reports/delete_register') ?>/" + id,
                  type: "POST",
                  error: function(jqXHR, textStatus, errorThrown) {
                     alert("error");
                  }
               });
               $('#stats').modal('hide');
               swal('<?= label("Deleted"); ?>', '<?= label("Deletedmessage"); ?>', "success");
            });
      }
   </script>

   <!-- Modal stats -->
   <div class="modal fade" id="stats" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document" id="statsModal">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="stats"><?= label("Stats"); ?></h4>
            </div>
            <div class="modal-body" id="modal-body">
               <div id="statsSection">
                  <!-- stats goes here -->
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal"><?= label("Close"); ?></button>
            </div>
         </div>
      </div>
   </div>
   <!-- /.Modal -->

   <!-- Modal register -->
   <div class="modal fade" id="RegisterDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel"><?= label("RegisterDetails"); ?></h4>
            </div>
            <div class="modal-body">
               <div id="RegisterDetails">
                  <!-- close register detail goes here -->
               </div>
            </div>
            <div class="modal-footer">
               <a href="javascript:void(0)" onclick="CloseRegisterDetails()" class="btn btn-orange col-md-12 flat-box-btn"><?= label("Return"); ?></a>
            </div>
         </div>
      </div>
   </div>
   <!-- /.Modal -->
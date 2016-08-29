<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Pernah Imunisasi</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Lengkap</td>
        <td align="right"><a onClick="getList('<?php echo "Lengkap"?>')" href="#tampildata"><?php echo $lengkap;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lengkap"?>')" href="#tampildata"><?php echo ($lengkap>0) ? number_format($lengkap/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Tidak Tahu</td>
        <td align="right"><a onClick="getList('<?php echo "Tidak Tahu"?>')" href="#tampildata"><?php echo $tidaktahu;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Tidak Tahu"?>')" href="#tampildata"><?php echo ($tidaktahu>0) ? number_format($tidaktahu/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Lengkap Sesuai Umur</td>
        <td align="right"><a onClick="getList('<?php echo "Lengkap Sesuai Umur"?>')" href="#tampildata"><?php echo $lengkapsesuaiumur;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lengkap Sesuai Umur"?>')" href="#tampildata"><?php echo ($lengkapsesuaiumur>0) ? number_format($lengkapsesuaiumur/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Tidak Lengkap</td>
        <td align="right"><a onClick="getList('<?php echo "Tidak Lengkap"?>')" href="#tampildata"><?php echo $tidaklengkap;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Tidak Lengkap"?>')" href="#tampildata"><?php echo ($tidaklengkap>0) ? number_format($tidaklengkap/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td style="font-weight:bold">Total</td>
        <td style="font-weight:bold" align="right"><?php echo $jumlahorang; ?></td>
        <td style="font-weight:bold" align="right"><?php echo ($jumlahorang>0) ? $jumlahorang/$jumlahorang*100 : 0; echo " %";?></td>
      </tr>
    </table>
  </div>
  <div class="col-md-6">
    <div class="row" id="row1">
      <div class="chart">
        <canvas id="pieChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
      </div>
    </div>
  </div>
  </div>
  <div class="row"> 
    <div class="col-md-7"></div>
    <div class="col-md-5">
      <div class="col-md-6">
          <div class="bux"></div> &nbsp; <label>Lengkap</label>
      </div>
      <div class="col-md-6">
          <div class="bux1"></div> &nbsp; <label>Tidak Tahu</label>
      </div>
      <div class="col-md-6">
          <div class="bux2"></div> &nbsp; <label>Lengkap Sesuai Umur</label>
      </div>
      <div class="col-md-6">
          <div class="bux3"></div> &nbsp; <label>Tidak Lengkap</label>
      </div>
    </div>
  </div>    
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #0066FF;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #0DCF0D;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #00FF7F;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #85ADAD;
        margin: 0;
        float: left;
      }
      
</style>
<?php // print_r($bar);?>
<script>
  $(function () { 
    
    //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
            
            echo "
              {
              value: ";echo number_format(($lengkap>0) ? $lengkap/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Lengkap'."\"
              },
              {
              value: ";echo number_format(($tidaktahu>0) ? $tidaktahu/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Tidak Tahu'."\"
              },
              {
              value: ";echo number_format(($lengkapsesuaiumur>0) ? $lengkapsesuaiumur/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Lengkap Sesuai Usia'."\"
              },
              
              {
              value: ";echo number_format(($tidaklengkap>0) ? $tidaklengkap/$jumlahorang*100:0,2).",
              color: \"".'#85ADAD'."\",
              highlight: \"".'#85ADAD'."\",
              label: \"".'Tidak Lengkap'."\"
              }"; 
            ?>

        ];
        var pieOptions = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 40, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: false,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        pieChart.Doughnut(PieData, pieOptions);
  });
</script>
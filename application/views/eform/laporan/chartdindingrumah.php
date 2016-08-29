<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Jenis Dinding Rumah</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Tembok</td>
        <td align="right"><a onClick="getList('<?php echo "Tembok"?>')" href="#tampildata"><?php echo $tembok;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Tembok"?>')" href="#tampildata"><?php echo ($tembok>0) ? number_format($tembok/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Kayu / Seng</td>
        <td align="right"><a onClick="getList('<?php echo "Kayu / Seng"?>')" href="#tampildata"><?php echo $kayu;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Kayu / Seng"?>')" href="#tampildata"><?php echo ($kayu>0) ? number_format($kayu/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Bambu</td>
        <td align="right"><a onClick="getList('<?php echo "Bambu"?>')" href="#tampildata"><?php echo $bambu;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Bambu"?>')" href="#tampildata"><?php echo ($bambu>0) ? number_format($bambu/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Lainnya</td>
        <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo $lainnya;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo ($lainnya>0) ? number_format($lainnya/$jumlahorang*100,2):0; echo " %";?></a></td>
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
  <div class="row"> 
        <div class="col-md-3">
            <div class="bux"></div> &nbsp; <label>Tembok</label>
        </div>
        <div class="col-md-3">
            <div class="bux1"></div> &nbsp; <label>Kayu / Seng</label>
        </div>
        <div class="col-md-3">
            <div class="bux2"></div> &nbsp; <label>Bambu</label>
        </div>
        <div class="col-md-3">
            <div class="bux3"></div> &nbsp; <label>Lainnya</label>
        </div>
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
              value: ";echo number_format(($tembok>0) ? $tembok/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Tembok'."\"
              },
              {
              value: ";echo number_format(($kayu>0) ? $kayu/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Kayu / Seng'."\"
              },
              {
              value: ";echo number_format(($bambu>0) ? $bambu/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Bambu'."\"
              },
              {
              value: ";echo number_format(($lainnya>0) ? $lainnya/$jumlahorang*100:0,2).",
              color: \"".'#85ADAD'."\",
              highlight: \"".'#85ADAD'."\",
              label:  \"".'Lainnya'."\"
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
<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Status Gizi</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Gizi Baik</td>
        <td align="right"><a onClick="getList('<?php echo "Gizi Baik"?>')" href="#tampildata"><?php echo $baik;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Gizi Baik"?>')" href="#tampildata"><?php echo ($baik>0) ? number_format($baik/$total*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Gizi Kurang</td>
        <td align="right"><a onClick="getList('<?php echo "Gizi Kurang"?>')" href="#tampildata"><?php echo $kurang;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Gizi Kurang"?>')" href="#tampildata"><?php echo ($kurang>0) ? number_format($kurang/$total*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Gizi Lebih</td>
        <td align="right"><a onClick="getList('<?php echo "Gizi Lebih"?>')" href="#tampildata"><?php echo $lebih;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Gizi Lebih"?>')" href="#tampildata"><?php echo ($lebih>0) ? number_format($lebih/$total*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Lain-lain</td>
        <td align="right"><a onClick="getList('<?php echo "Lain-lain"?>')" href="#tampildata"><?php echo ($total-($baik+$kurang+$lebih));?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lain-lain"?>')" href="#tampildata"><?php echo (($total-($baik+$kurang+$lebih))>0) ? number_format(($total-($baik+$kurang+$lebih))/$total*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td style="font-weight:bold">Total</td>
        <td style="font-weight:bold" align="right"><?php echo $total; ?></td>
        <td style="font-weight:bold" align="right">100 %</td>
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
          <div class="bux"></div> &nbsp; <label>Gizi Baik</label>
      </div>
      <div class="col-md-6">
          <div class="bux1"></div> &nbsp; <label>Gizi Kurang</label>
      </div>
      <div class="col-md-6">
          <div class="bux2"></div> &nbsp; <label>Gizi Lebih</label>
      </div>
      <div class="col-md-6">
          <div class="bux3"></div> &nbsp; <label>Lain-lain</label>
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
              value: ";echo number_format(($baik>0) ? number_format($baik/$total*100,2):0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Gizi Baik'."\"
              },
              {
              value: ";echo number_format(($kurang>0) ? number_format($kurang/$total*100,2):0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Gizi Kurang'."\"
              },
              {
              value: ";echo number_format(($lebih>0) ? number_format($lebih/$total*100,2):0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Gizi Lebih'."\"
              },
              {
              value: ";echo number_format((($total-($baik+$kurang+$lebih))>0) ? number_format(($total-($baik+$kurang+$lebih))/$total*100,2):0,2).",
              color: \"".'#85ADAD'."\",
              highlight: \"".'#85ADAD'."\",
              label: \"".'Lain-lain'."\"
              }
              "; 
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
<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Distribusi Usia Mulai Merokok</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Remaja (13-20)</td>
        <td align="right"><a onClick="getList('<?php echo "Remaja (13-20)"?>')" href="#tampildata"><?php echo $remaja;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Remaja (13-20)"?>')" href="#tampildata"><?php echo ($remaja>0) ? number_format($remaja/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Dewasa (21-40)</td>
        <td align="right"><a onClick="getList('<?php echo "Dewasa (21-40)"?>')" href="#tampildata"><?php echo $dewasa;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Dewasa (21-40)"?>')" href="#tampildata"><?php echo ($dewasa>0) ? number_format($dewasa/$jumlahorang*100,2):0; echo " %";?></a></td>
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
          <div class="bux"></div> &nbsp; <label>Remaja (13-20)</label>
      </div>
      <div class="col-md-6">
          <div class="bux1"></div> &nbsp; <label>Dewasa (21-40)</label>
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
              value: ";echo number_format(($remaja>0) ? $remaja/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Remaja (13-20)'."\"
              },
              {
              value: ";echo number_format(($dewasa>0) ? $dewasa/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Dewasa (21-40)'."\"
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
<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Jenis Atap Rumah</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Daun / Rumbia</td>
        <td align="right"><a onClick="getList('<?php echo "Daun / Rumbia"?>')" href="#tampildata"><?php echo $daun;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Daun / Rumbia"?>')" href="#tampildata"><?php echo ($daun>0) ? number_format($daun/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Seng / Asbes</td>
        <td align="right"><a onClick="getList('<?php echo "Seng / Asbes"?>')" href="#tampildata"><?php echo $seng;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Seng / Asbes"?>')" href="#tampildata"><?php echo ($seng>0) ? number_format($seng/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Genteng / Sirap</td>
        <td align="right"><a onClick="getList('<?php echo "Genteng / Sirap"?>')" href="#tampildata"><?php echo $genteng;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Genteng / Sirap"?>')" href="#tampildata"><?php echo ($genteng>0) ? number_format($genteng/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Lainnya</td>
        <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo $lainnya;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo ($lainnya>0) ? number_format($lainnya/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td style="font-weight:bold" >Total</td>
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
    <div class="col-md-5"></div>
    <div class="col-md-7">
      <div class="col-md-3">
          <div class="bux"></div> &nbsp; <label>Daun / Rumbia</label>
      </div>
      <div class="col-md-3">
          <div class="bux1"></div> &nbsp; <label>Seng / Asbes</label>
      </div>
      <div class="col-md-3">
          <div class="bux2"></div> &nbsp; <label>Genteng / Sirap</label>
      </div>
      <div class="col-md-3">
          <div class="bux3"></div> &nbsp; <label>Lainnya</label>
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
              value: ";echo number_format(($daun>0) ? $daun/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Daun / Rumbia'."\"
              },
              {
              value: ";echo number_format(($seng>0) ? $seng/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Seng / Asbes'."\"
              },
              {
              value: ";echo number_format(($genteng>0) ? $genteng/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Genteng / Sirap'."\"
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
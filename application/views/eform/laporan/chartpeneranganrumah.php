<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Jenis Penerangan Rumah</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Listrik</td>
        <td align="right"><a onClick="getList('<?php echo "Listrik"?>')" href="#tampildata"><?php echo $listrik;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Listrik"?>')" href="#tampildata"><?php echo ($listrik>0) ? number_format($listrik/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Genset / Diesel</td>
        <td align="right"><a onClick="getList('<?php echo "Genset / Diesel"?>')" href="#tampildata"><?php echo $genset;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Genset / Diesel"?>')" href="#tampildata"><?php echo ($genset>0) ? number_format($genset/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Lampu Minyak</td>
        <td align="right"><a onClick="getList('<?php echo "Lampu Minyak"?>')" href="#tampildata"><?php echo $minyak;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Lampu Minyak"?>')" href="#tampildata"><?php echo ($minyak>0) ? number_format($minyak/$jumlahorang*100,2):0; echo " %";?></a></td>
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
    </div>
  </div>
  <div class="row"> 
    <div class="col-md-5"></div>
    <div class="col-md-7">
      <div class="col-md-3">
          <div class="bux"></div> &nbsp; <label>Listrik</label>
      </div>
      <div class="col-md-3">
          <div class="bux1"></div> &nbsp; <label>Genset / Diesel</label>
      </div>
      <div class="col-md-3">
          <div class="bux2"></div> &nbsp; <label>Lampu Minyak</label>
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
              value: ";echo number_format(($listrik>0) ? $listrik/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Lisktrik'."\"
              },
              {
              value: ";echo number_format(($genset>0) ? $genset/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'Genset / Diesel'."\"
              },
              {
              value: ";echo number_format(($minyak>0) ? $minyak/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Lampu Minyak'."\"
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
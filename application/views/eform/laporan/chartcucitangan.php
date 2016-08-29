<table class="table table-bordered table-hover">
  <tr>
    <th>Kebiasaan Mencuci Tangan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <tr>
    <td><?php echo "Sebelum menyiapkan makanan"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Sebelum menyiapkan makanan"?>')" href="#tampildata"><?php echo (isset($pakaisabun)) ? $pakaisabun:0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Sebelum menyiapkan makanan"?>')" href="#tampildata"><?php echo ($pakaisabun>0) ? number_format($pakaisabun/$totalorang*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Setiap kali tangan kotor (pegang uang, binatang, berkebun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Setiap kali tangan kotor (pegang uang, binatang, berkebun)"?>')" href="#tampildata"><?php echo (isset($tangankotor)) ? $tangankotor : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Setiap kali tangan kotor (pegang uang, binatang, berkebun)"?>')" href="#tampildata"><?php echo ($tangankotor>0) ? number_format($tangankotor/$totalorang*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Setelah buang air besar"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Setelah buang air besar"?>')" href="#tampildata"><?php echo (isset($bab)) ? $bab : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Setelah buang air besar"?>')" href="#tampildata"><?php echo ($bab>0) ? number_format($bab/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Setelah mencebok bayi"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Setelah mencebok bayi"?>')" href="#tampildata"><?php echo (isset($cebok)) ? $cebok : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Setelah mencebok bayi"?>')" href="#tampildata"><?php echo ($cebok>0) ? number_format($cebok/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Setelah menggunakan pestisida/insektisida"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Setelah menggunakan pestisida/insektisida"?>')" href="#tampildata"><?php echo (isset($pestisida)) ? $pestisida : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Setelah menggunakan pestisida/insektisida"?>')" href="#tampildata"><?php echo ($pestisida>0) ? number_format($pestisida/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Sebelum menyusui bayi"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Sebelum menyusui bayi"?>')" href="#tampildata"><?php echo (isset($menyusui)) ? $menyusui : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Sebelum menyusui bayi"?>')" href="#tampildata"><?php echo ($menyusui>0) ? number_format($menyusui/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td style="font-weight:bold" align="right"><?php echo "Total"; ?></td>
    <td style="font-weight:bold" align="right"><?php echo (isset($totalorang)) ? $totalorang : 0;?></td>
    <td style="font-weight:bold" align="right"><?php echo ($totalorang>0) ? number_format($totalorang/$totalorang*100,2): 0;echo " %";?></td>
  </tr>
  
</table>
<div class="chart">
  <canvas id="barChart" height="500" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Sebelum menyiapkan makanan</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Setiap kali tangan kotor (pegang uang, binatang, berkebun)</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>Setelah buang air besar</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Setelah mencebok bayi</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Setelah menggunakan pestisida/insektisida</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Sebelum menyusui bayi</label>
  </div>
</div>

<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #FF9E38;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #FF700A;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #FF3700;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #8B4513;
        margin: 0;
        float: left;
      }
      .bux7{
        width: 10px;
        padding: 10px;
        background-color: #191919;
        margin: 0;
        float: left;
      }
      .bux9{
        width: 10px;
        padding: 10px;
        background-color: #737373;
        margin: 0;
        float: left;
      }
      
    </style>
<script>
$(function(){
  var areaChartData = {
        labels: [<?php 
            echo "\"".str_replace(array("KEC. ","KEL. "),"", $puskesmas)."\"";
            ?>],
        datasets: [
          {
            label: "Sebelum menyiapkan makanan",
            fillColor: "#FF9E38",
            strokeColor: "#FF9E38",
            pointColor: "#FF9E38",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format(($pakaisabun>0) ? ($pakaisabun/$totalorang*100): 0,2);
                    echo "\"".$x."\"";
                  ?>]
          },{
            label: "Setiap kali tangan kotor (pegang uang, binatang, berkebun)",
            fillColor: "#FF700A",
            strokeColor: "#FF700A",
            pointColor: "#FF700A",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php  $x = number_format(($tangankotor>0) ?($tangankotor/$totalorang*100):0,2);
                          echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Setelah buang air besar",
            fillColor: "#FF3700",
            strokeColor: "#FF3700",
            pointColor: "#FF3700",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php
                      $x = number_format(($bab>0) ? ($bab/$totalorang*100) :0 ,2);
                      echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Setelah mencebok bayi",
            fillColor: "#8B4513",
            strokeColor: "#8B4513",
            pointColor: "#8B4513",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format( ($cebok>0) ? ($cebok/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Setelah menggunakan pestisida/insektisida",
            fillColor: "#191919",
            strokeColor: "#191919",
            pointColor: "#191919",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format( ($pestisida>0) ? ($pestisida/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Sebelum menyusui bayi",
            fillColor: "#737373",
            strokeColor: "#737373",
            pointColor: "#737373",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format(($menyusui>0) ? ($menyusui/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          }
        ]
      };
  //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        var barChartOptions = {
          scaleBeginAtZero: true,
          scaleShowGridLines: true,
          scaleGridLineColor: "rgba(0,0,0,.05)",
          scaleGridLineWidth: 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines: true,
          barShowStroke: true,
          barStrokeWidth: 2,
          barValueSpacing: 5,
          barDatasetSpacing: 1,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          responsive: true,
          maintainAspectRatio: false
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
});
</script>
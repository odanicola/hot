<table class="table table-bordered table-hover">
  <tr>
    <th>Alasan Tidak KB</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
  	if(isset($rows['total'])){
  ?>
  <tr>
    <td><?php echo "Sedang Hamil"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Sedang Hamil"?>')" href="#tampildata"><?php echo (isset($rows['sedanghamil'])) ? $rows['sedanghamil']:0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Sedang Hamil"?>')" href="#tampildata"><?php echo (($rows['sedanghamil'])!=0) ? number_format($rows['sedanghamil']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Setuju"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Setuju"?>')" href="#tampildata"><?php echo (isset($rows['tidaksetuju'])) ? $rows['tidaksetuju'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Setuju"?>')" href="#tampildata"><?php echo (($rows['tidaksetuju'])!=0) ? number_format($rows['tidaksetuju']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Tahu"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Tahu"?>')" href="#tampildata"><?php echo (isset($rows['tidaktahu'])) ? $rows['tidaktahu'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Tahu"?>')" href="#tampildata"><?php echo (($rows['tidaktahu'])!=0) ? number_format($rows['tidaktahu']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Takut Efek KB"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Takut Efek KB"?>')" href="#tampildata"><?php echo (isset($rows['takutefekkb'])) ? $rows['takutefekkb'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Takut Efek KB"?>')" href="#tampildata"><?php echo (($rows['takutefekkb'])!=0) ? number_format($rows['takutefekkb']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Pelayanan KB Jauh"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Pelayanan KB Jauh"?>')" href="#tampildata"><?php echo (isset($rows['pelayanankb'])) ? $rows['pelayanankb'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Pelayanan KB Jauh"?>')" href="#tampildata"><?php echo (($rows['pelayanankb'])!=0) ? number_format($rows['pelayanankb']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Mampu / Mahal"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Mampu / Mahal"?>')" href="#tampildata"><?php echo (isset($rows['mahalkb'])) ? $rows['mahalkb'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Mampu / Mahal"?>')" href="#tampildata"><?php echo (($rows['mahalkb'])!=0) ? number_format($rows['mahalkb']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Fertilasi "; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Fertilasi"?>')" href="#tampildata"><?php echo (isset($rows['fertilasi'])) ? $rows['fertilasi'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Fertilasi"?>')" href="#tampildata"><?php echo (($rows['fertilasi'])!=0) ? number_format($rows['fertilasi']/$rows['total']*100,2) :0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Lainnya"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo (isset($rows['lainnyakb'])) ? $rows['lainnyakb'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo (($rows['lainnyakb'])!=0) ? number_format($rows['lainnyakb']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td style="font-weight:bold"><?php echo "Total"; ?></td>
    <td style="font-weight:bold" align="right"><?php echo (isset($rows['total'])) ? $rows['total'] : 0;?></td>
    <td style="font-weight:bold" align="right"><?php echo (($rows['total'])!=0) ? number_format($rows['total']/$rows['total']*100,2) : 0;echo " %";?></td>
  </tr>
  <?php }else{}?>

  <?php }
 // print_r($bar);
   ?>
  
</table>
<div class="chart">
	<canvas id="barChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-3">
      <div class="bux"></div> &nbsp; <label>Sedang Hamil</label>
  </div>
  <div class="col-md-3">
      <div class="bux1"></div> &nbsp; <label>Tidak Setuju</label>
  </div>
  <div class="col-md-3">
      <div class="bux2"></div> &nbsp; <label>Tidak Tahu</label>
  </div>
  <div class="col-md-3">
      <div class="bux3"></div> &nbsp; <label>Takut Efek KB</label>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
      <div class="bux4"></div> &nbsp; <label>Pelayanan KB Jauh</label>
  </div>
  <div class="col-md-3">
      <div class="bux5"></div> &nbsp; <label>Tidak Mampu / Mahal</label>
  </div>
  <div class="col-md-3">
      <div class="bux6"></div> &nbsp; <label>Fertilasi</label>
  </div>
  <div class="col-md-3">
      <div class="bux7"></div> &nbsp; <label>Lainnya</label>
  </div>
</div>

<style type="text/css">
      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #FFFF40;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #FFC200;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #FF9E38;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #FF700A;
        margin: 0;
        float: left;
      }
      .bux4{
        width: 10px;
        padding: 10px;
        background-color: #FF3700;
        margin: 0;
        float: left;
      }
      .bux5{
        width: 10px;
        padding: 10px;
        background-color: #8B4513;
        margin: 0;
        float: left;
      }
      .bux6{
        width: 10px;
        padding: 10px;
        background-color: #191919;
        margin: 0;
        float: left;
      }
      .bux7{
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
        $i=0;
       // print_r($bar);  
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".str_replace(array("KEC. ","KEL. "),"", $row['puskesmas'])."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Infant (0-12 bulan)",
            fillColor: "#FFFF40",
            strokeColor: "#FFFF40",
            pointColor: "#FFFF40",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['sedanghamil']))  $x = (($rows['total'])!=0) ? number_format(($row['sedanghamil']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },{
            label: "Toddler  (1-3 tahun)",
            fillColor: "#FFC200",
            strokeColor: "#FFC200",
            pointColor: "#FFC200",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidaksetuju']))  $x = (($rows['total'])!=0) ? number_format(($row['tidaksetuju']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Preschool (4 tahun-5 tahun)",
            fillColor: "#FF9E38",
            strokeColor: "#FF9E38",
            pointColor: "#FF9E38",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidaktahu']))  $x = (($rows['total'])!=0) ? number_format(($row['tidaktahu']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Usia sekolah (6 tahun- 12 tahun)",
            fillColor: "#FF700A",
            strokeColor: "#FF700A",
            pointColor: "#FF700A",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['takutefekkb']))  $x = (($rows['total'])!=0) ? number_format(($row['takutefekkb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Remaja ( 13 tahun-20 tahun)",
            fillColor: "#FF3700",
            strokeColor: "#FF3700",
            pointColor: "#FF3700",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pelayanankb']))  $x = (($rows['total'])!=0) ? number_format(($row['pelayanankb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Dewasa (21 tahun-44 tahun)",
            fillColor: "#8B4513",
            strokeColor: "#8B4513",
            pointColor: "#8B4513",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['mahalkb']))  $x = (($rows['total'])!=0) ? number_format(($row['mahalkb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Prelansia (45 tahun-59 tahun)",
            fillColor: "#191919",
            strokeColor: "#191919",
            pointColor: "#191919",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['fertilasi']))  $x = (($rows['total'])!=0) ? number_format(($row['fertilasi']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Lansia (>60 tahun)",
            fillColor: "#737373",
            strokeColor: "#737373",
            pointColor: "#737373",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['lainnyakb']))  $x = (($rows['total'])!=0) ? number_format(($row['lainnyakb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
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
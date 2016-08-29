<table class="table table-bordered table-hover">
  <tr>
    <th>Usia</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
  	if(isset($rows['total'])){
  ?>
  <tr>
    <td><?php echo "Infant  (0-12 bulan)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Infant  (0-12 bulan)"?>')" href="#tampildata"><?php echo (isset($rows['jmlinfant'])) ? $rows['jmlinfant']:0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Infant  (0-12 bulan)"?>')" href="#tampildata"><?php echo (($rows['jmlinfant'])!=0) ? number_format($rows['jmlinfant']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Toddler  (1-3 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Toddler  (1-3 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmltoddler'])) ? $rows['jmltoddler'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Toddler  (1-3 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmltoddler'])!=0) ? number_format($rows['jmltoddler']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Preschool (4 tahun-5 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Preschool (4 tahun-5 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmlpreschool'])) ? $rows['jmlpreschool'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Preschool (4 tahun-5 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmlpreschool'])!=0) ? number_format($rows['jmlpreschool']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Usia sekolah (6 tahun- 12 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Usia sekolah (6 tahun- 12 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmlsekolah'])) ? $rows['jmlsekolah'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Usia sekolah (6 tahun- 12 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmlsekolah'])!=0) ? number_format($rows['jmlsekolah']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Remaja ( 13 tahun-20 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Remaja ( 13 tahun-20 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmlremaja'])) ? $rows['jmlremaja'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Remaja ( 13 tahun-20 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmlremaja'])!=0) ? number_format($rows['jmlremaja']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Dewasa (21 tahun-44 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Dewasa (21 tahun-44 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmldewasa'])) ? $rows['jmldewasa'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Dewasa (21 tahun-44 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmldewasa'])!=0) ? number_format($rows['jmldewasa']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Prelansia (45 tahun-59 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Prelansia (45 tahun-59 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmlprelansia'])) ? $rows['jmlprelansia'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Prelansia (45 tahun-59 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmlprelansia'])!=0) ? number_format($rows['jmlprelansia']/$rows['total']*100,2) :0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Lansia (>60 tahun)"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Lansia (>60 tahun)"?>')" href="#tampildata"><?php echo (isset($rows['jmllansia'])) ? $rows['jmllansia'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Lansia (>60 tahun)"?>')" href="#tampildata"><?php echo (($rows['jmllansia'])!=0) ? number_format($rows['jmllansia']/$rows['total']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td style="font-weight:bold">Total</td>
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
      <div class="bux"></div> &nbsp; <label>Infant (0-12 bulan)</label>
  </div>
  <div class="col-md-3">
      <div class="bux1"></div> &nbsp; <label>Toddler (1-3 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux2"></div> &nbsp; <label>Preschool (4 tahun-5 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux3"></div> &nbsp; <label>Usia sekolah (6 tahun- 12 tahun)</label>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
      <div class="bux4"></div> &nbsp; <label>Remaja ( 13 tahun-20 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux5"></div> &nbsp; <label>Dewasa (21 tahun-44 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux6"></div> &nbsp; <label>Prelansia (45 tahun-59 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux7"></div> &nbsp; <label>Lansia (>60 tahun)</label>
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
              if(isset($row['jmlinfant']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlinfant']/$row['total']*100),2):0;
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
              if(isset($row['jmltoddler']))  $x = (($rows['total'])!=0) ? number_format(($row['jmltoddler']/$row['total']*100),2):0;
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
              if(isset($row['jmlpreschool']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlpreschool']/$row['total']*100),2):0;
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
              if(isset($row['jmlsekolah']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlsekolah']/$row['total']*100),2):0;
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
              if(isset($row['jmlremaja']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlremaja']/$row['total']*100),2):0;
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
              if(isset($row['jmldewasa']))  $x = (($rows['total'])!=0) ? number_format(($row['jmldewasa']/$row['total']*100),2):0;
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
              if(isset($row['jmlprelansia']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlprelansia']/$row['total']*100),2):0;
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
              if(isset($row['jmllansia']))  $x = (($rows['total'])!=0) ? number_format(($row['jmllansia']/$row['total']*100),2):0;
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
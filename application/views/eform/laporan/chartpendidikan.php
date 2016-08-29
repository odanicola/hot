<table class="table table-bordered table-hover">
  <tr>
    <th>Tingkat Pendidikan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
    if(isset($rows['totalblmsekolah'])||isset($rows['totaltidaksekolah'])||isset($rows['totaltdktamatsd'])||isset($rows['totalmasihsd'])||isset($rows['totaltamatsd'])||isset($rows['totalmasihsmp'])||isset($rows['totaltamatsmp'])||isset($rows['totalmasihsma'])||isset($rows['totaltamatsma'])||isset($rows['totalmasihpt'])||isset($rows['totaltamatpt'])){
  ?>
  <tr>
    <td><?php echo "Belum Sekolah"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Belum Sekolah"?>')" href="#tampildata"><?php echo (isset($rows['blmsekolah'])) ? $rows['blmsekolah']:0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Belum Sekolah"?>')" href="#tampildata"><?php echo (isset($rows['blmsekolah'])) ? number_format($rows['blmsekolah']/$rows['totalorang']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Sekolah"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Sekolah"?>')" href="#tampildata"><?php echo (isset($rows['tidaksekolah'])) ? $rows['tidaksekolah'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Sekolah"?>')" href="#tampildata"><?php echo (isset($rows['tidaksekolah'])) ? number_format($rows['tidaksekolah']/$rows['totalorang']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Tamat SD"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Tamat SD"?>')" href="#tampildata"><?php echo (isset($rows['tdktamatsd'])) ? $rows['tdktamatsd'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Tamat SD"?>')" href="#tampildata"><?php echo (isset($rows['tdktamatsd'])) ? number_format($rows['tdktamatsd']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Masih SD"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih SD"?>')" href="#tampildata"><?php echo (isset($rows['masihsd'])) ? $rows['masihsd'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Masih SD"?>')" href="#tampildata"><?php echo (isset($rows['masihsd'])) ? number_format($rows['masihsd']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tamat SD"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat SD"?>')" href="#tampildata"><?php echo (isset($rows['tamatsd'])) ? $rows['tamatsd'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat SD"?>')" href="#tampildata"><?php echo (isset($rows['tamatsd'])) ? number_format($rows['tamatsd']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Masih SMP"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih SMP"?>')" href="#tampildata"><?php echo (isset($rows['masihsmp'])) ? $rows['masihsmp'] : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih SMP"?>')" href="#tampildata"><?php echo (isset($rows['masihsmp'])) ? number_format($rows['masihsmp']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat SMP"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat SMP"?>')" href="#tampildata"><?php echo (isset($rows['tamatsmp'])) ? $rows['tamatsmp'] : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat SMP"?>')" href="#tampildata"><?php echo (isset($rows['tamatsmp'])) ? number_format($rows['tamatsmp']/$rows['totalorang']*100,2):0;?></td>
  </tr>
  <tr>
    <td><?php echo "Masih SMA"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih SMA"?>')" href="#tampildata"><?php echo (isset($rows['masihsma'])) ? $rows['masihsma'] : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih SMA"?>')" href="#tampildata"><?php echo (isset($rows['masihsma'])) ? number_format($rows['masihsma']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat SMA"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat SMA"?>')" href="#tampildata"><?php echo (isset($rows['tamatsma'])) ? $rows['tamatsma'] : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat SMA"?>')" href="#tampildata"><?php echo (isset($rows['tamatsma'])) ? number_format($rows['tamatsma']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Masih PT/Akademi"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih PT/Akademi"?>')" href="#tampildata"><?php echo (isset($rows['masihpt'])) ? $rows['masihpt'] : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Masih PT/Akademi"?>')" href="#tampildata"><?php echo (isset($rows['masihpt'])) ? number_format($rows['masihpt']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat PT/Akademi"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat PT/Akademi"?>')" href="#tampildata"><?php echo (isset($rows['tamatpt'])) ? $rows['tamatpt'] : 0;?></td>
    <td align="right"><a onClick="getList('<?php echo "Tamat PT/Akademi"?>')" href="#tampildata"><?php echo (isset($rows['tamatpt'])) ? number_format($rows['tamatpt']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td style="font-weight:bold"><?php echo "Total"; ?></td>
    <td style="font-weight:bold" align="right"><?php echo (isset($rows['totalorang'])) ? $rows['totalorang'] : 0;?></td>
    <td style="font-weight:bold" align="right"><?php echo (isset($rows['totalorang'])) ? number_format($rows['totalorang']/$rows['totalorang']*100,2): 0;echo " %";?></td>
  </tr>
  <?php }else{}?>
  <?php }
   ?>
  
</table>
<div class="chart">
  <canvas id="barChart" height="500" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Belum Sekolah</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Tidak Sekolah</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>Tidak Tamat SD</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Masih SD</label>
  </div>
  <div class="col-md-2">
      <div class="bux4"></div> &nbsp; <label>Tamat SD</label>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux5"></div> &nbsp; <label>Masih SMP</label>
  </div>
  <div class="col-md-2">
      <div class="bux6"></div> &nbsp; <label>Tamat SMP</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Masih SMA</label>
  </div>
  <div class="col-md-2">
      <div class="bux8"></div> &nbsp; <label>Tamat SMA</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Masih PT/Akademi</label>
  </div>
  <div class="col-md-2">
      <div class="bux10"></div> &nbsp; <label>Tamat PT/Akademi</label>
  </div>
</div>

<style type="text/css">
      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #D1A3E8;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #A346D1;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #00CCF5;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #0073E4;
        margin: 0;
        float: left;
      }
      .bux4{
        width: 10px;
        padding: 10px;
        background-color: #1919D2;
        margin: 0;
        float: left;
      }
      .bux5{
        width: 10px;
        padding: 10px;
        background-color: #8CE88C;
        margin: 0;
        float: left;
      }
      .bux6{
        width: 10px;
        padding: 10px;
        background-color: #0DCF0D;
        margin: 0;
        float: left;
      }
      .bux7{
        width: 10px;
        padding: 10px;
        background-color: #FFB459;
        margin: 0;
        float: left;
      }
      .bux8{
        width: 10px;
        padding: 10px;
        background-color: #FF920D;
        margin: 0;
        float: left;
      }
      .bux9{
        width: 10px;
        padding: 10px;
        background-color: #FF4D4D;
        margin: 0;
        float: left;
      }
      .bux10{
        width: 10px;
        padding: 10px;
        background-color: #FF1919;
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
            label: "Belum Sekolah",
            fillColor: "#D1A3E8",
            strokeColor: "#D1A3E8",
            pointColor: "#D1A3E8",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['blmsekolah']))  $x = number_format(($row['blmsekolah']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },{
            label: "Tidak Sekolah",
            fillColor: "#A346D1",
            strokeColor: "#A346D1",
            pointColor: "#A346D1",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidaksekolah']))  $x = number_format(($row['tidaksekolah']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tidak Tamat SD",
            fillColor: "#00CCF5",
            strokeColor: "#00CCF5",
            pointColor: "#00CCF5",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tdktamatsd']))  $x = number_format(($row['tdktamatsd']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih SD",
            fillColor: "#0073E4",
            strokeColor: "#0073E4",
            pointColor: "#0073E4",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihsd']))  $x = number_format(($row['masihsd']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat SD",
            fillColor: "#1919D2",
            strokeColor: "#1919D2",
            pointColor: "#1919D2",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatsd']))  $x = number_format(($row['tamatsd']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih SMP",
            fillColor: "#8CE88C",
            strokeColor: "#8CE88C",
            pointColor: "#8CE88C",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihsmp']))  $x = number_format(($row['masihsmp']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat SMP",
            fillColor: "#0DCF0D",
            strokeColor: "#0DCF0D",
            pointColor: "#0DCF0D",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatsmp']))  $x = number_format(($row['tamatsmp']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih SMA",
            fillColor: "#FFB459",
            strokeColor: "#FFB459",
            pointColor: "#FFB459",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihsma']))  $x = number_format(($row['masihsma']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat SMA",
            fillColor: "#FF920D",
            strokeColor: "#FF920D",
            pointColor: "#FF920D",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatsma']))  $x = number_format(($row['tamatsma']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih PT/Akademi",
            fillColor: "#FF4D4D",
            strokeColor: "#FF4D4D",
            pointColor: "#FF4D4D",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihpt']))  $x = number_format(($row['masihpt']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat PT/Akademi",
            fillColor: "#FF1919",
            strokeColor: "#FF1919",
            pointColor: "#FF1919",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatpt']))  $x = number_format(($row['tamatpt']/$row['totalorang']*100),2);
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
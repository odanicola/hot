<table class="table table-bordered table-hover">
  <tr>
    <th>Pekerjaan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
    if(isset($rows['totalpetani'])||isset($rows['totalnelayan'])||isset($rows['totalpnstniporli'])||isset($rows['totalswasta'])||isset($rows['totalwiraswasta'])||isset($rows['totalpensiunan'])||isset($rows['totalpekerjalepas'])||isset($rows['totallainnya'])||isset($rows['totaltidakbelumkerja'])||isset($rows['totalbekerja'])||isset($rows['totalbelumkerja'])||isset($rows['totaltidakkerja'])||isset($rows['totalirt'])){
  ?>
  <tr>
    <td><?php echo "Petani"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Petani"?>')" href="#tampildata"><?php echo (isset($rows['petani'])) ? $rows['petani']:0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Petani"?>')" href="#tampildata"><?php echo (isset($rows['petani'])) ? number_format($rows['petani']/$rows['totalorang']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Nelayan"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Nelayan"?>')" href="#tampildata"><?php echo (isset($rows['nelayan'])) ? $rows['nelayan'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Nelayan"?>')" href="#tampildata"><?php echo (isset($rows['nelayan'])) ? number_format($rows['nelayan']/$rows['totalorang']*100,2) : 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "PNS / Porli / TNI"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "PNS / Porli / TNI"?>')" href="#tampildata"><?php echo (isset($rows['pnstniporli'])) ? $rows['pnstniporli'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "PNS / Porli / TNI"?>')" href="#tampildata"><?php echo (isset($rows['pnstniporli'])) ? number_format($rows['pnstniporli']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Swasta"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Swasta"?>')" href="#tampildata"><?php echo (isset($rows['swasta'])) ? $rows['swasta'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Swasta"?>')" href="#tampildata"><?php echo (isset($rows['swasta'])) ? number_format($rows['swasta']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Wiraswasta"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Wiraswasta"?>')" href="#tampildata"><?php echo (isset($rows['wiraswasta'])) ? $rows['wiraswasta'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Wiraswasta"?>')" href="#tampildata"><?php echo (isset($rows['wiraswasta'])) ? number_format($rows['wiraswasta']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Pensiunan"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Pensiunan"?>')" href="#tampildata"><?php echo (isset($rows['pensiunan'])) ? $rows['pensiunan'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Pensiunan"?>')" href="#tampildata"><?php echo (isset($rows['pensiunan'])) ? number_format($rows['pensiunan']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Pekerja Lepas"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Pekerja Lepas"?>')" href="#tampildata"><?php echo (isset($rows['pekerjalepas'])) ? $rows['pekerjalepas'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Pekerja Lepas"?>')" href="#tampildata"><?php echo (isset($rows['pekerjalepas'])) ? number_format($rows['pekerjalepas']/$rows['totalorang']*100,2):0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak / Belum Bekerja"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak / Belum Bekerja"?>')" href="#tampildata"><?php echo (isset($rows['tidakbelumkerja'])) ? $rows['tidakbelumkerja'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak / Belum Bekerja"?>')" href="#tampildata"><?php echo (isset($rows['tidakbelumkerja'])) ? number_format($rows['tidakbelumkerja']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Bekerja"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Bekerja"?>')" href="#tampildata"><?php echo (isset($rows['bekerja'])) ? $rows['bekerja'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Bekerja"?>')" href="#tampildata"><?php echo (isset($rows['bekerja'])) ? number_format($rows['bekerja']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Belum Kerja"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Belum Kerja"?>')" href="#tampildata"><?php echo (isset($rows['belumkerja'])) ? $rows['belumkerja'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Belum Kerja"?>')" href="#tampildata"><?php echo (isset($rows['belumkerja'])) ? number_format($rows['belumkerja']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Kerja"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Kerja"?>')" href="#tampildata"><?php echo (isset($rows['tidakkerja'])) ? $rows['tidakkerja'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Tidak Kerja"?>')" href="#tampildata"><?php echo (isset($rows['tidakkerja'])) ? number_format($rows['tidakkerja']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "IRT"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "IRT"?>')" href="#tampildata"><?php echo (isset($rows['irt'])) ? $rows['irt'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "IRT"?>')" href="#tampildata"><?php echo (isset($rows['irt'])) ? number_format($rows['irt']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Pedagang"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Pedagang"?>')" href="#tampildata"><?php echo (isset($rows['pedagang'])) ? $rows['pedagang'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Pedagang"?>')" href="#tampildata"><?php echo (isset($rows['pedagang'])) ? number_format($rows['pedagang']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td><?php echo "Lainnya"; ?></td>
    <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo (isset($rows['lainnya'])) ? $rows['lainnya'] : 0;?></a></td>
    <td align="right"><a onClick="getList('<?php echo "Lainnya"?>')" href="#tampildata"><?php echo (isset($rows['lainnya'])) ? number_format($rows['lainnya']/$rows['totalorang']*100,2): 0;?></a></td>
  </tr>
  <tr>
    <td style="font-weight:bold"><?php echo "Total"; ?></td>
    <td style="font-weight:bold" align="right"><?php echo (isset($rows['totalorang'])) ? $rows['totalorang'] : 0;?></td>
    <td style="font-weight:bold" align="right"><?php echo (isset($rows['totalorang'])) ? number_format($rows['totalorang']/$rows['totalorang']*100,2): 0;echo " %";?></td>
  </tr>
  <?php }else{ }?>
  <?php }
 // print_r($bar);
   ?>
</table>
<div class="chart">
  <canvas id="barChart" height="500" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Petani</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Nelayan</label>
  </div>
  <div class="col-md-3">
      <div class="bux2"></div> &nbsp; <label>PNS / Porli / TNI</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Swasta</label>
  </div>
  <div class="col-md-2">
      <div class="bux4"></div> &nbsp; <label>Wirawasta</label>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux5"></div> &nbsp; <label>Pensiunan</label>
  </div>
  <div class="col-md-2">
      <div class="bux6"></div> &nbsp; <label>Pekerja Lepas</label>
  </div>
  <div class="col-md-3">
      <div class="bux8"></div> &nbsp; <label>Tidak/Belum Bekerja</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Bekerja</label>
  </div>
  <div class="col-md-2">
      <div class="bux10"></div> &nbsp; <label>Belum Bekerja</label>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux11"></div> &nbsp; <label>Tidak Bekerja</label>
  </div>
  <div class="col-md-2">
      <div class="bux12"></div> &nbsp; <label>IRT</label>
  </div>
  <div class="col-md-3">
      <div class="bux13"></div> &nbsp; <label>Pedagang</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Lainnya</label>
  </div>
</div>

<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #056BFF;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #61C9F2;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #2EB073;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #009900;
        margin: 0;
        float: left;
      }
      .bux4{
        width: 10px;
        padding: 10px;
        background-color: #33CC33;
        margin: 0;
        float: left;
      }
      .bux5{
        width: 10px;
        padding: 10px;
        background-color: #ADEB14;
        margin: 0;
        float: left;
      }
      .bux6{
        width: 10px;
        padding: 10px;
        background-color: #FFFF40;
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
      .bux8{
        width: 10px;
        padding: 10px;
        background-color: #FFC200;
        margin: 0;
        float: left;
      }
      .bux9{
        width: 10px;
        padding: 10px;
        background-color: #FF9E38;
        margin: 0;
        float: left;
      }
      .bux10{
        width: 10px;
        padding: 10px;
        background-color: #FF700A;
        margin: 0;
        float: left;
      }
      .bux11{
        width: 10px;
        padding: 10px;
        background-color: #FF3700;
        margin: 0;
        float: left;
      }
      .bux12{
        width: 10px;
        padding: 10px;
        background-color: #8B4513;
        margin: 0;
        float: left;
      }
      .bux13{
        width: 10px;
        padding: 10px;
        background-color: #191919;
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
            label: "Petani",
            fillColor: "#056BFF",
            strokeColor: "#056BFF",
            pointColor: "#056BFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['petani']))  $x = number_format(($row['petani']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },{
            label: "Nelayan",
            fillColor: "#61C9F2",
            strokeColor: "#61C9F2",
            pointColor: "#61C9F2",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['nelayan']))  $x = number_format(($row['nelayan']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "PNS / TNI / Porli",
            fillColor: "#2EB073",
            strokeColor: "#2EB073",
            pointColor: "#2EB073",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pnstniporli']))  $x = number_format(($row['pnstniporli']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Swasta",
            fillColor: "#009900",
            strokeColor: "#009900",
            pointColor: "#009900",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['swasta']))  $x = number_format(($row['swasta']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Wirawasta",
            fillColor: "#33CC33",
            strokeColor: "#33CC33",
            pointColor: "#33CC33",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['wiraswasta']))  $x = number_format(($row['wiraswasta']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Pensiunan",
            fillColor: "#ADEB14",
            strokeColor: "#ADEB14",
            pointColor: "#ADEB14",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pensiunan']))  $x = number_format(($row['pensiunan']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Pekerja Lepas",
            fillColor: "#FFFF40",
            strokeColor: "#FFFF40",
            pointColor: "#FFFF40",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pekerjalepas']))  $x = number_format(($row['pekerjalepas']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Belum / Tidak Kerja",
            fillColor: "#FFC200",
            strokeColor: "#FFC200",
            pointColor: "#FFC200",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidakbelumkerja']))  $x = number_format(($row['tidakbelumkerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Bekerja",
            fillColor: "#FF9E38",
            strokeColor: "#FF9E38",
            pointColor: "#FF9E38",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['bekerja']))  $x = number_format(($row['bekerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Belum Kerja",
            fillColor: "#FF700A",
            strokeColor: "#FF700A",
            pointColor: "#FF700A",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['belumkerja']))  $x = number_format(($row['belumkerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tidak Kerja",
            fillColor: "#FF3700",
            strokeColor: "#FF3700",
            pointColor: "#FF3700",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidakkerja']))  $x = number_format(($row['tidakkerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "IRT",
            fillColor: "#8B4513",
            strokeColor: "#8B4513",
            pointColor: "#8B4513",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['irt']))  $x = number_format(($row['irt']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Pedagang",
            fillColor: "#191919",
            strokeColor: "#191919",
            pointColor: "#191919",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['irt']))  $x = number_format(($row['pedagang']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Lainnya",
            fillColor: "#737373",
            strokeColor: "#737373",
            pointColor: "#737373",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['lainnya']))  $x = number_format(($row['lainnya']/$row['totalorang']*100),2);
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
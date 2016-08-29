<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Status Metode KB</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>IUD</td>
        <td align="right"><a onClick="getList('<?php echo "IUD"?>')" href="#tampildata"><?php echo $iud;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "IUD"?>')" href="#tampildata"><?php echo ($iud>0) ? number_format($iud/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>MOW</td>
        <td align="right"><a onClick="getList('<?php echo "MOW"?>')" href="#tampildata"><?php echo $mow;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "MOW"?>')" href="#tampildata"><?php echo ($mow>0) ? number_format($mow/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>MOP</td>
        <td align="right"><a onClick="getList('<?php echo "MOP"?>')" href="#tampildata"><?php echo $mop;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "MOP"?>')" href="#tampildata"><?php echo ($mop>0) ? number_format($mop/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Suntik</td>
        <td align="right"><a onClick="getList('<?php echo "Suntik"?>')" href="#tampildata"><?php echo $suntik;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Suntik"?>')" href="#tampildata"><?php echo ($suntik>0) ? number_format($suntik/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Batal Pilih</td>
        <td align="right"><a onClick="getList('<?php echo "Batal Pilih"?>')" href="#tampildata"><?php echo $batalpilih;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Batal Pilih"?>')" href="#tampildata"><?php echo ($batalpilih>0) ? number_format($batalpilih/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Kondom</td>
        <td align="right"><a onClick="getList('<?php echo "Kondom"?>')" href="#tampildata"><?php echo $kondom;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Kondom"?>')" href="#tampildata"><?php echo ($kondom>0) ? number_format($kondom/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Implan</td>
        <td align="right"><a onClick="getList('<?php echo "Implan"?>')" href="#tampildata"><?php echo $implan;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Implan"?>')" href="#tampildata"><?php echo ($implan>0) ? number_format($implan/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Pil</td>
        <td align="right"><a onClick="getList('<?php echo "Pil"?>')" href="#tampildata"><?php echo $pil;?></a></td>
        <td align="right"><a onClick="getList('<?php echo "Pil"?>')" href="#tampildata"><?php echo ($pil>0) ? number_format($pil/$jumlahorang*100,2):0; echo " %";?></a></td>
      </tr>
      <tr>
        <td>Tradisional</td>
        <td align="right"><a onClick="getList('<?php echo "Tradisional"?>')" href="#tampildata"><?php echo $tradisional;?></td>
        <td align="right"><a onClick="getList('<?php echo "Tradisional"?>')" href="#tampildata"><?php echo ($tradisional>0) ? number_format($tradisional/$jumlahorang*100,2):0; echo " %";?></td>
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
          <div class="bux"></div> &nbsp; <label>IUD</label>
      </div>
      <div class="col-md-6">
          <div class="bux1"></div> &nbsp; <label>MOW</label>
      </div>
      <div class="col-md-6">
          <div class="bux2"></div> &nbsp; <label>MOP</label>
      </div>
      <div class="col-md-6">
          <div class="bux3"></div> &nbsp; <label>Suntik</label>
      </div>
      <div class="col-md-6">
          <div class="bux4"></div> &nbsp; <label>Batal Pilih</label>
      </div>
      <div class="col-md-6">
          <div class="bux5"></div> &nbsp; <label>Kondom</label>
      </div>
      <div class="col-md-6">
          <div class="bux6"></div> &nbsp; <label>Implan</label>
      </div>
      <div class="col-md-6">
          <div class="bux7"></div> &nbsp; <label>Pil</label>
      </div>
      <div class="col-md-6">
          <div class="bux8"></div> &nbsp; <label>Tradisional</label>
      </div>

    </div>
  </div>    
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #FFFF00;
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
        background-color: #85ADAD;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #0066FF;
        margin: 0;
        float: left;
      }
      .bux4{
        width: 10px;
        padding: 10px;
        background-color: #FF9E0D;
        margin: 0;
        float: left;
      }
      .bux5{
        width: 10px;
        padding: 10px;
        background-color: #5F9EAD;
        margin: 0;
        float: left;
      }
      .bux6{
        width: 10px;
        padding: 10px;
        background-color: #148FAD;
        margin: 0;
        float: left;
      }
      .bux7{
        width: 10px;
        padding: 10px;
        background-color: #7FFF00;
        margin: 0;
        float: left;
      }
      .bux8{
        width: 10px;
        padding: 10px;
        background-color: #D1D1BA;
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
              value: ";echo number_format(($iud>0) ? $iud/$jumlahorang*100:0,2).",
              color: \"".'#FFFF00'."\",
              highlight: \"".'#FFFF00'."\",
              label: \"".'IUD'."\"
              },
              {
              value: ";echo number_format(($mow>0) ? $mow/$jumlahorang*100:0,2).",
              color: \"".'#0DCF0D'."\",
              highlight: \"".'#0DCF0D'."\",
              label: \"".'MOW'."\"
              },
              {
              value: ";echo number_format(($mop>0) ? $mop/$jumlahorang*100:0,2).",
              color: \"".'#85ADAD'."\",
              highlight: \"".'#85ADAD'."\",
              label: \"".'MOP'."\"
              },
              {
              value: ";echo number_format(($suntik>0) ? $suntik/$jumlahorang*100:0,2).",
              color: \"".'#0066FF'."\",
              highlight: \"".'#0066FF'."\",
              label: \"".'Suntik'."\"
              },
              {
              value: ";echo number_format(($batalpilih>0) ? $batalpilih/$jumlahorang*100:0,2).",
              color: \"".'#FF9E0D'."\",
              highlight: \"".'#FF9E0D'."\",
              label: \"".'Batal Pilih'."\"
              },
              {
              value: ";echo number_format(($kondom>0) ? $kondom/$jumlahorang*100:0,2).",
              color: \"".'#5F9EAD'."\",
              highlight: \"".'#5F9EAD'."\",
              label: \"".'Kondom'."\"
              },
              {
              value: ";echo number_format(($implan>0) ? $implan/$jumlahorang*100:0,2).",
              color: \"".'#148FAD'."\",
              highlight: \"".'#148FAD'."\",
              label: \"".'Implan'."\"
              },
              {
              value: ";echo number_format(($pil>0) ? $pil/$jumlahorang*100:0,2).",
              color: \"".'#7FFF00'."\",
              highlight: \"".'#7FFF00'."\",
              label: \"".'PIL'."\"
              },
              {
              value: ";echo number_format(($tradisional>0) ? $tradisional/$jumlahorang*100:0,2).",
              color: \"".'#D1D1BA'."\",
              highlight: \"".'#D1D1BA'."\",
              label: \"".'Tradisional'."\"
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
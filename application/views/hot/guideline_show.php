<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<div id="popup" style="display:none;">
  <div id="popup_title">Hypertension Online Treatment</div><div id="popup_content">{popup}</div>
</div>
<div id="popup_del" style="display:none;">
  <div id="popup_title_del">Hypertension Online Treatment</div><div id="popup_content_del">{popup}</div>
</div>
<section class="content">
<form>
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>
	      <div class="box-footer">

	      <meta name="viewport" content="width=device-width, initial-scale=1.0">
			<style>

			/* styles unrelated to zoom */
			* { border:0; margin:0; padding:0; }
			p { position:absolute; top:3px; right:28px; color:#555; font:bold 13px/1 sans-serif; }

			/* these styles are for the demo, but are not required for the plugin */
			.zoom {
				display:inline-block;
				position: relative;
			}
			
			/* magnifying glass icon */
			.zoom:after {
				content:'';
				display:block; 
				width:33px; 
				height:33px; 
				position:absolute; 
				top:0;
				right:0;
				background:url("<?=base_url('public/themes/sik/dist/img/icon.png')?>");
			}

			.zoom img {
				display: block;
				width: 100%;
			    height: auto;
    		    margin: 0 auto;
    		    text-align: center;
			}

			.zoom img::selection { background-color: transparent; }

			</style>

			<span class='zoom' id='ex1'>
				<img src = "<?=base_url('public/themes/sik/dist/img/guideline.png')?>"  width='555' height='320'/>
			</span>     

	    </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid_dokter"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>

<!-- <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script> -->
<script src="<?php echo base_url(); ?>plugins/js/jquery.zoom/jquery.zoom.js"></script>
<!--<script src="<?php echo base_url(); ?>plugins/js/jquery.zoom/jquery.min.js"></script> -->

<script type="text/javascript">   
	$(document).ready(function(){
		$('#ex1').zoom();

		$("#menu_hot_guideline").addClass("active");
		$("#menu_dashboard").addClass("active");
	});

</script>
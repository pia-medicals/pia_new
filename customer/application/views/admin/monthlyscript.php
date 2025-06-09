<script src="<?php echo base_url('static/admin/js/csvExport.min.js');?>"></script>
<script type="application/javascript">
$("#export").click(function() {
  	$('#report-table').csvExport();
});
</script>

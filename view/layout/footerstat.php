<div id="delete_modal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header">
				<div class="icon-box">
					<i class="fa fa-times" aria-hidden="true"></i>
				</div>				
				<h4 class="modal-title">Are you sure?</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Cancel</button>
				<a href="#" class="delete_url"> <button type="button" class="btn btn-sm btn-danger">Delete</button></a>
			</div>
		</div>
	</div>
</div> 


<script src="//<?=ASSET ?>js/bootstrap.min.js"></script>
<script src="//<?=ASSET ?>js/bootstrap-multiselect.js"></script>
<script src="//<?=ASSET ?>js/jquery-ui.min.js"></script>


<!-- DataTables -->


<script src="//<?=ADMIN_LTE ?>/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="//<?=ASSET ?>js/moment.min.js"></script>
<script src="//<?=ASSET ?>js/datetime-moment.js"></script>
<script src="//<?=ADMIN_LTE ?>/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="//<?=ADMIN_LTE ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<script src="//<?=ADMIN_LTE ?>/bower_components/fastclick/lib/fastclick.js"></script>
<script src="//<?=ADMIN_LTE ?>/dist/js/adminlte.js"></script>
<script src="//<?=ADMIN_LTE ?>/dist/js/demo.js"></script>





<script src="//<?=ASSET ?>js/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<script src="//<?=ASSET ?>js/custom.validate.js"></script>
<script src="//<?=ASSET ?>js/Chart.js"></script>
<script src="//<?=ASSET ?>js/customstat.js"></script>

<script src="//<?=ADMIN_LTE ?>/bower_components/select2/js/select2.full.min.js"></script>

<script type="text/javascript">
	$('body').addClass('<?=strtolower($cntrlr[0]) ?>');

	$(function () {
        $('.select2').select2();
    });
</script>
</div>
</body>

</html>


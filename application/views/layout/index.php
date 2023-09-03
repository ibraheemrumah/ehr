<!doctype html>
<html class="fixed sidebar-left-sm <?php echo ($theme_config['dark_skin'] == 'true' ? 'dark' : 'sidebar-light'); ?>">

<!-- Html Header -->
<?php $this->load->view('layout/htmlheader'); ?>

<!-- Html Body -->
<body class="loading-overlay-showing" data-loading-overlay>
	<!-- Page Preloader -->
	
	<section class="body">
		<!-- Top Navbar -->
		<?php $this->load->view('layout/topbar'); ?>
		<div class="inner-wrapper">
			<!-- Sidebar -->
			<?php $this->load->view('layout/sidebar'); ?>
			<!-- Page Main Content -->
			<section role="main" class="content-body">
				<header class="page-header">
					<a class="page-title-icon" href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-home"></i></a>
					<h2><?php echo $title; ?></h2>
				</header>
				<?php $this->load->view($sub_page); ?>
			</section>
		</div>
	</section>

	<!-- JS Script -->
	<?php $this->load->view('layout/script'); ?>
	
	<?php
	$alertclass = "";
	if($this->session->flashdata('alert-message-success')){
		$alertclass = "success";
	} else if ($this->session->flashdata('alert-message-error')){
		$alertclass = "error";
	} else if ($this->session->flashdata('alert-message-info')){
		$alertclass = "info";
	}
	if($alertclass != ''):
		$alert_message = $this->session->flashdata('alert-message-'. $alertclass);
	?>
		<script type="text/javascript">
			swal({
				toast: true,
				position: 'top-end',
				type: '<?php echo $alertclass; ?>',
				title: '<?php echo $alert_message; ?>',
				confirmButtonClass: 'btn btn-default',
				buttonsStyling: false,
				timer: 8000
			})
		</script>
	<?php endif; ?>

	<!-- Sweetalert -->
	<script type="text/javascript">
		function confirm_modal(delete_url) {
			swal({
				title: "<?php echo translate('are_you_sure'); ?>",
				text: "<?php echo translate('delete_this_information'); ?>",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn btn-default swal2-btn-default",
				cancelButtonClass: "btn btn-default swal2-btn-default",
				confirmButtonText: "<?php echo translate('yes_continue'); ?>",
				cancelButtonText: "<?php echo translate('cancel'); ?>",
				buttonsStyling: false,
				footer: "<?php echo translate('deleted_note'); ?>"
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: delete_url,
						success:function(data) {
							swal({
							title: "<?php echo translate('deleted'); ?>",
							text: "<?php echo translate('information_deleted'); ?>",
							buttonsStyling: false,
							showCloseButton: true,
							focusConfirm: false,
							confirmButtonClass: "btn btn-default swal2-btn-default",
							type: "success"
							}).then((result) => {
								if (result.value) {
									window.location.reload(true);
								}
							});
						}
					});
				}
			});
		}
	</script>
</body>
</html>
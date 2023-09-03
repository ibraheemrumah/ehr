<?php
$currency_symbol = $global_config['currency_symbol'];
$div = 0;
if (get_permission('today_invoice_widget', 'is_view')) {
	$div++;	
}
if (get_permission('today_commission_widget', 'is_view')) {
	$div++;	
}
if (get_permission('today_income_widget', 'is_view')) {
	$div++;	
}
if (get_permission('today_expense_widget', 'is_view')) {
	$div++;	
}
if ($div == 0) {
	$widget1 = 0;
}else{
	$widget1 = 12 / $div;
}

$div2 = 0;
if (get_permission('patient_count_widget', 'is_view')) {
	$div2++;	
}
if (get_permission('doctor_count_widget', 'is_view')) {
	$div2++;	
}
if (get_permission('employee_count_widget', 'is_view')) {
	$div2++;	
}
if (get_permission('pending_appointment_count_widget', 'is_view')) {
	$div2++;	
}
if ($div2 == 0) {
	$widget2 = 0;
}else{
	$widget2 = 12 / $div2;
}
?>
<div class="dashboard-page">
<?php if ($widget1 > 0) { ?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="row widget-row-in">
				<?php if (get_permission('today_invoice_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="row widget-col-in">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-money-check"></i>
									<h5 class="text-muted"><?php echo translate('invoice'); ?></h5>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($get_today_invoice_qty); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('today_total'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('today_commission_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6">
						<div class="panel-body">
							<div class="row widget-col-in">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-project-diagram"></i>
									<h5 class="text-muted"><?php echo translate('commission'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($currency_symbol . $get_today_commission); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
											<span class="text-muted text-uppercase"><?php echo translate('today_total'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('today_income_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="row widget-col-in">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="far fa-money-bill-alt" ></i>
									<h5 class="text-muted"><?php echo translate('income'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($currency_symbol . $get_today_incomeandexpense['total_income']); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('today_total'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('today_expense_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="row widget-col-in">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-wallet" ></i>
									<h5 class="text-muted"><?php echo translate('expense'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($currency_symbol . $get_today_incomeandexpense['total_expense']); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('today_total'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

	<div class="row">
<?php if (get_permission('pathology_fees_summary_chart', 'is_view')) { ?>
		<div class="<?php echo get_permission('appointment_status_chart', 'is_view') ? 'col-lg-8 col-md-7 col-sm-12' : 'col-md-12'; ?>">
			<section class="panel">
				<div class="panel-body">
					<h2 class="chart-title mb-md"><?php echo translate('annual') . ' ' . translate('income_vs_expense') . ' - ' . date('Y');?></h2>
					<div class="pe-chart">
						<canvas id="incomeExpense" style="height: 328px;"></canvas>
					</div>
				</div>
			</section>
		</div>
<?php } ?>
<?php if (get_permission('appointment_status_chart', 'is_view')) { ?>
		<div class="<?php echo get_permission('pathology_fees_summary_chart', 'is_view') ? 'col-lg-4 col-md-5 col-sm-12' : 'col-md-12'; ?>">
			<section class="panel">
				<div class="panel-body">
					<h2 class="chart-title mb-md"><?php echo translate('appointment_status') . ' - ' . date("F Y"); ?></h2>
					<div class="pe-chart">
						<canvas id="pieChart" style="height: 328px;"></canvas>
					</div>
				</div>
			</section>
		</div>
<?php } ?>
	</div>

<?php if ($widget2 > 0) { ?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="row widget-row-in">
				<?php if (get_permission('patient_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-wheelchair"></i>
									<h5 class="text-muted"><?php echo translate('patient'); ?></h5>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($get_total_patient); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('total_strength'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('doctor_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-user-md"></i>
									<h5 class="text-muted"><?php echo translate('doctor'); ?></h5> </div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($get_total_doctor); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
											<span class="text-muted text-uppercase"><?php echo translate('total_strength'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('employee_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-users" ></i>
									<h5 class="text-muted"><?php echo translate('employee'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($get_total_staff); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('total_strength'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('pending_appointment_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-notes-medical" ></i>
									<h5 class="text-muted"><?php echo translate('appointment'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php echo html_escape($get_total_appointment); ?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('pending') . " " . translate('request'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if (get_permission('patient_fees_summary_chart', 'is_view')) { ?>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<h2 class="chart-title mb-md"><?php echo translate('patient') . " " . translate('fees') . " " . translate('summary') . " - " . date("F"); ?></h2>
					<div class="pe-chart">
						<canvas id="monthlyBillsummary" style="height: 324px;"></canvas>
					</div>
				</div>
			</section>
		</div>
	</div>
<?php } ?>
</div>

<script type="application/javascript">
	// Annual Income Vs Expense JS
	var income = <?php echo json_encode($yearly_income_expense['income']); ?>;
	var expense = <?php echo json_encode($yearly_income_expense['expense']); ?>;

	var incomeVsExpense = {
		type: 'line',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			datasets: [
				{
					label: "<?php echo translate('income'); ?>",
					backgroundColor: 'rgba(0, 136, 204, .7)',
					borderColor: '#F5F5F5',
					borderWidth: 1,
					data: income
				},
				{
					label: "<?php echo translate('expense'); ?>",
					backgroundColor: 'rgba(204, 61, 61, 0.7)',
					borderColor: '#F5F5F5',
					borderWidth: 1,
					data: expense
				}
			]
		},
		options: {
			responsive: true,
			tooltips: {
				mode: 'index',
				bodySpacing: 4
			},
			title: {
				display: false,
				text: 'Income Expense'
			},
			legend: {
				position: 'bottom',
				labels: { boxWidth: 12 }
			},
            scales: {
                xAxes: [{
					stacked: true,
                    scaleLabel: {
                        display: false,
                    }
                }],
                yAxes: [{
                    stacked: true,
                    scaleLabel: {
                        display: false,
                    },
                }]
            }
		}
	};

	// Patient Fees Summary
	var days = <?php echo json_encode($patient_fees_summary['days']); ?>;
	var net_payable = <?php echo json_encode($patient_fees_summary['total_bill']); ?>;
	var total_paid = <?php echo json_encode($patient_fees_summary['total_paid']); ?>;
	var total_due = <?php echo json_encode($patient_fees_summary['total_due']); ?>;
	var barChartData = {
		type: 'bar',
		data: {
			labels: days,
			datasets: [{
				label: "<?php echo translate('net') . ' ' . translate('payable'); ?>",
				data: net_payable,
				backgroundColor: 'rgba(216, 27, 96, .8)',
				borderColor: '#F5F5F5',
				borderWidth: 1
			},{
				label: "<?php echo translate('total') . ' '  . translate('paid'); ?>",
				data: total_paid,
				backgroundColor: 'rgba(0, 136, 204, .8)',
				borderColor: '#F5F5F5',
				borderWidth: 1
			},{
				label: "<?php echo translate('total') . ' ' . translate('due'); ?>",
				data: total_due,
				backgroundColor: 'rgba(204, 102, 102, .8)',
				borderColor: '#F5F5F5',
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			stacked: false,
			tooltips: {
				mode: 'index',
				bodySpacing: 4
			},
			title: {
				display: false,
				text: 'Income Expense'
			},
			legend: {
				position: 'bottom',
				labels: { boxWidth: 12 }
			}
		}
	};

	/* appointment status pie chart */
	var getappointment = <?php echo json_encode($get_monthly_appointment); ?>;
	var labe = ['Confirmed', 'Pending', 'Canceled'];
	var appointmentDoughnut = {
	    type: 'doughnut',
	    data: {
	        datasets: [{
	                data: getappointment,
	                backgroundColor: [
	                    '#66aa18',
	                    '#4d5360',
	                    '#ff6384',
	                ],
	                label: 'Dataset 1'
	            }],
	        labels: labe,
	    },
	    options: {
	        responsive: true,
	        legend: {
	            position: 'bottom',
	            labels: { boxWidth: 12 },
	        },
	        title: {
	            display: false,
	            text: 'Chart.js Doughnut Chart'
	        },
	        animation: {
	            animateScale: true,
	            animateRotate: true
	        }
	    }
	};

	window.onload = function() {
	<?php if (get_permission('pathology_fees_summary_chart', 'is_view')): ?>
		var ctx = document.getElementById('incomeExpense').getContext('2d');
		window.myLine =new Chart(ctx, incomeVsExpense);
	<?php endif ?>
	<?php if (get_permission('annual_income_vs_expense_chart', 'is_view')): ?>
		var ctx2 = document.getElementById('monthlyBillsummary').getContext('2d');
		window.myBar = new Chart(ctx2, barChartData);
	<?php endif ?>
	<?php if (get_permission('appointment_status_chart', 'is_view')): ?>
        var ctx3 = document.getElementById('pieChart').getContext('2d');
        window.myDoughnut = new Chart(ctx3, appointmentDoughnut);
    <?php endif ?>
	};
</script>
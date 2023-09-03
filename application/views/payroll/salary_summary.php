<?php $currency_symbol = $global_config['currency_symbol']; ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><?php echo translate('select_ground'); ?></h4></header>
			<?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-offset-3 col-md-6 mb-lg">
							<div class="form-group">
								<label class="control-label"><?php echo translate('month'); ?> <span class="required">*</span></label>
								<input type="text" class="form-control monthyear" name="month_year" value="<?php echo set_value('month_year',date("Y-m")); ?>" required/>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="search" value="1" class="btn btn-default btn-block"><i class="fas fa-filter"></i> <?php echo translate('filter'); ?></button>
						</div>
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
		<?php if (isset($payslip)) { ?>
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('payroll') . " " . translate('summary'); ?></h4></header>
			<div class="panel-body">
				<div class="export_title">Payroll Summary Of : <?php echo $this->app_lib->get_months_list($month) . " - " . $year; ?></div>
				<table class="table table-bordered table-hover table-condensed tbr-top" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('designation'); ?></th>
							<th><?php echo translate('salary') . " " . translate('salary'); ?></th>
							<th><?php echo translate('allowance'); ?> (+)</th>
							<th><?php echo translate('deduction'); ?> (-)</th>
							<th><?php echo translate('net') . " " . translate('salary'); ?></th>
							<th><?php echo translate('pay_via'); ?></th>
							<th><?php echo translate('payslip'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						$basic_salary = 0;
						$total_allowance = 0;
						$total_deduction = 0;
						$net_salary = 0;
						if (count($payslip)) {
							foreach ( $payslip as $row ):
								$basic_salary += $row['basic_salary'];
								$total_allowance += $row['total_allowance'];
								$total_deduction += $row['total_deduction'];
								$net_salary += $row['net_salary'];
						?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo html_escape($row['staff_name']); ?></td>
							<td><?php echo html_escape($row['designation_name']); ?></td>
							<td><?php echo html_escape($currency_symbol . $row['basic_salary']); ?></td>
							<td><?php echo html_escape($currency_symbol . $row['total_allowance']); ?></td>
							<td><?php echo html_escape($currency_symbol . $row['total_deduction']); ?></td>
							<td><?php echo html_escape($currency_symbol . $row['net_salary']); ?></td>
							<td><?php echo html_escape($row['payvia']); ?></td>
							<td class="min-w-xs">
							<?php if (get_permission('salary_payment', 'is_view')) { ?>
						        <a href="<?php echo base_url('payroll/invoice/'.$row['id'].'/'.$row['hash']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('view'); ?>" ><i class="fas fa-eye"></i></a>
                            <?php } ?>
                            </td>
						</tr>
						<?php endforeach; } ?>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th><?php echo html_escape($currency_symbol . number_format($basic_salary, 2, '.', '')); ?></th>
							<th><?php echo html_escape($currency_symbol . number_format($total_allowance, 2, '.', '')); ?></th>
							<th><?php echo html_escape($currency_symbol . number_format($total_deduction, 2, '.', '')); ?></th>
							<th><?php echo html_escape($currency_symbol . number_format($net_salary, 2, '.', '')); ?></th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</section>
	<?php } ?>
	</div>
</div>
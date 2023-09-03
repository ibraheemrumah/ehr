<section class="panel">
	<header class="panel-heading"><h4 class="panel-title"><?php echo translate('select_ground'); ?></h4></header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-6 mb-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
	                    <?php
	                        $roleList = $this->app_lib->getRoles();
	                        echo form_dropdown("staff_role", $roleList, set_value('staff_role'), "class='form-control' id='staff_role' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
	                    ?>
					</div>
				</div>
				<div class="col-md-6 mb-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('month'); ?> <span class="required">*</span></label>
						<div class="input-group">
							<input type="text" class="form-control" name="timestamp" value="<?php echo set_value('timestamp', date('Y-F')); ?>" data-plugin-datepicker required data-plugin-options='{"format": "yyyy-MM", "minViewMode": "months"}' />
							<span class="input-group-addon"><i class="fas fa-calendar-check"></i></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" name="search" value="1" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?php echo translate('filter'); ?></button>
				</div>
			</div>
		</footer>
    <?php echo form_close(); ?>
</section>
<?php if (isset($stafflist)) { ?>
<section class="panel">
	<header class="panel-heading"><h4 class="panel-title"><i class="far fa-chart-bar"></i> <?php echo translate('attendance') . " " . translate('report'); ?></h4></header>
	<div class="panel-body">
		<div class="row mt-sm">
			<div class="col-md-offset-8 col-md-4">
				<table class="table table-condensed table-bordered text-dark text-center">
					<tbody>
						<tr>
							<td>Present : <i class="far fa-check-circle text-success"></i></td>
							<td>Absent :  <i class="far fa-times-circle text-danger"></i></td>
							<td>Holiday : <i class="fas fa-hospital-symbol text-info"></i></td>
							<td>Late : <i class="far fa-clock text-tertiary"></i></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Hidden information for printing -->
				<div class="export_title">Attendance Report : <?php echo  $this->app_lib->get_months_list($month) . " - " . $year?></div>
				<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<td><?php echo translate('staff') . " " . translate('name'); ?></td>
<?php
for($i = 1; $i <= $days; $i++){
$date = $year . '-' . $month . '-' . $i;
?>
							<td class="text-center"><?php echo date('D', strtotime($date)); ?> <br> <?php echo date('d', strtotime($date)); ?></td>
<?php } ?>
							<td class="text-center">Total<br> Present</td>
							<td class="text-center">Total<br> Absent</td>
							<td class="text-center">Total<br> Late</td>
						</tr>
					</thead>
					<tbody>
<?php
$total_present = 0;
$total_absent = 0;
$total_late = 0;

if(count($stafflist)){ foreach ($stafflist as $row){ ?>
						<tr>
							<td><?php echo html_escape($row['name']); ?></td>
<?php
for ($i = 1; $i <= $days; $i++) { 
$date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $i));
$atten = $this->attendance_model->get_attendance_by_date($row['id'], $date);
?>
							<td class="center">
<?php if (!empty($atten)) { ?>
								<span data-toggle="popover" data-trigger="hover" data-placement="top" data-trigger="hover" data-content="<?php echo html_escape($atten['remark']); ?>">
<?php if ($atten['status'] == 'A') { $total_absent++; ?>
									<i class="far fa-times-circle text-danger"></i><span class="visible-print">A</span>
<?php } if ($atten['status'] == 'P') { $total_present++; ?>
									<i class="far fa-check-circle text-success"></i><span class="visible-print">P</span>
<?php } if ($atten['status'] == 'L') { $total_late++; ?>
									<i class="far fa-clock text-info"></i><span class="visible-print">L</span>
<?php } if ($atten['status'] == 'H'){ ?>
									<i class="fas fa-hospital-symbol text-tertiary"></i><span class="visible-print">H</span>
<?php } ?>
								</span>
<?php } ?>
							</td>
<?php } ?>
							<td class="center"><?php echo ($total_present);?></td>
							<td class="center"><?php echo ($total_absent);?></td>
							<td class="center"><?php echo ($total_late);?></td>
						</tr>
<?php } } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('balance') . " " . translate('sheet'); ?></h4>
	</header>
	<div class="panel-body">
		<!-- Hidden information for printing -->
		<div class="export_title">Balance Sheet</div>
		<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th width="50"><?php echo translate('sl'); ?></th>
					<th><?php echo translate('account') . " " . translate('name'); ?></th>
					<th><?php echo translate('total_dr'); ?></th>
					<th><?php echo translate('total_cr'); ?></th>
					<th><?php echo translate('balance'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total_bal = 0;
				if(!empty($results)) {
					$count = 1;
					foreach($results as $row):
						$total_bal += html_escape($row['fbalance']);
				?>	
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo html_escape($row['ac_name']); ?></td>
					<td><?php echo html_escape($global_config['currency_symbol'] . $row['total_dr']); ?></td>
					<td><?php echo html_escape($global_config['currency_symbol'] . $row['total_cr']); ?></td>
					<td><?php echo html_escape($global_config['currency_symbol'] . $row['fbalance']); ?></td>
				</tr>
				<?php endforeach; } ?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo html_escape($global_config['currency_symbol']) . number_format($total_bal, 2, '.', ''); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
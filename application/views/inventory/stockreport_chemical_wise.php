<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"> <?php echo translate('select_ground'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<div class="panel-body">
			<div class="row mb-sm">		
				<div class="col-md-6 mb-sm">		
					<div class="form-group">
						<label class="control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
						<?php
							echo form_dropdown("category_id", $categorylist, set_value('category_id'), "class='form-control' required
							data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>
				<div class="col-md-6 mb-sm">		
					<div class="form-group">
						<label class="control-label"><?php echo translate('unit') ?> <span class="required">*</span></label>
						<?php
							echo form_dropdown("unit_id", $unitlist, set_value('unit_id'), "class='form-control' required
							data-plugin-selectTwo data-width='100%'");
						?>
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
<?php if (isset($results)): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('chemical_wise_stock') . " " . translate('report'); ?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title"><?php echo translate('chemical_wise_stock') . " " . translate('report'); ?></div>
		<table class="table table-bordered table-hover table-condensed tbr-top" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('chemical') . " " . translate('name'); ?></th>
					<th><?php echo translate('category'); ?></th>
					<th><?php echo translate('chemical') . " " . translate('code'); ?></th>
					<th><?php echo translate('unit'); ?></th>
					<th><?php echo translate('purchase') . " " . translate('price'); ?></th>
					<th><?php echo translate('sale') . " " . translate('price'); ?></th>
					<th><?php echo translate('in_qty'); ?></th>
					<th><?php echo translate('out_qty'); ?></th>
					<th><?php echo translate('current') . " " . translate('stock'); ?></th>
					<th><?php echo translate('purchase') . " " . translate('total'); ?></th>
					<th><?php echo translate('sale') . " " . translate('total'); ?></th>
					<th><?php echo translate('sale_profit'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$count = 1;
				$total_purchase_price = 0;
				$total_sale_price = 0;
				$total_profit = 0;
				if (!empty($results)){ foreach ($results as $row):
					$purchase_price_unit = $row['purchase_price'] / $row['unit_ratio'];
					$totl_out_stock = $row['in_stock'] - $row['available_stock'];
					$purchase_price = $purchase_price_unit * $totl_out_stock;
					$sales_price = $row['sales_price'] * $totl_out_stock;
					$profit = $sales_price - $purchase_price;
					$total_purchase_price += $purchase_price;
					$total_sale_price += $sales_price;
					$total_profit += $profit;

				?>	
				<tr>
					<td><?php echo $count++ ; ?></td>
					<td><?php echo html_escape($row['name']); ?></td>
					<td><?php echo html_escape($row['category_name']); ?></td>
					<td><?php echo html_escape($row['code']); ?></td>
					<td><?php echo html_escape($row['unit_name']); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($purchase_price_unit, 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['sales_price'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($row['in_stock']); ?></td>
					<td><?php echo html_escape($totl_out_stock); ?></td>
					<td><?php echo html_escape($row['available_stock']); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($purchase_price, 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($sales_price, 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($profit, 2, '.', '')); ?></td>
				</tr>
				<?php endforeach; }?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_purchase_price, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_sale_price, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_profit, 2, '.', '')); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
<?php endif; ?>
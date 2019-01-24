<table>
	<thead>
		<tr>
			<th>Amount</th>
			<th>Time</th>
		</tr>
	</thead>
	<tbody>
		<?php
		// List HTML here.
		if ( ! empty( $spends ) ):
			foreach ( $spends as $spend ) : ?>
				<tr>
					<td><?php echo $spend['amount']; ?></td>
					<td><?php echo $spend['spent_at']; ?></td>
				</tr>
			<?php endforeach;
		else: ?>
		<tr><td colspan="2">No spends found!</td></tr>
		<?php endif;
		?>
	</tbody>
</table>
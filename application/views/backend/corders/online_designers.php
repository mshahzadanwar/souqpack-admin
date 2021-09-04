<div style="flex-direction: row;display: flex;flex-wrap: wrap; max-height: 300px; overflow-y: scroll;">
	
	<?php

	

	foreach($designers->result() as $driver){


		$pending = $this->db->where("designer_id",$driver->id)->where_in("status",array(0,1,3))->count_all_results("designer_tasks");

		$done = $this->db->where("designer_id",$driver->id)->where("status",4)->count_all_results("designer_tasks");
	 ?>


	<label>
		
		<span style="padding: 5px 10px; border:1px dashed grey; background: #f0f0f0; border-radius: 5px;
		display: flex;
		flex-direction: row;
		align-items: center;
		font-size: 10px;

		">
			<input required type="radio" name="designer_id" value="<?php  echo $driver->id; ?>">
			
			<span style="margin-left: 10px;">Name: <?php echo $driver->name; ?></span>
			<span style="margin-left: 10px;">Pending: (<?php echo $pending; ?>) | Done: (<?php echo $done; ?>)</span>
		</span>
	</label>

	<?php

}

	if(empty($designers->result())) echo "You don't have any one in the system";
	 ?>
</div>

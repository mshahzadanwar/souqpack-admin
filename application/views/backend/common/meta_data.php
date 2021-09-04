<?php /* ?><div class="card">
    <div class="card-header">
		<h4 class="m-b-0 text-white">Meta Information</h4>
	</div>
    <div class="card-body">
     <div class="form-group <?=(form_error('meta_title') !='')?'error':'';?>">
		<h5>Meta Title </h5>
		<div class="controls">
		<?php 
        $val = '';
        if(set_value('meta_keys') != ''){
            $val = set_value('meta_keys');
        }elseif(isset($data)){
                $val = $data->meta_title;
           

        }else
        {
            $val=$prev->meta_title;
        }
		$data1= array(
            'name' => 'meta_title',
            'type' => 'text',
            'placeholder' => 'Meta Title',
            'class' => 'form-control',
            'value'=>$val

		);
		echo form_input($data1);
		?>
            <div class="text-danger"><?php echo form_error('meta_title');?></div>
		</div>
	</div>
     <div class="form-group <?=(form_error('meta_keys') !='')?'error':'';?>">
		<h5>Meta Keywords </h5>
		<div class="controls">
		<?php
        $val = '';
        if(set_value('meta_keys') != ''){
            $val = set_value('meta_keys');
        }elseif(isset($data)){
                $val = $data->meta_keywords;
            
        }
        else
        {
            $val = $prev->meta_keywords;
        }
		$data1= array(
            'name' => 'meta_keys',
            'type' => 'text',
            'placeholder' => 'Meta Keywords',
            'class' => 'form-control',
            'value'=>$val,
            'rows'    => '3',
		);
		echo form_textarea($data1);
		?>
            <div class="text-danger"><?php echo form_error('meta_keys');?></div>
		</div>
	</div>
   <div class="form-group <?=(form_error('meta_desc') !='')?'error':'';?>">
		<h5>Meta Description </h5>
		<div class="controls">
		<?php
        $val = '';
        if(set_value('meta_keys') != ''){
            $val = set_value('meta_keys');
        }elseif(isset($data)){
                $val = $data->meta_description;
           


        }
        else
        {
            $val = $prev->meta_description;
        }
		$data1= array(
            'name' => 'meta_desc',
            'type' => 'text',
            'placeholder' => 'Meta Descriptions',
            'class' => 'form-control',
            'id'    => 'exampleTextarea',
		    'rows'    => '3',
            'value'=>$val
		);
		echo form_textarea($data1);
		?>
            <div class="text-danger"><?php echo form_error('meta_desc');?></div>
			</div>
		</div>
    </div>
</div>


<?php */ ?>


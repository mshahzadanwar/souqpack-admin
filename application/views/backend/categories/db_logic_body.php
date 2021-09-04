<div class="card-header custom_class_inpu" style="background-color: #464646 !important;">
            <?php /* ?>
            <div class="easy">
                <input 
                type="number" 
                placeholder="Prints" 
                class="prints" 
                name="prints[<?php echo $table->id; ?>]" 
                onchange="printChanged(<?php echo $table->id; ?>,this.value)"
                value="<?php echo $table->prints; ?>">
                <input 
                type="number" 
                placeholder="Faces" 
                class="faces" 
                name="faces[<?php echo $table->id; ?>]" 
                onchange="faceChanged(<?php echo $table->id; ?>,this.value)"
                value="<?php echo $table->faces; ?>">
            </div>
            oninput="faceChanged(<?php echo $table->id; ?>,this.value)"
            <?php */ ?>
            <h4 class="m-b-0 text-white pull-left">
                <span style="margin-right: 25px;">QUANTITY FOR</span> 

                <input 
                name="table_qty[<?php echo $table->id; ?>]" 
                oninput="qtyChanged(<?php echo $table->id; ?>,this.value)"
                type="number" step="1" required value="<?php echo $table->qty; ?>" />


                <input 
                name="unit_name[<?php echo $table->id; ?>]" 
                placeholder="Unit (Kilo) English"
                style="width: 150px;"
                oninput="unitChanged(<?php echo $table->id; ?>,this.value)"
                type="text" value="<?php echo $table->unit_name; ?>" />

                <input 
                name="unit_name_er[<?php echo $table->id; ?>]" 
                placeholder="Unit (Kilo) Arabic"
                style="width: 150px;"
                oninput="unitChangedAr(<?php echo $table->id; ?>,this.value)"
                type="text" value="<?php echo $table->unit_name_ar; ?>" />

                <button type="button" onclick="newPrint(<?php echo $table->id; ?>)" class="btn btn-sm btn-primary" style="padding: 5px 6px;">+ ADD NEW PRINT</button>

            </h4>

            <?php 
            if(!$iamfirst){
            ?>

            <button 
            type="button" 
            class="btn btn-danger btn-sm pull-right" onclick='var r = confirm("Are you sure! You want to remove this table, it will not be recovered again!");
  if (r == true) {
    removeVariation_table_update(this,<?php echo $v_table->id;?>,<?php echo $table->id; ?>);
  } else {
  };'>Remove</button>

        <?php } ?>
        </div>

        <div class="col-md-12 custom_prrr">
            <!-- SIZE ENGLISH ARABIC -->
            <div class="col-md-6 " style="float: left;">
            <div class="">
                <label>Size (English)</label>
            <input 
                name="size_en[<?php echo $table->id; ?>]" 
                oninput="SizeNameChangedEn(<?php echo $table->id; ?>,this.value)"
                type="text" placeholder="Size (English)" required value="<?php echo $table->size_eng; ?>" style="margin-top: 15px;
    margin-left: 17px;
    padding: 9px;
    width: 70%;" />
    </div>  


    <div class="">
                <label>Size (Arabic)</label>
        <input 
                name="size_ar[<?php echo $table->id; ?>]" 
                oninput="SizeNameChangedAr(<?php echo $table->id; ?>,this.value)"
                type="text" placeholder="Size (Arabic)" required value="<?php echo $table->size_ar; ?>" style="margin-top: 15px;
    margin-left: 17px;
    padding: 9px;
    width: 70%;" />
        </div>
    </div>
    <div class="col-md-6" style="float: left;">
            <div class="">
                <label>Table (Print) English</label>
            <input 
                name="table_print_name_en[<?php echo $table->id; ?>]" 
                oninput="tablePrintNameChangedEn(<?php echo $table->id; ?>,this.value)"
                type="text" placeholder="Add Table Title (Print) English" required value="<?php echo $table->table_print_name_en; ?>" style="margin-top: 15px;
    margin-left: 17px;
    padding: 9px;
    width: 70%;" />
    </div>
         <div class="">
                <label>Table (Print) Arabic</label>
        <input 
                name="table_print_name_ar[<?php echo $table->id; ?>]" 
                oninput="tablePrintNameChangedAr(<?php echo $table->id; ?>,this.value)"
                type="text" placeholder="Add Table Title (Print) Arabic" required value="<?php echo $table->table_print_name_ar; ?>" style="margin-top: 15px;
    margin-left: 17px;
    padding: 9px;
    width: 70%;" />
        </div>
</div>


</div>
        <div class="card-body"
        style="padding: 20px 5px; float: left; width: 100%; overflow-x: scroll;">

        
        
        <div class="append_rows_in_here<?php echo $table->id; ?> easy">
         
            <div id="append_new_rows_header_here">
            </div>
            <?php 
                if($is_new==1){ 
                       echo newRow(0,$table->id,true);
                       echo newRow(0,$table->id,false);
                       // echo newRow(0,$table->id,false);
                       // echo newRow(0,$table->id,false);
                       // echo newRow(0,$table->id,false);
                } 
            ?>
            <?php
            if($is_new==0)
            {
                
                $rows = $this->db->where("table_id",$table->id)->get("tmp_rows")->result_object();
                foreach($rows as $row_key=>$row)
                {
                    $is_first = $row_key==0;
                    echo newRow($row->id,$table->id,$is_first);
                }
            }
             ?>
            
        </div>
        <div class="easy" style="    margin-top: 11px;
    margin-left: 20px;">
            <button type="button" class="btn btn-primary btn-sm" onclick="newRow(0,<?php echo $table->id; ?>)" >+ ADD NEW ROW</button>
        </div>

     


    </div>
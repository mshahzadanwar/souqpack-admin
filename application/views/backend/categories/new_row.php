<?php $row_time = time().rand(11,999); ?>


<div class="hover_orange">
    <div style="display: flex;flex-direction: column;justify-content: center; align-items: center;padding: 10px;">
       <?php if(!$is_first){ ?>
        <span
            onclick="removeRow(<?php echo $row->table_id; ?>,<?php echo $row->id; ?>)"
             style="
                 width: 20px;
                    height: 19px;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                    justify-content: center;
                    border-radius: 26px;
                    background: #fb9678;
                    color: #fff;
                    position: absolute;
                    right: initial;
                    left: 0;">
        <i class="fa fa-times"></i>
    </span>
<?php } ?>
    </div>
        <div class="chell_width ">
            <?php if($is_first){ ?>

            <span style="
                display: flex;flex-direction: row;
                justify-content: space-between;
                align-items: center;
                ">
                    <span style="flex: 1;
    padding: 13px;
    background: #ccc;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;">
                        <?php if($table->size_eng!=""){$print_size = $table->size_eng;} else {
                            $print_size = "Size";
                        }?>
                        <span class="value_update_data_size">
                            <?php echo $print_size;?>
                        </span>
                    </span>
                </span>
            <?php } ?>

            <input 
            type="text" 
            name="whg[<?php echo $row->table_id; ?>][<?php echo $row->id; ?>]" 
            oninput="whgChanged(<?php echo $row->id; ?>,this.value)"
            placeholder="W*H*G" 
            required
            value="<?php echo $row->whg; ?>"

            >
        </div>

        <div class="chell_width">
            <?php if($is_first){ ?>
             <span style="
                display: flex;flex-direction: row;
                justify-content: space-between;
                align-items: center;
                ">
                    <span style="flex: 1;
    padding: 12px;
    background: #ccc;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;">
                        Plain Price
                    </span>
                </span>
            <?php } ?>
            <input 
            type="number"
            step="0.1" 
            min="0"
            name="plain_price[<?php echo $row->table_id; ?>][<?php echo $row->id; ?>]" 
            oninput="plainPriceChanged(<?php echo $row->id; ?>,this.value)"
            placeholder="Plain Bag Price" 
            value="<?php echo $row->plain_price; ?>"
            >
        </div>
            
        <div class="chell_width_prints" style="display: flex;flex-direction: row; ">
             <?php 
             $prints = json_decode($row->prints);
             foreach($prints as $key=>$print){
                $count_pr = count($print);
             ?>
            <div class="" style="text-align: center; background: #fff">
                <?php if($is_first){ ?>
                <span style="
                display: flex;flex-direction: row;
                justify-content: space-between;
                align-items: center;background: #ccc;border-right: 1px solid #f0f0f0;
                ">
                    <?php 
                        if($count_pr ==1){
                            $flex = "0.8";
                    ?>
                    <?php } else {
                            $flex= "0.6";
                        ?>
                        <span style="flex: 0.2;">&nbsp;</span>
                    <?php } ?>
                    <?php if($table->table_print_name_en!=""){$print_tab = $table->table_print_name_en;} else{$print_tab = "Print";}?>
                    <span
                    title ="<?php echo $key+1; ?> <?php echo $print_tab;?>  2 Faces"
                     style="flex: <?php echo $flex;?>;
    padding: 12px;

    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;     width: 129px;
">
                       <?php echo $key+1; ?> 
                            <span class="value_update_data">
                                <?php echo $print_tab;?>
                            </span> 2 Faces
                    </span>
                    <span style="flex: 0.2;display: flex;flex-direction: row;justify-content: center;">
                        
                        <span
                        onclick="removePrint(<?php echo $row->table_id; ?>,<?php echo $key; ?>)"
                         style="
                         width: 21px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                    justify-content: center;
                    border-radius: 26px;
                    background: #fb9678;
                    color: #fff;"><i class="fa fa-times"></i></span>
                       
                    </span>


                </span>
                 <?php } ?>
                <div style="flex-direction: row;display: flex;">
                    <?php for($qq=0; $qq<=$table->faces-1;$qq++){
                     ?>
                     <div style="width:100%;">
                     <input
                      type="number" 
                      step="0.1"
                      min="0"
                      placeholder="<?php //echo $qq+1; 
                      echo " 2 faces price"; ?>" 
                     name="prints[<?php echo $row->table_id; ?>][<?php echo $row->id; ?>][<?php echo $key; ?>][<?php echo $qq; ?>]"
                     value="<?php echo ($print[$qq]); ?>"
                     oninput="columnChanged(<?php echo $row->id; ?>,<?php echo $key; ?>,<?php echo $qq; ?>,this.value)"
                     >
                    </div>

                    <?php } ?>

                </div>
            </div>
            <?php } ?>
        </div>
    </div>

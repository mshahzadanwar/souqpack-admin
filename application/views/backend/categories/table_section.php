    <?php $table_time = time().rand(22992,9292929); ?>
    <div class="card" >




         <div class="card-header">
            <h4 class="m-b-0 text-white pull-left">Quantity For <input 
                name="table_qty[<?php echo $table_time; ?>]" 
                type="number" step="1" required value="5000" /></h4>

            <?php 
            if(!$iamfirst){
            ?>

            <button 
            type="button" 
            class="btn btn-danger btn-sm pull-right" onclick="removeVariation(this);">Remove</button>

        <?php } ?>
        </div>


        <div class="card-body"
        style="
        padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;"
                            >


        <div class="append_rows_in_here">
            <?php echo newRow(true,$table_time); ?>
        </div>
        <div class="easy">
            <button type="button" onclick="newRow(false,<?php echo $table_time; ?>)" >+ Row</button>
        </div>

        <div class="" style="clear: both;overflow-x: scroll;display: flex;flex-direction: row;">
            <div class="">
                <input type="text" name="c_var_size_en[<?php echo $t; ?>]" class="form-control form-control-line" placeholder="Value" value="<?php echo $title_en; ?>">
            </div>
             <div class="col-6">
                <div class="form-group">
                    <h5>Title (Arabic) (i.e Size):</h5>
                    <input type="text" name="c_var_size_ar[<?php echo $t; ?>]" class="form-control form-control-line" placeholder="Value" value="<?php echo $title_ar; ?>">
                </div>
            </div>
        </div>

            <div class="easy row">

                <div class="col-6">
                    <div class="form-group">
                        <h5>Disabled?</h5>

                        <select name="disabled[<?php echo $t; ?>]" class="form-control">
                            <option <?php if($disabled!=1) echo "selected"; ?> value="0">No</option>
                            <option <?php if($disabled==1) echo "selected"; ?> value="1">Yes</option>
                        </select>
                    </div>
                </div>
            </div>




        <?php
        if(empty($options))

         echo more_optionv2($t,"","",100,1);
     else{
        foreach($options as $key=>$option)
        {

            if($key==0) $primary = 1;
            else $primary=0;
            echo more_optionv2($t,$option->en,$option->ar,$option->price,$primary);
        }
     }

          ?>


        <div id="add_more_option_in_me<?php echo $t; ?>" class="easy pull-left" style="width: 100%;">
        </div>
        <div class="form-group easy">
            <button type="button" class="btn btn-info btn-sm" onclick="moreOption(this,'<?php echo $t; ?>');">+ Value</button>
        </div>



    </div>
    </div>

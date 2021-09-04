<style type="text/css">
     .supplierbox_heading input{    padding: 8px 10px;}
     .tbale_dy div{background: #4f5467;
            border: 1px solid #636879;
    padding: 8px;
    color: #fff;
    width: 100%;
   }
    .tbale_dyy div {

            border: 1px solid #636879;
    
    color: #fff;
     width: 100%;
    }
    .tbale_dyy input {
        border: none !important;
    padding: 11px !important;
    }
    .res_c i{
        font-size: 24px;
    }
    .red {color: red;}
    .green {color: green;}
 </style>
<div class="tbale_dyy" style="flex-direction: row;display: flex;position: relative;">
    <?php if($row->allow_remove!=455){ ?>
    <button 
    onclick="removeRowCost(this)" 
    type="button" style="
    position: absolute;
    background: red;height: 20px; width: 20px; border-radius: 10px; right: -20px;bottom: 0;top:0;" >X</button>
<?php } ?>
    <div style="width: 30px;">
        <input
        name="item_no[]"
        type="number"
        step=1
        placeholder="#"
        disabled
        required
        style="width: 100%;"
        value="<?php echo $row->item_no; ?>"
         />
    </div>

    <div style="">
        <input
        name="item_name[]"
        placeholder="Item Name..."
        type="text"
        required
        style="width: 100%;"
        value="<?php echo $row->item_name; ?>"
         />
    </div>
    <div style="">
        <input
        name="item_details[]"
         placeholder="Item Details..."
        type="text"
        required
        style="width: 100%;"
        value="<?php echo $row->item_details; ?>"
         />
    </div>
    <div style="">
        <input
        name="item_qty[]"
        type="number"
        step="1"
         placeholder="Item Number"
        required
        class="qty"
        min="1"
        onchange="showTotal(this)"
        style="width: 100%;"
        value="<?php echo $row->item_qty; ?>"
         />
    </div>
    <div style="">
        <input
        name="item_cost[]"
        step="0.2"
        min="0"
        placeholder="Item Cost..."
        class="cost"
        type="number"
        onchange="showTotal(this)"
        required
        style="width: 100%;"
        value="<?php echo $row->item_cost; ?>"
         />
    </div>
    <div style="">
        <input
        name="item_total[]"
        disabled
        step="0.1"
        type="number"
        class="total"
        required
        style="width: 100%;"
        value="<?php echo $row->item_total; ?>"
         />
    </div>

    <?php if($edit == 1){?>
        <input
        name="item_idddd[]"
        
        type="hidden"
        style="width: 100%;"
        value="<?php echo $row->id; ?>"
         />
    <?php } ?>

    <?php //if(!is_purchaser()){ ?>
    <div class="res_c" style="padding: 6px 0;
text-align: center;">
    <?php if($row->ac_status==1){ ?>
        <i class="fa fa-check-circle green" data-toggle="tooltip" title="Approved By Accountant: (<?php echo staff_by_id($row->ac_id)->name; ?>) at <?php echo date("F d, Y h:i A",strtotime($row->ac_at)); ?>" aria-hidden="true"></i>
    <?php } ?>
    <?php if($row->ac_status==2){ ?>
        <i class="fa fa-times-circle red" data-toggle="tooltip" title="Rejected By Accountant: (<?php echo staff_by_id($row->ac_id)->name; ?>) at <?php echo date("F d, Y h:i A",strtotime($row->ac_at)); ?> --- (REASON: <?php echo $row->ac_comments; ?>)" aria-hidden="true"></i>
    <?php } ?>

            <?php 
            if($row->approve_reject==1 && ($row->ac_status==0 && is_accountant())){ ?>

            <button 
            onclick="rejectItem(<?php echo $row->id; ?>)" 
            type="button" style="
            margin-right: 15px;" class="btn btn-danger btn-sm btn-xs" >Reject</button>
            <a href="<?php echo base_url()."admin/costs/approveItem/".$row->id; ?>">
            <button 
            type="button" style="
           " class="btn btn-success btn-sm btn-xs" >Approve</button>
            </a>
        <?php } ?>

    </div>

    <div class="res_c" style="padding: 6px 0;
text-align: center;">
    <?php if($row->sk_status==1){ ?>
        <i class="fa fa-check-circle green" data-toggle="tooltip" title="Approved By Stock Manager: (<?php echo staff_by_id($row->sk_id)->name; ?>) at <?php echo date("F d, Y h:i A",strtotime($row->sk_at)); ?>" aria-hidden="true"></i>
    <?php } ?>
    <?php if($row->sk_status==2 || $row->sk_status==3){ ?>
        <i class="fa fa-times-circle red" data-toggle="tooltip" title="Rejected By Stock Manager: (<?php echo staff_by_id($row->sk_id)->name; ?>) at <?php echo date("F d, Y h:i A",strtotime($row->sk_at)); ?>  --- (REASON: <?php echo $row->sk_comments; ?>)" aria-hidden="true"></i>
    <?php } ?>

    <?php 
            if(($row->approve_reject==1 && ( $row->sk_status==0 && is_stock())) || $row->sk_status == 3){ ?>

            <button 
            onclick="rejectItem(<?php echo $row->id; ?>)" 
            type="button" style="
            margin-right: 15px;" class="btn btn-danger btn-sm btn-xs" >Reject</button>
            <a href="<?php echo base_url()."admin/costs/approveItem/".$row->id; ?>">
            <button 
            type="button" style="
           " class="btn btn-success btn-sm btn-xs" >Approve</button>
            </a>
        <?php } ?>
 </div>
<?php //} ?>
 <!--    <div>
    <?php if($row->approve_reject==1 && ($row->ac_status==0 && is_accountant()) || ( $row->sk_status==0 && is_stock() && $row->ac_status==1)){ ?>

    <button 
    onclick="rejectItem(<?php echo $row->id; ?>)" 
    type="button" style="
    margin-right: 15px;" class="btn btn-danger btn-sm btn-xs" >Reject</button>
    <a href="<?php echo base_url()."admin/costs/approveItem/".$row->id; ?>">
    <button 
    type="button" style="
   " class="btn btn-success btn-sm btn-xs" >Approve</button>
</a>
<?php


 } ?>

 
</div> -->
</div>
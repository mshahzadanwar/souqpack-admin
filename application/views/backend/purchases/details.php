<table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
    <tr>
        <th>Product Name</th>
        <td><?php echo $purchase->title; ?> / <?php echo $purchase_ar->title; ?></td>
    </tr>
    <tr>
        <th>SKU</th>
        <td><?php echo $purchase->sku; ?></td>
    </tr>
    <tr>
        <th>Total Stock</th>
        <td><?php echo $purchase->avl_qty; ?></td>
    </tr>
  <!--   <tr>
        <th>Currency</th>
        <td><?php 
        $region = $this->db->where("id",$purchase->region_id)->get("regions")->result_object()[0];


        echo $region->currency;
         ?></td>
    </tr> -->
    <tr>
        <th>Cost Price</th>
        <td><?php echo $purchase->price.' '.$region->currency; ?></td>
    </tr>
   <!--  <tr>
        <th>Unit Price</th>
        <td><?php echo $purchase->unit_price.' '.$region->currency; ?></td>
    </tr>
    <tr>
        <th>Total Price</th>
        <td><?php echo $purchase->total_price.' '.$region->currency; ?></td>
    </tr>
    <tr>
        <th>Discount</th>
        <td><?php echo $purchase->discount.' '.$region->currency; ?></td>
    </tr> -->
</table>
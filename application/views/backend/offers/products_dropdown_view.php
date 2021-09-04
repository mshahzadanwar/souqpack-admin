<div class="form-group ">
    <h5>Product: </h5>
    <div class="controls">
        <select required class="custom-select form-control required" id="product" name="product">
                         
               <?php foreach($products->result() as $product){?>
                   <option <?php if($product->id == $this->input->post('product') || $prev->product==$product->id){ echo 'selected="selected"';}?>  value="<?php echo $product->id;?>"><?php echo $product->title;?></option>
                <?php } ?>

        </select>
        <div class="text-danger"></div>
    </div>
</div>
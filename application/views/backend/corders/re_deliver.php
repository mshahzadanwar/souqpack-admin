<div id="reDeliverModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Deliver back to user:</h4>

        <button type="button" class="close" onclick="closeModal()" data-dismiss="modal">&times;</button>

      </div>
      <form method="post" action="<?php echo base_url()."admin/corders/re_deliver"; ?>" >
      <div class="modal-body driver_modal_body">
       <div class="form-group " >
          <h5 for="desc">Please type your comments</h5>
          <div class="controls ">
              <div class="switchery-demo m-b-20">
                  <textarea required="" rows=5 placeholder="Please comment down in details, why you are rejecting the delivery.... " class="form-control form-control-line" name="desc" ></textarea>
                  <input type="hidden" value="<?php echo $order->id; ?>" name="order_id">
              </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()" data-dismiss="modal">Later</button>
         <button  class="btn btn-primary" >Resend Delivery</button>
      </div>
    </form>
    </div>
  </div>
</div>
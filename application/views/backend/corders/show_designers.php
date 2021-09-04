<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Assign Order To:</h4>

        <button type="button" class="close" onclick="closeModal()" data-dismiss="modal">&times;</button>

      </div>
      <form method="post" action="<?php echo base_url()."admin/corders/assign"; ?>" >
      <div class="modal-body driver_modal_body">
        <p>Loading...</p>
      </div>
      <div class="modal-body">
        <div class="form-group <?=(form_error('deadline') !='')?'error':'';?>">
            <h5>End Date: (Deadline) </h5>
            <div class="controls">
                <input type="text" class="form-control publishTime required" required name="deadline" value="<?php echo date("Y-m-d H:i:s",strtotime("+5 days")); ?>">

                <input type="hidden" name="order_id" value="<?php  echo $order_id; ?>" />
                <input type="hidden" name="submit_type" id="submit_type" value="designer" />
            </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()" data-dismiss="modal">Later</button>
         <button  class="btn btn-primary" >Assign Now</button>
      </div>
    </form>
    </div>
  </div>
</div>
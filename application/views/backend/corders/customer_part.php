 <div class="row">
              
    <div class="col-lg-12 col-md-12 col-sm-12" style="    background: #fff;
    padding: 15px;">
        <h3 class="box-title">Order History</h3>
        <div class="table-responsive">

            <div class="page">
              <div class="timeline" id="timeline">
                
             

            <?php 

            $task_threads = $this->db->where("task_id",$order->id)
            ->where("parent_id",0)
            ->where("for_admin",1)
            ->order_by("created_at","DESC")
            ->group_by("DATE(created_at)")
            ->get("corder_threads")
            ->result_object();



            foreach($task_threads as $key=>$task_thread)
            {
             ?>
                

                <div class="timeline__group">
                  <span class="timeline__year time" aria-hidden="true"><?php echo date("F d",strtotime($task_thread->created_at)); ?></span>
                  <div class="timeline__cards">
                    <?php 
                    $this_time_threads = $this->db->where("DATE(created_at)",date("Y-m-d",strtotime($task_thread->created_at)))
                      ->where("for_admin",1)
                    ->where("task_id",$task_thread->task_id)
                    ->where("parent_id",0)
                    ->order_by("created_at","DESC")
                    ->get("corder_threads")->result_object();


                    foreach($this_time_threads as $time_key=>$this_time_thread){
                    ?>
                    <div class="timeline__card card_timline" style="background: <?php echo $this_time_thread->is_delivery==1?"#d5f6fd":""; ?> <?php echo $this_time_thread->status==2?"#f3d2d2":""; ?> <?php echo $this_time_thread->status==1?"#c6ffd5":""; ?> <?php echo $this_time_thread->by=="Production"?"#eaffc9":""; ?>;">
                      <header class="card__header">
                        <time class="time" datetime="2008-02-02">
                          <span class="time__day"><?php echo date("h:i A",strtotime($this_time_thread->created_at)); ?></span>
                        </time>
                      </header>
                      <div class="card__content">
                        <h5><span style="font-weight: bold;color: purple;"><?php echo $this_time_thread->by; ?></span> <?php echo $this_time_thread->title; ?></h5>
                        <p><?php echo $this_time_thread->desc; ?></p>
                        <?php if($this_time_thread->file!=""){ ?>

                            <?php if($this_time_thread->is_image==1){ ?>
                                <div style="padding: 10px;margin-top: 10px">
                                    <img src="<?php echo base_url()."resources/uploads/orders/".$this_time_thread->file; ?>" width="100px" />
                                </div>
                            <?php } ?>
                            <span style="float: left;margin-top: 10px;border:1px dotted grey; background: #f0f0f0; padding: 2px 10px; border-radius: 4px; display: flex;flex-direction: row;">
                                <span style="color: purple;font-style: italic; font-size: 11px;justify-content: space-between;"><?php echo $this_time_thread->filename; ?></span>
                                <a style="margin-left: 20px;" href="<?php echo base_url()."admin/corders/download/".$this_time_thread->id; ?>" >
                                    <span >
                                       <i class="fa fa-download"></i> Download (<?php echo $this_time_thread->file_size; ?> KB)
                                    </span>
                                </a>

                            </span>

                        <?php } ?>  
                        <!-- CHECK IF BANK ATTACH -->
                        <?php if($this_time_thread->attach_bank!=""){ ?>

                            <?php if($this_time_thread->by_bank==1){
                              $receipt = $this->db->query("SELECT * FROM bank_payment_recipts WHERE oID = ".$this_time_thread->task_id." ORDER by id DESC")->result_object()[0];
                             ?>
                                <div style="padding: 10px;margin-top: 10px">
                                    <img src="<?php echo base_url()."resources/uploads/orders/".$receipt->file_name; ?>" width="100px" />
                                </div>
                                <?php if($receipt->status == 0){?>
                                <div class="approve_payment">
                                  <a href="<?php echo base_url();?>admin/corders/apprve_payment/<?php echo $this_time_thread->task_id;?>/<?php echo $receipt->id;?>">
                                  <button type="button" class="btn btn-sm btn-primary">Approve</button>
                                </a>
                                </div>
                              <?php } ?>
                            <?php } ?>
                            <span style="float: left;margin-top: 10px;border:1px dotted grey; background: #f0f0f0; padding: 2px 10px; border-radius: 4px; display: flex;flex-direction: row;">
                                <span style="color: purple;font-style: italic; font-size: 11px;justify-content: space-between;"><?php echo $this_time_thread->attach_bank; ?></span>
                                <a style="margin-left: 20px;" download href="<?php echo base_url()."resources/uploads/orders/".$receipt->file_name; ?>" >
                                    <span >
                                       <i class="fa fa-download"></i> Download
                                    </span>
                                </a>

                            </span>

                        <?php } ?>

                        <?php if($this_time_thread->status==2){ ?>
                        <div class="form-group">
                                <a href="<?php echo base_url()."admin/corders/re_assign/".$this_time_thread->task_id; ?>">
                                <button

                                 type="button" class="btn btn-md btn-warning">Assign To Designer Again</button>
                              </a>


                              <button
                              onclick="reDeliver()"
                                 type="button" class="btn btn-md btn-danger">Assign Back to customer</button>
                            </div>
                      <?php } ?>

                      <?php if($this_time_thread->payment_status==1 && $order->production_id==0){ ?>
                        <div class="form-group">
                                <a href="javascript:assignProduction(<?php echo $this_time_thread->task_id; ?>)">
                                <button

                                 type="button" class="btn btn-md btn-danger">
                                  <?php if($order->payment_method_part_1 == "4"){
                                    echo "Send to Production";
                                  }else {
                                  echo "Confirm Payment and Send to production";
                                  } ?>
                                </button>
                              </a>
                            </div>
                      <?php } ?>


                       <?php if($this_time_thread->type_production==1 && $this_time_thread->only_admin==1){ ?>
                        <div class="form-group" style="margin-top: 10px;">
                                <a href="<?php echo base_url()."admin/corders/remove_only_admin/".$this_time_thread->id; ?>">
                                <button
                                 type="button" 
                                 class="btn btn-md btn-danger">Show this to user</button>
                              </a>
                            </div>
                      <?php } ?>


                        


                        


                      </div>
                    </div>
                   <?php } ?>
                  </div>
                </div>


            <?php } ?> 


            <div class="timeline__group">
                  <span class="timeline__year time" aria-hidden="true"><?php echo date("F d"); ?></span>
                  <div class="timeline__cards">
                   
                    <div class="timeline__card card_timline">
                      <header class="card__header">
                        <time class="time" datetime="2008-02-02">
                          <span class="time__day"><?php echo date("H:i A"); ?></span>
                        </time>
                      </header>
                      <div class="card__content">
                        <form enctype="multipart/form-data" method="post" action="<?php echo base_url()."admin/corders/attach_file"; ?>">
                            
                            <div class="form-group">
                                <div class="controls">
                                    <textarea rows=3 placeholder="Conversation with design... Write down your comments ..." class="form-control form-control-line" name="desc" ><?php echo set_value("desc"); ?></textarea>
                                    <input type="hidden" name="is_customer" value="1" />
                                </div>
                            </div>

                            <?php $input="task_file"; ?>
                            <div class="form-group <?=(form_error($input) !='')?'error':'';?>">

                                    <div class="card-bodyd" style="padding: 0; display: block !important;">
                                        <h5>Attach File</h5>

                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" accept="image/*,audio/*,video/*,.doc,.docs,.docx,.xls,.xlsx,.txt,.pdf,.psd,.xd,.ai" />
                                        <input type="hidden" name="task_id" value="<?php echo $task->id; ?>" />
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                            </div>
                            <?php if(is_designer()){ ?>
                            <div class="form-group">
                              <h5 for="delivery">Is it a final delivery?</h5>
                              <div class="controls ">
                                  <div class="switchery-demo m-b-20">
                                      <input  name="delivery" value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                                  </div>
                              </div>
                            </div>
                          <?php } ?>
                            <div class="form-group">
                               
                                <button style="text-align: right; float: right;"  class="btn btn-primary">SEND YOUR COMMENT</button>
                            </div>
                        </form>
                      </div>
                    </div>
                   
                  </div>
                </div>
                </div>
            </div>
        </div>
        
       
    </div>
    <!-- <div class="col-lg-6 col-md-6 col-sm-6" style="    background: #fff;
    padding: 15px;">
      <h3 class="box-title">Chat with Designer</h3>
      <?php
      $project_id = $task->id;
       //require 'chat.php'; ?>
    </div> -->

   
</div>
<?php require 're_deliver.php'; ?>
<script type="text/javascript">
    // setTimeout(function(){
    // var objDiv = document.getElementById("timeline");
    // objDiv.scrollTop = objDiv.scrollHeight;

    // },1400)
    function attachFile()
    {

    }
</script>
<style type="text/css">
    /*
!!!!
This pen is being refactored
!!!!
*/

/*
=====
DEPENDENCES
=====
*/

/*
styles to reset headings https://github.com/melnik909/r-title
*/

.r-title{
  margin-top: var(--rTitleMarginTop, 0) !important;
  margin-bottom: var(--rTitleMarginBottom, 0) !important;
}

/* 
styles of typography  https://github.com/melnik909/css-typography
*/

p:not([class]){
  line-height: 1.78;
  margin-top: 1em;
  margin-bottom: 0;
}

p:not([class]):first-child{
  margin-top: 0;
}

/*
text component
*/

.text{
  display: inline-flex;
  font-size: 1rem;  
}

/*
time component
*/

/*
core styles
*/

.time{
  display:  inline-flex;
}

/*
extensions
*/

.time__month{
  margin-left:.25em;
}

/*
skin
*/

.time{
  padding:.25rem 1.25rem .25rem;
  background-color:#fb9678;

  font-size: .75rem;
  text-transform: uppercase;
  color: #fff;
}

/*
card component
*/

/*
core styles
*/

.card_timline{
  padding: 1.5rem 1.5rem 1.25rem;
}

.card__content{
  margin-top:.5rem;
}
.card__content h5 {font-size: 14px;}
.card__content p
{
    color: #333;
    font-style: italic;
    font-size: 13px;
}

/*
skin
*/

.card_timline{
  border-radius: 2px;
  border-left: 3px solid #fb9678;
  box-shadow:0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);
  background-color: #fff;
}

/*
extensions
*/

.card__title{
  --rTitleMarginTop: 1rem;
  font-size:1.25rem;
}

/*
=====
CORE STYLES
=====
*/

.timeline{
  display: grid;
  grid-row-gap: 2rem;
  /*max-height: 1500px;
  overflow-y: scroll;*/
}

/*
1. If timeline__year isn't displaed the gap between it and timeline__cards isn't displayed too
*/

.timeline__year{
  margin-bottom: 1.25rem; /* 1 */
}

.timeline__cards{
  display: grid;
  grid-row-gap: 1.5rem;
}


/*
=====
SKIN
=====
*/

.timeline{
  --uiTimelineMainColor: #222;
  --uiTimelineSecondaryColor:#fff;

  border-left: 3px solid #fb9678;
  padding-top: 1rem;
  padding-bottom: 1.5rem;
}

/*.timeline__year{
  --timePadding: .5rem 1.5rem;
  --timeColor: var(--uiTimelineSecondaryColor);
  --timeBackgroundColor: var(--uiTimelineMainColor);
  --timeFontWeight: var(--timelineYearFontWeight, 400);
}
*/
.timeline__card{
  position: relative;
  margin-left: 1rem;
}

/*
1. Stoping cut box shadow
*/

.timeline__cards{
  overflow: hidden;
  padding-top: .25rem; /* 1 */
  padding-bottom: .25rem; /* 1 */
}

.timeline__card::before{
      content: "";
    width: 1rem;
    height: 2px;
    background-color: #fb9678;
    position: absolute;
    top: 1rem;
    left: -1.2rem;
    z-index: 0;
}

/*
=====
SETTINGS
=====
*/
/**/
.timeline{
  --timelineMainColor: #4557bb;
}


</style>
<script type="text/javascript">
  function Reject(id)
  {
    $(".reject_reason"+id).slideToggle();
  }
  function Accept(id)
  {
    var x = confirm("Are you sure you want to accept this delivery?");
    if(x) window.location.href = '<?php echo base_url()."admin/corders/accept_delivery/".$task->id; ?>/'+id;
  }
</script>
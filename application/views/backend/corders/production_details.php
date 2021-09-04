
<?php //$order=$this->db->where("production_id",$this->session->userdata("admin_id"))->get("c_orders")->result_object()[0]; ?>
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
   
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Customized Orders Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Customized Orders</li>
                </ol>
              
            </div>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <?php if($order->production_status<2){ ?>
     <div class="row">
        <div class="col-12">
            <div id="time_count"></div>
        </div>
    </div>
<?php  }?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="easy" >
                    <div class="card-header" style="">
                        <h4 class="m-b-0 text-white">Customize Order Details <span style="float: right;"><?php echo is_designer()?"#00TS".$task->id:"#00CP".$order->id; ?></span>
                        </h4>

                    </div>
                    
                </div>
                
                <div class="card-body" >
                   
                    <h3 class=""><?php

                    $this_cat = $this->db->where('id',$order->cat_id)
                    ->get('categories')
                    ->result_object()[0];

                    $p_cat = $this->db->where('id',$this_cat->parent)
                    ->get('categories')
                    ->result_object()[0];

                    $customer = $this->db->where('id',$order->user_id)
                    ->get('users')
                    ->result_object()[0];

                     ?></h3>

                    <style type="text/css">
                        .table-custom {  width: 80%;
    font-size: 13px;
    text-transform: uppercase;
    text-align: right;
    border: 1px solid #ccc;
    padding: 5px;
    display: inline-table;}
                        .table-custom th {border: 1px solid #ccc;
    padding: 8px;
    width: 50%;
    background: #f0f0f0;
    font-weight: bold;}
                        .table-custom td {border: 1px solid #ccc;
    padding: 8px;
    width: 50%;
    }
    .table td {border: 1px solid #ccc;}
    .table th {border: 1px solid #ccc;
    
    width: 50%;
    background: #f0f0f0;
    font-weight: bold;}
    .box-title {text-transform: uppercase; font-weight: bold;}
    .left {float: left;}
   .box-title {font-size: 18px;}
                    </style>
                 
                    <div class="row">
                        <?php if(!is_designer()){ ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h2 class="">


                                <div class="col-md-5 left">
                                    <form 
                                    style=""
                                    method="post" 
                                    id="handleProuctionChangeForm"
                                    action="<?php echo base_url()."admin/corders/update_production_status"; ?>"
                                    >
                                <table class="table-custom" style=""> 
                                    <tr>
                                        <th>Change Status</th>
                                        <td style="flex-direction: row;display: flex;float: left;border:0; width: 100%"> 
                                            

                                            <?php
                                            $status = array(
                                                ""=>"--Update status--",
                                                "1"=>"Prepairing",
                                                "2"=>"Ready to Deliver",
                                                "3"=>"Delivered",
                                                "4"=>"Completed"
                                            );
                                             ?>
                                             <select
                                             id="handleProuctionChange"
                                              name="status" required>
                                                 <?php foreach($status as $k=>$v){ ?>
                                                    <option
                                                    <?php if($k==$order->production_status) echo "selected"; ?>
                                                     value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                                 <?php } ?>
                                             </select>
                                             <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">


                                            <button 
                                            type="button"
                                            onclick="handleProductionChangeStatus()"
                                            style="margin-left: 15px;" class="btn btn-sm btn-success">Update</button>
                                            
                                        </td>
                                    </tr>
                                </table>
                                </form>
                                </div>
                               
                                <div class="col-md-7 left">
                                <table class="table-custom" style="width: 80%;float: right;"> 
                                    <tr>
                                        <th>Status</th>
                                        <td> <?php  if($order->production_status == 0){?>
                                    <span style="color:orange;">Processing</span>
                                    <?php } if($order->production_status == 1){?>
                                    <span style="color:purple;">Prepairing</span>
                                    <?php } if($order->production_status == 2){?>
                                    <span style="color:red;">Ready to Deliver</span>
                                    <?php } if($order->production_status == 3){?>
                                        <span style="color:red;">Delivered</span>
                                    <?php } if($order->production_status == 4){?>
                                        <span style="color:green;">Completed</span>
                                    <?php } ?>
                                        </td>
                                    </tr>
                                   
                                    
                                    <tr>
                                        <th>Payment Status</th>
                                        <td>
                                           <?php if($order->total_arrived==0){ ?>
                                            <span class="btn btn-sm btn-danger">Not Paid</span>
                                        <?php } ?>


                                        <?php if($order->total_arrived<$order->all_total){ ?>
                                            <span class="btn btn-sm btn-warning">Partialy Paid</span>
                                        <?php } ?>

                                        <?php if($order->total_arrived>=$order->all_total){ ?>
                                            <span class="btn btn-sm btn-success">Fully Paid</span>
                                        <?php } ?>


                                        </td>
                                    </tr>
                                    
                                </table>
                                </div>
                            </h2>
                        </div>
                        <?php } ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            <h3 class="box-title m-t-20">General Info</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Category</th>
                                            <td>  <?php

                                    echo $p_cat->title .' / '.$this_cat->title;
                                     ?> </td>
                                        </tr>

                                        <tr>
                                            <th width="390">Qty.</th>
                                            <td> <?php echo $order->qty;?></td>
                                        </tr>

                                        <tr>
                                            <th width="390">Size (W*H*G)</th>
                                            <td> <?php echo $order->whg;?></td>
                                        </tr>


                                        <tr>
                                            <th width="390">Prints</th>
                                            <td> <?php echo $order->print_face_title;?></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <h3 class="box-title m-t-20">Graphical Info</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Logo Type</th>
                                            <td><?php echo $logo_type==1?"Logo Uploaded":"Create Logo"; ?></td>
                                        </tr>
                                        <?php if($logo_type==1){ ?>
                                            <tr>
                                                <th width="390">File:</th>
                                                <td> 
                                                    <a 
                                                    download
                                                    href="<?php echo base_url()."/resources/uploads/orders/".$order->file_name; ?>">
                                                    <?php  echo "<img src='".base_url()."/resources/uploads/orders/".$order->file_name."' width='50px' />"; ?>
                                                </a>



                                                </td>
                                            </tr>

                                        <?php }else{ ?>


                                        <tr>
                                            <th width="390">Logo Name</th>
                                            <td> <?php echo $order->logo_name;?></td>
                                        </tr>


                                        <tr>
                                            <th width="390">Description</th>
                                            <td> <?php echo $order->logo_descp;?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <th width="390">Color</th>
                                            <td> <?php 
                                                if($order->color_type==1)
                                                {
                                                    echo "HEX";
                                                    echo "<br>";
                                                    echo "<span style='width:10px; height:10px;background:".$order->color."' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                                                }
                                                else
                                                {
                                                    echo "CMYK";
                                                    echo "<br>";
                                                    echo "<span style='width:10px; height:10px;background:".$order->color."' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                                                    echo "<br>";

                                                    echo "C:".$order->color_c.' M:'.$order->color_m.' Y:'.$order->color_y.' K'.$order->color_k;
                                                }
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <th width="390">Notes</th>
                                            <td> <?php echo $order->notes;?></td>
                                        </tr>



                                        
                                    </tbody>
                                </table>
                            </div>

                            <h3 class="box-title m-t-20">Custom Options</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <?php 
                                    $options = json_decode($order->options);
                                    foreach($options as $option)
                                    {
                                        ?>

                                        <tr>
                                            <th><?php echo $option->title; ?></th>
                                            <td><?php echo $option->value; ?></td>
                                        </tr>
                                       <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        

                        <?php if($order->logo_type==1){ ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                            <h4 class="box-title m-t-20">Uploaded Logo</h4>

                            <div class="white-box text-center" style="width: auto;"> <img width="200px" src="<?php echo base_url()."resources/uploads/orders/".$order->file_name; ?>"> </div>

                        </div>
                        <?php } ?>

                       
                        
                    </div>

                </div>
            </div>
            
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->




</div>
<div id="readyToDeliver" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Please attach a picture:</h4>

        <button type="button" class="close" onclick="closeModal()" data-dismiss="modal">&times;</button>

      </div>
      <form 
       enctype="multipart/form-data"
      method="post" action="<?php echo base_url()."admin/corders/update_production_status"; ?>" >
      <div class="modal-body driver_modal_body">
        <?php $input="production_file"; ?>
        <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
            <div class="card-bodyd" style="padding: 0; display: block !important;">
                <h5>Attach File</h5>

                <input 
                type="file" 
                id="input-file-disable-remove" 
                class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" accept="image/*" />
                <div class="text-danger"><?php echo form_error($input);?></div>
                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                <input type="hidden" name="status" value="2" />
            </div>
        </div>
      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()" data-dismiss="modal">Later</button>
         <button  class="btn btn-primary" >Ready For Delivery</button>
      </div>
    </form>
    </div>
  </div>
</div>
<?php 
$order_id = $order->id;
include 'show_designers.php'; 
?>
<script src="https://cdn.rawgit.com/vuejs/vue/v1.0.24/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<?php if($order->production_status<2){ ?>
<script type="text/javascript">
  Vue.filter('zerofill', function (value) {
  //value = ( value < 0 ? 0 : value );
  return ( value < 10 && value > -1 ? '0' : '' ) + value;
});

var Tracker = Vue.extend({
  template: `
  <span v-show="show" class="flip-clock__piece">
    <span class="flip-clock__card flip-card">
      <b class="flip-card__top">{{current | zerofill}}</b>
      <b class="flip-card__bottom" data-value="{{current | zerofill}}"></b>
      <b class="flip-card__back" data-value="{{previous | zerofill}}"></b>
      <b class="flip-card__back-bottom" data-value="{{previous | zerofill}}"></b>
    </span>
    <span class="flip-clock__slot">{{property}}</span>
  </span>`, 
  props: ['property','time'],
  data: () => ({
    current: 0,
    previous: 0,
    show: false
  }),
  
  events: {
    time(newValue) {
      
      if ( newValue[this.property] === undefined ) { 
        this.show = false; 
        return;
      }
      
      var val = newValue[this.property];
      this.show = true;
      
      val = ( val < 0 ? 0 : val );
      
      if ( val !== this.current ) {
  
        this.previous = this.current;
        this.current = val;
  
        this.$el.classList.remove('flip');
        void this.$el.offsetWidth;
        this.$el.classList.add('flip');
      }
      
    }
  },

});
  

  
var el = document.getElementById('time_count');
// document.body.appendChild(el);

var Countdown = new Vue({
  
  el: el,
  
  template: ` 
  <div class="flip-clock" data-date="2017-02-11" @click="update">
    <tracker 
      v-for="tracker in trackers"
      :property="tracker"
      :time="time"
      v-ref:trackers
    ></tracker>
  </div>
  `,

  props: ['date','callback'],

  data: () => ({
    time: {},
    i: 0,
    trackers: ['Days', 'Hours','Minutes','Seconds'] //'Random', 
  }),

  components: {
    Tracker
  },
  
  beforeDestroy(){
    if ( window['cancelAnimationFrame'] ) {
      cancelAnimationFrame(this.frame);
    }
  },
  
  watch: {
    'date': function(newVal){
      this.setCountdown(newVal);
    }
  },
  
  ready() {
    if ( window['requestAnimationFrame'] ) {
      this.setCountdown(this.date);
      this.callback = this.callback || function(){};
      this.update();
    }
  },
  
  methods: {
    
    setCountdown(date){
      
      if ( date ) {
        this.countdown = moment(date, 'YYYY-MM-DD HH:mm:ss');
      } else {
        this.countdown = moment().endOf('day');  //this.$el.getAttribute('data-date');
      }

      this.countdown = moment("<?php echo $order->production_deadline; ?>",'YYYY-MM-DD HH:mm:ss')
    },
    
    update() {
      this.frame = requestAnimationFrame(this.update.bind(this));
      if ( this.i++ % 10 ) { return; }
      var t = moment(new Date());
      // Calculation adapted from https://www.sitepoint.com/build-javascript-countdown-timer-no-dependencies/
      if ( this.countdown ) { 
        
        t = this.countdown.diff(t);
        
        //t = this.countdown.diff(t);//.getTime();
        //console.log(t);
        this.time.Days = Math.floor(t / (1000 * 60 * 60 * 24));
        this.time.Hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        this.time.Minutes = Math.floor((t / 1000 / 60) % 60);
        this.time.Seconds = Math.floor((t / 1000) % 60);
      } else {
        this.time.Days = undefined;
        this.time.Hours = t.hours() % 13;
        this.time.Minutes = t.minutes();
        this.time.Seconds = t.seconds();
      }
      
      this.time.Total = t;
      
      this.$broadcast('time',this.time);
      return this.time;
    }
  }
});
</script>
<?php } ?>
<style type="text/css">
  
.flip-clock {
  text-align: center;
  -webkit-perspective: 600px;
          perspective: 600px;
  margin: 0 auto;
}
.flip-clock *,
.flip-clock *:before,
.flip-clock *:after {
  box-sizing: border-box;
}
.flip-clock__piece {
  display: inline-block;
  margin: 0 0.2vw;
}
@media (min-width: 1000px) {
  .flip-clock__piece {
    margin: 0 5px;
  }
}
.flip-clock__slot {
  font-size: 1rem;
  line-height: 1.5;
  display: block;
  /*
  //position: relative;
  //top: -1.6em;
  z-index: 10;
  //color: #FFF;
*/
}
.flip-card {
  display: block;
  position: relative;
  padding-bottom: 0.72em;
  font-size: 2.25rem;
  line-height: 0.95;
}
@media (min-width: 1000px) {
  .flip-clock__slot {
    font-size: 1.2rem;
  }
  .flip-card {
    font-size: 3rem;
  }
}
/*////////////////////////////////////////*/
.flip-card__top,
.flip-card__bottom,
.flip-card__back-bottom,
.flip-card__back::before,
.flip-card__back::after {
  display: block;
  height: 0.72em;
  color: #ccc;
  background: #222;
  padding: 0.23em 0.25em 0.4em;
  border-radius: 0.15em 0.15em 0 0;
  backface-visiblity: hidden;
  -webkit-transform-style: preserve-3d;
          transform-style: preserve-3d;
  width: 1.8em;
}
.flip-card__bottom,
.flip-card__back-bottom {
  color: #fff;
  position: absolute;
  top: 50%;
  left: 0;
  border-top: solid 1px #000;
  background: #393939;
  border-radius: 0 0 0.15em 0.15em;
  pointer-events: none;
  overflow: hidden;
  z-index: 2;
}
.flip-card__back-bottom {
  z-index: 1;
}
.flip-card__bottom::after,
.flip-card__back-bottom::after {
  display: block;
  margin-top: -0.72em;
}
.flip-card__back::before,
.flip-card__bottom::after,
.flip-card__back-bottom::after {
  content: attr(data-value);
}
.flip-card__back {
  position: absolute;
  top: 0;
  height: 100%;
  left: 0%;
  pointer-events: none;
}
.flip-card__back::before {
  position: relative;
  overflow: hidden;
  z-index: -1;
}
.flip .flip-card__back::before {
  z-index: 1;
  -webkit-animation: flipTop 0.3s cubic-bezier(0.37, 0.01, 0.94, 0.35);
          animation: flipTop 0.3s cubic-bezier(0.37, 0.01, 0.94, 0.35);
  -webkit-animation-fill-mode: both;
          animation-fill-mode: both;
  -webkit-transform-origin: center bottom;
          transform-origin: center bottom;
}
.flip .flip-card__bottom {
  -webkit-transform-origin: center top;
          transform-origin: center top;
  -webkit-animation-fill-mode: both;
          animation-fill-mode: both;
  -webkit-animation: flipBottom 0.6s cubic-bezier(0.15, 0.45, 0.28, 1);
          animation: flipBottom 0.6s cubic-bezier(0.15, 0.45, 0.28, 1);
}
@-webkit-keyframes flipTop {
  0% {
    -webkit-transform: rotateX(0deg);
            transform: rotateX(0deg);
    z-index: 2;
  }
  0%,
  99% {
    opacity: 1;
  }
  100% {
    -webkit-transform: rotateX(-90deg);
            transform: rotateX(-90deg);
    opacity: 0;
  }
}
@keyframes flipTop {
  0% {
    -webkit-transform: rotateX(0deg);
            transform: rotateX(0deg);
    z-index: 2;
  }
  0%,
  99% {
    opacity: 1;
  }
  100% {
    -webkit-transform: rotateX(-90deg);
            transform: rotateX(-90deg);
    opacity: 0;
  }
}
@-webkit-keyframes flipBottom {
  0%,
  50% {
    z-index: -1;
    -webkit-transform: rotateX(90deg);
            transform: rotateX(90deg);
    opacity: 0;
  }
  51% {
    opacity: 1;
  }
  100% {
    opacity: 1;
    -webkit-transform: rotateX(0deg);
            transform: rotateX(0deg);
    z-index: 5;
  }
}
@keyframes flipBottom {
  0%,
  50% {
    z-index: -1;
    -webkit-transform: rotateX(90deg);
            transform: rotateX(90deg);
    opacity: 0;
  }
  51% {
    opacity: 1;
  }
  100% {
    opacity: 1;
    -webkit-transform: rotateX(0deg);
            transform: rotateX(0deg);
    z-index: 5;
  }
}


</style>
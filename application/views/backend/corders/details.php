

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
    <?php
    if(!is_designer())
        $task = $this->db->where("order_id",$order->id)->order_by("id","DESC")->get("designer_tasks")->result_object()[0];
    if($task){
     if($task->status==0 || $task->status==3){ ?>
     <div class="row">
        <div class="col-12">
            <div id="time_count"></div>
        </div>
    </div>
<?php  }
}?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="easy" style="display: flex;flex-direction: row;">
                    <div class="card-header" style="flex: 0.9">
                        <h4 class="m-b-0 text-white">Customize Order Details <span style="float: right;"><?php echo is_designer()?"#00TS".$task->id:"#".$order->id; ?></span>
                        </h4>
                        <button  style="float: right;" class="btn btn-primay btn-sm" type="button" onclick="PrintElem('PrintElem')">Print</button>
                    </div>
                    <div 
                    onclick="toggleMe(this)" 
                    class="" style="
                    flex:0.1; 
                    background: #03a9f3;
                    display: flex;
                    align-items: center;justify-content: center;">
                        <span style="
                            font-weight: bold;font-size: 25px;color: #fff;
                            
                            "><?php echo $order->status==1?"-":"+"; ?></span>
                    </div>
                </div>
                
                <div class="card-body" style="display: <?php echo $order->admin_status==1?"block":"none"; ?>">
                   
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
                                    <h3 class="box-title" style="font-size: 15px;">Actions</h3>
                            <div class="table-responsive">
                                <form 
                                    method="post" 
                                    action="<?php echo base_url()."admin/corders/update_admin_status"; ?>"
                                    id="handleAdminChangeForm"
                                    >
                                <table class="table-custom">
                                    <tbody>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span style="color:orange;">(<?php echo admin_status($order->admin_status); ?>)</span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Change Status</th>
                                            <td>

                                


                                    <?php
                                    $status = admin_statuses($order->admin_status);
                                     ?>
                                     <select name="status" required id="handleAdminChange">
                                         <?php foreach($status as $k=>$v){
                                            
                                                //if($order->admin_status <= 4){break;}
                                          ?>
                                            <option
                                            <?php if($k==$order->admin_status) echo "selected"; ?>
                                             value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                         <?php } ?>
                                     </select>
                                     <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">


                                    <button style="margin-top: 5px;"
                                    type="button"
                                    onclick="handleAdminChangeStatus()"
                                     class="btn btn-sm btn-success">Update</button>
                                                
                                            </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                                </form>

                            </div>
                                </div>
                                <div class="col-md-7 left">
                                <table class="table-custom" style="width: 80%;float: right;"> 
                                    
                                    <tr>
                                    <th>Total</th>
                                    <td>
                                        <?php echo with_currency(number_format($order->all_total,2)); ?>
                                        <small class="text-success">(0% off)</small></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Arrived</th>
                                        <td>
                                            <span style="color:green;">+<?php echo with_currency(number_format($order->total_arrived,2)); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment Pending</th>
                                        <td style="background: #ffbcbc;"><span>-<?php echo with_currency(number_format($order->all_total-$order->total_arrived,2)); ?></span></td>
                                    </tr>
                                </table>
                                </div>
                            </h2>
                        </div>
                        <?php } ?>
                        <?php if(!is_designer()){ ?>
                        <div class="col-lg-6 col-md-6 col-sm-6" id="PrintElem">
                        <?php }else{ ?>
                        <div class="col-lg-12 col-md-12 col-sm-12"  id="PrintElem">
                        <?php } ?>

                            <h3 class="box-title m-t-20">General Info</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Item Name</th>
                                            <td>  <?php

                                    echo $order->c_title;
                                     ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>  <?php

                                    echo $p_cat->title .' / '.$this_cat->title;
                                     ?> </td>
                                        </tr>

                                        <tr>
                                            <th width="390">Qty.</th>
                                            <td> <?php echo $order->qty;?>
                                                
                                                <?php if($order->old_qty!="0"){echo '<i style="color:#ccc">'.$order->old_qty.'</i>';}?>

                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="390">Size</th>
                                            <td> <?php echo $order->whg;?></td>
                                        </tr>


                                        <tr>
                                            <th width="390">Prints</th>
                                            <td> <?php echo $order->print_face_title;?></td>
                                        </tr>
                                        <tr>
                                            <th width="390">Delivery Date</th>
                                            <td>
                                                <?php 
                                                $Date1 = $order->created_at; 
                                                $days = $this_cat->delivery." ".$this_cat->delivery_type;
                                                $Date2 = date('F, d Y', strtotime($Date1 . " + ".$days));
                                                echo $Date2;
                                                ?>
                                            </td>
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
                                            <td><?php 
                                            if($order->logo_type != 0)
                                            {
                                                echo $order->logo_type==1?"Logo Uploaded":"Create Logo"; 
                                            }
                                            else {
                                                echo "NO LOGO";
                                            }
                                            ?>                                                
                                            </td>
                                        </tr>
                                        <?php 
                                        if($order->logo_type != 0){
                                        if($order->logo_type==1){ ?>
                                            <tr>
                                                <th width="390">File:</th>
                                                <td> 
                                                    <a 
                                                    download
                                                    href="<?php echo base_url()."resources/uploads/orders/".$order->file_name; ?>">
                                                    <?php $ext = pathinfo($order->file_name, PATHINFO_EXTENSION);
                                                    if($ext!="jpg" && $ext!="jpeg" && $ext!="png"){?>
                                                        <img src='<?php echo base_url();?>resources/uploads/orders/PDF_file_icon.svg' width='50px' />
                                                    <?php }else {
                                                        echo "<img src='".base_url()."/resources/uploads/orders/".$order->file_name."' width='50px' />"; 
                                                    } ?>
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
                                        <?php } } ?>
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

                        <?php if(!is_designer()){ ?>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <h3 class="box-title m-t-20">Payment Info</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Items Cost</th>
                                            <td><?php echo with_currency(number_format($order->price,2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Logo Cost</th>
                                            <td><?php echo with_currency(number_format($order->logo_cost,2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Stamps Cost</th>
                                            <td><?php echo with_currency(number_format($order->stamps_cost,2)); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Shipping Cost</th>
                                            <td><?php echo with_currency(number_format($order->shipping,2)); ?></td>
                                        </tr>

                                        <tr>
                                            <th>VAT</th>
                                            <td><?php echo with_currency(number_format($order->vat,2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Down Payment</th>
                                            <td><?php echo with_currency(number_format($order->down_payment,2)); ?></td>
                                        </tr>
                                        <?php /* ?> Uncomment this if client asks
                                        <tr>
                                            <td>Part 1 Payment</td>
                                            <td><?php 
                                            if($order->payment_done_part_1) 
                                                echo with_currency($order->payment_arrived_part_1);
                                            else
                                                echo "<span style='color:red'>Pending</span>";
                                            ?></td>
                                        </tr>
                                        <tr>
                                            <td>Part 2 Payment</td>
                                            <td><?php 
                                            if($order->payment_done_part_2) 
                                                echo with_currency($order->payment_arrived_part_2);
                                            else
                                                echo "<span style='color:red'>Pending</span>";
                                            ?></td>
                                        </tr>

                                        <tr>
                                            <td>Total Payment</td>
                                            <td><?php echo with_currency($order->all_total);
                                            ?></td>
                                        </tr>
                                        <?php */ ?>
                                       

                                       <tr>
                                            <th>Total Payment Arrived</th>
                                            <td style="color:green;">+<?php echo with_currency(number_format($order->total_arrived,2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Total Payment Pending</th>
                                            <td style="color:red;">-<?php echo with_currency(number_format($order->all_total-$order->total_arrived,2)); ?></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                        <?php if($order->logo_type==1){ ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                    <h4 class="box-title m-t-20">Uploaded Logo</h4>

                                    <div class="white-box text-center" style="width: auto;"> 

                                        <a 
                                                    download
                                                    href="<?php echo base_url()."resources/uploads/orders/".$order->file_name; ?>">
                                                    <?php $ext = pathinfo($order->file_name, PATHINFO_EXTENSION);
                                                    if($ext!="jpg" && $ext!="jpeg" && $ext!="png"){?>
                                                        <img src='<?php echo base_url();?>resources/uploads/orders/PDF_file_icon.svg' width='80px' />
                                                    <?php }else {
                                                        echo "<img src='".base_url()."/resources/uploads/orders/".$order->file_name."' width='50px' />"; 
                                                    } ?>
                                                </a>



                                </div>
                                <?php } ?>
                            </div>
                           
                        </div>
                        <?php } ?>

                        <?php if($order->logo_type==1 && is_designer()){ ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                    <h4 class="box-title m-t-20">Uploaded Logo</h4>

                                    <div class="white-box text-center" style="width: auto;"> <img width="200px" src="<?php echo base_url()."resources/uploads/orders/".$order->file_name; ?>"> </div>

                                </div>
                                <?php } ?>

                       
                        
                    </div>

                    <?php $ship_details = $this->db->query("SELECT * FROM shipping_addresses WHERE  is_default = 1 AND user_id = ".$order->user_id)->result_object()[0];
                        if(!empty($ship_details)){
                    ?>
                    <h3 class="box-title m-t-20">Shipping Information</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Full Name</th>
                                            <td>  <?php echo $ship_details->first_name;?> <?php echo $ship_details->last_name;?> </td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td>  +<?php echo $ship_details->c_code .' '.$ship_details->phone;?> </td>
                                        </tr>

                                        <tr>
                                            <th width="390">Address</th>
                                            <td> <?php echo $ship_details->street;?></td>
                                        </tr>


                                        <tr>
                                            <th width="390">City/State</th>
                                            <td> <?php echo $ship_details->state;?></td>
                                        </tr>
                                        <tr>
                                            <th width="390">Country</th>
                                            <td>
                                                <?php echo $ship_details->country;?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    <?php } ?>
                </div>
            </div>
            
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->


    <?php if($order->admin_status>1){ ?>
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: transparent;">
                <div class="easy" style="display: flex;flex-direction: row;">
                    <div class="card-header" style="flex: 0.9">
                        <h4 class="m-b-0 text-white"><?php

                        if(is_designer()) echo "Designer to Admin";
                        elseif(is_production()) echo "Production to admin";
                        else echo "Admin to designer";
                        


                        $designer = $this->db->where("id",$task->designer_id)->get("admin")->result_object()[0];

                         ?> <span style="float: right;"><?php echo is_designer()?"#00TS".$task->id:"#".$order->id; ?></span>
                            
                            
                        </h4>

                    </div>
                    <div 
                    onclick="toggleMe(this)" 
                    class="" style="
                    flex:0.1; 
                    background: #03a9f3;
                    display: flex;
                    align-items: center;justify-content: center;">
                        <span style="
                            font-weight: bold;font-size: 25px;color: #fff;
                            
                            "><?php echo $order->status==2?"-":"+"; ?></span>
                    </div>
                </div>
                
                <div class="card-body" style="display: <?php echo $order->status==2?"block":"none"; ?> ;     padding: 0 10px;">
                   
                   <?php require 'designer_part.php'; ?>

                </div>
            </div>
            
        </div>
    </div>

    <?php } ?>

    <!-- <div class="card-body">
        <div class="text-xs-right">
            <a href="<?=$url;?>admin/c_orders" class="btn btn-info">Done</a>
        </div>
    </div> -->

    <?php if(!is_designer()){ ?>

    <div class="row">
        <div class="col-12">
            <div class="card" style="background: transparent;">
                <div class="easy" style="display: flex;flex-direction: row;">
                    <div class="card-header" style="flex: 0.9">
                        <h4 class="m-b-0 text-white">Admin to Customer: (<?php
                        


                        $designer = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];

                        echo $designer->first_name. ' ' .$designer->last_name;
                         ?>) <span style="float: right;"><?php echo "#".$order->id; ?></span>
                            
                            
                        </h4>

                    </div>
                    <div 
                    onclick="toggleMe(this)" 
                    class="" style="
                    flex:0.1; 
                    background: #03a9f3;
                    display: flex;
                    align-items: center;justify-content: center;">
                        <span style="
                            font-weight: bold;font-size: 25px;color: #fff;
                            
                            "><?php echo $order->status>2?"-":"+"; ?></span>
                    </div>
                </div>
                
                <div class="card-body" style="display: <?php echo $order->admin_status>2?"block":"none"; ?> ;     padding: 0 10px;">
                   
                   <?php require 'customer_part.php'; ?>

                </div>
            </div>
            
        </div>
    </div>
<?php } ?>


</div>
<?php 
$order_id = $order->id;
include 'show_designers.php'; 
?>
<script src="https://cdn.rawgit.com/vuejs/vue/v1.0.24/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<?php 

    // if($task)
    if($task->status==0 || $task->status==3){ 
?> 
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

      this.countdown = moment("<?php echo $task->deadline; ?>",'YYYY-MM-DD HH:mm:ss')
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
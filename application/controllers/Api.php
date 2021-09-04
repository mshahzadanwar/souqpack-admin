<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Twilio\Rest\Client;

/**
 * handles the admins
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Api extends ADMIN_Controller {
    private $guest_id;
    private $only_in_region = array();
    private $region_id=0;
    function __construct()
    {
        parent::__construct();


    }

    private function generateRandomString($length = 10) {
        $characters = '023456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function send_otp()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $country_code = $post->c_code;
        $phone = $post->phone;
       
        if(substr($phone,0,1)=="0")
        {
            $phone = ltrim($phone,'0');
        }

        $to = "+" . ( (string) $country_code) . $phone;
        


        // Your Account SID and Auth Token from twilio.com/console
        $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
        
        $sid = $twillio_db->twillio_pub;
        $token = $twillio_db->twillio_sec;
        
        $twilio = new Client($sid, $token);
       
        try{

        $service = $twilio->verify->v2->services
                                      ->create("SouqPack verification service");

        $verification = $twilio->verify->v2->services($service->sid)
                                   ->verifications
                                   ->create($to, "sms");
                                   
        }
        catch(Exception $e)
        {
            echo json_encode(array("action"=>"failed","error"=>$e->getMessage()));
            return;
        }

        
        $this->db->where( array("code"=>$post->c_code,"phone"=>$post->phone))->delete("temp_phones");
        $this->db->insert("temp_phones",
            array(
                "sid"=>$service->sid,
                "code"=>$post->c_code,
                "phone"=>$post->phone,
                "code_text"=>$post->c_code_text
            )
        );

        echo json_encode(array("action"=>"success","slip"=>$this->db->insert_id()));
        
    }
    
    // public function resend_otp()
    // {
    //     $post = json_decode(file_get_contents("php://input"));
    //     if(empty($post))
    //     $post = (object) $_POST;

    //     $user = $this->do_auth($post);


    //     $sid = $user->sid;

    //     if(!$sid)
    //     {
    //         echo json_encode(array("action"=>"failed","err"=>"Invalid request"));
    //         return;
    //     }

    //     $country_code = $user->country_code;
    //     $phone =$user->phone;

    //     $to = "+" . ( (string) $user->country_code) . $user->phone;
        


    //     // Your Account SID and Auth Token from twilio.com/console
    //     $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
    //     $sid = $twillio_db->twillio_pub;
    //     $token = $twillio_db->twillio_sec;

    //     $twilio = new Client($sid, $token);


    //     try{

    //     $service = $twilio->verify->v2->services
    //                                   ->create("Cute Shop verification service");

    //     $verification = $twilio->verify->v2->services($service->sid)
    //                                ->verifications
    //                                ->create($to, "sms");
    //     }
    //     catch(Exception $e)
    //     {
    //         echo json_encode(array("action"=>"failed","err"=>"Sorry, twillio seems busy"));
    //         return;
    //     }
    //     $this->db->where( array("code"=>$post->c_code,"phone"=>$post->phone))->delete("temp_phones");
    //     $this->db->insert("temp_phones",
    //         array(
    //             "sid"=>$service->sid,
    //             "code"=>$post->c_code,
    //             "phone"=>$post->phone,
    //             "code_text"=>$post->c_code_text
    //         )
    //     );



    //     echo json_encode(array("action"=>"success","slip"=>$this->db->insert_id()));
        
    // }

    public function verify_otp()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        

        $slip = $post->slip;

        $temp_phone = $this->db->where("id",$slip)->get("temp_phones")->result_object()[0];

        

        $s_id = $temp_phone->sid;
        $code = $post->code;


      
        if(!$s_id){
            echo json_encode(array("action"=>"failed","error"=>"Invalid request"));
            return;
        }


        $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
        $sid = $twillio_db->twillio_pub;
        $token = $twillio_db->twillio_sec;
        $twilio = new Client($sid, $token);

        try{

        $verification_check = $twilio->verify->v2->services($s_id)
                                         ->verificationChecks
                                         ->create($code, array('to'=>'+'.$temp_phone->code . $temp_phone->phone));
        }
        catch(Exception $e)
        {
            echo json_encode(array("action"=>"failed","error"=>$e->getMessage()));
            return;
        }    

        if($verification_check->status=="approved")
        {

            $user = $this->db->where(array("code"=>$temp_phone->code,"phone"=>$temp_phone->phone))->get("users")->result_object()[0];

            if(!$user)
            {
                $arr = array(
                    "code"=>$temp_phone->code,
                    "phone"=>$temp_phone->phone,
                    "code_text"=>$temp_phone->code_text,
                    "is_guest"=>0
                );

                if($post->is_guest==1)
                {
                    $user_logged = $this->do_auth($post);
                    $this->db->where("id",$user_logged->id)->update("users",$arr);

                    $user_id = $user_logged->id;
                }
                else{
                    $this->db->insert("users",$arr);

                    $user_id = $this->db->insert_id();
                }

                
            }
            else{
                $user_id = $user->id;
            }
            $this->db->where( array("code"=>$temp_phone->c_code,"phone"=>$temp_phone->phone))->delete("temp_phones");

            $this->do_sure_login($user_id);
        }else{
            echo json_encode(array("action"=>"failed","err"=>"Invalid OTP Code"));
            return;
        }
    }
    

    public function update_phone_verify_otp()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $slip = $post->slip;

        $temp_phone = $this->db->where("id",$slip)->get("temp_phones")->result_object()[0];

        $s_id = $temp_phone->sid;
        $code = $post->code;

        if(!$s_id){
            echo json_encode(array("action"=>"failed","error"=>"Invalid request"));
            return;
        }

        $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
        $sid = $twillio_db->twillio_pub;
        $token = $twillio_db->twillio_sec;
        $twilio = new Client($sid, $token);

        try{

        $verification_check = $twilio->verify->v2->services($s_id)
                                         ->verificationChecks
                                         ->create($code, array('to'=>'+'.$temp_phone->code . $temp_phone->phone));
        }
        catch(Exception $e)
        {
            echo json_encode(array("action"=>"failed","error"=>$e->getMessage()));
            return;
        }    

        if($verification_check->status=="approved")
        {
            // $old_data = $this->db->query("SELECT * FROM ")

            $user = $this->db->where(array("phone"=>$post->old_phone))->get("users")->result_object()[0];

                  $arr = array(
                        "code"=>$temp_phone->code,
                        "phone"=>$temp_phone->phone,
                        "code_text"=>$temp_phone->code_text,
                        "is_guest"=>0
                    );

                    
                    $this->db->where("phone",$post->old_phone)->update("users",$arr);

                    // $user_logged = $this->do_auth($post);
                    $user_id = $user->id;
               
                $this->db->where( array("code"=>$temp_phone->c_code,"phone"=>$temp_phone->phone))->delete("temp_phones");

                $this->do_sure_login($user_id);
                // echo json_encode(array("action"=>"success"));
            return;
        }else{
            echo json_encode(array("action"=>"failed","err"=>"Invalid OTP Code"));
            return;
        }
    }

   
    public function signup()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $data = array(
            "email"=>$post->email,
            "first_name"=>$post->firstname,
            "last_name"=>$post->lastname,
            "password"=> md5($post->password),
            "code"=>$post->code_country,
            "phone"=>$post->phone_number,
            "created_at"=>date("Y-m-d H:i:s")
        );


        if($data["first_name"]=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"First name is required",
                "error_type"=>1
            ));
            return;
        }
        if($data["last_name"]=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Last name is required",
                "error_type"=>1
            ));
            return;
        }
        
        
        
        $does_email_exists = $this->db->where('email',$data['email'])->count_all_results('users')>0?true:false;

        

        if($data["email"]=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Email is required",
                "error_type"=>1
            ));
            return;
        }

        if($does_email_exists)
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Email already exists, please choose different one",
                "error_type"=>1
            ));
            exit;
        }
        
        
        
        if($data["password"]=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Please enter password",
                "error_type"=>1
                
            ));
            return;
        }



        $this->db->insert('users',$data);
        $id = $this->db->insert_id();
        
        // SEND SMS
        if($post->phone_number!=""){
            if($post->lang_id == 1){
                $send_mobile_desc = "SouqPack - تم إنشاء حسابك بنجاح.";
            }
            else{
                $post = "SouqPack - Your account has been successfully created.";
            }
            
            $phone_sms = "+".$post->code_country.$post->phone_number;
            $this->send_sms_to_users($phone_sms,$send_mobile_desc);
        }

        $rand = 1234;

        $this->do_sure_login($id);
    }
    public function do_social_auth()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        
        $data = array(

            "email"=>$post->email,
            "fullname"=>$post->username,
            "signup_type"=>$post->signup_type,
            "fb_id"=>$post->fb_id,
            "g_id"=>$post->g_id,
            "password"=> md5($post->username . time() . rand(05405,4594059)),
            "created_at"=>date("Y-m-d H:i:s"),

        );
        
        
        
        $already_user = $this->db->where('email',$data['email'])->get('users')->result_object()[0];
        
        if($already_user)
        {
            $this->do_sure_login($already_user->id,true);
            return;
        }
        

    
        

        $this->db->insert('users',$data);

        

        $id = $this->db->insert_id();
       
        
        

        $this->do_sure_login($id);

        
    }
    public function social_fb()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        
        $data = array(

            "email"=>$post->email,
            "first_name"=>$post->first_name,
            "last_name"=>$post->last_name,
            "signup_type"=>$post->signup_type,
            "fb_id"=>$post->fb_id,
            "g_id"=>$post->g_id,
            "password"=> md5($post->username . time() . rand(05405,4594059)),
            "created_at"=>date("Y-m-d H:i:s"),

        );
        
        
        
        $already_user = $this->db->where('email',$data['email'])->get('users')->result_object()[0];

        if($already_user)
        {
            //echo "here";
            $this->do_sure_login($already_user->id,true);
            return;
        }
        //echo "No account";
        //die;

    
        

        $this->db->insert('users',$data);

        

        $id = $this->db->insert_id();
       
        
        

        $this->do_sure_login($id);

        
    }
    
    
 

    public function forgot_pw()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $email = $post->username;

        $user_logged = $this->db->where('email',$email)->get('users')->result_object();

        if(empty($user_logged))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"It seems this email is not registered with us",
                "email"=>$email,
            ));
            return;
        }
        $user_logged=$user_logged[0];

        // print_r($post);exit;

        // print_r(file_get_contents("php://input"));exit;
        

        $new_pass = $this->generateRandomString(6);



        $mmmsg = "Hi ".$user_logged->username.", your new password for SouqPack is: ".$new_pass;


        $this->load->library('email');

        $this->email->from(settings()->email, 'SouqPack');
        $this->email->to($email);

        $this->email->subject("Password Reset Request");
        $this->email->message($mmmsg);

        $x = $this->email->send();
        


        if($x)
        $this->db->where('id',$user_logged->id)->update('users',array("password"=>md5($new_pass)));

        

        echo json_encode(array("action"=>"success","error"=>"Your password has been sent successfully","q"=>$this->db->last_query()));
        return;

        
    }

    

    public function store_push_id()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $push_id = $post->notif_key;

        $user_logged = $this->do_auth($post);
        $data["push_id"] = $push_id;
        $this->db->where('id',$user_logged->id)->update('users',$data);
        echo json_encode(array("action"=>"success","error"=>"Your push_id has been updated successfully","q"=>$this->db->last_query()));
        return;
    }
    

    

    private function do_sure_login($id)
    {
        $user = $this->db->where('id',$id)->get('users')->result_object();

        if(empty($user))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"

            ));
            return;
        }

        $user = $user[0];

        $login_data = array(
            "api_logged_sess"=>md5(guid()),
            "api_logged_time"=>date("Y-m-d H:i:s")
        );

        $this->db->where('id',$id)->update('users',$login_data);


        $this->print_user_data($id);
    }
    public function get_shipping_address_user(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        
        $user = 
        $this->db->where('api_logged_sess',$post->token)
        ->where('status',1)
        ->where('is_deleted',0)
        ->get('users')
        ->result_object()[0];
        
        $ship_check = $this->db->query("SELECT * FROM shipping_addresses WHERE user_id = ".$user->id." ORDER BY id DESC LIMIT 1")->result_object()[0];

            $shipping_address = array(
                                "firstname" => $ship_check->first_name,
                                "lastname" => $ship_check->last_name,
                                "address" => $ship_check->address?$ship_check->address:$ship_check->street,
                                "city"      => $ship_check->city,
                                "zip" => $ship_check->zip,
                                "state" => $ship_check->state,
                                "phone" => $ship_check->phone,
                                "email" => $ship_check->email,
                            );
            echo json_encode(array("action"=>"success",
            "data"=>$shipping_address));
    }

     public function get_phone_picker(){
        
        $countries = $this->db->query("SELECT * FROM countries")->result_object();
        echo json_encode(array("action"=>"success",
            "data"=>$countries));
    }

    private function print_user_data($id)
    {

        $user = $this->db->where('id',$id)->get('users')->result_object();

        if(empty($user))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"

            ));
            return;
        }

        $about_page = $this->db->where("slug","about")->get("pages")->result_object()[0];
        $settings = $this->db->get("settings")->result_object()[0];

        $user = $user[0];

        // echo "<pre>";
        // print_r($user);
        if($user->first_name == ""){
            $user_form = 1;
        }
        else if($user->last_name == ""){
            $user_form = 1;
        }
        else if($user->email == ""){
            $user_form = 1;
        } else{
            $user_form = 0;
        }
        // echo $user_form;
        $this->db->where("guest_id",$user->id)->delete("users");
        // die;
        echo json_encode(array(
            "action"=>"success",
            "data"=>array(
                    "id"=>$user->id,
                    "region"=>$user->region,
                    "token"=>$user->api_logged_sess,
                    "email"=>$user->email,
                    "gender"=>$user->gender,
                    "first_name"=>$user->first_name,
                    "last_name"=>$user->last_name,
                    "code"=>$user->code,
                    "acct_type"=>$user->acct_type,
                    "code_text"=>$user->code_text,
                    "phone"=>$user->phone,
                    "created_at"=>$user->created_at,
                    "about_page"=>$about_page->descriptions,
                    "contact_email"=>$settings->email,
                    "is_guest"=>$user->is_guest,
                    "guest_id"=>$user->guest_id
                            
                    ),
                "user_form" => $user_form,
                )
            );
    }
    
    private function currency()
    {
        $settings = $this->db->get("settings")->result_object()[0];

        return $settings->currency;
    }

    

    
   
    public function login()
    {
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user = 
        $this->db->group_start()
        ->where('email',$post->email)
        ->group_end()
        ->group_start()
        ->where('password',md5($post->password))
        ->where('status',1)
        ->where('is_deleted',0)
        ->group_end()
        ->get('users')
        ->result_object();

        if(empty($user))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }

        $this->do_sure_login($user[0]->id);
    }


   
    
    private function do_auth($post,$force=true)
    {

        if($post->is_guest==1)
        {
            $user = 
            $this->db->where('guest_id',$post->guest_id)
            ->get('users')
            ->result_object();




            if($force==true)
            if(empty($user) || $post->is_guest=="")
            {
                echo json_encode(array(
                    "action"=>"failed",
                    "error"=>"Invalid login credentials"
                ));
                exit;
            }

            if($user[0]->region_id==0)
            {
                $all_regions = $this->db->where("status",1)->where("is_deleted",0)->get("regions")->result_object()[0];

                $this->db->where("id",$user[0]->id)->update("users",array("region_id"=>$all_regions->id));

                $user[0]->region_id = $all_regions->id;
            }

            $this->guest_id = $user[0]->id;
            if($post->region_id=="")
            $this->region_id = $user[0]->region_id;

            $this->only_in_ids = $this->only_in();

            return $user[0];
        }



        $user = 
        $this->db->where('api_logged_sess',$post->token)
        ->where('status',1)
        ->where('is_deleted',0)
        ->get('users')
        ->result_object();
        $this->guest_id = $user[0]->id;


        if($user[0]->region_id==0)
        {
            $all_regions = $this->db->where("status",1)->where("is_deleted",0)->get("regions")->result_object()[0];

            $this->db->where("id",$user[0]->id)->update("users",array("region_id"=>$all_regions->id));

            $user[0]->region_id = $all_regions->id;
        }

        if($post->region_id=="")
        $this->region_id = $user[0]->region_id;
        $this->only_in_ids = $this->only_in();
        if($force==true)
        if(empty($user) || $post->token=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }

        return $user[0];
    }


    public function check_login()
    {
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

//      echo json_encode(array($post->token));exit;

        $user = 
        $this->db->where('api_logged_sess',$post->token)
        ->where('status',1)
        ->where('is_deleted',0)
        ->get('users')
        ->result_object();

        if(empty($user) || $post->token=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }
        $user=$user[0];

        $this->print_user_data($user->id);

    }
   

    public function public_image()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $final = $_FILES;
        $final_2 = $_POST;
        $final_3 = $post;



        $config['upload_path']          = './resources/uploads/posts/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000;
        $config['max_width']            = 2000;
        $config['max_height']           = 2000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('photo'))
        {
            echo json_encode(array("action"=>"failed","error"=>strip_tags($this->upload->display_errors())));
        }
        else
        {
            $data = $this->upload->data();

            echo json_encode(array("action"=>"success","error"=>"done","filename"=>$data["file_name"]));
        }
    }

    public function logo_file()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
            $post = (object) $_POST;

        $final = $_FILES;
        $final_2 = $_POST;
        $final_3 = $post;
//   

//                 /orrders
        $config['upload_path']          = './resources/uploads/orders';
        $config['allowed_types']        = 'jpeg|jpg|png|pdf|psd';
        $config['max_size']             = 10000;
        $config['max_width']            = 20000;
        $config['encrypt_name']            = true;
        $config['max_height']           = 20000;
        
      
        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('photo'))
        {
            echo json_encode(array("action"=>"failed","error"=>strip_tags($this->upload->display_errors())));
        }
        else
        {
            $data = $this->upload->data();
            $ext = pathinfo($data['file_name'], PATHINFO_EXTENSION);
            echo json_encode(array("action"=>"success","error"=>"done","filename"=>$data["file_name"],"ext"=>$ext));
        }
    }
    public function review_image()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $final = $_FILES;
        $final_2 = $_POST;
        $final_3 = $post;



        $config['upload_path']          = './resources/uploads/reviews/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000;
        $config['max_width']            = 2000;
        $config['max_height']           = 2000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('photo'))
        {
            echo json_encode(array("action"=>"failed","error"=>strip_tags($this->upload->display_errors())));
        }
        else
        {
            $data = $this->upload->data();
            
            echo json_encode(array("action"=>"success","error"=>"done","filename"=>$data["file_name"],"extension"=>$ext));
        }
    }
    
    public function get_important_data()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $settings=$this->db->get("settings")->result_object()[0];


        
        $lang = $this->db->where('id',$post->clang?$post->clang:$post->lang_id)->where('status',1)->get("languages")->result_object()[0];
        if(!$lang){
            if($post->tmobile=1){
                 $lang = dlang_guest();
                 $force_start = 1;
            } else{
                $lang = dlang();
                $force_start=0;
            }
        }
        $regions = $this->db->where("status",1)->where("is_deleted",0)->where("lparent",0)->get("regions")->result_object();

        
        // $regions = $this->enrich_regions($regions,$lang_id);

        $final_regions = array();

        // $final_regions[] = array(
        //         "id"=>0,
        //         "currency"=>get_currency($lang->slug),
        //         "value"=>"Default"
        //     );

        foreach($regions as $key=>$region)
        {
            $title_re = $region->title;
            if($post->lang_id == 1){
                $regions_ar = $this->db->where("status",1)->where("is_deleted",0)->where("lang_id",$post->lang_id)->where("lparent",$region->id)->get("regions")->result_object()[0];
                $title_re = $regions_ar->title;
            }
            $final_regions[] = array(
                "id"=>$region->id,
                "currency"=>$region->currency,
                "value"=>$title_re,
                "slug"=>$region->slug,
            );
        }

        
        $currency = get_currency($lang->slug);

        if($post->token!="")
        {
            $user = $this->db->where("api_logged_sess",$post->token)->get("users")->result_object()[0];
            $currency = $this->db->where("id",$user->region_id)->get("regions")->result_object()[0];

            if($currency->lang_id!=$lang->id){
                $this->db->where("lang_id",$lang->id);
                $this->db->group_start();
                $this->db->where("lparent",$currency->id);
                $this->db->or_where("id",$currency->lparent);
                $this->db->group_end();

                $currency = $this->db->get("regions")->result_object()[0];
            }
            $currency =$currency->currency;
        }

        $footers = $this->db->where("lang_id",$lang->id)->get("footers")->result_object();

        $r_footers = array();

        foreach($footers as $footer)
        {
            $r_footers[] = array(
                "title"=>$footer->title,
                "body"=>$footer->body,
            );
        }
      

        $imp_data = array(
            "currency"=>$currency,
            "title"=>$settings->site_title,
            "title_ar"=>$settings->site_title_ar,

            "footer_about"=>$settings->footer_about,
            "footer_about_ar"=>$settings->footer_about_ar,


            "logo"=>base_url()."resources/uploads/logo/".$settings->site_logo,
            "favicon"=>base_url()."resources/uploads/favicon/".$settings->site_favicon,
            "currency_position"=>$settings->currency_position,
            "currency_space"=>$settings->currency_space,
            // "shipping_fee"=>$settings->shipping_fee,
            // "tax"=>$settings->tax,
            "shipping_fee"=>0,
            "tax"=>0,
            "snapchat"=>$settings->snapchat,
            "instagram"=>$settings->instagram,
            "facebook"=>$settings->facebook,
            "twitter"=>$settings->twitter,
            "youtube"=>$settings->youtube,
            "langs"=>langs(),
            "phone"=>$settings->mobile,
            "help"=>$settings->mobile,
            "email"=>$settings->email,
            "address"=>$settings->address,
            "copy_right"=>$settings->copy_right,
            "regions"=>$final_regions,
            "active_lang"=>$lang,
            "footers"=>$r_footers,
            "meta_en"=>$settings->meta_title_en,
            "meta_ar"=>$settings->meta_title_ar,
            "meta_des_en"=>$settings->meta_desc_en,
            "meta_des_ar"=>$settings->meta_desc_ar,
            "meta_keys_en"=>$settings->meta_keys_en,
            "meta_keys_ar"=>$settings->meta_keys_ar,
            
        );
        //print_r($imp_data);
        echo json_encode(array("action"=>"success",
            "data"=>$imp_data,"force_start"=>$force_start));
    }



    
    public function get_regions()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;



        $regions = $this->db->where("status",1)->where("is_deleted",0)->where("lparent",0)->get("regions")->result_object();

        $regions = $this->enrich_regions($regions,$lang_id);

        // $final[] = array("id"=>0,
        //     "currency"=>get_currency($lang->slug),
        //     "value"=> $lang_id==2?"Default":"إفتراضي" );

        foreach($regions as $key=>$region)
        {
            $final[] = array("id"=>$region->id,"value"=>$region->title);
        }


          echo json_encode(
            array("action"=>"success",
            "data"=>array("regions"=>$final)));

    }
    private function enrich_regions($regions,$lang_id)
    {

        foreach($regions as $key=>$region){

            $this->db->group_start();
            $this->db->where("lang_id",$lang_id);
            $this->db->group_end();
            $this->db->group_start();
            $this->db->where("id",$region->id);
            $this->db->or_where("lparent",$region->id);
            $this->db->group_end();

            $real_id = $lang_region->id;

            $lang_region = $this->db->get("regions")->result_object()[0];

            $boss_id = $lang_region->lparent==0?$lang_region->id:$lang_region->lparent;

            $boss_region = $lang_region;

            if($lang_region->lparent!=0)
            {
                $boss_region =  $this->db->where("id",$lang_region->lparent)->get("regions")->result_object()[0];
            }

            $regions[$key]->title = $lang_region->title!=""?$lang_region->title:$boss_region->title;

            $regions[$key]->id = $boss_id;
            $regions[$key]->real_id = $real_id;

        }

        return $regions;

    }
    private function get_unit_text($unit_id, $lang_id){


        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("id",$unit_id);
        $this->db->or_where("lparent",$unit_id);
        $this->db->group_end();



        $real_id = $lang_slider->id;

        $lang_slider = $this->db->get("quantity_units")->result_object()[0];

        $boss_id = $lang_slider->lparent==0?$lang_slider->id:$lang_slider->lparent;

        $boss_slider = $lang_slider;

        if($lang_slider->lparent!=0)
        {
            $boss_slider =  $this->db->where("id",$lang_slider->lparent)->get("sliders")->result_object()[0];
        }

        return $lang_slider->title==""?$lang_slider->title:$boss_slider->title;

    }
    private function enrich_sliders($sliders,$lang_id=2)
    {
        if(!$lang_id)  $lang_id = dlang()->id;
        foreach($sliders as $key=>$slider){

            $this->db->group_start();
            $this->db->where("lang_id",$lang_id);
            $this->db->group_end();
            $this->db->group_start();
            $this->db->where("id",$slider->id);
            $this->db->or_where("lparent",$slider->id);
            $this->db->group_end();

            $real_id = $lang_slider->id;

            $lang_slider = $this->db->get("sliders")->result_object()[0];
            
           

            $boss_id = $lang_slider->lparent==0?$lang_slider->id:$lang_slider->lparent;

            $boss_slider = $lang_slider;

            if($lang_slider->lparent!=0)
            {
                $boss_slider =  $this->db->where("id",$lang_slider->lparent)->get("sliders")->result_object()[0];
            }

            $sliders[$key]->image = $lang_slider->image!=""?$lang_slider->image:$boss_slider->image;

            $sliders[$key]->id = $boss_id;
            $sliders[$key]->real_id = $real_id;

        }

        return $sliders;

    }
    public function get_categories()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;


       // $lang_id = 1;
        
        if(!$lang_id) $lang_id = dlang()->id;

        $this->db->where("lparent",0);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $categories = $this->db->get('categories')->result_object();

        $categories = $this->enrich_categories($categories,$lang_id);

        

        echo json_encode(array("data"=>array("categories"=>$categories),"action"=>"success"));
    }
    public function get_categories_mobile_click()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;


       // $lang_id = 1;
        
        if(!$lang_id) $lang_id = dlang()->id;

        $this->db->where("id",$post->id);
        $categories = $this->db->get('categories')->result_object();

        $categories = $this->enrich_categories($categories,$lang_id);
        echo json_encode(array("data"=>$categories,"action"=>"success"));
    }
    private function get_cat_id_n_title($cat_id,$lang_id){

        $fallback = $this->db->where("id",$cat_id)->get("categories")->result_object()[0];

        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("id",$cat_id);
        $this->db->or_where("lparent",$cat_id);
        $this->db->group_end();

        $cat = $this->db->get("categories")->result_object()[0];

        return (Object) array(
            "id"=>$cat->lparent==0?$cat->id:$cat->lparent,
            "title"=>$cat->title!=""?$cat->title:$fallback->title

        );
    }
    private function enrich_categories($categories,$lang_id)
    {
        $return = array();
        foreach($categories as $key=>$category)
        {
            $en_category = $category;

            $this->db->group_start();
            $this->db->where("lang_id",$lang_id);
            $this->db->group_end();
            $this->db->group_start();
            $this->db->where("id",$category->id);
            $this->db->or_where("lparent",$category->id);
            $this->db->group_end();

            $real_id = $category->id;

            $category = $this->db->get("categories")->result_object()[0];


            if($category->lang_id==2)
            {
                $boss_category = $category;
            }
            else
            {
                $boss_category = $this->db->where("id",$category->lparent)->get("categories")->result_object()[0];
                //echo $this->db->last_query();
            }

            //die;

            $one = $boss_category;

            if($one->lparent==0)
            {
                $two = $this->db->where("lparent",$one->id)->get("categories")->result_object()[0];
            }
            else
            {
                $two = $this->db->where("id",$one->lparent)->get("categories")->result_object()[0];
            }


            $boss_id = $category->lparent==0?$category->id:$category->lparent;


            $categories[$key]->real_id = $real_id;
            if($category->lparent!=0)
            {
                $categories[$key]->id = $category->lparent;
            }


            $sub_returnn = (Object) array(
                "id"=>$boss_category->id,
                "cust"=>$boss_category->cust==1?"1":"0",
                "cust_image"=>$boss_category->cust_image,

                "note_text_en"=>$boss_category->note_text_en,
                "note_text_ar"=>$boss_category->note_text_ar,
                "terms_en"=>$boss_category->terms_en,
                "terms_ar"=>$boss_category->terms_ar,

                "logo_price"=>$boss_category->logo_price,


                "min_qty"=>$boss_category->min_qty,
                "pc_price"=>$boss_category->pc_price,
                "stamps_price"=>$boss_category->stamps_price,

                "faces"=>$boss_category->faces,
                "faces_price"=>$boss_category->faces_price,
                "faces_from"=>$boss_category->faces_from,
                "faces_to"=>$boss_category->faces_to,

                "colors"=>$boss_category->colors,
                "colors_from"=>$boss_category->colors_from,
                "colors_to"=>$boss_category->colors_to,
                "colors_price"=>$boss_category->colors_price,

                "sides"=>$boss_category->sides,
                "sides_price"=>$boss_category->sides_price,

                "base"=>$boss_category->base,
                "base_price"=>$boss_category->base_price,

                "height"=>$boss_category->height,
                "width"=>$boss_category->width,


                "shipping"=>$boss_category->shipping,
                "vat"=>$boss_category->vat,
                "delivery"=>$boss_category->delivery,
                "delivery_type"=>$boss_category->delivery_type,
                "en_id"=>$one->lang_id==2?$one->id:$two->id,
                "slug"=>$boss_category->slug.'-'.$boss_category->id,
                "real_id"=>$category->id,
                "title"=>$category->title!=""?$category->title:$en_category->title,
                "image"=>$category->image!=""?$category->image:$en_category->image,
                "meta_title"=>$category->meta_title!=""?$category->meta_title:$en_category->meta_title,
                "meta_keywords"=>$category->meta_keywords!=""?$category->meta_keywords:$en_category->meta_keywords,
                "meta_description"=>$category->meta_description!=""?$category->meta_description:$en_category->meta_description,
            );


            $this->db->where("lparent",0);
            $this->db->where("status",1);
            $this->db->where("parent",$boss_id);
            $this->db->where("is_deleted",0);
            $subs = $this->db->get('categories')->result_object();

          
            $subs_return = array();
            foreach($subs as $subs_key=>$subs_category)
            {

                $en_subs_category = $subs_category;


                $one = $subs_category;

                if($one->lparent==0)
                {
                    $two = $this->db->where("lparent",$one->id)->get("categories")->result_object()[0];
                }
                else
                {
                    $two = $this->db->where("id",$one->lparent)->get("categories")->result_object()[0];
                }

                $real_id = $subs_category->id;

                $this->db->group_start();
                $this->db->where("lang_id",$lang_id);
                $this->db->group_end();
                $this->db->group_start();
                $this->db->where("id",$subs_category->id);
                $this->db->or_where("lparent",$subs_category->id);
                $this->db->group_end();

                $subs_category = $this->db->get("categories")->result_object()[0];
               
                $subs[$subs_key]->real_id = $subs_category->id;

                //REMOVE THIS IF FOR CATEGORIES IN WORKING FORM
                // if($subs_category->lparent!=0)
                // {
                //     $subs[$key]->id = $subs_category->lparent;
                // }

                if($subs_category->lang_id==2)
                {
                    $boss_category = $subs_category;
                }
                else
                {
                    $boss_category = $this->db->where("id",$subs_category->lparent)->get("categories")->result_object()[0];
                }
                
                $subs_return[] = array(
                    "en_id"=>$one->lang_id==2?$one->id:$two->id,
                    "id"=>$boss_category->id,
                    "c_title"=>$boss_category->c_title,
                    "c_title_ar"=>$boss_category->c_title_ar,
                    "shipping"=>$boss_category->shipping,
                    "vat"=>$boss_category->vat,
                    "cust"=>$boss_category->cust==1?1:0,
                    "cust_image"=>$boss_category->cust_image,
                    "logo_price"=>$boss_category->logo_price,

                    "note_text_en"=>$boss_category->note_text_en,
                    "note_text_ar"=>$boss_category->note_text_ar,
                    "terms_en"=>$boss_category->terms_en,
                    "terms_ar"=>$boss_category->terms_ar,

                    "min_qty"=>$boss_category->min_qty,
                    "pc_price"=>$boss_category->pc_price,
                    "stamps_price"=>$boss_category->stamps_price,

                    "faces"=>$boss_category->faces,
                    "faces_price"=>$boss_category->faces_price,
                    "faces_from"=>$boss_category->faces_from,
                    "faces_to"=>$boss_category->faces_to,

                    "colors"=>$boss_category->colors,
                    "colors_from"=>$boss_category->colors_from,
                    "colors_to"=>$boss_category->colors_to,
                    "colors_price"=>$boss_category->colors_price,

                    "sides"=>$boss_category->sides,
                    "sides_price"=>$boss_category->sides_price,

                    "base"=>$boss_category->base,
                    "base_price"=>$boss_category->base_price,

                    "height"=>$boss_category->height,
                    "width"=>$boss_category->width,

                    "delivery"=>$boss_category->delivery,
                    "delivery_type"=>$boss_category->delivery_type,
                    "real_id"=>$subs_category->id,
                    "title"=>$subs_category->title!=""?$subs_category->title:$en_subs_category->title,
                    // "image"=>$subs_category->image!=""?$subs_category->image:$en_subs_category->image,
                    "image"=>$boss_category->image,
                    "slug"=>$boss_category->slug.'-'.$boss_category->id,
                    "meta_title"=>$subs_category->meta_title!=""?$subs_category->meta_title:$en_subs_category->meta_title,
                    "meta_keywords"=>$subs_category->meta_keywords!=""?$subs_category->meta_keywords:$en_subs_category->meta_keywords,
                    "meta_description"=>$subs_category->meta_description!=""?$subs_category->meta_description:$en_subs_category->meta_description,
                );


            }
            
            
            if(empty($subs_return)) $subs_return = array();
            $sub_returnn->subs = $subs_return;




            $return[] = $sub_returnn;
        }




        if(!empty($return))
           $return[0]->active = true;
        else $return = array();


        return $return;
    }
    private function enrich_brands($brands,$lang_id)
    {

        $return = array();


        foreach($brands as $key=>$brand)
        {
            $en_brand = $brand;

            $this->db->group_start();
            $this->db->where("lang_id",$lang_id);
            $this->db->group_end();
            $this->db->group_start();
            $this->db->where("id",$brand->id);
            $this->db->or_where("lparent",$brand->id);
            $this->db->group_end();

            $real_id = $brand->id;

            $brand = $this->db->get("brands")->result_object()[0];


            $boss_id = $brand->lparent==0?$brand->id:$brand->lparent;
            $brands[$key]->real_id = $real_id;
            if($brand->lparent!=0)
            {
                $brands[$key]->id = $brand->lparent;
            }


            $sub_returnn = (Object) array(
                "id"=>$boss_id,
                "real_id"=>$brand->id,
                "title"=>$brand->title!=""?$brand->title:$en_brand->title,
                "image"=>$brand->image!=""?$brand->image:$en_brand->image,
            );


            $this->db->where("lparent",0);
            $this->db->where("status",1);
            $this->db->where("parent",$boss_id);
            $this->db->where("is_deleted",0);
            $subs = $this->db->get('brands')->result_object();

            

            $return[] = $sub_returnn;
        }




        if(!empty($return))
           $return[0]->active = true;
        else $return = array();


        return $return;
    }
    public function get_brands()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $this->db->where("lang_id",$lang_id);
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        $brands = $this->db->get('brands')->result_object();
        foreach($brands as $key=>$brand)
        {
            $boss_id = $brand->lparent==0?$brand->id:$brand->lparent;
            $brands[$key]->real_id = $brand->id;


            $brands[$key]->title = $this->get_brand_title($brand->id);
            $brands[$key]->sale_title = $this->get_brand_sale_title($brand->id);
            $brands[$key]->sale_subtitle = $this->get_brand_sale_subtitle($brand->id);
            $brands[$key]->image = $this->get_brand_image($brand->id);
            $brands[$key]->sale_banner = $this->get_brand_sale_banner($brand->id);



            if($brand->lparent!=0)
            {
                $brands[$key]->id = $brand->lparent;
            }
            
            if(empty($subs)) $subs = array();
            $brands[$key]->subs = $subs;
        }

        if(!empty($brands))
           $brands[0]->active = true;
        else $brands = array();

        echo json_encode(array("data"=>array("brands"=>$brands),"action"=>"success"));
    }
    public function only_in()
    {

        if($this->region_id!=0 && $this->region_id!=""){
            $real_region = $this->db->where("id",$this->region_id)->get("regions")->result_object()[0];
            if($real_region->lparent==0)
            {
                $two_region = $this->db->where("lparent",$real_region->id)->get("regions")->result_object()[0]->id;
                $one_region = $real_region->id;
            }
            else
            {
                $one_region = $real_region->id;
                $two_region = $real_region->lparent;
            }

            $regioned_prods = $this->db->where("region_id",$two_region)->or_where("region_id",$one_region)->get("product_units")->result_object();
            
         
        }
        else{
            $regioned_prods = $this->db->get("product_units")->result_object();
        }
        $prods[] = -1;
        foreach($regioned_prods as $region_prod)
            $prods[] = $region_prod->product_id;

        return $prods;
    }
    private function get_c_products_web_tabs($lang_id,$column="",$limit=10)
    {

        
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("lparent",0);
        $this->db->where("status",1);
        // $this->db->where("parent",0);

        if($column!="")
            $this->db->where($column,1);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $this->db->limit($limit);
        $products = $this->db->get('products')->result_object();
        $products = $this->enrich_products_tabs_home($products,$lang_id);
       
        // echo $this->db->last_query();exit;
        return $products; 
    }
    private function get_c_products($lang_id,$column="",$limit=10)
    {

        
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("lparent",0);
        $this->db->where("status",1);
        // $this->db->where("parent",0);

        if($column!="")
            $this->db->where($column,1);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $this->db->limit($limit);
        $products = $this->db->get('products')->result_object();
        $products = $this->enrich_products($products,$lang_id);

        // echo $this->db->last_query();exit;
        return $products; 
    }
    private function get_d_products_web_small_tab($lang_id,$limit=99,$column="")
    {
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("lparent",0);
        $this->db->where("status",1);
        // $this->db->where("parent",0);
        // $this->db->where("discount >",0);

        if($column!="")
            $this->db->where($column,1);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $this->db->limit($limit);
        $products = $this->db->get('products')->result_object();

        $products = $this->enrich_products_tabs_home($products,$lang_id);
        return $products; 
    }
    private function get_d_products($lang_id,$limit=99,$column="")
    {
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("lparent",0);
        $this->db->where("status",1);
        // $this->db->where("parent",0);
        // $this->db->where("discount >",0);

        if($column!="")
            $this->db->where($column,1);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $this->db->limit($limit);
        $products = $this->db->get('products')->result_object();

        $products = $this->enrich_products($products,$lang_id);
        return $products; 
    }
    public function get_home_products()
    {
        $started = time();
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        $clang = $this->db->where("id",$lang_id)->get("languages")->result_object()[0];

        // $lang_id = 2;


        // $ready_made = $this->db->where("lang_id",$lang_id)->where("os","phone")->where("region_id",$user_logged->region_id)->get("ready_data")->result_object()[0];

        // if($ready_made)
        // {
        //     $this->db->where("id",$ready_made->id)->update("ready_data",array("used"=>($ready_made->used+1)));
            
        //     echo $ready_made->data;
        //     return;
        // }
       
       

        $products = $this->get_c_products($lang_id,"popular");

    
        $tabs = array();

        $later_use_products = $products;

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"حصري":"Popular",
            "products"=>$products,
            
            "type"=>"popular"
        );

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"رئيسي":"Main",
            "products"=>$this->get_c_products($lang_id,"main"),
            
            "type"=>"popular"
        );

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"أكثر مبيعاًً":"Top Selling",
            "products"=>$this->get_c_products($lang_id,"top_selling"),
            
            "type"=>"popular"
        );

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"أفضل تقييماً":"Top Rated",
            "products"=>$this->get_c_products($lang_id,"top_rated"),
            
            "type"=>"popular"
        );


        $this->db->where("lparent",0);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $categories = $this->db->get('categories')->result_object();


        $categories = $this->enrich_categories($categories,$lang_id);

        
        foreach($categories as $key=>$category)
        {
            $products = $this->get_home_random_products($category,$lang_id);
            // print_r($category);
            $tabs[] = array(
                "title"=>$category->title,
                "products"=>$products,
                "cats"=>$category,
                "image"=>$category->image,
                "type"=>"normal"
            );
        }

        // $ready_made = $this->db->where("lang_id",$lang_id)->where("os","phone")->where("region_id",$user_logged->region_id)->delete("ready_data");

        $dataaaa = json_encode(
            array("data"=>array(
                "products"=>$later_use_products,
                "offers"=>$this->get_offers($lang_id),
                "tabs"=>$tabs,
                "my_searches"=>$this->get_my_search($user_logged->id),
                "hot_searches"=>$this->get_hot_search(),
                "sliders"=>$this->home_sliders($lang_id)
            ),
            "action"=>"success"
        ));

        //  $ready_made = $this->db->insert("ready_data",array(
        //     "data"=>$dataaaa,
        //     "lang_id"=>$lang_id,
        //     "os"=>"phone",
        //     "region_id"=>$user_logged->region_id
        // ));
        
        echo $dataaaa;
    }
    public function get_rel_products()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        $clang = $this->db->where("id",$lang_id)->get("languages")->result_object()[0];

        // $lang_id = 2;
       
       
        $count = $post->count;
        $products = $this->get_c_products($lang_id,"",$count);

        


        echo json_encode(
            array("data"=>array(
                "products"=>$products,
            ),
            "action"=>"success"
        ));
    }

    public function get_home_web()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $user_logged = $this->do_auth($post,false);

        $this->region_id = $post->region_id;

        // $this->guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        $clang = $this->db->where("id",$lang_id)->get("languages")->result_object()[0];

        // $lang_id = 2;


        // $ready_made = $this->db->where("lang_id",$lang_id)->where("os","web")->where("region_id",$post->region_id)->get("ready_data")->result_object()[0];

        // if($ready_made)
        // {
        //     $this->db->where("id",$ready_made->id)->update("ready_data",array("used"=>($ready_made->used+1)));
            
        //     echo $ready_made->data;
        //     return;
        // }
        
         
        
       

        $products = $this->get_d_products_web_small_tab($lang_id,10,"popular");


        $tabs = array();

        //$later_use_products = $this->get_d_products($lang_id,10);

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"حصري":"Popular",
            "products"=>$products,
            
            "type"=>"popular"
        );


        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"رئيسي":"Main",
            "products"=>$this->get_c_products_web_tabs($lang_id,"main"),
            
            "type"=>"popular"
        );

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"أكثر مبيعاًً":"Top Selling",
            "products"=>$this->get_c_products_web_tabs($lang_id,"top_selling"),
            
            "type"=>"popular"
        );

        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"أفضل تقييماً":"Top Rated",
            "products"=>$this->get_c_products_web_tabs($lang_id,"top_rated"),
            
            "type"=>"popular"
        );


        $brands = $this->db->select("image")->where("lparent",0)->where("is_deleted",0)->where("status",1)->order_by("id","DESC")->get("brands")->result_object();



      

        $ready_made = $this->db->where("lang_id",$lang_id)->where("os","web")->where("region_id",$user_logged->region_id)->delete("ready_data");

        $dataaaa = json_encode(
            array("data"=>array(
               //"products"=>$later_use_products,
               // "offers"=>$this->get_offers($lang_id,3),
                "two_offers"=>$this->get_offers($lang_id,2),
                "brands"=>$brands,
                "tabs"=>$tabs,
                "tabsv2"=>$this->get_all_tabs_v2($lang_id),
                "sliders"=>$this->home_sliders($lang_id)
            ),
            "action"=>"success"
        ));

        //  $ready_made = $this->db->insert("ready_data",array(
        //     "data"=>$dataaaa,
        //     "lang_id"=>$lang_id,
        //     "os"=>"web",
        //     "region_id"=>$post->region_id
        // ));
        
        echo $dataaaa;
    }
    public function get_all_tabs_v2($lang_id=1)
    {
        $tabs[] = array(
            "title"=>$clang->slug=="arabic"?"موصى به":"Recommended",
            "products"=>$this->get_c_products_web_tabs($lang_id,"",35),
            "slug"=>"recommended",
            "type"=>"popular"
        );

        $all_cats = $this->db->where("is_deleted",0)->where("status",1)->where("parent",0)->where("lparent",0)->get("categories")->result_object();

        $all_cats = $this->enrich_categories($all_cats,$lang_id);

        foreach($all_cats as $key=>$all_cat)
        {
            $my_ids = array();
            $my_ids[] = $all_cat->id;

            foreach($all_cat->subs as $sub_cat) $my_ids[] = $sub_cat["id"];
            // ->where_in("id",$this->only_in_ids)
            $prods = $this->db->where_in("category",$my_ids)->limit(35)->get("products")->result_object();

            $prods_display=$this->enrich_products_tabs_home($prods,$lang_id);

            if(!empty($prods_display))
            $tabs[] = array(
                "title"=>$all_cat->title,
                "slug"=>$all_cat->slug,
                "products"=>$prods_display,
                //"products"=>array(),
                "type"=>"popular"
            );

        }
        
        return $tabs;
    }
    private function per_page()
    {
        return 20;
    }

    public function get_cat_data()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $this->region_id = $post->region_id;

        //$user_logged = $this->do_auth($post);

        // $this->guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;




        $clang = $this->db->where("id",$lang_id)->get("languages")->result_object()[0];

        $slug = $post->slug;

        $cat_id = explode('-', $slug)[ count(explode('-', $slug)) - 1 ];

        $c_cat = $this->db->where("id",$cat_id)->where("is_deleted",0)->where("status",1)->get("categories")->result_object()[0];

        $c_cats =array($c_cat);


        $c_cat = $this->enrich_categories($c_cats,$lang_id)[0];


        //paginations


        if($slug!="recommended"){
            if(empty($c_cat->subs))
            {
                $this->db->where("category",$cat_id);
            }
            else{

                $my_ids[] = $c_cat->id;

                foreach($c_cat->subs as $sub_cat) $my_ids[] = $sub_cat["id"];


                $this->db->where_in("category",$my_ids);
            }
        }
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("lang_id",2);
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        if($post->order_by!="")
            $this->db->order_by($post->order_by,$post->order);

        $total = $this->db->get("products")->result_object();


        $brands_should_be = array(-1);

        foreach($total as $ttl) $brands_should_be[] = $ttl->brand;

        $pages = (int) count($total) / $this->per_page();



        if($slug!="recommended"){
            if(empty($c_cat->subs))
            {
                $this->db->where("category",$cat_id);
            }
            else{
                $my_ids[] = $c_cat->id;

                foreach($c_cat->subs as $sub_cat) $my_ids[] = $sub_cat["id"];


                $this->db->where_in("category",$my_ids);
            }
        }
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("status",1);
        $this->db->where("lang_id",2);
        $this->db->where("is_deleted",0);
        if($post->order_by!="")
            $this->db->order_by($post->order_by,$post->order);



        $this->db->limit($this->per_page(), $post->page*$this->per_page());

        $products = $this->db->get("products")->result_object();


        $products = $this->enrich_products($products,$lang_id);


        
         
        
       

       

        $this->db->where_in("id",$brands_should_be);
        $brands =  $this->db->where("lparent",0)->where("is_deleted",0)->where("status",1)->order_by("id","DESC")->get("brands")->result_object();

        $brands = $this->enrich_brands($brands,$lang_id);


        $categories =  $this->db->where("lparent",0)->where("is_deleted",0)->where("status",1)->order_by("id","DESC")->get("categories")->result_object();

        $categories = $this->enrich_categories($categories,$lang_id);
        $f_pages = array();

        $pages = ceil($pages);

        for($i = 1; $i<=$pages; $i++)
        {
            $f_pages[] = ($i);
        }

        

        echo json_encode(
            array("data"=>array(
                    "products"=>$products,
                    "categories"=>$categories,
                    "brands"=>$brands,
                    "category"=>$c_cat,
                    "pages"=>($pages),
                    "f_pages"=>$f_pages,
                    "per_page"=>$this->per_page(),
                ),
                "action"=>"success"
            ));
    }
    public function get_offer_prods()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $this->region_id = $post->region_id;

        // $user_logged = $this->do_auth($post);

        // $this->guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;




        $clang = $this->db->where("id",$lang_id)->get("languages")->result_object()[0];

        $slug = $post->slug;

        $offer = $this->db->where("slug",$slug)->get("offers")->result_object()[0];

        $prods = explode(',', $offer->products);
        $prods[] = -1;
        //paginations
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where_in("id",$prods);
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        if($post->order_by!="")
            $this->db->order_by($post->order_by,$post->order);

        $total = $this->db->get("products")->result_object();



        $pages = (int) count($total) / $this->per_page();


        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where_in("id",$prods);
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        if($post->order_by!="")
            $this->db->order_by($post->order_by,$post->order);



        $this->db->limit($this->per_page(), $post->page*$this->per_page());

        $products = $this->db->get("products")->result_object();


        $products = $this->enrich_products($products,$lang_id);


        
         
        
       

       

       

        echo json_encode(
            array("data"=>array(
                    "products"=>$products,
                    "per_page"=>$this->per_page(),
                ),
                "action"=>"success"
            ));
    }
    private function home_sliders($lang_id=2)
    {
        
        

        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;



         $sliders = $this->db->where("status",1)->where("is_deleted",0)->where("lparent",0)->order_by("id",DESC)->get("sliders")->result_object();
         
         

        $sliders = $this->enrich_sliders($sliders,$lang_id);

        $final = array();

        foreach($sliders as $key=>$slider)
        {
            $cat_id = $this->db->query("SELECT id FROM categories WHERE slug = '".$slider->cat_slug."'")->result_object()[0];

            $final[] = array(
                "id"=>$slider->id,
                "image"=>$slider->image,
                "path"=>base_url()."resources/uploads/sliders/".$slider->image,
                "link_to"=>$slider->cat_slug.'-'.$cat_id->id,
                "link_id"=>$cat_id->id
            );
        }

        return $final;

    }
    public function get_home_sliders()
    {

        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

         echo json_encode(
            array("data"=>array(
                    "sliders"=>$this->home_sliders($lang_id)
                ),
                "action"=>"success"
            ));

    }
    private function get_my_search($id)
    {
        return $this->db->where("user_id",$id)->order_by("id","DESC")->group_by("search")->get("search_history")->result_object();
    }
    private function get_hot_search()
    {
        return $this->db->order_by("id","DESC")->group_by("search")->get("search_history")->result_object();
    }
    private function get_offers($lang_id,$limit=99)
    {
        $this->db->where("lparent",0);
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $this->db->limit($limit);
        $offers = $this->db->get('offers')->result_object();
        
        foreach($offers as $key=>$offer)
        {
            if($offer->lang_id==$lang_id)
            {
                $use_offer = $offer;
            }
            else
            {
                $use_offer = $this->db->where("lparent",$offer->id)->where("lang_id",$lang_id)->get('offers')->result_object()[0];
            }
            $where_in = explode(',', $use_offer->products);

            if(empty($where_in)) $where_in = array(-1);


            $this->db->where_in("id",$where_in);
            // $this->db->where("lang_id",$lang_id);
            $this->db->where("status",1);
            $this->db->where("parent",0);
            $this->db->where("is_deleted",0);
            $this->db->order_by("id","DESC");
            $products = $this->db->get('products')->result_object();

            if($lang==1){
                $this->db->where("status",1);
                $this->db->where_in("lparent",$where_in);
                $this->db->where("is_deleted",0);
                $this->db->order_by("id","DESC");
                $products = $this->db->get('products')->result_object();
            }

            $discount_text = "Upto ";

            if($offer->discount_type==1)
            {
                $discount_text .= with_currency($offer->discount) ." discount";
            }
            else{
                $discount_text .= ($offer->discount) ."% discount";
            }

            $offers[$key]->discount_text = $discount_text;
            $offers[$key]->title = $use_offer->title!=""?$use_offer->title:$offer->title;
            $offers[$key]->description = $use_offer->description!=""?$use_offer->description:$offer->description;
            $offers[$key]->image = $use_offer->image?$use_offer->image:$offer->image;
            $offers[$key]->products = $this->enrich_products($products,$lang_id);
        }

        return $offers;


    }
    private function get_home_random_products($cat,$lang_id)
    {
        $ids = array();

        $ids[] = $cat->id;

        $varss = $cat->subs;

            
            
        foreach($varss as $ckk=>$ctt)
        {
                      

            $ids[] = $ctt["id"];
        }
        

       
        if(empty($ids))
            return array();



        $this->db->where_in("category",$ids);
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("status",1);
        $this->db->where("lparent",0);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $products = $this->db->get('products')->result_object();
 

   
        $products = $this->enrich_products($products,$lang_id);

        return $products;

    }
    private function enrich_products_tabs_home($products,$lang_id)
    {
        foreach($products as $key=>$product)
        {
            $products[$key] = $this->enrich_signle_product_tabs_home($product->id,$lang_id);
        }
        // echo "bilal------";
        // echo "<pre>";
        // print_r($products);
        return $products;
    }
    private function enrich_signle_product_tabs_home($product_id,$lang_id)
    {
       
        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("id",$product_id);
        $this->db->or_where("lparent",$product_id);
        $this->db->group_end();
        
        $product = $this->db->get('products')->result_object()[0];

        $boss_product = $product;

        if($boss_product->lparent!=0){
            $boss_product = $this->db->where("id",$boss_product->lparent)->get("products")->result_object()[0];
        }

        $boss_id = $product->lparent==0?$product->id:$product->lparent;

        $product->cat = $this->get_cat_id_n_title($boss_product->category,$lang_id);

        $product->real_id = $product->id;

        if($this->region_id==0)
            $product_prices = $this->db->where("product_id",$boss_id)->get("product_units")->result_object();
        else
            $product_prices = $this->db->where("region_id",$this->region_id)->where("product_id",$boss_id)->get("product_units")->result_object();

        if(empty($product_prices))
        {
            // if data has not been added, just select the first one
           $product_prices = $this->db->where("product_id",$boss_id)->get("product_units")->result_object(); 
        }

        $chosen_price = $product_prices[0]->price;


        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('id',$product_prices[0]->region_id);
        $this->db->or_where('lparent',$product_prices[0]->region_id);
        $this->db->group_end();


        $region_price = $this->db->get("regions")->result_object()[0];



        $discount_text = "";
        $discounted_price = 0;
        $discounted_text = $this->fake_currency(0,$region_price->currency);
        $real_price = $chosen_price;
        $real_price_text = $this->fake_currency($chosen_price,$region_price->currency);

        // COME BACK
        if($boss_product->discount_type==1)
        {
            $discount_text = $this->fake_currency($boss_product->discount,$region_price->currency);

            $dis_txt = $real_price-$boss_product->discount;
            $dis_txt = number_format((float)$dis_txt, 2, '.', '');
            $dis_txt = round($dis_txt);

            $discounted_price =  $dis_txt;
            $discounted_text = $this->fake_currency($dis_txt,$region_price->currency);
        }
        else if($boss_product->discount_type==2)
        {
            if($lang_id==1){
                $discount_text = "%".$boss_product->discount;
            }else {
                $discount_text = $boss_product->discount."%";
            }
             $percentage = ($boss_product->discount / 100) * $real_price;
            //$percentage = ($boss_product->discount / $real_price)*100;
              $dis_txt = $real_price-$percentage;
            $dis_txt = number_format((float)$dis_txt, 2, '.', '');
            $dis_txt = round($dis_txt);
            
            $discounted_price = $dis_txt;
            $discounted_text = $this->fake_currency($dis_txt,$region_price->currency);
        }

        $product->currency = $region_price->currency;

        // variations
        // $variations = $this->db->where("product_id",$boss_id)
        // ->where("lang_id",$lang_id)
        // ->get("variations")->result_object();

        // $product->store_id = $this->get_store_id($boss_product->id);
        // $product->store = $this->get_store($boss_product->id);
        $product->title = $this->get_title($product->id,$lang_id);
        // $product->min_qty = $boss_product->min_order_qty?$boss_product->min_order_qty:1;
        // $product->cat_title = $this->get_cat_title($product->id,$lang_id);
        // $product->brand_title = $this->get_branded_title($product->id,$lang_id);
        // NEW LINE IN CODE BILAL
        // $product->packaging_box = $boss_product->packaging_box;

        $product->slug = $boss_product->slug.'-'.$boss_product->id;
        // $product->sku = $boss_product->sku;

        // $product->brand = $boss_product->brand;
        // $product->delivery = $boss_product->delivery;
        
        $product->image = $this->get_image($boss_id);
        // $product->slider_images = $this->get_slider_images($boss_id);
        
        // $product->description_web = $product->description!=""?$product->description:$boss_product->description;
        // $product->description_sweb = $product->sdescription!=""?$product->sdescription:$boss_product->sdescription;


        // $product->description = strip_tags($product->description!=""?$product->description:$boss_product->description);


        // $product->variations = $this->sort_variations($variations);
        // $product->custom_variations = $this->c_variations($boss_id,$lang_id);
        // $product->reviews = $this->get_reviews($boss_product->id,$lang_id);

        // $product->more_descp = $product->description2!=""?json_decode($product->description2):json_decode($boss_product->description2);

        $product->discount_text = $discount_text;
        $product->discounted_price = $discounted_price;
        $product->discounted_text = $discounted_text;
        $product->real_price = $real_price;
        // $product->unit = $this->get_unit_text($boss_product->qty_unit,$lang_id);
        // $product->wishlist_count = $this->get_wishlist_count($boss_product->id);



        // $product->meta_title=$product->meta_title!=""?$product->meta_title:$boss_product->meta_title;
        // $product->meta_keywords=$product->meta_keywords!=""?$product->meta_keywords:$boss_product->meta_keywords;
        // $product->meta_description=$product->meta_description!=""?$product->meta_description:$boss_product->meta_description;




        $product->real_price_text = $real_price_text;
        // $product->in_wish = $this->is_in_wish($product);

        if($product->lparent!=0)
        {
            $product->id = $product->lparent;
        }


        // $reviews = $this->db->where("type",2)->where("order_id",$boss_product->id)->where("status",1)->get("order_reviews")->result_object();

        // foreach($reviews as $key=>$review)
        // {
        //     $author = $this->db->where("id",$review->user_id)->get("users")->result_object()[0];

        //     $reviews[$key]->author_name = $author->first_name . ' '. $author->last_name;
        //     $reviews[$key]->at = date("F d, Y",strtotime($review->created_at));
        // }

        // $overall_stars = 0;

        // $product->reviews = array();

        // foreach($reviews as $review){
        //     $overall_stars += $review->rating;
        // }

        // $rating = $overall_stars / count($reviews);
        // $rating = (int) abs($rating);

        // $product->rating=$rating;
        // if(!empty($reviews))
        // $product->reviews=$reviews;
        
        $product = array(
            "real_price_text"=>$real_price_text,
            "real_price"=>$real_price,
            "title"=>$product->title,
            "image"=>$this->get_image($boss_id),
            "slug"=>$product->slug,
            "discounted_text"=>$product->discounted_text,
            "discount_text"=>$product->discount_text,
            "discounted_price" => $discounted_price
        );
        // if(!empty($products))
        //    $products[0]->active = 1;
        // else $products = array();
       
        return $product;
    }
    private function enrich_products($products,$lang_id)
    {
        foreach($products as $key=>$product)
        {

            // $boss_id = $product->lparent==0?$product->id:$product->lparent;
            // $products[$key]->real_id = $product->id;
            


            

            // $discount_text = "";
            // $discounted_price = 0;
            // $discounted_text = with_currency(0);
            // $real_price = $product->price;
            // $real_price_text = with_currency($product->price);

            // if($product->discount_type==1)
            // {
            //     $discount_text = with_currency($product->discount);
            //     $discounted_price =  $real_price-$product->discount;
            //     $discounted_text = with_currency($real_price-$product->discount);
            // }
            // else if($product->discount_type==2)
            // {
            //     $discount_text = $product->discount."%";

            //     $percentage = ($product->discount / $real_price)*100;

            //     $discounted_price = $real_price-$percentage;
            //     $discounted_text = with_currency($real_price-$percentage);
            // }

            // // variations
            // $variations = $this->db->where("product_id",$boss_id)
            // ->where("lang_id",$lang_id)
            // ->get("variations")->result_object();

            // $products[$key]->store_id = $this->get_store_id($product->id);
            // $products[$key]->store = $this->get_store($product->id);
            // $products[$key]->title = $this->get_title($product->id);
            // $products[$key]->cat_title = $this->get_cat_title($product->id,$lang_id);
            // $products[$key]->brand_title = $this->get_branded_title($product->id,$lang_id);
            // $products[$key]->title = $this->get_title($product->id);
            // $products[$key]->image = $this->get_image($product->id);
            // $products[$key]->slider_images = $this->get_slider_images($product->id);
            // $products[$key]->description = strip_tags($this->get_description($product->id));
            // $products[$key]->variations = $this->sort_variations($variations);
            // $products[$key]->discount_text = $discount_text;
            // $products[$key]->discounted_price = $discounted_price;
            // $products[$key]->discounted_text = $discounted_text;
            // $products[$key]->real_price = $real_price;
            // $products[$key]->real_price_text = $real_price_text;

            // if($product->lparent!=0)
            // {
            //     $products[$key]->id = $product->lparent;
            // }

            $products[$key] = $this->enrich_signle_product($product->id,$lang_id);
        }

        // if(!empty($products))
        //    $products[0]->active = 1;
        // else $products = array();

        return $products;
    }
    private function fake_currency($amount,$sign,$lang_id=2)
    {
        return $amount . ' '.$sign;
        if($lang_id == 1){
            return $amount . ' '.$sign;
        }else {
            return $amount . ' '.$sign;
        } 
    }
    // private function fake_currency($amount,$sign,$lang_id=2)
    // {
      
    //     if($lang_id == 2){
          
    //         return $amount . ' '.$sign;
    //     }else {
           
    //         return $sign. ' '.$amount;
    //     } 
    // }

    
    private function enrich_signle_product($product_id,$lang_id)
    {
       
        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("id",$product_id);
        $this->db->or_where("lparent",$product_id);
        $this->db->group_end();
        
        $product = $this->db->get('products')->result_object()[0];

        $boss_product = $product;




        if($boss_product->lparent!=0){
            $boss_product = $this->db->where("id",$boss_product->lparent)->get("products")->result_object()[0];
        }




        $boss_id = $product->lparent==0?$product->id:$product->lparent;



        $product->cat = $this->get_cat_id_n_title($boss_product->category,$lang_id);

        $product->real_id = $product->id;

        if($this->region_id==0)
            $product_prices = $this->db->where("product_id",$boss_id)->get("product_units")->result_object();
        else
            $product_prices = $this->db->where("region_id",$this->region_id)->where("product_id",$boss_id)->get("product_units")->result_object();

        if(empty($product_prices))
        {
            // if data has not been added, just select the first one
           $product_prices = $this->db->where("product_id",$boss_id)->get("product_units")->result_object(); 
        }
        $chosen_price = $product_prices[0]->price;


        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('id',$product_prices[0]->region_id);
        $this->db->or_where('lparent',$product_prices[0]->region_id);
        $this->db->group_end();


        $region_price = $this->db->where("lang_id",$lang_id)->get("regions")->result_object()[0];



        $discount_text = "";
        $discounted_price = 0;
        $discounted_text = $this->fake_currency(0,$region_price->currency);
        $real_price = $chosen_price;
        $real_price_text = $this->fake_currency($chosen_price,$region_price->currency);
        $product->discount_type = $boss_product->discount_type;
        if($boss_product->discount_type==1)
        {
            $discount_text = $this->fake_currency($boss_product->discount,$region_price->currency);
            $discounted_price =  $real_price-$boss_product->discount;
            $dis_txt = $real_price-$boss_product->discount;
            $dis_txt = number_format((float)$dis_txt, 2, '.', '');
            $dis_txt = round($dis_txt);
            $discounted_text = $this->fake_currency($dis_txt,$region_price->currency);
        }
        else if($boss_product->discount_type==2)
        {
            if($lang_id==1){
                $discount_text = "%".$boss_product->discount;
            }else {
                $discount_text = $boss_product->discount."%";
            }
            //$discount_text = $boss_product->discount."%";
            //echo $percentage = ($boss_product->discount / $real_price)*100;
            $percentage = ($boss_product->discount / 100) * $real_price;
            $discounted_price = $real_price-$percentage;
            $dis_txt = $real_price-$percentage;
            $dis_txt = number_format((float)$dis_txt, 2, '.', '');
            $dis_txt = round($dis_txt);
            $discounted_text = $this->fake_currency($dis_txt,$region_price->currency);
        }

        $product->currency = $region_price->currency;

        // variations
        $variations = $this->db->where("product_id",$boss_id)
        ->where("lang_id",$lang_id)
        ->get("variations")->result_object();

        $product->store_id = $this->get_store_id($boss_product->id);
        $product->store = $this->get_store($boss_product->id);
        $product->title = $this->get_title($product->id,$lang_id);
        $product->min_qty = $boss_product->min_order_qty?$boss_product->min_order_qty:1;
        $product->cat_title = $this->get_cat_title($product->id,$lang_id);
        $product->brand_title = $this->get_branded_title($product->id,$lang_id);
        // NEW LINE IN CODE BILAL
        $product->packaging_box = $boss_product->packaging_box;

        $product->slug = $boss_product->slug.'-'.$boss_product->id;
        $product->sku = $boss_product->sku;

        $product->brand = $boss_product->brand;
        $product->delivery = $boss_product->delivery;
        
        $product->image = $this->get_image($boss_id);
        $product->slider_images = $this->get_slider_images($boss_id);
        
        $product->description_web = $product->description!=""?$product->description:$boss_product->description;
        $product->description_sweb = $product->sdescription!=""?$product->sdescription:$boss_product->sdescription;


        $product->description = "<body><html><style>
                        @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
                        body {font-family:'Cairo'}
                        </style>".($product->description!=""?$product->description:$boss_product->description)."</html></body>";

         $product->sdescription = "<body><html><style>
                        @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
                        body {font-family:'Cairo'}
                        </style>".($product->sdescription!=""?$product->sdescription:$boss_product->sdescription)."</html></body>";


        $product->variations = $this->sort_variations($variations);
        $product->custom_variations = $this->c_variations($boss_product,$boss_id,$lang_id);
        $product->reviews = $this->get_reviews($boss_product->id,$lang_id);

        $product->more_descp = $product->description2!=""?json_decode($product->description2):json_decode($boss_product->description2);

        // NEW LINE
        $discnt = number_format((float)$discounted_price, 2, '.', '');
        $discnt = round($discnt);
        $product->discount_text = $discount_text;
        $product->discounted_price = $discnt;
        $product->discounted_text = $discounted_text;
        $product->real_price = $real_price;
        $product->unit = $this->get_unit_text($boss_product->qty_unit,$lang_id);
        $product->wishlist_count = $this->get_wishlist_count($boss_product->id);



        $product->meta_title=$product->meta_title!=""?$product->meta_title:$boss_product->meta_title;
        $product->meta_keywords=$product->meta_keywords!=""?$product->meta_keywords:$boss_product->meta_keywords;
        $product->meta_description=$product->meta_description!=""?$product->meta_description:$boss_product->meta_description;




        $product->real_price_text = $real_price_text;
        $product->in_wish = $this->is_in_wish($product);
        //echo $product->currency = get_currency($lang_id==1?"arabic":"english");


        if($product->lparent!=0)
        {
            $product->id = $product->lparent;
        }


        $reviews = $this->db->where("type",2)->where("order_id",$boss_product->id)->where("status",1)->get("order_reviews")->result_object();

        foreach($reviews as $key=>$review)
        {
            $author = $this->db->where("id",$review->user_id)->get("users")->result_object()[0];

            $reviews[$key]->author_name = $author->first_name . ' '. $author->last_name;
            $reviews[$key]->at = date("F d, Y",strtotime($review->created_at));
        }

        $overall_stars = 0;

        $product->reviews = array();

        foreach($reviews as $review){
            $overall_stars += $review->rating;
        }

        $rating = $overall_stars / count($reviews);
        $rating = (int) abs($rating);

        $product->rating=$rating;
        if(!empty($reviews))
        $product->reviews=$reviews;
        

        // if(!empty($products))
        //    $products[0]->active = 1;
        // else $products = array();

        return $product;
    }
    public function c_variations($boss_product,$boss_id,$lang_id)
    {
        $variations = $this->db->where("product_id",$boss_id)->where("status",1)->get("product_c_vars")->result_object();

        $to_return = array();
        // NEW LINE IN CODE BILAL
        $region_price = $this->db->where("lang_id",$lang_id)->get("regions")->result_object()[0];
        foreach($variations as $variation)
        {

            $better_title = $variation->title_ar?$variation->title_ar:$variation->title_en;
            $title = $lang_id==1?$better_title:$variation->title_en;

            $options = array();

            $local_options = json_decode($variation->options);
            //print_r($local_options);
            foreach($local_options as $key=>$local_option)
            {
                if (array_key_exists('status', $local_option)) {
                    $option_sta = $local_option->status;
                } else {
                    $option_sta = 1;
                }
                
                if($option_sta != 0){
                    $real_price = $local_option->price;

                    $discounted_price = $real_price;
                    $discounted_text = "";
                    $discount_text = "";
                    $discount = 0;
                    // DISCOUNT PRODUCT
                    if($boss_product->discount_type==1)
                        {
                            $dis_txt = $real_price-$boss_product->discount;
                            $dis_txt = number_format((float)$dis_txt, 2, '.', '');
                            $dis_txt = round($dis_txt);

                            $discount_text = $this->fake_currency($boss_product->discount,$region_price->currency);
                            $discounted_price =  $real_price == 0?0:$dis_txt;

                            

                            $discounted_text = $this->fake_currency($dis_txt,$region_price->currency);
                            $discount = 1;

                        }
                        else if($boss_product->discount_type==2)
                        {
                            $discount_text = $boss_product->discount."%";

                            //$percentage = ($boss_product->discount / $real_price)*100;
                             $percentage = ($boss_product->discount / 100) * $real_price;

                                $dis_txt = $real_price-$percentage;
                                $dis_txt = number_format((float)$dis_txt, 2, '.', '');
                                $dis_txt = round($dis_txt);

                            $discounted_price = $real_price == 0?0:$dis_txt;

                           

                            $discounted_text = $this->fake_currency($dis_txt,$region_price->currency);
                            $discount = 1;
                        }


                    $better_title = $local_option->ar?$local_option->ar:$local_option->en;
                    $options[] =  array(
                        "title"=>$lang_id==1?$better_title:$local_option->en,
                        // NEW LINE IN CODE BILAL
                        "price"=>$discounted_price,
                        "real_price_after_discount"=>$real_price,
                        "discount_text"=>$discount_text,
                        "discounted_text"=>$discounted_text,
                        "price_text"=>$this->fake_currency($discounted_price,$region_price->currency),
                        //"selected"=>$key==0?true:false,
                        "selected"=>false,
                        "is_discount"=>$discount,
                        "status_shown"=>$option_sta,
                        // NEW LINE IN CODE BILAL
                        // "selected"=>$key==0?true:false
                    );
                    //print_r($options);
                }}

                $to_return[] = array(
                    "title"=>$title,
                    "options"=>$options
                );
          
        }

        return $to_return;
    }
    public function get_product_details($slug,$lang_id)
    {


        
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $this->region_id = $post->region_id;

        $user= $this->do_auth($post,false);
        $product_id = explode('-', $slug)[ count(explode('-', $slug)) - 1 ];
 
        $product_det = $this->db->where("id",$product_id)->where("lparent",0)->get("products")->result_object()[0];
        if($product_det->status == 1){

            $product=$this->enrich_signle_product($product_id,$lang_id);
            $related_prods = $this->db->where("category",$product->cat->id)->where("lparent",0)->order_by("RAND(id)")->limit(2)->get("products")->result_object();
            foreach($related_prods as $key=>$related_prod)
                $related_prods[$key] = $this->enrich_signle_product($related_prod->id,$lang_id);
            echo json_encode(array("action"=>"success","data"=>array("product"=>$product,"related"=>$related_prods)));
        }else{
            echo json_encode(array("action"=>"no_product"));
        }
    }
    private function get_wishlist_count($id)
    {
        return $this->db->where("product_id",$id)->count_all_results("wishlist");
    }
    private function is_in_wish($product)
    {


        if($product->lparent==0)
        {

        }
        else{
            $product = $this->db->where('id',$product->lparent)->get("products")->result_object()[0];
        }
        return $this->db->where("user_id",$this->guest_id)->where("product_id",$product->id)->count_all_results("wishlist")>0?1:0;
    }
    private function is_in_follow($store)
    {


        if($store->lparent==0)
        {

        }
        else{
            $store = $this->db->where('id',$store->lparent)->get("stores")->result_object()[0];
        }
        return $this->db->where("user_id",$this->guest_id)->where("store_id",$store->id)->count_all_results("store_followers")>0?1:0;
    }
    private function get_reviews($product_id,$lang_id)
    {
        // $this->db->group_start();
        // $this->db->where("lang_id",$lang_id);
        // $this->db->group_end();
        // $this->db->group_start();
        $this->db->where("id",$product_id);
        // $this->db->or_where("lparent",$product_id);
        // $this->db->group_end();
        
        $product = $this->db->get('products')->result_object()[0];


        $order_prods = $this->db->where("product_id",$product_id)->group_by("order_id")->get("order_products")->result_object();

        $order_ids = array(-1);

        foreach($order_prods as $key=>$order_prod)
        {
            $order_ids[] = $order_prod->order_id;
        }

        $reviews = $this->db->where_in("order_id",$order_ids)->get('order_reviews')->result_object();

        foreach($reviews as $key=>$review)
        {
            $signle_order = $this->db->where_in("order_id",$review->order_id)->get('order_products')->result_object()[0];

            $reviews[$key]->variation_text = $signle_order->variation;
            $reviews[$key]->user_data = $this->db->where("id",$review->user_id)->get("users")->result_object()[0];
        }

        return $reviews;
    }
    private function sort_variations($variations)
    {
        $variations[0]->selected=true;
        return $variations;
        $types = array();

        // foreach($variations as $key=>$variation)
        // {
        //     if(!in_array($variation->type, $types))
        //     {
        //         $types[] = $variation->type;
        //     }
        // }

        $to_return = array();

        foreach($variations as $key=>$variation)
        {
            $types[$variation->type]->title = $variation->type;
            $types[$variation->type]->values[] = array_merge($types[$variation->type]->values,$variation);
        }

        return $types;
    }
    public function get_brand_products()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        if(!$lang_id) $lang_id = dlang()->id;

        // $lang_id = 1;

        $this->db->where("lang_id",$lang_id);
        $this->db->where("brand",$post->brand_id);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $products = $this->db->get('products')->result_object();



        $products = $this->enrich_products($products,$lang_id);

        echo json_encode(array("data"=>array("products"=>$products),"action"=>"success"));
    }
    public function search_products()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;
        $user = $this->do_auth($post);
        if(!$lang_id) $lang_id = dlang()->id;

        $user_logged = $this->do_auth($post);

        // $lang_id = 1;

        $this->db->insert("search_history",array(
            //"user_id"=>$user_logged->id,
            "search"=>$post->search_string,
            "created_at"=>date("Y-m-d H:i:s")
        ));

        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->like("LOWER(title)",strtolower($post->search_string));
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $products = $this->db->get('products')->result_object();



        $products = $this->enrich_products($products,$lang_id);

        echo json_encode(array("data"=>array("products"=>$products),"action"=>"success"));
    }
    public function search_web()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;
        $user = $this->do_auth($post);

        $this->region_id = $post->region_id;

        if(!$lang_id) $lang_id = dlang()->id;

        $user_logged = $this->do_auth($post);

        // $lang_id = 1;

        $this->db->insert("search_history",array(
            //"user_id"=>$user_logged->id,
            "search"=>$post->search,
            "created_at"=>date("Y-m-d H:i:s")
        ));
       
        



        $tabs[] = array(
            "title"=>$lang_id==1?"موصى به":"New Arrival",
            "products"=>$this->search_web_sub($post,$lang_id,array("id","DESC"),"order"),
        );

        $tabs[] = array(
            "title"=>$lang_id==1?"موصى به":"Recommended",
            "products"=>$this->search_web_sub($post,$lang_id,"popular","where"),
        );

        $tabs[] = array(
            "title"=>$lang_id==1?"موصى به":"Top Rated",
            "products"=>$this->search_web_sub($post,$lang_id,"top_rated","where"),
        );

        $tabs[] = array(
            "title"=>$lang_id==1?"موصى به":"Top Selling",
            "products"=>$this->search_web_sub($post,$lang_id,"top_selling","where"),
        );



        echo json_encode(array("data"=>array("tabsv2"=>$tabs),"action"=>"success"));
    }
    private function search_web_sub($post,$lang_id,$condition,$type)
    {


        $this->db->where("lang_id",$lang_id);
        $this->db->like("LOWER(title)",strtolower($post->search));
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        if($type=="where")
        {
            $this->db->where($condition,1);
        }
        if($type=="order")
        {
            $this->db->order_by("id","DESC");
        }

        $prods = $this->db->get('products')->result_object();
        
       
        return $this->enrich_products($prods,$lang_id);
        
    }
    public function get_cat_products()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        //$user = $this->do_auth($post);

        if(!$lang_id) $lang_id = dlang()->id;

        // $lang_id = 1;
        // $this->db->where("lang_id",$lang_id);
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("category",$post->cat_id);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $products = $this->db->get('products')->result_object();

        // echo $this->db->last_query();

        $products = $this->enrich_products($products,$lang_id);
        echo json_encode(array("data"=>array("products"=>$products),"action"=>"success"));
    }
    public function get_store_products()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;
        $user = $this->do_auth($post);
        if(!$lang_id) $lang_id = dlang()->id;

        // $lang_id = 1;

        $this->db->where("lang_id",$lang_id);
        // $this->db->where_in("id",$this->only_in_ids);
        $this->db->where("store_id",$post->store_id);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $products = $this->db->get('products')->result_object();



        $products = $this->enrich_products($products,$lang_id);

        if(!empty($products))
           $products[0]->active = true;
        else $products = array();

        echo json_encode(array("data"=>array("products"=>$products),"action"=>"success"));
    }

    private function get_brand_title($id)
    {
        $product = $this->db->where("id",$id)->get("brands")->result_object()[0];
        if($product->title!="") return $product->title;
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("brands")->result_object()[0];
            return $product->title; 
        }
        return "N/A";
    }
    private function get_brand_sale_title($id)
    {
        $product = $this->db->where("id",$id)->get("brands")->result_object()[0];
        if($product->sale_title!="") return $product->sale_title;
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("brands")->result_object()[0];
            return $product->sale_title; 
        }
        return "N/A";
    }
    private function get_brand_sale_subtitle($id)
    {
        $product = $this->db->where("id",$id)->get("brands")->result_object()[0];
        if($product->sale_subtitle!="") return $product->sale_subtitle;
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("brands")->result_object()[0];
            return $product->sale_subtitle; 
        }
        return "N/A";
    }
    public function get_title($id,$lang_id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];
        
        // if($lang_id == 1){
        //     $product = $this->db->where("lparent",$id)->where("lang_id",$lang_id)->get("products")->result_object()[0];
        // }

        if($product->title!="") {
           
            return $product->title;
        }
        else if($product->lparent!=0)
        {
           
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];

            return $product->title; 
        }
        return "N/A";
    }
    private function get_store_id($id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];
        
        if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
        }
        return $product->store_id;
        return 0;
    }
    private function get_store($id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];
        
        if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
        }
        $the_store = $this->db->where("lang_id",$product->lang_id)->where("id",$product->store_id)->get("stores")->result_object()[0];



        if(!$the_store)
            $the_store = $this->db->where("lang_id",$product->lang_id)->where("lparent",$product->store_id)->get("stores")->result_object()[0];

        $followers = $this->db->where("store_id",$the_store->id)->count_all_results("store_followers");
        $the_store->followers = $followers;

        return $the_store;

        return null;
    }
    private function get_cat_title($id,$lang_id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];

        
        if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
            
        }
        $cat = $this->db->where("id",$product->category)->get("categories")->result_object()[0];

        if($cat->lang_id==$lang_id){
            return $cat->title;
        }
        else{
            $cat = $this->db->where("lang_id",$lang_id)->where("lparent",$product->category)->get("categories")->result_object()[0];
            return $cat->title;
        }
        return "";
    }
    private function get_branded_title($id,$lang_id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];

        
        if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
            
        }
        $brand = $this->db->where("id",$product->brand)->get("brands")->result_object()[0];
        return $brand->title;
        if($brand->lang_id==$lang_id){
            return $brand->title;
        }
        else{
            $brand = $this->db->where("lang_id",$lang_id)->where("lparent",$product->category)->get("brands")->result_object()[0];
            return $brand->title;
        }
        return "";
    }
    private function get_image($id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];
        
        if($product->image!=""){
            if(file_exists("./resources/uploads/products/".$product->image))
            return $product->image;

        }
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
            if(file_exists("./resources/uploads/products/".$product->image))
            return $product->image; 
        }


        return "dummy_product.png";
    }
    private function get_slider_images($id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];
        $all_images = array();
        if($product->image!=""){
            if(file_exists("./resources/uploads/products/".$product->image))
            $all_images[] = base_url()."resources/uploads/products/". $product->image;

        }
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
            if(file_exists("./resources/uploads/products/".$product->image))
            $all_images[] = base_url()."resources/uploads/products/".$product->image; 
        }

        $more_images = $this->db->where("product_id",$product->id)->or_where("product_id",$product->lparent)->get("product_images")->result_object();
        foreach($more_images as $k=>$more_image)
        {
            $all_images[] = base_url()."resources/uploads/products/".$more_image->image;
        }

        return $all_images;
    }
    private function get_brand_image($id)
    {
        $product = $this->db->where("id",$id)->get("brands")->result_object()[0];

        if($product->image!=""){
            if(file_exists("./resources/uploads/brands/".$product->image))
            return $product->image;

        }
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("brands")->result_object()[0];
            if(file_exists("./resources/uploads/brands/".$product->image))
            return $product->image; 
        }
        return "dummy_product.png";
    }
    private function get_brand_sale_banner($id)
    {
        $product = $this->db->where("id",$id)->get("brands")->result_object()[0];

        if($product->sale_banner!=""){
            if(file_exists("./resources/uploads/brands/".$product->sale_banner))
            return $product->sale_banner;

        }
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("brands")->result_object()[0];
            if(file_exists("./resources/uploads/brands/".$product->sale_banner))
            return $product->sale_banner; 
        }
        return "dummy_product.png";
    }
    private function get_description($id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];

        if($product->description!="") return $product->description;
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];

            return $product->description; 
        }
        return "N/A";
    }
    private function get_sdescription($id)
    {
        $product = $this->db->where("id",$id)->get("products")->result_object()[0];

        if($product->sdescription!="") return $product->sdescription;
        else if($product->lparent!=0)
        {
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];

            return $product->sdescription; 
        }
        return "N/A";
    }
    public function get_addresses()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        $adrss = $this->db->where("user_id",$guest_id)->order_by("is_default","ASC")->get("shipping_addresses")->result_object();

        echo json_encode(array("action"=>"success","data"=>$adrss));
    }
    public function store_address()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        $arr = array(
            "first_name"=>$post->first_name,
            "email"=>$post->email,
            "address"=>$post->address,
            "address_2"=>$post->address_2,
            "title"=>$post->title,
            "last_name"=>$post->last_name,
            "c_code"=>$post->c_code,
            "c_code_text"=>$post->c_code_text,
            "phone"=>$post->phone,
            "ac_code"=>$post->ac_code,
            "ac_code_text"=>$post->ac_code_text,
            "aphone"=>$post->aphone,
            "country"=>$post->country,
            "state"=>$post->state,
            "city"=>$post->city,
            "street"=>$post->street,
            "zip"=>$post->zip,
            "address_type"=>$post->address_type!=""?$post->address_type:1,
            "user_id"=>$guest_id
        );

        $count= $this->db->where("user_id",$guest_id)->count_all_results("shipping_addresses");

        if($count==0)
        {
            $arr["is_default"] = 1;
        }
        else{
            $arr["is_default"] = 0;
        }

        $this->db->insert("shipping_addresses",$arr);



        $adrss = $this->db->where("user_id",$guest_id)->order_by("is_default","ASC")->get("shipping_addresses")->result_object();

        echo json_encode(array("action"=>"success","data"=>$adrss));
    }
    public function update_address()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $id =$post->id;

        $arr = array(
            "first_name"=>$post->first_name,
            "email"=>$post->email,
            "address"=>$post->address,
            "address_2"=>$post->address_2,
            "title"=>$post->title,
            "last_name"=>$post->last_name,
            "c_code"=>$post->c_code,
            "c_code_text"=>$post->c_code_text,
            "phone"=>$post->phone,
            "ac_code"=>$post->ac_code,
            "ac_code_text"=>$post->ac_code_text,
            "aphone"=>$post->aphone,
            "country"=>$post->country,
            "state"=>$post->state,
            "city"=>$post->city,
            "street"=>$post->street,
            "zip"=>$post->zip,
            "address_type"=>$post->address_type!=""?$post->address_type:1,
        );

        $this->db->where("id",$id)->update("shipping_addresses",$arr);

        $adrss = $this->db->where("user_id",$guest_id)->order_by("is_default","ASC")->get("shipping_addresses")->result_object();

        echo json_encode(array("action"=>"success","data"=>$adrss));
    }
    public function default_address()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $id =$post->id;

    

        $this->db->where("user_id",$guest_id)->update("shipping_addresses",array("is_default"=>0));
        $this->db->where("user_id",$guest_id)->where("id",$id)->update("shipping_addresses",array("is_default"=>1));

        echo json_encode(array("action"=>"success"));
    }
    public function delete_address()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $id =$post->id;

        $this->db->where("user_id",$guest_id)->where("id",$id)->delete("shipping_addresses");

        echo json_encode(array("action"=>"success"));
    }

    public function new_order()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $region = $this->db->where("id",$user_logged->region_id)->get('regions')->result_object()[0];
        

        $arr = array(
            "shipping_id"=>$post->shipping_id,
            "region_id"=>$user_logged->region_id,
            "shipping_fee"=>$post->shipping_fee,
            "tax"=>$post->tax,
            "total"=>$post->total,
            "currency"=>$region->currency,
            "user_id"=>$guest_id,
            "anonymous"=>$post->is_guest,
            "created_at"=>date("Y-m-d H:i:s"),
            "status"=>1,
            "from_web_mobile" => 2,
            "address_text"=>$this->get_json_of_address($post->shipping_id)
        );

        $this->db->insert("orders",$arr);

        $order_id = $this->db->insert_id();

        $products = $post->products;


        $prods = "";

        foreach($products as $product)
        {

            
            $pr = $this->enrich_signle_product($product->id,2);

            $price = $pr->discount>0?$pr->discounted_price:$pr->real_price;
            $prods .= "<br> ".$pr->title. " | Price: ".$price.', | QTY. '.$product->qty;
            if($post->from_mobile == 1){
                $variation_cus_txt = json_encode($product->variation_text,JSON_UNESCAPED_UNICODE);
                
            }
            else {
               $variation_cus_txt =  $product->variation_text;
            }
            $product = array(
                "product_id"=>$product->id,
                "price"=>$product->price / $product->qty,
                "did_tap_options"=>$product->did_tap_options?1:0,
                "qty"=>$product->qty,
                "order_id"=>$order_id,
                "total"=>$product->qty *$product->price,
                "variation"=>$variation_cus_txt
            );
            $this->db->insert("order_products",$product);
        }


        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>$user_logged->id,
            "desc"=> "Customer has placed a new order #00CP".$order_id,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

          
        $this->db->insert("notifications",$thread);



        $mmmsg = "Hi ".$user_logged->first_name. ' '.$user_logged->last_name;
        $mmmsg .="<br> Your order has been received, you'll be notified shortly about further progress";
        $mmmsg .="<br><b>Order ID:</b> #00P" .$order_id;
        $mmmsg .="<br><b>Products:</b>";

        $mmmsg .= $prods;

        $mmmsg .="<br><b>Tax:</b> #00P" .$post->tax . ' '.$region->currency;
        $mmmsg .="<br><b>Shipping Fee:</b> #00P" .$post->shipping_fee . ' '.$region->currency;

        $mmmsg .="<br><b>Order Total:</b> #00P" .$post->total .' ' .$region->currency;

       


        $this->load->library('email');

        $this->email->from(settings()->email, 'SouqPack');
        $this->email->to($user_logged->email);
        $this->email->set_mailtype("html");
        $this->email->subject("New Order Received");
        $this->email->message($mmmsg);

        //$x = $this->email->send();

        echo json_encode(array("action"=>"success","order_id"=>$order_id));
    }

    public function cancel_order()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $order = $this->db->where("id",$post->order_id)->get("orders")->result_object()[0];

        if($order->user_id == $guest_id)
        {
            if($order->status<4)
            {
                $this->db->where("id",$post->order_id)->update("orders",array(
                    "status"=>7,
                    "reason"=>$post->rev_text,
                    "cancelled_by"=>"USER"
                ));


                echo json_encode(array("action"=>"success"));
                return;

            }
            else
            {
                echo json_encode(array("action"=>"failed","error"=>"Sorry, you cannot cancel the order at this point"));
                return;
            }
        }
    }

    public function cancel_order_web()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $order = $this->db->where("id",$post->id)->get("orders")->result_object()[0];
        
        if($order->user_id == $guest_id)
        {
            if($order->status<4)
            {
                $this->db->where("id",$post->id)->update("orders",array(
                    "status"=>7,
                    "reason"=>$post->cancel,
                    "cancelled_by"=>"USER"
                ));

                // NOTIFICATION
                $thread = array(
                    "by"=>"Customer",
                    "order_id"=>$order->id,
                    "by_id"=>0,
                    "desc"=> "Order #".$order->id." has been cancelled by the user.<br><b>REASON:</b> ".$post->cancel,
                    "for_admin"=>1,
                    "is_payment"=>0,
                    "is_normal"=>1,
                    "created_at"=>date("Y-m-d H:i:s")
                );
                $this->db->insert("notifications",$thread);

                echo json_encode(array("action"=>"success","error"=>"Order Cancelled successfully!"));
                return;

            }
            else
            {
                echo json_encode(array("action"=>"failed","error"=>"Sorry, you cannot cancel the order at this point"));
                return;
            }
        }
    }

     public function refund_order_web()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);
      
        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $order = $this->db->where("id",$post->id)->get("orders")->result_object()[0];
        // echo $this->db->last_query();
        // print_r($order);
        if($order->user_id == $guest_id)
        {
            if($order->status!=7)
            {
                
                $this->db->where("id",$post->id)->update("orders",array(
                    "status"=>8,
                ));

                // REFUND FORM
                $refund = array(
                    "pID"=>$post->id,
                    "uID"=>$guest_id,
                    "bank_name"=>$post->bankname,
                    "account_number"=>$post->accountnum,
                    "account_holder"=>$post->holdername,
                    "bank_address"=>$post->address,
                    "refund_reason"=>$post->reason,
                    "date_created"=>date("Y-m-d H:i:s")
                );
                $this->db->insert("refund",$refund);

                // ORDER RECEIVED EMAIL
                $order_id = $post->id;
                $subject = "Order Refund Request #";
                $view_ar = "order_refund_ar";
                $view_en = "order_refund_english";
                $mobile_desc = "Refund Request Against your order #".$post->id." on SouqPack has been received.";
                $mobile_desc_ar = "طلب رد الأموال مقابل طلبك # ".$post->id." على سوق.";
                $this->email_sent_souqpack($order_id,$subject,$view_ar,$view_en,$mobile_desc,$mobile_desc_ar);

                // NOTIFICATION
                $thread = array(
                    "by"=>"Customer",
                    "order_id"=>$order->id,
                    "by_id"=>0,
                    "desc"=> "A Refund Request has been initiated against the Order #".$order->id,
                    "for_admin"=>1,
                    "is_payment"=>0,
                    "is_normal"=>1,
                    "created_at"=>date("Y-m-d H:i:s")
                );
                $this->db->insert("notifications",$thread);

                echo json_encode(array("action"=>"success","error"=>"Order Refunded Request initiated successfully!"));
                return;

            }
            else
            {
                echo json_encode(array("action"=>"failed","error"=>"Sorry, this request can't be processed at this time."));
                return;
            }
        }
    }


    public function new_order_web()
    {
        //COME BACK
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        

        $arr = array(
            "shipping_id"=>0,
            "shipping_fee"=>0,
            "tax"=>0,
            "region_id"=>$post->region_id,
            "total"=>$post->total,
            "payment_method"=>$post->payment_method,
            "payment_done"=>0,
            "currency"=>$post->currency,
            "user_id"=>$guest_id,
            "anonymous"=>$post->is_guest,
            "created_at"=>date("Y-m-d H:i:s"),
            "status"=>1,
            "address_text"=>json_encode($post),
            "from_web_mobile"=>1,
            "lang_id" => $post->lang_id==""?2:$post->lang_id,
        );
        
        $this->db->insert("orders",$arr);

      
        $order_id = $this->db->insert_id();

        $products = $post->products;
        // echo "<pre>";
        // //print_r($products);
        
        foreach($products as $product)
        {
            // print_r($product->custom_variation);
            $json_var = json_encode($product->custom_variation,JSON_UNESCAPED_UNICODE);

            $pr = $this->enrich_signle_product($product->id,2);
            $product = array(
                "product_id"=>$product->id,
                "price"=>$product->price,
                "qty"=>$product->qty,
                "order_id"=>$order_id,
                "total"=>$product->qty * $product->price,
                "variation"=>$json_var,
            );
           
           $this->db->insert("order_products",$product);
        }
        
// die;


        $arr = array(
            "first_name"=>$post->firstname,
            "email"=>$post->email,
            "address"=>$post->address,
            "last_name"=>$post->lastname,
            "phone"=>$post->phone,
            "city"=>$post->city,
            "street"=>$post->address,
            "user_id"=>$guest_id
        );

        $count= $this->db->where("user_id",$guest_id)->count_all_results("shipping_addresses");

        if($count==0)
        {
            $arr["is_default"] = 1;
            $this->db->insert("shipping_addresses",$arr);
        }
        else{
            $arr["is_default"] = 0;
        }

        // NOTIFICATION
        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>0,
            "desc"=> "A new order (#".$order_id.") has been placed by '<b>".$post->firstname." ".$post->lastname."</b>'<br> Waiting for payment against the order from client",
            "for_admin"=>1,
            "is_payment"=>0,
            "is_normal"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $this->db->insert("notifications",$thread);
        

        echo json_encode(array("action"=>"success","order_id"=>$order_id));
    }
    public function get_my_orders()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $lang_id =$post->lang_id;
        

        $orders = $this->db->where("user_id",$guest_id)->order_by("id","DESC")->get("orders")->result_object();


        foreach($orders as $key=>$order)
        {
            $order_products = $this->db->where("order_id",$order->id)->get("order_products")->result_object();

            foreach($order_products as $kk=>$order_product)
            {
                $order_products[$kk]->pdata = $this->enrich_signle_product($order_product->product_id,$lang_id);
            }

            $orders[$key]->products = $order_products;
        }

        echo json_encode(array("action"=>"success","data"=>array("orders"=>$orders)));
    }
    public function get_my_custom_orders()
    {


        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $lang_id =$post->lang_id;



        $my_corders = $this->db->where("user_id",$user_logged->id)->order_by("id","DESC")->get("c_orders")->result_object();


        foreach($my_corders as $key=>$order)
        {

            $my_corders[$key]->at = date("F d, Y",strtotime($order->created_at));

            // if($order->status == 1){
            //     $status_text = "Order Received";
            // } if($order->status == 2){
            //     $status_text="Assigned to Designer";
            // } if($order->status == 3){
            //         $status_text="Waiting for sample approval";
            // } if($order->status == 4){
            //     $status_text = "Order In Production";
            // } if($order->status == 5){
            //     $status_text ="Cancelled";
            // }
            // if($order->status == 6){
            //     $status_text ="Revision";
            // }
            // if($order->status == 7){
            //     $status_text ="Completed";
            // }
            // if($order->status == 8){
            //     $status_text ="Pending Down Payment";
            // }
            // if($order->status == 10){
            //     $status_text ="Pending Down Payment";
            // }
            if($order->admin_status==4 && $order->payment_done_part_1 !=0){
                $status_text = $lang_id!=1?"Down Payment Done":"تم استلام الدفعة الأولي";
            }
            else 
            {
                $status_text = admin_status($order->admin_status,$lang_id);
            }
            $my_corders[$key]->pay_now=0;

            if($order->status>2)
            {

                if($order->payment_done_part_2 == 0){
                    $payment_text = $lang_id!=1?"Pending Final Payment":"في انتظار الدفعة النهائية";
                    $my_corders[$key]->pay_now=1;
                }
                else
                {
                    $payment_text = "All Payment Clear";
                }
            }
            else
            {
                if($order->payment_done_part_1 == 0){
                    $payment_text = $lang_id!=1?"Pending Down Payment":"في انتظار الدفعة الأولي";
                    $my_corders[$key]->pay_now=1;
                }
                else
                {
                    $payment_text = $lang_id!=1?"Down Payment Done":"تم استلام الدفعة الأولي";;
                }
            }

            $my_corders[$key]->status_text = $status_text;
            $my_corders[$key]->payment_text = $payment_text;


            $sub_cat = $this->db->where("id",$order->cat_id)->get("categories")->result_object()[0];

            $cat = $this->db->where("id",$sub_cat->parent)->get("categories")->result_object()[0];

            $my_corders[$key]->sub_cat = $sub_cat;
            $my_corders[$key]->cat = $cat;
            $my_corders[$key]->file_src = base_url()."resources/uploads/orders/".$order->file_name;


            $my_corders[$key]->price_text = with_currency_bilal(number_format($order->price,2),$lang_id);
            $my_corders[$key]->stamps_price_text = with_currency_bilal(number_format($order->logo_cost,2),$lang_id);
            $my_corders[$key]->vat_text = with_currency_bilal(number_format($order->vat,2),$lang_id);
            $my_corders[$key]->shipping_text = with_currency_bilal(number_format($order->shipping,2),$lang_id);
            $my_corders[$key]->down_text = with_currency_bilal(number_format($order->total,2),$lang_id);
            // $my_corders[$key]->payment_text = with_currency_bilal($order->total);
            $my_corders[$key]->all_total_text = with_currency_bilal(number_format($order->all_total,2),$lang_id);
        }
        echo json_encode(array("action"=>"success","data"=>array("orders"=>$my_corders)));
    }
    public function get_order_products()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $lang_id =$post->lang_id;
        

        $order = $this->db->where("id",$post->order_id)->where("user_id",$guest_id)->order_by("id","DESC")->get("orders")->result_object()[0];

       
       
        $order_products = $this->db->where("order_id",$order->id)->get("order_products")->result_object();

        foreach($order_products as $kk=>$order_product)
        {
            $order_products[$kk]->pdata = $this->enrich_signle_product($order_product->product_id,$lang_id);
        }

        echo json_encode(array("action"=>"success","data"=>array("order"=>$order,"products"=>$order_products)));
    }
    public function submit_review()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $order_id = $post->order_id;
        

        $arr = array(
            "user_id"=>$guest_id,
            "order_id"=>$order_id,
            "rating"=>$post->rating,
            "review"=>$post->rev_text!=""?$post->rev_text:$post->revieww,
            "image"=>$post->image,
            "anony"=>$post->anony==1?1:0,
            "created_at"=>date("Y-m-d H:i:s"),
            "type"=>$post->type==2?2:1,
            "status"=>0
        );

        $this->db->insert("order_reviews",$arr);

        // $this->db->where("id",$order_id)->update("orders",array("status"=>5));

        $orders=array();

        $lang_id =$post->lang_id;
        if($post->review!=2){

            $orders = $this->db->where("user_id",$guest_id)->get("orders")->result_object();


            foreach($orders as $key=>$order)
            {
                $order_products = $this->db->where("order_id",$order->id)->get("order_products")->result_object();

                foreach($order_products as $kk=>$order_product)
                {
                    $order_products[$kk]->pdata = $this->enrich_signle_product($order_product->product_id,$lang_id);
                }

                $orders[$key]->products = $order_products;
            }
        }



        echo json_encode(array("action"=>"success","data"=>array("orders"=>$orders)));
    }
    private function get_json_of_address($id)
    {
        $add = $this->db->where("id",$id)->get("shipping_addresses")->result_object()[0];

        return json_encode($add);
    }
    public function redeem_coupon()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $code = $post->code;


        $used = $this->db->where("code",$code)->where("user_id",$guest_id)->count_all_results("redeemed_coupons");

        

        if($used>0)
        {
            echo json_encode(array("action"=>"failed","error"=>"You've already redeemed this coupon"));
            return;
        }

        $available = $this->db->where("code",$code)->where("DATE(from_date) <=",date("Y-m-d"))
        ->where("DATE(to_date) >=",date("Y-m-d"))
        ->where("status",1)
        ->where("is_deleted",0)
        ->get("coupons")->result_object()[0];

        if(!$available)
        {
            echo json_encode(array("action"=>"failed","error"=>"Invalid Coupon"));
            return;
        }

        $arr = array(
            "c_id"=>$available->id,
            "code"=>$available->code,
            "discount_type"=>$available->discount_type,
            "discount"=>$available->discount,
            "user_id"=>$guest_id,
            "currency"=>get_currency(),
            "status"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

        $this->db->insert("redeemed_coupons",$arr);
        $copons = $this->print_my_coupons($guest_id);
        echo json_encode(array("action"=>"success","data"=>array("coupons"=>$copons)));
        return;
    }
    public function get_coupons()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
       $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $copons = $this->print_my_coupons($guest_id);


        echo json_encode(array("action"=>"success","data"=>array("coupons"=>$copons)));
    }
    private function print_my_coupons($guest_id)
    {
        $copons = $this->db->where("user_id",$guest_id)->get("redeemed_coupons")->result_object();
        foreach($copons as $key=>$copon)
        {
            if($copon->discount_type=="0")
            {
                $discount =  "N/A";
            }elseif($copon->discount_type=="1")
            {
                $discount =  with_currency($copon->discount);
            }elseif ($copon->discount_type=="2") {
                $discount =  $copon->discount."%";
            }
            else $discount =  "N/A";

            $copons[$key]->discount_text = $discount;
        }
        return $copons;
    }

    public function wish_me()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $product_id =$post->product_id;


        $product = $this->db->where("id",$product_id)->get("products")->result_object()[0];

        if($product->lparent==0)
        {

        }
        else{
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
        }


        $this->db->where("user_id",$guest_id)->where("product_id",$product->id)->delete("wishlist");


        if($post->do_add == 1)
        {
            $ar = array(

                "user_id"=>$guest_id,
                "product_id"=>$product->id,
                "created_at"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("wishlist",$ar);
        }

        echo json_encode(array("action"=>"success"));
    }

    public function gender_me()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $finale_gender = "male";

        if($user_logged->gender=="male") $finale_gender="female";


        $this->db->where("id",$user_logged->id)->update("users",array("gender"=>$finale_gender));


  
        echo json_encode(array("action"=>"success"));
    }


    public function update_account()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $ar = array(
            "first_name"=>$post->first_name,
            "last_name"=>$post->last_name,
            "email"=>$post->email
        );


        $this->db->where("id",$user_logged->id)->update("users",$ar);


  
        echo json_encode(array("action"=>"success"));
    }
    public function type_me()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


        $finale_gender = "p";

        if($user_logged->acct_type=="p") $finale_gender="c";


        $this->db->where("id",$user_logged->id)->update("users",array("acct_type"=>$finale_gender));


  
        echo json_encode(array("action"=>"success"));
    }    
    public function follow_me()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $store_id =$post->store_id;


        $store = $this->db->where("id",$store_id)->get("stores")->result_object()[0];

        if($store->lparent==0)
        {

        }
        else{
            $store = $this->db->where("id",$store->lparent)->get("stores")->result_object()[0];
        }


        $this->db->where("user_id",$guest_id)->where("store_id",$store->id)->delete("store_followers");


        if($post->do_add == 1)
        {
            $ar = array(

                "user_id"=>$guest_id,
                "store_id"=>$store->id,
                "created_at"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("store_followers",$ar);
        }

        echo json_encode(array("action"=>"success"));
    }
    public function remove_wishlist()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $product_id =$post->product_id;


        $product = $this->db->where("id",$product_id)->get("products")->result_object()[0];

        if($product->lparent==0)
        {

        }
        else{
            $product = $this->db->where("id",$product->lparent)->get("products")->result_object()[0];
        }


        $this->db->where("user_id",$guest_id)->where("product_id",$product->id)->delete("wishlist");


        

        echo json_encode(array("action"=>"success"));
    }
    public function remove_wishlist2()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;
        $product_id =$post->product_id;


     


        $this->db->where("user_id",$guest_id)->where_in("product_id",$product->id)->delete("wishlist");


        

        echo json_encode(array("action"=>"success"));
    }
     public function remove_search()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;


       

        $this->db->where("user_id",$guest_id)->delete("search_history");


        

        echo json_encode(array("action"=>"success"));
    }
    public function get_wishlist()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $this->region_id = $post->region_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        // $lang_id = 2;

        $my_wishes = $this->db->where("user_id",$this->guest_id)->get("wishlist")->result_object();

        $ids = array(-1);

        foreach($my_wishes as $my_wish) $ids[] = $my_wish->product_id;

        $this->db->where_in("id",$ids);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $products = $this->db->get('products')->result_object();


        echo json_encode(array("action"=>"success","data"=>array('products'=>$this->enrich_products($products,$lang_id))));
    }
    public function get_followed_stores()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        // $lang_id = 2;

        $my_stores = $this->db->where("user_id",$this->guest_id)->get("store_followers")->result_object();

        $ids = array(-1);

        foreach($my_stores as $my_store) $ids[] = $my_store->store_id;

        $this->db->where_in("id",$ids);
        
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $stores = $this->db->get('stores')->result_object();



        $this->db->where("lang_id",$lang_id);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $this->db->order_by("id","DESC");
        $likes = $this->db->get('stores')->result_object();


        echo json_encode(
            array("action"=>"success",
                "data"=>array(
                    'stores'=>$this->enrich_stores($stores,$lang_id),
                    'might_likes'=>$this->enrich_stores($likes,$lang_id),
                )));
    }
    private function enrich_stores($stores,$lang_id)
    {
        foreach($stores as $key=>$store)
        {
            $stores[$key]=$this->enrich_signle_store($store,$lang_id);
        }
        return $stores;
    }

    private function enrich_signle_store($store,$lang_id)
    {

        $this->db->group_start();
        $this->db->where("lang_id",$lang_id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("id",$store->id);
        $this->db->or_where("lparent",$store->id);
        $this->db->group_end();
        
        $store = $this->db->get('stores')->result_object()[0];


        $boss_id = $store->lparent==0?$store->id:$store->lparent;

       

        $this->db->where("store_id",$boss_id);
        $this->db->where("status",1);
        $this->db->where("parent",0);
        $this->db->where("is_deleted",0);
        $this->db->limit(6);
        $products = $this->db->get('products')->result_object();




        $products = $this->enrich_products($products,$lang_id);

        if(!empty($products))
           $products[0]->active = true;
        else $products = array();
       
        

        
        $store->real_id = $store->id;




        $store->title = $this->get_store_title($store->id);
        $store->image = $this->get_store_image($store->id);
        $store->products = $this->enrich_products($products,$lang_id);

        $store->followers = $this->db->where("store_id",$boss_id)->count_all_results("store_followers");
        
        $store->in_follow = $this->is_in_follow($store);

        if($store->lparent!=0)
        {
            $store->id = $store->lparent;
        }

        return $store;
    }
    private function get_store_title($id)
    {
        $store = $this->db->where("id",$id)->get("stores")->result_object()[0];
        if($store->title!="") return $store->title;
        else if($store->lparent!=0)
        {
            $store = $this->db->where("id",$store->lparent)->get("stores")->result_object()[0];
            return $store->title; 
        }
        return "N/A";
    }
    private function get_store_image($id)
    {
        $store = $this->db->where("id",$id)->get("stores")->result_object()[0];
        
        if($store->image!=""){
            if(file_exists("./resources/uploads/stores/".$store->image))
            return $store->image;

        }
        else if($store->lparent!=0)
        {
            $store = $this->db->where("id",$store->lparent)->get("stores")->result_object()[0];
            if(file_exists("./resources/uploads/stores/".$store->image))
            return $store->image; 
        }


        return "dummy_store.png";
    }
    public function install_guest()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $this->guest_id = $post->guest_id;
        $guest_id = $post->guest_id;

        if(!$lang_id) $lang_id = dlang()->id;

        if($guest_id=="") $guest_id = time().rand(11,99);


        $arr = array(
            "guest_id"=>$guest_id,
            "lang_id"=>$lang_id,
            "is_guest"=>1
        );
        $user_id = 0;
        $exists = $this->db->where("guest_id",$guest_id)->get("users")->result_object()[0];
        if($exists)
        {
            $this->db->where("id",$exists->id)->update("users",array("lang_id"=>$lang_id));
            $user_id = $exists->id;
        }
        else{
            $this->db->insert("users",$arr);
            $user_id = $this->db->insert_id();
        }

        $this->do_sure_login($user_id);
    }
    public function get_page()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        $this->db->where("slug",$post->slug);
        $this->db->where("lparent",0);
        $this->db->where("status",1);
        $this->db->where("is_deleted",0);
        $page = $this->db->get('pages')->result_object()[0];
        
        $lang_page = $page;
        if($page->lang_id!=$lang_id)
        {
            $lang_page = $this->db->where("lparent",$page->id)->where("lang_id",$lang_id)->get("pages")->result_object()[0];

        }

        $content = $lang_page->descriptions==""?$page->descriptions:$lang_page->descriptions;
        $content = strip_tags($content);
        $data = array(
            "title"=>$lang_page->title==""?$page->title:$lang_page->title,
            "content"=>$content,

            "meta_title"=>$lang_page->meta_title!=""?$lang_page->meta_title:$page->meta_title,
            "meta_keywords"=>$lang_page->meta_keywords!=""?$lang_page->meta_keywords:$page->meta_keywords,
            "meta_description"=>$lang_page->meta_description!=""?$lang_page->meta_description:$page->meta_description,


        );
        

        echo json_encode(array("data"=>$data,"action"=>"success"));
    }
    public function get_page_web()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        if($post->slug == "faq"){
            $this->db->where("lparent",0);
            $this->db->where("status",1);
            $this->db->where("is_deleted",0);
            $page = $this->db->get('faqs')->result_object();
            foreach($page as $pag){
                $lang_page = $pag;
                if($pag->lang_id!=$lang_id)
                {
                    $lang_page = $this->db->where("lparent",$pag->id)->where("lang_id",$lang_id)->get("faqs")->result_object()[0];
                }
                $content = $lang_page->description==""?$pag->description:$lang_page->description;
                $data[] = array(
                        "title"=>$lang_page->title==""?$pag->title:$lang_page->title,
                        "content"=>$content,
                        "id"=>$pag->id,
                    );

            }
            echo json_encode(array("data"=>$data,"action"=>"success"));

        }else {
            $this->db->where("slug",$post->slug);
            $this->db->where("lparent",0);
            $this->db->where("status",1);
            $this->db->where("is_deleted",0);
            $page = $this->db->get('pages')->result_object()[0];
            
            $lang_page = $page;
            if($page->lang_id!=$lang_id)
            {
                $lang_page = $this->db->where("lparent",$page->id)->where("lang_id",$lang_id)->get("pages")->result_object()[0];

            }

            $content = $lang_page->descriptions==""?$page->descriptions:$lang_page->descriptions;
            $content = ($content);
            $data = array(
                "title"=>$lang_page->title==""?$page->title:$lang_page->title,
                "content"=>$content,
            );
            

            echo json_encode(array("data"=>$data,"action"=>"success"));
        }
    }

    public function get_notifications()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $lang_id =$post->lang_id;

        //$this->db->where("lparent",0);
        // $this->db->where("status",1);
        $this->db->where("by_id",0);
        $this->db->order_by("id","DESC");
        $this->db->limit(20);
        $notifications = $this->db->get('corder_threads')->result_object();


        foreach($notifications as $key=>$notification)
        {
            $lang_notif = $notification;
            if($notification->lang_id!=$lang_id)
            {
                // $lang_notif = $this->db->where("lparent",$notification->id)->where("lang_id",$lang_id)->get("notifications")->result_object()[0];
                $lang_notif = $this->db->where("by_id",122)->get("corder_threads")->result_object()[0];
            }
            
           

            // $notifications[$key]->title = $lang_notif->title==""?$notification->title:$lang_notif->title;
            // $notifications[$key]->sub_title = $lang_notif->desc==""?$notification->desc:$lang_notif->desc;
            $notifications[$key]->title = "";
            $notifications[$key]->sub_title = "";
            //$notifications[$key]->image = $lang_notif->image==""?$notification->image:$lang_notif->image;
        }
        

        
        

        echo json_encode(array("data"=>array("notifications"=>$notifications),"action"=>"success"));
    }
    public function update_lang()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        

        $lang_id =$post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        $this->db->where("id",$user_logged->id)->update("users",array("lang_id"=>$lang_id));

        echo json_encode(array("action"=>"success"));
    }
    public function update_region()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        

        $lang_id =$post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;


        $region_object = $this->db->where("title",$post->region)->get("regions")->result_object()[0];

        if($region_object->lang_id!=2)
        {
            $this->db->where("lang_id",2);
            $this->db->group_start();
            $this->db->where("lparent",$region_object->id);
            $this->db->or_where("id",$region_object->lparent);
            $this->db->group_end();

            $region_object = $this->db->get("regions")->result_object()[0];
        }

        $this->db->where("id",$user_logged->id)->update("users",array("region"=>$post->region,"region_id"=>$region_object->id));

        echo json_encode(array("action"=>"success"));
    }
    public function get_payment_methods()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        

        $lang_id = $post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        $methods = $this->db->where("status",1)->get("payment_methods")->result_object();

        // foreach($methods as $key=>$method)
        // {
        //     $methods[$key]->is_default=0;
        // }

        //$methods[1]->is_default=0;

        echo json_encode(array("action"=>"success","data"=>array("methods"=>$methods)));
    }
    public function pay_paypal($order_id=0)
    {
        $order = $this->db->where("id",$order_id)->get("orders")->result_object()[0];

        $data["total"] = $order->total;
        $the_payment_method = $this->db->where("type",1)->get("payment_methods")->result_object()[0];

        if($the_payment_method->status!=1)
        {
            echo "PayPal is disabled";exit;
        }

        if($order->payment_done==1)
        {
            echo "Already Paid";exit;
        }

        $the_key = $the_payment_method->paypal_api;

        $data["the_key"] = $the_key;
        $data["the_id"] = $order_id;
        $this->load->view("frontend/paypal",$data);
    }
    public function pay_payfort($order_id=0)
    { 

        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST; 

        // $user_logged = $this->do_auth($post); 

        $paypal=$this->db->where("type",3)->get("payment_methods")->result_object();



        $order = $this->db->where("id",$order_id)->get("orders")->result_object()[0];

        $data["total"] = $order->total;
        $the_payment_method = $this->db->where("type",3)->get("payment_methods")->result_object()[0];

        if($the_payment_method->status!=1)
        {
            echo "Payfort is disabled";exit;
        }

        if($order->payment_done==1)
        {
            echo "Already Paid"; exit;
        }
        $amount = $order->total;

        $from__ = $order->currency;

        $to___ = "_SAR";

        $currency_convo = $from__.$to___;


        // $convert = file_get_contents("https://free.currconv.com/api/v7/convert?q=".$currency_convo."&compact=ultra&apiKey=19fbc6cd58c182b44e25");



        // $amount = $amount * (json_decode($convert)->$currency_convo);

        $amount = (int) $amount;


        $amount = ($amount*100);

        $shaString  = '';
        // array request
        $arrData    = array(
        'command'            =>'PURCHASE',
        'access_code'        =>'Sx6NVacbc8BdqsHkT4n5',
        'merchant_identifier'=>'ZYGPBMlV',
        'merchant_reference' =>$order->id,
        'amount'             =>$amount,
        //'currency'           =>$from__,
        'currency'           =>'SAR',
        'language'           =>'en',
        'customer_email'     =>"nomail@souqpack.com",
        "return_url"=>base_url()."api/complete_payfortv2"
        );
        // sort an array by key
        ksort($arrData); 
        foreach ($arrData as $key => $value) {
            $shaString .= "$key=$value";
        }
        // make sure to fill your sha request pass phrase
        $shaString = '13PdQlvh8kZIPro/nI9dXp{$' . $shaString . '13PdQlvh8kZIPro/nI9dXp{$';
        $signature = hash("sha256", $shaString);

        // your request signature

        if($signature)
        {

            $arrData["signature"] = $signature;

            $data["arr_data"] = $arrData;
            $this->load->view("frontend/payfort",$data);
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function pay_payfort_custom($order_id=0,$user_id=0)
    { 
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST; 
        $user_logged = $this->db->where("id",$user_id)->get("users")->result_object()[0]; 

        $paypal=$this->db->where("type",3)->get("payment_methods")->result_object();



        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];

        $data["total"] = $order->total;
        $the_payment_method = $this->db->where("type",3)->get("payment_methods")->result_object()[0];

        if($the_payment_method->status!=1)
        {
            echo "Payfort is disabled";exit;
        }

        
        $amount = (int) $order->down_payment;



        $amount = ($amount*100);

       

        $shaString  = '';
        // array request
        $arrData    = array(
        'command'            =>'PURCHASE',
        'access_code'        =>'Sx6NVacbc8BdqsHkT4n5',
        'merchant_identifier'=>'ZYGPBMlV',
        'merchant_reference' =>$order->payfort_id,
        'amount'             =>$amount,
        'currency'           =>"SAR",
        'language'           =>'en',
        'customer_email'     =>$user_logged->email!=""?$user_logged->email:"noemail@gmail.com",
        "return_url"=>base_url()."api/complete_payfort_c_order_phone"
        );
        // sort an array by key
        ksort($arrData);
        foreach ($arrData as $key => $value) {
            $shaString .= "$key=$value";
        }
        // make sure to fill your sha request pass phrase
        $shaString = '13PdQlvh8kZIPro/nI9dXp{$' . $shaString . '13PdQlvh8kZIPro/nI9dXp{$';
        $signature = hash("sha256", $shaString);

        // your request signature

        if($signature)
        {

            $arrData["signature"] = $signature;

            $data["arr_data"] = $arrData;
            $this->load->view("frontend/payfort",$data);
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function pay_stripe($order_id=0)
    {
        $order = $this->db->where("id",$order_id)->get("orders")->result_object()[0];

        $data["total"] = $order->total;
        $the_payment_method = $this->db->where("type",2)->get("payment_methods")->result_object()[0];

        if($the_payment_method->status!=1)
        {
            echo "PayPal is disabled";exit;
        }

        // if($order->payment_done==1)
        // {
        //     echo "Already Paid";exit;
        // }


        require './vendor/autoload.php';
        \Stripe\Stripe::setApiKey($the_payment_method->stripe_secret);

        $intent = \Stripe\PaymentIntent::create([
           'amount' => $order->total*100, // we multiply the amount by hundered cuz it's required in cents, not in dollars, if we demand 5 dollars, we pass it as 5*100 = 500 cents
           'currency' => 'usd',
        ]);

        $data["client_secret"] = $intent->client_secret;

        $the_key = $the_payment_method->stripe_api;

        $data["the_key"] = $the_key;
        $data["the_id"] = $order_id;
        $this->load->view("frontend/stripe",$data);
    }
    public function complete_paypal()
    {
        $order_id = $this->input->post("order_id");

        $object = $this->input->post("object");

        $this->db->where("id",$order_id)->update("orders",array(
            "payment_method"=>1,
            "payment_object"=>json_encode($object),
            "payment_done"=>1,
        ));

        echo "success";
    }
    public function complete_stripe()
    {
        $order_id = $this->input->post("order_id");

        $object = $this->input->post("object");

        $this->db->where("id",$order_id)->update("orders",array(
            "payment_method"=>2,
            "payment_object"=>json_encode($object),
            "payment_done"=>1,
        ));

        echo "success";
    }

    public function complete_cod()
    {


        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        

        $lang_id = $post->lang_id;

        $user_logged = $this->do_auth($post);

        $this->guest_id = $user_logged->id;
        $guest_id = $user_logged->id;

        if(!$lang_id) $lang_id = dlang()->id;

        $order_id = $post->order_id;


        $this->db->where("id",$order_id)->update("orders",array(
            "payment_method"=>4,
            "payment_done"=>0
        ));

        echo json_encode(array("action"=>"success"));
    }
    public function try_coupon()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $coupon_code = $post->coupon;

        $coupon_ = $this->db->where("code",$coupon_code)
        ->where("status",1)
        ->where("is_deleted",0)
        ->where("DATE(from_date) <=",date("Y-m-d"))
        ->where("DATE(to_date) >=",date("Y-m-d"))
        ->get("coupons")->result_object()[0];
        if($coupon_)
        {
            echo json_encode(array("action"=>"success","data"=>$coupon_));
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function try_coupon_s()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $coupon_code = $post->id;

        $coupon_ = $this->db->where("id",$coupon_code)
        ->where("status",1)
        ->where("is_deleted",0)
        ->where("DATE(from_date) <=",date("Y-m-d"))
        ->where("DATE(to_date) >=",date("Y-m-d"))
        ->get("coupons")->result_object()[0];

        if($coupon_)
        {
            echo json_encode(array("action"=>"success","data"=>$coupon_));
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function get_order_web()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $coupon_code = $post->order_id;

        $coupon_ = $this->db->where("id",$coupon_code)
        ->get("orders")->result_object()[0];


        $paypal=$this->db->where("type",1)->get("payment_methods")->result_object();

        $paypal_key = $paypal[0]->paypal_api;

        if($coupon_)
        {
            echo json_encode(array("action"=>"success","data"=>$coupon_,"paypal_key"=>$paypal_key));
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function get_payfort()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $user_logged = $this->do_auth($post);


        $order_id = $post->order_id;
        // $oder_payformt = $order_id;

        // $oder_id_get_data = substr($order_id,0,-3);
        
        $paypal=$this->db->where("type",3)->get("payment_methods")->result_object();



        $order = $this->db->where("id",$order_id)->get("orders")->result_object()[0];

        $data["total"] = $order->total;
        $the_payment_method = $this->db->where("type",3)->get("payment_methods")->result_object()[0];

        if($the_payment_method->status!=1)
        {
            echo "Payfort is disabled";exit;
        }

        if($order->payment_done==1)
        {
            echo "Already Paid"; exit;
        }
        $amount = $order->total;

        $from__ = $order->currency;

        $to___ = "_SAR";

        $currency_convo = $from__.$to___;


        // $convert = file_get_contents("https://free.currconv.com/api/v7/convert?q=".$currency_convo."&compact=ultra&apiKey=19fbc6cd58c182b44e25");



        // $amount = $amount * (json_decode($convert)->$currency_convo);

        $amount = (int) $amount;


        $amount = ($amount*100);

        $shaString  = '';
        // array request
        $arrData    = array(
            'command'            =>'PURCHASE',
            'access_code'        =>'Sx6NVacbc8BdqsHkT4n5',
            'merchant_identifier'=>'ZYGPBMlV',
            'merchant_reference' =>$order_id,
            'amount'             =>$amount,
            //'currency'           =>$from__,
            'currency'           =>'SAR',
            'language'           =>'en',
            'customer_email'     =>$user_logged->email,
            "return_url"=>base_url()."api/complete_payfort"
        );

        // sort an array by key
        ksort($arrData);
        foreach ($arrData as $key => $value) {
            $shaString .= "$key=$value";
        }
        // make sure to fill your sha request pass phrase
        $shaString = '13PdQlvh8kZIPro/nI9dXp{$' . $shaString . '13PdQlvh8kZIPro/nI9dXp{$';
        $signature = hash("sha256", $shaString);

        // your request signature
        $this->data["signature"] = $signature;

        if($signature)
        {
            echo json_encode(
                array(
                    "action"=>"success",
                    "data"=>$coupon_,
                    "payfort"=>array(
                        "access_code"               =>"89473987", 
                        "signature"                 =>$signature,
                        "vals"                      =>$arrData, 
                    )
                    

                ));
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function complete_payfort()
    { 
        $order_id = $this->input->post("merchant_reference"); 
        $object = $_REQUEST;

        $order_id = $object['merchant_reference'];
        //print_r($object);
        if($object['response_message'] == "Success" || $object['response_message'] == "success"){ 
            $arr = array(
                "payment_method"=>3,
                "payment_done"=>1,
                "payment_object"=>json_encode($object)
            );
                
            // ORDER RECEIVED EMAIL
            $order_id = $object['merchant_reference'];
            $subject = "Order Received #";
            $view_ar = "order_received_ar";
            $view_en = "order_received_english";
            
            $mobile_desc = "Your Order (#".$order_id.") has been placed on SouqPack and payment has been made successfully of amount ".$order_details->total.". Transaction ID : #".$object['fort_id'];
            $mobile_desc_ar = "تم وضع طلبك (# ".$order_id.") على SouqPack وتم سداد المبلغ بنجاح ". $order_details->total.". رقم المعاملة : #".$object['fort_id'];

            $this->email_sent_souqpack($order_id,$subject,$view_ar,$view_en,$mobile_desc,$mobile_desc_ar);
            
            $descp_notif = "Payment has been successfully made against order (#".$order_id.") of amount ".$order_details->total." via Payfort (Credit Card)<br> Transaction ID : #".$object['fort_id'];
        } else {
            $arr = array(
                "payment_method"        =>3,
                "payment_done"          =>2,
                "payment_object"        =>json_encode($object),
                "payment_reason_rejct"  =>$object['response_message']
            );
            $descp_notif = "Payment against order (#".$order_id.") was not made due to following reasons ".$object['response_message'];
        }

        //die;

        // NOTIFICATION
        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>0,
            "desc"=> $descp_notif,
            "for_admin"=>1,
            "is_payment"=>1,
            "is_normal"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $this->db->insert("notifications",$thread);

        $this->db->where("id",$order_id)->update("orders",$arr);

        if($object['response_message'] == "Success" || $object['response_message'] == "success"){
           // redirect(settings()->frontend_url."/profile?order_success=order");
             redirect(settings()->frontend_url."/thank-you");
        }else {
            redirect(settings()->frontend_url."/profile?order_failed=order_fail");
        }

        // echo "success";
    }
    public function invoice_template_send_user($order_id,$id=1){
            $where_invoice["id"]=$id;
            $this->data['where_invoice'] = $where_invoice;
            
            $where["id"]=$order_id;
            $this->data['where'] = $where;
            $this->data['show_border'] = 1;
            require_once './vendor/autoload.php';
            $new__new_name = "invoice_".$order_id;
            $html = $this->load->view('backend/invoice_templates/view',$this->data,true);
            // die;
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                // 'default_font_size' => 9,
                //  'default_font' => 'arial'
            ]);
            $mpdf->WriteHTML($html);
            $new_name = './resources/uploads/invoice/'.$new__new_name.".pdf";
            $upload_name = 'resources/uploads/invoice/'.$new__new_name.".pdf";
            $mpdf->Output($new_name,'F');
            return $upload_name;
            //echo $this->load->view('backend/invoice_templates/view',$this->data,true);
    }

     public function custom_invoice_template_send_user($order_id,$id=1,$down="no"){
            $where_invoice["id"]=$id;
            $this->data['where_invoice'] = $where_invoice;
            $this->data['down_remain'] = $down;
            $where["id"]=$order_id;
            $this->data['where'] = $where;
            $this->data['show_border'] = 1;
            require_once './vendor/autoload.php';
            $new__new_name = date(Ymdhis)."invoice_".$order_id;
            $html = $this->load->view('backend/invoice_templates/view_custom_invoice',$this->data,true);
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                // 'default_font_size' => 9,
                //  'default_font' => 'arial'
            ]);
            $mpdf->WriteHTML($html);
            $new_name = './resources/uploads/invoice/'.$new__new_name.".pdf";
            $upload_name = 'resources/uploads/invoice/'.$new__new_name.".pdf";
            $mpdf->Output($new_name,'F');
            return $upload_name;
            //echo $this->load->view('backend/invoice_templates/view',$this->data,true);
    }


    public function send_sms_to_users($phone=0,$desc=""){
        $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
        $sid = $twillio_db->twillio_pub;
        $token = $twillio_db->twillio_sec;
    
        $twilio = new Client($sid, $token);
        try{
            $message = $twilio->messages
                          ->create($phone, // to
                                   ["body" => $desc, "from" => "+13233100736", "locale" => "ar"]
                          );
        }catch(\Exception $e) { return get_class($e); }
        // $message = $twilio->messages
        //                   ->create($phone, // to
        //                            ["body" => $desc, "from" => "+13233100736"]
                         // );
        //print($message->sid);
    }
    public function email_sent_souqpack($order_id,$subject_title,$ar_view, $en_view,$desc_mobile="",$desc_mobile_ar=""){
        $order_details = $this->db->query("SELECT * FROM orders WHERE id = '".$order_id."'")->result_object()[0];
        $user_details = $this->db->query("SELECT * FROM users WHERE id = '".$order_details->user_id."'")->result_object();
        if(!empty($user_details) || $user_details[0]->email != ""){

            $email_user = $user_details[0]->email;
            // SEND EMAIL IF SUCCCESS
            
            $fromemail=settings()->email;
            $toemail = $email_user;
            //$subject = "Order Received #00".$order_details->id;
            $subject = $subject_title.$order_details->id;
            if($order_details->from_web_mobile == "1"){
                $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
            } else {
                if($user_details[0]->first_name!=""){
                    $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
                }else {
                    $user_name = $user_details[0]->phone;
                }
            }
            $order_date = date("F, d Y", strtotime($order_details->created_at));
            $order_id_email = "#".$order_details->id;
            $total_amount = $order_details->total." ".$order_details->currency;
            $shipping_cust_json = json_decode($order_details->address_text);
            $shipping_cust_name = $shipping_cust_json->firstname." ".$shipping_cust_json->lastname;
            $ship_address = $shipping_cust_json->address.", ".$shipping_cust_json->city;
            $shiping_cost = $order_details->shipping_fee." ".$order_details->currency;

            $this->data["products_order"] = $this->db->query("SELECT * FROM order_products WHERE `order_id` = '".$order_details->id."'")->result_object();
            if($order_details->lang_id == 1){
                $mesg = $this->load->view('frontend/emails/'.$ar_view,$this->data,true);
            }else {
                $mesg = $this->load->view('frontend/emails/'.$en_view,$this->data,true);
            }
            if($order_details->status > 4){
                $refund = $this->db->query("SELECT * FROM refund WHERE pID = ".$order_id)->result_object()[0];    
                if(!empty($refund)){
                    $refund_number = "#".$refund->id;
                    $contatc_num = settings()->mobile;
                    $contact_email = "help@souqpack.com";
                }
            }
            $souq = '<a href="https://souqpack.com" style="color:yellow;">SouqPack</a>';
            $search  = array('[CUSTOMER_NAME]','[ORDER_DATE]','[ORDER_NUMBER]','[TOTAL_AMOUNT]','[SHIPPING_CUSTOMER_NAME]','[SHIP_ADDR]','[SUB_TOTAL]','[SHIPPING_COST]','[REFUND_NUMBER]','[CONTACT_NUMBER]','[EMAIL_ADDR_COMPANY]',"SouqPack","Souqpack");
            $replace = array($user_name, $order_date, $order_id_email, $total_amount, $shipping_cust_name,$ship_address,$total_amount,$shiping_cost,$refund_number,$contatc_num,$contact_email,$souq,$souq);

            $new_html = str_replace($search,$replace,$mesg);

            $config=array(
            'charset'=>'UTF-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );

            $attachmt = $this->invoice_template_send_user($order_id);
            //$final_attch = base_url().$attachmt;
            $final_attch = FCPATH.$attachmt;
           //echo $final_attch = FCPATH.$attachmt;
             // $this->email->attach($final_attch);

            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");

            //$this->email->initialize($config);

            $this->email->to($toemail);
            $this->email->from($fromemail, 'SouqPack');
            $this->email->subject($subject);
            
            $this->email->message($new_html);
            $this->email->attach($final_attch);
            $this->email->send();
            // if($this->email->send())
            //  {
            //   echo 'Email send.';
            //  }
            //  else
            // {
            //  show_error($this->email->print_debugger());
            // }

            
        }
        if($order_details->lang_id == 1){
            $desc_mob = $desc_mobile_ar;
        }
        else {
            $desc_mob = $desc_mobile;
        }
        $phone = "+".$user_details[0]->code.$user_details[0]->phone;
        // SEND SMS
        $this->send_sms_to_users($phone,$desc_mob);
    }
    public function complete_payfort_c_order()
    {


        $this->child_complete_payfort_c_order();

        // redirect(settings()->frontend_url."/profile?order_success=order");
         redirect(settings()->frontend_url."/thank-you");
    }
    public function payfort_is_good()
    {
        echo "Acha";
    }
    public function payfort_is_bad()
    {
        echo "Oh";
    }
    public function complete_payfort_c_order_phone()
    {


        $status = $this->child_complete_payfort_c_order();
        if($status)
        redirect(base_url()."api/payfort_is_good");
        else
        redirect(base_url()."api/payfort_is_bad");

        // redirect(settings()->frontend_url."#/profile");
    }
    public function child_complete_payfort_c_order()
    {

        $order_id = $this->input->post("merchant_reference");
        $amount = $this->input->post("amount");
        $amount = $amount/100;
        $response_code = $this->input->post("response_code");
        $order = $this->db->where("payfort_id",$order_id)->get("c_orders")->result_object()[0];
        $user = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];

        
        $payment_status = 0;
        if($response_code==14000)
        {
            
            $object = $_REQUEST;
            //print_r($object);
           
            if($order->payment_done_part_1!=1){
                $update_arr = array(
                    "payment_method_part_1"=>3,
                    "payment_done_part_1"=>1,
                    "payment_arrived_part_1"=>$order->down_payment,
                    "payment_object_part_1"=>json_encode($object),
                    "total_arrived"=>$order->total_arrived + $amount,
                    "payfort_id"=>time()
                    
                );
                $payment_status=1;
                $msg = "A down payment of ".with_currency($amount)." has been paid via Pay Fort, reference #".$order_id. ", ".settings()->site_title." will verify this payment and order will be forward to production";
                $msg_ar = "دفعة أولى بقيمة ".with_currency($amount)." تم الدفع عبر Pay Fort ، المرجع # ".$order_id."، ".settings()->site_title." سيتحقق من هذا الدفع وسيتم إرسال الطلب إلى الإنتاج";

                //SEND CUSTON ORDER EMAIL RECEIVED

                $order_id = $order->id;
                $subject = "Custom Order Down Payment Received #";
                $view_ar = "custom_order_payment_received_ar";
                $view_en = "custom_order_payment_received_english";

                // DOWN PAYMENT
                $attachmt = $this->custom_invoice_template_send_user($order->id,1,"down");
                $final_attch = FCPATH.$attachmt;

                $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en,$final_attch);


            }
            else{


                $update_arr = array(
                    "payment_method_part_2"=>3,
                    "payment_done_part_2"=>1,
                    "payment_arrived_part_2"=>$order->down_payment,
                    "payment_object_part_2"=>json_encode($object),
                    "total_arrived"=>$order->total_arrived + $amount,
                    "payfort_id"=>time()


                );
                $payment_status=2;
                $msg = "A remaining payment of ".with_currency($amount)." has been paid via Pay Fort, reference #".$order_id. ", ".settings()->site_title." will verify this payment and order will be delivered after confirmation";
                $msg_ar = "الدفعة المتبقية من ".with_currency ($amount)." تم الدفع عبر Pay Fort ، المرجع # ".$order_id."، ".settings()->site_title." سوف يتحقق من هذا الدفع وسيتم تسليم الطلب بعد التأكيد";

                $order_id = $order->id;
                $subject = "Custom Order Final Payment Received #";
                $view_ar = "custom_order_payment_received_ar";
                $view_en = "custom_order_payment_received_english";

                // DOWN PAYMENT
                $attachmt = $this->custom_invoice_template_send_user($order->id);
                $final_attch = FCPATH.$attachmt;

                $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en,$final_attch);
                // $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
            }

            if($user->phone!=""){
                if($order->lang_id == 1){
                    $send_mobile_desc = $msg_ar;
                }
                else{
                    $send_mobile_desc = $msg;
                }
                
                $phone_sms = "+".$user->code.$user->phone;
                $this->send_sms_to_users($phone_sms,$send_mobile_desc);
            }

            //die;
            $update_arr["show_payment"]=0;
            $update_arr["payfort_id"]=time();

            $this->db->where("id",$order->id)->update("c_orders",$update_arr);

            $thread = array(
                "by"=>$user->first_name!=""?$user->first_name." ".$user->last_name:"Customer",
                "task_id"=>$order->id,
                "by_id"=>$user->id,
                "payment_status"=>$payment_status,
                "title"=>"Payment Made",
                "desc"=>$msg,
                "for_admin"=>1,
                "title_ar"=>"تم الدفع",
                "desc_ar"=>"",
                "created_at"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("corder_threads",$thread);


             $thread = array(
                "by"=>"Customer",
                "order_id"=>$order->id,
                "by_id"=>0,
                "desc"=> "Customer has paid an amount of ".with_currency($amount)." against order #00CP".$order->id,
                "for_admin"=>1,
                "is_payment"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

              
            $this->db->insert("notifications",$thread);
            return true;

        }
        else
        {
            $msg = "Payment has been failed, ".$this->input->post("response_message");
            $msg_ar = "فشل الدفع ، ".$this->input->post("response_message");
            $thread = array(
                "by"=>$user->first_name!=""?$user->first_name." ".$user->last_name:"Customer",
                "task_id"=>$order->id,
                "by_id"=>$user->id,
                "payment_status"=>$payment_status,
                "title"=>"Payment Failed",
                "desc"=>$msg,
                "for_admin"=>1,
                "title_ar"=>"عملية الدفع فشلت",
                "desc_ar" => $msg_ar,
                "created_at"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("corder_threads",$thread);
            return false;
        }
    }
    public function complete_payfortv2()
    {

     
        $order_id = $this->input->post("merchant_reference");


        // $object = $_REQUEST;

        // $this->db->where("id",$order_id)->update("orders",array(
        //     "payment_method"=>3,
        //     "payment_object"=>json_encode($object),
        // ));


        // echo "success";


       // $order_id = $this->input->post("merchant_reference");


        $object = $_REQUEST;
        $order_id = $object['merchant_reference'];
        //print_r($object);
        if($object['response_message'] == "Success" || $object['response_message'] == "success"){


            $arr = array(
                "payment_method"=>3,
                "payment_done"=>1,
                "payment_object"=>json_encode($object)
            );
                
                // ORDER RECEIVED EMAIL
                $order_id = $object['merchant_reference'];
                $subject = "Order Received #";
                $view_ar = "order_received_ar";
                $view_en = "order_received_english";
                

                $mobile_desc = "Your Order (#".$order_id.") has been placed on SouqPack and payment has been made successfully of amount ".$order_details->total.". Transaction ID : #".$object['fort_id'];

                $mobile_desc_ar = "تم وضع طلبك (# ".$order_id.") على SouqPack وتم سداد المبلغ بنجاح ". $order_details->total.". رقم المعاملة : #".$object['fort_id'];

                $this->email_sent_souqpack($order_id,$subject,$view_ar,$view_en,$mobile_desc,$mobile_desc_ar);

                $descp_notif = "Payment has been successfully made against order (#".$order_id.") of amount ".$order_details->total." via Payfort (Credit Card)<br> Transaction ID : #".$object['fort_id'];

                $returnURL = 0;
        } else {
            $arr = array(
                "payment_method"=>3,
                "payment_done"=>2,
                "payment_object"=>json_encode($object),
                "payment_reason_rejct"=>$object['response_message']
            );
            $descp_notif = "Payment against order (#".$order_id.") was not made due to following reasons ".$object['response_message'];
            $returnURL = 1;
        }

        // NOTIFICATION
        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>0,
            "desc"=> $descp_notif,
            "for_admin"=>1,
            "is_payment"=>1,
            "is_normal"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $this->db->insert("notifications",$thread);

        $this->db->where("id",$order_id)->update("orders",$arr);

        $order_detsss = $this->db->query("SELECT * FROM orders WHERE id = ".$order_id)->result_object()[0];
        //echo $this->db->last_query();
        if($order_detsss->from_web_mobile == 2 && $returnURL == 1){
            $this->db->query("DELETE FROM orders WHERE id = ".$order_id);
            redirect(base_url()."api/mobile_payment_error");
            die;
        }else {
            echo "success";
        }


    }

    public function mobile_payment_error(){
        echo "cancel";
    }
    public function save_order()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $coupon_code = $post->order_id;

        $order = $this->db->where("id",$coupon_code)
        ->get("orders")->result_object()[0];


        $this->db->where("id",$coupon_code)->update("orders",array("payment_object"=>json_encode($post->raw_data),"payment_done"=>1,"payment_method"=>1));

        

        if($order)
        {
            echo json_encode(array("action"=>"success"));
        }
        else
        {
            echo json_encode(array("action"=>"failed"));
        }
    }
    public function contact_req()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $mmmsg = "Hi admin";
        $mmmsg .="<br> We just received a contact us request from:";

        $mmmsg .="<br><b>Name:</b>".$post->firstname;
        $mmmsg .="<br><b>Phone:</b>".$post->phone;
        $mmmsg .="<br><b>Email:</b>".$post->email;
        $mmmsg .="<br><b>Subject:</b>".$post->subject;
        $mmmsg .="<br><b>Message:</b>".$post->msg;


        $this->load->library('email');

        $this->email->from(settings()->email, 'Peace');
        $this->email->to($post->email);
        $this->email->set_mailtype("html");
        $this->email->subject("Ping Request");
        $this->email->message($mmmsg);

        $x = $this->email->send();

        if($x)
        {
            echo json_encode(array("action"=>"success"));
        }
        else
        {
            echo json_encode(array("action"=>"failed","error"=>"our server is not good enough"));
        }
    }

    public function paypal_is_good()
    {
        echo "success";
    }
    public function get_mydashboard()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $user_logged = $this->do_auth($post);
        $lang_id = $post->lang_id;

        $my_orders = $this->db->where("user_id",$user_logged->id)->order_by("id","DESC")->get("orders")->result_object();


        foreach($my_orders as $key=>$order)
        {
            $order_products = $this->db->where("order_id",$order->id)->get("order_products")->result_object();
            $reund = $this->db->where("pID",$order->id)->get("refund")->result_object()[0];

            $my_orders[$key]->at = date("F d, Y",strtotime($order->created_at));
            
            if(!empty($reund)){

                if($reund->status == 0){
                    $Rstatus_text = "Received";
                } 
                if($reund->status == 1){
                    $Rstatus_text = "Confirmed";
                } 
                if($reund->status == 2){
                    $Rstatus_text = "Money Transferred";
                } 
                if($reund->status == 3){
                    $Rstatus_text = "Completed";
                } 
                if($reund->status == 4){
                    $Rstatus_text = "Declined";
                } 

                $my_orders[$key]->refund_status = $Rstatus_text;
                $my_orders[$key]->refunded_at = date("F d, Y H:i A",strtotime($reund->date_created));
                $my_orders[$key]->refund = $reund;
            }

            if($order->status == 1 || $order->status==2){
                $status_text = "Order Received";
             } if($order->status == 3){
                $status_text="Prepairing";
             } if($order->status == 4){
                    $status_text="Shipped";
             } if($order->status == 5){
                $status_text = "Review";
             } if($order->status == 6){
                $status_text = "Completed";
             } if($order->status == 7){
                $status_text ="<b class='color_red'>Cancelled</b>";
             } if($order->status == 8){
                $status_text ="<b><i>Refunded</i></b><br>(Status: ".$Rstatus_text.")";
             } 

            $my_orders[$key]->status_text = $status_text;

            foreach($order_products as $kk=>$order_product)
            {
                $order_products[$kk]->pdata = $this->enrich_signle_product($order_product->product_id,$lang_id);
            }


            $my_orders[$key]->products = $order_products;

           

        }






        $my_corders = $this->db->where("user_id",$user_logged->id)->order_by("id","DESC")->get("c_orders")->result_object();


        foreach($my_corders as $key=>$order)
        {

            $my_corders[$key]->at = date("F d, Y",strtotime($order->created_at));

            if($order->status == 1){
                $status_text = "Pending";
            } if($order->status == 2){
                $status_text="Under Work";
            } if($order->status == 3){
                    $status_text="Under Review";
            } if($order->status == 4){
                $status_text = "Sent to Production";
            } if($order->status == 5){
                $status_text ="Cancelled";
            }
            if($order->status == 6){
                $status_text ="Revision";
            }
            if($order->status == 7){
                $status_text ="Completed";
            }
            if($order->status == 8){
                $status_text ="Pending approval";
            }

            $my_corders[$key]->pay_now=0;

            if($order->status>2)
            {

                if($order->payment_done_part_2 == 0){
                    $payment_text = "2nd Payment Required";
                    $my_corders[$key]->pay_now=1;
                }
                else
                {
                    $payment_text = "All Payment Clear";
                }
            }
            else
            {
                if($order->payment_done_part_1 == 0){
                    $payment_text="---";
                    if($order->admin_status == 4){
                        $payment_text = "Down Payment Required";
                        $my_corders[$key]->pay_now=1;
                    }
                }
                else
                {
                    $payment_text = "Down Payment Clear";
                }
            }

            $my_corders[$key]->status_text = $status_text;
            $my_corders[$key]->payment_text = $payment_text;


            $sub_cat = $this->db->where("id",$order->cat_id)->get("categories")->result_object()[0];

            $cat = $this->db->where("id",$sub_cat->parent)->get("categories")->result_object()[0];

            $my_corders[$key]->sub_cat = $sub_cat;
            $my_corders[$key]->cat = $cat;
            $my_corders[$key]->file_src = base_url()."resources/uploads/orders/".$order->file_name;


            $my_corders[$key]->price_text = with_currency(number_format($order->price,2));
            $my_corders[$key]->stamps_price_text = with_currency(number_format($order->logo_cost,2));
            $my_corders[$key]->vat_text = with_currency(number_format($order->vat,2));
            $my_corders[$key]->shipping_text = with_currency(number_format($order->shipping,2));
            $my_corders[$key]->down_text = with_currency(number_format($order->total,2));
            // $my_corders[$key]->payment_text = with_currency($order->total);
            $my_corders[$key]->all_total_text = with_currency(number_format($order->all_total,2));
        }

        $adrss = $this->db->where("user_id",$user_logged->id)->order_by("is_default","ASC")->get("shipping_addresses")->result_object();

        echo json_encode(array("action"=>"success","data"=>array(
            "orders"=>$my_orders,
            "corders"=>$my_corders,
            "addresses"=>$adrss,
        )));
    }

    function translate_lang($from_lan, $to_lan, $text=''){
        $text = "This is testing";
        $json = json_decode(file_get_contents('https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' . urlencode($text) . '&langpair=' . $from_lan . '|' . $to_lan));
        print_r($json);
            $translated_text = $json->responseData->translatedText;

            echo $translated_text;
    }
    public function get_cust_ord()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $lang_id = $post->lang_id;
        $user_logged = $this->do_auth($post);


        
        $my_corders = $this->db->where("id",$post->order_id)->get("c_orders")->result_object();


        foreach($my_corders as $key=>$order)
        {

            $my_corders[$key]->at = date("F d, Y",strtotime($order->created_at));

            if($order->status == 1){
                $status_text = "Pending";
            } if($order->status == 2){
                $status_text="Under Work";
            } if($order->status == 3){
                    $status_text="Under Review";
            } if($order->status == 4){
                $status_text = "Completed";
            } if($order->status == 5){
                $status_text ="Cancelled";
            }
            $my_corders[$key]->show_payment=0;
            $my_corders[$key]->pay_now=0;

            if($order->status>2)
            {

                if($order->payment_done_part_2 == 0){
                    $payment_text = "2nd Payment Required";
                     $my_corders[$key]->pay_now=1;
                     $my_corders[$key]->show_payment=1;
                    if($order->production_status>1){
                       $my_corders[$key]->pay_now=0;
                     $my_corders[$key]->show_payment=0;
                    }
                }
                else
                {
                    $payment_text = "All Payment Clear";
                }
            }
            else
            {
                if($order->payment_done_part_1 == 0){
                    $payment_text = "Down Payment Required";
                    $my_corders[$key]->pay_now=1;
                }
                else
                {
                    $payment_text = "Down Payment Clear";
                    // $my_corders[$key]->pay_now=0;
                    // $my_corders[$key]->show_payment=0;
                    $my_corders[$key]->pay_now=0;
                }
            }

            if($order->admin_status == 4){
                if($order->payment_done_part_1 == 0){
                    $my_corders[$key]->show_payment=1;
                }
            }
            if($order->admin_status == 6){
                 if($order->payment_done_part_2 == 0){
                    $my_corders[$key]->show_payment=1;
                    $my_corders[$key]->pay_now=1;
                }else {
                    $my_corders[$key]->show_payment=0;
                    $my_corders[$key]->pay_now=0;
                }
            }



            $my_corders[$key]->status_text = $status_text;
            $my_corders[$key]->payment_text = $payment_text;
            $ext = pathinfo($order->file_name, PATHINFO_EXTENSION);
            $file_source_custom_url  = base_url()."resources/uploads/orders/".$order->file_name;
            if($ext == "jpg" || $ext == "png" || $ext == "jpg") {
                $file_source_custom  = $file_source_custom_url;
            }else {
                $file_source_custom  = base_url()."resources/uploads/orders/PDF_file_icon.svg";
            }   
            $my_corders[$key]->file_src = $file_source_custom;
            $my_corders[$key]->file_url = $file_source_custom_url;
            

            $this_cat = $this->db->where('id',$order->cat_id)
                    ->get('categories')
                    ->result_object()[0];
            $Date1 = $order->created_at; 
            $days = $this_cat->delivery." ".$this_cat->delivery_type;
            $Date2 = date('F, d Y', strtotime($Date1 . " + ".$days));
            $my_corders[$key]->deliver_date = $Date2;


            $sub_cat = $this->db->where("id",$order->cat_id)->get("categories")->result_object()[0];

            $cat = $this->db->where("id",$sub_cat->parent)->get("categories")->result_object()[0];

            $my_corders[$key]->sub_cat = $sub_cat;
            $my_corders[$key]->cat = $cat;
            $my_corders[$key]->options = json_decode($order->options);


            $my_corders[$key]->price_text = with_currency_bilal(number_format($order->price,2),$lang_id);
            $my_corders[$key]->stamps_price_text = with_currency_bilal(number_format($order->stamps_cost,2),$lang_id);

            $my_corders[$key]->logo_cost_text = with_currency_bilal(number_format($order->logo_cost,2),$lang_id);

            $my_corders[$key]->vat_text = with_currency_bilal(number_format($order->vat,2),$lang_id);
            $my_corders[$key]->shipping_text = with_currency_bilal(number_format($order->shipping,2),$lang_id);
            $my_corders[$key]->down_text = with_currency_bilal(number_format($order->down_payment,2),$lang_id);
            // $my_corders[$key]->payment_text = with_currency_bilal($order->total);
            $my_corders[$key]->all_total_text = with_currency_bilal(number_format($order->all_total,2),$lang_id);


            $my_corders[$key]->total_arrived_text = with_currency_bilal(number_format($order->total_arrived,2),$lang_id);
            $my_corders[$key]->total_pending_text = with_currency_bilal(number_format($order->all_total - $order->total_arrived,2),$lang_id);

           
        }

        $adrss = $this->db->where("user_id",$user_logged->id)->order_by("is_default","ASC")->get("shipping_addresses")->result_object();





        $paypal=$this->db->where("type",1)->get("payment_methods")->result_object();

        $paypal_key = $paypal[0]->paypal_api;




        $amount = (int) $my_corders[0]->down_payment;



        $amount = ($amount*100);

        $shaString  = '';
        // array request
        $arrData    = array(
            'command'            =>'PURCHASE',
            'access_code'        =>'Sx6NVacbc8BdqsHkT4n5',
            'merchant_identifier'=>'ZYGPBMlV',
            'merchant_reference' =>$my_corders[0]->payfort_id,
            'amount'             =>$amount,
            //'currency'           =>get_currency(),
            'currency'           =>'SAR',
            'language'           =>'en',
            'customer_email'     =>$user_logged->email,
            "return_url"=>base_url()."api/complete_payfort_c_order"
        );
        // sort an array by key
        ksort($arrData);
        foreach ($arrData as $key => $value) {
            $shaString .= "$key=$value";
        }
        // make sure to fill your sha request pass phrase
        $shaString = '13PdQlvh8kZIPro/nI9dXp{$'.$shaString.'13PdQlvh8kZIPro/nI9dXp{$';
        $signature = hash("sha256", $shaString);


        


        $threads = $this->db->where("task_id",$my_corders[0]->id)->where("only_admin",0)->order_by("created_at","DESC")->get("corder_threads")->result_object();
        // foreach ($threads as $key => $value) {
        //     if($lang_id != 1){
        //         $en_title = array("ar_title",$this->translate_lang(en,ar,$value->desc)); 
        //         array_push($threads[$key],$en_title);
        //     }
        // }
        
        $bank=$this->db->where("id",5)->get("payment_methods")->result_object()[0];

        $address=$this->db->where("user_id",$user_logged->id)->get("shipping_addresses")->num_rows();
        $show_address_box = 0;
        if($address==0){
            if($my_corders[0]->payment_done_part_2==1 || $my_corders[0]->admin_status > 6){
                $show_address_box = 1;
            }
        }
    
        echo json_encode(array("action"=>"success","data"=>array(
            "order"=>$my_corders[0],
            "threads"=>$threads,
            "status"=>1,
            "show_address"=>$show_address_box,
            "paypal_key"=>$paypal_key,
            "paypal_allowed"=>$paypal_key->status,
            "payfort"=>array(
                "access_code"=>"89473987",
                "signature"=>$signature,
                "vals"=>$arrData
            ),
            "bank"=>array(
                "iban"=>$bank->iban,
                "name_bank"=>$bank->bank_name,
                "company"=>$bank->company_name,
                "iban_2"=>$bank->iban_2,
                "bank_name_2"=>$bank->bank_name_2,
                "iban_3"=>$bank->iban_3,
                "bank_name_3"=>$bank->bank_name_3,
                //"description"=>$bank->description
            )
        )));
    }

    

    public function update_profile()
    {
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


        $user_logged = $this->do_auth($post);


        $this->db->where("id",$user_logged->id)->update("users",array(
            "first_name"=>$post->firstname,
            "last_name"=>$post->lastname,
            "phone"=>$post->phone,
            "code"=>$post->code
        ));


        


        $this->print_user_data($user_logged->id);
    }


    public function get_cust_cats()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;
        $lang_id = $post->lang_id;

        $this->region_id = $post->region_id;

        //$user_logged = $this->do_auth($post);

        // $this->guest_id = $user_logged->id;

        if (!$lang_id) $lang_id = dlang()->id;


        $clang = $this->db->where("id", $lang_id)->get("languages")->result_object()[0];

        $slug = $post->slug;

        $cat_id = explode('-', $slug)[count(explode('-', $slug)) - 1];

        $c_cats = $this->db->where("is_deleted", 0)->where("status", 1)->get("categories")->result_object();



        $c_cats = $this->enrich_categories($c_cats, $lang_id);

        $visited = array();
        $final = array();

        foreach($c_cats as $key=>$c_cat)
        {

            

            $c_cat->currency = get_currency();
            //$images_more = $this->db->where("category_id",$c_cat->id)->get("category_images")->result_object();
            //$c_cat->more_images = $images_more;
            if(empty($c_cat->subs) || in_array($c_cat->id,$visited)) continue;

            $subs_final = array();
            $all_img_cusm = array();
            foreach($c_cat->subs as $sub_key=>$sub_cat)
            {
                $sub_cat = (Object) $sub_cat;

                if($sub_cat->cust!=1) continue;
                $options = $this->db->where("category",$sub_cat->id)->get("cat_options")->result_object();
                
                $variations = $this->db->where("category",$sub_cat->id)->get("v_variations")->result_object();
                //$all_img_cusm = array();

                
                foreach ($options as $opt_key=>$option)
                {
                    $opppp = json_decode($option->options);

                    foreach ($opppp as $kkk=>$opp)
                    {
                        $opppp[$kkk]->selected=$kkk==0?1:0;
                    }
                    $options[$opt_key]->options = $opppp;
                }
                $sub_cat->options = $options;
                $sub_cat->currency = get_currency();

                $sub_cat->terms_en_custom = $sub_cat->terms_en;
                $sub_cat->terms_ar_custom = "<body><html><style>
                        @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
                        body {font-family:'Cairo'}
                        </style>".$sub_cat->terms_ar."</html></body>";

                if($sub_cat->id==$cat_id)
                {
                    $sub_cat->selected = 1;
                    $c_cat->selected = 1;
                }
               

                if(empty($variations)) continue;

                foreach($variations as $vkey=>$variation)
                {
                    // VARIATION MORE IMAGES START
                    $all_img_cusm = array();
                    $images_more = $this->db->where("category_id",$sub_cat->id)->where("v_id",$variation->id)->get("category_images")->result_object();
                    if(!empty($images_more)){
                        $all_img_cusm[] = $variation->cust_image;
                        foreach ($images_more as $key => $image_cc) {
                            $all_img_cusm[] .= $image_cc->image;
                        }
                         //echo $variation->cust_image;
                        // $var_img = $variation->cust_image;
                        
                        // unset($all_img_cusm[count($all_img_cusm)-1]);
                        $sub_cat_more_images = $all_img_cusm;
                    } else{
                        $sub_cat_more_images = array();
                    }

                    $variations[$vkey]->more_images = $sub_cat_more_images;

                    // VARIATION MORE IMAGES END

                    $tables = $this->db->where("variation_id",$variation->id)->get("v_table")->result_object();

                    foreach($tables as $table_key=>$table)
                    {

                        $rows = $this->db->where("table_id",$table->id)->get("v_rows")->result_object();
                        if(empty($rows))
                        {
                            unset($tables[$key]);
                            continue;
                        }
                        else
                        {
                            foreach($rows as $row_key=>$row)
                            {
                                $prints = json_decode($row->prints);

                                $faces = array();
                                if($lang_id == 1){
                                    $title_norm = "بدون طباعة";
                                }
                                else{
                                    $title_norm = "Plain Price";
                                }
                                $faces[] = array(
                                            "title"=>"--".$title_norm." --",
                                            "price"=>$row->plain_price,
                                            "face_index"=>-2,
                                            "print_index"=>-1
                                        );

                                foreach($prints as $key_print=>$print)
                                {
                                    foreach($print as $key_face=>$face)
                                    {
                                        if($key_face == 0){
                                        if($lang_id==1){
                                            if($table->table_print_name_ar!=""){
                                                $name_prin = "لون";
                                            } else{
                                                $name_prin = "لون";
                                            }
                                        } else {
                                            if($table->table_print_name_en!=""){
                                                $name_prin = ($key_print+1)==1?"Color":"Colors";
                                                
                                            } else{
                                                $name_prin = ($key_print+1)==1?"Color":"Colors";
                                            }
                                        }
                                      
                                            $ptitle = ($key_print+1) ." ".$name_prin;
                                            $faces[] = array(
                                                "title"=>$ptitle,
                                                "price"=>$face,
                                                "face_index"=>$key_face,
                                                "print_index"=>$key_print
                                            );
                                        }
                                    }
                                    
                                }
                                $id = $row->id;
                                $rows[$row_key]->faces = $faces;
                            }
                            $tables[$table_key]->rows = $rows;
                        }
                       //$tables[$table_key]->table_print_name_en = $table->table_print_name_en;
                        $variations[$vkey]->eng_title_faces = $table->table_print_name_en;
                        $variations[$vkey]->ara_title_faces = $table->table_print_name_ar;

                        $variations[$vkey]->c_descps_en = $variation->c_descps_en;
                        $variations[$vkey]->c_descps_ar = "<body><html><style>
                        @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
                        body {font-family:'Cairo'}
                        </style>".$variation->c_descps_ar."</html></body>";

                        $variations[$vkey]->size_en_title = $table->size_eng;
                        $variations[$vkey]->size_ar_title = $table->size_ar;


                        $variations[$vkey]->meta_title_en = $variation->meta_title_en;
                        $variations[$vkey]->meta_descps_en = $variation->meta_descps_en;
                        $variations[$vkey]->meta_keys_en = $variation->meta_keys_en;
                        $variations[$vkey]->meta_title_ar = $variation->meta_title_ar;
                        $variations[$vkey]->meta_descps_ar = $variation->meta_descps_ar;
                        $variations[$vkey]->meta_keys_ar = $variation->meta_keys_ar;
                    }

                    $variations[$vkey]->tables = $tables;



                    $options = $this->db->where("variation_id",$variation->id)->where("category",$sub_cat->id)->get("cat_options")->result_object();
                    foreach ($options as $opt_key=>$option)
                    {
                        $opppp = json_decode($option->options);

                        foreach ($opppp as $kkk=>$opp)
                        {
                            $opppp[$kkk]->selected=$kkk==0?1:0;
                        }
                        $options[$opt_key]->options = $opppp;

                    }

                    $variations[$vkey]->options = $options;


                }
                $sub_cat->variations=$variations;
                
                $subs_final[] = $sub_cat;
            }

            if(empty($subs_final)) continue;
            $c_cat->subs = $subs_final;
            // $c_cat->
            $final[] = $c_cat;

            $visited[] = $c_cat->id;
        }


        echo json_encode((array(
            "action"=>"success",
            "data"=>array(
                "categories"=>$final
            )
        )));
    }
    public function update_order_junks()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;

        $user_logged = $this->do_auth($post);

        $junk_login_for = $post->order_id;

        $order = $this->db->where("login_junk",1)->where("login_junk_for",$junk_login_for)->where("user_id",-1)->get("c_orders")->result_object()[0];

        //echo $this->db->last_query();exit;

        if(!$order)
        {
            echo json_encode(array("action"=>"failed"));
            return;

        }
        else
        {
            $this->db->where("id",$order->id)->update("c_orders",array(
                "user_id"=>$user_logged->id,
                "region_id"=>$user_logged->region_id,
                "login_junk"=>0,
                "junk"=>0,

                "login_junk_for"=>""
            ));
            //SEND EMAIL
                $id = $order->id;

                $order_id = $id;
                $subject = "Custom Order Received #";
                $view_ar = "custom_order_received_ar";
                $view_en = "custom_order_received_english";
                $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
            //SEND SMS
                $phone = "+".$user_logged->code.$user_logged->phone;
                if($order->lang_id==1){
                    $sms_desc = "طلبك المخصص # ".$id." تم التسجيل بنجاح ، وسوف يقوم SouqPack بمعالجة طلبك قريبًا
        ";
                }else{
                    $sms_desc = "Your Custom Order #".$id." has been placed successfully, SouqPack will process your order soon";
                }
                $this->send_sms_to_users($phone,$sms_desc);

            echo json_encode(array("action"=>"success","id"=>$order->id));
        }

    }
     public function update_order_junks_logic_2()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;

        $user_logged = $this->do_auth($post);

        $junk_login_for = $post->order_id;

        $order = $this->db->where("login_junk",1)->where("id",$junk_login_for)->where("user_id",-1)->get("c_orders")->result_object()[0];


        if(!$order)
        {
            echo json_encode(array("action"=>"failed"));
            return;

        }
        else
        {
            $this->db->where("id",$order->id)->update("c_orders",array(
                "user_id"=>$user_logged->id,
                "region_id"=>$user_logged->region_id,
                "login_junk"=>0,
                "junk"=>0,
                "login_junk_for"=>""
            ));
            echo json_encode(array("action"=>"success","id"=>$order->id));
        }

    }
    public function custom_order()
    {
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;

        if($post->junk_login!=1)
        {
            $user_logged = $this->do_auth($post);
            $this->guest_id = $user_logged->id;
            $guest_id = $user_logged->id;
            $region_id = $user_logged->region_id;
        }
        else
        {
            $user_logged = (Object) array();
            if($post->user_id != -1){
                $user_logged->id = $post->user_id;
            }else {
                $user_logged->id = -1;
            }
            $this->guest_id = $user_logged->id;
            $guest_id = $user_logged->id;
            $user_logged->region_id = 0;
            $region_id = $user_logged->region_id;
        }

        $sub_cat = $this->db->where("id",$post->sub_cat_id)->get("categories")->result_object()[0];

        if($sub_cat->lparent==0)
        {

        }
        else
        {
            $sub_cat = $this->db->where("id",$sub_cat->lparent)->get("categories")->result_object()[0];
        }

        $lang_id = $post->lang_id;
        if($lang_id!="" || $lang_id != 0){
            $lang_id = $lang_id;
        } else{
            $lang_id = 2;
        }
        $order = array(
            // general
            "user_id"=>$user_logged->id,
            "region_id"=>$region_id,
            "cat_id"=>$sub_cat->id,

            "c_title"=>$post->c_title,
            "c_title_ar"=>$post->c_title_ar,

            "login_junk"=>$post->junk_login==1?1:0,
            "login_junk_for"=>$post->junk_login_for,

            // logo
            "logo_type"=>$post->logo->logo_type,
            "logo_colors"=>$post->logo->logo_file->selected==1?$post->logo->logo_file->logo_colors:$post->logo->logo_create->logo_colors,
            "file_name"=>$post->logo->logo_file->file_name,
            // "logo_print"=>$post->logo->logo_file->logo_print,
            // "logo_stamps"=>$post->logo->logo_file->logo_stamps,
            "logo_name"=>$post->logo->logo_create->logo_name,
            // "logo_name_2"=>$post->logo->logo_create->logo_name_2,
            "logo_desc"=>$post->logo->logo_create->logo_desc,

            // color
            "color_type"=>$post->color->choose_color->selected==1?1:2,
            "color"=>$post->color->choose_color->selected==1?$post->color->choose_color->color:$post->color->create_color->hex_color,
            "color_c"=>$post->color->create_color->color->c,
            "color_m"=>$post->color->create_color->color->m,
            "color_y"=>$post->color->create_color->color->y,
            "color_k"=>$post->color->create_color->color->k,

            "qty"=>$post->statics->qty,

            // statics
            // "faces_val"=>$post->statics->faces_val,
            // "colors_val"=>$post->statics->colors_val,
            // "base_val"=>$post->statics->base_val,
            // "sides_val"=>$post->statics->sides_val,
            // "height_val"=>$post->statics->height_val,
            // "width_val"=>$post->statics->width_val,

            "whg"=>$post->db_logic->selected_table->rows[$post->db_logic->row_index]->whg,
            "print_face_price"=>$post->db_logic->selected_table->rows[$post->db_logic->row_index]->faces[$post->db_logic->face_index]->price,
            "print_face_title"=>$post->db_logic->selected_table->rows[$post->db_logic->row_index]->faces[$post->db_logic->face_index]->title,

            // notes & options
            "notes"=>$post->notes,
            "options"=>json_encode($post->options),

            // "table_json"=>json_encode($post->table_json),
            "final_form"=>json_encode($post),

            // finance
            "price"=>$post->total->price,
            "stamps_cost"=>$post->total->stamps_price,
            "logo_cost"=>$post->total->logo,
            "down_payment"=>(int)$post->total->down,
            "shipping"=>$post->total->shipping,
            "vat"=>$post->total->vat,
            "all_total"=>(int)$post->total->sub_total,
            "total"=>(int)$post->total->down,
            "delivery_type"=>$sub_cat->delivery_type,
            "delivery"=>$sub_cat->delivery,

            // down payment
            "payment_method_part_1"=>0,
            "payment_done_part_1"=>0,
            "payment_arrived_part_1"=>0,

            // part 2 payment
            "payment_method_part_2"=>0,
            "payment_done_part_2"=>0,
            "payment_arrived_part_2"=>0,
            "total_arrived"=>0,

            // general
            "status"=>1,
            "created_at"=>date("Y-m-d H:i:s"),
            "junk"=>$post->junk_login,
            "payfort_id"=>time(),
            "lang_id"=>$lang_id,
            "from_web_mobile"=>$post->from_web?$post->from_web:2,
        );

        $this->db->insert("c_orders",$order);
        $id = $this->db->insert_id();


        //SEND CUSTON ORDER EMAIL RECEIVED
        $order_id = $id;
        $subject = "Custom Order Received #";
        $view_ar = "custom_order_received_ar";
        $view_en = "custom_order_received_english";
        $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        //SEND CUSTON ORDER EMAIL RECEIVED
       

        if(settings()->site_title_ar != ""){
            $site_title = settings()->site_title_ar;
        }else {
            $site_title = settings()->site_title;
        }
        $thread = array(
            "by"=>$user_logged->first_name!=""?$user_logged->first_name." ".$user_logged->last_name:"Customer",
            "task_id"=>$id,
            "by_id"=>$user_logged->id,
            "title"=>"New Custom Order",
            "desc"=>"Custom Order (#".$id.") has been received, ".settings()->site_title." will process this order soon",
            "title_ar"=> "طلب مخصص جديد",
            "desc_ar" => "تم استلام الطلب المخصص (# ".$id.") ، ".settings()->site_title." سيعالج هذا الطلب قريبًا",
            "created_at"=>date("Y-m-d H:i:s")
        );

        $this->db->insert("corder_threads",$thread);

        // SEND SMS
        


        $phone = "+".$user_logged->code.$user_logged->phone;
        if($lang_id==1){
            $sms_desc = "طلبك المخصص # ".$id." تم التسجيل بنجاح ، وسوف يقوم SouqPack بمعالجة طلبك قريبًا
";
        }else{
            $sms_desc = "Your Custom Order #".$id." has been placed successfully, SouqPack will process your order soon";
        }
        $this->send_sms_to_users($phone,$sms_desc);

        $paypal=$this->db->where("type",1)->get("payment_methods")->result_object();

        $paypal_key = $paypal[0]->paypal_api;




        $amount = (int) $post->total->down;


        $amount = ($amount*100);

        $shaString  = '';
        // array request
        $arrData    = array(
            'command'            =>'PURCHASE',
            'access_code'        =>'Sx6NVacbc8BdqsHkT4n5',
            'merchant_identifier'=>'ZYGPBMlV',
            'merchant_reference' =>$id,
            'amount'             =>$amount,
            //'currency'           =>get_currency(),
            'currency'           =>'SAR',
            'language'           =>'en',
            'customer_email'     =>$user_logged->email?$user_logged->email:"noone@gmail.com",
            "return_url"=>base_url()."api/complete_payfort_c_order"
        );
        // sort an array by key
        ksort($arrData);
        foreach ($arrData as $key => $value) {
            $shaString .= "$key=$value";
        }
        // make sure to fill your sha request pass phrase
        $shaString = '13PdQlvh8kZIPro/nI9dXp{$' . $shaString . '13PdQlvh8kZIPro/nI9dXp{$';
        $signature = hash("sha256", $shaString);


        $thread = array(
            "by"=>"Customer",
            "order_id"=>$id,
            "by_id"=>$user_logged->id,
            "desc"=> "Customer has placed a new custom order #".$id,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

          
        $this->db->insert("notifications",$thread);

        echo json_encode(array(
            "action"=>"success",
            "order_id"=>$id,
            "status"=>1,
            "paypal_key"=>$paypal_key,
            "paypal_allowed"=>$paypal_key->status,
            "payfort"=>array(
                "access_code"=>"89473987",
                "signature"=>$signature,
                "vals"=>$arrData
            )
        ));
    }

public function email_custom_sent_souqpack($order_id,$subject_title,$ar_view, $en_view,$attachmt=""){
        $order_details = $this->db->query("SELECT * FROM c_orders WHERE id = '".$order_id."'")->result_object()[0];
        $user_details = $this->db->query("SELECT * FROM users WHERE id = '".$order_details->user_id."'")->result_object();
        if(!empty($user_details) || $user_details[0]->email != ""){

            $email_user = $user_details[0]->email;
            // SEND EMAIL IF SUCCCESS
            
            $fromemail=settings()->email;
            $toemail = $email_user;
            //$subject = "Order Received #00".$order_details->id;
            $subject = $subject_title.$order_details->id;
            if($order_details->from_web_mobile == "1"){
                $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
            } else {
                 if($user_details[0]->first_name!=""){
                    $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
                }else {
                    $user_name = $user_details[0]->phone;
                }
            }
            $order_date = date("F, d Y", strtotime($order_details->created_at));
            $order_id_email = "#".$order_details->id;
            $order_normal = $order_details->id;
            if($order_details->lang_id == 1){
                $currency = "ر.س";
            }else{
                $currency = "SAR";
                }
            $total_amount = $order_details->all_total." ".$currency;
            $down_pay = $order_details->down_payment." ".$currency;
            $shipping_cust_json = json_decode($order_details->address_text);

            $ship_add_det = $this->db->query("SELECT * FROM shipping_addresses WHERE user_id = ".$order_details->user_id)->result_object()[0];
            if(empty($ship_add_det)){

                $ship_address = "No Address Added<br> <a href='https://souqpack.com/profile' target='_blank'>Add Your Address</a>";
                $shipping_cust_name = $user_name;
            }else{
                $ship_address = $ship_add_det->street.", ".$ship_add_det->city;
                $shipping_cust_name = $ship_add_det->first_name." ".$ship_add_det->last_name;
            }

            
            
            $shiping_cost = $order_details->shipping_fee." ".$order_details->currency;

            $this->data["products_order"] = $this->db->query("SELECT * FROM order_products WHERE `order_id` = '".$order_details->id."'")->result_object();
            if($order_details->lang_id == 1){
                $mesg = $this->load->view('frontend/emails/'.$ar_view,$this->data,true);
            }else {
                $mesg = $this->load->view('frontend/emails/'.$en_view,$this->data,true);
            }
            if($order_details->status > 4){
                $refund = $this->db->query("SELECT * FROM refund WHERE pID = ".$order_id)->result_object()[0];    
                if(!empty($refund)){
                    $refund_number = "#".$refund->id;
                    $contatc_num = settings()->mobile;
                    $contact_email = "help@souqpack.com";
                }
            }

            // CUSTOM ORDER DETAILS:
            $this_cat = $this->db->where('id',$order_details->cat_id)
            ->get('categories')
            ->result_object()[0];

            $p_cat = $this->db->where('id',$this_cat->parent)
            ->get('categories')
            ->result_object()[0];

            $customer = $this->db->where('id',$order_details->user_id)
            ->get('users')
            ->result_object()[0];

            $item_name = $order_details->lang_id==1?"اسم العنصر":"Item Name";
            $qty = $order_details->lang_id==1?"الكمية":"Qty";
            $print = $order_details->lang_id==1?"أوجه الطباعة":"Prints";
            $size = $order_details->lang_id==1?"الحجم":"Size";
            $color = $order_details->lang_id==1?"اللون":"Color";
            $create_logo = $order_details->lang_id==1?"إنشاء شعار جديد":"Create New Logo";
            $logo = $order_details->lang_id==1?"الشعار":"Logo";
            $cat = $order_details->lang_id==1?"الفئة":"Category";

            $custom_details = '

            <table class="table" style="width: 100%;margin-left: -1px;">
                                    <tbody>
                                        <tr>
                                            <th>'.$item_name.'</th>
                                            <td> '.$order_details->c_title.'</td>
                                        </tr>
                                        <tr>
                                            <th>'.$cat.'</th>
                                            <td>  '.$p_cat->title .' / '.$this_cat->title.'</td>
                                        </tr>

                                        <tr>
                                            <th width="390">'.$qty.'</th>
                                            <td> '.$order_details->qty.'</td>
                                        </tr>

                                        <tr>
                                            <th width="390">'.$size.'</th>
                                            <td> '.$order_details->whg.'</td>
                                        </tr>


                                        <tr>
                                            <th width="390">'.$print.'</th>
                                            <td> '.$order_details->print_face_title.'</td>
                                        </tr>
                                        <tr>
                                            <th width="390">'.$color.'</th>
                                            <td> '.$order_details->color.'</td>
                                        </tr>

                                        ';
                                        if($order_details->logo_type == 2){
                                        $custom_details .='<tr>
                                            <th width="390">'.$create_logo.'</th>
                                            <td> '.$order_details->logo_name.'<br>
                                                    '.$order_details->logo_desc.'</td>
                                        </tr>';
                                        } else if($order_details->logo_type == 1){
                                            $custom_details .='<tr>
                                            <th width="390">'.$logo.'</th>
                                            <td> <img src="'.base_url().'/resources/uploads/orders/'.$order->file_name.'" width="30px" /></td>
                                        </tr>';
                                        }
                                        $options = json_decode($order_details->options);
                                        foreach($options as $option)
                                        {
                                         $custom_details .= '<tr>
                                                <th>'.$option->title.'</th>
                                                <td>'.$option->value.'</td>
                                            </tr>';
                                        }
                                   $custom_details .=  '</tbody>
                                </table>';
             
                $price = $order_details->lang_id==1?"السعر":"Price";
                $loog_cost = $order_details->lang_id==1?"تكلفة التصميم":"Logo Cost";
                $Shipping = $order_details->lang_id==1?"الشحن":"Shipping";
                $downp = $order_details->lang_id==1?"الدفعة الأولى":"Down Payment";
                $t_p_ar = $order_details->lang_id==1?"وصل إجمالي السداد":"Total Payment Arrived";
                $t_p_pe = $order_details->lang_id==1?"إجمالي الدفعة المعلقة":"Total Payment Pending";
                // $t = $order_details->lang_id==1?"":"";

                $payment_dd = '<table class="table_ar">
                                    <tbody>
                                        <tr>
                                            <th>'.$price.'</th>
                                            <td>'.with_currency(number_format($order_details->price,2)).'</td>
                                        </tr>
                                        <tr>
                                            <th>'.$loog_cost.'</th>
                                            <td>'.with_currency(number_format($order_details->logo_cost,2)).'</td>
                                        </tr>

                                        <tr>
                                            <th>'.$Shipping.'</th>
                                            <td>'.with_currency(number_format($order_details->shipping,2)).'</td>
                                        </tr>

                                        <tr>
                                            <th>'.$downp.'</th>
                                            <td>'.with_currency(number_format($order_details->down_payment,2)).'</td>
                                        </tr>
                                       <tr>
                                            <th>'.$t_p_ar.'</th>
                                            <td style="color:green;">+'.with_currency(number_format($order_details->total_arrived,2)).'</td>
                                        </tr>
                                        <tr>
                                            <th>'.$t_p_pe.'</th>
                                            <td style="color:red;">-'.with_currency(number_format($order_details->all_total-$order_details->total_arrived,2)).'</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>';
            $souq = '<a href="https://souqpack.com" style="color:yellow;">SouqPack</a>';
            $search  = array('[CUSTOMER_NAME]','[ORDER_DATE]','[ORDER_NUMBER]','[ORDER_NUMBER_LINK]','[TOTAL_AMOUNT]','[SHIPPING_CUSTOMER_NAME]','[SHIP_ADDR]','[SUB_TOTAL]','[SHIPPING_COST]','[REFUND_NUMBER]','[CONTACT_NUMBER]','[EMAIL_ADDR_COMPANY]','[USER_EMAIL]','[COPYRIGHT]','[FB]','[TW]','[IN]','[YT]','[SC]','[AMOUNT_TO_PAY]',"SouqPack","Souqpack","[CUSTOM_DETAILS]","[PAYEMNT_INFOR]");
            $replace = array($user_name, $order_date, $order_id_email, $order_normal, $total_amount, $shipping_cust_name,$ship_address,$total_amount,$shiping_cost,$refund_number,$contatc_num,$contact_email,$user_details[0]->email,settings()->copy_right,settings()->facebook,settings()->twitter,settings()->instagram,settings()->youtube,settings()->snapchat,$down_pay,$souq,$souq,$custom_details,$payment_dd);

          $new_html = str_replace($search,$replace,$mesg);

            $config=array(
            'charset'=>'UTF-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );


            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            // die;
            //$this->email->initialize($config);

            $this->email->to($toemail);
            $this->email->from($fromemail, 'SouqPack');
            $this->email->subject($subject);
            if($attachmt!=""){
                $this->email->attach($attachmt);
            }
            $this->email->message($new_html);
            $this->email->send();
        }
    }
    public function download($id)
    {
        $thread = $this->db->where("id",$id)->get("corder_threads")->result_object()[0];

        $this->load->helper('download');
        $data = file_get_contents(base_url()."resources/uploads/orders/".$thread->file);
        force_download($thread->filename, $data);
    }
    public function reject_delivery()
    {

        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;

        $user_logged = $this->do_auth($post);


        $order_id = $post->order_id;
        $reason = $post->reason;

        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
        
        if(!$order)
        {
            echo json_encode(array("action"=>"failed","error"=>"Invalid request"));
            return;
        }

        $this->db->where("id",$order->id)->update("c_orders",array(
            "status"=>6, // revision
        ));


        // creating a thread
        $thread = array(
            "by"=>$user_logged->first_name!=""?$user_logged->first_name. " " .$user_logged->last_name:"Customer",
            "task_id"=>$order_id,
            "title"=>"Rejected the delivery",
            "by_id"=>$user_logged->id,
            "desc"=>"Reason: ".$reason,
            "status"=>2,
            "title_ar"=>"رفض التسليم",
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );


        $this->db->insert("corder_threads",$thread);


        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>$user_logged->id,
            "desc"=> "Customer has rejected designs for order #00CP".$order_id,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

          
        $this->db->insert("notifications",$thread);
        //SEND NOTIF HERE
        echo json_encode(array("action"=>"success"));
        return;
    }

    public function accept_delivery()
    {

        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;

        $user_logged = $this->do_auth($post);


        $order_id = $post->order_id;

        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
        
        if(!$order)
        {
            echo json_encode(array("action"=>"failed","error"=>"Invalid request"));
            return;
        }

        


        $this->db->where("id",$order->id)->update("c_orders",array(
            "admin_status"=>4, // accepted/ waiting down payment
            "status"=>4, // Pending admin approval,
            "show_payment"=>1
        ));


        // creating a thread
        $thread = array(
            "by"=>$user_logged->first_name!=""?$user_logged->first_name. " " .$user_logged->last_name:"Customer",
            "task_id"=>$order_id,
            "title"=>"Accepted the delivery",
            "for_admin"=>1,
            "status"=>1,
            "by_id"=>$user_logged->id,
            "desc"=>"delivery has been accepted, waiting for down payment from customer",
            "cdesc"=>"you have accepted the delivery, please pay the down payment",
            "title_ar"=>"قبلت التصميم",
            "desc_ar" => "لقد قبلت التصميم ، يرجى دفع الدفعة الأولى",
            "created_at"=>date("Y-m-d H:i:s")
        );


        $this->db->insert("corder_threads",$thread);

        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>$user_logged->id,
            "desc"=> "Customer has approved designs for order #".$order_id,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );
          
        $this->db->insert("notifications",$thread);

        //SEND CUSTON ORDER EMAIL PENDING PAYMENT
        $order_id = $order_id;
        $subject = "Custom Order Pending Payment #";
        $view_ar = "custom_order_down_payment_ar";
        $view_en = "custom_order_down_payment_english";
        $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);

        //SEND NOTIF HERE
        echo json_encode(array("action"=>"success"));
        return;
    }

    public function check_custom_order_email()
    {
        //SEND CUSTON ORDER EMAIL RECEIVED
        $order_id = 20;
        $subject = "Custom Order Received #";
        $view_ar = "custom_order_received_ar";
        $view_en = "custom_order_received_english";
        $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
    }

    public function upload_bank_data(){
        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;
        $user_logged = $this->do_auth($post);
        $order_id = $post->order_id;

        
        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>$user_logged->id,
            "desc"=> "Customer has made payment (Bank Transfer) against order #".$order_id,
            "for_admin"=>1,
            "is_payment"=>1,
            "is_normal"=>0,
            "created_at"=>date("Y-m-d H:i:s")
        );
          
        $this->db->insert("notifications",$thread);

        // INSERT INTO TABLE
        $thread = array(
            "oID"=>$order_id,
            "uID"=>$user_logged->id,
            "file_name"=> $post->file_name,
            "status"=>0,
            "task_id"=>$order_id,
            "create_at"=>date("Y-m-d")
        );
          
        $this->db->insert("bank_payment_recipts",$thread);


         // creating a thread
        $thread = array(
            "by"=>$user_logged->first_name!=""?$user_logged->first_name. " " .$user_logged->last_name:"Customer",
            "task_id"=>$order_id,
            "title"=>"Bank Transfer Payment",
            "for_admin"=>1,
            "status"=>1,
            "by_id"=>$user_logged->id,
            "desc"=>"made a payemnt by direct bank transfer",
            "cdesc"=>"you have made a payment through direct bank transfer",
            "title_ar"=>"دفع بالتحويل المصرفي",
            "desc_ar" => "قمت بسداد دفعة من خلال التحويل المصرفي المباشر",
            "created_at"=>date("Y-m-d H:i:s"),
            "by_bank"=>1,
            "attach_bank"=>$post->file_name
        );

        // GET ORDER DETAILS
        // $cu_order = $this->db->query("SELECT * FROM c_orders WHERE id = ".$order_id)->result_object()[0];
        // if($cu_order->payment_done_part_1 == 0){
        //     $arr = array(
        //         "payment_done_part_1"=>1,
        //         "payment_method_part_1"=>5,
        //         "payment_arrived_part_1"=>$cu_order->down_payment,
        //         "total_arrived"=>$cu_order->total_arrived+$cu_order->down_payment
        //     );
        // }else{
        //     $arr = array(
        //         "payment_done_part_2"=>1,
        //         "payment_method_part_2"=>5,
        //         "payment_arrived_part_2"=>$cu_order->down_payment,
        //         "total_arrived"=>$cu_order->total_arrived+$cu_order->down_payment
        //     );

        //     $thread['desc'] = "Made remaining payment through direct bank transfer";
        //     $thread['desc_ar'] = "سدد المبلغ المتبقي من خلال التحويل المصرفي المباشر";
        // }
        

        $this->db->insert("corder_threads",$thread);
        $last_id = $this->db->insert_id();

        // $this->db->where("id",$order_id)->update("c_orders",$arr);

        echo json_encode(array("action"=>"success"));
        // print_r($_POST);
    }


    // POST USER COMMENT
     public function post_comment_customer (){

        $post = json_decode(file_get_contents("php://input"));
        if (empty($post))
            $post = (object)$_POST;
        $user_logged = $this->do_auth($post);
        $order_id = $post->order_id;

       

        $dbData = array();
        $dbData['title'] = "updated the timeline";
        $dbData["is_delivery"] = 0;
        $dbData["desc"] = $post->notes;
        
        $dbData["by"] = "Customer";

        $dbData["created_at"] = date("Y-m-d H:i:s");
        $dbData["by_id"]=$user_logged->id;
        
        $dbData["task_id"]=$order_id;

        $task = $this->db->where('id',$dbData["task_id"])->get("c_orders")->result_object()[0];

        $dbData["for_admin"]=1;
        
        $this->db->insert("corder_threads",$dbData);

        
         $notification = "Customer has posted a new comment against order #".$task->order_id;
        $thread = array(
            "by"=>"Customer",
            "order_id"=>$order_id,
            "by_id"=>$user_logged->id,
            "desc"=> $notification,
            "created_at"=>date("Y-m-d H:i:s"),
            "for_admin" => 1
        );
        $this->db->insert("notifications",$thread);
        echo json_encode(array("action"=>"success"));

    }

    public function custom_order_data_retreive(){
        $variable = $this->db->query("SELECT * FROM `tmp_variations` WHERE `category` = 95")->result_object();
        foreach ($variable as $key => $value) {
             $custom_tables = $this->db->query("SELECT * FROM `tmp_table` WHERE `variation_id` = ".$value->id)->result_object();
             foreach ($custom_tables as $key => $table) {
                 $custom_rows = $this->db->query("SELECT * FROM `tmp_rows` WHERE `table_id` = ".$table->id)->result_object();

                 echo "<pre>";
                 print_r($custom_rows);
             }
        }
    }

    public function get_server_info(){
        echo phpinfo();
    }

}
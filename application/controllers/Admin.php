<?php

class Admin extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Admin_Model');
        $this->load->library('pdf');
    }


    public function myHash($pass){
        return  password_hash($pass, PASSWORD_DEFAULT);
    }

    public function index(){

        // $csrf = array(
        //     'name' => $this->security->get_csrf_token_name(),
        //     'hash' => $this->security->get_csrf_hash()
        // );

        if(isset($_SESSION['is_logged_in']) == true){
            $this->load->view('index');
        }
        else{
            redirect('Admin/login');
        }
    }

    public function login(){
        
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('username', 'username', 'trim|required');
            $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');
            if($this->form_validation->run() === false){
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
            else{
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $result = $this->Admin_Model->login($username, $password);
                if ($result) {
                    redirect('admin');
                } else {
                   $this->session->set_flashdata('error','wrong login credentials');
                    redirect('admin/login');
                }
            }
        }
        $this->load->view('login');
    }

    function logout(){
        $this->session->unset_userdata('is_logged_in');
        redirect(base_url() . 'admin/login');
    }
    
    public function recover(){
        $this->load->view('recover');
    }

    public function random(){
        // $digits = 4;
        // return rand(pow(10, $digits-1), pow(10, $digits)-1);
        return rand ( 1000 , 9999 );
    }
    
    public function send_mail($user_email, $rand_num) { 
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = 'ahmedsayyam19@gmail.com';
        $config['smtp_pass'] = 'vonczffdxcreffwj';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html'' => TRUE
        $config['validation'] = true;

        // Email Content Starts
        $emailContent = "<!DOCTYPE>
        <html>
        <head></head>
        <body>
            <div>
                <span style='color:#17a2b8;border-bottom:2px solid #17a2b8;text-transform:capitalize;font-size:28px;'>
                    4 digit code verification for change password 
                </span>
                <p>Hi Admin,</p>
                <p>
                    As you have requested for forgot password, here there is 4 digit code. 
                    <br />
                    Please enter this code and continue to change password procedure.     
                </p>
            </div>
            <div>
                <h3>4 Digit Code: &nbsp; &nbsp;{$rand_num}</h3>
            </div>

        <body>
        <html>";

        // Email Content Ends
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from("ahmedsayyam19@gmail.com");
        $this->email->to($user_email);
        $this->email->subject("Forgot Password of LEO Fitness GYM");
        $this->email->message($emailContent);
        $flag = $this->email->send();
        if($flag){
            return true;
        }else{
            return false;
        }
    }

    public function forgot(){
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('email', 'email', 'trim|required');
            $user_email = $this->input->post('email');
            if($this->form_validation->run() == true){
                $email_check = $this->Admin_Model->check_email($user_email);
                if($email_check){
                    $rand_num = $this->random();
                    $forgot_insert = $this->Admin_Model->update_forgot_code($user_email, $rand_num);
                    if($forgot_insert){
                        if($this->send_mail($user_email, $rand_num)){
                            redirect('admin/verification');
                        }
                        else{
                            $this->session->set_flashdata('mail_not_sent','Mail Sending Failed! Try Again');
                            redirect('admin/forgot');
                        }
                    }
                    else{
                        $this->session->set_flashdata('request_error','Request Error! Try Again');
                        redirect('admin/forgot');
                    }
                }
                else{
                    $this->session->set_flashdata('email_not_found','Email Not Found! Try Again');
                    redirect('admin/forgot');
                }
            }
            else{
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
        }
        else{
            $this->load->view('forgot');
        }

    }

    public function verify(){
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('code', 'code', 'trim|required|is_numeric|min_length[4]|max_length[4]');
            $code = $this->input->post('code');
            if($this->form_validation->run() == true){
                $code_verify = $this->Admin_Model->verify_code($code);
                if($code_verify){
                    redirect('admin/change_password');
                }
                else{
                    $this->session->set_flashdata('code_not_verify',"Code doesn't found! Try Again");
                    $this->load->view('verify');
                }
            }
            else{
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
        }
    }

    public function change_pass(){
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('pass', 'pass', 'trim|required|min_length[8]|max_length[12]');
            $new_pass = $this->input->post('pass');
            $new_hashed_pass = $this->myHash($new_pass);
            if($this->form_validation->run() == true){
                $pass_change = $this->Admin_Model->change_password($new_hashed_pass);
                if($pass_change){
                    // echo "Code Verified";
                    redirect('admin/login');
                    // $this->load->view('login');
                }
                else{
                    $this->load->view('change');
                    // echo "Password updation failed";
                }
            }
            else{
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
        }
    }

    public function verification(){
        $this->load->view('verify');
    }

    public function change_password(){
        $this->load->view('change');
    }

#====================================================================================================
#================================== sabzimandi section start ========================================
#====================================================================================================



            /**
             * 
             * 
             * customer (CREATE,READ,UPDATE) Functionality
             * 
             */
            public function customer($id = null)
            {
                   
                        if ($this->input->method(true) == 'POST') {
                            // $_POST = json_decode(file_get_contents("php://input"), true);

                            if ($this->input->post('id')) {
                                # Update Existing customer Record
                                $update = array();
                                # Set up Validation On Sent Fields


                                if ($this->input->post('name')) {
                                    $this->form_validation->set_rules('name', "name", 'trim|required|max_length[100]');

                                    $update['name'] = $this->input->post('name');
                                }
                                if ($this->input->post('phone')) {
                                    $this->form_validation->set_rules('phone', "phone", 'trim|required|max_length[11]');

                                    $update['phone'] = $this->input->post('phone');
                                }
                                if ($this->input->post('cnic')) {
                                    $this->form_validation->set_rules('cnic', "cnic", 'trim|max_length[13]');

                                    $update['cnic'] = $this->input->post('cnic');
                                }
                                 if ($this->input->post('adress')) {
                                    $this->form_validation->set_rules('adress', "adress", 'trim|max_length[100]');

                                    $update['adress'] = $this->input->post('adress');
                                } 
                                if ($this->input->post('amount')) {
                                    $this->form_validation->set_rules('amount', "amount", 'trim|required|is_numeric');

                                    $update['amount'] = $this->input->post('amount');
                                } 
                                if ($this->input->post('image')) {
                                    $this->form_validation->set_rules('image', "image", 'trim|max_length[100]');

                                    $update['image'] = $this->input->post('image');
                                } 

                                if ($this->form_validation->run()) {
                                    if ($customer=$this->Admin_Model->updatecustomer($this->input->post('id'), $update)) {
                                        # On Success Update
                                        $this->db->where('id', $this->input->post('id'))
                                            ->update('tblcustomer', array('updated_on' => date('Y-m-d H:i:s')));

                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => true,
                                                    'data' => $customer,
                                                    'error' => 'successfully updated'
                                                )
                                            ));
                                    } else {
                                        # Else
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => false,
                                                    'data' => [],
                                                    'error' => 'No changes made'
                                                )
                                            ));
                                    }
                                } else {
                                    return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(400)
                                        ->set_output(json_encode(
                                            array(
                                                'status' => false,
                                                'data' => [],
                                                'error' => $this->form_validation->error_string(" ", " ")
                                            )
                                        ));
                                }
                            } else {
                                # Add
                                # Setting Validation

                                $this->form_validation->set_rules('name', "name", 'trim|required|max_length[100]');
                                $this->form_validation->set_rules('phone', "phone", 'trim|required|max_length[13]');
                                $this->form_validation->set_rules('adress', "adress", 'trim|required|max_length[200]');
                                $this->form_validation->set_rules('amount', "amount", 'trim|required|is_numeric');
                                $this->form_validation->set_rules('cnic', "cnic", 'trim|max_length[13]');
                                $this->form_validation->set_rules('image', "image", 'trim|max_length[200]');
                               


                                if ($this->form_validation->run()) {
                                    $addcustomer = $this->Admin_Model->addcustomer(array(
                                        'name' =>  $this->input->post('name'),
                                        'phone' =>  $this->input->post('phone'),
                                        'adress' => $this->input->post('adress'),
                                        'amount' => $this->input->post('amount'),
                                        'cnic' =>  $this->input->post('cnic'),
                                        'image' => $this->input->post('image'),
                                    ));
                                    if ($addcustomer && $addcustomer > 0) {
                                        # On success addition
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => true,
                                                    'data' => $addcustomer,
                                                    'error' => 'successfully added ',
                                                    
                                                )
                                            ));
                                    } else {
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(400)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => false,
                                                    'data' => [],
                                                    'error' => 'customer already register '
                                                )
                                            ));
                                    }
                                } else {
                                    return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(400)
                                        ->set_output(json_encode(
                                            array(
                                                'status' => false,
                                                'data' => [],
                                                'error' => $this->form_validation->error_string(" ", " ")
                                            )
                                        ));
                                }
                            }
                        }
                        # Fetch Records
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(
                                array(
                                    'status' => true,
                                    'data' => $this->Admin_Model->getcustomer($id),
                                    'error' => ''
                                )
                            ));
                    
                
            }

             # repeat customer data create 

            // function addcustomer()
            // { 
            //      if ($this->input->method(true) == 'POST') {
            // $id =  $this->input->post('id');
            // $amount   =  $this->input->post('amount');

            // return $this->output
            // ->set_content_type('application/json')
            // ->set_status_header(200)
            // ->set_output(json_encode(
            // array(
            // 'status' => true,
            // 'data' =>$this->Admin_Model->addcustomers($id,$amount),
            // 'error'=>'successfully added ',
            // )
            // ));
            
                    
            // }
            // }

            /**
             * 
             * 
             * customer_reports (READ) Functionality
             * 
             **/

              # customer_reports report (D,W,M)
              function customer_reports()
              { 
                    if ($this->input->method(true) == 'POST') {
                //   $_POST = json_decode(file_get_contents("php://input"), true);
                // $from =  $this->input->post('from');
                // $to   =  $this->input->post('to');
                $id   =  $this->input->post('id');

                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                array(
                'status' => true,
                'data' =>$this->Admin_Model->customer_reports($id),
                'error'=>'successfully ',
                )
                ));
                
                        
                }
             }

             function saleDetail($id=null){

                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                array(
                'status' => true,
                'data' =>$this->Admin_Model->saleDetail($id),
                'error'=>'successfully ',
                )
                ));
             }

#==========================================================================================
//---------------------------------- Products create read function ------------------------
#==========================================================================================

            /**
             * 
             * 
             * Products (CREATE,READ,UPDATE) Functionality
             * 
             */
            public function products($id = null)
            {
                   
                        if ($this->input->method(true) == 'POST') {

                            if ($this->input->post('id')) {
                                # Update Existing products Record
                                $update = array();
                                # Set up Validation On Sent Fields


                                if ($this->input->post('label')) {
                                    $this->form_validation->set_rules('label', "label", 'trim|required|max_length[100]');

                                    $update['label'] = $this->input->post('label');
                                }
                              

                                if ($this->form_validation->run()) {
                                    if ($products=$this->Admin_Model->updateproducts($this->input->post('id'), $update)) {
                                        # On Success Update
                                        $this->db->where('id', $this->input->post('id'))
                                            ->update('tblproducts', array('updated_on' => date('Y-m-d')));

                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => true,
                                                    'data' => $products,
                                                    'error' => 'successfully updated'
                                                )
                                            ));
                                    } else {
                                        # Else
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => false,
                                                    'data' => [],
                                                    'error' => 'No changes made'
                                                )
                                            ));
                                    }
                                } else {
                                    return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(400)
                                        ->set_output(json_encode(
                                            array(
                                                'status' => false,
                                                'data' => [],
                                                'error' => $this->form_validation->error_string(" ", " ")
                                            )
                                        ));
                                }
                            } else {
                                # Add
                                # Setting Validation

                                $this->form_validation->set_rules('label', "label", 'trim|required|max_length[100]');
    
                               


                                if ($this->form_validation->run()) {
                                    $addproducts = $this->Admin_Model->addproducts(array(
                                        'label' =>  $this->input->post('label'),
                                        'created_on' => date('Y-m-d')
                                    ));
                                    if ($addproducts && $addproducts > 0) {
                                        # On success addition
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => true,
                                                    'data' => $addproducts,
                                                    'error' => 'successfully added ',
                                                    
                                                )
                                            ));
                                    } else {
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(400)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => false,
                                                    'data' => [],
                                                    'error' => 'Error Creating New customer'
                                                )
                                            ));
                                    }
                                } else {
                                    return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(400)
                                        ->set_output(json_encode(
                                            array(
                                                'status' => false,
                                                'data' => [],
                                                'error' => $this->form_validation->error_string(" ", " ")
                                            )
                                        ));
                                }
                            }
                        }
                        # Fetch Records
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(
                                array(
                                    'status' => true,
                                    'data' => $this->Admin_Model->getproducts($id),
                                    'error' => ''
                                )
                            ));
                    
                
            }

            function activeproducts(){

                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                        array(
                            'status' => true,
                            'data' => $this->Admin_Model->activeproducts(),
                            'error' => ''
                        )
                    ));
                
               
            }

            //-------------------------------  product disable enable -------------------------

            function product_disable(){

                if($this->input->method(true)=='POST'){
                     $product_id=$this->input->post('product_id');
                     $status=$this->input->post('status');

                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                        array(
                            'status' => true,
                            'data' => $this->Admin_Model->product_disable($product_id,$status),
                            'error' => ''
                        )
                    ));
                }
               
            }


            #================================= ========================= = =====================
            //------------------------ PAYAmount Section start-----------------------------
            #===================================================================================

            /**
             * 
             * 
             * pay_amount (CREATE) Functionality
             * 
             */

            function pay_amount(){
                if($this->input->method(true)=='POST'){

                    $this->form_validation->set_rules('c_id', "c_id", 'trim|required|is_numeric');
                    $this->form_validation->set_rules('pay_amount', "pay_amount", 'trim|required|is_numeric');
                        if ($this->form_validation->run()) {
                                    $addpay_amount = $this->Admin_Model->addpay_amount($this->input->post('c_id'),$this->input->post('pay_amount'));
                                    if ($addpay_amount && $addpay_amount > 0) {
                                        # On success addition
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => true,
                                                    'data' => $addpay_amount,
                                                    'error' => 'successfully added '
                                                )
                                            ));
                                    } else {
                                        return $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(400)
                                            ->set_output(json_encode(
                                                array(
                                                    'status' => false,
                                                    'data' => [],
                                                    'error' => 'Error Creating New pay amount'
                                                )
                                            ));
                                    }
                                } else {
                                    return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(400)
                                        ->set_output(json_encode(
                                            array(
                                                'status' => false,
                                                'data' => [],
                                                'error' => $this->form_validation->error_string(" ", " ")
                                            )
                                        ));
                                }
                }
            }

            /**
             * 
             * 
             * parAmount customer detail (READ) Functionality
             * 
             */
             function account_list(){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    array(
                        'status' => true,
                        'data' => $this->Admin_Model->account_list(),
                        'error' => ''
                    )
                ));
             }
             /**
             * 
             * 
             * account detail one customer api (read) Functionality
             * 
             **/
             function account_detail($c_id=null)
             { 

             if ($this->input->method(true) == 'POST') {

             $from =  $this->input->post('from');
             $to   =  $this->input->post('to');
             $id   =  $this->input->post('c_id');

             return $this->output
             ->set_content_type('application/json')
             ->set_status_header(200)
             ->set_output(json_encode(
             array(
             'status' => true,
             'data' =>$this->Admin_Model->account_detail($id,$from,$to),
             'error'=>'',
             )
             ));
             
                     
             }
             return $this->output
             ->set_content_type('application/json')
             ->set_status_header(200)
             ->set_output(json_encode(
             array(
             'status' => true,
             'data' =>$this->Admin_Model->account_detail($c_id),
             'error'=>'',
             )
             ));
             
            }
#============================================ END =================================================

            //--------------------------  Upload Image -----------------------------------------
    function image_upload(){
        if ($this->input->method(true) == 'POST') {
            if (isset($_FILES['image'])) {
                $config['upload_path']          = './public/uploads/';
                $config['allowed_types']        = 'jpg|png|jpeg/*';
                $config['max_size']             = 4096;
                $config['file_name'] = time() . $_FILES['image']['name'];
                $config['file_name'] = $this->security->sanitize_filename($config['file_name']);
                $this->upload->initialize($config);
                            
                if (!$this->upload->do_upload('image')) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->upload->display_errors()
                    )
                    ));
                }
                $base=base_url('/public/uploads/');
                $file_name = $base.$this->upload->data('file_name');

                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                array(
                    'status' => true,
                    'data' => $file_name,
                    'error' => ''
                )
                ));
            } 
        }
    }
     #----------------------- Dashbord start ------------------------

     function dashbord()
     {
         if ($this->input->method(true) == 'POST') {
            
                //  $_POST = json_decode(file_get_contents("php://input"), true);
                 $from =  $this->input->post('from');
                 $to   =  $this->input->post('to');
                 return $this->output
                     ->set_content_type('application/json')
                     ->set_status_header(200)
                     ->set_output(json_encode(
                         array(
                             'status' => true,
                             'data' => $this->Admin_Model->dashbord($from, $to),
                             'error' => ''
                         )
                     ));
             
         }
     }

     #==============================================================================================
     //--------------------------------- sale section start ----------------------------------------
     #==============================================================================================

     function sale_item($id = null)
     {
    
         if ($this->input->method(true) == 'POST') {
             //   if (isset($data->success) && $data->success == "true"){
 
 
             if ($this->input->post('c_id')) {
                 # Update Existing
                 $c_id = $this->input->post('c_id');
                 $sale_item = array();
 
                 $products = $this->input->post('products');
 
                 $sale_id = $this->Admin_Model->addsale(
                     array(
                         'total_amount' => $this->input->post('total_amount'),
                         'c_id' => $c_id,
                         'created_on' => date('Y-m-d')
                     )
                 );
 
                 if ($sale_id) {
                     foreach ($products as $product) {
 
                         array_push(
                             $sale_item,
                             array(
                                 's_id' => $sale_id,
								 'c_id' => $c_id,
                                 'product_id' => $product['product_id'],
                                 'quantity' => $product['quantity'],
                                 'price' => $product['price'],
								 'created_on'=> date('Y-m-d')
                             )
                         );
                     }
                     $sale = $this->Admin_Model->addsale_item($sale_item);
 
                     if ($sale) {
						 return $this->output
							 ->set_content_type('application/json')
							 ->set_status_header(200)
							 ->set_output(
								 json_encode(
									 array(
										 'status' => true,
										 'data' => $sale,
										 'error' => 'Successfully added'
									 )
								 )
							 );
                     }
                 } else {
                     return $this->output
                         ->set_content_type('application/json')
                         ->set_status_header(200)
                         ->set_output(
                             json_encode(
                                 array(
                                     'status' => false,
                                     'data' => '',
                                     'message' => 'total amount required',
                                     'error' => ''
                                 )
                             )
                         );
                 }
             } else {
                 $this->form_validation->set_rules('name', "name", 'trim|required|max_length[50]');
                 $this->form_validation->set_rules('adress', "adress", 'trim|required|max_length[50]');
                 $this->form_validation->set_rules('image', "address", 'trim|max_length[100]');
                 $this->form_validation->set_rules('phone', "phone", 'trim|required|max_length[13]');
 
                 if ($this->form_validation->run()) {
                     $customers = $this->Admin_Model->addnewcustomers(
                         array(
                             'name' => $this->input->post('name'),
                             'adress' => $this->input->post('adress'),
                             'phone' => $this->input->post('phone'),
                             'image' => $this->input->post('image'),
                             'created_on'=> date('Y-m-d')
                         )
                     );
 
                     
                 } else {
                     return $this->output
                         ->set_content_type('application/json')
                         ->set_status_header(400)
                         ->set_output(
                             json_encode(
                                 array(
                                     'status' => false,
                                     'data' => [],
                                     'error' => $this->form_validation->error_string(" ", " ")
                                 )
                             )
                         );
                 }
 
 
                 // $products = array(
                 // array(
                 // 'product_id' => 1,
                 // 'qty' => 5,
                 // 'price' => 50,
                 // 'sub_total'=>250
                 // ),
                 // array(
                 // 'product_id' => 3,
                 // 'qty' => 5,
                 // 'price' => 100,
                 // 'sub_total'=>500
                 //      )
                 //          );
                 $sale_item = array();
 
                 $products = $this->input->post('products');
 
                 $sale_id = $this->Admin_Model->addsale(
                     array(
                         'total_amount' => $this->input->post('total_amount'),
                         'c_id'  => $customers,
                         'created_on'   => date('Y-m-d H:i:s')
                     )
                 );
 
                 if ($sale_id) {
                     foreach ($products as $product) {
                         array_push(
                             $sale_item,
                             array(
                                's_id' => $sale_id,
                                'c_id' => $customers,
                                'product_id' => $product['product_id'],
                                'quantity' => $product['quantity'],
                                'price' => $product['price'],
                                'created_on'=> date('Y-m-d')
                             )
                         );
                     }
                     $sale_item = $this->Admin_Model->addsale_item($sale_item);
 
                     if ($sale_item) {
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(
                            json_encode(
                                array(
                                    'status' => true,
                                    'data' => $sale_item,
                                    'error' => 'Successfully added'
                                )
                            )
                        );
                         }
                     } else {
                     return $this->output
                         ->set_content_type('application/json')
                         ->set_status_header(200)
                         ->set_output(
                             json_encode(
                                 array(
                                     'status' => false,
                                     'data' => '',
                                     'message' => 'total amount required',
                                     'error' => ''
                                 )
                             )
                         );
                 }
             }

         }
 
     }
 
     #end

     function getSale($c_id=null){
         if($this->input->method(true)=='GET'|| $this->input->method(true)=='POST'){
                  if($this->input->post('s_id') && $this->input->post('c_id')){
                       $s_id=$this->input->post('s_id');
                       $c_id=$this->input->post('c_id');
                       $data=$this->Admin_Model->getSale($c_id,$s_id);
                       return $this->output
                       ->set_content_type('application/json')
                       ->set_status_header(200)
                       ->set_output(
                           json_encode(
                               array(
                                   'status' => false,
                                   'data' => $data,
                                   'error' => ''
                               )
                           )
                       );

                  }else{
                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(
                        json_encode(
                            array(
                                'status' => false,
                                'data' => $this->Admin_Model->getSale($c_id),
                                'error' => ''
                            )
                        )
                    );
                  }
           
         }
     }

 #================================== END =====================================================
#==================================================================================================
// ----------------------- listing Section start -----------------------------------------
#==================================================================================================

             /**
             * 
             * 
             * today_list (READ) Functionality
             * 
             */
            function today_list($id=null){

                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    array(
                        'status' => true,
                        'data' => $this->Admin_Model->today_list($id),
                        'error' => ''
                    )
                ));
            }
              /**
             * 
             * 
             * today_list slae (READ) Functionality
             * 
             */

            function today_list_sale($id=null){

                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    array(
                        'status' => true,
                        'data' => $this->Admin_Model->today_list_sale($id),
                        'error' => ''
                    )
                ));
            }

  #========================================== END ==================================================

  //---------------------------------------- printing data --------------------------------------
  
//   function report(){


//     // $report = $this->db->where(array('MONTH(donor.last_date)' => date('m'), 'YEAR(donor.last_date)' => date('Y')))
//     // ->select('donor.name,donor.gender,blood_type.name AS blood_type,city.Name AS city,donor.last_date,donor.mobile_no')
//     // ->join('blood_type','blood_type.id = donor.blood_type')
//     // ->join('city','city.id = donor.city')
//     // ->order_by('donor.last_date')
//     // ->get('donor')->result_array();
//   $customer_list=  $this->Admin_Model->today_list();
// // json_encode($customer_list);
// // return;
// // Include the main TCPDF library (search for installation path).

// // create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// global $l;
// $l = Array();

// // PAGE META DESCRIPTORS --------------------------------------

// $l['a_meta_charset'] = 'UTF-8';
// $l['a_meta_dir'] = 'rtl';
// $l['a_meta_language'] = 'ur';

// // TRANSLATIONS --------------------------------------
// $l['w_page'] = 'page';
// $pdf->AddPage();


// $pdf->cell(180,5,"SAVE LIVES",1,1,'C');
// $pdf->setfont('dejavusans','',8);
// $pdf->cell(180,5,"Report",1,1,'C');

// $pdf->SetTextColor(0,0,0);

// $pdf->cell(30,5,'Account no',1);
// $pdf->cell(30,5,'Nmae',1);
// $pdf->cell(20,5,'Address',1);
// $pdf->cell(40,5,'Remaining Amount',1);
// $pdf->cell(20,5,'New Amount',1);
// $pdf->cell(20,5,'Total Amount',1);
// $pdf->cell(20,5,'Pay Amount',1);

// $pdf->Ln();

// $pdf->SetTextColor(48,48,48);

// foreach($customer_list  as $data){
// $pdf->cell(30,5,$data['id'],1);
// $pdf->cell(30,5,$data['name'],1);
// $pdf->cell(20,5,$data['adress'],1);
// $pdf->cell(40,5,$data['rummening_amount'],1);
// $pdf->cell(20,5,$data['fresh_amount'],1);
// $pdf->cell(20,5,$data['total_amount'],1);
// $pdf->cell(20,5," ",1);

// $pdf->Ln();
// }


// //Close and output PDF document
// ob_end_clean();
// $pdf->Output('Blood_Report.pdf');
// }
     #=====================================================================================
               // customer due total amount listing today
function today_list_print(){
    
  $customer_list=  $this->Admin_Model->today_list();

// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
// convert TTF font to TCPDF format and store it on the fonts folder
// $fontname = TCPDF_FONTS::addTTFfont('TCPDF-main/urdufont.ttf', 'TrueTypeUnicode', '', 96);
$fontname = TCPDF_FONTS::addTTFfont('TCPDF-main/ARIALUNI.ttf', 'TrueTypeUnicode', '', 20);
$fontname = TCPDF_FONTS::addTTFfont('TCPDF-main/urdufont.ttf', 'TrueTypeUnicode', '', 32);
// use the font


$pdf->SetAuthor('Ahmed Sayyam');
$pdf->SetTitle('Virtual Soft Software Company');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set margins
$pdf->SetMargins('3', '4', '1');


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// // set image scale factor
// set image scale factor
$pdf->setImageScale(1);
// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'ur';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
// // set some language-dependent strings (optional)
// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
//     require_once(dirname(__FILE__).'/lang/eng.php');
//     $pdf->setLanguageArray($l);
// }
// if (@file_exists(dirname(__FILE__).'/lang/urd.php')) {
//     require_once(dirname(__FILE__).'/lang/urd.php');
//     $pdf->setLanguageArray($l);
// }


// ---------------------------------------------------------
$date=date('Y-m-d');
// set font
// $pdf->SetFont($fontname, '', 14);
// $this->SetFont($font,'',10);
// $pdf->SetFont('dejavusans', '', 12);
$pdf->SetFont('arialuni', '', 15);

// add a page
$pdf->AddPage();

// create some HTML content
$html = '

<h1 style="text-align:center;">رانا عدنان اینڑ برادرز مارکہ</h1>

<span style="text-align:center; margin:0; display:block;">&nbsp; &nbsp;<span>دکان نمبر</span><span>38/A</span><span>نیوسبزی منڑی صادق آباد</span></span>
<p style="text-align:center; margin:0;"><span>رابطہ نمبر</span>:</p>
<p style="text-align:center; margin:0;">&nbsp; &nbsp;<span>راناعدنان</span><Span>:</span><span>0302-6819127,0332-7303544</span></p>
<p style="text-align:center; margin:0;"><span>راناعبدالرؤف</span><Span>:</span><span>0300-9860631,0346-7687836</span></p>
<p style="text-align:center;"><span>تاریخ</span>&nbsp; '.$date.'</p>
';

$tbl_html = "
<h3><b>گاہک کی تفصیلات</b></h3>
<table>
<tr>
    <th><b>نمبرشمار</b></th>
    <th><b>اکاونٹ نمبر</b></th>
    <th><b>نام اکاونٹ</b></th>
    <th><b>پتہ</b></th>
    <th><b>بقایا رقم</b></th>
    <th><b>تازہ مال رقم</b></th>
    <th><b>کل بقایا</b></th>
    <th><b>وصولی</b></th>
</tr>";
$i=1;
foreach($customer_list  as $customer){
    $image=$customer['image'];
    $tbl_html .= "<tr  style='border:1px solid #eeeeee;padding:4px;'>
       <td>$i</td>
       <td>{$customer['id']}</td>
       <td>{$customer['name']}</td>
       <td>{$customer['adress']}</td>
       <td>{$customer['rummening_amount']}</td>
       <td>{$customer['fresh_amount']}</td> 
       <td>{$customer['total_amount']}</td> 
       <td>  </td> 


    </tr>";
    $i++;
  };
  $tbl_html .="
</table>

<style>
span{
    
}
table{
    borer-collapse:collapse;
}
table tr th{
    background-color: lightgrey;
}
th,td{
    border: 1px solid #696969;text-align:center;height:15px;vertical-align: center;
    font-size:12px;
}
</style>	
";

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->writeHTMLCell(200,0,1,'',$tbl_html,0);



//Close and output PDF document
$pdf->Output('current_voucher.pdf', 'I');
}
#========================================================================================================
// slae products   listing today
function today_list_print_saleProducts(){
    
    $customer_list=  $this->Admin_Model->today_list_sale();
  
  // create new PDF document
  $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
  // convert TTF font to TCPDF format and store it on the fonts folder
  // $fontname = TCPDF_FONTS::addTTFfont('TCPDF-main/urdufont.ttf', 'TrueTypeUnicode', '', 96);
  $fontname = TCPDF_FONTS::addTTFfont('TCPDF-main/ARIALUNI.ttf', 'TrueTypeUnicode', '', 20);
  $fontname = TCPDF_FONTS::addTTFfont('TCPDF-main/urdufont.ttf', 'TrueTypeUnicode', '', 32);
  // use the font
  
  
  $pdf->SetAuthor('Ahmed Sayyam');
  $pdf->SetTitle('Virtual Soft Software Company');
  
  // remove default header/footer
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  
  // set margins
  $pdf->SetMargins('3', '4', '1');
  
  
  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  
  
  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  
  // // set image scale factor
  // set image scale factor
  $pdf->setImageScale(1);
  // set some language dependent data:
  $lg = Array();
  $lg['a_meta_charset'] = 'UTF-8';
  $lg['a_meta_dir'] = 'rtl';
  $lg['a_meta_language'] = 'ur';
  $lg['w_page'] = 'page';
  
  // set some language-dependent strings (optional)
  $pdf->setLanguageArray($lg);
  // // set some language-dependent strings (optional)
  // if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  //     require_once(dirname(__FILE__).'/lang/eng.php');
  //     $pdf->setLanguageArray($l);
  // }
  // if (@file_exists(dirname(__FILE__).'/lang/urd.php')) {
  //     require_once(dirname(__FILE__).'/lang/urd.php');
  //     $pdf->setLanguageArray($l);
  // }
  
  
  // ---------------------------------------------------------
  $date=date('Y-m-d');
  // set font
  // $pdf->SetFont($fontname, '', 14);
  // $this->SetFont($font,'',10);
  // $pdf->SetFont('dejavusans', '', 12);
  $pdf->SetFont('arialuni', '', 15);
  
  // add a page
  $pdf->AddPage();
  
  // create some HTML content
  $html = '
  
  <h1 style="text-align:center;">رانا عدنان اینڑ برادرز مارکہ</h1>
  
  <span style="text-align:center; margin:0; display:block;">&nbsp; &nbsp;<span>دکان نمبر</span><span>38/A</span><span>نیوسبزی منڑی صادق آباد</span></span>
  <p style="text-align:center; margin:0;"><span>رابطہ نمبر</span>:</p>
  <p style="text-align:center; margin:0;">&nbsp; &nbsp;<span>راناعدنان</span><Span>:</span><span>0302-6819127,0332-7303544</span></p>
  <p style="text-align:center; margin:0;"><span>راناعبدالرؤف</span><Span>:</span><span>0300-9860631,0346-7687836</span></p>
  <p style="text-align:center;"><span>تاریخ</span>&nbsp; '.$date.'</p>
  ';
  
  $tbl_html = "
  <h3><b>گاہک کی تفصیلات</b></h3>
  <table>
  <tr>
      <th><b>نمبرشمار</b></th>
      <th><b>اکاونٹ نمبر</b></th>
      <th><b>نام اکاونٹ</b></th>
      <th><b>پروڈکٹ کا نام </b></th>
      <th><b>مقدار</b></th>
      <th><b> رقم</b></th>
      <th><b> تاریخ</b></th>

  </tr>";
  $i=1;
  foreach($customer_list  as $customer){
    //   $image=$customer['image'];
      $tbl_html .= "<tr  style='border:1px solid #eeeeee;padding:4px;'>
         <td>$i</td>
         <td>{$customer['c_id']}</td>
         <td>{$customer['name']}</td>
         <td><span>{$customer['label']}</span></td>
         <td><span>{$customer['quantity']}</span></td>
         <td>{$customer['price']}</td> 
         <td>{$customer['created_on']}</td> 

  
      </tr>";
      $i++;
    };
    $tbl_html .="
  </table>
  
  <style>
  span{
      
  }
  table{
      borer-collapse:collapse;
  }
  table tr th{
      background-color: lightgrey;
  }
  th,td{
      border: 1px solid #696969;text-align:center;height:15px;vertical-align: center;
      font-size:12px;
  }
  </style>	
  ";
  
  // output the HTML content
  $pdf->writeHTML($html, true, 0, true, 0);
  $pdf->writeHTMLCell(200,0,1,'',$tbl_html,0);
  
  
  
  //Close and output PDF document
  $pdf->Output('current_voucher.pdf', 'I');
  }

    #=============================================================================================
    #=============================================================================================

   

    public function myData(){
        $member_id = $this->Admin_Model->getData();
        if($member_id && $member_id > 0){
            return $this->output->set_content_type('application/json')->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => true,
                'data' => $member_id,
                'error' => 'New Member Created Successfully'
            )));
        }
        else{
            return $this->output->set_content_type('application/json')->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'data' => [],
                'error' => 'New Member has not been created'
            )));
        }
    }
}

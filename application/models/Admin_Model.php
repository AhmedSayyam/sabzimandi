<?php

class Admin_Model extends CI_Model{
    function __construct(){
		parent:: __construct();
	}

    public function login($name, $password){
        $query = $this->db->where('tbladmin.name', $name)->get('tbladmin')->row();
       
        if($query){
          
            $hash = $query->password;
            $id = $query->id;
            if(password_verify($password, $hash)){
                $session_data = array(
                    'username' => $name,
                    'id' => $id,
                    'is_logged_in' => true
                );
                $today = date("Y-m-d H:i:s");
                $this->db->where('id',$id)->update('tbladmin',array('last_login'=>$today)); 
                $this->session->set_userdata($session_data);
                return array(
                    'status' => true,
                        'id' => $query->id,
                        'username' => $query->name,
                    'message' => 'Login Successfull'
                );
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function check_email($email){
        $query = $this->db->where('tbladmin.email', $email)->get('tbladmin')->row();
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function update_forgot_code($email, $rand_num){
        $query = $this->db->where('tbladmin.email',$email)->update('tbladmin', ['forgot_code'=>$rand_num]);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function verify_code($code){
        $query = $this->db->where('tbladmin.forgot_code', $code)->get('tbladmin')->row();
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function change_password($pass){
        $query = $this->db->where('tbladmin.id', 1)->update('tbladmin', ['password'=>$pass]);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    #============================================================================================
    #=============================-------- START--------========================================= 
    #============================================================================================
            /**
             * 
             * 
             * products (CREATE,READ,UPDATE) Functionality
             * 
             */
            function addproducts($data){
                $this->db->insert('tblproducts',$data);
                 return $this->db->insert_id();
            }

            function updateproducts($id,$data){
               
                $this->db->where('tblproducts.id', $id)->update('tblproducts', $data);
        
        
                if ($this->db->affected_rows() > 0) {
        
                    return true;
                } else {
                    return false;
                }
            
             }

             function getproducts($id=null){
                 if($id==null){
                    return  $this->db->select('tblproducts.id,tblproducts.label,tblproducts.status')->get('tblproducts')->result_array();
                 }
                 else{
                  return  $this->db->where('tblproducts.id',$id)->get('tblproducts')->result_array();
                 }
             }
            // products List 
             function activeproducts($id=null){
               
          return  $this->db->where('tblproducts.status=1')->select('tblproducts.id,tblproducts.label,tblproducts.status')->get('tblproducts')->result_array();
               
            }

             function product_disable($product_id,$status){

                $update = $this->db->query(
                    "update tblproducts set status=$status
                  , updated_on = CURRENT_TIMESTAMP where tblproducts.id=$product_id"
                );
                if ($this->db->affected_rows() > 0) {
            
                    return true;
                } else {
                    return false;
                }
             }
     #================================ END =============================================

            /**
             * 
             * 
             * customer (CREATE,READ,UPDATE) Functionality
             * 
             */
            function updatecustomer($id,$data){
               
                    $this->db->where('tblcustomer.id', $id)->update('tblcustomer', $data);
            
            
                    if ($this->db->affected_rows() > 0) {
            
                        return true;
                    } else {
                        return false;
                    }
                
            }
            // function addcustomers($id,$amount){
            //     $t_amounts = $this->db->query(
            //         "SELECT tblcustomer.amount AS total_amount FROM tblcustomer
            //                     WHERE tblcustomer.id=$id"
            //     )
            //         ->row()->total_amount;
            //     $t_amounts += $amount;
              
            //     $update = $this->db->query(
            //         "update tblcustomer set amount=$t_amounts
            //       , updated_on = CURRENT_TIMESTAMP where tblcustomer.id=$id"
            //     );
    
            //     if ($this->db->affected_rows() > 0) {
            //         $this->db->insert(
            //             'tblsale',
            //             array(
            //                 'c_id' => $id,
            //                 'new_amount' => $amount,
            //                 'created_on' => date('y-m-d H:i:s')
            //             )
            //         );
            //         return true;
            //     }
               
            // }

            function addnewcustomers($data){
                $customer = $this->db->where(array('tblcustomer.name'=>$data['name'],'tblcustomer.phone'=>$data['phone']))->get('tblcustomer');
                if ($customer->num_rows() > 0) {
                    $customer = $customer->row();
                    return $customer->id;
                    // $amount = $this->db->query(
                    //     "SELECT tblcustomer.amount AS total_amount FROM tblcustomer
                    //                 WHERE tblcustomer.id=$customer->id"
                    // )
                    //     ->row()->total_amount;
                    // $amount += $data['amount'];
                  
                    // $update = $this->db->query(
                    //     "update tblcustomer set amount=$amount
                    //   , updated_on = CURRENT_TIMESTAMP where tblcustomer.id=$customer->id"
                    // );
        
                    // if ($this->db->affected_rows() > 0) {
                    //     $this->db->insert(
                    //         'tblsale',
                    //         array(
                    //             'c_id' => $customer->id,
                    //             'new_amount' => $data['amount'],
                    //             'created_on' => date('y-m-d H:i:s')
                    //         )
                    //     );
                    //     return true;
                    // }
                }else{

                     $this->db->insert(
                    'tblcustomer',
                    array(
                        'name' => $data['name'],
                        'phone' => $data['phone'],
                        'adress' =>$data['adress'],
                        'image'  =>$data['image'],
                        'amount'=>0,
                        'created_on' => date('y-m-d')
                    )
                );
                // $this->db->insert('tblcustomer', $data);
                // $id = $this->db->insert_id();
    
               
                return  $this->db->insert_id();
                }
               
            }

            function addcustomer($data)
            {
        # for updating check if record already exists

        $customer = $this->db->where(array('tblcustomer.name'=>$data['name'],'tblcustomer.phone'=>$data['phone']))->get('tblcustomer');
        if ($customer->num_rows() > 0) {
            return false;
            // $customer = $customer->row();

            // $amount = $this->db->query(
            //     "SELECT tblcustomer.amount AS total_amount FROM tblcustomer
            //                 WHERE tblcustomer.id=$customer->id"
            // )
            //     ->row()->total_amount;
            // $amount += $data['amount'];
          
            // $update = $this->db->query(
            //     "update tblcustomer set amount=$amount
            //   , updated_on = CURRENT_TIMESTAMP where tblcustomer.id=$customer->id"
            // );

            // if ($this->db->affected_rows() > 0) {
            //     $this->db->insert(
            //         'tblsale',
            //         array(
            //             'c_id' => $customer->id,
            //             'new_amount' => $data['amount'],
            //             'created_on' => date('y-m-d H:i:s')
            //         )
            //     );
            //     return true;
            // }
        } else {
            $this->db->insert('tblcustomer', $data);
            $id = $this->db->insert_id();

            // $this->db->insert(
            //     'tblsale',
            //     array(
            //         'c_id' => $id,
            //         'new_amount' => $data['amount'],
            //         'created_on' => date('y-m-d H:i:s')
            //     )
            // );
            return $id;
        }
    }

    function getcustomer($id=null){
        
            if ($id != null && is_numeric($id)) {
                $this->db->where('id', $id);
            }
    
            $customer = $this->db->select(
                'tblcustomer.*'
            )
                ->order_by('tblcustomer.created_on', 'DESC')
                ->get('tblcustomer');
    
    
            return ($id != null && is_numeric($id)) ? $customer->row() : ($customer->num_rows(
            ) > 0 ? $customer->result_array() : []);
        
    }

    

            /**
             * 
             * 
             * customer_reports (READ) Functionality
             * 
             **/

        function customer_reports($id,$from=null,$to=null)
        {
     
        if ($from == null && $to == null) {

            $list=array();
            $query =  $this->db->query("select tblsale.*
            From 
            tblsale
            where tblsale.c_id=$id ORDER BY tblsale.created_on DESC");
            $customer=$query->result_array();
            return $customer;
        }
        // if($from !=null ){

        // }
       
        
    //   $list=array();
    //   $query =  $this->db->query("select tblcustomer.id,tblcustomer.name,tblcustomer.adress,
    //    tblsale.new_amount,tblsale.created_on AS sale_date , tblaccount.pay_amount,tblaccount.created_on AS pay_date
    //   From 
    //   tblcustomer
    //   left JOIN tblaccount ON tblaccount.c_id=tblcustomer.id
    //   left JOIN tblsale ON tblsale.c_id=tblcustomer.id
    //   where tblcustomer.id=$id ORDER BY tblsale.created_on DESC");

    // //   AND DATE(tblsale.created_on) BETWEEN '$from' AND '$to'
    //     $customer=$query->result_array();
    //     return $customer;
    //          foreach($customer as $data){

    //              $payamount =  $this->db->query("select tblaccount.*
    //             From 
    //             tblaccount
    //              where tblaccount.c_id=$id AND DATE(tblaccount.created_on) =DATE('{$data['created_on']}')");
 
    //             if($payamount->num_rows()>0){
                  
    //                $customer_pay=$payamount->result_array();

    //             array_push(
    //                 $list,
    //                 array(
            
    //                     'id'    => $data['id'],
    //                     'name'  => $data['name'],
    //                     'adress' => $data['adress'],
    //                     'new_amount' => $data['new_amount'],
    //                     'pay_amount' =>  $customer_pay[0]['pay_amount'],
    //                     'created_on' => $data['created_on'],

    //                 )
    //                 );
    //   }else{
    //     array_push(
    //         $list,
    //         array(
    
    //             'id'    => $data['id'],
    //             'name'  => $data['name'],
    //             'adress' => $data['adress'],
    //             'new_amount' => $data['new_amount'],
    //             'pay_amount' => 0,
    //             'created_on' => $data['created_on'],
    //         )
    //         );
    //   }
     

    //     }
    //     return $list; 

       }

       function saleDetail($id)
       {
    
       
           $query =  $this->db->query("select tblsale_product.*,tblproducts.label
           From 
           tblsale_product
           left Join tblproducts ON tblsale_product.product_id=tblproducts.id
           where tblsale_product.s_id=$id ");
           $customer=$query->result_array();
           return $customer;
       
     }
       

       #=========================================== END ===========================================
            #================================= ========================= = =====================
            //------------------------ PAYAmount Section start-----------------------------
            #===================================================================================
            /**
             * 
             * 
             * Pay_amount (CREATE) Functionality
             * 
             **/
             
             function addpay_amount($c_id,$pay_amount)
             {     

                //   $this->db->insert('tblaccount',$data);
                //   $id=$this->db->insert_id();
                  

                //    $total_amount= $this->db->query(
                //         "SELECT tblcustomer.amount AS total_amount FROM tblcustomer
                //                     WHERE tblcustomer.id=$customer->id"
                //     )
                //         ->row()->total_amount;

                $total_amount= $this->db->where('tblcustomer.id',$c_id)->get('tblcustomer')->row();
            
                $amount=$total_amount->amount-$pay_amount;
                    $this->db->where(array('tblcustomer.id' => $c_id))
                    ->update('tblcustomer', array('amount' => $amount , 'updated_on' => date('Y-m-d')));
                    if ($this->db->affected_rows() > 0) {
                       
                        $this->db->insert(
                            'tblaccount',
                            array(
                                'c_id' => $c_id,
                                'total_amount'     => $total_amount->amount,
                                'pay_amount'       => $pay_amount,
                                'due_total_amount' => $amount,
                                'created_on'       => date('y-m-d')
                            )
                        );
                        return $this->db->insert_id();
                    }else{
                        return false;
                    }
              }

              // ======================= account detail for customer ======================


              function account_list($id=null){
                $customer =  $this->db->get('tblcustomer')->result_array();
                $list = array();
      
              foreach ($customer as $data) {

                $customer_account =  $this->db->query(
                    "SELECT tblaccount.c_id, SUM(tblaccount.total_amount) AS total_amount ,SUM(tblaccount.pay_amount) AS pay_amount ,
                    SUM(tblaccount.due_total_amount) AS due_total_amount 
                     FROM tblaccount
                          WHERE tblaccount.c_id={$data['id']}"
                )->result_array();
                   
                  if ($customer_account[0]['c_id'] !=null ) {
                      
                    //   $customer_account = $query->result_array();
      
      
                          array_push(
                              $list,
                              array(
                                  'image' => $data['image'],
                                  'id'    => $data['id'],
                                  'name'  => $data['name'],
                                  'adress' => $data['adress'],
                                  'phone' => $data['phone'],
                                  'total_amount' => $customer_account[0]['total_amount'],
                                  'pay_amount' => $customer_account[0]['pay_amount'],
                                  'due_total_amount' =>$customer_account[0]['due_total_amount'],
    
                              )
                              );
                      
                  }else{
                    array_push(
                        $list,
                        array(
                            'image' => $data['image'],
                            'c_id'    => $data['id'],
                            'name'  => $data['name'],
                            'adress' => $data['adress'],
                            'phone' => $data['phone'],
                            'total_amount' => $data['amount'],
                            'pay_amount' => '0',
                            'due_total_amount' => $data['amount'],

                        )
                        );
                  }
              }
              return $list;
              }
           /**
             * 
             * 
             * account detail one customer api (read) Functionality
             * 
             **/
              function account_detail($c_id,$from=null,$to=null){
                if($from==null && $to ==null){
                      
                $account=  $this->db->where('tblaccount.c_id',$c_id)->get('tblaccount');
                if($account->num_rows()>0){
                    return $account->result_array();
                }else{
                    return false;
                }

                  
                }
                else{
                  $data = $this->db->query(
                      "SELECT tblaccount.* FROM tblaccount
                            WHERE tblaccount.c_id=$c_id AND DATE(tblaccount.created_on) BETWEEN '$from' AND '$to'"
                  );

                  if($data->num_rows()>0){
                    return $data->result_array();
                   }
                   else{
                      return false;
                    }
                    
                }

              
            }
              

                
          #============================================ END =================================================

          #==================================================================================================
          #================================= Dashbord section Start =========================================
   
             function get_total_due_amount($from=null,$to=null)
             {

                if ($from == null && $to==null ) {
                    // $from = date("Y-m-d ");
                    $data = $this->db->query(
                        "SELECT SUM(tblcustomer.amount) AS total_amount FROM tblcustomer
                              "
                    )
                        ->result_array();
                        return $data;
                }else{

                    if ($to == null) {
                          $to = date("Y-m-d ");
                    }
                    if ($from == null) {
                        $to = date("Y-m-d ");
                    }
                    $data = $this->db->query(
                        "SELECT SUM(tblcustomer.amount) AS total_amount FROM tblcustomer
                              WHERE DATE(tblcustomer.created_on) BETWEEN '$from' AND '$to'"
                    )
                        ->result_array();
                        return $data;

                }
             }

             function get_new_amount($from=null,$to=null)
             {
                if ($to == null) {
                    $to = date("Y-m-d ");
                 }
                if ($from == null) {
                  $from = date("Y-m-d ");
                 }
                 $data = $this->db->query(
                    "SELECT SUM(tblsale.new_amount) AS new_amount FROM tblsale
                          WHERE DATE(tblsale.created_on) BETWEEN '$from' AND '$to'"
                )
                    ->result_array();
                    return $data;
             }

             function dashbord($from = null, $to = null)
            {
        
                $due_total_amount=$this->get_total_due_amount($from,$to);
                $new_amount=$this->get_new_amount($from,$to);

                return [$due_total_amount,$new_amount];

            }

         #================================= END =======================================================

         #=============================================================================================
         //---------------------------- Sale Section Start --------------------------------------------
           #---------------------- sale_item (CREATE,READ,UPDATE) Functionality -----------------------

    function addsale_item($data)
    {
        $this->db->insert_batch('tblsale_product', $data);
        return $this->db->insert_id();
    }

    function addsale($total_amount)
    {
        // $total_amount['c_id'];
        $t_amounts = $this->db->query(
            "SELECT tblcustomer.amount AS total_amount FROM tblcustomer
                        WHERE tblcustomer.id={$total_amount['c_id']};"
        )
            ->row()->total_amount;
        $t_amounts += $total_amount['total_amount'];
      
        $update = $this->db->query(
            "update tblcustomer set amount=$t_amounts
          , updated_on = CURRENT_TIMESTAMP where tblcustomer.id={$total_amount['c_id']};"
        );

        if ($this->db->affected_rows() > 0) {
            //  $this->db->insert('tblsale', $total_amount);
            //  return  $this->db->insert_id();
            $this->db->insert(
                'tblsale',
                array(
                    'c_id' => $total_amount['c_id'],
                    'new_amount' =>$total_amount['total_amount'],
                    'created_on' => $total_amount['created_on']
                )
            );
               return  $this->db->insert_id();
        }
//        do {
//            $order_number = strtolower(random_string('alnum', 12));
//            $check = $this->dbfg->where('tblinvoice.order_number', $order_number)->get('tblinvoice');
//        } while ($check->num_rows() > 0);

//        $this->dbfg->where('tblinvoice.invoice_id', $id)->update('tblinvoice', array('order_number' => $order_number));
//        return $order_number;
    }


    function getSale($c_id=null,$s_id=null){

           if($c_id !=null && $s_id !=null){
             $sale=   $this->db->where('tblsale_product',array('tblsale_product.s_id'=>$s_id,
                'tblsale_product.c_id'=>$c_id))->get('tblsale_product');
                if($sale->num_rows()>0){
                    return $sale->result_array();
                }
           }
           else{
              return $this->db->where('tblsale.c_id',$c_id)->select('tblsale.id AS s_id,tblsale.c_id,tblsale.new_amount,
              tblsale.created_on,tblcustomer.name,tblcustomer.phone,')
              ->join('tblcustomer','tblcustomer.id = tblsale.c_id')
              ->order_by('tblsale.c_id', 'ASC')
              ->get('tblsale')->result_array();
           }
    }

    #======================================== End =====================================================
    #==================================================================================================
    #------------------------------ LIST SECTION START ------------------------------------------------
    #==================================================================================================
     // customer due total amount listing today
    function today_list($id=null){
        $customer =  $this->db->get('tblcustomer')->result_array();
        $list = array();

      foreach ($customer as $data) {
         
          $query = $this->db->query("select tblsale.*   From
         tblsale
         where DATE(tblsale.created_on) = CURRENT_DATE AND tblsale.c_id={$data['id']}");

          if ($query->num_rows() > 0) {
              $customer_data = $query->result_array();


                  array_push(
                      $list,
                      array(
                          'image' => $data['image'],
                          'id'    => $data['id'],
                          'name'  => $data['name'],
                          'adress' => $data['adress'],
                          'rummening_amount' => $data['amount']-$customer_data[0]['new_amount'],
                          'fresh_amount' => $customer_data[0]['new_amount'],
                          'total_amount' => $data['amount'],
                      )
                      );
              
          }else{

              array_push(
                  $list,
                  array(
                      'image' => $data['image'],
                      'id' => $data['id'],
                      'name' => $data['name'],
                      'adress' => $data['adress'],
                      'rummening_amount' => $data['amount'],
                      'fresh_amount' => 0,
                      'total_amount' => $data['amount'],
                  )
                  );
          }
      }
      return $list;
      }


      // slae products   listing today
    function today_list_sale($id=null){
        
        $query = $this->db->query("select tblsale_product.*,tblproducts.label,tblcustomer.name ,tblcustomer.adress
         From
         tblsale_product
         left join tblcustomer ON tblcustomer.id=tblsale_product.c_id
          join tblproducts ON tblproducts.id=tblsale_product.product_id
         where DATE(tblsale_product.created_on) = CURRENT_DATE");

         if( $query->num_rows()>0){
            return  $query->result_array();
        }else{
            return false;
        }

    //     $customer =  $this->db->get('tblcustomer')->result_array();
    //     $list = array();

    //   foreach ($customer as $data) {
         
    //       $query = $this->db->query("select tblsale.*   From
    //      tblsale
    //      where DATE(tblsale.created_on) = CURRENT_DATE AND tblsale.c_id={$data['id']}");

    //       if ($query->num_rows() > 0) {
    //           $customer_data = $query->result_array();


    //               array_push(
    //                   $list,
    //                   array(
    //                       'image' => $data['image'],
    //                       'id'    => $data['id'],
    //                       'name'  => $data['name'],
    //                       'adress' => $data['adress'],
    //                       'rummening_amount' => $data['amount']-$customer_data[0]['new_amount'],
    //                       'fresh_amount' => $customer_data[0]['new_amount'],
    //                       'total_amount' => $data['amount'],
    //                   )
    //                   );
              
    //       }else{

    //           array_push(
    //               $list,
    //               array(
    //                   'image' => $data['image'],
    //                   'id' => $data['id'],
    //                   'name' => $data['name'],
    //                   'adress' => $data['adress'],
    //                   'rummening_amount' => $data['amount'],
    //                   'fresh_amount' => 0,
    //                   'total_amount' => $data['amount'],
    //               )
    //               );
    //       }
    //   }
    //   return $list;
      }

      #========================================== END =======================================================



    #==============================================================================================================
    #==================================------- END --------========================================================
    #==============================================================================================================
    // public function getData(){
    //     echo "Working";
    // }

    // public function add_new_member($data){
    //     $this->db->insert('tbl_members', $data);
    //     return $this->db->insert_id();
    // }
    
    // public function add_new_package($data){
    //     $this->db->insert('tbl_packages', $data);
    //     return $this->db->insert_id();
    // }

    // public function add_new_staff($data){
    //     $this->db->insert('tbl_instructors', $data);
    //     return $this->db->insert_id();
    // }

    // public function get_members(){
    //     return $this->db->select('tbl_packages.package_name,tbl_packages.package_amount,tbl_members.*,tbl_instructors.instructor_name')
    //     ->join('tbl_packages','tbl_packages.package_id = tbl_members.pack_id')
    //     ->join('tbl_instructors','tbl_instructors.instructor_id = tbl_members.ref_id')
    //     ->get('tbl_members')
    //     ->result_array();
    // }

    // public function get_staff(){
    //     $staff = $this->db->get('tbl_instructors');
    //     return $staff->result_array();
    // }

    // public function get_packages(){
    //     $packages = $this->db->get('tbl_packages');
    //     return $packages->result_array();
    // }

    // public function delete_member($id){
    //     $this->db->where('member_id', $id)->delete('tbl_members');
    //     return $this->db->affected_rows()>0;
    // }
    
    // public function delete_staff($id){
    //     $this->db->where('instructor_id', $id)->delete('tbl_instructors');
    //     return $this->db->affected_rows()>0;
    // }

    // public function delete_package($id){
    //     $this->db->where('package_id', $id)->delete('tbl_packages');
    //     return $this->db->affected_rows()>0;
    // }
    
    // public function update_package($id, $data){
    //     $this->db->where(array('package_id'=>$id))->update('tbl_packages',$data);
    //     return $this->db->affected_rows()>0;
    // }

    // public function update_staff($id, $data){
    //     $this->db->where(array('instructor_id'=>$id))->update('tbl_instructors',$data);
    //     return $this->db->affected_rows()>0;
    // }

    // public function update_member($id, $data){
    //     $this->db->where(array('member_id'=>$id))->update('tbl_members',$data);
    //     return $this->db->affected_rows()>0;
    // }

    // public function deposit_fee($data){
    //     $this->db->insert('tbl_deposit', $data);
    //     return $this->db->insert_id();
    // }
    
}

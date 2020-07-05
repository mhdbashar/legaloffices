<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);    
    }

    public function insert_post()
    {
		
	$offic_name = $this->input->post('offic_name');
	
  
	 
	 if(isset($offic_name) && !empty($offic_name)){
		   $result=$this->db->query("select * from offic where offic_name= '". $offic_name."'")->row();
	
	 if($result){
 		//$token_token=rand(1,1020120110000230);
		//  $token = AUTHORIZATION::generateToken($token_token);
		$token = $this->input->post('token');
		$office_url = $this->input->post('office_url');


		$id= $result->id;
		
			$data = array(
			
				'token'		=>	$token,
				'office_url'		=>	$office_url

			);
		 
		 $this->db->where('id', $id)->update('offic', $data);
            
	 }
	 else{
		 
          
            // $token = AUTHORIZATION::generateToken(['offic_name' => $offic_name]);
			
			$token = $this->input->post('token');
			$office_url = $this->input->post('office_url');
			
			
			
			$data = array(
				'offic_name'	=>	$offic_name,
				'token'		=>	$token,
				'office_url'		=>	$office_url

			);
			
			
			 $this->db->insert('offic',$data);

            
            $status = parent::HTTP_OK;

            $response = ['status' => $status, 'token' => $token];

            $this->response($response, $status);
	 }
         }
        else {
            $this->response(['msg' => 'where Office Name!'], parent::HTTP_NOT_FOUND);
        }
		 
		 
	 

 

    }

   
   /*  public function update_put($id)
        {

        $input = $this->put();
		
        $this->db->update('offic', $input, array('id'=>$id));
     
        $this->response(['offic updated successfully.'], REST_Controller::HTTP_OK);
		
        } */
  

	function list_get()
	{
		
	
		
		$data=$this->db->query("select id,offic_name,office_url from offic ")->result_array();
	
		
		$this->response($data, REST_Controller::HTTP_OK);
	}
	
	function get_token_get($offic_name)
	{
		
		
		
		$data=$this->db->query("select office_url,token from offic where offic_name= '".$offic_name."'")->row();
	
		
		$this->response($data, REST_Controller::HTTP_OK);
	}
	
	

}

/* End of file Api.php */

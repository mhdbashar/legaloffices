<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

use Restserver\Libraries\REST_Controller;

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
    }

    public function encode_string($office_name = '', $length = 1000) {

        $key = '';
        $k = $office_name;
        $c = "شبكة بابل للقانون";

        for ($i = 0; $i < $length; $i++) {
            $key = base64_encode(base64_encode(base64_encode($k) . $i . $c));
            $rr = base64_encode($key);
        }

        return $rr;
    }

    public function insert_post() {

        $offic_name = $this->input->post('offic_name');

        $key = $this->encode_string($offic_name);




        if (isset($offic_name) && !empty($offic_name)) {
            $result = $this->db->query("select * from offic where offic_name= '" . $offic_name . "'")->row();

            if ($result) {






/* 
                $token = $this->input->post('token');
                $office_url = $this->input->post('office_url');

                $id = $result->id;



                $data = array(
                    'token' => $token,
                    'office_url' => $office_url
                );

                $this->db->where('id', $id)->update('offic', $data); */
				
				

                   echo '<script>alert("الاسم موجود مسبقا لايمكنك أخذه ")</script>'; 

                       return false;

				
				
				
            } else {




                $token = $this->input->post('token');
                $office_url = $this->input->post('office_url');

                $data = array(
                    'offic_name' => $offic_name,
                    'token' => $token,
                    'office_url' => $office_url,
                    'keycode' => $key
                );


                $this->db->insert('offic', $data);

                $last_id = $this->db->insert_id();
                $result = $this->db->query("select * from offic where id= '" . $last_id . "'")->row();

                $status = parent::HTTP_OK;

                $response = ['status' => $status, 'keycode' => $result->keycode];

                $this->response($response, $status);
            }
        } else {
            $this->response(['msg' => 'where Office Name!'], parent::HTTP_NOT_FOUND);
        }
    }

    function list_post() {

  $keycode= $this->input->post('keycode');


        if (isset($keycode) && !empty($keycode)) {


            $result = $this->db->query("select * from offic where keycode= '" . $keycode . "'")->row();

            if ($result) {
                $data = $this->db->query("select id,offic_name,office_url from offic ")->result_array();

                $status = parent::HTTP_OK;

               // $response = ['status' => $status, 'token' => $keycode];

                $this->response($data,$status);
            } else {
                $this->response(['msg' => 'Invailed Token Name!'], parent::HTTP_NOT_FOUND);
            }
        } else {
            $this->response(['msg' => 'Invailed Token Name!'], parent::HTTP_NOT_FOUND);
        }
    }

    function get_token_post() {

       $keycode= $this->input->post('keycode');
       $offic_name= $this->input->post('office_name');

        if (isset($keycode) && !empty($keycode)) {

            $result = $this->db->query("select * from offic where keycode= '" . $keycode . "'")->row();

            if ($result) {

                $data = $this->db->query("select office_url,token from offic where offic_name= '" . $offic_name . "'")->row();

                $this->response($data, REST_Controller::HTTP_OK);
            } else {
                $this->response(['msg' => 'Invailed Token Name!'], parent::HTTP_NOT_FOUND);
            }
        } else {
            $this->response(['msg' => 'Invailed Token Name!'], parent::HTTP_NOT_FOUND);
        }
    }

    function update_office_name_post() {


        $keycode = $this->input->post('keycode');
        $new_office_name = $this->input->post('new_office_name');
        $old_office_name =$this->input->post('old_office_name');




        if (isset($keycode) && !empty($keycode)) {

            $result = $this->db->query("select * from offic where keycode= '" . $keycode . "'")->row();

            if ($result) {

                $result = $this->db->query("select * from offic where offic_name= '" . $old_office_name . "'")->row();

                $id = $result->id;


                if ($id) {
                    $new_key = $this->encode_string($new_office_name);
                    $data = array(
                        'offic_name' => $new_office_name,
                        'keycode' => $new_key
                    );
                    $this->db->where('id', $id)->update('offic', $data);
                    $result = $this->db->query("select * from offic where id= '" . $id . "'")->row();

                    $status = parent::HTTP_OK;

                   $this->response($result, $status);
                 
                }
            } else {
                $this->response(['msg' => 'Invailed Token Name!'], parent::HTTP_NOT_FOUND);
            }
        } else {
            $this->response(['msg' => 'Invailed Token Name!'], parent::HTTP_NOT_FOUND);
        }
    }

}

/* End of file Api.php */

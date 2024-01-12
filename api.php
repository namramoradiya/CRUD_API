<?php
require_once("Rest.inc.php");
//require_once("PaytmChecksum.php");

//global $welcom_point,$charges;
//$welcom_point = 80;
//$charges = 120;

class API extends REST
{

	public $data = "";
	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB = "prc";
	private $db = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->dbConnect();
	}

	private function dbConnect()    
	{
		$this->db = mysqli_connect(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
		$this->db->set_charset('utf8mb4');
	}
	
	public function processApi()
	{
		if (isset($_REQUEST['rquest']) && $_REQUEST['rquest'] != null) {
			$func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));

			if ((int) method_exists($this, $func) > 0)

				$this->$func();
			else
				$result = array(
					'status' => "0",
					'message' => "Something Wrong1"
				);


			$this->response($this->json($result), 404);
		} else {
			$result = array(
				'status' => "0",
				'message' => "Something Wrong"
			);
			$this->response($this->json($result), 404);
		}
	}
    private function insert()
	{
		if ($this->get_request_method() != "POST") {
			$this->response('', 406);
		}
		$Insert=json_decode($this->_request['details'],true);
		$result=array();
		$iname=$Insert['iname'];
		$uname=$Insert['uname'];
		$addresss=$Insert['address'];
		$contact=$Insert['contact'];
		$email=$Insert['email'];
		$dob=$Insert['dob'];
		$passwordd=$Insert['password'];

		$qry=mysqli_query($this->db,"INSERT INTO registrationn (iname,uname,address,contact,email,dob,password) VALUES ('$iname', '$uname', '$addresss', '$contact', '$email', '$dob', '$passwordd')");
		if ($qry) {
			$result['message'] = "User Inserted successfully";
			$result['status'] = "1";
		} else {
		
			$result['message'] = "Failed to insert user.";
			$result['status'] = "0";
		}
		$this->response($this->json($result), 200);
	}
	private function delete()
	{
		if($this->get_request_method()!="POST")
		{
			$this->response('',406);
		}
		$delete = json_decode($this->_request['details'], true);
		$result=array();
		$id = mysqli_real_escape_string($this->db, $delete['cid']);
		$sql=mysqli_query($this->db,"DELETE FROM registrationn WHERE cid='{$id}'");
		if($sql)
		{
			$result['message']="User Deleted Successfully";
			$result['status']="1";
		}
		else
		{
			$result['message']="Error in Deleting User";
			$result['status']="0";
		}
		$this->response($this->json($result),200);
	}
	private function update()
	{
		if($this->get_request_method()!="POST")
		{
			$this->response('',406);
		}
		$update=json_decode($this->_request['details'],true);
		$result=array();
		$id=mysqli_real_escape_string($this->db,$update['cid']);
		$iname=$update['iname'];
		$uname=$update['uname'];
		$address=$update['address'];
		$contact=$update['contact'];
		$email=$update['email'];
		$dob=$update['dob'];
		$password=$update['password'];
		if($iname)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET iname='{$iname}' WHERE cid='{$id}'");
			
		}
		if($uname)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET uname='{$uname}' WHERE cid='{$id}'");
		}
		if($address)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET address='{$address}' WHERE cid='{$id}'");
		}
		if($contact)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET contact='{$contact}' WHERE cid='{$id}'");
		}
		if($email)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET email='{$email}' WHERE cid='{$id}'");
		}
		if($dob)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET dob='{$dob}' WHERE cid='{$id}'");
		}
		if($password)
		{
			$sql=mysqli_query($this->db,"UPDATE registrationn SET password='{$password}' WHERE cid='{$id}'");
		}
		if($iname && $uname && $address && $contact && $email && $dob && $password)
		{
		$sql=mysqli_query($this->db,"UPDATE registrationn SET iname='{$iname}',uname='{$uname}',address='{$address}',contact='{$contact}',email='{$email}',dob='{$dob}',password='{$password}' WHERE cid='{$id}'");
		}
		if($sql)
		{
			$result['message']="Records updated successfully";
			$result['status']="1";
		}
		else
		{
			$result['message']="Error in updating data";
			$result['status']="0";
		}
		$this->response($this->json($result),200);
	}
	/*private function display()
	{
		if($this->get_request_method()!="POST")
		{
			$this->response('',406);
		}
		$display=json_encode($this->_request["details"],true);
		$result=array();
		$this->response
	}*/
    private function json($data)
	{
		// $encrypt = PHP_AES_Cipher::encrypt(self::Mkey, self::Miv, json_encode($data));
		$data= array($data);
		$data['encrypt'] = $data;
		if (is_array($data)) {
			return json_encode($data);
			// return $data;
		}
	}
}
$api = new API;
$api->processApi();
<?php

class MY_Model extends CI_Model
{
	function __construct(){
		parent::__construct();
		// Load the Database Module REQUIRED for this to work.
		$this->load->database();//Without it -> Message: Undefined property: XXXController::$db
	}

	function get($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		#Query
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}		
		
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->row_array();
		} else {
			$result = $query->row();
		}
		$query->free_result();
		return $result;
	}

	function fetch($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $return_array = false)
	{
		
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}
		
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$this->db->limit($limit, $start);
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->result_array();
		} else {
			$result = $query->result();
		}
		$query->free_result();
		return $result;
	}

	function fetch_join($select = "*", $table = "", $where = "", $join_1 = "", $table_1 = "", $on_1 = "", $join_2 = "", $table_2 = "", $on_2 = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $distinct = false,$return_array = false)
	{
        $this->db->select($select);
		if(($join_1 == "INNER" || $join_1 == "LEFT" || $join_1 == "RIGHT") && $table_1 != "" && $on_1 != "")
		{
			$this->db->join($table_1, $on_1, $join_1);
		}
		if(($join_2 == "INNER" || $join_2 == "LEFT" || $join_2 == "RIGHT") && $table_2 != "" && $on_2 != "")
		{
			$this->db->join($table_2, $on_2, $join_2);
		}
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
            $this->db->order_by($order, $by);
		}
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$this->db->limit($limit, $start);
		}
		if($distinct == true)
		{
			$this->db->distinct();
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->result_array();
		} else {
            $result = $query->result();
		}
		$query->free_result();
		return $result;
	}

	function insert($table = "", $data)
	{
		return $this->db->insert($table, $data);
	}

	function update($table = "", $data, $where = "")
	{
    	if($where != "")
    	{
            $this->db->where($where);
    	}
		return $this->db->update($table, $data);
	}

	function delete($table = "", $where = "")
    {
		if($where != "")
    	{
            $this->db->where($where);
    	}
		return $this->db->delete($table);
    }
    
	function Upload($file='', $uploadDir='') {
		if($file['error']!=0 || empty($uploadDir)) return false;

		Newfolder($uploadDir);
		$tmp_name= $file['tmp_name'];
		$path_parts = pathinfo($file['name']);
		$path_parts['dirname'];
		$path_parts['basename'];
		$path_parts['extension'];
		$path_parts['filename'];
		
		//TODO: Lay extesion
		$ext = ".".strtolower($path_parts['extension']);
		$name = md5(uniqid(mt_rand())).'_'.time().$ext;

		if(move_uploaded_file($tmp_name, $uploadDir.$name)) {
			//Upload  thanh cong
			return $name;
		}else {
			return false;
		}//else
	}//Upload     

	function send_email($data= '') {

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Additional headers
		$headers .= 'To: '.$data['email']."\r\n";
		$headers .= "From: MaxGame <info@maxgame.vn> "."\r\n";;

		mail($data['email'], $data['subject'], $data['html'], $headers);

		return true;
	}

	function getSetting($slug=''){
		$this->db->select('*');
		if($slug!=''){
			$this->db->where('slug', $slug);
			$this->db->limit(1);
		}
		$query = $this->db->get('admin_nqt_settings');
		if($query->result()){
			return $query->row();
		}else{
			return false;
		}
	}
}
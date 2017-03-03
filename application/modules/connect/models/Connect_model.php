<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Connect_model extends MY_Model {
	private $module = 'connect';
	private $table = 'connect_user';
 	private $retval = array(
        'status'    =>  0,
        'msg'       =>  ''
    );
    /* BEGIN ADMINCP ------------------------*/
    function getsearchContent($record,$start=0, $flag=0){
		// pr($this->input->post(),1);
		$this->db->select('*');
		$this->db->order_by('t1.'.$this->input->post('func_order_by'), $this->input->post('order_by'));
		$this->db->where('t1.is_delete', 0);
		if($this->input->post('name')!=''){
			$this->db->where('(`t1.fullname` LIKE "%'.$this->input->post('name').'%" OR `t1.email` LIKE "%'.$this->input->post('name').'%" OR `t1.phone` LIKE "%'.$this->input->post('name').'%")');
		}	
		if($flag) return $this->db->count_all_results(PREFIX.$this->table.' AS t1');
		else{
			$start ? $start = ($start-1)*$record : $start;
			$this->db->select('t1.fullname, t1.email, t1.phone, t1.conn_location, t1.conn_date, t1.conn_time, t2.fullname as fr_name, t2.phone as fr_phone, t2.email as fr_email');
			$this->db->limit($record,$start);
			$this->db->join(PREFIX.'connect_friends AS t2', 't2.user_id = t1.id', 'left');			
			return $this->db->get(PREFIX.$this->table.' AS t1')->result();
		}
	}
	function saveManagement($data){
		$dataIns = array(
			'fullname'      => $data['fullname'], 
			'email'         => $data['email'], 
			'phone'         => $data['phone'],
			'conn_location' => $data['conn_location'],
			'conn_date'     => date("Y-m-d", strtotime($data['conn_date'])) ,
			'conn_time'     => $data['conn_time'],
			'waiting'       => $data['waiting'],
			'note'          => $data['note'],
		);
		$dataFriend = array(
			'fullname' => $data['fr_fullname'], 
			'phone'    => 	$data['fr_phone'],
			'email'    => $data['fr_email'],
		);
		if($this->input->post('hiddenIdAdmincp') == 0){
			$this->db->insert(PREFIX.$this->table, $dataIns);
			$id = $this->db->insert_id();
			$dataFriend['user_id'] = $id;
			$this->db->insert(PREFIX.'connect_friends', $dataFriend);
		}else{
			$this->db->where('id',$this->input->post('hiddenIdAdmincp'));
			$this->db->update(PREFIX.$this->table,$dataIns);
			$this->db->where('user_id',$this->input->post('hiddenIdAdmincp'));
			$this->db->update(PREFIX.'connect_friends',$dataFriend);
		}
		return "success";
	}
	function getDetailManagement($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$query = $this->db->get(PREFIX.$this->table);
		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	function getFriend($id){
		$this->db->select('*');
		$this->db->where('user_id',$id);
		$query = $this->db->get(PREFIX."connect_friends");
		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	/*--------------------- END ADMINCP ---------------------*/
	function processConnect($data){
		// VALIDATE USER 
		if(!isset($data['conn_location']) || !$data['conn_location'] || $data['conn_location'] > 2) {
			$this->retval['msg'] = "Vui lòng làm mới lại trình duyệt";
            return json_encode($this->retval);
		}
		if(!$data['fullname'] || $data['fullname'] == ''){
			$this->retval['msg'] = "Vui lòng nhập họ và tên của bạn";
            return json_encode($this->retval);
		}
		$phone = $data['phone'];
		if(substr($phone,0,2) == '84') $phone = substr_replace($phone,'0',0,2);
        if(substr($phone,0,3) == '084') $phone = substr_replace($phone,'0',0,3);		
        if(!$this->validateNumberic($phone)){
            $this->retval['msg'] = "Định dạng số điện thoại không chính xác";
            return json_encode($this->retval);
        }
        $email = $data['email'];
        if(!$this->validateEmail($email)){
            $this->retval['msg'] = "Địa chỉ email không hợp lệ";
            return json_encode($this->retval);
        }
        // VALIDATE FRIEND
        if(!$data['fr_fullname'] || $data['fr_fullname'] == ''){
        	$this->retval['msg'] = "Vui lòng nhập họ và tên của người thân";
            return json_encode($this->retval);
        }
        $fr_phone = $data['fr_phone'];
        if(substr($fr_phone,0,2) == '84') $fr_phone = substr_replace($fr_phone,'0',0,2);
        if(substr($fr_phone,0,3) == '084') $fr_phone = substr_replace($fr_phone,'0',0,3);		
        if(!$this->validateNumberic($fr_phone)){
            $this->retval['msg'] = "Định dạng số điện thoại người thân chính xác";
            return json_encode($this->retval);
        }
        $fr_email = $data['fr_email'];
        if(!$this->validateEmail($fr_email)){
            $this->retval['msg'] = "Địa chỉ email không hợp lệ";
            return json_encode($this->retval);
        }

        // VALIDATE TIME
        if(!$data['conn_date']) {
        	$this->retval['msg'] = "Vui lòng chọn ngày kết nối";
            return json_encode($this->retval);
        }
        if(!$data['conn_time']) {
        	$this->retval['msg'] = "Vui lòng chọn thời gian tham gia";
            return json_encode($this->retval);
        }
        // VALIDATE TRUNG NHAU
        $sql = "SELECT * FROM `".PREFIX."connect_user` AS t1 LEFT JOIN ".PREFIX."connect_friends AS t2 ON t2.user_id=t1.id WHERE t1.email='".$data['email']."' AND t1.phone='".$data['phone']."' AND t2.email = '".$data['fr_email']."' AND t2.phone='".$data['fr_phone']."'";
        $checkData = $this->db->query($sql);
        if($checkData->num_rows() > 0){
        	$this->retval['msg'] = "Số điện thoại/Email bị trùng, hãy nhập số điện thoại/email người thân tại cổng kết nối";
            return json_encode($this->retval);
        }
        // pr($sql,1);
        $dataUser = array(
			'fullname'      => $data['fullname'],
			'email'         => $data['email'],
			'phone'         => $data['phone'],
			'conn_location' => $data['conn_location'],
			'conn_time'     => $data['conn_time'],
			'note'          => $data['note'],
			'conn_date'     => date("Y-m-d", strtotime($data['conn_date'])),
			'created_at'    => date("Y-m-d H:i:s")
    	);
        //VALIDATE FULL TIME
     //    if(!isset($data['waiting'])){
     //    	// pr($data);
	    //     $numUser = $this->db->where('conn_time', $data['conn_time'])->where("DATE(`conn_date`)", $data['conn_date'])->get(PREFIX.$this->table);
	    //     if($numUser->num_rows() >= 12){
	    //     	$this->retval['status'] = -1;
	    //     	$this->retval['msg'] = 'Xin cảm ơn bạn đã đăng ký tham gia. </br> Nhưng thật tiếc là khung giờ bạn chọn hiện đã đầy. Bạn có muốn tiếp tục vào danh sách chờ? . <br> Ban Tổ Chức sẽ liên lạc với bạn khi khung giờ trống trở lại!';
	    //         return json_encode($this->retval);
	    //     }
	    // }else{
	    // 	$dataUser['waiting'] = $data['waiting'];
	    // }
        // VALIDATE ISSET        
    	$this->db->insert(PREFIX.$this->table, $dataUser);
    	$userID = $this->db->insert_id();
    	if(!$userID){
    		$this->retval['msg'] = "Hệ thống đang bận, vui lòng đăng ký lại sau";
            return json_encode($this->retval);
    	}
    	$dataFriend = array(
			'fullname' => $data['fr_fullname'],
			'phone'    => $data['fr_phone'],
			'email'    => $data['fr_email'],
			'user_id'  => $userID,
			'created_at' => date("Y-m-d H:i:s")
		);
		$this->db->insert(PREFIX.'connect_friends', $dataFriend);
		$this->retval['status'] = 1;
		$this->retval['msg'] = "tham-gia-ket-noi-tieng-cuoi?name=".$data['fullname']."&location=".$data['conn_location']."&date=".$data['conn_date']."&time=".$data['conn_time'];
        return json_encode($this->retval);
	}
	function validateEmail($email){
        $email = trim(strtolower($email));
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
       	else return true;
    }
    function validateNumberic($number){
        if(is_numeric($number)){
            if(strlen($number) >= 9 && strlen($number) <= 13) return true;
            else return false;
        }
        else return false;
    }
}

?>
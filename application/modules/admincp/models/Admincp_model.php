<?php
class Admincp_model extends MY_Model
{
    function checkLogin($user)
    {
        $this->db->select('*');
        $this->db->where('username', $user);
        $this->db->where('status', 1);
        $query = $this->db->get('admin_ops_users');
        foreach ($query->result() as $row) {
            $pass = $row->password;
        }
        if (!empty($pass)) {
            return $pass;
        } else {
            return false;
        }
    }

    function getInfo($user)
    {
        $this->db->select('*');
        $this->db->where('username', $user);
        $this->db->where('status', 1);
        $query = $this->db->get('admin_nqt_users');

        if ($query->result()) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getSetting($slug = '')
    {
        $this->db->select('*');
        if ($slug != '') {
            $this->db->where('slug', $slug);
            $this->db->limit(1);
        }
        $query = $this->db->get('admin_nqt_settings');

        if ($query->result()) {
            $data = $query->result();
            if (!$slug) {
                $retval = array();
                foreach ($data as $key => $value) {
                    $retval[$value->slug] = $value->content;
                }
            } else
                $retval = $data;
            return $retval;
        } else {
            return false;
        }
    }

    function getListCoin()
    {
        $this->db->select('*');
        $this->db->where('slug LIKE "%coin%"');
        $query = $this->db->get('admin_nqt_settings');

        if ($query->result()) {
            return $query->result();
        } else {
            return false;
        }
    }

    function checkSlug($slug)
    {
        $this->db->select('id');
        $this->db->where('slug', $slug);
        $this->db->limit(1);
        $query = $this->db->get('admin_nqt_settings');

        if ($query->result()) {
            return $query->result();
        } else {
            return false;
        }
    }

    function reportRegister($date = '')
    {
        $this->db->select('id');
        if ($date != '') {
            $this->db->like('created', $date);
        }
        $query = $this->db->count_all_results(PREFIX . 'web_users');
        return $query;
    }


    function getRegisterChart($days, $getdate = '')
    {
        $data = array();
        $query = "SELECT DATE(created) as date,COUNT(id) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff
					FROM cli_web_users
					WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND referer = '' AND utm_source = '0'
					GROUP BY DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY))
					ORDER BY diff ASC
					";
        $tmp1 = $this->db->query($query)->result();

        $query = "SELECT DATE(created) as date,COUNT(id) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff
					FROM cli_web_users
					WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND (referer != '' OR utm_source != '0')
					GROUP BY DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY))
					ORDER BY diff ASC
					";
        $tmp2 = $this->db->query($query)->result();
        $query = "SELECT DATE(created) as date,COUNT(id) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff
					FROM cli_web_users
					WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND utm_campaign != '0'
					GROUP BY DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY))
					ORDER BY diff ASC
					";
        $tmp3 = $this->db->query($query)->result();

        $data = array($tmp1, $tmp2, $tmp3);
        return $data;
    }
     function getRegisterChartByIP($days, $getdate = '', $ip = '')
    {
      
        $ip = '';
        if($ip != '')
        {
            $where = "'AND registerip = '$ip'";
        }
        //pr($ip,1);
        //$ip = 'id';
     
        $data = array();
        // $query = "SELECT DATE(created) as date, COUNT( DISTINCT registerip ) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY))
        //     FROM cli_web_users
        //     WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND utm_campaign != '0'
        //     GROUP BY  date
        //     ORDER BY  registerip ASC
        //     ";
        // $tmp1 = $this->db->query($query)->result();

        $query = "SELECT DATE(created) as date, COUNT( DISTINCT `registerip` ) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff
            FROM cli_web_users
            WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND referer = '' AND utm_source = '0'
            GROUP BY  date
            ORDER BY diff ASC
            ";
        $tmp1 = $this->db->query($query)->result();

        $query = "SELECT DATE(created) as date, COUNT( DISTINCT registerip ) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff
            FROM cli_web_users
            WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND (referer != '' OR utm_source != '0')
            GROUP BY  date
            ORDER BY diff ASC
            ";
        $tmp2 = $this->db->query($query)->result();
        $query = "SELECT DATE(created) as date, COUNT( DISTINCT registerip ) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff
            FROM cli_web_users
            WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND utm_campaign != '0'
            GROUP BY  date
            ORDER BY diff ASC
            ";
        $tmp3 = $this->db->query($query)->result();

        //pr($tmp2,1);
        $data = array($tmp1, $tmp2, $tmp3);
        return $data;
    }



    function reportReferrer($days, $getdate = '')
    {
        //Lấy tông số lượt người đăng ký trong 20 ngày.
        $query = "SELECT DATE(created) as date,COUNT(id) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff, utm_campaign
					FROM cli_web_users
					WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND utm_campaign != '0' AND utm_campaign != ''
					GROUP BY diff,utm_campaign
					ORDER BY total DESC
					";
        $tmp3 = $this->db->query($query)->result();
        // Lấy tông số người đăng ký theo nguồn giới thiệu trong giới hạn thời gian $dat.
        $query = "SELECT DATE(created) as date,COUNT(id) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff, referer
					FROM cli_web_users
					WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND referer != ''
					GROUP BY diff,referer
					ORDER BY total DESC
					";
        $tmp4 = $this->db->query($query)->result();
        //Lấy tổng số người đăng ký có nguồn giới thiệu từ đâu.
        $query = "SELECT DATE(created) as date,COUNT(id) as total, DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) as diff, utm_source
					FROM cli_web_users
					WHERE DATEDIFF(DATE(created),DATE_SUB('$getdate', INTERVAL $days DAY)) > 0 AND utm_source != '0'
					GROUP BY diff,utm_source
					ORDER BY total DESC
					";
        $tmp5 = $this->db->query($query)->result();

        $data = array(
            'a_campaign' => $tmp3,
            'a_source' => $tmp5,
            'a_referer' => $tmp4
        );
        return $data;
    }


    function getReportPR($dStart, $dEnd)
    {
        error_reporting(E_ALL);
        $dateStart = date('Y-m-d H:i:s', $dStart);
        $dateEnd = date('Y-m-d H:i:s', $dEnd);
        $data = $this->fetch('utm_medium, utm_source,created', PREFIX . "web_users", "created > '{$dateStart}' AND created < '{$dateEnd}'");
        $final = array();
        // pr($data,1);
        if($data){
            foreach ($data as $key => $value) {
                $utm_medium = !empty($value->utm_medium) ? $value->utm_medium : 'not_set';
                $utm_source = !empty($value->utm_source) ? $value->utm_source : 'not_set';
                $text = date('d-m', strtotime($value->created));
                if(!array_key_exists($utm_medium, $final)){
                    $final[$utm_medium][$utm_source][$text] = 1;
                }
                else{
                    if(!array_key_exists($utm_source, $final[$utm_medium])){
                        $final[$utm_medium][$utm_source][$text] = 1;
                    }
                    else{
                        if(!array_key_exists($text, $final[$utm_medium][$utm_source])){
                            $final[$utm_medium][$utm_source][$text] = 1;
                        }
                        else{
                            $final[$utm_medium][$utm_source][$text] ++;   
                        }
                    }   
                }
            }
        }
        // pr($final,1);
        return $final;
    }
    function getReportRegis($startDate = 0, $endDate = 0){
        $xahoi = 0;
        $tructiep = 0;
        $tong = 0;
        $this->db->select('id');
        if($startDate != 0 AND $endDate != 0){
            $this->db->where("date(`created`) >= '$startDate' AND date(`created`) <= '$endDate'");
        }
        $query = $this->db->get(PREFIX.'web_users');
        // pr(last_query(),1);
        if($query->num_rows > 0){
            $tong = $query->num_rows;
        }

        $this->db->select('id');
        $this->db->where("username LIKE '%@%'");
        if($startDate != 0 AND $endDate != 0){
            $this->db->where("date(`created`) >= '$startDate' AND date(`created`) <= '$endDate'");
        }

        $query = $this->db->get(PREFIX.'web_users');
        if($query->num_rows > 0){
            $xahoi = ( $query->num_rows / $tong )*100;
        }

        $tructiep = 100 - $xahoi;
        // pr(array('tong'=>$tong,'tructiep'=>$tructiep,'xahoi'=>$xahoi),1 );
        return array('tong'=>$tong,'tructiep'=>$tructiep,'xahoi'=>$xahoi);
    }
    function countIp(){
        return $this->db->query("SELECT  `registerip` , COUNT(  `registerip` ) FROM  `".PREFIX."web_users` GROUP BY  `registerip`")->num_rows();
    }
}
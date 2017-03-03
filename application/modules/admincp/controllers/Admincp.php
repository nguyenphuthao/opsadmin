<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Admincp extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->uri->segment(2) != 'login') {
            if (!$this->session->userdata('userInfo')) {
                header('Location: ' . PATH_URL . 'admincp/login');
                return false;
            }
        }
        // if(!is_local()){
        //     redirect(PATH_URL);
        // }
        $this->load->model('admincp_model', 'model');
        // $this->template->set_template('admin');
        // $this->template->write('title', 'Admin Control Panel');
    }
    
    function index()
    {
        redirect(PATH_URL.'admincp/connect');
        // set_time_limit(0);
        // ini_set('memory_limit', '300M');
        // $d    = new DateTime();
        // $days = '';
        // $day  = $d->format('d-m');
        // //pr($day,1);
        // for ($i = 0; $i < 20; $i++) {
        //     if ($i > 0) {
        //         $d->modify('-1 day');
        //         if ($i + 1 < 20)
        //             $days = "'" . $d->format('d-m') . "'," . $days;
        //         else
        //             $days = "'" . $d->format('d-m') . "'," . $days;
        //     }
        // }
        // $days .= "'" . $day . "'";
        // $data        = array(
        //     'data' => $this->model->getRegisterChart(20, date('Y-m-d')),
        //     'dataByIP' => $this->model->getRegisterChartByIP(20, date('Y-m-d')),
        //     'days' => $days
        // );
        // $reportRef   = $this->model->reportReferrer(20, date('Y-m-d'));
        // $source_name = array();
        // foreach ($reportRef['a_source'] as $key => $item) {
        //     if (isset($item->utm_source))
        //         if (!in_array($item->utm_source, $source_name))
        //             $source_name[] = $item->utm_source;
        // }
        // //pr($reportRef,1);
        // $ref_name = array();
        // foreach ($reportRef['a_referer'] as $key => $item) {
        //     if (isset($item->referer))
        //         if (!in_array($item->referer, $ref_name))
        //             $ref_name[] = $item->referer;
        // }
        // $refName       = array_merge($source_name, $ref_name);
        // $campaign_name = array();
        // foreach ($reportRef['a_campaign'] as $key => $item) {
        //     if (isset($item->utm_campaign))
        //         if (!in_array($item->utm_campaign, $campaign_name))
        //             $campaign_name[] = $item->utm_campaign;
        // }
        // $data['reportRef'] = array_merge($reportRef['a_source'], $reportRef['a_referer']);
        // $data['a_camp']    = $reportRef['a_campaign'];
        // $data['refName']   = $refName;
        // $data['campName']  = $campaign_name;
        // $dEnd              = strtotime(date('Y-m-d 23:59:59'));
        // $dStart            = $dEnd - 24 * 3600 * 20 + 1;
        // $data['reportPR']  = $this->model->getReportPR($dStart, $dEnd);
        // //pr($data['reportPR']);
        // //pr($refName);
        // $data['reportReg'] = $this->model->getReportRegis();
        // $data['module']    = 'admincp';
        // $data['reg_today'] = $this->model->reportRegister(date('Y-m-d'));
        // $data['reg_full']  = $this->model->reportRegister();
        $data['title'] = "Admin Control Panel";
        // $data['countIP'] = $this->model->countIp();
        $this->blade->render('main', $data);
        // $this->template->write_view('content', 'index', $data);
        // $this->template->render();
    }
    function statistics()
    {
        $endDate   = $this->input->post('endDate');
        $startDate = $this->input->post('startDate');
        $days      = '';
        $numstatis = tim_so_ngay($startDate, $endDate);
        $day       = strtotime($endDate);
        $date      = date('d-m', $day);
        for ($i = 0; $i < $numstatis; $i++) {
            if ($i > 0) {
                $day = strtotime('-1 day', $day);
                if ($i + 1 < $numstatis)
                    $days = "'" . date('d-m', strtotime('-1 day', $day)) . "'," . $days;
                else
                    $days = "'" . date('d-m', strtotime('-1 day', $day)) . "'," . $days;
            }
        }
        $days .= "'" . $date . "'";
        //pr($days,1);
        /*list($year, $month, $day) = explode('-', $endDate);
        
        $date = ($day.'-'.$month);
        
        //echo $numstatis.'and';
        
        for ($i = 0; $i < $numstatis ; $i++) {
        
        if ($i > 0) {
        
        if ($i + 1 < $numstatis)
        
        $days = "'" . (--$day)."-".$month . "'," . $days;
        
        else
        
        $days = "'" . (--$day)."-".$month  . "'," . $days;
        
        }
        
        }
        
        $days .= "'" . $date . "'";
        
        //pr($days,1);
        
        */
        $data        = array(
            'data' => $this->model->getRegisterChart($numstatis, $endDate),
            'days' => $days
        );
        $reportRef   = $this->model->reportReferrer($numstatis, $endDate);
        $source_name = array();
        foreach ($reportRef['a_source'] as $key => $item) {
            if (isset($item->utm_source))
                if (!in_array($item->utm_source, $source_name))
                    $source_name[] = $item->utm_source;
        }
        //pr($source_name,1);
        $ref_name = array();
        foreach ($reportRef['a_referer'] as $key => $item) {
            if (isset($item->referer))
                if (!in_array($item->referer, $ref_name))
                    $ref_name[] = $item->referer;
        }
        $refName       = array_merge($source_name, $ref_name);
        //pr($refName,1);
        $campaign_name = array();
        foreach ($reportRef['a_campaign'] as $key => $item) {
            if (isset($item->utm_campaign))
                if (!in_array($item->utm_campaign, $campaign_name))
                    $campaign_name[] = $item->utm_campaign;
        }
        $data['reportRef'] = array_merge($reportRef['a_source'], $reportRef['a_referer']);
        $data['a_camp']    = $reportRef['a_campaign'];
        $data['refName']   = $refName;
        $data['campName']  = $campaign_name;
        // pr($refName);
        // pr($campaign_name,1);
        $data['reportReg'] = $this->model->getReportRegis($startDate, $endDate);
        $data['module']    = 'admincp';
        $data['reg_today'] = $this->model->reportRegister($endDate);
        $data['reg_full']  = $this->model->reportRegister();
        $data['num']       = $numstatis;
        $data['endDate']   = $endDate;
        //pr($data['endDate'],1);
        //echo $endDate . 'and' . $startDate;
        return $this->load->view('ajax_content', $data, NULL);
    }
    function login()
    {
        if (!empty($_POST)) {
            if (md5($this->input->post('password')) == $this->model->checkLogin($this->input->post('username'))) {
                $this->session->set_userdata('userInfo', $this->input->post('username'));
                if (!session_id())
                    session_start();
                setcookie('admineditor', $this->input->post('username'), time() + 64000, '/');
                print 1;
            } else {
                print 0;
            }
        } else {
            $this->blade->render('login');
        }
    }
    function logout()
    {
        $this->session->unset_userdata('userInfo');
        header('Location: ' . PATH_URL . 'admincp/login');
    }
    function permission()
    {
        $data['module'] = 'admincp';
        $this->template->write_view('content', 'permission', $data);
        $this->template->render();
    }
    function chk_perm($id_module, $type, $isAjax)
    {
        $this->load->model('admincp_accounts/admincp_accounts_model');
        $this->load->model('admincp/admincp_model');
        $info = $this->admincp_model->getInfo($this->session->userdata('userInfo'));
        $pos  = strpos($info[0]->permission, ',' . $id_module . '|');
        if ($pos != 0) {
            $pos = $pos + strlen($id_module);
        } else {
            $pos = 0;
        }
        $sub_str    = substr($info[0]->permission, $pos, 5);
        $pos_result = strpos($sub_str, $type);
        if ($pos_result === false) {
            if ($isAjax == 0) {
                header('Location: ' . PATH_URL . 'admincp/permission');
                exit();
            } else {
                return 'permission-denied';
                exit;
            }
        }
    }
    function saveLog($func, $func_id, $field, $type, $old_value = '', $new_value = '')
    {
        if ($field != '') {
            $data = array(
                'function' => $func,
                'function_id' => $func_id,
                'field' => $field,
                'type' => $type,
                'old_value' => $old_value,
                'new_value' => $new_value,
                'account' => $this->session->userdata('userInfo'),
                'ip' => getIP(),
                'created' => date('Y-m-d H:i:s')
            );
            $this->db->insert('admin_nqt_logs', $data);
        } else {
            foreach ($new_value as $k => $v) {
                if ($v != $old_value[0]->$k) {
                    $data = array(
                        'function' => $func,
                        'function_id' => $func_id,
                        'field' => $k,
                        'type' => $type,
                        'old_value' => $old_value[0]->$k,
                        'new_value' => $v,
                        'account' => $this->session->userdata('userInfo'),
                        'ip' => getIP(),
                        'created' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('admin_nqt_logs', $data);
                }
            }
        }
    }
    function update_profile()
    {
        if (!empty($_POST)) {
            if (md5($this->input->post('oldpassAdmincp')) == $this->model->checkLogin($this->session->userdata('userInfo'))) {
                $data = array(
                    'username' => $this->session->userdata('userInfo'),
                    'password' => md5($this->input->post('newpassAdmincp'))
                );
                $this->db->where('username', $this->session->userdata('userInfo'));
                if ($this->db->update('admin_nqt_users', $data)) {
                    $this->load->model('admincp_accounts/admincp_accounts_model');
                    $userInfo = $this->admincp_accounts_model->getData($this->session->userdata('userInfo'));
                    $this->saveLog('update_profile', $userInfo[0]->id, 'password', 'Update', $this->input->post('oldpassAdmincp'), $this->input->post('newpassAdmincp'));
                    print 'success_update_profile';
                    exit;
                }
            } else {
                print 'error_update_profile';
                exit;
            }
        } else {
            $this->template->write_view('content', 'update_profile');
            $this->template->render();
        }
    }
    function setting()
    {
            if (!empty($_POST)) {
                $content = $this->input->post('contentAdmincp');
                foreach ($content as $k => $v) {
                    $chk_slug = $this->model->checkSlug($k);
                    if ($chk_slug) {
                        $data = array(
                            'content' => $v,
                            'modified' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('id', $chk_slug[0]->id);
                        $this->db->update('admin_nqt_settings', $data);
                    } else {
                        $data = array(
                            'slug' => $k,
                            'content' => $v,
                            'modified' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('admin_nqt_settings', $data);
                    }
                }
                print 'success-setting';
                exit;
            } else {
                $data['list_server'] = $this->model->fetch('*', PREFIX . 'servers');
                $data['setting']     = $this->model->getSetting();
                $this->template->write_view('content', 'setting', $data);
                $this->template->render();
            }
      
    }
    function getSetting($slug = '')
    {
        $this->load->model('admincp_model');
        $data['setting'] = $this->admincp_model->getSetting($slug);
        $this->load->view('getSetting', $data);
    }
    function post_security(){
        if($this->input->post()){
            if(md5($this->input->post('pwgt')) == get_security()){
                $this->session->set_userdata('pwgt', get_security());
                redirect(PATH_URL.'admincp/setting');
            }else{
                $this->load->view('security_gt');
            }
        }
        else $this->load->view('security_gt');
    }
}
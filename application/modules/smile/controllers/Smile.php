<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    require(APPPATH.'/third_party/facebook-php-sdk-v5/autoload.php');
class Smile extends MX_Controller
{
    private $module = 'smile';
    private $fb = '';
    private $config = array(
        'app_id' => FB_CLIENT_ID,
        'app_secret' => FB_CLIENT_SECRET,
        'default_graph_version' => 'v2.8',
      //'default_access_token' => '{access-token}', // optional
    );
    function __construct() {
        parent::__construct();
        $this->load->model($this->module . '_model', 'model');
    }
    
    // BƯỚC 1.
    function index()
    {
        if(!$this->session->userdata('userData')) redirect(PATH_URL.'thu-vien-tieng-cuoi');
        $num = $this->db->get(PREFIX.'connect_user');
        $userData = $this->session->userdata('userData');
        if(!$userData) {echo -1; exit;}
        $user_id = $userData['user_id'];
        $data['oauth_uid'] = $userData['oauth_uid'];
        $data['title'] = "Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn";
        $data['friends'] = $this->model->getFriendsList($user_id, 4, 0, "");
        $this->blade->render('FRONTEND.index', $data);
    }
    function ajaxGetFriendSearch(){
        $userData = $this->session->userdata('userData');
        if(!$userData) {echo -1; exit;}
        $user_id = $userData['user_id'];
        $name = $this->input->post('name');
        if(!$name) $name = " ";
        $record = 4;
        $start = 0;
        $friends = array();
        $friends = $this->model->getFriendsList($user_id, $record, $start, $name);
        // if($friends)
        //     foreach ($friends as $key => $value) {
        //         if(!$value->picture_url){
        //             $picture_url = $this->getImageFriend($value->oauth_uid); $id = $value->id;
        //             $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
        //             $value->picture_url = $picture_url;
        //             $friends[$key] = $value;
        //         }
        //     }
        $data['friends'] = $friends;
        $this->blade->render('FRONTEND.ajax_get_friends', $data);
    }
    // BƯỚC 2. TẠO VIDEO DEMO.
    function createVideoDemo(){
        header('Access-Control-Allow-Origin: *');
        $rs = array('path'=>0, 'idInsert'=> 0, 'status' => 0, 'msg'=>'Vui lòng đăng nhập');
        $userData = $this->session->userdata('userData');
        if(!$userData) {
            $rs['status'] = -1;
            echo json_encode($rs);
            exit;
        }
        $rs['oauth_uid'] = $userData['oauth_uid'];
        $friends = $this->input->post('friends');
        if(!$friends){ $rs['msg'] = "Vui lòng chọn bạn đề cử"; echo json_encode($rs); exit;}
        $user_id = $userData['user_id'];
        $picture_url = $this->getImageFriend($userData['oauth_uid']);        
        $path = 'public/_data/images/'. $userData['oauth_uid'].'/avatar';
        $avatar_user = storeImageToUrl($picture_url, $path);
        $this->model->update(PREFIX.'users', array('avatar_image' => $avatar_user), "id = $user_id");    
        
        $dataFriend = $this->db->where_in('oauth_uid', $friends)->where('user_id', $user_id)->get(PREFIX.'friends_list');
        if($dataFriend->num_rows() == 0){
            $rs['msg'] = "Vui lòng chọn bạn đề cử"; echo json_encode($rs); exit;
        }
        $dataFriend = $dataFriend->result();
        $arrayAvatar = array(); array_push($arrayAvatar, $avatar_user);
        // $arrayA = array($userData['name'] => $avatar_user);
        $sql_like = $friends_list = '';
        if(count($dataFriend) > 2) {$rs['msg'] = "Số lượng bạn quá lớn "; echo json_encode($rs); exit;}
        foreach ($dataFriend as $key => $value) { 
            $friends_list .= $value->oauth_uid;
            if(!$value->avatar_image){
                $id = $value->id;
                if(!$value->picture_url){
                    $picture_url = $this->getImageFriend($value->oauth_uid);
                    $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
                }else{
                    $picture_url = $value->picture_url;
                }
                $avatar = storeImageToUrl($picture_url, $path);
                $this->model->update(PREFIX.'friends_list', array('avatar_image' => $avatar), "id = $id");
            }else{
                $avatar = $value->avatar_image;
            }
            // $arrayA[$value->name] = $avatar;
            array_push($arrayAvatar, $avatar);
            $sql_like .= "friends_list like '%".$value->oauth_uid."%'";
            if(count($dataFriend) > 1 && $key < 1) $sql_like .= ' AND ';
        }
        $result = $this->model->fetch('friends_list, images_large, images_id, created_time, comments_count, likes_count', PREFIX.'images_tags', "user_id = $user_id AND $sql_like",'likes_count, comments_count',"DESC");
        $total_like = 0;
        foreach ($result as $value) {
            $total_like += $value->comments_count + $value->likes_count;
        }        
        /* -------------- KIỂM TRA SỐ LƯỢNG HÌNH NẾU NHỎ HƠN THÌ RETURN --------------------*/
        if(count($result) < 8) {
            $desiredLength = 8;
            $newArray = array();
            while(count($newArray) <= $desiredLength){
                $newArray = array_merge($newArray, $result);
            }
            $arrayTmp = array_slice($newArray, 0, $desiredLength);
            $result = $arrayTmp;
        }else{
            array_slice($result, 0, 8);
        }
        array_slice($result, 0, 8);
        $resultTmp = (array) $result;
        $rs['result'] = json_encode($resultTmp);

        $dataImage = array('user_id' => $user_id, 'friends_list' => $friends_list, 'status' => 1, 'created_at' => date("Y-m-d H:i:s"));
        $this->db->insert(PREFIX.'images_select', $dataImage);
        $idInsert = $this->db->insert_id();
        $path = "public/_data/_delete/".$idInsert.'/';
        $pathfinal = $path.'final'; 
        Newfolder($pathfinal);
        recurse_copy("public/frames_final", $path.'final');
        /* ------------------ FRAME 1 ------------------*/
        $pathtmp = $path.'tmp/';
        Newfolder($pathtmp);
        if(count($arrayAvatar) > 3) $arrayAvatar = array_slice($arrayAvatar, 0, 3);
        $rs['arrayAvatar'] = json_encode($arrayAvatar);

        // // -------------- FRAME TEXT 1 -------------------------*/
        $total = $total_like;//$this->model->getTotalUser();
        $left = 130;
        if($total < 10) $left = 223;
        if($total > 9 && $total < 100) $left = 230;
        if($total > 99 && $total < 1000) $left = 160;
        if($total > 999 && $total < 10000) $left = 150;
        for($i = 504; $i <= 547; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->watermarkText($sourcefile, $total, $left, 330, 80, "#6f1625");
        }
        

        // // -------------- FRAME TEXT 2 -------------------------*/     
        
        // if($userData['name'] <= 12) $left = 130;
        // else if($userData['name'] > 12 && $userData['name'] <= 20) $left = 150;
        // else $left = 170;
        // for($i = 48; $i <= 73; $i++){
        //     $sourcefile = $path.'final/thumb00'.$i.'.png';
        //     $this->watermarkText($sourcefile, v2e($userData['name']), 130, 250, 16, "#ffffff");
        //     $this->watermarkText($sourcefile, v2e($dataFriend[0]->name), 260, 250, 16, "#ffffff");
        //     $this->watermarkText($sourcefile, v2e($dataFriend[0]->name), 160, 410, 24, "#ffffff");
        // }

        $friend = $dataFriend[0]->name;
        if(strlen($friend) <= 12) $left = 180;
        else if(strlen($friend) > 12 && strlen($friend) <=20) $left = 160;
        else $left = 130;
        for($i = 48; $i <= 73; $i++){
        $sourcefile = $path.'final/thumb00'.$i.'.png';
            $this->watermarkText($sourcefile, v2e($userData['name']), 130, 250, 16, "#ffffff");
            $this->watermarkText($sourcefile, v2e(substr($friend, strlen($friend)-20)), 260, 250, 16, "#ffffff");
            $this->watermarkText($sourcefile, v2e($friend), $left, 410, 24, "#ffffff");
        }


        $path = 'public/_data/images/'. $userData['oauth_uid'];
        $rs['idInsert']  = $idInsert;
        $rs['status']    = 1;
        $rs['msg']       = "Tạo thành công";
        echo json_encode($rs);
        exit;
    }
    
    function createImageFrame(){
        header('Access-Control-Allow-Origin: *');        
        $frame = $this->input->get('frame');
        $waterfile = $this->input->get('sourcefile');
        $created_time = date("d/m/Y", strtotime($this->input->get('created_time')));
        $idInsert = $this->input->get('idInsert');
        $path = "public/_data/_delete/".$idInsert.'/';

        switch ($frame) {
            case 1:
                $width = 290; $height = 290; $degrees = 6; $position = 86; $ofset = 71;                
                $pathlib = $path.'tmp/2.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 135; $i <= 171; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 1;
                break;
            case 2:
                $width = 290; $height = 290; $degrees = 0; $position = 99; $ofset = 82;
                $pathlib = $path.'tmp/3.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 172; $i <= 204; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 2;
                break;
            case 3:
                $width = 290; $height = 290; $degrees = 5.5; $position = 76; $ofset = 79;
                $pathlib = $path.'tmp/4.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 205; $i <= 237; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 3;
                break;
            case 4:
                $width = 300; $height = 300; $degrees = 2.8; $position = 85; $ofset = 76;
                $pathlib = $path.'tmp/5.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 334; $i <= 365; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 4;
                break;
            case 5:
                $width = 300; $height = 300; $degrees = -4.8; $position = 77; $ofset = 62;
                $pathlib = $path.'tmp/6.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 366; $i <= 399; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 5;
                break;
            case 6:
                $width = 300; $height = 300; $degrees = 2.4; $position = 84; $ofset = 75;
                $pathlib = $path.'tmp/7.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 400; $i <= 434; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 6;
                break;
            case 7:
                $width = 300; $height = 304; $degrees = 0.8; $position = 94; $ofset = 75;
                $pathlib = $path.'tmp/8.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 435; $i <= 469; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 7;
                break;
            case 8:
                $width = 300; $height = 300; $degrees = -5; $position = 92; $ofset = 65;
                $pathlib = $path.'tmp/9.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 470; $i <= 503; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 8;
                break;
            case 9:
                $arrayAvatar= $this->input->get('sourcefile');
                $arrayAvatar = json_decode($arrayAvatar);                
                $pathtmp = $path.'tmp/';
                if(count($arrayAvatar) == 2){
                    $fileTmp1 = $pathtmp.'1_1.png';
                    $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 0, $fileTmp1);
                    $fileTmp2 = $pathtmp.'1_2.png';
                    $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], 0, $fileTmp2);
                    // CHAY FOR DE TAO NHIU HINH
                    for($i = 51; $i <= 73; $i++){
                        $sourcefile = $path.'final/thumb00'.$i.'.png';
                        $this->libWarterImage($sourcefile, $imagelib1, 161, 258);
                        $this->libWarterImage($sourcefile, $imagelib2, 260, 258);
                    }
                }
                if(count($arrayAvatar) == 3){
                    $fileTmp1 = $pathtmp.'1_1.png';
                    $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 9, $fileTmp1);
                    $fileTmp2 = $pathtmp.'1_2.png';
                    $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], -9, $fileTmp2);
                    $fileTmp3 = $pathtmp.'1_3.png';
                    $imagelib3 = $this->createImageLibFirst($arrayAvatar[2], -9, $fileTmp3);
                    // CHAY FOR DE TAO NHIU HINH
                    $sourcefile = $path.'final/thumb0002.png';
                    $this->libWarterImage($sourcefile, $imagelib1, 28, 228);
                    $this->libWarterImage($sourcefile, $imagelib2, 191, 230);
                    $this->libWarterImage($sourcefile, $imagelib3, 352, 226);
                }
                $userData = $this->session->userdata('userData');
                if($userData){
                    $dest = "public/_data/images/".$userData['oauth_uid'].'/'.$idInsert.'/poster/';
                    Newfolder($dest);
                    $dest .= "thumb0016.png";
                    copy($path.'final/thumb0016.png', $dest);
                }
                echo 9;
                break;
            default:
                echo 0;
                break;
        }      
        
    }
    function getVideoFinal(){
        $userData = $this->session->userdata('userData');
        if(!$userData) {
            echo -1;
            exit;
        }
        $oauth_uid = $this->input->post('oauth_uid');
        $idupdate = $this->input->post('id');
        $path = $this->input->post('path');
        $filename = time();
        $urlCreateVideo = PATH_URL_PUBLIC."Default.aspx?song=Kalimba&&folderone=$oauth_uid&&foldertwo=$idupdate&&filename=$filename";
        $video_file = file_get_contents($urlCreateVideo);
        if($video_file){
            $video_update = 'public/_data/images/'. $userData['oauth_uid'].'/'.$idupdate."/video/".$filename.".mp4";
            $this->db->set('video_file', $video_update)->where('id', $idupdate)->update(PREFIX.'images_select');
            sleep(4);
            echo $idupdate;
        }else echo 0;
        
    }
    function stepTwo(){
        
        $id = $this->uri->segment(3);
        $result = $this->model->get('*', PREFIX.'images_select',"id = $id");
        if(!$result) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $user_id = $result->user_id;
        $userData = $this->model->get('oauth_uid', PREFIX."users", "id = $user_id"); $oauth_uid = $userData->oauth_uid;
        if($oauth_uid != $this->uri->segment(2)) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $data['poster'] = PATH_URL_PUBLIC."public/_data/images/".$oauth_uid.'/'.$id.'/poster/thumb0016.png';
        $data['title'] = "Clip của bạn";
        $data['result'] = $this->model->get('*', PREFIX.'images_select',"id = $id");
        $userData = $this->session->userdata('userData');
        $data['userData'] = $userData ? $userData : 0;
        $this->blade->render('FRONTEND.step_two', $data);        
    }
    function createImageNomal($width = 500, $height = 500, $sourcefile, $filename, $path){
        $this->load->library('image_moo');    
        $filesave = $path.$filename;
        Newfolder($sourcefile);
        $this->image_moo->load($sourcefile)->resize_crop($width, $height)->save($filesave, true);
        return $filesave;
    }
    function test(){
        // $this->createImageLibFirst($sourcefile, $degrees = 0, $namefile);
        /* -----------CHECK CREATE IMAGE WATERMARK -------------- */
        $path = 'public/_data/test/';
        // $width = 300;
        // $height = 300; $degrees = -5;
        // $position = 92; 
        // $ofset = 65;
        // $waterfile = "public/test/demo.jpg";
        // $pathlib = $path.'tmp/9.png';
        // $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);        
        // $sourcefile = $path."9/1.png";

        // $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);  
        
        $sourcefile = $path."text/1.png";
        $name = "Nguyễn Thảo";
        $friend = "Lecirc Thao";
        if(strlen($friend) <= 12) $left = 175;
        else if(strlen($friend) > 12 && strlen($friend) <=20) $left = 160;
        else $left = 130;
        $sourcefile = $path.'thumb0050.png';
        $this->watermarkText($sourcefile, v2e($name), 130, 250, 16, "#ffffff");
        $this->watermarkText($sourcefile, v2e(substr($friend, strlen($friend)-20)), 260, 250, 16, "#ffffff");
        $this->watermarkText($sourcefile, v2e($friend), $left, 410, 24, "#ffffff");
        echo $sourcefile;
        // $total = 2234;
        // if($total < 10) $left = 223;
        // if($total > 9 || $total < 100) $left = 230;
        // if($total > 99 || $total < 1000) $left = 160;
        // if($total > 999 || $total < 10000) $left = 150;
        // $this->watermarkText($sourcefile, $total, $left, 330, 80, "#6f1625");
        // echo $sourcefile;

        // $path = 'public/_data/test/';
        // $sourcefile = $path."text/2.png";
        // $this->watermarkText($sourcefile, "NGUYỄN THẢO &", 173, 230, 28, "#f7d916");
        // $this->watermarkText($sourcefile, "NGỌC SỰ", 173, 280, 28, "#f7d916");
        // echo $sourcefile;
        // $width        = 288; $height = 268; $degrees = 0; $position = 42; $ofset = 127;                
        // $pathlib      = $path.'tmp/2.png';
        // $waterfile    = "public/test/demo.jpg";
        // $sourcefile   = $path."7/1.png";
        // $imagelib     = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        // $created_time = "03/05/2016";
        // $this->watermarkText($sourcefile, $created_time, 300, 490, 28, "#fffc01", 12);
        // echo $sourcefile;
    }
    
    function watermarkText($sourcefile='', $text='CON BÒ CƯỜI', $position=10, $ofset=10, $fontsize=10, $colour='#fa060f', $dergees=0){
        header('Access-Control-Allow-Origin: *');  
        $imagetobewatermark=imagecreatefrompng($sourcefile);
        $watermarktext=$text;
        $font="public/fonts/AAA.TTF";
        $col = $this->_html2rgb($colour);
        $white = imagecolorallocate($imagetobewatermark, $col[0], $col[1], $col[2]);
        imagettftext($imagetobewatermark, $fontsize, $dergees, $position, $ofset, $white, $font, $watermarktext);
        imagepng($imagetobewatermark, $sourcefile);
        imagedestroy($imagetobewatermark);
    }
   
    function createImageLib($sourcefile, $width, $height, $degrees, $path ){
        header('Access-Control-Allow-Origin: *');  
        $this->load->library('image_moo');
        // ------------- B1: Lưu ảnh về ----------------- //
        $infopath = pathinfo($path);
        $pathTmp = $infopath['dirname'];
        $file_path = $this->saveImage($sourcefile, $pathTmp);
        if($file_path == "Error") return 0;
        // ------------- B2: Resize Image ---------------//
        $this->image_moo->load($file_path);
        $this->image_moo->resize_crop($width,$height)->save($path, true);
         // --------------B3: XOAY HINH ------------------//
        if($degrees && $infopath['extension'] = 'png') $this->rotate($path, $degrees);
        return $path;
    }
    function libWarterImage($sourcefile, $water, $left, $top){
        $this->load->library('image_moo');
        $this->image_moo->load($sourcefile)
                ->load_watermark($water)
                ->set_watermark_transparency(1)->watermark($left,$top, true)
                ->save($sourcefile, true);
    }
    function createImageLibFirst($sourcefile, $degrees = 0, $namefile){
        $this->load->library('image_moo');
        // RESIZE IMAGE 1
        $this->image_moo->load($sourcefile);
        $this->image_moo->resize_crop(76,76)->save($namefile, true);
        // ROUND IMAGE 1
        // $this->image_moo->roundPng($namefile, 20, $namefile);
        // ROTATE IMAGE 1

        // BORDER IMAGE
        if($degrees){
            ob_start();
            $im = imagecreatefrompng( $namefile );
            $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
            $rotated = imagerotate( $im, $degrees, $transparency, 1);
            imagealphablending( $rotated, true );
            imagesavealpha( $rotated, true );
            imagepng($im);
            ob_end_clean();
            imagepng( $rotated, $namefile );
            imagedestroy( $im );
            imagedestroy( $rotated );
        }
        // BORDER IMAGE
        return $namefile;
    }
    
    function saveImage($sourcefile='', $path=''){
        Newfolder($path);
        return storeImageToUrl($sourcefile, $path);   
    }
    function rotate($newfile, $degrees){
        ob_start(); 
        $im = imagecreatefrompng( $newfile );
        $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
        $rotated = imagerotate( $im, $degrees, $transparency, 1);
        imagealphablending( $rotated, true );
        imagesavealpha( $rotated, true );
        imagepng($im);
        ob_end_clean();
        imagepng( $rotated, $newfile );
        imagedestroy( $im );
        imagedestroy( $rotated );
        return true;
    }
    function stepOne(){
        $fb               = new Facebook\Facebook($this->config);
        $helper           = $fb->getRedirectLoginHelper();
        $permissions      = ['email', 'user_friends', 'user_photos'];
        $loginUrl         = $helper->getLoginUrl(PATH_URL.'smile/clientProcessLogin', $permissions);
        $total = $this->model->getTotalUser();
        $start=$this->input->get('p');
        $per_page = 64;
        $data['pageLink'] = pagination($total, $start, $per_page);
        $result= $this->model->listUser($per_page, $start);

        if($result && isset($_SESSION['fb_access_token']))
        foreach ($result as $key => $value) {
            if(!$value->avatar_image){
                $idupdate = $value->id; $oauth_uid = $value->oauth_uid;
                $picture_url = $this->getImageFriend($oauth_uid);   
                $path = 'public/_data/images/'. $oauth_uid.'/avatar';
                Newfolder($path);
                $avatar_user = storeImageToUrl($picture_url, $path);
                $this->model->update(PREFIX.'users', array('avatar_image' => $avatar_user), "id = $idupdate");
                $value->avatar_image = $avatar_user;
                $result[$key] = $value;
            }
        }
        $data['total']    = $total;
        $data['result']   = $result;
        $data['loginUrl'] = htmlspecialchars($loginUrl);
        $data['title']    = "Thư viện tiếng cười";
        $this->blade->render('FRONTEND.step_one', $data);
    }
    function clientProcessLogin(){
        $fb = new Facebook\Facebook($this->config);        
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if (! isset($accessToken)) {
            if ($helper->getError()) {
              header('HTTP/1.0 401 Unauthorized');
              echo "Error: " . $helper->getError() . "\n";
              echo "Error Code: " . $helper->getErrorCode() . "\n";
              echo "Error Reason: " . $helper->getErrorReason() . "\n";
              echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
              header('HTTP/1.0 400 Bad Request');
              echo 'Bad request';
            }
            exit;
        }
        $_SESSION['fb_access_token'] = (string) $accessToken;
        $fbApp = new Facebook\FacebookApp(FB_CLIENT_ID, FB_CLIENT_SECRET);
        $request = new Facebook\FacebookRequest($fbApp,
            $accessToken,
            'GET',
            '/me',
            array(
                'fields' => 'id,name,first_name,last_name,email,gender,locale,picture'
            )
        );
        $response = $fb->getClient()->sendRequest($request)->getdecodedBody();
        $userData['oauth_provider'] = 'facebook';
        $userData['oauth_uid'] = $response['id'];
        $userData['name'] = $response['name'];
        $userData['first_name'] = $response['first_name'];
        $userData['last_name'] = $response['last_name'];
        $userData['email'] = isset($response['email']) ? $response['email'] : '';
        $userData['gender'] = $response['gender'];
        $userData['locale'] = $response['locale'];
        $userData['profile_url'] = 'https://www.facebook.com/'.$response['id'];
        $userData['picture_url'] = $response['picture']['data']['url'];
        $userID = $this->model->checkUser($userData);
        if(!empty($userID)){
            $user = $this->model->get("*", PREFIX.'friends_list', "user_id = $userID");
            if($user) $userData['login_count'] = 1;
            else $userData['login_count'] = 0;
            $userData['user_id'] = $userID;
            $this->session->set_userdata('userData',$userData);
            $userData['title'] = "Process Login";
            $this->blade->render('FRONTEND.client_process_login', $userData);
        } else {
            echo "Error Connect"; exit;
        }
    }

    function editVideoStepOne(){
        $id = $this->uri->segment(2);
        $userData = $this->session->userdata('userData');        
        if(!$userData) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        if(!$id) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $user_id = $userData['user_id'];
        $getFriend = $this->model->get('*', PREFIX.'images_select', "id = $id");
        if(!$getFriend) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $friends_list= $getFriend->friends_list;
        $friends = explode(",", $friends_list);        
        if(count($friends) >= 2){
            $sql = "SELECT * FROM `ops_images_tags` where user_id = $user_id AND friends_list like '%,$friends[0],%' and friends_list like '%,$friends[1],%' ORDER BY `likes_count` DESC, `comments_count` DESC";
        }else{
            $sql = "SELECT * FROM `ops_images_tags` where user_id = $user_id AND friends_list like '%,$friends[0],%' ORDER BY `likes_count` DESC, `comments_count` DESC";
        }
        $query = $this->db->query($sql);
        if($query->num_rows() == 0) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $result = $query->result();
        $total_like = 0;
        foreach ($result as $value) {
            $total_like += $value->comments_count + $value->likes_count;
        }
        if(count($result) < 8){
            $desiredLength = 8;
            $newArray = array();
            while(count($newArray) <= $desiredLength){
                $newArray = array_merge($newArray, $result);
            }
            $arrayTmp = array_slice($newArray, 0, $desiredLength);
            $result = $arrayTmp;
        }  
        // array_slice($result, 0, 14);        
        $data['result'] = $result;
        $data['id'] = $id;
        $data['friends_list'] = $friends_list;
        $data['title'] = "Chỉnh sửa clip";
        $data['total_like'] = $total_like;
        $this->blade->render('FRONTEND.edit_video_step_one', $data);
    }
    function editVideoStepTwo(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL); 
        $user_id = $userData['user_id'];
        if(!$this->input->post('images')) redirect(PATH_URL.'chinh-sua-clip-buoc-1/'.$id = $this->uri->segment(2));
        $images = $this->input->post('images');
        $total_like = $this->input->post('total_like');
        if(count($images) != 8) redirect(PATH_URL.'chinh-sua-clip-buoc-1/'.$id = $this->uri->segment(2));
        $friends_list = $this->input->post('friends_list');

        // GET AVATAR FRIEND LIST
        $friends = explode(",", $friends_list);
        $friendsAvatar = $this->db->where('user_id', $user_id)->where_in("oauth_uid", $friends)->get(PREFIX.'friends_list');
        $arrayAvatar = array();
        $userAvatar = $this->model->get('avatar_image', PREFIX.'users', "id = $user_id");
        array_push($arrayAvatar, $userAvatar->avatar_image);
        $dataName = array();
        array_push($dataName, $userData['name']);
        if($friendsAvatar->num_rows() > 0){
            foreach ($friendsAvatar->result() as $key => $value) {
                $arrayAvatar[] = $value->avatar_image;
                array_push($dataName, $value->name);
            }
        }
        
        $data['dataName'] = json_encode($dataName);
        $data['arrayAvatar'] = json_encode($arrayAvatar);
        // CREATE RECORD IMAGE SELECT.
        $dataImage = array('user_id' => $user_id, 'friends_list' => $friends_list, 'status' => 1, 'created_at' => date("Y-m-d H:i:s"));
        $this->db->insert(PREFIX.'images_select', $dataImage);
        $idInsert = $this->db->insert_id();
        $path = "public/_data/_delete/".$idInsert.'/';
        $pathfinal = $path.'final';
        Newfolder($pathfinal);
        recurse_copy("public/frames_final", $path.'final');
        foreach ($images as $key => $value) {
            $imagesave[] = $this->model->get('created_time, images_large', PREFIX.'images_tags', "images_id = $value");
        }
        $pathtmp = "public/_data/_delete/".$idInsert.'/tmp';
        foreach ($imagesave as $key => $value) {
            $checksave = storeImageToUrl($value->images_large, $pathtmp);
            if($checksave != 'Error'){
                $images_list[$key]['images_large'] = $checksave;
            }
        }
        if(count($images_list) < 8) redirect(PATH_URL.'chinh-sua-clip-buoc-1/'.$id = $this->uri->segment(2));
        $pathframe = $path.'frame';
        Newfolder($pathfinal);
        recurse_copy("public/images/libframe", $pathframe);
        for($i = 0; $i < 8; $i++){
            switch ($i) {               
                case 0:
                    $width = 290; $height = 290; $degrees = 6; $position = 86; $ofset = 71;                
                    $pathlib = $path.'tmp/2.png';
                    $sourcefile = $path."frame/1.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 1:
                    $width = 290; $height = 290; $degrees = 0; $position = 99; $ofset = 82;
                    $pathlib = $path.'tmp/3.png';
                    $sourcefile = $path."frame/2.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 2:
                    $width = 290; $height = 290; $degrees = 5.5; $position = 76; $ofset = 79;
                    $pathlib = $path.'tmp/4.png';
                    $sourcefile = $path."frame/3.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 3:
                    $width = 300; $height = 300; $degrees = 2.8; $position = 85; $ofset = 76;
                    $pathlib = $path.'tmp/5.png';
                    $sourcefile = $path."frame/4.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 4:
                    $width = 300; $height = 300; $degrees = -4.8; $position = 77; $ofset = 62;
                    $pathlib = $path.'tmp/6.png';
                    $sourcefile = $path."frame/5.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 5:
                    $width = 300; $height = 300; $degrees = 2.4; $position = 84; $ofset = 75;
                    $pathlib = $path.'tmp/7.png';
                    $sourcefile = $path."frame/6.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 6:
                    $width = 300; $height = 304; $degrees = 0.8; $position = 94; $ofset = 75;
                    $pathlib = $path.'tmp/8.png';
                    $sourcefile = $path."frame/7.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
                case 7:
                    $width = 300; $height = 300; $degrees = -5; $position = 92; $ofset = 65;
                    $pathlib = $path.'tmp/9.png';
                    $sourcefile = $path."frame/8.png";
                    $imagelib = $this->createImageLib($images_list[$i]['images_large'], $width, $height, $degrees, $pathlib);
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    break;
            }
        }
        $data['oauth_uid']  = $userData['oauth_uid'];
        $data['total_like'] = $total_like;
        $data['imagesave']  = json_encode($imagesave);
        $data['title']      = "Chỉnh sửa clip";
        $data['idInsert']   = $idInsert;
        $this->blade->render('FRONTEND.edit_video_step_two', $data);  
    }

    function ajaxCreateImageFrame(){
        header('Access-Control-Allow-Origin: *');        
        $frame = $this->input->get('frame');
        $idInsert = $this->input->get('idInsert');
        $path = "public/_data/_delete/".$idInsert.'/';
        switch ($frame) {
            case 1:
                $width = 290; $height = 290; $degrees = 6; $position = 86; $ofset = 71;                
                $imagelib = $path.'tmp/2.png';
                for($i = 135; $i <= 171; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 1;
                break;
            case 2:
                $width = 290; $height = 290; $degrees = 0; $position = 99; $ofset = 82;
                $imagelib = $path.'tmp/3.png';
                for($i = 172; $i <= 204; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 2;
                break;
            case 3:
                $width = 290; $height = 290; $degrees = 5.5; $position = 76; $ofset = 79;
                $imagelib = $path.'tmp/4.png';
                for($i = 205; $i <= 237; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 3;
                break;
            case 4:
                $width = 300; $height = 300; $degrees = 2.8; $position = 85; $ofset = 76;
                $imagelib = $path.'tmp/5.png';
                for($i = 334; $i <= 365; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 4;
                break;
            case 5:
                $width = 300; $height = 300; $degrees = -4.8; $position = 77; $ofset = 62;
                $imagelib = $path.'tmp/6.png';
                for($i = 366; $i <= 399; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 5;
                break;
            case 6:
                $width = 300; $height = 300; $degrees = 2.4; $position = 84; $ofset = 75;
                $imagelib = $path.'tmp/7.png';
                for($i = 400; $i <= 434; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 6;
                break;
            case 7:
                $width = 300; $height = 304; $degrees = 0.8; $position = 94; $ofset = 75;
                $imagelib = $path.'tmp/8.png';
                for($i = 435; $i <= 469; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 7;
                break;
            case 8:
                $width = 300; $height = 300; $degrees = -5; $position = 92; $ofset = 65;
                $imagelib = $path.'tmp/9.png';
                for($i = 470; $i <= 503; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 8;
                break;
            case 9:
                $arrayAvatar= $this->input->get('sourcefile');
                $arrayAvatar = json_decode($arrayAvatar);                
                $pathtmp = $path.'tmp/';
                if(count($arrayAvatar) == 2){
                    $fileTmp1 = $pathtmp.'1_1.png';
                    $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 0, $fileTmp1);
                    $fileTmp2 = $pathtmp.'1_2.png';
                    $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], 0, $fileTmp2);
                    // CHAY FOR DE TAO NHIU HINH
                    for($i = 51; $i <= 73; $i++){
                        $sourcefile = $path.'final/thumb00'.$i.'.png';
                        $this->libWarterImage($sourcefile, $imagelib1, 161, 258);
                        $this->libWarterImage($sourcefile, $imagelib2, 260, 258);
                    }
                }
                if(count($arrayAvatar) == 3){
                    $fileTmp1 = $pathtmp.'1_1.png';
                    $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 9, $fileTmp1);
                    $fileTmp2 = $pathtmp.'1_2.png';
                    $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], -9, $fileTmp2);
                    $fileTmp3 = $pathtmp.'1_3.png';
                    $imagelib3 = $this->createImageLibFirst($arrayAvatar[2], -9, $fileTmp3);
                    // CHAY FOR DE TAO NHIU HINH
                    $sourcefile = $path.'final/thumb0002.png';
                    $this->libWarterImage($sourcefile, $imagelib1, 28, 228);
                    $this->libWarterImage($sourcefile, $imagelib2, 191, 230);
                    $this->libWarterImage($sourcefile, $imagelib3, 352, 226);
                }
                $userData = $this->session->userdata('userData');
                if($userData){
                    $dest = "public/_data/images/".$userData['oauth_uid'].'/'.$idInsert.'/poster/';
                    Newfolder($dest);
                    $dest .= "thumb0016.png";
                    copy($path.'final/thumb0016.png', $dest);
                }
                echo 9;
                break;
            default:
                echo 0;
                break;
        }      
        
    }

    function ajaxSaveImageEditStepTwo(){
        header('Access-Control-Allow-Origin: *');  
        $userData = $this->session->userdata('userData');
        $imagesave = $this->input->post('imagesave');
        $idInsert = $this->input->post('idInsert');
        $imagesave = json_decode($imagesave);
        $pathtmp = "public/_data/_delete/".$idInsert.'/tmp';
        $images_update = array();
        foreach ($imagesave as $key => $value) {
            $images_list[$key]['created_time'] = $value->created_time;
            $checksave = storeImageToUrl($value->images_large, $pathtmp);
            if($checksave != 'Error'){
                $images_list[$key]['images_large'] = $checksave;
                $images_update[] = $checksave;
            }else{ 
                $images_list[$key]['images_large'] = 0;
            }
        }
        if($images_update) {
            $this->db->set('images_list', json_encode($images_update))->where('id', $idInsert)->update(PREFIX.'images_select');
            echo json_encode($images_list);
        }else echo json_encode($images_list);        
    }
    function editVideoStepThree(){
        $idInsert = $this->uri->segment(3);
        $result = $this->model->get('*', PREFIX.'images_select',"id = $idInsert");
        if($result->video_file){
            $data['video_file'] = $result->video_file;
        }else{
            $data['video_file'] = 0;
        }
        if(!$result) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $user_id = $result->user_id;
        $userData = $this->model->get('oauth_uid', PREFIX."users", "id = $user_id"); $oauth_uid = $userData->oauth_uid;
        if($oauth_uid != $this->uri->segment(2)) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        if(!$idInsert) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $data['idInsert'] = $idInsert;
        $data['title'] ='Chinh sua clip';
        $data['poster'] = PATH_URL_PUBLIC."public/_data/images/".$oauth_uid.'/'.$idInsert.'/poster/thumb0016.png';
        $userData = $this->session->userdata('userData');
        $data['userData'] = $userData ? $userData : 0;
        $this->blade->render('FRONTEND.edit_video_step_three', $data);
    }
    function ajaxCreateVideoByEdit(){  
        header('Access-Control-Allow-Origin: *');        
        $userData = $this->session->userdata('userData');
        $oauth_uid = $userData['oauth_uid'];        
        $idupdate = $this->input->post('idInsert');
        $data = array('idupdate' => $idupdate);
        $checkVideo = $this->model->get('video_file', PREFIX.'images_select', "id = $idupdate");
        if(!$checkVideo->video_file){            
            $filename = time();
            $urlCreateVideo = PATH_URL_PUBLIC."Default.aspx?song=Kalimba&&folderone=$oauth_uid&&foldertwo=$idupdate&&filename=$filename";
            $video_file = file_get_contents($urlCreateVideo);
            if($video_file){
                $video_update = "public/_data/images/".$oauth_uid."/".$idupdate."/video/".$filename.".mp4";
                $this->db->set('video_file', $video_update)->where('id', $idupdate)->update(PREFIX.'images_select');
                sleep(4);
                $data['video_file'] = $video_update;
                echo json_encode($data);
            }else {
                $data = array('idupdate' => 0);
                echo json_encode($data);
            }
        }
        else{
            $data['video_file'] = $checkVideo->video_file;
            echo json_encode($data);
        }

    }
    function ajaxEditImageByWater(){
        header('Access-Control-Allow-Origin: *');  
        $frame = $this->input->get('frame');
        $water = $this->input->get('water');
        $idInsert = $this->input->get('idInsert');
        if($water == 0 || $frame == 0) {echo $frame + 7; exit;}
        $imagelib = "public/images/water/items_".$water.".png";
        $path = "public/_data/_delete/".$idInsert.'/';
        switch ($frame) {
            case 1:
                $position = 5; $ofset = 5;
                for($i = 135; $i <= 171; $i++){
                    if($i <= 99) $sourcefile = $path.'final/thumb00'.$i.'.png';
                    else $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 10;
                break;
            case 2:
                $position = 100; $ofset = 1;
                for($i = 172; $i <= 204; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 11;
                break;   
            case 3:
                $position = 300; $ofset = 5;
                for($i = 205; $i <= 237; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 12;
                break; 
            case 4:
                $position = 0; $ofset = 5;
                for($i = 334; $i <= 365; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 13;
                break; 
            case 5:
                $position = 100; $ofset = 0;
                for($i = 366; $i <= 399; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 14;
                break; 
            case 6:
                $position = 300; $ofset = 5;
                for($i = 400; $i <= 434; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 15;
                break;
            case 7:
                $position = 5; $ofset = 5;
                for($i = 435; $i <= 469; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 16;
                break; 
            case 8:
                $position = 100; $ofset = 1;
                for($i = 470; $i <= 503; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 17;
                break;           
            default:
                echo 0;
                break;
        }
    }
    function ajaxCreateFrameTextOne(){
        // -------------- FRAME TEXT 1 -------------------------*/
        header('Access-Control-Allow-Origin: *');  
        // $userData = $this->session->userdata('userData');
        // if(!$userData){echo -1; exit;}
        $total = $this->input->get("total");
        $idInsert = $this->input->get('idInsert');
        $path = "public/_data/_delete/".$idInsert.'/';
        if($total < 10) $left = 223;
        else if($total > 9 && $total < 100) $left = 230;
        else if($total > 99 && $total < 1000) $left = 170;
        else if($total > 999 && $total < 10000) $left = 160;
        else $left = 150;
        for($i = 504; $i <= 547; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->watermarkText($sourcefile, $total, $left, 330, 80, "#6f1625");
        }
        echo 18;
    }
    function ajaxCreateFrameTextTwo(){
        // -------------- FRAME TEXT 2 -------------------------*/
        header('Access-Control-Allow-Origin: *');  
        $idInsert = $this->input->get('idInsert');
        $dataName = $this->input->get('dataName');
        $dataText = json_decode($dataName, true);
        $path = "public/_data/_delete/".$idInsert.'/';
        $friend = $dataText[1];
        if(strlen($friend) <= 12) $left = 180;
        else if(strlen($friend) > 12 && strlen($friend) <=20) $left = 160;
        else $left = 130;

        for($i = 48; $i <= 73; $i++){
            $sourcefile = $path.'final/thumb00'.$i.'.png';
            $this->watermarkText($sourcefile, v2e($dataText[0]), 130, 250, 16, "#ffffff");
            $this->watermarkText($sourcefile, v2e(substr($friend, strlen($friend)-20)), 260, 250, 16, "#ffffff");
            $this->watermarkText($sourcefile, v2e($friend), $left, 410, 24, "#ffffff");
        }
        echo 19;
    }
    // LIBRARY FACEBOOK
    // LẤY ẢNH ĐẠI DIỆN CỦA BẠN
    function getImageFriend($oauth_uid){
        $accessToken = $_SESSION['fb_access_token'];
        if(!$accessToken) redirect(PATH_URL);
        $fbApp = new Facebook\FacebookApp(FB_CLIENT_ID, FB_CLIENT_SECRET);
        $fb = new Facebook\Facebook($this->config);
        $request = new Facebook\FacebookRequest($fbApp,
            $accessToken,
            'GET',
            "/$oauth_uid/",
            array(
                'fields' => 'picture.width(100)',
                'picture.height(100)' => 'null'
            )
        );
        try {
            $response = $fb->getClient()->sendRequest($request)->getdecodedBody();
            if(isset($response['picture'])) return $response['picture']['data']['url'];
            else return 0;
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return 0;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return 0;
        }    
    }
    function _html2rgb($colour)  {
        if (is_array($colour))
        {
            if (count($colour)==3) return $colour;                              // rgb sent as an array so use it
            $this->set_error('Colour error, array sent not 3 elements, expected array(r,g,b)');
            return false;
        }
        if ($colour[0] == '#')
            $colour = substr($colour, 1);

        if (strlen($colour) == 6)
        {
            list($r, $g, $b) = array($colour[0].$colour[1],
                                     $colour[2].$colour[3],
                                     $colour[4].$colour[5]);
        }
        elseif (strlen($colour) == 3)
        {
            list($r, $g, $b) = array($colour[0].$colour[0], $colour[1].$colour[1], $colour[2].$colour[2]);
        }
        else
        {
            $this->set_error('Colour error, value sent not #RRGGBB or RRGGBB, and not array(r,g,b)');
            return false;
        }

        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

        return array($r, $g, $b);
    }
    function getPhotoUser(){
        $userData = $this->session->userdata('userData');
        $data['userData'] = $userData;
        $data['title'] = "List photo user";
        $this->blade->render('FRONTEND.get_photo_user', $data);
    }
    function trackingShare(){
        $oauth_uid = $this->input->post('oauth_uid');
        $video_id = $this->input->post('video_id');
        $link = $this->input->post('link');
        $data = array(
                'oauth_uid'  => $oauth_uid,
                'video_id'   => $video_id,
                'link'       => $link,
                'created_at' => date("Y-m-d H:i:s")
            );
        $this->db->insert(PREFIX.'tracking_share', $data);
        echo 1;
    }
}
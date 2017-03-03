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
        $data['title'] = "Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn";
        $data['friends'] = $this->model->getFriendsList($user_id, 4, 0, "");
        $this->blade->render('FRONTEND.index', $data);
    }
    function ajaxGetFriendSearch(){
        $userData = $this->session->userdata('userData');
        if(!$userData) {echo -1; exit;}
        $user_id = $userData['user_id'];
        $name = $this->input->post('name');
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
        if(count($result) < 6) {
            $rs['msg'] = "Số lượng ảnh của bạn và người đề cử không đủ để tạo video"; echo json_encode($rs); exit;
        }
        array_slice($result, 0, 6);
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

        // -------------- FRAME TEXT 1 -------------------------*/
        $total = $total_like;//$this->model->getTotalUser();
        if($total < 10) $left = 270;
        if($total > 9 || $total < 100) $left = 250;
        if($total > 99|| $total < 1000) $left = 240;
        if($total > 999|| $total < 10000) $left = 220;
        for($i = 190; $i <= 219; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->watermarkText($sourcefile, $total, $left, 245, 60, "#d62128");
        }

        // -------------- FRAME TEXT 2 -------------------------*/     
        for($i = 323; $i <= 353; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->watermarkText($sourcefile, $userData['name'], 173, 230, 28, "#f7d916");
            $this->watermarkText($sourcefile, $dataFriend[0]->name, 173, 280, 28, "#f7d916");
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
        // $userData = $this->session->userdata('userData');
        // if(!$userData) {
        //     $rs['status'] = -1;
        //     echo json_encode($rs);
        //     exit;
        // }
        $frame = $this->input->post('frame');
        $waterfile = $this->input->post('sourcefile');
        $created_time = date("d/m/Y", strtotime($this->input->post('created_time')));
        $idInsert = $this->input->post('idInsert');
        $path = "public/_data/_delete/".$idInsert.'/';

        switch ($frame) {
            case 1:
                $width = 288; $height = 268; $degrees = 0; $position = 42; $ofset = 127;                
                $pathlib = $path.'tmp/2.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 73; $i <= 106; $i++){
                    if($i <= 99) $sourcefile = $path.'final/thumb00'.$i.'.png';
                    else $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    $this->watermarkText($sourcefile, $created_time, 100, 432, 28, "#f7d916");
                }
                echo 1;
                break;
            case 2:
                $width = 270; $height = 252; $degrees = -1.4; $position = 194; $ofset = 129;
                $pathlib = $path.'tmp/3.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 107; $i <= 147; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    $this->watermarkText($sourcefile, $created_time, 260, 118, 28, "#f7d916", -3);
                }
                echo 2;
                break;
            case 3:
                $width = 356; $height = 334; $degrees = 2.4; $position = 79; $ofset = 99;
                $pathlib = $path.'tmp/4.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 148; $i <= 184; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    $this->watermarkText($sourcefile, $created_time, 197, 479, 28, "#f7d916", 2.4);
                }
                echo 3;
                break;
            case 4:
                $width = 312; $height = 294; $degrees = 4; $position = 67; $ofset = 80;
                $pathlib = $path.'tmp/5.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 220; $i <= 241; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    $this->watermarkText($sourcefile, $created_time, 206, 425, 28, "#f7d916", 4);
                }
                echo 4;
                break;
            case 5:
                $width = 340; $height = 316; $degrees = -8.7; $position = 120; $ofset = 106;
                $pathlib = $path.'tmp/6.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 242; $i <= 273; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    $this->watermarkText($sourcefile, $created_time, 150, 460, 28, "#fffc01", -6.5);
                }
                echo 5;
                break;
            case 6:
                $width = 330; $height = 311; $degrees = 13.15; $position = 41; $ofset = 92;
                $pathlib = $path.'tmp/7.png';
                $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
                for($i = 274; $i <= 318; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                    $this->watermarkText($sourcefile, $created_time, 300, 490, 28, "#fffc01", 12);
                }
                echo 6;
                break;
            case 7:
                $arrayAvatar= $this->input->post('sourcefile');
                $arrayAvatar = json_decode($arrayAvatar);
                $pathtmp = $path.'tmp/';
                if(count($arrayAvatar) == 2){
                    $fileTmp1 = $pathtmp.'1_1.png';
                    $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 9, $fileTmp1);
                    $fileTmp2 = $pathtmp.'1_2.png';
                    $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], -9, $fileTmp2);
                    // CHAY FOR DE TAO NHIU HINH
                    for($i = 15; $i <= 45; $i++){
                        $sourcefile = $path.'final/thumb00'.$i.'.png';
                        $this->libWarterImage($sourcefile, $imagelib1, 45, 226);
                        $this->libWarterImage($sourcefile, $imagelib2, 330,234);
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
                echo 7;
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
        $id = $this->uri->segment(2);
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect('clip-trao-nhau-tieng-cuoi-tet');
        $data['poster'] = PATH_URL_PUBLIC."public/_data/images/".$userData['oauth_uid'].'/'.$id.'/poster/thumb0016.png';
        $data['title'] = "Clip của bạn";
        $data['result'] = $this->model->get('*', PREFIX.'images_select',"id = $id");
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
        $idInsert = 27;
        $userData = $this->session->userdata('userData');
        $path = "public/_data/_delete/".$idInsert.'/';
        if($userData){
            $dest = "public/_data/images/".$userData['oauth_uid'].'/'.$idInsert.'/poster/';
            pr($dest);
            Newfolder($dest);
            $dest .= "thumb0016.png";
            pr($dest);
            copy($path.'final/thumb0016.png', $dest);
            echo "Done";
        }else{
             echo "Error";
        }
       
        // $this->createImageLibFirst($sourcefile, $degrees = 0, $namefile);
        /* -----------CHECK CREATE IMAGE WATERMARK -------------- */
        // $path = 'public/_data/test/';
        // $width = 330; $height = 311; $degrees = 13.15; $position = 41; $ofset = 92;
        // $waterfile = "public/test/demo.jpg";
        // $pathlib = $path.'tmp/2.png';
        // $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);        
        // $sourcefile = $path."7/1.png";
        // $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);  
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
        $this->rotate($path, $degrees);
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
        $this->image_moo->resize_crop(103,104)->save($namefile, true);
        // ROUND IMAGE 1
        $this->image_moo->roundPng($namefile, 20, $namefile);
        // ROTATE IMAGE 1
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
        $userData = $this->session->userdata('userData'); /// Tam thời thôi.
        if(!$userData){
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
            $userData['email'] = $response['email'];
            $userData['gender'] = $response['gender'];
            $userData['locale'] = $response['locale'];
            $userData['profile_url'] = 'https://www.facebook.com/'.$response['id'];
            $userData['picture_url'] = $response['picture']['data']['url'];
            $userID = $this->model->checkUser($userData);

            if(!empty($userID)){
                $user = $this->model->get("*", PREFIX.'users', "id = $userID");
                $userData['login_count'] = $user->login_count;
                $userData['user_id'] = $userID;
                $this->session->set_userdata('userData',$userData);
                $userData['title'] = "Process Login";
                $this->blade->render('FRONTEND.client_process_login', $userData);
            } else {
                echo "Error Connect"; exit;
            }
        }else{
            $userData['title'] = "Process Login";
            $this->blade->render('FRONTEND.client_process_login', $userData);
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
        array_slice($result, 0, 14);        
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
        if(count($images) != 6) redirect(PATH_URL.'chinh-sua-clip-buoc-1/'.$id = $this->uri->segment(2));
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
        $data['total_like'] = $total_like;
        $data['imagesave'] = json_encode($imagesave);
        $data['title'] = "Chỉnh sửa clip";
        $data['idInsert'] = $idInsert;
        $this->blade->render('FRONTEND.edit_video_step_two', $data);  
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
        $userData = $this->session->userdata('userData');
        if(!$this->session->userdata('userData')) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $idInsert = $this->uri->segment(2);

        if(!$idInsert) redirect(PATH_URL.'clip-trao-nhau-tieng-cuoi-tet');
        $data['idInsert'] = $idInsert;
        $data['title'] ='Chinh sua clip';
        $data['poster'] = PATH_URL_PUBLIC."public/_data/images/".$userData['oauth_uid'].'/'.$idInsert.'/poster/thumb0016.png';
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
        $userData = $this->session->userdata('userData');
        // if(!$userData){echo -1; exit;}
        $frame = $this->input->post('frame');
        $water = $this->input->post('water');
        $idInsert = $this->input->post('idInsert');
        if($water == 0 || $frame == 0) {echo $frame + 7; exit;}
        $imagelib = "public/images/water/items_".$water.".png";
        $path = "public/_data/_delete/".$idInsert.'/';
        switch ($frame) {
            case 1:
                $position = 200; $ofset = 200;
                for($i = 73; $i <= 106; $i++){
                    if($i <= 99) $sourcefile = $path.'final/thumb00'.$i.'.png';
                    else $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 8;
                break;
            case 2:
                $position = 200; $ofset = 200;
                for($i = 107; $i <= 147; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 9;
                break;   
            case 3:
                $position = 200; $ofset = 200;
                for($i = 148; $i <= 184; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 10;
                break; 
            case 4:
                $position = 200; $ofset = 200;
                for($i = 220; $i <= 241; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 11;
                break; 
            case 5:
                $position = 200; $ofset = 200;
                for($i = 242; $i <= 273; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 12;
                break; 
            case 6:
                $position = 200; $ofset = 200;
                for($i = 274; $i <= 318; $i++){
                    $sourcefile = $path.'final/thumb0'.$i.'.png';
                    $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
                }
                echo 13;
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
        $total = $this->input->post("total");
        $idInsert = $this->input->post('idInsert');
        $path = "public/_data/_delete/".$idInsert.'/';
        if($total < 10) $left = 280;
        if($total > 9 || $total < 100) $left = 250;
        if($total > 99|| $total < 1000) $left = 240;
        if($total > 999|| $total < 10000) $left = 220;
        for($i = 190; $i <= 219; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->watermarkText($sourcefile, $total, $left, 245, 60, "#d62128");
        }
        echo 14;
    }
    function ajaxCreateFrameTextTwo(){
        // -------------- FRAME TEXT 2 -------------------------*/
        header('Access-Control-Allow-Origin: *');  
        // $userData = $this->session->userdata('userData');
        // if(!$userData){echo -1; exit;}
        $idInsert = $this->input->post('idInsert');
        $dataName = $this->input->post('dataName');
        $dataText = json_decode($dataName, true);
        $path = "public/_data/_delete/".$idInsert.'/';
        for($i = 323; $i <= 353; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->watermarkText($sourcefile, $dataText[0], 173, 230, 28, "#f7d916");
            $this->watermarkText($sourcefile, $dataText[1], 173, 280, 28, "#f7d916");
        }
        echo 15;
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
}
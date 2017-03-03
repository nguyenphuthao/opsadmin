<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    require(APPPATH.'/third_party/facebook-php-sdk-v5/autoload.php');
class Home extends MX_Controller
{
    private $module = 'home';
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
    
    function index()
    {
        $config = array(
          'app_id' => FB_CLIENT_ID,
          'app_secret' => FB_CLIENT_SECRET,
          'default_graph_version' => 'v2.8',
          //'default_access_token' => '{access-token}', // optional
        );
        $fb = new Facebook\Facebook($config);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_friends', 'user_photos'];
        $loginUrl = $helper->getLoginUrl(PATH_URL.'home/loginCallback', $permissions);
        echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
    }
    function callbackLogin(){
        
    }
    function loginCallback(){
        
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
            $userData['user_id'] = $userID;
            $this->session->set_userdata('userData',$userData);
        } else {
           echo "Error";
        }
        redirect(PATH_URL.'home/getPhotos');
    }

    function getImagesTags($after=0){
        $userData = $this->session->userdata('userData');       
        $accessToken = $_SESSION['fb_access_token'];
        if(!$userData || !$accessToken) redirect(PATH_URL.'home');
        $fb = new Facebook\Facebook($this->config);
        $fbApp = new Facebook\FacebookApp(FB_CLIENT_ID, FB_CLIENT_SECRET);
        $fields = array(
            'fields' => 'name_tags,can_tag,tags',
            'limit'  => '200'
        );
        if($after) $fields['after'] = $after;
        $request = new Facebook\FacebookRequest($fbApp,
            "$accessToken",
            'GET',
            '/me/photos',
            $fields
        );
        $response = $fb->getClient()->sendRequest($request)->getBody();
        return $response;
    }

    function getPhotos(){
        $userData = $this->session->userdata('userData');
        $accessToken = $_SESSION['fb_access_token'];
        if(!$userData || !$accessToken) redirect(PATH_URL.'home');
        $fbApp = new Facebook\FacebookApp(FB_CLIENT_ID, FB_CLIENT_SECRET);
        $fb = new Facebook\Facebook($this->config);
        $photos = $this->getImagesTags();
        $photos = json_decode($photos);
        $data = $photos->data;
        $data_merge = array();
        if(isset($photos->paging->next)){
            $photos_merge = $this->getImagesTags($photos->paging->cursors->after);
            $photos_merge = json_decode($photos_merge);
            $data_merge = $photos_merge->data;
        }
        foreach ($data_merge as $key => $value) {
            array_push($data, $value);
        }
        
        foreach ($data as $key => $value) {
            $dataImages = array(); $friends_list = '';
            $tags = $value->tags->data;
            foreach ($tags as $vl) {
                $dataFriend['user_id'] = $userData['user_id'];
                if(isset($vl->id)) {$dataFriend['oauth_uid'] = $vl->id; $friends_list .= $vl->id.",";}
                if(isset($vl->name)) $dataFriend['name'] = $vl->name;
                if(isset($vl->name) && isset($vl->id) && $vl->name != $userData['name']) $this->model->checkFriendsList($dataFriend);
            }
            $imageID = $value->id;
            $requestImage = new Facebook\FacebookRequest($fbApp,
                "$accessToken",
                'GET',
                "$imageID",
                array(
                    'fields' => 'likes.summary(true),comments.summary(true),images'
                )
            );
            $friends_list = substr($friends_list, 0, -1);
            if($friends_list != $userData['oauth_uid']){
                $response                     = $fb->getClient()->sendRequest($requestImage)->getdecodedBody();
                $images_full                  = json_encode( $response['images'] );
                $images_large                 = $response['images'][0]['source'];
                $dataImages['friends_list']   = $friends_list;
                $dataImages['user_id']        = $userData['user_id'];
                $dataImages['images_id']      = $imageID;
                $dataImages['images_full']    = $images_full;
                $dataImages['images_large']   = $images_large;
                $dataImages['likes_count']    = $response['likes']['summary']['total_count'];
                $dataImages['comments_count'] = $response['comments']['summary']['total_count'];
                $this->model->checkImagesTags($dataImages);
            }
        }
        redirect(PATH_URL.'home/getFriendsList');
    }
    function chooseImage(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL.'home');
        $user_id = $userData['user_id'];
        $data['friendsList'] = $this->model->fetch('oauth_uid, name', PREFIX.'friends_list', "user_id = $user_id", 'appear_count');
        $data['imagesTags'] = $this->model->fetch('friends_list, images_large', PREFIX.'images_tags', "user_id = $user_id", "likes_count, comments_count", "", "");
        $data['title'] = "Chon hình ảnh giữa bạn và những người bạn";
        $this->blade->render('FRONTEND.chose_images', $data);
    }
    // function getImagesWithFriend(){
    //     $friend_id = $this->input->post('friend_id');
    //     $userData = $this->session->userdata('userData');
    //     if(!$userData) echo 0;
    //     $user_id = $userData['user_id'];
    //     $data['friendsList'] = $this->model->fetch('oauth_uid, name', PREFIX.'friends_list', "user_id = $user_id", 'appear_count');
    //     $data['imagesTags'] = $this->model->fetch('friends_list, images_large', PREFIX.'images_tags', "user_id = $user_id AND friends_list LIKE '%$friend_id%'",'likes_count, comments_count');
    //     $this->blade->render('FRONTEND.ajax_get_image', $data);
    //     // echo $friend_id;
    // }


    function getImageClient(){
        $data['title'] = "Login with Facebook";
        $this->blade->render('FRONTEND.images_client', $data);
    }
    function ajaxLogin(){
        $picture                    = $this->input->post('picture');
        $userData['oauth_provider'] = 'facebook';
        $userData['oauth_uid']      = $this->input->post('id');
        $userData['name']           = $this->input->post('name');
        $userData['first_name']     = $this->input->post('first_name');
        $userData['last_name']      = $this->input->post('last_name');
        $userData['email']          = $this->input->post('email'); 
        $userData['gender']         = $this->input->post('gender'); 
        $userData['locale']         = $this->input->post('locale'); 
        $userData['profile_url']    = 'https://www.facebook.com/' . $this->input->post('id');
        $userData['picture_url']    = $picture['data']['url'];
        $userID = $this->model->checkUser($userData);        
        if(!empty($userID)){
            $userData['user_id'] = $userID;
            $this->session->set_userdata('userData',$userData);
            echo 1;
        } else {
           echo 0;
        }        
    }
    function ajaxGetFriendsList(){
        $oauth_uid = $this->input->post('oauth_uid');
        $user = $this->model->getUser($oauth_uid);
        if($user){
            $friends = $this->input->post('friends');
            $friends_list = json_decode($friends, true);
            $dataFriend['user_id'] = $user->id;
            foreach ($friends_list as $key => $value) {
                if(isset($value['oauth_uid'])){
                    $value['user_id'] = $user->id;
                    $this->model->checkFriendsList($value);
                }
            }            
        }
    }

    // LÀM THEO HƯƠNG MỚI
    // GET IMAGE FRIEND (size: 100x100).
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
    // LOAD FRIEND LIST.
    function getFriendsList(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL);
        $user_id = $userData['user_id'];
        $data['num_friend'] = $this->model->getCountFriends($user_id);
        $record = 21; $start = 0;
        $friends = $this->model->getFriendsList($user_id, $record, $start);
        foreach ($friends as $key => $value) {
            if(!$value->picture_url){
                $picture_url = $this->getImageFriend($value->oauth_uid); $id = $value->id;
                $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
                $value->picture_url = $picture_url;
                $friends[$key] = $value;
            }
        }
        $data['record']  = $record;
        $data['friends'] = $friends;
        $data['title']   = "Chọn bạn";
        $this->blade->render('FRONTEND.get_friends_list', $data);
    }
    // AJAX LOAD FRIEND LIST MORE
    function ajaxGetFriendsListMore(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL);
        $user_id = $userData['user_id'];
        $this->input->post();
        $record = $this->input->post('record');
        $start = $this->input->post('start');
        $friends = array();
        $friends = $this->model->getFriendsList($user_id, $record, $start);
        if($friends)
            foreach ($friends as $key => $value) {
                if(!$value->picture_url){
                    $picture_url = $this->getImageFriend($value->oauth_uid); $id = $value->id;
                    $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
                    $value->picture_url = $picture_url;
                    $friends[$key] = $value;
                }
            }
        $data['friends'] = $friends;
        $this->blade->render('FRONTEND.ajax_get_friends_list_more', $data);
    }

    // AJAX SEARCH FRIEND
    function ajaxGetFriendSearch(){
        $userData = $this->session->userdata('userData');
        if(!$userData) {echo 0; exit;}
        $user_id = $userData['user_id'];
        $name = $this->input->post('name');
        $record = 100;
        $start = 0;
        $friends = array();
        $friends = $this->model->getFriendsList($user_id, $record, $start, $name);
        if($friends)
            foreach ($friends as $key => $value) {
                if(!$value->picture_url){
                    $picture_url = $this->getImageFriend($value->oauth_uid); $id = $value->id;
                    $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
                    $value->picture_url = $picture_url;
                    $friends[$key] = $value;
                }
            }
        $data['friends'] = $friends;
        $this->blade->render('FRONTEND.ajax_get_friends_list_more', $data);
    }
    // END AJAX SEARCH FRIEND
    function getImagesWithFriend(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL);
        $user_id = $userData['user_id'];
        $friends = $this->input->post('friends');
        foreach ($friends as $key => $value) {
            $result[] = $this->model->fetch('friends_list, images_large, images_id', PREFIX.'images_tags', "user_id = $user_id AND friends_list LIKE '%$value%'",'likes_count, comments_count');
        }
        $data['title'] = "Chọn hình ảnh giữa bạn và những người bạn";
        $data['imagesTags'] = $result;
        $data['friendsList'] = $this->model->fetch('oauth_uid, name', PREFIX.'friends_list', "user_id = $user_id", 'appear_count');
        $this->blade->render('FRONTEND.get_Images_with_friend', $data);
    }
    function saveImages(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL); 
        $user_id = $userData['user_id'];
        $images = $this->input->post('images');
        $path = 'public/_data/images/'. $userData['oauth_uid'];
        foreach ($images as $key => $value) {
            $images_list[] = storeImageToUrl($value, $path);
        }
        $dataIns = array('user_id' => $user_id, 'images_list' => json_encode($images_list), 'created_at' => date("Y-m-d H:i:s"));
        $this->model->insert(PREFIX.'images_select', $dataIns);
        $data['images_libs'] = $this->model->fetch('*', PREFIX.'images_libs');
        $data['images_list'] = $images_list;
        $data['title'] = 'Ghép thứ tự ảnh thành video';
        $this->blade->render('FRONTEND.join_images', $data);
    }
    function createFileVideo(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL); 
        $user_id = $userData['user_id'];
        $videos = $this->input->post('videos');
        $tbImages = $this->model->get('id', PREFIX.'images_select',"user_id = $user_id","id",'DESC');
        if(!$tbImages) redirect(PATH_URL);
        $idUpdate = $tbImages->id;        
        $video = explode(',', $videos);
        $filename = time();
        $path = 'public/_data/files/'. $userData['oauth_uid'].'/'.$filename.'.txt';
        Newfolder('public/_data/files/'. $userData['oauth_uid']);
        $flag = 0;
        foreach ($video as $key => $value) {
            $info = getimagesize($value);
            $width = $info[0];
            $height = $info[1];
            if($width % 2 == 1) $width--;
            if($height % 2 == 1) $height--;
            if($width % 2 == 1 || $height % 2 == 1){
                $config['image_library']  = 'gd2';
                $config['source_image']   = $value;
                $config['create_thumb']   = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['width']          = 900;
                $config['height']         = 500;
                $config['new_image'] = $value;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
            }
            $value = "file '" . $value . "'\r\n duration 3\r\n";
            if(!write_file_txt($path, $value)) $flag = 1;
        }
        $oauth_uid = $userData['oauth_uid'];
        if($flag == 0){
            $video_name = '';
            $urlCreateVideo = "http://beltet.opsgreat.vn/Default?song=Kalimba&&folder=$oauth_uid&&filename=$filename&&id=_____";
            $video_file = file_get_contents($urlCreateVideo);
            if($video_file == 0){
                redirect(PATH_URL);
            }
            $video_name = 'public/_data/videos/' . $userData['oauth_uid'] . "/" . $filename . ".mp4";
            $this->model->update(PREFIX.'images_select', array('images_video' => $videos, 'path_file' => $path, 'video_file' => $video_name), "id = $idUpdate");
            redirect(PATH_URL.'home/playVideo');
        }else{
            echo "Tao file that bai";
        }
        
        // $path = 'public/images/'. $userData['oauth_uid'];
        // write_file_txt()
    } 
    
    function test(){
        
        $image                    =  'public/images/cover.jpg';
        $info                     = pathinfo($image);
        $file_name                =  $info['basename'];

        $config['image_library']  = 'gd2';
        $config['source_image']   = $image;
        $config['create_thumb']   = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width']          = 900;
        $config['height']         = 500;
        $config['new_image'] = 'public/_data/images/'.$file_name;

        $this->load->library('image_lib', $config);
        if ( ! $this->image_lib->resize())
        {
            echo $this->image_lib->display_errors();
        }
        echo "Done";
    }
    // -------------------- BEGIN CLIENT -----------------------------//

    function clientLogin(){
        // $userData = $this->session->userdata('userData');
        //  if(!$userData){
            $fb               = new Facebook\Facebook($this->config);
            $helper           = $fb->getRedirectLoginHelper();
            $permissions      = ['email', 'user_friends', 'user_photos'];
            $loginUrl         = $helper->getLoginUrl(PATH_URL.'home/clientProcessLogin', $permissions);
            $data['title']    = "Login with Facebook";
            $data['loginUrl'] = htmlspecialchars($loginUrl);
            $this->blade->render('FRONTEND.client_login', $data);
        // }else{
        //     redirect(PATH_URL.'home/clientGetFriendsList');
        // }
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
    function clientAjaxSetFriendsList(){
        $user_id = $this->input->post('user_id');
        $oauth_uid = $this->input->post('oauth_uid');
        $userData = $this->session->userdata('userData');
        if(!$userData) {echo 0; exit;}
        if($userData['user_id'] != $user_id) {echo 0; exit;}       
        $friends = $this->input->post('friends');
        $friends_list = json_decode($friends, true);
        foreach ($friends_list as $key => $value) {
            if(isset($value['oauth_uid'])){
                $value['user_id'] = $user_id;
                $this->model->checkFriendsList($value);
            }
        }       
        echo 1;
    }
    function clientAjaxSetImagesTags(){
        $user_id   = $this->input->post('user_id');
        $oauth_uid = $this->input->post('oauth_uid');
        $userData  = $this->session->userdata('userData');
        if(!$userData) {echo 0; exit;}
        if($userData['user_id'] != $user_id) {echo 0; exit;}   
        $images = $this->input->post('images');    
        $images_list = json_decode($images, true);
        $images_list['user_id'] = $user_id;
        $this->model->checkImagesTags($images_list);
        echo 1;
    }
    function clientGetFriendsList(){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL);
        $user_id = $userData['user_id'];
        $data['num_friend'] = $this->model->getCountFriends($user_id);
        $record = 21; $start = 0;
        $friends = $this->model->getFriendsList($user_id, $record, $start);
        foreach ($friends as $key => $value) {
            if(!$value->picture_url){
                $picture_url = $this->getImageFriend($value->oauth_uid); $id = $value->id;
                $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
                $value->picture_url = $picture_url;
                $friends[$key] = $value;
            }
        }
        $allFriends = $this->model->getAllFriendsNoAvartar($user_id);
        $arr_friends = array();
        foreach ($allFriends as $value) {
            $arr_friends[] = $value->oauth_uid;
        }
        $data['arr_friends'] = $arr_friends;
        $data['user_id'] = $user_id;
        $data['record']  = $record;
        $data['friends'] = $friends;
        $data['title']   = "Chọn bạn";
        $this->blade->render('FRONTEND.client_get_friends_list', $data);
    }
    function clientAjaxUpdateAvatar(){
        $picture = $this->input->post('pic_u');
        if($picture){
            $pic = json_decode($picture,true); $oauth_uid = $pic['oauth_uid'];
            $this->model->update(PREFIX.'friends_list', array('picture_url' => $pic['picture_url']), "oauth_uid = $oauth_uid");
        }
    }
    // END PROCCESS CLIENT.
    function playVideo($filename=''){
        $userData = $this->session->userdata('userData');
        if(!$userData) redirect(PATH_URL);
        $user_id = $userData['user_id'];
        $data['title'] ='Video của bạn';
        $videoUser = $this->model->get('video_file', PREFIX.'images_select', "user_id = $user_id","id","desc");
        $filevideo = PATH_URL_PUBLIC . $videoUser->video_file;
        $filecontent = getUrlContent($filevideo);
        if(!$filecontent) sleep(5);
        $data['urlvideo'] = $filevideo;
        $this->blade->render('FRONTEND.play_video', $data);
    }
    // ------------- LOGOUT ---------------- //
    public function logout(){
        unset($this->session->userdata);
        // if (isset($_SERVER['HTTP_COOKIE'])) {
        //     $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        //     foreach ($cookies as $cookie) {
        //         $parts = explode('=', $cookie);
        //         $name  = trim($parts[0]);
        //         if($name=='utm_source' || $name=='utm_medium'){
        //             continue;
        //         }
        //         setcookie($name, '', time() - 1000);
        //         setcookie($name, '', time() - 1000, '/');
        //     }
        // }
        session_destroy();
        $this->session->set_userdata("logout", 1);
        redirect(PATH_URL);
    }
    function test1(){
        $img = 'public/_data/images/1253546244712734/13100731_785852988213245_4607221309673219369_n.jpg';
        $this->createImage(1, $img);
    }
    function createImage($id=0, $image='', $offset=1, $water=0){
        
        $this->load->library('image_moo');
        $dataFrame = $this->model->get('*', 'ops_images_frames', "id = $id");
        $sourcefile = $dataFrame->image;
        switch ($offset) {
            case 2:
                $offset_1 = $dataFrame->offset_2;
                break;
            case 3:
                $offset_1 = $dataFrame->offset_3;
                break;
            default:
                $offset_1 = $dataFrame->offset_1;
                break;
        }
        if(isset($offset_1)){
            $offset_1 = explode(",", $offset_1);
            $info = pathinfo($image);
            $path = $info['dirname'].'/tmp';
            Newfolder($path);
            $newfile = $newfile_video = $path."/".time().'.png';
            $this->image_moo->load($image);

            // CO GI DO SAI SAI DOAN NAY 
            $this->image_moo->resize_crop($offset_1[0],$offset_1[1])->save($newfile, true);

            if(isset($offset_1[4]))  $action_1 = explode("|", $offset_1[4]);
            if(isset($action_1)){
                if($action_1[0] != 'roundPng'){
                    $action = $action_1[0];
                    switch ($action) {
                        case 'rotate':
                            $param1 = $action_1[1];
                            ob_start();                           
                            $degrees        = $param1;
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
                            break;
                        
                        default:
                            # code...
                            break;
                    }                    
                }else{
                    $this->image_moo->roundPng($newfile, $action_1[1], $newfile);
                }
            }
            if(isset($offset_1[5]))  $action_2 = explode("|", $offset_1[5]);
            if(isset($action_2)){
                $this->image_moo->load($newfile);
                if($action_2[0] != 'roundPng'){
                    $action = $action_2[0];
                    switch ($action) {
                        case 'rotate':
                            $param1 = $action_2[1];
                            ob_start();                           
                            $degrees        = $param1;
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
                            break;
                        default:
                            # code...
                            break;
                    }
                }else{
                    $this->image_moo->roundPng($newfile, $action_2[1], $newfile);
                }
            }
            if($water){
                $path = $info['dirname'].'/frames';
                Newfolder($path);
                $newfile_video = $path."/".time().'.png';
                $this->image_moo->load($sourcefile)
                                ->load_watermark($newfile)
                                ->set_watermark_transparency(1)->watermark($offset_1[2],$offset_1[3], true)
                                ->save($newfile_video, true);
            }

            return $newfile_video;

        }
        echo "Done";
        // pr($dataFrame);
    }
}
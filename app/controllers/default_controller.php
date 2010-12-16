<?php

class DefaultController extends AppController
{
  const TUMBLR_AUTHENTICATE_URL = 'http://www.tumblr.com/api/authenticate';
  const TUMBLR_DASHBOARD_URL    = 'http://www.tumblr.com/api/dashboard';
  const TUMBLR_REBLOG_URL       = 'http://www.tumblr.com/api/reblog';
  const TUMBLR_LIKE_URL         = 'http://www.tumblr.com/api/like';

  const TUMBLR_DEFAULT_NUM = 10;
  const TUMBLR_START_MAX   = 250;
  
  public
    $name = 'Default',
    $uses = array();
  
  public function index()
  {
    $data     = array_key_exists('data'     , $this->params)? $this->params['data']: array();
    $tumblr   = array_key_exists('tumblr'   , $data        )? $data['tumblr']      : array();
    $email    = array_key_exists('email'    , $tumblr      )? $tumblr['email']     : '';
    
    $this->set('email', $email);
  }
  
  public function login()
  {
    $data     = array_key_exists('data'     , $this->params)? $this->params['data']: array();
    $tumblr   = array_key_exists('tumblr'   , $data        )? $data['tumblr']      : array();
    $email    = array_key_exists('email'    , $tumblr      )? $tumblr['email']     : '';
    $password = array_key_exists('password' , $tumblr      )? $tumblr['password']  : '';
    
    if (strlen($email) === 0 || strlen($password) === 0) {
      $this->index();
      $this->render('index');
    } else {
      $data = array(
        'email'    => $email,
        'password' => $password,
      );
      $contents = self::post(self::TUMBLR_AUTHENTICATE_URL, $data);
      if ($contents === false) {
        $this->index();
        $this->render('index');
      } else {
        $this->dashboard();
        $this->render('dashboard');
      }
    }
  }
  
  public function dashboard()
  {
    $data     = array_key_exists('data'     , $this->params)? $this->params['data']: array();
    $tumblr   = array_key_exists('tumblr'   , $data        )? $data['tumblr']      : array();
    $email    = array_key_exists('email'    , $tumblr      )? $tumblr['email']     : '';
    $password = array_key_exists('password' , $tumblr      )? $tumblr['password']  : '';
    $page     = array_key_exists('page'     , $tumblr      )? $tumblr['page']      : 0;
    
    $page = is_numeric($page)? (int)$page: 0;
    
    if (strlen($email) === 0 || strlen($password) === 0) {
      $this->redirect('/');
    }
    
    $data = array(
      'email'    => $email,
      'password' => $password,
      'start'    => $page * self::TUMBLR_DEFAULT_NUM,
      'num'      => self::TUMBLR_DEFAULT_NUM
    );
    $contents = self::post(self::TUMBLR_DASHBOARD_URL, $data);
    if ($contents === false) {
      $this->redirect('/');
    }
    
    $xml = self::xmlLoadString($contents);
    $this->set('posts', $xml['posts']['post']);
    
    $this->set('email'   , $email);
    $this->set('password', $password);
    $this->set('pager'   , array(
      'page'         => $page,
      'num'          => self::TUMBLR_DEFAULT_NUM,
      'has_previous' => $page !== 0,
      'has_next'     => $page * self::TUMBLR_DEFAULT_NUM < self::TUMBLR_START_MAX,
    ));
  }
  
  public function reblog()
  {
    $data       = array_key_exists('data'      , $this->params)? $this->params['data']: array();
    $tumblr     = array_key_exists('tumblr'    , $data        )? $data['tumblr']      : array();
    $email      = array_key_exists('email'     , $tumblr      )? $tumblr['email']     : '';
    $password   = array_key_exists('password'  , $tumblr      )? $tumblr['password']  : '';
    $post_id    = array_key_exists('post-id'   , $tumblr      )? $tumblr['post-id']   : '';
    $reblog_key = array_key_exists('reblog-key', $tumblr      )? $tumblr['reblog-key']: '';
    
    if (strlen($email) === 0 || strlen($password) === 0) {
      $this->redirect('/');
    }
    
    if (strlen($post_id) > 0 && strlen($reblog_key) > 0) {
      $data = array(
        'email'      => $email,
        'password'   => $password,
        'post-id'    => $post_id,
        'reblog-key' => $reblog_key
      );
      $contents = self::post(self::TUMBLR_REBLOG_URL, $data);
    }
    
    $this->dashboard();
    $this->render('dashboard');
  }
  
  public function like()
  {
    $data       = array_key_exists('data'      , $this->params)? $this->params['data']: array();
    $tumblr     = array_key_exists('tumblr'    , $data        )? $data['tumblr']      : array();
    $email      = array_key_exists('email'     , $tumblr      )? $tumblr['email']     : '';
    $password   = array_key_exists('password'  , $tumblr      )? $tumblr['password']  : '';
    $post_id    = array_key_exists('post-id'   , $tumblr      )? $tumblr['post-id']   : '';
    $reblog_key = array_key_exists('reblog-key', $tumblr      )? $tumblr['reblog-key']: '';
    
    if (strlen($email) === 0 || strlen($password) === 0) {
      $this->redirect('/');
    }
    
    if (strlen($post_id) > 0 && strlen($reblog_key) > 0) {
      $data = array(
        'email'      => $email,
        'password'   => $password,
        'post-id'    => $post_id,
        'reblog-key' => $reblog_key
      );
      $contents = self::post(self::TUMBLR_LIKE_URL, $data);
    }
    
    $this->dashboard();
    $this->render('dashboard');
  }
  
  protected static function request($url, $data = array(), $method = 'GET')
  {
    $options = array('http' => array(
      'method'  => strtoupper($method),
      'content' => http_build_query($data)
    ));
    return @file_get_contents($url, false, stream_context_create($options));
  }
  
  protected static function post($url, $data = array())
  {
    return self::request($url, $data, 'POST');
  }
  
  protected static function get($url, $data = array())
  {
    return self::request($url, $data, 'GET');
  }
  
  protected static function xmlLoadString($contents)
  {
    $xml = simplexml_load_string($contents);
    return self::objectToArray($xml);
  }
  
  protected static function objectToArray($obj)
  {
    if (!is_object($obj) && !is_array($obj)) {
      return $obj;
    }
    $arr = (array)$obj;
    foreach ($arr as $key => $value) {
      unset($arr[$key]);
      $key = str_replace('@', '', $key);
      $arr[$key] = self::objectToArray($value);
    }
    return $arr;
  }
}

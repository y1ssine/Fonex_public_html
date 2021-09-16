<?php
 function __autoload($class_name) 
   {
    require_once $class_name.".php";
  }
/**
 * 
 */
if(!isset($_SESSION)){
   session_start();
   $_SESSION = unserialize(serialize($_SESSION));
}

if (isset($_POST['action'])) {
     call_user_func($_POST['action'], $_POST);   

}
function final_execution($data){
     $err = "";
     if(isset($_SESSION['add_customer'])){
           
        $err =   SignupHelper::MakeSoapCall('add_customer','Customer',$_SESSION['add_customer'], 'Admin');
        $_SESSION['i_customer'] = $err->i_customer;
     }
     if(!empty($err->i_customer) && isset($_SESSION['add_did_number'])){
        $_SESSION['add_did_number']['i_customer'] = $_SESSION['i_customer'];
        $err =  SignupHelper::MakeSoapCall('add_account','Account', array('account_info'=>$_SESSION['add_did_number']), 'Account');
        $_SESSION['MAIN_DID_I_ACC'] = $err->i_account;
     }
     if(!empty($err->i_account) && isset($_SESSION['add_cli'])){
        $_SESSION['add_cli']['i_customer'] = $_SESSION['i_customer'];
        $add = SignupHelper::MakeSoapCall('update_service_features','Customer', $_SESSION['add_cli'], 'Customer');
     }
     if(isset($_SESSION['accounts'])){
      $count = 0;
       foreach ($_SESSION['accounts'] as $key => $value) {
          $value['i_customer'] = $_SESSION['i_customer'];
          $err =  SignupHelper::MakeSoapCall('add_account','Account', array('account_info'=>$value), 'Account');
          $_SESSION['Ext10'.$count]['i_account'] = $err->i_account;
          $_SESSION['Exts_i_acc'][] = $err->i_account;

          $service_features = array(
            'i_account'=>$err->i_account,
            'service_features'=>$_SESSION['unified_messaging'],
          );
          $AddAccountResponse = SignupHelper::MakeSoapCall('update_service_features','Account', $service_features, 'Admin');
          $count++;
       }
     }



}
function add_customer($data){
   if($pl = explode(" ", $data["plan_name"])){
      switch ($pl[0]) {
        case 'Standard':
          $volumediscount ='5140';
          # code...
          break;
        case 'Premium':
          $volumediscount ='5141';
          # code...
          break;
        case 'Enterprise':
          $volumediscount ='5146';
          # code...
          break;
        default:
          # code...
          $volumediscount ='';
          break;
      }
    }
    
    $cust_login = preg_replace("%[^a-z\d\-_@\.]%i", '',$data['email']);
    $cust_pass = generatePassword(8);
    foreach ($data as $key => $value) {
      if(is_string($value) && !empty($value)){
        $data[$key] = SignupHelper::PrepareString($value);
      }
    }
    $lastname ='';
    if($names = explode(' ', $data['names'])){
      $firstname = isset($names[0]) ? $names[0] : '' ;
      $lastname = isset($names[1]) ? $names[1] : '' ;
    }else{
      $firstname = $data['names'];
    }
    $_SESSION['EMAIL'] = $data["email"];
    $customer_info = array(
      'name' => $data['companyname'].'-'.time(),
      'iso_4217' => 'USD',
      'perm_credit_limit'=>300,
      'i_balance_control_type' => 1,
      'opening_balance' => 0,
      'i_billing_period'=>5,//monthy aniversary
      'login' => $cust_login,
      'password' => $cust_pass,
      'companyname' => $data['companyname'],
      'firstname' => $firstname,
      'lastname' => $lastname,
      'baddr1' => $data["address"],
      'city' => $data["city"],
      'state' => $data["states"],
      'zip' => $data["zip"],
      'country' => 'US',
      'note' => 'Users:'.$data["numberofexts"].';plan:'.$data["plan_name"],
      'phone1' => $data["contact"],
      'email' => $data["email"],
      'i_vd_plan'=>$volumediscount,
      'i_time_zone' =>$data['i_time_zone'],
      'i_ui_time_zone'=>$data['i_time_zone'],
      'service_features' => array()
    );
    try {
      $_SESSION['firstform'] = $data; 
      $_SESSION['plan']  = $data["plan"];
      $_SESSION['plan_name']  = $data["plan_name"];
      $_SESSION['add_customer'] = array('customer_info' => $customer_info);
    } catch (Exception $e) {
       $err = $e->getMessage();
    }
}
function set_opt($data){
  final_execution($data);
  sleep(0.5);
  if($data['callflow']=="cellphone"){
     cellphone_flow($data);
  }
  if($data['callflow']=="recepIVR" || $data['callflow']=="IVR"){
   unset($data['action']);
   $lang = $data['lang'];
   $flow = $data['callflow'];
   unset($data['callflow']);
   unset($data['lang']);
   unset($data['i_customer']);
   unset($data['number']);
   $events = array();
   foreach ($data as $key => $value) {
      if($value == 'disabled') continue;
      $events[$key]['event']=$key;
      if($value == 'ringall'){
        $events[$key]['action']='Transfer';
        $events[$key]['destination']='201';
      }elseif($value =='recepext'){
        $events[$key]['action']='Transfer';
        $events[$key]['destination']='100';
      }elseif($value =='replay'){
        $events[$key]['action']='Menu';
        $events[$key]['menu']='replay';
      }elseif($value =='vm'){
        $events[$key]['action']='Voicemail';
      }elseif($value =='afterh'){
        $events[$key]['action']='Menu';
        $events[$key]['menu']='afterh';
      }elseif($value =='lang'){
        $events[$key]['action']='Menu';
        $events[$key]['menu']='lang';
      }elseif($value =='directory'){
        $events[$key]['action']='Directory';
      }else{
        //unset($events[$key]['event']);
      }
      
   }
    $_SESSION['lang'] = $data['lang'] = $lang;
    $data['i_customer'] = $_SESSION['i_customer'];
    $data['number']= $_SESSION['number'];
    $data['callflow'] = $flow;
    if($data['callflow']=="IVR"){
       IVR($data,$events);
    }else{
      recepIVR($data,$events);
    }
 }
 if($data['callflow']=="ringall"){
   ringall($data);
 }
 if(isset($_SESSION['i_customer'])){
   echo "The customer ".$_SESSION['add_customer']['name']." was added, please check its settings.";
   session_destroy();
 }
}
function get_number_list($data){
  
  $area_code = $data['area'];
  if($area_code[0] !== '1') $area_code = '1'.$area_code;
  $request = array('limit'=>9, 'offset'=>0, 'number'=>$area_code.'%','usage'=>'I');
  $Response = SignupHelper::MakeSoapCall('get_number_list','DID',$request, 'Admin');
  $count = 0;
  $res = '';
  foreach ($Response->number_list as $key) {
    if($key->frozen === 'Y' || isset($key->i_customer) || !isset($key->owner_batch_name)) continue;
    $count++;
       $res.='<div class="number-result-item local">
            <label class="radio-container btn btn-lg font-bold body-bold" for="'.$key->number.'">
              <input type="radio" class="hide" name="local_did" value="'.$key->number.'" id="'.$key->number.'">
              <i class="far fa-circle"></i>
              '.$key->number.'</label>

          </div>';
  }
  if($count>0){
   echo $res;
    //var_dump($data);
  }
  echo '<script>
            var state = $("#statess");
            var stat = $("#stat");
            var btnlg = $(".btn-lg1");
            var byarea = $("#byarea");
            var bycity = $("#bycity");
            var btnlg2 = $(".btn-lg2");
            var btn_local_pick = $("#btn_local_pick");
            var btn_local_keep = $("#btn_local_keep");
            var searchby = $(".SearchDID");
            var search = $(".Search");
            var keepnumb = $("#keepnumb");
           btnlg2.on("click", function(e){
           e.preventDefault();
           var aclick1 = $(this);
           btnlg2.removeClass("btn-uv-blue");
           aclick1.addClass("btn-uv-blue");
           if(aclick1.attr("id") === "btn_local_pick"){
             keepnumb.hide();
             search.show();
           }else{
             searchby.hide();
             keepnumb.show();
           }

        });
        btnlg.on("click", function(e){
           e.preventDefault();
           var aclick = $(this);
           btnlg.removeClass("btn-uv-blue");
           aclick.addClass("btn-uv-blue");
           if(aclick.attr("id") === "btn_local_search_by_areacode"){
             bycity.hide();
             byarea.show();
           }else{
             byarea.hide();
             bycity.show();
           }

        });
        var did_buttons = $(".local");
        var did_i = $(".radio-container").find("i");
        did_buttons.on("click", function(e){
           e.preventDefault();
           var label_i = $(this).find("i");
           var label_number = $(this).find("input").val();
           did_i.removeClass("fas fa-check-circle");
           did_i.addClass("far fa-circle");
           label_i.removeClass("far fa-circle");
           label_i.addClass("fas fa-check-circle");
           $("#didnumber").val(label_number);
        });

       </script>';
}
function add_number($data){
  //params in data : number,i_customer, exts
   if(isset($_SESSION['didinfo'])){
        foreach ($_SESSION['didinfo'] as $key) {
           if ($data['number'] != $key->number) {
             continue;
           }
           $_SESSION['DIDAPI'] = ($c = explode("::", $key->package)) ? $c[1] : "";
           $_SESSION['ratecenter'] = "Ratecenter: ".$key->ratecenter;
           $_SESSION['state'] = "State: ".$key->state;
           $_SESSION['API_Desc'] = 
           "
            ".$_SESSION['DIDAPI'].
            " ".$_SESSION['state'].
            " Activation fee: ".$key->init_cost.
            " Monthly:".$key->monthly."
           ";

        }
   }
   $data['number'] = preg_replace("/[^a-zA-Z0-9]/", "", $data['number']) ? preg_replace("/[^a-zA-Z0-9]/", "", $data['number']) : $data['number'];
   $data['number'] = substr($data['number'], 0, strpos($data['number'], "xxx")) ? substr($data['number'], 0, strpos($data['number'], "xxx"))."X".time() : $data['number'];
   $data['number'] = ($data['number'][0] !== '1') ? '1'.$data['number'] : $data['number'] ;
   $i_did_number = add_main_number($data);
   $exts = add_exts_account($data);
   $_SESSION['period'] = $data['period'];
   $_SESSION['period_desc'] = $data['period_desc'];
   
}
function add_main_number($data){
   $pass = generatePassword(8);
   $i_product = '23704';
   $dt = new DateTime("now");
   $dt->setTimestamp(time());
   $AddAccountRequest = array(
        'id'=> $data['number'],
        'billing_model'=> 1,
        'opening_balance'=> 0,
        'balance'=>0.00,
        'h323_password'=> $pass,
        'i_product'=>$i_product,
        'activation_date'=> $dt->format('Y-m-d'),
        'follow_me_enabled'=>'Y',
        'notepad' => (isset($_SESSION['API_Desc'] )) ? $_SESSION['API_Desc'] : "",
   );
   $_SESSION['add_did_number'] = $AddAccountRequest;
   $_SESSION['number'] = $data['number'];
   try {
        $sfc = array(

         array(         
          "flag_value"=> "Y",               
          "name"=> "cli",
          "attributes"=> array(
            array( "name"=> "centrex", "values"=> array($data['number'])),
            array( "name"=> "display_number", "values"=> array($data['number'])), 
            array( "name"=> "display_number_check","values"=>array('D')), 
            array( "name"=> "display_name_override","values"=>array('N'))
          ),
         )
       );
       $_SESSION['add_cli']['service_features'] = $sfc;
       
     
   }catch(Exception $e) {
       $err = $e->getMessage();
    }
}
function add_exts_account($data){
  $count = 0;
  $i_product = isset($_SESSION['plan']) ? $_SESSION['plan'] : '';
  $pass = generatePassword(8);
  $acc = array();
  $_SESSION['unified_messaging'] = array(
          array(
             'name'=>'unified_messaging',
             'flag_value'=>'Y'
          ),
        );
  while($count<$data['exts']){
    $dt = new DateTime("now");
    $dt->setTimestamp(time());
    $AddAccountRequest = array(
        'id'=> '020'.$_SESSION['number'].'10'.$count,
        'billing_model'=> 1,
        'opening_balance'=> 0,
        'balance'=>0.00,
        'h323_password'=> $pass.''.$count,
        'i_product'=>$i_product,
        'activation_date'=> $dt->format('Y-m-d'),
   );
   $_SESSION['accounts'][$count] = $AddAccountRequest;

    $count++;
  }
}
function add_extensions_hg($data){
      $i_acc = $_SESSION['Exts_i_acc'];
      $i_c_ext = array();
      foreach ($i_acc as $key => $value) {
          $AddCustomerExtensionRequest = array(
               'i_customer'=>$_SESSION['i_customer'],
               'id'=>'10'.$key,
               'i_account'=> $value,
            );
          $_SESSION['add_extensions'][] = $AddCustomerExtensionRequest;
    }
    if(isset($_SESSION['add_extensions'])){
        
        foreach ($_SESSION['add_extensions'] as $key => $value) {
          $err = SignupHelper::MakeSoapCall('add_customer_extension','Customer', $value, 'Customer');
            $_SESSION['Exts_i_c_ext'][] =$err->i_c_ext;
        }
     }
    $AddCustomerHuntgroupRequest = array(
               'i_customer'=>$_SESSION['i_customer'],
               'id'=>'200',
               'name'=>'HG to Ext100',
               'hunt_sequence'=>'Simultaneous',
               'add_extensions' =>array(
                    array(
                    'i_c_ext'=>$_SESSION['Exts_i_c_ext'][0],
                    'huntstop'=>'Y'),
               ),
    );
    $_SESSION['add_hg'][0] = $AddCustomerHuntgroupRequest;
    $extss = array();
    foreach ($_SESSION['Exts_i_c_ext'] as $key => $value) {
      $extss[] = array(
         'i_c_ext'=> $value,
         'huntstop'=> 'Y',
      );
    }
    $AddCustomerHuntgroupRequest2 = array(
               'i_customer'=>$_SESSION['i_customer'],
               'id'=>'201',
               'name'=>'Ring All Exts',
               'hunt_sequence'=>'Simultaneous',
               'add_extensions' =>$extss,
    );
    $_SESSION['add_hg'][1] = $AddCustomerHuntgroupRequest2;
    if( isset($_SESSION['add_hg'])){
       foreach ($_SESSION['add_hg'] as $key => $value) {
        $AddCustomerExtensionResponse = SignupHelper::MakeSoapCall('add_customer_huntgroup','Customer', $value, 'Customer');
      }
     }

}
//IVR only call flow
function IVR($data,$options){
    //setup create exts
    //setup huntgroups 
    //HG 200->ext100 //HG 201 -> all exts(VM enabled on ext 100)
    add_ivr_number($data);
    add_extensions_hg($data);
    setup_followme($data, 'IVR');
    set_period();
    add_menus($data, $options);
}
//Call flow receptionist then ivr
function recepIVR($data, $options){

   add_ivr_number($data);
   add_extensions_hg($data);

   setup_followme($data, 'recepIVR'); //
   set_period();
   add_menus($data, $options); //options, lang


}
//RingAll Callflow
function ringall($data){
  add_extensions_hg($data);
  setup_followme($data, 'RingAll');
}
//Cell phone call flow
function cellphone_flow($data){
  if($data['cellphone'][0] !== '1') $data['cellphone']= '1'.$data['cellphone'];
  setup_followme($data, 'cellphone');
}
function add_ivr_number($data){
   $pass = generatePassword(8);
   $i_product = '23704';
   $dt = new DateTime("now");
   $dt->setTimestamp(time());
   $AddAccountRequest = array(
        'id'=> $_SESSION['number'].'AA',
        'billing_model'=> 1,
        'i_customer'=>$_SESSION['i_customer'],
        'opening_balance'=> 0,
        'balance'=>0.00,
        'h323_password'=> $pass,
        'login'=> $_SESSION['number'].'AA',
        'password'=>$pass,
        'i_product'=>$i_product,
        'activation_date'=> $dt->format('Y-m-d'),
   );
   $account_info = array('account_info'=> $AddAccountRequest);
   try {
     $AddAccountResponse = SignupHelper::MakeSoapCall('add_account','Account', $account_info, 'Account');
     if(isset($AddAccountResponse->i_account)){
      $_SESSION['IVR_ID'] = $_SESSION['number'].'AA';
      $_SESSION['IVR_PASS'] = $pass;
      $_SESSION['IVR_I_ACC'] = $AddAccountResponse->i_account;
      $sf = array(
          array(
             'name'=>'unified_messaging',
             'flag_value'=>'Y'
          ),
          array(
             'name'=>'auto_attendant',
             'flag_value'=>'Y'
          ),
        );
      $service_features = array(
          'i_account'=>$AddAccountResponse->i_account,
          'service_features'=>$sf,
       );
       $AddAccountResponse = SignupHelper::MakeSoapCall('update_service_features','Account', $service_features, 'Account');
      if(isset($AddAccountResponse->i_account)){
         setup_voicemail();
      }
     }
   }catch(Exception $e) {
       $err = $e->getMessage();
    }
}
function set_period(){
    //i_account 23495363, lang, loginrequest,//options
    //create_menu
    if(!isset($_SESSION['I_ROOT_MENU'])){
      $res = SignupHelper::make_um_call('get_menu_list', 'AutoAttendant',array());
      $i_menu_root ='';
      if(isset($res->menu_list)){
        foreach ($res->menu_list as $key => $value) {
         if($value->name !== 'ROOT'){
            continue;
        }
        $i_menu_root = $value->i_menu;
       }
     }
     $_SESSION['I_ROOT_MENU'] = $i_menu_root;
    }
    if(isset($_SESSION['I_ROOT_MENU']) && !empty($_SESSION['I_ROOT_MENU'])){
     $UpdateMenuRequest = array(
      'menu_info'=> array(
            'i_menu'=>$_SESSION['I_ROOT_MENU'],
            'period'=>$_SESSION['period'],  //hr{8-18} wd{mo-fri}, hr{4-6} wd{sa}
            'period_desc'=>$_SESSION['period_desc']
      ),
     );
     $update = SignupHelper::make_um_call('update_menu', 'AutoAttendant',$UpdateMenuRequest);
    }
    
}
function setup_voicemail(){
   if(isset($_SESSION['EMAIL'])){
   $SetVMSettingsRequest = array(
        'vm_settings'=> array(
                      'ext_email'=> $_SESSION['EMAIL'],
                      'ext_email_action'=>'forward',
 
        ),
   );
   try {
      $SetVMResponse = SignupHelper::make_um_call('set_vm_settings','Voicemail',$SetVMSettingsRequest);
    } catch (Exception $e) {
       $err = $e->getMessage();
    }
  }
}


function setup_followme($data,$opt=""){
  //params : DID i_account, number, period
  //add_followme_number
  if(!empty($opt) && $opt == 'cellphone'){
    $AddFollowMeNumberRequest = array(
          'number_info'=> array(
               'i_account' => $_SESSION['MAIN_DID_I_ACC'],
                 'redirect_number'=>$_SESSION['cellphone'],
                 'timeout'=>20,
                 'name'=>'Reception',
                 'period' => $_SESSION['period'],
                 'period_description'=>$_SESSION['period_desc'],

          ),
    );
      $AddCustomerExtensionResponse = SignupHelper::MakeSoapCall('add_followme_number','Account', $AddFollowMeNumberRequest, 'Account');
  }
  if(!empty($opt) && $opt == 'recepIVR'){
    $AddFollowMeNumberRequest = array(
          'number_info'=> array(
               'i_account' => $_SESSION['MAIN_DID_I_ACC'],
                 'redirect_number'=>'200',
                 'timeout'=>20,
                 'name'=>'Reception',
                 'period' => $_SESSION['period'],
                 'period_description'=>$_SESSION['period_desc'],

          ),
    );
      $AddCustomerExtensionResponse = SignupHelper::MakeSoapCall('add_followme_number','Account', $AddFollowMeNumberRequest, 'Account');
  }
  if(!empty($opt) && ($opt == 'recepIVR' || $opt == 'IVR') ){
    $AddFollowMeNumberRequest2 = array(
          'number_info'=> array(
               'i_account' => $_SESSION['MAIN_DID_I_ACC'],
                 'redirect_number'=>$_SESSION['IVR_ID'],
                 'name'=>'IVR',

          ),
    );

    sleep(0.5);
    $AddCustomerExtensionResponse = SignupHelper::MakeSoapCall('add_followme_number','Account', $AddFollowMeNumberRequest2, 'Account');
  }
  if(!empty($opt) && $opt == 'RingAll'){
    $AddFollowMeNumberRequest = array(
          'number_info'=> array(
               'i_account' => $_SESSION['MAIN_DID_I_ACC'],
                 'redirect_number'=>'201',
                 'timeout'=>20,
                 'name'=>'Reception',
                 'period' => $_SESSION['period'],
                 'period_description'=>$_SESSION['period_desc'],

          ),
    );
      $AddCustomerExtensionResponse = SignupHelper::MakeSoapCall('add_followme_number','Account', $AddFollowMeNumberRequest, 'Account');
  }

}


function generatePassword($length) {
    $lowercase = "qwertyuiopasdfghjklzxcvbnm";
    $uppercase = "ASDFGHJKLZXCVBNMQWERTYUIOP";
    $numbers = "1234567890";
    $specialcharacters = "{}[];:,./<>?_+~!@#";
    $randomCode = "";
    mt_srand(crc32(microtime()));
    $max = strlen($lowercase) - 1;
    for ($x = 0; $x < abs($length/4); $x++) {
    $randomCode .= $lowercase{mt_rand(0, $max)};
    }
    $max = strlen($uppercase) - 1;
    for ($x = 0; $x < abs($length/4); $x++) {
    $randomCode .= $uppercase{mt_rand(0, $max)};
    }
    //$max = strlen($specialcharacters) - 1;
    /*for ($x = 0; $x < abs($length/4); $x++) {
    $randomCode .= $specialcharacters{mt_rand(0, $max)};
    }*/
    $max = strlen($numbers) - 1;
    for ($x = 0; $x < abs($length/4); $x++) {
    $randomCode .= $numbers{mt_rand(0, $max)};
    }
    return str_shuffle($randomCode);
  }
function GetUrl()
  {

    $link_return = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] 
                === 'on' ? "https" : "http") . "://" . 
          $_SERVER['HTTP_HOST'].'/fonexinc'; 
    return $link_return;
  }
function add_menus($data, $options){
    //i_account 23495363, lang, loginrequest,//options
    //create_menu
    $_SESSION['lang'] = ($data['lang'] != "Disabled") ? $data['lang'] : "";
    $CreateMenuRequest[0] = array(
        'menu_info'=>array(
            'name'=> 'After Hours',
            'period_desc'=>'Always',
            'period'=>'Always'
        )
    );
    if(!empty($_SESSION['lang'])){
      $CreateMenuRequest[1] = array(
          'menu_info'=>array(
              'name'=> $_SESSION['lang'].' After Hours',
              'period_desc'=>'Always',
              'period'=>'Always'
          )
      );
      $CreateMenuRequest[2] = array(
          'menu_info'=>array(
              'name'=> $_SESSION['lang'].' Menu',
              'period_desc'=>'Always',
              'period'=>'Always'
          )
      );
    }
    $menus = array();
    foreach ($CreateMenuRequest as $key => $value) {

      $AddSubscriptionResponse = SignupHelper::make_um_call('create_menu','AutoAttendant',$CreateMenuRequest[$key]);
      $menus[] = $AddSubscriptionResponse->i_menu;
      sleep(0.5);
    }
    
    $afterhours = array();
    $afterhours[0] = array(
      'transition_info' => array(               
        'event'     => 'Timeout',  
        'action'    => 'Voicemail',      
        //'action'    => 'Transfer',         
        //'destination'=> '5555',
      )
    );
    $afterhours[1] = array(
      'transition_info' => array(               
        'event'     => '0',  
        'action'    => 'Voicemail',      
        //'action'    => 'Transfer',         
        //'destination'=> '5555',
      )
    );
    //Set options for after hours menus
    $variable = array();
    $variable['i_afterhour'] = $menus[0];
    if(!empty($_SESSION['lang']) && isset($menus[1])){
      $variable['i_afterhour_lang'] = $menus[1];
    }
    foreach ($variable as $k => $val) {
      foreach ($afterhours as $key => $value) {
        $afterhourss = array();
        $afterhourss = $afterhours[$key];
        $afterhourss['i_menu'] = $val;
        
        $AddSubscriptionResponse2 = SignupHelper::make_um_call('set_menu_transition','AutoAttendant',$afterhourss);
        sleep(0.5);
      }
    }
   
   $i_menu_root = isset($_SESSION['I_ROOT_MENU']) ? $_SESSION['I_ROOT_MENU'] : "";
   if($i_menu_root == ""){
     $res = SignupHelper::make_um_call('get_menu_list', 'AutoAttendant',array());
     if(isset($res->menu_list)){
     foreach ($res->menu_list as $key => $value) {
      if($value->name !== 'ROOT'){
          continue;
      }
      $i_menu_root = $value->i_menu;
     }
    }
   }
   $menus[] = $i_menu_root;
   set_options($menus, $options);
    
}
function set_options($menus, $options = array()){
    $i_afterhour = $menus[0];
    $i_afterhour_lang = (!empty($_SESSION['lang']) && isset($menus[1])) ? $menus[1] : "";
    $variable = array();
    $variable['i_root'] = $menus[3];
    if(!empty($_SESSION['lang']) && isset($menus[2])){
      $variable['i_workhour_lang'] = $menus[2];
    }
    //Set options for after hours menus
    $options['10']['event'] = 'Not Active';
    $options['10']['action'] = 'Menu';
    foreach ($variable as $k => $val) {
      foreach ($options as $key => $value) {
        $workhourss = array();
        if(($k === 'i_root') && (($value['event'] == 'Not Active') || ($value['action']=='Menu' && $value['menu']=='afterh'))){

                 $value['target_i_menu'] = $i_afterhour; 
                 unset($value['menu']);

        }elseif(($k === 'i_workhour_lang') && (($value['event'] == 'Not Active') || ($value['action']=='Menu' && $value['menu']=='afterh'))){
                 $value['target_i_menu'] = $i_afterhour_lang;
                 unset($value['menu']);
        }
          if(($k === 'i_root') && ($value['action']=='Menu' && $value['menu']=='replay')){

                 $value['target_i_menu'] = $val; 
                 unset($value['menu']);

        }elseif(($k === 'i_workhour_lang') && ($value['action']=='Menu' && $value['menu']=='replay')){
                 $value['target_i_menu'] = $val;
                 unset($value['menu']);
        }
        if(($k === 'i_root') && ($value['action']=='Menu' && $value['menu']=='lang')){

                 $value['target_i_menu'] = isset($variable['i_workhour_lang']) ? $variable['i_workhour_lang'] :"";
                 unset($value['menu']); 

        }elseif(($k === 'i_workhour_lang') && ($value['action']=='Menu' && $value['menu']=='lang')){
                 $value['target_i_menu'] = $variable['i_root'];
                 unset($value['menu']);
        }
        $workhourss['i_menu'] = $val;
        $workhourss['transition_info'] = $value;
        //var_dump($workhourss);
        $AddSubscriptionResponse2 = SignupHelper::make_um_call('set_menu_transition','AutoAttendant',$workhourss);
        sleep(0.5);
      }
    }
    
}


function get_states(){
  $GetDIDAPIStatesListRequest = array('country' => 'US');
  try{
    $GetDIDAPIStatesListResponse = SignupHelper::MakeSoapCall('get_states','DIDAPI', $GetDIDAPIStatesListRequest, 'DIDAPI');
    //var_dump($GetDIDAPIStatesListResponse);
    $options = "<option>Select State/Province</option>";
    foreach ($GetDIDAPIStatesListResponse->states as $state) {
       if(($state->package !== 'DIDAPI::MagicTelecom' && $state->value!=='TF') || $state->value=='XX' || $state->value == 'TS'){
            continue;
       }
         $options .= '<option value="'.$state->value.'">'.$state->name.'</option>';
    }
    if(!empty($GetDIDAPIStatesListResponse->states)){
      echo $options;
    }
  }catch(SoapFault $e){
   
  }

}

function get_ratecenter($state){
  $GetDIDAPIRatecentersListRequest = array('country' => 'US','state'=>$state['state']);
  try{
    $GetDIDAPIRatecentersListResponse = SignupHelper::MakeSoapCall('get_ratecenters','DIDAPI', $GetDIDAPIRatecentersListRequest, 'DIDAPI');
    $options = "<option>Select City</option>";
    foreach ($GetDIDAPIRatecentersListResponse->ratecenters as $ratecenter) {
        $options.= '<option value="'.$ratecenter->value.'">'.$ratecenter->name.'</option>';
    }
    if(!empty($GetDIDAPIRatecentersListResponse->ratecenters)){
      echo $options;
    }
  }catch(SoapFault $e){
  }
}

function get_numbers($data){
  $GetDIDAPINumbersListRequest = array('country' => 'US','state'=>$data['state'],'ratecenter'=>$data['city']);
  //DIDAPI::DIDww
  //DIDAPI::MagicTelecom
  $package = array('ThinQ', 'DIDww', 'MagicTelecom');
  $list =array();
  foreach ($package as $key => $value) {

      /*
      $area_code = isset($data['area']) ? $data['area'] : "";
      if(!empty($area_code) && $area_code[0] !== '1') $area_code = '1'.$area_code;
      $request = array('limit'=>9, 'offset'=>0, 'number'=>$area_code.'%','usage'=>'I');
      $Response = SignupHelper::MakeSoapCall('get_number_list','DID',$request, 'Admin');
      $list = $Response->number_list;*/
      $GetDIDAPINumbersListRequest['package'] = 'DIDAPI::'.$value;
      $Response = SignupHelper::MakeSoapCall('get_numbers','DIDAPI', $GetDIDAPINumbersListRequest, 'DIDAPI');
    if(!empty($Response->numbers) && (is_array($Response->numbers) || is_object($Response))){
      $list = $Response->numbers;
    }
    if(!empty($list)){
      break;
    }
  }
  $count = 0;
  $res = '';
  $_SESSION['didinfo'] = $list;
  foreach ($list as $key) {
    if( isset($key->frozen) && ($key->frozen === 'Y' || isset($key->i_customer) || !isset($key->owner_batch_name) )) continue;
    if(preg_match('/c2/', $key->number)) continue;
    $key->number = strtok($key->number, '_') ? strtok($key->number, '_') : $key->number;
    $count++;
    $res.='<div class="number-result-item local">
            <label class="radio-container btn btn-lg font-bold body-bold" for="'.$key->number.'">
              <input type="radio" class="hide" name="local_did" value="'.$key->number.'" id="'.$key->number.'">
              <i class="far fa-circle"></i>
              '.$key->number.'</label>

          </div>';
  }
  if($count>0){
   echo $res;
  }else{
   echo "There are no numbers available for the selected city";
  }

  echo '<script>
            var state = $("#statess");
            var stat = $("#stat");
            var btnlg = $(".btn-lg1");
            var byarea = $("#byarea");
            var bycity = $("#bycity");
            var btnlg2 = $(".btn-lg2");
            var btn_local_pick = $("#btn_local_pick");
            var btn_local_keep = $("#btn_local_keep");
            var searchby = $(".SearchDID");
            var search = $(".Search");
            var keepnumb = $("#keepnumb");
           btnlg2.on("click", function(e){
           e.preventDefault();
           var aclick1 = $(this);
           btnlg2.removeClass("btn-uv-blue");
           aclick1.addClass("btn-uv-blue");
           if(aclick1.attr("id") === "btn_local_pick"){
             keepnumb.hide();
             search.show();
           }else{
             searchby.hide();
             keepnumb.show();
           }

        });
        btnlg.on("click", function(e){
           e.preventDefault();
           var aclick = $(this);
           btnlg.removeClass("btn-uv-blue");
           aclick.addClass("btn-uv-blue");
           if(aclick.attr("id") === "btn_local_search_by_areacode"){
             bycity.hide();
             byarea.show();
           }else{
             byarea.hide();
             bycity.show();
           }

        });
        var did_buttons = $(".local");
        var did_i = $(".radio-container").find("i");
        did_buttons.on("click", function(e){
           e.preventDefault();
           var label_i = $(this).find("i");
           var label_number = $(this).find("input").val();
           did_i.removeClass("fas fa-check-circle");
           did_i.addClass("far fa-circle");
           label_i.removeClass("far fa-circle");
           label_i.addClass("fas fa-check-circle");
           $("#didnumber").val(label_number);
        });

       </script>';
}

function order_number($country, $ratecenter,$number,$package,$city_id, $i_account){
  
  $DIDAPIOrderNumberRequest = array('country' => $country,'ratecenter'=>$ratecenter, 'city_id'=>$city_id, 'number'=>$number, 'package'=>$package);
  try{
    $DIDAPIOrderNumberResponse = $did_client->order_number($DIDAPIOrderNumberRequest);
    if($DIDAPIOrderNumberResponse->result){ //
        $result = $DIDAPIOrderNumberResponse->result; 
        if($result->number){ //$result->number
          echo $result->number;
        }
    }
  }catch(SoapFault $e){
    error_log('Order failed: '.$e->getMessage());
  }
 
}
?>
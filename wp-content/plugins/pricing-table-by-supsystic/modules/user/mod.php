<?php
class userPts extends modulePts {
   protected $_data = array();
   protected $_curentID = 0;
   protected $_dataLoaded = false;
   public function loadUserData() {
      return $this->getCurrent();
   }
   public function isAdmin() {
      if (!function_exists('wp_get_current_user')) {
         framePts::_()->loadPlugins();
      }
      return current_user_can(framePts::_()->getModule('adminmenu')->getMainCap());
   }
   public function getCurrentUserPosition() {
      if ($this->isAdmin()) return PTS_ADMIN;
      else if ($this->getCurrentID()) return PTS_LOGGED;
      else return PTS_GUEST;
   }
   public function getCurrent() {
      return wp_get_current_user();
   }
   public function getCurrentID() {
      $this->_loadUserData();
      return $this->_curentID;
   }
   protected function _loadUserData() {
      if (!$this->_dataLoaded) {
         if (!function_exists('wp_get_current_user')) framePts::_()->loadPlugins();
         $user = wp_get_current_user();
         $this->_data = $user->data;
         $this->_curentID = $user->ID;
         $this->_dataLoaded = true;
      }
   }
}

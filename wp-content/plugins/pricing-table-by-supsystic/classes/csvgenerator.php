<?php
class csvgeneratorPts {
   protected $_filename = '';
   protected $_delimiter = ';';
   protected $_enclosure = "\n";
   protected $_data = array();
   protected $_escape = '\\';
   public function __construct($filename) {
      $this->_filename = $filename;
   }
   public function addCell($x, $y, $value) {
      $this->_data[$x][$y] = '"' . $value . '"';
   }
   public function generate() {
      $strData = '';
      if (!empty($this->_data)) {
         $rows = array();
         foreach ($this->_data as $cells) {
            $rows[] = implode($this->_delimiter, $cells);
         }
         $strData = implode($this->_enclosure, $rows);
      }
      filegeneratorPts::_($this->_filename, $strData, 'csv')->generate();
   }
   public function getDelimiter() {
      return $this->_delimiter;
   }
   public function getEnclosure() {
      return $this->_enclosure;
   }
   public function getEscape() {
      return $this->_escape;
   }
}

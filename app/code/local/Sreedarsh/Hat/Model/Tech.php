<?php

/**
* Customer Request
*
* @category      Fingent
* @package       Fingent_Cr
* @author Sreedarsh <sreedarsh.a@fingent.com>
*/
class Sreedarsh_Hat_Model_Tech extends Mage_Core_Model_Abstract {

    public function _construct()
   {
      parent::_construct();
      $this->_init('sreedarsh_hat/tech');
   }
   public function reset()
    {
        $this->setData(array());
        $this->setOrigData();
        $this->_attributes = null;
        return $this;
    }

}

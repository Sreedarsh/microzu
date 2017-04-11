<?php

/**
* Customer Request
*
* @category      Fingent
* @package       Fingent_Cr
* @author Sreedarsh <sreedarsh.a@fingent.com>
*/
class Sreedarsh_Hat_Model_Resource_Child_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct()
    {
        parent::_construct();
        $this->_init('sreedarsh_hat/child');
    }

}
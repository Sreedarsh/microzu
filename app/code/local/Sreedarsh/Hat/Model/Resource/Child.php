<?php

/**
* Customer Request
*
* @category      Fingent
* @package       Fingent_Cr
* @author Sreedarsh <sreedarsh.a@fingent.com>
*/
class Sreedarsh_Hat_Model_Resource_Child extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('sreedarsh_hat/child', 'id');
    }

}
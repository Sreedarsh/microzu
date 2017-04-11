<?php
class Sreedarsh_Registration_Model_System_Config_Source_Org extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(

                array(
                    "label" => Mage::helper("sreedarsh_registration")->__("University"),
                    "value" =>  1
                ),

                array(
                    "label" => Mage::helper("sreedarsh_registration")->__("College"),
                    "value" =>  2
                ),

                array(
                    "label" => Mage::helper("sreedarsh_registration")->__("High School"),
                    "value" =>  3
                ),

                array(
                    "label" => Mage::helper("sreedarsh_registration")->__("Trade School"),
                    "value" =>  4
                ),
               
                array(
                    "label" => Mage::helper("sreedarsh_registration")->__("Others"),
                    "value" =>  5
                ),

            );
        }
        return $this->_options;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $_options = array();
        foreach ($this->getAllOptions() as $option) {
            $_options[$option["value"]] = $option["label"];
        }
        return $_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions();
        foreach ($options as $option) {
            if ($option["value"] == $value) {
                return $option["label"];
            }
        }
        return false;
    }
}
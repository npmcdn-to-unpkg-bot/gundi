<?php
namespace Core\Library\Validator;

use Core\Contract\Validator\IValidator;

class Validator extends \Particle\Validator\Validator implements IValidator
{

    /**
     * @var \Particle\Validator\ValidationResult
     */
    private $_oValidateResult;

    /**
     * @param array $aVars - vars for validate
     * @exception Exception
     * @return  void
     */
    public function validateOrFail($aVars)
    {
        if (!$this->isValid($aVars))
        {
            throw (new Exception('core.not_valid_data'))->setErrors($this->_oValidateResult->getMessages());
        }
    }

    /**
     * @param array $aVars - vars for validate
     * @return boolean
     */
    public function isValid($aVars)
    {
        if (!is_array($aVars)){
            $aVars = (array)$aVars;
        }
        $this->_oValidateResult = $this->validate($aVars);
        return $this->_oValidateResult->isValid();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $this->_oValidateResult->getMessages();
    }
}
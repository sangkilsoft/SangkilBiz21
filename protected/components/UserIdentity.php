<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;
    private $_unit = 'No Default Unit';

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $user = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$user->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {

            $this->_id = $user->id;
            $this->username = $user->username;

            $unit = Userunit::model()->find('id=? AND is_default=?', array($this->_id, TRUE));

            if (count($unit) > 0)
                $this->_unit = $unit->unt->dscrp;
            
            $this->setState('unit', $this->_unit);

            $this->setMMenu("");
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }

    public function getUnit() {
        return $this->getState('unit');
    }

    protected function setMMenu($val) {
        $this->setState('mmenu', $val);
    }

    protected function setUnit($val) {
        $this->setState('unit', $val);
    }

}
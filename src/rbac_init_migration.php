<?php

if (!class_exists('m140506_102106_rbac_init')) {
    include \Yii::getAlias('@yii/rbac/migrations/m140506_102106_rbac_init.php');
}

class rbac_init_migration extends \m140506_102106_rbac_init
{
    public $authManager;

    /**
     * Override method to allow injection of used authManager from trait.
     * @return \yii\rbac\DbManager
     */
    public function getAuthManager()
    {
        if ($this->authManager !== null) {
            return $this->authManager;
        }

        return parent::getAuthManager();
    }
}

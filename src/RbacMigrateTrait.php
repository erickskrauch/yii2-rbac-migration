<?php
namespace ErickSkrauch\Yii2;

use yii\helpers\Console;

trait RbacMigrateTrait
{
    /**
     * @return \yii\rbac\ManagerInterface the auth manager to be used for migration.
     */
    protected abstract function getAuthManager();

    /**
     * @param string $name
     * @param string|null $description
     * @return ItemBuilder
     */
    public function createPermission($name, $description = null)
    {
        $this->begin("create permission $name");
        /** @var \yii\rbac\Permission $permission */
        $permission = $this->createItem('createPermission', $name, $description);
        $this->done();

        return new ItemBuilder($this->getAuthManager(), $permission);
    }

    /**
     * @param string $name
     * @param string|null $description
     * @return ItemBuilder
     */
    public function createRole($name, $description = null)
    {
        $this->begin("create role $name");
        /** @var \yii\rbac\Role $role */
        $role = $this->createItem('createRole', $name, $description);
        $this->done();

        return new ItemBuilder($this->getAuthManager(), $role);
    }

    public function removePermission($name)
    {
        $this->begin("remove permission $name");
        $this->removeItem('getRole', $name);
        $this->done();
    }

    public function removeRole($name)
    {
        $this->begin("remove permission $name");
        $this->removeItem('getPermission', $name);
        $this->done();
    }

    /**
     * @param string $method
     * @param string $name
     * @param string|null $description
     *
     * @return \yii\rbac\Permission|\yii\rbac\Rule
     */
    private function createItem($method, $name, $description = null)
    {
        /** @var \yii\rbac\Permission|\yii\rbac\Rule $item */
        $item = $this->getAuthManager()->$method($name);
        if ($description !== null) {
            $item->description = $description;
        }

        $this->getAuthManager()->add($item);

        return $item;
    }

    private function removeItem($method, $name)
    {
        /** @var \yii\rbac\Permission|\yii\rbac\Rule $item */
        $item = $this->getAuthManager()->$method($name);
        $this->getAuthManager()->remove($item);
    }

    private $beginTime;

    private function begin($action)
    {
        Console::stdout("    > $action ...");
        $this->beginTime = microtime(true);
    }

    private function done()
    {
        Console::output(' done (time: ' . sprintf('%.3f', microtime(true) - $this->beginTime) . 's)');
    }

}

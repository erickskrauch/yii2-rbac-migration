<?php
namespace ErickSkrauch\Yii2;

use yii\helpers\Console;

/**
 * RbacMigrateTrait contains shortcut methods to build and morph structure of Yii2 RBAC.
 *
 * @author ErickSkrauch <erickskrauch@ely.by>
 */
trait RbacMigrateTrait
{
    /**
     * @return \yii\rbac\ManagerInterface the auth manager to be used for migration.
     */
    protected abstract function getAuthManager();

    /**
     * Create new permission, add it to authManager and return builder object
     * @param string $name new permission name
     * @param string|null $description optional description, which will be passed to created permission item
     * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
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
     * Create new role, add it to authManager and return builder object
     * @param string $name new role name
     * @param string|null $description optional description, which will be passed to created role item
     * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
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

    /**
     * Remove permission by passed name
     * @param string $name name of removing permission
     */
    public function removePermission($name)
    {
        $this->begin("remove permission $name");
        $this->removeItem('getPermission', $name);
        $this->done();
    }

    /**
     * Remove role by passed name
     * @param string $name name of removing role
     */
    public function removeRole($name)
    {
        $this->begin("remove role $name");
        $this->removeItem('getRole', $name);
        $this->done();
    }

    /**
     * Initialize RBAC structure. This is alternative to execute
     * yii migrate --migrationPath=@yii/rbac/migrations/
     * from console
     */
    public function initRbacStructure()
    {
        $this->createBaseMigrationClass()->up();
    }

    /**
     * Call down method of RBAC migration. This is alternative to execute
     * yii migrate/down --migrationPath=@yii/rbac/migrations/
     * from console
     */
    public function rollbackRbacStructure()
    {
        $this->createBaseMigrationClass()->down();
    }

    /**
     * @param string $method
     * @param string $name
     * @param string|null $description
     * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
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

    private function createBaseMigrationClass()
    {
        if (!class_exists('rbac_init_migration')) {
            include __DIR__ . DIRECTORY_SEPARATOR . 'rbac_init_migration.php';
        }

        return new \rbac_init_migration(['authManager' => $this->getAuthManager()]);
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

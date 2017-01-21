# Yii2 RBAC Migration

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Simple and useful extension for migrations in Yii2. It allows you to perform operations on the Roles and Permission
directly within the database migrations. This can be useful if you assign the Roles and Permissions for users in the
application and not during the generation of RBAC.

Extension allows you to create, update and remove Roles, Permissions and Rules, and also contains useful builder to
easy roles creation.

**A warning**: if you using constants for Roles, Permissions or Rules names, you should not to use it inside migration classes!
Use simple strings with it names. Constants values during application development can change, but you old migrations
should stay persistent.

## Install

Via Composer

``` bash
$ composer require erickskrauch/yii2-rbac-migration
```

## Usage

First of all we must *use trait*. You can do it within the migration file:

``` php
<?php

class m160705_120827_init extends \yii\db\Migration
{
    use \ErickSkrauch\Yii2\RbacMigrateTrait;

    public function safeUp()
    {
        $this->createPermission('upload_data');
    }

    public function safeDown()
    {
        $this->removePermission('upload_data');
    }

    protected function getAuthManager()
    {
        return Yii::$app->authManager;
    }
}
```

But it's somewhat uncomfortable due to the fact, that every time you will have to implement a `getAuthManager()` method.

Therefore, a more convenient way is to create your own base migration class, which already is use this trait
and implemented the required method. For [Yii2 Advanced Template](https://github.com/yiisoft/yii2-app-advanced) this
can be done by creating `\console\models\Migration` class with the following contents:

```php
<?php
namespace console\models;

use ErickSkrauch\Yii2\RbacMigrateTrait;
use yii\db\Migration as YiiMigration;

class Migration extends YiiMigration
{
    use RbacMigrateTrait;

    protected function getAuthManager()
    {
        return \Yii::$app->authManager;
    }
}
```

...and extending the migration class from the newly created:

``` php
<?php

use console\models\Migration;

class m160705_120827_init extends Migration
{
    public function safeUp()
    {
        $this->createPermission('upload_data');
    }

    public function safeDown()
    {
        $this->removePermission('upload_data');
    }
}
```

In search of the ideal, you can override in the console application configuration migration path to the you own template
file, which will be immediately with necessary base class.

In the future, you can use the methods as follows:

```
// Init db structure, create few permissions and one role and assing permissions to that role
public function safeUp()
{
    $this->initRbacStructure();
    $this->createPermission('view_invoices');
    $this->createPermission('data_analysis');
    $this->createPermission('upload_data');
    $this->createRole('accountant')
         ->addPermission('view_invoices')
         ->addPermission('data_analysis')
         ->addPermission('upload_data');
}

public function safeDown()
{
    $this->removePermission('view_invoices');
    $this->removePermission('data_analysis');
    $this->removePermission('upload_data');
    $this->removeRole('accountant');
    $this->rollbackRbacStructure();
}
```

## Available methods

### RbacMigrateTrait

#### createPermission($name, $description = null, $ruleName = null): ItemBuilder

Create new permission, add it to authManager and return [ItemBuilder](#itembuilder) object.

#### createRole($name, $description = null): ItemBuilder

Create new role, add it to authManager and return [ItemBuilder](#itembuilder) object.

#### addRule($className, $name): void

Add new rule to auth manager. If class no more exists by provided $className, then it will be created and added
to authManager, so you old migrations will not fail if you change or delete original rule.

#### updatePermission($oldName, $newName, $newDescription = false, $newRule = false): void

Method allows you to change signature of exists permission. If `$newDescription` passed as (bool)false, then description
will not be changed. The same behavior with $newRule.

#### updateRole($oldName, $newName, $newDescription = false): void

Method allows you to change signature of exists role. If `$newDescription` passed as (bool)false, then description
will not be changed.

#### removePermission($name): void

Remove permission by passed name.

#### removeRole($name): void

Remove role by passed name.

#### removeRule($name): void

Removing rule from auth manager. If class no more exists by provided $name, then it will be created and removed
from authManager, so you old migrations will not fail if you change or delete original rule

#### getRole($role): ItemBuilder

Return [ItemBuilder](#itembuilder) object for passed role name.

#### getPermission($permission): ItemBuilder

Return [ItemBuilder](#itembuilder) object for passed permission name.

#### initRbacStructure(): void

Initialize RBAC structure. This is alternative to execute `yii migrate --migrationPath=@yii/rbac/migrations/`
from console

#### rollbackRbacStructure(): void

Call down method of RBAC migration. This is alternative to execute
`yii migrate/down --migrationPath=@yii/rbac/migrations/` from console

### ItemBuilder

#### addPermission($permission): ItemBuilder

Add child permission by passed permission object or it's name. `$this` will be returned.

#### addRole($role): ItemBuilder

Add child role by passed role object or it's name. `$this` will be returned.

#### removePermission($permission): ItemBuilder

Remove child permission by passed permission object or it's name. `$this` will be returned.

#### removeRole($role): ItemBuilder

Remove child role by passed role object or it's name. `$this` will be returned.

## Change log

Until the `1.0.0` version will be released, backwards compatibility is guaranteed only in patch releases.
Minor updates may include breaking changes, so check [CHANGELOG](CHANGELOG.md) when updating the minor version.

## Testing

For now I have not figured out how to test it and whether it should be tested.
If you have any ideas, contributes are welcome.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [ErickSkrauch][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/erickskrauch/yii2-rbac-migration.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/erickskrauch/yii2-rbac-migration.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/erickskrauch/yii2-rbac-migration
[link-downloads]: https://packagist.org/packages/erickskrauch/yii2-rbac-migration
[link-author]: https://github.com/erickskrauch
[link-contributors]: ../../contributors

# Data Layer SQlServer

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)


###### The data layer is a persistent abstraction component of your database that PDO has prepared instructions for performing common routines such as registering, reading, editing, and removing data.

O data layer é um componente para abstração de persistência no seu banco de dados (SQL SERVER) que usa PDO com prepared statements para executar rotinas comuns como cadastrar, ler, editar e remover dados.

### Highlights

- Easy to set up (Fácil de configurar)
- Total CRUD asbtration (Asbtração total do CRUD)
- Create safe models (Crie de modelos seguros)
- Composer ready (Pronto para o composer)
- PSR-2 compliant (Compatível com PSR-2)

## Installation

Data Layer is available via Composer:

```bash
"vresende/datalayer": "^1.5.3"
```

or run

```bash
composer require vresende/datalayer
```

## Documentation

###### For details on how to use the Data Layer, see the sample folder with details in the component directory

Para mais detalhes sobre como usar o Data Layer, veja a pasta de exemplo com detalhes no diretório do componente

#### connection

######To begin using the Data Layer, you need to connect to the database (SQL Server). For more connections [PDO connections manual on PHP.net](https://www.php.net/manual/pt_BR/pdo.drivers.php)

Para começar a usar o Data Layer precisamos de uma conexão com o seu banco de dados (SQL Server). Para ver as conexões possíveis acesse o [manual de conexões do PDO em PHP.net](https://www.php.net/manual/pt_BR/pdo.drivers.php)

```php
define("DATA_LAYER_CONFIG", [
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "datalayer_example",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);
```

#### your model

######The Data Layer is based on an MVC structure with the Layer Super Type and Active Record design patterns. Soon to consume it is necessary to create the model of your table and inherit the Data Layer.

O Data Layer é baseado em uma estrutura MVC com os padrões de projeto Layer Super Type e Active Record. Logo para consumir é necessário criar o modelo de sua tabela e herdar o Data Layer.

```php
class User extends DataLayer
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        //string "TABLE_NAME", array ["REQUIRED_FIELD_1", "REQUIRED_FIELD_2"], string "PRIMARY_KEY", bool "TIMESTAMPS"
        parent::__construct("users", ["first_name", "last_name"]);
    }
}
```

#### find

```php
<?php
use Example\Models\User;
$model = new User();

//find all users
$users = $model->find()->fetch(true);

//find all users limit 2
$users = $model->find()->limit(2)->fetch(true);

//find all users limit 2 offset 2
$users = $model->find()->limit(2)->offset(2)->fetch(true);

//find all users limit 2 offset 2 order by field ASC
$users = $model->find()->limit(2)->offset(2)->order("first_name ASC")->fetch(true);

//looping users
foreach ($users as $user) {
    echo $user->first_name;
}

//find one user by condition
$user = $model->find("first_name = :name", "name=Vinicius")->fetch();
echo $user->first_name;
```

#### findById

```php
<?php
use Example\Models\User;

$model = new User();
$user = $model->findById(2);
echo $user->first_name;
```

#### count

```php
<?php
use Example\Models\User;
$model = new User();

$count = $model->find()->count();
```

#### save create

```php
<?php
use Example\Models\User;
$user = new User();

$user->first_name = "Vinicius";
$user->last_name = "Resende";
$userId = $user->save();
```

#### save update

```php
<?php
use Example\Models\User;
$user = (new User())->findById(2);

$user->first_name = "Vinicius";
$userId = $user->save();
```

#### destroy

```php
<?php
use Example\Models\User;
$user = (new User())->findById(2);

$user->destroy();
```

#### fail

```php
<?php
use Example\Models\User;
$user = (new User())->findById(2);

if($user->fail()){
    echo $user->fail()->getMessage();
}
```

## Contributing

Please see [CONTRIBUTING](https://github.com/vresende/datalayer/blob/master/CONTRIBUTING.md) for details.

## Support

###### Security: If you discover any security related issues, please email vini_handball@hotmail.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para vini_handball@hotmail.com em vez de usar o rastreador de problemas.

Thank you

## Credits
- [Vinicius R Santos](https://github.com/vresende) (Developer)
- [Robson V. Leite](https://github.com/robsonvleite) (Based on Upinside.com FSPHP course)

## License

The MIT License (MIT). Please see [License File](https://github.com/vresende/datalayer/blob/master/LICENSE) for more information.

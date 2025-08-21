_Ob:_ Cada branch é um assunto diferente, sendo o main o projeto sem qualquer alteração.

# Container e ServiceProvider

[Documentação Laravel - Service Container](https://laravel.com/docs/12.x/container)

É possível fazer uma ligação para vincular a declaração de uma interface injetada numa classe com _Property Promotion_. O que sem essa vinculação (ligação) causaria um erro. Afinal, não se pode instanciar interfaces.

Veja, o _Property Promotion_ no [HomeController](https://github.com/Grabium/Laravel-cases/blob/1-service-container-e-service-provider/app/Http/Controllers/HomeController.php) (Jeito 3).

## Quando usar Container?

Em muitos casos, graças à injeção automática de dependência e fachadas, você pode construir aplicativos Laravel sem nunca vincular manualmente ou resolver qualquer coisa do recipiente. **Então, quando você interagiria manualmente com o contêiner?** Vamos examinar duas situações.

- Primeiro, se você escrever uma classe que implementa uma interface e deseja digitar essa interface em uma rota ou construtor de classe, você deve dizer ao contêiner como resolver essa interface.
- Em segundo lugar, se você estiver escrevendo um pacote Laravel que você planeja compartilhar com outros desenvolvedores Laravel, talvez seja necessário vincular os serviços do seu pacote no contêiner.


## Adquirindo uma instância de App

Dentro de uma classe que estenda Container (ServiceProvider, por exemplo):

```php
//Retornam, ambos, o singleton de \Illuminate\Foundation\Application
app(); /* Ou */ $this->app;
```


Fora de Container é possível acessar por uma Facade:

```php
use Illuminate\Support\Facades\App;

App

//use assim:

App::make(Sub\Dir\Class::class);
```


## Fazendo um bind() (ligação/vinculação)

Essas são as opções de métodos para fazer uma vinculação na camada de Providers:


```php
//Faça a vincluação a partir desse singleton em um register de uma classe que herda de SeviceProvider:
app()->bind(Interface $interface, Concrete|callable $concrete);//salva em bindingns e cria uma nova instância a cada chamada.
app()->singleton(Interface $interface, Concrete|callable $concrete);//salva em bindings e instances para chamadas futuras.
app()->instance(Interface $interface, Concrete $concrete);//salva em bindings e instances para chamadas futuras.
```

É possível criar um provider com o comando:

`php artisan make:provider NomeDoProvider`

Dessa maneira além da classe semelhante ao AppServiceProvider, ocorre uma inscrição dessa no documento `./bootstrap/providers.php`

```php
//    bootstrap/providers.php
<?php

return [
    App\Providers\NomeDoProvider::class,
    App\Providers\AppServiceProvider::class,
];
```

## Ligação contextual - Vinculação dinâmica

- Considere uma família de abstração (com interface nesse caso);
- Considere que classes diferentes vão usar usar a promoção de propriedade em seus construtores;
- Considere que cada classe irá chamar uma implementação diferente dessa interface.

Laravel fornece uma interface simples e fluente para definir esse comportamento:

```php
//Solicitantes
use App\Http\Controllers\Cliente1;
use App\Http\Controllers\Cliente2;
use App\Http\Controllers\Cliente3;

//Interface
use Illuminate\Contracts\Filesystem\Filesystem;

//Implementação. Veja que ela possui duas configurações ('local' e 's3') mas poderiam ser duas classes contretas
use Illuminate\Support\Facades\Storage;


//...Em ServiceProvider::register() 
//grupo (apenas com classe1) que retorna 'local'.
$this->app->when(Cliente1::class)
    ->needs(Filesystem::class)
    ->give(function () {
        return Storage::disk('local');
    });

//grupo (classe2 3 classe3) que retorna 'local'.
$this->app->when([Cliente2::class, Cliente3::class])
    ->needs(Filesystem::class)
    ->give(function () {
        return Storage::disk('s3');
    });
```

## Atributo de vinculação - Usando notação (PHP 8)

É possível dispensar toda essa codificação do **bind()** e semelhantes no provider usando apenas notação na declaração da interface:

```php
<?php
namespace App\Contracts;

use App\Services\FakeEventPusher;              //para amiente de teste e local (desenvolvimento).
use App\Services\RedisEventPusher;             //para ambiente de produção.
use Illuminate\Container\Attributes\Bind;      //necessário para funcionamento da notação.

#[Bind(RedisEventPusher::class)]
#[Bind(FakeEventPusher::class, environments: ['local', 'testing'])]
interface EventPusher
{
    // ...
}
```

Além disso, os atributos Singleton e Scoped podem ser aplicados para indicar se as ligações do contêiner devem ser resolvidas uma vez ou uma vez por solicitação / ciclo de vida do trabalho:

```php
use App\Services\RedisEventPusher;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;

#[Bind(RedisEventPusher::class)]
#[Singleton]
interface EventPusher
{
    // ...
}
```
## Atributos Contextuais

Ao invés de fazer uma notação na interface, pode-se fazer uma na promoção de propriedade no construtor da classe cliente:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class PhotoController extends Controller
{
    public function __construct(
        #[Storage('local')] protected Filesystem $filesystem
    ) {
        // ...
    }
}
```

Segue outros exemplos já possíveis:

```php
<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepository;
use App\Models\Photo;
use App\Repositories\DatabaseRepository;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Container\Attributes\Cache;
use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Context;
use Illuminate\Container\Attributes\DB;
use Illuminate\Container\Attributes\Give;
use Illuminate\Container\Attributes\Log;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Connection;
use Psr\Log\LoggerInterface;

class PhotoController extends Controller
{
    public function __construct(
        #[Auth('web')] protected Guard $auth,
        #[Cache('redis')] protected Repository $cache,
        #[Config('app.timezone')] protected string $timezone,
        #[Context('uuid')] protected string $uuid,
        #[Context('ulid', hidden: true)] protected string $ulid,
        #[DB('mysql')] protected Connection $connection,
        #[Give(DatabaseRepository::class)] protected UserRepository $users,
        #[Log('daily')] protected LoggerInterface $log,
        #[RouteParameter('photo')] protected Photo $photo,
        #[Tag('reports')] protected iterable $reports,
    ) {
        // ...
    }
}
```

E uma notação para capturar o usuário logado atualmente:

```php
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

Route::get('/user', function (#[CurrentUser] User $user) {
    return $user;
})->middleware('auth');
```

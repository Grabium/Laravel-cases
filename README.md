_Ob:_ Cada branch é um assunto diferente, sendo o main o projeto sem qualquer alteração.

# Container e ServiceProvider

[Documentação Laravel - Service Container](https://laravel.com/docs/12.x/container)

É possível fazer uma ligação para vincular a declaração de uma interface injetada numa classe com _Property Promotion_. O que sem essa vinculação (ligação) causaria um erro. Afinal, não se pode instanciar interfaces.


<hr />
## Fazendo um bind() (ligação/vinculação)
Essas são as opções de métodos para fazer uma vinculação na camada de Providers:

```php
//Retornam, ambos, o singleton de \Illuminate\Foundation\Application
app(); /* Ou */ $this->app;

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

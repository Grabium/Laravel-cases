_obs:_ Cada branch é um assunto diferente, sendo o _main_ o projeto sem qualquer alteração.

# FACADE

Diz-se "Façade". É uma expressão francesa.
As **Facades** são uma das funcionalidades mais elegantes e controversas do Laravel. Elas servem como uma "proxie estática" ou um "atalho estático" para os serviços que estão registrados no **Container de Serviços** do framework.

Pense nelas como uma forma de usar uma sintaxe estática e limpa para acessar instâncias de classes dinâmicas.

Apesar de parecerem classes estáticas, elas não são. Por baixo dos panos, quando você chama um método estático em uma Facade (por exemplo, **Route::get()**), o Laravel intercepta essa chamada e a encaminha para uma instância da classe que a Facade representa. Isso significa que a classe por trás da Facade ainda pode ser testada e substituída (mocked), mantendo os benefícios da injeção de dependência sem a verbosidade de injetar a classe em todos os lugares.

As Facades são uma forma conveniente de acessar funcionalidades comuns do Laravel sem precisar injetar manualmente a dependência em cada classe, mas também geram debates entre desenvolvedores sobre a visibilidade de dependências e a "magia" que elas escondem.

Todas as fachadas de Laravel são definidas em:

**`Illuminate\Support\Facades`**

Exmeplo de uso:

```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/cache', function () {
    return Cache::get('key');
});
```


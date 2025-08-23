<?php

namespace App\Http\Controllers;

use App\Contracts\ApiInterface;
use Illuminate\Http\Request;
use App\Library\ApiExample;


class HomeController extends Controller
{
    
        //Jeito 1 - Property Promotion de classe concreta.

    /*//Jeito padrão para injetar objetos concretos:
    public function __construct(
        protected ApiExample $api //Property Promotion (promoção de propriedades).
    )
    { }

    public function index(): void
    {
        echo '<h1>Seja bem vindo ao Larvel-Cases!</h1>';
        dd($this->api);
    }*/


    
        //Jeito 2 - Container resolvendo classe concreta.


    /*//Injeta classe concreta (no modo "in-line") pelo App que implementa Container e dispensa __construc:
    public function index(): void
    {
        echo '<h1>Seja bem vindo ao Larvel-Cases!</h1>';
        echo app(ApiExample::class)->fazAlgumaCoisa();//fazer um make() de uma implementação dispensa fazer um bind(). app() resolveu ApiExample.
        dump(app());//verifique o atributo "resolved". Verá:"App\Http\Controllers\HomeController" => true, "App\Library\ApiExample" => true.
    }*/

    
    
    
        //Jeito 3 - ServiceProvider resolve a Property Promotion da Interface. (ideal)

    //Recebendo e declarando atributo. O type-hint da Property Promotion está na interface e não na implementação.
    public function __construct(
        
        //Geraria um erro fatal (Interface não pode instanciar-se), mas o bind() resolve isso.
        //Resolve em App\Providers\AppServiceProvider::register() no app()->bind()
        protected ApiInterface $api  
    )
    { }

    public function index(): void
    {
        echo '<h1>Seja bem vindo ao Larvel-Cases!</h1>';
        echo $this->api->fazAlgumaCoisa();
        dd(app());//verifique o atributo "resolved" e "bindings". "App\Library\ApiInterface" é resolvida.
    }

    
    
    
        //Jeito 4 - ServiceProvider resolve o make() da Interface dispensando o construtor.

    /*//fazendo o bind() no provider e dispensando o __construct aqui:
    public function index(): void
    {
        echo '<h1>Seja bem vindo ao Larvel-Cases!</h1>';
        echo app(ApiInterface::class)->fazAlgumaCoisa();//Repare que é feito um make() na interface.
        dd(app());//verifique o atributo "resolved" e "bindings". "App\Library\ApiInterface" é resolvida.
    }*/
}

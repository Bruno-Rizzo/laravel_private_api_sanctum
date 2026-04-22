## CRIAR A MODEL USER E A MIGRATION E EXECUTAR

* php artisan make:model User -m


## INSTALAR O LARAVEL SANCTUM

* php artisan install:api

* rodar a migration

* adicionar ao model User:

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens;
}


## CRIAR SEED PARA NOVO USUÁRIO

* php artisan make:seed UsersTableSeeder

* php artisan db:seed --class=UsersTableSeeder


## CRIAR ENDPOINT DE LOGIN

* php artisan make:controller v1/AuthController

* criar o metodo login no AuthController:

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if(!$user){
            return ApiResponse::error('Invalid credentials', 401);
        }

        if(!Hash::check($credentials['password'], $user->password)){
            return ApiResponse::error('Invalid credentials', 401);
        }

        return ApiResponse::success([
            'token' => $user->createToken($user->name)->plainTextToken
        ]);
    }
}

* criar a rota no api_v1.php

Route::post('/login', [AuthController::class, 'login']);

* criar o endpoint no Postman

- Adicionar nova pasta 'Auth'

POST  localhost/api/v1/login/ 

{
    "email": "consumidor1@gmail.com",
    "password": "abc123"
}

Ao fazer a requisição será gerado o token


## PROTEGER AS ROTAS

* no api_v1.php:

Route::middleware('auth:sanctum')->group(function(){
 // Colocar aqui todas as rotas a serem protegidas
}

* para melhorar a resposta de rota não protegida:

- em bootstrap/app.php:

$exceptions->render(function(\Exception $e, Request $request){
    if($request->is('api/*')){

    if($e->getMessage() ===  "Route [login] not defined."){

        return ApiResponse::error(
        message: "Invalid or missing authentication token",
        code: 401,
        );

    }

        return ApiResponse::error(
        message: "An unexpected error ocurred",
        code: 500,
        errors:[$e->getMessage()]
        );
    }
});


## COMO ENVIAR O TOKEN PELO POSTMAN

* dentro do postman, procurar nos cabeçalhos a opçaõ 'Authorization" e selecionar o 'Bearer Token'



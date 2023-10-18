<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\User\UserRegisterRequest;
use App\Http\Requests\Api\V1\User\UserLoginRequest;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $roleRepository;
    public $userRepository;

    public function __construct(RoleRepository $roleRepository, UserRepository $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegisterRequest $request)
    {
        // Если role_id нет возвращаем 400 статус код
        if (empty($this->roleRepository->getRoleById($request->role_id))) {
            return response([
                'messages' => [
                    'role_id' => [
                        'Роли с данным ID нет'
                    ]
                ]
            ], 400);
        }

        // Создаем нового пользователя
        $newUser = new User();
        $newUser->first_name = $request->first_name;
        $newUser->last_name  = $request->last_name;
        $newUser->email      = $request->email;
        $newUser->role_id    = $request->role_id;
        $newUser->password   = bcrypt($request->password);
        $newUser->created_at = now();
        $newUser->save();

        if ($request->hasFile('user_avatar')) {
            $newUser->addMedia($request->file('user_avatar'))
                    ->toMediaCollection('user_avatar');
        }

        return response([
            'status' => true,
            'user_id' => $newUser->id
        ], 201);
    }

    public function login(UserLoginRequest $request)
    {
        // Ищем пользователя по email
        $user = $this->userRepository->getUserByEmailAndPassword($request->email);

        if (!empty($user)) {
            if (Hash::check($request->password, $user->password)) {
                // Добавляем аватарку в вывод(если она есть)
                $user->getMedia();
                if (count($user['media'])) {
                    $user['user_avatar_url'] = $user['media'][0]->getUrl();
                } else {
                    $user['user_avatar_url'] = null;
                }

                // Удаляем лишние данные от автарки
                unset($user['media']);

                // Создаем токен
                $token = $user->createToken('Sanctum token')->plainTextToken;
                
                // Возвращаем пользователя
                return response([
                    'status' => true,
                    'user'   => $user,
                    'token'  => $token,
                ], 200);
            } else {
                // Если пользователь найден, но пароль не совпадает
                return response([
                    'status'  => false,
                    'message' => 'Неверный пароль'
                ], 401);
            }
        } else {

            // Если пользователь не найден
            return response([
                'status'  => false,
                'message' => 'Пользователь не найден'
            ], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

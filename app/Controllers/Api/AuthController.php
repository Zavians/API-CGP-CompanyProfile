<?php

namespace App\Controllers\Api;

use CodeIgniter\Commands\Encryption\GenerateKey;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $UserModel;
    
    public function __construct() {
        $this->UserModel = new UserModel();        
    }

    public function register() {
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {
            $userEntityData = new User([
                'username' => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password')
            ]);

            $this->UserModel->save($userEntityData);
            $response = [
                'status' => true,
                'message' => 'Registrasi Akun Berhasil',
                'data' => []
            ];
        }

        return $this->respondCreated($response);
    }
    public function login()  {

        if (auth()->loggedIn()) {
            auth()->logout();
        }
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => 'Pastikan kembali akun login anda',
                'data' => []
            ];
        } else {
            $dataLogin = [
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password')
            ];

            $login = auth()->attempt($dataLogin);

            if (!$login->isOK()) {
                $response = [
                    'status' => false,
                    'message' => 'Akun Tidak Ada, Pastikan Kembali Akun Anda',
                    'data' => []
                ];
            } else {
                $dataUser = $this->UserModel->findById(auth()->id());
                $token = $dataUser ->generateAccessToken('Ini Token Anda');
                $auth_token = $token->raw_token;

                $response = [
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'data' => [
                        'token' => $auth_token
                    ]
                ];
            }
        }

        return $this->respondCreated($response);
    }

    public function profile()  {
        $user_id = auth()->id();
        $userLogin =  $this->UserModel->findById($user_id);
        $response = [
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => [
                'user' => $userLogin
            ]
            ];
        return $this->respondCreated($response);
    }

    public function logout()  {
        auth()->logout();
        auth()->user()->revokeAllAccessTokens();

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Logout Berhasil',
            'data' => []
        ]);

    }

    public function invalid() {
        return $this-> respondCreated([
            'status' => false,
            'message' => 'Akses Gagal',
            'data' => []
        ]);
    }

}

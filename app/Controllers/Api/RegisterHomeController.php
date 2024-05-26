<?php

namespace App\Controllers\Api;

use App\Models\HomeModel;
use App\Models\RegisterHomeModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class RegisterHomeController extends ResourceController
{
    protected $RegisterHomeModel;
    protected $HomeModel;

    public function __construct()
    {
        $this->RegisterHomeModel = new RegisterHomeModel();
        $this->HomeModel = new HomeModel();
    }

    public function addRegisterHome()
    {
        $selectedData = $this->request->getVar('home_id');
        $homeData = $this->HomeModel
            ->where('id_home', $selectedData)
            ->first();

        if ($homeData['status'] != 0) {
            $rules = [
                'nama_lengkap' => 'required|is_unique[registerhome.nama_lengkap]',
                'no_telp' => 'required',
                'email_addres' => 'required'
            ];

            if (!$this->validate($rules)) {
                $response = [
                    'status' => false,
                    'message' => $this->validator->getErrors(),
                    'data' => []
                ];
            } else {
                $dataUploaded = [
                    'home_id' => $selectedData,
                    'nama_lengkap' => $this->request->getVar('nama_lengkap'),
                    'no_telp' => $this->request->getVar('no_telp'),
                    'email_addres' => $this->request->getVar('email_addres'),
                ];
                $this->RegisterHomeModel->save($dataUploaded);
                $response = [
                    'status' => false,
                    'message' => 'Data Uploaded',
                    'data' => $dataUploaded
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Stock Rumah telah habis',
                'data' => []
            ];
        }

        return $this->respondCreated($response);
    }

    public function showRegisterHome()
    {
        $allData = $this->RegisterHomeModel->join('home', 'registerhome.home_id = home.id_home')->findAll();
        if (!empty($allData)) {
            $response = [
                'status' => true,
                'message' => 'Data Ditampilkan',
                'data' => $allData
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Tidak Ada Data',
                'data' => []
            ];
        }

        return $this->respondCreated($response);
    }

    public function showRegisterHomeById($id_registerhome = null)
    {
        $allData = $this->RegisterHomeModel
        ->join('home', 'registerhome.home_id = home.id_home')->find($id_registerhome);

        if ($allData) {
            $dataRegister = [
                'nama_lengkap' => $allData['nama_lengkap'],
                'no_telp' => $allData['no_telp'],
                'email_addres' => $allData['email_addres'],
            ];
            $dataHome = [
                'home_name'=> $allData['home_name'],
                'type' => $allData['type'],
                'description' => $allData['description'],
                'image_home' => $allData['image_home']
            ];

            $response = [
                'status' => true,
                'message' => 'Data ditampilkan',
                'data' => [
                    'dataRumah' => $dataHome,
                    'dataRegister' => $dataRegister
                ]
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Data Tidak Ada',
                'data' => []
            ];
        }
        return $this->respond($response);
    }

    public function updateRegisterHome()
    {
    }
    public function deleteRegisterHome($id_registerhome = null)
    {
        $allData = $this->RegisterHomeModel
        ->join('home', 'registerhome.home_id = home.id_home')->find($id_registerhome);

        if (!empty($allData)) {
            $this->RegisterHomeModel->delete($allData);
            $response = [
                'status' => true,
                'message' => 'Data Terhapus',
                'data' => []
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Data Tidak ditemukan',
                'data' => []
            ];
        }

        return $this->respondDeleted($response);
    }
}

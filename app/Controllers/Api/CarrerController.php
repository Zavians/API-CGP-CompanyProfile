<?php

namespace App\Controllers\Api;

use App\Models\CarrerModel;
use App\Models\RegisterCarrer;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CarrerController extends ResourceController
{
    protected $CarrerModel;
    protected $RegisterCarrer;

    public function __construct()
    {
        $this->CarrerModel = new CarrerModel();
        $this->RegisterCarrer = new RegisterCarrer();
    }


    // ADMIN RULES
    public function addCarrer()
    {
        $rules = [
            'carrer_name' => 'required|is_unique[carrer.carrer_name]',
            'description' => 'required',
            'publish_date' => 'required',
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {
            $user_id = auth()->id();
            $addCarrer = [
                'user_id' => $user_id,
                'carrer_name' => $this->request->getVar('carrer_name'),
                'description' => $this->request->getVar('description'),
                'publish_date' => $this->request->getVar('publish_date'),
                'status' => 1,
            ];
            $this->CarrerModel->save($addCarrer);
            $response = [
                'status' => true,
                'message' => 'Carrer Added',
                'data' => [
                    $addCarrer
                ]
            ];
        }
        return $this->respondCreated($response);
    }

    public function showCarrer()
    {
        $dataCarrer = $this->CarrerModel->findAll();

        if (!empty($dataCarrer)) {
            $response = [
                'status' => true,
                'message' => 'Data Carrer',
                'data' => [
                    $dataCarrer
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Belum ada data Carrer',
                'data' => []
            ];
        }
        return $this->respondCreated($response);
    }

    public function showCarrerById($id_carrer = null)
    {
        $dataCarrer = $this->CarrerModel->find($id_carrer);
        if (!empty($dataCarrer)) {
            $response = [
                'status' => true,
                'message' => 'Data Carrer',
                'data' => [
                    $dataCarrer
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Belum ada data Carrer',
                'data' => []
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateCarrer($id_carrer = null)
    {
        $carrerInfo = $this->CarrerModel->find($id_carrer);
        if (!empty($carrerInfo)) {
            $carrer_name = $this->request->getVar('carrer_name');
            if (isset($carrer_name) && !empty($carrer_name)) {
                $carrerInfo['carrer_name'] = $carrer_name;
            }

            $description = $this->request->getVar('description');
            if (isset($description) && !empty($description)) {
                $carrerInfo['description'] = $description;
            }

            $publish_date = $this->request->getVar('publish_date');
            if (isset($publish_date) && !empty($publish_date)) {
                $carrerInfo['publish_date'] = $publish_date;
            }

            $status = $this->request->getVar('status');
            if (isset($status) && !empty($status)) {
                $carrerInfo['status'] = $status;
            }

            $this->CarrerModel->update($id_carrer, $carrerInfo);
            $response = [
                'status' => true,
                'message' => 'Carrer Updated',
                'data' => $carrerInfo
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'ID Carrer Tidak Tersedia ',
                'data' => []
            ];
        }

        return $this->respondUpdated($response);
    }

    public function deleteCarrer($id_carrer = null)
    {
        $data = $this->CarrerModel->find($id_carrer);

        if (!empty($data)) {
            $cekData = $this->RegisterCarrer->where('carrer_id', $id_carrer)->findAll();
            if (!empty($cekData)) {
                $response = [
                    'status' => true,
                    'message' => 'Data Sedang Digunakan',
                    'data' => []
                ];
            } else {
                $this->CarrerModel->delete($data);
                $response = [
                    'status' => true,
                    'message' => 'Data Deleted',
                    'data' => []
                ];
            }
        } else {
            $response = [
                'status' => true,
                'message' => 'Data Not Found',
                'data' => []
            ];
        }
        return $this->respondDeleted($response);
    }
}

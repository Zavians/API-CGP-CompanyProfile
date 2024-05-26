<?php

namespace App\Controllers\Api;

use App\Models\HomeModel;
use App\Models\RegisterHomeModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class HomeController extends ResourceController
{
    protected $HomeModel;
    protected $RegisterHomeModel;

    public function __construct()
    {
        $this->HomeModel = new HomeModel();
        $this->RegisterHomeModel = new RegisterHomeModel();
    }

    public function addHome()
    {
        $rules = [
            'home_name' => 'required|is_unique[home.home_name]',
            'type' => 'required',
            'description' => 'required',
            'publish_date' => 'required'
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {
            $imagesFile = $this->request->getFile('image_home');
            $imagesName = $imagesFile->getName();
            $tempArray = explode(".", $imagesName);
            $newImageName = round(microtime(true)) . "." . end($tempArray);
            $user_id = auth()->id();

            if ($imagesFile->move('images/Home', $newImageName)) {
                $dataUploaded = [
                    'user_id' => $user_id,
                    'home_name' => $this->request->getVar('home_name'),
                    'type' => $this->request->getVar('type'),
                    'description' => $this->request->getVar('description'),
                    'publish_date' => $this->request->getVar('publish_date'),
                    'status' => 1,
                    'image_home' => $newImageName
                ];

                $this->HomeModel->save($dataUploaded);
                $response = [
                    'status' => true,
                    'message' => 'Data Uploaded',
                    'data' => []
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Failed Upload File',
                    'data' => []
                ];
            }
        }
        return $this->respondCreated($response);
    }

    public function showHome()
    {
        $showData = $this->HomeModel->findAll();

        if (!empty($showData)) {
            $response = [
                'status' => false,
                'message' => 'Home Data',
                'data' => $showData
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'No Data Found',
                'data' => []
            ];
        }
        return $this->respondCreated($response);
    }

    public function showHomeById($id_home = null)
    {
        $findData = $this->HomeModel->find($id_home);
        if (!empty($findData)) {
            $response = [
                'status' => false,
                'message' => 'Home Data',
                'data' => $findData
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'No Data Found',
                'data' => []
            ];
        }
        return $this->respondCreated($response);
    }

    public function updateHome($id_home = null)
    {
        $homeInfo = $this->HomeModel->find($id_home);

        if (!empty($homeInfo)) {
            $home_name = $this->request->getVar('home_name');
            if (isset($home_name) && !empty($home_name)) {
                $homeInfo['home_name'] = $home_name;
            }

            $type = $this->request->getVar('type');
            if (isset($type) && !empty($type)) {
                $homeInfo['type'] = $type;
            }

            $description = $this->request->getVar('description');
            if (isset($description) && !empty($description)) {
                $homeInfo['description'] = $description;
            }

            $status = $this->request->getVar('status');
            if (isset($status) && !empty($status)) {
                $homeInfo['status'] = $status;
            }

            $imagesFile = $this->request->getFile('image_home');
            if (!empty($imagesFile)) {
                $imagesName = $imagesFile->getName();
                $tempArray = explode(".", $imagesName);
                $newImageName = round(microtime(true)) . "." . end($tempArray);

                if ($imagesFile->move('images/Home', $newImageName)) {
                    $homeInfo['images_home'] = $newImageName;
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Fail Image Uploaded'
                    ];
                }
            }

            $this->HomeModel->update($id_home, $homeInfo);
            $response = [
                'status' => true,
                'message' => 'Data Updated',
                'data' => $homeInfo
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'No Data Found',
                'data' => []
            ];
        }
        return $this->respondUpdated($response);
    }

    public function deleteHome($id_home)
    {
        $findData = $this->HomeModel->find($id_home);
        if (!empty($findData)) {
            $cekData = $this->RegisterHomeModel->where('home_id', $id_home)->findAll();
            if (!empty($cekData)) {
                $response = [
                    'status' => true,
                    'message' => 'Data Sedang digunakan',
                    'data' => []
                ];
            } else {
                $this->HomeModel->delete($findData);
                $response = [
                    'status' => true,
                    'message' => 'Data Deleted',
                    'data' => []
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'No Data Found',
                'data' => []
            ];
        }
        return $this->respondDeleted($response);
    }
}

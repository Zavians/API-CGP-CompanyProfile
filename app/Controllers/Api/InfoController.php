<?php

namespace App\Controllers\Api;

use App\Models\CompanyInfoModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class InfoController extends ResourceController
{
    protected $CompanyInfoModel;

    public function __construct()
    {
        $this->CompanyInfoModel = new CompanyInfoModel();
    }


    public function addInfo()
    {
        $rules = [
            'judul' => 'required|is_unique[company_info.judul]',
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
            $imagesFile = $this->request->getFile('image_info');
            $imagesName = $imagesFile->getName();
            $tempArray = explode(".", $imagesName);
            $newImageName = round(microtime(true)) . "." . end($tempArray);
            $user_id = auth()->id();

            if ($imagesFile->move('images/Info', $newImageName)) {

                $dataUploaded = [
                    'user_id' => $user_id,
                    'judul' => $this->request->getVar('judul'),
                    'description' => $this->request->getVar('description'),
                    'publish_date' => $this->request->getVar('publish_date'),
                    'image_info' => 'info' . $newImageName
                ];
                $this->CompanyInfoModel->save($dataUploaded);
                $response = [
                    'status' => true,
                    'message' => "Data Uploaded",
                    'data' => $dataUploaded
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => "Failed To Procces Data",
                    'data' => []
                ];
            }
        }

        return $this->respondCreated($response);
    }

    public function showAllInfo()
    {
        $allData = $this->CompanyInfoModel->findAll();

        if (!empty($allData)) {
            $response = [
                'status' => true,
                'message' => 'Data Ditampilkan',
                'data' => $allData
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Data tidak ditemukan',
                'data' => []
            ];
        }
         return $this->respond($response);
    }

    public function showInfoById()
    {
        $user_id = auth()->id();
        $infoById = $this->CompanyInfoModel->where([
            'user_id' => $user_id
        ])->findAll();

        if (!empty($infoById)) {
            $response = [
                'status' => true,
                'message' => 'Data Ditampilkan',
                'data' => $infoById
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'User Ini belum menambahkan data',
                'data' => []
            ];
        }

        return $this->respondCreated($response);
    }

    public function updateInfo($id_info = null)
    {

        $companyInfo = $this->CompanyInfoModel->find($id_info);
        if (!empty($companyInfo)) {
            $judul = $this->request->getVar('judul');
            if (isset($judul) && !empty($judul)) {
                $companyInfo['judul'] = $judul;
            }

            $description = $this->request->getVar('description');
            if (isset($description) && !empty($description)) {
                $companyInfo['description'] = $description;
            }

            $publish_date = $this->request->getVar('publish_date');
            if (isset($publish_date) && !empty($publish_date)) {
                $companyInfo['publish_date'] = $publish_date;
            }

            $imagesFile = $this->request->getFile('image_info');

            if (!empty($imagesFile)) {
                $imagesName = $imagesFile->getName();
                $tempArray = explode(".", $imagesName);
                $newImageName = round(microtime(true)) . "." . end($tempArray);

                if ($imagesFile->move('images/Info', $newImageName)) {
                    $companyInfo['images_info'] = $newImageName;
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Fail Image Uploaded'
                    ];
                }
            }

            $this->CompanyInfoModel->update($id_info, $companyInfo);
            $response = [
                'status' => true,
                'message' => 'Data Updated',
                'data' => $companyInfo
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Data Tidak Ditemukan',
                'data' => []
            ];
        }
        return $this->respondUpdated($response);
    }

    public function deleteInfo($id_info = null)
    {
        $deletedData = $this->CompanyInfoModel->find($id_info);
        if (!empty($deletedData)) {
            $this->CompanyInfoModel->delete($deletedData);
            $response = [
                'status' => true,
                'message' => 'Data Deleted',
                'data' => []
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Id Tidak Tersedia',
                'data' => []
            ];
        }
            return $this->respondDeleted($response);
    }
}

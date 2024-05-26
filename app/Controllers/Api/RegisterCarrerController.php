<?php

namespace App\Controllers\Api;

use App\Models\CarrerModel;
use App\Models\RegisterCarrer;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class RegisterCarrerController extends ResourceController
{
    protected $RegisterCarrer;
    protected $CarrerModel;

    public function __construct()
    {
        $this->RegisterCarrer =  new RegisterCarrer();
        $this->CarrerModel =  new CarrerModel();
    }
    // $allData = $this->RegisterCarrer->join('carrer', 'registercarrer.carrer_id = carrer.id_carrer')->find($find_data);
    public function addRegisterCarrer()
    {
        $selectedCarrerId = $this->request->getVar('carrer_id');

        $carrerData = $this->CarrerModel
            ->where('id_carrer', $selectedCarrerId)
            ->first();

        if ($carrerData['status'] != 0) {
            $rules = [
                'nama_lengkap' => 'required|is_unique[registercarrer.nama_lengkap]',
                'no_telp' => 'required',
                'email_addres' => 'required|valid_email',
                'status_pendidikan' => 'required',
                'resume' => 'uploaded[resume]|mime_in[resume,application/pdf]|max_size[resume,2048]',
                'cover_letter' => 'uploaded[cover_letter]|mime_in[cover_letter,application/pdf]'
            ];

            if (!$this->validate($rules)) {
                $response = [
                    'status' => false,
                    'message' => $this->validator->getErrors(),
                    'data' => []
                ];
            } else {
                $resumeFile = $this->request->getFile('resume');
                $resumeName = $resumeFile->getName();
                $tempArray = explode(".", $resumeName);
                $newResumeName = round(microtime(true)) . "." . end($tempArray);

                if ($resumeFile->move('File/Carrer/Resume', $newResumeName)) {
                    $coverFile = $this->request->getFile('cover_letter');
                    $coverName = $coverFile->getName();
                    $tempArray = explode(".", $coverName);
                    $newCoverName = round(microtime(true)) . "." . end($tempArray);

                    if ($coverFile->move('File/Carrer/Cover', $newCoverName)) {
                        $dataUploaded = [
                            'carrer_id' => $selectedCarrerId,
                            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
                            'no_telp' => $this->request->getVar('no_telp'),
                            'email_addres' => $this->request->getVar('email_addres'),
                            'status_pendidikan' => $this->request->getVar('status_pendidikan'),
                            'resume' => $newResumeName,
                            'cover_letter' => $newCoverName
                        ];

                        $this->RegisterCarrer->save($dataUploaded);
                        $response = [
                            'status' => true,
                            'message' => "Data Uploaded",
                            'data' => $dataUploaded
                        ];
                    } else {
                        $response = [
                            'status' => false,
                            'message' => "Failed To Upload Cover Letter",
                            'data' => []
                        ];
                    }
                } else {
                    $response = [
                        'status' => false,
                        'message' => "Failed To Upload Resume",
                        'data' => []
                    ];
                }
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Lowongan Tidak Terbuka',
                'data' => []
            ];
        }


        return $this->respond($response);
    }
    public function showRegisterCarrer()
    {
        $allData = $this->RegisterCarrer
        ->join('carrer', 'registercarrer.carrer_id = carrer.id_carrer')
        ->findAll();

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

    public function showRegisterCarrerById($id_registercarrer = null)
    {
        $allData = $this->RegisterCarrer
        ->join('carrer', 'registercarrer.carrer_id = carrer.id_carrer')
        ->find($id_registercarrer);

        if ($allData) {
            $dataRegister = [
                'nama_lengkap' => $allData['nama_lengkap'],
                'no_telp' => $allData['no_telp'],
                'email_addres' => $allData['email_addres'],
                'status_pendidikan' => $allData['status_pendidikan'],
                'resume' => $allData['resume'],
                'cover_letter' => $allData['cover_letter']
            ];

            $dataCarrer = [
                'carrer_name' => $allData['carrer_name'],
                'description' => $allData['description']
            ];

            $response = [
                'status' => true,
                'message' => 'Data Ditampilkan',
                'data' => [
                    'dataPelamar' => $dataRegister,
                    'dataCarrerPelamar' => $dataCarrer
                ]
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

    public function updateRegisterCarrer()
    {
    }

    public function deleteRegisterCarrer($id_registercarrer = null)
    {
        $deletedData = $this->RegisterCarrer->find($id_registercarrer);

        if (!empty($deletedData)) {
            $delete = $this->RegisterCarrer->delete($deletedData);
            $response = [
                'status' => false,
                'message' => 'Data Terhapus',
                'data' => []
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Tidak Ada Data',
                'data' => []
            ];
        }
        return $this->respond($response);
    }
}

<?php

namespace App\Controllers\Backend\Modules;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use App\Models\JobListModel;
use App\Models\ApplicantsModel;
use App\Models\ResultsModel;
use App\Models\KriteriaModel;
use App\Models\KriteriaNilaiModel;
use App\Models\KriteriaHasilModel;
use App\Models\SubkriteriaModel;
use App\Models\SubkriteriaNilaiModel;
use App\Models\SubkriteriaHasilModel;
use App\Models\ApplicantsNilaiModel;

class Recruitment extends BaseController
{
    function __construct()
    {
        $this->users = new UsersModel();
        $this->jobList = new JobListModel();
        $this->applicants = new ApplicantsModel();
        $this->results = new ResultsModel();
        $this->appliNilai = new ApplicantsNilaiModel();
        $this->criteria = new KriteriaModel();
        $this->cnilai = new KriteriaNilaiModel();
        $this->chasil = new KriteriaHasilModel();
        $this->subcriteria = new SubkriteriaModel();
        $this->subcnilai = new SubkriteriaNilaiModel();
        $this->subchasil = new SubkriteriaHasilModel();
    }

    public function jobApplicants()
    {
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Job Applicants',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/recruitment/job_applicants', $data);
    }

    public function listApplicants()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        // $list_data = $this->tblSubkriteria;
        $list_data = $this->applicants;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = $no;
            $row[] = $lists->nm_applicants;
            $row[] = $lists->pend_applicants;
            $row[] = $lists->jurusan_applicants;
            $row[] = $lists->nilai_ijazah;

            $buttons = $lists->deleted_at == null ? 
            '<button id="detail-applicants" type="button" class="btn btn-outline-secondary btn-sm mr-3" data-idapplicants="' . $lists->id_applicants . '" data-toggle="tooltip" title="Detail ' . $lists->nm_applicants . '" data-placement="bottom"><i class="fas fa-eye fa-sm"></i></button>
            <button id="edit-applicants" type="button" class="btn btn-outline-success btn-sm mr-3" data-idapplicants="' . $lists->id_applicants . '" data-toggle="tooltip" title="Edit ' . $lists->nm_applicants . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>' :'';
            
            $buttons .= $role == 1 && $lists->deleted_at != null ? 
                '<button id="restore-applicants" type="button" class="btn btn-outline-info btn-sm mr-3" data-idapplicants="' . $lists->id_applicants . '" data-toggle="tooltip" title="Restore ' . $lists->nm_applicants . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
                <button id="remove-applicants" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idapplicants="' . $lists->id_applicants . '" data-toggle="tooltip" title="Remove ' . $lists->nm_applicants . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' :
                '<button id="delete-applicants" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idapplicants="' . $lists->id_applicants . '" data-toggle="tooltip" title="Delete ' . $lists->nm_applicants . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>';

            $applicants = $this->appliNilai->getApplicants($lists->id_applicants);
            $buttons .= $applicants == null ? '<a href="' . base_url("recruitment/evalApplicants/$lists->slug") . '" class="btn btn-outline-primary btn-sm mr-3" data-toggle="tooltip" title="Nilai ' . $lists->nm_applicants . '" data-placement="bottom"><i class="fas fa-file-signature fa-sm"></i></a>' : "";
            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function detailApplicants()
    {
        if ($this->request->isAJAX()) {
            $applicants = $this->applicants->getApplicants($this->request->getVar('applicantsId'));
            $output = [
                'modals' => view('backend/layout/modals'),
                'form' => view('backend/form/detail_applicants'),
                'applicants' => $applicants
            ];
            echo json_encode($output);
        }
    }

    public function tambahApplicants()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhApplicants") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/applicants'),
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtApplicants") {
                $applicants = $this->applicants->getApplicants($this->request->getVar('applicantsId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/applicants'),
                    'applicants' => $applicants,
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveApplicants()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhApplicants") {
            $rules = [
                'name' => [
                    'rules' => 'required|trim|is_unique[applicants.nm_applicants]',
                    'errors' => [
                        'required' => 'Nama Tidak Boleh Kosong',
                        'is_unique' => 'Nama Sudah Ada'
                    ]
                ],
                'pendidikan' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Pendidikan Tidak Boleh Kosong'
                    ]
                ],
                'jurusan' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Jurusan Tidak Boleh Kosong'
                    ]
                ],
                'nilai' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Nilai Ijazah Tidak Boleh Kosong'
                    ]
                ],
                'posisi' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Posisi Terakhir Tidak Boleh Kosong'
                    ]
                ],
                'pengalaman' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Pengalaman Tidak Boleh Kosong'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'name' => $this->validation->getError('name'),
                    'pendidikan' => $this->validation->getError('pendidikan'),
                    'jurusan' => $this->validation->getError('jurusan'),
                    'nilai' => $this->validation->getError('nilai'),
                    'posisi' => $this->validation->getError('posisi'),
                    'pengalaman' => $this->validation->getError('pengalaman'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $slug = url_title(substr($this->request->getVar('name'), 0, 30), '-', TRUE);
                $dataApplicants = [
                    'nm_applicants' => $this->request->getVar('name'),
                    'pend_applicants' => $this->request->getVar('pendidikan'),
                    'jurusan_applicants' => $this->request->getVar('jurusan'),
                    'nilai_ijazah' => $this->request->getVar('nilai'),
                    'posisi_terakhir' => $this->request->getVar('posisi'),
                    'pengalaman' => $this->request->getVar('pengalaman'),
                    'slug' => $slug,
                    'posisi' => 1,
                ];
                $this->applicants->insert($dataApplicants);
                $output = [
                    'status' => true,
                    'token' => $token
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtApplicants") {
            $applicantsId = $this->request->getVar('id');
            $applicantsLama = $this->applicants->getApplicants($applicantsId);
            if ($applicantsLama->nm_applicants == $this->request->getVar('name')) {
                $rule_applicants = 'required|trim';
            } else {
                $rule_applicants = 'required|trim|is_unique[applicants.nm_applicants]';
            }
            $rules = [
                'name' => [
                    'rules' => $rule_applicants,
                    'errors' => [
                        'required' => 'Applicants Tidak Boleh Kosong',
                        'is_unique' => 'Applicants Sudah Ada'
                    ]
                ],
                'pendidikan' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Pendidikan Tidak Boleh Kosong'
                    ]
                ],
                'jurusan' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Jurusan Tidak Boleh Kosong'
                    ]
                ],
                'nilai' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Nilai Ijazah Tidak Boleh Kosong'
                    ]
                ],
                'posisi' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Posisi Terakhir Tidak Boleh Kosong'
                    ]
                ],
                'pengalaman' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Pengalaman Tidak Boleh Kosong'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'name' => $this->validation->getError('name'),
                    'pendidikan' => $this->validation->getError('pendidikan'),
                    'jurusan' => $this->validation->getError('jurusan'),
                    'nilai' => $this->validation->getError('nilai'),
                    'posisi' => $this->validation->getError('posisi'),
                    'pengalaman' => $this->validation->getError('pengalaman'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $applicantsId = $this->request->getVar('id');
                $slug = url_title(substr($this->request->getVar('name'), 0, 30), '-', TRUE);
                $dataApplicants = [
                    'nm_applicants' => $this->request->getVar('name'),
                    'pend_applicants' => $this->request->getVar('pendidikan'),
                    'jurusan_applicants' => $this->request->getVar('jurusan'),
                    'nilai_ijazah' => $this->request->getVar('nilai'),
                    'posisi_terakhir' => $this->request->getVar('posisi'),
                    'pengalaman' => $this->request->getVar('pengalaman'),
                    'slug' => $slug,
                    'posisi' => 1,
                ];
                $this->applicants->update($applicantsId, $dataApplicants);
                $output = [
                    'status' => true,
                    'token' => $token
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteApplicants()
    {
        $applicants = $this->applicants->getApplicants($this->request->getVar('applicantsId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteApplicants") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'applicants' => $applicants,
                    'text' => "Are you sure to deleted this applicants??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeApplicants") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'applicants' => $applicants,
                    'text' => "Are you sure to deleted permanent this applicants??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreApplicants") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'applicants' => $applicants,
                    'text' => "Are you sure to restore this applicants??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusApplicants()
    {
        $applicantsId = $this->request->getVar('applicantsId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteApplicants") {
            $this->applicants->delete($applicantsId);
            $output = [
                'status' => true,
                'title' => "Applicants Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeApplicants") {
            $this->applicants->delete($applicantsId, true);
            $output = [
                'status' => true,
                'title' => "Applicants Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreApplicants") {
            $this->applicants->update($applicantsId, ['deleted_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "Applicants Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function evalApplicants($slug = null)
    {
        $kriteria = $this->criteria->getKriteria();
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Job Applicants',
            'sub_title2' => 'Evaluation',
            'kriteria' => $kriteria,
            'slug' => $this->applicants->getApplicantsSlug($slug),
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/recruitment/eval_applicants_management', $data);
    }

    public function saveNilaiApplicants()
    {
        $token = csrf_hash();
        $error=FALSE;
        $msg="";
        $total = 0;
        foreach($_POST as $k=>$v)
        {
            foreach($v as $k2=>$v2)
            {
                $hasilKrit = $this->chasil->getKriteriaHasil($k2);
                $idHasilKrit = $hasilKrit->id_kriteria_hasil;
                $hasilSubkrit = $this->subchasil->getSubkriteriaHasil($v2);
                $idHasilSubrit = $hasilSubkrit->id_subkriteria_hasil;
                $d=array(
                    'id_applicants' => $k,
                    'id_kriteria_hasil'=>$k2,
                    'id_subkriteria_hasil'=>$v2,
                );
                $nilHasilKrit = $hasilKrit->prioritas;
                $nilHasilSubkrit = $hasilSubkrit->prioritas;
                $totPrio = $nilHasilKrit * $nilHasilSubkrit;
                $total += $totPrio;
                $Listposisi = $this->jobList->getJobList();
                foreach ($Listposisi as $job) {
                    if($job->nil_min == $job->nil_max){
                        if ($total >= $job->nil_max) {
                            $posisi =  $job->id_job_list;
                            break;
                        }
                    }else{
                        if ($total >= $job->nil_min && $total < $job->nil_max) {
                            $posisi = $job->id_job_list;
                            break;
                        }
                    }
                }
                $this->appliNilai->insert($d);
                $this->applicants
                        ->where('id_applicants', $k)
                        ->set('tot_nilai', $total)
                        ->set('posisi', $posisi)
                        ->update();
            }
            $error=FALSE;
        }
        if($error==FALSE)
        {
            $output = [
                'status' => true,
                'msg' => "Berhasil",
                'token' => $token
            ];
			echo json_encode($output);
		}else{
            $output = [
                'status' => false,
                'msg' => $msg,
                'token' => $token
            ];
			echo json_encode($output);
		}
    }

    public function managementKriteria()
    {
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Criteria Management',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/recruitment/kriteria_management', $data);
    }

    public function listKriteria()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        $list_data = $this->criteria;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $lists->nm_kriteria;
            $row[] = ($lists->is_aktif == 1) ? '<p class="badge badge-warning">Active</p>' : '<p class="badge badge-danger">Not Active</p>';

            $buttons = $lists->deleted_at == null ? 
            '<button id="edit-kriteria" type="button" class="btn btn-outline-success btn-sm mr-3" data-idkriteria="' . $lists->id_kriteria . '" data-toggle="tooltip" title="Edit ' . $lists->nm_kriteria . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>
            <button id="delete-kriteria" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idkriteria="' . $lists->id_kriteria . '" data-toggle="tooltip" title="Delete ' . $lists->nm_kriteria . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : '';

            $buttons .= $role == 1 && $lists->deleted_at == null ? 
            '<a href="' . base_url("recruitment/subcriteria/$lists->slug") . '" class="btn btn-outline-primary btn-sm mr-3" data-toggle="tooltip" title="Subkriteria ' . $lists->nm_kriteria . '" data-placement="bottom"><i class=" fas fa-stream fa-sm"></i></a>' : '';

            $buttons .= $role == 1 && $lists->deleted_at != null ? 
            '<button id="restore-kriteria" type="button" class="btn btn-outline-info btn-sm mr-3" data-idkriteria="' . $lists->id_kriteria . '" data-toggle="tooltip" title="Restore ' . $lists->nm_kriteria . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-kriteria" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idkriteria="' . $lists->id_kriteria . '" data-toggle="tooltip" title="Remove ' . $lists->nm_kriteria . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : '';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function tambahKriteria()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhKriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/kriteria'),
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtKriteria") {
                $kriteria = $this->criteria->getKriteria($this->request->getVar('kriteriaId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/kriteria'),
                    'kriteria' => $kriteria,
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveKriteria()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhKriteria") {
            $rules = [
                'kriteria' => [
                    'rules' => 'required|trim|is_unique[kriteria.nm_kriteria]',
                    'errors' => [
                        'required' => 'Kriteria Tidak Boleh Kosong',
                        'is_unique' => 'Kriteria Sudah Ada'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'kriteria' => $this->validation->getError('kriteria'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('check-kriteriaaktif') == null) {
                    $kriteriaaktif = 0;
                } else {
                    $kriteriaaktif = $this->request->getVar('check-kriteriaaktif');
                }
                $slug = url_title(substr($this->request->getVar('kriteria'), 0, 30), '-', TRUE);
                $dataKriteria = [
                    'nm_kriteria' => $this->request->getVar('kriteria'),
                    'slug' => $slug,
                    'is_aktif' => $kriteriaaktif,
                ];
                $this->criteria->insert($dataKriteria);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Kriteria Berhasil Di Tambah"
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtKriteria") {
            $kriteriaId = $this->request->getVar('id');
            $kriteriaLama = $this->criteria->getKriteria($kriteriaId);
            if ($kriteriaLama->nm_kriteria == $this->request->getVar('kriteria')) {
                $rule_kriteria = 'required|trim';
                $slug = $kriteriaLama->slug;
            } else {
                $rule_kriteria = 'required|trim|is_unique[kriteria.nm_kriteria]';
            }
            $rules = [
                'kriteria' => [
                    'rules' => $rule_kriteria,
                    'errors' => [
                        'required' => 'Kriteria Tidak Boleh Kosong',
                        'is_unique' => 'Kriteria Sudah Ada'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'kriteria' => $this->validation->getError('kriteria'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('check-kriteriaaktif') == null) {
                    $kriteriaaktif = 0;
                } else {
                    $kriteriaaktif = $this->request->getVar('check-kriteriaaktif');
                }
                $kriteriaId = $this->request->getVar('id');
                $kriteriaLama = $this->criteria->getKriteria($kriteriaId);
                if ($kriteriaLama->nm_kriteria == $this->request->getVar('kriteria')) {
                    $slug = $kriteriaLama->slug;
                } else {
                    $slug = url_title(substr($this->request->getVar('kriteria'), 0, 30), '-', TRUE);
                }
                $dataKriteria = [
                    'nm_kriteria' => $this->request->getVar('kriteria'),
                    'slug' => $slug,
                    'is_aktif' => $kriteriaaktif,
                ];
                $this->criteria->update($kriteriaId, $dataKriteria);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Kriteria Berhasil Di Rubah"
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteKriteria()
    {
        $kriteria = $this->criteria->getKriteria($this->request->getVar('kriteriaId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteKriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'kriteria' => $kriteria,
                    'text' => "Are you sure to deleted this kriteria??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeKriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'kriteria' => $kriteria,
                    'text' => "Are you sure to deleted permanent this kriteria??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreKriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'kriteria' => $kriteria,
                    'text' => "Are you sure to restore this kriteria??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusKriteria()
    {
        $kriteriaId = $this->request->getVar('kriteriaId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteKriteria") {
            $this->criteria->delete($kriteriaId);
            $output = [
                'status' => true,
                'title' => "Kriteria Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeKriteria") {
            $this->criteria->delete($kriteriaId, true);
            $output = [
                'status' => true,
                'title' => "Kriteria Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreKriteria") {
            $this->criteria->update($kriteriaId, ['deleted_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "Kriteria Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function subkriteriaManagement($slug = null)
    {
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Criteria Management',
            'sub_title2' => 'Subcriteria',
            'slug' => $this->criteria->getKriteriaSlug($slug),
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/recruitment/subkriteria_management', $data);
    }

    public function listSubkriteria()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        // $list_data = $this->tblsubkeriteria;
        $list_data = $this->subcriteria;
        $id_subkriteria = $request->getPost("id_kriteria");
        $role = session()->get('role');
        $list = $list_data->get_datatables($role, $id_subkriteria);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = $no;
            $row[] = $lists->nm_kriteria;
            $row[] = $lists->nm_subkriteria;

            $buttons = $lists->delete_at == null ? 
            '<button id="edit-subkriteria" type="button" class="btn btn-outline-success btn-sm mr-3" data-idsubkriteria="' . $lists->id_subkriteria . '" data-toggle="tooltip" title="Edit ' . $lists->nm_subkriteria . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>
            <button id="delete-subkriteria" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idsubkriteria="' . $lists->id_subkriteria . '" data-toggle="tooltip" title="Delete ' . $lists->nm_subkriteria . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : '';

            $buttons .= $role == 1 && $lists->delete_at != null ? 
            '<button id="restore-subkriteria" type="button" class="btn btn-outline-info btn-sm mr-3" data-idsubkriteria="' . $lists->id_subkriteria . '" data-toggle="tooltip" title="Restore ' . $lists->nm_subkriteria . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-subkriteria" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idsubkriteria="' . $lists->id_subkriteria . '" data-toggle="tooltip" title="Remove ' . $lists->nm_subkriteria . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : '';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role, $id_subkriteria),
            "recordsFiltered" => $list_data->count_filtered($role, $id_subkriteria),
            "data" => $data,
            "id_subkriteria" => $id_subkriteria,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function tambahSubkriteria()
    {
        // $kriteria = $this->criteria->getKriteria();
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhSubkriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/subkriteria'),
                    // 'kriteria' => $kriteria,
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtSubkriteria") {
                $subkriteria = $this->subcriteria->getSubkriteria($this->request->getVar('subkriteriaId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/subkriteria'),
                    // 'kriteria' => $kriteria,
                    'subkriteria' => $subkriteria,
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveSubkriteria()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhSubkriteria") {
            if($this->request->getVar('option') == "teks"){
                $rules = [
                    'teks_subkriteria' => [
                        'rules' => 'required|trim',
                        'errors' => [
                            'required' => 'Keterangan Harus Diisi'
                        ]
                    ],
                ];
            }elseif($this->request->getVar('option') == "nilai"){
                $rules = [
                    'min_subkriteria' => [
                        'rules' => 'required|trim',
                        'errors' => [
                            'required' => 'Nilai Min Harus Diisi'
                        ]
                    ],
                    'max_subkriteria' => [
                        'rules' => 'required|trim',
                        'errors' => [
                            'required' => 'Nilai Max Harus Diisi'
                        ]
                    ],
                ];
            }
            if (!$this->validate($rules)) {
                if($this->request->getVar('option') == "teks"){
                    $error = [
                        'teks_subkriteria' => $this->validation->getError('teks_subkriteria'),
                    ];
                }elseif($this->request->getVar('option') == "nilai"){
                    $error = [
                        'min_subkriteria' => $this->validation->getError('min_subkriteria'),
                        'max_subkriteria' => $this->validation->getError('max_subkriteria'),
                    ];
                }
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if($this->request->getVar('option') == "teks"){
                    $dataSubkriteria = [
                        'id_kriteria' => $this->request->getVar('kriteriaId'),
                        'nm_subkriteria' => $this->request->getVar('teks_subkriteria'),
                        'tipe' => $this->request->getVar('option'),
                    ];
                }elseif($this->request->getVar('option') == "nilai"){
                    $min = $this->request->getVar('min_subkriteria');
                    $max = $this->request->getVar('max_subkriteria');
                    if($min == $max){
                        $nm_subkriteria = "x => ". $max;
                    }else{
                        $nm_subkriteria = $min . " <= x < " . $max;
                    }
                    $dataSubkriteria = [
                        'id_kriteria' => $this->request->getVar('kriteriaId'),
                        'nm_subkriteria' =>$nm_subkriteria,
                        'tipe' => $this->request->getVar('option'),
                        'min' => $min,
                        'max' => $max,
                    ];
                }

                $this->subcriteria->insert($dataSubkriteria);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Subkriteria Berhasil Di Tambah"
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtSubkriteria") {
            $subkriteriaId = $this->request->getVar('id');
            $subkriteriaLama = $this->subcriteria->getSubkriteria($subkriteriaId);
            if($this->request->getVar('option') == "teks"){
                if ($subkriteriaLama->nm_subkriteria = $this->request->getVar('teks_subkriteria')) {
                    $rule_subkriteria = 'required|trim';
                } else {
                    $rule_subkriteria = 'required|trim|is_unique[subkriteria.nm_subkriteria]';
                }
                $rules = [
                    'teks_subkriteria' => [
                        'rules' => $rule_subkriteria,
                        'errors' => [
                            'required' => 'Keterangan Harus Diisi',
                            'is_unique' => 'Sub Kriteria Sudah Ada'
                        ]
                    ],
                ];
            }elseif($this->request->getVar('option') == "nilai"){
                $rules = [
                    'min_subkriteria' => [
                        'rules' => 'required|trim',
                        'errors' => [
                            'required' => 'Nilai Min Harus Diisi'
                        ]
                    ],
                    'max_subkriteria' => [
                        'rules' => 'required|trim',
                        'errors' => [
                            'required' => 'Nilai Max Harus Diisi'
                        ]
                    ],
                ];
            }
            if (!$this->validate($rules)) {
                if($this->request->getVar('option') == "teks"){
                    $error = [
                        // 'kriteria' => $this->validation->getError('kriteria'),
                        'teks_subkriteria' => $this->validation->getError('teks_subkriteria'),
                    ];
                }elseif($this->request->getVar('option') == "nilai"){
                    $error = [
                        // 'kriteria' => $this->validation->getError('kriteria'),
                        'min_subkriteria' => $this->validation->getError('min_subkriteria'),
                        'max_subkriteria' => $this->validation->getError('max_subkriteria'),
                    ];
                }
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if($this->request->getVar('option') == "teks"){
                    $dataSubkriteria = [
                        'id_kriteria' => $this->request->getVar('kriteriaId'),
                        'nm_subkriteria' => $this->request->getVar('teks_subkriteria'),
                        'tipe' => $this->request->getVar('option'),
                    ];
                }elseif($this->request->getVar('option') == "nilai"){
                    $min = $this->request->getVar('min_subkriteria');
                    $max = $this->request->getVar('max_subkriteria');
                    if($min == $max){
                        $nm_subkriteria = "x => ". $max;
                    }else{
                        $nm_subkriteria = $min . " <= x < " . $max;
                    }
                    $dataSubkriteria = [
                        'id_kriteria' => $this->request->getVar('kriteriaId'),
                        'nm_subkriteria' =>$nm_subkriteria,
                        'tipe' => $this->request->getVar('option'),
                        'min' => $this->request->getVar('min_subkriteria'),
                        'max' => $this->request->getVar('max_subkriteria'),
                    ];
                }
                $this->subcriteria->update($subkriteriaId, $dataSubkriteria);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Subkriteria Berhasil Di Rubah"
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteSubkriteria()
    {
        $subkriteria = $this->subcriteria->getSubkriteria($this->request->getVar('subkriteriaId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteSubkriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'subkriteria' => $subkriteria,
                    'text' => "Are you sure to deleted this sub-kriteria??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeSubkriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'subkriteria' => $subkriteria,
                    'text' => "Are you sure to deleted permanent this sub-kriteria??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreSubkriteria") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'subkriteria' => $subkriteria,
                    'text' => "Are you sure to restore this sub-kriteria??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusSubkriteria()
    {
        $subkriteriaId = $this->request->getVar('subkriteriaId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteSubkriteria") {
            $this->subcriteria->delete($subkriteriaId);
            $output = [
                'status' => true,
                'title' => "Sub-kriteria Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeSubkriteria") {
            $this->subcriteria->delete($subkriteriaId, true);
            $output = [
                'status' => true,
                'title' => "Sub-kriteria Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreSubkriteria") {
            $this->subcriteria->update($subkriteriaId, ['delete_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "Sub-kriteria Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function ratioManagement()
    {
        $krit = array();
        $kriteria = $this->criteria->getKriteria();
        foreach($kriteria as $kri)
		{
			$krit[$kri->id_kriteria]=$kri->nm_kriteria;
		}
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Criteria Management',
            'sub_title2' => 'Ratio',
            'user' => $this->users->getUsers(session()->get('id_user')),
            'arr' => $krit,
            'kriteria' => $kriteria,
        );
        return view('backend/view/recruitment/ratio', $data);
    }

    public function saveNilai()
    {
        $token = csrf_hash();
    	$error=FALSE;
    	$msg="";
    	$cr=$this->request->getVar('crvalue');
    	if($cr > 0.01)
    	{
    		$msg="Gagal diupdate karena nilai CR kurang dari 0.01";
			$error=TRUE;
		}else{
			foreach($_POST as $k=>$v)
			{
				if($k!="crvalue")
				{
                    if ($k != 'prioritas') {
                        foreach($v as $x=>$x2)
                        {
                            $x2 = floatval($x2);
                            $d=array(
                            'kriteria_id_dari'=>$k,
                            'kriteria_id_tujuan'=>$x,
                            'nilai'=>$x2,
                            );
                            $existingData = $this->cnilai
                                                    ->where('kriteria_id_dari', $k)
                                                    ->where('kriteria_id_tujuan', $x)
                                                    ->first();
                            if ($existingData) {
                                $this->cnilai
                                        ->where('kriteria_id_dari', $k)
                                        ->where('kriteria_id_tujuan', $x)
                                        ->set('nilai', $x2)
                                        ->update();
                            } else {
                                $this->cnilai->insert($d);
                            }
                        }
                    }else{
                        foreach($v as $v=>$t)
                        {
                            $t = floatval($t);
                            $d=array(
                            'id_kriteria'=>$v,
                            'prioritas'=>$t,
                            );
                            $existingData = $this->chasil
                                                    ->where('id_kriteria', $v)
                                                    ->first();
                            if ($existingData) {
                                $this->chasil
                                        ->where('id_kriteria', $v)
                                        ->set('prioritas', $t)
                                        ->update();
                            } else {
                                $this->chasil->insert($d);
                            }
                        }
                    }
				}
                $msg="Berhasil update nilai dan bobot kriteria";
                $error=FALSE;
			}
		}	
    	
    	if($error==FALSE)
    	{
            $output = [
                'status' => true,
                'msg' => $msg,
                'token' => $token
            ];
			echo json_encode($output);
		}else{
            $output = [
                'status' => false,
                'msg' => $msg,
                'token' => $token
            ];
			echo json_encode($output);
		}
	}

    public function ratioSubManagement($slug)
    {
        $kriteria = $this->criteria->getKriteriaSlug($slug);
        $subkrit = array();
        $subkriteria = $this->subcriteria->getSubkriteriaByKriteria($kriteria->id_kriteria);
        foreach($subkriteria as $skri)
		{
			$subkrit[$skri->id_subkriteria]=$skri->nm_subkriteria;
		}
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Criteria Management',
            'sub_title2' => 'Ratio Subkriteria',
            'user' => $this->users->getUsers(session()->get('id_user')),
            'slug' => $this->criteria->getKriteriaSlug($slug),
            'arr' => $subkrit,
        );
        return view('backend/view/recruitment/ratioSub', $data);
    }

    public function saveSubratio()
    {
        $token = csrf_hash();
        $error=FALSE;
        $msg="";
        $cr=$this->request->getVar('crvalue');
        if($cr > 0.01)
        {
            $msg="Gagal diupdate karena nilai CR kurang dari 0.01";
			$error=TRUE;
		}else{
			foreach($_POST as $k1=>$v1)
			{
				if($k1!="crvalue")
				{
                    if ($k1 != 'prioritas') {
                        foreach($v1 as $k2=>$v2)
                        {
                            foreach($v2 as $k3=>$v3){
                                $v3 = floatval($v3);
                                $d=array(
                                    'id_kriteria' => $k2,
                                    'subkriteria_id_dari'=>$k1,
                                    'subkriteria_id_tujuan'=>$k3,
                                    'nilai'=>$v3,
                                );
                                $existingData = $this->subcnilai
                                                            ->where('id_kriteria', $k2)
                                                            ->where('subkriteria_id_dari', $k1)
                                                            ->where('subkriteria_id_tujuan', $k3)
                                                            ->first();
                                if ($existingData) {
                                    $this->subcnilai
                                        ->where('id_kriteria', $k2)
                                        ->where('subkriteria_id_dari', $k1)
                                        ->where('subkriteria_id_tujuan', $k3)
                                        ->set('nilai', $v3)
                                        ->update();
                                } else {
                                    $this->subcnilai->insert($d);
                                }
                            }
                        }
                    }else{
                        foreach($v1 as $k2=>$v2)
                        {
                            foreach($v2 as $k3=>$v3){
                                $v3 = floatval($v3);
                                $d=array(
                                'id_kriteria'=>$k3,
                                'id_subkriteria'=>$k2,
                                'prioritas'=>$v3,
                                );
                                $this->subchasil->insert($d);
                            }
                            $existingData = $this->subchasil
                                                    ->where('id_kriteria', $k3)
                                                    ->where('id_subkriteria', $k2)
                                                    ->first();
                            if ($existingData) {
                                $this->subchasil
                                        ->where('id_kriteria', $k3)
                                        ->where('id_subkriteria', $k2)
                                        ->set('prioritas', $v3)
                                        ->update();
                            } else {
                                $this->subcnilai->insert($d);
                            }
                        }
                    }
				}
                $error=FALSE;
			}
		}	
        if($error==FALSE)
        {
            $output = [
                'status' => true,
                'msg' => "Berhasil update nilai dan bobot kriteria",
                'token' => $token
            ];
			echo json_encode($output);
		}else{
            $output = [
                'status' => false,
                'msg' => $msg,
                'token' => $token
            ];
			echo json_encode($output);
		}
    }

    public function resultsApplicant()
    {
        $kriteria = $this->criteria->getKriteria();
        // $applicant = $this->applicants->getApplicants();
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Results',
            // 'posisi' => $this->jobList->getJobList(),
            'user' => $this->users->getUsers(session()->get('id_user')),
            'arr' => $kriteria,
            // 'arr2' => $applicant,
        );
        return view('backend/view/recruitment/results', $data);
    }

    public function listResult()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        $list_data = $this->results;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        // $no = $request->getPost("start");
        foreach ($list as $lists) {
            // $no++;
            $kriteria = $this->criteria->getKriteria();
            $row   = array();
            // $row[] = $no;
            $row[] = $lists->nm_applicants;
            foreach($kriteria as $kriteria){
                $applicantsId = $lists->id_applicants;
                $kriteriaId = $kriteria->id_kriteria;
                $kritHasil = getKriteriaHasil($kriteriaId);
                $kritHasilId = $kritHasil->id_kriteria_hasil;
                $subkritHasil = getSubkritHasilId($applicantsId,$kriteriaId);
                if($subkritHasil != null){
                    $subkritHasilId = $subkritHasil->id_subkriteria_hasil;
                    $prioKrit = nilPrioKrit($kritHasilId) ;
                    $prioSubkrit = nilPrioSubkrit($subkritHasilId);
                    $priorit = $prioKrit->prioritas * $prioSubkrit->prioritas;
                    $row[] = number_format((float)$priorit, 3);
                }else{
                    $row[] = 0;
                }
            }
            $row[] = number_format($lists->tot_nilai,3);
            $row[] = $lists->nm_posisi;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function jobList()
    {
        $data = array(
            'title' => 'Recruitment',
            'sub_title' => 'Job List',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/recruitment/jobList', $data);
    }

    public function listJob()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        $list_data = $this->jobList;
        // $list_data = $this->tblMenu;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $lists->nm_posisi;
            $row[] = $lists->nil_max;
            $row[] = $lists->nil_min;
            $row[] = ($lists->is_aktif == 1) ? '<p class="badge badge-warning">Active</p>' : '<p class="badge badge-danger">Not Active</p>';

            $buttons = $lists->delet_at == null ? 
            '<button id="edit-posisi" type="button" class="btn btn-outline-success btn-sm mr-3" data-idposisi="' . $lists->id_job_list . '" data-toggle="tooltip" title="Edit ' . $lists->nm_posisi . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>' : '';

            $buttons .= $role == 1 && $lists->delet_at != null ? 
            '<button id="restore-posisi" type="button" class="btn btn-outline-info btn-sm mr-3" data-idposisi="' . $lists->id_job_list . '" data-toggle="tooltip" title="Restore ' . $lists->nm_posisi . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-posisi" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idposisi="' . $lists->id_job_list . '" data-toggle="tooltip" title="Remove ' . $lists->nm_posisi . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : 
            '<button id="delete-posisi" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idposisi="' . $lists->id_job_list . '" data-toggle="tooltip" title="Delete ' . $lists->nm_posisi . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function tambahPosisi()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhPosisi") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/job_list'),
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtPosisi") {
                $posisi = $this->jobList->getJobList($this->request->getVar('posisiId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/job_list'),
                    'posisi' => $posisi,
                ];
                echo json_encode($output);
            }
        }
    }

    public function savePosisi()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhPosisi") {
            $rules = [
                'posisi' => [
                    'rules' => 'required|trim|is_unique[job_list.nm_posisi]',
                    'errors' => [
                        'required' => 'Posisi Tidak Boleh Kosong',
                        'is_unique' => 'Posisi Sudah Ada'
                    ]
                ],
                'max' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Max Harus Dipilih'
                    ]
                ],
                'min' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Min Harus Dipilih'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'posisi' => $this->validation->getError('posisi'),
                    'max' => $this->validation->getError('max'),
                    'min' => $this->validation->getError('min'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('check-jobList') == null) {
                    $jobListAktif = 0;
                } else {
                    $jobListAktif = $this->request->getVar('check-jobList');
                }
                $dataJobList = [
                    'nm_posisi' => $this->request->getVar('posisi'),
                    'nil_min' => $this->request->getVar('min'),
                    'nil_max' => $this->request->getVar('max'),
                    'is_aktif' => $jobListAktif,
                ];
                $this->jobList->insert($dataJobList);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Posisi Berhasil Di Tambah"
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtPosisi") {
            $posisiId = $this->request->getVar('id');
            $posisilama = $this->jobList->getJobList($posisiId);
            if ($posisilama->nm_posisi == $this->request->getVar('posisi')) {
                $rule_posisi = 'required|trim';
            } else {
                $rule_posisi = 'required|trim|is_unique[job_list.nm_posisi]';
            }
            $rules = [
                'posisi' => [
                    'rules' => $rule_posisi,
                    'errors' => [
                        'required' => 'Posisi Tidak Boleh Kosong',
                        'is_unique' => 'Posisi Sudah Ada'
                    ]
                ],
                'max' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Max Harus Dipilih'
                    ]
                ],
                'min' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Min Harus Dipilih'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'posisi' => $this->validation->getError('posisi'),
                    'max' => $this->validation->getError('max'),
                    'min' => $this->validation->getError('min'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('check-jobList') == null) {
                    $jobListAktif = 0;
                } else {
                    $jobListAktif = $this->request->getVar('check-jobList');
                }
                $dataJobList = [
                    'nm_posisi' => $this->request->getVar('posisi'),
                    'nil_min' => $this->request->getVar('min'),
                    'nil_max' => $this->request->getVar('max'),
                    'is_aktif' => $jobListAktif,
                ];
                $this->jobList->update($posisiId, $dataJobList);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Posisi Berhasil Di Rubah"
                ];
                echo json_encode($output);
            }
        }
    }

    public function deletePosisi()
    {
        $posisi = $this->jobList->getJobList($this->request->getVar('posisiId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deletePosisi") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'posisi' => $posisi,
                    'text' => "Are you sure to deleted this posisi??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removePosisi") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'posisi' => $posisi,
                    'text' => "Are you sure to deleted permanent this posisi??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restorePosisi") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'posisi' => $posisi,
                    'text' => "Are you sure to restore this posisi??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusPosisi()
    {
        $posisiId = $this->request->getVar('id');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deletePosisi") {
            $this->jobList->delete($posisiId);
            $output = [
                'status' => true,
                'title' => "Posisi Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removePosisi") {
            $this->jobList->delete($posisiId, true);
            $output = [
                'status' => true,
                'title' => "Posisi Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restorePosisi") {
            $this->jobList->update($posisiId, ['deleted_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "Posisi Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

}

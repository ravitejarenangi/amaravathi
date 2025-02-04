<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Additionalfeemaster extends Admin_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model('additionalfeetype_model');
        $this->load->model('additionalfeegroup_model');
        $this->load->model('additionalfeesessiongroup_model');
        $this->load->model('additionalfeegrouptype_model');
        
    }

    public function index()
    {

//         $this->session->set_userdata('top_menu', 'Fees Collection');
//         $this->session->set_userdata('sub_menu', 'admin/feemaster');
		
        $data['title']        = $this->lang->line('fees_master_list');
        $feegroup             = $this->additionalfeegroup_model->get();
        $data['feegroupList'] = $feegroup;
        $feetype              = $this->additionalfeetype_model->get();
        $data['feetypeList']  = $feetype;

        $feegroup_result       = $this->additionalfeesessiongroup_model->getFeesByGroup(null,0);
        $data['feemasterList'] = $feegroup_result;

        // $this->form_validation->set_rules('feetype_id', $this->lang->line('fee_type'), 'required');
        // $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');

        $this->form_validation->set_rules(
            'fee_groups_id', $this->lang->line('fee_group'), array(
                'required',
                array('check_exists', array($this->additionalfeesessiongroup_model, 'valid_check_exists')),
            )
        );

        // if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
        //     $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
        //     $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');

        // } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
        //     $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
        //     $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
        //     $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        // }

        if ($this->form_validation->run() == false) {

        } else {
            
            // if($this->input->post('fine_amount')){
            //     $fine_amount    =   convertCurrencyFormatToBaseAmount($this->input->post('fine_amount'));
            // }else{
            //     $fine_amount    = '';
            // }
            
            $insert_array = array(
                'fee_groups_id'   => $this->input->post('fee_groups_id'),
                'feetype_id'      => $this->input->post('feetype_id'),
                'session_id'      => $this->setting_model->getCurrentSession(),
                
            );

            $feegroup_result = $this->additionalfeesessiongroup_model->add($insert_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/additionalfeemaster/index');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/additionalfeemaster/feemasterList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
//         if (!$this->rbac->hasPrivilege('fees_master', 'can_delete')) {
//             access_denied();
//         }
        $data['title'] = $this->lang->line('fees_master_list');
        $this->additionalfeegrouptype_model->remove($id);
        redirect('admin/additionalfeemaster/index');
    }

    public function deletegrp($id)
    {
        $data['title'] = $this->lang->line('fees_master_list');
        $this->additionalfeesessiongroup_model->remove($id);
        redirect('admin/additionalfeemaster');
    }

    public function edit($id)
    {
//         if (!$this->rbac->hasPrivilege('fees_master', 'can_edit')) {
//             access_denied();
//         }
//         $this->session->set_userdata('top_menu', 'Fees Collection');
//         $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']            = $id;
        $feegroup_type         = $this->additionalfeegrouptype_model->get($id);
        $data['feegroup_type'] = $feegroup_type;
        $feegroup              = $this->additionalfeegroup_model->get();
        $data['feegroupList']  = $feegroup;
        $feetype               = $this->additionalfeetype_model->get();
        $data['feetypeList']   = $feetype;
        $feegroup_result       = $this->additionalfeesessiongroup_model->getFeesByGroup(null,0);
        $data['feemasterList'] = $feegroup_result;
        // $this->form_validation->set_rules('feetype_id', $this->lang->line('fee_type'), 'required');
        // $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');
        $this->form_validation->set_rules(
            'fee_groups_id', $this->lang->line('fee_group'), array(
                'required',
                array('check_exists', array($this->additionalfeesessiongroup_model, 'valid_check_exists')),
            )
        );

        if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
            $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/additionalfeemaster/feemasterEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            
            if($this->input->post('fine_amount')){
                $fine_amount    =   convertCurrencyFormatToBaseAmount($this->input->post('fine_amount'));
            }else{
                $fine_amount    = '';
            }
            
            $insert_array = array(
                'id'              => $this->input->post('id'),
                'feetype_id'      => $this->input->post('feetype_id'),
                
            );

            $feegroup_result = $this->additionalfeegrouptype_model->add($insert_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/additionalfeemaster/index');
        }
    }

    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']              = $id;
        $data['title']           = $this->lang->line('student_fees');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $feegroup_result         = $this->additionalfeesessiongroup_model->getFeesByGroup($id);
        $data['feegroupList']    = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $genderList            = $this->customlib->getGender();
        $data['genderList']    = $genderList;
        $RTEstatusList         = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;

        $category             = $this->category_model->get();
        $data['categorylist'] = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender']      = $this->input->post('gender');
            $data['rte_status']  = $this->input->post('rte');
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');

            $resultlist         = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/additionalfeemaster/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    
}

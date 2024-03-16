<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Additionalfeegroup extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('additionalfeegroup_model');

    }

    public function index()
    {
        // if (!$this->rbac->hasPrivilege('fees_group', 'can_view')) {
        //     access_denied();
        // }
        // $this->session->set_userdata('top_menu', 'Fees Collection');
        // $this->session->set_userdata('sub_menu', 'admin/feegroup');

        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array(
                'required',
                array('check_exists', array($this->additionalfeegroup_model, 'check_exists')),
            )
        );

        if ($this->form_validation->run() == false) {

        } else {
            $data = array(
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
            );
            $this->additionalfeegroup_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/additionalfeegroup/index');
        }
        $feegroup_result      = $this->additionalfeegroup_model->get();
        $data['feegroupList'] = $feegroup_result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/additionalfeegroup/feegroupList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        // if (!$this->rbac->hasPrivilege('fees_group', 'can_delete')) {
        //     access_denied();
        // }
        $this->additionalfeegroup_model->remove($id);
        redirect('admin/additionalfeegroup/index');
    }

    public function edit($id)
    {
        // if (!$this->rbac->hasPrivilege('fees_group', 'can_edit')) {
        //     access_denied();
        // }
        // $this->session->set_userdata('top_menu', 'Fees Collection');
        // $this->session->set_userdata('sub_menu', 'admin/feegroup');
        $data['id']           = $id;
        $feegroup             = $this->additionalfeegroup_model->get($id);
        $data['feegroup']     = $feegroup;
        $feegroup_result      = $this->additionalfeegroup_model->get();
        $data['feegroupList'] = $feegroup_result;
        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array(
                'required',
                array('check_exists', array($this->additionalfeegroup_model, 'check_exists')),
            )
        );

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/additionalfeegroup/feegroupEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
            );
            $this->additionalfeegroup_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/additionalfeegroup/index');
        }
    }

}

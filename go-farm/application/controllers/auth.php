<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	public function index()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Email', 'trim|required');
		if ($this->form_validation->run() == false){
			$data['title'] = 'Log-in Go-Farm';
			$this->load->view("tamplates/auth_header", $data);
			$this->load->view("auth/login");
			$this->load->view("tamplates/auth_footer");
		}else{
			//bisa dibuat private
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$user = $this->db->get_where('user',['email'=>$email])->row_array();

			//cek user ada/tidak
			if($user){
				//cek user aktif
				if($user['aktif'] == 1){
					//cek passwor
					if(password_verify($password, $user['password'])){
						$data = ['email'=>$user['email']]; //line 31 dan 32 di gunakan jika ada akun admin dan member
						$this->session->set_userdata($data);
						//arahkan ke layout web
					}else {
						$this->session->set_flashdata('pesan','<div class="alert alert-danger" role"alert">Password Salah</div>');
						redirect('auth');
					}
				}else {
					$this->session->set_flashdata('pesan','<div class="alert alert-danger" role"alert">Akun Belum Terverifikasi</div>');
					redirect('auth');
				}
			}else {
				$this->session->set_flashdata('pesan','<div class="alert alert-danger" role"alert">User Tidak Ditemukan</div>');
				redirect('auth');
			}
		}
	}
	public function registration()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[3]|matches[password2]');
		$this->form_validation->set_rules('password2', 'password', 'required|trim|matches[password2]');
		
		if($this->form_validation->run() == false){
			$data['title'] = 'Registration Go-Farm';
			$this->load->view("tamplates/auth_header", $data);
			$this->load->view("auth/registration");
			$this->load->view("tamplates/auth_footer");
		}else{
			$data = [
				'nama' => htmlspecialchars($this->input->post('name',true)),
				'email' => htmlspecialchars($this->input->post('email',true)),
				'foto' => 'namafoto.jpg',
				'password' => password_hash($this->input->post('password'),PASSWORD_DEFAULT),
				'role_id' => 2,
				'aktif' => 1,
				'tanggal' => time()
			];
			$this->db->insert('user',$data);
			$this->session->set_flashdata('pesan','<div class="alert alert-success" role"alert">Akun berhasil dibuat</div>');
			redirect('auth');
		}
	}
}

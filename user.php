
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	//This is the main controller extending the code igniters controller class.
	
	class User extends CI_Controller{
	 public function __construct()
	 {
	  parent::__construct();
	  
	//here we load our own defined model to use for database related functions.   
	
		$this->load->model('user_model');
	 }
	 
	 
	 public function index()
	 {
	 
	 //if the user_name attribute of the session is set,this means that the user has already logged in,so redirect the user to welcome page!
	 
	  if(($this->session->userdata('user_name')!=""))
	  {
	   $this->welcome();
	  }
	  
	//else ,load the header,footer and dynamically generated registration views .
	
	  else{
	   $data['title']= 'Home';
	   $this->load->view('header_view',$data);
	   $this->load->view("registration_view.php", $data);
	   $this->load->view('footer_view',$data);
	  }
	 }
	 
	 
	// This is the welcome function,where the header,footer and registration views is shown.
	 public function welcome()
	 {
	  $data['title']= 'Welcome';
	  $this->load->view('header_view',$data);
	  $this->load->view('welcome_view.php', $data);
	  $this->load->view('footer_view',$data);
	 }
	 
	 //This function does the main job : validate,verify and login/redirect. 
	 public function login()
	 {
	  $email=$this->input->post('email');
	  $password=md5($this->input->post('pass'));
	
	//The username and password from the bootstraps's form is sent in the user_models login function for validation and verification from database.
	
	  $result=$this->user_model->login($email,$password);
	  
	//If the returned value is true i.e credentials are valid and verified,redirect to welcome page.  
	  if($result) $this->welcome();
	  
	//Else, go back to index page.
	
	  else        $this->index();
	 }
	 
	 
	//This is just a thankyou note printing function after successful signup of the user!
	
	 public function thank()
	 {
	  $data['title']= 'Thank';
	  $this->load->view('header_view',$data);
	  $this->load->view('thank_view.php', $data);
	  $this->load->view('footer_view',$data);
	 }
	 
	//This is the other main function of our app,The Sign up part.
	
	 public function registration()
	 {
	 //load the form_validation library to validate username and password.
	  $this->load->library('form_validation');
	  // field name, error message, validation rules, 1) remove all white spaces 2) the field is required 3) min/max lengths etc.
	  $this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[4]');
	  $this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email');
	  $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
	  
	  //This is where the confirm password part is implemented,the value in confirm password's field should match the value in password field.
	  $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');
	
	 //if validation fails,redirect to index page	
	  if($this->form_validation->run() == FALSE)
	  {
	   $this->index();
	  }
	  //else call the user_model's add_user function to set the session and redirect to thankyou note for signing up!
	  else
	  {
	   $this->user_model->add_user();
	   $this->thank();
	  }
	 }
	 
	 //This is the logout function,it simply unsets and destroys the session variable to successfully logout !
	 
	 public function logout()
	 {
	  $newdata = array(
	  'user_id'   =>'',
	  'user_name'  =>'',
	  'user_email'     => '',
	  'logged_in' => FALSE,
	  );
	  $this->session->unset_userdata($newdata );
	  $this->session->sess_destroy();
	  $this->index();
	 }
	}
	?>

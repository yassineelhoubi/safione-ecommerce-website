<?php class User {
        public $db;
        public $product;

public function __construct() {
        // instantiate database
        $database=new Database();
        $this->db=$database->connect();

        //Instantiate User object
        $this->user=new Users($this->db);

}
    public function sign_up() {

        // get raw posted data
        $data=json_decode(file_get_contents("php://input"));

        $this->user->email=$data->email;
        $this->user->password=password_hash($data->password, PASSWORD_DEFAULT);

        $this->user->nbrPhone=$data->nbrPhone;
        $this->user->Fname=$data->Fname;
        $this->user->Lname=$data->Lname;
        $this->user->address1=$data->address1;
        $this->user->address2=$data->address2;
        $this->user->gender=$data->gender;


        $bol = true;
        $rowCount = $this->user->checkemail();
        if($rowCount >= 1){
            $bol = false;
        }
        if($bol){
            if ($this->user->create()) {
            

                echo json_encode(array('message'=> 'user iserted',
                    'state'=> true));
    
            }
    
            else {
    
            
                echo json_encode(array('message'=> 'user not iserted',
                'state'=> false));
    
            }
        }else{
            echo json_encode(array('message'=> 'this email already exist',
                'state'=> false));
        }


    }



    public function login() {

        // get raw posted data
        $data=json_decode(file_get_contents("php://input"));

        $this->user->email    =   $data->email;
        $password       =   $data->password;

        
        if($row = $this->user->login()){

            $hachPassword = $row['password'];
        }

       

        if($row == !0 && password_verify($password , $hachPassword)) {
            $token          = $this->user->gen_token();
            $this->user->token    = $token;
            if($this->user->gave_token()){
                echo json_encode(array('message'=> 'user login','token'=>$token, 
                'state'=> true)); 
            }
            
        }elseif($row ==!0){
            echo json_encode(array('message'=>'data not valid'));
        }
        else{
            echo json_encode(array('message'=>"doesn't user exist"));
        }


    }
    public function check_token(){


        // get raw posted data
        $data=json_decode(file_get_contents("php://input"));
        $this->user->token       =   $data->token;

        if($this->user->check_token()){

            echo json_encode(array('message'=>'token is valid'));
        }
        else{
            echo json_encode(array('message'=>"token not valid"));
        }
            
    }
    public function get_role_token()
    {
            // get raw posted data
            $data=json_decode(file_get_contents("php://input"));
            $this->user->token       =   $data->token;
            if($this->user->check_token()){
            if($role = $this->user->get_role_token()){

                echo json_encode(array('role'=>$role));
            }
        }else{
            echo json_encode(array('message'=>"token not valid"));
        }
            

    }
}
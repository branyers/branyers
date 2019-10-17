<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
//use http\Env\Response;
use http\Header;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function pruebas(request $request){
        return "Accion de prueba de UserController";
    }

    public function register(request $request){

        //Collect users data for post

        $json = $request->input('json',null);

        $params = json_decode($json);

        $params_array = json_decode($json, true);


        if(!empty($params) && !empty($params_array)) {
            //Limpiar datos


            $params_array = array_map('trim', $params_array);


            // Validate data

            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users',
                'password' => 'required',


            ]);

            if ($validate->fails()){

                //La validacion ha fallado

                $data = array(

                    'status' => 'error',
                    'code' => 404,
                    'message' => 'The user has not been created correctly',
                    'errors' => $validate->errors()

                );

            } else {
                //La validacion ha pasado correctamente


                //Cifrar la contrasena

              $pwd = hash('sha256',$params->password);

                //Comprobar si el usuario existe ya (Duplicado)

                $user = new User();

                $user ->name = $params_array['name'];
                $user ->surname = $params_array['surname'];
                $user ->email = $params_array['email'];
                $user ->password = $pwd;
                $user->role  = 'ROLE_USER';
                $user->save();


                $data = array(

                    'status' => 'success',
                    'code' => 200,
                    'message' => 'The user was create correctly'

                );

            }


        }else{
            $data = array(

                'status' => 'error',
                'code' => 404,
                'message' => 'The date has not sent correctly'
            );
        }

        return response()->json($data,$data['code']);

    }


    public function login(request $request){
        $jwtAuth = new \JwtAuth();
        //Recibir datos por POST
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array= json_decode($json, true);


        //Validar esos datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if ($validate->fails()){
            //La validacion ha fallado
            $signup = array(

                'status' => 'error',
                'code' => 404,
                'message' => 'El Usuario no se ha podido identificar',
                'errors' => $validate->errors()

            );

        } else {
           //Cifrar la password
            $pwd = hash('sha256',$params->password);
            //Devolver token o datos

          $signup = $jwtAuth->signup($params->email,$pwd);


        if(!empty($params->gettoken)){
            $signup = $jwtAuth->signup($params->email,$pwd, true);
        }    
    }
        return response()->json($signup,200);

    }

    public function update(request $request){

        //Comprobar si el usuario esta identificado


        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);


        //Recoger los datos por POST
        $json =  $request->input('json',null);
        $params_array = json_decode($json,true);

        if($checkToken && !empty($params_array)){
            //Actualizar usuario



            // Sacar usario identificado
            $user = $jwtAuth->checkToken($token,true);

            //Validar los datos

            $validate = \Validator::make($params_array,array(
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,'.$user->sub
            ));

            //Quitar los campos que no quiero actualizar

            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //Actualizar usuario en la BD

            $user_update = User::where('id',$user->sub) ->update($params_array);

            //Devolver array con resultado

            $data = array(

                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array

            );

        }else{

            $data = array(

                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado.'

            );

        }

       return response()->json($data, $data['code']);

    }


    public function upload(Request $request){
        //Reocoger datos

        $image = $request->file('file0');

        //Validacion

        $validate = \Validator::make($request->all(), [

            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);


        //guardar imagen
        if(!$image || $validate->fails()){
            $data = array(

                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen.'

            );
        }else{

            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name,\File::get($image));

            $data = array(

                'code' => 200,
                'status' => 'success',
                'image' => $image_name,

            );

        }
        return response()->json($data,$data['code']);


    }


    public function getImage($filename)
    {

        $isset = \Storage::disk('users')->exists($filename);

        if ($isset){

            $file = \Storage::disk('users')->get($filename);
        return new Response($file, 200);

    }else{
            $data = array(

                'code' => 400,
                'status' => 'error',
                'message' => 'La imagen no existe.'

            );

            return response()->json($data,$data['code']);

        }



    }


    public function detail($id){

        $user = User::find($id);


        if(is_object($user)){
            $data = array(

                'code' => 200,
                'status'=> 'success',
                'user' => $user

            );
        }else{
            $data = array(

                'code' => 404,
                'status'=> 'error',
                'message' => 'El usuario no existe,'

        );

        }

        return response()->json($data, $data['code']);


    }

}

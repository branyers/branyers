<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;

class pruebaController extends Controller
{
    public function index(){

        $titulo = 'Animales';
        $animales = ['Perro','gato','tiguere'];
        return view('animales',array(

            'animales' => $animales,
            'titulo' => $titulo
        ));

    }



    public function testOrm(){
        $post = Post::all();
//       foreach ($post as $posted){
//
//           echo "<h1>".$posted->title."</h1>";
//           echo "<span>{$posted->user->name} - {$posted->category->name}</span>";
//           echo "<p>".$posted->content."</p>";
//
//        }

       $categories = Category::all();

       foreach ($categories as $categorias){

           echo "<h1>{$categorias->name}</h1>";

           foreach ($categorias->posts as $posted){

               echo "<h1>".$posted->title."</h1>";
               echo "<span>{$posted->user->name} - {$posted->category->name}</span>";
               echo "<p>".$posted->content."</p>";


           }
           echo "<hr>";




       }


        die();

    }
}

<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function inscribete()
    {
        return view('inscribete-en-construccion');
    }

    public function about()
    {
        return view('sobre-nosotros-en-construccion');
    }

    public function ofertas()
    {
        return view('ofertas-en-construccion');
    }
    public function tienda()
{
    return view('tienda-en-construccion');
}
}

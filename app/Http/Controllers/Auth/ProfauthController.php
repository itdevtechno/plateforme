<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Professeur;
use App\Session;
use App\Test;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;

class ProfauthController extends Controller
{
    public function index()
    {
        return view('profauth.login');
    }

    public function professeurLogin(Request $request)
    {
        /*$profs = Professeur::all();
        foreach ($profs as $prof) {
            if ((strcmp($prof->username, $request->username)==0) && (strcmp($prof->password , $request->password)==0)) {
                return view('profauth.test')->with('prof',$prof);
            }
        }
        return redirect()->route('profauth.login');*/
        //$request->session()->flush();
        if ($request->session()->get('p_username') !== null) {
            $username = $request->session()->get('p_username');
            $password = $request->session()->get('p_password');

        } else {
            $username = $request->username;
            $password = $request->password;
        }
        $professeur = Professeur::query()->where('username', '=', $username)->count();
        if (intval($professeur) > 0) {
            $professeurPass = Professeur::query()->where('username', '=', $username)->first();
            if (strcmp($password, $professeurPass->password) == 0) {
                $request->session()->put('p_username', $username);
                $request->session()->put('p_password', $password);
                $request->session()->put('p_id', $professeurPass->professeur_id);
                $tests = Test::query()->where('professeur_id',$professeurPass->professeur_id)->count();
                if($tests > 0) {
                    return view('accueilProf.index')->with('prof', $professeurPass);
                   // return view('profauth.test')->with('prof', $professeurPass);
                }else{
                    return view('create-test.index')->with(['p'=>$professeurPass]);
                }
            } else {
                $error = "le nom d'utilisateur ou le mot de passe sont incorrects";
                return redirect()->route('profauth.login')->with('error',$error);
            }
        } else {
            $error = "le nom d'utilisateur ou le mot de passe sont incorrects";
            return redirect()->route('profauth.login')->with('error',$error);
        }
    }

    public function professeurLogout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('profauth.login');
    }

}

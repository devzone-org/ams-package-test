<?php

namespace Devzone\Ams;


use Illuminate\Http\Request;



class Ams
{

    public function testing(Request $request){
        $input = $request->all();
        dd($request,'w');
    }
}

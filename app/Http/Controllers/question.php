<?php

namespace App\Http\Controllers;

use App\binaire;
use App\Option;
use App\Session;
use App\Text_libre;
use Illuminate\Http\Request;


use App\Test;
use App\qcm;
use Illuminate\Support\Facades\DB;


class question extends Controller
{

    public function index()
    {

    }

    public function index2($test_id)
    {
        $test['test'] = test::findOrfail($test_id);
        return view('create-question.index', compact('test'));

    }


    public function index1(Request $request)
    {
        return view('create-qcm.index');
    }

    public function select ($test_id){
        $test =test::find($test_id);
        return view ('select-question.index',compact('test'));

    }
    public function Random ($test_id){
        $test =test::find($test_id);
        return view ('Random.index',compact('test'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function StoreSelected(Request $request)
    {
        $qcms['qcms'] = qcm::find(array_values($request->input('qcm')));
        if ($request->input('bin') != null)
            $binaires['binaires'] = binaire::find(array_values($request->input('bin')));
        $text_libre['text_libre'] = Text_libre::query()->find(array_values($request->input('text_libre')));
        foreach ($qcms['qcms'] as $qcm) {
            $qcm->test_id = $request->test_id;
            $qc = array(
                'question_text' => $qcm->question_text,
                'note' => $qcm->note,
                'test_id' => $qcm->test_id,
                'difficulty' => $qcm->difficulty
            );
            $id = qcm::create($qc);
            $options = DB::table('option')->where('question_id', $qcm->question_id)->get();

            foreach ($options as $option) {
                $option->question_id = $id->question_id;
                $opt = array(

                    'point' => $option->point,
                    'question_id' => $option->question_id,
                    'option_text' => $option->option_text

                );

                Option::create($opt);
            }

        }
        foreach ($binaires['binaires'] as $binaire) {
            $binaire->test_id = $request->test_id;
            $bin = array(
                'question_text' => $binaire->question_text,
                'note' => $binaire->note,
                'test_id' => $binaire->test_id,
                'difficulty' => $binaire->difficulty

            );
            $id1 = binaire::create($bin);
            $options = DB::table('option')->where('binaire_id', $binaire->binaire_id)->get();

            foreach ($options as $option) {
                $option->binaire_id = $id1->binaire_id;
                $opt = array(

                    'point' => $option->point,
                    'binaire_id' => $option->binaire_id,
                    'option_text' => $option->option_text

                );

                Option::create($opt);
            }

        }

        foreach ($text_libre['text_libre'] as $text_libre){
            $text_libre->test_id = $request->test_id;
            $tl = array(
                'test_id' => $text_libre->test_id,
                'question_text' => $text_libre->question_text,
                'note' => $text_libre->note,
                'difficulty' => $text_libre->difficulty
            );
            Text_libre::query()->create($tl);
        }

        return redirect()->back();

    }


    public function RandomStoring(Request $request){
        $nbr=$request->nombre ;
        $difficulty =$request->difficulty ;
        $type=$request->type;
        $test=test::find($request->test_id);

        $test2['test2'] =DB::table('test')->where('test_id','<>',$request->test_id)->where('matiere_id',$test->matiere_id)->where('professeur_id',$test->professeur_id)->get();
        $i=0;
        foreach($test2['test2'] as $test){
            $test1[$i]=$test->test_id ;
            $i++;
        }



        if ($type == 'qcm'){
            $i=0;
            $t=[];
            $shiit['shiit']= DB::table('qcm')->where('test_id',$request->test_id)->where('difficulty',$difficulty)->get();
            foreach( $shiit['shiit'] as $qcm1){
                $t[$i]=$qcm1->question_text ;
                $i++;
            }


            $qcms= qcm::orderByRaw("RAND()")->where('test_id','<>',$request->test_id)->where('difficulty',$difficulty)->whereIn('test_id',$test1)->whereNotIn('question_text',$t)->take($nbr)->get();
            $count= qcm::orderByRaw("RAND()")->where('test_id','<>',$request->test_id)->where('difficulty',$difficulty)->whereIn('test_id',$test1)->whereNotIn('question_text',$t)->take($nbr)->get()->count();

            // $qcmCount =DB::table('qcm')->where('test_id',$test->test_id)->where('difficulty',$difficulty)->get() ;
            //$disponible = - count($qcmCount);

            if($nbr==$count){
                foreach($qcms as $qcm){
                    $test1 =test::find($qcm->test_id);
                    if($test1->matiere_id == $test->matiere_id && $test1->professeur_id == $test->professeur_id ){
                        $insert =array(
                            'question_text' => $qcm->question_text ,
                            'test_id' => $request->test_id ,
                            'note' => $qcm->note,
                            'difficulty' => $qcm->difficulty ,
                        );
                        $id=qcm::create($insert);
                        $options=DB::table('option')->where('question_id',$qcm->question_id)->get();

                        foreach($options as $option ){
                            $option->question_id=$id->question_id ;
                            $opt =array(

                                'point'=>$option->point,
                                'question_id'=>$option->question_id,
                                'option_text'=> $option->option_text

                            );

                            Option::create($opt);
                        }



                    }

                }}else {session()->flash('notif','le nombre que vous avez choisi est plus grands que les question de type qcm  qui existe');}
        }
        if ($type == 'binaire'){
            $j=0;
            $k=[];
            $shiit1['shiit1']= DB::table('binaire')->where('test_id',$request->test_id)->where('difficulty',$difficulty)->get();
            foreach( $shiit1['shiit1'] as $binaire1){
                $k[$j]=$binaire1->question_text ;
                $j++;
            }

            $binaires= binaire::orderByRaw("RAND()")->where('test_id','<>',$request->test_id)->where('difficulty',$difficulty)->whereNotIn('question_text',$k)->whereIn('test_id',$test1)->take($nbr)->get();
            $count1= binaire::orderByRaw("RAND()")->where('test_id','<>',$request->test_id)->where('difficulty',$difficulty)->whereNotIn('question_text',$k)->whereIn('test_id',$test1)->take($nbr)->get()->count();

            if($nbr==$count1){
                ;
                foreach($binaires as $binaire){
                    $test1 =test::find($binaire->test_id);

                    if($test1->matiere_id == $test->matiere_id && $test1->professeur_id == $test->professeur_id ){


                        $insert1 =array(
                            'question_text' => $binaire->question_text ,
                            'test_id' => $request->test_id ,
                            'note' => $binaire->note,
                            'difficulty' => $binaire->difficulty
                        );


                        $id1=binaire::create($insert1);


                        $options=DB::table('option')->where('binaire_id',$binaire->binaire_id)->get();

                        foreach($options as $option ){
                            $option->binaire_id=$id1->binaire_id ;
                            $opt =array(

                                'point'=>$option->point,
                                'binaire_id'=>$option->binaire_id,
                                'option_text'=> $option->option_text

                            );

                            Option::create($opt);
                        }



                    }

                }
            }else{session()->flash('notif','le nombre que vous avez choisi est plus grands que les question de type Binaire qui existe');}

        }
        return  redirect()->back();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {
        $question = $request->question;
        $test_id = $request->test_id;
        $choice = $request->choice;
        $question = array(
            'question_text' => $question,
            'test_id' => $test_id,
            'note' => '1'
        );
        $id = Binaire::create($question);
        if ($choice == 'vrai') {
            $option = array(
                'option_text' => 'vrai',

                'binaire_id' => $id->binaire_id,
                'point' => '1'
            );
            option::create($option);
            $option1 = array(
                'option_text' => 'false',

                'binaire_id' => $id->binaire_id,
                'point' => '0'
            );
            option::create($option1);
        }
        if ($choice == 'faux') {
            $option = array(
                'option_text' => 'false',

                'binaire_id' => $id->binaire_id,
                'point' => '1'
            );
            option::create($option);
            $option1 = array(
                'option_text' => 'true',
                'binaire_id' => $id->binaire_id,
                'point' => '0'
            );
            option::create($option1);
        }
        return view('depatement.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\binaire $binaire
     * @return \Illuminate\Http\Response
     */
    public function show(binaire $binaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\binaire $binaire
     * @return \Illuminate\Http\Response
     */
    public function edit(binaire $binaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\binaire $binaire
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, binaire $binaire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\binaire $binaire
     * @return \Illuminate\Http\Response
     */
    public function destroy(binaire $binaire)
    {
        //
    }
}

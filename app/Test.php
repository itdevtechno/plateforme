<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Test extends Model implements ToModel, WithHeadingRow
{

    protected $table = "test";
    protected $fillable = ['nom', 'duree', 'salle', 'date', 'note', 'discription','professeur_id','matiere_id','d1','d2','d3','d4','d5'];
    protected $primaryKey = 'test_id';

    public function model(array $row)
    {

        return new Test(array(
            'nom' => $row['nom'],
            'note' => $row['note'],
            'duree' => $row['duree'],
            'salle' => $row['salle'],
            'discription' => $row['discription'],
            'professeur_id' => $row['professeur_id'],
            'matiere_id' => $row['matiere_id'],
            'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])
        ));
    }

    public function qcm()
    {
        return $this->hasMany('App\qcm', 'test_id');
    }
    public function binaire()
    {
        return $this->hasMany('App\binaire', 'test_id', 'test_id');
    }

    public function text_libre()
    {
        return $this->hasMany('App\Text_libre', 'test_id', 'test_id');
    }
    public function session()
    {
        return $this->hasMany('App\Session', 'session_id');
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Niveau extends Model implements ToModel, WithHeadingRow
{
    protected $table="niveau";
    protected $fillable=['nom'];
    protected $primaryKey='niveau_id';

    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        // TODO: Implement model() method.
        return new Niveau(array(
            'nom' => $row['nom']
        ));
    }

    public function filiere(){
        return $this->belongsToMany('App\filiere','filiere_niveau','niveau_id','filiere_id');
    }
}

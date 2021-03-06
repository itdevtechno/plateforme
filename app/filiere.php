<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class filiere extends Model implements ToModel, WithHeadingRow
{
    protected $table = "filiere";

    protected $fillable = ['nom', 'coordinateur', 'datedebut', 'datefin', 'departement_id'];
    protected $primaryKey = 'filiere_id';

    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        // TODO: Implement model() method.
        return new filiere(array(
            'nom' => $row['nom'],
            'coordinateur' => $row['coordinateur'],
            'datedebut' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_de_debut']),
            'datefin' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_de_fin']),
            'departement_id' => $row['departement_id']
        ));
    }

    public function niveau(){
        return $this->belongsToMany('App\Niveau','filiere_niveau','filiere_id','niveau_id');
    }

    public function module(){
        return $this->belongsToMany('App\Module','filiere_module','filiere_id','module_id');
    }
}

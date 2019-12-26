<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $table = "plano";
    protected $guarded = ["id", "created_at"];

    public function licencas()
    {
        return $this->hasMany(Licenca::class, "plano_id");
    }

    public function produtos()
    {
        return $this->hasMany(PlanoProduto::class, "plano_id");
    }

    public function store(Array $formData)
    {
        DB::beginTransaction();

        $response = $this->create($formData);

        if ($response) {
            DB::commit();

            return [
                "id"        => $response->id,
                "success"   => true,
                "message"   => "Plano cadastrado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao cadastrar o plano."
            ];
        }
    }

    public function atualizar(Array $formData)
    {
        DB::beginTransaction();

        $plano = $this->find($formData['id']);

        foreach ($plano->original as $key => $value) {

            switch ($key) {
                case 'id':
                case 'created_at':
                case 'created_by':
                break;
                
                default:
                    $plano[$key] = isset($formData[$key]) ? $formData[$key] : null;
                break;
            }

        }

        $response = $plano->save();

        if ($response) {
            DB::commit();

            return [
                "success"   => true,
                "message"   => "Plano atualizado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao atualizar o plano."
            ];
        }
    }

    public static function search(String $search, $totalPage = 10)
    {
        return Plano::where('nm_plano', 'LIKE', "%$search%")
                    ->with(["produtos" => function($query) {
                        $query->with(["produto" => function($query) {
                            $query->select("id", "nm_produto");
                        }]);
                    }])
                    ->paginate($totalPage);
    }
}

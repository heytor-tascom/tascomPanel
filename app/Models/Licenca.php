<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    protected $table = "licenca";
    protected $guarded = ["id", "created_at"];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, "cliente_id");
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, "plano_id");
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
                "message"   => "Licença cadastrado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao cadastrar o licença."
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
                "message"   => "Licença atualizado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao atualizar o licença."
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

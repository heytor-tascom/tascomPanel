<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

#models

use App\Models\INTEGRACAO\Cadastro;
use App\Models\INTEGRACAO\Custo;
use App\Models\INTEGRACAO\Admissao;
use App\Models\INTEGRACAO\Depara;

class GerenciamentoController extends Controller
{
    public function index(){

        $messages = Cadastro::paginate(7);

        $error = Cadastro::where('sn_integrado', 'F')->get();
        $success = Cadastro::where('sn_integrado', 'S')->get();
        $aguard =  Cadastro::where('sn_integrado', 'N')->get();

        return view('integracao.index', compact('messages', 'error', 'success', 'aguard'));
    }

    public function getReturnDRG(Request $request){

        $data = Cadastro::where('id', $request->only('id'))->first();

        return $data;

    }

    public function searchCadastro(Request $request){

        $value = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');
        $req = $request->except('_token');


        if ($value == null){

            $messages = Cadastro::where('sn_integrado', $status)
            ->paginate(7);

            $error = Cadastro::where('sn_integrado', 'F')->get();
            $success = Cadastro::where('sn_integrado', 'S')->get();
            $aguard =  Cadastro::where('sn_integrado', 'N')->get();

            return view('integracao.index', compact('messages', 'error', 'success', 'aguard', 'req'));
        }


        if ($type == 'nome'){
            $messages = Cadastro::where('bnome', 'like', $value)
            ->where('sn_integrado', $status)
            ->paginate(7);

            $error = Cadastro::where('sn_integrado', 'F')
            ->where('bnome', 'like', $value)
            ->get();

            $success = Cadastro::where('sn_integrado', 'S')
            ->where('bnome', 'like', $value)
            ->get();

            $aguard =  Cadastro::where('sn_integrado', 'N')
            ->where('bnome', 'like', $value)
            ->get();

            return view('integracao.index', compact('messages', 'error', 'success', 'aguard', 'req'));

        }

        if ($type == 'atendimento'){

            $messages = Cadastro::where('numeroatendimento', $value)->paginate(7);

            $error = Cadastro::where('sn_integrado', 'F')
            ->where('numeroatendimento', $value)
            ->where('sn_integrado', $status)
            ->get();

            $success = Cadastro::where('sn_integrado', 'S')
            ->where('numeroatendimento', $value)
            ->get();

            $aguard =  Cadastro::where('sn_integrado', 'N')
            ->where('numeroatendimento', $value)
            ->get();

            return view('integracao.index', compact('messages', 'error', 'success', 'aguard', 'req'));
        }

        if ($type == 'data'){

            $data = explode(',', $value);

            $date1 = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d');
            $date2 = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d');

            $messages = Cadastro::whereRaw("updated_at between '$date1' AND '$date2'")
            ->orderby('updated_at', 'asc')
            ->where('sn_integrado', $status)
            ->paginate(7);


            $error = Cadastro::where('sn_integrado', 'F')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            $success = Cadastro::where('sn_integrado', 'S')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            $aguard =  Cadastro::where('sn_integrado', 'N')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            return view('integracao.index', compact('messages', 'error', 'success', 'aguard', 'req'));
        }


        return 'false';



    }

    public function getReturnDRGCusto(Request $request){

        $data = Custo::where('id', $request->only('id'))->first();

        return $data;

    }

    public function searchCusto(Request $request){

        $value = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');
        $req = $request->except('_token');


        if ($value == null){

            $messages = Custo::where('sn_integrado', $status)
            ->paginate(7);

            $error = Custo::where('sn_integrado', 'F')->get();
            $success = Custo::where('sn_integrado', 'S')->get();
            $aguard =  Custo::where('sn_integrado', 'N')->get();

            return view('integracao.custo', compact('messages', 'error', 'success', 'aguard', 'req'));
        }



        if ($type == 'data'){

            $data = explode(',', $value);

            $date1 = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d');
            $date2 = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d');

            $messages = Custo::whereRaw("updated_at between '$date1' AND '$date2'")
            ->orderby('updated_at', 'asc')
            ->where('sn_integrado', $status)
            ->paginate(7);


            $error = Custo::where('sn_integrado', 'F')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            $success = Custo::where('sn_integrado', 'S')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            $aguard =  Custo::where('sn_integrado', 'N')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            return view('integracao.custo', compact('messages', 'error', 'success', 'aguard', 'req'));
        }


        return 'false';



    }

    public function indexCusto($status = true){

        $messages = Custo::paginate(7);

        $error      = Custo::where('sn_integrado', 'F')->get();
        $success    = Custo::where('sn_integrado', 'S')->get();
        $aguard     =  Custo::where('sn_integrado', 'N')->get();

        return view('integracao.custo', compact('messages', 'error', 'success', 'aguard', 'status'));
    }

    public function geraCusto(Request $request){

        $data = explode(',', $request->input('data'));

            $date1 = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d');
            $date2 = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d');

            $dt_competencia = Carbon::createFromFormat('d/m/Y', $data[0])->format('m/Y');

        $atendimentos = Cadastro::whereRaw("str_to_date(datainternacao, '%Y-%m-%dT%TZ')
                        between '".$date1."T00:00:00' AND '".$date2."T23:59:59' and xml is not null")
                        ->get();

        if ($atendimentos){
            foreach ($atendimentos as $atendimento){

                $eCusto = Custo::where('cd_atendimento', $atendimento->numeroatendimento)->first();

                if(!$eCusto){
                    $add = Custo::create([
                        'acao' => 'A',
                        'cd_atendimento' => $atendimento->numeroatendimento,
                        'sn_integrado' => 'N',
                        'dt_competencia_geracao' => $dt_competencia,
                    ]);
                }else{

                    // update?

                }

            }

            return redirect()->route('custo', ['status' => true]);
        }

        return redirect()->route('custo',  ['status' => false]);


    }

    public function admissao(){

        $messages = Admissao::paginate(7);

        $error = Admissao::where('sn_integrado', 'F')->get();
        $success = Admissao::where('sn_integrado', 'S')->get();
        $aguard =  Admissao::where('sn_integrado', 'N')->get();

        return view('integracao.admissao', compact('messages', 'error', 'success', 'aguard'));
    }

    public function getReturnDRGAdmissao(Request $request){

        $data = Admissao::where('id', $request->only('id'))->first();

        return $data;

    }

    public function searchCadastroAdmissao(Request $request){

        $value = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');
        $req = $request->except('_token');


        if ($value == null){

            $messages = Admissao::where('sn_integrado', $status)
            ->paginate(7);

            $error = Admissao::where('sn_integrado', 'F')->get();
            $success = Admissao::where('sn_integrado', 'S')->get();
            $aguard =  Admissao::where('sn_integrado', 'N')->get();

            return view('integracao.admissao', compact('messages', 'error', 'success', 'aguard', 'req'));
        }


        if ($type == 'nome'){
            $messages = Admissao::where('bnome', 'like', $value)
            ->where('sn_integrado', $status)
            ->paginate(7);

            $error = Admissao::where('sn_integrado', 'F')
            ->where('bnome', 'like', $value)
            ->get();

            $success = Admissao::where('sn_integrado', 'S')
            ->where('bnome', 'like', $value)
            ->get();

            $aguard =  Admissao::where('sn_integrado', 'N')
            ->where('bnome', 'like', $value)
            ->get();

            return view('integracao.admissao', compact('messages', 'error', 'success', 'aguard', 'req'));

        }

        if ($type == 'atendimento'){

            $messages = Admissao::where('numeroatendimento', $value)->paginate(7);

            $error = Admissao::where('sn_integrado', 'F')
            ->where('numeroatendimento', $value)
            ->where('sn_integrado', $status)
            ->get();

            $success = Admissao::where('sn_integrado', 'S')
            ->where('numeroatendimento', $value)
            ->get();

            $aguard =  Admissao::where('sn_integrado', 'N')
            ->where('numeroatendimento', $value)
            ->get();

            return view('integracao.admissao', compact('messages', 'error', 'success', 'aguard', 'req'));
        }

        if ($type == 'data'){

            $data = explode(',', $value);

            $date1 = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d');
            $date2 = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d');

            $messages = Admissao::whereRaw("updated_at between '$date1' AND '$date2'")
            ->orderby('updated_at', 'asc')
            ->where('sn_integrado', $status)
            ->paginate(7);


            $error = Admissao::where('sn_integrado', 'F')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            $success = Admissao::where('sn_integrado', 'S')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            $aguard =  Admissao::where('sn_integrado', 'N')
            ->whereRaw("updated_at between '$date1' AND '$date2'")
            ->get();

            return view('integracao.admissao', compact('messages', 'error', 'success', 'aguard', 'req'));
        }


        return 'false';



    }

    public function depara(Request $request){

        $status = $request->input('status');
        $req = $request->except('_token');

        if ($request->input('tp_depara') && $request->input('codigo') && $request->input('search')){

            if ($request->input('codigo') == 'mv'){
                $tipoCodigo = 'cd_depara_mv';
            }

            if ($request->input('codigo') == 'externo'){
                $tipoCodigo = 'cd_depara_integra';
            }

            $messages = Depara::where('tp_depara', $request->input('tp_depara'))
            ->where('tp_depara', $request->input('tp_depara'))
            ->where($tipoCodigo, $request->input('search'))
            ->paginate(7);

            $tiposDepara = Depara::selectRaw('distinct tp_depara')->get();
            return view('integracao.depara', compact('messages', 'tiposDepara', 'req'));
        }


        if ($request->input('tp_depara') && $request->input('codigo')){

            $messages = Depara::where('tp_depara', $request->input('tp_depara'))
            ->where('tp_depara', $request->input('tp_depara'))
            ->paginate(7);

            $tiposDepara = Depara::selectRaw('distinct tp_depara')->get();
            return view('integracao.depara', compact('messages', 'tiposDepara', 'req'));
        }



        $messages = Depara::paginate(7);
        $tiposDepara = Depara::selectRaw('distinct tp_depara')->get();
        return view('integracao.depara', compact('messages', 'tiposDepara', 'status'));



    }

    public function storeDepara(Request $request){


        $insert = Depara::create([
            'tp_depara' => $request->input('tp_depara'),
            'cd_depara_mv' => $request->input('cd_depara_mv'),
            'cd_depara_integra' => $request->input('cd_depara_integra'),
            'cd_sistema_integra' => $request->input('cd_sistema_integra'),
            'cd_multi_empresa' => 1,
        ]);

        if ($insert){
            return redirect()->route('depara', ['status' => true]);
        }else{
            return redirect()->route('depara', ['status' => false]);
        }

    }


}

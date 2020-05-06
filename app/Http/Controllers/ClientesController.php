<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;


class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = \DB::connection('sqlsrv')->table("edi_clientes")->paginate(5);
        return \view('visteon.clientes', \compact('clientes'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validacion = Validator::make($request->all(), [
            'idhalcon' => 'required|numeric',
            'idvisteon' => 'required',
            'cliente' => 'required',
            'direccion' => 'required',
            'ciudad' => 'required',
            'estado' => 'required',
            'pais' => 'required',
            'cp' => 'required|numeric'],
            [
                'idhalcon.required' => 'Campo requerido',
                'idvisteon.required' => 'Campo requerido',
                'cliente.required' => 'Campo requerido',
                'direccion.required' => 'Campo requerido',
                'ciudad.required' => 'Campo requerido',
                'estado.required' => 'Campo requerido',
                'pais.required' => 'Campo requerido',
                'cp.required' => 'Campo requerido',
            ]);
            //si la validacion falla
            if($validacion->fails()){
            //return redirect('/')->with('error','La informacion NO fue enviada')->withErrors($validacion->errors());
            return back()->withInput()->with('error','La informacion NO fue enviada')->withErrors($validacion->errors());
            }
            else{
                DB::connection('sqlsrv')->table("edi_clientes")->insert([
                    'id_cliente' => $request->idhalcon,
                    'cliente' => $request->idvisteon,
                    'nombre' => $request->cliente,
                    'direccion' => $request->direccion,
                    'ciudad' => $request->ciudad,
                    'estado' => $request->estado,
                    'pais' => $request->pais,
                    'cp' => $request->cp,
                ]);
                return redirect()->route('clientes')->with('info','Informacion se guardo con exito!');
            } 

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

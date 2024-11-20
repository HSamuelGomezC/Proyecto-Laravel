<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presupuesto;

class PresupuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Presupuesto::query();
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }

        // Usa paginate en lugar de get()
        $presupuestos = $query->paginate(10);
        return view('presupuestos.index', compact('presupuestos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presupuestos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);
        
        //Guardar el presupuesto en la base de datos
        Presupuesto::create($request->all());
        
        //Redirigir al listado con mensaje de éxito
        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        return view('presupuestos.edit', compact('presupuesto'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            ]);

        // Buscar el presupuesto y actualizarlo
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->update($request->all());

        // Redirigir al listado con mensaje de éxito
        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->delete();

        // Redirigir al listado con mensaje de éxito
        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto eliminado correctamente.');
    }

    public function abonar(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:0.01',
            ]);

        $presupuesto = Presupuesto::findOrFail($id);

        // Verifica si el monto supera el total pendiente
        $pendiente = $presupuesto->monto - $presupuesto->abonos;
        if ($request->cantidad > $pendiente) {
            return redirect()->back()->with('error', 'El abono excede el monto pendiente.');
            }

        // Incrementar los abonos
        $presupuesto->abonos += $request->cantidad;
        $presupuesto->save();
        return redirect()->route('presupuestos.index')->with('success', 'Abono registrado exitosamente.');
        
    }

}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use PDF;

class ReportesController extends Controller
{
    //

    public function reporte_finanzas()
    {
        $models = Producto::all();
        $data=[];
        $data["alta_total"]=0;
        $data["media_total"]=0;
        $data["baja_total"]=0;
        $data["total"]=0;
        $data["alta_utilidad"]=0;
        $data["media_utilidad"]=0;
        $data["baja_utilidad"]=0;
        $data["utilidades"]=0;
        foreach ($models as $model):
            if ($model->categoria=="Gama Alta") {
                $data["alta_total"] += $model->facturado;
                $data["alta_utilidad"] += $model->utilidades;
            } elseif ($model->categoria == "Gama Media") {
                $data["media_total"] += $model->facturado;
                $data["media_utilidad"] += $model->utilidades;
            } else {
                $data["baja_total"] += $model->facturado;
                $data["baja_utilidad"] += $model->utilidades;
            }
            $data["total"] += $model->facturado;
            $data["utilidades"] += $model->utilidades;
        endforeach;
        $pdf = PDF::loadView('reporte', ["data"=>$data,"date"=>date("d-m-Y")]);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('itsolutionstuff.pdf');
    }
}

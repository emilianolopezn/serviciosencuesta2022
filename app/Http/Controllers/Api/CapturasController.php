<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\CapturaEncuesta;
use App\SintomaCaptura;



class CapturasController extends Controller
{
    public function store(Request $request) {
        $respuesta = array();
        $respuesta['exito'] = false;

        $nuevaCaptura = new CapturaEncuesta();
        $idTipoUsuario = 
            intval($request->input('id_tipo_usuario'));
        $nuevaCaptura->id_tipo_usuario = 
           $idTipoUsuario;
        // 1 - Alumno, 2 - Profesor, 3 - Empleado
        if ($idTipoUsuario == 1) {
            $nuevaCaptura->matricula = 
                $request->input('matricula');
        } else if ($idTipoUsuario == 2) {
            $nuevaCaptura->numero_profesor =
                $request->input('numero_profesor');
        } else {
            $nuevaCaptura->numero_empleado =
                $request->input('numero_empleado');
        }
        $nuevaCaptura->nombre =
            $request->input('nombre');
        $nuevaCaptura->correo =
            $request->input('correo');
        $nuevaCaptura->contacto_covid =
            $request->input('contacto_covid');
        $nuevaCaptura->vacunado =
            $request->input('vacunado');

        $nuevaCaptura->cadena_qr = "123456789";

        $sintomas = 
            $request->input('sintomas');
        
        if ($nuevaCaptura->save()) {
            if ($sintomas != NULL && count($sintomas) > 0) {
                foreach($sintomas as $sintoma) {
                    $nuevoSintomaCaptura =
                        new SintomaCaptura();
                    $nuevoSintomaCaptura->id_sintoma =
                        $sintoma;
                    $nuevoSintomaCaptura->id_captura =
                        $nuevaCaptura->id;
                    $nuevoSintomaCaptura->save();
                }
            }

            $respuesta['exito'] = true;
            $respuesta['cadena_qr'] = 
                $nuevaCaptura->cadena_qr;
            
        }

        
        return $respuesta;
    }
}

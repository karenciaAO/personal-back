<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactosController extends Controller
{
   /**
     * Devuelve el listado de los contactos historicos
     *
     * @return \Illuminate\Http\Response
     */
    public function obtenerContactos()
    {
        $contactos = Contacto::all();
        $data = $contactos->map(function ($contacto){
            return [
                'id' => $contacto->id,
                'firstName' => $contacto->nombre,
                'lastName' => $contacto->nombre,
                'mail' => $contacto->mail,
                'phone' => $contacto->telefono,
                'message'=> $contacto->mensaje,
                'created_at' => $contacto->created_at->toDateTimeString(),
                'updated_at' => $contacto->updated_at->toDateTimeString()
            ];
        });

        return response()->json([
            'message' => 'Listado de contactos historico',
            'data' => $data
        ]);
    }

    /**
     * Obtiene la informacion de la DB del contacto definido
     * 
     * @param id , id del contacto que se quiere identificar
     * @return \Illuminate\Http\Response
     */
    public function obtenerContacto($id)
    {
        $contacto = Contacto::findOrFail($id);

        return response()->json([
            'message' => 'Contacto',
            'data' => $contacto
        ]);
    }

    /**
     * Guarda un nuevo contacto en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insertarContacto(ContactoRequest $request)
    {     
        $nuevoContacto = Contacto::create([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'message' => $request['message']
        ]);

        $details = [
            'title' => 'Recibiste un nuevo contacto',
            'body' =>   $nuevoContacto
        ];

        self::enviarMail($details);

        return response()->json([
            'message' => 'Se agrego correctamente el contacto',
            'data' => $nuevoContacto
        ]);
    }

    /**
     * Actualiza el contacto que tiene $id, los campos son obligatorios
     *
     * @param id , id del contacto
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizaContacto($id, ContactoRequest $request)
    {
        $contacto = Contacto::find($id);
        $contacto->firstName = $request['firstName'];
        $contacto->lastName = $request['lastName'];
        $contacto->email = $request['email'];
        $contacto->phone = $request['phone'];
        $contacto->message = $request['message'];
        $contacto->save();

        return response()->json([
            'message' => 'Datos del contacto modificados',
            'data' => $contacto
        ]);
    }

    /**
     * Borra Logicamente el contacto segun su id
     *
     * @param  id , id del contacto que se desea borrar
     * @return \Illuminate\Http\Response
     */
    public function borrarContacto($id)
    {
        $contacto = Contacto::findOrFail($id);
        $contacto->delete();

        return response()->json([
            'message' => 'Contacto',
            'data' => $contacto
        ]);
    }

    /**
     * Hace el envio de mail
     * @param details son los detalles que va a tener el mail
     */

    private function enviarMail($details)
    {
        Mail::to('karenaraqueo@gmail.com')->send(new NuevoContacto($details));
    }

    public function prueba()
    {
        return response()->json([
            'message'=> 'Prueba ejecutada correctamente',
            'data'=> 'data data data'
        ]);
    }
}
    
    

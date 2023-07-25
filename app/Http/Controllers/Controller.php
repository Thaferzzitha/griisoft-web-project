<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API del Sistema Web de Atractores Caóticos de GRIISOFT",
 *     version="1.0.0",
 *     description="Aquí se encuentra toda la documentación del API utilizada para gestionar la información de atractores caóticos manejados por el sistema. 
 *                  Tenga en cuenta que el Token de Autenticación lo puede encontrar en su perfil, una vez que se haya registrado"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

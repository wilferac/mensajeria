<?
/*****************************************************************************
funcion: generarToken
 
  
  Funcion invocada por codigo de formulario.Crea una id para ser 
  usado en la sesion. Es aleatorio y está asociado a un formulario 
  y con un tiempo de creacion de dicho id de sesion.

*****************************************************************************/

function generarToken($form) {
 
   // generar token de forma aleatoria
   $token = md5(uniqid(microtime(), true));
 
   // generar fecha de generación del token
   $token_time = time();
 
   // escribir la información del token en sesión para poder
   // comprobar su validez cuando se reciba un token desde un formulario
   $_SESSION['csrf'][$form.'_token'] = array('token'=>$token, 'time'=>$token_time); 

   return $token;
}


/*****************************************************************************
funcion: verificarToken

	Funcion invocada por la pagina que requiere seguridad. Verifica si el
	token es igual al del formulario y si la sesion ha caducada (si aplica)
	sessiontime=0 (la sesion no caduca)
*****************************************************************************/

function verificarToken($form, $token, $sessiontime=0) {
 
   // comprueba si hay un token registrado en sesión para el formulario
   if(!isset($_SESSION['csrf'][$form.'_token'])) {
    	return false;
   }
 
   // compara el token recibido con el registrado en sesión
   if ($_SESSION['csrf'][$form.'_token']['token'] !== $token) {
	return false;
   }
 
   // si se indica un tiempo máximo de validez del ticket se compara la
   // fecha actual con la de generación del ticket
   if($sessiontime > 0){
       $token_resta = time() - $_SESSION['csrf'][$form.'_token']['time'];
       if($token_resta >= $sessiontime){
      return false;
       }
   }
 
   return true;
}

?>

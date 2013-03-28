/***************************************/
function validar(forma)
{ var sw=0,alerta;

alerta="Falta por ingresar: \n\n";
 if (forma.login.value=="")
  {alerta=alerta+"Usuario\n";
  sw=1;
  }
  if(forma.password.value=="")
 { alerta=alerta+"Contraseña";
   sw=1;
   
 }
 
 
   if (sw==1)
   {
   alert (alerta);
   return false;
   }
   else
   return true;
   
}
/*************************************/

<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_tipoprocessamento()" bgcolor="#cccccc">
<?

if (db_getsession("DB_id_usuario") == 1) {
  
?>

<table align="center" border=0 width="100%">
 <tr><td height="15"></td></tr>
 <tr>
  <td>
  <form name="form1" method="POST" action="arr4_manutencaovinculosnumpre002.php">
   <table align="center" border=0 width="600">
     <tr>
      <td height="50">
      </td>
     </tr>

     <tr>
      <td width=192> <strong>Numpre: </strong> </td>
      <td> <? db_input('numpre',11,1,true,"text",1) ?> </td>
     </tr>
     
     <tr>
      <td width=192> <strong>Tipo de Vinculo: </strong> </td>
      <td>
        <? 
         $aTipos = array("0"=>"Selecione","1"=>"Matricula","2"=>"Inscri��o");
         db_select("tipo", $aTipos,true,1);
        ?> 
      </td>
     </tr>
     
     <tr>
      <td align="center" colspan=2 style='padding:30px'>
       <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" onClick="js_valida()"> 
      </td>
     </tr>
     
   </table>
   </form>
  </td>
 </tr>    
</table>

<?
 } else {
   db_msgbox("Procedimento n�o dispon�vel!");
 }
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_valida() {

 if ($F("numpre") == "") {
    alert("Informe o numpre");
    return false;
 }

 if ($F("tipo") == "0") {
    alert("Informe o tipo de vinculo");
    return false;
 }
 
 document.form1.submit();
 
} 
</script>
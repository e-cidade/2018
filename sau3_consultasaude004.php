<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once('libs/db_utils.php');
db_postmemory($HTTP_POST_VARS);


$db_opcao = 3;
$db_botao = true;

$oDaoCgsUnd = db_utils::getdao("cgs_und");
$oDaoCgsUnd->rotulo->label();


if (isset($z01_i_cgsund)) {
	
  $sSql     = $oDaoCgsUnd->sql_query("","*",""," z01_i_cgsund = $z01_i_cgsund ");
  $rsResult = $oDaoCgsUnd->sql_record($sSql);
  db_fieldsmemory($rsResult,0);
  
}

?>
<html>
  <head>
     <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
     <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
     <meta http-equiv="Expires" CONTENT="0">
     <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
     <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post" action="">
   <center>
     <table width="100%"> 
       <tr>
         <td nowrap >
           <?=$Lz01_v_cgccpf?>
         </td>
         <td>
           <? db_input('z01_v_cgccpf', 40, $Iz01_i_cgsund, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_v_ident?>
         </td>
         <td>
           <? db_input('z01_v_ident', 40, $Iz01_v_ident, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_d_nasc?>
         </td>
         <td>
           <? db_input('z01_d_nasc', 40, $Iz01_i_cgsund, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_v_sexo?>
         </td>
         <td>
           <? db_input('z01_v_sexo', 40, $Iz01_v_sexo, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_i_estciv?>
         </td>
         <td>
           <? db_input('z01_i_estciv', 40, $Iz01_i_cgsund, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           &nbsp;
         </td>
         <td>
           &nbsp;
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_v_pai?>
         </td>
         <td>
           <? db_input('z01_v_pai', 40, $Iz01_v_pai, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_v_mae?>
         </td>
         <td>
           <? db_input('z01_v_mae', 40, $Iz01_v_mae, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_v_ender?>
         </td>
         <td>
           <? db_input('z01_v_ender', 40, $Iz01_v_ender, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_i_numero?>
         </td>
         <td>
           <? db_input('z01_i_numero', 40, $Iz01_i_numero, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_v_compl?>
         </td>
         <td>
           <? db_input('z01_v_ender', 40, $Iz01_v_ender, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           &nbsp;
         </td>
         <td>
           &nbsp;
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_v_bairro?>
         </td>
         <td>
           <? db_input('z01_v_bairro', 40, $Iz01_v_bairro, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_v_munic?>
         </td>
         <td>
           <? db_input('z01_v_munic', 40, $Iz01_v_munic, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_v_cep?>
         </td>
         <td>
           <? db_input('z01_v_cep', 40, $Iz01_v_cep, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_v_uf?>
         </td>
         <td>
           <? db_input('z01_v_uf', 40, $Iz01_v_uf, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
       <tr>
         <td nowrap >
           <?=$Lz01_v_telef?>
         </td>
         <td>
           <? db_input('z01_v_telef', 40, $Iz01_v_telef, true, 'text', $db_opcao, "")?> 
         </td>
         <td nowrap >
           <?=$Lz01_v_telcel?>
         </td>
         <td>
           <? db_input('z01_v_telcel', 40, $Iz01_v_telcel, true, 'text', $db_opcao, "")?>
         </td>
       </tr>
     </table>
   </center>
  </form>
  </body>
</html>
<script>
function js_formataData(dData) {
	  
  if(dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}

function js_estadoCivil(iCodigo) {

  switch (iCodigo) {

    case 0:
      return 'Não informado';

    case 1:
      return 'Solteiro.';

    case 2:
     return 'Casado';

    case 3:
      return 'Viúvo';

    case 4:
      return 'Separado';

    case 5:
      return 'União C.';

    case 9:
      return 'Ignorado';

    default:
      return '';

  }

}

function js_getInfoCgs() {

  var oParam  = new Object();
  oParam.exec = "getInfoCgs";
  oParam.iCgs = $F('z01_i_cgsund');

  if($F('z01_i_cgsund') != '') {
    js_webajax(oParam, 'js_retornogetInfoCgs', 'sau4_ambulatorial.RPC.php');
  }

}

function js_retornogetInfoCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  dNasc    = js_formataData(oRetorno.z01_d_nasc);
  sEstCiv  = js_estadoCivil(parseInt(oRetorno.z01_i_estciv.urlDecode(), 10));


  /* Preencho as informações do CGS */
  $('z01_v_ender').value  = oRetorno.z01_v_ender.urlDecode(); 
  $('z01_v_bairro').value = oRetorno.z01_v_bairro.urlDecode();
  $('z01_v_munic').value  = oRetorno.z01_v_munic.urlDecode();
  $('z01_v_cep').value    = oRetorno.z01_v_cep.urlDecode();
  $('z01_v_uf').value     = oRetorno.z01_v_uf.urlDecode();
  $('z01_v_telef').value  = oRetorno.z01_v_telef.urlDecode();
  $('z01_v_telcel').value = oRetorno.z01_v_telcel.urlDecode();
  $('z01_d_nasc').value   = dNasc;
  $('z01_v_cgccpf').value = oRetorno.z01_v_cgccpf.urlDecode();
  $('z01_v_ident').value  = oRetorno.z01_v_ident.urlDecode();
  $('z01_v_mae').value    = oRetorno.z01_v_mae.urlDecode();
  $('z01_v_pai').value    = oRetorno.z01_v_pai.urlDecode();
  $('z01_i_numero').value = oRetorno.z01_i_numero.urlDecode();
  $('z01_v_sexo').value   = oRetorno.z01_v_sexo.urlDecode();
  $('z01_v_compl').value  = oRetorno.z01_v_compl.urlDecode();
  $('z01_i_estciv').value = sEstCiv;

}
</script>
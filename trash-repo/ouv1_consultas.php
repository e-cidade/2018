<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$oGet = db_utils::postMemory($_GET); 
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  <body>
    <center>
     <table><tr><td> 
      <fieldset>
         <Legend><b>Imprimir pesquisa</b></Legend>
         <form name='frmopcoes'>
         <table>
           <tr>
             <td>
               <B>Escolha o que ser� impresso no relat�rio:</b>
               <hr>
             </td>
           </tr>  
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sAtendimentosVinculados' value='1' checked>
                <label for='sAtendimentosVinculados'>Atendimentos Vinculados</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sDespachos' value='1' checked>
                <label for='sDespachos'>Despachos</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sRetornos' value='1' checked>
                <label for='sRetornos'>Retornos Efetuados</label>
             </td>
           </tr>
         </table>  
      </fieldset>
     </td></tr></table> 
     <input type='button' value='Visualizar' onclick='js_visualizarRelatorio(<?=$oGet->iAtendimento?>,<?=$oGet->iProcesso?>)'>
     </form>
    </center>
  </body>
</html>
<script>

function js_visualizarRelatorio(iAtendimento,iProcesso) {

  var url = 'iAtendimento='+iAtendimento+'&iProcesso='+iProcesso+'&';
  
  if ($('sAtendimentosVinculados').checked) {
    url +='sAtendimentosVinculados=1&';
  }  
  if ($('sDespachos').checked) {
    url +='sDespachos=1&';
  }  
  if ($('sRetornos').checked) {
    url +='sRetornos=1';
  }  
 
  url = 'ouv1_reldetalhesprocesso002.php?'+url;

  var jan = window.open(url+'permissao=true','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');   
}
</script>
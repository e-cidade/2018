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
               <B>Escolha o que sera impresso no relatório:</b>
               <hr>
             </td>
           </tr>  
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sEstoque' value='1' checked>
                <label for='sEstoque'>Estoque</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sLancamentos' value='1' checked>
                <label for='sLancamentos'>Lançamentos</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sRequisicoes' value='1' checked>
                <label for='sRequisicoes'>Requisições</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sAtendimentos' value='1' checked>
                <label for='sAtendimentos'>Atendimentos</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sDevolucoes' value='1' checked>
                <label for='sDevolucoes'>Devoluções</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sPontoPedido' value='1' checked>
                <label for='sPontoPedido'>Ponto de Pedido</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sLotes' value='1' checked>
                <label for='sLotes'>Lotes</label>
             </td>
           </tr>
           <tr>
             <td>
                <input class='sOpcoes' type='checkbox' id='sNotaFiscal' value='1' checked>
                <label for='sNotaFiscal'>Nota Fiscal</label>
             </td>
           </tr>
         </table>  
      </fieldset>
     </td></tr></table> 
     <input type='button' value='Visualizar' onclick='js_visualizarRelatorio()'>
     </form>
    </center>
  </body>
</html>

<script>

var iCodigoMaterial = <?=$oGet->codmater?>;

function js_visualizarRelatorio() {

  var sUrlFiltros = "mat3_consultamaterialimprimir002.php?iMaterial="+iCodigoMaterial;
  
  if ($('sEstoque').checked) {
    sUrlFiltros +='&lEstoque=true';
  }
  if ($('sLancamentos').checked) {
    sUrlFiltros +='&lLancamentos=true';
  }
  if ($('sRequisicoes').checked) {
    sUrlFiltros +='&lRequisicoes=true';
  }
  if ($('sAtendimentos').checked) {
    sUrlFiltros +='&lAtendimentos=true';
  }
  if ($('sDevolucoes').checked) {
    sUrlFiltros +='&lDevolucoes=true';
  }
  if ($('sPontoPedido').checked) {
    sUrlFiltros +='&lPontoPedido=true';
  }
  if ($('sLotes').checked) {
    sUrlFiltros +='&lLotes=true';
  }
  if ($('sNotaFiscal').checked) {
    sUrlFiltros +='&lNotaFiscal=true'; 
  }
  jan = window.open(sUrlFiltros, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}
</script>
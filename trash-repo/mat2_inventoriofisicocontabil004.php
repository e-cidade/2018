<?php
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

/**
 * 
 * @author I
 * @revision $Author: dbevandro $
 * @version $Revision: 1.2 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, prototype.js, strings.js");
db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  <form name='form1' method='post'>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend><b>Filtros</b></legend>
          <table border=0>
            <tr>
               <td><b>Período:</b></td>
               <td>
                 <?  
                   db_inputdata('datainicial',null, null,null, true, "text", 1);
                 ?>
                 
                 <b>&nbsp; A &nbsp;</b> 
                 <?  
                   db_inputdata('datafinal',null, null,null, true, "text", 1);
                 ?> 
               </td>
            </tr>
            <tr>
              <td>
                <b>Almoxarifado</b>
              </td>
              <td colspan="4">
              <?
              $cldb_almox = db_utils::getDao("db_almox");
              $result = $cldb_almox->sql_record($cldb_almox->sql_query(null,"coddepto,descrdepto","descrdepto"));
              db_selectrecord("coddepto",$result,true,(isset($mostrapesquisa)?"3":"1"),"","","","0-Selecione");
              ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>
                  Ordenar Por:
                </b>
              </td>
              <td>
                 <?
                  $aOrderBy = array(1 => "Código do Item",
                                    2 => "Descrição do Item"
                                   );
                                   
                  db_select("orderby", $aOrderBy, true, 1);                 
                 ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>
                  Exibir Saldos Negativos:
                </b>
              </td>
              <td>
                 <?
                  $aSaldoNegativo = array("N" => "Não",
                                          "S" => "Sim"
                                   );
                                   
                  db_select("saldonegativo", $aSaldoNegativo, true, 1);                 
                 ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>
                  Exibir Itens Sem Movimento:
                </b>
              </td>
              <td>
                 <?
                  $aItensSemMovimento = array("N" => "Não",
                                              "S" => "Sim");
                                   
                  db_select("itenssemmovimento", $aItensSemMovimento, true, 1);                 
                 ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>
                  Opção de Impressão:
                </b>
              </td>
              <td>
                 <?
                  $aOpcao = array(1 => "Almoxarifado",
                                  2 => "Conta"
                                 );
                                   
                  db_select("opcaoImpressao", $aOpcao, true, 1);                 
                 ?>
              </td>
            </tr>
            <tr id='tipo-conta' >
              <td>
                <b>
                  Tipo de Impressão:
                </b>
              </td>
              <td>
                 <?
                  $aTipo = array(1 => "Sintética",
                                 2 => "Analitica"
                                );
                                   
                  db_select("tipoImpressao", $aTipo, true, 1);
                 ?>
              </td>
            </tr>
            <tr id='tipo-conta' >
              <td>
                <b>
                  Agrupar por elemento:
                </b>
              </td>
              <td>
                 <?
                  $aAgruparPorElemento = array(1 => "Nao",
                                               2 => "Sim"
                                              );
                                   
                  db_select("agruparporelemento", $aAgruparPorElemento, true, 1);
                 ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="3" style='text-align: center'>
        <input type="button" id='btnEnviar' name='btnEnviar' value='Visualizar' onclick="js_visualizarRelatorio()">
      </td>
    </tr>
  </table>
  </form>
  </center>
</body>
</html>  
<script>
function js_visualizarRelatorio() {
  
  if ($F('datainicial') == "" || $F('datafinal') == "") {
    
    alert('Informe do período!');
    if ($F('datainicial') == "") {

     $('datainicial').focus();
     return false;
    }
    if ($F('datafinal') == "") {
     
     $('datafinal').focus();
     return false;
    }
  }
  var virgula     = "";
  var listaContas = "";

  for(x = 0; x < parent.iframe_inventariorel.document.form1.contas.length; x++) {
    
    listaContas += virgula+parent.iframe_inventariorel.document.form1.contas.options[x].value;
    virgula = ",";
  }
  
  var sUrl  = "mat2_inventariofisicocontabil002.php?";
  sUrl     += "datainicial="+$F('datainicial');
  sUrl     += "&datafinal="+$F('datafinal');
  sUrl     += "&almoxarifado="+$F('coddepto');
  sUrl     += "&orderby="+$F('orderby');
  sUrl     += "&saldonegativo="+$F('saldonegativo');
  sUrl     += "&itenssemmovimento="+$F('itenssemmovimento');
  sUrl     += "&listacontas="+listaContas;
  sUrl     += "&impressao="+$F('opcaoImpressao');
  sUrl     += "&tipo="+$F('tipoImpressao');
  sUrl     += "&agruparporelemento="+$F('agruparporelemento');
      
  var jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+
                       (screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

$('opcaoImpressao').observe('change', function() {

  var iOpcaoImpressao = $F('opcaoImpressao');

  if (iOpcaoImpressao == 2) {
    $('tipo-conta').style.display = "none";
    parent.document.formaba.inventariorel.disabled = false;
  } else {
    $('tipo-conta').style.display = "table-row";
    parent.document.formaba.inventariorel.disabled = true;
  }
  
 
});
</script>
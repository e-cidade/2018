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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oRotuloInscricaopassivo = new rotulo("inscricaopassivo");
$oRotuloInscricaopassivo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px" >
  <center>
  <div style="display: table;">    
  <form id="form1" name="form1">
    <fieldset style="width: 200px;">  
      <legend><b>Inscrição por Empenho</b></legend>
      <table border="0">          
        <!-- Inscrição por Empenho-->
        <tr>
          <td>
            <?
            db_ancora("<b>Inscrição:</b>", "js_pesquisaInscricao(true)", 1);
            ?>
          </td>     
          <td>
            <?
            $funcaoJs= "onchange = 'js_pesquisaInscricao(false);'";
            db_input('c36_sequencial', 10, $Ic36_sequencial, true, 'text', 1, $funcaoJs);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <center>
      <br />
      <input type='button' name="btnEmitirEmpenho" id="btnEmitirEmpenho" value='Emitir Empenho' onclick = "js_emitirEmpenho();">
    </center>
  </form>
  </div>    
  </center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  
<script >
$('btnEmitirEmpenho').disabled = true;
/**
 * Função de emissão de empenho
 * repassa o código da inscrição para tela seguinte  
 */
function js_emitirEmpenho() {


  if (!$F('c36_sequencial')) {
    
    alert("Inscrição não informada");
    return false;
  }
  
  var sParametros  = "iCodigoInscricao="+$F('c36_sequencial');
  var sUrl         = "con4_baixaempenhosemorcamento002.php?"+sParametros;
  location.href    = sUrl; 
}

/* Funções de pesquisa Inscrição */
function js_pesquisaInscricao(lMostra) {
  
  var sUrlLookUp = "func_inscricaopassivo.php?pesquisa_chave="+$F('c36_sequencial')+"&lEmpenhado=true&lAnulado=true&funcao_js=parent.js_preencheInscricao";
  if (lMostra) {
    sUrlLookUp = "func_inscricaopassivo.php?lEmpenhado=true&lAnulado=true&funcao_js=parent.js_mostraInscricao|c36_sequencial";
  }
  js_OpenJanelaIframe("", "db_iframe_inscricaopassivo", sUrlLookUp, "Pesquisa Inscrição", lMostra);
}

function js_mostraInscricao(iCodigoInscricao) {

  $("c36_sequencial").value = iCodigoInscricao;
  $('btnEmitirEmpenho').disabled = false;
  db_iframe_inscricaopassivo.hide();
}

function js_preencheInscricao(iCodigoInscricao, lErro) {

  if (!lErro) {
    $('btnEmitirEmpenho').disabled = false; 
  } else {

    $('btnEmitirEmpenho').disabled = true;
    $('c36_sequencial').value = '';
  }
}
</script>
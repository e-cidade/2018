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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      $sLib  = "scripts.js,prototype.js,datagrid.widget.js,strings.js,grid.style.css,";
      $sLib .= "estilos.css,webseller.js,classes/DBVisualizadorImpressaoTexto.js";
      db_app::load($sLib);
    ?>
    <link href="./estilos/dbVisualizadorImpressaoTexto.style.css" rel="stylesheet" type="text/css">
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" id='visualizador'>
    <center>
      <big>Pré-visualização da Impressão</big>
    </center>
    <?db_input('sSessionNome',500,null,true,'hidden',1,'');?>
  </body>
</html>
<script>
visualizadorTxt();

function visualizadorTxt() {

  var oParam          = new Object();
  oParam.exec         = 'getArquivoTXT';
  oParam.sSessionNome = $F('sSessionNome');
  js_webajax(oParam, 'retornoVisualizadorTxt', 'sau4_ambulatorial.RPC.php');

}

function retornoVisualizadorTxt(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    oVisualizador = new DBVisualizadorImpressaoTexto('visualizador', '80%', '100%');
    oVisualizador.setImpressoras(oRetorno.aImpressoraId, oRetorno.aImpressoraDescr);
    oVisualizador.iIpPadrao = oRetorno.iIpPadrao;
    oVisualizador.gerarVisualizador();
    iTam = oRetorno.aArquivo.length;
    for (i=0; i < iTam; i++) {
      oVisualizador.addArquivo(oRetorno.aArquivo[i]);
    }
    lResultado = oVisualizador.renderizarArquivos();
    if (lResultado == false) {
      alert('Erro ao gerar arquivo TXT!');
    }

  }

}
</script>
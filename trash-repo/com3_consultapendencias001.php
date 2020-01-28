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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");

/**
* Carregamos no objeto $oGet o valor do $_GET e validamos se a propriedade pc10_numero foi setada.
* Se o teste for negativo redirecionamos para uma página de erro informando que a solicitação da
* pesquisa não foi passada
*/
$oGet = db_utils::postMemory($_GET, false);

if (!isset($oGet->pc10_numero) || trim($oGet->pc10_numero) == "") {

  $sMsgErro = urlencode("Pesquisa sem parâmetros.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}

?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">
	<div id = "gridContainer"></</div>
</body>
</html>

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/messageboard.widget.js"></script>
<script>

/**
 * Definimos a solicitação que será a chave para a pesquisa principal e a URL 
 * para o RPC que será utilizado nas pesquisas
 */
var sSolicita = <?php echo $oGet->pc10_numero; ?>;
var sUrl      = 'com4_cadpendencias002.RPC.php';

/**
 * Criamos a grid e em seguida a populamos com as informações necessárias
 */
var dbGrid                = new DBGrid('gridContainer');
    dbGrid.nameInstance   = 'dbGrid';
    dbGrid.hasTotalizador = false;
    dbGrid.setHeight(150);
    dbGrid.allowSelectColumns(false);
var aAligns    = new Array();
    aAligns[0] = 'right';
    aAligns[1] = 'left';
    aAligns[2] = 'center';
    aAligns[3] = 'left';
var aHeader    = new Array();
    aHeader[0] = 'Código';
    aHeader[1] = 'Pendência';
    aHeader[2] = 'Data Inclusão';
    aHeader[3] = 'Usuário';
//dbGrid.setCellWidth();
dbGrid.setCellAlign(aAligns);
dbGrid.setHeader(aHeader);
dbGrid.show($('gridContainer'));

js_divCarregando('Aguarde, pesquisando pendencias', 'msgBox'); // exibimos o gif de status da pesquisa

var oParam              = new Object();
    oParam.sExec        = 'getPendenciasSolicitacao';
    oParam.iSolicitacao = sSolicita;

var oAjax = new Ajax.Request(sUrl,
                            {method:'post',
                             parameters:'json='+Object.toJSON(oParam),
                             onComplete:js_populaGrid
                            }
                           );

/**
 * Função que popula a grid com as suas informações
 */
function js_populaGrid(oAjax) {
  
	js_removeObj('msgBox'); // ocultamos o gif de status da pesquisa
	var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1 && oRetorno.aDados !== null) {

  	dbGrid.clearAll(true);

    oRetorno.aDados.each(function (oDado, iInd) {

      var aRowPendencia     = new Array();
          aRowPendencia[0]  = oDado.pc91_sequencial;
          aRowPendencia[1]  = oDado.pc91_pendencia;
          aRowPendencia[2]  = oDado.pc91_datainclusao;
          aRowPendencia[3]  = oDado.nome;
  		dbGrid.addRow(aRowPendencia);

  		dbGrid.aRows[iInd].sEvents = 'ondblclick="parent.js_exibeDetalhesPendencia('+oDado.pc91_sequencial+');"';
    });
    
  	dbGrid.renderRows();
  }
}

</script>
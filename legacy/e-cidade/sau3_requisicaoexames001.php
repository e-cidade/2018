<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
?>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
   <link href="estilos.css" rel="stylesheet" type="text/css">
   <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  </head>
<body>
  <fieldset>
    <legend>Exames</legend>
    <div id="gridExames"></div>
  </fieldset>
</body>
</html>
<script>
const MENSANGEM_SAU3_REQUISICAOEXAMES001 = 'saude.ambulatorial.sau3_requisicaoexames001.';

var oGet = js_urlToObject();

var oGridExames = new DBGrid( 'oGridExames' );
var aHeaders    = [ 'Exame' ];
var aCellAlign  = [ 'left' ];

oGridExames.nameInstance = 'oGridExames';
oGridExames.setCellAlign(aCellAlign);
oGridExames.setHeader(aHeaders);
oGridExames.setHeight(150);
oGridExames.show($('gridExames'));

(function() {

var oParametros             = {};
    oParametros.sExecucao   = 'buscarRequisicaoProntuario';
    oParametros.iProntuario = oGet.iProntuario;

var oAjaxRequest = new AjaxRequest( 'sau4_requisicaoexameprontuario.RPC.php', oParametros, retornoBuscarExames );
    oAjaxRequest.setMessage( _M( MENSANGEM_SAU3_REQUISICAOEXAMES001 + "buscando_exames") );
    oAjaxRequest.execute();
})();

function retornoBuscarExames( oRetorno, erro ) {

  oGridExames.clearAll(true);

  oRetorno.aExames.each( function( oExame ) {

    aLinhas = new Array();
    aLinhas.push( oExame.sExame.urlDecode() );

    oGridExames.addRow( aLinhas );
  });

  oGridExames.renderRows();
}
</script>
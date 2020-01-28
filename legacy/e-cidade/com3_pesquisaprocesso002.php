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

require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
$oGet           = db_utils::postMemory($_GET);
$sWhereProcesso = '';
if (!empty($oGet->dtInicial)) {
  $oGet->dtInicial = implode("-", array_reverse(explode("/", $oGet->dtInicial)));
}
if (!empty($oGet->dtFinal)) {
  $oGet->dtFinal = implode("-", array_reverse(explode("/", $oGet->dtFinal)));
}
$sWhereProcesso = "pc10_instit = ".db_getsession("DB_instit");
if ($oGet->dtInicial != '' && $oGet->dtFinal == '') {
   $sWhereProcesso .= " and pc80_data >= '{$oGet->dtInicial}'"; 
} else if ($oGet->dtInicial == '' && $oGet->dtFinal != '') {
  $sWhereProcesso .= " and pc80_data <= '{$oGet->dtFinal}'";
} else if ($oGet->dtInicial != '' && $oGet->dtFinal != '') {
  $sWhereProcesso .= " and pc80_data between '{$oGet->dtInicial}' and '{$oGet->dtFinal}'";
}
if ($oGet->iSolicitacaoInicial  != "" && $oGet->iSolicitacaoFinal == '') {
  $sWhereProcesso .= " and pc10_numero >= {$oGet->iSolicitacaoInicial}";
} else if ($oGet->iSolicitacaoInicial  != "" && $oGet->iSolicitacaoFinal == '') {
  $sWhereProcesso .= " and pc10_numero <= {$oGet->iSolicitacaoFinal}";
} else if ($oGet->iSolicitacaoInicial  != "" && $oGet->iSolicitacaoFinal != '') {
  $sWhereProcesso .= " and pc10_numero between {$oGet->iSolicitacaoInicial} and {$oGet->iSolicitacaoFinal}";
}

if ($oGet->iProcessoInicial  != "" && $oGet->iProcessoFinal == '') {
  $sWhereProcesso .= " and pc80_codproc >= {$oGet->iProcessoInicial}";
} else if ($oGet->iProcessoInicial  != "" && $oGet->iProcessoFinal == '') {
  $sWhereProcesso .= " and pc80_codproc <= {$oGet->iProcessoFinal}";
} else if ($oGet->iProcessoInicial  != "" && $oGet->iProcessoFinal != '') {
  $sWhereProcesso .= " and pc80_codproc between {$oGet->iProcessoInicial} and {$oGet->iProcessoFinal}";
}

$oDaoPcProc = db_utils::getDao("pcproc");
$sSqlProcessoCompras = $oDaoPcProc->sql_query_proc_solicita(null, 
                                                            "distinct pc80_codproc, 
                                                             pc80_data, 
                                                             pc80_depto,
                                                             descrdepto,
                                                             pc80_resumo", 
                                                            "pc80_data", 
                                                            $sWhereProcesso
                                                            );

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
  <body bgcolor="#cccccc" onload="">
    <center>
      <form name="form1" method="post">
        <div style="display: table;">
          <fieldset>
            <legend><b>Processos de Compras</b></legend>
          <?
           db_lovrot($sSqlProcessoCompras, 15, "()", "", "js_abrePesquisaProcesso|pc80_codproc");
          ?>
          </fieldset>
        </div>
      </form>
    </center>
  </body>
</html>
<script>
  function js_abrePesquisaProcesso(iProcesso) {
     
     js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_pesquisa_processo',
                        'com3_pesquisaprocessocompras003.php?pc80_codproc='+iProcesso,
                        'Consulta Processo de Compras',
                        true
                       );  
  }
</script>
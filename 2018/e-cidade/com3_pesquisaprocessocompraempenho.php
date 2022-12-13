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
$oGet                = db_utils::postMemory($_GET);
$oDaoPcProc          = db_utils::getDao("pcproc");
$sSqlProcessoCompras = $oDaoPcProc->sql_query_empenho($oGet->iProcesso, 
                                                  "distinct e60_numemp, 
                                                   e60_codemp||'/'||cast(e60_anousu as varchar) as e60_codemp, 
                                                   e60_emiss,
                                                   z01_nome,
                                                   e60_vlremp ,
                                                   e60_vlrliq,
                                                   e60_vlrpag,
                                                   e60_vlranu,
                                                   e60_resumo
                                                   ",
                                                  "e60_numemp");


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
            <legend><b>Empenhos do Processo de Compras</b></legend>
          <?
           db_lovrot($sSqlProcessoCompras, 15, "()", "", "js_mostraempenho|e60_numemp");
          ?>
          </fieldset>
        </div>
      </form>
    </center>
  </body>
</html>
<script>
  function js_mostraempenho(iEmpenho) {
     
     js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_pesquisaempenho',
                        'func_empempenho001.php?e60_numemp='+iEmpenho,
                        'Dados do Empenho',
                        true
                       );  
  }
</script>
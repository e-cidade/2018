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
$sWhereProcesso      = "pc80_codproc ={$oGet->iProcesso}";
$sSqlProcessoCompras = $oDaoPcProc->sql_query_dados_item(null, 
                                                            "pc11_numero,
                                                             pc90_numeroprocesso,
                                                             pc11_seq,
                                                             pc01_descrmater, 
                                                             pc11_quant,
                                                             pc11_vlrun,
                                                             pc11_resum",
                                                            "pc11_numero, pc11_seq", 
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
            <legend><b>Itens do Processos de Compras</b></legend>
          <?
           db_lovrot($sSqlProcessoCompras, 15);
          ?>
          </fieldset>
        </div>
      </form>
    </center>
  </body>
</html>
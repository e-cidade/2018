<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_layouttxt.php");

$oGet                   = db_utils::postMemory($_GET);
$oClassesPit50          = new stdClass;
$oClassesPit50->arquivo = "arquivoPit50";
$aClasses[50]           = $oClassesPit50;

require_once("model/{$aClasses[$oGet->tipodocumento]->arquivo}.model.php");
$oArquivoPit   = new arquivoPit50();
$sArquivoPit   = "tmp/arquivo_pit_".date("Ymd",db_getsession("DB_datausu")).".txt"; 
$oLayoutPIT    = new db_layouttxt($oArquivoPit->getCodigoLayout(), $sArquivoPit);
$dtInicial     = implode("-", array_reverse(explode("/", $oGet->datainicial)));  
$dtFinal       = implode("-", array_reverse(explode("/", $oGet->datafinal)));
$aNotas        =  $oArquivoPit->getNotasParaArquivo($dtInicial, $dtFinal);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>  
  <body>
  <form name='form1'>
      <?
       db_criatermometro("progressbarpit","Arquivo Gerado");
       $i = 0;
       $lArquivoGerado = false;
       try {
         
         db_inicio_transacao();
         $oArquivoPit->writeHeader($oLayoutPIT,$dtInicial,$dtFinal);
         foreach ($aNotas as $oNota) {
           
           $oArquivoPit->writeLine($oLayoutPIT, $oNota);
           db_atutermometro($i, count($aNotas),"progressbarpit");
           $i++;
         }
         $oArquivoPit->saveArquivo($oLayoutPIT);
         db_fim_transacao(false);
         $lArquivoGerado = true;
         
       } catch(Exception $eErro) {
         
         $lArquivoGerado = false;
         db_fim_transacao(true);
         db_msgbox(str_replace("\n","\\n",$eErro->getMessage()));
         
       }
      ?>
   </form>   
  </body>
</html>
<script>
<?
 if ($lArquivoGerado) {
  
   echo "var sLista = '{$sArquivoPit}#Arquivo pit'\n";
   echo "alert(sLista);\n";
   echo "js_montarlista(sLista,'form1');\n";
   echo "parent.gridNotas.clearAll(true);";
 }
?>  
</script>
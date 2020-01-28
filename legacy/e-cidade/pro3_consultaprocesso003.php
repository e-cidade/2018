<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oDaoProcesso = db_utils::getDao("protprocesso");
$sWhere       = "     p58_instit = ".db_getsession("DB_instit");
//$sWhere      .= " and tipoproc.p51_tipoprocgrupo = 1 ";

if (isset($oGet->cgm) && !empty($oGet->cgm)) {
  $sWhere .= " and p58_numcgm = {$oGet->cgm}";
} else if (isset($oGet->codproc) && !empty($oGet->codproc)) {
  $sWhere .= " and p58_codproc = {$oGet->codproc}";
} else if (isset($oGet->numeroprocesso) && !empty($oGet->numeroprocesso)) {
  
  $aNumeroProcesso  = explode("/", $oGet->numeroprocesso);
  $sWhere          .= " and p58_numero = '{$aNumeroProcesso[0]}' " ;
  if (count($aNumeroProcesso) > 1 && strlen($aNumeroProcesso[1]) == 4) {
    $sWhere .= " and p58_ano = {$aNumeroProcesso[1]}";
  } else {
    $sWhere .= "  and p58_ano = " . db_getsession("DB_anousu");
  }
  unset($aNumeroProcesso);
}  

$sCamposProcesso          = "p58_codproc,";
$sCamposProcesso         .= "p58_numero||'/'||p58_ano as p58_numero,";
$sCamposProcesso         .= "z01_nome,";
$sCamposProcesso         .= "p58_dtproc,";
$sCamposProcesso         .= "p51_descr,";
$sCamposProcesso         .= "p58_obs";
$sSqlBuscaCodigoProcesso  = $oDaoProcesso->sql_query(null, $sCamposProcesso, " 1 desc", $sWhere);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
      db_app::load('scripts.js, prototype.js');
      db_app::load('estilos.css');
    ?>
  </head>
  <body style='background-color: #cccccc'>
    <script type="text/javascript">

    function js_consultaProcesso(iCodigoProcesso) {

      var sUrl = 'pro3_consultaprocesso002.php?codproc='+iCodigoProcesso;
      js_OpenJanelaIframe('parent', 'db_iframe_consultaprocesso', sUrl, 'Consulta Processo');
      parent.db_iframe.hide();
    } 
    </script>
    <center>
      <?php 
        db_lovrot($sSqlBuscaCodigoProcesso, 15, "()", "", "js_consultaProcesso|p58_codproc", "", "NoMe");
      ?>
    </center>  
  </body>
</html>

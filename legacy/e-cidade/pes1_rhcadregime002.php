<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhcadregime_classe.php");
require_once("dbforms/db_funcoes.php");

define("MSG_PATH", "recursoshumanos.pessoal.pes1_rhcadregime002.");
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$clrhcadregime     = new cl_rhcadregime;
$clbaserhcadregime = new cl_baserhcadregime;
$clbases           = new cl_bases;
$db_opcao = 22;
$db_botao = false;

  $iInstituicao = db_getsession('DB_instit');
if( count($_POST) > 0 ) {

  db_inicio_transacao();
  $lErro = false;
  $sMensagem = _M(MSG_PATH."alterado_com_sucesso");

  $sWhereBaseRHCadRegimeExcluir  = "     rh158_regime = $rh52_regime";
  $sWhereBaseRHCadRegimeExcluir .= " and rh158_instit = $iInstituicao";
  $sWhereBaseRHCadRegimeExcluir .= " and rh158_ano    = " . DBPessoal::getAnoFolha();
  $sWhereBaseRHCadRegimeExcluir .= " and rh158_mes    = " . DBPessoal::getMesFolha();
  $clbaserhcadregime->excluir(null, $sWhereBaseRHCadRegimeExcluir);
  
  if ($clbaserhcadregime->erro_status == "0") {
    $sMensagem = _M(MSG_PATH."erro_alteracao");
  } 
  
  

  if ( !empty($rh158_basesubstituido) && !empty($rh158_basesubstituto) ) {
    
     $oDaoBaseRHCadRegime = new cl_baserhcadregime;
     $oDaoBaseRHCadRegime->rh158_regime          = $rh52_regime;
     $oDaoBaseRHCadRegime->rh158_ano             = DBPessoal::getAnoFolha();
     $oDaoBaseRHCadRegime->rh158_mes             = DBPessoal::getMesFolha();
     $oDaoBaseRHCadRegime->rh158_basesubstituto  = $rh158_basesubstituto;
     $oDaoBaseRHCadRegime->rh158_basesubstituido = $rh158_basesubstituido;
     $oDaoBaseRHCadRegime->rh158_instit          = $iInstituicao;
     $oDaoBaseRHCadRegime->incluir(null);
     if ($oDaoBaseRHCadRegime->erro_status == "0") {
       $lErro     = true;
       $sMensagem = _M(MSG_PATH."erro_alteracao");
     } 
  }


  db_msgbox($sMensagem);
  db_fim_transacao($lErro);


} 

if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $result   = $clrhcadregime->sql_record($clrhcadregime->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
  $oDaoBaseRHCadRegime = new cl_baserhcadregime;

  $sWhereBaseRHCadRegime  = "     rh158_regime = $rh52_regime";
  $sWhereBaseRHCadRegime .= " and rh158_instit = $iInstituicao";
  $sWhereBaseRHCadRegime .= " and rh158_ano    = " . DBPessoal::getAnoFolha();
  $sWhereBaseRHCadRegime .= " and rh158_mes    = " . DBPessoal::getMesFolha();

  $sSql = $oDaoBaseRHCadRegime->sql_query(null,"rh158_basesubstituto, 
                                                rh158_basesubstituido, 
                                                substituido.r08_descr as descricao_substituido, 
                                                substituto.r08_descr  as descricao_substituto",
                                          null, $sWhereBaseRHCadRegime);
  // die($sSql);


  $rsSql = db_query($sSql);
  if (pg_num_rows($rsSql) > 0) {
    db_fieldsmemory($rsSql, 0);
  }
  $db_botao = true;
} 
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
include("forms/db_frmrhcadregime.php");
db_menu();
?>
</body>
</html>
<?
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","rh52_descr",true,1,"rh52_descr",true);
</script>

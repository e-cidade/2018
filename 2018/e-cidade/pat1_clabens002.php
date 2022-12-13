<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_cfpatri_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_clabensconplano_classe.php");


db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clclabens = new cl_clabens;
$cldb_estrut = new cl_db_estrut;
$clcfpatri = new cl_cfpatri;
if(isset($t64_codcla)){
  $db_opcao = 2;
  $db_botao = true;
}else{
  $db_opcao = 22;
  $db_botao = false;
}
if(isset($alterar)){
  
  
  if ($oPost->t64_bemtipos == "0") {
    $erro_msg = _M("patrimonial.patrimonio.db_frmclabens.informe_tipo_bem");
  } else {
  
    try {
  
      db_inicio_transacao();
      $oClassificacao = new BemClassificacao($oPost->t64_codcla, DB_getsession("DB_anousu"));
      $oClassificacao->setClassificacao($oPost->t64_class);
      $oClassificacao->setDescricao($oPost->t64_descr);
      $oClassificacao->setObservacao($oPost->t64_obs);
      $oClassificacao->setPlanoConta($oPost->t86_conplano);
      $oClassificacao->setCodigoContaDepreciacao($oPost->t86_conplanodepreciacao);
      $oClassificacao->setAnalitica($oPost->t64_analitica);
      $oClassificacao->setTipoBem($oPost->t64_bemtipos);
      $oClassificacao->setTipoDepreciacao($oPost->t64_benstipodepreciacao);
      $oClassificacao->setVidaUtil($oPost->t64_vidautil);
      $oClassificacao->setInstituicao(db_getsession("DB_instit"));
      $oClassificacao->salvar();
      $erro_msg = _M("patrimonial.patrimonio.db_frmclabens.classificacao_salva");
      db_fim_transacao(false);
  
    } catch (Exception $eErro) {
  
      db_fim_transacao(true);
      $erro_msg = $eErro->getMessage();
    }
  }
  
}else if(isset($chavepesquisa)){
    $db_opcao = 2;
    $db_botao = true;
    
    $sCampos  = " t64_codcla, t64_class, t64_descr, t64_obs, t64_analitica,";
    $sCampos .= " t64_bemtipos, t64_benstipodepreciacao, t64_vidautil, ";
    $sCampos .= " t46_descricao,";
    $sCampos .= " t86_conplano, t86_conplanodepreciacao,";
    $sCampos .= " contadepreciacao.c60_descr as descricaocontadepreciacao, conta.c60_descr as descricaoconta";
    
    $result = $clclabens->sql_record($clclabens->sql_query_contas($chavepesquisa,$sCampos));
    db_fieldsmemory($result,0);
}
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
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmclabens.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  db_msgbox($erro_msg);
  db_redireciona("pat1_clabens002.php");
}  
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
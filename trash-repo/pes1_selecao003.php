<?php
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_selecao_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clselecao = new cl_selecao;
$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){

  try{
  
    db_inicio_transacao();
    $db_opcao = 3;

    /**
     * Verifica se a seleção que esta sendo exluida esta vinculada a alguma regra do ponto, 
     * se estiver, não permite a exclusão.
     */
    $oDaoRegraPonto  = db_utils::getDao('regraponto');
    $sSqlRegraPonto  = $oDaoRegraPonto->sql_query(null, 'rh123_sequencial, rh123_descricao', null, "rh123_selecao = $r44_selec");
    $rsRegraPonto    = $oDaoRegraPonto->sql_record($sSqlRegraPonto);
    $aRegrasPontos   = db_utils::getCollectionByRecord($rsRegraPonto);
    $sErroRegraPonto = 'Não é possível excluir a seleção: \n';

    foreach ($aRegrasPontos as $oRegraPonto) {
      $sErroRegraPonto .= "Seleção $r44_selec está vinculada com regra: {$oRegraPonto->rh123_sequencial} - {$oRegraPonto->rh123_descricao} \\n" ;
    }

    if ( count($aRegrasPontos) > 0 ) {
      throw new Exception($sErroRegraPonto);
    }

    /**
     * Realiza a exclusão da seleção.
     */
    $oDaoRegraPontoRhRubricas = db_utils::getDao('regrapontorhrubricas');
    $clselecao->excluir($r44_selec);
    db_fim_transacao();
  } catch (Exception $oErro) {

    db_fim_transacao(true);
    $sErro = $oErro->getMessage();
  }

}else if(isset($chavepesquisa)){

   $db_opcao = 3;
   $result = $clselecao->sql_record($clselecao->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

	<?php include("forms/db_frmselecao.php"); ?>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
  
</body>
</html>
<?
if ( isset($excluir) ) {

  if ( empty($sErro) ) {

    if ( $clselecao->erro_status=="0" ) {
      $clselecao->erro(true,false);
    } else {
      $clselecao->erro(true,true);
    };
  } else {
    
    db_msgBox($sErro);
    echo "<script>document.form1.pesquisar.click();</script>";
  }
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
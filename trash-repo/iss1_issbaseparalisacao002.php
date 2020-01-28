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
require_once("classes/db_issbaseparalisacao_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/issqn/paralisacao/ParalisacaoEmpresa.model.php");

define('MENSAGEM', 'tributario.issqn.db_frmissbaseparalisacao.');

$clissbaseparalisacao = new cl_issbaseparalisacao;
$oRequest       = db_utils::postMemory($_REQUEST);
$sChavePesquisa = isset($oRequest->chavepesquisa) ? $oRequest->chavepesquisa : null;
$sAlterar       = isset($oRequest->alterar) ? $oRequest->alterar : null;
$db_opcao       = 22;
$db_botao       = false;

if ($sAlterar) {

  try {

    $oParalisacaoEmpresa = new ParalisacaoEmpresa($oRequest->q140_sequencial);

    if (!$oRequest->q140_issbase) {
      throw new ParameterException( _M(MENSAGEM . 'erro_inscricao'));    
    }

    $oParalisacaoEmpresa->setEmpresa( new Empresa($oRequest->q140_issbase));
    $oParalisacaoEmpresa->setMotivo( $oRequest->q140_issmotivoparalisacao);

    if (empty($oRequest->q140_datainicio)) {
    	throw new ParameterException(_M(MENSAGEM . 'erro_data_inicial_nao_informada'));
    }
		$oParalisacaoEmpresa->setDataInicio( new DBDate($oRequest->q140_datainicio));

    if (!empty($oRequest->q140_datafim)) {
      $oParalisacaoEmpresa->setDataFim( new DBDate($oRequest->q140_datafim));
    } else {
      $oParalisacaoEmpresa->setDataFim(NULL);
    }

    $oParalisacaoEmpresa->setObservacao($oRequest->q140_observacao);

    db_inicio_transacao();

    $oParalisacaoEmpresa->salvar();

    db_msgbox(_M(MENSAGEM . 'alterar'));

    db_fim_transacao(false);
    db_redireciona('iss1_issbaseparalisacao002.php');

  } catch (Exception $eErro) {

    db_msgbox($eErro->getMessage());  
    db_fim_transacao(true);
    $db_opcao = 2;
    $db_botao = true;

  }

} else if($sChavePesquisa) {

  try {

    $oParalisacaoEmpresa = new ParalisacaoEmpresa($sChavePesquisa);

    $q140_sequencial            = $oParalisacaoEmpresa->getCodigo();
    $q140_issbase               = $oParalisacaoEmpresa->getEmpresa()->getInscricao();
    $q140_issmotivoparalisacao  = $oParalisacaoEmpresa->getMotivo();

    if ($oParalisacaoEmpresa->getDataInicio() instanceof DBDate) {
      $q140_datainicio_dia      = $oParalisacaoEmpresa->getDataInicio()->getDia();
      $q140_datainicio_mes      = $oParalisacaoEmpresa->getDataInicio()->getMes();
      $q140_datainicio_ano      = $oParalisacaoEmpresa->getDataInicio()->getAno();
    }
    if ($oParalisacaoEmpresa->getDataFim() instanceof DBDate) {
      $q140_datafim_dia         = $oParalisacaoEmpresa->getDataFim()->getDia();
      $q140_datafim_mes         = $oParalisacaoEmpresa->getDataFim()->getMes();
      $q140_datafim_ano         = $oParalisacaoEmpresa->getDataFim()->getAno();
    }
    $q140_observacao            = $oParalisacaoEmpresa->getObservacao();
    $q141_descricao             = $oParalisacaoEmpresa->getDescricaoMotivo();
    $z01_nome                   = $oParalisacaoEmpresa->getEmpresa()->getCgmEmpresa()->getNome();

    $db_opcao = 2;
    $db_botao = true;
  } catch (Exception $eErro) {
    $db_botao = false;
    $db_opcao = 22;
  }

}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/numbers.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body bgcolor="#CCCCCC" >
  
  	<?php
  	  include("forms/db_frmissbaseparalisacao.php");
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

  </body>

</html>

<?php

  if ($db_opcao==22) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }

?>

<script>
  js_tabulacaoforms("form1","q140_issbase",true,1,"q140_issbase",true);
</script>
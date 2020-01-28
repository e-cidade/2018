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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhfolhapagamento_classe.php");
require_once("dbforms/db_funcoes.php");

define("MENSAGEM", 'recursoshumanos.pessoal.pes4_cancelaaberturasuplementar001.');

db_postmemory($HTTP_POST_VARS);

// Sempre será readonly
$db_opcao = 3;
$lProcessado = false;

if ( isset($_GET['cancelado']) ) {
  $lProcessado = true;
}     

try {

   /**
   *  Verifica se o parametro r11_suplementar na tabela cfpess está ativo.
   */
  if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()){

     /**
     * Desativa o formulário
     */
    $lDisabled = true;
    $db_opcao  = 3;

    throw new BusinessException(_M(MENSAGEM . "rotina_desativada"));
  }
     
} catch (Exception $eException) {
     
   throw new \ECidade\V3\Extension\Exceptions\ResponseException($eException->getMessage()); 
   db_redireciona('corpo.php');
}

try {

  db_inicio_transacao();

  // Se há folha aberta, desabilita os campos do formulário
  $lExisteFolhaAberta = FolhaPagamentoSuplementar::hasFolhaAberta(
    new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
  );

  if (isset($submit) && $lExisteFolhaAberta) {
    $oFolhaSuplementar = FolhaPagamentoSuplementar::getFolhaAberta();

    if ($oFolhaSuplementar->cancelarAbertura()) {
      db_fim_transacao();
      db_redireciona("pes4_cancelaaberturasuplementar001.php?cancelado=true");
      exit;
    }
  }
  
  if ($lExisteFolhaAberta) {

    $oFolhaPagamento = FolhaPagamentoSuplementar::getFolhaAberta();
    $rh141_codigo    = $oFolhaPagamento->getNumero();
    $rh141_descricao = $oFolhaPagamento->getDescricao();  
    $rh141_anoref    = $oFolhaPagamento->getCompetenciaReferencia()->getAno();
    $rh141_mesref    = $oFolhaPagamento->getCompetenciaReferencia()->getMes();
  }

} catch (Exception $e) {
  
  db_fim_transacao(true);
  db_msgbox($e->getMessage());
}

?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <form name="form1" method="post" class="container" action="">
    <fieldset>
      <legend>Cancelar Abertura da Folha Suplementar</legend>
      <?php include 'forms/db_frmrhfolhapagamento.php'; ?>
    </fieldset>

    <?php

    if ($lExisteFolhaAberta && !$lProcessado) {  
      echo '<input type="submit" name="submit" value="Processar" />';
    } elseif (!$lExisteFolhaAberta && !$lProcessado) {
      echo '<input type="submit" name="submit" value="Processar" disabled />';
      db_msgbox(_M(MENSAGEM . "folha_nao_aberta"));
    } else {
      echo '<input type="submit" name="submit" value="Processar" disabled />';
      db_msgbox(_M(MENSAGEM . 'cancelado_com_sucesso'));
    }
    ?>
  </form>
  <?php db_menu(); ?>
</body>
</html>

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

$oRotulo = new rotulocampo();
$oRotulo->label("rh141_sequencial");

define("MENSAGEM", 'recursoshumanos.pessoal.pes4_cancelafechamentosuplementar001.');

$lExisteFolhaFechada = false;

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

  $db_opcao        = 3;
  db_postmemory($_POST);

  $oFolhaPagamento     = FolhaPagamentoSuplementar::getUltimaFolha();
  $rh141_sequencial    =  $oFolhaPagamento->getSequencial();
  $lExisteFolhaFechada = !$oFolhaPagamento->isAberto();

  if (!$lExisteFolhaFechada) {
    throw new BusinessException(_M(MENSAGEM . "folha_fechada_inexistente"));
  }  
  
   
  if (isset($processar)) {
    $oPost              = db_utils::postMemory($_POST);
    $oFolhaSuplementar = new FolhaPagamentoSuplementar($oPost->rh141_sequencial);
   
    if ($oFolhaSuplementar->cancelarFechamento()) {
      db_fim_transacao();
      $sMensagem = _M(MENSAGEM . 'incluido_com_sucesso');
    }
  }

  $rh141_codigo      = $oFolhaPagamento->getNumero();
  $rh141_descricao   = $oFolhaPagamento->getDescricao();
  $rh141_anoref      = $oFolhaPagamento->getCompetencia()->getAno();
  $rh141_mesref      = $oFolhaPagamento->getCompetencia()->getMes();

  $oFolhaPagamento->verificarEmpenho();
  
} catch (Exception $eException) {
  db_fim_transacao(true);
  $sMensagem = $eException->getMessage(); 
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
    <?php db_input('rh141_sequencial', 4, $Irh141_sequencial, true, 'hidden', 3); ?>

      <fieldset>
        <legend>Cancelar Fechamento da Folha Suplementar</legend>
        <?php require_once('forms/db_frmrhfolhapagamento.php'); ?>
      </fieldset>

      <?php 

        if (empty($sMensagem)) {
          echo '<input type="submit" name="processar" value="Processar" />';

        } else {
          echo '<input type="button" value="Processar" disabled />';
          db_msgbox($sMensagem); 
        }
      ?>
    </form>
    <?php db_menu(); ?>
  </body>
</html>

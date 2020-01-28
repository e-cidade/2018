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
define("MENSAGEM", 'recursoshumanos.pessoal.pes4_cancelafechamentocomplementar001.');

$oRotulo = new rotulocampo();
$oRotulo->label("rh141_sequencial");

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
  
  $db_opcao = 3;

  /**
   * Verifica se existe uma folha salário
   * aberta na competência atual
   */
  $lExisteSalarioAberta = FolhaPagamentoSalario::hasFolhaAberta(
    new DBCompetencia(DBPessoal::getAnofolha(), DBPessoal::getMesFolha())
  );

  if ($lExisteSalarioAberta) {

    /**
     * Verifica se existe uma folha complementar
     * aberta na competência atual.
     */
    $lExisteComplementarAberta = FolhaPagamentoComplementar::hasFolhaAberta(
      new DBCompetencia(DBPessoal::getAnofolha(), DBPessoal::getMesFolha())
    );

    /**
     * Se a folha complementar estive aberta
     */
    if (!$lExisteComplementarAberta) {

      $oFolhaPagamento = FolhaPagamentoComplementar::getUltimaFolha();
      
      /**
       * Verifica se a folha complementar
       * está empenhada.
       */
      if ($oFolhaPagamento->verificarEmpenho()) {
      
        $lSubmit = isset($_POST['submit']);
        if ($lSubmit) {
          
          if ($oFolhaPagamento->cancelarFechamento()) {

            $lDisabled = true;

            /**
             * Exibe a mensagem de sucesso para usuário
             */
            db_msgbox(_M(MENSAGEM . 'fechamento_cancelado_com_sucesso'));
          }
        } else {

          $rh141_sequencial = $oFolhaPagamento->getSequencial();
          $rh141_codigo     = $oFolhaPagamento->getNumero();
          $rh141_descricao  = $oFolhaPagamento->getDescricao();
          $rh141_anoref     = $oFolhaPagamento->getCompetencia()->getAno();
          $rh141_mesref     = $oFolhaPagamento->getCompetencia()->getMes();

          $lDisabled = false;
        }
      } else {

        $lDisabled = true;
        throw new BusinessException(_M(MENSAGEM . "folha_complementar_empenhada"));
      }
    } else {

      $lDisabled = true;
      throw new BusinessException(_M(MENSAGEM . "folha_complementar_aberta")); // Ou não existe
    }
  } else {

    $lDisabled = true;
    throw new BusinessException(_M(MENSAGEM . "folha_salario_fechada"));
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  db_fim_transacao(true);
  db_msgbox($e->getMessage());
  $lDisabled = true;
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
        <legend>Cancelar Fechamento da Folha Complementar</legend>
        <?php require_once('forms/db_frmrhfolhapagamento.php'); ?>
      </fieldset>
  
      <?php 
        if ($lDisabled) {
          echo '<input type="submit" name="submit" value="Processar" disabled />';
        } else {     
          echo '<input type="submit" name="submit" value="Processar" />';
        }
      ?>
    </form>
    <?php db_menu(); ?>
  </body>
</html>

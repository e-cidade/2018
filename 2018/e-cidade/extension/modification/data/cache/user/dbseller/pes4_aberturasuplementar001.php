<?php
/**
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
define("MENSAGEM", 'recursoshumanos.pessoal.pes4_aberturasuplementar001.');

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

  $rh141_anoref    = DBPessoal::getAnoFolha();
  $rh141_mesref    = DBPessoal::getMesFolha();

  /**
   * Verifica se o ponto foi inicializado
   * na competência atual.
   */
  if (FolhaPagamentoSalario::hasFolha()) {

    /**
     * Verifica se existe uma folha salário
     * aberta na competência atual
     */
    $lExisteSalarioAberta = FolhaPagamentoSalario::hasFolhaAberta(
      new DBCompetencia(DBPessoal::getAnofolha(), DBPessoal::getMesFolha())
    );

    /**
     * Se a folha salário estiver fechada.
     */
    if (!$lExisteSalarioAberta) {

      /**
       * Verifica se existe uma folha suplementar
       * aberta na competência atual.
       */
      $lExisteSuplementarAberta = FolhaPagamentoSuplementar::hasFolhaAberta(
        new DBCompetencia(DBPessoal::getAnofolha(), DBPessoal::getMesFolha())
      );

      if ($lExisteSuplementarAberta) {

        $oFolhaPagamento = FolhaPagamentoSuplementar::getFolhaAberta( );
        
        $rh141_codigo    = $oFolhaPagamento->getNumero();
        $rh141_descricao = $oFolhaPagamento->getDescricao();
        $rh141_anoref    = $oFolhaPagamento->getCompetenciaReferencia()->getAno();
        $rh141_mesref    = $oFolhaPagamento->getCompetenciaReferencia()->getMes();

        /**
         * Desativa o formulário
         */
        $lDisabled = true;
        $db_opcao  = 3;

        throw new BusinessException(_M(MENSAGEM . "folha_suplementar_aberta"));
      } else {

        $lSubmit = isset($_POST['submit']);
        if ($lSubmit) {

          $oPost              = db_utils::postMemory($_POST);
          $oFolhaSuplementar = new FolhaPagamentoSuplementar();

          $oFolhaSuplementar->setNumero(FolhaPagamentoSuplementar::getProximoNumero());
          $oFolhaSuplementar->setDescricao($oPost->rh141_descricao);
          $oFolhaSuplementar->setCompetenciaReferencia(new DBCompetencia($oPost->rh141_anoref, $oPost->rh141_mesref));
          $oFolhaSuplementar->setCompetenciaFolha(new DBCompetencia(DBPessoal::getAnofolha(), DBPessoal::getMesFolha()));
          $oFolhaSuplementar->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit")));
         
          if ($oFolhaSuplementar->salvar()) {
           
            /**
             * Desativa o formulário
             */
            $lDisabled = true;
            $db_opcao  = 3;

            /**
             * Exibe a mensagem de sucesso para usuário
             * e preenche campo Número na tela com o número referente à folha aberta.
             */
            db_msgbox(_M(MENSAGEM . 'incluido_com_sucesso'));
            $rh141_codigo = $oFolhaSuplementar->getNumero();
          }
        } else {

          /**
           * Ativa o formulário
           */
          $lDisabled = false;
          $db_opcao  = 1;
        }
      }
    } else {

      /**
       * Desativa o formulário
       */
      $lDisabled = true;
      $db_opcao  = 3;

      throw new BusinessException(_M(MENSAGEM . "folha_salario_aberta"));
    }
  } else {

    /**
     * Desativa o formulário
     */
    $lDisabled = true;
    $db_opcao  = 3;

    throw new BusinessException(_M(MENSAGEM . "ponto_nao_inicializado"));
  }

  db_fim_transacao(false);

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
        <legend>Abertura da Folha Suplementar</legend>
        <?php require_once('forms/db_frmrhfolhapagamento.php'); ?>
      </fieldset>

     <?php if ($lDisabled): ?>
        <input type="submit" name="submit" value="Processar" disabled />
      <?php else: ?>
        <input type="submit" name="submit" value="Processar" onclick="return validarFormulario()" />
      <?php endif; ?>

    </form>
    <?php db_menu(); ?>

    <script type="text/javascript">

      function validarFormulario() {

        const CAMINHO_MENSAGENS = "recursoshumanos.pessoal.pes4_aberturasuplementar001.";

        if ($F("rh141_descricao").length == 0) {

          alert( _M(CAMINHO_MENSAGENS + "descricao_vazio"));
          return false;
        } 

        if ($F("ano").length == 0) {

          alert( _M(CAMINHO_MENSAGENS + "ano_vazio"));
          return false;
        } 

        if ($F("mes").length == 0) {

          alert( _M(CAMINHO_MENSAGENS + "mes_vazio"));
          return false;
        } 

        return true;
      }

    </script>
  </body>
</html>

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


require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$oDaoExcecaoRubrica = db_utils::getDao("rhempenhofolhaexcecaorubrica");

define("MENSAGENS", "recursoshumanos.pessoal.pes1_rhempenhofolhaexcecaorubrica.");

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "getRubricas":

      $iExcecaoRegra = $oParametros->iExcecaoRegra;
      $iTipoFolha    = $oParametros->iTipoFolha;

      $sSql = $oDaoExcecaoRubrica->sql_query_rubricas($iExcecaoRegra, db_getsession("DB_anousu"), db_getsession("DB_instit"), $iTipoFolha);

      $rsRubricas = $oDaoExcecaoRubrica->sql_record($sSql);

      $oRetorno->aRubricas = array();

      if ($oDaoExcecaoRubrica->numrows > 0) {
        $oRetorno->aRubricas = db_utils::getCollectionByRecord($rsRubricas, false, false, true);
      }

    break;

    case "validaRubricasSelecionadas":

      $sWhereRubricas     = '';
      $aRubricas          = array();
      $iTipoFolha         = $oParametros->iTipoFolha;
      $iExcecaoRegra      = $oParametros->iExcecaoRegra;
      $aRubricas          = $oParametros->aRubricasSelecionadas;

      $sWhere             = ' rh74_tipofolha = ' . $iTipoFolha;

      if( !empty( $iExcecaoRegra ) ){
        $sWhere          .= ' and rh128_sequencial <> ' . $iExcecaoRegra;
      }

      foreach ( $aRubricas as $sRubrica ) {
        $sWhereRubricas  .= " '$sRubrica',";
      }

      $sWhere    .= ' and rh74_rubric IN ( ' . substr( $sWhereRubricas, 0, -1 ) . ' )';
      $sSql       = $oDaoExcecaoRubrica->sql_query( null, 'rh74_rubric', null, $sWhere );
      $rsRubricas = $oDaoExcecaoRubrica->sql_record( $sSql );

      $oRetorno->aRubricasConflitantes = array();

      $oRetorno->lExisteRegra   = false;
      if ( $oDaoExcecaoRubrica->numrows > 0 ) {
        $oRetorno->lExisteRegra = true;

        for ($iLinha = 0; $iLinha < $oDaoExcecaoRubrica->numrows; $iLinha++) {

          $oRubrica = db_utils::fieldsMemory($rsRubricas, $iLinha);
          $oRetorno->aRubricasConflitantes[] = $oRubrica->rh74_rubric;
        }
      }

    break;

    case "getDadosRegra":

      $iExcecaoRegra    = $oParametros->iExcecaoRegra;
      $oDaoExcecaoRegra = db_utils::getDao("rhempenhofolhaexcecaoregra");
      $sSql             = $oDaoExcecaoRegra->sql_query_dados_regra($iExcecaoRegra);
      $rsRegra          = $oDaoExcecaoRegra->sql_record($sSql);

      if ($oDaoExcecaoRegra->numrows == 0) {
        throw new BusinessException(_M(MENSAGENS . "nenhum_registro_encontrado"));
      }

      $oRetorno->oDadoRegra = db_utils::fieldsMemory($rsRegra, 0, false, false, true);

      $aTipoFolha = Array( "0" => "Todos",
                           "1" => "Salário",
                           "2" => "Complementar",
                           "3" => "Recisão",
                           "4" => "13º Salário",
                           "5" => "Adiantamento"
                           );
      $oRetorno->oDadoRegra->sTipoFolha = urlencode($aTipoFolha[$oRetorno->oDadoRegra->rh74_tipofolha]);

    break;

    case "salvar":

      $oDaoExcecaoRubrica = db_utils::getDao("rhempenhofolhaexcecaorubrica");
      $oDaoExcecaoRegra   = db_utils::getDao("rhempenhofolhaexcecaoregra");
      $oDaoConCarPeculiar = db_utils::getDao("concarpeculiar");
      $iAnoUso            = db_getsession('DB_anousu');

      db_inicio_transacao();

      /**
       * Valida Caracteristica Peculiar
       */
      $sSqlConCarPeculiar = $oDaoConCarPeculiar->sql_query_file( null, "*", null, "c58_sequencial = '" . $oParametros->iCaracteristica . "' and (c58_tipo = 1 or c58_tipo = 3)" );
      $rsConCarPeculiar   = $oDaoConCarPeculiar->sql_record( $sSqlConCarPeculiar );

      if ($oDaoConCarPeculiar->numrows == 0) {
        throw new BusinessException(_M(MENSAGENS . 'concarpeculiar_invalida'));
      }

      $oDaoExcecaoRegra->rh128_sequencial = $oParametros->iSequencial;
      $oDaoExcecaoRegra->rh128_descricao  = $oParametros->sDescricao;

      /**
       * Verifica se deve criar ou alterar uma exceção regra
       */
      if (!empty($oParametros->iSequencial)) {
        $oDaoExcecaoRegra->alterar( $oParametros->iSequencial );
      } else {
        $oDaoExcecaoRegra->incluir( null );
      }

      if (!empty($oParametros->aRubricas)) {

        /**
         * Exclui as exceções para as rubricas que foram desmarcadas na tela
         */
        $oDaoExcecaoRubrica->excluir( null,
                                      "rh74_rubric NOT IN ('" . implode("', '", $oParametros->aRubricas)
                                      . "') and rh74_rhempenhofolhaexcecaoregra = {$oDaoExcecaoRegra->rh128_sequencial}" );

        /**
         * Array que irá conter as exceções que devem ser alteradas
         */
        $aExcecaoRubrica = array();

        /**
         * Busca as Exceções que devem ser alteradas
         */
        $sSql = $oDaoExcecaoRubrica->sql_query_file( null,
                                                     "rh74_sequencial, rh74_rubric",
                                                     null,
                                                     "rh74_rubric IN ('" . implode("', '", $oParametros->aRubricas)
                                                     . "') and rh74_rhempenhofolhaexcecaoregra = {$oDaoExcecaoRegra->rh128_sequencial} and rh74_anousu = $iAnoUso" );
        $rsExcecaoRubrica = $oDaoExcecaoRubrica->sql_record( $sSql );

        if ($oDaoExcecaoRubrica->numrows > 0) {
          for ($iLinha = 0; $iLinha < $oDaoExcecaoRubrica->numrows; $iLinha++) {
            $oExcecao = db_utils::fieldsMemory($rsExcecaoRubrica, $iLinha);

            $aExcecaoRubrica[$oExcecao->rh74_rubric] = $oExcecao->rh74_sequencial;
          }
        }

        /**
         * Seta a nova configuração
         */
        $oDaoExcecaoRubrica->rh74_instit                     = db_getsession("DB_instit");
        $oDaoExcecaoRubrica->rh74_anousu                     = db_getsession("DB_anousu");
        $oDaoExcecaoRubrica->rh74_orgao                      = $oParametros->iOrgao;
        $oDaoExcecaoRubrica->rh74_unidade                    = $oParametros->iUnidade;
        $oDaoExcecaoRubrica->rh74_projativ                   = $oParametros->iProjetoAtividade;
        $oDaoExcecaoRubrica->rh74_recurso                    = $oParametros->iRecurso;
        $oDaoExcecaoRubrica->rh74_programa                   = $oParametros->iPrograma;
        $oDaoExcecaoRubrica->rh74_funcao                     = $oParametros->iFuncao;
        $oDaoExcecaoRubrica->rh74_subfuncao                  = $oParametros->iSubFuncao;
        $oDaoExcecaoRubrica->rh74_concarpeculiar             = $oParametros->iCaracteristica;
        $oDaoExcecaoRubrica->rh74_codele                     = $oParametros->iDesdobramento;
        $oDaoExcecaoRubrica->rh74_tipofolha                  = $oParametros->iTipoFolha;
        $oDaoExcecaoRubrica->rh74_rhempenhofolhaexcecaoregra = $oDaoExcecaoRegra->rh128_sequencial;

        /**
         * Salva ou altera as exceções para cada rubrica que foi enviada
         */
        foreach ($oParametros->aRubricas as $sRubrica) {
          $oDaoExcecaoRubrica->rh74_sequencial = null;
          $oDaoExcecaoRubrica->rh74_rubric     = $sRubrica;

          if (!empty($oParametros->iSequencial) && isset($aExcecaoRubrica[$sRubrica])) {
            $oDaoExcecaoRubrica->rh74_sequencial = $aExcecaoRubrica[$sRubrica];

            $oDaoExcecaoRubrica->alterar( $aExcecaoRubrica[$sRubrica] );
          } else {
            $oDaoExcecaoRubrica->incluir( null );
          }
        }

      }

      db_fim_transacao();

      $oRetorno->sMessage = urlencode(_M(MENSAGENS . "excecao_salva"));

      break;

    case "excluir":

      db_inicio_transacao();

      $oDaoExcecaoRubrica = db_utils::getDao("rhempenhofolhaexcecaorubrica");
      $oDaoExcecaoRegra   = db_utils::getDao("rhempenhofolhaexcecaoregra");
      $oDaoExcecaoRubrica->excluir(null, "rh74_rhempenhofolhaexcecaoregra = $oParametros->iSequencial");
      
      if($oDaoExcecaoRubrica->erro_status == "0"){
        throw new DBException(_M(MENSAGENS . "erro_excluir_rubricas"));
      }

      $oDaoExcecaoRubrica->excluir($oParametros->iSequencial);

      if($oDaoExcecaoRubrica->erro_status == "0"){
        throw new DBException(_M(MENSAGENS . "erro_excluir_regra"));
      }

      db_fim_transacao();

      $oRetorno->sMessage = urlencode(_M(MENSAGENS . "regra_excluida"));

    break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
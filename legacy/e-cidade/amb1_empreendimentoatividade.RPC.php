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
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

define("MENSAGENS", "tributario.meioambiente.amb1_empreendimentos.");

$oDaoAtividadeImpacto               = db_utils::getDao("atividadeimpacto");
$oDaoAtividadeImpactoPorte          = db_utils::getDao("atividadeimpactoporte");
$oDaoEmpreendimentoAtividadeImpacto = db_utils::getDao("empreendimentoatividadeimpacto");

try {

  switch ($oParametros->sExecucao) {

    case "getAtividadeImpacto":

      if( empty( $oParametros->iCodigoAtividade ) ){
        throw new BusinessException( _M( MENSAGENS . 'codigo_atividade_obrigatorio' ) );
      }

      $sSqlAtividadeImpacto = $oDaoAtividadeImpacto->getAtividadeImpactoPorteCriterio( $oParametros->iCodigoAtividade );
      $rsAtividadeImpacto   = $oDaoAtividadeImpacto->sql_record( $sSqlAtividadeImpacto );

      if( !$rsAtividadeImpacto  && !empty($oDaoAtividadeImpacto->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_atividadeimpacto' ) );
      }

      $oAtividadeImpacto           = db_utils::getCollectionByRecord($rsAtividadeImpacto, true, false, true);
      $oRetorno->oAtividadeImpacto = $oAtividadeImpacto;
    break;

    case "getEmpreendimentoAtividadeImpacto":

      if( empty( $oParametros->iCodigoEmpreendimentoAtividade ) ){
        throw new BusinessException( _M( MENSAGENS . 'codigo_atividade_obrigatorio' ) );
      }

      $sSqlEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_query( $oParametros->iCodigoEmpreendimentoAtividade );
      $rsEmpreendimentoAtividadeImpacto   = $oDaoEmpreendimentoAtividadeImpacto->sql_record( $sSqlEmpreendimentoAtividadeImpacto );

      if( !$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_empreendimentoatividadeimpacto' ) );
      }

      /**
       * Populamos os portes vinculados a esta atividade
       */
      $iCodigoAtividadeImpactoPorte = db_utils::fieldsMemory($rsEmpreendimentoAtividadeImpacto,0)->am04_atividadeimpacto;

      $sWhere  = "am04_atividadeimpacto      = {$iCodigoAtividadeImpactoPorte}";
      $sSqlAtividadeImpactoPorte = $oDaoAtividadeImpactoPorte->sql_query( null, 'am02_sequencial, am02_descricao', null, $sWhere );
      $rsAtividadeImpactoPorte   = $oDaoAtividadeImpactoPorte->sql_record( $sSqlAtividadeImpactoPorte );
      if(!$rsAtividadeImpactoPorte && !empty($oDaoAtividadeImpactoPorte->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_atividadeimpactoporte' ) );
      }
      $oPorteAtividades = db_utils::getCollectionByRecord($rsAtividadeImpactoPorte, true, false, true);

      $oEmpreendimentoAtividadeImpacto           = db_utils::getCollectionByRecord($rsEmpreendimentoAtividadeImpacto, true, false, true);
      $oRetorno->oEmpreendimentoAtividadeImpacto = $oEmpreendimentoAtividadeImpacto;
      $oRetorno->oPorteAtividades                = $oPorteAtividades;
    break;

    case "getAtividadeEmpreendimento":

      if( empty( $oParametros->iCodigoEmpreendimento ) ){
        throw new BusinessException( _M( MENSAGENS . 'codigo_empreendimento_obrigatorio' ) );
      }

      $sWhere = " am06_empreendimento = {$oParametros->iCodigoEmpreendimento} ";
      $sSqlEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_query( null,"*", null, $sWhere );
      $rsEmpreendimentoAtividadeImpacto   = $oDaoEmpreendimentoAtividadeImpacto->sql_record( $sSqlEmpreendimentoAtividadeImpacto );

      if( !$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_empreendimentoatividadeimpacto' ) );
      }

      $oEmpreendimentoAtividadeImpacto           = db_utils::getCollectionByRecord($rsEmpreendimentoAtividadeImpacto, true, false, true);
      $oRetorno->oEmpreendimentoAtividadeImpacto = $oEmpreendimentoAtividadeImpacto;
    break;

    case "setAtividadeEmpreendimento":

      /**
       * Validamos se já existe alguma atividade principal cadastrada (só pode haver uma)
       */
      if($oParametros->lIsPrincipal == 1){

        $sWhere  = "     am06_empreendimento   = {$oParametros->iCodigoEmpreendimento} ";
        $sWhere .= " and am06_principal is true                                        ";
        $sSqlEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_query( null, 'am06_sequencial', null, $sWhere );
        $rsEmpreendimentoAtividadeImpacto   = $oDaoEmpreendimentoAtividadeImpacto->sql_record( $sSqlEmpreendimentoAtividadeImpacto );
        if(!$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){
          throw new BusinessException( _M( MENSAGENS . 'erro_consulta_empreendimentoatividadeimpacto' ) );
        }
        if($oDaoEmpreendimentoAtividadeImpacto->numrows <> 0){
          throw new BusinessException( _M( MENSAGENS . 'atividade_principal_existe_empreendimentoatividadeimpacto' ) );
        }
      }

      /**
       * Validamos se existe uma mesma atividade já lançada para o empreendimento
       */
      $sWhere  = "     am06_atividadeimpacto = {$oParametros->iCodigoAtividade}      ";
      $sWhere .= " and am06_empreendimento   = {$oParametros->iCodigoEmpreendimento} ";
      $sSqlEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_query( null, 'am06_sequencial', null, $sWhere );
      $rsEmpreendimentoAtividadeImpacto   = $oDaoEmpreendimentoAtividadeImpacto->sql_record( $sSqlEmpreendimentoAtividadeImpacto );
      if(!$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_empreendimentoatividadeimpacto' ) );
      }
      if($oDaoEmpreendimentoAtividadeImpacto->numrows <> 0){
        throw new BusinessException( _M( MENSAGENS . 'atividade_duplicada_empreendimentoatividadeimpacto' ) );
      }

      /**
       * Buscamos na tabela de vinculo a chave da atividade com o Porte
       */
      $sWhere  = "     am04_atividadeimpacto      = {$oParametros->iCodigoAtividade} ";
      $sWhere .= " and am04_porteatividadeimpacto = {$oParametros->iCodigoPorte}     ";
      $sSqlAtividadeImpactoPorte = $oDaoAtividadeImpactoPorte->sql_query( null, 'am04_sequencial', null, $sWhere );
      $rsAtividadeImpactoPorte   = $oDaoAtividadeImpactoPorte->sql_record( $sSqlAtividadeImpactoPorte );
      if(!$rsAtividadeImpactoPorte && !empty($oDaoAtividadeImpactoPorte->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_atividadeimpactoporte' ) );
      }
      $iCodigoAtividadeImpactoPorte = db_utils::fieldsMemory($rsAtividadeImpactoPorte,0)->am04_sequencial;

      /**
       * Incluimos a atividade para o empreendimento
       */
      db_inicio_transacao();

      $oDaoEmpreendimentoAtividadeImpacto->am06_atividadeimpacto      = $oParametros->iCodigoAtividade;
      $oDaoEmpreendimentoAtividadeImpacto->am06_empreendimento        = $oParametros->iCodigoEmpreendimento;
      $oDaoEmpreendimentoAtividadeImpacto->am06_atividadeimpactoporte = $iCodigoAtividadeImpactoPorte;

      $oDaoEmpreendimentoAtividadeImpacto->am06_principal   = 'false';
      if( $oParametros->lIsPrincipal == 1 ){
        $oDaoEmpreendimentoAtividadeImpacto->am06_principal = 'true';
      }

      $oDaoEmpreendimentoAtividadeImpacto->incluir(null);
      $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_cadastrar_empreendimentoatividadeimpacto' ) );

      if ($oDaoEmpreendimentoAtividadeImpacto->erro_status == "0") {

        db_fim_transacao(true);
        throw new BusinessException( _M( MENSAGENS . 'erro_incluir_empreendimentoatividadeimpacto' ) );
      }

      db_fim_transacao(false);
    break;

    case "alteraAtividadeEmpreendimento":

      /**
       * Só pode ser alterado o porte e o tipo de atividade (principal ou secundaria)
       * Quando for primaria nao pode ser alterado para secundaria
       * Quando for secundaria e for alterado para primaria todas as outras atividades serao atualizadas para secundarias
       */

      /**
       * Devemos alterar o tipo da atividade para principal e setar o restante para secundaria
       */
      db_inicio_transacao();

      /**
       * Alteramos o porte da mesma indiferente do tipo
       * Buscamos na tabela de vinculo a chave da atividade com o Porte
       */
      $sWhere  = "     am04_atividadeimpacto      = {$oParametros->iCodigoAtividade} ";
      $sWhere .= " and am04_porteatividadeimpacto = {$oParametros->iCodigoPorte}     ";
      $sSqlAtividadeImpactoPorte = $oDaoAtividadeImpactoPorte->sql_query( null, 'am04_sequencial', null, $sWhere );
      $rsAtividadeImpactoPorte   = $oDaoAtividadeImpactoPorte->sql_record( $sSqlAtividadeImpactoPorte );
      if(!$rsAtividadeImpactoPorte && !empty($oDaoAtividadeImpactoPorte->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_atividadeimpactoporte' ) );
      }
      $iCodigoAtividadeImpactoPorte = db_utils::fieldsMemory($rsAtividadeImpactoPorte,0)->am04_sequencial;

      $oDaoEmpreendimentoAtividadeImpacto->am06_sequencial             = $oParametros->iCodigoEmpreendimentoAtividade;
      $oDaoEmpreendimentoAtividadeImpacto->am06_atividadeimpactoporte  = $iCodigoAtividadeImpactoPorte;
      $oDaoEmpreendimentoAtividadeImpacto->alterar( $oDaoEmpreendimentoAtividadeImpacto->am06_sequencial );
      if($oDaoEmpreendimentoAtividadeImpacto->erro_banco == '0' ){
        throw new BusinessException( _M( MENSAGENS . 'erro_alterar_atividadeimpacto' ) );
      }

      if($oParametros->AtualizaTipoSecundaria){

        $sSql  = "update empreendimentoatividadeimpacto                              ";
        $sSql .= "   set am06_principal = false                                      ";
        $sSql .= " where am06_empreendimento = {$oParametros->iCodigoEmpreendimento} ";
        $rsEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_record($sSql);
        if(!$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){

          db_fim_transacao(true);
          throw new BusinessException( _M( MENSAGENS . 'erro_atualizar_atividadeimpactoporte' ) );
        }

        $oDaoEmpreendimentoAtividadeImpacto->am06_sequencial = $oParametros->iCodigoEmpreendimentoAtividade;
        $oDaoEmpreendimentoAtividadeImpacto->am06_principal  = 'true';
        $oDaoEmpreendimentoAtividadeImpacto->alterar( $oDaoEmpreendimentoAtividadeImpacto->am06_sequencial );
        if($oDaoEmpreendimentoAtividadeImpacto->erro_banco == '0' ){
          throw new BusinessException( _M( MENSAGENS . 'erro_alterar_atividadeimpacto' ) );
        }
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_alterar_atividadeimpacto' ) );

      db_fim_transacao(false);
    break;

    case "excluiAtividadeEmpreendimento":

      /**
       * Não deve excluir se for primária
       * Não deve excluir todas as atividades
       * A ultima deve ser alterada para primaria
       * Não pode ficar sem principal
       */
      if( empty( $oParametros->iCodigoEmpreendimentoAtividade ) ){
        throw new BusinessException( _M( MENSAGENS . 'codigo_atividade_obrigatorio' ) );
      }

      /**
       * Buscamos todas as atividades vinculadas ao empreendimento
       */
      $sWhere = " am06_empreendimento   = {$oParametros->iCodigoEmpreendimento} ";
      $sSqlEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_query_file( null, '*', null, $sWhere );
      $rsEmpreendimentoAtividadeImpacto   = $oDaoEmpreendimentoAtividadeImpacto->sql_record($sSqlEmpreendimentoAtividadeImpacto);
      if(!$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){
        throw new BusinessException( _M( MENSAGENS . 'erro_consulta_atividadeimpactoporte' ) );
      }

      $aAtividades      = db_utils::getCollectionByRecord($rsEmpreendimentoAtividadeImpacto, true);
      $iTotalAtividades = count($aAtividades);
      /**
       * Validamos se existe pelo menos uma atividade, esta nao deve ser excluida
       */
      if( $iTotalAtividades == 1 ){
        throw new BusinessException( _M( MENSAGENS . 'erro_exclusao_minimodeatividades' ) );
      }

      foreach ($aAtividades as $aAtividade) {

        /**
         * Validamos se é atividade primária, esta não deve ser excluida
         */
        if( $aAtividade->am06_sequencial == $oParametros->iCodigoEmpreendimentoAtividade ){

          if( $aAtividade->am06_principal == 't' ){
            throw new BusinessException( _M( MENSAGENS . 'erro_exclusao_atividadeprincipal' ) );
          }
        }
      }

      /**
       * Excluimos a atividade
       */
      db_inicio_transacao();

      $oDaoEmpreendimentoAtividadeImpacto->excluir($oParametros->iCodigoEmpreendimentoAtividade);
      if( $oDaoEmpreendimentoAtividadeImpacto->erro_banco == '0' ){

        db_fim_transacao(true);
        throw new BusinessException( _M( MENSAGENS . 'erro_excluir_atividadeimpacto' ) );
      }
      $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_excluir_atividadeimpacto' ) );

      /**
       * Validamos se restou apenas uma atividade estava deve ser setada como principal
       * @todo rever esta logica
       */
      $iTotalAtividades--;
      if( $iTotalAtividades == 1 ){

        $sSql  = "update empreendimentoatividadeimpacto                              ";
        $sSql .= "   set am06_principal = true                                       ";
        $sSql .= " where am06_empreendimento = {$oParametros->iCodigoEmpreendimento} ";
        $rsEmpreendimentoAtividadeImpacto = $oDaoEmpreendimentoAtividadeImpacto->sql_record($sSql);
        if(!$rsEmpreendimentoAtividadeImpacto && !empty($oDaoEmpreendimentoAtividadeImpacto->erro_banco) ){

          db_fim_transacao(true);
          throw new BusinessException( _M( MENSAGENS . 'erro_atualizar_atividadeimpactoporte' ) );
        }
      }

      db_fim_transacao(false);
    break;
  }

} catch (Exception $eErro){

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
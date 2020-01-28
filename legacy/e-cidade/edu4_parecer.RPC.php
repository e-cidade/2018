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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

define ("MENSAGEM_PARECER_RPC", "educacao.escola.edu4_parecer_RPC.");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oDaoParecer           = db_utils::getDao('parecer');
$oDaoParecerTurma      = db_utils::getDao('parecerturma');
$oDaoParecerDisciplina = db_utils::getDao('parecerdisciplina');

$oRetorno         = new stdClass();
$oRetorno->dados  = array();
$oRetorno->status = 1;

switch ($oParam->exec) {

  case 'Salvar' :

    /**
     * Verificamos se veio valor no campo sequencia. (que é a sequencia do parecer para uma escola)
     * Se não veio, buscamos o ultimo valor do campo para a escola atual.
     */
    $iSequencia = $oParam->iSequencia;
    if (isset($oParam->iSequencia) && empty($oParam->iSequencia)) {

      $sCampos = " coalesce(max(ed92_i_sequencial), 0) + 1  as sequencia";
      $sWhere  = " ed92_i_escola = ".db_getsession("DB_coddepto");

      $sSqlBuscaSequencia = $oDaoParecer->sql_query_file(null, $sCampos, "", $sWhere);
      $rsBuscaSequencia   = $oDaoParecer->sql_record($sSqlBuscaSequencia);

      $iSequencia = db_utils::fieldsMemory($rsBuscaSequencia, 0)->sequencia;
    }

    $oDaoParecer->ed92_i_codigo     = null;
    $oDaoParecer->ed92_c_descr      = db_stdClass::normalizeStringJson($oParam->sDescricao);
    $oDaoParecer->ed92_i_escola     = db_getsession("DB_coddepto");
    $oDaoParecer->ed92_i_sequencial = $iSequencia;

    $aListaDisciplina      = explode(",", $oParam->sListaDisciplina);
    $aListaPeriodos        = explode(",", $oParam->sListaPeriodos);
    $iCountListaDisciplina = !empty($oParam->sListaDisciplina)?count($aListaDisciplina):0;
    $iCountListaPeriodo    = !empty($oParam->sListaPeriodos)?count($aListaPeriodos):0;

    db_inicio_transacao();
    try {
      switch ($oParam->opcao) {

        case 1:

          $oDaoParecer->incluir(null);
          break;
        case 2:

          $oDaoParecer->ed92_i_codigo     = $oParam->iCodigo;
          $oDaoParecer->alterar($oParam->iCodigo);
          break;
        case 3:

          $oDaoParecer->ed92_i_codigo     = $oParam->iCodigo;
          $oDaoParecer->excluir($oParam->iCodigo);
          break;
      }

      if ($oDaoParecer->erro_status == 0) {

        $sMsgErro  = "Não foi possível salvar os dados do paracer.";
        $sMsgErro .= str_replace("\\n", "\n", $oDaoParecer->erro_msg);
        throw new BusinessException($sMsgErro);
      }

      $sWhereParecerDisciplina = "ed106_parecer = {$oDaoParecer->ed92_i_codigo}";
      $oDaoParecerDisciplina->excluir(null, $sWhereParecerDisciplina);
      if ($oDaoParecerDisciplina->erro_status == 0) {

        $sErro  = "Erro ao remover vínculo do parecer com a disciplina.\n";
        $sErro .= str_replace("\\n", "\n", $oDaoParecerDisciplina->erro_msg);
        throw new BusinessException($sErro);
      }

      for ($i = 0; $i < $iCountListaDisciplina; $i++) {

        $oDaoParecerDisciplina->ed106_sequencial = null;
        $oDaoParecerDisciplina->ed106_parecer    = $oDaoParecer->ed92_i_codigo;
        $oDaoParecerDisciplina->ed106_disciplina = $aListaDisciplina[$i];
        $oDaoParecerDisciplina->incluir(null);

        if ($oDaoParecerDisciplina->erro_status == 0) {

          $sErro  = "Erro ao incluir vinculo do Parecer com a disciplina.\n";
          $sErro .= str_replace("\\n", "\n", $oDaoParecerDisciplina->erro_msg);
          throw new BusinessException($sErro);
        }
      }

      $oDaoParecerPeriodo   = db_utils::getDao("parecerperiodo");
      $sWhereParecerPeriodo = "ed120_parecer = {$oDaoParecer->ed92_i_codigo}";
      $oDaoParecerPeriodo->excluir(null, $sWhereParecerPeriodo);
      if ($oDaoParecerPeriodo->erro_status == 0) {

        $sErro  = "Erro ao remover vínculo do parecer com o período.\n";
        $sErro .= str_replace("\\n", "\n", $oDaoParecerPeriodo->erro_msg);
        throw new BusinessException($sErro);
      }
      for ($i = 0; $i < $iCountListaPeriodo; $i++) {

        $oDaoParecerPeriodo->ed120_sequencial       = null;
        $oDaoParecerPeriodo->ed120_parecer          = $oDaoParecer->ed92_i_codigo;
        $oDaoParecerPeriodo->ed120_periodoavaliacao = $aListaPeriodos[$i];
        $oDaoParecerPeriodo->incluir(null);

        if ($oDaoParecerPeriodo->erro_status == 0) {

          $sErro  = "Erro ao incluir vínculo do Parecer com o periodo de avaliação.\n";
          $sErro .= str_replace("\\n", "\n", $oDaoParecerPeriodo->erro_msg);
          throw new BusinessException($sErro);
        }
      }


      $oRetorno->iCodigo    = $oDaoParecer->ed92_i_codigo;
      $oRetorno->iSequencia = $oDaoParecer->ed92_i_sequencial;
      $oRetorno->message    = urlencode("Dados salvo com sucesso");
      db_fim_transacao();
    } catch (BusinessException $oErro) {

      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode($oErro->getMessage());
      db_fim_transacao(true);
    }

    break;

  case 'validaDisciplinaTemTurmaVinculada':

    $oRetorno->iDisciplina = $oParam->iDisciplina;

    if (!empty($oParam->iParecer)) {

      $sWhere      = "     ed92_i_codigo    = {$oParam->iParecer}";
      $sWhere     .= " and ed106_disciplina = {$oParam->iDisciplina}";

      $sSqlParecer   = $oDaoParecer->sql_query_turma_disciplina(null, " * ", null, $sWhere);
      $rsParecer     = $oDaoParecer->sql_record($sSqlParecer);
      $iCountParecer = $oDaoParecer->numrows;
      if ($rsParecer && $iCountParecer > 0) {

        $sErro             = "Você não pode desvincular a disciplina pois ela esta vinculada a uma turma.";
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($sErro);

      }
    }
    break;

  case 'buscarParecer':

    $sSqlParecer = $oDaoParecer->sql_query_file($oParam->iCodigo);
    $rsParecer   = $oDaoParecer->sql_record($sSqlParecer);

    if ($rsParecer) {

      $oDados = db_utils::fieldsMemory($rsParecer, 0);
      $oRetorno->iCodigo    = $oDados->ed92_i_codigo;
      $oRetorno->sDescricao = urlencode($oDados->ed92_c_descr);
      $oRetorno->iSequencia = $oDados->ed92_i_sequencial;

      $sCampos = " ed106_disciplina as codigo_disciplina, trim(ed232_c_descr||' ['||ed10_c_abrev||']') as disciplina ";
      $sWhere  = " ed106_parecer = {$oParam->iCodigo}";

      $sSqlParecerDisciplina = $oDaoParecerDisciplina->sql_query(null, $sCampos, null, $sWhere);
      $rsParecerDisciplina   = $oDaoParecerDisciplina->sql_record($sSqlParecerDisciplina);

      if ($rsParecerDisciplina) {

        $aDadosDisciplina = db_utils::getCollectionByRecord($rsParecerDisciplina, false, false, true);
        foreach ($aDadosDisciplina as $oDisciplina) {

          $oRetorno->dados[] = $oDisciplina;
        }
      }

      $oDaoParecerPeriodo    = db_utils::getDao("parecerperiodo");
      $sCamposParecerPeriodo = "ed09_i_codigo, ed09_c_descr ||' ['||ed09_c_abrev||']' as periodo";
      $sWhereParecerPeriodo  = "ed120_parecer = {$oParam->iCodigo}";
      $sSqlParecerPeriodo    = $oDaoParecerPeriodo->sql_query(null, $sCamposParecerPeriodo,
                                                                   null, $sWhereParecerPeriodo );
      $rsParecerPeriodo      = $oDaoParecerPeriodo->sql_record($sSqlParecerPeriodo);

      $oRetorno->aPeriodos = array();
      if ($rsParecerPeriodo) {

        $aDadosPeriodos = db_utils::getCollectionByRecord($rsParecerPeriodo, false, false, true);

        foreach ($aDadosPeriodos as $oPeriodo) {
          $oRetorno->aPeriodos[] = $oPeriodo;
        }

      }


      $sWhereParecerTurma  = "ed105_i_parecer = {$oParam->iCodigo}";
      $sSqlParecerTurma    = $oDaoParecerTurma->sql_query_file('','ed105_i_turma','', $sWhereParecerTurma);
      $rsParecerTurma      = pg_query( $sSqlParecerTurma );
      $iLinhasParecerTurma = pg_num_rows( $rsParecerTurma );
      $oRetorno->aTurmasVinculadas = array();

      if ( $iLinhasParecerTurma > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhasParecerTurma; $iContador++ ) {
          $oRetorno->aTurmasVinculadas[] = db_utils::fieldsMemory($rsParecerTurma, $iContador)->ed105_i_turma;
        }
      }

    }

    break;
  case 'excluirParecer':

    db_inicio_transacao();
    try {
      $sWhereParecerTurma = " ed105_i_parecer = {$oParam->iCodigo}";
      $oDaoParecerTurma->excluir(null, $sWhereParecerTurma);
      if ($oDaoParecerTurma->erro_status == 0) {

        $sMsgErro  = "Erro ao excluir vinculo do parecer com a Turma. ";
        $sMsgErro .= str_replace("\n", "\\n", $oDaoParecerTurma->erro_msg);
        throw new BusinessException($sMsgErro);
      }

      $sWhereParecerDisciplina = "ed106_parecer = {$oParam->iCodigo}";
      $oDaoParecerDisciplina->excluir(null, $sWhereParecerDisciplina);

      if ($oDaoParecerDisciplina->erro_status == 0) {

        $sMsgErro  = "Erro ao excluir vinculo do parecer com a Disciplina. ";
        $sMsgErro .= str_replace("\n", "\\n", $oDaoParecerDisciplina->erro_msg);
        throw new BusinessException($sMsgErro);
      }

      $oDaoParecerPeriodo   = db_utils::getDao("parecerperiodo");
      $sWhereParecerPeriodo = "ed120_parecer = {$oParam->iCodigo}";
      $oDaoParecerPeriodo->excluir(null, $sWhereParecerPeriodo);
      if ($oDaoParecerPeriodo->erro_status == "0") {

        $sMsgErro  = "Erro ao excluir vinculo do parecer com o Período. ";
        $sMsgErro .= str_replace("\n", "\\n", $oDaoParecerPeriodo->erro_msg);
        throw new BusinessException($sMsgErro);
      }

      $oDaoParecer->ed92_i_codigo = $oParam->iCodigo;
      $oDaoParecer->excluir($oParam->iCodigo);

      if ($oDaoParecer->erro_status == 0) {

        $sMsgErro  = "Erro ao excluir cadastro parecer";
        $sMsgErro .= str_replace("\n", "\\n", $oDaoParecer->erro_msg);
        throw new BusinessException($sMsgErro);
      }

      $oRetorno->message  = urlencode("Parecer excluído com sucesso.");
      db_fim_transacao();
    } catch (BusinessException $oErro) {

      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode($oErro->getMessage());
      db_fim_transacao(true);
    }
    break;

  /**
   * Salva vínculo do parecer com as turmas selecionadas
   * @param array   $oParam->aTurmas
   * @param integer $oParam->iCodigo
   */
  case 'vincularTurma' :

    if ( isset( $oParam->aTurmas ) && isset( $oParam->iCodigo ) ) {

      db_inicio_transacao();

      try{

        $sWhereParecerTurma = " ed105_i_parecer = {$oParam->iCodigo} ";
        $oDaoParecerTurma->excluir(null, $sWhereParecerTurma);

        if ($oDaoParecerTurma->erro_status == 0) {

          $oParametros = new stdClass();
          $oParametros->sErro = str_replace("\\n", "\n", $oDaoParecerTurma->erro_msg);

          $sErro  = _M( MENSAGEM_PARECER_RPC."erro_excluir_vinculo_turma_parecer", $oParametros );
          throw new BusinessException($sErro);
        }

        $aTurmasInclusas = array();
        foreach ( $oParam->aTurmas as $iTurma ) {

          if ( in_array($iTurma, $aTurmasInclusas) ) {
            continue;
          }

          $aTurmasInclusas[]                 = $iTurma;
          $oDaoParecerTurma->ed105_i_codigo  = null;
          $oDaoParecerTurma->ed105_i_turma   = $iTurma;
          $oDaoParecerTurma->ed105_i_parecer = $oParam->iCodigo;
          $oDaoParecerTurma->incluir(null);

          if ($oDaoParecerTurma->erro_status == 0) {

            $oParametros = new stdClass();
            $oParametros->sErro = str_replace("\\n", "\n", $oDaoParecerTurma->erro_msg);

            $sErro  = _M( MENSAGEM_PARECER_RPC."erro_vincular_turma_parecer", $oParametros );
            throw new BusinessException($sErro);
          }
        }

        $oRetorno->message  = urlencode( _M(MENSAGEM_PARECER_RPC."turma_vinculada_sucesso") );
        db_fim_transacao();
      } catch (BusinessException $oErro) {

        $oRetorno->status   = 2;
        $oRetorno->message  = urlencode($oErro->getMessage());
        db_fim_transacao(true);
      }

    }
}

echo $oJson->encode($oRetorno);
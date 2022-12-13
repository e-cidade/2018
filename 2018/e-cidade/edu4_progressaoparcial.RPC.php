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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

/**
 * Constante definida com o caminho onde se encontram as mensagens do RPC
 */
define( "CAMINHO_MENSAGENS", "educacao.escola.edu4_progressaoparcial." );

try {

  switch( $oParam->exec ) {

    /**
     * Retorna as progressões de um aluno
     *
     * @param integer $oParam->iAluno - Código do aluno
     *
     * - Parâmetros Opcionais
     * @param boolean $oParam->lEscolaSessao - Retorna somente progressões da escola da sessão
     * @param boolean $oParam->lInativos     - Retorna progressões inativas
     *
     * @return array aProgressoes[]
     *         ..... stdClass
     *         .............. integer iCodigo       - Código da progressão
     *         .............. integer iEtapa        - Código da etapa da progressão
     *         .............. string  sEtapa        - Nome da etapa da progressão
     *         .............. integer iDisciplina   - Código da disciplina da progressão
     *         .............. string  sDisciplina   - Nome da disciplina da progressão
     *         .............. integer iAno          - Ano da progressão
     *         .............. integer iEscola       - Código da escola da progressão
     *         .............. string  sEscola       - Nome da escola da progressão
     *         .............. string  sSituacao     - Situação da progressão
     *         .............. integer iEnsino       - Código do ensino da etapa da progressão
     *         .............. string  sEnsino       - Nome do ensino da etapa da progressão
     *         .............. bool    lManual       - Se foi de inclusão manual
     *         .............. bool    lTemMatricula - Se já teve/tem matricula
     */
  	case 'buscaDadosProgressaoAluno':

  	  if ( !isset( $oParam->iAluno ) || empty( $oParam->iAluno ) ) {
  	    throw new ParameterException( _M( CAMINHO_MENSAGENS."aluno_nao_informado" ) );
  	  }

  	  $oRetorno->aProgressoes = array();
  	  $oAluno                 = AlunoRepository::getAlunoByCodigo( $oParam->iAluno );

  	  if (count( $oAluno->getProgressaoParcial(true) ) > 0 ) {

  	    /**
  	     * Percorre as progressoes do aluno para armazenar em um stdClass e incrementando o array
  	     */
  	    foreach ( $oAluno->getProgressaoParcial(true) as $oProgressaoParcial ) {

  	      if ( $oProgressaoParcial->isConcluida() && !isset($oParam->lBuscaTodas)) {
  	        continue;
  	      }

  	      if (    !$oProgressaoParcial->getSituacaoProgressao()->isAtivo()
  	           && ( !isset( $oParam->lInativos ) || !$oParam->lInativos ) ) {
  	        continue;
  	      }

  	      /**
  	       * Caso tenha sido setado parâmetro para validar a escola da sessão e a progressão seja de uma escola
  	       * diferente, não retorna a progressão
  	       */
  	      if (    isset( $oParam->lEscolaSessao )
  	           && $oParam->lEscolaSessao
  	           && $oProgressaoParcial->getEscola()->getCodigo() != $iEscola ) {
  	        continue;
  	      }

  	      $oDadosProgressao                = new stdClass();
  	      $oDadosProgressao->iCodigo       = $oProgressaoParcial->getCodigoProgressaoParcial();
  	      $oDadosProgressao->iEtapa        = $oProgressaoParcial->getEtapa()->getCodigo();
  	      $oDadosProgressao->sEtapa        = urlencode( $oProgressaoParcial->getEtapa()->getNome() );
  	      $oDadosProgressao->iDisciplina   = $oProgressaoParcial->getDisciplina()->getCodigoDisciplina();
  	      $oDadosProgressao->sDisciplina   = urlencode( $oProgressaoParcial->getDisciplina()->getNomeDisciplina() );
  	      $oDadosProgressao->iAno          = $oProgressaoParcial->getAno();
  	      $oDadosProgressao->iEscola       = $oProgressaoParcial->getEscola()->getCodigo();
  	      $oDadosProgressao->sEscola       = urlencode( $oProgressaoParcial->getEscola()->getNome() );
  	      $oDadosProgressao->iEnsino       = $oProgressaoParcial->getEtapa()->getEnsino()->getCodigo();
  	      $oDadosProgressao->sEnsino       = urlencode( $oProgressaoParcial->getEtapa()->getEnsino()->getNome() );
  	      $oDadosProgressao->sSituacao     = urlencode( $oProgressaoParcial->getSituacaoProgressao()->getDescricao());
          $oDadosProgressao->lAtiva        = $oProgressaoParcial->getSituacaoProgressao()->isAtivo();
          $oDadosProgressao->lManual       = is_null($oProgressaoParcial->getCodigoDiarioFinal());
          $oDadosProgressao->lTemMatricula = $oProgressaoParcial->verificaProgressaoTeveMatricula();

  	      $oRetorno->aProgressoes[] = $oDadosProgressao;
  	    }
  	  }

  	  break;

  	case 'atualizarSituacaoProgressao':

  	  if ( !isset( $oParam->aProgressoes ) || empty( $oParam->aProgressoes ) ) {
  	    throw new ParameterException( _M( CAMINHO_MENSAGENS."progressao_nao_informada" ) );
  	  }

  	  db_inicio_transacao();

  	  foreach ($oParam->aProgressoes as $oProgressao) {

  	    foreach ($oProgressao->aProgressoes as $iProgressao) {

  	      $oProgressaoParcialAluno = new ProgressaoParcialAluno($iProgressao);
  	      $oProgressaoParcialAluno->alterarSituacao($oProgressao->situacao);
  	    }

  	  }

  	  $oRetorno->message = urlencode( _M( CAMINHO_MENSAGENS."progressao_atualizada" ) );
  	  db_fim_transacao();

  	  break;

    /**
     * Case para buscar os dados das progressoes onde o aluno possui matrícula
     */
    case 'buscaDadosProgressaoAlunoMatriculado':

      if ( !isset( $oParam->iProgressao ) || empty( $oParam->iProgressao ) ) {
        throw new ParameterException( _M( CAMINHO_MENSAGENS."progressao_nao_informada" ) );
      }

      $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();

      db_inicio_transacao();

      $sCamposProgressao = " ed115_regencia, ed121_resultadofinal, ed121_nota as aproveitamento ";
      $sWhereProgressao  = " ed150_progressaoparcialaluno = {$oParam->iProgressao}" ;
      $sOrdemProgressao  = " ed150_ano";
      $sSqlProgressao    = $oDaoProgressaoParcialAluno->sql_query_resultado_regencia(null, $sCamposProgressao, $sOrdemProgressao, $sWhereProgressao);
      $rsProgressao      = db_query( $sSqlProgressao );

      if ( !$rsProgressao ) {
        throw new DBException( _M( CAMINHO_MENSAGENS."erro_buscar_progressao" ) );
      }

      $oRetorno->aDadosProgressao = array();
      $iLinha                     = pg_num_rows( $rsProgressao );

      if ( $iLinha > 0 ) {

        for ( $iContador = 0; $iContador < $iLinha; $iContador++ ) {

          $oDadosProgressaoMatricula = db_utils::fieldsMemory( $rsProgressao, $iContador );

          $oRegencia = new Regencia( $oDadosProgressaoMatricula->ed115_regencia );

          $oDadosProgressao                  = new stdClass();
          $oDadosProgressao->iEscola         = $oRegencia->getTurma()->getEscola()->getCodigo();
          $oDadosProgressao->sEscola         = urlencode( $oRegencia->getTurma()->getEscola()->getNome() );
          $oDadosProgressao->iAno            = $oRegencia->getTurma()->getCalendario()->getAnoExecucao();
          $oDadosProgressao->iTurma          = $oRegencia->getTurma()->getCodigo();
          $oDadosProgressao->sTurma          = urlencode( $oRegencia->getTurma()->getDescricao() );
          $oDadosProgressao->sAproveitamento = urlencode( $oDadosProgressaoMatricula->aproveitamento );
          $oDadosProgressao->sResultadoFinal = urlencode( $oDadosProgressaoMatricula->ed121_resultadofinal );
          $oRetorno->aDadosProgressao[]      = $oDadosProgressao;
        }
      }

      db_fim_transacao();

      break;
  }
} catch ( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode( $oErro->getMessage() );
}
$oRetorno->erro = $oRetorno->status == 2;
echo $oJson->encode($oRetorno);
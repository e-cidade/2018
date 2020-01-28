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

define( 'MENSAGENS_HISTORICOESCOLAR', 'educacao.escola.HistoricoEscolar.' );

/**
 * Classe reservada para encapsular as regras ou a��es referente ao hist�rico escolar do aluno
 *
 * @package educacao
 * @author  Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.6 $
 */
class HistoricoEscolar {


  /**
   * Escola tem permiss�o total de manuten��o no historico do aluno
   */
  const PERMITE_MANUTENCAO = 1;

  /**
   * Quando a �ltima matr�cula do aluno pertencer � escola atual e o per�odo de manuten��o de hist�rico
   * da escola anterior ainda estiver vigente
   */
  const PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS = 2;

  /**
   * Quando a escola anterior quiser dar manuten��o e o per�odo de manuten��o do hist�rico estiver vigente
   */
  const PERMITE_MANUTENCAO_ETAPAS_MENORES = 3;

  /**
   * Quando a escola n�o pode realizar manuten��o no hist�rico
   */
  const NAO_PERMITE_MANUTENCAO = 4;

  /**
   * Valida se a Escola pode dar manuten��o no Hist�rico do aluno, se sim qual tipo
   *
   * @param Aluno  $oAluno
   * @param Escola $oEscola
   * @return int
   * @throws BusinessException
   * @throws DBException
   */
  public function permiteManutencaoHistorico( Aluno $oAluno, Escola $oEscola ) {

    $oUltimaMatricula = MatriculaRepository::getUltimaMatriculaAluno( $oAluno );

    /**
     * Se aluno n�o tem matr�cula, todas escolas podem dar manuten��o no hist�rico
     * -> Em releases anteriores a 2.3.50 assim que criado hist�rico o mesmo s� teria manuten��o pela escola que o criou
     */
    if ( is_null($oUltimaMatricula) ) {
      return self::PERMITE_MANUTENCAO;
    }

    // N�mero de dias que a escola tem para informar o hist�rico do aluno ap�s transferido
    $iDiasManutencaoHistorico = EducacaoSessionManager::diasManutencaoHistorico();

    /**
     * Se n�o foi configurado dias para manuten��o do hist�rico, somente escola onde aluno encontra-se matr�culado tem acesso
     */
    if ( empty($iDiasManutencaoHistorico) ) {

      if ( $oUltimaMatricula->getTurma()->getEscola()->getCodigo() != $oEscola->getCodigo() ) {
        return self::NAO_PERMITE_MANUTENCAO;
      }
      return self::PERMITE_MANUTENCAO;
    }

    $oDataAtual = new DBDate( date('Y-m-d') );
    $aSituacoes = array('TRANSFERIDO FORA', 'TRANSFERIDO REDE');

    /**
     * Se aluno esta transferido e ainda n�o foi matr�culado
     */
    if ( in_array($oUltimaMatricula->getSituacao(), $aSituacoes )  ) {

      // N�mero de dias que passou desde a transfer�ncia do aluno at� hoje
      $iNumeroDiasTransferencia = DBDate::getIntervaloEntreDatas($oDataAtual, $oUltimaMatricula->getDataEncerramento())->days;

      /**
       * Se escola � a mesma da matr�cula
       */
      if ( $oUltimaMatricula->getTurma()->getEscola()->getCodigo() == $oEscola->getCodigo() ) {

        if ($iNumeroDiasTransferencia <= $iDiasManutencaoHistorico ) {
          return self::PERMITE_MANUTENCAO_ETAPAS_MENORES;
        }
      }
      return self::NAO_PERMITE_MANUTENCAO;
    }


    /**
     * Validar matr�cula anterior, se esta transferido
     */
    if ( $oUltimaMatricula->getSituacao() == 'MATRICULADO' ) {

      $aMatriculas = MatriculaRepository::getTodasMatriculasAluno($oAluno, false, null, " ed60_i_codigo desc limit 2 ");

      $oPenultimaMatricula = null;

      if ( count($aMatriculas) > 1 )  {

        $oPenultimaMatricula = $aMatriculas[1];
        /**
         * Se a situa��o da matr�cula anterior for 'MATRICULADO', somente a escola onde aluno esta matriculado
         * tem permiss�o de manuten��o no hist�rico
         */
        if ($oPenultimaMatricula->getSituacao() == 'MATRICULADO') {

          if ( $oUltimaMatricula->getTurma()->getEscola()->getCodigo() != $oEscola->getCodigo() ) {
            return self::NAO_PERMITE_MANUTENCAO;
          }
          return self::PERMITE_MANUTENCAO;
        }

        // Se situa��o da matr�cula anterior for TRANSFERIDO (FORA/REDE)
        if ( in_array($oPenultimaMatricula->getSituacao(), $aSituacoes) ) {

          $iNumeroDiasTransferencia = DBDate::getIntervaloEntreDatas($oDataAtual, $oPenultimaMatricula->getDataEncerramento())->days;

          /**
           * Caso aluno tenha sido TRANSFERIDO FORA e retornado para a mesma escola, permitimos a manuten��o do hist�rico,
           * do mesmo.
           */
          if ( $oPenultimaMatricula->getSituacao() == 'TRANSFERIDO FORA'
              && $oPenultimaMatricula->getTurma()->getEscola()->getCodigo() == $oUltimaMatricula->getTurma()->getEscola()->getCodigo()
              && $oUltimaMatricula->getTurma()->getEscola()->getCodigo() == $oEscola->getCodigo() ) {
            return self::PERMITE_MANUTENCAO;
          }

          /**
           * Se estiver na escola onde o aluno foi transferido, e esta no prazo para atualizar o hist�rico,
           * deve permitir atualizar as etapas inferiores a de transfer�ncia.
           * Ap�s vencer o prazo, escola n�o da mais manuten��o
           */
          if ( $oPenultimaMatricula->getTurma()->getEscola()->getCodigo() == $oEscola->getCodigo() ) {

            if ($iNumeroDiasTransferencia <= $iDiasManutencaoHistorico) {
              return self::PERMITE_MANUTENCAO_ETAPAS_MENORES;
            }
            return self::NAO_PERMITE_MANUTENCAO;
          }


          /**
           * Se acessado da escola atual do aluno, e escola anterior ainda esta no prazo para atualizar o di�rio,
           * escola atual s� pode dar manuten��o na etapa em que aluno esta matriculado ou maior
           */
          if ( $oUltimaMatricula->getTurma()->getEscola()->getCodigo() == $oEscola->getCodigo() ) {

            if ($iNumeroDiasTransferencia <= $iDiasManutencaoHistorico) {
              return self::PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS;
            }
            return self::PERMITE_MANUTENCAO;
          }
        }
      }
      /**
       * Se aluno n�o tem matr�cula anterior
       */
      if ( $oUltimaMatricula->getTurma()->getEscola()->getCodigo() != $oEscola->getCodigo() ) {
        return self::NAO_PERMITE_MANUTENCAO;
      }
      return self::PERMITE_MANUTENCAO;
    }

    $sMsgErro  = "Situa��o do aluno {$oAluno->getNome()} n�o est� prevista nas regras para manuten��o do hist�rico ";
    $sMsgErro .= "que foram implementadas na vers�o v2.3.50. Favor entrar em contato com suporte.";
    throw new BusinessException( $sMsgErro );
  }

  /**
   * Verifica se a etapa a ser adicionada ao hist�rico, possui inconsist�ncias em rela��o a equival�ncias e a matr�cula
   * atual( quando ativa )
   *
   * @param Etapa $oEtapa
   * @param Aluno $oAluno
   * @param       $iAnoEtapa
   * @param       $sResultado
   * @return bool
   * @throws DBException
   */
  public static function temInconsistenciaEtapaSelecionada( Etapa $oEtapa, Aluno $oAluno, $iAnoEtapa, $sResultado ) {

    $aHistoricosAluno = HistoricoAlunoRepository::getHistoricosPorAluno( $oAluno );
    $oMatricula       = MatriculaRepository::getMatriculaAtivaPorAluno( $oAluno );

    /**
     * Valida��es considerando aluno com matr�cula ativa
     */
    if( $oMatricula != null && $oMatricula instanceof Matricula ) {

      /**
       * Compara se o ano � igual ou maior que o da matr�cula
       */
      if( $iAnoEtapa >= $oMatricula->getTurma()->getCalendario()->getAnoExecucao() ) {

        db_msgbox( _M( MENSAGENS_HISTORICOESCOLAR . 'ano_invalido' ) );
        return true;
      }

      /**
       * Compara se a etapa � igual ou maior que a etapa da matr�cula, quando trata-se do mesmo ensino
       */
      if(    $oEtapa->getEnsino()->getCodigo() == $oMatricula->getEtapaDeOrigem()->getEnsino()->getCodigo()
          && $oEtapa->getOrdem() >= $oMatricula->getEtapaDeOrigem()->getOrdem()
        ) {

        db_msgbox( _M( MENSAGENS_HISTORICOESCOLAR . 'etapa_invalida' ) );
        return true;
      }

      /**
       * Busca as etapas equivalentes da etapa seleciona, comparando se a etapa da matr�cula faz parte dessa equival�ncia
       */
      foreach( $oEtapa->buscaEtapaEquivalente() as $oEtapaEquivalente ) {

        if( $oEtapaEquivalente->getCodigo() == $oMatricula->getEtapaDeOrigem()->getCodigo() ) {

          db_msgbox( _M( MENSAGENS_HISTORICOESCOLAR . 'etapa_equivalente_matricula' ) );
          return true;
        }
      }
    }

    /**
     * Valida as equival�ncias da etapa selecionada, com as etapas j� adicionadas ao hist�rico, somente quando selecionado
     * resultado de aprovado e a Etapa no Hist�rico esta aprovada
     */
    if( $sResultado == 'A' ) {

      foreach( $oEtapa->buscaEtapaEquivalente() as $oEtapaEquivalente ) {

        foreach( $aHistoricosAluno as $oHistoricoAluno ) {

          foreach( $oHistoricoAluno->getEtapas() as $oHistoricoEtapa ) {

            if(    $oEtapaEquivalente->getCodigo() == $oHistoricoEtapa->getEtapa()->getCodigo()
                && $oHistoricoEtapa->getResultadoAno() == 'A') {

              db_msgbox( _M( MENSAGENS_HISTORICOESCOLAR . 'etapa_equivalente_historico' ) );
              return true;
            }
          }
        }
      }
    }

    return false;
  }
}
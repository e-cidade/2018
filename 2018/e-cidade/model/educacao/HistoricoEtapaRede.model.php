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

class HistoricoEtapaRede extends HistoricoEtapa {

  /**
   * Construtor da classe. Recebe o código de historicomps
   * @param integer $iCodigoHistoricoEtapa - Código de historicomps
   */
  public function __construct($iCodigoHistoricoEtapa = '') {

    if (!empty($iCodigoHistoricoEtapa)) {

      $oDaoHistoricoEtapa = new cl_historicomps();
      $sSqlDadosEtapa     = $oDaoHistoricoEtapa->sql_query_file($iCodigoHistoricoEtapa);
      $rsEtapa            = $oDaoHistoricoEtapa->sql_record($sSqlDadosEtapa);
      if ($oDaoHistoricoEtapa->numrows > 0) {

        $oDadosEtapa            = db_utils::fieldsMemory($rsEtapa, 0);
        $this->iCodigoEtapa     = $iCodigoHistoricoEtapa;
        $this->iCodigoHistorico = $oDadosEtapa->ed62_i_historico;
        $this->setAnoCurso($oDadosEtapa->ed62_i_anoref);
        $this->setCargaHoraria($oDadosEtapa->ed62_i_qtdch);
        $this->setDiasLetivos($oDadosEtapa->ed62_i_diasletivos);
        $this->setEscola( EscolaRepository::getEscolaByCodigo($oDadosEtapa->ed62_i_escola) );
        $this->setEtapa( EtapaRepository::getEtapaByCodigo($oDadosEtapa->ed62_i_serie) );
        $this->setJustificativa($oDadosEtapa->ed62_i_justificativa);
        $this->setMininoParaAprovacao($oDadosEtapa->ed62_c_minimo);
        $this->setResultadoAno($oDadosEtapa->ed62_c_resultadofinal);
        $this->setSituacaoEtapa($oDadosEtapa->ed62_c_situacao);
        $this->setTurma($oDadosEtapa->ed62_i_turma);
        $this->setLancamentoAutomatico($oDadosEtapa->ed62_lancamentoautomatico == 't');
        $this->setTermoFinal($oDadosEtapa->ed62_c_termofinal);
        $this->setPercentualFrequencia($oDadosEtapa->ed62_percentualfrequencia);
        $this->setObservacao($oDadosEtapa->ed62_observacao);
        unset($oDadosEtapa);
      }
    }
  }

  /**
   * Persiste os dados da Etapa
   * @param integer $iCodigoHistorico - Código do Histórico
   */
  public function salvar($iCodigoHistorico = '') {

    if (!($this->getEscola() instanceof Escola)) {
      throw new BusinessException("Escola informado nao é uma escola da rede.");
    }

    $oDaoHistorico = new cl_historicomps();
    $oDaoHistorico->ed62_c_minimo             = $this->getMininoParaAprovacao();
    $oDaoHistorico->ed62_c_resultadofinal     = $this->getResultadoAno();
    $oDaoHistorico->ed62_c_situacao           = $this->getSituacaoEtapa();
    $oDaoHistorico->ed62_i_anoref             = $this->getAnoCurso();
    $oDaoHistorico->ed62_i_diasletivos        = $this->getDiasLetivos();
    $oDaoHistorico->ed62_i_escola             = $this->getEscola()->getCodigo();
    $oDaoHistorico->ed62_i_justificativa      = $this->getJustificativa();
    $oDaoHistorico->ed62_i_periodoref         = $this->getAnoCurso();
    $oDaoHistorico->ed62_i_qtdch              = $this->getCargaHoraria();
    $oDaoHistorico->ed62_i_serie              = $this->getEtapa()->getCodigo();
    $oDaoHistorico->ed62_i_turma              = $this->getTurma();
    $oDaoHistorico->ed62_percentualfrequencia = "{$this->getPercentualFrequencia()}";
    $oDaoHistorico->ed62_observacao           = $this->getObservacao();

    /**
     * @todo fazer salvar o ed62_c_termofinal
     */
    $oDaoHistorico->ed62_lancamentoautomatico = $this->isLancamentoAutomatico() ? 'true' : 'false';

    if (empty($this->iCodigoEtapa)) {

      $oDaoHistorico->ed62_i_historico = $iCodigoHistorico;
      $oDaoHistorico->incluir(null);
      $this->iCodigoEtapa  = $oDaoHistorico->ed62_i_codigo;
    } else {

      $oDaoHistorico->ed62_i_codigo = $this->iCodigoEtapa;
      $oDaoHistorico->alterar($oDaoHistorico->ed62_i_codigo);
    }

    if ($oDaoHistorico->erro_status == 0) {
      throw new BusinessException($oDaoHistorico->erro_msg);
    }

    foreach ($this->aDisciplinas as $oDisciplina) {
      $oDisciplina->salvar($this->iCodigoEtapa);
    }
  }

 /**
  * Adiciona uma Disciplina no Historico
  * @param DisciplinaHistoricoRede $oDisciplina Disciplina Cursada na Rede
  */
  public function adicionarDisciplina(DisciplinaHistoricoRede $oDisciplina) {

    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplinaLancada) {

      if (  $oDisciplina->getDisciplina()->getCodigoDisciplina() ==
            $oDisciplinaLancada->getDisciplina()->getCodigoDisciplina()
          ) {

        $sMensagem = "Disciplina {$oDisciplina->getDisciplina()->getNomeDisciplina()} já lançada para essa Etapa.";
        throw new BusinessException( $sMensagem );
      }
    }

    $this->aDisciplinas[] = $oDisciplina;
  }

  /**
   * Retorna as disciplinas cursadas na etapa
   * @return DisciplinaHistoricoRede[];
   */
  public function getDisciplinas() {

    if (count($this->aDisciplinas) == 0 && !empty($this->iCodigoEtapa)) {

      $oDaoDisciplina = new cl_histmpsdisc();
      $sWhere         = "ed65_i_historicomps = {$this->iCodigoEtapa}";
      $sSqlDisciplina = $oDaoDisciplina->sql_query_file(null, "ed65_i_codigo", "ed65_i_ordenacao", $sWhere);
      $rsDisciplina   = $oDaoDisciplina->sql_record($sSqlDisciplina);

      if( $rsDisciplina ) {

        $aDisciplinas   = db_utils::getCollectionByRecord($rsDisciplina);
        foreach ($aDisciplinas as $oDisciplina) {
          $this->aDisciplinas[] = new DisciplinaHistoricoRede($oDisciplina->ed65_i_codigo);
        }
        unset($aDisciplinas);
      }
    }

    return $this->aDisciplinas;
  }

  /**
   * Retorna a Disciplina pelo codigo de lancamento caso nao encontre a disciplina, retorna false
   * @param  integer $iCodigoDisciplina - Código da disciplina
   * @return DisciplinaHistoricoRede
   */
  public function getDisciplinaByCodigoDeLancamento($iCodigoDisciplina) {

    $aDisciplinas       = $this->getDisciplinas();
    $oDisciplinaRetorno = false;
    foreach ($aDisciplinas as $oDisciplina) {

      if ($oDisciplina->getCodigo() == $iCodigoDisciplina) {
        $oDisciplinaRetorno = $oDisciplina;
        break;
      }
    }

    return $oDisciplinaRetorno;
  }
}
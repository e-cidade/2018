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


require_once ('model/educacao/HistoricoEtapa.model.php');

class HistoricoEtapaForaRede extends HistoricoEtapa {

  /**
   *
   */
  function __construct($iCodigoEtapa = '') {

    if (!empty($iCodigoEtapa)) {

      $oDaoHistoricoEtapa = db_utils::getDao("historicompsfora");
      $sSqlDadosEtapa     = $oDaoHistoricoEtapa->sql_query_file($iCodigoEtapa);
      $rsEtapa            = $oDaoHistoricoEtapa->sql_record($sSqlDadosEtapa);
      if ($oDaoHistoricoEtapa->numrows > 0) {

        $oDadosEtapa        = db_utils::fieldsMemory($rsEtapa, 0);
        $this->iCodigoEtapa = $iCodigoEtapa;
        $this->iCodigoHistorico = $oDadosEtapa->ed99_i_historico;
        $this->setAnoCurso($oDadosEtapa->ed99_i_anoref);
        $this->setCargaHoraria($oDadosEtapa->ed99_i_qtdch);
        $this->setDiasLetivos($oDadosEtapa->ed99_i_diasletivos);
        $this->setEscola(new EscolaProcedencia($oDadosEtapa->ed99_i_escolaproc));
        $this->setEtapa(new Etapa($oDadosEtapa->ed99_i_serie));
        $this->setJustificativa($oDadosEtapa->ed99_i_justificativa);
        $this->setMininoParaAprovacao($oDadosEtapa->ed99_c_minimo);
        $this->setResultadoAno($oDadosEtapa->ed99_c_resultadofinal);
        $this->setSituacaoEtapa($oDadosEtapa->ed99_c_situacao);
        $this->setTurma($oDadosEtapa->ed99_c_turma);
        $this->setTermoFinal($oDadosEtapa->ed99_c_termofinal);
        $this->setObservacao($oDadosEtapa->ed99_observacao);
        unset($oDadosEtapa);
      }
    }
  }

  /**
   * Persiste os dados da Etapa
   * @param codigo do Historico
   */
  function salvar($iCodigoHistorico = '') {

    if (!($this->getEscola() instanceof EscolaProcedencia )) {
      throw new BusinessException("Escola informado nao é uma escola de procedencia.");
    }
    $oDaoHistorico = db_utils::getDao("historicompsfora");
    $oDaoHistorico->ed99_c_minimo         = $this->getMininoParaAprovacao();
    $oDaoHistorico->ed99_c_resultadofinal = $this->getResultadoAno();
    $oDaoHistorico->ed99_c_situacao       = $this->getSituacaoEtapa();
    $oDaoHistorico->ed99_i_anoref         = $this->getAnoCurso();
    $oDaoHistorico->ed99_i_diasletivos    = $this->getDiasLetivos();
    $oDaoHistorico->ed99_i_escolaproc     = $this->getEscola()->getCodigo();
    $oDaoHistorico->ed99_i_justificativa  = $this->getJustificativa();
    $oDaoHistorico->ed99_i_periodoref     = $this->getAnoCurso();
    $oDaoHistorico->ed99_i_qtdch          = $this->getCargaHoraria();
    $oDaoHistorico->ed99_i_serie          = $this->getEtapa()->getCodigo();
    $oDaoHistorico->ed99_c_turma          = $this->getTurma();
    $oDaoHistorico->ed99_observacao       = $this->getObservacao();

    /**
     * @todo fazer salvar o ed62_c_termofinal
     */
    if (empty($this->iCodigoEtapa)) {

      $oDaoHistorico->ed99_i_historico  = $iCodigoHistorico;
      $oDaoHistorico->incluir(null);

      $this->iCodigoHistorico = $iCodigoHistorico;
      $this->iCodigoEtapa     = $oDaoHistorico->ed99_i_codigo;
    } else {

      $oDaoHistorico->ed99_i_historico = $this->iCodigoHistorico;
      $oDaoHistorico->ed99_i_codigo = $this->iCodigoEtapa;
      $oDaoHistorico->alterar($oDaoHistorico->ed99_i_codigo);
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
  public function adicionarDisciplina(DisciplinaHistoricoForaRede $oDisciplina) {

    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplinaLancada) {

      if ($oDisciplina->getDisciplina()->getCodigoDisciplina() ==
          $oDisciplinaLancada->getDisciplina()->getCodigoDisciplina()) {
        throw new BusinessException("Disciplina {$oDisciplina->getDisciplina()->getNomeDisciplina()} já lançada para essa Etapa.");
      }
    }
    $this->aDisciplinas[] = $oDisciplina;
  }

  /**
   * Retorna as disclinas cursadas na etapa
   * @return DisciplinaHistoricoRede[];
   */
  public function getDisciplinas() {

    if (count($this->aDisciplinas) == 0) {

      $oDaoDisciplina = db_utils::getDao("histmpsdiscfora");
      $sWhere         = "ed100_i_historicompsfora = {$this->iCodigoEtapa}";
      $sSqlDisciplina = $oDaoDisciplina->sql_query_file(null, "ed100_i_codigo", "ed100_i_ordenacao", $sWhere);
      $rsDisciplina   = $oDaoDisciplina->sql_record($sSqlDisciplina);
      $aDisciplinas   = db_utils::getCollectionByRecord($rsDisciplina);
      foreach ($aDisciplinas as $oDisciplina) {
        $this->aDisciplinas[] = new DisciplinaHistoricoForaRede($oDisciplina->ed100_i_codigo);
      }
      unset($aDisciplinas);
    }
    return $this->aDisciplinas;
  }

  /**
   * Retorna a Disciplina pelo codigo de lancamento
   * caso nao encontre a disciplina, retorna false;
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

?>
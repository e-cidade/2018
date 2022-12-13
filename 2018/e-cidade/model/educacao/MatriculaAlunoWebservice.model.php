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

require_once "libs/db_stdlibwebseller.php";
/**
 * Classe para webservices de aluno
 * Atua como um facade para a classe de Aluno.
 * as informações Retornadas são dos dados de Documentação, matriculas e notas dos alunos
 * @author dbseller
 *
 */
class MatriculaAlunoWebservice {

  /**
   * Matricula do aluno
   * @var Matricula
   */
  private $oMatricula   = null;

  /**
   * Cria uma nova instancia do Servico
   * @param string $iCodigoMatricula
   */
  public function __construct($iCodigoMatricula = null) {
    $this->oMatricula = new Matricula($iCodigoMatricula);
  }

  /**
   * Retorna os dados da matricula
   * @return stdClass
   */
  public function getDados() {

    $oDadosMatricula = new stdClass();
    $iCodigoMatricula                    = $this->oMatricula->getCodigo();
    $iCodigoAluno                        = $this->oMatricula->getAluno()->getCodigoAluno();
    $iCodigoTurma                        = $this->oMatricula->getTurma()->getCodigo();
    $iCodigoEnsino                       = $this->oMatricula->getTurma()->getBaseCurricular()->getCurso()
                                                                        ->getEnsino()->getCodigo();
    $oDadosMatricula->codigo_matricula   = $this->oMatricula->getMatricula();
    $oDadosMatricula->data_matricula     = $this->oMatricula->getDataMatricula()->convertTo(DBDate::DATA_EN);
    $oDadosMatricula->situacao_matricula = utf8_encode($this->oMatricula->getSituacao());
    if ($this->oMatricula->getTipo() == 'R' && $this->oMatricula->getSituacao() == 'MATRICULADO') {
      $oDadosMatricula->situacao_matricula = utf8_encode("REMATRICULADO");
    }
    if ($this->oMatricula->isConcluida()) {
      $oDadosMatricula->situacao_matricula .= " (".utf8_encode("CONCLUÍDA").")";
    }
    $oDadosMatricula->etapa_matricula    = utf8_encode($this->oMatricula->getEtapaDeOrigem()->getNome());
    $oDadosMatricula->turma_matricula    = utf8_encode($this->oMatricula->getTurma()->getDescricao());
    $oDadosMatricula->turma_turno        = utf8_encode($this->oMatricula->getTurma()->getTurno()->getDescricao());
    $oDadosMatricula->turma_calendario   = utf8_encode($this->oMatricula->getTurma()->getCalendario()->getDescricao());
    $oDadosMatricula->turma_escola       = utf8_encode($this->oMatricula->getTurma()->getEscola()->getNome());
    $oDadosMatricula->data_saida         = '';
    if ($this->oMatricula->getDataEncerramento() != "") {
      $oDadosMatricula->data_saida = $this->oMatricula->getDataEncerramento()->convertTo(DBDate::DATA_EN);
    }


    $oDadosMatricula->periodos_etapa            = $this->getPeridosEtapa();
    $oDadosMatricula->grade_aproveitamento      = $this->getGradeAproveitamento();
    $oDadosMatricula->resultado_final_etapa     = utf8_encode(ResultadoFinal($iCodigoMatricula,
                                                                 $iCodigoAluno,
                                                                 $iCodigoTurma,
                                                                 $this->oMatricula->getSituacao(),
                                                                 $this->oMatricula->isConcluida()?'S':'N',
                                                                 $iCodigoEnsino
                                                                ));
    $oDadosMatricula->atividades_complementares = $this->getAtividadesComplementares();
    return $oDadosMatricula;
  }

  protected function getPeridosEtapa() {

    $aPeriodos    = array();
    $oEtapaOrigem = $this->oMatricula->getEtapaDeOrigem();

    $oProcedimentoAvaliacao = $this->oMatricula->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapaOrigem);
    foreach ($oProcedimentoAvaliacao->getElementos() as $oElemento){

      if ($oElemento->isResultado() && !$oElemento->imprimeNoBoletim()) {
        continue;
      }
      $oPeriodo                  = new stdClass();
      $oPeriodo->sFormaAvaliacao = $oElemento->getFormaDeAvaliacao()->getTipo();
      if ($oElemento instanceof ResultadoAvaliacao) {

        $oPeriodo->sDescricao      = utf8_encode($oElemento->getTipoResultado()->getDescricao());
        $oPeriodo->sAbreviatura    = utf8_encode($oElemento->getTipoResultado()->getDescricaoAbreviada());
      } else {

        $oPeriodo->sDescricao      = utf8_encode($oElemento->getPeriodoAvaliacao()->getDescricao());
        $oPeriodo->sAbreviatura    = utf8_encode($oElemento->getPeriodoAvaliacao()->getDescricaoAbreviada());
      }

      $aPeriodos[] = $oPeriodo;
    }

    return $aPeriodos;
  }

  /**
   * Retorna a grade de notas do Aluno
   * @return Ambigous <Ambigous, NULL, stdClass>
   */
  protected function getGradeAproveitamento() {

    $_SESSION["DB_coddepto"] = $this->oMatricula->getTurma()->getEscola()->getCodigo();
    db_inicio_transacao();
    $oGradeHorario = new GradeAproveitamentoAluno($this->oMatricula);
    $oGradeHorario->setUtfEncode(true);
    $oGradeRetorno = $oGradeHorario->getGradeAproveitamento();
    db_fim_transacao();
    return $oGradeRetorno;
  }


  /**
   * Retorna as Atividades complementares, e atendimento especializado do aluno
   */
  public function getAtividadesComplementares() {

    $oDaoMatriculaAc     = new cl_turmaacmatricula();
    $iCodigoAluno        = $this->oMatricula->getAluno()->getCodigoAluno();
    $iAnoCalendario      = $this->oMatricula->getTurma()->getCalendario()->getAnoExecucao();
    $iEscola             = $this->oMatricula->getTurma()->getEscola()->getCodigo();
    $sWhere              = "ed269_aluno = {$iCodigoAluno}";
    $sWhere             .= " and ed52_i_ano = {$iAnoCalendario}";

    $aAtividadesComplementares = array();

    $sSqlMatriculaAluno = $oDaoMatriculaAc->sql_query_turma(null, "*", null, $sWhere);
    $rsMatriculaAluno   = $oDaoMatriculaAc->sql_record($sSqlMatriculaAluno);

    if ($rsMatriculaAluno && $oDaoMatriculaAc->numrows > 0) {

      $iTurmasAc = $oDaoMatriculaAc->numrows;
      for ($i = 0; $i < $iTurmasAc; $i++) {

        $oDadosTurmaComplementar = db_utils::fieldsMemory($rsMatriculaAluno, $i);
        $oEscola                 = EscolaRepository::getEscolaByCodigo($oDadosTurmaComplementar->ed268_i_escola);

        $iCodigoTurma                       = $oDadosTurmaComplementar->ed268_i_codigo;
        $iTipoTurma                         = $oDadosTurmaComplementar->ed268_i_tipoatend;
        $oDadosAtendimento                  = $oDadosTurmaComplementar->ed268_c_aee;
        $oTurmaComplementar                 = new stdclass();
        $oTurmaComplementar->nome_turma     = utf8_encode($oDadosTurmaComplementar->ed268_c_descr);
        $oTurmaComplementar->codigo_turma   = utf8_encode($oDadosTurmaComplementar->ed268_i_codigo);
        $oTurmaComplementar->turno_turma    = utf8_encode($oDadosTurmaComplementar->ed15_c_nome);
        $oTurmaComplementar->escola         = utf8_encode($oEscola->getNome());
        $oTurmaComplementar->tipo_turma     = $iTipoTurma;
        $oTurmaComplementar->atividades     = $this->getAtividades($iCodigoTurma, $iTipoTurma, $oDadosAtendimento);
        $oTurmaComplementar->aProfissionais = $this->getProfessoresVinculadosTurmaAtividadeComplementar($oDadosTurmaComplementar->ed268_i_codigo);
        $aAtividadesComplementares[]        = $oTurmaComplementar;
      }
    }
    return $aAtividadesComplementares;
  }

  /**
   * Retorna os professores vinculados em uma turmaac
   * @param int $iCodigoTurma código sequencial da turmaac
   * @return array
   */
  protected function getProfessoresVinculadosTurmaAtividadeComplementar($iCodigoTurma) {

    $sCampos  = " distinct                        ";
    $sCampos .= " ed20_i_codigo,                  ";
    $sCampos .= " case                            ";
    $sCampos .= "    when ed20_i_tiposervidor = 1 ";
    $sCampos .= "      then trim(cgmrh.z01_nome)  ";
    $sCampos .= "    else trim(cgmcgm.z01_nome)   ";
    $sCampos .= " end as profissional,            ";
    $sCampos .= " ed32_i_codigo,                  ";
    $sCampos .= " ed32_c_descr,                   ";
    $sCampos .= " ed346_horainicial,              ";
    $sCampos .= " ed346_horafinal                 ";

    $sWhere   = " ed346_turmaac = {$iCodigoTurma} ";

    $oDaoTurmaHorarioProfissional = new cl_turmaachorarioprofissional();

    $sSqlVinculos = $oDaoTurmaHorarioProfissional->sql_query_vinculo_profissional(null, $sCampos, "ed32_i_codigo", $sWhere);
    $rsVinculos   = $oDaoTurmaHorarioProfissional->sql_record($sSqlVinculos);

    $aProfissionais = array();
    if ($rsVinculos && $oDaoTurmaHorarioProfissional->numrows > 0) {

      $iLinhasProfissionais = $oDaoTurmaHorarioProfissional->numrows;
      for ($iProfissional = 0; $iProfissional < $iLinhasProfissionais; $iProfissional ++ ) {

        $oDadosProfissional    = db_utils::fieldsMemory($rsVinculos, $iProfissional);

        $oHorario              = new stdClass();
        $oHorario->iDia        = $oDadosProfissional->ed32_i_codigo;
        $oHorario->sDia        = utf8_encode($oDadosProfissional->ed32_c_descr);
        $oHorario->sHoraInicio = $oDadosProfissional->ed346_horainicial;
        $oHorario->sHoraFinal  = $oDadosProfissional->ed346_horafinal;

        if ( !isset($aProfissionais[$oDadosProfissional->ed20_i_codigo] ) ) {

          $oProfissional              = new stdClass();
          $oProfissional->iCodigo     = $oDadosProfissional->ed20_i_codigo;
          $oProfissional->sNome       = utf8_encode($oDadosProfissional->profissional);
          $oProfissional->aHorarios   = array();
          $aProfissionais[$oDadosProfissional->ed20_i_codigo] = $oProfissional;
        }

        $aProfissionais[$oDadosProfissional->ed20_i_codigo]->aHorarios[] = $oHorario;
      }

    }
    return $aProfissionais;
  }


  /**
   * retorna quais dias da semana a turma de AC/AEE possui aulas
   * @return array
   */
  protected function getDiasDaSemana($iCodigoTurma) {

    $aDiasDeAula      = array();
    $oDaoTurmaHorario = new cl_turmaachorario();
    $sWhereDiasDeAula = "ed270_i_turmaac = {$iCodigoTurma}";
    $sSqlDiasDeAula   = $oDaoTurmaHorario->sql_query_horario(null,
                                                            "distinct ed32_i_codigo,ed32_c_descr",
                                                            "ed32_i_codigo",
                                                             $sWhereDiasDeAula);
    $rsDiasDeAula     = $oDaoTurmaHorario->sql_record($sSqlDiasDeAula);
    if ($rsDiasDeAula && $oDaoTurmaHorario->numrows > 0) {
      for ($iDia = 0; $iDia < $oDaoTurmaHorario->numrows; $iDia++) {
        $aDiasDeAula[] = utf8_encode(db_utils::fieldsMemory($rsDiasDeAula, $iDia)->ed32_c_descr);
      }
    }
    return $aDiasDeAula;
  }

  /**
   * Retorna as atividades das turmas de atividade complementar/atendimento Especializado
   * @param integer $iTurma
   * @param integer $iTipoTurma
   * @param string $sDadosAtendimento dados com os atendimentos Especializados que aturma possui
   * @return Ambigous <multitype:, multitype:string >
   */
  protected function getAtividades($iTurma, $iTipoTurma, $sDadosAtendimento) {

    $aListaAtividades = array();
    switch ($iTipoTurma) {
      case 4:

        $aListaAtividades = $this->getAtividadesComplementaresNaTurma($iTurma);
        break;

      case 5:

        $aListaAtividades = $this->getAtendimentosEspeciaisNaTurma($sDadosAtendimento);
        break;
    }
    return $aListaAtividades;
  }

  /**
   * Lista de atividades complementares que o aluno está matriculado
   * @param unknown $iTurma
   * @return multitype:
   */
  protected function getAtividadesComplementaresNaTurma($iTurma) {

    $aAtividades               = array();
    $oDaoAtividadeComplementar = new cl_turmaacativ();
    $sSqlAtividadeComplementar = $oDaoAtividadeComplementar->sql_query(null,
                                                                       "ed133_c_descr",
                                                                       "ed133_c_descr",
                                                                       "ed267_i_turmaac = {$iTurma}"
                                                                      );
    $rsAtividadeComplementar   = $oDaoAtividadeComplementar->sql_record($sSqlAtividadeComplementar);
    if ($rsAtividadeComplementar && $oDaoAtividadeComplementar->numrows > 0) {
      for ($i = 0; $i < $oDaoAtividadeComplementar->numrows; $i++) {
        $aAtividades[] = utf8_encode(db_utils::fieldsMemory($rsAtividadeComplementar, $i)->ed133_c_descr);
      }
    }
    return $aAtividades;
  }

  /**
   * Atividades de atendimento especializado em que o aluno esta matriculado
   * @param integer $iTurma
   * @return array
   */
  protected function getAtendimentosEspeciaisNaTurma($sListaAtividades) {

    $aAtividades      = array();
    $aListaAtividades = array(
                              'Ensino do Sistema Braile',
                              '',//está criando a string com um zero mais....
                              'Ensino do uso de recursos ópticos e não ópticos',
                              'Estratégias para o desenvolvimento de processos mentais',
                              'Técnicas de orientação e mobilidade',
                              'Ensino da Língua Brasileira de Sinais – Libras',
                              'Ensino de uso da Comunicação Alternativa e Aumentativa - CAA',
                              'Estratégias para enriquecimento curricular',
                              'Ensino do uso do Soroban',
                              'Ensino da usabilidade e das funcionalidades da informática acessível',
                              'Ensino da Língua Portuguesa na modalidade escrita',
                              'Estratégias para autonomia no ambiente escolar'
                             );

    $iTamanho = strlen($sListaAtividades);
    for ($iAtividade = 0; $iAtividade < $iTamanho; $iAtividade++) {

      if (substr($sListaAtividades, $iAtividade, 1) == '1') {
        $aAtividades[] = utf8_encode($aListaAtividades[$iAtividade]);
      }
    }
    return $aAtividades;
  }
}
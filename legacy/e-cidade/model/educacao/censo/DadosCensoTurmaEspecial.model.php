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

/**
 * Classe para geração dos dados das turmas de Educação especial e Atividade Complementar.
 * @package Educacao
 * @subpackage Censo
 *
 */
class DadosCensoTurmaEspecial extends DadosCenso {

  protected $iTurma;

  protected $iTipoTurma;

  protected $aNomeCampoDisciplinaCenso;

  protected $iCodigoEscola;

  /**
   *
   */
  function __construct($iCodigoTurma) {

    $this->aNomeCampoDisciplinaCenso[1]  = "disciplinas_turma_quimica";
    $this->aNomeCampoDisciplinaCenso[2]  = "disciplinas_turma_fisica";
    $this->aNomeCampoDisciplinaCenso[3]  = "disciplinas_turma_matematica";
    $this->aNomeCampoDisciplinaCenso[4]  = "disciplinas_turma_biologia";
    $this->aNomeCampoDisciplinaCenso[5]  = "disciplinas_turma_ciencias";
    $this->aNomeCampoDisciplinaCenso[6]  = "disciplinas_turma_lingua_literatura_portuguesa";
    $this->aNomeCampoDisciplinaCenso[7]  = "disciplinas_lingua_literatura_estrangeira_inglesa";
    $this->aNomeCampoDisciplinaCenso[8]  = "disciplinas_lingua_literatura_estrangeira_espanhol";
    $this->aNomeCampoDisciplinaCenso[9]  = "disciplinas_lingua_literatura_estrangeira_outra";
    $this->aNomeCampoDisciplinaCenso[10] = "disciplinas_turma_artes";
    $this->aNomeCampoDisciplinaCenso[11] = "disciplinas_turma_educacao_fisica";
    $this->aNomeCampoDisciplinaCenso[12] = "disciplinas_turma_historia";
    $this->aNomeCampoDisciplinaCenso[13] = "disciplinas_turma_geografia";
    $this->aNomeCampoDisciplinaCenso[14] = "disciplinas_turma_filosofia";
    $this->aNomeCampoDisciplinaCenso[16] = "disciplinas_turma_informatica_computacao";
    $this->aNomeCampoDisciplinaCenso[17] = "disciplinas_turma_disciplinas_profissionalizantes";
    $this->aNomeCampoDisciplinaCenso[20] = "disciplinas_turma_voltadas_atendimento_necessidade";
    $this->aNomeCampoDisciplinaCenso[21] = "disciplinas_turma_voltadas_diversidade_sociocultur";
    $this->aNomeCampoDisciplinaCenso[23] = "disciplinas_turma_libras";
    $this->aNomeCampoDisciplinaCenso[25] = "disciplinas_turma_disciplinas_pedagogicas";
    $this->aNomeCampoDisciplinaCenso[26] = "disciplinas_turma_ensino_religioso";
    $this->aNomeCampoDisciplinaCenso[27] = "disciplinas_turma_lingua_indigena";
    $this->aNomeCampoDisciplinaCenso[28] = "disciplinas_turma_estudos_sociais";
    $this->aNomeCampoDisciplinaCenso[29] = "disciplinas_turma_sociologia";
    $this->aNomeCampoDisciplinaCenso[30] = "disciplinas_lingua_literatura_estrangeira_frances";
    $this->aNomeCampoDisciplinaCenso[99] = "disciplinas_turma_outras";
    $this->iTurma = $iCodigoTurma;
  }

  public function getDados() {

    $oDaoTurma     = new cl_turmaac();
    $sCamposTurma  = " ed268_i_codigoinep as codigo_turma_inep, ";
    $sCamposTurma .= " ed268_i_codigo as codigo_turma_entidade_escola, ";
    $sCamposTurma .= " ed268_i_escola, ";
    $sCamposTurma .= " trim(ed268_c_descr) as nome_turma, ";

    $sCamposTurma .= " (SELECT ed346_horainicial  ";
    $sCamposTurma .= "    from turmaachorarioprofissional ";
    $sCamposTurma .= "   where ed346_turmaac = turmaac.ed268_i_codigo ";
    $sCamposTurma .= "   order by ed346_diasemana limit 1) AS horario_inicial, ";

    $sCamposTurma .= " (SELECT ed346_horafinal  ";
    $sCamposTurma .= "    from turmaachorarioprofissional ";
    $sCamposTurma .= "   where ed346_turmaac = turmaac.ed268_i_codigo ";
    $sCamposTurma .= "   order by ed346_diasemana limit 1) AS horario_final, ";

    $sCamposTurma .= " ed268_i_tipoatend  as tipo_atendimento, ";
    $sCamposTurma .= "  case when ed268_i_tipoatend = 4 then '' else ed268_c_aee end  as atividades_apoio,";
    $sCamposTurma .= " '' as  codigo_curso_educacao_profissional, ";
    $sCamposTurma .= " '' as etapa_ensino_turma, ";
    $sCamposTurma .= " '' as modalidade_turma,";
    $sCamposTurma .= " ed268_programamaiseducacao as turma_participante_mais_educacao_ensino_medio_inov, ";
    $sCamposTurma .= " 1 as mediacao_didatico_pedagogica ";
    $sSqlTurma     = $oDaoTurma->sql_query($this->getCodigoTurma(), $sCamposTurma);


    $rsDadosTurma  = $oDaoTurma->sql_record($sSqlTurma);
    if ($oDaoTurma->numrows == 0) {
      throw new Exception('não existe turma com os dados informados');
    }

    $oDadosTurma = db_utils::fieldsMemory($rsDadosTurma, 0);
    $oDadosTurma->nome_turma                           = $this->removeCaracteres(trim($oDadosTurma->nome_turma), 2);
    $oDadosTurma->horario_turma_horario_inicial_hora   = substr($oDadosTurma->horario_inicial, 0, 2);
    $oDadosTurma->horario_turma_horario_inicial_minuto = substr($oDadosTurma->horario_inicial, 3, 2);
    $oDadosTurma->horario_turma_horario_final_hora     = substr($oDadosTurma->horario_final, 0, 2);
    $oDadosTurma->horario_turma_horario_final_minuto   = substr($oDadosTurma->horario_final, 3, 2);
    $this->iCodigoEscola                               = $oDadosTurma->ed268_i_escola;

    unset($oDadosTurma->horario_final);
    unset($oDadosTurma->horario_inicial);

    /**
     * Turmas normais nao possuem atividade complementar.
     * apenas criamos as propriedades vazias
    */
    $aAtividades = $this->getAtividadesComplementares();
    $oDadosTurma->quantidade_de_atividades_na_turma  = count($aAtividades);
    for ($iAtividade = 0; $iAtividade < 6; $iAtividade++) {

      $iCodigoAtividade = '';
      if ($oDadosTurma->tipo_atendimento == 4) {
        if (isset($aAtividades[$iAtividade])) {
          $iCodigoAtividade = $aAtividades[$iAtividade]->atividade;
        }
      }
      $iSequenciaAtividade = $iAtividade + 1;
      $oDadosTurma->{"codigo_tipo_atividade_complementar_{$iSequenciaAtividade}"} = $iCodigoAtividade;
    }

    /**
     * as informações de Atendimento Educacional Especializado,
     * devem apenas preenchidas quando existe turma de AEE.
     */
    $oDadosTurma->aee_ensino_sistema_braille                         = substr($oDadosTurma->atividades_apoio, 0, 1);
    $oDadosTurma->aee_ensino_uso_recursos_opticos_nao_opticos        = substr($oDadosTurma->atividades_apoio, 2, 1);
    $oDadosTurma->aee_estrategias_desenvolvimento_processos_mentais  = substr($oDadosTurma->atividades_apoio, 3, 1);
    $oDadosTurma->aee_tecnicas_orientacao_mobilidade                 = substr($oDadosTurma->atividades_apoio, 4, 1);
    $oDadosTurma->aee_ensino_lingua_brasileira_sinais_libras         = substr($oDadosTurma->atividades_apoio, 5, 1);
    $oDadosTurma->aee_ensino_comunicacao_alternativa_aumentativa     = substr($oDadosTurma->atividades_apoio, 6, 1);
    $oDadosTurma->aee_estrategia_enriquecimento_curricular           = substr($oDadosTurma->atividades_apoio, 7, 1);
    $oDadosTurma->aee_ensino_uso_soroban                             = substr($oDadosTurma->atividades_apoio, 8, 1);
    $oDadosTurma->aee_ensino_usabilidade_funcionalidades_informatica = substr($oDadosTurma->atividades_apoio, 9, 1);
    $oDadosTurma->aee_ensino_lingua_portuguesa_modalidade_escrita    = substr($oDadosTurma->atividades_apoio, 10, 1);
    $oDadosTurma->aee_estrategias_autonomia_ambiente_escolar         = substr($oDadosTurma->atividades_apoio, 11, 1);

    unset($oDadosTurma->atividades_apoio);
    /**
     * Criamos as propriedades dos dias da Semana
    */
    $aDiasComAula = $this->getDiasDaSemanaComAula();
    for ($iDia = 1; $iDia <= 7; $iDia++) {

      $iTemAulaNoDia = in_array($iDia, $aDiasComAula)?1:0;
      switch ($iDia) {

        case 1:

          $oDadosTurma->dia_semana_domingo = $iTemAulaNoDia;
          break;

        case 2:

          $oDadosTurma->dia_semana_segunda = $iTemAulaNoDia;
          break;

        case 3:

          $oDadosTurma->dia_semana_terca  = $iTemAulaNoDia;
          break;

        case 4:

          $oDadosTurma->dia_semana_quarta = $iTemAulaNoDia;
          break;

        case 5:

          $oDadosTurma->dia_semana_quinta = $iTemAulaNoDia;
          break;

        case 6:

          $oDadosTurma->dia_semana_sexta = $iTemAulaNoDia;
          break;

        case 7:

          $oDadosTurma->dia_semana_sabado = $iTemAulaNoDia;
          break;
      }

    }
    /**
     * Carregamos as Disciplinas que a turma oferece.
    */
    foreach ($this->getDisciplinasCenso() as $oDisciplinaCenso) {
      $oDadosTurma->{$oDisciplinaCenso->getCampoLayout()} = '';
    }
    $oDadosTurma->turma_sem_docente = '';

    $oDadosTurma->lTurmaRegular = false;
    return $oDadosTurma;
  }

  /**
   * Retorna o codigo da turma
   * @return integer
   */
  public function getCodigoTurma() {
    return $this->iTurma;
  }

  /**
   * Retorna os dias da semana que a turma tem aula.
   * retorna o dia da semana numericamente:
   * 1 - DOMINGO
   * 2 - SEGUNDA
   * 3 - TERCA
   * 4 - QUARTA
   * 5 - QUINTA
   * 6 - SEXTA
   * 7 - SABADO
   * @return Array com os dias da semana de aula
   */
  public function getDiasDaSemanaComAula () {

    $aDiasDaSemana       = array();
    $oDaoRegenciaHorario =  new cl_turmaachorarioprofissional();
    $sWhere              = "ed346_turmaac = {$this->iTurma}";
    $sSqlDiasDaSemana    = $oDaoRegenciaHorario->sql_query_file( null,
                                                                 'ed346_diasemana',
                                                                 null,
                                                                 $sWhere  );

    $rsDiasDaSemana  = $oDaoRegenciaHorario->sql_record($sSqlDiasDaSemana);
    $aDias           = db_utils::getCollectionByRecord($rsDiasDaSemana);
    foreach ($aDias as $oDia) {
      $aDiasDaSemana[] = $oDia->ed346_diasemana;
    }
    unset($aDias);
    return $aDiasDaSemana;
  }

  /**
   * Retorna todas as disciplinas disponiveis no censo
   * @return Array
   */
  public function getDisciplinasCenso() {

    $aDisciplinaCenso     = array();
    $oDaoCensoDisciplina  = db_utils::getDao("censodisciplina");
    $sSqlDisciplinasCenso = $oDaoCensoDisciplina->sql_query(null,
        "ed265_i_codigo as codigo,
        ed265_c_descr as descricao",
        'ed265_i_codigo');
    $rsDisciplinasCenso   = $oDaoCensoDisciplina->sql_record($sSqlDisciplinasCenso);

    for ($iDisciplina = 0; $iDisciplina < $oDaoCensoDisciplina->numrows; $iDisciplina++) {

      $oDisciplina               = db_utils::fieldsMemory($rsDisciplinasCenso, $iDisciplina);
      if (!isset($this->aNomeCampoDisciplinaCenso[$oDisciplina->codigo])) {
        continue;
      }
      $oDisciplinaCenso          = new DisciplinaCenso();
      $oDisciplinaCenso->setDisciplina($oDisciplina->codigo);
      $oDisciplinaCenso->setCampoLayout($this->aNomeCampoDisciplinaCenso[$oDisciplina->codigo]);
      $oDisciplinaCenso->setNome($oDisciplina->descricao);
      $aDisciplinaCenso[]        = $oDisciplinaCenso;
      unset($oDisciplina);
    }
    return $aDisciplinaCenso;
  }

  /**
   * Retorna as Atividades Complementares da Turma;
   * @return Array
   */
  public function getAtividadesComplementares () {

    $oDaoAtividadeComplementar    = db_utils::getDao("turmaacativ");
    $sWhere                       = "ed267_i_turmaac = {$this->getCodigoTurma()}";
    $sSqlAtividadesComplementares = $oDaoAtividadeComplementar->sql_query(null,
        "ed133_i_codigo as atividade,
        ed133_c_descr as nome",
        null,
        $sWhere
    );
    $rsAtividadesComplementares   = $oDaoAtividadeComplementar->sql_record($sSqlAtividadesComplementares);
    return db_utils::getCollectionByRecord($rsAtividadesComplementares);
  }
}

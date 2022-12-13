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


class DadosCensoTurma extends DadosCenso {

  protected $iTurma;

  protected $iTipoTurma;

  protected $aNomeCampoDisciplinaCenso;

  protected $iCodigoEscola;

  /**
   * Código da turma do censo
   * Turma do censo é uma turma unificada através da rotina Turmas Multietapa de Ensinos Diferentes
   * @var integer
   */
  protected $iTurmaCenso = null;

  /**
   * Nome da turma do censo
   * Novo nome da turma informado na unificação de turmas através da rotina Turmas Multietapa de Ensinos Diferentes
   * @var string
   */
  protected $sTurmaCenso = null;

  /**
   * Etapa do censo para turmacenso
   * Nova etapa atribuida a Turma ao unificar turmas através da rotina Turmas Multietapa de Ensinos Diferentes
   * @var integer
   */
  protected $iEtapaTurmaCenso = null;

  /**
   * Data limite da geração do censo
   * @var string
   */
  protected $dtDataCenso = null;

  /**
   * Se a turma instanciada faz parte de uma turma unificada
   * @var boolean
   */
  protected $lTurmaUnificada = false;

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
    $this->iTurma     = $iCodigoTurma;
  }

  /**
   * Seta a data do censo
   * @param string $dtDataCenso
   */
  public function setDataCenso( $dtDataCenso ) {
    $this->dtDataCenso = $dtDataCenso;
  }

  public function getDados() {

    $oDaoTurma     = new cl_turma();
    $sCamposTurma  = " ed57_i_codigoinep as codigo_turma_inep, 													";
    $sCamposTurma .= " ed57_i_codigo as codigo_turma_entidade_escola, 									";
    $sCamposTurma .= " ed57_i_escola, 			                                            ";
    $sCamposTurma .= " ed36_i_codigo,                                                   ";
    $sCamposTurma .= " trim(ed57_c_descr) as nome_turma, 																";
    $sCamposTurma .= " fc_nomeetapaturma(ed57_i_codigo) as etapa_turma, 								";
    $sCamposTurma .= " (SELECT min(ed17_h_inicio) 																			";
    $sCamposTurma .= "    FROM periodoescola 																						";
    $sCamposTurma .= "   WHERE ed17_i_turno  = ed57_i_turno   													";
    $sCamposTurma .= "     AND ed17_i_escola = ed57_i_escola) AS horario_inicial,				";
    $sCamposTurma .= " (SELECT max(ed17_h_fim) 																					";
    $sCamposTurma .= "    FROM periodoescola 																						";
    $sCamposTurma .= "   WHERE ed17_i_turno  = ed57_i_turno 														";
    $sCamposTurma .= "     AND ed17_i_escola = ed57_i_escola) AS horario_final,  				";
    $sCamposTurma .= " ed57_i_tipoatend as tipo_atendimento, 														";
    $sCamposTurma .= " ed36_i_codigo as tipo_ensino, 																		";
    $sCamposTurma .= " ed57_i_censocursoprofiss as codigo_curso_educacao_profissional, 	";
    $sCamposTurma .= " ed132_censoetapa as etapa_ensino_turma, 												  ";
    $sCamposTurma .= " ed57_i_tipoturma as modalidade_turma,														";
    $sCamposTurma .= " ed57_censoprogramamaiseducacao as turma_participante_mais_educacao_ensino_medio_inov, ";
    $sCamposTurma .= " ed10_mediacaodidaticopedagogica as mediacao_didatico_pedagogica  ";
    $sSqlTurma     = $oDaoTurma->sql_query($this->getCodigoTurma(), $sCamposTurma);
    $rsDadosTurma  = $oDaoTurma->sql_record($sSqlTurma);

    if ($oDaoTurma->numrows == 0) {
      throw new Exception('não existe turma com os dados informados');
    }

    $oDadosTurma                  = db_utils::fieldsMemory($rsDadosTurma, 0);
    $oDadosTurma->lTurmaUnificada = $this->lTurmaUnificada;

    if ( !empty($this->sTurmaCenso) ) {
      $oDadosTurma->nome_turma = $this->sTurmaCenso;
    }

    $oDadosTurma->nome_turma = $this->removeCaracteres( $oDadosTurma->nome_turma, 6, false );
    if (!empty($this->iTurmaCenso) ){
      $oDadosTurma->codigo_turma_entidade_escola = $this->iTurmaCenso;
    }

    if ( !empty($this->iEtapaTurmaCenso) ) {
      $oDadosTurma->etapa_ensino_turma = $this->iEtapaTurmaCenso;
    }

    $oDadosTurma->horario_turma_horario_inicial_hora   = substr($oDadosTurma->horario_inicial, 0, 2);
    $oDadosTurma->horario_turma_horario_inicial_minuto = substr($oDadosTurma->horario_inicial, 3, 2);
    $oDadosTurma->horario_turma_horario_final_hora     = substr($oDadosTurma->horario_final, 0, 2);
    $oDadosTurma->horario_turma_horario_final_minuto   = substr($oDadosTurma->horario_final, 3, 2);

    switch ($oDadosTurma->modalidade_turma) {

      case 1:

        $oDadosTurma->modalidade_turma = 1;

        $oDataCenso = new DBDate( $this->dtDataCenso );
        if( $oDataCenso->getAno() > 2014 ) {
          $oDadosTurma->modalidade_turma = $oDadosTurma->ed36_i_codigo;
        }

        break;

      case 7:

        $oDadosTurma->modalidade_turma = 1;
        break;

      case 2:

        $oDadosTurma->modalidade_turma = 3;
        break;

      case 3:

        $oDadosTurma->modalidade_turma = $oDadosTurma->ed36_i_codigo;
        break;
    }

    $iParticipaPrograma = $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov == 't'?1:0;
    $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = $iParticipaPrograma;
    $this->iCodigoEscola = $oDadosTurma->ed57_i_escola;
    unset($oDadosTurma->horario_final);
    unset($oDadosTurma->horario_inicial);

    /**
     * Turmas normais nao possuem atividade complementar.
     * apenas criamos as propriedades vazias
     */
    for ($iAtividade = 1; $iAtividade <= 6; $iAtividade++) {
      $oDadosTurma->{"codigo_tipo_atividade_complementar_{$iAtividade}"} = '';
    }

    /**
     * as informações de Atendimento Educacional Especializado,
     * devem apenas preenchidas quando existe turma de AEE.
     */
    $oDadosTurma->aee_ensino_sistema_braille                         = '';
    $oDadosTurma->aee_ensino_uso_recursos_opticos_nao_opticos        = '';
    $oDadosTurma->aee_estrategias_desenvolvimento_processos_mentais  = '';
    $oDadosTurma->aee_tecnicas_orientacao_mobilidade                 = '';
    $oDadosTurma->aee_ensino_lingua_brasileira_sinais_libras         = '';
    $oDadosTurma->aee_ensino_comunicacao_alternativa_aumentativa     = '';
    $oDadosTurma->aee_estrategia_enriquecimento_curricular           = '';
    $oDadosTurma->aee_ensino_uso_soroban                             = '';
    $oDadosTurma->aee_ensino_usabilidade_funcionalidades_informatica = '';
    $oDadosTurma->aee_ensino_lingua_portuguesa_modalidade_escrita    = '';
    $oDadosTurma->aee_estrategias_autonomia_ambiente_escolar         = '';

    if ( $oDadosTurma->tipo_atendimento == 4 ) {

      $oDadosTurma->etapa_ensino_turma = '';
      $oDadosTurma->modalidade_turma   = '';
    }

    if ( $oDadosTurma->modalidade_turma == 3 ) {
      $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
    }

    /**
     * Validamos o codigo do curso para educacao profissional
     * para censo 2014 foi removido a etapa 66
     */
    $aEtapas = array(30, 31, 32, 33, 34, 39, 40, 62, 63, 64);
    if ( !in_array($oDadosTurma->etapa_ensino_turma, $aEtapas) ) {
      $oDadosTurma->codigo_curso_educacao_profissional = '';
    }

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
    $iTurmaSemProfessor = 1;
    foreach ($this->getDisciplinasCenso() as $oDisciplinaCenso) {

      $oDadosTurma->{$oDisciplinaCenso->getCampoLayout()} = $this->oferereDisciplina($oDisciplinaCenso);

      if ($oDadosTurma->{$oDisciplinaCenso->getCampoLayout()} == 1) {
        $iTurmaSemProfessor = 0;
      }

      $aEtapasCensoTurma = array(1, 2, 3, 65);
      if ( in_array($oDadosTurma->etapa_ensino_turma, $aEtapasCensoTurma) || ( ($oDadosTurma->tipo_atendimento == 4 || $oDadosTurma->tipo_atendimento == 5) ) ) {

        $oDadosTurma->{$oDisciplinaCenso->getCampoLayout()}              = '';
        $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
      }

      $oDadosTurma->turma_sem_docente = $iTurmaSemProfessor;
    }

    $oDataCenso   = new DBDate( $this->dtDataCenso );
    $aEtapaEnsino = array('4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22',
                          '23','24','25','26','27','28','29','35','36','37','38','41');
    if ( !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) && $oDataCenso->getAno() != 2014 ) {
      $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
    }

    $oDadosTurma->lTurmaRegular = true;
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
    $oDaoRegenciaHorario = db_utils::getDao("dialetivo");
    $sWhere              = "ed04_i_escola = {$this->iCodigoEscola}";
    $sWhere             .= " and ed04_c_letivo = 'S'";
    $sSqlDiasDaSemana    = $oDaoRegenciaHorario->sql_query_file(null,
                                                                'ed04_i_diasemana',
                                                                null,
                                                                $sWhere
                                                               );

    $rsDiasDaSemana  = $oDaoRegenciaHorario->sql_record($sSqlDiasDaSemana);
    $aDias           = db_utils::getCollectionByRecord($rsDiasDaSemana);
    foreach ($aDias as $oDia) {
      $aDiasDaSemana[] = $oDia->ed04_i_diasemana;
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

      $oDisciplina = db_utils::fieldsMemory($rsDisciplinasCenso, $iDisciplina);
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
   * Verifica se a turma oferece a disciplina do censo.
   * @param DisciplinaCenso $oDisciplinaCenso Instancia da Disciplina do censo
   * @return integer retorna o valores 0 - nao oferece, 1  OFerecem sem Professor, 2 - Oferece com professor.
   */
  public function oferereDisciplina(DisciplinaCenso $oDisciplinaCenso) {

    $oDaoRegencia   = new cl_regencia();

    $sSqlTemProfessor  = "select 1 \n";
    $sSqlTemProfessor .= "  from regenciahorario \n";
    $sSqlTemProfessor .= "       inner join rechumanoescola on rechumanoescola.ed75_i_rechumano = regenciahorario.ed58_i_rechumano \n";
    $sSqlTemProfessor .= "       left  join docenteausencia on docenteausencia.ed321_rechumano  = regenciahorario.ed58_i_rechumano \n";
    $sSqlTemProfessor .= "  where ed58_i_regencia = ed59_i_codigo \n";
    $sSqlTemProfessor .= "    and ed58_ativo is true \n";
    $sSqlTemProfessor .= "    and ed75_d_ingresso <= '{$this->dtDataCenso}' \n";
    $sSqlTemProfessor .= "    and ( ed75_i_saidaescola is null or ed75_i_saidaescola > '{$this->dtDataCenso}' ) \n";
    $sSqlTemProfessor .= "    and (    ed321_inicio is null or ed321_inicio > '{$this->dtDataCenso}' \n";
    $sSqlTemProfessor .= "          or ( ed321_inicio < '{$this->dtDataCenso}' and ed321_final < '{$this->dtDataCenso}' ) ) \n";

    $sCampos            = "ed59_i_codigo, ";
    $sCampos           .= " exists ({$sSqlTemProfessor}) as tem_regente";
    $sWhere             = "ed265_i_codigo = {$oDisciplinaCenso->getDisciplina()} ";
    $sWhere            .= "and ed59_i_turma = {$this->getCodigoTurma()}";
    $sSqlDisciplina     = $oDaoRegencia->sql_query_censo(null, $sCampos, null, $sWhere);
    $rsDisciplina       = $oDaoRegencia->sql_record($sSqlDisciplina);
    $iOfereceDisciplina = 0;
    if ($oDaoRegencia->numrows > 0) {

      $iOfereceDisciplina = 2;
      for ( $iContador = 0; $iContador < $oDaoRegencia->numrows; $iContador++ ) {

        if (  db_utils::fieldsMemory($rsDisciplina, $iContador)->tem_regente == 't' ) {

          $iOfereceDisciplina = 1;
          break;
        }
      }
    }
    return $iOfereceDisciplina;
  }

  /**
   * Valida os dados do arquivo
   * @param IExportacaoCenso $oExportacaoCenso da Importacao do censo
   * @return boolean
   */
  public function validarDados(IExportacaoCenso $oExportacaoCenso) {

    $lDadosValidos = true;
    $aDadosDaTurma = $oExportacaoCenso->getDadosProcessadosTurma();
    $oDadosEscola  = $oExportacaoCenso->getDadosProcessadosEscola();
    $aDadosDocente = $oExportacaoCenso->getDadosProcessadosDocente();

    foreach ($aDadosDaTurma as $oDadosTurma) {

      /**
       * Valida se o código da turma Entidade/Escola foi preenchida pelo sistema
       */
      if ( empty($oDadosTurma->codigo_turma_entidade_escola) ) {

        $sMsgErro  = "Turma {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Código da Turma na Entidade/Escola não atribuído pelo próprio sistema do usuário migrador.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se o nome da turma contém mais de 80 caracteres
       */
      if ( strlen($oDadosTurma->nome_turma) > 80 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Nome da Turma deve conter no máximo 80 caracteres.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      if ( preg_match ('/\s{2,}/',  $oDadosTurma->nome_turma) == 1) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Nome da turma contém excesso de espaços.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      if (strlen($oDadosTurma->nome_turma) < 4) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Nome da turma deve conter no mínimo 4 caracteres.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se a hora do horario inicial da turma possui 2 dígitos
       */
      if ( strlen($oDadosTurma->horario_turma_horario_inicial_hora) != 2 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "A hora do horário inicial da turma deve conter 2 dígitos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se a hora do horário inicial esta entre 0 e 23
       */
      if ( $oDadosTurma->horario_turma_horario_inicial_hora < 0 ||
           $oDadosTurma->horario_turma_horario_inicial_hora > 23 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "A hora do horário inicial da turma deve ser maior que 0 e menor que 24";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se os minutos do horario inicial da turma possui 2 dígitos
       */
      if ( strlen($oDadosTurma->horario_turma_horario_inicial_minuto) != 2 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Os minutos do horário inicial da turma devem conter 2 dígitos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se os minutos do horario inicial estão entre 0 e 55;
       * Verifica se os minutos não estão entre os seguintes números:
       *   00 - 05 - 10 - 15 - 20 - 25 -30 - 35 - 40 - 45 - 50 - 55
       */
      if ( $oDadosTurma->horario_turma_horario_inicial_minuto < 0  ||
           $oDadosTurma->horario_turma_horario_inicial_minuto > 55 ||
           ( $oDadosTurma->horario_turma_horario_inicial_minuto % 5 != 0 ) ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Os minutos do horário inicial da turma deve conter um dos seguintes valores: ";
        $sMsgErro .= "00 - 05 - 10 - 15 - 20 - 25 -30 - 35 - 40 - 45 - 50 - 55.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se a hora do horario final da turma possui 2 dígitos
       */
      if ( strlen($oDadosTurma->horario_turma_horario_final_hora) != 2 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "A hora do horário final da turma deve conter 2 dígitos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se a hora do horário final esta entre 0 e 23
       */
      if ( $oDadosTurma->horario_turma_horario_final_hora < 0 ||
           $oDadosTurma->horario_turma_horario_final_hora > 23 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "A hora do horário final da turma deve ser maior que 0 e menor que 24";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se os minutos do horario final da turma possui 2 dígitos
       */
      if ( strlen($oDadosTurma->horario_turma_horario_final_minuto) != 2 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Os minutos do horário final da turma devem conter 2 dígitos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida se os minutos do horario final estão entre 0 e 55;
       * Verifica se os minutos não estão entre os seguintes números:
       *   00 - 05 - 10 - 15 - 20 - 25 -30 - 35 - 40 - 45 - 50 - 55
       */
      if ( $oDadosTurma->horario_turma_horario_final_minuto < 0  ||
           $oDadosTurma->horario_turma_horario_final_minuto > 55 ||
           ( $oDadosTurma->horario_turma_horario_final_minuto % 5 != 0 ) ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Os minutos do horário final da turma deve conter um dos seguintes valores: ";
        $sMsgErro .= "00 - 05 - 10 - 15 - 20 - 25 -30 - 35 - 40 - 45 - 50 - 55.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      $sHorarioInicial = "{$oDadosTurma->horario_turma_horario_inicial_hora}:$oDadosTurma->horario_turma_horario_inicial_minuto";
      $sHorarioFinal   = "{$oDadosTurma->horario_turma_horario_final_hora}:$oDadosTurma->horario_turma_horario_final_minuto";

      /**
       * Valida se o Horario inicial da turma é igual ou maior do que o horário final da turma
       */
      if ( strtotime($sHorarioInicial) >= strtotime($sHorarioFinal) ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "O horário inicial da turma não pode ser maior ou igual ao horário final.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      if ($oDadosTurma->dia_semana_domingo == 0 &&
          $oDadosTurma->dia_semana_segunda == 0 &&
          $oDadosTurma->dia_semana_terca   == 0 &&
          $oDadosTurma->dia_semana_quarta  == 0 &&
          $oDadosTurma->dia_semana_quinta  == 0 &&
          $oDadosTurma->dia_semana_sexta   == 0 &&
          $oDadosTurma->dia_semana_sabado  == 0) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Deve ser selecionado pelo menos um campo em Dias da Semana";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Validações que serão executadas caso o tipo de atendimento seja igual a 5
       * 5 - Atendimento Educacional Especializado (AEE)
       */
      if ($oDadosTurma->tipo_atendimento == 5) {

        if ($oDadosTurma->aee_ensino_sistema_braille 												 == 0 &&
            $oDadosTurma->aee_ensino_uso_recursos_opticos_nao_opticos 			 == 0 &&
            $oDadosTurma->aee_estrategias_desenvolvimento_processos_mentais  == 0 &&
            $oDadosTurma->aee_tecnicas_orientacao_mobilidade 								 == 0 &&
            $oDadosTurma->aee_ensino_lingua_brasileira_sinais_libras 				 == 0 &&
            $oDadosTurma->aee_ensino_comunicacao_alternativa_aumentativa 		 == 0 &&
            $oDadosTurma->aee_estrategia_enriquecimento_curricular 					 == 0 &&
            $oDadosTurma->aee_ensino_uso_soroban 														 == 0 &&
            $oDadosTurma->aee_ensino_usabilidade_funcionalidades_informatica == 0 &&
            $oDadosTurma->aee_ensino_lingua_portuguesa_modalidade_escrita 	 == 0 &&
            $oDadosTurma->aee_estrategias_autonomia_ambiente_escolar 				 == 0) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} é AEE. ";
          $sMsgErro .= "Deve ser selecionado pelo menos uma atividades de atendimento educacional especializado.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }

      if ($oDadosTurma->tipo_atendimento == 4) {

        if ($oDadosTurma->quantidade_de_atividades_na_turma > 6) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}. ";
          $sMsgErro .= "Possui mais que 6(seis) atividades complementares.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }
      if ( ($oDadosTurma->tipo_atendimento == 2 || $oDadosTurma->tipo_atendimento == 3) &&
              in_array($oDadosTurma->etapa_ensino_turma, array( 1, 2, 3, 56 ) ) ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} é de Ensino Regular. ";
        $sMsgErro .= "Turmas que possuem atendimento do tipo: Unidade de Atendimento Socioeducativo ou Unidade Prisional ";
        $sMsgErro .= "não podem ter informado as seguintes etapas: \n";
        $sMsgErro .= " - EDUCACAO INFANTIL E ENSINO FUNDAMENTAL MULTIETAPA;\n";
        $sMsgErro .= " - EDUCACAO INFANTIL - CRECHE (0 A 3 ANOS);\n";
        $sMsgErro .= " - EDUCACAO INFANTIL - PRE ESCOLA (4 E 5 ANOS);\n";
        $sMsgErro .= " - EDUCACAO INFANTIL - UNIFICADA (0 A 5 ANOS).\n";

        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Validações que serão executadas caso o tipo de atendimento seja diferente de 4 ou 5
       * 4 - Atividade Complementar
       * 5 - Atendimento Educacional Especializado (AEE)
       */
      if ( !in_array($oDadosTurma->tipo_atendimento, array(4, 5) ) ) {

        if ($oDadosTurma->tipo_atendimento == 1) {

          if ($oDadosEscola->registro10->modalidade_ensino_regular != 1) {

            $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} é de Ensino Regular. ";
            $sMsgErro .= "Modalidade da turma deve ser informado como Ensino Regular.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
            $lDadosValidos = false;
          }

          if ($oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov == 1) {

            $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} é de Ensino Regular. ";
            $sMsgErro .= "Turmas de de classe hospitalar, não podem participar do programa Mais Educação.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
            $lDadosValidos = false;
          }
        }

        if ($oDadosTurma->tipo_atendimento == 2 &&
            $this->oDadosEscola->modalidade_educacao_especial_modalidade_substutiva != 1) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "O campo Modalidade deve ser informado como Educação especial - modalidade substutiva";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        if ($oDadosTurma->tipo_atendimento == 3 && $oDadosEscola->registro10->modalidade_educacao_jovens_adultos != 1) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Turmas atendidas em Unidade Prisional devem ter o campo \"Tipo\" informado como Educação de jovens e adultos.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }

      /**
       * Validamos relacionadas ao programa mais educacao
       */
      $aPrimeiraCondicaoMaisEducacao = array(0, 2, 3);
      $aEtapasTurma                  = array( range(4,38) );

      /**
       * As etapas abaixo correspondem as etapas do censo, e que nao podem participar do programa mais educacao
       */
      $aEtapasNaoPermitidas = array(1, 2, 3, 43, 44, 45, 46, 47, 48, 51, 58, 60, 61, 62, 63, 66);

      if ( $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov != '' ) {

	      if ( $oDadosTurma->tipo_atendimento == 1 || $oDadosTurma->tipo_atendimento == 5 ) {

	      	$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
	      	$sMsgErro .= "Turma não pode ser participante do programa mais educação, pois foi informado como tipo de ";
	      	$sMsgErro .= "Atendimento Classe Hospitalar ou Atendimento Educacional Especializado";
	      	$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
	      	$lDadosValidos = false;
	      }

      	if ( in_array($oDadosTurma->tipo_atendimento, $aPrimeiraCondicaoMaisEducacao) &&
      			 ( $oDadosTurma->modalidade_turma != 1 && $oDadosTurma->modalidade_turma != 2 )
      		 ) {


      		if ( !in_array( $oDadosTurma->etapa_ensino_turma, $aEtapasTurma ) &&
      				 $oDadosTurma->etapa_ensino_turma != 41 &&
      				 $oDadosTurma->etapa_ensino_turma != 56
      			 ) {

	      		$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
	      		$sMsgErro .= "Modalidade e/ou etapa não compatível com o tipo de atendimento selecionado para a turma: ";
	      		$sMsgErro .= "Não se Aplica, Unidade de Internação Socioeducativa ou Unidade Prisional.";
	      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
	      		$lDadosValidos = false;
      		}
      	}

      	if( $oDadosTurma->tipo_atendimento == 4){

          if ( $oDadosTurma->codigo_tipo_atividade_complementar_1 == '' &&
               $oDadosTurma->codigo_tipo_atividade_complementar_2 == '' &&
               $oDadosTurma->codigo_tipo_atividade_complementar_3 == '' &&
               $oDadosTurma->codigo_tipo_atividade_complementar_4 == '' &&
               $oDadosTurma->codigo_tipo_atividade_complementar_5 == '' &&
               $oDadosTurma->codigo_tipo_atividade_complementar_6 == '' ) {

            $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
            $sMsgErro .= "Ao menos um tipo de atividade deve ser preenchido quando turma for de Atividade Complementar.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
            $lDadosValidos = false;
          }

      		$aAtividades = array( $oDadosTurma->codigo_tipo_atividade_complementar_1,
      								 				  $oDadosTurma->codigo_tipo_atividade_complementar_2,
      												  $oDadosTurma->codigo_tipo_atividade_complementar_3,
									      			  $oDadosTurma->codigo_tipo_atividade_complementar_4,
									      			  $oDadosTurma->codigo_tipo_atividade_complementar_5,
									      			  $oDadosTurma->codigo_tipo_atividade_complementar_6
      											  );

     			if(!parent::VerificaDuplicidade($aAtividades)) {

     				$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
     				$sMsgErro .= "Turma já possui Atividade Complementar cadastrada com o mesmo código informado. ";
     				$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
     				$lDadosValidos = false;
      		}
      	}

      	if ( $oDadosTurma->tipo_atendimento    == 4 &&
      			 $oDadosTurma->modalidade_turma   != '' &&
      			 $oDadosTurma->etapa_ensino_turma != ''
      		 ) {

      		$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
      		$sMsgErro .= "Não deve ser informada a modalidade e etapa de ensino da turma, pois foi selecionado como ";
      		$sMsgErro .= "tipo de atendimento Atividade Complementar";
      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      		$lDadosValidos = false;
      	}

      	if ( $oDadosTurma->modalidade_turma == 3 ||
      			 (
      			 		$oDadosTurma->modalidade_turma == 1 && $oDadosTurma->modalidade_turma == 2 &&
      			 		!in_array( $oDadosTurma->etapa_ensino_turma, $aEtapasTurma ) &&
      			 		$oDadosTurma->etapa_ensino_turma != 41 &&
      			 		$oDadosTurma->etapa_ensino_turma != 56
      			 )
      		 ) {

      		$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
      		$sMsgErro .= "Turma não deve ser participante do Programa Mais Educação, pois há inconsistências em algum dos ";
      		$sMsgErro .= "seguintes dados: Modalidade da Turma selecionada como EJA; Modalidade de Ensino Regular ou ";
      		$sMsgErro .= "Ensino Especial, porém com etapa não equivalente a modalidade selecionada.";
      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      		$lDadosValidos = false;
      	}

      	if ( in_array($oDadosTurma->etapa_ensino_turma, $aEtapasNaoPermitidas) ) {

      		$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
      		$sMsgErro .= "Não deve ser informado o campo Mais Educação para turmas de Educação Infantil, EJA ou ";
      		$sMsgErro .= "Atendimento Educacional Especializado.";
      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      		$lDadosValidos = false;
      	}
      }

      $iTotalProfessores       = 0;
      $lPossuiInterpreteLibras = false;
      foreach ($aDadosDocente as $oDocente) {
        foreach ($oDocente->registro51 as $oDadosDocencia) {

          if ($oDadosDocencia->codigo_turma_entidade_escola == $oDadosTurma->codigo_turma_entidade_escola) {
            if ($oDadosDocencia->funcao_exerce_escola_turma == 4) {

              $lPossuiInterpreteLibras = true;
              continue;
            }
            $iTotalProfessores++;
          }
        }
      }

      if ($lPossuiInterpreteLibras && $iTotalProfessores == 0) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Possui apenas Tradutor de libras informado. Deverá informado no mínimo um docente.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;

  }

  /**
   * @param int $iTurmaCenso
   */
  public function setCodigoTurmaCenso ($iTurmaCenso) {
    $this->iTurmaCenso = $iTurmaCenso;
  }

  /**
   * @param string $sTurmaCenso
   */
  public function setNomeTurmaCenso ($sTurmaCenso) {
    $this->sTurmaCenso = $sTurmaCenso;
  }

  /**
   * @param int $iEtapaTurmaCenso
   */
  public function setEtapaTurmaCenso ($iEtapaTurmaCenso) {
    $this->iEtapaTurmaCenso = $iEtapaTurmaCenso;
  }

  public function setTurmaUnificada($lTurmaUnificada = false) {
    $this->lTurmaUnificada = $lTurmaUnificada;
  }
}

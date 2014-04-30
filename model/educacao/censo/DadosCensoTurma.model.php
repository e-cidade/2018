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


class DadosCensoTurma extends DadosCenso {

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
    $this->iTurma     = $iCodigoTurma;
  }

  public function getDados() {

    $oDaoTurma     = new cl_turma();
    $sCamposTurma  = " ed57_i_codigoinep as codigo_turma_inep, 													";
    $sCamposTurma .= " ed57_i_codigo as codigo_turma_entidade_escola, 									";
    $sCamposTurma .= " ed57_i_escola, 																									";
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
    $sCamposTurma .= " ed57_i_censoetapa as etapa_ensino_turma, 												";
    $sCamposTurma .= " ed57_i_tipoturma as modalidade_turma,														";
    $sCamposTurma .= " ed57_censoprogramamaiseducacao as turma_participante_mais_educacao_ensino_medio_inov ";
    $sSqlTurma     = $oDaoTurma->sql_query($this->getCodigoTurma(), $sCamposTurma);
    $rsDadosTurma  = $oDaoTurma->sql_record($sSqlTurma);
    if ($oDaoTurma->numrows == 0) {
      throw new Exception('não existe turma com os dados informados');
    }
    $oDadosTurma                                       = db_utils::fieldsMemory($rsDadosTurma, 0);
    $oDadosTurma->nome_turma                           = $this->removeCaracteres(trim($oDadosTurma->nome_turma), 4);
    $oDadosTurma->horario_turma_horario_inicial_hora   = substr($oDadosTurma->horario_inicial, 0, 2);
    $oDadosTurma->horario_turma_horario_inicial_minuto = substr($oDadosTurma->horario_inicial, 3, 2);
    $oDadosTurma->horario_turma_horario_final_hora     = substr($oDadosTurma->horario_final, 0, 2);
    $oDadosTurma->horario_turma_horario_final_minuto   = substr($oDadosTurma->horario_final, 3, 2);
    switch ($oDadosTurma->modalidade_turma) {

      case 1:
      case 7:

        $oDadosTurma->modalidade_turma = 1;
        break;

      case 2:

        $oDadosTurma->modalidade_turma = 3;
        break;

      case 3:

        $oDadosTurma->modalidade_turma = 2;
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
     */
    $aEtapas = array(30, 31, 32, 33, 34, 39, 40, 62, 63, 64, 66);
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

      $aEtapasCensoTurma = array(1, 2, 3, 65, 66);
      if ( in_array($oDadosTurma->etapa_ensino_turma, $aEtapasCensoTurma) || ( ($oDadosTurma->tipo_atendimento == 4 || $oDadosTurma->tipo_atendimento == 5) ) ) {
      	$oDadosTurma->{$oDisciplinaCenso->getCampoLayout()} 						 = '';
      	$oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
      }
      $oDadosTurma->turma_sem_docente = $iTurmaSemProfessor;
    }
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
   * Verifica se a turma oferece a disciplina do censo.
   * @param DisciplinaCenso $oDisciplinaCenso Instancia da Disciplina do censo
   * @return retornoa o valores 0 - nao oferece, 1  OFerecem sem Professor, 2 - Oferece com professor.
   */
  public function oferereDisciplina(DisciplinaCenso $oDisciplinaCenso) {

    $oDaoRegencia   = db_utils::getDao("regencia");

    $sSqlTemProfessor   = "(select 1 ";
    $sSqlTemProfessor  .= "   from regenciahorario ";
    $sSqlTemProfessor  .= "  where ed58_i_regencia = ed59_i_codigo and ed58_ativo is true)";

    $sCampos            = "ed59_i_codigo, ";
    $sCampos           .= " exists ({$sSqlTemProfessor}) as tem_regente";
    $sWhere             = "ed265_i_codigo = {$oDisciplinaCenso->getDisciplina()} ";
    $sWhere            .= "and ed59_i_turma = {$this->getCodigoTurma()}";
    $sSqlDisciplina     = $oDaoRegencia->sql_query_censo(null, $sCampos, null, $sWhere);
    $rsDisciplina       = $oDaoRegencia->sql_record($sSqlDisciplina);
    $iOfereceDisciplina = 0;
    if ($oDaoRegencia->numrows > 0) {

      $iOfereceDisciplina = 2;
      if (db_utils::fieldsMemory($rsDisciplina, 0)->tem_regente == 't') {
        $iOfereceDisciplina = 1;
      }
    }
    return $iOfereceDisciplina;
  }

  /**
   * Valida os dados do arquivo
   * @param $oExportacaoCenso instancia da Importacao do censo
   * @return boolean
   */
  public function validarDados(ExportacaoCenso2013 $oExportacaoCenso) {

    $lDadosValidos = true;
    $aDadosDaTurma = $oExportacaoCenso->getDadosProcessadosTurma();
    $oDadosEscola  = $oExportacaoCenso->getDadosProcessadosEscola();

    foreach ($aDadosDaTurma as $oDadosTurma) {

      if (strlen($oDadosTurma->nome_turma) < 4) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Nome da turma deve conter no mínimo 4 caracteres.";
        $oExportacaoCenso->logErro($sMsgErro);
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
        $oExportacaoCenso->logErro($sMsgErro);
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
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      /**
       * Validações que serão executadas caso o tipo de atendimento seja diferente de 4 ou 5
       * 4 - Atividade Complementar
       * 5 - Atendimento Educacional Especializado (AEE)
       */
      if ($oDadosTurma->tipo_atendimento != 4 || $oDadosTurma->tipo_atendimento != 5) {

        if ($oDadosTurma->tipo_atendimento == 1 && $oDadosEscola->registro10->modalidade_ensino_regular != 1) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} é de Ensino Regular. ";
          $sMsgErro .= "Modalidade da turma deve ser setado como Ensino Regular.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }

        if ($oDadosTurma->tipo_atendimento == 2 &&
            $this->oDadosEscola->modalidade_educacao_especial_modalidade_substutiva != 1) {

            $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
            $sMsgErro .= "O campo Modalidade deve ser setado como Educação especial - modalidade substutiva";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
        }

        if ($oDadosTurma->tipo_atendimento == 3 && $oDadosEscola->modalidade_educacao_jovensadultos != 1) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "O campo Modalidade deve ser setado como Educação de jovens e adultos.";
          $oExportacaoCenso->logErro($sMsgErro);
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
	      	$oExportacaoCenso->logErro($sMsgErro);
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
	      		$oExportacaoCenso->logErro($sMsgErro);
	      		$lDadosValidos = false;
      		}
      	}

      	if( $oDadosTurma->tipo_atendimento == 4){

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
     				$oExportacaoCenso->logErro($sMsgErro);
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
      		$oExportacaoCenso->logErro($sMsgErro);
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
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}

      	if ( in_array($oDadosTurma->etapa_ensino_turma, $aEtapasNaoPermitidas) ) {

      		$sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
      		$sMsgErro .= "Não deve ser informado o campo Mais Educação para turmas de Educação Infantil, EJA ou ";
      		$sMsgErro .= "Atendimento Educacional Especializado.";
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}
      }
    }
    return $lDadosValidos;
  }
}
?>
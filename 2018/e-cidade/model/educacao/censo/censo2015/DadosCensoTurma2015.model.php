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


class DadosCensoTurma2015 extends DadosCensoTurma {

  /**
   * Etapas e disciplinas do censo
   * @var array
   */
  protected static $aEtapasDisciplinas = array();

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
    $aTurmaSemProfissional  = array();


    /**
     * Busca todas as turmas que possuem vinculo com docente
     */
    foreach ( $aDadosDocente as $aRegistros ) {

      foreach ( $aRegistros->registro51 as $oRegistroDocente ) {
        array_push($aTurmaSemProfissional, $oRegistroDocente->codigo_turma_entidade_escola);
      }
    }

    if ( count($aDadosDaTurma) == 0 ) {

      $sMsgErro  = "Nenhuma turma válida. ";
      $sMsgErro .= "Confira se existem alunos matrículados com data de matrícula anterior a data do Censo.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    $aStatusValidacao = array();
    foreach ($aDadosDaTurma as $oDadosTurma) {

      $sMsgTurma = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
      /**
       * Validado se o codigo INEP da turma esta vazio
       */
      if ( $oDadosTurma->codigo_turma_inep !== '' ) {

        $sMsgErro  = "Turma {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Código da Turma INEP deve estar vazio.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }
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
       * Valida se o Código da Turma na Entidade/Escola contém mais de 20 caracteres
       */
      if ( strlen($oDadosTurma->codigo_turma_entidade_escola) > 20 ) {

        $sMsgErro  = "Turma {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Código da Turma na Entidade/Escola deve conter no máximo 20 caracteres.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }


      /**
       * Valida se o nome da turma foi informado
       */
      if ( empty($oDadosTurma->nome_turma) ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Nome da Turma é um campo obrigatório.";
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

      /**
       * Valida para aceitar somente os caracteres (ABCDEFGHIJKLMNOPQRSTUWXYZ 0123456789ªº-)
       */
      if ( preg_match ('/[^a-z0-9ªº\s\-]+/i',  $oDadosTurma->nome_turma) == 1 ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Nome da turma contém excesso de espaços.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      /**
       * Valida obrigatoriedade do campo mediação didático-pedagógica
       */
      if ( empty($oDadosTurma->mediacao_didatico_pedagogica) ) {

        $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
        $sMsgErro .= "Tipo de mediação didático-pedagógica não informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }

      $aValidacaoDidaticoComum = array(
        'Horário da Turma - Horário Inicial - Hora'   => $oDadosTurma->horario_turma_horario_inicial_hora,
        'Horário da Turma - Horário Inicial - Minuto' => $oDadosTurma->horario_turma_horario_inicial_minuto,
        'Horário da Turma - Horário Final - Hora'     => $oDadosTurma->horario_turma_horario_final_hora,
        'Horário da Turma - Horário Final - Minuto'   => $oDadosTurma->horario_turma_horario_final_minuto,
        'Domigo'                                      => $oDadosTurma->dia_semana_domingo,
        'Segunda-feira'                               => $oDadosTurma->dia_semana_segunda,
        'Terça-feira'                                 => $oDadosTurma->dia_semana_terca,
        'Quarta-feira'                                => $oDadosTurma->dia_semana_quarta,
        'Quinta-feira'                                => $oDadosTurma->dia_semana_quinta,
        'Sexta-feira'                                 => $oDadosTurma->dia_semana_sexta,
        'Sábado'                                      => $oDadosTurma->dia_semana_sabado
      );

      $oDaoTurmaAcMatricula = new cl_turmaacmatricula();
      $sMensagemErro = "";

      foreach ( $aValidacaoDidaticoComum as $sDescricao => $sValor ) {

        /**
         * Valida se os campos 7 ao 17 estão todos preenchidos
         */
        if ( $oDadosTurma->mediacao_didatico_pedagogica == 1 ) {

          if ( $sValor === "" ) {

            $sMensagemErro .= "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
            $sMensagemErro .= "Campo {$sDescricao} não pode ser vazio.";
            $lDadosValidos = false;
          }
        } else {

        /**
         * Valida se os campos 7 ao 17 estão todos vazios
         */
          if ( $sValor !== "" ) {

            $sMensagemErro .= "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
            $sMensagemErro .= "Campo {$sDescricao} deve ser vazio.";
            $lDadosValidos = false;
          }
        }
      }

      if ( !empty($sMensagemErro) ) {
        $oExportacaoCenso->logErro($sMensagemErro, ExportacaoCensoBase::LOG_TURMA);
      }

      if ( $oDadosTurma->mediacao_didatico_pedagogica == 2 ) {

        /**
         * Valida se o Tipo de Atendimento informado está dentro dos valores:
         *   0 - Não se aplica
         *   1 - Classe hospitalar
         *   2 - Unidade de internação socioeducativa
         *   3 - Unidade prisional
         */
        $aValorTipoAtendimento = array('0','1','2','3');
        if ( !in_array($oDadosTurma->tipo_atendimento, $aValorTipoAtendimento) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Tipo de atendimento deve conter um dos seguintes valores: Não se aplica, Classe hospitalar, ";
          $sMsgErro .= "Unidade de internação socioeducativa ou Unidade prisional.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        /**
         * Valida se a Etapa de Ensino informado está dentro dos valores: 69, 70, 71 e 72
         */
        $aEtapaEnsino = array('69','70','71','72');
        if ( !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Valor inválido para a etapa de ensino.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }




      if ( $oDadosTurma->mediacao_didatico_pedagogica == 3 ) {

        /**
         * Valida se o Tipo de Atendimento informado está dentro dos valores:
         *   0 - Não se aplica
         *   1 - Classe hospitalar
         *   2 - Unidade de internação socioeducativa
         *   3 - Unidade prisional
         */
        $aValorTipoAtendimento = array('0','1','2','3');
        if ( !in_array($oDadosTurma->tipo_atendimento, $aValorTipoAtendimento) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Tipo de atendimento deve conter um dos seguintes valores: Não se aplica, Classe hospitalar, ";
          $sMsgErro .= "Unidade de internação socioeducativa ou Unidade prisional.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        /**
         * Valida se a Modalidade de Ensino informado está dentro dos valores:
         *   1 - Ensino Regular
         *   3 - Educação de Jovens e Adultos (EJA)
         * @var array
         */
        $aModalidadeEnsino = array('1','3');
        if ( !in_array($oDadosTurma->modalidade_turma, $aModalidadeEnsino) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Modalidade de ensino deve conter um dos seguintes valores: Ensino Regular ou Educação de Jovens e Adultos (EJA).";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        /**
         * Valida se a Etapa de Ensino informado está dentro dos valores: '30','31','32','33','34','35','36','37',
         * '38','39','40','44','45','60','62','64','67','68'
         */
        $aEtapaEnsino = array('30','31','32','33','34','35','36','37','38','39','40','44','45','60','62','64','67','68');
        if ( !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Valor inválido para a etapa de ensino.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }

      /**
       * Validações executadas quando a mediação didatico pedagogica for igual a 1 - Presencial
       */
      if ( $oDadosTurma->mediacao_didatico_pedagogica == 1 ) {

        /**
         * Valida os minutos das horas iniciais e finais.
         */
        $aMinutosValidos = array('00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');

        if ( !in_array($oDadosTurma->horario_turma_horario_inicial_minuto, $aMinutosValidos) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Valor inválido para minuto inicial da turma.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        if ( !in_array($oDadosTurma->horario_turma_horario_final_minuto, $aMinutosValidos) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Valor inválido para minuto final da turma.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        /**
         * Valida se ao menos um dia da semana foi informado.
         * @var array
         */
        $aDiasSemana = array(
          $oDadosTurma->dia_semana_domingo,
          $oDadosTurma->dia_semana_segunda,
          $oDadosTurma->dia_semana_terca,
          $oDadosTurma->dia_semana_quarta,
          $oDadosTurma->dia_semana_quinta,
          $oDadosTurma->dia_semana_sexta,
          $oDadosTurma->dia_semana_sabado
        );

        if ( !in_array(1, $aDiasSemana) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Ao menos um dia da semana deve ser informado.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        /**
         * Verifica se o campo dependencia administrativa é igual a Estadual ou Municipal
         */
        if (   $oDadosEscola->registro00->dependencia_administrativa != 2
            && $oDadosEscola->registro00->dependencia_administrativa != 3
           ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "O campo Dependência Administrativa deve ser igual a Estadual ou Municipal.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

      } // fim if $oDadosTurma->mediacao_didatico_pedagogica == 1

     /**
      * Validações referente ao Código do Tipo de Atividade Complementar
      */
      $aTipoAtividade = array(
        $oDadosTurma->codigo_tipo_atividade_complementar_1,
        $oDadosTurma->codigo_tipo_atividade_complementar_2,
        $oDadosTurma->codigo_tipo_atividade_complementar_3,
        $oDadosTurma->codigo_tipo_atividade_complementar_4,
        $oDadosTurma->codigo_tipo_atividade_complementar_5,
        $oDadosTurma->codigo_tipo_atividade_complementar_6
      );

      $aTipoAtividade = array_filter($aTipoAtividade);

      if ( $oDadosTurma->tipo_atendimento == 4 ) {

        if ( empty($aTipoAtividade) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Ao menos um código do Tipo de Atividade deve ser informado quando Tipo de Atendimento for igual a Atividade Complementar.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        } else {

          $aTipoAtividade = array_count_values($aTipoAtividade);
          $sMensagemErro  = '';

          foreach ( $aTipoAtividade as $iCodigoAtividade => $iOcorrencia ) {

            if ( $iOcorrencia > 1 ) {

              $sMensagemErro .= "Código da Atividade {$iCodigoAtividade} informado {$iOcorrencia} vezes.";
              $lDadosValidos = false;
            }
          }
          if ( !empty($sMensagemErro) ) {
            $oExportacaoCenso->logErro($sMensagemErro, ExportacaoCensoBase::LOG_TURMA);
          }
        }
      } else {

        if ( !empty( $aTipoAtividade ) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Campo código do Tipo de Atividade não deve ser informado quando Tipo de Atendimento for diferente de Atividade Complementar.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }

      if ( $oDadosTurma->tipo_atendimento == 4 || $oDadosTurma->tipo_atendimento == 5 ) {

        $sWhereTurmaAcMatricula  = " ed269_i_turmaac = {$oDadosTurma->codigo_turma_entidade_escola} ";
        $sWhereTurmaAcMatricula .= " AND ed269_d_data <= '{$oExportacaoCenso->getDataCenso()}' ";
        $sSqlTurmaAcMatricula    = $oDaoTurmaAcMatricula->sql_query("","1","ed268_c_descr",$sWhereTurmaAcMatricula);
        $sRsTurmaAcMatricula     = $oDaoTurmaAcMatricula->sql_record($sSqlTurmaAcMatricula);

        if ( $oDaoTurmaAcMatricula->numrows == 0 ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= " Não possui nenhum aluno matriculado. ";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }
      /**
       * Validações referentes a Atividade do Atendimento Educacional Especializado (AEE)
       */
      if ( $oDadosTurma->tipo_atendimento == 5 ) {

        $aAtividadeEducacionalEspecializada = array(
          $oDadosTurma->aee_ensino_sistema_braille,
          $oDadosTurma->aee_ensino_uso_recursos_opticos_nao_opticos,
          $oDadosTurma->aee_estrategias_desenvolvimento_processos_mentais,
          $oDadosTurma->aee_tecnicas_orientacao_mobilidade,
          $oDadosTurma->aee_ensino_lingua_brasileira_sinais_libras,
          $oDadosTurma->aee_ensino_comunicacao_alternativa_aumentativa,
          $oDadosTurma->aee_estrategia_enriquecimento_curricular,
          $oDadosTurma->aee_ensino_uso_soroban,
          $oDadosTurma->aee_ensino_usabilidade_funcionalidades_informatica,
          $oDadosTurma->aee_ensino_lingua_portuguesa_modalidade_escrita,
          $oDadosTurma->aee_estrategias_autonomia_ambiente_escolar
        );

        if ( !in_array(1, $aAtividadeEducacionalEspecializada) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Ao menos uma Atividade Educacional Especializada (AEE) deve ser informada.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }

      /**
       * Valida se a modalidade da turma é compatível com a modalidade da escola
       */
      if( $oDadosTurma->tipo_atendimento != 4 && $oDadosTurma->tipo_atendimento != 5 ) {

        if (   ($oDadosTurma->modalidade_turma == 1 && $oDadosEscola->registro10->modalidade_ensino_regular != 1)
            || ($oDadosTurma->modalidade_turma == 2 && $oDadosEscola->registro10->modalidade_educacao_especial_modalidade_substutiva != 1)
            || ($oDadosTurma->modalidade_turma == 3 && $oDadosEscola->registro10->modalidade_educacao_jovens_adultos != 1)
            || ($oDadosTurma->modalidade_turma == 4 && $oDadosEscola->registro10->modalidade_educacao_profissional != 1)
           ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Modalidade da Turma deve ser compatível com a Modalidade da Escola.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }

        /**
         * Valida se a etapa de ensino corresponde ao tipo de atendimento especifico.
         */
        if ( $oDadosTurma->tipo_atendimento == 1 ) {

          $aEtapaEnsino = array( '1', '2', '3', '56' );

          if ( in_array( $oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) ) {

            $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
            $sMsgErro .= "Etapa de Ensino inválida para o Tipo de Atendimento informado.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
            $lDadosValidos = false;
          }
        }
      }

      /**
       * Valida se o codigo do curso tecnico inormado corresponde a etapa de ensino correta.
       */
      if ( !empty( $oDadosTurma->codigo_curso_educacao_profissional ) ) {

        $aEtapaEnsino = array( '30', '31', '32', '33', '34', '39', '40', '64', '74' );
        if ( !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) ) {

          $sMsgErro  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
          $sMsgErro .= "Código do Curso Técnico inválido para a Etapa de Ensino informada.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
          $lDadosValidos = false;
        }
      }

      /**
       * Novas validações... censo 2016
       */
      $aStatusValidacao[] = self::validarRegistro20Coluna18($oExportacaoCenso, $oDadosTurma, $oDadosEscola, $aDadosDocente);
      $aStatusValidacao[] = self::validarRegistro20Coluna19($oExportacaoCenso, $oDadosTurma, $oDadosEscola);
      $aStatusValidacao[] = self::validarRegistro20Coluna37($oExportacaoCenso, $oDadosTurma, $oDadosEscola);
      $aStatusValidacao[] = self::validarRegistro20Coluna38($oExportacaoCenso, $oDadosTurma, $oDadosEscola, $aDadosDocente);
      $aStatusValidacao[] = self::validarRegistro20Coluna40a65($oExportacaoCenso, $oDadosTurma, $oDadosEscola);
    }

    if ( $lDadosValidos ) {
      $lDadosValidos = array_reduce( $aStatusValidacao, 'validaVerdadeiro');
    }

    return $lDadosValidos;
  }

  /**
   * Validações da coluna Tipo de Atendimento
   *
   * Valores Possiveis para Tipo de Atendimento:
   *   0 - Não se aplica
   *   1 - Classe hospitalar
   *   2 - Unidade de internação socioeducativa
   *   3 - Unidade prisional
   *   4 - Atividade complementar
   *   5 - Atendimento Educacional Especializado (AEE)
   *
   * @param  IExportacaoCenso $oExportacaoCenso   Dados da exportação
   * @param  stdClass         $oDadosTurma        Dados da turma
   * @param  stdClass         $oDadosEscola       Registros da escola (00 e 10)
   * @param  array            $aDadosDocente      Todos docentes e seus registros (30, 40, 50 e 51)
   * @return boolean
   */
  protected static function validarRegistro20Coluna18($oExportacaoCenso, $oDadosTurma, $oDadosEscola, $aDadosDocente) {

    $sMsgTurma     = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
    $lDadosValidos = true;
    /**
     * Validado coluna 18 do registro 20 Regra 3
     */
    if ( $oDadosEscola->registro10->local_funcionamento_escola_predio_escolar          == 1 &&
         $oDadosEscola->registro10->local_funcionamento_escola_casa_professor          == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_galpao_rancho_paiol_bar == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_outros                  == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_salas_empresas          == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_salas_outras_escolas    == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_templo_igreja           == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_unidade_prisional       == 0 &&
         $oDadosEscola->registro10->local_funcionamento_escola_un_internacao_socio     == 0 &&
         !in_array($oDadosTurma->tipo_atendimento, array(0, 4, 5)) ) {

      $sMsgErro  = "{$sMsgTurma} Quando Local de funcionamento for Prédio Escolar, os Tipos de atendimentos devem ser ";
      $sMsgErro .= "Não se aplica, Atividade complementar, Atendimento Educacional Especializado (AEE)";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    /**
     * Valida se a escola fornece Atividade Complementar e se há turmas informadas com este tipo de atividade
     * Coluna 18 registro 20 regra 4
     */
    if ( $oDadosEscola->registro10->atividade_complementar == 0 && $oDadosTurma->tipo_atendimento == 4 ) {

      $sMsgErro  = "{$sMsgTurma} Tipo de atendimento não pode ser Atividade Complementar quando a Escola não à oferece.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    /**
     * Valida se a escola fornece Atendimento Educacional Especializado (AEE) e se há turmas informadas com este tipo de atividade
     * Coluna 18 registro 20 regra 5
     */
    if ( $oDadosEscola->registro10->atendimento_educacional_especializado == 0 && $oDadosTurma->tipo_atendimento == 5 ) {

      $sMsgErro  = "{$sMsgTurma} Tipo de atendimento não pode";
      $sMsgErro .= " ser Atendimento Educacional Especializado (AEE) quando a Escola não à oferece.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    /**
     * Coluna 18 registro 20 regra 6
     */
    if (in_array($oDadosTurma->tipo_atendimento, array(1, 4, 5)) &&  $oDadosTurma->mediacao_didatico_pedagogica != 1 ) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Tipo de Atendimento\" não pode ser preenchido com com 1 (Classe hospitalar),";
      $sMsgErro .= " 4 (Atividade complementar) ou 5 (AEE) quando o campo \"Mediação didático-pedagógica\"";
      $sMsgErro .= " for diferente de 1 (Presencial).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    /**
     * Coluna 18 registro 20 regra 7
     */
    if ( in_array($oDadosTurma->tipo_atendimento, array(2, 3)) && !in_array($oDadosTurma->mediacao_didatico_pedagogica, array(1,2) ) ) {

      $sMsgErro  = $sMsgTurma . 'O campo "Tipo de Atendimento" não pode ser preenchido com 2 (Unidade de internação ';
      $sMsgErro .= 'socioeducativa) ou 3 (Unidade prisional) quando o campo "Mediação didático-pedagógica" for ';
      $sMsgErro .= 'diferente de 1 (Presencial) e 2 (Semipresencial).';
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    /**
     * Coluna 18 registro 20 regra 8 e e
     */
    if ( in_array($oDadosTurma->tipo_atendimento, array(4, 5)) ) {

      $lValidouDocente = false;
      foreach ( $aDadosDocente as $aRegistros ) {

        foreach ( $aRegistros->registro51 as $oRegistro51 ) {

          if ( $oRegistro51->codigo_turma_entidade_escola == $oDadosTurma->codigo_turma_entidade_escola) {

            //docente ou profissional/monitor de atividade
            if ($oDadosTurma->tipo_atendimento == 4 && in_array($oRegistro51->funcao_exerce_escola_turma, array(1,3)) ) {

              $lValidouDocente = true;
              break;
            } elseif ($oDadosTurma->tipo_atendimento == 5 && $oRegistro51->funcao_exerce_escola_turma == 1) {

              $lValidouDocente = true;
              break;
            }
          }
        }
      }

      if ( !$lValidouDocente ) {

        $sMsgErro  = "{$sMsgTurma} O campo \"Tipo de Atendimento\" foi preenchido com 4 (Atividade complementar), ";
        $sMsgErro .= "mas não há nenhum docente ou profissional/monitor de atividade complementar vinculado a essa turma.";

        // Só troca a mensagem quando tipo 5
        if ( $oDadosTurma->tipo_atendimento == 5) {

          $sMsgErro  = "{$sMsgTurma} O campo \"Tipo de Atendimento\" foi preenchido com 5 (AEE), mas não há nenhum ";
          $sMsgErro .= "docente vinculado a essa turma. ";
        }
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   *  Validações da coluna Modalidade
   *
   * OBS.
   *  regra 1 - Não precisa ser validada sempre carrega da turma
   *  regra 2 - Não precisa ser validada pois o campo já é definido como ''
   *  regra 3 - Não precisa ser validada pois o campo já é definido como ''
   *  regra 4 - Sistema não deixa criar outros
   *
   * @param  IExportacaoCenso $oExportacaoCenso   Dados da exportação
   * @param  stdClass         $oDadosTurma        Dados da turma
   * @param  stdClass         $oDadosEscola       Registros da escola (00 e 10)
   * @return boolean
   */
  protected static function validarRegistro20Coluna37($oExportacaoCenso, $oDadosTurma, $oDadosEscola) {

    $sMsgTurma     = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
    $lDadosValidos = true;

    // Coluna 37 registro 20 - regra 5
    if ( $oDadosTurma->modalidade_turma == 1 && $oDadosEscola->registro10->modalidade_ensino_regular != 1) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Modalidade\" não pode ser preenchido com 1 (Ensino Regular) quando ";
      $sMsgErro .= "o campo \"Modalidade - Ensino regular\" for diferente de 1 (Sim)";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // Coluna 37 registro 20 - regra 6
    if ( $oDadosTurma->modalidade_turma == 2 && $oDadosEscola->registro10->modalidade_educacao_especial_modalidade_substutiva != 1) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Modalidade\" não pode ser preenchido com 2 (Educação Especial - Modalidade ";
      $sMsgErro .= "Substitutiva) quando \"Modalidade - Educação especial - modalidade substitutiva\" for diferente de 1 (Sim)";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // Coluna 37 registro 20 - regra 7
    if ( $oDadosTurma->modalidade_turma == 3 && $oDadosEscola->registro10->modalidade_educacao_jovens_adultos != 1) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Modalidade\" não pode ser preenchido com 3 (Educação de Jovens e Adultos) ";
      $sMsgErro .= "quando \"Modalidade - Educação de jovens e adultos\" for diferente de 1 (Sim)";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // Coluna 37 registro 20 - regra 8
    if ( $oDadosTurma->modalidade_turma == 4 && $oDadosEscola->registro10->modalidade_educacao_profissional != 1) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Modalidade\" não pode ser preenchido com 4 (Educação Profissional) ";
      $sMsgErro .= "quando \"Modalidade - Educação Profissional\" for diferente de 1 (Sim)";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    //Coluna 37 registro 20 - regra 9
    if ( !in_array($oDadosTurma->modalidade_turma, array(2, 3)) && $oDadosTurma->mediacao_didatico_pedagogica == 2) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Modalidade\" deve ser preenchido com (Educação Especial - Modalidade Substitutiva)";
      $sMsgErro .= " ou 3 (EJA) quando o campo \"Mediação didático-pedagógica\" for igual a 2 (Semipresencial).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    //Coluna 37 registro 20 - regra 10
    if ( !in_array($oDadosTurma->modalidade_turma, array(1, 3, 4)) && $oDadosTurma->mediacao_didatico_pedagogica == 3) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Modalidade\" deve ser preenchido com 1, 3 ou 4 ";
      $sMsgErro .= "quando o campo \"Mediação didático-pedagógica\" for igual a 2 (Educação a Distância).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }
    return $lDadosValidos;
  }

  /**
   * Validações da coluna Etapa de Ensino
   *
   * Obs.: não precisa validar pois..
   *   regra 1 - ao buscar os dados da turmas sempre carrega valor
   *   regra 2 - ao buscar os dados da turmas ac já define como vazio
   *   regra 3 - ao buscar os dados da turmas aee já define como vazio
   *   regra 4 - ao buscar carrega o codigo da etapa da tabela do censo... só estará errada se tabela não foi atualizada
   *
   * @param  IExportacaoCenso $oExportacaoCenso   Dados da exportação
   * @param  stdClass         $oDadosTurma        Dados da turma
   * @param  stdClass         $oDadosEscola       Registros da escola (00 e 10)
   * @param  array            $aDadosDocente      Todos docentes e seus registros (30, 40, 50 e 51)
   *
   * @return boolean
   */
  protected static function validarRegistro20Coluna38($oExportacaoCenso, $oDadosTurma, $oDadosEscola, $aDadosDocente) {

    $sMsgTurma     = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
    $lDadosValidos = true;

    /**
     * Modalidades de ensino
     * 1 - ER  - ENSINO REGULAR
     * 2 - ES  - EDUCAÇÃO ESPECIAL
     * 3 - EJA - EDUCAÇÃO DE JOVENS E ADULTOS
     * 4 - EP  - EDUCAÇÃO PROFISSIONAL
     */
    $aEtapasER  = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,35,36,37,38,41,56);
    $aEtapasES  = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,56,64,67,68,69,70,71,72,73,74);
    $aEtapasEJA = array(65,69,70,71,72);
    $aEtapasEP  = array(30,31,32,33,34,39,40,64,67,68,73,74);

    // regra 5 - valida etapa condiz com a modalidade da turma
    if (    $oDadosTurma->modalidade_turma == 1 && !in_array($oDadosTurma->etapa_ensino_turma, $aEtapasER)
         || $oDadosTurma->modalidade_turma == 2 && !in_array($oDadosTurma->etapa_ensino_turma, $aEtapasES)
         || $oDadosTurma->modalidade_turma == 3 && !in_array($oDadosTurma->etapa_ensino_turma, $aEtapasEJA)
         || $oDadosTurma->modalidade_turma == 4 && !in_array($oDadosTurma->etapa_ensino_turma, $aEtapasEP)
       ) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Etapa de Ensino\" foi preenchido com valor  incompatível com a modalidade ";
      $sMsgErro .= "informada no campo \"Modalidade\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 6
    if ( in_array($oDadosTurma->etapa_ensino_turma, array(1, 2, 3, 56)) &&
         in_array($oDadosTurma->tipo_atendimento, array(2,3))) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Etapa de Ensino\" não pode ser preenchido com educação infantil quando o campo ";
      $sMsgErro .= " \"Tipo de Atendimento\" for preenchido com 2 (Unidade de internação socioeducativa) ou 3 (Unidade prisional).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 7
    if ( !in_array($oDadosTurma->etapa_ensino_turma, array(69, 70, 71, 72)) && $oDadosTurma->mediacao_didatico_pedagogica == 2) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Etapa de Ensino\" deve ser preenchido com 69, 70, 71 ou 72 quando o campo";
      $sMsgErro .= " \"Mediação didático-pedagógica\" for igual a 2 (Semipresencial).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 8
    if ( !in_array($oDadosTurma->etapa_ensino_turma, array(30,31,32,33,34,35,36,37,38,39,40,70,71,73,74,64,67, 68)) &&
         $oDadosTurma->mediacao_didatico_pedagogica == 3) {

      $sMsgErro  = "{$sMsgTurma} O campo \"Etapa de Ensino\" deve ser preenchido com 30, 31, 32, 33, 34, 35, 36, 37, ";
      $sMsgErro .= "38, 39, 40, 70, 71, 73, 74, 64, 67 ou 68 quando o campo \"Mediação didático-pedagógica\" for ";
      $sMsgErro .= "igual a 3 (Educação a Distância).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 9
    if ( $oDadosTurma->etapa_ensino_turma == 1 ) {

      $lValidou = false;
      foreach ( $aDadosDocente as $aRegistros ) {

        foreach ( $aRegistros->registro51 as $oRegistro51 ) {

          //docente ou auxiliar/assistente educacional
          if ( $oRegistro51->codigo_turma_entidade_escola == $oDadosTurma->codigo_turma_entidade_escola &&
               in_array($oRegistro51->funcao_exerce_escola_turma, array(1,2)) ) {

            $lValidou = true;
            break;
          }
        }
      }

      if ( !$lValidou ) {

        $sMsgErro  = "{$sMsgTurma} O campo \"Etapa de Ensino\" foi preenchido com 1 (Creche), mas não há nenhum ";
        $sMsgErro .= "docente ou auxiliar/assistente educacional vinculado a essa turma.";

        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }
    }

    // regra 10
    if ( !in_array($oDadosTurma->tipo_atendimento, array(4,5)) && $oDadosTurma->etapa_ensino_turma != 1 ) {

      $lValidou = false;
      foreach ( $aDadosDocente as $aRegistros ) {

        foreach ( $aRegistros->registro51 as $oRegistro51 ) {

          //docente ou auxiliar/assistente educacional
          if ( $oRegistro51->codigo_turma_entidade_escola == $oDadosTurma->codigo_turma_entidade_escola &&
               $oRegistro51->funcao_exerce_escola_turma == 1 ) {

            $lValidou = true;
            break;
          }
        }
      }

      if ( !$lValidou ) {

        $sMsgErro  = "{$sMsgTurma} O campo \"Etapa de Ensino\" foi preenchido com uma etapa diferente de 1 (Creche), ";
        $sMsgErro .= "mas não há nenhum docente vinculado a essa turma.";

        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   * Validações das colunas referentes as disciplinas
   *
   * Obs.: não precisa ser validadar
   *   regra 4 - pois não é possível informar valor inválido
   *   regra 7 - só marca 1 na disciplina se tem docente que oferece
   *
   * @param  IExportacaoCenso $oExportacaoCenso   Dados da exportação
   * @param  stdClass         $oDadosTurma        Dados da turma
   * @param  stdClass         $oDadosEscola       Registros da escola (00 e 10)
   * @return boolean
   */
  protected static function validarRegistro20Coluna40a65($oExportacaoCenso, $oDadosTurma, $oDadosEscola) {

    $sMsgTurma     = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
    $lDadosValidos = true;
    $aEtapaEnsino  = array(1, 2, 3, 65); // etapas infantis e eja
    $aDisciplinas  = array(
      $oDadosTurma->disciplinas_turma_quimica,
      $oDadosTurma->disciplinas_turma_fisica,
      $oDadosTurma->disciplinas_turma_matematica,
      $oDadosTurma->disciplinas_turma_biologia,
      $oDadosTurma->disciplinas_turma_ciencias,
      $oDadosTurma->disciplinas_turma_lingua_literatura_portuguesa,
      $oDadosTurma->disciplinas_lingua_literatura_estrangeira_inglesa,
      $oDadosTurma->disciplinas_lingua_literatura_estrangeira_espanhol,
      $oDadosTurma->disciplinas_lingua_literatura_estrangeira_outra,
      $oDadosTurma->disciplinas_turma_artes,
      $oDadosTurma->disciplinas_turma_educacao_fisica,
      $oDadosTurma->disciplinas_turma_historia,
      $oDadosTurma->disciplinas_turma_geografia,
      $oDadosTurma->disciplinas_turma_filosofia,
      $oDadosTurma->disciplinas_turma_informatica_computacao,
      $oDadosTurma->disciplinas_turma_disciplinas_profissionalizantes,
      $oDadosTurma->disciplinas_turma_voltadas_atendimento_necessidade,
      $oDadosTurma->disciplinas_turma_voltadas_diversidade_sociocultur,
      $oDadosTurma->disciplinas_turma_libras,
      $oDadosTurma->disciplinas_turma_disciplinas_pedagogicas,
      $oDadosTurma->disciplinas_turma_ensino_religioso,
      $oDadosTurma->disciplinas_turma_lingua_indigena,
      $oDadosTurma->disciplinas_turma_estudos_sociais,
      $oDadosTurma->disciplinas_turma_sociologia,
      $oDadosTurma->disciplinas_lingua_literatura_estrangeira_frances,
      $oDadosTurma->disciplinas_turma_outras
    );

    /**
     * Validado para quando tipo de ensino for infantil não permitir informar disciplinas
     * OBS.:Utilizamos a função strlen para que as possições contendo 0 fossem mantidas
     */
    $aDisciplinasPreenchidas = array_filter( $aDisciplinas, 'strlen' );

    // regra 1
    if ( count($aDisciplinasPreenchidas) == 0 &&
         ($oDadosTurma->tipo_atendimento != 4 && $oDadosTurma->tipo_atendimento != 5) &&
         !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) ) {

      $sMsgErro  = "{$sMsgTurma} Os campos referentes a \"Disciplinas\" deve ser preenchido quando o campo ";
      $sMsgErro .= "\"Tipo de Atendimento\" for diferente de 4 (Atividade complementar) e 5 (AEE), e o campo ";
      $sMsgErro .= "\"Etapa de Ensino\" for diferente de 1, 2, 3 e 65. asdsdsa: {$oDadosTurma->tipo_atendimento}  - " . count($aDisciplinasPreenchidas);

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 2
    if ( count($aDisciplinasPreenchidas) != 0 && ($oDadosTurma->tipo_atendimento == 4 || $oDadosTurma->tipo_atendimento == 5) ) {

      $sMsgErro  = "{$sMsgTurma} Os campos referentes a \"Disciplinas\" não pode ser preenchido quando o campo ";
      $sMsgErro .= "\"Tipo de Atendimento\" for igual a 4 (Atividade complementar) ou 5 (AEE).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 3
    if ( count($aDisciplinasPreenchidas) != 0 && in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino) ) {

      $sMsgErro  = "{$sMsgTurma} Os campos referentes a \"Disciplinas\" não pode ser preenchido quando o campo ";
      $sMsgErro .= "\"Etapa de Ensino\" for igual a 1, 2, 3 ou 65";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 6
    if ( in_array(2, $aDisciplinasPreenchidas) && $oDadosTurma->mediacao_didatico_pedagogica == 3 ) {

      $sMsgErro  = "{$sMsgTurma} Os campos referentes a \"Disciplinas\" não pode ser preenchido com 2 (Sim, oferece ";
      $sMsgErro .= "disciplina sem docente  vinculado.) quando o campo \"Tipo de mediação didático-pedagógica\" ";
      $sMsgErro .= "for igual a 3 (Educação a Distância).";

      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      $lDadosValidos = false;
    }

    // regra 5
    if ( $oDadosTurma->tipo_atendimento != 4 && $oDadosTurma->tipo_atendimento != 5 ) {

      $aValidaDisciplina = array();
      if ( in_array($oDadosTurma->disciplinas_turma_quimica, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 1, "QUIMICA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_fisica, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 2, "FISICA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_matematica, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 3, "MATEMATICA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_biologia, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 4,"BIOLOGIA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_ciencias, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 5, "CIENCIAS");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_lingua_literatura_portuguesa, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 6, "LINGUA /LITERATURA PORTUGUESA");
      }
      if ( in_array($oDadosTurma->disciplinas_lingua_literatura_estrangeira_inglesa, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 7, "LINGUA /LITERATURA ESTRANGEIRA - INGLES");
      }
      if ( in_array($oDadosTurma->disciplinas_lingua_literatura_estrangeira_espanhol, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 8, "LINGUA /LITERATURA ESTRANGEIRA - ESPANHOL");
      }
      if ( in_array($oDadosTurma->disciplinas_lingua_literatura_estrangeira_outra, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 9, "LINGUA /LITERATURA ESTRANGEIRA - OUTRA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_artes, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 10, "ARTES");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_educacao_fisica, array(1,2)) ) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 11, "EDUCACAO FISICA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_historia, array(1,2)) ) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 12, "HISTORIA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_geografia, array(1,2)) ) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 13, "GEOGRAFIA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_filosofia, array(1,2)) ) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 14, "FILOSOFIA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_informatica_computacao, array(1,2)) ){
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 16, "INFORMATICA/COMPUTACAO");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_disciplinas_profissionalizantes, array(1,2)) ) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 17, "DISCIPLINAS PROFISSIONALIZANTES");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_voltadas_atendimento_necessidade, array(1,2)) ) {
        self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 20, "DISCIPLINAS VOLTADAS AO ATENDIMENTO AS NECESSIDADES EDUCACIONAIS ESPECIFICAS DOS ALUNOS");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_voltadas_diversidade_sociocultur, array(1,2)) ) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 21, "DISCIPLINAS VOLTADAS A DIVERSIDADE SOCIOCULTURAL (DISCIPLINAS PEDAGOGICAS)");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_libras, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 23, "LIBRAS");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_disciplinas_pedagogicas, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 25, "DISCIPLINAS PEDAGOGICAS");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_ensino_religioso, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 26, "ENSINO RELIGIOSO");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_lingua_indigena, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 27, "LINGUA INDIGENA");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_estudos_sociais, array(1,2))) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 28, "ESTUDOS SOCIAIS");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_sociologia, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 29, "SOCIOLOGIA");
      }
      if ( in_array($oDadosTurma->disciplinas_lingua_literatura_estrangeira_frances, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 30, "LINGUA/LITERATURA ESTRANGEIRA - FRANCES");
      }
      if ( in_array($oDadosTurma->disciplinas_turma_outras, array(1,2) )) {
        $aValidaDisciplina[] = self::validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, 99, "OUTRAS DISCIPLINAS");
      }

      if ($lDadosValidos && !empty($aValidaDisciplina) ) {
        $lDadosValidos = array_reduce( $aValidaDisciplina, 'validaVerdadeiro');
      }
    }

    return $lDadosValidos;
  }

  /**
   * valida
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exportação
   * @param  stdClass         $oDadosTurma       Dados da turma
   * @param  integer          $iDisciplina       codigo da disciplina do censo
   * @param  string           $sDisciplina       nome da disciplina
   * @return boolean
   */
  protected static function validaDisciplinaComEtapa($oExportacaoCenso, $oDadosTurma, $iDisciplina, $sDisciplina) {

    $aDisciplinas = array();
    $iEtapa    = $oDadosTurma->etapa_ensino_turma;
    $sMsgTurma = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}: ";
    if ( empty(self::$aEtapasDisciplinas[$iEtapa]) ) {

      $sWhere         = " ed272_i_censoetapa = {$iEtapa} and ed272_ano = {$oExportacaoCenso->getAnoCenso()} ";
      $oDaoDisciplina = new cl_censoregradisc;
      $sSqlDisciplina = $oDaoDisciplina->sql_query_file( null, ' ed272_i_censodisciplina ', null, $sWhere);

      $rsDisciplina   = db_query($sSqlDisciplina);

      if ( !$rsDisciplina ) {
        throw new Exception("Erro ao buscar disciplinas do censo.\n" .pg_last_error());
      }

      $iLinhas = pg_num_rows($rsDisciplina);
      for ($i = 0; $i < $iLinhas; $i++) {
        self::$aEtapasDisciplinas[$iEtapa][] = db_utils::fieldsMemory($rsDisciplina, $i)->ed272_i_censodisciplina;
      }
    }



    $aDisciplinas = self::$aEtapasDisciplinas[$iEtapa];

    if ( !in_array($iDisciplina, $aDisciplinas) ) {

      $sMsgErro = "{$sMsgTurma} A disciplina {$sDisciplina} é incompatível com a etapa de ensino informada para a turma.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
      return false;
    }


    return true;
  }

  /**
   * Valida a coluna 19 - Turma participante do Programa Mais Educação/Ensino Médio Inovador
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exportação
   * @param  stdClass         $oDadosTurma       Dados da turma
   * @param  stdClass         $oDadosEscola      Registros da escola (00 e 10)
   * @return boolean
   */
  protected static function validarRegistro20Coluna19($oExportacaoCenso, $oDadosTurma, $oDadosEscola) {

    $sMsgTurma  = "Turma {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma}:\n";
    $sMsgTurma .='O campo "Turma participante do Programa Mais Educação/Ensino Médio Inovador" não pode';
    $lValidou   = true;

    // regra 1 não precisa validar pois sempre vem com valor, as regras abaixo limpa a variável quando esta deveria ser nula

    // regra 2
    if ( $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov !== '' && $oDadosTurma->mediacao_didatico_pedagogica != 1) {
      $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
    }

    // regra 3
    if (  $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov !== '' &&
         !in_array($oDadosEscola->registro00->dependencia_administrativa, array(2,3)) ) {
      $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
    }

    // regra 4
    if ( $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov !== '' &&
         in_array($oDadosTurma->tipo_atendimento, array(1, 5)) )  {
      $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
    }

    // regra 5
    $aEtapaEnsino = array(  4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22,
                           23, 24, 41, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38);
    if ( $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov !== '' &&
         $oDadosTurma->modalidade_turma == 3 || !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino)) {
      $oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov = '';
    }

    // regra 6
    if ( !in_array($oDadosTurma->turma_participante_mais_educacao_ensino_medio_inov, array(0,1)) ) {

      $sMsgErro = "{$sMsgTurma}foi preenchido com valor inválido.";
      $lValidou = false;
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_TURMA);
    }

    return $lValidou;
  }
}

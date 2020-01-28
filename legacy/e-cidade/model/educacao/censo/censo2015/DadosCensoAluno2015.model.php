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
 *
 */
class DadosCensoAluno2015 extends DadosCensoAluno {

  private $sAluno           = '';
  private $oExportacaoCenso = null;

  public static function validarDados(IExportacaoCenso $oExportacaoCenso) {

    $lValidou = true;

    foreach ( $oExportacaoCenso->getDadosProcessadosAluno() as $oDadosAluno ) {

      if ( !DadosCensoAluno2015::validaRegistro60($oExportacaoCenso, $oDadosAluno) ) {
        $lValidou = false;
      }

      if ( !DadosCensoAluno2015::validaRegistro70($oExportacaoCenso, $oDadosAluno) ) {
        $lValidou = false;
      }

     if ( !DadosCensoAluno2015::validaRegistro80($oExportacaoCenso, $oDadosAluno ) ) {
       $lValidou = false;
     }
    }

    return $lValidou;
  }

  /**
   * Valida os dados do registro 60 do layout do censo de 2015
   * Campos n�o validados pois s�o validados na gera��o dos dados do aluno
   *  - tipo_registro
   *  - codigo_escola_inep
   *  - nome_completo (garante que s� vem caracteres v�lidos)
   *  - data_nascimento
   *  - sexo
   *  - cor_raca
   *
   * @param  stdClass $oDadosAluno
   * @return boolean
   */
  public static function validaRegistro60($oExportacaoCenso, $oDadosAluno) {

    $sAluno = "{$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}";
    $oRegistro60 = $oDadosAluno->registro60;
    $lValidou    = true;

    $aStatusValidacao = array();
    if (!empty($oRegistro60->identificacao_unica_aluno) && strlen($oRegistro60->identificacao_unica_aluno) < 12) {

      $sMsgErro  = "Aluno(a) {$sAluno}:\n";
      $sMsgErro .= "C�digo INEP do aluno possui tamanho inferior a 12 d�gitos.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    $aStatusValidacao[] = self::validaRegistro60Coluna6($oExportacaoCenso, $oDadosAluno);

    if ( !DBString::isNomeValido($oRegistro60->nome_completo, DBString::NOME_REGRA_2) ) {

      $sMsgErro  = "Nome do Aluno(a) {$sAluno} dever possuir nome e sobrenome.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    /**
     * valida a filia��o do aluno
     * 0 - N�o declarado/Ignorado;
     * 1 - Pai e/ou M�e
     */
    switch ($oRegistro60->filiacao) {

      case 0:
        // coluna 10 e 11 regra 1
        if (!empty($oRegistro60->filiacao_1) || !empty($oRegistro60->filiacao_2) ) {

          $sMsgErro  = "Aluno(a) {$sAluno}:\n";
          $sMsgErro .= "Nome do pai e/ou m�e s� devem ser informadados quando a filia��o for igual a: Pai e/ou M�e.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
          $lValidou = false;
        }

        break;
      case 1:

          // coluna 9 regra 3
          if (empty($oRegistro60->filiacao_1) && empty($oRegistro60->filiacao_2) ) {

            $sMsgErro  = "Aluno(a) {$sAluno}:\n";
            $sMsgErro .= "O campo \"Filia��o 1\" ou o campo \"Filia��o 2\" deve ser preenchido quando o campo ";
            $sMsgErro .= " \"Filia��o\" for igual a 1 (Pai e/ou M�e).";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
            $lValidou = false;
          }

          // coluna 9 regra 5
          if (!empty($oRegistro60->filiacao_1) &&
              !DBString::isNomeValido($oRegistro60->filiacao_1, DBString::NOME_REGRA_4) ) {

            $sMsgErro  = "Aluno(a) {$sAluno}:\n";
            $sMsgErro .= " \"Filia��o 1\" ({$oRegistro60->filiacao_1}) possui mais de 4 letras repetidas em sequ�ncia.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
            $lValidou = false;
          }
          // coluna 11 regra 5
          if (!empty($oRegistro60->filiacao_2) &&
              !DBString::isNomeValido($oRegistro60->filiacao_2, DBString::NOME_REGRA_4) ) {

            $sMsgErro  = "Aluno(a) {$sAluno}:\n";
            $sMsgErro .= " \"Filia��o 1\" ({$oRegistro60->filiacao_2}) possui mais de 4 letras repetidas em sequ�ncia.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
            $lValidou = false;
          }

          // coluna 11 regra 6
          if ( !empty($oRegistro60->filiacao_2) && $oRegistro60->filiacao_2 == $oRegistro60->filiacao_1) {
            $sMsgErro  = "Aluno(a) {$sAluno}:\n";
            $sMsgErro .= " O campo \"Filia��o 2\" n�o pode ser igual ao campo \"Filia��o 1\".";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
            $lValidou = false;
          }
        break;
    }

    if ( $oRegistro60->nacionalidade_aluno == 1 ) {

      /**
       * Linha 14: Valida se a Nascionalidade do aluno � brasileira e se a UF de Nascimento foi preenchida
       */
      if ( $oRegistro60->uf_nascimento == '' ) {

        $sMsgErro  = "Aluno(a) {$sAluno}:\n";
        $sMsgErro .= "Obrigat�rio informar UF de nascimento quando a nascionalidade for Brasileira.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }


      /**
       * Linha 15: Valida se a Nascionalidade do aluno � brasileira e se o Munic�pio de Nascimento foi preenchido
       */
      if ( $oRegistro60->municipio_nascimento == '' ) {

        $sMsgErro  = "Aluno(a) {$sAluno}:\n";
        $sMsgErro .= "Obrigat�rio informar Munic�pio de nascimento quando a nascionalidade for Brasileira.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }
    }

    // valida��es das deficiencias
    $aStatusValidacao[] = self::validaRegistro60Coluna16($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna17a29($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna17($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna18($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna19($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna20($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna24($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna25($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna26($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna27($oExportacaoCenso, $oDadosAluno);

    // valida��es dos recursos de auxilio
    $aStatusValidacao[] = self::validaRegistro60Coluna30a39($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna30e31($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna32($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna33e34($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna35a38($oExportacaoCenso, $oDadosAluno);
    $aStatusValidacao[] = self::validaRegistro60Coluna39($oExportacaoCenso, $oDadosAluno);

    if ( $lValidou ) {
      $lValidou = array_reduce( $aStatusValidacao, 'validaVerdadeiro');
    }

    return $lValidou;
  }

  /**
   * Valida��es referentes ao registro 80 - V�NCULO (MATR�CULA)
   * @param $oDadosAluno
   * @return bool
   */
  public static function validaRegistro80($oExportacaoCenso, $oDadosAluno ) {

    $sAluno   = "{$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}";
    $lValidou = true;

    foreach( $oDadosAluno->registro80 as $iIndice => $oMatricula ) {

      $oTurma       = DadosCensoAluno::getTurmaAluno( $oExportacaoCenso, $oMatricula->codigo_turma_entidade_escola );

      $aTransportes = array(
         $oMatricula->rodoviario_vans_kombi,
         $oMatricula->rodoviario_microonibus,
         $oMatricula->rodoviario_onibus,
         $oMatricula->rodoviario_bicicleta,
         $oMatricula->rodoviario_tracao_animal,
         $oMatricula->rodoviario_outro,
         $oMatricula->aquaviario_embarcacao_5_pessoas,
         $oMatricula->aquaviario_embarcacao_5_a_15_pessoas,
         $oMatricula->aquaviario_embarcacao_15_a_35_pessoas,
         $oMatricula->aquaviario_embarcacao_mais_de_35_pessoas,
         $oMatricula->ferroviario_trem_metro
      );

      $aEtapasMultiEtapa = array( 12, 13, 22, 23, 24, 51, 56, 58, 64 );

      if ( $oExportacaoCenso->getAnoCenso() > 2014 ) {
        $aEtapasMultiEtapa[] = 72;
      }

      $aEtapasPermitidas[12] = array( 4, 5, 6, 7, 8, 9, 10, 11 );
      $aEtapasPermitidas[13] = array( 4, 5, 6, 7, 8, 9, 10, 11 );
      $aEtapasPermitidas[22] = array( 14, 15, 16, 17, 18, 19, 20, 21, 41 );
      $aEtapasPermitidas[23] = array( 14, 15, 16, 17, 18, 19, 20, 21, 41 );
      $aEtapasPermitidas[24] = array( 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19, 20, 21, 41 );
      $aEtapasPermitidas[56] = array( 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19, 20, 21, 41 );
      $aEtapasPermitidas[64] = array( 39, 40 );
      $aEtapasPermitidas[72] = array( 69, 70 );

      if( $oTurma->etapa_ensino_turma == 3 && !in_array( $oMatricula->turma_unificada, array( 1, 2 ) ) ) {

        $sMensagem  = "Aluno(a) {$sAluno}: \n";
        $sMensagem .= "Deve ser informada a turma Unificada do Aluno";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ALUNO );
        $lValidou = false;
      }

      if( in_array( $oTurma->etapa_ensino_turma, $aEtapasMultiEtapa ) ) {

        if (!in_array($oMatricula->codigo_etapa_multi_etapa, $aEtapasPermitidas[$oTurma->etapa_ensino_turma])) {

          $sMensagem  = "Aluno(a) {$sAluno}: \n";
          $sMensagem .= "Turma: {$oTurma->nome_turma}";
          $sMensagem .= " Etapa do aluno em turma multietapa fora das etapas permitidas.";
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ALUNO );
          $lValidou = false;
        }
      }

      if( $oTurma->mediacao_didatico_pedagogica == 1 && $oMatricula->tipo_turma == 'NORMAL') {

        if( !in_array( $oTurma->tipo_atendimento, array(4,5) )
            && empty($oMatricula->recebe_escolarizacao_outro_espaco) ) {

          $sMensagem  = "Aluno(a) {$sAluno}: \n";
          $sMensagem .= "O campo 'Recebe escolariza��o em outro espa�o' deve ser informado.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ALUNO);
          $lValidou = false;
        }

        if( $oMatricula->recebe_escolarizacao_outro_espaco == 1 ) {

          if( $oTurma->tipo_atendimento != 1 ) {

            $sMensagem  = "Aluno(a) {$sAluno}: \n";
            $sMensagem .= "Quando informado 'Recebe escolariza��o em outro espa�o' com o valor";
            $sMensagem .= " 1-Em Hospital, o campo 'Tipo de Atendimento' da turma deve ser informado com o valor";
            $sMensagem .= " 1-Classe hospitalar.";
            $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ALUNO);
            $lValidou = false;
          }
        }
      }

      $aTransportes = array_count_values( $aTransportes );

      if( $oMatricula->transporte_escolar_publico  == 0 ) {

        if( $oMatricula->poder_publico_transporte_escolar != "" ) {

          $sMensagem  = "Aluno(a) {$sAluno}: \n";
          $sMensagem .= "O campo 'Poder P�blico respons�vel pelo transporte escolar' n�o pode ser informado, Aluno n�o";
          $sMensagem .= " utiliza transporte p�blico.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ALUNO);
          $lValidou = false;
        }

        if( isset( $aTransportes[1] ) ) {

          $oDadosAluno->registro80[ $iIndice ]->rodoviario_vans_kombi                    = '';
          $oDadosAluno->registro80[ $iIndice ]->rodoviario_microonibus                   = '';
          $oDadosAluno->registro80[ $iIndice ]->rodoviario_onibus                        = '';
          $oDadosAluno->registro80[ $iIndice ]->rodoviario_bicicleta                     = '';
          $oDadosAluno->registro80[ $iIndice ]->rodoviario_tracao_animal                 = '';
          $oDadosAluno->registro80[ $iIndice ]->rodoviario_outro                         = '';
          $oDadosAluno->registro80[ $iIndice ]->aquaviario_embarcacao_5_pessoas          = '';
          $oDadosAluno->registro80[ $iIndice ]->aquaviario_embarcacao_5_a_15_pessoas     = '';
          $oDadosAluno->registro80[ $iIndice ]->aquaviario_embarcacao_15_a_35_pessoas    = '';
          $oDadosAluno->registro80[ $iIndice ]->aquaviario_embarcacao_mais_de_35_pessoas = '';
          $oDadosAluno->registro80[ $iIndice ]->ferroviario_trem_metro                   = '';
        }
      }

      if( $oMatricula->transporte_escolar_publico == 1 ) {

        if( $oMatricula->poder_publico_transporte_escolar == "" ) {

          $sMensagem  = "Aluno(a) {$sAluno}: \n";
          $sMensagem .= "Deve ser informado o poder p�blico respons�vel.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ALUNO);
          $lValidou = false;
        }

        if( isset( $aTransportes[0] ) && $aTransportes[0] == 11 ) {

          $sMensagem  = "Aluno(a) {$sAluno}: \n";
          $sMensagem .= "Informado que o aluno utiliza transporte p�blico. Ao menos uma das op��es de transporte p�blico";
          $sMensagem .= " deve ser selecionada.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ALUNO);
          $lValidou = false;
        }

        if( isset( $aTransportes[1] ) && $aTransportes[1] > 3 ) {

          $sMensagem  = "Aluno(a) {$sAluno}: \n";
          $sMensagem .= "Permitido informar no m�ximo 3 op��es de transporte p�blico.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ALUNO);
          $lValidou = false;
        }
      }
    }

    $aStatusValidacao[] = self::validaRegistro80Coluna10($oExportacaoCenso, $oDadosAluno);

    if ( $lValidou ) {
      $lValidou = array_reduce( $aStatusValidacao, 'validaVerdadeiro');
    }

    return $lValidou;
  }

  /**
   * Valida��es referentes ao registro 70 - DOCUMENTOS E ENDERE�O
   * @param $oDadosAluno
   * @return bool
   */
  public static function validaRegistro70($oExportacaoCenso, $oDadosAluno) {

    $sAluno      = "{$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}";
    $oRegistro70 = $oDadosAluno->registro70;
    $oRegistro60 = $oDadosAluno->registro60;
    $lValidou    = true;

    $oAlunoDao     = new cl_aluno();
    $oDtNascimento = new DBDate($oRegistro60->data_nascimento);

    $aDocumentosNacionalidadeBrasileira = array(
      $oRegistro70->numero_identidade         != '' ? true : false,
      $oRegistro70->orgao_emissor_identidade  != '' ? true : false,
      $oRegistro70->uf_identidade             != '' ? true : false,
      $oRegistro70->data_expedicao_identidade != '' ? true : false,
      $oRegistro70->certidao_civil            != '' ? true : false,
      $oRegistro70->tipo_certidao_civil       != '' ? true : false,
      $oRegistro70->numero_termo              != '' ? true : false,
      $oRegistro70->folha                     != '' ? true : false,
      $oRegistro70->livro                     != '' ? true : false,
      $oRegistro70->data_emissao_certidao     != '' ? true : false,
      $oRegistro70->uf_cartorio               != '' ? true : false,
      $oRegistro70->municipio_cartorio        != '' ? true : false,
      $oRegistro70->codigo_cartorio           != '' ? true : false,
      $oRegistro70->numero_matricula          != '' ? true : false,
      $oRegistro70->numero_cpf                != '' ? true : false
    );

    /**
     * Valida��o do campo 5 ao campo 18 referente a Nacionalidade do Aluno
     */
    if ( $oRegistro60->nacionalidade_aluno == 3 && in_array(true, $aDocumentosNacionalidadeBrasileira) ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "N�mero de identidade, Org�o Emissor da Identidade, UF da Identidade, Data da Expedi��o da Identidade, ";
      $sMsgErro .= "Certid�o Civil, Tipo de Certid�o Civil, N�mero do Termo, Folha, Livro, Data de Emiss�o da Certid�o,  ";
      $sMsgErro .= "UF do Cart�rio, Munic�pio do Cart�rio, C�digo do Cart�rio e N�mero da Matr�cula( Registro Civil - Certid�o Nova ) ";
      $sMsgErro .= "devem ser preenchido apenas por alunos com nacionalidade Brasileira ";
      $sMsgErro .= "ou Brasileira - nascido no exterior ou naturalizado";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    $aDocumentosIdentidade = array(
      $oRegistro70->numero_identidade,
      $oRegistro70->uf_identidade,
      $oRegistro70->orgao_emissor_identidade,
      $oRegistro70->data_expedicao_identidade
    );

    $iDocumentosIdentidadeInformados = 0;
    foreach ($aDocumentosIdentidade as $oDocumentoIdentidade) {

      if ( $oDocumentoIdentidade != "" ) {
        $iDocumentosIdentidadeInformados++;
      }
    }


    /**
     * Valida��o do campo 5 ao campo 8 referente a obrigatoriedade de preenchimento
     */
    if ( $iDocumentosIdentidadeInformados > 0 && $iDocumentosIdentidadeInformados < 4 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Ao preencher uma das seguintes informa��es da identidade (N�mero de Identidade, �rg�o Emissor da ";
      $sMsgErro .= "Identidade, UF da Identidade ou Data de Expedi��o da Identidade), todas as outras devem ser informadas.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 5 regra 3
    if ( $oRegistro60->nacionalidade_aluno != 3 && !empty($oRegistro70->numero_identidade) &&
         preg_match("/[^a-zA-Z0-9\-��]/", $oRegistro70->numero_identidade) > 0 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= 'O campo "N�mero da identidade" foi preenchido com valor inv�lido.';
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    if (    trim( $oRegistro70->tipo_certidao_civil ) == ''
         && (    trim( $oRegistro70->numero_termo    ) != ''
              || trim( $oRegistro70->uf_cartorio     ) != ''
              || trim( $oRegistro70->codigo_cartorio ) != ''
            )
       ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Tipo de certid�o n�o informado.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    if( $oRegistro70->situacaodocumentacao == 0 ) {

      $aDocumentosAluno = array(
                                 trim( $oRegistro70->numero_identidade ),
                                 trim( $oRegistro70->complemento_identidade ),
                                 trim( $oRegistro70->orgao_emissor_identidade ),
                                 trim( $oRegistro70->uf_identidade ),
                                 trim( $oRegistro70->data_expedicao_identidade ),
                                 trim( $oRegistro70->certidao_civil ),
                                 trim( $oRegistro70->tipo_certidao_civil ),
                                 trim( $oRegistro70->numero_termo ),
                                 trim( $oRegistro70->folha ),
                                 trim( $oRegistro70->livro ),
                                 trim( $oRegistro70->data_emissao_certidao ),
                                 trim( $oRegistro70->uf_cartorio ),
                                 trim( $oRegistro70->municipio_cartorio ),
                                 trim( $oRegistro70->codigo_cartorio ),
                                 trim( $oRegistro70->numero_matricula ),
                                 trim( $oRegistro70->numero_cpf ),
                                 trim( $oRegistro70->documento_estrangeiro_passaporte )
                               );

      $iTotalDocumentos         = count( $aDocumentosAluno );
      $iDocumentosNaoInformados = 0;

      foreach( $aDocumentosAluno as $sDocumento ) {

        if( empty( $sDocumento ) ) {
          $iDocumentosNaoInformados++;
        }
      }

      if( $iTotalDocumentos == $iDocumentosNaoInformados ) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "Informado que o aluno possui documenta��o, por�m nenhum foi informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }
    }

    /**
     * Valida��es referentes ao campo 9
     */
    if ( $oRegistro70->certidao_civil == 1 ) {

      if ( $oRegistro70->numero_matricula != '' ){

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "Quando informado Certid�o Civil igual a 'Modelo Antigo', o N�mero da Matr�cula n�o deve ser informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }

      if (    trim( $oRegistro70->tipo_certidao_civil ) == ''
           || trim( $oRegistro70->numero_termo        ) == ''
           || trim( $oRegistro70->uf_cartorio         ) == ''
           || trim( $oRegistro70->codigo_cartorio     ) == '' ) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "Quando informado Certid�o Civil igual a 'Modelo Antigo', os campos Tipo de Certid�o Civil, ";
        $sMsgErro .= "N�mero do Termo, UF do Cart�rio e C�digo do Cart�rio devem ser preenchidos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }

     // coluna 14
      if ( !empty($oRegistro70->data_emissao_certidao) ) {

        $oDtCertidao   = new DBDate($oRegistro70->data_emissao_certidao);
        if ( $oDtCertidao->getTimeStamp() < $oDtNascimento->getTimeStamp() ) {

          $sMsgErro  = "Aluno(a) {$sAluno}: \n";
          $sMsgErro .= "Data de Nascimento deve ser menor que a data de Emiss�o da Certid�o.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
          $lValidou = false;
        }

        // coluna 14 regra 4
        $oDtAtual = new DBDate(date('Y-m-d'));
        if ( $oDtCertidao->getTimeStamp() > $oDtAtual->getTimeStamp() ) {

          $sMsgErro  = "Aluno(a) {$sAluno}: \n";
          $sMsgErro .= 'O campo "Data de emiss�o da certid�o" foi preenchido com valor inv�lido.';
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
          $lValidou = false;
        }
      }
    }

    /**
     * Valida��es referentes ao campo 9
     */
    if ( $oRegistro70->certidao_civil == 2 ) {

      if ( $oRegistro70->numero_matricula == '' ) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "Quando informado Certid�o Civil igual a 'Modelo Novo', o N�mero da Matr�cula deve ser informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }

      $aDocumentosTipoCertidao = array(
        $oRegistro70->tipo_certidao_civil   != '' ? true : false,
        $oRegistro70->numero_termo          != '' ? true : false,
        $oRegistro70->folha                 != '' ? true : false,
        $oRegistro70->livro                 != '' ? true : false,
        $oRegistro70->data_emissao_certidao != '' ? true : false,
        $oRegistro70->uf_cartorio           != '' ? true : false,
        $oRegistro70->municipio_cartorio    != '' ? true : false,
        $oRegistro70->codigo_cartorio       != '' ? true : false
      );

      if ( in_array( true, $aDocumentosTipoCertidao) ) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "Quando informado Certid�o Civil igual a 'Modelo Novo', os campos Tipo de Certid�o Civil, ";
        $sMsgErro .= "N�mero do Termo, Folha, Livro, Data de Emiss�o da Cerdit�o, UF do Cart�rio Munic�pio do Cart�rio ";
        $sMsgErro .= "e C�digo do Cart�rio n�o devem ser preenchidos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }

      /**
       * Valida��es referentes ao campo 18
       *
       */
      if ( $oRegistro70->numero_matricula != '' ) {

        $sCamposAluno = " ed47_i_codigo, ed47_v_nome ";
        $sWhereAluno  = " ed47_certidaomatricula = '{$oRegistro70->numero_matricula}'";
        $sWhereAluno .= " and ed47_i_codigo <> {$oRegistro70->codigo_aluno_entidade}";
        $sSqlAluno    = $oAlunoDao->sql_query_file( null, $sCamposAluno, null, $sWhereAluno );
        $rsAluno      = db_query( $sSqlAluno );

        if ( !$rsAluno || pg_num_rows($rsAluno) > 0 ) {

          $aAlunoMatricula = array();
          for ($iContador=0; $iContador < pg_num_rows($rsAluno); $iContador++) {

            $oAluno = db_utils::fieldsMemory($rsAluno, $iContador);
            $aAlunoMatricula[] = $oAluno->ed47_i_codigo . '-' . $oAluno->ed47_v_nome;
          }

          $sMsgErro  = "Aluno(a) {$sAluno}: \n";
          $sMsgErro .= "N�mero da Matr�cula (Registro Civil - Certid�o Nova) repetido no(s) seguinte(s) aluno(s):\n";
          $sMsgErro .= implode("\n", $aAlunoMatricula);
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
          $lValidou = false;
        }
      }

      try {
         DadosCensoAluno::validarCertidadaoNova($oRegistro70->numero_matricula, $oExportacaoCenso->getAnoCenso(), $oDtNascimento->getAno());
      } catch(Exception $eErroCertidao) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "N�mero da Matr�cula (Registro Civil - Certid�o Nova) inv�lida.".$eErroCertidao->getMessage();
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }
    }

    /**
     * Valida��es referentes ao campo 10
     */
    $oAluno = new Aluno( $oRegistro70->codigo_aluno_entidade );

    if ( $oRegistro70->tipo_certidao_civil == 2 && $oAluno->getIdade() < 11 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Quando informado Tipo de Certid�o igual a 'Casamento', o aluno n�o pode ter idade inferior a 11 anos.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    /**
     * Valida��es referentes ao campo 19
     */
    if ($oRegistro70->numero_cpf != "") {

      if (!DBString::isCPF($oRegistro70->numero_cpf)) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= $oRegistro70->numero_cpf . " n�o � um CPF v�lido";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }
    }

    /**
     * Valida��es referentes ao campo 20
     */
    if (    $oRegistro70->documento_estrangeiro_passaporte != ''
         && $oRegistro60->nacionalidade_aluno != 3 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Quando informado Documento Estrangeiro/Passaporte, a Nacionalidade do aluno deve ser igual a 'Estrangeira'.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    /**
     * Valida��es referentes ao campo 21
     */
    if (!empty($oRegistro70->numero_identificacao_social)) {

      if (!parent::ValidaNIS($oRegistro70->numero_identificacao_social)) {

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "N�mero NIS do aluno � inv�lido.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }

      $sCamposAluno = " ed47_i_codigo, ed47_v_nome ";
      $sWhereAluno  = " ed47_c_nis = '{$oRegistro70->numero_identificacao_social}' ";
      $sWhereAluno .= " and ed47_i_codigo <> {$oRegistro70->codigo_aluno_entidade} ";
      $sSqlAluno    = $oAlunoDao->sql_query_file( null, $sCamposAluno, null, $sWhereAluno );
      $rsAluno      = db_query( $sSqlAluno );

      if ( !$rsAluno || pg_num_rows($rsAluno) > 0 ) {

        $aMsgNIS = array();
        for ($iContador=0; $iContador < pg_num_rows($rsAluno); $iContador++) {

          $oAluno    = db_utils::fieldsMemory($rsAluno, $iContador);
          $aMsgNIS[] = $oAluno->ed47_i_codigo . '-' . $oAluno->ed47_v_nome;
        }

        $sMsgErro  = "Aluno(a) {$sAluno}: \n";
        $sMsgErro .= "N�mero NIS do aluno repetido no(s) seguinte(s) aluno(s):\n";
        $sMsgErro .= implode("\n", $aMsgNIS);
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
        $lValidou = false;
      }
    }

    $aDadosEndereco = array(
      $oRegistro70->cep,
      $oRegistro70->endereco,
      $oRegistro70->uf,
      $oRegistro70->municipio
    );

    if( !empty( $oRegistro70->cep ) ) {

      if( !DBNumber::isInteger( $oRegistro70->cep ) ) {

        $lValidou   = false;
        $sMensagem  = "Aluno(a) {$sAluno}: \n";
        $sMensagem .= "CEP inv�lido. Deve conter somente n�meros.";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ALUNO );
      }

      if (strlen($oRegistro70->cep) < 8) {

        $lValidou = false;
        $sMensagem  = "Aluno(a) {$sAluno}: \n";
        $sMensagem .= "CEP deve conter 8 d�gitos.";
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ALUNO);
      }

      if (preg_match ('/1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8}/', $oRegistro70->cep)) {

        $lValidou   = false;
        $sMensagem  = "Aluno(a) {$sAluno}: \n";
        $sMensagem .= "O Campo CEP foi preenchido com um valor inv�lido. CEP: {$oRegistro70->cep}";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ALUNO );
      }
    }

    $iDadosEnderecoInformado = 0;
    foreach ($aDadosEndereco as $oDadoEndereco) {

      if ( $oDadoEndereco != "" ) {
        $iDadosEnderecoInformado++;
      }
    }

    /**
     * Va�oda��es referentes aos campos 23, 24, 28 e 29
     */
    if ( $iDadosEnderecoInformado > 0 && $iDadosEnderecoInformado < 4 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Ao preencher uma das seguintes informa��es do endere�o residencial (CEP, Endere�o, UF ou Munic�pio) ";
      $sMsgErro .= "todas as outras devem ser informadas.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    /**
     * Va�oda��es referentes aos campos 25
     */
    if ( $oRegistro70->numero != '' && $iDadosEnderecoInformado < 4 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Ao preencher o n�mero do endere�o residencial, os campos CEP, Endere�o, UF e Munic�pio ";
      $sMsgErro .= "devem ser informados.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    /**
     * Va�oda��es referentes aos campos 26
     */
    if ( $oRegistro70->complemento != '' && $iDadosEnderecoInformado < 4 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Ao preencher o complemento do endere�o residencial, os campos CEP, Endere�o, UF e Munic�pio ";
      $sMsgErro .= "devem ser informados.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    /**
     * Va�oda��es referentes aos campos 27
     */
    if ( $oRegistro70->bairro != '' && $iDadosEnderecoInformado < 4 ) {

      $sMsgErro  = "Aluno(a) {$sAluno}: \n";
      $sMsgErro .= "Ao preencher o bairro do endere�o residencial, os campos CEP, Endere�o, UF e Munic�pio ";
      $sMsgErro .= "devem ser informados.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Preenche os dados da DAO do aluno de acordo com o informado na linha do arquivo de importa��o do CENSO
   * @param DBLayoutLinha $oLinha
   * @return cl_aluno
   */
  protected function preencherDaoAluno(DBLayoutLinha $oLinha) {

    $oDaoAluno                         = new cl_aluno();
    $oDaoAluno->ed47_i_censoorgemissrg = "";
    $oDaoAluno->ed47_i_censocartorio   = "";
    $oDaoAluno->ed47_i_pais            = "";
    $oDaoAluno->oid                    = "";
    $oDaoAluno->ed47_c_bolsafamilia    = "N";

    if (!empty($oLinha->nome_mae)) {
      $oDaoAluno->ed47_v_mae =  str_replace(array('�', '�'), array('', ''), $oLinha->nome_mae);
    }

      if (!empty($oLinha->filiacao_1)) {
          $oDaoAluno->ed47_v_mae =  str_replace(array('�', '�'), array('', ''), $oLinha->filiacao_1);
      }

    if (!empty($oLinha->nome_pai)) {
      $oDaoAluno->ed47_v_pai = str_replace(array('�', '�'), array('', ''), $oLinha->nome_pai);
    }

      if (!empty($oLinha->filiacao_2)) {
          $oDaoAluno->ed47_v_pai = str_replace(array('�', '�'), array('', ''), $oLinha->filiacao_2);
      }

    if ($oLinha->data_nascimento  != "") {
      $oDaoAluno->ed47_d_nasc = importacaoCenso::formataData($oLinha->data_nascimento);
    }

    if ($oLinha->sexo != "") {
      $oDaoAluno->ed47_v_sexo = $oLinha->sexo == 1?"M":"F";
    }

    $oDaoAluno->ed47_c_raca = $this->getRaca(trim($oLinha->cor_raca));
    if ($oLinha->pais_origem != "") {
      $oDaoAluno->ed47_i_pais = importacaoCenso::getPais($oLinha->pais_origem);
    }

    if ($oLinha->nacionalidade_aluno != "") {
      $oDaoAluno->ed47_i_nacion = $oLinha->nacionalidade_aluno;
    } else {
      $oDaoAluno->ed47_i_nacion = 1;
    }
    if ($oLinha->uf_nascimento != "") {
      $oDaoAluno->ed47_i_censoufnat = $oLinha->uf_nascimento;
    }

    if ($oLinha->municipio_nascimento != "") {
      $oDaoAluno->ed47_i_censomunicnat = $oLinha->municipio_nascimento;
    }
    $oDaoAluno->ed47_c_codigoinep         = $oLinha->identificacao_unica_aluno;
    $oDaoAluno->ed47_i_filiacao           = $oLinha->filiacao;
    $oDaoAluno->ed47_c_atenddifer         = '3';
    $oDaoAluno->ed47_v_ender              = 'NAO INFORMADO';
    $oDaoAluno->ed47_i_transpublico       = "";
    $oDaoAluno->ed47_situacaodocumentacao = "0";
    $oDaoAluno->ed47_v_nome = str_replace(array('�', '�'), array('', ''), $oLinha->nome_completo);

    return $oDaoAluno;
  }

  /**
   * Atualiza as Necessidades Especiais do aluno
   * @param DBLayoutLinha $oLinha
   */
  protected function atualizarNecessidadesEspeciais(DBLayoutLinha $oLinha) {

    if (isset($oLinha->alunos_deficiencia_transtorno_desenv_superdotacao)) {

      $oDaoAlunoNecessidade = new cl_alunonecessidade();
      $oDaoAlunoNecessidade->excluir(null, "ed214_i_aluno = {$this->getCodigoAluno()}");

      $aNecessidade = array();

      trim($oLinha->tipos_defic_transtorno_cegueira)                == 1 ? $aNecessidade[] = 101 : '';
      trim($oLinha->tipos_defic_transtorno_baixa_visao)             == 1 ? $aNecessidade[] = 102 : '';
      trim($oLinha->tipos_defic_transtorno_surdez)                  == 1 ? $aNecessidade[] = 103 : '';
      trim($oLinha->tipos_defic_transtorno_auditiva)                == 1 ? $aNecessidade[] = 104 : '';
      trim($oLinha->tipos_defic_transtorno_surdocegueira)           == 1 ? $aNecessidade[] = 105 : '';
      trim($oLinha->tipos_defic_transtorno_def_fisica)              == 1 ? $aNecessidade[] = 106 : '';
      trim($oLinha->tipos_defic_transtorno_def_intelectual)         == 1 ? $aNecessidade[] = 107 : '';
      trim($oLinha->tipos_defic_transtorno_def_multipla)            == 1 ? $aNecessidade[] = 108 : '';
      trim($oLinha->tipos_defic_transtorno_def_autismo_infantil)    == 1 ? $aNecessidade[] = 109 : '';
      trim($oLinha->tipos_defic_transtorno_def_asperger)            == 1 ? $aNecessidade[] = 110 : '';
      trim($oLinha->tipos_defic_transtorno_def_sindrome_rett)       == 1 ? $aNecessidade[] = 111 : '';
      trim($oLinha->tipos_defic_transtorno_desintegrativo_infancia) == 1 ? $aNecessidade[] = 112 : '';
      trim($oLinha->tipos_defic_transtorno_altas_habilidades)       == 1 ? $aNecessidade[] = 113 : '';
      $iTam = count($aNecessidade);

      for ($iContNecessidade = 0; $iContNecessidade < $iTam; $iContNecessidade++) {

        if ($aNecessidade[$iContNecessidade] > 0) {

          $oDaoAlunoNecessidade->ed214_i_necessidade = $aNecessidade[$iContNecessidade];
          $oDaoAlunoNecessidade->ed214_c_principal   = 'NAO';
          $oDaoAlunoNecessidade->ed214_i_apoio       = 1;
          $oDaoAlunoNecessidade->ed214_d_data        = 'null';
          $oDaoAlunoNecessidade->ed214_i_tipo        = 1;
          $oDaoAlunoNecessidade->ed214_i_escola      = 'null';
          $oDaoAlunoNecessidade->ed214_i_aluno       = $this->getCodigoAluno();
          $oDaoAlunoNecessidade->incluir(null);

          if ($oDaoAlunoNecessidade->erro_status == '0') {

            throw new Exception("Erro na inclus�o das necessidades do aluno. Erro da classe: ".
                                $oDaoAlunoNecessidade->erro_msg
                               );

          }

        }
      }

      $this->atualizarRecursosAvaliacaoINEP( $oLinha );
    }
  }

  /**
   * Adiciona os Recursos de Avalia��o do INEP do aluno
   * @param DBLayoutLinha $oLinha
   */
  protected function atualizarRecursosAvaliacaoINEP( DBLayoutLinha $oLinha ) {

    $aRecursosAvaliacaoInep        = array();
    $oDaoAlunoRecursoAvaliacaoInep = new cl_alunorecursosavaliacaoinep();
    $oDaoAlunoRecursoAvaliacaoInep->excluir( null, "ed327_aluno = {$this->getCodigoAluno()}");

    trim($oLinha->recurso_auxilio_ledor)             == 1 ? $aRecursosAvaliacaoInep[] = 101 : '';
    trim($oLinha->recurso_auxilio_transcricao)       == 1 ? $aRecursosAvaliacaoInep[] = 102 : '';
    trim($oLinha->recurso_auxilio_interprete)        == 1 ? $aRecursosAvaliacaoInep[] = 103 : '';
    trim($oLinha->recurso_auxilio_interprete_libras) == 1 ? $aRecursosAvaliacaoInep[] = 104 : '';
    trim($oLinha->recurso_auxilio_leitura_labial)    == 1 ? $aRecursosAvaliacaoInep[] = 105 : '';
    trim($oLinha->recurso_auxilio_prova_ampliada_16) == 1 ? $aRecursosAvaliacaoInep[] = 106 : '';
    trim($oLinha->recurso_auxilio_prova_ampliada_20) == 1 ? $aRecursosAvaliacaoInep[] = 107 : '';
    trim($oLinha->recurso_auxilio_prova_ampliada_24) == 1 ? $aRecursosAvaliacaoInep[] = 108 : '';
    trim($oLinha->recurso_auxilio_prova_braille)     == 1 ? $aRecursosAvaliacaoInep[] = 109 : '';
    trim($oLinha->recurso_auxilio_nenhum)            == 1 ? $aRecursosAvaliacaoInep[] = 110 : '';

    for ( $iContador = 0; $iContador < count($aRecursosAvaliacaoInep); $iContador++ ) {

      $oDaoAlunoRecursoAvaliacaoInep->ed327_aluno                 = $this->getCodigoAluno();
      $oDaoAlunoRecursoAvaliacaoInep->ed327_recursosavaliacaoinep = $aRecursosAvaliacaoInep[$iContador];
      $oDaoAlunoRecursoAvaliacaoInep->incluir(null);

      if ( $oDaoAlunoRecursoAvaliacaoInep->erro_status == '0' ) {
        throw new Exception("Erro na inclus�o dos Recursos de Avalia��es do INEP. Erro da classe: {$oDaoAlunoRecursoAvaliacaoInep->erro_msg}");
      }
    }
  }

  /**
   * Validar coluna data de nascimento
   *
   * OBS.: regras que n�o precisam validar
   *  regra 2 e 3 - vem de uma coluna date no banco de dados
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return booelan
   */
  protected static function validaRegistro60Coluna6($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;
    if ( empty($oDadosAluno->registro60->data_nascimento) ) {

      $sMsgErro  = "{$sAluno} O campo \"Data de nascimento\" � uma informa��o obrigat�ria.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    $oDtNascimento = new DBDate($oDadosAluno->registro60->data_nascimento);
    $oDataAtual    = new DBDate(date("Y-m-d"));
    $oIntervalo    = DBDate::getIntervaloEntreDatas($oDtNascimento, $oDataAtual);

    // regra 4
    if ( $oIntervalo->y > 106 )  {

      $sMsgErro  = "{$sAluno} O campo \"Data de nascimento\" foi preenchido com valor inv�lido.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }
    // regra 5
    $oDtCenso = new DBDate($oExportacaoCenso->getDataCenso());
    if ( $oDtCenso->getTimeStamp() < $oDtNascimento->getTimeStamp() ) {

      $sMsgErro  = "{$sAluno} O campo \"Data de nascimento\" esta maior que a data do censo.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Validar coluna Aluno com defici�ncia, transtorno global do desenvolvimento ou altas habilidades/superdota��o
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna16($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // coluna 16 registro 60  regra 1 e 2
    if ( $oDadosAluno->registro60->alunos_deficiencia_transtorno_desenv_superdotacao === '' ||
         !in_array($oDadosAluno->registro60->alunos_deficiencia_transtorno_desenv_superdotacao, array(0,1))) {

      $sMsgErro  = "{$sAluno} O campo \"Aluno com defici�ncia, transtorno global do desenvolvimento ou altas ";
      $sMsgErro .= "habilidades/superdota��o\" foi preenchido com valor inv�lido.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    foreach( $oDadosAluno->registro80 as $iIndice => $oMatricula ) {

      $oTurma  = DadosCensoAluno::getTurmaAluno( $oExportacaoCenso, $oMatricula->codigo_turma_entidade_escola );

      // coluna 16 registro 60  regra 3
      if ( $oDadosAluno->registro60->alunos_deficiencia_transtorno_desenv_superdotacao == 0 &&
           $oTurma->modalidade_turma == 2 ){

        $sMsgErro  = "{$sAluno} O aluno foi vinculado em turma de Educa��o Especial mas informou que n�o possui ";
        $sMsgErro .= "defici�ncia, transtorno global do desenvolvimento ou altas habilidades/superdota��o.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }

      // coluna 16 registro 60  regra 4
      if ( $oDadosAluno->registro60->alunos_deficiencia_transtorno_desenv_superdotacao == 0 &&
           $oTurma->tipo_atendimento == 5 ) {

        $sMsgErro  = "{$sAluno} O aluno foi vinculado em turma de AEE mas informou que n�o possui defici�ncia, ";
        $sMsgErro .= "transtorno global do desenvolvimento ou altas habilidades/superdota��o.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }
    }
    return $lValidou;
  }


  /**
   * Realiza as valida��es das regras 1, 2 e 3 para todos as colunas de 16 a 29 visto que s�o iguais
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna17a29($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    $aDeficiencias = array(
      "Cegueira"                              => $oDadosAluno->registro60->tipos_defic_transtorno_cegueira,
      "Baixa vis�o"                           => $oDadosAluno->registro60->tipos_defic_transtorno_baixa_visao,
      "Surdez"                                => $oDadosAluno->registro60->tipos_defic_transtorno_surdez,
      "Defici�ncia auditiva"                  => $oDadosAluno->registro60->tipos_defic_transtorno_auditiva,
      "Surdocegueira"                         => $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira,
      "Defici�ncia F�sica"                    => $oDadosAluno->registro60->tipos_defic_transtorno_def_fisica,
      "Defici�ncia Intelectual"               => $oDadosAluno->registro60->tipos_defic_transtorno_def_intelectual,
      "Defici�ncia M�ltipla"                  => $oDadosAluno->registro60->tipos_defic_transtorno_def_autismo_infantil,
      "Autismo Infantil"                      => $oDadosAluno->registro60->tipos_defic_transtorno_def_asperger,
      "S�ndrome de Asperger"                  => $oDadosAluno->registro60->tipos_defic_transtorno_def_sindrome_rett,
      "S�ndrome de Rett"                      => $oDadosAluno->registro60->tipos_defic_transtorno_desintegrativo_infancia,
      "Transtorno desintegrativo da inf�ncia" => $oDadosAluno->registro60->tipos_defic_transtorno_altas_habilidades,
      "Superdota��o"                          => $oDadosAluno->registro60->tipos_defic_transtorno_def_multipla
    );

    foreach ($aDeficiencias as $sDeficiencia => $mValor) {

      // regra 1
      if ( $mValor === '' && $oDadosAluno->registro60->alunos_deficiencia_transtorno_desenv_superdotacao == 1) {

        $sMsgErro  = "{$sAluno} O campo \"{$sDeficiencia}\" deve ser preenchido quando o campo \"Aluno com defici�ncia, ";
        $sMsgErro .= "transtorno global do desenvolvimento ou altas habilidades/superdota��o\" for igual a 1 (Sim).";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }

      // regra 2
      if ( $mValor !== '' && $oDadosAluno->registro60->alunos_deficiencia_transtorno_desenv_superdotacao != 1) {

        $sMsgErro  = "{$sAluno} O campo \"{$sDeficiencia}\" n�o pode ser preenchido quando o campo \"Aluno com defici�ncia, ";
        $sMsgErro .= "transtorno global do desenvolvimento ou altas habilidades/superdota��o\" for diferente de 1 (Sim).";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }

      // regra 3
      if ( $mValor == 1 && !in_array($oDadosAluno->registro60->tipos_defic_transtorno_cegueira, array(0,1)) ) {

        $sMsgErro = "{$sAluno}O campo \"{$sDeficiencia}\" foi preenchido com valor inv�lido.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - Cegueira
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna17($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_cegueira !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_baixa_visao == 1) {

      $sMsgErro = "{$sAluno}O campo \"Cegueira\" � incompat�vel com o campo \"Baixa vis�o\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_cegueira !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_surdez == 1) {

      $sMsgErro = "{$sAluno}O campo \"Cegueira\" � incompat�vel com o campo \"Surdez\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }
    // regra 6
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_cegueira !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira == 1) {

      $sMsgErro = "{$sAluno}O campo \"Cegueira\" � incompat�vel com o campo \"Surdocegueira\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }
    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna  - Baixa vis�o
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna18($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_baixa_visao !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira == 1 ) {

      $sMsgErro = "{$sAluno}O campo \"Baixa vis�o\" � incompat�vel com o campo \"Surdocegueira\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna  - Surdez
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna19($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_surdez !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_auditiva == 1 ) {

      $sMsgErro = "{$sAluno}O campo \"Surdez\" � incompat�vel com o campo \"Defici�ncia auditiva\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_surdez !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira == 1 ) {

      $sMsgErro = "{$sAluno}O campo \"Surdez\" � incompat�vel com o campo \"Surdocegueira\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - Defici�ncia auditiva
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna20($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_auditiva !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira == 1 ) {

      $sMsgErro = "{$sAluno}O campo \"Defici�ncia auditiva\" � incompat�vel com o campo \"Surdocegueira\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - Defici�ncia M�ltipla
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * colunas
   *   17 - tipos_defic_transtorno_cegueira
   *   18 - tipos_defic_transtorno_baixa_visao
   *   19 - tipos_defic_transtorno_surdez
   *   20 - tipos_defic_transtorno_auditiva
   *   21 - tipos_defic_transtorno_surdocegueira
   *   22 - tipos_defic_transtorno_def_fisica
   *   23 - tipos_defic_transtorno_def_intelectual
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna24($oExportacaoCenso, $oDadosAluno) {

    $oR60     = $oDadosAluno->registro60;
    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    $aCombinacaoes = array(
      ($oR60->tipos_defic_transtorno_cegueira == 1) && ($oR60->tipos_defic_transtorno_auditiva == 1),
      ($oR60->tipos_defic_transtorno_cegueira == 1) && ($oR60->tipos_defic_transtorno_def_fisica == 1),
      ($oR60->tipos_defic_transtorno_cegueira == 1) && ($oR60->tipos_defic_transtorno_def_intelectual == 1),
      ($oR60->tipos_defic_transtorno_baixa_visao == 1) && ($oR60->tipos_defic_transtorno_surdez == 1),
      ($oR60->tipos_defic_transtorno_baixa_visao == 1) && ($oR60->tipos_defic_transtorno_auditiva == 1),
      ($oR60->tipos_defic_transtorno_baixa_visao == 1) && ($oR60->tipos_defic_transtorno_def_fisica == 1),
      ($oR60->tipos_defic_transtorno_baixa_visao == 1) && ($oR60->tipos_defic_transtorno_def_intelectual == 1),
      ($oR60->tipos_defic_transtorno_surdez == 1) && ($oR60->tipos_defic_transtorno_def_fisica == 1),
      ($oR60->tipos_defic_transtorno_surdez == 1) && ($oR60->tipos_defic_transtorno_def_intelectual == 1),
      ($oR60->tipos_defic_transtorno_auditiva == 1) && ($oR60->tipos_defic_transtorno_def_fisica == 1),
      ($oR60->tipos_defic_transtorno_auditiva == 1) && ($oR60->tipos_defic_transtorno_def_intelectual == 1),
      ($oR60->tipos_defic_transtorno_surdocegueira == 1) && ($oR60->tipos_defic_transtorno_def_fisica == 1),
      ($oR60->tipos_defic_transtorno_surdocegueira == 1) && ($oR60->tipos_defic_transtorno_def_intelectual == 1),
      ($oR60->tipos_defic_transtorno_def_fisica == 1) && ($oR60->tipos_defic_transtorno_def_intelectual == 1),
    );
    // regra 4
    if ( $oR60->tipos_defic_transtorno_def_multipla === 0 && in_array(true, $aCombinacaoes)) {

      $sMsgErro  = "{$sAluno} O campo \"Defici�ncia M�ltipla\" n�o foi preenchido com 1 (Sim) e as defici�ncias ";
      $sMsgErro .= "combinadas acarretam em defici�ncia m�ltipla.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oR60->tipos_defic_transtorno_def_multipla == 1 && !in_array(true, $aCombinacaoes)) {

      $sMsgErro  = "{$sAluno} O campo \"Defici�ncia M�ltipla\" preenchido com 1 (Sim) mas as defici�ncias informadas ";
      $sMsgErro .= "n�o acarretam em defici�ncia m�ltipla.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - Autismo Infantil
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna25($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_def_autismo_infantil !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_def_asperger == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"Autismo Infantil\" � incompat�vel com o campo \"S�ndrome de Asperger\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_def_autismo_infantil !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_def_sindrome_rett == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"Autismo Infantil\" � incompat�vel com o campo \"S�ndrome de Rett\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 6
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_def_autismo_infantil !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_desintegrativo_infancia == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"Autismo Infantil\" � incompat�vel com o campo \"Transtorno desintegrativo da inf�ncia\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - S�ndrome de Asperger
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna26($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_def_asperger !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_def_sindrome_rett == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"S�ndrome de Asperger\" � incompat�vel com o campo \"S�ndrome de Rett\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_def_asperger !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_desintegrativo_infancia == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"S�ndrome de Asperger\" � incompat�vel com o campo \"Transtorno desintegrativo da inf�ncia\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }


    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - S�ndrome de Rett
   *   para outras regras ver self::validaRegistro60Coluna17a29
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna27($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->tipos_defic_transtorno_def_sindrome_rett !== 0 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_desintegrativo_infancia == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"S�ndrome de  Rett\" � incompat�vel com o campo \"Transtorno desintegrativo da inf�ncia\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }
    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna - 30 a 39
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna30a39($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    $aDeficiencias = array(
      $oDadosAluno->registro60->tipos_defic_transtorno_cegueira,
      $oDadosAluno->registro60->tipos_defic_transtorno_baixa_visao,
      $oDadosAluno->registro60->tipos_defic_transtorno_surdez,
      $oDadosAluno->registro60->tipos_defic_transtorno_auditiva,
      $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_fisica,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_intelectual,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_autismo_infantil,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_asperger,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_sindrome_rett,
      $oDadosAluno->registro60->tipos_defic_transtorno_desintegrativo_infancia,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_multipla,
    );

    $aRecursos = array(
      $oDadosAluno->registro60->recurso_auxilio_ledor,
      $oDadosAluno->registro60->recurso_auxilio_transcricao,
      $oDadosAluno->registro60->recurso_auxilio_interprete,
      $oDadosAluno->registro60->recurso_auxilio_interprete_libras,
      $oDadosAluno->registro60->recurso_auxilio_leitura_labial,
      $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_16,
      $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_20,
      $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_24,
      $oDadosAluno->registro60->recurso_auxilio_prova_braille,
      $oDadosAluno->registro60->recurso_auxilio_nenhum,
    );

    // colunas 30 a 39 regra 1
    if ( in_array(1, $aDeficiencias) ) {

      $lTemVinculoEtapaModalidade = false;
      foreach( $oDadosAluno->registro80 as $iIndice => $oMatricula ) {

        $oTurma = DadosCensoAluno::getTurmaAluno( $oExportacaoCenso, $oMatricula->codigo_turma_entidade_escola );

        if ( in_array($oTurma->etapa_ensino_turma, array(16, 7, 18, 11, 41, 27, 28, 32, 33, 37, 38)) &&
             in_array($oTurma->modalidade_turma, array(1, 4) ) ) {

          $lTemVinculoEtapaModalidade = true;
          if ( !in_array(1, $aRecursos) ) {

            $sMsgErro  = "{$sAluno} Os Recursos necess�rios para a participa��o do aluno em avalia��es do Inep (prova ";
            $sMsgErro .= "Brasil, SAEB, Outros) n�o foram informados quando deveriam ser informados.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
            $lValidou = false;
          }
        }
      }

      // regra 1 de cada uma das colunas de recursos 30 a 39
      // Deve ser preenchido quando pelo menos um dos campos de 17 a 28 (Tipos de defici�ncia, transtorno global do
      // desenvolvimento) for igual a 1 (Sim) e o aluno possuir v�nculo na escola

      if ( in_array('', $aRecursos, true) && $lTemVinculoEtapaModalidade) {

        $sMsgErro  = "{$sAluno} Os campos \"Recursos necess�rios para a participa��o do aluno em avalia��es do Inep (prova ";
        $sMsgErro .= "Brasil, SAEB, Outros)\" deve ser preenchido quando o aluno possuir defici�ncia ou transtorno ";
        $sMsgErro .= "global do desenvolvimento.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }
    }

    // regra 1 de cada uma das colunas de recursos 30 a 39
    // Deve ser nulo quando todos os campos de 17 a 28 (Tipos de defici�ncia, transtorno global do desenvolvimento)
    // forem diferentes de 1 (Sim).
    if ( !in_array('', $aRecursos, true) && !in_array(1, $aDeficiencias) ) {

      $sMsgErro  = "{$sAluno} Os campos \"Recursos necess�rios para a participa��o do aluno em avalia��es do Inep (prova ";
      $sMsgErro .= "Brasil, SAEB, Outros)\" n�o pode ser preenchido quando o aluno possuir defici�ncia ou transtorno ";
      $sMsgErro .= "global do desenvolvimento.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // colunas 30 a 39 regra 2
    unset($aRecursos[1]);
    if ( $oDadosAluno->registro60->recurso_auxilio_transcricao == 1 &&
         !in_array(1, $aRecursos) &&
         ( $oDadosAluno->registro60->tipos_defic_transtorno_cegueira == 1 ||
           $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira == 1) ) {

      $sMsgErro  = "{$sAluno} Para alunos com cegueira ou surdocegueira n�o pode ser informado apenas aux�lio ";
      $sMsgErro .= "transcri��o como recurso necess�rio para a participa��o do aluno em avalia��es do INEP.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna 30 - Aux�lio ledor e 31 - Aux�lio transcri��o
   *   para outras regras ver self::validaRegistro60Coluna30a39
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna30e31($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    $aDeficiencias = array(
      $oDadosAluno->registro60->tipos_defic_transtorno_cegueira,
      $oDadosAluno->registro60->tipos_defic_transtorno_baixa_visao,
      $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_fisica,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_intelectual,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_autismo_infantil,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_asperger,
      $oDadosAluno->registro60->tipos_defic_transtorno_def_sindrome_rett,
      $oDadosAluno->registro60->tipos_defic_transtorno_desintegrativo_infancia,
    );

    // coluna 30 regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_ledor == 1 && !in_array(1, $aDeficiencias) ) {

      $sMsgErro  = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso Aux�lio ledor.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 31 regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_transcricao == 1 && !in_array(1, $aDeficiencias) ) {

      $sMsgErro  = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso Aux�lio transcri��o.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 30 regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_ledor !== 0 && $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Aux�lio ledor\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 31 regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_transcricao !== 0 && $oDadosAluno->registro60->recurso_auxilio_nenhum == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Aux�lio transcri��o\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 30 regra 6
    if ( $oDadosAluno->registro60->recurso_auxilio_ledor == 1 && $oDadosAluno->registro60->tipos_defic_transtorno_surdez == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Aux�lio ledor\" n�o pode ser preenchido com 1 (Sim) quando o campo \"Surdez\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna 32 - Guia-Int�rprete
   *   para outras regras ver self::validaRegistro60Coluna30a39
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna32($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_interprete == 1 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira != 1 ) {

      $sMsgErro = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso Guia-Int�rprete.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_interprete !== 0 &&
         $oDadosAluno->registro60->recurso_auxilio_leitura_labial == 1 ) {

      $sMsgErro = "{$sAluno} O campo \"Guia-Int�rprete\" � incompat�vel com o campo \"Leitura Labial\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 6
    if ( $oDadosAluno->registro60->recurso_auxilio_interprete !== 0 &&
         $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Guia-Int�rprete\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }


  /**
   * Realiza as valida��es da coluna 33 - Int�rprete de Libras e 34 - Leitura Labial
   *   para outras regras ver self::validaRegistro60Coluna30a39
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna33e34($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    $aDeficiencias = array(
      $oDadosAluno->registro60->tipos_defic_transtorno_surdez,
      $oDadosAluno->registro60->tipos_defic_transtorno_auditiva,
      $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira,
    );

    // coluna 33 - regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_interprete_libras == 1 && !in_array(1, $aDeficiencias) ) {

      $sMsgErro  = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso Int�rprete de Libras.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 34 - regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_leitura_labial == 1 && !in_array(1, $aDeficiencias) ) {

      $sMsgErro  = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso Leitura Labial.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 33 - regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_interprete_libras !== 0 && $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Int�rprete de Libras\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 34 - regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_leitura_labial !== 0 && $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Leitura Labial\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 33 - regra 6
    if ( $oDadosAluno->registro60->recurso_auxilio_interprete_libras == 1 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_cegueira == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Int�rprete de Libras\" n�o pode ser preenchido com 1 (Sim) quando o campo \"Cegueira\"";
      $sMsgErro .= " for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 34 - regra 6
    if ( $oDadosAluno->registro60->recurso_auxilio_leitura_labial == 1 &&
         $oDadosAluno->registro60->tipos_defic_transtorno_cegueira == 1 ) {

      $sMsgErro  = "{$sAluno} O campo \"Leitura Labial\" n�o pode ser preenchido com 1 (Sim) quando o campo \"Cegueira\"";
      $sMsgErro .= " for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }


  /**
   * Realiza as valida��es das colunas:
   *  35 - Prova Ampliada (Fonte Tamanho 16)
   *  36 - Prova Ampliada (Fonte Tamanho 20)
   *  37 - Prova Ampliada (Fonte Tamanho 24)
   *  38 - Prova em Braille
   *
   *   para outras regras ver self::validaRegistro60Coluna30a39
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna35a38($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    $aRecursos = array(
      "Prova Ampliada (Fonte Tamanho 16)" => $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_16,
      "Prova Ampliada (Fonte Tamanho 20)" => $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_20,
      "Prova Ampliada (Fonte Tamanho 24)" => $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_24,
    );

    $aDeficiencias = array(
      $oDadosAluno->registro60->tipos_defic_transtorno_baixa_visao,
      $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira,
    );

    // valida regras comuns entre as colunas 35, 36 e 37
    foreach ($aRecursos as $sRescurso => $sValue) {

      // regra 4 Deve ser diferente de 1 (Sim) quando os campos 18 (Baixa vis�o) e 21 (Surdocegueira) forem diferentes de 1 (Sim).
      if ( $sValue == 1 && !in_array(1, $aDeficiencias) ) {

        $sMsgErro = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso {$sRescurso}.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }

      // regra Deve ser preenchido com 0 (N�o) quando o campo 39 (Nenhum) for igual a 1 (Sim)
      if ( $sValue !== 0 && $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 ) {

        $sMsgErro  = "{$sAluno} O campo \"{$sRescurso}\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
        $sMsgErro .= "for igual a 1 (Sim).";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }

      // Deve ser preenchido com 0 (N�o) quando o campo 38 (Prova em Braille) for igual a 1 (Sim)
      if ( $sValue == 1 && $oDadosAluno->registro60->recurso_auxilio_prova_braille == 1) {

        $sMsgErro  = "{$sAluno} O campo \"{$sRescurso}\" � incompat�vel com o campo \"Prova em Braille\".";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }

      // regra Deve ser diferente de com 1 (Sim) quando o campo 17 (Cegueira) for igual a 1 (Sim).
      if ( $sValue == 1 && $oDadosAluno->registro60->tipos_defic_transtorno_cegueira == 1) {

        $sMsgErro  = "{$sAluno} O campo \"{$sRescurso}\" n�o pode ser preenchido com 1 (Sim) quando o campo \"Cegueira\"";
        $sMsgErro .= " for igual a 1 (Sim).";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }
    }

    // coluna 35 regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_16 !== 0 &&
         $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_20 == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Prova Ampliada (Fonte Tamanho 16)\" � incompat�vel com o campo ";
      $sMsgErro .= "\"Prova Ampliada (Fonte Tamanho 20)\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 35 regra 6
    if ( $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_16 !== 0 &&
         $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_24 == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Prova Ampliada (Fonte Tamanho 16)\" � incompat�vel com o campo ";
      $sMsgErro .= "\"Prova Ampliada (Fonte Tamanho 24)\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 36 regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_20 !== 0 &&
         $oDadosAluno->registro60->recurso_auxilio_prova_ampliada_24 == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Prova Ampliada (Fonte Tamanho 20)\" � incompat�vel com o campo ";
      $sMsgErro .= "\"Prova Ampliada (Fonte Tamanho 24)\".";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 38 regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_prova_braille == 1 &&
         (    $oDadosAluno->registro60->tipos_defic_transtorno_cegueira != 1
           && $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira != 1) ) {

      $sMsgErro = "{$sAluno} Combina��o de tipos de defici�ncia incompat�veis com o recurso Prova em Braille.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // coluna 38 regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_prova_braille !== 0 &&
         $oDadosAluno->registro60->recurso_auxilio_nenhum == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Prova em Braille\" deve ser preenchido com 0 (N�o) quando o campo \"Nenhum\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

  /**
   * Realiza as valida��es da coluna 39 - Nenhum
   *   para outras regras ver self::validaRegistro60Coluna30a39
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro60Coluna39($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $lValidou = true;

    // regra 4
    if ( $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 && $oDadosAluno->registro60->tipos_defic_transtorno_cegueira == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Nenhum\" n�o pode ser preenchido com 1 (Sim) quando o campo \"Cegueira\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    // regra 5
    if ( $oDadosAluno->registro60->recurso_auxilio_nenhum == 1 && $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira == 1) {

      $sMsgErro  = "{$sAluno} O campo \"Nenhum\" n�o pode ser preenchido com 1 (Sim) quando o campo \"Surdocegueira\" ";
      $sMsgErro .= "for igual a 1 (Sim).";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
      $lValidou = false;
    }

    return $lValidou;
  }

    /**
   * Realiza as valida��es da coluna 10 - Recebe escolariza��o em outro espa�o (diferente da escola)
   *
   * @param  IExportacaoCenso $oExportacaoCenso  Dados da exporta��o
   * @param  stdClass         $oDadosAluno       Dados do aluno registros 60, 70, 80
   * @return boolean
   */
  protected static function validaRegistro80Coluna10($oExportacaoCenso, $oDadosAluno) {

    $sAluno   = "Aluno(a) {$oDadosAluno->registro60->codigo_aluno_entidade_escola} - {$oDadosAluno->registro60->nome_completo}:\n";
    $sAluno  .= 'O campo "Recebe escolariza��o em outro espa�o (diferente da escola)"';
    $lValidou = true;

    foreach ($oDadosAluno->registro80 as $oRegistro80) {

      $oDadosTurma = DadosCensoAluno::getTurmaAluno( $oExportacaoCenso, $oRegistro80->codigo_turma_entidade_escola );

      // regra 5
      if (     $oRegistro80->tipo_turma == 'NORMAL'
            && $oRegistro80->recebe_escolarizacao_outro_espaco != 1
            && $oDadosTurma->tipo_atendimento == 1
         ) {

        $sMsgErro  = "{$sAluno} deve ser preenchido com 1 (Em hospital) quando o tipo de atendimento da turma for em ";
        $sMsgErro .= "classe hospitalar.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCenso2015::LOG_ALUNO);
        $lValidou = false;
      }
    }

    return $lValidou;
  }
}

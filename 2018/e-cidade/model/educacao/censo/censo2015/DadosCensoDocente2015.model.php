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

define( "MENSAGENS_DADOSCENSODOCENTE2015", "educacao.escola.DadosCensoDocente2015." );
define('GRAU_LICENCIATURA', 3);
define('GRAU_BACHARELADO',  2);
define('GRAU_TECNOLOGICO',  1);

class DadosCensoDocente2015 extends DadosCensoDocente {

  private   $sDadosDocente          = '';
  private   $oExportacaoCenso       = '';
  protected $aEtapasCensoTurma      = array(1, 2, 3, 65);
  protected static $aCursosFormacao = array();

  /**
   * Valida os dados do arquivo
   * @param IExportacaoCenso $oExportacaoCenso  Importacao do censo
   * @return boolean
   */
  public static function validarDados( IExportacaoCenso $oExportacaoCenso ) {

    $lDadosValidos    = true;
    $oExportacaoCenso = $oExportacaoCenso;
    $aDadosDocente    = $oExportacaoCenso->getDadosProcessadosDocente();

    DadosCensoDocente2015::getGrauCurso();

    foreach( $aDadosDocente as $oDadosCensoDocente ) {

      $sDadosDocente  = $oDadosCensoDocente->registro30->numcgm . " - " . $oDadosCensoDocente->registro30->nome_completo;
      $sDadosDocente .= " - Data de Nascimento: " . $oDadosCensoDocente->registro30->data_nascimento;

      $oRegistro30 = $oDadosCensoDocente->registro30;
      $oRegistro40 = $oDadosCensoDocente->registro40;
      $oRegistro50 = $oDadosCensoDocente->registro50;
      $aRegistro51 = $oDadosCensoDocente->registro51;

      if( !DadosCensoDocente2015::validacoesRegistro30( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 ) ) {
        $lDadosValidos = false;
      }

      if( !DadosCensoDocente2015::validacoesRegistro40( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 ) ) {
        $lDadosValidos = false;
      }

      if( !DadosCensoDocente2015::validacoesRegistro50( $sDadosDocente, $oExportacaoCenso, $oRegistro50, $oRegistro30 ) ) {
        $lDadosValidos = false;
      }

      if( !DadosCensoDocente2015::validacoesRegistro51( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $aRegistro51 ) ) {
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes ao Registro 30
   * @param $oRegistro30
   * @return bool
   * @throws Exception
   */
  static function validacoesRegistro30( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 ) {

    $lDadosValidos    = true;
    $aStatusValidacao = array();

    if( $oRegistro30->codigo_docente_entidade_escola == '' ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Códido do docente na escola não informado.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    $sNome = trim( $oRegistro30->nome_completo );
    if( !DBString::isNomeValido( $sNome, DBString::NOME_REGRA_2 ) ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "O nome deve ser composto de nome e sobrenome.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    if( empty($oRegistro40->numero_cpf) && !DBString::isNomeValido( $sNome, DBString::NOME_REGRA_4 ) ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "O nome não deve conter 4 letras repetidas em sequência.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }



    if( empty( $oRegistro30->data_nascimento ) ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Data de nascimento não informada.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    if( !empty( $oRegistro30->data_nascimento ) ) {

      $oDataNascimento = new DBDate( $oRegistro30->data_nascimento );
      $sDataAtual      = date( 'd/m/Y' );
      $oDataAtual      = new DBDate( $sDataAtual );
      $iIntervalo      = DBDate::calculaIntervaloEntreDatas( $oDataAtual, $oDataNascimento, 'y' );

      if( $iIntervalo < 14 || $iIntervalo > 95 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Idade do docente não pode ser menor que 14 ou maior que 95 anos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    if(    $oRegistro30->pais_origem == 76
        && $oRegistro30->nacionalidade_docente != 1
        && $oRegistro30->nacionalidade_docente != 2 ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "País de origem deve ser BRASIL quando selecionada nacionalidade: \n";
      $sMsgErro .= " - Brasileira;\n - Brasileira nascido no Exterior.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    if (    $oRegistro30->nacionalidade_docente == 3
         && $oRegistro30->pais_origem == 76
       ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "País de origem deve diferente de BRASIL quando nacionalidade:\n";
      $sMsgErro .= " - Estrangeira.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    /**
     * Chamado método para validações especifícas referentes a Necessidades Especiais
     */
    if( !DadosCensoDocente2015::validacoesNecessidadesEspeciais( $sDadosDocente, $oExportacaoCenso, $oRegistro30 ) ) {
      $lDadosValidos = false;
    }


    $aStatusValidacao[] = self::validarRegistro30Coluna6(  $sDadosDocente, $oExportacaoCenso, $oRegistro30 );
    $aStatusValidacao[] = self::validarRegistro30Coluna11( $sDadosDocente, $oExportacaoCenso, $oRegistro30 );
    $aStatusValidacao[] = self::validarRegistro30Coluna12( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 );
    $aStatusValidacao[] = self::validarRegistro30Coluna13( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 );
    $aStatusValidacao[] = self::validarRegistro30Coluna16( $sDadosDocente, $oExportacaoCenso, $oRegistro30 );
    $aStatusValidacao[] = self::validarRegistro30Coluna17( $sDadosDocente, $oExportacaoCenso, $oRegistro30 );

    if ( $lDadosValidos ) {
      $lDadosValidos = array_reduce( $aStatusValidacao, 'validaVerdadeiro');
    }

    return $lDadosValidos;
  }

  /**
   * Valiações referentes ao e-mail do docente.
   *   - Valida o Tamanho máximo de 100 caracteres
   *   - Valida se o formato do e-mail está correto
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro30
   * @return boolean
   */
  protected static function validarRegistro30Coluna6( $sDadosDocente, $oExportacaoCenso, $oRegistro30 ) {

    $lDadosValidos = true;

    $sEmail = trim( $oRegistro30->email );

    if( !empty( $sEmail ) ) {

      if( !DBString::validarTamanhoMaximo( $sEmail, 100 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O email excede o limite de caracteres permitidos( 100 ).";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( !DBString::isEmail( $sEmail ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O email não é válido - Aceitos somente os seguintes caracteres entre parentêses:\n";
        $sMsgErro .= "(ABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789 @ . - _). Deve possuir os caracteres \"@\" e \".\", ";
        $sMsgErro .= "e caracteres alfanuméricos antes e depois de cada.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes a filiação do docente.
   *   - Valida se foi informado;
   *   - Valida se o nome do pai ou da mãe foi informado;
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro30
   * @return boolean
   */
  protected static function validarRegistro30Coluna11( $sDadosDocente, $oExportacaoCenso, $oRegistro30 ) {

    $lDadosValidos = true;

    if ( $oRegistro30->filiacao === '' ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= 'O campo "Filiação" é uma informação obrigatória.';
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    if ( $oRegistro30->filiacao != '' && $oRegistro30->filiacao_1 == '' && $oRegistro30->filiacao_2 == '' ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= 'O campo "Filiação 1" ou o campo "Filiação 2" deve ser preenchido quando o campo "Filiação"';
      $sMsgErro .= ' for igual a 1 (Filiação 1 e/ou Filiação 2).';
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    return $lDadosValidos;
  }

  /**
   * Valida a filiação 1 (nome da mãe preferencialmente) do docente
   *   - Valida se foi informado que docente não possui filiação e foi informado filiação 1;
   *   - Valida para que o nome da filiação 1 possua mais de uma palavra quando não informado o CPF do mesmo;
   *   - Valida para que o nome da filiação 1 não possua mais de 4 caracteres iguais repetidos quando não informado o CPF do mesmo;
   *   - Valida para que o nome da filiação 1 possua somente caracteres válidos;
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro30
   * @param stdClass $oRegistro40
   * @return boolean
   */
  protected static function validarRegistro30Coluna12( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 ) {

    $lDadosValidos = true;

    if ( $oRegistro30->filiacao == 0 &&  $oRegistro30->filiacao_1 != '' ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Filiação 1" não pode ser preenchido quando o campo 9 (Filiação) for igual a 0 (Não declarado/Ignorado).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    if( !empty( $oRegistro30->filiacao_1 ) ) {

      $sFiliacao1 = trim( $oRegistro30->filiacao_1 );
      if( empty($oRegistro40->numero_cpf) && !DBString::isNomeValido( $sFiliacao1, DBString::NOME_REGRA_3 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O nome da mãe deve ser composto de nome e sobrenome.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( empty($oRegistro40->numero_cpf) && !DBString::isNomeValido( $sFiliacao1, DBString::NOME_REGRA_4 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O nome da mãe não deve conter 4 letras repetidas em sequência.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( !DBString::isNomeValido( $sFiliacao1, DBString::NOME_REGRA_5 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Filiação 1" foi preenchido com valor inválido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

    /**
   * Valida a filiação 2 (nome do pai preferencialmente) do docente
   *   - Valida se foi informado que docente não possui filiação e foi informado filiação 2;
   *   - Valida para que o nome da filiação 2 possua mais de uma palavra quando não informado o CPF do mesmo;
   *   - Valida para que o nome da filiação 2 não possua mais de 4 caracteres iguais repetidos quando não informado o CPF do mesmo;
   *   - Valida para que o nome da filiação 2 possua somente caracteres válidos;
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro30
   * @param stdClass $oRegistro40
   * @return boolean
   */
  protected static function validarRegistro30Coluna13( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 ) {

    $lDadosValidos = true;

    if ( $oRegistro30->filiacao == 0 &&  $oRegistro30->filiacao_2 != '' ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Filiação 1" não pode ser preenchido quando o campo 9 (Filiação) for igual a 0 (Não declarado/Ignorado).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    if( !empty( $oRegistro30->filiacao_2 ) ) {

      $sFiliacao2 = trim( $oRegistro30->filiacao_2 );
      if( empty($oRegistro40->numero_cpf) && !DBString::isNomeValido( $sFiliacao2, DBString::NOME_REGRA_3 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O nome da mãe deve ser composto de nome e sobrenome.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( empty($oRegistro40->numero_cpf) && !DBString::isNomeValido( $sFiliacao2, DBString::NOME_REGRA_4 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O nome da mãe não deve conter 4 letras repetidas em sequência.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( !DBString::isNomeValido( $sFiliacao2, DBString::NOME_REGRA_5 ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Filiação 2" foi preenchido com valor inválido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes a UF de nascimento do docente
   *   - Valida para que seja informado a UF somente se a nascionalidade do docente seja igual a Brasileira.
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro30
   * @return boolean
   */
  protected static function validarRegistro30Coluna16( $sDadosDocente, $oExportacaoCenso, $oRegistro30 ) {

    $lDadosValidos = true;

    if( $oRegistro30->nacionalidade_docente == 1 && empty( $oRegistro30->uf_nascimento ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "País de origem informado como BRASIL. É necessário informar a UF de nascimento.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    if( $oRegistro30->nacionalidade_docente != 1 && !empty( $oRegistro30->uf_nascimento ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "País de origem informado como BRASIL. É necessário informar a UF de nascimento.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes ao município de nascimento do docente
   *   - Valida para que seja informado o município somente se a nascionalidade do docente seja igual a Brasileira.
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro30
   * @return boolean
   */
  protected static function validarRegistro30Coluna17( $sDadosDocente, $oExportacaoCenso, $oRegistro30 ) {

    $lDadosValidos = true;

    if( $oRegistro30->nacionalidade_docente == 1 && empty( $oRegistro30->municipio_nascimento ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "País de origem informado como BRASIL. É necessário informar a UF de nascimento.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    if( $oRegistro30->nacionalidade_docente != 1 && !empty( $oRegistro30->municipio_nascimento ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "País de origem informado como BRASIL. É necessário informar a UF de nascimento.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes ao Registro 40
   * @param $oRegistro30
   * @param $oRegistro40
   * @return bool
   */
  static function validacoesRegistro40( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $oRegistro40 ) {

    $lDadosValidos = true;

    if( $oRegistro30->nacionalidade_docente != 3 ) {

      if( empty( $oRegistro40->numero_cpf ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "CPF não informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( !empty( $oRegistro40->numero_cpf ) &&
          (!DBString::isCPF( $oRegistro40->numero_cpf ) || $oRegistro40->numero_cpf == '00000000191')
        ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "CPF informado não é válido.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    /**
     * Chamado método responsável pelas validações referentes ao endereço residencial
     */
    if( !DadosCensoDocente2015::validacoesEnderecoResidencial( $sDadosDocente, $oExportacaoCenso, $oRegistro40 ) ) {
      $lDadosValidos = false;
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes ao Registro 50
   * @param $oRegistro50
   * @return bool
   */
  static function validacoesRegistro50( $sDadosDocente, $oExportacaoCenso, $oRegistro50, $oRegistro30 ) {

    $lDadosValidos    = true;
    $aStatusValidacao = array();

    // docente precisa ter a data de nascimento informada para realizar as validações do curso
    if( !empty( $oRegistro30->data_nascimento ) ) {
      $aStatusValidacao[] = self::validarCursosSuperiores( $sDadosDocente, $oExportacaoCenso, $oRegistro50, $oRegistro30);
    }
    $aStatusValidacao[] = self::validarPosGraduacao( $sDadosDocente, $oExportacaoCenso, $oRegistro50);
    $aStatusValidacao[] = self::validarOutrosCursos( $sDadosDocente, $oExportacaoCenso, $oRegistro50);

    if ( $lDadosValidos ) {
      $lDadosValidos = array_reduce( $aStatusValidacao, 'validaVerdadeiro');
    }

    return $lDadosValidos;
  }

  /**
   *  Validações do registro 50 da coluna 7 até a 23
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro50
   * @param stdClass $oRegistro30
   * @return boolean
   */
  static function validarCursosSuperiores( $sDadosDocente, $oExportacaoCenso, $oRegistro50, $oRegistro30 ) {

    $lDadosValidos   = true;
    $oDataNascimento = new DBDate( $oRegistro30->data_nascimento );
    $iAnoNascimento  = $oDataNascimento->getAno();

    /* coluna 7 - DAQUI */
    if ( $oRegistro50->situacao_curso_superior_1 == '' && $oRegistro50->escolaridade == 6 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Situação do Curso Superior 1" deve ser preenchido quando o campo "Escolaridade"';
        $sMsgErro .= ' for igual a 6 (Superior).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    if ( $oRegistro50->situacao_curso_superior_1 != '' && $oRegistro50->escolaridade != 6 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Situação do Curso Superior 1" não pode ser preenchido quando o campo "Escolaridade"';
        $sMsgErro .= ' for diferente de 6 (Superior).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }
    /* ATÉ AQUI */

    /* coluna 12 - DAQUI */
    if ( $oRegistro50->situacao_curso_superior_2 != '' && $oRegistro50->situacao_curso_superior_1 == ''  ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Situação do Curso Superior 2" não pode ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior 1" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }
    /* ATÉ AQUI */

    /* coluna 18 - DAQUI */
    if ( $oRegistro50->situacao_curso_superior_3 != '' && $oRegistro50->situacao_curso_superior_2 == ''  ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Situação do Curso Superior 3" não pode ser preenchido quando o';
        $sMsgErro .=' "Situação do Curso Superior 2" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }
    /* ATÉ AQUI */
    for ( $iContador = 1; $iContador <= 3; $iContador++ ) {

      $iFormacaoComplementacaoPedagogica = "formacao_complementacao_pedagogica_{$iContador}";
      $iSituacaoCursoSuperior            = "situacao_curso_superior_{$iContador}";
      $iCodigoCursoSuperior              = "codigo_curso_superior_{$iContador}";
      $iAnoInicioCursoSuperior           = "ano_inicio_curso_superior_{$iContador}";
      $iAnoConclusaoCursoSuperior        = "ano_conclusao_curso_superior_{$iContador}";
      $iInstituicaoCursoSuperior         = "instituicao_curso_superior_{$iContador}";

      /* coluna 7 - DAQUI */
      if ( $oRegistro50->$iFormacaoComplementacaoPedagogica == ''
          && $oRegistro50->$iSituacaoCursoSuperior == 1
          && DadosCensoDocente2015::$aCursosFormacao[$oRegistro50->$iCodigoCursoSuperior] != GRAU_LICENCIATURA ) {


        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Formação/Complementação pedagógica ' . $iContador . '" deve ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" for igual a 1 (Concluído) e o código do curso informado no campo';
        $sMsgErro .= ' "Código do Curso Superior ' . $iContador . '" for de bacharelado ou tecnológico.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      /**
       * Sempre que o curso for bacharelado ou tecnológico ou não estiver concluído, o campo Formação/Complementação
       * pedagógica deve ser nulo
       */
      if ( $oRegistro50->$iSituacaoCursoSuperior != 1  ||
           ( !empty($oRegistro50->$iCodigoCursoSuperior) &&
             DadosCensoDocente2015::$aCursosFormacao[$oRegistro50->$iCodigoCursoSuperior] == GRAU_LICENCIATURA) ) {
        $oRegistro50->$iFormacaoComplementacaoPedagogica = '';
      }
      /* ATÉ AQUI */

      /* coluna 8 - DAQUI */
      if ( $oRegistro50->$iCodigoCursoSuperior == '' &&  $oRegistro50->$iSituacaoCursoSuperior != '' ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Código do Curso Superior ' . $iContador . '" deve ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $oRegistro50->$iCodigoCursoSuperior != '' && $oRegistro50->$iSituacaoCursoSuperior == '' ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Código do Curso Superior ' . $iContador . '" não pode ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
      /* ATÉ AQUI */

      /* coluna 9 - DAQUI */
      if ( $oRegistro50->$iAnoInicioCursoSuperior == '' && $oRegistro50->$iSituacaoCursoSuperior == 2 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Início do Curso Superior ' . $iContador . '" deve ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" for igual a 2 (Em andamento).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if (  $oRegistro50->$iAnoInicioCursoSuperior != '' && $oRegistro50->$iSituacaoCursoSuperior != 2 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Início do Curso Superior ' . $iContador . '" não pode ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" for diferente de 2 (Em andamento).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      // registro 50 coluna 9 regra 4
      if ( !empty($oRegistro50->$iAnoInicioCursoSuperior) &&
           $oRegistro50->$iAnoInicioCursoSuperior < 2001) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Início do Curso Superior ' . $iContador . '" foi preenchido com valor inválido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      // registro 50 coluna 9 regra 5
      if ( !empty($oRegistro50->$iAnoInicioCursoSuperior) &&
           $oRegistro50->$iAnoInicioCursoSuperior > $oExportacaoCenso->getAnoCenso()) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Início do Curso Superior ' . $iContador . '" foi preenchido com valor inválido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if (  !empty($oRegistro50->$iAnoInicioCursoSuperior) && $oRegistro50->$iAnoInicioCursoSuperior <= $iAnoNascimento ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Início do Curso Superior ' . $iContador . '" não pode ser anterior ou igual à data informada no';
        $sMsgErro .= ' campo "Data de nascimento".';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
      /* ATÉ AQUI */

      /* Coluna 10 - DAQUI */
      if ( $oRegistro50->$iAnoConclusaoCursoSuperior == '' && $oRegistro50->$iSituacaoCursoSuperior == 1 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .='O campo "Ano de Conclusão do Curso Superior ' . $iContador . '" deve ser preenchido quando o campo';
        $sMsgErro .=' "Situação do Curso Superior ' . $iContador . '" for igual a 1 (Concluído).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $oRegistro50->$iAnoConclusaoCursoSuperior != '' && $oRegistro50->$iSituacaoCursoSuperior != 1 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Conclusão do Curso Superior ' . $iContador . '" não pode ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" for diferente de 1 (Concluído).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty($oRegistro50->$iAnoConclusaoCursoSuperior)
          && ($oRegistro50->$iAnoConclusaoCursoSuperior < 1940 || $oRegistro50->$iAnoConclusaoCursoSuperior > $oExportacaoCenso->getAnoCenso()) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Conclusão do Curso Superior ' . $iContador . '" foi preenchido com valor inválido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty($oRegistro50->$iAnoConclusaoCursoSuperior) && $oRegistro50->$iAnoConclusaoCursoSuperior <= $iAnoNascimento ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Ano de Conclusão do Curso Superior ' . $iContador . '" não pode ser anterior ou igual ao ano informado no';
        $sMsgErro .= ' campo "Data de nascimento".';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
      /* ATÉ AQUI */

      /* Coluna 11 - DAQUI */
      if ( $oRegistro50->$iInstituicaoCursoSuperior == '' && $oRegistro50->$iSituacaoCursoSuperior != '' ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Instituição de Ensino Superior ' . $iContador . '" deve ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $oRegistro50->$iInstituicaoCursoSuperior != '' && $oRegistro50->$iSituacaoCursoSuperior == '' ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Instituição de Ensino Superior ' . $iContador . '" não pode ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior ' . $iContador . '" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
      /* ATÉ AQUI */
    }

    return $lDadosValidos;
  }

  /**
   *  Validações do registro 50 da coluna 24 até a 27
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro50
   * @return boolean
   */
  static function validarPosGraduacao( $sDadosDocente, $oExportacaoCenso, $oRegistro50 ) {

    $lDadosValidos = true;

    $aSituacoesCursoSuperior = array(
      $oRegistro50->situacao_curso_superior_1,
      $oRegistro50->situacao_curso_superior_2,
      $oRegistro50->situacao_curso_superior_3
    );
    if ( $oRegistro50->escolaridade == 6 && in_array(1, $aSituacoesCursoSuperior) ) {

      if (    $oRegistro50->pos_graduacao_especializacao === 0
           && $oRegistro50->pos_graduacao_doutorado === 0
           && $oRegistro50->pos_graduacao_mestrado === 0
           && $oRegistro50->pos_graduacao === 0 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'Pós-Graduação inválido. Não podem ser informadas todas as opções com valor igual a 0 (Não).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    $aPosGraduacoes = array(
      'pos_graduacao_especializacao',
      'pos_graduacao_mestrado',
      'pos_graduacao_doutorado',
      'pos_graduacao'
    );

    for ( $iPosGraduacao = 0; $iPosGraduacao < count($aPosGraduacoes); $iPosGraduacao++ ) {

      if ( $oRegistro50->$aPosGraduacoes[$iPosGraduacao] == '' && in_array( 1, $aSituacoesCursoSuperior) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Pós-Graduação - Especialização" deve ser preenchido quando o campo';
        $sMsgErro .= ' "Situação do Curso Superior" for igual a 1 (Concluído).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $oRegistro50->$aPosGraduacoes[$iPosGraduacao] !== '' && !in_array( 1, $aSituacoesCursoSuperior) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Pós-Graduação - Especialização" não pode ser preenchido quando o profissional escolar não';
        $sMsgErro .= ' tiver concluído nenhum curso superior.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $iPosGraduacao != 3 && ( $oRegistro50->$aPosGraduacoes[$iPosGraduacao] != 0 && $oRegistro50->pos_graduacao == 1) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Pós-Graduação - Especialização" deve ser preenchido com 0 (Não) quando o campo';
        $sMsgErro .= ' "Pós-Graduação - Nenhum" for igual a 1 (Sim).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   *  Validações do registro 50 da coluna 28 até a 43
   *
   * @param string $sDadosDocente
   * @param stdClass $oExportacaoCenso
   * @param stdClass $oRegistro50
   * @return boolean
   */
  static function validarOutrosCursos( $sDadosDocente, $oExportacaoCenso, $oRegistro50 ) {

    $lDadosValidos = true;

    $aOutrosCursos = array(
      $oRegistro50->especifico_creche_0_3_anos,
      $oRegistro50->especifico_pre_escola_4_5_anos,
      $oRegistro50->especifico_anos_iniciais_ensino_fundamental,
      $oRegistro50->especifico_anos_finais_ensino_fundamental,
      $oRegistro50->especifico_ensino_medio,
      $oRegistro50->especifico_eja,
      $oRegistro50->especifico_educacao_especial,
      $oRegistro50->especifico_educacao_indigena,
      $oRegistro50->especifico_educacao_campo,
      $oRegistro50->especifico_educacao_ambiental,
      $oRegistro50->especifico_educacao_direitos_humanos,
      $oRegistro50->genero_diversidade_sexual,
      $oRegistro50->direitos_crianca_adolescente,
      $oRegistro50->educ_relacoes_etnicorraciais_his_cult_afro_brasil,
      $oRegistro50->outros
    );

    if ( !in_array(1, $aOutrosCursos) && $oRegistro50->nenhum == 0 ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'Outros cursos específicos inválidos. Não podem ser informadas todas as opções com valor igual a 0 (Não).';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
    }

    if ( $oRegistro50->nenhum == 1 ) {

      if ( in_array(1, $aOutrosCursos) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Caso selecionado a opção 'Nenhum' em Outros Cursos, as demais opções não podem estar selecionadas.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   * Validações referentes ao Registro 51
   * @param $oRegistro30
   * @param $aRegistro51
   * @return bool
   */
  static function validacoesRegistro51( $sDadosDocente, $oExportacaoCenso, $oRegistro30, $aRegistro51 ) {

    $oDadosEscola  = $oExportacaoCenso->getDadosProcessadosEscola();
    $lDadosValidos = true;
    $aDadosDaTurma = $oExportacaoCenso->getDadosProcessadosTurma();

    if( count( $aRegistro51 ) == 0 ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Docente possui horário de regência, porém não está vinculado a nenhuma turma.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }


    foreach( $aRegistro51 as $oRegistro51 ) {

      if( $oRegistro51->identificacao_unica_inep != $oRegistro30->identificacao_unica_docente_inep ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Informado códigos INEP diferentes para o docente.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( $oRegistro51->codigo_docente_entidade_escola != $oRegistro30->codigo_docente_entidade_escola ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Informado códigos do sistema diferentes para o docente.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      foreach( $aDadosDaTurma as $oDadosTurma ) {

        /**
         * Como uma turma regular e uma turma AC/AEE podem ter o mesmo código, foi criado um controle para saber se as
         * turmas são do mesmo tipo.
         */
        if(  $oRegistro51->lTurmaRegular != $oDadosTurma->lTurmaRegular ||
             $oRegistro51->codigo_turma_entidade_escola != $oDadosTurma->codigo_turma_entidade_escola ) {
          continue;
        }

        /**
         * 1 - Docente
         * 2 - Auxiliar/Assistente Educacional
         * 3 - Profissional/Monitor de Atividade Complementar
         * 4 - Tradutor Intérprete de LIBRAS
         * 5 - Docente titular - coordenador de tutoria (de  módulo ou disciplina) - EAD
         * 6 - Docente tutor - Auxiliar (de módulo ou disciplina) - EAD
         */

        // coluna 7 regra 2
        if ( !in_array($oRegistro51->funcao_exerce_escola_turma, array(1, 2, 3, 4, 5, 6)) ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "O campo \"Função que exerce na escola/Turma\" foi preenchido com valor inválido.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 7 regra 3
        if(    in_array($oRegistro51->funcao_exerce_escola_turma, array( 1, 2, 3, 4 ))
            && $oDadosTurma->mediacao_didatico_pedagogica == 3 ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ] a qual";
          $sMsgErro .= " o docente possui vínculo, possui mediação didático-pedagógica do tipo Educação a Distância.";
          $sMsgErro .= " Funções exercidas permitidas neste caso são:\n";
          $sMsgErro .= " Docente Titular ou Docente Tutor";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 7 regra 4
        if ( $oRegistro51->funcao_exerce_escola_turma == 2 && in_array($oDadosTurma->tipo_atendimento, array(4, 5)) ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ]: \n";
          $sMsgErro .= " O campo \"Função que exerce na escola/Turma\" não pode ser preenchido com 2 ";
          $sMsgErro .= "(Auxiliar/Assistente Educacional) quando o tipo de atendimento da turma for igual a 4 ";
          $sMsgErro .= "(Atividade complementar) ou 5 (AEE).";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 7 regra 5
        if( $oRegistro51->funcao_exerce_escola_turma == 3 && $oDadosTurma->tipo_atendimento != 4 ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Docente com função Profissional/Monitor de Atividade Complementar só pode ser vinculado";
          $sMsgErro .= " a uma turma de Atividade Complementar.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 7 regra 6
        if(    in_array( $oRegistro51->funcao_exerce_escola_turma, array(5, 6) )
            && $oDadosTurma->mediacao_didatico_pedagogica != 3 ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ] a qual";
          $sMsgErro .= " o docente possui vínculo, possui mediação didático-pedagógica do tipo Presencial ou .";
          $sMsgErro .= " Semipresencial. Funções exercidas não permitidas neste caso são:\n";
          $sMsgErro .= " Docente Titular ou Docente Tutor";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 7 regra 8 - Se preenchido com 4 deve haver aluno ou outro profissional escolar em sala de aula com
        // surdez, deficiência auditiva ou surdocegueira
        if ($oRegistro51->funcao_exerce_escola_turma == 4) {

          $aTemDeficiente = array();
          $aDadosDocente  = $oExportacaoCenso->getDadosProcessadosDocente();

          foreach( $aDadosDocente as $oOutroDocente ) {

            if ( $oOutroDocente->registro30->codigo_docente_entidade_escola == $oRegistro30->codigo_docente_entidade_escola ) {
              continue;
            }

            $aDeficienciaDocente = array (
              $oOutroDocente->registro30->tipos_deficiencia_surdez,
              $oOutroDocente->registro30->tipos_deficiencia_auditiva,
              $oOutroDocente->registro30->tipos_deficiencia_surdocegueira,
            );
            foreach ($oOutroDocente->registro51 as $oOutro51) {

              if ( $oOutro51->codigo_turma_entidade_escola != $oRegistro51->codigo_turma_entidade_escola) {
                continue;
              }

              if ( in_array( 1, $aDeficienciaDocente ) ) {
                $aTemDeficiente[] = true;
                break 2;
              }
            }
          }

          // valida entre os alunos, se algum aluno da turma tem deficiencia
          foreach ($oExportacaoCenso->getDadosProcessadosAluno() as $oDadosAluno ) {

            $aDeficienciaAluno = array(
              $oDadosAluno->registro60->tipos_defic_transtorno_surdez,
              $oDadosAluno->registro60->tipos_defic_transtorno_auditiva,
              $oDadosAluno->registro60->tipos_defic_transtorno_surdocegueira,
            );

            foreach ($oDadosAluno->registro80 as $oVinculuTurma) {

              if ( $oVinculuTurma->codigo_turma_entidade_escola == $oRegistro51->codigo_turma_entidade_escola &&
                   in_array(1, $aDeficienciaAluno)) {

                $aTemDeficiente[] = true;
                break 2;
              }
            }
          }

          if ( empty($aTemDeficiente) ) {

            $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ]\n";
            $sMsgErro .= "O campo \"Função que exerce na escola/Turma\" não pode ser preenchido com 4 (Tradutor ";
            $sMsgErro .= "Intérprete de Libras) quando não há aluno ou profissional escolar com surdez, surdocegueira ";
            $sMsgErro .= "ou deficiência auditiva vinculado à turma.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }
        } // fim validação da coluna 7 regra 8



        // coluna 8 regra 1
        if( empty( $oRegistro51->situacao_funcional_contratacao_vinculo ) &&
            in_array( $oRegistro51->funcao_exerce_escola_turma, array(1, 5, 6) ) ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Obrigatório informar o Regime de Contratação/Tipo de Vínculo para profissionais com função";
          $sMsgErro .= " de Docente.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 8 regra 2
        if( !empty( $oRegistro51->situacao_funcional_contratacao_vinculo ) &&
            !in_array( $oRegistro51->funcao_exerce_escola_turma, array(1, 5, 6) ) ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "O campo \"Situação Funcional/ Regime de contratação/Tipo de Vínculo\" não pode ser preenchido ";
          $sMsgErro .= "quando o campo \"Função que exerce na escola/Turma\" for diferente de 1 (Docente), 5 ";
          $sMsgErro .= "(Docente titular - coordenador de tutoria - EAD) e 6 (Docente tutor).";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // coluna 8 regra 3
        if ( !empty($oRegistro51->situacao_funcional_contratacao_vinculo) &&
             !in_array($oDadosEscola->registro00->dependencia_administrativa, array(1,2,3)) ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "O campo \"Situação Funcional/ Regime de contratação/Tipo de Vínculo\" não pode ser preenchido ";
          $sMsgErro .= "quando o campo \"Dependência administrativa\" for diferente de 1 (Federal), 2 (Estadual) ou 3 (Municipal).";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        // colunas 9 a 21
        $aDisciplinasDocente = array(
          $oRegistro51->codigo_disciplina_1,
          $oRegistro51->codigo_disciplina_2,
          $oRegistro51->codigo_disciplina_3,
          $oRegistro51->codigo_disciplina_4,
          $oRegistro51->codigo_disciplina_5,
          $oRegistro51->codigo_disciplina_6,
          $oRegistro51->codigo_disciplina_7,
          $oRegistro51->codigo_disciplina_8,
          $oRegistro51->codigo_disciplina_9,
          $oRegistro51->codigo_disciplina_10,
          $oRegistro51->codigo_disciplina_11,
          $oRegistro51->codigo_disciplina_12,
          $oRegistro51->codigo_disciplina_13
        );


        $aFuncoesDocente = array( 1, 5, 6 );
        $aControleDisciplinasDocente = array_count_values( $aDisciplinasDocente );

        if (    !in_array( $oRegistro51->funcao_exerce_escola_turma, array( 1, 5 ) )
             && !empty($oRegistro51->situacao_funcional_contratacao_vinculo)
             && count( $aControleDisciplinasDocente ) > 1
           ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ] ";
          $sMsgErro .= " Função exercida atribuida ao profissional não pode ter vínculo com a grade de horário ";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        if ( in_array( $oRegistro51->funcao_exerce_escola_turma, $aFuncoesDocente )) {

          /**
           * 4 - Atividade complementar
           * 5 - Atendimento Educacional Especializado( AEE )
           */
          $aTipoAtendimentoACAEE = array( 4, 5 );
          if (    $oRegistro51->funcao_exerce_escola_turma == 2
              && in_array( $oDadosTurma->tipo_atendimento, $aTipoAtendimentoACAEE ) ) {

            $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ] a qual";
            $sMsgErro .= " o docente possui vínculo, é do tipo Atividade Complementar/AEE. Função exercida não";
            $sMsgErro .= " permitidas neste caso:\nAuxiliar/Assistente Educacional";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }

          /**
           * Validações para casos em que a turma que o docente encontra-se vinculado, não trata-se de turma AEE/AC
           */
          if( !in_array( $oDadosTurma->tipo_atendimento, $aTipoAtendimentoACAEE ) ) {

            $aEtapaEnsino = array( 1, 2, 3, 56, 65 );
            if (    !in_array($oDadosTurma->etapa_ensino_turma, $aEtapaEnsino)
                 && isset( $aControleDisciplinasDocente[''] )
                 && count( $aControleDisciplinasDocente ) == 1
               ) {

              $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
              $sMsgErro .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ]: \n";
              $sMsgErro .= "Docente encontra-se vinculado a turma, porém não foi informada a disciplina.";
              $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
              $lDadosValidos = false;
            }

            $oTurma    = self::buscarTurma($oDadosTurma);
            $aRegencia = $oTurma->getDisciplinas();

            $aDisciplinaRegencia = array();
            foreach( $aRegencia as $oRegencia ) {

              $oDisciplinaRegencia   = $oRegencia->getDisciplina();
              $aDisciplinaRegencia[] = $oDisciplinaRegencia->getCodigoCensoDisciplina();
            }

            foreach( $aDisciplinasDocente as $iChave => $iDisciplinaDocente ) {

              if (  !empty($iDisciplinaDocente) && !in_array($iDisciplinaDocente, $aDisciplinaRegencia)) {

                $oDisciplina = DisciplinaRepository::getDisciplinaByCodigoCenso( $iDisciplinaDocente );
                $sMsgErro    = "Docente CGM {$sDadosDocente}: \n";
                $sMsgErro   .= "Turma [ {$oDadosTurma->codigo_turma_entidade_escola} - {$oDadosTurma->nome_turma} ].";
                $sMsgErro   .= " Disciplina {$oDisciplina->getNomeDisciplina()} não está vinculada a turma.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
                $lDadosValidos = false;
              }
            }
          }
        }
      }
    }

    return $lDadosValidos;
  }

  /**
   * Método responsável por realizar as validações referentes as Necessidades Especiais
   * @param $oRegistro30
   * @return bool
   */
  static function validacoesNecessidadesEspeciais( $sDadosDocente, $oExportacaoCenso, $oRegistro30 ) {

    $lDadosValidos = true;

    /**
     * Array para validar se ao selecionar Cegueira, outros tipos de deficiencia não permitidas foram marcadas
     * Coluna 19
     */
    $aDeficienciaCegueira = array(
                                   $oRegistro30->tipos_deficiencia_baixa_visao,
                                   $oRegistro30->tipos_deficiencia_surdez,
                                   $oRegistro30->tipos_deficiencia_surdocegueira
                                 );

    if( $oRegistro30->tipos_deficiencia_cegueira == 1 && in_array(1, $aDeficienciaCegueira) ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Ao informar necessidade Cegueira, os seguintes tipos de deficiência não podem ser informados:\n";
      $sMsgErro .= "Baixa Visão, Surdez e Surdocegueira.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    /**
     * Array para validar se ao selecionar Baixa Visão, outros tipos de deficiencia não permitidas foram marcadas
     * Coluna 20
     */
    if( $oRegistro30->tipos_deficiencia_baixa_visao == 1 && $oRegistro30->tipos_deficiencia_surdocegueira == 1 ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Ao informar necessidade Baixa Visão, os seguintes tipos de deficiência não podem ser informados:\n";
      $sMsgErro .= "Cegueira e Surdocegueira.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    /**
     * Array para validar se ao selecionar Surdez, outros tipos de deficiencia não permitidas foram marcadas
     * Coluna 21
     */
    $aDeficienciaSurdez = array(
                                 $oRegistro30->tipos_deficiencia_auditiva,
                                 $oRegistro30->tipos_deficiencia_surdocegueira
                               );



    if( $oRegistro30->tipos_deficiencia_surdez == 1 && in_array(1, $aDeficienciaSurdez) ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Ao informar necessidade Surdez, os seguintes tipos de deficiência não podem ser informados:\n";
      $sMsgErro .= "Cegueira, Deficiência Auditiva e Surdocegueira.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    /**
     * Array para validar se ao selecionar Deficiência Auditiva, outros tipos de deficiência não permitidas foram marcadas
     * Coluna 22
     */
    if( $oRegistro30->tipos_deficiencia_auditiva == 1 && $oRegistro30->tipos_deficiencia_surdocegueira == 1 ) {

      $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      $sMsgErro .= "Ao informar necessidade Deficiência Auditiva, os seguintes tipos de deficiência não podem ser informados:\n";
      $sMsgErro .= "Surdez e Surdocegueira.";
      $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      $lDadosValidos = false;
    }

    return $lDadosValidos;
  }

  /**
   * Método responsável por realizar as validações referentes aos dados de endereço residencial
   * @param $oRegistro40
   * @return bool
   */
  static function validacoesEnderecoResidencial( $sDadosDocente, $oExportacaoCenso, $oRegistro40 ) {

    $lDadosValidos = true;
    if( !empty( $oRegistro40->cep ) ) {

      if ( !DBNumber::isInteger( $oRegistro40->cep ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "CEP informado não é válido.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if (strlen($oRegistro40->cep) < 8) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "CEP [{$oRegistro40->cep}] da escola deve conter 8 dígitos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if (preg_match ('/1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8}/', $oRegistro40->cep)) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O Campo CEP [{$oRegistro40->cep}] foi preenchido com um valor inválido.";
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCensoBase::LOG_DOCENTE );
        $lDadosValidos = false;
      }

      if ( empty($oRegistro40->endereco) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Endereço" deve ser preenchido quando o campo "CEP" for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( empty($oRegistro40->uf) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "UF" deve ser preenchido quando o campo "CEP" for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( empty($oRegistro40->municipio) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Município" deve ser preenchido quando o campo "CEP" for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }


    } else {

      if ( !empty( $oRegistro40->endereco ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Endereço" não pode ser preenchido quando o campo "CEP" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty( $oRegistro40->numero_endereco ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Número do Endereço" não pode ser preenchido quando o campo "CEP" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty( $oRegistro40->complemento ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Complemento" não pode ser preenchido quando o campo "CEP" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty( $oRegistro40->bairro ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Bairro" não pode ser preenchido quando o campo "CEP" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty( $oRegistro40->uf ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "UF" não pode ser preenchido quando o campo "CEP" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty( $oRegistro40->municipio ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= 'O campo "Município" não pode ser preenchido quando o campo "CEP" não for preenchido.';
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

    }

    $aDadosEnderecoResidencial = array();

    $oDadosLogradouro             = new stdClass();
    $oDadosLogradouro->sCampo     = 'endereco';
    $oDadosLogradouro->sValor     = $oRegistro40->endereco;
    $oDadosLogradouro->iTamanho   = 100;
    $oDadosLogradouro->sDescricao = 'Endereço';
    $aDadosEnderecoResidencial[]  = $oDadosLogradouro;

    $oDadosComplemento             = new stdClass();
    $oDadosComplemento->sCampo     = 'complemento';
    $oDadosComplemento->sValor     = $oRegistro40->complemento;
    $oDadosComplemento->iTamanho   = 20;
    $oDadosComplemento->sDescricao = 'Complemento do endereço';
    $aDadosEnderecoResidencial[]   = $oDadosComplemento;

    $oDadosNumero                = new stdClass();
    $oDadosNumero->sCampo        = 'numero_endereco';
    $oDadosNumero->sValor        = $oRegistro40->numero_endereco;
    $oDadosNumero->iTamanho      = 10;
    $oDadosNumero->sDescricao    = 'Número do endereço';
    $aDadosEnderecoResidencial[] = $oDadosNumero;

    $oDadosBairro                = new stdClass();
    $oDadosBairro->sCampo        = 'bairro';
    $oDadosBairro->sValor        = $oRegistro40->bairro;
    $oDadosBairro->iTamanho      = 50;
    $oDadosBairro->sDescricao    = 'Bairro';
    $aDadosEnderecoResidencial[] = $oDadosBairro;

    foreach( $aDadosEnderecoResidencial as $oDadosEndereco ) {

      if( $oDadosEndereco->sValor === '' ) {
        continue;
      }

      if( preg_match ( '/[^a-z0-9ªº\s\-.,\/]+/i',  $oDadosEndereco->sValor ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "{$oDadosEndereco->sDescricao} [{$oDadosEndereco->sValor}] possui caracteres inválidos. ";
        $sMsgErro .= "Caracteres permitidos( entre parentêses ): (ABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789 ./ -ªº ,)";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if( !DBString::validarTamanhoMaximo( $oDadosEndereco->sValor, $oDadosEndereco->iTamanho ) ) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "{$oDadosEndereco->sDescricao} excede o tamanho máximo permitido( {$oDadosEndereco->iTamanho} caracteres ).";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }
    }

    return $lDadosValidos;
  }

  /**
   *  0 - OUTROS
   *  1 - LICENCIATURA
   *  2 - BACHARELADO
   *  3 - TECNOLOGICO
   * @return type
   */
  protected static function getGrauCurso() {

    $oDaoCursoFormacao = new cl_cursoformacao();
    $sCamposGrau       = " ed94_c_codigocenso, ed94_i_grauacademico";
    $sSqlGrau          = $oDaoCursoFormacao->sql_query_file( null, $sCamposGrau, null, null );
    $rsGrau            = db_query( $sSqlGrau );

    if ( !$rsGrau ) {
      throw new DBException("Erro ao buscar os cursos de formações.");
    }

    $iTotalLinhas = pg_num_rows($rsGrau);
    if ( $iTotalLinhas > 0 ) {

      for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

        $oGrau = db_utils::fieldsMemory( $rsGrau, $iContador );
        DadosCensoDocente2015::$aCursosFormacao[$oGrau->ed94_c_codigocenso] = $oGrau->ed94_i_grauacademico;
      }
    }
  }

  /**
   * Identifica se a turma informada no censo é uma turma que foi unificada
   * se for, busca qual a turma principal
   * @param  stdclass $oDadosTurma
   * @return Turma
   */
  protected static function buscarTurma($oDadosTurma) {

    if ( $oDadosTurma->lTurmaUnificada ) {

      $oTurmaCenso = new TurmaCenso($oDadosTurma->codigo_turma_entidade_escola);

      $oTurma = null;
      $aTumas = $oTurmaCenso->getTurmaCensoTurma();

      foreach ($aTumas as $oTurmaUnificada) {

        if ( $oTurmaUnificada->getPrincipal() ) {

          $oTurma = $oTurmaUnificada->getTurma();
          break;
        }
      }
      return $oTurma;
    }
    return new Turma($oDadosTurma->codigo_turma_entidade_escola);

  }
}

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

define( "CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013", "educacao.escola.ImportacaoSituacaoAlunoCenso2013." );

/**
 * Classe para importação do arquivo de Situação do Aluno do Censo 2013 e 2014
 * Foi refatorado para atender também o ano de 2014
 *
 * @author     Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package    educacao
 * @subpackage censo
 *
 * @version   $Revision: 1.32 $
 */
class ImportacaoSituacaoAlunoCenso2013 {

  /**
   * Constante com o código do layout referente a Situação do Aluno
   * @var integer
   */
  const CODIGO_LAYOUT = 216;

  /**
   * Constantes com o número de colunas permitidas de acordo com o registro (Cadastro do Layout)
   * @var integer
   */
  const REGISTRO_89_NUMERO_COLUNAS = 6;
  const REGISTRO_90_NUMERO_COLUNAS = 13;
  const REGISTRO_91_NUMERO_COLUNAS = 16;

  /**
   * Caminho do arquivo
   * @var string
   */
  private $sCaminhoArquivo = '';

  private $oLog = null;

  /**
   * Ano do arquivo.
   * Foi alterado de constante para uma propriedade pois o arquivo de 2013 e 2014 permaneceram os mesmos.
   * @var integer
   */
  private $iAnoArquivo = 2013;

  /**
   * Controla se todos os dados são válidos
   */
  private $lDadosValidos = true;

  /**
   * Controla os alunos percorridos no arquivo, para evitar duplicação
   * @var array
   */
  private $aAlunosPercorridos = array();

  /**
   * Armazena uma instância da data do censo
   * @var DBDate
   */
  private $oDataCenso;

  /**
   * Armazena instância da Escola
   * @var Escola
   */
  private $oEscola;

  /**
   * Turmas que apresentarem erro no registro
   * @var array
   */
  private $aTurmasErro = array();

  private $lErroEncontrado = false;

  /**
   * Construtor da classe
   * @param string    $sCaminhoArquivo
   * @param DBLogJSON $oDBLog
   * @param integer   $iAno             ano do censo
   */
  public function __construct( $sCaminhoArquivo, DBLogJSON $oDBLog, $iAno = 2013 ) {

    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->oLog            = $oDBLog;
    $this->oEscola         = EscolaRepository::getEscolaByCodigo(db_getsession( "DB_coddepto" ));
    $this->iAnoArquivo     = $iAno;
    $this->calculaDataCenso();
  }

  /**
   * Retorna o caminho do arquivo
   * @return string
   */
  public function getCaminhoArquivo() {
    return $this->sCaminhoArquivo;
  }

  /**
   * Gera o arquivo de log de erros/sucesso da importação
   *
   * @param boolean $lErro     - Informa se trata-se de um erro
   * @param string  $sMensagem - Mensagem a ser apresentada
   */
  public function log( $lErro, $sMensagem ) {

    $oTipoLog = DBLog::LOG_INFO;

    if ( $lErro ) {
      $oTipoLog = DBLog::LOG_ERROR;
    }

    $oMensagem            = new stdClass();
    $oMensagem->sMensagem = utf8_encode( $sMensagem );

    $this->oLog->log( $oMensagem, $oTipoLog );
  }

  /**
   * Valida Aluno pelo Nome do Aluno, Nome da Mãe e sua Data de Nascimento e adiciona ao Log
   * @param string $sNomeAluno
   * @param string $sNomeMae
   * @param DBDate $oDataNascimento
   */
  public function validaAluno($sNomeAluno, $sNomeMae, DBDate $oDataNascimento){

    $oAluno = AlunoRepository::getAlunoPorNomeDataNascimentoNomeMae($sNomeAluno, $sNomeMae, $oDataNascimento);

    if ( is_null($oAluno) ) {

      $this->lDadosValidos = false;
      $sMensagem           = "Aluno {$sNomeAluno} com Nome da Mãe {$sNomeMae} e Data de Nascimento ";
      $sMensagem          .= "{$oDataNascimento->getDate(DBDate::DATA_PTBR)} não possui cadastro.";
      $this->log( true, $sMensagem );
    }

    return $oAluno;
  }

  /**
   * Importa as informações o INEP para o sistema.
   * @return void
   */
  public function importarArquivo() {

    require_once("model/dbLayoutReader.model.php");
    require_once("model/dbLayoutLinha.model.php");

    $oDbLayoutReader = new DBLayoutReader(self::CODIGO_LAYOUT, $this->getCaminhoArquivo(), true, true, "|");
    $aLines          = $oDbLayoutReader->getLines();

    if ( count( $aLines ) == 0 ) {
      throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "registros_nao_encontrados" ) );
    }

    foreach ($aLines as $iIndex => $oLinha) {

      if (!($oLinha instanceof DBLayoutLinha)) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "arquivo_invalido" ) );
      }
      $this->importaLinha($oLinha, $iIndex);
    }
  }

  /**
   * Importa um objeto DBLayoutLinha do censo 2013 para o sistema
   * @param  DBLayoutLinha $oLinha Instância do objeto da linha
   * @return [type]                [description]
   */
  private function importaLinha(DBLayoutLinha $oLinha, $iLinha) {

    /**
     * Array contendo os registros existentes para o arquivo de importação.
     * Valida se a linha contem um registro inexistente, parando a importação
     */
    $aRegistros = array( 89, 90, 91 );
    if ( !in_array( $oLinha->tipo_registro, $aRegistros ) ) {

      $oMensagem            = new stdClass();
      $oMensagem->iRegistro = $oLinha->tipo_registro;
      $oMensagem->iLinha    = $iLinha;

      throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "registro_invalido", $oMensagem ) );
    }

    $this->lDadosValidos = true;

    /**
     * Chama o método para validação dos dados, registrando as linhas com erros encontrados
     */
    $this->validaDados( $oLinha, $iLinha, $oLinha->tipo_registro );

    /**
     * Aluno normal, enviado no arquivo no prazo do censo
     */
    if ($oLinha->tipo_registro == "90") {
      $oAluno = new Aluno( $oLinha->codigo_aluno_escola );
    }

    /**
     * Aluno admitido após o censo
     */
    if ($oLinha->tipo_registro == "91") {

      /**
       * Aluno enviado após o censo
       */
      $sNomeAluno      = $oLinha->nome_aluno;
      $sNomeMae        = $oLinha->nome_mae;
      $sDataNascimento = $oLinha->data_nascimento;

      if ( empty( $sNomeMae ) ) {

        $sMensagem            = "Linha [{$iLinha}] - Código INEP: {{$oLinha->codigo_aluno_inep}] - Código Aluno: ";
        $sMensagem           .= "[{$oLinha->codigo_aluno_escola}].\nAluno {$sNomeAluno} sem Nome da Mãe";
        $this->lDadosValidos  = false;
        $this->log( true, $sMensagem);
      }

      try {

        $oDate  = new DBDate($sDataNascimento);
        $oAluno = $this->validaAluno($sNomeAluno, $sNomeMae, $oDate);
      } catch (Exception $e) {

        $this->lDadosValidos = false;
        $this->log( true, "Aluno {$sNomeAluno} com Nome da Mãe {$sNomeMae}, possui Data de Nascimento inválida." );
      }
    }

    /**
     * Se houver instancia de aluno, pega os dados da linha e salva.
     */
    if ( isset( $oAluno ) && !empty( $oAluno ) && $oAluno->getCodigoAluno() != null ) {

      if ( $oLinha->codigo_escola_inep != $this->oEscola->getCodigoInep() ) {

        $oMensagem                     = new stdClass();
        $oMensagem->iCodigoInep        = $this->oEscola->getCodigoInep();
        $oMensagem->iCodigo            = $this->oEscola->getDepartamento()->getCodigo();
        $oMensagem->sDescricao         = $this->oEscola->getDepartamento()->getNomeDepartamento();
        $oMensagem->iCodigoInepArquivo = $oLinha->codigo_escola_inep;
        throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "departamento_invalido", $oMensagem ) );
      }

      if (empty($oLinha->codigo_aluno_inep)) {

        $sMensagem           = "Linha [{$iLinha}].\nAluno sem código INEP.";
        $this->lDadosValidos = false;
        $this->log( true, $sMensagem);
      }

      $oAluno->setCodigoInep($oLinha->codigo_aluno_inep);
      $oAluno->salvar();

      $oAlunoMatriculaCenso = new AlunoMatriculaCenso($oAluno, $this->iAnoArquivo);
      $oAlunoMatriculaCenso->setTurmaCenso($oLinha->codigo_turma_inep);

      if ( $oLinha->tipo_registro == "90" ) {
        $oAlunoMatriculaCenso->setMatriculaCenso($oLinha->codigo_matricula_inep);
      }

      if ( !$this->lDadosValidos ) {
        $this->lErroEncontrado = true;
      }

      if ( $this->lDadosValidos ) {

        $oAlunoMatriculaCenso->salvar();
        $oTurma = TurmaRepository::getTurmaByCodigo( $oLinha->codigo_turma_escola );
        $oTurma->setCodigoInep( $oLinha->codigo_turma_inep );
        $oTurma->salvar();

        $sMensagem = "Linha [{$iLinha}].\nAluno {$oAluno->getCodigoAluno()} - {$oAluno->getNome()} importado com sucesso.";
        $this->log( false, $sMensagem );
      }
    }
  }

  /**
   * Método para validar os campos conforme layout
   *
   * @param DBLayoutLinha $oLinha
   * @return boolean $this->lDadosValidos - Controla se todos os dados são válidos
   */
  public function validaDados( DBLayoutLinha $oLinha, $iLinha, $iRegistro ) {

    /**
     * Incrementa a variável linha para apresentar a linha correta ao usuário
     */
    $iLinha++;

    /**
     * Campos padrão a serem validados independente da validação a ser feita (Obrigatório / Tamanho), quanto o tipo
     * de registro
     */
    $aCamposPadrao = array(
                            "codigo_escola_inep",
                            "codigo_turma_inep",
                            "codigo_aluno_inep",
                            "codigo_matricula_inep"
                          );

    /**
     * Array com os campos obrigatórios
     */
    $aValidacaoObrigatorio = array(
                                    89 => array(
                                                "codigo_escola_inep"
                                               ),
                                    90 => array(
                                  	             $aCamposPadrao
                                               ),
                                    91 => array( "codigo_aluno_inep" )
                                  );

    /**
     * Array com os campos para validação do tamanho permitido
     */
    $aValidacaoTamanho = array(
                                89 => array(
                                            "codigo_escola_inep"
                                           ),
                                90 => array(
                             	               $aCamposPadrao,
                                             "codigo_turma_escola",
                                             "codigo_aluno_escola"
                                           ),
                                91 => array(
                                             $aCamposPadrao,
                                             "codigo_turma_escola",
                                             "codigo_aluno_escola"
                                           )
                              );

    /**
     * Variáveis para armazenamento de informações para posteriores validações
     *
     * @var $oTurma            - Instancia de Turma
     * @var $iCodigoInepTurma  - Código INEP da turma informado no arquivo
     * @var $iCodigoInepEscola - Código INEP da escola informado no arquivo
     * @var $oAluno            - Instancia de Aluno
     * @var $iMatriculaInep    - Código da matrícula INEP informado no arquivo
     */
    $oTurma             = null;
    $iCodigoInepTurma   = null;
    $iCodigoInepEscola  = null;
    $oAluno             = null;
    $iMatriculaInep     = null;

    /**
     * Verifica se a quantidade de colunas na linha é igual ao número de colunas cadastradas no layout
     */
    $iColunas = explode( "|" , $oLinha->getLinha() );

    $oMensagem          = new stdClass();
    $oMensagem->iLinha  = $iLinha;

    /**
     * O número de colunas é incrementado em +1, pois o validador do censo considera o pipe como o fim da linha.
     */
    if ( $iRegistro == 89 && count( $iColunas ) != self::REGISTRO_89_NUMERO_COLUNAS + 1 ) {

      $oMensagem->iCampos = self::REGISTRO_89_NUMERO_COLUNAS;
      throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "quantidade_campos", $oMensagem ) );
    }

    if ( $iRegistro == 90 && count( $iColunas ) != self::REGISTRO_90_NUMERO_COLUNAS + 1 ) {

      $oMensagem->iCampos = self::REGISTRO_90_NUMERO_COLUNAS;
      throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "quantidade_campos", $oMensagem ) );
    }

    if ( $iRegistro == 91 && count( $iColunas ) != self::REGISTRO_91_NUMERO_COLUNAS + 1 ) {

      $oMensagem->iCampos = self::REGISTRO_91_NUMERO_COLUNAS;
      throw new BusinessException( _M( CAMINHO_MENSAGENS_IMPORTACAO_SITUACAO2013 . "quantidade_campos", $oMensagem ) );
    }

    /**
     * Percorre cada propriedade de oLinha para realizar as validações
     */
    foreach ( $oLinha->getProperties() as $sIndice => $aCampos ) {

      /**
       * Valor informado para o campo
       */
      $sValor = $oLinha->{$sIndice};

      /**
       * Valida se o campo foi preenchido e o tamanho é maior que o permitido
       */
      if (    $sValor != null
           && strlen( $sValor ) > $aCampos[1]
           && in_array( $sIndice, $aValidacaoTamanho[$iRegistro] )
         ) {

        $sMensagem            = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}] - Tamanho permitido: [{$aCampos[1]}]";
        $sMensagem           .= "\nCampo com tamanho inválido.";
        $this->lDadosValidos  = false;
        $this->log(true, $sMensagem);
      }

      /**
       * Valida se o campo é obrigatório e se está vazio
       */
      if ( in_array( $sIndice, $aValidacaoObrigatorio[$iRegistro] ) && $sValor == '' ) {

        $sMensagem           = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}].\nCampo obrigatório não preenchido.";
        $this->lDadosValidos = false;
        $this->log( true, $sMensagem );

        if ($iRegistro == 89) {
          return false;
        }
      }

      /**
       * Armazena o código INEP da escola para posterior validação em relação a turma existir na escola
       */
      if ( $sIndice == "codigo_escola_inep" ) {

        if ( $sValor != $this->oEscola->getCodigoInep() ) {

          $sMensagem            = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}] - Código da Escola Arquivo: [{$sValor}]";
          $sMensagem           .= " - Código da Escola Sistema: [{$this->oEscola->getCodigoInep()}].";
          $sMensagem           .= "\nCódigo da Escola no sistema não corresponde ao informado no arquivo.";
          $this->lDadosValidos  = false;
          $this->log( true, $sMensagem );
        }

        $iCodigoInepEscola = $sValor;
      }

      /**
       * Armazena uma instancia da Turma para comparação da escola que a turma se encontra com o código INEP da escola
       * informado no arquivo
       */
      if ( $sIndice == "codigo_turma_escola" ) {
        $oTurma = TurmaRepository::getTurmaByCodigo( $sValor );
      }

      /**
       * Armazena o código INEP da turma informada no arquivo
       */
      if ( $sIndice == "codigo_turma_inep" ) {
        $iCodigoInepTurma = $sValor;
      }

      /**
       * Armazena uma instancia de Aluno
       */
      if ( $sIndice == "codigo_aluno_escola" ) {

        $oAluno = AlunoRepository::getAlunoByCodigo( $sValor );

        if ( $oAluno->getCodigoAluno() == null ) {

          $sMensagem            = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}] - Código do Aluno: [{$sValor}].";
          $sMensagem           .= "\nCódigo do aluno não encontrado no sistema.";

          if ( empty( $sValor ) ) {

            $sMensagem  = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}].";
            $sMensagem .= "\nCódigo do aluno não informado.";
          }

          $this->lDadosValidos = false;
          $this->log( true, $sMensagem );
        }

        if ( $oAluno->getCodigoAluno() != null && in_array( $oAluno->getCodigoAluno(), $this->aAlunosPercorridos ) ) {

          $sMensagem            = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}] - Código do Aluno:";
          $sMensagem           .= " [{$oAluno->getCodigoAluno()}].\nAluno duplicado no arquivo de importação.";
          $this->lDadosValidos  = false;
          $this->log( true, $sMensagem );
        } else {
          $this->aAlunosPercorridos[] = $oAluno->getCodigoAluno();
        }
      }

      /**
       * Armazena matrícula INEP
       */
      if ( $sIndice == "codigo_matricula_inep" ) {

        $iMatriculaInep = $sValor;

        if ($iRegistro == 91 && $iMatriculaInep != null) {

          $sMensagem            = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}].\nMatrícula INEP não deve ser informado";
          $sMensagem           .= " quando Registro é igual a {$iRegistro}.";
          $this->lDadosValidos  = false;
          $this->log( true, $sMensagem );
        }
      }

      /**
       * *******************************************
       * VALIDAÇÕES ESPECÍFICAS DOS CAMPOS DO LAYOUT
       * *******************************************
       */
      switch( $sIndice ) {

      	case "codigo_turma_inep":

      	  if (    $this->lDadosValidos
               && $oTurma->getCodigo() != null
               && $oTurma->getEscola()->getCodigoInep() != $iCodigoInepEscola ) {

      	    $sMensagem            = "Linha: [{$iLinha}] - Campo: [{$aCampos[6]}] - Código INEP da turma:";
      	    $sMensagem           .= " [{$iCodigoInepTurma}].\nTurma não encontrada na escola.";
      	    $this->lDadosValidos  = false;
      	    $this->log( true, $sMensagem );
      	  }

      	  break;
      }
    }

    /**
     * Caso seja o registro 89, não precisa validar os dados do aluno,
     * pois não existe dados de aluno neste registro
     */
    if ($iRegistro == 89) {
      return $this->lDadosValidos;
    }

    $lMatriculaExistente = false;

    /**
     * Percorre as matrículas existentes
     */
    foreach ( $oAluno->getMatriculas() as $oMatricula ) {

      /**
       * Verifica qual matrícula encontra-se ativa e com situação MATRICULADO,
       */
      if (    $oMatricula->getTurma()->getCalendario()->getAnoExecucao() == $this->iAnoArquivo
           && $oMatricula->getTurma()->getEscola()->getCodigoInep() == $iCodigoInepEscola
         ) {

        $lMatriculaExistente = true;
        $lRegistroErrado     = false;
        $sMensagemErro       = "";
        $sMensagemRegistro   = "";

        /**
         * Valida se a linha pertence ao registro 90 e se a data de matrícula é maior que a data do censo
         */
        if ( $iRegistro == 90 && $oMatricula->getDataMatricula()->getDate() > $this->oDataCenso->getDate() ) {

          $lRegistroErrado  = true;
          $sMensagemErro    = "\nO aluno deve ser informado somente no registro 91, pois a data da matrícula é ";
          $sMensagemErro   .= "maior que a data do censo.";
        }

        /**
         * Valida se a linha pertence ao registro 91 e se a data de matrícula é menor ou igual que a data do censo
         */
        if ( $iRegistro == 91 && $oMatricula->getDataMatricula()->getDate() <= $this->oDataCenso->getDate() ) {

          $lRegistroErrado  = true;
          $sMensagemErro    = "\nO aluno deve ser informado somente no registro 90, pois a data da matrícula é ";
          $sMensagemErro   .= "menor que a data do censo.";
        }

        /**
         * Caso algum aluno esteja no registro errado em relação a sua matrícula e data do censo, apresenta o erro
         */
        if ( $lRegistroErrado ) {

          $sMensagemRegistro  = " Linha: [{$iLinha}] - Aluno: [{$oAluno->getCodigoAluno()}] [{$oAluno->getNome()}]";
          $sMensagemRegistro .= " - Data da Matrícula: [{$oMatricula->getDataMatricula()->getDate(DBDate::DATA_PTBR)}]";
          $sMensagemRegistro .= " - Data do Censo: [{$this->oDataCenso->getDate(DBDate::DATA_PTBR)}].";
          $sMensagemRegistro .= $sMensagemErro;

          $this->lDadosValidos = false;
          $this->log( true, $sMensagemRegistro );
        }
      }
    }

    if ( !$lMatriculaExistente && $oAluno->getCodigoAluno() != null ) {

      $sMensagem            = "Linha: [{$iLinha}] - Código INEP da turma: [{$iCodigoInepTurma}]";
      $sMensagem           .= " - Código: [{$sValor}] - Aluno: [{$oAluno->getNome()}]";
      $sMensagem           .= "\nAluno sem matrícula na escola.";
      $this->lDadosValidos  = false;
      $this->log( true, $sMensagem );
    }

    /**
     * Valida se a matrícula informada no arquivo corresponde a matrícula do aluno no ano, caso exista
     */
    $oMatriculaCenso = $oAluno->getAlunoMatriculaCenso( $this->iAnoArquivo );

    if (    $oMatriculaCenso != null
         && $oMatriculaCenso->getCodigo() != null
         && $oMatriculaCenso->getMatriculaCenso() != $iMatriculaInep
         && $iRegistro == 90 ) {

      $sMensagem            = "Linha: [{$iLinha}] - Matrícula INEP Arquivo: [{$iMatriculaInep}]";
      $sMensagem           .= " - Matrícula INEP Aluno: [{$oMatriculaCenso->getMatriculaCenso()}].";
      $sMensagem           .= "\nMatrícula do censo do aluno não corresponde a matrícula existente no arquivo.";
      $this->lDadosValidos  = false;
      $this->log( true, $sMensagem );
    }
  }

  /**
   * Retorna se o arquivo passou nas validações
   * @return boolean
   */
  public function encontrouErro() {
    return $this->lErroEncontrado;
  }

  /**
   * A Data do censo é sempre a ultima quarta-feira do mês de maio
   */
  private function calculaDataCenso() {

    $oData1 = new DBDate("15/05/{$this->iAnoArquivo}");
    $oData2 = new DBDate("31/05/{$this->iAnoArquivo}");

    foreach ( DBDate::getDatasNoIntervalo($oData1, $oData2, array(3)) as $oDtQuarta) {
      $this->oDataCenso = $oDtQuarta;
    }
  }

}
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


class Job {

  /**
   * Momento da Criação da Instrução de Execução da Tarefa
   * @var intreger
   */
  private $iMomentoCriacao;

  /**
   * Nome da Tarefa
   * @var string
   */
  private $sNome;

  /**
   * Diretorio do Arquivo da Tarefa
   * @var string
   */
  private $sDiretorio;

  /**
   * Usuario do Sistema que criou a instrução
   * @var int
   */
  private $iUsuario;

  /**
   * Descrição da Tarefa
   * @var string
   */
  private $sDescricao;

  /**
   * Nome da Classe que vai ser feita a chamada do gerenciador
   * @var string
   */
  private $sNomeClasse;

  /**
   * Observações
   * @var string
   */
  private $sObservacoes;

  /**
   * Tipo de Periodicidade
   * @var integer
   */
  private $iTipoPeriodicidade;

  /**
   * Array contento as Periodicidades executadas
   * @var array
   */
  private $aPeriodicidades = array();

  /**
   * Determnina com qual frequencia uma tarefa deve ser executada
   * @var string
   */
  private $sPeriodicidadeExecucao;

  /**
   * Caminho do Fonte  onde encontyra-se a classe a ser executada.
   * @var string
   */
  private $sCaminhoPrograma;

  /**
   * Parametros a serem repassados/informados para a classe
   * @var array
   */
  private $aParametros = array();

  /**
   * Constante com a terminação do
   * @var string
   */
  const   SUFIXO_NOME_TAREFA = ".task.xml";

  /**
   * Construtor da Classe
   */
  function __construct( $sNome = null ) {

    db_app::import('configuracao.TaskManager');

    $this->setMomentoCricao( time() );
    $this->sDiretorio = TaskManager::PATH_FILA_TAREFAS;

    if ( !empty($sNome) ) {

      $this->setNome($sNome);

      if ( !file_exists( $this->getCaminhoTarefa() )) {
        throw new BusinessException("Arquivo não encontrado.");

      }
      $oArquivoXML = new DOMDocument();
      $oArquivoXML->load($this->getCaminhoTarefa());
      $aXMLTarefa = $oArquivoXML->getElementsByTagName('Tarefa');

      foreach ( $aXMLTarefa as $oXMLTarefa ) {

        $this->setMomentoCricao  ( $oXMLTarefa->getAttribute('DataCriacao')     );
        $this->setCodigoUsuario  ( $oXMLTarefa->getAttribute('UsuarioSistema')  );
        $this->setDescricao      ( $oXMLTarefa->getAttribute('Descricao')       );
        $this->setNomeClasse     ( $oXMLTarefa->getAttribute('NomeClasse')      );
        $this->setCaminhoPrograma( $oXMLTarefa->getAttribute('CaminhoPrograma') );
        $this->setObservacoes    ( $oXMLTarefa->getAttribute('Observacoes')     );

        $aXMLPeriodicidades = $oXMLTarefa->getElementsByTagName('Periodicidades');
        foreach ( $aXMLPeriodicidades as $oXMLPeriodicidades ) {

          $this->setTipoPeriodicidade($oXMLPeriodicidades->getAttribute('TipoPeriodicidade'));

          $aXMLPeriodicidade = $oXMLPeriodicidades->getElementsByTagName('Periodicidade');
          for ( $iIndicePeriodicidade = 0; $iIndicePeriodicidade < $aXMLPeriodicidade->length; $iIndicePeriodicidade++ ) {
            $this->adicionarPeriodicidade($aXMLPeriodicidade->item($iIndicePeriodicidade)->nodeValue);
          }
        }
        $aXMLParametros = $oXMLTarefa->getElementsByTagName('Parametros');
        foreach ( $aXMLParametros as $oXMLParametro ) {

          $aXMLParametro = $oXMLParametro->getElementsByTagName('Parametro');
          for ( $iIndiceParametro = 0; $iIndiceParametro < $aXMLParametro->length; $iIndiceParametro++ ) {
            $this->adicionarParametro($aXMLParametro->item($iIndiceParametro)->getAttribute('name'), $aXMLParametro->item($iIndiceParametro)->getAttribute('value'));
          }
        }
      }
    }
  }

  /**
   * Define o momento da Criação da Tarefa
   * @param integer $iMomentoCriacao (Timestamp)
   */
  public function setMomentoCricao($iMomentoCriacao) {
    $this->iMomentoCriacao = $iMomentoCriacao;
  }

  /**
   * Retorna o momento da Criação
   */
  public function getMomentoCricao() {
    return $this->iMomentoCriacao;
  }

  /**
   * Define o nome da tarefa
   * @param string $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna o nome da tarefa
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Retorna o diretorio da  tarefa
   */
  public function getDiretorio() {
    return $this->sDiretorio;
  }

  /**
   * Define o código do Usuario do Sistema qual incluir a tarefa
   * @param integer $iUsuario
   */
  public function setCodigoUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  /**
   * Retorna o código do Usuario do Sistema qual incluir a tarefa
   * @return integer
   */
  public function getCodigoUsuario() {
    return $this->iUsuario;
  }

  /**
   * Define a Descricao da Tarefa
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a Descricao da Tarefa
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna o nome da classe que sera executada
   * @param string  $sNomeClasse
   */
  public function setNomeClasse($sNomeClasse) {
    $this->sNomeClasse = $sNomeClasse;
  }

  /**
   * Retorna o nome da classe que foi/sera executada
   * @return string $sNomeClasse
   */
  public function getNomeClasse() {
    return $this->sNomeClasse;
  }

  /**
   *
   * @param unknown_type $sObservacoes
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }


  public function getObservacoes() {
    return $this->sObservacoes;
  }


  public function setCaminhoPrograma($sCaminhoPrograma) {
    $this->sCaminhoPrograma = $sCaminhoPrograma;
  }


  public function getCaminhoPrograma() {
    return $this->sCaminhoPrograma;
  }


  public function getCaminhoTarefa() {

    return $this->getDiretorio() . $this->getNome().self::SUFIXO_NOME_TAREFA;
  }


  public function getTipoPeriodicidade() {
    return $this->iTipoPeriodicidade;
  }


  public function setTipoPeriodicidade($iTipoPeriodicidade) {
    $this->iTipoPeriodicidade = $iTipoPeriodicidade;
  }


  public function getPeriodicidades() {
    return $this->aPeriodicidades;
  }


  public function setPeriodicidadeExecucao($sUnidade) {
    $this->sPeriodicidadeExecucao = $sUnidade;
  }

  public function getPeriodicidadeExecucao() {
    return $this->sPeriodicidadeExecucao;
  }

  public function salvar() {

    $oXMLWriter = new XMLWriter;

    $oXMLWriter->openURI($this->getCaminhoTarefa());
    $oXMLWriter->setindent(true);
    $oXMLWriter->startDocument('1.0', 'UTF-8');

    $oXMLWriter->startElement('Tarefa');

    $oXMLWriter->startAttribute('DataCriacao');
    $oXMLWriter->text( $this->getMomentoCricao() );
    $oXMLWriter->endAttribute();

    $oXMLWriter->startAttribute('UsuarioSistema');
    $oXMLWriter->text( $this->getCodigoUsuario() );
    $oXMLWriter->endAttribute();

    $oXMLWriter->startAttribute('Descricao');
    $oXMLWriter->text( $this->getDescricao() );
    $oXMLWriter->endAttribute();

    $oXMLWriter->startAttribute('NomeClasse');
    $oXMLWriter->text( $this->getNomeClasse() );
    $oXMLWriter->endAttribute();

    $oXMLWriter->startAttribute('CaminhoPrograma');
    $oXMLWriter->text( $this->getCaminhoPrograma() );
    $oXMLWriter->endAttribute();

    $oXMLWriter->startAttribute('Observacoes');
    $oXMLWriter->text( $this->getObservacoes() );
    $oXMLWriter->endAttribute();

    $oXMLWriter->startElement('Periodicidades');

    $oXMLWriter->startAttribute('TipoPeriodicidade');
    $oXMLWriter->text( $this->getTipoPeriodicidade() );
    $oXMLWriter->endAttribute();

    foreach ( $this->aPeriodicidades as $sPeriodicidade ) {

      $oXMLWriter->startElement('Periodicidade');
      $oXMLWriter->text( $sPeriodicidade );
      $oXMLWriter->endElement();//Fim Periodicidade
    }
    $oXMLWriter->endElement();//Fim Periodicidades

    /**
     * Adicionado bloco de parâmetros para passar para o model chamado
     */
    if (count($this->aParametros) > 0) {

      $oXMLWriter->startElement('Parametros'); // abre <Parametros>

      foreach ($this->aParametros as $sName => $mValor) {

        $oXMLWriter->startElement('Parametro'); // abre <Parametro>

        $oXMLWriter->startAttribute('name');
        $oXMLWriter->text( $sName );
        $oXMLWriter->endAttribute();

        $oXMLWriter->startAttribute('value');
        $oXMLWriter->text( $mValor );
        $oXMLWriter->endAttribute();

        $oXMLWriter->endElement(); // fecha </Parametro>
      }

      $oXMLWriter->endElement(); // fecha </Parametros>
    }
    $oXMLWriter->endElement();//Fim Tarefa

    $oXMLWriter->endDocument();
  }

  /**
   * Adiciona Periodicidade ao Trabalho a ser executado
   */
  public function adicionarPeriodicidade($iValor) {
    $this->aPeriodicidades[] = $iValor;
  }

  /**
   * [adicionarParametro description]
   * @param  mixed $sParametro nome do parametro
   * @param  mixed $mValor     valor
   */
  public function adicionarParametro($sParametro, $mValor) {

    $this->aParametros[$sParametro] = $mValor;
  }

  /**
   * Retornar os parâmetros informados
   * @return array onde o index é o nome do parâmetro
   */
  public function getParametros() {

   return $this->aParametros;
  }

  /**
   * Exclui o arquivo XML
   */
  public function excluir() {

    if ( empty($this->sNome) || !file_exists( $this->getCaminhoTarefa() ) ){
      throw new BusinessException("Arquivo não encontrado.");
    }
    unlink($this->getCaminhoTarefa());
  }
}
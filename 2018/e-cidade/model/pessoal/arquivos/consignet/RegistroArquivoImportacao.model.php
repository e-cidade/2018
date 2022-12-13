<?php

/**
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
 * Classe que representa um registro do ponto na consignet
 *
 * @package folha
 * @author  Renan Silva <renan.silva@dbseller.com.br>
 */
class RegistroArquivoImportacao{

  const RUBRICA_MARGEM_CONSIGNAVEL = 'R803';
  const MENSAGEM = 'recursoshumanos.pessoal.RegistroArquivoImportacao.';

  /**
   * @var Integer
   */
  private $iCodigoArquivo;

  /**
   * @var Object
   */
  private $oArquivo;
  
  /**
   * @var Integer
   */
  private $iLinha;

  /**
   * @var Integer
   */
  private $iSequencialMovimentoServidor;

  /**
   * @var Integer
   */
  private $iSequencialMovimentoServidorRubrica;

  /**
   * @var Integer
   */
  private $iMatricula = null;

  /**
   * @var $sNome
   */
  private $sNome = '';

  /**
   * @var $oServidor
   */
  private $oServidor;

  /**
   * @var $sRubric
   */
  private $sRubric = '';

  /**
   * @var $oRubrica
   */
  private $oRubrica;

  /**
   * @var $fValorParcela
   */
  private $fValorParcela;

  /**
   * @var $fValorDescontado
   */
  private $fValorDescontado;

  /**
   * @var $iParcela
   */
  private $iParcela;

  /**
   * @var $iTotalParcelas
   */
  private $iTotalParcelas;
  
  /**
   * @var $iMotivo
   */
  private $iMotivo = null;

  /**
   * Constrututor da classe
   * @param Integer|null $iMatricula
   * @param String|null  $sRubric
   * @param Float|null   $fValor
   */
  public function __construct(Integer $iMatricula = null, String $sRubric = null, $fValor = null) {
    
    if(!empty($iMatricula)){

      $this->iMatricula = $iMatricula;
      $this->oServidor  = ServidorRepository::getInstanciaByCodigo($iMatricula, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

    }

    if (!empty($sRubric)){

      $this->sRubric  = $sRubric;
      $this->oRubrica = RubricaRepository::getInstanciaByCodigo($sRubric);

    }

    if (!empty($fValor)){
      $this->fValorParcela = $fValor;
    }
  }
  
  /**
   * Define Codigo do Arquivo do Registro do Arquivo de importação
   * @param Integer $iCodigoArquivo
   */
  public function setCodigoArquivo ($iCodigoArquivo) {
    $this->iCodigoArquivo = $iCodigoArquivo;
  }
  
  /**
   * Retorna Codigo do Arquivo do Registro do Arquivo de importação
   * @return Integer
   */
  public function getCodigoArquivo () {
    return $this->iCodigoArquivo;
  }

  /**
   * Define Arquivo de importação
   * @param Object $oArquivo
   */
  public function setArquivo ($oArquivo) {
    $this->oArquivo = $oArquivo;
  }
  
  /**
   * Retorna o Arquivo de importação
   * @return Object
   */
  public function getArquivo () {
    return $this->oArquivo;
  }
  
  /**
   * Define a Linha do Registro do Arquivo de importação
   * @param Integer $iLinha
   */
  public function setLinha ($iLinha) {
    $this->iLinha = $iLinha;
  }
  
  /**
   * Retorna a Linha do Registro do Arquivo de importação
   * @return Integer
   */
  public function getLinha () {
    return $this->iLinha;
  }

  /**
   * Define o sequencial da tabela Movimento servidor
   * @param Integer $iSequencialMovimentoServidor
   */
  public function setSequencialMovimentoServidor ($iSequencialMovimentoServidor) {
    $this->iSequencialMovimentoServidor = $iSequencialMovimentoServidor;
  }
  
  /**
   * Retorna o sequencial da tabela Movimento servidor
   * @return Integer
   */
  public function getSequencialMovimentoServidor () {
    return $this->iSequencialMovimentoServidor;
  }

  /**
   * Define o sequencial da tabela Movimento servidor rubrica
   * @param Integer $iSequencialMovimentoServidorRubrica
   */
  public function setSequencialMovimentoServidorRubrica ($iSequencialMovimentoServidorRubrica) {
    $this->iSequencialMovimentoServidorRubrica = $iSequencialMovimentoServidorRubrica;
  }
  
  /**
   * Retorna o sequencial da tabela Movimento servidor rubrica
   * @return Integer
   */
  public function getSequencialMovimentoServidorRubrica () {
    return $this->iSequencialMovimentoServidorRubrica;
  }
  
  /**
   * Define a Matricula do servidor no Registro do Arquivo de importação
   * @param Integer $iMatricula
   */
  public function setMatricula ($iMatricula) {
    $this->iMatricula = $iMatricula;
  }
  
  /**
   * Retorna a Matricula do servidor no Registro do Arquivo de importação
   * @return Integer
   */
  public function getMatricula () {
    return $this->iMatricula;
  }
  
  /**
   * Define o Nome do servidor no Registro do Arquivo de importação
   * @param String $sNome
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna o Nome do servidor no Registro do Arquivo de importação
   * @return [type]
   */
  public function getNome () {
    return $this->sNome;
  }
  
  /**
   * Define o objeto Servidor do Registro do Arquivo de importação
   * @param Object $oServidor
   */
  public function setServidor ($oServidor) {
    $this->oServidor = $oServidor;
  }
  
  /**
   * Retorna o objeto Servidor do Registro do Arquivo de importação
   * @return Object
   */
  public function getServidor () {
    return $this->oServidor;
  }
  
  /**
   * Define a Rubrica do Registro do Arquivo de importação
   * @param String $sRubric
   */
  public function setRubric ($sRubric) {
    $this->sRubric = $sRubric;
  }
  
  /**
   * Retorna a Rubrica do Registro do Arquivo de importação
   * @return String
   */
  public function getRubric () {
    return $this->sRubric;
  }
  
  /**
   * Define o objeto Rubrica do Registro do Arquivo de importação
   * @param Object $oRubrica
   */
  public function setRubrica ($oRubrica) {
    $this->oRubrica = $oRubrica;
  }
  
  /**
   * Retorna o objeto Rubrica do Registro do Arquivo de importação
   * @return Object
   */
  public function getRubrica () {
    return $this->oRubrica;
  }
  
  /**
   * Define o Valor da Parcela do Registro do Arquivo de importação
   * @param Float $fValorParcela
   */
  public function setValorParcela ($fValorParcela) {
    $this->fValorParcela = $fValorParcela;
  }
  
  /**
   * Retorna o Valor da Parcela do Registro do Arquivo de importação
   * @return Float
   */
  public function getValorParcela () {
    return $this->fValorParcela;
  }

  /**
   * Define o Valor descontado do Servidor
   * @param Float $fValor
   */
  public function setValorDescontado ($fValor) {
    $this->fValorDescontado = $fValor;
  }
  
  /**
   * Retorna o Valor descontado do Servidor
   * @return Float
   */
  public function getValorDescontado () {
    return $this->fValorDescontado;
  }
  
  /**
   * Define a Parcela do Registro do Arquivo de importação
   * @param Integer $iParcela
   */
  public function setParcela ($iParcela) {
    $this->iParcela = $iParcela;
  }
  
  /**
   * Retorna a Parcela do Registro do Arquivo de importação
   * @return Integer
   */
  public function getParcela () {
    return $this->iParcela;
  }
  
  /**
   * Define o Total de Parcelas do Registro do Arquivo de importação
   * @param Integer $iTotalParcelas
   */
  public function setTotalParcelas ($iTotalParcelas) {
    $this->iTotalParcelas = $iTotalParcelas;
  }
  
  /**
   * Retorna o Total de Parcelas do Registro do Arquivo de importação
   * @return Integer
   */
  public function getTotalParcelas () {
    return $this->iTotalParcelas;
  }
  
  /**
   * Define o código do Motivo do Registro do Arquivo de importação
   * @param Integer $iMotivo
   */
  public function setMotivo ($iMotivo) {
    $this->iMotivo = $iMotivo;
  }
  
  /**
   * Retorna o códifo do Motivo do Registro do Arquivo de importação
   * @return Integer
   */
  public function getMotivo () {
    return $this->iMotivo;
  }
}

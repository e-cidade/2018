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

class CabecalhoLicitaCon {

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * @var DBDate Data Inicial das Informações
   */
  private $oDataInicial;

  /**
   * @var DBDate Data Final das Informações
   */
  private $oDataFinal;

  /**
   * @var DBDate Data da Geração do Arquivo
   */
  private $oDataGeracao;

  /**
   * @var integer Total de Registros
   */
  private $iTotalRegistros;

  /**
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * @return DBDate
   */
  public function getDataInicial() {
      return $this->oDataInicial;
  }

  /**
   * @param DBDate $oDataInicial Data Inicial das Informações
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @return DBDate Data Final das Informações
   */
  public function getDataFinal() {
      return $this->oDataFinal;
  }

  /**
   * @param DBDate $oDataFinal Data Final das Informações
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @return DBDate Data da Geração do Arquivo
   */
  public function getDataGeracao() {
    return $this->oDataGeracao;
  }

  /**
   * @param DBDate $oDataGeracao Data da Geração do Arquivo
   */
  public function setDataGeracao(DBDate $oDataGeracao) {
    $this->oDataGeracao = $oDataGeracao;
  }

  /**
   * @return integer Total de Registros
   */
  public function getTotalRegistros() {
    return $this->iTotalRegistros;
  }

  /**
   * @param integer $iTotalRegistros Total de Registros
   */
  public function setTotalRegistros($iTotalRegistros) {
    $this->iTotalRegistros = (integer) $iTotalRegistros;
  }

  /**
   * Retorno objeto no formato que é utilizado no layout
   *
   * @return StdClass
   */
  public function getDadosLayout() {

    return (object) array(
      'NOME_SETOR'      => $this->getInstituicao()->getDescricao(),
      'CNPJ'            => $this->getInstituicao()->getCnpj(),
      'DATA_INICIAL'    => $this->getDataInicial()->getDate('d/m/Y'),
      'DATA_FINAL'      => $this->getDataFinal()->getDate('d/m/Y'),
      'DATA_GERACAO'    => $this->getDataGeracao()->getDate('d/m/Y'),
      'TOTAL_REGISTROS' => $this->getTotalRegistros()
    );
  }
}
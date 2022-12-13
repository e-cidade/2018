<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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
 *  Classe responsável pelos dados dos eventos financeiros automaticos
 */
class ConfiguracaoEventoFinanceiroAutomatico {
  
  private $iCodigo;

  private $sDescricao;

  private $oRubrica;

  private $iMes;

  private $oSelecao;

  private $oInstituicao;

  public function __construct() {}
  
  /**
   * Codigo sequencial da tabela
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Descrição da configuração
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Rubrica que deve ser lançada como evendo financeiro
   * @param Rubrica $oRubrica [description]
   */
  public function setRubrica(Rubrica $oRubrica) {
    $this->oRubrica = $oRubrica;
  }

  /**
   * Define o mes no qual o lançamento de evento financeiro irá ocorrer
   * @param integer $iMes [01 ... 12]
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * Define a seleção para qual o lançamento de evento financeiro deve ocorrer
   * @param Selecao $oSelecao
   */
  public function setSelecao(Selecao $oSelecao) {
    $this->oSelecao = $oSelecao;
  }

  /**
   * Define a Instituicao
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna Codigo sequencial da tabela
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna Descrição da configuração
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna a rubrica que deve ser lançada como evendo financeiro
   * @return Rubrica $oRubrica
   */
  public function getRubrica() {
    return $this->oRubrica;
  }

  /**
   * Retorna o mes no qual o lançamento de evento financeiro irá ocorrer
   * @return integer $iMes
   */
  public function getMes() {
    return $this->iMes;
  }

  /**
   * Retorna a seleção para qual o lançamento de evento financeiro deve ocorrer
   * @return Selecao
   */
  public function getSelecao() {
    return $this->oSelecao;
  }

  /**
   * Retorna Define a Instituicao
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  } 
}
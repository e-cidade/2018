<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015 DBSeller Servicos de Informatica
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
 * Representa o padrão que o Servidor
 *
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
class Padrao {

  const TIPO_CALCULO_HORA = "H";
  const TIPO_CALCULO_MES = "M";

  /**
   * Regime Vinculado
   *
   * @var Regime
   */
  private $oRegime;
  /**
   * oCompetencia
   *
   * @var DBCompetencia
   */
  private $oCompetencia;
  /**
   * Instiituição
   *
   * @var Instituicao
   */
  private $oInstituicao;
  /**
   * Codigo Identificador do padrão
   *
   * @var String
   */
  private $sCodigo;
  /**
   * Valor do Padrão
   *
   * @var Number
   */
  private $nValor;
  /**
   * Fórmula utilizada para o cálculo do valor
   *
   * @var string
   */
  private $sFormula;
  /**
   * Tipo valor
   *
   * M - Mensal - Padrao::TIPO_CALCULO_MES
   * H - Horário - Padrao::TIPO_CALCULO_HORA
   *
   * @var String
   */
  private $sTipo;
  /**
   * Descrição do Padrão
   *
   * @var String
   */
  private $sDescricao;


  /**
   * Construtor da Classe
   *
   * @param String $sCodigo
   * @param Regime $oRegime
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   */
  public function __construct( $sCodigo,  Regime $oRegime,  DBCompetencia $oCompetencia, Instituicao $oInstituicao) {

    $this->sCodigo      = $sCodigo;
    $this->oCompetencia = $oCompetencia;
    $this->oInstituicao = $oInstituicao;
    $this->oRegime      = $oRegime;
  }

  /**
   * Retorna o valor de oRegime.
   *
   * @return oRegime.
   */
  public function getRegime() {
    return $this->oRegime;
  }

  /**
   * Retorna o valor de oCompetencia.
   *
   * @return oCompetencia.
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }


  /**
   * Retorna o valor de oInstituicao.
   *
   * @return oInstituicao.
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }


  /**
   * Retorna o valor de sCodigo.
   *
   * @return sCodigo.
   */
  public function getCodigo() {
    return $this->sCodigo;
  }

  /**
   * Retorna o valor de nValor.
   *
   * @return nValor.
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Define o valor de nValor.
   *
   * @param nValor the value to set.
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna o valor de sFormula.
   *
   * @return sFormula.
   */
  public function getFormula() {
    return $this->sFormula;
  }

  /**
   * Define o valor de sFormula.
   *
   * @param sFormula the value to set.
   */
  public function setFormula($sFormula) {
    $this->sFormula = $sFormula;
  }

  /**
   * Retorna o valor de sTipo.
   *
   * @return sTipo.
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * Define o valor de sTipo.
   *
   * @param sTipo the value to set.
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
  }

  /**
   * Retorna o valor de Descricao.
   *
   * @return sDescricao.
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define o valor de sDescricao.
   *
   * @param sDescricao the value to set.
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
}

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

namespace ECidade\Tributario\Agua;

use BusinessException;
use DBException;
use ECidade\Financeiro\Tesouraria\Receita;
use ECidade\Financeiro\Tesouraria\Repository\Receita as ReceitaRepository;
use ECidade\Tributario\Arrecadacao\TipoDebito;
use ECidade\Tributario\Cadastro\Caracteristica;
use ParameterException;

class Configuracao {

  /**
   * @var int
   */
  private $iCodigoInstituicao;

  /**
   * @var int
   */
  private $iAno;

  /**
   * @var int
   */
  private $iCodigoTipoDebito;

  /**
   * @var int
   */
  private $iCodigoCaracteristicaSemEsgoto;

  /**
   * @var int
   */
  private $iCodigoCaracteristicaSemAgua;

  /**
   * @var int
   */
  private $iCodigoReceitaCredito;

  /**
   * @var int
   */
  private $iCodigoReceitaDebito;

  /**
   * @var TipoDebito
   */
  private $oTipoDebito;

  /**
   * @var Caracteristica
   */
  private $oCaracteristicaSemEsgoto;

  /**
   * @var Caracteristica
   */
  private $oCaracteristicaSemAgua;

  /**
   * @var Receita
   */
  private $oReceitaDebito;

  /**
   * @var Receita
   */
  private $oReceitaCredito;

  /**
   * @param int $iAno
   * @param int $iCodigoInstituicao
   *
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function __construct($iAno, $iCodigoInstituicao) {

    if (!$iAno) {
      throw new ParameterException('Ano não informado.');
    }

    if (!$iCodigoInstituicao) {
      throw new ParameterException('Código de Instituição não informado.');
    }

    $this->iAno = $iAno;
    $this->iCodigoInstituicao = $iCodigoInstituicao;

    $rsConfiguracao = db_query("select * from aguaconf where x18_anousu = {$iAno} limit 1");

    if (!$rsConfiguracao) {
      throw new DBException('Ocorreu um erro ao procurar a configuração.');
    }

    if (pg_num_rows($rsConfiguracao) === 0) {
      throw new BusinessException('Configurações não encontradas para o exercício. A rotina de Virada Anual deve ser executada.');
    }

    $oConfiguracao = pg_fetch_object($rsConfiguracao);

    $this->iCodigoCaracteristicaSemEsgoto = $oConfiguracao->x18_carsemesgoto;
    $this->iCodigoCaracteristicaSemAgua   = $oConfiguracao->x18_carsemagua;
    $this->iCodigoReceitaCredito          = $oConfiguracao->x18_receitacreditorecalculo;
    $this->iCodigoReceitaDebito           = $oConfiguracao->x18_receitadebitorecalculo;
    $this->iCodigoTipoDebito              = $oConfiguracao->x18_arretipo;
  }

  /**
   * @return Configuracao
   * @throws BusinessException
   * @throws DBException
   */
  public static function create() {

    $sSql = "select codigo from db_config where db21_usasisagua is true limit 1";
    $rsDados = db_query($sSql);

    if (!$rsDados) {
      throw new DBException('Ocorreu um erro ao procurar a insituição do Água.');
    }

    if (pg_num_rows($rsDados) === 0) {
      throw new BusinessException('Nenhuma instituição está configurada para utilizar o módulo Água.');
    }

    $iInstituicao = pg_fetch_result($rsDados, 'codigo');
    $iAno = db_getsession('DB_anousu');

    return new self($iAno, $iInstituicao);
  }

  /**
   * @return int
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param int $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param int $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return int
   */
  public function getCodigoTipoDebito() {
    return $this->iCodigoTipoDebito;
  }

  /**
   * @deprecated
   */
  public function getCodigoTipoArrecadacao() {
    return $this->getCodigoTipoDebito();
  }

  /**
   * @param int $iCodigoTipoDebito
   */
  public function setCodigoTipoDebito($iCodigoTipoDebito) {
    $this->iCodigoTipoDebito = $iCodigoTipoDebito;
  }

  /**
   * @return int
   */
  public function getCodigoCaracteristicaSemEsgoto() {
    return $this->iCodigoCaracteristicaSemEsgoto;
  }

  /**
   * @param int $iCodigoCaracteristicaSemEsgoto
   */
  public function setCodigoCaracteristicaSemEsgoto($iCodigoCaracteristicaSemEsgoto) {
    $this->iCodigoCaracteristicaSemEsgoto = $iCodigoCaracteristicaSemEsgoto;
  }

  /**
   * @return int
   */
  public function getCodigoCaracteristicaSemAgua() {
    return $this->iCodigoCaracteristicaSemAgua;
  }

  /**
   * @param int $iCodigoCaracteristicaSemAgua
   */
  public function setCodigoCaracteristicaSemAgua($iCodigoCaracteristicaSemAgua) {
    $this->iCodigoCaracteristicaSemAgua = $iCodigoCaracteristicaSemAgua;
  }

  /**
   * @return int
   */
  public function getCodigoReceitaCredito() {
    return $this->iCodigoReceitaCredito;
  }

  /**
   * @param int $iCodigoReceitaCredito
   */
  public function setCodigoReceitaCredito($iCodigoReceitaCredito) {
    $this->iCodigoReceitaCredito = $iCodigoReceitaCredito;
  }

  /**
   * @return int
   */
  public function getCodigoReceitaDebito() {
    return $this->iCodigoReceitaDebito;
  }

  /**
   * @param int $iCodigoReceitaDebito
   */
  public function setCodigoReceitaDebito($iCodigoReceitaDebito) {
    $this->iCodigoReceitaDebito = $iCodigoReceitaDebito;
  }

  /**
   * @return TipoDebito
   */
  public function getTipoDebito() {

    if (!$this->oTipoDebito && $this->iCodigoTipoDebito) {
      $this->oTipoDebito = new TipoDebito($this->iCodigoTipoDebito);
    }
    return $this->oTipoDebito;
  }

  /**
   * @param TipoDebito $oTipoDebito
   */
  public function setTipoDebito(TipoDebito $oTipoDebito) {
    $this->oTipoDebito = $oTipoDebito;
  }

  /**
   * @return Caracteristica
   */
  public function getCaracteristicaSemEsgoto() {

    if (!$this->oCaracteristicaSemEsgoto && $this->iCodigoCaracteristicaSemEsgoto) {
      $this->oCaracteristicaSemEsgoto = new Caracteristica($this->iCodigoCaracteristicaSemEsgoto);
    }
    return $this->oCaracteristicaSemEsgoto;
  }

  /**
   * @param Caracteristica $oCaracteristicaSemEsgoto
   */
  public function setCaracteristicaSemEsgoto(Caracteristica $oCaracteristicaSemEsgoto) {
    $this->oCaracteristicaSemEsgoto = $oCaracteristicaSemEsgoto;
  }

  /**
   * @return Caracteristica
   */
  public function getCaracteristicaSemAgua() {

    if (!$this->oCaracteristicaSemAgua && $this->iCodigoCaracteristicaSemAgua) {
      $this->oCaracteristicaSemAgua = new Caracteristica($this->iCodigoCaracteristicaSemAgua);
    }
    return $this->oCaracteristicaSemAgua;
  }

  /**
   * @param Caracteristica $oCaracteristicaSemAgua
   */
  public function setCaracteristicaSemAgua(Caracteristica $oCaracteristicaSemAgua) {
    $this->oCaracteristicaSemAgua = $oCaracteristicaSemAgua;
  }

  /**
   * @return Receita
   */
  public function getReceitaDebito() {

    if (!$this->oReceitaDebito && $this->iCodigoReceitaDebito) {
      $this->oReceitaDebito = ReceitaRepository::getById($this->iCodigoReceitaDebito);
    }
    return $this->oReceitaDebito;
  }

  /**
   * @param Receita $oReceitaDebito
   */
  public function setReceitaDebito(Receita $oReceitaDebito) {
    $this->oReceitaDebito = $oReceitaDebito;
  }

  /**
   * @return Receita
   */
  public function getReceitaCredito() {

    if (!$this->oReceitaCredito && $this->iCodigoReceitaCredito) {
      $this->oReceitaCredito = ReceitaRepository::getById($this->iCodigoReceitaCredito);
    }

    return $this->oReceitaCredito;
  }

  /**
   * @param Receita $oReceitaCredito
   */
  public function setReceitaCredito(Receita $oReceitaCredito) {
    $this->oReceitaCredito = $oReceitaCredito;
  }

  public function salvar() {

    if (!$this->iCodigoTipoDebito) {
      throw new BusinessException('Tipo de Débito não informado.');
    }

    if (!$this->iCodigoCaracteristicaSemAgua) {
      throw new BusinessException('Característica Sem Água não informada.');
    }

    if (!$this->iCodigoCaracteristicaSemEsgoto) {
      throw new BusinessException('Característica Sem Esgoto não informada.');
    }

    $aCamposAtualizar = array(
      "x18_carsemesgoto = {$this->iCodigoCaracteristicaSemEsgoto}",
      "x18_carsemagua = {$this->iCodigoCaracteristicaSemAgua}",
      "x18_arretipo = {$this->iCodigoTipoDebito}",
    );

    $sCamposAtualizar = implode(', ', $aCamposAtualizar);
    $sSql = "update aguaconf set {$sCamposAtualizar} where x18_anousu = {$this->iAno}";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao atualizar a configuração.');
    }
  }
}

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
 * classe par acontrole de abastecimentos de veiculos
 * @package Veiculos
 */
class AbastecimentoVeiculo {

  protected $iCodigoAbastecimento;

  protected $iTipoCombustivel;

  /**
   * @type DBDate
   */
  protected $dtAbastecimento;

  protected $nLitros;

  protected $iVeiculo;

  protected $nValorTotal;

  protected $nValorUnitario;

  protected $nMedidaUso;

  protected $lAtivo;

  protected $iCodigoUsuario;

  /**
   * @type DBDate
   */
  protected $dtInclusao;

  protected $hHoraInclusao;
  function __construct($iCodigoAbastecimento) {

    if (!empty($iCodigoAbastecimento)) {

      $oDaoAbastecimento      = new cl_veicabast();
      $sSqlDadosAbastecimento = $oDaoAbastecimento->sql_query_info($iCodigoAbastecimento);
      $rsAbastecimento        = $oDaoAbastecimento->sql_record($sSqlDadosAbastecimento);
      if ($oDaoAbastecimento->numrows == 1) {

        $oDadosAbastecimento     = db_utils::fieldsMemory($rsAbastecimento, 0);
        $this->setDataAbastecimento(new DBDate($oDadosAbastecimento->ve70_dtabast));
        $this->iCodigoUsuario       = $oDadosAbastecimento->ve70_usuario;
        $this->dtInclusao           = new DBDate($oDadosAbastecimento->ve70_data);
        $this->hHoraInclusao        = $oDadosAbastecimento->ve70_hora;
        $this->nValorUnitario       = $oDadosAbastecimento->ve70_vlrun;
        $this->nValorTotal          = $oDadosAbastecimento->ve70_valor;
        $this->nMedidaUso           = $oDadosAbastecimento->ve70_medida;
        $this->nLitros              = $oDadosAbastecimento->ve70_litros;
        $this->lAtivo               = $oDadosAbastecimento->ve70_ativo=='f'?false:true;
        $this->iCodigoAbastecimento = $oDadosAbastecimento->ve70_codigo;
        $this->iTipoCombustivel     = $oDadosAbastecimento->ve70_veiculoscomb;
        $this->iVeiculo             = $oDadosAbastecimento->ve70_veiculos;
      }
    }
  }
  /**
   * data de abastecimento do veiculo
   * @return DBDate de abastecimento
   */
  public function getDataAbastecimento() {
    return $this->dtAbastecimento;
  }

  /**
   * define a data de abastecimento do veiculo
   * @param string $dtAbastecimento data no formato YYYY-mm-dd
   */
  public function setDataAbastecimento($dtAbastecimento) {
    $this->dtAbastecimento = $dtAbastecimento;
  }

  /**
   * @return DBDate de inclusao do abastecimento no sistema
   */
  public function getDataInclusao() {
    return $this->dtInclusao;
  }

  /**
   * Retorna a hora de inclusao do Abastecimento
   * @return string com a hora de inclusão
   */
  public function getHoraInclusao() {

    return $this->hHoraInclusao;
  }

  /**
   * Retorna o codigo da Inclusao do abastecimento
   * @return integer
   */
  public function getCodigoAbastecimento() {
    return $this->iCodigoAbastecimento;
  }

  /**
   * Retorna  o Código do usuario que realizou a inclusão
   * @return Codigo do Usuario
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * @param unknown_type $iCodigoUsuario
   */
  public function setICodigoUsuario($iCodigoUsuario) {

    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @return Retorna o tipo do combustivel
   */
  public function getTipoCombustivel() {
    return $this->iTipoCombustivel;
  }

  /**
   * Define o Tipo do Combustivel do Veiculo
   * @param integer $iTipoCombustivel Código do tipo do combustivel
   */
  public function setTipoCombustivel($iTipoCombustivel) {
    $this->iTipoCombustivel = $iTipoCombustivel;
  }

  /**
   * @return unknown
   */
  public function isAtivo() {

    return $this->lAtivo;
  }

  /**
   * @return Quantidades delitros abastecidos
   */
  public function getLitros() {

    return $this->nLitros;
  }

  /**
   * quantidade de litros que foram abastecidos
   * @param float $nLitros quantidade de litros abastecidos
   */
  public function setLitros($nLitros) {

    $this->nLitros = $nLitros;
  }

  /**
   * Retorna qual a quilometragem/Horas de uso o veiculo foi abastecido.
   * @return float
   */
  public function getMedidaUso() {

    return $this->nMedidaUso;
  }

  /**
   * Define qual quilometragem/Horas de uso o abastecimento foi realizado.
   * @param integer $nMedidaUso quilometragem/horas de uso
   */
  public function setMedidaUso($nMedidaUso) {

    $this->nMedidaUso = $nMedidaUso;
  }

  /**
   * @return o Valor Total do abastecimento
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Retorna o valor Unitario do Litro
   * @return float valor unitario do litro
   */
  public function getValorUnitario() {

    return $this->nValorUnitario;
  }

  /**
   * valor unitário do litro
   * @param float $nValorUnitario valor unitario do litro
   */
  public function setValorUnitario($nValorUnitario) {

    $this->nValorUnitario = $nValorUnitario;
  }


  function __destruct() {

  }
}

?>
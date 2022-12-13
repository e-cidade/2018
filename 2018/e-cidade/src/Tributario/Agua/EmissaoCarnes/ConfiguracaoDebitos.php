<?php

namespace ECidade\Tributario\Agua\EmissaoCarnes;

use AguaContrato as Contrato;
use AguaContratoEconomia as Economia;
use BusinessException;
use CgmBase as Cgm;
use DBException;
use ParameterException;

/**
 * Gerencia em qual carnê os débitos referentes a parcelamentos e diversos devem ser enviados.
 */
class ConfiguracaoDebitos {

  /**
   * @var Cgm
   */
  private $oCgm;

  /**
   * @var Contrato
   */
  private $oContrato;

  /**
   * @var Economia
   */
  private $oEconomia;

  /**
   * @param Cgm $oCgm
   */
  public function setCgm(Cgm $oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * @param Contrato $oContrato
   */
  public function setContrato(Contrato $oContrato) {
    $this->oContrato = $oContrato;
  }

  public function getContrato() {
    return $this->oContrato;
  }

  /**
   * @param Economia $oEconomia
   */
  public function setEconomia(Economia $oEconomia) {
    $this->oEconomia = $oEconomia;
  }

  public function getEconomia() {
    return $this->oEconomia;
  }

  /**
   * Limpa a configuração existente nos contratos e/ou economias onde o CGM está vinculado.
   *
   * @throws DBException
   */
  private function limparConfiguracao() {

    $sLimparContratos = "
      update
        aguacontrato
      set 
        x54_emitiroutrosdebitos = false
      where
        x54_cgm = {$this->oCgm->getCodigo()}
    ";
    if (!db_query($sLimparContratos)) {
      throw new DBException('Não foi possível desvincular os contratos para o CGM informado.');
    }

    $sLimparEconomias = "
      update
        aguacontratoeconomia
      set
        x38_emitiroutrosdebitos = false
      where
        x38_cgm = {$this->oCgm->getCodigo()};
    ";
    if (!db_query($sLimparEconomias)) {
      throw new DBException('Não foi possível desvincular as economias para o CGM informado.');
    }
  }

  /**
   * Altera a configuração para o Contrato ou Economia informado.
   * Caso o contrato seja de condomínio e o responsável pelo pagamento seja a economia a configuração
   * será salva na economia, caso contrário será salva no contrato.
   *
   * @throws DBException
   */
  private function alterarConfiguracao() {

    if ($this->oContrato->isCondominio() && $this->oContrato->isPagamentoEconomia()) {

      $sVincularEconomia = "
      update
        aguacontratoeconomia
      set
        x38_emitiroutrosdebitos = true
      where
        x38_sequencial = {$this->oEconomia->getCodigo()}";
      if (!db_query($sVincularEconomia)) {
        throw new DBException('Não foi possível salvar a configuração.');
      }
    } else {

      $sVincularContrato = "
      update
        aguacontrato
      set
        x54_emitiroutrosdebitos = true
      where
        x54_sequencial = {$this->oContrato->getCodigo()}";
      if (!db_query($sVincularContrato)) {
        throw new DBException('Não foi possível salvar a configuração.');
      }
    }
  }

  /**
   * @throws ParameterException
   */
  private function validarParametros() {

    if (!$this->oCgm) {
      throw new ParameterException('Nome/Razão Social não informado.');
    }

    if (!$this->oContrato) {
      throw new ParameterException('Contrato não informado.');
    }

    if (
         !$this->oEconomia
      && $this->oContrato->isCondominio()
      && $this->oContrato->isPagamentoEconomia()
    ) {
      throw new ParameterException('Economia não informada.');
    }

    if (
         !$this->oContrato->isCondominio()
      && $this->oContrato->getCodigoCgm() != $this->oCgm->getCodigo()
    ) {
      throw new BusinessException('O contrato não está vinculado ao CGM informado.');
    }

    if (
         $this->oContrato->isCondominio()
      && $this->oContrato->isPagamentoEconomia()
      && $this->oEconomia->getCodigoCgm() != $this->oCgm->getCodigo()
    ) {
      throw new BusinessException('A economia não está vinculada ao CGM informado.');
    }
  }

  /**
   * Procura por uma configuração para o CGM informado e carrega os dados no objeto.
   *
   * @param $iCgm
   * @return bool Verdadeiro se encontrou configuração, falso no caso contrário.
   * @throws DBException
   */
  public function carregar($iCgm) {

    $sSql = "
      select
        x54_sequencial as contrato,
        (case when x54_condominio is true and x54_responsavelpagamento = 1 then
          x38_sequencial
        else
          null
        end) as economia
      from
        aguacontrato
      left join aguacontratoeconomia on x38_aguacontrato = x54_sequencial
      where
        (x54_cgm = {$iCgm} and x54_emitiroutrosdebitos is true)
        or (
          case when x54_condominio is true and x54_responsavelpagamento = 1 then
            x38_cgm = {$iCgm} and x38_emitiroutrosdebitos is true
          else 
            false
          end
        )
    ";

    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao procurar pela Configuração da Emissão de Débitos.');
    }

    if (pg_num_rows($rsResultado) === 0) {
      return false;
    }

    $oResultado = pg_fetch_object($rsResultado);

    $this->oContrato = new Contrato($oResultado->contrato);
    if ($oResultado->economia) {

      $this->oEconomia = new Economia();
      $this->oEconomia->carregar($oResultado->economia);
    }

    return true;
  }

  /**
   * Salva a configuração.
   */
  public function salvar() {

    $this->validarParametros();
    $this->limparConfiguracao();
    $this->alterarConfiguracao();
  }
}

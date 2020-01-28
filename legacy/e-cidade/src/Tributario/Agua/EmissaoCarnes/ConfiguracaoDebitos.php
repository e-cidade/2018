<?php

namespace ECidade\Tributario\Agua\EmissaoCarnes;

use AguaContrato as Contrato;
use AguaContratoEconomia as Economia;
use BusinessException;
use CgmBase as Cgm;
use DBException;
use ParameterException;

/**
 * Gerencia em qual carn� os d�bitos referentes a parcelamentos e diversos devem ser enviados.
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
   * Limpa a configura��o existente nos contratos e/ou economias onde o CGM est� vinculado.
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
      throw new DBException('N�o foi poss�vel desvincular os contratos para o CGM informado.');
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
      throw new DBException('N�o foi poss�vel desvincular as economias para o CGM informado.');
    }
  }

  /**
   * Altera a configura��o para o Contrato ou Economia informado.
   * Caso o contrato seja de condom�nio e o respons�vel pelo pagamento seja a economia a configura��o
   * ser� salva na economia, caso contr�rio ser� salva no contrato.
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
        throw new DBException('N�o foi poss�vel salvar a configura��o.');
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
        throw new DBException('N�o foi poss�vel salvar a configura��o.');
      }
    }
  }

  /**
   * @throws ParameterException
   */
  private function validarParametros() {

    if (!$this->oCgm) {
      throw new ParameterException('Nome/Raz�o Social n�o informado.');
    }

    if (!$this->oContrato) {
      throw new ParameterException('Contrato n�o informado.');
    }

    if (
         !$this->oEconomia
      && $this->oContrato->isCondominio()
      && $this->oContrato->isPagamentoEconomia()
    ) {
      throw new ParameterException('Economia n�o informada.');
    }

    if (
         !$this->oContrato->isCondominio()
      && $this->oContrato->getCodigoCgm() != $this->oCgm->getCodigo()
    ) {
      throw new BusinessException('O contrato n�o est� vinculado ao CGM informado.');
    }

    if (
         $this->oContrato->isCondominio()
      && $this->oContrato->isPagamentoEconomia()
      && $this->oEconomia->getCodigoCgm() != $this->oCgm->getCodigo()
    ) {
      throw new BusinessException('A economia n�o est� vinculada ao CGM informado.');
    }
  }

  /**
   * Procura por uma configura��o para o CGM informado e carrega os dados no objeto.
   *
   * @param $iCgm
   * @return bool Verdadeiro se encontrou configura��o, falso no caso contr�rio.
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
      throw new DBException('Ocorreu um erro ao procurar pela Configura��o da Emiss�o de D�bitos.');
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
   * Salva a configura��o.
   */
  public function salvar() {

    $this->validarParametros();
    $this->limparConfiguracao();
    $this->alterarConfiguracao();
  }
}

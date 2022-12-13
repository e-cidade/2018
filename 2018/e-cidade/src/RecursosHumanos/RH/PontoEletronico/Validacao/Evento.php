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
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Validacao;

use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento as EventoModel;

/**
 * Class Evento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Validacao
 */
class Evento extends PontoEletronico implements InterfacePontoEletronico {

  /**
   * @var EventoModel
   */
  protected $evento;

  /**
   * @var array
   */
  protected $erros = array();

  /**
   * @return bool
   * @throws \ParameterException
   */
  public function validar() {

    if (empty($this->servidor) || !$this->servidor instanceof \Servidor) {
      throw new \ParameterException("Informe o servidor para ser validado.");
    }

    if (empty($this->evento) || !$this->evento instanceof EventoModel) {
      throw new \ParameterException("Informe o evento para ser validado.");
    }

    if ($this->possuiAfastamentoNoRHNaData($this->evento->getDataInicial())) {
      $this->erros[self::POSSUI_AFASTAMENTO_NO_RH_NA_DATA] = 'Servidor com afastamento no RH para a data do evento.';
    }

    if ( ! $this->possuiEscalaNaData($this->evento->getDataInicial())) {
      $this->erros[self::POSSUI_ESCALA_NA_DATA] = 'Servidor sem escala cadastrada para a data do evento.';
    }

    if ($this->possuiJustificativaNaData($this->evento->getDataInicial())) {
      $this->erros[self::POSSUI_JUSTIFICATIVA_NA_DATA] = 'Servidor com justificativa na data do evento.';
    }

    if ( ! $this->possuiLotacaoConfiguradaNoPontoEletronico()) {
      $this->erros[self::POSSUI_LOTACAO_CONFIGURADA_NO_PONTO_ELETRONICO] = 'Servidor sem configuração no ponto eletrônico.';
    }

    if ( ! $this->possuiLotacaoConfiguradaParaServidor()) {
      $this->erros[self::POSSUI_LOTACAO_CONFIGURADA] = 'Servidor sem lotação configurada.';
    }

    if ($this->possuiConflitoDeEvento($this->evento)) {
      $this->erros[self::POSSUI_CONFLITO_ENTRE_EVENTOS]  = "Os seguintes servidores já possuem evento para a data";
    }

    return count($this->erros) === 0;
  }

  /**
   * @return array
   */
  public function getErros() {
    return $this->erros;
  }

  /**
   * @param EventoModel $evento
   */
  public function setEvento(EventoModel $evento) {
    $this->evento = $evento;
  }
}
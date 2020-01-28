<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBseller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao;

use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;


/**
 * Classe que representa uma marca��o de sa�da do hor�rio ponto
 * Class MarcacaoPontoSaida
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class MarcacaoPontoSaida extends MarcacaoPonto {

  /**
   * @var \DateTime $oMarcacaoEntrada
   */
  private $oMarcacaoEntrada;

  /**
   * Define a hora da marca��o
   *
   * @param \DateTime $oMarcacaoEntrada
   */
  public function setMarcacaoEntrada($oMarcacaoEntrada) {
    $this->oMarcacaoEntrada = $oMarcacaoEntrada;
  }

  /**
   * Retorna a hora da marca��o
   *
   * @return \DateTime $oMarcacaoEntrada 
   */
  public function getMarcacaoEntrada() {
    return $this->oMarcacaoEntrada;
  }

  /**
   * Retorna o hor�rio trabalhado
   *
   * @return \DateInterval
   */
  public function getHorarioTrabalhado() {
    return !empty($this->oMarcacaoEntrada) && !is_null($this->oMarcacao) ? $this->oMarcacaoEntrada->diff($this->oMarcacao) : null;
  }
}

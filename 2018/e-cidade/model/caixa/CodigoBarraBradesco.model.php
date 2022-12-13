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
class CodigoBarraBradesco {

  /**
   * @var CodigoBarra
   */
  private $oCodigoBarra;

  /**
   * @var int
   */
  private $iAgenciaCedente;

  /**
   * @var int
   */
  private $iCarteira;

  /**
   * @var int
   */
  private $iNossoNumero;

  /**
   * @var int
   */
  private $iContaCedente;

  /**
   * @var int
   */
  private $iZero;

  /**
   * CodigoBarraBradesco constructor.
   *
   * @param CodigoBarra $oCodigoBarra
   */
  public function __construct(CodigoBarra $oCodigoBarra) {

    $this->oCodigoBarra = $oCodigoBarra;
    $this->processaCodigoBarra();
  }

  /**
   * @return int
   */
  public function getAgenciaCedente() {
    return $this->iAgenciaCedente;
  }

  /**
   * @return int
   */
  public function getCarteira() {
    return $this->iCarteira;
  }

  /**
   * @return int
   */
  public function getNossoNumero() {
    return $this->iNossoNumero;
  }

  /**
   * @return int
   */
  public function getContaCedente() {
    return $this->iContaCedente;
  }

  /**
   * @return int
   */
  public function getZero() {
    return $this->iZero;
  }

  /**
   * @return CodigoBarra
   */
  public function getCodigoBarra() {
    return $this->oCodigoBarra;
  }

  /**
   * Processa as informações do código de barras segundo o padrão do banco Bradesco.
   */
  private function processaCodigoBarra() {

    $sCampoLivre = $this->oCodigoBarra->getCampoLivre();
    $this->iAgenciaCedente = substr($sCampoLivre, 0, 4);
    $this->iCarteira       = substr($sCampoLivre, 4, 2);
    $this->iNossoNumero    = substr($sCampoLivre, 6, 11);
    $this->iContaCedente   = substr($sCampoLivre, 17, 7);
    $this->iZero           = substr($sCampoLivre, 24, 1);
  }

  /**
   * @return int
   */
  public function getDigitoAgencia() {
    return (int) $this->oCodigoBarra->modulo11($this->getAgenciaCedente());
  }

  /**
   * @return int
   */
  public function getDigitoContaCorrente() {
    return (int) $this->oCodigoBarra->modulo11($this->getContaCedente());
  }
}
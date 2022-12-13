<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo;

class Header
{
  /**
   * @var integer
   */
  private $iLote;

  /**
   * @var integer
   */
  private $iSequencial;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var \ContaBancaria
   */
  private $oContaBancaria;

  /**
   * @var \stdClass
   */
  private $oConvenio;

  /**
   * @return integer
   */
  public function getLote()
  {
    return $this->iLote;
  }

  /**
   * @param integer iLote
   */
  public function setLote($iLote)
  {
    $this->iLote = $iLote;
  }

  /**
   * @return integer
   */
  public function getSequencial()
  {
    return $this->iSequencial;
  }

  /**
   * @param integer iSequencial
   */
  public function setSequencial($iSequencial)
  {
    $this->iSequencial = $iSequencial;
  }

  /**
   * @return \Instituicao
   */
  public function getInstituicao()
  {
    return $this->oInstituicao;
  }

  /**
   * @param \Instituicao oInstituicao
   */
  public function setInstituicao(\Instituicao $oInstituicao)
  {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * @return \ContaBancaria
   */
  public function getContaBancaria()
  {
    return $this->oContaBancaria;
  }

  /**
   * @param \ContaBancaria oContaBancaria
   */
  public function setContaBancaria(\ContaBancaria $oContaBancaria)
  {
    $this->oContaBancaria = $oContaBancaria;
  }

  /**
   * @return \stdClass oConvenio
   */
  public function getConvenio()
  {
    return $this->oConvenio;
  }

  /**
   * @param \stdClass oConvenio
   */
  public function setConvenio(\stdClass $oConvenio)
  {
    $this->oConvenio = $oConvenio;
  }

}

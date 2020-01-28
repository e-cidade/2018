<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral;

use ECidade\Tributario\Arrecadacao\EmissaoGeral\Repository;

class EmissaoGeral
{
  const TIPO_IPTU = 1;

  /**
   * Constantes com os tipos de movimentação do retorno do banco
   */
  const MOVIMENTACAO_RETORNO_CONFIRMADO = '02';
  const MOVIMENTACAO_RETORNO_REJEITADO  = '03';
  const MOVIMENTACAO_RETORNO_LIQUIDACAO = '06';
  const MOVIMENTACAO_RETORNO_BAIXA      = '09';

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iTipo;

  /**
   * @var \DBDate
   */
  private $oData;

  /**
   * @var string
   */
  private $sHora;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var integer
   */
  private $iConvenio;

  /**
   * @var integer
   */
  private $iUsuario;

  /**
   * @var \stdClass
   */
  private $oParametros;

  public static function create($iTipoEmissao, $iConvenio, $oParametros)
  {
    $oEmissao = new EmissaoGeral();
    $oEmissao->setTipo($iTipoEmissao);
    $oEmissao->setConvenio($iConvenio);
    $oEmissao->setData(new \DBDate(date("Y-m-d", \db_getsession("DB_datausu"))));
    $oEmissao->setHora(date("H:i"));
    $oEmissao->setUsuario(\db_getsession("DB_id_usuario"));
    $oEmissao->setInstituicao(new \Instituicao(\db_getsession("DB_instit")));
    $oEmissao->setParametros($oParametros);

    $oRepository = new Repository();
    $oEmissao    = $oRepository->add($oEmissao);

    return $oEmissao;
  }

  /**
   * @return integer
   */
  public function getCodigo()
  {
    return $this->iCodigo;
  }

  /**
   * @param integer iCodigo
   */
  public function setCodigo($iCodigo)
  {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer
   */
  public function getTipo()
  {
    return $this->iTipo;
  }

  /**
   * @param integer iTipo
   */
  public function setTipo($iTipo)
  {
    $this->iTipo = $iTipo;
  }

  /**
   * @return \DBDate
   */
  public function getData()
  {
    return $this->oData;
  }

  /**
   * @param \DBDate oData
   */
  public function setData(\DBDate $oData)
  {
    $this->oData = $oData;
  }

  /**
   * @return string
   */
  public function getHora()
  {
    return $this->sHora;
  }

  /**
   * @param string sHora
   */
  public function setHora($sHora)
  {
    $this->sHora = $sHora;
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
   * @return integer
   */
  public function getConvenio()
  {
    return $this->iConvenio;
  }

  /**
   * @param integer iConvenio
   */
  public function setConvenio($iConvenio)
  {
    $this->iConvenio = $iConvenio;
  }

  /**
   * @return integer
   */
  public function getUsuario()
  {
    return $this->iUsuario;
  }

  /**
   * @param integer iUsuario
   */
  public function setUsuario($iUsuario)
  {
    $this->iUsuario = $iUsuario;
  }

  /**
   * @return \stdClass
   */
  public function getParametros()
  {
    return $this->oParametros;
  }

  /**
   * @param \stdClass oParametros
   */
  public function setParametros(\stdClass $oParametros)
  {
    $this->oParametros = $oParametros;
  }
}

<?php
/**
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

namespace ECidade\Tributario\Integracao\JuntaComercial\Model;


class Protocolo
{
  /**
   * @var integer $iCodigo
   */
  private $iCodigo;

  /**
   * @var string $sServico
   */
  private $sServico;

  /**
   * @var integer $iFuncao
   */
  private $iFuncao;

  /**
   * @var string $sProtocolo
   */
  private $sProtocolo;

  /**
   * @var integer $iXml
   */
  private $iXml;

  /**
   * @var \DateTime $oData
   */
  private $oData;

  /**
   * @var string $sCNPJEmissor
   */
  private $sCNPJEmissor;

  /**
   * @var string $sCNPJReceptor
   */
  private $sCNPJReceptor;

  /**
   * @var string $sCPFCNPJProcesso
   */
  private $sCPFCNPJProcesso;

  /**
   * OId do XML
   * @var integer
   */
  private $iOid;

  /**
   * Array de eventos
   * @var Evento[] $aEventos
   */
  private $aEventos;

  /**
   * @return Evento[]
   */
  public function getEventos()
  {
    return $this->aEventos;
  }

  /**
   * @param Evento $aEvento
   */
  public function adicionarEvento($aEvento)
  {
    $this->aEventos[] = $aEvento;
  }

  /**
   * @param Evento[] $aEventos
   */
  public function setEventos($aEventos)
  {
    $this->aEventos = $aEventos;
  }


  /**
   * @return int
   */
  public function getCodigo()
  {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo)
  {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string
   */
  public function getServico()
  {
    return $this->sServico;
  }

  /**
   * @param string $sServico
   */
  public function setServico($sServico)
  {
    $this->sServico = $sServico;
  }

  /**
   * @return int
   */
  public function getFuncao()
  {
    return $this->iFuncao;
  }

  /**
   * @param int $iFuncao
   */
  public function setFuncao($iFuncao)
  {
    $this->iFuncao = $iFuncao;
  }

  /**
   * @return string
   */
  public function getProtocolo()
  {
    return $this->sProtocolo;
  }

  /**
   * @param string $sProtocolo
   */
  public function setProtocolo($sProtocolo)
  {
    $this->sProtocolo = $sProtocolo;
  }

  /**
   * @return int
   */
  public function getXml()
  {
    return $this->iXml;
  }

  /**
   * @param int $iXml
   */
  public function setXml($iXml)
  {
    $this->iXml = $iXml;
  }

  /**
   * @return \DateTime
   */
  public function getData()
  {
    return $this->oData;
  }

  /**
   * @param \DateTime $oData
   */
  public function setData($oData)
  {
    $this->oData = $oData;
  }

  /**
   * @return string
   */
  public function getCNPJEmissor()
  {
    return $this->sCNPJEmissor;
  }

  /**
   * @param string $sCNPJEmissor
   */
  public function setCNPJEmissor($sCNPJEmissor)
  {
    $this->sCNPJEmissor = $sCNPJEmissor;
  }

  /**
   * @return string
   */
  public function getCNPJReceptor()
  {
    return $this->sCNPJReceptor;
  }

  /**
   * @param string $sCNPJReceptor
   */
  public function setCNPJReceptor($sCNPJReceptor)
  {
    $this->sCNPJReceptor = $sCNPJReceptor;
  }

  /**
   * @return string
   */
  public function getCPFCNPJProcesso()
  {
    return $this->sCPFCNPJProcesso;
  }

  /**
   * @param string $sCPFCNPJProcesso
   */
  public function setCPFCNPJProcesso($sCPFCNPJProcesso)
  {
    $this->sCPFCNPJProcesso = $sCPFCNPJProcesso;
  }

  public function getOIDXml() {

    if (empty($this->iOid)) {

      $this->iOid = \DBLargeObject::criaOID(true);
      $sCaminhoArquivo = "tmp/Regin{$this->getProtocolo()}.xml";
      file_put_contents($sCaminhoArquivo, $this->getXml());

       if (!\DBLargeObject::escrita($sCaminhoArquivo, $this->iOid)) {
          new \Exception("Não foi possível salvar o arquivo XML.");
       }

       unlink($sCaminhoArquivo);
    }
    return $this->iOid;

  }
  public function __construct(){ }
}

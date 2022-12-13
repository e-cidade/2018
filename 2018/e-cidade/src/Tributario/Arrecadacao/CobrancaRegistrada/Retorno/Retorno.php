<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno;

class Retorno
{
  private $iNumpre;

  private $iCodigoMovimento;

  private $iCodRet;

  private $aOcorrencias;

  public function __construct($iNumpre, $iCodigoMovimento, $iCodRet, $aOcorrencias)
  {
    $this->iNumpre          = $iNumpre;
    $this->iCodigoMovimento = $iCodigoMovimento;
    $this->iCodRet          = $iCodRet;
    $this->aOcorrencias     = $aOcorrencias;
  }

  public function getNumpre()
  {
    return $this->iNumpre;
  }

  public function getCodigoMovimento()
  {
    return $this->iCodigoMovimento;
  }

  public function getCodRet()
  {
    return $this->iCodRet;
  }

  public function getOcorrencias()
  {
    return $this->aOcorrencias;
  }

  public function setNumpre($iNumpre)
  {
    $this->iNumpre = $iNumpre;
  }

  public function setCodigoMovimento($iCodigoMovimento)
  {
    $this->iCodigoMovimento = $iCodigoMovimento;
  }

  public function setCodRet($iCodRet)
  {
    $this->iCodRet = $iCodRet;
  }

  public function setOcorrencia($aOcorrencia)
  {
    $this->aOcorrencias = $aOcorrencia;
  }

  public function addOcorrencia($iOcorrencia)
  {
    array_push($this->aOcorrencias, $iOcorrencia);
  }
}
<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\RequisicaoInterface;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\RetornoBoleto;
use SoapVar;
use SoapClient;

abstract class ConexaoSoap
{
  /**
   * Objeto que conterá a conexão com o Webservice
   * @var SoapClient
   */
  protected $oSoapClient;

  /**
   * Objeto que cria o arquivo de requisição do Webservice
   *
   * @var RequisicaoInterface
   */
  protected $oRequisicao;

  protected $oRetornoSoap;

  /**
   * Processamos a requisição conforme informações disponibilizadas
   *
   * @return \stdClass
   */
  public function processarRequisicao()
  {
    $oDomDocument = $this->oRequisicao->getRequestXml();
    $sXml         = $oDomDocument->saveXML();

    $sXml = str_replace("\\n", "", $sXml);
    $sXml = str_replace("<?xml version=\"1.0\"?>", "", $sXml);
    $sXml = str_replace("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>", "", $sXml);
    $sXml = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "", $sXml);

    $oSoapVar           = new SoapVar($sXml, XSD_ANYXML);
    $oRetornoSoap       = $this->oSoapClient->__soapCall($this->oRequisicao->getOperacao(), array($oSoapVar) );
    $this->oRetornoSoap = $oRetornoSoap;
  }

  public function getResposta() 
  {
    return new RetornoBoleto($this->oRetornoSoap);
  }
}
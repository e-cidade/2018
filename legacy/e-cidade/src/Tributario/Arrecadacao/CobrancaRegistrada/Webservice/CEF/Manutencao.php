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
use SoapClient;

class Manutencao extends ConexaoSoap
{
  /**
   * Constante com o wsdl do webservice desejado
   */
  const WSDL_HOMOLOGACAO    = "https://des.barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo?wsdl";
  const WSDL_PRODUCAO       = "https://barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo?wsdl";
  const USUARIO_HOMOLOGACAO = "SGCBS01D";

  /**
   * Código do banco que utiliza este webservice
   */
  const CODIGO_BANCO = "104";

  /**
   * Construtor da classe
   *
   * @param RequisicaoInterface $oRequisicao
   */
  public function __construct(RequisicaoInterface $oRequisicao)
  {
    $oContext = stream_context_create(array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    ));

    $aOpcoes = array("soap_version"   => SOAP_1_1,
                     "stream_context" => $oContext,
                     "cache_wsdl"     => WSDL_CACHE_NONE,
                     "trace"          => true);

    $oRegistro = $oRequisicao->getRegistro();

    $sWsdl = self::WSDL_PRODUCAO;

    if (trim($oRegistro->usuarioServico) == self::USUARIO_HOMOLOGACAO) {
      $sWsdl = self::WSDL_HOMOLOGACAO;
    }

    $this->oSoapClient = new SoapClient($sWsdl, $aOpcoes);
    $this->oRequisicao = $oRequisicao;
  }
}
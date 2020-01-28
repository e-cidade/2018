<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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
namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo;

class RetornoBoleto
{

	private $iCodigoRetorno;

	private $sMensagemRetorno;

	function __construct($oRetornoSoap)
	{
		if ($oRetornoSoap->COD_RETORNO != '00' && $oRetornoSoap->COD_RETORNO != '0') {

		  $this->setCodigoRetorno($oRetornoSoap->COD_RETORNO);
		  $this->setMensagemRetorno($oRetornoSoap->MSG_RETORNO);
		} else {

		  $this->setCodigoRetorno($oRetornoSoap->DADOS->CONTROLE_NEGOCIAL->COD_RETORNO);
		  $this->setMensagemRetorno($oRetornoSoap->DADOS->CONTROLE_NEGOCIAL->MENSAGENS->RETORNO);
		}

	}

	public function getMensagemRetorno($lUsuarioExterno = false)
	{
	   if ($lUsuarioExterno) {
	   	 return "Ocorreu um erro ao registrar o boleto no banco: $this->sMensagemRetorno. Por favor entre em contato com a Instituição.";
	   }

	   return $this->sMensagemRetorno;
	}

	public function setMensagemRetorno($sMensagemRetorno)
	{
	   $this->sMensagemRetorno = $sMensagemRetorno;
	}

	public function getCodigoRetorno()
	{
	   return $this->iCodigoRetorno;
	}

	public function setCodigoRetorno($iCodigoRetorno)
	{
	   $this->iCodigoRetorno = $iCodigoRetorno;
	}

	public function isBoletoIncluido() {
	  return ($this->iCodigoRetorno == 00);
	}

}

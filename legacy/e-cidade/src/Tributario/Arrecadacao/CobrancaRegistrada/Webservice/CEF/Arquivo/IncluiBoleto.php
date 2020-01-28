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

use DOMDocument;

/**
 * Classe responsável pela criação do xml para a operação INCLUIR_BOLETO
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class IncluiBoleto implements RequisicaoInterface
{
  const SISTEMA_ORIGEM = "SIGCB";
  const VERSAO         = "1.5";

  private $oXml;
  private $oRegistro;
  private $sOperacao;

  /**
   * Criamos o objeto da classe com as informações necessárias para a sua exitência
   *
   * @param stdClass $oRegistro
   */
  public function __construct(\stdClass $oRegistro)
  {
    $this->oXml      = new DOMDocument("1.0", "utf-8");
    $this->oRegistro = $oRegistro;
    $this->sOperacao = "INCLUI_BOLETO";
  }

  /**
   * Buscamos a operação que será executada no webservice
   *
   * @return string
   */
  public function getOperacao()
  {
    return $this->sOperacao;
  }

  /**
   * Retornamos a coleção com os registros
   *
   * @return stdClass
   */
  public function getRegistro()
  {
    return $this->oRegistro;
  }

  /**
   * Geramos o xml com os dados do boleto
   *
   * @return DOMDocument
   */
  public function getRequestXml()
  {
    $oServicoEntrada = $this->oXml->createElementNS( 'http://caixa.gov.br/sibar/manutencao_cobranca_bancaria/boleto/externo',
                                                     'manutencaocobrancabancaria:SERVICO_ENTRADA' );

    $oHeader = $this->getHeaderXml();
    $oDados  = $this->getDadosXml();

    $oServicoEntrada->appendChild($oHeader);
    $oServicoEntrada->appendChild($oDados);

    $this->oXml->appendChild($oServicoEntrada);

    return $this->oXml;
  }

  /**
   * Criamos o Header do xml
   *
   * @return DOMElement
   */
  protected function getHeaderXml()
  {
    $oHeader = $this->oXml->createElementNS( "http://caixa.gov.br/sibar", "sibar_base:HEADER" );

    $oVersao         = $this->oXml->createElement("VERSAO", self::VERSAO);
    $oAutenticacao   = $this->oXml->createElement("AUTENTICACAO", $this->oRegistro->autenticacao);
    $oUsuarioServico = $this->oXml->createElement("USUARIO_SERVICO", $this->oRegistro->usuarioServico);
    $oOperacao       = $this->oXml->createElement("OPERACAO", $this->sOperacao);
    $oSistemaOriem   = $this->oXml->createElement("SISTEMA_ORIGEM", self::SISTEMA_ORIGEM);
    $oDataHora       = $this->oXml->createElement("DATA_HORA", date('YmdHis'));

    $oHeader->appendChild($oVersao);
    $oHeader->appendChild($oAutenticacao);
    $oHeader->appendChild($oUsuarioServico);
    $oHeader->appendChild($oOperacao);
    $oHeader->appendChild($oSistemaOriem);
    $oHeader->appendChild($oDataHora);

    return $oHeader;
  }

  /**
   * Criamos o elemento com os dados do recibo para o xml
   *
   * @return DOMElement
   */
  protected function getDadosXml()
  {
    $oDados        = $this->oXml->createElement("DADOS");
    $oIncluiBoleto = $this->oXml->createElement("INCLUI_BOLETO");

    $oCodigoBeneficiario = $this->oXml->createElement("CODIGO_BENEFICIARIO", $this->oRegistro->codigoBeneficiario);
    $oTitulo             = $this->oXml->createElement("TITULO");

    $oNossoNumero     = $this->oXml->createElement("NOSSO_NUMERO", $this->oRegistro->nossoNumero);
    $oNumeroDocumento = $this->oXml->createElement("NUMERO_DOCUMENTO", $this->oRegistro->numeroDocumento);
    $oDataVencimento  = $this->oXml->createElement("DATA_VENCIMENTO", $this->oRegistro->dataVencimento);
    $oValor           = $this->oXml->createElement("VALOR", $this->oRegistro->valor);
    $oTipoEspecie     = $this->oXml->createElement("TIPO_ESPECIE", $this->oRegistro->tipoEspecie);
    $oFlagAceite      = $this->oXml->createElement("FLAG_ACEITE", $this->oRegistro->flagAceite);
    $oJurosMora       = $this->oXml->createElement("JUROS_MORA");

    $oTipo       = $this->oXml->createElement("TIPO", $this->oRegistro->tipo);
    $oData       = $this->oXml->createElement("DATA", $this->oRegistro->dataVencimento);
    $oValorJuros = $this->oXml->createElement("VALOR", $this->oRegistro->valorJuros);

    $oJurosMora->appendChild($oTipo);
    $oJurosMora->appendChild($oData);
    $oJurosMora->appendChild($oValorJuros);

    $oPosVencimento = $this->oXml->createElement("POS_VENCIMENTO");

    $oAcao       = $this->oXml->createElement("ACAO", $this->oRegistro->acao);
    $oNumeroDias = $this->oXml->createElement("NUMERO_DIAS", $this->oRegistro->numeroDias);

    $oPosVencimento->appendChild($oAcao);
    $oPosVencimento->appendChild($oNumeroDias);

    $oCodigoMoeda = $this->oXml->createElement("CODIGO_MOEDA", $this->oRegistro->codigoMoeda);
    $oPagador     = $this->oXml->createElement("PAGADOR");

    $oCpfCnpj = $this->oXml->createElement("CNPJ", $this->oRegistro->cpfcnpj);
    $oNome    = $this->oXml->createElement("RAZAO_SOCIAL", htmlspecialchars(utf8_encode($this->oRegistro->nome)));

    if ( strlen($this->oRegistro->cpfcnpj) == 11 ) {
      $oCpfCnpj  = $this->oXml->createElement("CPF", $this->oRegistro->cpfcnpj);
      $oNome     = $this->oXml->createElement("NOME", htmlspecialchars(utf8_encode($this->oRegistro->nome)));
    }

    $mensagemRecibo = \DBString::removerAcentuacao(\DBString::removerCaracteresEspeciais($this->oRegistro->mensagemRecibo));
    
    if (!empty($mensagemRecibo)) {
      
      $oFichaCompensacao = $this->oXml->createElement("FICHA_COMPENSACAO");
      $oMensagens = $this->oXml->createElement("MENSAGENS");

      $oMensagem1 = $this->oXml->createElement("MENSAGEM", substr($mensagemRecibo, 0, 40));
      $oMensagens->appendChild($oMensagem1);

      if (strlen($mensagemRecibo) > 40 ) {

        $oMensagem2 = $this->oXml->createElement("MENSAGEM", substr($mensagemRecibo, 40, 40));
        $oMensagens->appendChild($oMensagem2);
      }

      $oFichaCompensacao->appendChild($oMensagens);
    }

    $oPagador->appendChild($oCpfCnpj);
    $oPagador->appendChild($oNome);

    $oTitulo->appendChild($oNossoNumero);
    $oTitulo->appendChild($oNumeroDocumento);
    $oTitulo->appendChild($oDataVencimento);
    $oTitulo->appendChild($oValor);
    $oTitulo->appendChild($oTipoEspecie);
    $oTitulo->appendChild($oFlagAceite);
    $oTitulo->appendChild($oJurosMora);
    $oTitulo->appendChild($oPosVencimento);
    $oTitulo->appendChild($oCodigoMoeda);
    $oTitulo->appendChild($oPagador);

    if (!empty($mensagemRecibo)) {
      $oTitulo->appendChild($oFichaCompensacao);
    }

    $oIncluiBoleto->appendChild($oCodigoBeneficiario);
    $oIncluiBoleto->appendChild($oTitulo);

    $oDados->appendChild($oIncluiBoleto);

    return $oDados;
  }
}

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

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\BaseAbstract;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Registro;

final class CEF extends BaseAbstract
{
  /**
   * Constante do codigo do banco
   */
  const CODIGO_BANCO          = '104';
  const VERSAO_LAYOUT_ARQUIVO = '050';
  const VERSAO_LAYOUT_LOTE    = '030';

  /**
   * Função que cria o Header com os dados despecíficos do banco CEF
   * @return \stdClass
   */
  protected function gerarHeader()
  {
    $oHeader   = new \stdClass();
    $oHeader   = parent::gerarHeader();

    /**
     * Implementação dos dados despecíficos do banco CEF
     */
    $oHeader->codigo_banco          = self::CODIGO_BANCO;
    $oHeader->versao_layout         = self::VERSAO_LAYOUT_ARQUIVO;
    $oHeader->codigo_convenio_banco = str_pad("", 20, "0");
    $oHeader->exclusivo_banco_1     = str_pad($this->oHeader->getConvenio()->cedente, 6, "0", STR_PAD_LEFT);
    $oHeader->exclusivo_banco_1    .= str_pad("", 8, "0");
    $oHeader->uso_reservado_empresa = "REMESSA-PRODUCAO";

    return $oHeader;
  }

  /**
   * Função que cria o Header do Lote com os dados despecíficos do banco CEF
   * @return \stdClass
   */
  protected function gerarHeaderLote()
  {
    $oHeaderLote = parent::gerarHeaderLote();

    /**
     * Implementação dos dados despecíficos do banco CEF
     */
    $oHeaderLote->codigo_banco           = self::CODIGO_BANCO;
    $oHeaderLote->versao_layout          = self::VERSAO_LAYOUT_LOTE;
    $oHeaderLote->exclusivo_febraban_1   = str_pad("", 2, "0");
    $oHeaderLote->codigo_convenio_banco  = str_pad($this->oHeader->getConvenio()->cedente, 6, "0", STR_PAD_LEFT);
    $oHeaderLote->codigo_convenio_banco .= str_pad("", 14, "0");
    $oHeaderLote->exclusivo_banco_1      = str_pad($this->oHeader->getConvenio()->cedente, 6, "0", STR_PAD_LEFT);

    /**
     * Código do Modelo Personalizado
     * Preencher com zeros
     */
    $oHeaderLote->exclusivo_banco_1    .= str_pad("", 8, "0");

    return $oHeaderLote;
  }

  /**
   * Função que cria o Segmento P com os dados despecíficos do banco CEF
   *
   * @param  Registro $oRegistro
   * @return \stdClass
   */
  protected function gerarSegmentoP(Registro $oRegistro)
  {
    $oSegmento = parent::gerarSegmentoP($oRegistro);

    /**
     * Implementação dos dados despecíficos do banco CEF
     */
    $oSegmento->codigo_banco          = self::CODIGO_BANCO;
    $oSegmento->especie_titulo        = "02";
    $oSegmento->codigo_juros          = 3;

    /**
     * Código de Convênio
     */
    $oSegmento->exclusivo_banco_1     = str_pad($this->oHeader->getConvenio()->cedente, 6, "0", STR_PAD_LEFT);
    $oSegmento->exclusivo_banco_1    .= str_pad("", 8, "0");

    /**
     * Modalidade da Carteira
     */
    $oSegmento->exclusivo_banco_2     = str_pad('', 3, "0");
    $oSegmento->exclusivo_banco_2    .= str_pad($oRegistro->getNossoNumero(), 10, "0");
    $oSegmento->exclusivo_banco_2    .= str_pad('', 8, "0");

    $oSegmento->documento_cobranca    = str_pad($oRegistro->getNumeroDocumento(), 11, '0', STR_PAD_LEFT);
    $oSegmento->documento_cobranca   .= str_pad("", 4, ' ');
    $oSegmento->dv_agencia_cobradora  = '0';
    $oSegmento->exclusivo_banco_3     = str_pad("", 10, '0');
    $oSegmento->codigo_desconto       = (string)$oSegmento->codigo_desconto;

    return $oSegmento;
  }

  /**
   * Função que cria o Segmento Q com os dados despecíficos do banco CEF
   *
   * @param  Registro  $oRegistro
   * @return \stdClass
   */
  protected function gerarSegmentoQ(Registro $oRegistro)
  {
    $oSegmento               = parent::gerarSegmentoQ($oRegistro);
    $oSegmento->codigo_banco = self::CODIGO_BANCO;

    return $oSegmento;
  }

  /**
   * Função que cria o Trailer do Lote com os dados despecíficos do banco CEF
   * @return \stdClass
   */
  protected function gerarTrailerLote()
  {
    $oTrailerLote                        = parent::gerarTrailerLote();
    $oTrailerLote->codigo_banco          = self::CODIGO_BANCO;
    $oTrailerLote->exclusivo_febraban_2  = str_pad('', 69, '0');

    return $oTrailerLote;
  }

  /**
   * Função que cria o Trailer do Arquivo com os dados despecíficos do banco CEF
   * @return \stdClass
   */
  protected function gerarTrailer()
  {
    $oTrailer               = parent::gerarTrailer();
    $oTrailer->codigo_banco = self::CODIGO_BANCO;

    return $oTrailer;
  }
}

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
use \ECidade\Tributario\NumeroControle\Calculo;

final class Banrisul extends BaseAbstract
{

  /**
   * Constante do codigo do banco
   */
    const CODIGO_BANCO          = '041';
    const VERSAO_LAYOUT_ARQUIVO = '040';
    const VERSAO_LAYOUT_LOTE    = '020';

    /**
     * Função que cria o Header com os dados despecíficos do banco Banrisul
     * @return \stdClass
     */
    protected function gerarHeader()
    {
        $oHeader                         = parent::gerarHeader();
        $oHeader->codigo_banco           = self::CODIGO_BANCO;
        $oHeader->versao_layout          = self::VERSAO_LAYOUT_ARQUIVO;

        $sBeneficiarioAgencia = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 4, '0', STR_PAD_LEFT);
        $sBeneficiarioConta   = str_pad($this->oHeader->getConvenio()->cedente, 7, '0');

        /**
         * Geramos o Código do Beneficiário
         * Que consiste em 4 dígitos do código da agência + 7 dígitos do cedente +
         * 2 dígitos do NC (número de controle calculados a partir dos módulos 10 e 11)
         */
        $oNumeroControle = new Calculo();
        $oNumeroControle->setNumeracao($sBeneficiarioAgencia . $sBeneficiarioConta);
        $oNumeroControle->calcular();

        $sCodigoBeneficiario = $oNumeroControle->getNumeracao() . $oNumeroControle->getNumeracaoCalculada();

        $oHeader->codigo_convenio_banco  = str_pad($sCodigoBeneficiario, 13, "0", STR_PAD_LEFT);
        $oHeader->codigo_convenio_banco .= str_pad("", 7, "0");
        $oHeader->codigo_agencia         = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 5, "0", STR_PAD_LEFT);
        $oHeader->dv_agencia             = str_pad($this->oHeader->getContaBancaria()->getDVAgencia(), 1, "0", STR_PAD_LEFT);

        $oHeader->exclusivo_banco_1      = str_pad($this->oHeader->getContaBancaria()->getNumeroConta(), 12, "0", STR_PAD_LEFT);
        $oHeader->exclusivo_banco_1     .= str_pad($this->oHeader->getContaBancaria()->getDVConta(), 1, "0", STR_PAD_LEFT);
        $oHeader->exclusivo_banco_1     .= " ";

        return $oHeader;
    }

    /**
     * Função que cria o Header do Lote com os dados do banco Banrisul
     * @return \stdClass   Header do lote
     */
    protected function gerarHeaderLote()
    {
        $oHeaderLote                         = parent::gerarHeaderLote();
        $oHeaderLote->codigo_banco           = self::CODIGO_BANCO;
        $oHeaderLote->exclusivo_febraban_1   = '00';
        $oHeaderLote->versao_layout          = self::VERSAO_LAYOUT_LOTE;

        $sBeneficiarioAgencia = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 4, '0', STR_PAD_LEFT);
        $sBeneficiarioConta   = str_pad($this->oHeader->getConvenio()->cedente, 7, '0');

        /**
         * Geramos o Código do Beneficiário
         * Que consiste em 4 dígitos do código da agência + 7 dígitos da conta +
         * 2 dígitos do NC (número de controle calculados a partir dos módulos 10 e 11)
         */
        $oNumeroControle = new Calculo();
        $oNumeroControle->setNumeracao($sBeneficiarioAgencia . $sBeneficiarioConta);
        $oNumeroControle->calcular();

        $sCodigoBeneficiario = $oNumeroControle->getNumeracao() . $oNumeroControle->getNumeracaoCalculada();

        $oHeaderLote->codigo_convenio_banco  = str_pad($sCodigoBeneficiario, 13, "0", STR_PAD_LEFT);
        $oHeaderLote->codigo_convenio_banco .= str_pad("", 7, "0");

        $oHeaderLote->codigo_agencia         = str_pad($this->oHeader->getContaBancaria()->getNumeroAgencia(), 5, "0", STR_PAD_LEFT);
        $oHeaderLote->dv_agencia             = str_pad($this->oHeader->getContaBancaria()->getDVAgencia(), 1, "0", STR_PAD_LEFT);

        $oHeaderLote->exclusivo_banco_1      = str_pad($this->oHeader->getContaBancaria()->getNumeroConta(), 12, "0", STR_PAD_LEFT);
        $oHeaderLote->exclusivo_banco_1     .= str_pad($this->oHeader->getContaBancaria()->getDVConta(), 1, "0", STR_PAD_LEFT);
        $oHeaderLote->exclusivo_banco_1     .= " ";

        return $oHeaderLote;
    }

    /**
     * Função que cria o Segmento P com os dados despecíficos do banco Banrisul
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

        $oSegmento->exclusivo_banco_1     = str_pad($this->oHeader->getContaBancaria()->getNumeroConta(), 12, "0", STR_PAD_LEFT);
        $oSegmento->exclusivo_banco_1    .= str_pad($this->oHeader->getContaBancaria()->getDVConta(), 1, "0");
        $oSegmento->exclusivo_banco_1    .= str_pad($this->oHeader->getContaBancaria()->getDVConta(), 1, "0");

        $oSegmento->exclusivo_banco_2     = str_pad($oRegistro->getNossoNumero(), 10, "0", STR_PAD_LEFT);
        $oSegmento->exclusivo_banco_2    .= str_pad("", 10, "0");
        $oSegmento->dv_agencia_cobradora  = '0';
        $oSegmento->especie_titulo        = 'AA';
        $oSegmento->exclusivo_banco_3     = str_pad("", 10, '0');

        // Quando Barisul, deve ir sem informacao
        $oSegmento->codigo_juros          = ' ';
        $oSegmento->data_juros            = str_pad("", 8, ' ');

        return $oSegmento;
    }

    /**
     * Função que cria o Segmento Q com os dados despecíficos do banco Banrisul
     *
     * @param  Registro  $oRegistro
     * @return \stdClass
     */
    protected function gerarSegmentoQ(Registro $oRegistro)
    {
        $oSegmento               = parent::gerarSegmentoQ($oRegistro);
        $oSegmento->codigo_banco = self::CODIGO_BANCO;
        $oSegmento->nosso_numero = str_pad($oRegistro->getNossoNumero(), 20);

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
        $oTrailerLote->exclusivo_febraban_2  = str_pad('', 84, '0');
        $oTrailerLote->exclusivo_febraban_2 .= str_pad('', 125, ' ');

        return $oTrailerLote;
    }

    /**
     * Função que cria o Trailer do Arquivo com os dados despecíficos do banco Banrisul
     * @return \stdClass
     */
    protected function gerarTrailer()
    {
        $oTrailer               = parent::gerarTrailer();
        $oTrailer->codigo_banco = self::CODIGO_BANCO;

        return $oTrailer;
    }
}

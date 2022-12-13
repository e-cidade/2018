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

class BB extends BaseAbstract
{
  /**
   * Constante do codigo do banco
   */
  const CODIGO_BANCO = '001';
  const VERSAO_LAYOUT_ARQUIVO = '083';

  protected function gerarHeader()
  {
    $oHeader = parent::gerarHeader();
    $oHeader->codigo_banco = self::CODIGO_BANCO;
    $oHeader->codigo_convenio_banco = $this->gerarCodigoConvenioBanco();
    // Passa o digito para maiusculo caso seja um caracter alfabetico
    $oHeader->dv_agencia = (ctype_alpha($oHeader->dv_agencia) ? strtoupper($oHeader->dv_agencia) : $oHeader->dv_agencia);
    $oHeader->exclusivo_banco_1 = $this->gerarExclusivoBancoHeader();
    $oHeader->versao_layout = self::VERSAO_LAYOUT_ARQUIVO;

    return $oHeader;
  }

  protected function gerarHeaderLote()
  {
    $oHeaderLote = parent::gerarHeaderLote();

    $oHeaderLote->codigo_banco = self::CODIGO_BANCO;
    $oHeaderLote->versao_layout = '042';
    $oHeaderLote->codigo_convenio_banco = $this->gerarCodigoConvenioBanco();
    $oHeaderLote->dv_agencia = (ctype_alpha($oHeaderLote->dv_agencia) ? strtoupper($oHeaderLote->dv_agencia) : $oHeaderLote->dv_agencia);
    $oHeaderLote->exclusivo_banco_1 = $this->gerarExclusivoBancoHeader();
    // $oHeaderLote->mensagem1 = str_pad('', 40, ' ');
    // $oHeaderLote->mensagem2 = str_pad('', 40, ' ');
    // $oHeaderLote->data_credito = str_pad('', 8, ' ');

    return $oHeaderLote;
  }

  protected function gerarSegmentoP(Registro $oRegistro)
  {
    $oSegmentoP = parent::gerarSegmentoP($oRegistro);

    $oSegmentoP->codigo_banco      = self::CODIGO_BANCO;
    $oSegmentoP->dv_agencia        = (ctype_alpha($oSegmentoP->dv_agencia) ? strtoupper($oSegmentoP->dv_agencia) : $oSegmentoP->dv_agencia);
    $oSegmentoP->exclusivo_banco_1 = $this->gerarExclusivoBancoHeader();

    $oSegmentoP->exclusivo_banco_2 = str_pad($oRegistro->getNossoNumero(), 20, ' ', STR_PAD_RIGHT);
    $oSegmentoP->codigo_carteira   = '7';
    $oSegmentoP->data_juros        = '00000000';
    $oSegmentoP->codigo_protesto   = '3';
    $oSegmentoP->prazo_protesto    = '00';
    $oSegmentoP->codigo_baixa_devolucao = '0';
    $oSegmentoP->prazo_baixa_devolucao  = '000';
    $oSegmentoP->codigo_moeda = '09';
    $oSegmentoP->exclusivo_banco_3 = str_pad('', 10, '0');

    return $oSegmentoP;
  }

  protected function gerarSegmentoQ(Registro $oRegistro)
  {
    $oSegmentoQ = parent::gerarSegmentoQ($oRegistro);
    $oSegmentoQ->codigo_banco        = self::CODIGO_BANCO;

    return $oSegmentoQ;
  }

  protected function gerarTrailerLote()
  {
    $oTrailerLote = parent::gerarTrailerLote();
    $oTrailerLote->codigo_banco = self::CODIGO_BANCO;

    return $oTrailerLote;
  }

  protected function gerarTrailer()
  {
    $oTrailer = parent::gerarTrailer();
    $oTrailer->codigo_banco = self::CODIGO_BANCO;

    return $oTrailer;
  }

  /**
   * Gera o campo Codigo do Convenio no Banco
   * @return string $sCodigoConvenioBanco
   */
  private function gerarCodigoConvenioBanco()
  {
    $sCodigoConvenioBanco= '';
    $oConvenio = $this->oHeader->getConvenio();

    /* Monta parte do numero do convenio de cobranca com 9 digitos completados a esquerda com 0 */
    $sCodigoConvenioBanco .= str_pad($oConvenio->numero_convenio, 9, '0', STR_PAD_LEFT);
    /* Monta parte cobranca cedente */
    $sCodigoConvenioBanco .= '0014'; //str_pad($oConvenio->cedente, 4, '0', STR_PAD_LEFT);
    /* Monta carteira de cobranca */
    $sCodigoConvenioBanco .= $oConvenio->carteira;
    /* Monta variacao da carteira de cobranca */
    $sCodigoConvenioBanco .= str_pad($oConvenio->variacao, 3, '0', STR_PAD_LEFT);
    /* Identificao de remessa de testes 2 digitos preencher com espacos ou TS para testes */
    $sCodigoConvenioBanco .= '  ';

    return $sCodigoConvenioBanco;
  }

  /**
   * Monta campo exclusivo_banco_1 do  Header BB 59 a 72
   * @return string $sExclusivoBancoHeader
   */
  private function gerarExclusivoBancoHeader()
  {
    /*
     * Adiciona a conta corrente completando com 0 ate 12 caracteres
     */
    $sExclusivoBancoHeader  = str_pad($this->oHeader->getContaBancaria()->getNumeroConta(), 12, '0', STR_PAD_LEFT);

    /* Adiciona o digito verificador da conta, tratando se for uma letra */
    if (ctype_alpha($this->oHeader->getContaBancaria()->getDVConta())) {
      $sExclusivoBancoHeader .= strtoupper($this->oHeader->getContaBancaria()->getDVConta());
    } else {
      $sExclusivoBancoHeader .= $this->oHeader->getContaBancaria()->getDVConta();
    }

    /* Adiciona digito verificador da conta/agecia, em branco ou 0*/
     $sExclusivoBancoHeader .=  ' ';

    return $sExclusivoBancoHeader;
  }
}
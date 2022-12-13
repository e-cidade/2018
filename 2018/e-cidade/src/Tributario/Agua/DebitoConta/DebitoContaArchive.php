<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Agua\DebitoConta;

final class DebitoContaArchive
{
  private $sNome;

  private $oFile;

  private $sHeader;

  private $sLinha;

  private $sFooter;

  public function open($iBanco, $iUltimoNsa)
  {
    $this->sNome = "tmp/debconta_".str_pad($iBanco, 3, "0", STR_PAD_LEFT)."_nsa_".str_pad($iUltimoNsa, 10, "0", STR_PAD_LEFT)."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
    $this->oFile = fopen($this->sNome, 'w+');
  }

  public function header($iConvenio, $sNomeInst, $iBanco, $sBancoDesc, $iUltimaNsa)
  {
    $sHeader  = "A";
    $sHeader .= "1";
    $sHeader .= str_pad(substr($iConvenio, 0, 20), 20, " ", STR_PAD_RIGHT);
    $sHeader .= str_pad(substr($sNomeInst, 0, 20), 20, " ", STR_PAD_RIGHT);
    $sHeader .= str_pad($iBanco, 3, "0", STR_PAD_LEFT);
    $sHeader .= str_pad(substr($sBancoDesc, 0, 20), 20);
    $sHeader .= date("Ymd", db_getsession("DB_datausu"));
    $sHeader .= str_pad($iUltimaNsa, 6, "0", STR_PAD_LEFT);
    $sHeader .= "04";
    $sHeader .= "DEBITO AUTOMATICO";
    $sHeader .= str_repeat(" ", 52);
    $sHeader .= "\n";

    $this->sHeader = $sHeader;
  }

  public function linha(
    $sBancoIdempresa,
    $sBancoAgencia,
    $sBancoConta,
    $oDebitoDataVencimento,
    $nDebitoValor,
    $nDebitoNumpre,
    $nDebitoNumpar,
    $iNumeroContrato,
    $iCodigoPedido
  ) {

    $sLinha  = "E";
    $sLinha .= str_pad(trim($sBancoIdempresa), 25, " ", STR_PAD_RIGHT);
    $sLinha .= str_pad(trim($sBancoAgencia), 4, "0", STR_PAD_LEFT);
    $sLinha .= str_pad(trim($sBancoConta),  14, " ", STR_PAD_RIGHT);
    $sLinha .= $oDebitoDataVencimento->getDate("Ymd");
    $sLinha .= str_pad(trim(db_formatar($nDebitoValor, 'valsemform', '0', 15)), 15, "0", STR_PAD_LEFT);
    $sLinha .= "03";
    $sLinha .= str_pad("001-" . str_pad($nDebitoNumpre, 8, "0", STR_PAD_LEFT) . "-" . str_pad($nDebitoNumpar, 3, "0", STR_PAD_LEFT) . "-" . str_pad($iNumeroContrato, 10, "0", STR_PAD_LEFT) . "-" . str_pad($iCodigoPedido, 10, "0", STR_PAD_LEFT), 60, " ", STR_PAD_RIGHT);
    $sLinha .= str_repeat(" ", 20);
    $sLinha .= "0";
    $sLinha .= "\n";

    $this->sLinha .= $sLinha;
  }

  public function footer($iTotal, $iTotalValor)
  {
    $sFooter  = "Z";
    $sFooter .= str_pad($iTotal + 2, 6, "0", STR_PAD_LEFT);
    $sFooter .= str_pad(trim(db_formatar($iTotalValor, 'valsemform', '0', 17)), 17, "0", STR_PAD_LEFT);
    $sFooter .= str_repeat(" ", 126);

    $this->sFooter = $sFooter;
  }

  public function close()
  {
    fputs($this->oFile, $this->getLinhas());
    fclose($this->oFile);
  }

  public function getLinhas()
  {
    return $this->sHeader.$this->sLinha.$this->sFooter;
  }

  public function getNome()
  {
    return $this->sNome;
  }
}

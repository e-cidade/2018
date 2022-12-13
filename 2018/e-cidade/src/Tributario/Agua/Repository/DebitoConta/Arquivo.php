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

namespace ECidade\Tributario\Agua\Repository\DebitoConta;

use \cl_debcontaarquivo as DebitoContaArquivo;
use \cl_debcontaarquivotipo as DebitoContaArquivoTipo;
use \cl_debcontaarquivoreg as DebitoContaArquivoReg;
use \cl_debcontaarquivoregmov as DebitoContaArquivoRegMov;
use \cl_debcontaarquivoregcad as DebitoContaArquivoRegCad;
use \cl_debcontaarquivoregped as DebitoContaArquivoRegPed;
use \Exception;

final class Arquivo
{
  private $oDebitoContaArquivo;

  private $oDebitoContaArquivoTipo;

  private $oDebitoContaArquivoReg;

  private $oDebitoContaArquivoRegMov;

  private $oDebitoContaArquivoRegCad;

  private $oDebitoContaArquivoRegPed;

  public function __construct(
    DebitoContaArquivo $oDebitoContaArquivo,
    DebitoContaArquivoTipo $oDebitoContaArquivoTipo,
    DebitoContaArquivoReg $oDebitoContaArquivoReg,
    DebitoContaArquivoRegMov $oDebitoContaArquivoRegMov,
    DebitoContaArquivoRegCad $oDebitoContaArquivoRegCad,
    DebitoContaArquivoRegPed $oDebitoContaArquivoRegPed
  ) {
    $this->oDebitoContaArquivo = $oDebitoContaArquivo;
    $this->oDebitoContaArquivoTipo = $oDebitoContaArquivoTipo;
    $this->oDebitoContaArquivoReg = $oDebitoContaArquivoReg;
    $this->oDebitoContaArquivoRegMov = $oDebitoContaArquivoRegMov;
    $this->oDebitoContaArquivoRegCad = $oDebitoContaArquivoRegCad;
    $this->oDebitoContaArquivoRegPed = $oDebitoContaArquivoRegPed;
  }

  public function insertDebitoContaArquivo($iUltimoNSA, $sNomeArquivo, $iTipoDebito, $iMes, $iBanco)
  {
    $this->oDebitoContaArquivo->d72_codigo = null;
    $this->oDebitoContaArquivo->d72_nsa = $iUltimoNSA;
    $this->oDebitoContaArquivo->d72_tipo = 1;
    $this->oDebitoContaArquivo->d72_data = date("Y-m-d", db_getsession("DB_datausu"));
    $this->oDebitoContaArquivo->d72_hora = db_hora();
    $this->oDebitoContaArquivo->d72_usuario = db_getsession("DB_id_usuario");
    $this->oDebitoContaArquivo->d72_nome = $sNomeArquivo;
    $this->oDebitoContaArquivo->d72_conteudo = " ";
    $this->oDebitoContaArquivo->d72_numpar = $iMes;
    $this->oDebitoContaArquivo->d72_arretipo = $iTipoDebito;
    $this->oDebitoContaArquivo->d72_banco = $iBanco;
    $this->oDebitoContaArquivo->d72_instit = db_getsession("DB_instit");

    $this->oDebitoContaArquivo->incluir(null);

    if ($this->oDebitoContaArquivo->erro_banco) {
      throw new Exception($this->oDebitoContaArquivo->erro_msg);
    }

    return $this->oDebitoContaArquivo->d72_codigo;
  }

  public function verificaDebitoContaTipo($iCodigoDebitoContaArquivo, $iTipoDebito)
  {
    $sSqlDebitoContaArquivoTipo = $this->oDebitoContaArquivoTipo->sql_query_file(null, "*", null, "d79_codigo = $iCodigoDebitoContaArquivo and d79_arretipo = $iTipoDebito");

    $this->oDebitoContaArquivoTipo->sql_record($sSqlDebitoContaArquivoTipo);

    if ($this->oDebitoContaArquivoTipo->erro_banco) {
      throw new Exception($this->oDebitoContaArquivoTipo->erro_msg);
    }

    if ($this->oDebitoContaArquivoTipo->numrows == '0') {

      $this->oDebitoContaArquivoTipo->d79_sequencial = null;
      $this->oDebitoContaArquivoTipo->d79_codigo = $iCodigoDebitoContaArquivo;
      $this->oDebitoContaArquivoTipo->d79_arretipo = $iTipoDebito;
      $this->oDebitoContaArquivoTipo->incluir(null);

      if ($this->oDebitoContaArquivoTipo->erro_banco) {
        throw new Exception($this->oDebitoContaArquivoTipo->erro_msg);
      }
    }
  }

  public function gravaArquivoReg(
    $iCodigoDebitoContaArquivo,
    $oDebitoDataVencimento,
    $nDebitoValor,
    $nDebitoNumpar,
    $iCodigoPedido
  ) {
    $this->oDebitoContaArquivoReg->d73_sequencial = null;
    $this->oDebitoContaArquivoReg->d73_codigo = $iCodigoDebitoContaArquivo;
    $this->oDebitoContaArquivoReg->d73_tipo = 1;
    $this->oDebitoContaArquivoReg->incluir(null);

    $iCodigoDebitoContaArquivoReg = $this->oDebitoContaArquivoReg->d73_sequencial;

    if ($this->oDebitoContaArquivoReg->erro_banco) {
      throw new Exception($this->oDebitoContaArquivoReg->erro_msg);
    }

    $this->oDebitoContaArquivoRegMov->d75_sequencial = null;
    $this->oDebitoContaArquivoRegMov->d75_codigo = $iCodigoDebitoContaArquivoReg;
    $this->oDebitoContaArquivoRegMov->d75_venc = $oDebitoDataVencimento->getDate("Y-m-d");;
    $this->oDebitoContaArquivoRegMov->d75_valor = $nDebitoValor;
    $this->oDebitoContaArquivoRegMov->d75_numpar = $nDebitoNumpar;
    $this->oDebitoContaArquivoRegMov->incluir(null);

    if ($this->oDebitoContaArquivoRegMov->erro_banco) {
      throw new Exception($this->oDebitoContaArquivoRegMov->erro_msg);
    }

    $this->oDebitoContaArquivoRegCad->d74_sequencial = null;
    $this->oDebitoContaArquivoRegCad->d74_codigo = $iCodigoDebitoContaArquivoReg;
    $this->oDebitoContaArquivoRegCad->d74_tipomov = 1;
    $this->oDebitoContaArquivoRegCad->d74_data = date("Y-m-d", db_getsession("DB_datausu"));
    $this->oDebitoContaArquivoRegCad->incluir(null);

    if ($this->oDebitoContaArquivoRegCad->erro_banco) {
      throw new Exception($this->oDebitoContaArquivoRegCad->erro_msg);
    }

    $this->oDebitoContaArquivoRegPed->d80_sequencial = null;
    $this->oDebitoContaArquivoRegPed->d80_arquivoreg = $iCodigoDebitoContaArquivoReg;
    $this->oDebitoContaArquivoRegPed->d80_pedido = $iCodigoPedido;
    $this->oDebitoContaArquivoRegPed->incluir(null);

    if ($this->oDebitoContaArquivoRegPed->erro_banco) {
      throw new Exception($this->oDebitoContaArquivoRegPed->erro_msg);
    }
  }

  public function atualizaConteudoDebitoContaArquivo($iCodigoDebitoContaArquivo, $sConteudo)
  {
    $sSql  = " update debcontaarquivo                         ";
    $sSql .= "    set d72_conteudo = '$sConteudo'             ";
    $sSql .= "  where d72_codigo = $iCodigoDebitoContaArquivo ";

    $this->oDebitoContaArquivo->sql_record($sSql);

    if ($this->oDebitoContaArquivo->erro_banco) {
      throw new Exception($this->oDebitoContaArquivo->erro_msg);
    }
  }
}

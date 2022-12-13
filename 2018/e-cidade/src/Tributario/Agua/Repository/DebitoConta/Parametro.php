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

use \cl_debcontaparam as DebitoContaParametro;
use \cl_db_config as DBConfig;
use \db_utils;
use \Exception;

final class Parametro
{
  private $oDebitoContaParametro;

  private $oDBConfig;

  public function __construct(DebitoContaParametro $oDebitoContaParametro, DBConfig $oDBConfig)
  {
    $this->oDebitoContaParametro = $oDebitoContaParametro;
    $this->oDBConfig = $oDBConfig;
  }

  public function getParametroConfig($iInstit)
  {
    $sSql = $this->oDBConfig->sql_query(null, "nomeinst, nomedebconta", null, "codigo = $iInstit");

    $rsDBConfig = $this->oDBConfig->sql_record($sSql);

    if ($this->oDBConfig->erro_banco) {
      throw new Exception($this->oDBConfig->erro_msg);
    }

    return db_utils::fieldsMemory($rsDBConfig, 0);
  }

  public function getParametroBanco($iInstit, $iBanco)
  {
    $sSql  = " select *                                                                 ";
    $sSql .= "   from debcontaparam                                                     ";
    $sSql .= "        inner join db_bancos on to_number(db90_codban, '999') = d62_banco ";
    $sSql .= "  where d62_instituicao = $iInstit                                        ";
    $sSql .= "    and d62_banco = $iBanco                                               ";

    $rsDebitoContaParametro = $this->oDebitoContaParametro->sql_record($sSql);

    if ($this->oDebitoContaParametro->erro_banco) {
      throw new Exception($this->oDebitoContaParametro->erro_msg);
    }

    return db_utils::fieldsMemory($rsDebitoContaParametro, 0);
  }

  public function aumentaUltimoNSA($iInstit, $iBanco)
  {
    $sSql  = " update debcontaparam                     ";
    $sSql .= "    set d62_ultimonsa = d62_ultimonsa + 1 ";
    $sSql .= "  where d62_instituicao = $iInstit        ";
    $sSql .= "    and d62_banco = $iBanco               ";

    $rsDebitoContaParametro = $this->oDebitoContaParametro->sql_record($sSql);

    if ($this->oDebitoContaParametro->erro_banco) {
      throw new Exception($this->oDebitoContaParametro->erro_msg);
    }
  }
}

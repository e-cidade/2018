<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

class ZerarParcelaISSQNVariavel
{

  /**
   * @var integer
   */
  private $iNumpre;

  /**
   * @var integer
   */
  private $iParcela;

  public function setNumpre($iNumpre)
  {
    $this->iNumpre = $iNumpre;
  }

  public function setParcela($iParcela)
  {
    $this->iParcela = $iParcela;
  }

  public function zerarParcela()
  {
    $oDaoIssvar = new cl_issvar();
    $sSqlIssvar = $oDaoIssvar->sql_query_file(null, "*", null, "q05_numpre = {$this->iNumpre} and q05_numpar = {$this->iParcela}");
    $rsIssvar = $oDaoIssvar->sql_record($sSqlIssvar);

    if ($oDaoIssvar->numrows == 0) {
      throw new Exception("Débito não encontrado.");
    }

    $oDaoIssvar->q05_codigo = db_utils::fieldsMemory($rsIssvar, 0)->q05_codigo;
    $oDaoIssvar->q05_valor = "0";
    $oDaoIssvar->q05_vlrinf = "0";

    $oDaoIssvar->alterar($oDaoIssvar->q05_codigo);

    if ($oDaoIssvar->erro_status == 0) {
      throw new Exception("Erro ao alterar valores.");
    }

    $oDaoArrecad = new cl_arrecad();
    $oDaoArrecad->k00_valor = "0";

    $oDaoArrecad->alterar(null, "k00_numpre = {$this->iNumpre} and k00_numpar = {$this->iParcela}");

    if ($oDaoArrecad->erro_status == 0) {
      throw new Exception("Erro ao alterar valores.");
    }

    return true;
  }
}

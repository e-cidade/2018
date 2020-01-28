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

namespace ECidade\Patrimonial\Protocolo\UF;

use ECidade\Patrimonial\Protocolo\UF\UF;

class Repository
{

  /**
   * @var \cl_db_uf
   */
  private $oDaoUF;

  public function __construct()
  {
    $this->oDaoUF = new \cl_db_uf;
  }

  /**
   * @param integer $iCodigo
   * @return UF
   */
  public function get($iCodigo)
  {

    $sSqlUf = $this->oDaoUF->sql_query_file($iCodigo);
    $rsUf = $this->oDaoUF->sql_record($sSqlUf);

    if (!$rsUf || $this->oDaoUF->numrows == 0) {
      throw new Exception("Erro ao buscar UF.");
    }

    $oDados = \db_utils::fieldsMemory($rsUf, 0);

    $oUf = new UF();
    $oUf->setCodigo($oDados->db12_codigo);
    $oUf->setUF($oDados->db12_uf);
    $oUf->setNome($oDados->db12_nome);
    $oUf->setNomeExtenso($oDados->db12_extenso);

    return $oUf;
  }

  /**
   * @param string $sUf
   * @return UF
   */
  public function getBySigla($sUf)
  {

    $sSqlUf = $this->oDaoUF->sql_query_file(null, "*", null, "db12_uf = '{$sUf}'");
    $rsUf = $this->oDaoUF->sql_record($sSqlUf);

    if (!$rsUf || $this->oDaoUF->numrows == 0) {
      throw new Exception("Erro ao buscar UF.");
    }

    $oDados = \db_utils::fieldsMemory($rsUf, 0);

    $oUf = new UF();
    $oUf->setCodigo($oDados->db12_codigo);
    $oUf->setUF($oDados->db12_uf);
    $oUf->setNome($oDados->db12_nome);
    $oUf->setNomeExtenso($oDados->db12_extenso);

    return $oUf;
  }
}

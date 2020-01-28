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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa;

use \cl_remessacobrancaregistrada as RemessaCobrancaRegistradaDAO;
use \cl_conveniocobranca as ConvenioCobrancaDAO;
use \Exception;
use \db_utils;

class RemessaRepository
{
  private $oRemessaCobrancaRegistradaDAO;

  private $oConvenioCobrancaDAO;

  public function __construct(RemessaCobrancaRegistradaDAO $oRemessaCobrancaRegistradaDAO, ConvenioCobrancaDAO $oConvenioCobrancaDAO)
  {
    $this->oRemessaCobrancaRegistradaDAO = $oRemessaCobrancaRegistradaDAO;
    $this->oConvenioCobrancaDAO = $oConvenioCobrancaDAO;
  }

  public function getDaoRemessaCobrancaRegistrada()
  {
    return $this->oRemessaCobrancaRegistradaDAO;
  }

  public function getDaoConvenioCobranca()
  {
    return $this->oConvenioCobrancaDAO;
  }

  public function createTempRemessaConvenio($sTempTable, $iConvenio)
  {
    return $this->createTemp($sTempTable, "reciboregistra", "reciboregistra.k146_numpre", "reciboregistra.k146_convenio = $iConvenio");
  }

  public function createTempRemessaGerada($sTempTable, $iRemessa)
  {
    return $this->createTemp($sTempTable, "remessacobrancaregistradarecibo", "remessacobrancaregistradarecibo.k148_numpre", "remessacobrancaregistradarecibo.k148_remessacobrancaregistrada = $iRemessa");
  }

  private function createTemp($sTempTable, $sFrom, $sJoin, $sWhere)
  {
    $sSql  = " select recibopaga.k00_numnov,                                                               ";
    $sSql .= "        sum(recibopaga.k00_valor) as valor_total,                                            ";
    $sSql .= "        recibopaga.k00_dtpaga,                                                               ";
    $sSql .= "        recibopagaboleto.k138_data,                                                          ";
    $sSql .= "        arrebanco.k00_numbco as nosso_numero,                                                ";
    $sSql .= "        ' '::char as regra,                                                                  ";
    $sSql .= "        (select array_agg(arrematric.k00_matric)                                             ";
    $sSql .= "           from arrematric                                                                   ";
    $sSql .= "          where arrematric.k00_numpre = any(array_agg(recibopaga.k00_numpre))) as matricula, ";
    $sSql .= "        (select array_agg(arreinscr.k00_inscr)                                               ";
    $sSql .= "           from arreinscr                                                                    ";
    $sSql .= "          where arreinscr.k00_numpre = any(array_agg(recibopaga.k00_numpre))) as inscricao,  ";
    $sSql .= "        (select array_agg(arrenumcgm.k00_numcgm)                                             ";
    $sSql .= "           from arrenumcgm                                                                   ";
    $sSql .= "          where arrenumcgm.k00_numpre = any(array_agg(recibopaga.k00_numpre))) as cgm,       ";
    $sSql .= "        array[1] as sacado                                                                   ";
    $sSql .= "   from $sFrom                                                                               ";
    $sSql .= "        inner join recibopaga ON recibopaga.k00_numnov = $sJoin                              ";
    $sSql .= "        inner join recibopagaboleto ON recibopaga.k00_numnov = recibopagaboleto.k138_numnov  ";
    $sSql .= "                                   AND recibopagaboleto.k138_data > '2016-01-01'             ";
    $sSql .= "        left join arrebanco ON arrebanco.k00_numpre = recibopaga.k00_numnov                  ";
    $sSql .= "  where $sWhere                                                                              ";
    $sSql .= "  group by recibopaga.k00_numcgm,                                                            ";
    $sSql .= "           recibopaga.k00_dtpaga,                                                            ";
    $sSql .= "           recibopaga.k00_numnov,                                                            ";
    $sSql .= "           recibopagaboleto.k138_data,                                                       ";
    $sSql .= "           arrebanco.k00_numbco                                                              ";

    $sSql .= "  union all                                                                                  ";

    $sSql .= " select recibo.k00_numpre as k00_numnov,                                                     ";
    $sSql .= "        sum(recibo.k00_valor) as valor_total,                                                ";
    $sSql .= "        recibo.k00_dtvenc as k00_dtpaga,                                                     ";
    $sSql .= "        recibo.k00_dtoper as k138_data,                                                      ";
    $sSql .= "        arrebanco.k00_numbco as nosso_numero,                                                ";
    $sSql .= "        ' '::char as regra,                                                                  ";
    $sSql .= "        (select array_agg(arrematric.k00_matric)                                             ";
    $sSql .= "           from arrematric                                                                   ";
    $sSql .= "          where arrematric.k00_numpre = any(array_agg(recibo.k00_numpre))) as matricula,     ";
    $sSql .= "        (select array_agg(arreinscr.k00_inscr)                                               ";
    $sSql .= "           from arreinscr                                                                    ";
    $sSql .= "          where arreinscr.k00_numpre = any(array_agg(recibo.k00_numpre))) as inscricao,      ";
    $sSql .= "        (select array_agg(arrenumcgm.k00_numcgm)                                             ";
    $sSql .= "           from arrenumcgm                                                                   ";
    $sSql .= "          where arrenumcgm.k00_numpre = any(array_agg(recibo.k00_numpre))) as cgm,           ";
    $sSql .= "        array[1] as sacado                                                                   ";
    $sSql .= "   from $sFrom                                                                               ";
    $sSql .= "        inner join recibo on recibo.k00_numpre = $sJoin                                      ";
    $sSql .= "        inner join arrebanco on arrebanco.k00_numpre = $sJoin                                ";
    $sSql .= "  where $sWhere                                                                              ";
    $sSql .= "  group by recibo.k00_numpre,                                                                ";
    $sSql .= "           recibo.k00_dtvenc,                                                                ";
    $sSql .= "           recibo.k00_dtoper,                                                                ";
    $sSql .= "           arrebanco.k00_numbco                                                              ";

    $sSql = "create temp table $sTempTable as $sSql";

    $rsRemessaCobrancaRegistradaDAO = $this->oRemessaCobrancaRegistradaDAO->sql_record($sSql);

    if ($this->oRemessaCobrancaRegistradaDAO->erro_banco) {
      throw new Exception($this->oRemessaCobrancaRegistradaDAO->erro_msg);
    }

    return $rsRemessaCobrancaRegistradaDAO;
  }

  public function getCodigoConvenio($iRemessa)
  {
    $sSql = $this->oRemessaCobrancaRegistradaDAO->sql_query_file($iRemessa);
    $rsRemessaCobrancaRegistradaDAO = $this->oRemessaCobrancaRegistradaDAO->sql_record($sSql);

    if ($this->oRemessaCobrancaRegistradaDAO->erro_banco) {
      throw new Exception("Erro ao buscar os dados da remessa.");
    }

    $oRemessa = db_utils::fieldsMemory($rsRemessaCobrancaRegistradaDAO, 0);

    return $oRemessa->k147_convenio;
  }

  public function getCodigoBanco($iConvenio)
  {
    $sSql = $this->oConvenioCobrancaDAO->sql_query(null, "db90_codban", null, "ar13_cadconvenio = $iConvenio");
    $rsConvenioCobrancaDAO = $this->oConvenioCobrancaDAO->sql_record($sSql);

    if ($this->oConvenioCobrancaDAO->erro_banco) {
      throw new Exception("Erro ao buscar as informações do convênio.");
    }

    $oConvenioCobranca = db_utils::fieldsMemory($rsConvenioCobrancaDAO, 0);

    return $oConvenioCobranca->db90_codban;
  }
}

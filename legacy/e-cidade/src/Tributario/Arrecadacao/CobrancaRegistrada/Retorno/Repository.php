<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno;

use \DBDate;
use \Exception;
use \cl_recibopagaboleto as ReciboPagaBoletoDAO;
use \cl_retornocobrancaregistrada as RetornoCobrancaRegistradaDAO;
use \cl_ocorrenciaretornocobrancaregistrada as OcorrenciaRetornoCobrancaRegistradaDAO;

class Repository
{
  /**
   * Retorna coleção de registros referente ao retorno de cobrança registrada.
   * @param  DBDate  $oDataEmissaoInicio
   * @param  DBDate  $oDataEmissaoFim
   * @param  integer $iCodigoConvenio
   * @param  integer $iCodigoTipoDebito
   * @param  integer $iCodigoArrecadacao
   * @param  integer $iCodigoOcorrencia
   * @return RetornoCollection
   */
  public static function findAllByRequestFilters(RetornoRequestFilters $oRetornoRequestFilters)
  {
    $oReciboPagaBoletoDAO = new ReciboPagaBoletoDAO;

    $sSql  = " select view.numnov as numnov,                                                ";
    $sSql .= "        codigo_arrecadacao,                                                   ";
    $sSql .= "        convenio,                                                             ";
    $sSql .= "        data_emissao,                                                         ";
    $sSql .= "        (select recibopaga.k00_numcgm                                         ";
    $sSql .= "           from recibopaga                                                    ";
    $sSql .= "          where recibopaga.k00_numnov = view.numnov limit 1) as cgm,          ";
    $sSql .= "        array_to_string(array_agg(k149_descricao), '#') as ocorrencia,        ";
    $sSql .= "        array_to_string((                                                     ";
    $sSql .= "         select array_agg(distinct                                            ";
    $sSql .= "                arretipo.k00_descr)                                           ";
    $sSql .= "           from (select k00_tipo                                              ";
    $sSql .= "                   from arrecad                                               ";
    $sSql .= "                  where k00_numpre in (select k00_numpre                      ";
    $sSql .= "                                         from recibopaga                      ";
    $sSql .= "                                        where k00_numnov = view.numnov)       ";
    $sSql .= "                  union all                                                   ";
    $sSql .= "                 select k00_tipo                                              ";
    $sSql .= "                   from arrecant                                              ";
    $sSql .= "                  where k00_numpre in (select k00_numpre                      ";
    $sSql .= "                                         from recibopaga                      ";
    $sSql .= "                                        where k00_numnov = view.numnov)       ";
    $sSql .= "                ) as arrec                                                    ";
    $sSql .= "                inner join arretipo on arretipo.k00_tipo = arrec.k00_tipo     ";
    $sSql .= "        ), '#') as tipo                                                       ";
    $sSql .= "   from (select rpad(lpad(recibopagaboleto.k138_numnov::text, 8, '0'), 11, '0') as codigo_arrecadacao,                                                                           ";
    $sSql .= "                recibopagaboleto.k138_numnov as numnov,                                                                                                                          ";
    $sSql .= "                cadtipoconvenio.ar12_nome as convenio,                                                                                                                           ";
    $sSql .= "                recibopagaboleto.k138_data as data_emissao,                                                                                                                      ";
    $sSql .= "                ocorrenciacobrancaregistrada.k149_descricao,                                                                                                                     ";
    $sSql .= "                ocorrenciaretornocobrancaregistrada.k170_sequencial,                                                                                                             ";
    $sSql .= "                movimentoocorrenciacobrancaregistrada.k169_sequencial                                                                                                            ";
    $sSql .= "           from recibopagaboleto                                                                                                                                                 ";
    $sSql .= "                inner join remessacobrancaregistradarecibo on remessacobrancaregistradarecibo.k148_numpre = recibopagaboleto.k138_numnov                                         ";
    $sSql .= "                inner join remessacobrancaregistrada on remessacobrancaregistrada.k147_sequencial = remessacobrancaregistradarecibo.k148_remessacobrancaregistrada               ";
    $sSql .= "                inner join retornocobrancaregistrada on retornocobrancaregistrada.k168_numpre = remessacobrancaregistradarecibo.k148_numpre                                      ";
    $sSql .= "                inner join ocorrenciaretornocobrancaregistrada on ocorrenciaretornocobrancaregistrada.k170_retornocobrancaregistrada = retornocobrancaregistrada.k168_sequencial ";
    $sSql .= "                inner join ocorrenciacobrancaregistrada on ocorrenciacobrancaregistrada.k149_sequencial = ocorrenciaretornocobrancaregistrada.k170_ocorrenciacobrancaregistrada  ";
    $sSql .= "                inner join movimentoocorrenciacobrancaregistrada on movimentoocorrenciacobrancaregistrada.k169_sequencial = ocorrenciacobrancaregistrada.k149_movimento          ";
    $sSql .= "                inner join cadconvenio on cadconvenio.ar11_sequencial = remessacobrancaregistrada.k147_convenio                                                                  ";
    $sSql .= "                inner join cadtipoconvenio on cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio                                                                 ";

    $sSql .= "          where remessacobrancaregistrada.k147_instit = ".db_getsession("DB_instit");

    $sDataEmissaoInicio = $oRetornoRequestFilters->getDataEmissaoInicio()->getDate();
    $sDataEmissaoFim = $oRetornoRequestFilters->getDataEmissaoFim()->getDate();

    $sSql .= "            and recibopagaboleto.k138_data between '{$sDataEmissaoInicio}' and '{$sDataEmissaoFim}' ";

    $iCodigoConvenio = $oRetornoRequestFilters->getCodigoConvenio();

    if (!empty($iCodigoConvenio)) {
      $sSql .= "          and remessacobrancaregistrada.k147_convenio = {$iCodigoConvenio} ";
    }

    $iCodigoArrecadacao = $oRetornoRequestFilters->getCodigoArrecadacao();

    if (!empty($iCodigoArrecadacao)) {
      $sSql .= "          and rpad(lpad(recibopagaboleto.k138_numnov::text, 8, '0'), 11, '0') like '{$iCodigoArrecadacao}' ";
    }

    $iCodigoTipoDebito = $oRetornoRequestFilters->getCodigoTipoDebito();

    if (!empty($iCodigoTipoDebito)) {

      $sSql .= "          and {$iCodigoTipoDebito} in                                                          ";
      $sSql .= "              (select distinct                                                                 ";
      $sSql .= "                      k00_tipo                                                                 ";
      $sSql .= "                 from                                                                          ";
      $sSql .= "                      (                                                                        ";
      $sSql .= "                       select k00_tipo                                                         ";
      $sSql .= "                         from arrecad                                                          ";
      $sSql .= "                        where k00_numpre in (select k00_numpre                                 ";
      $sSql .= "                                               from recibopaga                                 ";
      $sSql .= "                                              where k00_numnov = recibopagaboleto.k138_numnov) ";
      $sSql .= "                        union all                                                              ";
      $sSql .= "                       select k00_tipo                                                         ";
      $sSql .= "                         from arrecant                                                         ";
      $sSql .= "                        where k00_numpre in (select k00_numpre                                 ";
      $sSql .= "                                               from recibopaga                                 ";
      $sSql .= "                                              where k00_numnov = recibopagaboleto.k138_numnov) ";
      $sSql .= "                      ) as arrec                                                               ";
      $sSql .= "              )                                                                                ";
    }

    $sSql .= "          order by ocorrenciaretornocobrancaregistrada.k170_sequencial                           ";

    $sSql .= "         ) as view                                                                               ";
    $sSql .= "  group by numnov,                                                                               ";
    $sSql .= "           cgm,                                                                                  ";
    $sSql .= "           codigo_arrecadacao,                                                                   ";
    $sSql .= "           convenio,                                                                             ";
    $sSql .= "           data_emissao                                                                          ";

    $iCodigoOcorrencia = $oRetornoRequestFilters->getCodigoOcorrencia();

    if (!empty($iCodigoOcorrencia) and $iCodigoOcorrencia != 0) {

      $sSql .= "  having sum(case                                             ";
      $sSql .= "           when k169_sequencial = {$iCodigoOcorrencia} then 1 ";
      $sSql .= "           else 0                                             ";
      $sSql .= "         end) > 0                                             ";
    }

    $sSql .= "  order by data_emissao,      ";
    $sSql .= "           codigo_arrecadacao ";

    $rsRetornoCobrancaRegistrada = $oReciboPagaBoletoDAO->sql_record($sSql);

    if ($oReciboPagaBoletoDAO->erro_banco) {
      throw new Exception($oReciboPagaBoletoDAO->erro_msg);
    }

    return new RetornoCollection($rsRetornoCobrancaRegistrada);
  }

  /**
   * Proporciona a inclusão de retornos e suas ocorrências da cobrança registrada
   * @param Retorno $oRetorno
   */
  public function incluir(Retorno $oRetorno)
  {
    $oRetornoCobrancaRegistradaDAO = new RetornoCobrancaRegistradaDAO;

    $oRetornoCobrancaRegistradaDAO->k168_numpre = $oRetorno->getNumpre();
    $oRetornoCobrancaRegistradaDAO->k168_codret = $oRetorno->getCodRet();
    $oRetornoCobrancaRegistradaDAO->incluir();

    if ($oRetornoCobrancaRegistradaDAO->erro_banco) {
      throw new Exception($oRetornoCobrancaRegistradaDAO->erro_msg);
    }

    $iRetornoCobrancaRegistrada = $oRetornoCobrancaRegistradaDAO->k168_sequencial;

    $aOcorrencia = $oRetorno->getOcorrencias();

    foreach ($oRetorno->getOcorrencias() as $iOcorrencia) {
      $this->incluirOcorrencia($iRetornoCobrancaRegistrada, $oRetorno->getCodigoMovimento(), $iOcorrencia);
    }
  }

  /**
   * Inclusão de ocorrências
   * @param integer $iRetornoCobrancaRegistrada
   * @param integer $iCodigoMovimento
   * @param integer $iOcorrencia
   */
  private function incluirOcorrencia($iRetornoCobrancaRegistrada, $iCodigoMovimento, $iOcorrencia)
  {
    $oOcorrenciaRetornoCobrancaRegistradaDAO = new OcorrenciaRetornoCobrancaRegistradaDAO;

    $sSql  = " insert into ocorrenciaretornocobrancaregistrada                                                                                                                    ";
    $sSql .= "   (k170_retornocobrancaregistrada, k170_ocorrenciacobrancaregistrada)                                                                                              ";
    $sSql .= "   (select {$iRetornoCobrancaRegistrada},                                                                                                                           ";
    $sSql .= "           ocorrenciacobrancaregistrada.k149_sequencial                                                                                                             ";
    $sSql .= "      from ocorrenciacobrancaregistrada                                                                                                                             ";
    $sSql .= "           inner join bancoagencia on bancoagencia.db89_db_bancos = ocorrenciacobrancaregistrada.k149_banco                                                         ";
    $sSql .= "           inner join conveniocobranca on conveniocobranca.ar13_bancoagencia = bancoagencia.db89_sequencial                                                         ";
    $sSql .= "           inner join cadconvenio on cadconvenio.ar11_sequencial = conveniocobranca.ar13_cadconvenio                                                                ";
    $sSql .= "           inner join remessacobrancaregistrada on remessacobrancaregistrada.k147_convenio = cadconvenio.ar11_sequencial                                            ";
    $sSql .= "           inner join remessacobrancaregistradarecibo on remessacobrancaregistradarecibo.k148_remessacobrancaregistrada = remessacobrancaregistrada.k147_sequencial ";
    $sSql .= "           inner join retornocobrancaregistrada on retornocobrancaregistrada.k168_numpre = remessacobrancaregistradarecibo.k148_numpre                              ";
    $sSql .= "           inner join movimentoocorrenciacobrancaregistrada on movimentoocorrenciacobrancaregistrada.k169_sequencial = ocorrenciacobrancaregistrada.k149_movimento  ";
    $sSql .= "     where ocorrenciacobrancaregistrada.k149_codigo = '{$iOcorrencia}'                                                                                              ";
    $sSql .= "       and movimentoocorrenciacobrancaregistrada.k169_codigo = '{$iCodigoMovimento}'                                                                                ";
    $sSql .= "       and retornocobrancaregistrada.k168_sequencial = {$iRetornoCobrancaRegistrada})                                                                               ";

    $rsOcorrenciaRetornoCobrancaRegistrada = $oOcorrenciaRetornoCobrancaRegistradaDAO->sql_record($sSql);

    if ($oOcorrenciaRetornoCobrancaRegistradaDAO->erro_banco) {
      throw new Exception($oOcorrenciaRetornoCobrancaRegistradaDAO->erro_msg);
    }
  }

  public function excluirRetornoArquivo($iCodRet)
  {
    $oRetornoCobrancaRegistradaDAO = new RetornoCobrancaRegistradaDAO;

    $sSql  = " delete from ocorrenciaretornocobrancaregistrada as orcr               ";
    $sSql .= "       using retornocobrancaregistrada as rcr,                         ";
    $sSql .= "             disarq as d                                               ";
    $sSql .= "       where orcr.k170_retornocobrancaregistrada = rcr.k168_sequencial ";
    $sSql .= "         and rcr.k168_codret = d.codret                                ";
    $sSql .= "         and d.codret = {$iCodRet}                                     ";

    $rsRetornoCobrancaRegistrada = $oRetornoCobrancaRegistradaDAO->sql_record($sSql);

    if ($oRetornoCobrancaRegistradaDAO->erro_banco) {
      throw new Exception($oRetornoCobrancaRegistradaDAO->erro_msg);
    }

    $oOcorrenciaRetornoCobrancaRegistradaDAO = new OcorrenciaRetornoCobrancaRegistradaDAO;

    $sSql  = " delete from retornocobrancaregistrada as rcr ";
    $sSql .= "       using disarq as d                      ";
    $sSql .= "       where rcr.k168_codret = d.codret       ";
    $sSql .= "         and d.codret = {$iCodRet}            ";

    $rsOcorrenciaRetornoCobrancaRegistrada = $oOcorrenciaRetornoCobrancaRegistradaDAO->sql_record($sSql);

    if ($oOcorrenciaRetornoCobrancaRegistradaDAO->erro_banco) {
      throw new Exception($oOcorrenciaRetornoCobrancaRegistradaDAO->erro_msg);
    }
  }
}
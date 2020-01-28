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

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Factory;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\BaseAbstract;
use \db_stdClass;
use \Exception;
use \db_utils;

class RemessaTemporaryService
{
  private $oRemessaRepository;

  private $sTempTable;

  private $sTempTableExportacao;

  private $iConvenio;

  private $iRemessa;

  private $iRegraCgmIss;

  private $iRegraCgmIptu;

  private $iQuantidadeRegistros;

  public function __construct(RemessaRepository $oRemessaRepository)
  {
    $this->oRemessaRepository = $oRemessaRepository;
    $this->sTempTable = "w_recibopaga_cobrancaregistrada_".time();
    $this->sTempTableExportacao = "w_cobranca_registrada_exportacao_".time();
  }

  public function getTempTableExportacao()
  {
    return $this->sTempTableExportacao;
  }

  public function getConvenio()
  {
    return $this->iConvenio;
  }

  public function getRemessa()
  {
    return $this->iRemessa;
  }

  public function getRegraCgmIss()
  {
    return $this->iRegraCgmIss;
  }

  public function getRegraCgmIptu()
  {
    return $this->iRegraCgmIptu;
  }

  public function getQuantidadeRegistros()
  {
    if ($this->iQuantidadeRegistros == null) {
      $this->iQuantidadeRegistros = $this->carregaQuantidadeRegistros();
    }

    return $this->iQuantidadeRegistros;
  }

  public function setConvenio($iConvenio)
  {
    $this->iConvenio = $iConvenio;
  }

  public function setRemessa($iRemessa)
  {
    $this->iRemessa = $iRemessa;
  }

  public function setRegraCgmIss($iRegraCgmIss)
  {
    $this->iRegraCgmIss = $iRegraCgmIss;
  }

  public function setRegraCgmIptu($iRegraCgmIptu)
  {
    $this->iRegraCgmIptu = $iRegraCgmIptu;
  }

  public function setQuantidadeRegistros($iQuantidadeRegistros)
  {
    $this->iQuantidadeRegistros = $iQuantidadeRegistros;
  }

  public function preparaRegistros()
  {
    $lReturn = false;

    if (!empty($this->iConvenio)) {

      $lReturn = $this->oRemessaRepository->createTempRemessaConvenio($this->sTempTable, $this->iConvenio);

    } else if (!empty($this->iRemessa)) {

      $this->iConvenio = $this->oRemessaRepository->getCodigoConvenio($this->iRemessa);

      $lReturn = $this->oRemessaRepository->createTempRemessaGerada($this->sTempTable, $this->iRemessa);

    } else {
      throw new Exception("Código do convênio ou remessa não informados.");
    }

    return $lReturn;
  }

  public function atualizaRegistros()
  {

    $sSql  = "update $this->sTempTable ";
    $sSql .= "set regra = (";
    $sSql .= "  CASE";
    $sSql .= "    when array_length(inscricao, 1) <> 0 then 'I'";
    $sSql .= "    when array_length(matricula, 1) <> 0 then";
    $sSql .= "    CASE (select db21_regracgmiptu from db_config where codigo = ".db_getsession("DB_instit").")";
    $sSql .= "      when 0 THEN 'C'";
    $sSql .= "      when 1 THEN 'M'";
    $sSql .= "      when 2 THEN 'C'";
    $sSql .= "    END";
    $sSql .= "    else 'C'";
    $sSql .= "  END";
    $sSql .= ")";

    $rsAtualizaRegistros = $this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->sql_record($sSql);

    if ($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_banco) {
      throw new Exception($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_msg);
    }

    $sSql  = " update $this->sTempTable                                                                  ";
    $sSql .= "    set sacado = (select ARRAY[riTipoEnvol,                                                ";
    $sSql .= "                               riMatric,                                                   ";
    $sSql .= "                               riInscr,                                                    ";
    $sSql .= "                               riNumcgm]                                                   ";
    $sSql .= "                    from fc_busca_envolvidos(true,                                         ";
    $sSql .= "                                             (case                                         ";
    $sSql .= "                                                when regra = 'I' then $this->iRegraCgmIss  ";
    $sSql .= "                                                when regra = 'M' then $this->iRegraCgmIptu ";
    $sSql .= "                                                else null                                  ";
    $sSql .= "                                              end),                                        ";
    $sSql .= "                                             regra,                                        ";
    $sSql .= "                                             (case                                         ";
    $sSql .= "                                                when regra = 'M' then matricula[1]         ";
    $sSql .= "                                                when regra = 'I' then inscricao[1]         ";
    $sSql .= "                                                else cgm[1]                                ";
    $sSql .= "                                              end)                                         ";
    $sSql .= "                                            ) limit 1)                                     ";

    $rsAtualizaRegistros = $this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->sql_record($sSql);

    if ($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_banco) {
      throw new Exception($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_msg);
    }

    return $rsAtualizaRegistros;
  }

  public function exportaRegistros()
  {
    $aCamposEndereco = array(
      "$this->sTempTable.*",
      "case when $this->sTempTable.sacado[2] is not null then cgm_matricula.z01_cgccpf when $this->sTempTable.sacado[3] is not null then cgm_inscricao.z01_cgccpf else cgm.z01_cgccpf end as cpf_cnpj",
      "case when $this->sTempTable.sacado[2] is not null then cgm_matricula.z01_nome when $this->sTempTable.sacado[3] is not null then cgm_inscricao.z01_nome else cgm.z01_nome end as nome",
      "case when $this->sTempTable.sacado[2] is not null then fc_iptuender($this->sTempTable.sacado[2]) else '' end as endereco_matricula",
      "case when $this->sTempTable.sacado[3] is not null then (case when ruas.j14_nome is not null then ruas.j14_nome else cgm_inscricao.z01_ender end) else cgm.z01_ender end as endereco",
      "case when $this->sTempTable.sacado[3] is not null then (case when issruas.q02_numero is not null then issruas.q02_numero else cgm_inscricao.z01_numero end) else cgm.z01_numero end as numero",
      "case when $this->sTempTable.sacado[3] is not null then (case when issruas.q02_compl is not null then issruas.q02_compl else cgm_inscricao.z01_compl end) else cgm.z01_compl end as complemento",
      "case when $this->sTempTable.sacado[3] is not null then (case when bairro.j13_descr is not null then bairro.j13_descr else cgm_inscricao.z01_bairro end) else cgm.z01_bairro end as bairro",
      "case when $this->sTempTable.sacado[3] is not null then (case when issruas.z01_cep is not null then issruas.z01_cep else cgm_inscricao.z01_cep end) else cgm.z01_cep end as cep",
      "case when $this->sTempTable.sacado[3] is not null then cgm_inscricao.z01_munic else cgm.z01_munic end as municipio",
      "case when $this->sTempTable.sacado[3] is not null then cgm_inscricao.z01_uf else cgm.z01_uf end as uf",
    );

    $sSql  = " select " . implode(", ", $aCamposEndereco) ." from $this->sTempTable";
    $sSql .= "        left join issbase on issbase.q02_inscr = $this->sTempTable.sacado[3] and $this->sTempTable.sacado[3] is not null";
    $sSql .= "        left join issbairro on issbairro.q13_inscr = issbase.q02_inscr";
    $sSql .= "        left join bairro on bairro.j13_codi = issbairro.q13_bairro";
    $sSql .= "        left join issruas on issruas.q02_inscr = issbase.q02_inscr";
    $sSql .= "        left join ruas on ruas.j14_codigo = issruas.j14_codigo";
    $sSql .= "        left join cgm as cgm_inscricao on issbase.q02_numcgm = cgm_inscricao.z01_numcgm";
    $sSql .= "        left join iptubase on iptubase.j01_matric = $this->sTempTable.sacado[2] and $this->sTempTable.sacado[2] is not null";
    $sSql .= "        left join cgm as cgm_matricula on iptubase.j01_numcgm = cgm_matricula.z01_numcgm";
    $sSql .= "        left join cgm on cgm.z01_numcgm = $this->sTempTable.sacado[4]";

    $sSql = "create temp table $this->sTempTableExportacao on commit drop as $sSql";

    $rsExportaRegistros = $this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->sql_record($sSql);

    if ($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_banco) {
      throw new Exception($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_msg);
    }

    return $rsExportaRegistros;
  }

  private function carregaQuantidadeRegistros()
  {
    $sSql = "select count(*) as total from $this->sTempTableExportacao";

    $rsQuantidadeRegistros = $this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->sql_record($sSql);

    if ($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_banco) {
      throw new Exception($this->oRemessaRepository->getDaoRemessaCobrancaRegistrada()->erro_msg);
    }

    $oQuantidadeRegistros = db_utils::fieldsMemory($rsQuantidadeRegistros, 0);

    if (empty($oQuantidadeRegistros->total)) {
      throw new Exception("Sem registros para gerar o arquivo de remessa.");
    }

    return $oQuantidadeRegistros->total;
  }

  public function getCodigoBanco()
  {
    return $this->oRemessaRepository->getCodigoBanco($this->iConvenio);
  }

  public function getSqlCollection($iLimit, $iOffSet)
  {
    $sSql  = " select * ";
    $sSql .= "   from $this->sTempTableExportacao ";
    $sSql .= "  limit $iLimit ";
    $sSql .= " offset $iOffSet ";

    return $sSql;
  }
}

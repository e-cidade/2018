<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 * @property int i08_sequencial
 * @property int i08_arquivoinfracao
 * @property int i08_codigoinfracao
 * @property int i08_nivel
 * @property string i08_dtpagamento
 * @property string i08_dtrepasse
 * @property string i08_nossonumero
 * @property string i08_autoinfracao
 * @property float i08_vlfunset
 * @property float i08_vldetran
 * @property float i08_vlbruto
 * @property float i08_vlprefeitura
 * @property bool i08_duplicado
 */
class cl_arquivoinfracaomulta extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('caixa.arquivoinfracaomulta');
    }

    /**
     * Retornas os totais das multas de um período de repasse, as agrupando por receita.
     * @param DBDate $oDataRepasseInicial
     * @param DBDate $oDataRepasseFinal
     * @return array
     * @throws DBException
     */
    public function buscarDadosRelatorioConsolidado(DBDate $oDataRepasseInicial, DBDate $oDataRepasseFinal)
    {

        $iAno = db_getsession("DB_anousu");
        $sDataInicial = $oDataRepasseInicial->getDate();
        $sDataFinal = $oDataRepasseFinal->getDate();


        $sSqlMultas = "select * from (";
        $sSqlMultas .= "  select i06_receitaprincipal as codigo_receita,";
        $sSqlMultas .= "         k02_descr as descricao_receita,";
        $sSqlMultas .= "         sum(cast(i08_vlbruto as NUMERIC(10,2))) as total_bruto,";
        $sSqlMultas .= "         sum(cast(i08_vlprefeitura as NUMERIC(10,2))) as total_prefeitura,";
        $sSqlMultas .= "         sum(cast(i08_vldetran as NUMERIC(10,2))) as total_detran,";
        $sSqlMultas .= "         sum(cast(i08_vlfunset as NUMERIC(10,2))) as total_funset,";
        $sSqlMultas .= "         count(i08_sequencial) as total_multas";
        $sSqlMultas .= "    from arquivoinfracaomulta";
        $sSqlMultas .= "   inner join receitainfracao on receitainfracao.i06_nivel = arquivoinfracaomulta.i08_nivel";
        $sSqlMultas .= "   inner join tabrec          on tabrec.k02_codigo = receitainfracao.i06_receitaprincipal";
        $sSqlMultas .= "   where i06_anousu = {$iAno}";
        $sSqlMultas .= "     and i08_dtrepasse between '{$sDataInicial}' and '{$sDataFinal}'";
        $sSqlMultas .= "     and i08_duplicado is false";
        $sSqlMultas .= "   group by i06_receitaprincipal, k02_descr";
        $sSqlMultas .= "  union";
        $sSqlMultas .= "   select i06_receitaduplicidade as codigo_receita,";
        $sSqlMultas .= "         k02_descr as descricao_receita,";
        $sSqlMultas .= "         sum(cast(i08_vlbruto as NUMERIC(10,2))) as total_bruto,";
        $sSqlMultas .= "         sum(cast(i08_vlprefeitura as NUMERIC(10,2))) as total_prefeitura,";
        $sSqlMultas .= "         sum(cast(i08_vldetran as NUMERIC(10,2))) as total_detran,";
        $sSqlMultas .= "         sum(cast(i08_vlfunset as NUMERIC(10,2))) as total_funset,";
        $sSqlMultas .= "         count(i08_sequencial) as total_multas";
        $sSqlMultas .= "    from arquivoinfracaomulta";
        $sSqlMultas .= "   inner join receitainfracao on receitainfracao.i06_nivel = arquivoinfracaomulta.i08_nivel";
        $sSqlMultas .= "   inner join tabrec          on tabrec.k02_codigo = receitainfracao.i06_receitaduplicidade";
        $sSqlMultas .= "   where i06_anousu = {$iAno}";
        $sSqlMultas .= "     and i08_dtrepasse between '{$sDataInicial}' and '{$sDataFinal}'";
        $sSqlMultas .= "     and i08_duplicado is true";
        $sSqlMultas .= "   group by i06_receitaduplicidade, k02_descr) as x;";

        $rsMultas = db_query($sSqlMultas);

        if (!$rsMultas) {
            throw new DBException(_M("financeiro.caixa.db_arquivoinfracaomulta_classe.erro_buscar_multas"));
        }
        if (pg_num_rows($rsMultas) == 0) {
            return array();
        }

        $aMultasConsolidadas = \db_utils::makeCollectionFromRecord($rsMultas, function ($oMulta) {
            return $oMulta;
        });

        return $aMultasConsolidadas;
    }

}

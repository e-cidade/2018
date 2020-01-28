<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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
 * @property integer v36_sequencial
 * @property integer v36_taxa
 * @property integer v36_inicialpartilha
 * @property float   v36_valor
 * @property integer v36_numnov
 * @property bool    v36_dispensalancamentorecibo
 */
class cl_inicialpartilhacustas extends DAOBasica
{

    function __construct()
    {
        parent::__construct("juridico.inicialpartilhacustas");
    }

    public function sql_query_inicialpartilhacustas ($v36_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {
        
             $sql  = "select {$campos}";
             $sql .= "  from inicialpartilhacustas ";
             $sql .= "      inner join taxa  on  taxa.ar36_sequencial = inicialpartilhacustas.v36_taxa";
             $sql .= "      inner join inicialpartilha  on  inicialpartilha.v35_sequencial = inicialpartilhacustas.v36_inicialpartilha";
             $sql .= "      inner join tabrec  on  tabrec.k02_codigo = taxa.ar36_receita";
             $sql .= "      inner join grupotaxa  on  grupotaxa.ar37_sequencial = taxa.ar36_grupotaxa";
             $sql .= "      inner join inicial  as a on   a.v50_inicial = inicialpartilha.v35_inicial";
             $sql2 = "";
             if (empty($dbwhere)) {
               if (!empty($v36_sequencial)) {
                 $sql2 .= " where inicialpartilhacustas.v36_sequencial = {$v36_sequencial} ";
               }
             } else if (!empty($dbwhere)) {
               $sql2 = " where $dbwhere";
             }
             $sql .= $sql2;
             if (!empty($ordem)) {
               $sql .= " order by {$ordem}";
             }
             return $sql;
    }

    /**
     * Query para exibir os dados de custas no recibo
     * @param $v36_numnov
     * @return string
     */
    public function sql_query_recibo_custas($v36_numnov)
    {
        $sSql  = "SELECT sum(valor) AS valor, ";
        $sSql .= "       v36_taxa, ";
        $sSql .= "       v36_dispensalancamentorecibo, ";
        $sSql .= "       ar36_receita, ";
        $sSql .= "       ar36_descricao, ";
        $sSql .= "       v35_tipolancamento ";
        $sSql .= "FROM ";
        $sSql .= "  (SELECT v36_valor AS valor, ";
        $sSql .= "          v36_taxa, ";
        $sSql .= "          v36_dispensalancamentorecibo, ";
        $sSql .= "          ar36_receita, ";
        $sSql .= "          ar36_descricao , ";
        $sSql .= "          CASE ";
        $sSql .= "              WHEN v36_dispensalancamentorecibo = FALSE THEN '1'::integer ";
        $sSql .= "              ELSE v35_tipolancamento::integer ";
        $sSql .= "          END AS v35_tipolancamento ";
        $sSql .= "   FROM inicialpartilhacustas ";
        $sSql .= "   INNER JOIN inicialpartilha ON v35_sequencial = v36_inicialpartilha ";
        $sSql .= "   INNER JOIN taxa ON ar36_sequencial = v36_taxa ";
        $sSql .= "   WHERE v36_numnov = {$v36_numnov}) AS partilhacustas ";
        $sSql .= "GROUP BY v36_taxa, ";
        $sSql .= "         v36_dispensalancamentorecibo, ";
        $sSql .= "         ar36_receita, ";
        $sSql .= "         ar36_descricao , ";
        $sSql .= "         v35_tipolancamento ";
        $sSql .= "ORDER BY v35_tipolancamento, ";
        $sSql .= "         v36_dispensalancamentorecibo ";

        return $sSql;
    }
}

<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

class ImportacaoDiversosCobrancaAdministrativa extends ImportacaoGeralDiversos
{

    private $iTipoDebitoOrigem;

    /**
     * Define se vai unificar os débitos agrupando por receita
     * @var bool
     */
    protected $lUnificarDebitos = false;

    /**
     * Debitos que serão utilizados para processamento
     * @var stdClass[]
     */
    protected $debitosProcessar = array();

    /**
     * Define qual data de vencimento será utilizada para unificar os débitos.
     * @see ImportacaoDiversos
     * @var integer
     */
    private $iOrdemDataVencimento;

    public function getTipoDebitoOrigem()
    {
        return $this->iTipoDebitoOrigem;
    }

    public function setTipoDebitoOrigem($iTipoDebitoOrigem)
    {
        $this->iTipoDebitoOrigem = $iTipoDebitoOrigem;
    }

    /**
     * @param $lUnificarDebito
     */
    public function setUnificarDebitos($lUnificarDebito)
    {
        $this->lUnificarDebitos = $lUnificarDebito;
    }

    /**
     * Define qual a data de vencimento será utilizada para unificação dos débitos
     * @param $iOrdemDataVencimento
     * @throws ParameterException
     */
    public function setOrdemDataVencimento($iOrdemDataVencimento)
    {

        if (!in_array($iOrdemDataVencimento,
          array(ImportacaoDiversos::MENOR_DATA_VENCIMENTO, ImportacaoDiversos::MAIOR_DATA_VENCIMENTO))) {
            throw new ParameterException(_M("tributario.diversos.ImportacaoGeralDiversos.sem_transacao_ativa"));
        }
        $this->iOrdemDataVencimento = $iOrdemDataVencimento;
    }

    /**
     * @param stdClass[] $aDebitos
     */
    public function setDebitos(array $aDebitos)
    {
        $this->debitosProcessar = $aDebitos;
    }

    /**
     * @return bool
     */
    protected function menorDataVencimento()
    {
        return $this->iOrdemDataVencimento === ImportacaoDiversos::MENOR_DATA_VENCIMENTO;
    }

    /**
     * @return bool
     */
    protected function maiorDataVencimento()
    {
        return $this->iOrdemDataVencimento === ImportacaoDiversos::MAIOR_DATA_VENCIMENTO;
    }

    /**
     * Função que realiza a consulta dos débitos que serão importados
     * @param $sWhere
     * @return bool|resource
     * @throws DBException
     */
    protected function buscarDebitos($sWhere)
    {
        $sSqlCaseValor = " case                                                          ";
        $sSqlCaseValor .= "   when cadtipo.k03_tipo = 3 then (                            ";
        $sSqlCaseValor .= "     select case                                               ";
        $sSqlCaseValor .= "              when issvar.q05_valor > 0 then issvar.q05_valor  ";
        $sSqlCaseValor .= "              when issvar.q05_valor = 0 then issvar.q05_vlrinf ";
        $sSqlCaseValor .= "            end                                                ";
        $sSqlCaseValor .= "       from issvar                                             ";
        $sSqlCaseValor .= "      where issvar.q05_numpre = arrecad.k00_numpre             ";
        $sSqlCaseValor .= "        and issvar.q05_numpar = arrecad.k00_numpar             ";
        $sSqlCaseValor .= "   )                                                           ";
        $sSqlCaseValor .= "   else arrecad.k00_valor                                      ";
        $sSqlCaseValor .= " end                                                           ";

        $campos = implode(", ", array(
            "arrecad.k00_numpre",
            "1 as k00_numpar",
            "arrecad.k00_numcgm",
            "arrecad.k00_dtoper",
            "arrecad.k00_receit",
            "array_to_string(array_accum(k00_hist), ',') as colecao_historico",
            "arrecad.k00_tipo",
            "cadtipo.k03_tipo",
            "arrecad.k00_tipojm",
            "arrecad.k00_numtot",
            "sum({$sSqlCaseValor}) as k00_valor",
            "min(arrecad.k00_dtvenc) as menor_data_vencimento",
            "max(arrecad.k00_dtvenc) as maior_data_vencimento",
            "array_to_string(array_accum(arrecad.k00_numpar), ',') as colecao_numpar",
            "array_to_string(array_accum({$sSqlCaseValor}), ',') as colecao_valor",
            "(select array_to_string(array_accum(k00_matric || '|' ||k00_perc), ',') from arrematric where arrematric.k00_numpre = arrecad.k00_numpre) as colecao_matricula",
            "(select array_to_string(array_accum(k00_inscr || '|' ||k00_perc), ',') from arreinscr where arreinscr.k00_numpre = arrecad.k00_numpre) as colecao_inscricao",
            "(select array_to_string(array_accum(distinct arrenumcgm.k00_numcgm), ',') from arrenumcgm where arrenumcgm.k00_numpre = arrecad.k00_numpre) as colecao_cgm",
            "array_to_string(array_accum(k00_dtvenc), ',') as data_vencimento"
          )
        );

        $where = $sWhere;
        $where .= " group by arrecad.k00_numpre,arrecad.k00_numcgm,arrecad.k00_dtoper,arrecad.k00_receit,arrecad.k00_tipo,arrecad.k00_tipojm,arrecad.k00_numtot,cadtipo.k03_tipo";

        $daoArrecadacao = new cl_arrecad();

        $sSqlBuscaDebitos = $daoArrecadacao->sql_query_arrecad(null, $campos, null, $where);

        $sSql = " select k00_numpre,                ";
        $sSql .= "        k00_numpar,               ";
        $sSql .= "        k00_numcgm,               ";
        $sSql .= "        k00_dtoper,               ";
        $sSql .= "        k00_receit,               ";
        $sSql .= "        colecao_historico,        ";
        $sSql .= "        k00_tipo,                 ";
        $sSql .= "        k03_tipo,                 ";
        $sSql .= "        k00_tipojm,               ";
        $sSql .= "        k00_numtot,               ";
        $sSql .= "        k00_valor,                ";
        $sSql .= "        menor_data_vencimento,    ";
        $sSql .= "        maior_data_vencimento,    ";
        $sSql .= "        colecao_numpar,           ";
        $sSql .= "        colecao_valor,            ";
        $sSql .= "        colecao_matricula,        ";
        $sSql .= "        colecao_inscricao,        ";
        $sSql .= "        colecao_cgm,              ";
        $sSql .= "        data_vencimento           ";
        $sSql .= "  from ({$sSqlBuscaDebitos}) as x ";
        $sSql .= " where k00_valor > 0              ";
        $sSql .= " order by k00_numpre              ";

        $rsBuscaDebitos = db_query($sSql);

        if (!$rsBuscaDebitos) {
            throw new DBException("Não foi possível consultar os dados a serem importados.");
        }

        return $rsBuscaDebitos;
    }

    /**
     * Método responsável por processar a importação de diversos unificando os débitos
     * @param string $sWhere
     * @return bool
     * @throws DBException
     * @throws ParameterException
     */
    protected function importar($sWhere)
    {

        $iCodDiverImporta = $this->salvarDiverImporta(ImportacaoDiversos::PROCESSAMENTO_GERAL);
        $rsBuscaDebitos = $this->buscarDebitos($sWhere);
        $iTotalRegistros = pg_num_rows($rsBuscaDebitos);

        for ($iRowDebitos = 0; $iRowDebitos < $iTotalRegistros; $iRowDebitos++) {

            $stdDebitoOrigem = db_utils::fieldsMemory($rsBuscaDebitos, $iRowDebitos);
            $oProcedencia = $this->aReceitaProcedencia[$stdDebitoOrigem->k00_receit];

            $oDaoNumpref = new cl_numpref();
            $iNumPreNovo = $oDaoNumpref->sql_numpre();

            /** Inclui Diversos */
            $oDaoDiversos = new cl_diversos();
            $oDaoDiversos->dv05_numcgm = $stdDebitoOrigem->k00_numcgm;
            $oDaoDiversos->dv05_dtinsc = date('Y-m-d', db_getsession('DB_datausu'));
            $oDaoDiversos->dv05_vlrhis = $stdDebitoOrigem->k00_valor;
            $oDaoDiversos->dv05_valor = $stdDebitoOrigem->k00_valor;
            $oDaoDiversos->dv05_procdiver = $oProcedencia->getProcedenciaDiverso();
            $oDaoDiversos->dv05_exerc = substr($stdDebitoOrigem->k00_dtoper, 0, 4);
            $oDaoDiversos->dv05_numpre = $iNumPreNovo;
            $oDaoDiversos->dv05_numtot = $stdDebitoOrigem->k00_numtot;
            $oDaoDiversos->dv05_privenc = $stdDebitoOrigem->menor_data_vencimento;
            $oDaoDiversos->dv05_provenc = $stdDebitoOrigem->maior_data_vencimento;
            $oDaoDiversos->dv05_diaprox = substr($stdDebitoOrigem->maior_data_vencimento, 8, 2);
            $oDaoDiversos->dv05_oper = $stdDebitoOrigem->k00_dtoper;
            $oDaoDiversos->dv05_obs = "";

            if ($this->lUnificarDebitos) {

                $parcelas = explode(",", $stdDebitoOrigem->colecao_numpar);
                sort($parcelas);
                $oDaoDiversos->dv05_obs = "Este débito refere-se às parcelas " . implode(", ", $parcelas) . ". ";
            }

            $oDaoDiversos->dv05_obs .= pg_escape_string($this->sObservacoes);
            $oDaoDiversos->dv05_instit = db_getsession('DB_instit');
            $oDaoDiversos->incluir(null);

            if ($oDaoDiversos->erro_status == '0') {
                throw new DBException($oDaoDiversos->erro_msg);
            }

            $oDaoDiverImportaReg = new cl_diverimportareg();
            $oDaoDiverImportaReg->dv12_diversos = $oDaoDiversos->dv05_coddiver;
            $oDaoDiverImportaReg->dv12_diverimporta = $iCodDiverImporta;
            $oDaoDiverImportaReg->incluir(null);

            if ($oDaoDiverImportaReg->erro_status == '0') {
                throw new DBException($oDaoDiverImportaReg->erro_msg);
            }

            foreach (explode(',', $stdDebitoOrigem->colecao_numpar) as $indiceParcela => $codigoParcela) {

                $oDaoDiverImportaOld = new cl_diverimportaold();
                $oDaoDiverImportaOld->dv13_diversos = $oDaoDiversos->dv05_coddiver;
                $oDaoDiverImportaOld->dv13_numpre = $stdDebitoOrigem->k00_numpre;
                $oDaoDiverImportaOld->dv13_numpar = $codigoParcela;
                $oDaoDiverImportaOld->dv13_receita = $stdDebitoOrigem->k00_receit;
                $oDaoDiverImportaOld->incluir(null);
                if ($oDaoDiverImportaOld->erro_status === "0") {
                    throw new DBException("Ocorreu um erro ao vincular débito de diversos com suas origens.");
                }

                /* Exclui ARRECAD original e inclui na ARREOLD */
                $oDaoArrecad = new cl_arrecad();
                $oDaoArrecad->excluir_arrecad($stdDebitoOrigem->k00_numpre, $codigoParcela, true,
                  $stdDebitoOrigem->k00_receit);

                if (!$this->lUnificarDebitos) {


                    $aValoresParcelas = explode(',', $stdDebitoOrigem->colecao_valor);
                    $aDatasVencimentos = explode(',', $stdDebitoOrigem->data_vencimento);
                    $aHistoricos = explode(',', $stdDebitoOrigem->colecao_historico);
                    $daoArrecad = new cl_arrecad();
                    $daoArrecad->k00_numpre = $iNumPreNovo;
                    $daoArrecad->k00_numpar = $codigoParcela;
                    $daoArrecad->k00_numcgm = $stdDebitoOrigem->k00_numcgm;
                    $daoArrecad->k00_dtoper = $stdDebitoOrigem->k00_dtoper;
                    $daoArrecad->k00_receit = $oProcedencia->getReceita();
                    $daoArrecad->k00_hist = $aHistoricos[$indiceParcela];
                    $daoArrecad->k00_valor = $aValoresParcelas[$indiceParcela];
                    $daoArrecad->k00_dtvenc = $aDatasVencimentos[$indiceParcela];
                    $daoArrecad->k00_numtot = $stdDebitoOrigem->k00_numtot;
                    $daoArrecad->k00_numdig = 1;
                    $daoArrecad->k00_tipo = $oProcedencia->getTipoDebito();
                    $daoArrecad->k00_tipojm = '0';
                    $daoArrecad->incluir();
                    if ($daoArrecad->erro_status === "0") {
                        throw new DBException("Não foi possível salvar os dados do novo débito.");
                    }

                }
            }

            if ($this->lUnificarDebitos) {

                $daoArrecad = new cl_arrecad();
                $daoArrecad->k00_numpre = $iNumPreNovo;
                $daoArrecad->k00_numpar = 1;
                $daoArrecad->k00_numcgm = $stdDebitoOrigem->k00_numcgm;
                $daoArrecad->k00_dtoper = $stdDebitoOrigem->k00_dtoper;
                $daoArrecad->k00_receit = $oProcedencia->getReceita();
                $daoArrecad->k00_hist = min(explode(',', $stdDebitoOrigem->colecao_historico));
                $daoArrecad->k00_valor = $stdDebitoOrigem->k00_valor;
                $daoArrecad->k00_dtvenc = $this->maiorDataVencimento() ? $stdDebitoOrigem->maior_data_vencimento : $stdDebitoOrigem->menor_data_vencimento;
                $daoArrecad->k00_numtot = 1;
                $daoArrecad->k00_numdig = 1;
                $daoArrecad->k00_tipo = $oProcedencia->getTipoDebito();
                $daoArrecad->k00_tipojm = '0';
                $daoArrecad->incluir();
                if ($daoArrecad->erro_status === "0") {
                    throw new DBException("Não foi possível salvar os dados do novo débito.");
                }
            }

            /* Salva Vinculo com Matricula */
            if (!empty($stdDebitoOrigem->colecao_matricula)) {

                foreach (explode(',', $stdDebitoOrigem->colecao_matricula) as $indice => $matriculas) {

                    list($codigoMatricula, $percentualMatricula) = explode('|', $matriculas);
                    $daoArreMatricMatricula = new cl_arrematric();
                    $daoArreMatricMatricula->k00_numpre = $iNumPreNovo;
                    $daoArreMatricMatricula->k00_matric = $codigoMatricula;
                    $daoArreMatricMatricula->k00_perc = $percentualMatricula;
                    $daoArreMatricMatricula->incluir($daoArreMatricMatricula->k00_numpre,
                      $daoArreMatricMatricula->k00_matric);
                    if ($daoArreMatricMatricula->erro_status === '0') {
                        throw new ParameterException("Não foi possível vincular o débito {$iNumPreNovo} na matricula {$codigoMatricula}.");
                    }
                }
            }

            /* Salva Vinculo com Inscrição */
            if (!empty($stdDebitoOrigem->colecao_inscricao)) {

                foreach (explode(',', $stdDebitoOrigem->colecao_inscricao) as $indice => $inscricoes) {

                    list($codigoInscricao, $percentualInscricao) = explode('|', $inscricoes);
                    $daoArreInscricao = new cl_arreinscr();
                    $daoArreInscricao->k00_numpre = $iNumPreNovo;
                    $daoArreInscricao->k00_inscr = $codigoInscricao;
                    $daoArreInscricao->k00_perc = $percentualInscricao;
                    $daoArreInscricao->incluir($daoArreInscricao->k00_numpre, $daoArreInscricao->k00_inscr);
                    if ($daoArreInscricao->erro_status === '0') {
                        throw new ParameterException("Não foi possível vincular o débito {$iNumPreNovo} na inscrição {$codigoInscricao}.");
                    }
                }
            }
        }
        return true;
    }

    /**
     * @return bool
     * @throws ParameterException
     */
    public function importacaoParcial()
    {

        if (empty($this->debitosProcessar)) {
            throw new ParameterException("Informe os débitos que devem ser processados.");
        }

        $aWhere = array();
        foreach ($this->debitosProcessar as $stdDebito) {

            $this->adicionarReceita($stdDebito->iReceita, null,
              new ProcedenciaDiversos($stdDebito->iCodigoProcedencia));
            $aWhere[] = "(arrecad.k00_numpre = {$stdDebito->iNumpre} and arrecad.k00_numpar = {$stdDebito->iNumpar} and arrecad.k00_receit = {$stdDebito->iReceita})";
        }

        return $this->importar(implode(' or ', $aWhere));
    }

    /**
     * @return bool
     */
    public function importacaoGeral()
    {

        $sReceitas = implode(',', array_keys($this->aReceitaProcedencia));

        $sWhere = implode(' and ',
          array(
            "arrecad.k00_receit in ({$sReceitas})",
            "arrecad.k00_tipo = {$this->getTipoDebitoOrigem()}"
          )
        );
        return $this->importar($sWhere);
    }
}

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

namespace ECidade\Tributario\Divida;

/**
 * Class ImportacaoDividaAtiva
 * @package ECidade\Tributario\Divida
 */
class ImportacaoDividaAtiva extends \ImportacaoDiversosCobrancaAdministrativa
{
    const CADTIPO_DIVIDA = 5;
    /**
     * @var
     */
    private $oDadosDebitos;

    /**
     * Tipos para buscar as Observacoes
     * @var array
     */
    private $aTipos = array(3, 7, 4, 11, 16, 17, 19);

    /**
     * Codigo da Importacao
     * @var integer
     */
    protected $codigoImportacao;

    /**
     * Define se deve vincular a matricula e inscricoes de debito
     * @var bool
     */
    private $vincularMatriculasInscricoes = true;

    /**
     * @var \ProcedenciaDivida[]
     */
    protected $aReceitaProcedencia = array();

    /**
     * @var array
     */
    protected $aCertidaoDivida = array();


    /**
     * @var \processoProtocolo
     */
    protected $processo;

    /**
     * @var \stdClass
     */
    protected $stdProcesso;

    /**
     * @param $codigoReceita
     * @param \ProcedenciaDivida $procedenciaDivida
     */
    public function adicionarReceitaProcedencia($codigoReceita, \ProcedenciaDivida $procedenciaDivida)
    {
        $this->aReceitaProcedencia[$codigoReceita] = $procedenciaDivida;
        $this->aReceitaVencimento[$codigoReceita] = null;
    }

    /**
     * @param string $sWhere
     * @throws \DBException
     * @throws \Exception
     * @return boolean true
     */
    protected function importar($sWhere)
    {
        $rsBuscaDebitos = $this->buscarDebitos($sWhere);
        $iTotalRegistros = pg_num_rows($rsBuscaDebitos);

        if ( $iTotalRegistros == 0 ) {
            throw new \Exception("Nenhum registro encontrado.");
        }

        $this->oDadosDebitos = \db_utils::getCollectionByRecord($rsBuscaDebitos);
        $this->validarOrigem();

        $daoDividaImportada = new \cl_divimporta();
        $daoDividaImportada->v02_divimporta = null;
        $daoDividaImportada->v02_usuario = db_getsession('DB_id_usuario');
        $daoDividaImportada->v02_data    = date('Y-m-d', db_getsession('DB_datausu'));
        $daoDividaImportada->v02_hora    = db_hora();
        $daoDividaImportada->v02_horafim = db_hora();
        $daoDividaImportada->v02_datafim = date('Y-m-d', db_getsession('DB_datausu'));
        $daoDividaImportada->v02_tipo    = "1"; // MUDAR O TIPO
        $daoDividaImportada->v02_instit  = \InstituicaoRepository::getInstituicaoSessao()->getCodigo();
        $daoDividaImportada->incluir($daoDividaImportada->v02_divimporta);
        if ($daoDividaImportada->erro_status === '0') {
            throw new \DBException("Erro ao incluir os dados da importação");
        }

        $this->codigoImportacao = $daoDividaImportada->v02_divimporta;

        foreach ($this->oDadosDebitos as $iRowDebitos => $stdDebitoOrigem) {

            $numpreNovo = \cl_numpref::getNumpre();
            $this->vincularMatriculasInscricoes = true;

            $parcelas = explode(",", $stdDebitoOrigem->colecao_numpar);

            foreach ( $parcelas as $indiceParcela => $parcela) {

                $this->incluirDivida($stdDebitoOrigem, $indiceParcela, $numpreNovo);
                $oDaoArrecad = new \cl_arrecad();
                $oDaoArrecad->excluir_arrecad($stdDebitoOrigem->k00_numpre, $parcela, true, $stdDebitoOrigem->k00_receit);

                if (!$this->lUnificarDebitos) {
                    $this->incluirDebito($stdDebitoOrigem, $indiceParcela, $numpreNovo);
                }
            }

            if ($this->lUnificarDebitos) {
                $this->incluirDebito($stdDebitoOrigem, 0, $numpreNovo);
            }
        }

        $this->emitirCertidao();
        $this->emitirInicial();

        return true;
    }

    /**
     * Função que emite as certdões de acordo com as dívidas geradas
     */
    protected function emitirCertidao()
    {
        $cda = new \cda(null);

        foreach ($this->aDividasArrecad as $indiceDono=> $aDividasArrecad) {

            foreach ($aDividasArrecad as $iExercicio => $aDivida) {

                $indiceDonos = explode("-", $indiceDono);
                $dados = new \stdClass();
                $dados->cgm = $indiceDonos[0];
                $dados->dividas = $aDivida;
                $dados->certidao = $cda->geraLoteCertidao(self::CADTIPO_DIVIDA, $aDivida);

                $this->aCertidaoDivida[] = $dados;
            }
        }
    }

    protected function emitirInicial()
    {
        foreach ($this->aCertidaoDivida as $dadosCertidao) {

            $whereAdvogado = "v57_numcgm in (select numcgm from db_config where prefeitura is true)";
            $daoAdvogado = new \cl_advog();
            $sqlAdvogado = $daoAdvogado->sql_query_file(null,  "v57_numcgm as cgm", null, $whereAdvogado);
            $recordAdvogado = $daoAdvogado->sql_record($sqlAdvogado);

            if (!$recordAdvogado) {
                throw new \DBException("Erro ao buscar os dados do advogado.");
            }

            $advogado = \db_utils::fieldsMemory($recordAdvogado, 0);

            $inicial = new \inicial();
            $inicial->setCodigoAdvogado($advogado->cgm);
            $inicial->setCodigoLocal("0");
            $inicial->setCodigoMovimentacao("0");
            $inicial->setInstituicao(db_getsession("DB_instit"));
            $inicial->setUsuario(db_getsession("DB_id_usuario"));
            $inicial->setData( date("Y-m-d", db_getsession("DB_datausu")) );
            $inicial->setSituacao(1);
            $inicial->salvar();

            $numpresInceridos = array();

            foreach ($dadosCertidao->dividas as $divida) {

                if (in_array($divida->iNumpre, $numpresInceridos)) {
                    continue;
                }

                $daoInicialNumpre = new \cl_inicialnumpre();
                $daoInicialNumpre->v59_inicial = $inicial->getCodigoInicial();
                $daoInicialNumpre->v59_numpre  = $divida->iNumpre;
                $resultadoNumpre = $daoInicialNumpre->incluir();

                if (!$resultadoNumpre){
                    throw new \DBException("Erro ao incluir os débitos na inicial do foro. ");
                }

                $numpresInceridos[] = $divida->iNumpre;
            }

            $daoInicialNomes = new \cl_inicialnomes();
            $resultadoCgm = $daoInicialNomes->incluir($inicial->getCodigoInicial(), $dadosCertidao->cgm);

            if (!$resultadoCgm) {
                throw new \DBException("Erro ao vincular inicial ao CGM.");
            }

            $daoInicialCert = new \cl_inicialcert();
            $retornoCertidao = $daoInicialCert->incluir($inicial->getCodigoInicial(), $dadosCertidao->certidao);

            if (!$retornoCertidao) {
                throw new \DBException("Erro ao vincular a Certidão de Dívida na Inicial do Foro.");
            }

            $observacoes = "Movimentação gerada automaticamente pela importação para a dívida ativa.";
            $inicial->adicionarMovimentacao(1, $observacoes);
            $inicial->salvar();

            $daoParametros = new \cl_pardiv();
            $where = "v04_instit = ".db_getsession('DB_instit');
            $sqlParametro = $daoParametros->sql_query_file(null, "v04_tipoinicial", null, $where);
            $rsParametro  = $daoParametros->sql_record($sqlParametro);

            if (!$rsParametro) {
                throw new \DBException("Erro ao consultar os parâmentros de configuração do Módulo Divida Ativa.");
            }

            $parametros = \db_utils::fieldsMemory($rsParametro, 0);

            foreach ($dadosCertidao->dividas as $divida) {

                $daoArrecad = new \cl_arrecad();
                $daoArrecad->k00_tipo = $parametros->v04_tipoinicial;
                $resuladoArrecad = $daoArrecad->alterar_arrecad("k00_numpre = {$divida->iNumpre}");

                if (!$resuladoArrecad) {
                    throw new \DBException("Erro ao trocar o tipo de débito para inicial do foro.");
                }
            }
        }
    }

    /**
     * @param \stdClass $debito
     * @param $indiceParcela
     * @throws \DBException|\ParameterException
     * @return bool true
     */
    protected function incluirDebito(\stdClass $debito, $indiceParcela, $numpreNovo)
    {
        $procedenciaDivida = $this->aReceitaProcedencia[$debito->k00_receit];
        $valoresParcelas = explode(',', $debito->colecao_valor);
        $datasVencimento = explode(',', $debito->data_vencimento);
        $historicos      = explode(',', $debito->colecao_historico);
        $parcelas        = explode(',', $debito->colecao_numpar);
        $daoArrecad = new \cl_arrecad();
        $daoArrecad->k00_numpre = $numpreNovo;
        $daoArrecad->k00_numpar = $this->lUnificarDebitos ? 1 : $parcelas[$indiceParcela];
        $daoArrecad->k00_numcgm = $debito->k00_numcgm;
        $daoArrecad->k00_dtoper = $debito->k00_dtoper;
        $daoArrecad->k00_receit = $procedenciaDivida->getReceitaDivida();
        $daoArrecad->k00_hist   = $historicos[$indiceParcela];
        $daoArrecad->k00_valor  = $this->lUnificarDebitos ? $debito->k00_valor : $valoresParcelas[$indiceParcela];
        $daoArrecad->k00_dtvenc = $datasVencimento[$indiceParcela];
        $daoArrecad->k00_numtot = $debito->k00_numtot;
        $daoArrecad->k00_numdig = 1;
        $daoArrecad->k00_tipo   = $procedenciaDivida->getTipoDebito();
        $daoArrecad->k00_tipojm = '0';
        $daoArrecad->incluir();
        if ($daoArrecad->erro_status === "0") {
            throw new \DBException("Não foi possível salvar os dados do novo débito.");
        }

        /**
         * Logica implementada para vincular somente uma vez as matriculas/inscricoes no debito novo criado
         */
        if ($this->vincularMatriculasInscricoes === true) {

            $this->vincularMatriculasInscricoes = false;

            if (!empty($debito->colecao_matricula)) {

                foreach (explode(',', $debito->colecao_matricula) as $indice => $matriculas) {

                    list($codigoMatricula, $percentualMatricula) = explode('|', $matriculas);
                    $daoArreMatricMatricula = new \cl_arrematric();
                    $daoArreMatricMatricula->k00_numpre = $numpreNovo;
                    $daoArreMatricMatricula->k00_matric = $codigoMatricula;
                    $daoArreMatricMatricula->k00_perc   = $percentualMatricula;
                    $daoArreMatricMatricula->incluir($daoArreMatricMatricula->k00_numpre, $daoArreMatricMatricula->k00_matric);
                    if ($daoArreMatricMatricula->erro_status === '0') {
                        throw new \ParameterException("Não foi possível vincular o débito {$numpreNovo} na matricula {$codigoMatricula}.");
                    }
                }
            }

            if (!empty($debito->colecao_inscricao)) {

                foreach (explode(',', $debito->colecao_inscricao) as $indice => $inscricoes) {

                    list($codigoInscricao, $percentualInscricao) = explode('|', $inscricoes);
                    $daoArreInscricao = new \cl_arreinscr();
                    $daoArreInscricao->k00_numpre = $numpreNovo;
                    $daoArreInscricao->k00_inscr  = $codigoInscricao;
                    $daoArreInscricao->k00_perc   = $percentualInscricao;
                    $daoArreInscricao->incluir($daoArreInscricao->k00_numpre, $daoArreInscricao->k00_inscr);
                    if ($daoArreInscricao->erro_status === '0') {
                        throw new \ParameterException("Não foi possível vincular o débito {$numpreNovo} na inscrição {$codigoInscricao}.");
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param \stdClass $debito
     * @param integer $indiceParcela
     * @return integer
     * @throws \DBException
     */
    protected function incluirDivida(\stdClass $debito, $indiceParcela, $numpreNovo)
    {
        $daoDivida = new \cl_divida();
        $observacaoDivida = "";

        if (in_array($debito->k03_tipo, $this->aTipos)) {
            $observacaoDivida = $daoDivida->resumo_importacao($debito->k00_numpre, $debito->k03_tipo);
        }

        $valor           = explode(",", $debito->colecao_valor);
        $datasVencimento = explode(",", $debito->data_vencimento);
        $parcelas        = explode(",", $debito->colecao_numpar);
        $dataDia = date("Y-m-d", db_getsession("DB_datausu"));
        list($anoSessao) = explode('-', $dataDia);
        $exercicioDivida = $daoDivida->getExercicioDivida($debito->k00_numpre, $debito->k03_tipo, substr($debito->k00_dtoper, 0, 4));

        $where = "(v01_folha, v01_livro) = (select max(v01_folha) as v01_folha,";
        $where .= "                      v01_livro ";
        $where .= "                 from divida ";
        $where .= "                where v01_livro in (select max(v01_livro) ";
        $where .= "                                      from divida ";
        $where .= "                                     where extract(year from v01_dtinclusao) = 2017) ";
        $where .= "                group by v01_livro) ";
        $where .= "group by 1, 2";

        $campos = implode(',',
          array(
            "v01_livro",
            "v01_folha",
            "count(*) as total_registros",
          )
        );

        $query = $daoDivida->sql_query_file(null, $campos, null, $where);
        $buscaLivroExercicio = db_query($query);

        if (pg_num_rows($buscaLivroExercicio) == 0) {

            $novoLivro = db_query($daoDivida->sql_query_file(null, "coalesce(max(v01_livro), 0) + 1 as v01_livro"));
            $livroExercicio = \db_utils::fieldsMemory($novoLivro, 0)->v01_livro;
            $folhaExercicio = 1;
            $totalRegistrosPorFolha = 0;
        } else {

            $livroExercicio = \db_utils::fieldsMemory($buscaLivroExercicio, 0)->v01_livro;
            $folhaExercicio = \db_utils::fieldsMemory($buscaLivroExercicio, 0)->v01_folha;
            $totalRegistrosPorFolha = \db_utils::fieldsMemory($buscaLivroExercicio, 0)->total_registros;
        }


        if ($totalRegistrosPorFolha >= 30) {
            $folhaExercicio++;
        }

        $daoDivida->v01_coddiv = null;
        $daoDivida->v01_numcgm = $debito->k00_numcgm;
        $daoDivida->v01_dtinsc = $dataDia;
        $daoDivida->v01_exerc  = $exercicioDivida;
        $daoDivida->v01_numpre = $numpreNovo;
        $daoDivida->v01_numpar = $parcelas[$indiceParcela];
        $daoDivida->v01_numtot = $debito->k00_numtot;
        $daoDivida->v01_vlrhis = $valor[$indiceParcela];
        $daoDivida->v01_proced = $this->aReceitaProcedencia[$debito->k00_receit]->getProcedenciaDivida();
        $daoDivida->v01_livro  = $livroExercicio;
        $daoDivida->v01_folha  = $folhaExercicio;
        $daoDivida->v01_dtvenc = $datasVencimento[$indiceParcela];
        $daoDivida->v01_dtoper = $debito->k00_dtoper;
        $daoDivida->v01_valor  = $valor[$indiceParcela];
        $daoDivida->v01_obs    = str_replace("'", "\'", " - {$observacaoDivida} \n {$this->sObservacoes}");
        $daoDivida->v01_numdig = "0";
        $daoDivida->v01_instit = db_getsession("DB_instit");
        $daoDivida->v01_dtinclusao = $dataDia;
        $daoDivida->v01_processo   = '';
        $daoDivida->v01_dtprocesso = '';
        $daoDivida->v01_titular    = '';

        if (!empty($this->stdProcesso)) {
            $daoDivida->v01_processo   = $this->stdProcesso->codigo;
            $daoDivida->v01_dtprocesso = $this->stdProcesso->data;
            $daoDivida->v01_titular    = $this->stdProcesso->titular;
        }
        $daoDivida->incluir($daoDivida->v01_coddiv);
        if ($daoDivida->erro_status === 0) {
            throw new \DBException("Nao incluiu divida");
        }
        $codigoDivida = $daoDivida->v01_coddiv;

        if (!empty($this->processo)) {

            $daoProcessoDivida = new \cl_dividaprotprocesso();
            $daoProcessoDivida->v88_sequencial = null;
            $daoProcessoDivida->v88_divida = $codigoDivida;
            $daoProcessoDivida->v88_protprocesso = $this->processo->getCodProcesso();
            $daoProcessoDivida->incluir($daoProcessoDivida->v88_sequencial);
            if ($daoProcessoDivida->erro_status === '0') {
                throw new \DBException("Ocorreu um erro ao vincular a dívida com o processo do protocolo.");
            }
        }

        $indiceDivida = $numpreNovo.$parcelas[$indiceParcela];
        $indiceDono   = $debito->k00_numcgm . "-" . $debito->colecao_matricula . "-" . $debito->colecao_inscricao;

        if (!isset($this->aDividasArrecad[$indiceDono][$exercicioDivida][$indiceDivida])) {

            $oDivida = new \stdClass();
            $oDivida->iNumpre = $numpreNovo;
            $oDivida->iNumpar = $parcelas[$indiceParcela];

            $this->aDividasArrecad[$indiceDono][$exercicioDivida][$indiceDivida] = $oDivida;
        }

        $daoDividaImportaReg = new \cl_divimportareg();
        $daoDividaImportaReg->v04_coddiv = $codigoDivida;
        $daoDividaImportaReg->v04_divimporta = $this->codigoImportacao;
        $daoDividaImportaReg->incluir();

        if ($daoDividaImportaReg->erro_status === '0') {
            throw new \DBException("Nao incluiu registro de importação da dívida");
        }

        $daoDividaOld = new \cl_divold();
        $daoDividaOld->k10_sequencial = null;
        $daoDividaOld->k10_coddiv     = $codigoDivida;
        $daoDividaOld->k10_numpre     = $debito->k00_numpre;
        $daoDividaOld->k10_numpar     = $parcelas[$indiceParcela];
        $daoDividaOld->k10_receita    = $debito->k00_receit;
        $daoDividaOld->incluir($daoDividaOld->k10_sequencial);
        if ($daoDividaOld->erro_status === '0') {
            throw new \DBException("Não foi possível incluir a divida em divold. ".pg_last_error());
        }

        return $codigoDivida;
    }

    /**
     * @param $iParcelamento
     * @return \stdClass[]
     * @throws \DBException
     * @throws \Exception
     */
    protected function buscarOrigemParcelamento($iParcelamento)
    {
        $oDaoTermo  = new \cl_termo();
        $sSqlOrigem = $oDaoTermo->sql_query_origem_parcelamento($iParcelamento);

        $sCampos = implode("," , array(
          "k00_numpre",
          "1 as k00_numpar",
          "k00_numcgm",
          "k00_dtoper",
          "k00_receit",
          "array_to_string(array_accum(k00_hist), ',') as colecao_historico",
          "x.k00_tipo",
          "k03_tipo",
          "k00_tipojm",
          "k00_numtot",
          "sum(k00_valor) as k00_valor",
          "min(k00_dtvenc) as menor_data_vencimento",
          "max(k00_dtvenc) as maior_data_vencimento",
          "array_to_string(array_accum(k00_numpar), ',') as colecao_numpar",
          "array_to_string(array_accum(k00_valor), ',') as colecao_valor",
          "(select array_to_string(array_accum(k00_matric || '|' ||k00_perc), ',') from arrematric where arrematric.k00_numpre = x.k00_numpre) as colecao_matricula",
          "(select array_to_string(array_accum(k00_inscr || '|' ||k00_perc), ',') from arreinscr where arreinscr.k00_numpre = x.k00_numpre) as colecao_inscricao",
          "(select array_to_string(array_accum(distinct arrenumcgm.k00_numcgm), ',') from arrenumcgm where arrenumcgm.k00_numpre = x.k00_numpre) as colecao_cgm",
          "array_to_string(array_accum(k00_dtvenc), ',') as data_vencimento"
        ));

        $sGroupBy = implode(",", array(
          "x.k00_numpre",
          "x.k00_numcgm",
          "x.k00_dtoper",
          "x.k00_receit",
          "x.k00_tipo",
          "arretipo.k03_tipo",
          "x.k00_tipojm",
          "x.k00_numtot"
        ));

        $sSql  = "select $sCampos           ";
        $sSql .= "  from ($sSqlOrigem) as x ";
        $sSql .= "      inner join arretipo on arretipo.k00_tipo = x.k00_tipo ";
        $sSql .= " where k00_valor > 0      ";
        $sSql .= " group by $sGroupBy       ";
        $sSql .= " order by k00_numpre      ";

        $rsOrigem = db_query($sSql);

        if (!$rsOrigem) {
            throw new \DBException("Erro ao consultar os dados de origem do parcelamento.");
        }

        if (pg_num_rows($rsOrigem) == 0) {
            throw new \Exception("Não há débitos de origem para este parcelamento.");
        }

        return \db_utils::getCollectionByRecord($rsOrigem);
    }

    /**
     * Função que anula o parcelamento e atualiza os dados dos débitos a serem importados
     * @param $iParcelamento
     * @return array|\stdClass[]
     * @throws \DBException
     */
    protected function anularParcelamento($iParcelamento)
    {
        $oOrigemParcelamento = $this->buscarOrigemParcelamento($iParcelamento);

        $oDaoTermo = new \cl_termo();
        $sSqlSimulacao = $oDaoTermo->sql_query_simular_anulacao($iParcelamento);
        $rsSimulacao   = $oDaoTermo->sql_record($sSqlSimulacao);

        if (!$rsSimulacao) {
            throw new \DBException("Erro ao simular a anulação do parcelamento.");
        }

        $oRetorno = \db_utils::fieldsMemory($rsSimulacao, 0);

        if ( strpos($oRetorno->simulacao, 'OK' ) === false ) {
            throw new \DBException($oRetorno->simulacao);
        }

        $sSqlSimulacao = $oDaoTermo->sql_query_simulacao(
          $iParcelamento,
          null,
          "max(v21_sequencial) as codigo_simulacao" );
        $rsSimulacao   = $oDaoTermo->sql_record($sSqlSimulacao);

        if (!$rsSimulacao) {
            throw new \DBException("Erro ao buscar o código da simulação de anulação do parcelamento.");
        }

        $oSimulacao = \db_utils::fieldsMemory($rsSimulacao, 0);
        $iUsuario   = db_getsession("DB_id_usuario");

        $sMotivo = "Anulado por inscrição em Divida Ativa";

        $sSqlAnulacao = $oDaoTermo->sql_query_anular_parcelamento($oSimulacao->codigo_simulacao, $iUsuario, $sMotivo);
        $rsAnulacao   = $oDaoTermo->sql_record($sSqlAnulacao);

        if (!$rsAnulacao) {
            throw new \DBException("Erro ao anular o parcelamento.");
        }

        $oAnulacao = \db_utils::fieldsMemory($rsAnulacao, 0);

        if ( strpos($oAnulacao->anulacao, 'OK' ) === false ) {
            throw new \DBException($oAnulacao->anulacao);
        }

        $aNumpres = array();
        $retornoOrigem = array();
        foreach ($oOrigemParcelamento as $indice => $debito) {

            $aNumpres[$debito->k00_numpre] = $debito->k00_numpre;

            /**
             * Atualiza os valores das parcelas.
             * Se houver algum pagamento do parcelamento, os valores de parcelas serão atualizadas após a anulação
             * do parcelamento
             */
            $oDaoArrecad = new \cl_arrecad();
            $sCampos = "array_to_string(array_accum(k00_valor), ',') as colecao_valor";
            $sWhere  = "k00_numpre = {$debito->k00_numpre}";
            $sSqlParcelaAtualizada = $oDaoArrecad->sql_query_file(null, $sCampos, null, $sWhere);
            $rsParcelaAtualizada = db_query($sSqlParcelaAtualizada);

            if(!$rsParcelaAtualizada) {
                throw new \DBException("Erro ao buscar os valores da parcela.");
            }

            if(pg_num_rows($rsParcelaAtualizada) > 0) {
                $colecao_valor = \db_utils::fieldsMemory($rsParcelaAtualizada, 0)->colecao_valor;
                $oOrigemParcelamento[$indice]->colecao_valor =  $colecao_valor;
            }
        }

        foreach ($aNumpres as $numpre) {
            $parcelamento = $this->verificarParcelamento($numpre);

            if (!empty($parcelamento)) {
                $retornoOrigem = array_merge($retornoOrigem, $this->anularParcelamento($parcelamento));
            }
        }

        if (!empty($retornoOrigem)) {
            $oOrigemParcelamento = $retornoOrigem;
        }

        return $oOrigemParcelamento;
    }

    /**
     * Função que verifica se o débito que está sendo importado é um parcelamento
     * @return bool|integer
     * @throws \DBException
     */
    protected function verificarParcelamento($numpre)
    {
        $oDaoTermo = new \cl_termo();
        $sWhere    = " v07_numpre = {$numpre} ";
        $sSqlTermo = $oDaoTermo->sql_query_file(
          null,
          "distinct v07_parcel as codigo_parcelamento",
          null,
          $sWhere );
        $rsTermo   = db_query($sSqlTermo);

        if (!$rsTermo) {
            throw new \DBException("Erro ao consultar dados do parcelamento.");
        }

        if ( pg_num_rows($rsTermo) > 0 ) {

            $oTermo = \db_utils::fieldsMemory($rsTermo, 0);
            return $oTermo->codigo_parcelamento;
        }

        return false;
    }

    protected function validarOrigem()
    {
        $aNumpres = array();

        foreach ($this->oDadosDebitos as $debito) {
            $aNumpres[$debito->k00_numpre] = $debito->k00_numpre;
        }

        $aOrigens = array();

        foreach ($aNumpres as $numpre) {
            $parcelamento = $this->verificarParcelamento($numpre);

            if (!empty($parcelamento)) {
                $aOrigens = array_merge($aOrigens, $this->anularParcelamento($parcelamento));
            }
        }

        if (!empty($aOrigens)) {
            $this->oDadosDebitos = $aOrigens;
        }
    }

    /**
     * @param \processoProtocolo $processo
     */
    public function setProcessoProtocolo(\processoProtocolo $processo)
    {
        $this->processo = $processo;
    }

    /**
     * @param \stdClass $stdProcesso
     */
    public function setProcesso(\stdClass $stdProcesso)
    {
        $this->stdProcesso = $stdProcesso;
    }

    /**
     * @return bool
     * @throws \ParameterException
     */
    public function importacaoParcial()
    {
        if (empty($this->debitosProcessar)) {
            throw new \ParameterException("Informe os débitos que devem ser processados.");
        }

        $aWhere = array();

        foreach ($this->debitosProcessar['aDebitos'] as $stdDebito) {

            if ( isset($stdDebito->iCodigoProcedencia) ) {
                $this->adicionarReceitaProcedencia($stdDebito->iReceita,new \ProcedenciaDivida($stdDebito->iCodigoProcedencia));
            }

            $aWhere[] = "(arrecad.k00_numpre = {$stdDebito->iNumpre} and arrecad.k00_numpar = {$stdDebito->iNumpar} and arrecad.k00_receit = {$stdDebito->iReceita})";
        }

        if (isset($this->debitosProcessar['aProcedencias'])) {
            $aProcedencias = (array) $this->debitosProcessar['aProcedencias'];

            foreach ($aProcedencias as $receita => $procedencia){
                $this->adicionarReceitaProcedencia($receita,new \ProcedenciaDivida($procedencia->iProcedencia));
            }
        }

        return $this->importar(implode(' or ', $aWhere));
    }

    /**
     * @return bool
     */
    public function importacaoGeral()
    {
        $sWhere = "arrecad.k00_tipo = {$this->getTipoDebitoOrigem()}";

        if(count($this->aReceitaProcedencia) > 0) {

            $aReceitas = array();

            foreach($this->aReceitaProcedencia as $indice => $oReceita) {
                $aReceitas[] = $indice;
            }

            $sReceitas = implode(', ', $aReceitas);
            $sWhere .= " AND arrecad.k00_receit in({$sReceitas})";
        }

        return $this->importar($sWhere);
    }
}

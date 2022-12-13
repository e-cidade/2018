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
 * Model referente a rotina importacao do censo
 * @author jamile
 */
abstract class importacaoCenso
{

    protected static $aTipoRegistro = array("00", "10", "20", "30", "40", "50", "51", "60", "70", "80");

    /**
     * se for true importa dados da escola, caso seja false nao importa
     * @var boolean $lImportarEscola
     */
    public $lImportarEscola = false;
    /**
     * se for true importa dados da turma, caso seja false nao importa
     * @var boolean $lImportarTurma
     */
    public $lImportarTurma = false;
    /**
     * se for true importa dados do docente, caso seja false nao importa
     * @var boolean $lImportarDocente
     */
    public $lImportarDocente = false;
    /**
     * se for true importa dados do aluno, caso seja false nao importa
     * @var boolean $lImportarAluno
     */
    public $lImportarAluno = false;
    /**
     * @var resource $pArquivoLog
     * stream para o arquivo de log
     */
    public $pArquivoLog;
    /**
     * @var string $sCaminhoArquivo
     * nome do caminho onde se encontra o arquivo do censo
     */
    public $sCaminhoArquivo;
    /**
     * @var integer $iAnoEscolhido
     * ano que foi escolhido ex: 2011 ou 2010
     */
    public $iAnoEscolhido;
    /**
     * @var integer $iCodigoInepEscola
     * codigo inep escola utilizado nos sql das funcoes abaixo
     */
    public $iCodigoInepEscola;
    /**
     * @var string $sNomeArquivoLog
     * nome, endereco do arquivo de log
     */
    public $sNomeArquivoLog;
    /**
     * @var boolean lImportarAlunoAtivo
     * variavel utilizada para verificar qual o parametro escolhido no modulo secretaria
     * conforme o que foi escolhido ele verifica se o aluno tem vinculo ou nao com a escola
     * antes de importar se possuir valor true somente importa alunos que estejam ativos na escola
     * senao importa todos
     */
    public $lImportarAlunoAtivo;
    /**
     * Variavel sera utilizada para setar o codigo do layout que será lido pela funcao DBLayoutReader()
     * @var integer $iCodigoLayout ;
     */
    public $iCodigoLayout;

    /**
     * Se o for true é executada a funcao incluir caso contrario nao inclui
     * @var boolean lIncluirAlunoNaoEncontrado
     */
    public $lIncluirAlunoNaoEncontrado = false;
    /**
     * Se o for true é utilizado o layout cadastrado com o | caso contrário é sem o |
     * @var boolean lLayoutComPipe
     */
    public $lLayoutComPipe = true;
    /**
     * Verifica de qual módulo está sendo acessado.
     *  - Escola: Atualiza os dados do aluno e não inclui novos
     *  - Secretaria: Inclui novos alunos e não atualiza os já existentes.
     * @var boolean $lModuloEscola
     */
    public $lModuloEscola = true;
    /**
     * Vai guardar os caminhos dos arquivos separados por escola
     * @var array $aCaminhosArquivosFatiados
     */
    public $aCaminhosArquivosFatiados;

    /**
     * @var ProgressBar $oProgressBar
     */
    public $oProgressBar;

    protected $sCampoChave = 'tiporegistro';
    /**
     * Se for true os sql da classe irão checar sempre pelo inep da escola.
     * Se for false, usado para importação de alunos na implantação, a classe não irá
     * exigir o inep da escola para importar os dados;
     *
     * @var boolean $lInepEscola
     */
    protected $lInepEscola = true;

    protected $iColunaNomeEscola = 5;

    /**
     * @var string $sTextoLog
     */
    public $sTextoLog;

    /**
     * Guarda todos os alunos importados
     * @var array
     */
    protected $aAlunosArquivo = array();

    /**
     * @var int $iTotalProcessados
     */
    private $iTotalProcessados;

    /**
     * @var bool
     */
    protected $lTemRegistroImportado = false;

    /**
     * @var string $sCabecalhoImportacaoAtualizados
     */
    protected $sCabecalhoImportacaoAtualizados;

    /**
     * @var string $sCabecalhoImportacaoNaoAtualizados
     */
    protected $sCabecalhoImportacaoNaoAtualizados;

    /**
     * @var bool $lTemInconsistencia
     */
    protected $lTemInconsistencia;

    /**
     * Funcao do construtor da classe
     * @param integer $iAnoEscolhido
     * @param integer $iCodigoInepEscola
     */
    public function __construct($iAnoEscolhido, $iCodigoInepEscola = null, $iCodigoLayout)
    {

        $oDaoSecParametros = db_utils::getdao('sec_parametros');
        $sSqlSecParametros = $oDaoSecParametros->sql_query("", "ed290_importcenso", "", "");
        $rsSecParametros = $oDaoSecParametros->sql_record($sSqlSecParametros);

        if ($iCodigoInepEscola == null) {
            $this->lInepEscola = false;
        } else {
            $this->iCodigoInepEscola = $iCodigoInepEscola;
        }

        $oFile = db_utils::postMemory($_FILES);
        $sCaminhoArquivo = $oFile->arquivo['tmp_name'];

        $this->lImportarAlunoAtivo          = db_utils::fieldsmemory($rsSecParametros, 0)->ed290_importcenso == 2 ? true : false;
        $this->sCaminhoArquivo              = $sCaminhoArquivo;

        $this->lTemInconsistencia           = false;
        $this->iAnoEscolhido                = $iAnoEscolhido;
        $this->iCodigoLayout                = $iCodigoLayout;

        if (empty($this->sNomeArquivoLog)) {
            $this->sNomeArquivoLog = "tmp/censo" . $this->iAnoEscolhido . "_importacao_" . db_getsession("DB_coddepto") . "_";
            $this->sNomeArquivoLog .= db_getsession("DB_id_usuario") . "_" . date("dmY") . "_" . date("His") . "_log.txt";
        }

        $this->pArquivoLog = fopen($this->sNomeArquivoLog, "w");
        if (!$this->pArquivoLog) {
            throw new Exception("Importação de Arquivo Censo abortada!\n Não foi possível abrir o arquivo de log!");
        }

        $this->aCaminhosArquivosFatiados    = $this->fatiaArquivos($sCaminhoArquivo);

        if (empty($this->sTextoLog)) {
            $this->sTextoLog                    = "";
        }

        if (empty($this->iTotalProcessados)) {
            $this->iTotalProcessados            = 0;
        }
    }

    /**
     * @param $sCaminhoArquivo
     * @return array
     * @throws Exception
     */
    private function fatiaArquivos($sCaminhoArquivo)
    {
        $aCaminhosArquivosFatiados = array();
        $sPastaUpload = 'tmp/importacenso/';

        if (!is_dir($sPastaUpload) && !mkdir($sPastaUpload, 0775)) {
            throw new Exception("Erro ao criar os diretórios para iniciar a importação do Censo.");
        }

        $sArquivo = file_get_contents($sCaminhoArquivo);

        $aLinhas = explode("\n", $sArquivo);
        $aLinhasEscola = array();
        $aEscolasArquivo = array();
        $sCabecalhoInconsistencia = '';

        foreach ($aLinhas as $indice => $linha) {
            $conteudo = explode('|', $linha);
            if (!empty($linha) && is_array($conteudo)) {
                if (isset($aLinhasEscola[$conteudo[1]])) {
                    $aLinhasEscola[$conteudo[1]] .= $linha .PHP_EOL;
                } else {
                    $aLinhasEscola[$conteudo[1]] = $linha .PHP_EOL;
                }

                if ($conteudo[0] == '00') {
                    $sNomeEscola = $conteudo[$this->iColunaNomeEscola];
                    $aEscolasArquivo[$conteudo[1]] = $sNomeEscola;

                    /**
                     * Verifica o ano no arquivo
                     */
                    $sData = $conteudo[7];
                    $aData = explode("/", $sData);

                    if (!empty($aData[2]) && $this->iAnoEscolhido != $aData[2]) {
                        $this->lTemInconsistencia = true;

                        if (trim($sCabecalhoInconsistencia) == '') {
                            $sCabecalhoInconsistencia = 'Inconsistências da importação:';
                            $this->log("{$sCabecalhoInconsistencia}\n\n");
                        }

                        $this->log("O registro da escola {$sNomeEscola} não pertence ao ano de {$this->iAnoEscolhido}\n");
                    }
                }

                if ($conteudo[0] == '60') {
                    $oDadosAluno = new stdClass();
                    $oDadosAluno->iCodigoUnicoAluno = $conteudo[2];
                    $oDadosAluno->sNomeAluno = $conteudo[4];

                    $this->aAlunosArquivo[$conteudo[1]][] = $oDadosAluno;
                }
            }
        }

        foreach ($aLinhasEscola as $codigo => $linha) {
            $sCaminhoArquivo = $sPastaUpload . $codigo . ".txt";

            $oCaminhoArquivoFatiado = new stdClass();
            $oCaminhoArquivoFatiado->iCodigo = $codigo;
            $oCaminhoArquivoFatiado->sEscola = $aEscolasArquivo[$codigo];
            $oCaminhoArquivoFatiado->sCaminho = $sCaminhoArquivo;
            $oCaminhoArquivoFatiado->aAlunos = array();

            foreach ($this->aAlunosArquivo[$codigo] as $oAluno) {
                $oCaminhoArquivoFatiado->aAlunos[] = $oAluno;
            }

            $aCaminhosArquivosFatiados[] = $oCaminhoArquivoFatiado;
            if (!file_put_contents($sCaminhoArquivo, $linha)) {
                throw new Exception("Erro ao criar os arquivos para iniciar a importação do Censo.");
            }
        }

        return $aCaminhosArquivosFatiados;
    }

    /**
     * @return array
     */
    public function getCaminhosArquivosFatiados()
    {
        return $this->aCaminhosArquivosFatiados;
    }

    /**
     * @param array $aCaminhosArquivosFatiados
     */
    public function setCaminhosArquivosFatiados($aCaminhosArquivosFatiados)
    {
        $this->aCaminhosArquivosFatiados = $aCaminhosArquivosFatiados;
    }

    /**
     * funcao que importa os dados do arquivo txt
     *
     */
    public function importarArquivo()
    {

        $this->validarImportacao();
        $this->validaArquivo();
        $aLinhasArquivo = $this->getLinhasArquivo();

        $this->importarRegistrosArquivo($aLinhasArquivo);

        $aArquivosFatiados      = $this->getCaminhosArquivosFatiados();
        $sUltimoArquivoFatiado  = end($aArquivosFatiados);

        if ($this->sCaminhoArquivo == $sUltimoArquivoFatiado->sCaminho) {
            $this->gerarLog();
        }
    }

    /**
     * escreve os valores da variavel de texto no .txt
     */
    public function gerarLog()
    {
        fwrite($this->pArquivoLog, $this->sTextoLog);
        fclose($this->pArquivoLog);
    }
    /**
     * @throws Exception
     */
    public function validarImportacao()
    {

        $sMsgErro = "Importação de Arquivo Censo abortada!\n";
        if (!db_utils::inTransaction()) {
            throw new Exception("Nenhuma transação do banco encontrada!");
        }

        if ($this->lIncluirAlunoNaoEncontrado) {
            if (empty($this->sCabecalhoImportacaoAtualizados)) {
                $this->sCabecalhoImportacaoAtualizados = "Registros atualizados na importação do Censo Escolar:\n\n";
                $this->log($this->sCabecalhoImportacaoAtualizados);
            }
        } else {
            if (empty($this->sCabecalhoImportacaoNaoAtualizados)) {
                $this->sCabecalhoImportacaoNaoAtualizados = "Registros não atualizados na importação do Censo Escolar:\n\n";
                $this->log($this->sCabecalhoImportacaoNaoAtualizados);
            }
        }
    }

    /**
     * função que valida o arquivo txt para importacao dos dados, verifica se o codigo inep é da escola
     * onde o usuario esta logado, e verifica também o ano atual
     */
    public function validaArquivo()
    {

        $sMsgErro = "Importação de Arquivo Censo abortada!\n";
        $pArquivoCenso = fopen($this->sCaminhoArquivo, "r");
        if (!$pArquivoCenso) {
            throw new Exception($sMsgErro . " Não foi possível abrir o arquivo para importação! ");
        }

        $sLinha = fgets($pArquivoCenso);
        $aLinha = explode("|", $sLinha);

        if ($aLinha[0] != "00") {
            fclose($pArquivoCenso);
            throw new Exception(" Arquivo informado não é um arquivo de exportação geral gerado pelo Educacenso! ");
        }

        $this->validaEscolaArquivo($aLinha);
        $this->validaAnoArquivo($aLinha);
        $this->validarRegistrosArquivo($pArquivoCenso);

        fclose($pArquivoCenso);
    }//fecha a funcao getPais

    /**
     * Busca o código INEP do departamento logado para validar se arquivo corresponde a escola atual
     * @param  array $aLinha dados da linha
     * @throws DBException
     * @throws Exception
     * @return boolean
     */
    protected function validaEscolaArquivo($aLinha)
    {

        $oDaoEscola = new cl_escola();
        $sSqlEscola = $oDaoEscola->sql_query(
            "",
            "ed18_c_codigoinep",
            "",
            "ed18_i_codigo = " . db_getsession("DB_coddepto")
        );
        $rsEscola = db_query($sSqlEscola);

        if (!$rsEscola) {
            throw new DBException("Erro ao buscar código INEP da escola.\n" . pg_last_error());
        }

        if (pg_num_rows($rsEscola) == 0) {
            throw new DBException("Não foi encontrado uma escola para o departamento logago.");
        }

        $iInepEscola = db_utils::fieldsMemory($rsEscola, 0)->ed18_c_codigoinep;

        if ($this->lInepEscola && ($aLinha[1] != $iInepEscola)) {
            throw new Exception(" Arquivo não pertence a esta escola, código inep diferente do que informado no arquivo! ");
        }

        return true;
    }

    /**
     * Refatorado validação do ano selecionado e o ano do arquivo importado
     * @param  array $aLinha dados da linha
     * @throws Exception
     * @return boolean
     */
    protected function validaAnoArquivo($aLinha)
    {

        $sData = $aLinha[3];
        $aData = explode("/", $sData);

        if (!empty($aData[2]) && $this->iAnoEscolhido != $aData[2]) {
            throw new Exception(" Arquivo informado não pertence ao ano de " . $this->iAnoEscolhido);
        }

        return true;
    }//fecha a funcao getPais

    /**
     * Refatorado validação dos registros dos arquivos separando em uma função
     * @param            $pArquivoCenso
     * @throws Exception
     * @return boolean
     */
    protected function validarRegistrosArquivo($pArquivoCenso)
    {

        rewind($pArquivoCenso);
        while (!feof($pArquivoCenso)) {
            $sLinha = fgets($pArquivoCenso);
            $aLinha = explode("|", $sLinha);

            if (empty($aLinha[0])) {
                continue;
            }

            if (!in_array($aLinha[0], static::$aTipoRegistro)) {
                fclose($pArquivoCenso);
                throw new Exception(" Arquivo informado não é valido, existe registro de código " . $aLinha[0] . " que é desconhecido");
            }
        }

        return true;
    } //fecha a funcao getDadosAluno

        /**
     * lê todas as linhas do layout cadastrado,
     * e le as linhas do layout de cada registro cadastrado
     */
    public function getLinhasArquivo()
    {

        $oDBLayoutReader = new DBLayoutReader($this->iCodigoLayout, $this->sCaminhoArquivo, $this->lLayoutComPipe);
        $aLinhasArquivo = $oDBLayoutReader->getLines();

        return $aLinhasArquivo;
    } //fecha a funcao getDadosRechumano

    /**
     * @param $aLinhasArquivo
     */
    public function importarRegistrosArquivo($aLinhasArquivo)
    {

        foreach ($aLinhasArquivo as $iIndLinha => $oLinha) {
            if ($this->lImportarEscola) {
                if ($oLinha->{$this->sCampoChave} == '00') {
                    $this->atualizaDadosEscola($oLinha);
                }

                if ($oLinha->{$this->sCampoChave} == "10") {
                    $this->atualizaDadosEscolaEstrutura($oLinha);
                }
            }

            if ($this->lImportarTurma) {
                if ($oLinha->{$this->sCampoChave} == "20") {
                    $this->atualizaDadosTurma($oLinha, $this->iAnoEscolhido);
                }
            }

            if ($this->lImportarDocente) {
                if ($oLinha->{$this->sCampoChave} == "30") {
                    $this->atualizaDadosDocente($oLinha);
                }

                if ($oLinha->{$this->sCampoChave} == "40") {
                    $this->atualizaEnderecoDocente($oLinha);
                }

                if ($oLinha->{$this->sCampoChave} == "50") {
                    $this->atualizaEscolaridadeDocente($oLinha);
                }
            }

            if ($this->lImportarAluno) {
                if ($oLinha->{$this->sCampoChave} == "60") {
                    $this->atualizaDadosAluno($oLinha);
                    $this->addTotalProcessados();
                }

                if ($oLinha->{$this->sCampoChave} == "70") {
                    $this->atualizaEnderecoDocumentosAluno($oLinha);
                    $this->getProgressBar()->updatePercentual($this->getTotalProcessados());
                }

                if ($oLinha->{$this->sCampoChave} == "80") {
                    $this->atualizaDadosEscolarizacaoAluno($oLinha);
                }
            }
        }
    } //fecha a funcao getDadosEscola

    /**
     * funcao que seleciona os dados do registro 00(dados da escola)do arquivo txt para
     * atualizar no banco de dados
     * @param DBLayoutLinha $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaDadosEscola(DBLayoutLinha $oLinha)
    {

        $oDaoEscola = db_utils::getDao('escola');
        $oDadosEscola = $this->getDadosEscola($oLinha);

        if ($oDadosEscola != null) {
            if ($oLinha->sit_funcionamento != ""
              && $oLinha->sit_funcionamento != trim($oDadosEscola->ed18_i_funcionamento)
            ) {
                $oDaoEscola->ed18_i_funcionamento = $oLinha->sit_funcionamento;
            }

            if ($oLinha->cep != ""
              && $oLinha->cep != trim($oDadosEscola->ed18_c_cep)
            ) {
                $oDaoEscola->ed18_c_cep = $oLinha->cep;
            }

            if ($oLinha->numero != ""
              && $oLinha->numero != trim($oDadosEscola->ed18_i_numero)
            ) {
                $oDaoEscola->ed18_i_numero = $oLinha->numero;
            }

            if ($oLinha->complemento != ""
              && $oLinha->complemento != trim($oDadosEscola->ed18_c_compl)
            ) {
                $oDaoEscola->ed18_c_complemento = $oLinha->complemento;
            }

            if ($oLinha->email_esc != ""
              && $oLinha->email_esc != trim($oDadosEscola->ed18_c_email)
            ) {
                $oDaoEscola->ed18_c_email = $oLinha->email_esc;
            }

            if ($oLinha->uf != ""
              && $oLinha->uf != trim($oDadosEscola->ed18_i_censouf)
            ) {
                $oDaoEscola->ed18_i_censouf = $oLinha->uf;
            }

            if ($oLinha->mun_esc != ""
              && $oLinha->mun_esc != trim($oDadosEscola->ed18_i_censomunic)
            ) {
                $oDaoEscola->ed18_i_censomunic = $oLinha->mun_esc;
            }

            if ($oLinha->distrito_esc != ""
              && $oLinha->distrito_esc != trim($oDadosEscola->ed18_i_censodistrito)
            ) {
                $oDaoCensoDistrito = db_utils::getdao('censodistrito');
                $sWhere = "ed262_i_censomunic = " . $oLinha->mun_esc;
                $sWhere .= " and ed262_i_coddistrito = " . $oLinha->distrito_esc;
                $sSqlCensoDistrito = $oDaoCensoDistrito->sql_query_file("", "ed262_i_codigo", "", $sWhere);
                $rsCensoDistrito = $oDaoCensoDistrito->sql_record($sSqlCensoDistrito);

                if ($oDaoCensoDistrito->numrows > 0) {
                    $oDaoEscola->ed18_i_censodistrito = db_utils::fieldsmemory($rsCensoDistrito, 0)->ed262_i_codigo;
                } else {
                    $oDaoEscola->ed18_i_censodistrito = "null";
                }
            }

            if ($oLinha->orgregensino != ""
              && $oLinha->orgregensino != trim($oDadosEscola->ed18_i_censoorgreg)
            ) {
                $oDaoCensoOrgReg = db_utils::getdao('censoorgreg');
                $sWhereCensoOrg = "ed263_i_censouf = " . $oLinha->uf . " and ed263_i_codigocenso = '" . $oLinha->orgregensino . "'";
                $sSqlOrgReg = $oDaoCensoOrgReg->sql_query("", "ed263_i_codigo", "", $sWhereCensoOrg);
                $rsCensoOrgReg = $oDaoCensoOrgReg->sql_record($sSqlOrgReg);

                if ($oDaoCensoOrgReg->numrows > 0) {
                    $oDaoEscola->ed18_i_censoorgreg = db_utils::fieldsmemory($rsCensoOrgReg, 0)->ed263_i_codigo;
                } else {
                    $oDaoEscola->ed18_i_censoorgreg = "null";
                }
            }

            if ($oLinha->local_esc != ""
              && $oLinha->local_esc != trim($oDadosEscola->ed18_c_local)
            ) {
                $oDaoEscola->ed18_c_local = $oLinha->local_escola;
            }

            if ($oLinha->catprivada_esc != ""
              && $oLinha->catprivada_esc != trim($oDadosEscola->ed18_i_categprivada)
            ) {
                $oDaoEscola->ed18_i_categprivada = $oLinha->catprivada_esc;
            }

            if ($oLinha->conveniada != ""
              && $oLinha->conveniada != trim($oDadosEscola->ed18_i_conveniada)
            ) {
                $oDaoEscola->ed18_i_conveniada = $oLinha->conveniada;
            }

            if ($oLinha->cnas != ""
              && $oLinha->cnas != trim($oDadosEscola->ed18_i_cnas)
            ) {
                $oDaoEscola->ed18_i_cnas = $oLinha->cnas;
            }

            if ($oLinha->cebas != ""
              && $oLinha->cebas != trim($oDadosEscola->ed18_i_cebas)
            ) {
                $oDaoEscola->ed18_i_cebas = $oLinha->cebas;
            }

            if ($oLinha->mant_empresa != ""
              && $oLinha->mant_empresa != trim($oDadosEscola->ed18_c_mantprivada)
            ) {
                $oDaoEscola->ed18_c_mantprivada = $oLinha->mant_empresa;
            }

            if ($oLinha->cnpj_escprivada != ""
              && $oLinha->cnpj_escprivada != trim($oDadosEscola->ed18_i_cnpj)
            ) {
                $oDaoEscola->ed18_i_cnpj = $oLinha->cnpj_escprivada;
            }

            if ($oLinha->bairro != "") {
                $oDaoBairro = db_utils::getdao('bairro');
                $sSqlBairro = $oDaoBairro->sql_query_file(
                    "",
                    "j13_codi",
                    "",
                    "to_ascii(j13_descr,'LATIN1') = '" . $oLinha->bairro . "'"
                );
                $rsBairro = $oDaoBairro->sql_record($sSqlBairro);

                if ($oDaoBairro->numrows > 0) {
                    $oDaoEscola->ed18_i_bairro = db_utils::fieldsmemory($rsBairro, 0)->j13_codi;
                } else {
                    $oDaoEscola->ed18_i_bairro = 0;
                }
            }

            if ($oLinha->credenciamento != ""
              && $oLinha->credenciamento != trim($oDadosEscola->ed18_i_credenciamento)
            ) {
                $oDaoEscola->ed18_i_credenciamento = $oLinha->credenciamento;
            }


            $oDaoEscola->ed18_i_codigo = $oDadosEscola->ed18_i_codigo;
            $oDaoEscola->alterar($oDadosEscola->ed18_i_codigo);

            if ($oDaoEscola->erro_status == '0') {
                throw new Exception("Erro na alteração dos dados da escola. Erro da classe: " . $oDaoEscola->erro_msg);
            }//fecha o if que verifica o erro_status
        } else { //fecha o if que verifica se é != null
            $sMsg = " Dados da Escola abaixo, informada no censo " . $this->iAnoEscolhido . ", não foram ";
            $sMsg .= " encontrados no sistema. \n";
            $sMsg .= " Escola: [" . $this->iCodigoInepEscola . "] " . $oDadosEscola->ed18_c_nome . "\n";
            $this->log($sMsg);
        }//fecha o else
    }

    /**
     * Funçã o que seleciona os dados da escola para utilizarmos nas funcoes
     * atualizaDadosEscola,atualizaDadosEscolaEstrutura
     * @param DBLayoutLinha $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @return object com os dados da escola caso tiver registro, caso contrario retorna null
     */
    public function getDadosEscola(DBLayoutLinha $oLinha)
    {

        $oDaoEscola = db_utils::getDao('escola');
        $sSqlEscola = $oDaoEscola->sql_query_file("", "*", "", "ed18_c_codigoinep = '" . $oLinha->inep_escola . "'");
        $rsEscola = $oDaoEscola->sql_record($sSqlEscola);

        if ($oDaoEscola->numrows > 0) {
            return db_utils::fieldsmemory($rsEscola, 0);
        } else {
            return null;
        } //fecha o else
    }

    /**
     * Funcao que escreve uma string no arquivo de log do censo
     * @param string $sMsg mensagem de log
     */
    public function log($sMsg)
    {
        $this->sTextoLog .= $sMsg;
    }

    /**
     * funcao que atualiza os dados de infra estrutura da escola (agua, esgoto, sala reuniao, banheiro,etc), registro 10
     * @param DBLayoutLinha $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaDadosEscolaEstrutura(DBLayoutLinha $oLinha)
    {

        $oDaoEscola = db_utils::getDao('escola');
        $oDaoEscolaEstrutura = db_utils::getDao('escolaestrutura');
        $oDadosEscola = $this->getDadosEscola($oLinha);

        if ($oDadosEscola != null) {
            if ($oLinha->educindigena != ""
              && $oLinha->educindigena != trim($oDadosEscola->ed18_i_educindigena)
            ) {
                $oDaoEscola->ed18_i_educindigena = $oLinha->educindigena;
            }

            if ($oLinha->linguaindigenaministrado != ""
              && $oLinha->linguaindigenaministrado != trim($oDadosEscola->ed18_i_tipolinguain)
            ) {
                $oDaoEscola->ed18_i_tipolinguain = $oLinha->linguaindigenaministrado;
            }

            if ($oLinha->linguaportministrado != ""
              && $oLinha->linguaportministrado != trim($oDadosEscola->ed18_i_tipolinguapt)
            ) {
                $oDaoEscola->ed18_i_tipolinguapt = $oLinha->linguaportministrado;
            }

            if ($oLinha->codigoindigena != ""
              && $oLinha->codigoindigena != trim($oDadosEscola->ed18_i_linguaindigena)
            ) {
                $oDaoEscola->ed18_i_linguaindigena = $oLinha->codigoindigena;
            }

            if ($oLinha->localdifescola != ""
              && $oLinha->localdifescola != trim($oDadosEscola->ed18_i_locdiferenciada)
            ) {
                $oDaoEscola->ed18_i_locdiferenciada = $oLinha->localdifescola;
            }

            $oDaoEscola->ed18_i_codigo = $oDadosEscola->ed18_i_codigo;
            $oDaoEscola->alterar($oDadosEscola->ed18_i_codigo);

            if ($oDaoEscola->erro_status == '0') {
                throw new Exception("Erro na alteração dos dados da escola. Erro da classe: " . $oDaoEscola->erro_msg);
            }

            $sWhereEstrutura = "ed255_i_escola = " . $oDaoEscola->ed18_i_codigo;
            $sSqlEscolaEstrutura = $oDaoEscolaEstrutura->sql_query("", "*", "", $sWhereEstrutura);
            $rsEscolaEstrutura = $oDaoEscolaEstrutura->sql_record($sSqlEscolaEstrutura);

            if ($oDaoEscolaEstrutura->numrows > 0) {
                $oDadosEscolaEstrutura = db_utils::fieldsmemory($rsEscolaEstrutura, 0);

                if ($oLinha->prediocompartilhado != ""
                  && $oLinha->prediocompartilhado != trim($oDadosEscolaEstrutura->ed255_i_compartilhado)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_compartilhado = $oLinha->prediocompartilhado;
                }

                if (isset($oLinha->escolacompartilha1) && $oLinha->escolacompartilha1 != ''
                  && isset($oLinha->escolacompartilha2) && $oLinha->escolacompartilha2 != ''
                  && isset($oLinha->escolacompartilha3) && $oLinha->escolacompartilha3 != ''
                  && isset($oLinha->escolacompartilha4) && $oLinha->escolacompartilha4 != ''
                  && isset($oLinha->escolacompartilha5) && $oLinha->escolacompartilha5 != ''
                  && isset($oLinha->escolacompartilha6) && $oLinha->escolacompartilha6 != ''
                ) {
                    $sEscolaCompartilhada = $oLinha->escolacompartilha1;
                    $sEscolaCompartilhada .= $oLinha->escolacompartilha2;
                    $sEscolaCompartilhada .= $oLinha->escolacompartilha3;
                    $sEscolaCompartilhada .= $oLinha->escolacompartilha4;
                    $sEscolaCompartilhada .= $oLinha->escolacompartilha5;
                    $sEscolaCompartilhada .= $oLinha->escolacompartilha6;
                    $sEscolaCompartilhada .= $oLinha->escolacompartilha7;

                    $oDaoEscolaEstrutura->ed255_i_escolacompartilhada = $sEscolaCompartilhada;
                }


                if ($oLinha->salaexistentes != ""
                  && $oLinha->salaexistentes != trim($oDadosEscolaEstrutura->ed255_i_salaexistente)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_salaexistente = $oLinha->salaexistentes;
                }

                if ($oLinha->salautilizada != ""
                  && $oLinha->salautilizada != trim($oDadosEscolaEstrutura->ed255_i_salautilizada)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_salautilizada = $oLinha->salautilizada;
                }


                if (isset($oLinha->aguaredepublica) && $oLinha->aguaredepublica != ''
                  && isset($oLinha->aguapocoartesiano) && $oLinha->aguapocoartesiano != ''
                  && isset($oLinha->aguacacimba) && $oLinha->aguacacimba != ''
                  && isset($oLinha->aguafonte) && $oLinha->aguafonte != ''
                  && isset($oLinha->aguainexistente) && $oLinha->aguainexistente != ''
                ) {
                    $sAbastecimentoAgua = $oLinha->aguaredepublica;
                    $sAbastecimentoAgua .= $oLinha->aguapocoartesiano;
                    $sAbastecimentoAgua .= $oLinha->aguacacimba;
                    $sAbastecimentoAgua .= $oLinha->aguafonte;
                    $sAbastecimentoAgua .= $oLinha->aguainexistente;

                    $oDaoEscolaEstrutura->ed255_c_abastagua = $sAbastecimentoAgua;
                }

                if (isset($oLinha->eletricaredepublica) && $oLinha->eletricaredepublica != ''
                  && isset($oLinha->eletricagerador) && $oLinha->eletricagerador != ''
                  && isset($oLinha->eletricaoutros) && $oLinha->eletricaoutros != ''
                  && isset($oLinha->eletricainexistente) && $oLinha->eletricainexistente != ''
                ) {
                    $sEnergia = $oLinha->eletricaredepublica;
                    $sEnergia .= $oLinha->eletricagerador;
                    $sEnergia .= $oLinha->eletricaoutros;
                    $sEnergia .= $oLinha->eletricainexistente;

                    $oDaoEscolaEstrutura->ed255_c_abastenergia = $sEnergia;
                }


                if ($oLinha->aguaconsaluno != ""
                  && $oLinha->aguaconsaluno != trim($oDadosEscolaEstrutura->ed255_i_aguafiltrada)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_aguafiltrada = $oLinha->aguaconsaluno;
                }


                if (isset($oLinha->esgotoredepublica) && $oLinha->esgotoredepublica != ''
                  && isset($oLinha->esgotofossa) && $oLinha->esgotofossa != ''
                  && isset($oLinha->esgotoinexistente) && $oLinha->esgotoinexistente != ''
                ) {
                    $sEsgoto = $oLinha->esgotoredepublica;
                    $sEsgoto .= $oLinha->esgotofossa;
                    $sEsgoto .= $oLinha->esgotoinexistente;

                    $oDaoEscolaEstrutura->ed255_c_esgotosanitario = $sEsgoto;
                }

                if (isset($oLinha->destinolixocoleta) && $oLinha->destinolixocoleta != ''
                  && isset($oLinha->destinolixoqueima) && $oLinha->destinolixoqueima != ''
                  && isset($oLinha->destinolixojogaarea) && $oLinha->destinolixojogaarea != ''
                  && isset($oLinha->destinolixorecicla) && $oLinha->destinolixorecicla != ''
                  && isset($oLinha->destinolixoenterra) && $oLinha->destinolixoenterra != ''
                  && isset($oLinha->destinolixooutros) && $oLinha->destinolixooutros != ''
                ) {
                    $sLixo = $oLinha->destinolixocoleta;
                    $sLixo .= $oLinha->destinolixoqueima;
                    $sLixo .= $oLinha->destinolixojogaarea;
                    $sLixo .= $oLinha->destinolixorecicla;
                    $sLixo .= $oLinha->destinolixoenterra;
                    $sLixo .= $oLinha->destinolixooutros;

                    $oDaoEscolaEstrutura->ed255_c_destinolixo = $sLixo;
                }

                if (isset($oLinha->local_predioescolar) && $oLinha->local_predioescolar != ''
                  && isset($oLinha->local_temploigreja) && $oLinha->local_temploigreja != ''
                  && isset($oLinha->local_salaempresa) && $oLinha->local_salaempresa != ''
                  && isset($oLinha->local_casaprofessor) && $oLinha->local_casaprofessor != ''
                  && isset($oLinha->local_salaoutraescola) && $oLinha->local_salaoutraescola != ''
                  && isset($oLinha->local_galpao) && $oLinha->local_galpao != ''
                  && isset($oLinha->local_internacaoprisional) && $oLinha->local_internacaoprisional != ''
                  && isset($oLinha->local_outros) && $oLinha->local_outros != ''
                ) {
                    $sLocalizacao = $oLinha->local_predioescolar;
                    $sLocalizacao .= $oLinha->local_temploigreja;
                    $sLocalizacao .= $oLinha->local_salaempresa;
                    $sLocalizacao .= $oLinha->local_casaprofessor;
                    $sLocalizacao .= $oLinha->local_salaoutraescola;
                    $sLocalizacao .= $oLinha->local_galpao;
                    $sLocalizacao .= $oLinha->local_internacaoprisional;
                    $sLocalizacao .= $oLinha->local_outros;

                    $oDaoEscolaEstrutura->ed255_c_localizacao = $sLocalizacao;
                }

                if (isset($oLinha->dependenciasdiretoria) && $oLinha->dependenciasdiretoria != ''
                  && isset($oLinha->depsalaprof) && $oLinha->depsalaprof != ''
                  && isset($oLinha->depsalainf) && $oLinha->depsalainf != ''
                  && isset($oLinha->deplabciencias) && $oLinha->deplabciencias != ''
                  && isset($oLinha->depaee) && $oLinha->depaee != ''
                  && isset($oLinha->depquadracob) && $oLinha->depquadracob != ''
                  && isset($oLinha->depquadradescob) && $oLinha->depquadradescob != ''
                  && isset($oLinha->depcozinha) && $oLinha->depcozinha != ''
                  && isset($oLinha->depbiblioteca) && $oLinha->depbiblioteca != ''
                  && isset($oLinha->depsalaleitura) && $oLinha->depsalaleitura != ''
                  && isset($oLinha->depparqueinfantil) && $oLinha->depparqueinfantil != ''
                  && isset($oLinha->depbercario) && $oLinha->depbercario != ''
                  && isset($oLinha->depsanitariofora) && $oLinha->depsanitariofora != ''
                  && isset($oLinha->depsanitariodentro) && $oLinha->depsanitariodentro != ''
                  && isset($oLinha->depsanitarioadequado) && $oLinha->depsanitarioadequado != ''
                  && isset($oLinha->depsanitariodef) && $oLinha->depsanitariodef != ''
                  && isset($oLinha->depmobilidade) && $oLinha->depmobilidade != ''
                  && isset($oLinha->depnenhuma) && $oLinha->depnenhuma != ''
                ) {
                    $sDependencia = $oLinha->dependenciasdiretoria;
                    $sDependencia .= $oLinha->depsalaprof;
                    $sDependencia .= $oLinha->depsalainf;
                    $sDependencia .= $oLinha->deplabciencias;
                    $sDependencia .= $oLinha->depaee;
                    $sDependencia .= $oLinha->depquadracob;
                    $sDependencia .= $oLinha->depquadradescob;
                    $sDependencia .= $oLinha->depcozinha;
                    $sDependencia .= $oLinha->depbiblioteca;
                    $sDependencia .= $oLinha->depsalaleitura;
                    $sDependencia .= $oLinha->depparqueinfantil;
                    $sDependencia .= $oLinha->depbercario;
                    $sDependencia .= $oLinha->depsanitariofora;
                    $sDependencia .= $oLinha->depsanitariodentro;
                    $sDependencia .= $oLinha->depsanitarioadequado;
                    $sDependencia .= $oLinha->depsanitariodef;
                    $sDependencia .= $oLinha->depmobilidade;
                    $sDependencia .= $oLinha->depnenhuma;

                    $oDaoEscolaEstrutura->ed255_c_dependencias = $sDependencia;
                }

                if (isset($oLinha->equipaparelhotv) && $oLinha->equipaparelhotv != ''
                  && isset($oLinha->equipvideocassete) && $oLinha->equipvideocassete != ''
                  && isset($oLinha->equipdvd) && $oLinha->equipdvd != ''
                  && isset($oLinha->equipantena) && $oLinha->equipantena != ''
                  && isset($oLinha->equipcopiadora) && $oLinha->equipcopiadora != ''
                  && isset($oLinha->equipretroprojetor) && $oLinha->equipretroprojetor != ''
                  && isset($oLinha->equipimpressora) && $oLinha->equipimpressora != ''
                ) {
                    $sEquipamento = $oLinha->equipaparelhotv;
                    $sEquipamento .= $oLinha->equipvideocassete;
                    $sEquipamento .= $oLinha->equipdvd;
                    $sEquipamento .= $oLinha->equipantena;
                    $sEquipamento .= $oLinha->equipcopiadora;
                    $sEquipamento .= $oLinha->equipretroprojetor;
                    $sEquipamento .= $oLinha->equipimpressora;

                    $oDaoEscolaEstrutura->ed255_c_equipamentos = $sEquipamento;
                }


                if ($oLinha->equipcomputador != ""
                  && $oLinha->equipcomputador != trim($oDadosEscolaEstrutura->ed255_i_computadores)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_computadores = $oLinha->equipcomputador;
                }

                if ($oLinha->qtdcomp != ""
                  && $oLinha->qtdcomp != trim($oDadosEscolaEstrutura->ed255_i_qtdcomp)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_qtdcomp = $oLinha->qtdcomp;
                }

                if ($oLinha->qtdcompadm != ""
                  && $oLinha->qtdcompadm != trim($oDadosEscolaEstrutura->ed255_i_qtdcompadm)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_qtdcompadm = $oLinha->qtdcompadm;
                }

                if ($oLinha->qtdcompaluno != ""
                  && $oLinha->qtdcompaluno != trim($oDadosEscolaEstrutura->ed255_i_qtdcompalu)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_qtdcompalu = $oLinha->qtdcompaluno;
                }

                if ($oLinha->internet != ""
                  && $oLinha->internet != trim($oDadosEscolaEstrutura->ed255_i_internet)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_internet = $oLinha->internet;
                }

                if ($oLinha->bandalarga != ""
                  && $oLinha->bandalarga != trim($oDadosEscolaEstrutura->ed255_i_bandalarga)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_bandalarga = $oLinha->bandalarga;
                }

                if ($oLinha->alimentacao != ""
                  && $oLinha->alimentacao != trim($oDadosEscolaEstrutura->ed255_i_alimentacao)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_alimentacao = $oLinha->alimentacao;
                }

                if ($oLinha->ativcomplementar != ""
                  && $oLinha->ativcomplementar != trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_ativcomplementar = $oLinha->ativcomplementar;
                }

                if ($oLinha->atendaee != ""
                  && $oLinha->atendaee != trim($oDadosEscolaEstrutura->ed255_i_aee)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_aee = $oLinha->atendaee;
                }

                if ($oLinha->ensinoorgciclo != ""
                  && $oLinha->ensinoorgciclo != trim($oDadosEscolaEstrutura->ed255_i_efciclos)
                ) {
                    $oDaoEscolaEstrutura->ed255_i_efciclos = $oLinha->ensinoorgciclo;
                }

                if (isset($oLinha->matdid) && $oLinha->matdid != ''
                  && isset($oLinha->matdidquilomba) && $oLinha->matdidquilomba != ''
                  && isset($oLinha->matdidindigena) && $oLinha->matdidindigena != ''
                ) {
                    $sMaterialDidatico = $oLinha->matdid;
                    $sMaterialDidatico .= $oLinha->matdidquilomba;
                    $sMaterialDidatico .= $oLinha->matdidindigena;

                    $oDaoEscolaEstrutura->ed255_c_materdidatico = $sMaterialDidatico;
                }

                if (isset($oLinha->formaocupacao) && trim($oDadosEscolaEstrutura->ed255_i_formaocupacao)) {
                    $oDaoEscolaEstrutura->ed255_i_formaocupacao = $oLinha->formaocupacao;
                }

                $oDaoEscolaEstrutura->ed255_i_codigo = $oDadosEscolaEstrutura->ed255_i_codigo;
                $oDaoEscolaEstrutura->alterar($oDaoEscolaEstrutura->ed255_i_codigo);

                if ($oDaoEscolaEstrutura->erro_status == '0') {
                    throw new Exception(
                        "Erro na alteração dos dados da infra estrutura da escola. Erro da classe: " . $oDaoEscolaEstrutura->erro_msg
                    );
                } //fecha o if que verifica o err_status
            } //fecha o if que verifica $oDaoEscolaEstrutura->numrows > 0
        } else {//fecha o if que verifica se $oDadosEscola != null
            $sMsg = " Escola " . $oDadosEscola->ed18_c_nome . " não encontrada, impossível atualizar dados da estrutura!\n";
            $this->log($sMsg);
        }//fecha o else
    }

    /**
     * funcao que seleciona os dados da turma (nome, modalidade, tipo de atendimento),registro 20
     * e atualiza os se forem diferentes dos encontrados no banco de dados
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaDadosTurma($oLinha)
    {

        $sNomeTurmaCensoNovo = $oLinha->nometurma;
        $iCodigoInepTurma = trim($oLinha->codigoinepturma);
        $iTipoAtendimento = trim($oLinha->tpatend);
        $iEtapa = trim($oLinha->etapaensino);

        if ($oLinha->tpatend == 0 || $oLinha->tpatend == 1
          || $oLinha->tpatend == 2 || $oLinha->tpatend == 3
        ) {
            $oDaoTurma = db_utils::getdao('turma');
            $sWhereTurma = "";

            if (isset($oLinha->nometurma) && !empty($oLinha->nometurma)) {
                $sWhereTurma .= "translate(ed57_c_descr, 'áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙãÃê', 'aeiouAEIOUaeiouAEIOUaAe') = '";
                $sWhereTurma .= $oLinha->nometurma . "' ";
            }

            if (isset($oLinha->codigoturma) && $oLinha->codigoturma != "") {
                $sWhereTurma .= (empty($sWhereTurma) ? "" : " AND ");
                $sWhereTurma .= "      ed57_i_codigo = " . $oLinha->codigoturma;
            }

            $sWhereTurma .= "      AND ed57_i_tipoatend = $iTipoAtendimento ";
            $sWhereTurma .= "      AND ed52_i_ano = " . $this->iAnoEscolhido;

            if (isset($oLinha->modalidade) && !empty($oLinha->modalidade)) {
                $sWhereTurma .= (empty($sWhereTurma) ? "" : " AND ");
                $sWhereTurma .= "      ed10_i_tipoensino = " . trim($oLinha->modalidade);
            }

            $sWhereTurma .= "      AND ed18_c_codigoinep = '" . $this->iCodigoInepEscola . "'";
            $sSqlTurma = $oDaoTurma->sql_query_censo("", "ed57_i_codigo", "", $sWhereTurma);
            $rsTurma = $oDaoTurma->sql_record($sSqlTurma);

            if ($oDaoTurma->numrows == 0) {
                $sMsg = "TURMA: [" . $this->iCodigoInepEscola . "] " . $sNomeTurmaCensoNovo;
                $sMsg .= " não foi encontrada no sistema.\n";
                $this->log($sMsg);
            } else {
                $oDadosTurma = db_utils::fieldsmemory($rsTurma, 0);
                if (!empty($iCodigoInepTurma)) {
                    $oDaoTurma->ed57_i_codigoinep = $iCodigoInepTurma;
                    $oDaoTurma->ed57_i_codigo = $oDadosTurma->ed57_i_codigo;
                    $oDaoTurma->alterar($oDadosTurma->ed57_i_codigo);

                    if ($oDaoTurma->erro_status == '0') {
                        throw new Exception("Erro na alteração dos dados da Turma. Erro da classe: " . $oDaoTurma->erro_msg);
                    } //fecha o if do erro_status
                }//fecha o if (trim($this->iCodigoInepEscola) != "")
            } //fecha o else
        } else if ($oLinha->tpatend == 4 || $oLinha->tpatend == 5) {
            $oDaoTurmaac = db_utils::getdao('turmaac');
            $sWhereTurmaAc = "";

            if (isset($oLinha->nometurma) && !empty($oLinha->nometurma)) {
                $sWhereTurmaAc = "translate(to_ascii(ed268_c_descr, 'LATIN1'),' ','') = '";
                $sWhereTurmaAc .= str_replace(" ", "", $sNomeTurmaCensoNovo) . "' ";
            }

            if (isset($iCodigoTurma) && $iCodigoTurma != "") {
                $sWhereTurmaAc .= (empty($sWhereTurmaAc) ? "" : " AND ");
                $sWhereTurmaAc .= "      ed268_i_codigo = $iCodigoTurma";
            }

            $sWhereTurmaAc .= "      AND ed268_i_tipoatend = " . $iTipoAtendimento;
            $sWhereTurmaAc .= "      AND ed52_i_ano = " . $this->iAnoEscolhido;
            $sWhereTurmaAc .= "      AND ed18_c_codigoinep = '" . $this->iCodigoInepEscola . "'";
            $sSqlTurmaac = $oDaoTurmaac->sql_query_censo("", "*", "", $sWhereTurmaAc);
            $rsTurmaac = $oDaoTurmaac->sql_record($sSqlTurmaac);

            if ($oDaoTurmaac->numrows == 0) {
                $sMsg = "TURMA: [" . $this->iCodigoInepEscola . "] " . $sNomeTurmaCensoNovo;
                $sMsg .= " não foi encontrada no sistema.\n";
                $this->log($sMsg);
            } else {
                $oDadosTurmaac = db_utils::fieldsmemory($rsTurmaac, 0);

                if (trim($this->iCodigoInepEscola) != "") {
                    $oDaoTurmaac->ed268_i_codigoinep = $oDadosTurmaac->ed268_i_codigoinep;

                    if ($oLinha->freqsemanal != ""
                      && $oLinha->freqsemanal != trim($oDadosTurmaac->ed268_i_ativqtd)
                    ) {
                        $oDaoTurmaac->ed268_i_ativqtd = $oLinha->freqsemanal;
                    }

                    if ($oLinha->ensinobraile != ""
                      && $oLinha->ensinobraile != trim($oDadosTurmaac->ed268_c_aee)
                    ) {
                        $oDaoTurmaac->ed268_c_aee = $oLinha->ensinobraile;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->ensinoptico;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->estratprocmentais;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->tecorientmob;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->libras;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->ensinocaa;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->enriquecimentocurricular;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->soroban;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->ensinoifacessivel;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->ensinoportuguesa;
                        $oDaoTurmaac->ed268_c_aee .= $oLinha->autonomiaambescolar;
                    }

                    $oDaoTurmaac->ed268_i_codigo = $oDadosTurmaac->ed268_i_codigo;
                    $oDaoTurmaac->alterar($oDadosTurmaac->ed268_i_codigo);

                    if ($oDaoTurmaac->erro_status == '0') {
                        throw new Exception(
                            "Erro na alteração dos dados da Turma de atendimento especial. Erro da classe: " . $oDaoTurmaac->erro_msg
                        );
                    } //fecha if $oDaoTurmaac->erro_status == '0'
                } //fecha if que verifica $codigoinep_turmacenso
            } //fecha o else
        } //fecha o elseif tipoatend ==4 e ==5
    } //fecha a funcao atualizaDadosEscola

    /**
     * Funcao que seleciona os dados do docente no arquivo txt, registro 30, e atualiza - os  no banco
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaDadosDocente($oLinha)
    {

        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);

        if ($aDadosRechumano != null) {
            $iTam = count($aDadosRechumano);

            for ($iCont = 0; $iCont < $iTam; $iCont++) {
                if ($this->lImportarDocente) {
                    if (trim($aDadosRechumano[$iCont]->vinculo_escola) != trim($this->iCodigoInepEscola)) {
                        $sMsg = "Recurso Humano [" . $aDadosRechumano[$iCont]->ed20_i_codigoinep . "] " . $aDadosRechumano[$iCont]->z01_nome;
                        $sMsg .= ": Recurso Humano não está mais vinculado a esta escola.\n";
                        $this->log($sMsg);

                        return;
                    } //fecha if $oDadosAluno->vinculo_escola) != trim($this->iCodigoInepEscola
                } //fecha o if $this->lImportarAlunoAtivo

                $oDaoRechumano = db_utils::getdao('rechumano');
                $oDaoRechumano->ed20_i_pais = "";

                if (isset($oLinha->inepdocente)
                  && $oLinha->inepdocente != trim($aDadosRechumano[$iCont]->ed20_i_codigoinep)
                ) {
                    $oDaoRechumano->ed20_i_codigoinep = $oLinha->inepdocente;
                }

                if ($oLinha->nis != ""
                  && $oLinha->nis != trim($aDadosRechumano[$iCont]->ed20_c_nis)
                ) {
                    $oDaoRechumano->ed20_c_nis = $oLinha->nis;
                }

                if ($oLinha->raca != ""
                  && $oLinha->raca != trim($aDadosRechumano[$iCont]->ed20_i_raca)
                ) {
                    $oDaoRechumano->ed20_i_raca = $oLinha->raca;
                }

                if ($oLinha->nacionalidade != ""
                  && $oLinha->nacionalidade != trim($aDadosRechumano[$iCont]->ed20_i_nacionalidade)
                ) {
                    $oDaoRechumano->ed20_i_nacionalidade = $oLinha->nacionalidade;
                }

                if (!empty($oLinha->pais)
                  && $oLinha->pais != trim($aDadosRechumano[$iCont]->ed228_i_paisonu)
                ) {
                    $oDaoRechumano->ed20_i_pais = $this->getPais($oLinha->pais);
                }

                if ($oLinha->ufnasc != ""
                  && $oLinha->ufnasc != trim($aDadosRechumano[$iCont]->ed20_i_censoufnat)
                ) {
                    $oDaoRechumano->ed20_i_censoufnat = $oLinha->ufnasc;
                }

                if ($oLinha->municnasc != ""
                  && $oLinha->municnasc != trim($aDadosRechumano[$iCont]->ed20_i_censomunicnat)
                ) {
                    $oDaoRechumano->ed20_i_censomunicnat = $oLinha->municnasc;
                }

                $oDaoRechumano->ed20_i_codigo = $aDadosRechumano[$iCont]->ed20_i_codigo;
                $oDaoRechumano->alterar($aDadosRechumano[$iCont]->ed20_i_codigo);

                if ($oDaoRechumano->erro_status == '0') {
                    throw new Exception("Erro na alteração dos dados do Rechumano. Erro da classe: " . $oDaoRechumano->erro_msg);
                } //fecha if do erro_status
            }//fecha o for
        } else {//fecha o if $oDadosRechumano != null
            $sMsg = "Docente: [" . $oLinha->inepdocente . "] " . $oLinha->nomedocente;
            $sMsg .= " não foi encontrado no sistema.\n";
            $this->log($sMsg);
        }//fecha o for
    }//fecha a funcao atualizaDadosEscolaEstrutura

    /**
     *
     * Funcao que seleciona os recursos humanos para utilizarmos o mesmo sql nas funcoes
     * atualizaDadosDocente, atualizaEnderecoDocente,atualizaEscolarizacaoDocente
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @return object com os dados do rechumano caso tiver registro, caso contrario retorna null
     */
    public function getMatriculasRechumano($oLinha)
    {

        $iCodDocenteEsc = trim($oLinha->codigodocenteescola);

        $oDaoRechumano = db_utils::getdao('rechumano');
        $sCampos = 'rechumano.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola, ';
        $sCampos .= 'case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome';
        $sWhereRechumano = "";

        if (isset($oLinha->inepdocente) && !empty($oLinha->inepdocente)) {
            $sWhereRechumano .= "ed20_i_codigoinep = " . $oLinha->inepdocente;
        } else if (isset($oLinha->codigodocenteescola) && !empty($oLinha->codigodocenteescola)) {
            $sWhereRechumano .= (empty($sWhereRechumano) ? "" : " AND ");
            $sWhereRechumano .= "cgmrh.z01_numcgm = $iCodDocenteEsc";
        }

        if (!empty($sWhereRechumano)) {
            $sSqlRechumano = $oDaoRechumano->sql_query_censomodel(
                "",
                $sCampos,
                "vinculo_escola DESC",
                $sWhereRechumano
            );
            $rsRechumano = $oDaoRechumano->sql_record($sSqlRechumano);
        }

        /* Nao encontrou o docente pelo codigo inep, entao tenta encontrar pelo nome, data de nascimento de nome da mae */
        if ($oDaoRechumano->numrows <= 0 && isset($oLinha->nomedocente)
          && isset($oLinha->nascdocente) && isset($oLinha->mae)
        ) {
            $sNomeDocenteCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nomedocente);
            $dNascDocente = $this->formataData($oLinha->nascdocente);
            $sMaeDocenteCenso = str_replace(array('ª', 'º'), array('', ''), $oLinha->mae);
            $sWhereRechumano = " ed18_c_codigoinep = '" . $this->iCodigoInepEscola . "'";
            $sWhereRechumano .= " AND ( ";
            $sWhereRechumano .= " ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
            $sWhereRechumano .= "                  cgmcgm.z01_nome end, 'LATIN1') = '$sNomeDocenteCensoNovo'  ";
            $sWhereRechumano .= "                  OR to_ascii(case when ed20_i_tiposervidor = 1 then ";
            $sWhereRechumano .= "                     cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end ";
            $sWhereRechumano .= "                     ) = '$sNomeDocenteCensoNovo') AND case when ";
            $sWhereRechumano .= "                     ed20_i_tiposervidor = 1 then cgmrh.z01_nasc else ";
            $sWhereRechumano .= "                     cgmcgm.z01_nasc end = '$dNascDocente') ";
            $sWhereRechumano .= " OR ";
            $sWhereRechumano .= " ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
            $sWhereRechumano .= " cgmcgm.z01_nome end) = '$sNomeDocenteCensoNovo' OR to_ascii(case when ";
            $sWhereRechumano .= " ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end ";
            $sWhereRechumano .= " ) = '$sNomeDocenteCensoNovo') AND to_ascii(case when ed20_i_tiposervidor = 1 ";
            $sWhereRechumano .= "  then cgmrh.z01_mae else cgmcgm.z01_mae end) = '$sMaeDocenteCenso') ";
            $sWhereRechumano .= " OR ";
            $sWhereRechumano .= " ((to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
            $sWhereRechumano .= "            cgmcgm.z01_nome end) = '$sNomeDocenteCensoNovo' OR ";
            $sWhereRechumano .= "            to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple ";
            $sWhereRechumano .= "              else cgmcgm.z01_nomecomple end) = '$sNomeDocenteCensoNovo')) ";
            $sWhereRechumano .= " ) ";
            $sSqlRechumano = $oDaoRechumano->sql_query_censomodel("", $sCampos, "", $sWhereRechumano);
            $rsRechumano = $oDaoRechumano->sql_record($sSqlRechumano);
        }

        if ($oDaoRechumano->numrows > 0) {
            return $aDadosRechumano = db_utils::getCollectionByRecord($rsRechumano, false, false, false);
        } else {
            return null;
        } //fecha o else
    } //fecha a funcao atualizaDadosTurma

    /**
     * * Funcao que altera a formatacao da data
     * @param string $dData
     * @return string
     */
    public static function formataData($dData)
    {

        if (count(explode("-", $dData)) > 1
          || count(explode("/", $dData)) > 1
        ) {
            return substr($dData, 6, 4) . "-" . substr($dData, 3, 2) . "-" . substr($dData, 0, 2);
        } else {
            return substr($dData, 4, 4) . "-" . substr($dData, 2, 2) . "-" . substr($dData, 0, 2);
        }
    } //fecha a funcao atualizaDadosDocente

    /**
     * Funcao que seleciona o codigo  do pais para utilizarmos na inclusao do aluno e do docente(RecHumano)
     * @param integer $iCodPaisCenso
     * @return int
     */
    public function getPais($iCodPaisCenso)
    {

        $oDaoPais = db_utils::getdao('pais');
        $sWhere = "ed228_i_paisonu = " . $iCodPaisCenso;
        $sSqlPais = $oDaoPais->sql_query_file("", "ed228_i_codigo", "", $sWhere);
        $rsPais = $oDaoPais->sql_record($sSqlPais);

        if ($oDaoPais->numrows > 0) {
            return $iDadospais = db_utils::fieldsmemory($rsPais, 0)->ed228_i_codigo;
        } else {
            return $iDadosPais = 10; //Código do Brasil?!
        }//fecha o else
    } //fecha a funcao atualizaEnderecoDocente

    /**
     * funcao que seleciona e atualiza os dados de endereco, bairro, cep, do docente , registro 40
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaEnderecoDocente($oLinha)
    {

        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);

        if ($aDadosRechumano != null) {
            $iTam = count($aDadosRechumano);

            for ($iCont = 0; $iCont < $iTam; $iCont++) {
                $oDaoRechumano = db_utils::getdao('rechumano');
                $oDaoRechumano->ed20_i_pais = "";

                if ($oLinha->uf != ""
                  && $oLinha->uf != trim($aDadosRechumano[$iCont]->ed20_i_censoufender)
                ) {
                    $oDaoRechumano->ed20_i_censoufender = $oLinha->uf;
                }

                if ($oLinha->munic != ""
                  && $oLinha->munic != trim($aDadosRechumano[$iCont]->ed20_i_censomunicender)
                ) {
                    $oDaoRechumano->ed20_i_censomunicender = $oLinha->munic;
                }

                $oDaoRechumano->ed20_i_codigo = $aDadosRechumano[$iCont]->ed20_i_codigo;
                $oDaoRechumano->alterar($aDadosRechumano[$iCont]->ed20_i_codigo);

                if ($oDaoRechumano->erro_status == '0') {
                    throw new Exception("Erro na alteração dos dados do rechumano. Erro da classe: " . $oDaoRechumano->erro_msg);
                } //fecha o if do erro_status
            } //fecha o for
        } //fecha o if que verifica $oDadosRechumano
    } //fecha a funcao atualizaEscolaridadeDocente

    /**
     * funcao que seleciona  e atualiza os dados de escolaridade do docente, registro 50
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaEscolaridadeDocente($oLinha)
    {

        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);

        if ($aDadosRechumano != null) {
            $iTam = count($aDadosRechumano);

            for ($iCont = 0; $iCont < $iTam; $iCont++) {
                $oDaoRechumano = db_utils::getdao('rechumano');
                $oDaoRechumano->ed20_i_pais = "";

                if (isset($oLinha->escolaridade) && !empty($oLinha->escolaridade)
                  && $oLinha->escolaridade != trim($aDadosRechumano[$iCont]->ed20_i_escolaridade)
                ) {
                    $oDaoRechumano->ed20_i_escolaridade = $oLinha->escolaridade;
                }

                if (isset($oLinha->especializacao) && $oLinha->especializacao != ''
                  && isset($oLinha->mestrado) && $oLinha->mestrado != ''
                  && isset($oLinha->doutorado) && $oLinha->doutorado != ''
                  && isset($oLinha->nenhum) && $oLinha->nenhum != ''
                ) {
                    $sEspecializacao = $oLinha->especializacao;
                    $sEspecializacao .= $oLinha->mestrado;
                    $sEspecializacao .= $oLinha->doutorado;
                    $sEspecializacao .= $oLinha->nenhum;

                    $oDaoRechumano->ed20_c_posgraduacao = $sEspecializacao;
                }

                /* Verifica os campos outroscursos */
                if (isset($oLinha->especificocreche) && $oLinha->especificocreche != ''
                  && isset($oLinha->especificopreescola) && $oLinha->especificopreescola != ''
                  && isset($oLinha->especificoanosiniciais) && $oLinha->especificoanosiniciais != ''
                  && isset($oLinha->especificoanosfinais) && $oLinha->especificoanosfinais != ''
                  && isset($oLinha->especificoensinomedio) && $oLinha->especificoensinomedio != ''
                  && isset($oLinha->especificoeja) && $oLinha->especificoeja != ''
                  && isset($oLinha->especificoeducespecial) && $oLinha->especificoeducespecial != ''
                  && isset($oLinha->especificoindigena) && $oLinha->especificoindigena != ''
                  && isset($oLinha->intercultural) && $oLinha->intercultural != ''
                  && isset($oLinha->outros) && $oLinha->outros != ''
                  && isset($oLinha->nenhum) && $oLinha->nenhum != ''
                ) {
                    $sOutrosCursos = $oLinha->especificocreche;
                    $sOutrosCursos .= $oLinha->especificopreescola;
                    $sOutrosCursos .= $oLinha->especificoanosiniciais;
                    $sOutrosCursos .= $oLinha->especificoanosfinais;
                    $sOutrosCursos .= $oLinha->especificoensinomedio;
                    $sOutrosCursos .= $oLinha->especificoeja;
                    $sOutrosCursos .= $oLinha->especificoeducespecial;
                    $sOutrosCursos .= $oLinha->especificoindigena;
                    $sOutrosCursos .= $oLinha->intercultural;
                    $sOutrosCursos .= $oLinha->outros;
                    $sOutrosCursos .= $oLinha->nenhum;

                    $oDaoRechumano->ed20_c_outroscursos = $sOutrosCursos;
                }

                $oDaoRechumano->ed20_i_codigo = $aDadosRechumano[$iCont]->ed20_i_codigo;
                $oDaoRechumano->alterar($aDadosRechumano[$iCont]->ed20_i_codigo);

                if ($oDaoRechumano->erro_status == '0') {
                    throw new Exception("Erro na alteração dos dados do rechumano. Erro da classe: " . $oDaoRechumano->erro_msg);
                }

                $aFormacao = array();

                if (isset($oLinha->situacaocurso1) && isset($oLinha->formacao1) && isset($oLinha->cursosuperior1)
                  && isset($oLinha->anoinicurso1) && isset($oLinha->anoconclusao1) && isset($oLinha->tpinstintsuperior1)
                  && isset($oLinha->institsuperior1)
                ) {
                    $aFormacao[0] = array(
                      trim($oLinha->situacaocurso1),
                      trim($oLinha->formacao1),
                      trim($oLinha->cursosuperior1),
                      trim($oLinha->anoinicurso1),
                      trim($oLinha->anoconclusao1),
                      trim($oLinha->tpinstintsuperior1),
                      trim($oLinha->institsuperior1)
                    );
                }

                if (isset($oLinha->situacaocurso2) && isset($oLinha->formacao2) && isset($oLinha->cursosuperior2)
                  && isset($oLinha->anoinicurso2) && isset($oLinha->anoconclusao2) && isset($oLinha->tpinstsuperior2)
                  && isset($oLinha->institsuperior2)
                ) {
                    $aFormacao[1] = array(
                      trim($oLinha->situacaocurso2),
                      trim($oLinha->formacao2),
                      trim($oLinha->cursosuperior2),
                      trim($oLinha->anoinicurso2),
                      trim($oLinha->anoconclusao2),
                      trim($oLinha->tpinstsuperior2),
                      trim($oLinha->institsuperior2)
                    );
                }

                if (isset($oLinha->situacaocurso3) && isset($oLinha->formacao3) && isset($oLinha->cursosuperior3)
                  && isset($oLinha->anoinicurso3) && isset($oLinha->anoconclusao3) && isset($oLinha->tpinstsuperior3)
                  && isset($oLinha->institsuperior3)
                ) {
                    $aFormacao[2] = array(
                      trim($oLinha->situacaocurso3),
                      trim($oLinha->formacao3),
                      trim($oLinha->cursosuperior3),
                      trim($oLinha->anoinicurso3),
                      trim($oLinha->anoconclusao3),
                      trim($oLinha->tpinstsuperior3),
                      trim($oLinha->institsuperior3)
                    );
                }

                for ($iContFormacao = 0; $iContFormacao < count($aFormacao); $iContFormacao++) {
                    if (isset($aFormacao[$iContFormacao])
                      && trim($aFormacao[$iContFormacao][0]) != ""
                    ) {
                        $oDaoCursoFormacao = db_utils::getdao('cursoformacao');
                        $oDaoFormacao = db_utils::getdao('formacao');
                        $oDaoFormacao->excluir(null, "ed27_i_rechumano = " . $oDaoRechumano->ed20_i_codigo);

                        $sWhereCursoFormacao = "ed94_c_codigocenso = '" . $aFormacao[$iContFormacao][2] . "'";
                        $sSqlCursoFormacao = $oDaoCursoFormacao->sql_query("", "*", "", $sWhereCursoFormacao);
                        $rsCursoFormacao = $oDaoCursoFormacao->sql_record($sSqlCursoFormacao);

                        if ($oDaoCursoFormacao->numrows > 0) {
                            if ($aFormacao[$iContFormacao][0] != null) {
                                $oDaoFormacao->ed27_i_rechumano = $oDaoRechumano->ed20_i_codigo;
                                $oDaoFormacao->ed27_i_cursoformacao = db_utils::fieldsmemory($rsCursoFormacao, 0)->ed94_i_codigo;
                                $oDaoFormacao->ed27_c_situacao = 'CON';
                                $oDaoFormacao->ed27_i_licenciatura = (isset($aFormacao[$iContFormacao][0]) ?
                                  $aFormacao[$iContFormacao][0] : '');
                                $oDaoFormacao->ed27_i_anoconclusao = (isset($aFormacao[$iContFormacao][4]) ?
                                  $aFormacao[$iContFormacao][4] : '');
                                $oDaoFormacao->ed27_i_censosuperior = (isset($aFormacao[$iContFormacao][5]) ?
                                  $aFormacao[$iContFormacao][5] : '');
                                $oDaoFormacao->ed27_i_censoinstsuperior = (isset($aFormacao[$iContFormacao][6]) ?
                                  $aFormacao[$iContFormacao][6] : '');

                                $oDaoFormacao->incluir(null);

                                if ($oDaoFormacao->erro_status == '0') {
                                    throw new Exception(
                                        "Erro na alteração dos dados da Formação do professor. Erro da classe: " . $oDaoFormacao->erro_msg
                                    );
                                }//fecha o erro_status
                            } //fecha o if que verifica se o curso de formacao >0
                        } //fecha o if trim($aFormacao[$iContFormacao][1]) != ""
                    }//fecha o if que verifica $aFormacao[$iContFormacao][1]) != ""
                } //fecha o for do curso de formacao
            } //fecha o for
        } //fecha o if que verifica $oDadosRecHumano
    } //fecha a funcao atualizaDadosAluno

    /**
     * funcao que seleciona e atualiza os dados dos alunos no arquivo txt. registro 60
     * @param DBLayoutLinha $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaDadosAluno(DBLayoutLinha $oLinha)
    {

        $oDaoAluno = db_utils::getdao('aluno');
        $aDadosAluno = $this->getDadosAluno($oLinha);
        $oDaoAluno->ed47_i_censoorgemissrg = "";
        $oDaoAluno->ed47_i_censocartorio = "";
        $oDaoAluno->ed47_i_pais = "";
        $oDaoAluno->oid = "";

        if (!empty($oLinha->mae)) {
            $oDaoAluno->ed47_v_mae = str_replace(array('ª', 'º'), array('', ''), $oLinha->mae);
        }

        if (!empty($oLinha->filiacao_1)) {
            $oDaoAluno->ed47_v_mae = str_replace(array('ª', 'º'), array('', ''), $oLinha->filiacao_1);
        }

        if (!empty($oLinha->nomealuno)) {
            $oDaoAluno->ed47_v_nome = str_replace(array('ª', 'º'), array('', ''), $oLinha->nomealuno);
        }

        if (!empty($oLinha->pai)) {
            $oDaoAluno->ed47_v_pai = str_replace(array('ª', 'º'), array('', ''), $oLinha->pai);
        }

        if (!empty($oLinha->filiacao_2)) {
            $oDaoAluno->ed47_v_pai = str_replace(array('ª', 'º'), array('', ''), $oLinha->filiacao_2);
        }

        if ($oLinha->nascaluno != "") {
            $oDaoAluno->ed47_d_nasc = $this->formataData($oLinha->nascaluno);
        }

        if ($oLinha->sexo != "") {
            if ($oLinha->sexo == 1) {
                $oDaoAluno->ed47_v_sexo = 'M';
            } else {
                $oDaoAluno->ed47_v_sexo = 'F';
            }
        }

        if (trim($oLinha->racaaluno) != "") {
            if ($oLinha->racaaluno == 0) {
                $oDaoAluno->ed47_c_raca = 'NÃO DECLARADA';
            } else if ($oLinha->racaaluno == 1) {
                $oDaoAluno->ed47_c_raca = 'BRANCA';
            } else if ($oLinha->racaaluno == 2) {
                $oDaoAluno->ed47_c_raca = 'PRETA';
            } else if ($oLinha->racaaluno == 3) {
                $oDaoAluno->ed47_c_raca = 'PARDA';
            } else if ($oLinha->racaaluno == 4) {
                $oDaoAluno->ed47_c_raca = 'AMARELA';
            } else {
                $oDaoAluno->ed47_c_raca = 'INDÍGENA';
            }
        }//fecha if da raca

        if ($aDadosAluno != null) {
            $iTam = count($aDadosAluno);

            for ($iCont = 0; $iCont < $iTam; $iCont++) {
                if (!isset($aDadosAluno[$iCont])) {
                    break;
                }

                if ($this->lImportarAlunoAtivo) {
                    if (trim($aDadosAluno[$iCont]->vinculo_escola) != trim($this->iCodigoInepEscola)) {
                        if ($this->lInepEscola) {
                            $sMsg = "Aluno [" . $aDadosAluno[$iCont]->ed47_c_codigoinep . "] " . $aDadosAluno[$iCont]->ed47_v_nome . ": aluno";
                            $sMsg .= " não está mais vinculado a esta escola.\n";
                            $this->log($sMsg);

                            return;
                        }
                    } //fecha if $oDadosAluno->vinculo_escola) != trim($this->iCodigoInepEscola
                } //fecha o if $this->lImportarAlunoAtivo

                if ($oLinha->inepaluno != ""
                  && $oLinha->inepaluno != trim($aDadosAluno[$iCont]->ed47_c_codigoinep)
                ) {
                    $oDaoAluno->ed47_c_codigoinep = $oLinha->inepaluno;
                }

                if ($oLinha->nis != ""
                  && $oLinha->nis != trim($aDadosAluno[$iCont]->ed47_c_nis)
                ) {
                    $oDaoAluno->ed47_c_nis = $oLinha->nis;
                }

                if ($oLinha->filiacao != ""
                  && $oLinha->filiacao != trim($aDadosAluno[$iCont]->ed47_i_filiacao)
                ) {
                    $oDaoAluno->ed47_i_filiacao = $oLinha->filiacao;
                }

                if ($oLinha->nacionalidade != ""
                  && $oLinha->nacionalidade != trim($aDadosAluno[$iCont]->ed47_i_nacion)
                ) {
                    $oDaoAluno->ed47_i_nacion = $oLinha->nacionalidade;
                }

                if ($oLinha->paisorigem != ""
                  && $oLinha->paisorigem != trim($aDadosAluno[$iCont]->ed228_i_paisonu)
                ) {
                    $oDaoAluno->ed47_i_pais = $this->getPais($oLinha->paisorigem);
                }//fecha o if do pais

                if ($oLinha->uf != ""
                  && $oLinha->uf != trim($aDadosAluno[$iCont]->ed47_i_censoufnat)
                ) {
                    $oDaoAluno->ed47_i_censoufnat = $oLinha->uf;
                }

                if ($oLinha->municnasc != ""
                  && $oLinha->municnasc != trim($aDadosAluno[$iCont]->ed47_i_censomunicnat)
                ) {
                    $oDaoAluno->ed47_i_censomunicnat = $oLinha->municnasc;
                }

                if ($oLinha->defglobal != ""
                  && $oLinha->defglobal != trim($aDadosAluno[$iCont]->ed47_i_atendespec)
                ) {
                    $oDaoAluno->ed47_i_atendespec = $oLinha->defglobal;
                }
                $oDaoAluno->ed47_i_codigo = $aDadosAluno[$iCont]->ed47_i_codigo;
                $oDaoAluno->alterar($oDaoAluno->ed47_i_codigo);

                if ($oDaoAluno->erro_status == '0') {
                    throw new Exception("Erro na alteração dos dados do Aluno. Erro da classe: " . $oDaoAluno->erro_msg);
                }

                if (isset($oLinha->defglobal) && $oLinha->defglobal == 1) {
                    $oDaoAlunoNecessidade = db_utils::getdao('alunonecessidade');
                    $oDaoAlunoNecessidade->excluir(null, "ed214_i_aluno = " . $aDadosAluno[$iCont]->ed47_i_codigo);

                    trim($oLinha->cegueira) == 1 ? $aNecessidade[] = 101 : '';
                    trim($oLinha->baixavisao) == 1 ? $aNecessidade[] = 102 : '';
                    trim($oLinha->surdez) == 1 ? $aNecessidade[] = 103 : '';
                    trim($oLinha->defauditiva) == 1 ? $aNecessidade[] = 104 : '';
                    trim($oLinha->surdocegueira) == 1 ? $aNecessidade[] = 105 : '';
                    trim($oLinha->deffisica) == 1 ? $aNecessidade[] = 106 : '';
                    trim($oLinha->defintelectual) == 1 ? $aNecessidade[] = 107 : '';
                    trim($oLinha->defmultipla) == 1 ? $aNecessidade[] = 108 : '';
                    trim($oLinha->autismoinfantil) == 1 ? $aNecessidade[] = 109 : '';
                    trim($oLinha->sindromeasperger) == 1 ? $aNecessidade[] = 110 : '';
                    trim($oLinha->sindromerett) == 1 ? $aNecessidade[] = 111 : '';
                    trim($oLinha->transdesintegrativoinfancia) == 1 ? $aNecessidade[] = 112 : '';
                    trim($oLinha->altashabilidades) == 1 ? $aNecessidade[] = 113 : '';
                    $iTam = count($aNecessidade);

                    for ($iContNecessidade = 0; $iContNecessidade < $iTam; $iContNecessidade++) {
                        if ($aNecessidade[$iContNecessidade] > 0) {
                            $oDaoAlunoNecessidade->ed214_i_necessidade = $aNecessidade[$iContNecessidade];
                            $oDaoAlunoNecessidade->ed214_c_principal = 'NAO';
                            $oDaoAlunoNecessidade->ed214_i_apoio = 1;
                            $oDaoAlunoNecessidade->ed214_d_data = 'null';
                            $oDaoAlunoNecessidade->ed214_i_tipo = 1;
                            $oDaoAlunoNecessidade->ed214_i_escola = 'null';
                            $oDaoAlunoNecessidade->ed214_i_aluno = $aDadosAluno[$iCont]->ed47_i_codigo;
                            $oDaoAlunoNecessidade->incluir(null);

                            if ($oDaoAlunoNecessidade->erro_status == '0') {
                                throw new Exception(
                                    "Erro na inclusão das necessidades do aluno. Erro da classe: " . $oDaoAlunoNecessidade->erro_msg
                                );
                            }//fecha o if do erro status
                        }//fecha o if da necessidade
                    } //fecha o for das necessidades
                } else { //fecha o if $ed47_i_atendespec == 1
                    $oDaoAlunoNecessidade = db_utils::getdao('alunonecessidade');
                    $oDaoAlunoNecessidade->excluir(null, "ed214_i_aluno = " . $aDadosAluno[$iCont]->ed47_i_codigo);
                }

                unset($aNecessidade);
            }//fecha o for iCont
        } else {
            if ($this->lIncluirAlunoNaoEncontrado) {
                if ($oLinha->paisorigem != "") {
                    $oDaoAluno->ed47_i_pais = $this->getPais($oLinha->paisorigem);
                }

                if ($oLinha->nacionalidade != "") {
                    $oDaoAluno->ed47_i_nacion = $oLinha->nacionalidade;
                } else {
                    $oDaoAluno->ed47_i_nacion = 1;
                }

                $oDaoAluno->ed47_c_codigoinep = $oLinha->inepaluno;
                $oDaoAluno->ed47_c_nis = $oLinha->nis;
                $oDaoAluno->ed47_i_filiacao = $oLinha->filiacao;
                $oDaoAluno->ed47_i_censoufnat = $oLinha->uf;
                $oDaoAluno->ed47_i_censomunicnat = $oLinha->municnasc;
                $oDaoAluno->ed47_i_atendespec = $oLinha->defglobal;
                $oDaoAluno->ed47_c_atenddifer = '3';
                $oDaoAluno->ed47_v_ender = 'NAO INFORMADO';
                $oDaoAluno->ed47_i_transpublico = '0';
                $oDaoAluno->incluir(null);

                if ($oDaoAluno->erro_status == '0') {
                    throw new Exception("Erro na inclusão do aluno. Erro da classe: \n" . $oDaoAluno->erro_msg);
                }//fecha o erro_status

                $sMsg = "Aluno [" . $oLinha->inepaluno . "] " . $oLinha->nomealuno;
                $sMsg .= ": foi importado para o sistema.\n";
                $this->log($sMsg);

                if (isset($oLinha->defglobal) && $oLinha->defglobal == 1) {
                    $oDaoAlunoNecessidade = db_utils::getdao('alunonecessidade');
                    $oDaoAlunoNecessidade->excluir(null, "ed214_i_aluno = " . $oDaoAluno->ed47_i_codigo);

                    trim($oLinha->cegueira) == 1 ? $aNecessidade[] = 101 : '';
                    trim($oLinha->baixavisao) == 1 ? $aNecessidade[] = 102 : '';
                    trim($oLinha->surdez) == 1 ? $aNecessidade[] = 103 : '';
                    trim($oLinha->defauditiva) == 1 ? $aNecessidade[] = 104 : '';
                    trim($oLinha->surdocegueira) == 1 ? $aNecessidade[] = 105 : '';
                    trim($oLinha->deffisica) == 1 ? $aNecessidade[] = 106 : '';
                    trim($oLinha->defintelectual) == 1 ? $aNecessidade[] = 107 : '';
                    trim($oLinha->defmultipla) == 1 ? $aNecessidade[] = 108 : '';
                    trim($oLinha->autismoinfantil) == 1 ? $aNecessidade[] = 109 : '';
                    trim($oLinha->sindromeasperger) == 1 ? $aNecessidade[] = 110 : '';
                    trim($oLinha->sindromerett) == 1 ? $aNecessidade[] = 111 : '';
                    trim($oLinha->transdesintegrativoinfancia) == 1 ? $aNecessidade[] = 112 : '';
                    trim($oLinha->altashabilidades) == 1 ? $aNecessidade[] = 113 : '';
                    $iTam = count($aNecessidade);

                    for ($iContNecessidade = 0; $iContNecessidade < $iTam; $iContNecessidade++) {
                        if ($aNecessidade[$iContNecessidade] > 0) {
                            $oDaoAlunoNecessidade->ed214_i_necessidade = $aNecessidade[$iContNecessidade];
                            $oDaoAlunoNecessidade->ed214_c_principal = 'NAO';
                            $oDaoAlunoNecessidade->ed214_i_apoio = 1;
                            $oDaoAlunoNecessidade->ed214_d_data = 'null';
                            $oDaoAlunoNecessidade->ed214_i_tipo = 1;
                            $oDaoAlunoNecessidade->ed214_i_escola = 'null';
                            $oDaoAlunoNecessidade->ed214_i_aluno = $oDaoAluno->ed47_i_codigo;
                            $oDaoAlunoNecessidade->incluir(null);

                            if ($oDaoAlunoNecessidade->erro_status == '0') {
                                throw new Exception(
                                    "Erro na inclusão das necessidades do aluno. Erro da classe: " . $oDaoAlunoNecessidade->erro_msg
                                );
                            }//fecha o if do erro status
                        }//fecha o if da necessidade
                    }
                }
            } else { //else correspondente ao if $lIncluirAlunoNaoEncontrado
                $sMsg = "Aluno [" . $oLinha->inepaluno . "] " . $oLinha->nomealuno;
                $sMsg .= ": Nome cadastrado no censo não existe no sistema.\n";
                $this->log($sMsg);
            } //fecha o else
        }//fecha o else
    } //fecha a funcao atualizaEnderecoAluno

    /**
     * Funcao que seleciona os dados dos alunos para utilizarmos nas funcoes
     * atualizaDadosAluno,atualizaEnderecoAluno,AtualizaDadosAdicionais
     *
     * @param DBLayoutLinha $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @param boolean $lPesquisaInep //Se deseja incluir o código do inep na pesquisa do aluno
     *
     * @return object  com os dados do aluno se encontra-lo atraves dos dados contidos em $oLinha,
     * caso contrario retorna null
     */
    public function getDadosAluno(DBLayoutLinha $oLinha, $lPesquisaInep = false)
    {

        $oDaoAluno = db_utils::getdao('aluno');
        $sCamposAluno = "aluno.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola";
        $sWhereAluno = "";

        if (isset($oLinha->codigoaluno) && !empty($oLinha->codigoaluno)) {
            $sWhereAluno .= " ed47_i_codigo = " . $oLinha->codigoaluno;
        }

        if ($lPesquisaInep) {
            if (isset($oLinha->inepaluno) && !empty($oLinha->inepaluno)) {
                $sWhereAluno .= (empty($sWhereAluno) ? '' : ' AND ');
                $sWhereAluno .= " ed47_c_codigoinep = " . $oLinha->inepaluno;
            }
        }

        if (isset($oLinha->nomealuno) && !empty($oLinha->nomealuno)) {
            $sNomeAlunoCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nomealuno);
            $sWhereAluno .= (empty($sWhereAluno) ? '' : ' AND ');
            $sWhereAluno .= " to_ascii(translate(ed47_v_nome, '´`', '') ,'LATIN1') = '" . $sNomeAlunoCensoNovo . "'";
        }

        if (!empty($sWhereAluno)) {
            $sSqlAluno = $oDaoAluno->sql_query_censo("", $sCamposAluno, "vinculo_escola DESC", $sWhereAluno);
            $rsAluno = $oDaoAluno->sql_record($sSqlAluno);
        } else {
            return null;
        }

        if ($oDaoAluno->numrows > 0) {
            return $aDadosAluno = db_utils::getCollectionByRecord($rsAluno, false, false, false);
        } else {
            return null;
        } //fecha o else
    }//fecha a funcao atualizaDadosEscolarizacaoAluno

    /**
     * funcao que seleciona e atualiza os dados de endereco  e documentos(certidao, identidade.) do aluno, registro 70
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaEnderecoDocumentosAluno($oLinha)
    {

        $oDaoAluno = db_utils::getdao('aluno');
        $aDadosAluno = $this->getDadosAluno($oLinha, true);
        $oDaoAluno->ed47_i_censoorgemissrg = "";
        $oDaoAluno->ed47_i_pais = "";
        $oDaoAluno->ed47_i_censocartorio = "";
        $oDaoAluno->oid = "";

        if ($aDadosAluno != null) {
            $iTam = count($aDadosAluno);

            for ($iCont = 0; $iCont < $iTam; $iCont++) {
                if ($oLinha->identidade != ""
                  && $oLinha->identidade != trim($aDadosAluno[$iCont]->ed47_v_ident)
                ) {
                    $oDaoAluno->ed47_v_ident = $oLinha->identidade;
                }

                if (!empty($oLinha->complidentidade)
                  && $oLinha->complidentidade != trim($aDadosAluno[$iCont]->ed47_v_identcompl)
                ) {
                    $oDaoAluno->ed47_v_identcompl = $oLinha->complidentidade;
                }

                if ($oLinha->orgaoidentidade != ""
                  && $oLinha->orgaoidentidade != trim($aDadosAluno[$iCont]->ed47_i_censoorgemissrg)
                ) {
                    $oDaoAluno->ed47_i_censoorgemissrg = $oLinha->orgaoidentidade;
                }

                if ($oLinha->ufidentidade != ""
                  && $oLinha->ufidentidade != trim($aDadosAluno[$iCont]->ed47_i_censoufident)
                ) {
                    $oDaoAluno->ed47_i_censoufident = $oLinha->ufidentidade;
                }

                if (!empty($oLinha->dataexpedidentidade)
                  && $oLinha->dataexpedidentidade != trim($aDadosAluno[$iCont]->ed47_d_identdtexp)
                ) {
                    $oDaoAluno->ed47_d_identdtexp = $this->formataData($oLinha->dataexpedidentidade);
                }

                if (!empty($oLinha->tipodecertidao)
                  && $oLinha->tipodecertidao != trim($aDadosAluno[$iCont]->ed47_c_certidaotipo)
                ) {
                    if ($oLinha->tipodecertidao == 1) {
                        $oDaoAluno->ed47_c_certidaotipo = 'N';
                    } else if ($oLinha->tipodecertidao == 2) {
                        $oDaoAluno->ed47_c_certidaotipo = 'C';
                    } else {
                        $oDaoAluno->ed47_c_certidaotipo = '';
                    }
                }//fecha if do tipo de certidao

                if ($oLinha->numerotermo != ""
                  && $oLinha->numerotermo != trim($aDadosAluno[$iCont]->ed47_c_certidaonum)
                ) {
                    $oDaoAluno->ed47_c_certidaonum = $oLinha->numerotermo;
                }

                if ($oLinha->folha != ""
                  && $oLinha->folha != trim($aDadosAluno[$iCont]->ed47_c_certidaofolha)
                ) {
                    $oDaoAluno->ed47_c_certidaofolha = $oLinha->folha;
                }

                if ($oLinha->livro != ""
                  && $oLinha->livro != trim($aDadosAluno[$iCont]->ed47_c_certidaolivro)
                ) {
                    $oDaoAluno->ed47_c_certidaolivro = $oLinha->livro;
                }

                if (!empty($oLinha->dataemisscertidao)
                  && $oLinha->dataemisscertidao != trim($aDadosAluno[$iCont]->ed47_c_certidaodata)
                ) {
                    $oDaoAluno->ed47_c_certidaodata = $this->formataData($oLinha->dataemisscertidao);
                }

                if ($this->iAnoEscolhido == 2010) {
                    if ($oLinha->codigocartorio != ""
                      && ($oLinha->codigocartorio != trim($aDadosAluno[$iCont]->ed47_i_censocartorio))
                    ) {
                        $oDaoAluno->ed47_i_censocartorio = $this->getCartorio(null, $oLinha->codigocartorio);
                    }
                } else {
                    if ($oLinha->codigocartorio != "") {
                        $oDaoAluno->ed47_i_censocartorio = $this->getCartorio($oLinha->codigocartorio, null);
                    }
                }

                if ($oLinha->ufcartorio != ""
                  && $oLinha->ufcartorio != trim($aDadosAluno[$iCont]->ed47_i_censoufcert)
                ) {
                    $oDaoAluno->ed47_i_censoufcert = $oLinha->ufcartorio;
                }

                if (!empty($oLinha->cpf)
                  && $oLinha->cpf != trim($aDadosAluno[$iCont]->ed47_v_cpf)
                ) {
                    $oDaoAluno->ed47_v_cpf = $oLinha->cpf;
                }

                if (!empty($oLinha->passaporte)
                  && $oLinha->passaporte != trim($aDadosAluno[$iCont]->ed47_c_passaporte)
                ) {
                    $oDaoAluno->ed47_c_passaporte = $oLinha->passaporte;
                }

                if (!empty($oLinha->localzona)
                  && $oLinha->localzona != trim($aDadosAluno[$iCont]->ed47_c_zona)
                ) {
                    if ($oLinha->localzona == 1) {
                        $oDaoAluno->ed47_c_zona = 'URBANA';
                    } else if ($oLinha->localzona == 2) {
                        $oDaoAluno->ed47_c_zona = 'RURAL';
                    }
                }//fecha o if do local

                if (!empty($oLinha->cep)
                  && $oLinha->cep != trim($aDadosAluno[$iCont]->ed47_v_cep)
                ) {
                    $oDaoAluno->ed47_v_cep = $oLinha->cep;
                }

                if (!empty($oLinha->endereco)
                  && $oLinha->endereco != trim($aDadosAluno[$iCont]->ed47_v_ender)
                ) {
                    $oDaoAluno->ed47_v_ender = $oLinha->endereco;
                }

                if (!empty($oLinha->numero)
                  && $oLinha->numero != trim($aDadosAluno[$iCont]->ed47_c_numero)
                ) {
                    $oDaoAluno->ed47_c_numero = $oLinha->numero;
                }

                if (!empty($oLinha->complemento)
                  && $oLinha->complemento != trim($aDadosAluno[$iCont]->ed47_v_compl)
                ) {
                    $oDaoAluno->ed47_v_compl = $oLinha->complemento;
                }

                if (!empty($oLinha->bairro)
                  && $oLinha->bairro != trim($aDadosAluno[$iCont]->ed47_v_bairro)
                ) {
                    $oDaoAluno->ed47_v_bairro = substr($oLinha->bairro, 0, 40);
                }

                if ($oLinha->ufendere != ""
                  && $oLinha->ufendere != trim($aDadosAluno[$iCont]->ed47_i_censoufend)
                ) {
                    $oDaoAluno->ed47_i_censoufend = $oLinha->ufendere;
                }

                if ($oLinha->municipio != ""
                  && $oLinha->municipio != trim($aDadosAluno[$iCont]->ed47_i_censomunicend)
                ) {
                    $oDaoAluno->ed47_i_censomunicend = $this->getCensoMunicipioCertidao($oLinha->municipio);
                }

                if (isset($oLinha->municartorio) && $oLinha->municartorio != "") {
                    $oDaoAluno->ed47_i_censomuniccert = $oLinha->municartorio;
                }

                $oDaoAluno->ed47_i_codigo = $aDadosAluno[$iCont]->ed47_i_codigo;
                $oDaoAluno->alterar($aDadosAluno[$iCont]->ed47_i_codigo);

                if ($oDaoAluno->erro_status == '0') {
                    throw new Exception("Erro na alteração do endereço do aluno.Erro da classe: " . $oDaoAluno->erro_msg);
                }

                if (!empty($oDaoAluno->ed47_v_bairro)) {
                    $oDaoBairro = db_utils::getdao('bairro');
                    $sWhereBairro = "to_ascii(j13_descr,'LATIN1') = '" . $aDadosAluno[$iCont]->ed47_v_bairro . "'";
                    $sSqlBairro = $oDaoBairro->sql_query_file("", "j13_codi", "", $sWhereBairro);
                    $rsBairro = $oDaoBairro->sql_record($sSqlBairro);

                    if ($oDaoBairro->numrows > 0) {
                        $oDaoBairroAluno = db_utils::getdao('alunobairro');
                        $oDaoBairroAluno->excluir(null, "ed225_i_aluno = " . $aDadosAluno[$iCont]->ed47_i_codigo);
                        $oDaoBairroAluno->ed225_i_aluno = $aDadosAluno[$iCont]->ed47_i_codigo;
                        $oDaoBairroAluno->ed225_i_bairro = db_utils::fieldsmemory($rsBairro, 0)->j13_codi;
                        $oDaoBairroAluno->incluir(null);

                        if ($oDaoBairroAluno->erro_status == '0') {
                            throw new Exception("Erro na alteração do bairro do aluno. Erro da classe: " . $oDaoBairroAluno->erro_msg);
                        }//fecha o if erro_status
                    } //fecha o if $oDaoBairro->numrows > 0
                } //fecha o if que verifica se o $oDaoAluno->ed47_v_bairro != ""
            }//fecha o for iCont
        } //fecha o if que verifica se $oDadosAluno
    }//fecha a funcao importaArquivo

    /**
     * * Funcao que seleciona o codigo  do cartorio para utilizarmos na inclusao do aluno e do docente(RecHumano)
     * @param integer $iCodigoCensoCartorio
     * @param string $sNomeCartorio
     * @param integer $iServentia
     * @return integer
     */
    public function getCartorio($iCodigoCensoCartorio = null, $sNomeCartorio = null, $iServentia = null)
    {

        $oDaoCensoCartorio = db_utils::getdao('censocartorio');
        $sWhere = "";

        if ($iCodigoCensoCartorio != null) {
            $sWhere = "ed291_i_codigo = " . $iCodigoCensoCartorio;
        } else if ($sNomeCartorio != null) {
            $sWhere = "ed291_c_nome = '" . $sNomeCartorio . "'";
        } else if ($iServentia != null) {
            $sWhere = "ed291_i_serventia = {$iServentia}";
        }

        if (!empty($sWhere)) {
            $sSqlCartorio = $oDaoCensoCartorio->sql_query_file("", "ed291_i_codigo", "", $sWhere);
            $rsCartorio = $oDaoCensoCartorio->sql_record($sSqlCartorio);
        } else {
            return $oDadosCartorio = null;
        }

        if ($oDaoCensoCartorio->numrows > 0) {
            return $oDadosCartorio = db_utils::fieldsmemory($rsCartorio, 0)->ed291_i_codigo;
        } else {
            return $oDadosCartorio = null;
        }//fecha o else
    }

    /**
     * Função que verifica se o código vindo do EDUCACENSO existe cadastrado no sistema.
     *
     * @param integer $iCodCartorio
     * @return integer $ed261_i_codigo -> Se encontrar cadastro
     *                  null  -> Se não encontrar
     */
    public function getCensoMunicipioCertidao($iCodCartorio)
    {

        $oDaoCensoMunicipioCartorio = db_utils::getdao('censomunic');

        $sWhereSql = " ed261_i_codigo = $iCodCartorio ";
        $sSqlMunicipio = $oDaoCensoMunicipioCartorio->sql_query("", "ed261_i_codigo", "", $sWhereSql);
        $rsMunicipioCartorio = $oDaoCensoMunicipioCartorio->sql_record($sSqlMunicipio);
        $iLinhasEncontradas = $oDaoCensoMunicipioCartorio->numrows;

        if ($iLinhasEncontradas > 0) {
            return db_utils::fieldsmemory($rsMunicipioCartorio, 0)->ed261_i_codigo;
        } else {
            return null;
        }
    }

    /**
     * funcao que seleciona se o aluno tem algum servico adicional como tranporte publico, aula em outro local,
     * registro 80
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    public function atualizaDadosEscolarizacaoAluno($oLinha)
    {

        $oDaoAluno = db_utils::getdao('aluno');
        $aDadosAluno = $this->getDadosAluno($oLinha, true);
        $oDaoAluno->ed47_i_censoorgemissrg = "";
        $oDaoAluno->ed47_i_pais = "";
        $oDaoAluno->ed47_i_censocartorio = "";
        $oDaoAluno->oid = "";

        if ($aDadosAluno != null) {
            $iTam = count($aDadosAluno);

            for ($iCont = 0; $iCont < $iTam; $iCont++) {
                if ($oLinha->escolarizacaoespaco != ""
                  && $oLinha->escolarizacaoespaco != trim($aDadosAluno[$iCont]->ed47_c_atenddifer)
                ) {
                    $oDaoAluno->ed47_c_atenddifer = $oLinha->escolarizacaoespaco;
                }

                if ($oLinha->transescopubl != ""
                  && $oLinha->transescopubl != trim($aDadosAluno[$iCont]->ed47_i_transpublico)
                ) {
                    $oDaoAluno->ed47_i_transpublico = $oLinha->transescopubl;
                }

                if ($oLinha->poderpublico != trim($aDadosAluno[$iCont]->ed47_c_transporte)) {
                    if ($oLinha->transescopubl == "") {
                        $oLinha->poderpublico = "0";
                    }
                    $oDaoAluno->ed47_c_transporte = "{$oLinha->poderpublico}";
                }

                $oDaoAluno->ed47_i_codigo = $aDadosAluno[$iCont]->ed47_i_codigo;
                $oDaoAluno->alterar($aDadosAluno[$iCont]->ed47_i_codigo);

                if ($oDaoAluno->erro_status == '0') {
                    throw new Exception("Erro na alteração dos dados adicionais do aluno. Erro da classe: " . $oDaoAluno->erro_msg);
                }//fecha o if do erro_status
            }//fecha o for iCont
        }//fecha o if $oDadosAluno == null

        unset($aDadosAluno);
    }

    /**
     * @return array
     */
    public function getAlunosArquivo()
    {
        return $this->aAlunosArquivo;
    }

    public function getTotalAlunosArquivo()
    {
        $iTotal         = 0;
        $aAlunosArquivo = $this->getAlunosArquivo();

        foreach ($aAlunosArquivo as $alunoArquivo) {
            $iTotal += count($alunoArquivo);
        }

        return $iTotal;
    }

    /**
     * @return ProgressBar
     */
    public function getProgressBar()
    {
        return $this->oProgressBar;
    }

    /**
     * @param ProgressBar $oProgressBar
     */
    public function setProgressBar($oProgressBar)
    {
        $this->oProgressBar = $oProgressBar;
    }

    private function getTotalProcessados()
    {
        return $this->iTotalProcessados;
    }

    private function addTotalProcessados()
    {
        return $this->iTotalProcessados++;
    }

    /**
     * @return bool
     */
    public function temRegistroImportado()
    {
        return $this->lTemRegistroImportado;
    }

    /**
     * @return bool
     */
    public function temInconsistencia()
    {
        return $this->lTemInconsistencia;
    }
}

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

class importacaoCenso2015 extends ImportacaoCenso2012
{

    protected $sCampoChave = 'tipo_registro';

    private $iCodigoEscola = null;

    function __construct($iAnoEscolhido, $iCodigoInepEscola = null, $iCodigoLayout)
    {

        parent::__construct($iAnoEscolhido, $iCodigoInepEscola, $iCodigoLayout);
        $this->sCampoChave = 'tipo_registro';
    }

    /**
     * Informa qual escola esta importando o arquivo
     * @param integer $iEscola
     */
    function setCodigoEscola($iEscola)
    {

        $this->iEscola = $iEscola;
    }

    /**
     * Atualiza os dados da Escola
     *
     * @param DBLayoutLinha $oLinha
     */
    public function atualizaDadosEscola(DBLayoutLinha $oLinha)
    {

        $oDadosEscola = new DadosCensoEscola2015($this->iEscola, null, null);
        $oDadosEscola->atualizarDados($oLinha);
    }

    /**
     * Atualiza os dados do aluno de acordo com o arquivo de importação do CENSO
     * @param DBLayoutLinha $oLinha
     * @return bool|null
     */
    public function atualizaDadosAluno(DBLayoutLinha $oLinha)
    {

        if (!$this->lImportarAluno) {
            return true;
        }
        $aDadosAluno = $this->getDadosAluno($oLinha, true);

        if ($aDadosAluno != null) {

            foreach ($aDadosAluno as $iCont => $oDadosAluno) {

                if ($this->lImportarAlunoAtivo) {

                    if (trim($oDadosAluno->vinculo_escola) != trim($this->iCodigoInepEscola)) {

                        if ($this->lInepEscola) {

                            $sMsg = "Aluno [" . $aDadosAluno[$iCont]->ed47_c_codigoinep . "] " . $aDadosAluno[$iCont]->ed47_v_nome . ": aluno";
                            $sMsg .= " não está mais vinculado a esta escola.\n";
                            $this->log($sMsg);

                            return;

                        }
                    }
                }

                if ($this->lModuloEscola) {

                    $oAlunoCenso = new DadosCensoAluno2015($oDadosAluno->ed47_i_codigo, null);
                    $oAlunoCenso->atualizarDados($oLinha, $oDadosAluno);
                }

            }
        } else {
            if ($this->lIncluirAlunoNaoEncontrado && !$this->lModuloEscola) {
                $oAlunoCenso = new DadosCensoAluno2015(null, null);
                $lAdicionouAluno = $oAlunoCenso->adicionarNovoAluno($oLinha);

                if($lAdicionouAluno && !$this->lTemRegistroImportado) {
                    $this->lTemRegistroImportado = true;
                }

                $sMsg = "Aluno [{$oLinha-> identificacao_unica_aluno}] {$oLinha->nome_completo}";
                $sMsg .= ": foi importado para o sistema.\n";
                $this->log($sMsg);
            }
        }
    }

    /**
     * Pesquisa os dados do aluno conforme os dados do censo.
     * @param DBLayoutLinha $oLinha linha com os dados do registro 60 do censo escolar
     * @return stdClass
     */
    public function getDadosAluno(DBLayoutLinha $oLinha, $lPesquisaInep = false, $lValidaCodigo = true)
    {

        $oDaoAluno = new cl_aluno();
        $sCamposAluno = "aluno.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola";
        $aWhereAluno = array();

        if ($lPesquisaInep && !empty($oLinha->identificacao_unica_aluno)) {
            $aWhereAluno[] = " ed47_c_codigoinep = '" . $oLinha->identificacao_unica_aluno . "'";
        }

        if ($lValidaCodigo && !empty($oLinha->codigo_aluno_entidade_escola)) {
            $aWhereAluno[] = " ed47_i_codigo = " . $oLinha->codigo_aluno_entidade_escola;
        }

        if (!empty($aWhereAluno)) {

            $sSqlAluno = $oDaoAluno->sql_query_censo("", $sCamposAluno, "vinculo_escola DESC",
              implode(" AND ", $aWhereAluno));
            $rsAluno = db_query($sSqlAluno);

            if (!$rsAluno || pg_num_rows($rsAluno) == 0) {
                return null;
            }

            return $aDadosAluno = db_utils::getCollectionByRecord($rsAluno, false, false, false);
        }

        return null;
    }

    /**
     * Importa o código INEP presente no arquivo do censo e atualiza os dados do e-cidade
     * @todo  revisar
     */
    public function importarINEP()
    {

        $this->validarImportacao();
        $this->validaArquivo();
        $aLinhasArquivo = $this->getLinhasArquivo();

        $this->importarCodigoInep($aLinhasArquivo);

        fclose($this->pArquivoLog);
    }

    /**
     * Identifica os registro que devem ser importados no e-cidade
     *
     * @todo  ver necessidade de trocar acesso do registro pelo campo que identifica a chave
     *        trocar $this->lImportarTurma por $oLinha->{$this->sCampoChave}
     * @todo  trazer os metodos comentados da classe importacaoCodigoInep2014 alterando para que atenda o layout de 2015
     */
    public function importarCodigoInep($aLinhasArquivo)
    {

        foreach ($aLinhasArquivo as $iIndLinha => $oLinha) {

            if (!in_array($oLinha->{$this->sCampoChave}, array(20, 30, 60))) {
                continue;
            }

            if ($this->lImportarTurma && $oLinha->{$this->sCampoChave} == "20") {
                $this->atualizaCodigoInepTurma($oLinha);
            }

            if ($this->lImportarDocente && $oLinha->{$this->sCampoChave} == "30") {
                $this->atualizaCodigoInepDocente($oLinha);
            }

            if ($this->lImportarAluno && $oLinha->{$this->sCampoChave} == "60") {
                $this->atualizaCodigoInepAluno($oLinha);
            }
        }
    }

    /**
     * Atualiza o codigo INEP das turmas
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function atualizaCodigoInepTurma(DBLayoutLinha $oLinha)
    {

        /**
         * Atualiza turmas onde o tipo de atendimento seja
         * 0 - Não se aplica
         * 1 - Classe hospitalar
         * 2 - Unidade de internação socioeducativa
         * 3 - Unidade prisional
         */
        if (in_array(trim($oLinha->tipo_atendimento), array(0, 1, 2, 3))) {

            $oTurma = $this->validaTurma($oLinha);
            if ($oTurma && trim($this->iCodigoInepEscola) != "") {

                $oDaoTurma = new cl_turma();
                $oDaoTurma->ed57_i_codigoinep = $oLinha->codigo_turma_inep;
                $oDaoTurma->ed57_i_codigo = $oTurma->ed57_i_codigo;
                $oDaoTurma->alterar($oTurma->ed57_i_codigo);

                if ($oDaoTurma->erro_status == '0') {
                    throw new DBException("Erro na alteração dos dados da Turma. Erro da classe: " . $oDaoTurma->erro_msg);
                }
            }
        }

        /**
         * Atualiza turmas onde o tipo de atendimento seja
         * 4 - Atividade complementar
         * 5 - Atendimento Educacional Especializado (AEE)
         */
        if (in_array(trim($oLinha->tipo_atendimento), array(4, 5))) {

            $oTurma = $this->validarTurmaEspecial($oLinha);
            if ($oTurma && trim($this->iCodigoInepEscola) != "") {

                $oDaoTurmaac = new cl_turmaac();
                $oDaoTurmaac->ed268_i_codigoinep = $oLinha->codigo_turma_inep;
                $oDaoTurmaac->ed268_i_codigo = $oTurma->ed268_i_codigo;
                $oDaoTurmaac->alterar($oTurma->ed268_i_codigo);

                if ($oDaoTurmaac->erro_status == '0') {
                    throw new DBException("Erro na alteração do código inep da Turma. Erro da classe: " . $oDaoTurmaac->erro_msg);
                }
            }
        }

        return true;
    }

    /**
     * Valida as turmas normais para atualizar o codigo do inep
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function validaTurma(DBLayoutLinha $oLinha)
    {

        $sNomeTurmaCensoNovo = str_replace(array('ª', 'º'), array('', ''), trim($oLinha->nome_turma));

        $aWhere = array();
        if (!empty($oLinha->codigo_turma_entidade_escola)) {
            $aWhere[] = " ed57_i_codigo = {$oLinha->codigo_turma_entidade_escola} ";
        } else {
            $aWhere[] = " translate(to_ascii(ed57_c_descr, 'LATIN1'), ' ', '') = '{$sNomeTurmaCensoNovo}' ";
        }
        $aWhere[] = " ed57_i_tipoatend = " . trim($oLinha->tipo_atendimento);
        $aWhere[] = " ed52_i_ano = {$this->iAnoEscolhido} ";
        $aWhere[] = " ed10_i_tipoensino = " . trim($oLinha->modalidade_turma);
        $aWhere[] = " ed18_c_codigoinep = '{$this->iCodigoInepEscola}' ";

        $sWhereTurma = implode(" and ", $aWhere);
        $oDaoTurma = new cl_turma();
        $sSqlTurma = $oDaoTurma->sql_query_censo("", "ed57_i_codigo", "", $sWhereTurma);
        $rsTurma = db_query($sSqlTurma);

        if (!$rsTurma) {
            throw new DBException("Erro tentar atualizar o código do INEP das turmas.\n" . pg_last_error());
        }
        if (pg_num_rows($rsTurma) == 0) {

            $sMsg = "TURMA: [" . $this->iCodigoInepEscola . "] " . $sNomeTurmaCensoNovo;
            $sMsg .= " não foi encontrada no sistema.\n";
            $this->log($sMsg);

            return false;
        }

        return db_utils::fieldsmemory($rsTurma, 0);;
    }

    /**
     * Valida turmas de AC/AEE
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function validarTurmaEspecial(DBLayoutLinha $oLinha)
    {

        $sNomeTurmaCensoNovo = str_replace(array('ª', 'º'), array('', ''), trim($oLinha->nome_turma));

        $aWhere = array();
        if (!empty($oLinha->codigo_turma_entidade_escola)) {
            $aWhere[] = " ed268_i_codigo = {$oLinha->codigo_turma_entidade_escola} ";
        } else {
            $aWhere[] = " translate(to_ascii(ed268_c_descr, 'LATIN1'), ' ', '') = '{$sNomeTurmaCensoNovo}'";
        }

        $aWhere[] = " ed268_i_tipoatend = " . trim($oLinha->tipo_atendimento);;
        $aWhere[] = " ed52_i_ano = {$this->iAnoEscolhido} ";
        $aWhere[] = " ed18_c_codigoinep = '{$this->iCodigoInepEscola}'";
        $sWhere = implode(' and ', $aWhere);

        $oDaoTurmaac = new cl_turmaac();
        $sSqlTurmaac = $oDaoTurmaac->sql_query_censo("", "*", "", $sWhere);
        $rsTurmaac = db_query($sSqlTurmaac);

        if (!$rsTurmaac) {
            throw new DBException("Erro tentar atualizar o código do INEP das turma AC/AEE .\n" . pg_last_error());
        }
        if (pg_num_rows($rsTurmaac) == 0) {

            $sMsg = "TURMA: [" . $this->iCodigoInepEscola . "] " . $sNomeTurmaCensoNovo;
            $sMsg .= " código inep diferente do informado no sistema.\n";
            $this->log($sMsg);

            return false;
        }

        return db_utils::fieldsmemory($rsTurmaac, 0);;
    }

    /**
     * Atualiza o código inep do docente
     * @param  DBLayoutLinha $oLinha [description]
     * @throws DBException
     * @return Boolean
     */
    protected function atualizaCodigoInepDocente(DBLayoutLinha $oLinha)
    {

        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);

        if ($aDadosRechumano != null) {

            foreach ($aDadosRechumano as $oProfissional) {

                $oDaoRechumano = new cl_rechumano();
                if ($oLinha->identificacao_unica_docente_inep != "") {
                    $oDaoRechumano->ed20_i_codigoinep = $oLinha->identificacao_unica_docente_inep;
                }
                $oDaoRechumano->ed20_i_codigo = $oProfissional->ed20_i_codigo;
                $oDaoRechumano->alterar($oProfissional->ed20_i_codigo);

                if ($oDaoRechumano->erro_status == '0') {
                    throw new DBException("Erro na alteração dos dados do Rechumano. Erro da classe " . $oDaoRechumano->erro_msg);
                }
            }
        } else {

            $sMsg = "Docente: [" . $oLinha->identificacao_unica_docente_inep . "] " . $oLinha->nome_completo . " - " . $oLinha->codigo_docente_entidade_escola;
            $sMsg .= " não foi encontrado no sistema.\n";
            $this->log($sMsg);
        }

        return true;
    }

    /**
     * Alualiza o código do INEP dos alunos
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function atualizaCodigoInepAluno(DBLayoutLinha $oLinha)
    {

        $aDadosAluno = $this->getDadosAluno($oLinha);
        if ($aDadosAluno != null) {

            foreach ($aDadosAluno as $oDadosAluno) {

                if ($this->lImportarAlunoAtivo && ($oDadosAluno->vinculo_escola != trim($this->iCodigoInepEscola))) {

                    $sMsg = "Aluno [" . $oDadosAluno->ed47_c_codigoinep . "] " . $oDadosAluno->ed47_v_nome . ": aluno";
                    $sMsg .= " não está mais vinculado a esta escola.\n";
                    $this->log($sMsg);

                    return;
                }

                $oDaoAluno = new cl_aluno();
                if (!empty($oLinha->identificacao_unica_aluno)) {
                    $oDaoAluno->ed47_c_codigoinep = trim($oLinha->identificacao_unica_aluno);
                }

                $oDaoAluno->ed47_i_codigo = $oDadosAluno->ed47_i_codigo;
                $oDaoAluno->alterar($oDadosAluno->ed47_i_codigo);

                if ($oDaoAluno->erro_status == '0') {
                    throw new Exception("Erro na alteração do código inep do Aluno. Erro da classe " . $oDaoAluno->erro_msg);
                }
            }
        } else {

            $sMsg = "Aluno [" . $oLinha->identificacao_unica_aluno . "] " . $oLinha->nome_completo;
            $sMsg .= " : Nome cadastrado no censo não existe no sistema.\n";
            $this->log($sMsg);
        }

        return true;
    }

    /**
     * Sobrescrito função que consiste o ano do arquivo devido a alteração do layout
     * @param  array $aLinha dados da linha
     * @throws Exception
     * @return boolean
     */
    protected function validaAnoArquivo($aLinha)
    {

        $sData = $aLinha[7];
        $aData = explode("/", $sData);

        if ($this->iAnoEscolhido != $aData[2]) {

            fclose($pArquivoCenso);
            throw new Exception(" Arquivo informado não pertence ao ano de " . $this->iAnoEscolhido);
        }

        return true;
    }
}
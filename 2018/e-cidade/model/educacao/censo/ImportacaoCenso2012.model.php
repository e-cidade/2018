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


require_once(modification('model/educacao/importacaoCenso.model.php'));

class ImportacaoCenso2012 extends importacaoCenso
{

    protected $sCampoChave = 'tipo_registro';

    /**
     *
     */
    function __construct($iAnoEscolhido, $iCodigoInepEscola = null, $iCodigoLayout)
    {
        parent::__construct($iAnoEscolhido, $iCodigoInepEscola, $iCodigoLayout);
    }

    /**
     * Atualiza os dados do Docente
     *
     * @param DB $oLinha
     */
    function atualizaDadosDocente($oLinha)
    {

        if (!$this->lImportarDocente) {
            return true;
        }


        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);


        if ($aDadosRechumano != null) {

            foreach ($aDadosRechumano as $oDocente) {

                if (trim($oDocente->vinculo_escola) != trim($this->iCodigoInepEscola)) {

                    $sMsg = "Recurso Humano [" . $oDocente->ed20_i_codigoinep . "] " . $oDocente->z01_nome;
                    $sMsg .= ": Recurso Humano não está mais vinculado a esta escola.\n";
                    $this->log($sMsg);

                    return;

                }
                $oRecursoHumano = new DadosCensoDocente($oDocente->ed20_i_codigo);
                $oRecursoHumano->atualizarDados($oLinha, $oDocente);
            }
        } else {

            $sMsg = "Docente: [" . $oLinha->identificacao_unica_docente_inep . "] " . $oLinha->nome_completo;
            $sMsg .= " não foi encontrado no sistema.\n";
            $this->log($sMsg);

        }
    }

    /**
     * Atualiza os dados do docente.
     * a partir do layout de 2012, Usamos o cadastro de avaliacao para definir a escolaridade do
     * docente.
     *
     * @param Object $oLinha
     */

    /**
     *
     * Funcao que seleciona os recursos humanos para utilizarmos o mesmo sql nas funcoes
     * atualizaDadosDocente, atualizaEnderecoDocente,atualizaEscolarizacaoDocente
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @return object com os dados do rechumano caso tiver registro, caso contrario retorna null
     */
    function getMatriculasRechumano($oLinha)
    {

        $iCodDocenteEsc = trim($oLinha->codigo_docente_entidade_escola);

        $oDaoRechumano = new cl_rechumano();
        $sCampos = 'rechumano.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola, ';
        $sCampos .= 'case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome';
        $sWhereRechumano = "";

        if (isset($oLinha->identificacao_unica_docente_inep) && !empty($oLinha->identificacao_unica_docente_inep)) {
            $sWhereRechumano .= "ed20_i_codigoinep = " . $oLinha->identificacao_unica_docente_inep;
        } else {
            if (isset($oLinha->identificacao_unica_docente) && !empty($oLinha->identificacao_unica_docente)) {
                $sWhereRechumano .= "ed20_i_codigoinep = {$oLinha->identificacao_unica_docente}";
            } else if (isset($oLinha->codigo_docente_entidade_escola) && !empty($oLinha->codigo_docente_entidade_escola)) {

                $oDadosDocente = new DadosCensoDocente($iCodDocenteEsc);
                $iCodDocenteEsc = $oDadosDocente->getDadosIdentificacao()->numcgm;
                $sWhereRechumano .= (empty($sWhereRechumano) ? "" : " AND ");
                $sWhereRechumano .= " (cgmrh.z01_numcgm = {$iCodDocenteEsc} or cgmcgm.z01_numcgm = {$iCodDocenteEsc}) ";
            }
        }

        if (!empty($sWhereRechumano)) {

            $sSqlRechumano = $oDaoRechumano->sql_query_censomodel("", $sCampos,
              "vinculo_escola DESC",
              $sWhereRechumano
            );

            $rsRechumano = $oDaoRechumano->sql_record($sSqlRechumano);
        }

        /* Nao encontrou o docente pelo codigo inep, entao tenta encontrar pelo nome, data de nascimento de nome da mae */
        if ($oDaoRechumano->numrows <= 0 && isset($oLinha->nome_completo)
          && isset($oLinha->data_nascimento) && isset($oLinha->nome_completo_mae)
        ) {

            $sNomeDocenteCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo);
            $dNascDocente = $this->formataData($oLinha->data_nascimento);
            $sMaeDocenteCenso = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo_mae);
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
        }
    }

    /**
     * Atualiza os dados de endereco do docente
     */
    public function atualizaEnderecoDocente($oLinha)
    {

        if (!$this->lImportarDocente) {
            return true;
        }
        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);
        if (is_array($aDadosRechumano)) {

            foreach ($aDadosRechumano as $oDocente) {

                $oRecursoHumano = new DadosCensoDocente($oDocente->ed20_i_codigo);
                $oRecursoHumano->atualizarDadosEndereco($oLinha, $oDocente);
            }
        }
    }

    /**
     * Atualiza os dados da escolaridade do docente.
     */
    public function atualizaEscolaridadeDocente($oLinha)
    {

        if (!$this->lImportarDocente) {
            return true;
        }

        $aDadosRechumano = $this->getMatriculasRechumano($oLinha);
        if (is_array($aDadosRechumano)) {

            foreach ($aDadosRechumano as $oDocente) {

                $oRecursoHumano = new DadosCensoDocente($oDocente->ed20_i_codigo);
                $oRecursoHumano->atualizarDadosEscolaridade($oLinha);


                $oAvaliacao = $oRecursoHumano->getAvaliacacaoEscolaridade();
                $this->setDadosAvaliacao($oAvaliacao, $oLinha, 3000002);

                foreach ($oAvaliacao->getGruposPerguntas() as $oGrupoPergunta) {

                    foreach ($oGrupoPergunta->getPerguntas() as $oPergunta) {

                        $oPergunta->setAvaliacao($oAvaliacao->getAvaliacaoGrupo());
                        $oPergunta->salvarRespostas();
                    }
                }
            }
        }
    }

    /**
     * Define os dados das avaliacoes
     * @param Avaliacao $oAvaliacao Instancia de Avaliacao
     * @param DBLayoutLinha $oLinha linha com os dados de identificacao
     * @param integer $iGrupo Codigo do grupo de perguntas
     * @throws Exception
     */
    protected function setDadosAvaliacao(Avaliacao $oAvaliacao, DBLayoutLinha $oLinha, $iGrupo = null)
    {

        foreach ($oAvaliacao->getGruposPerguntas() as $oGrupo) {

            if ($oGrupo->getGrupo() == $iGrupo || $iGrupo == null) {

                foreach ($oGrupo->getPerguntas() as $oPergunta) {

                    $oPergunta->getRespostas();
                    $oPergunta->setRespostasPorLayout($oLinha, $this->iAnoEscolhido);

                }
            }
        }
    }

    /**
     * Atualiza os dados da Escola
     *
     * @param DBLayoutLinha $oLinha
     */
    public function atualizaDadosEscola(DBLayoutLinha $oLinha)
    {

        $oEscola = $this->getDadosEscola($oLinha);
        if ($oEscola != null) {

            $oDadosEscola = new DadosCensoEscola($oEscola->ed18_i_codigo, null, null);
            $oDadosEscola->atualizarDados($oLinha);
        }
    }

    /**
     * Retorna os dados da escola
     *
     * @param DBLayoutLinha $oLinha
     * @return unknown
     */
    function getDadosEscola(DBLayoutLinha $oLinha)
    {

        $oDaoEscola = db_utils::getDao('escola');
        $sSqlEscola = $oDaoEscola->sql_query_file("", "*", "",
          "ed18_c_codigoinep = '" . $oLinha->codigo_escola_inep . "'");
        $rsEscola = $oDaoEscola->sql_record($sSqlEscola);

        if ($oDaoEscola->numrows > 0) {
            return db_utils::fieldsmemory($rsEscola, 0);
        } else {
            return null;
        }

    }

    /**
     * Realiza a atualização dos dados de estrutura e dependencias da escola
     *
     * @param DBLayoutLinha $oLinha
     */
    public function atualizaDadosEscolaEstrutura(DBLayoutLinha $oLinha)
    {

        $oEscola = $this->getDadosEscola($oLinha);
        if ($oEscola != null) {

            $oDadosEscola = new DadosCensoEscola($oEscola->ed18_i_codigo, null, null);
            $oDadosEscola->atualizarDadosEstrutura($oLinha, $oEscola);
            /**
             * Atualizamos dados que estão em avaliacoes
             */
            $oAvaliacao = $oDadosEscola->getAvaliacao();
            $this->setDadosAvaliacao($oAvaliacao, $oLinha);
            foreach ($oAvaliacao->getGruposPerguntas() as $oGrupoPergunta) {
                foreach ($oGrupoPergunta->getPerguntas() as $oPergunta) {

                    $oPergunta->setAvaliacao($oAvaliacao->getAvaliacaoGrupo());
                    $oPergunta->salvarRespostas();
                }
            }
        }
    }

    /**
     * Atualiza os dos alunos com posição no arquivo de Retorno do censo
     */
    public function atualizaDadosAluno(DBLayoutLinha $oLinha)
    {

        if (!$this->lImportarAluno) {
            return true;
        }
        $aDadosAluno = $this->getDadosAluno($oLinha, false, false);

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
                $oAlunoCenso = new DadosCensoAluno($oDadosAluno->ed47_i_codigo, null);
                $oAlunoCenso->atualizarDados($oLinha, $oDadosAluno);
            }
        } else {

            if ($this->lIncluirAlunoNaoEncontrado) {

                $oAlunoCenso = new DadosCensoAluno(null, null);
                $oAlunoCenso->adicionarNovoAluno($oLinha);
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

        $oDaoAluno = db_utils::getdao('aluno');
        $sCamposAluno = "aluno.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola";
        $sWhereAluno = "";

        if ($lValidaCodigo) {

            if (isset($oLinha->codigo_aluno_entidade_escola) && !empty($oLinha->codigo_aluno_entidade_escola)) {

                $sWhereAluno .= " ed47_i_codigo = " . $oLinha->codigo_aluno_entidade_escola;

            }
        }

        if ($lPesquisaInep) {

            if (isset($oLinha->identificacao_unica_aluno) && !empty($oLinha->identificacao_unica_aluno)) {

                $sWhereAluno .= (empty($sWhereAluno) ? '' : ' AND ');
                $sWhereAluno .= " ed47_c_codigoinep = '" . $oLinha->identificacao_unica_aluno . "'";

            }

        }

        if (isset($oLinha->nome_completo) && !empty($oLinha->nome_completo)) {

            $sNomeAlunoCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo);
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
        }
    }

    public function atualizaEnderecoDocumentosAluno($oLinha)
    {

        if (!$this->lImportarAluno) {
            return true;
        }
        $aDadosAluno = $this->getDadosAluno($oLinha, true);
        if ($aDadosAluno != null) {

            foreach ($aDadosAluno as $oDadosAluno) {

                $oAlunoCenso = new DadosCensoAluno($oDadosAluno->ed47_i_codigo, null);
                $oAlunoCenso->atualizarEnderecoDocumentos($oLinha);
            }
        }
    }

    public function atualizaDadosEscolarizacaoAluno($oLinha)
    {

        if (!$this->lImportarAluno) {
            return true;
        }
        $aDadosAluno = $this->getDadosAluno($oLinha, true);
        if ($aDadosAluno != null) {

            foreach ($aDadosAluno as $oDadosAluno) {

                $oAlunoCenso = new DadosCensoAluno($oDadosAluno->ed47_i_codigo, null);
                $oAlunoCenso->atualizarDadosTransporte($oLinha);
            }
        }
    }

    /**
     * funcao que seleciona os dados da turma (nome, modalidade, tipo de atendimento),registro 20
     * e atualiza os se forem diferentes dos encontrados no banco de dados
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     */
    function atualizaDadosTurma($oLinha, $iAnoCenso)
    {

        $sNomeTurmaCensoNovo = $oLinha->nome_turma;
        $iCodigoInepTurma = trim($oLinha->codigo_turma_inep);
        $iTipoAtendimento = trim($oLinha->tipo_atendimento);

        $sWhereTurma = '';
        if ($iTipoAtendimento == 0 || $iTipoAtendimento == 1
          || $iTipoAtendimento == 2 || $iTipoAtendimento == 3
        ) {

            $oDaoTurma = db_utils::getdao('turma');
            if (!empty($oLinha->codigo_turma_entidade_escola)) {
                $sWhereTurma .= "ed57_i_codigo = " . $oLinha->codigo_turma_entidade_escola;
            }

            if (isset($oLinha->nome_turma) && !empty($oLinha->nome_turma)) {

                if (!empty($sWhereTurma)) {
                    $sWhereTurma .= " and ";
                }
                $sWhereTurma .= "translate(ed57_c_descr, 'áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙãÃê', 'aeiouAEIOUaeiouAEIOUaAe') = '";
                $sWhereTurma .= $oLinha->nome_turma . "' ";
            }

            if (isset($oLinha->modalidade_turma) && !empty($oLinha->modalidade_turma)) {

                $sWhereTurma .= (empty($sWhereTurma) ? "" : " AND ");
                $sWhereTurma .= "      ed10_i_tipoensino = " . trim($oLinha->modalidade_turma);
            }
            if ($sWhereTurma != "") {
                $sWhereTurma .= " and ";
            }
            $sWhereTurma .= "  ed57_i_tipoatend  = {$iTipoAtendimento}";
            $sWhereTurma .= "  AND ed52_i_ano    = {$iAnoCenso}";
            $sWhereTurma .= "  AND ed18_c_codigoinep = '" . $this->iCodigoInepEscola . "'";
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
                    }
                }
            }
        } else if ($iTipoAtendimento == 4 || $iTipoAtendimento == 5) {

            $oDaoTurmaac = db_utils::getdao('turmaac');
            $sWhereTurmaAc = "";

            if (isset($oLinha->nome_turma) && !empty($oLinha->nome_turma)) {

                $sWhereTurmaAc = "translate(to_ascii(ed268_c_descr, 'LATIN1'),' ','') = '";
                $sWhereTurmaAc .= str_replace(" ", "", $sNomeTurmaCensoNovo) . "' ";
            }

            if (isset($iCodigoTurma) && $iCodigoTurma != "") {

                $sWhereTurmaAc .= (empty($sWhereTurmaAc) ? "" : " AND ");
                $sWhereTurmaAc .= "      ed268_i_codigo = {$oLinha->codigo_turma_entidade_escola}";
            }

            $sWhereTurmaAc .= "      AND ed268_i_tipoatend = {$iTipoAtendimento}";
            $sWhereTurmaAc .= "      AND ed52_i_ano = {$iAnoCenso}";
            $sWhereTurmaAc .= "      AND ed18_c_codigoinep = '{$this->iCodigoInepEscola}'";
            $sSqlTurmaac = $oDaoTurmaac->sql_query_censo("", "*", "", $sWhereTurmaAc);
            $rsTurmaac = $oDaoTurmaac->sql_record($sSqlTurmaac);

            if ($oDaoTurmaac->numrows == 0) {

                $sMsg = "TURMA: ACC [" . $this->iCodigoInepEscola . "] " . $sNomeTurmaCensoNovo;
                $sMsg .= " não foi encontrada no sistema.\n";
                $this->log($sMsg);

            } else {

                $oDadosTurmaac = db_utils::fieldsmemory($rsTurmaac, 0);

                if (trim($this->iCodigoInepEscola) != "") {

                    $oDaoTurmaac->ed268_i_codigoinep = $oDadosTurmaac->ed268_i_codigoinep;
                    $oDaoTurmaac->ed268_i_codigo = $oDadosTurmaac->ed268_i_codigo;
                    $oDaoTurmaac->alterar($oDadosTurmaac->ed268_i_codigo);

                    if ($oDaoTurmaac->erro_status == '0') {

                        throw new Exception("Erro na alteração dos dados da Turma de atendimento especial. Erro da classe: " .
                          $oDaoTurmaac->erro_msg
                        );

                    }
                }
            }
        }
    }
}

?>
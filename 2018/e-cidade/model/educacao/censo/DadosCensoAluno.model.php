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


class DadosCensoAluno extends DadosCenso
{

    protected $iCodigoAluno;

    protected $oDadosAluno;

    protected $oDadosDocumento;

    protected $oDadosEndereco;

    protected $aDadosMatricula;

    protected $sDataCenso;

    protected $iAnoCenso;

    protected $iEscola;

    /**
     *
     */
    function __construct($iAluno, $iEscola)
    {

        $this->iCodigoAluno = $iAluno;
        $this->iEscola = $iEscola;
    }

    /**
     * Valida os dados do arquivo
     * @param IExportacaoCenso $oExportacaoCenso da Importacao do censo
     * @return boolean
     */
    public static function validarDados(IExportacaoCenso $oExportacaoCenso)
    {

        $lDadosValidos = true;
        $lValidaCertidao = true;
        $aNecessidades = array();
        $oDadosAluno = $oExportacaoCenso->getDadosProcessadosAluno();

        foreach ($oDadosAluno as $oAlunos) {

            $sAluno = $oAlunos->registro60->codigo_aluno_entidade_escola . " - " . $oAlunos->registro60->nome_completo;
            $sAluno .= " - Data de Nascimento: {$oAlunos->registro60->data_nascimento}";
            $lTemDataCenso = false;

            if ($oExportacaoCenso->getDataCenso() != '') {

                $oDataCenso = new DBDate($oExportacaoCenso->getDataCenso());
                $lTemDataCenso = true;
            }

            /**
             * Validações do registro 60 do Layout do Censo
             */
            if (!empty($oAlunos->registro60->identificacao_unica_aluno)) {

                if (strlen($oAlunos->registro60->identificacao_unica_aluno) < 12) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Código INEP do aluno possui tamanho inferior a 12 dígitos.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if (!empty($oAlunos->registro60->numero_identificacao_social)) {

                if (!parent::ValidaNIS($oAlunos->registro60->numero_identificacao_social)) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Número NIS do aluno é inválido.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro60->data_nascimento == "") {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Campo Nascimento é obrigatório.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if (!empty($oAlunos->registro60->data_nascimento)) {

                $oDataNascimento = new DBDate($oAlunos->registro60->data_nascimento);
                if ($oDataNascimento->getAno() < 1908) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "O ano de nascimento do aluno deve ser posterior a 1907.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if ($lTemDataCenso) {

                    if (DBDate::calculaIntervaloEntreDatas($oDataNascimento, $oDataCenso, 'd') > 0) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "A data de nascimento do aluno deve ser inferior a data de referência do censo";
                        $sMsgErro .= "( {$oExportacaoCenso->getDataCenso()} )";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }
            }

            if ($oAlunos->registro60->filiacao == 1) {

                if ($oAlunos->registro60->nome_mae == "" && $oAlunos->registro60->nome_pai == "") {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "É necessário preencher o Nome da mãe e/ou o Nome do pai.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if ($oAlunos->registro60->nome_mae == $oAlunos->registro60->nome_pai) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "O nome da mãe e do pai devem ser diferentes.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if (!empty($oAlunos->registro60->nome_mae)) {

                    if (!DBString::isSomenteLetras($oAlunos->registro60->nome_mae)) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "O nome da mãe possui caracteres inválidos, deve ser informado apenas letras.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if (strpos($oAlunos->registro60->nome_mae, '  ')) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "O nome da mãe deve conter apenas espaços simples.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }

                if (!empty($oAlunos->registro60->nome_pai)) {

                    if (!DBString::isSomenteLetras($oAlunos->registro60->nome_pai)) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "O nome da pai possui caracteres inválidos, deve ser informado apenas letras.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if (strpos($oAlunos->registro60->nome_pai, '  ')) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "O nome do pai deve conter apenas espaços simples.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }
            }

            if ($oAlunos->registro60->pais_origem == 76) {

                if ($oAlunos->registro60->nacionalidade_aluno == 3) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "O país de origem deve estar de acordo com a nacionalidade.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro60->pais_origem != 76) {

                if ($oAlunos->registro60->nacionalidade_aluno != 3) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Deve ser selecionada a nacionalidade Estrangeira para este país.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro60->nacionalidade_aluno == 1) {

                if ($oAlunos->registro60->uf_nascimento == "") {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "UF de nascimento deve ser informado.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if ($oAlunos->registro60->municipio_nascimento == "") {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Município de nascimento deve ser informado.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            $iControleDeficiencias = 0;

            if ($oAlunos->registro60->tipos_defic_transtorno_def_autismo_infantil == 1) {
                $iControleDeficiencias++;
            }

            if ($oAlunos->registro60->tipos_defic_transtorno_def_asperger == 1) {
                $iControleDeficiencias++;
            }

            if ($oAlunos->registro60->tipos_defic_transtorno_def_sindrome_rett == 1) {
                $iControleDeficiencias++;
            }

            if ($oAlunos->registro60->tipos_defic_transtorno_desintegrativo_infancia == 1) {
                $iControleDeficiencias++;
            }

            if ($iControleDeficiencias > 1) {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Deve ser selecionado apenas um tipo de deficiência entre: Autismo Infantil, Síndrome de ";
                $sMsgErro .= "Asperger, Síndrome de Rett e Transtorno Desintegrativo da Infância.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            /* Criando vetores com necessidades e recursos do aluno */
            $aNecessidades['tipos_defic_transtorno_cegueira'] = $oAlunos->registro60->tipos_defic_transtorno_cegueira;
            $aNecessidades['tipos_defic_transtorno_baixa_visao'] = $oAlunos->registro60->tipos_defic_transtorno_baixa_visao;
            $aNecessidades['tipos_defic_transtorno_surdez'] = $oAlunos->registro60->tipos_defic_transtorno_surdez;
            $aNecessidades['tipos_defic_transtorno_auditiva'] = $oAlunos->registro60->tipos_defic_transtorno_auditiva;
            $aNecessidades['tipos_defic_transtorno_surdocegueira'] = $oAlunos->registro60->tipos_defic_transtorno_surdocegueira;
            $aNecessidades['tipos_defic_transtorno_def_fisica'] = $oAlunos->registro60->tipos_defic_transtorno_def_fisica;
            $aNecessidades['tipos_defic_transtorno_def_intelectual'] = $oAlunos->registro60->tipos_defic_transtorno_def_intelectual;
            $aNecessidades['tipos_defic_transtorno_def_autismo_infantil'] = $oAlunos->registro60->tipos_defic_transtorno_def_autismo_infantil;
            $aNecessidades['tipos_defic_transtorno_def_asperger'] = $oAlunos->registro60->tipos_defic_transtorno_def_asperger;
            $aNecessidades['tipos_defic_transtorno_def_sindrome_rett'] = $oAlunos->registro60->tipos_defic_transtorno_def_sindrome_rett;
            $aNecessidades['tipos_defic_transtorno_desintegrativo_infancia'] = $oAlunos->registro60->tipos_defic_transtorno_desintegrativo_infancia;
            $aNecessidades['tipos_defic_transtorno_altas_habilidades'] = $oAlunos->registro60->tipos_defic_transtorno_altas_habilidades;
            $aNecessidades['tipos_defic_transtorno_def_multipla'] = $oAlunos->registro60->tipos_defic_transtorno_def_multipla;

            $aRecursos['recurso_auxilio_ledor'] = $oAlunos->registro60->recurso_auxilio_ledor;
            $aRecursos['recurso_auxilio_transcricao'] = $oAlunos->registro60->recurso_auxilio_transcricao;
            $aRecursos['recurso_auxilio_interprete'] = $oAlunos->registro60->recurso_auxilio_interprete;
            $aRecursos['recurso_auxilio_interprete_libras'] = $oAlunos->registro60->recurso_auxilio_interprete_libras;
            $aRecursos['recurso_auxilio_leitura_labial'] = $oAlunos->registro60->recurso_auxilio_leitura_labial;
            $aRecursos['recurso_auxilio_prova_ampliada_16'] = $oAlunos->registro60->recurso_auxilio_prova_ampliada_16;
            $aRecursos['recurso_auxilio_prova_ampliada_20'] = $oAlunos->registro60->recurso_auxilio_prova_ampliada_20;
            $aRecursos['recurso_auxilio_prova_ampliada_24'] = $oAlunos->registro60->recurso_auxilio_prova_ampliada_24;
            $aRecursos['recurso_auxilio_prova_braille'] = $oAlunos->registro60->recurso_auxilio_prova_braille;
            $aRecursos['recurso_auxilio_nenhum'] = $oAlunos->registro60->recurso_auxilio_nenhum;

            $avalidarNecessidades = DadosCensoAluno::validarNecessidades($aNecessidades, $aRecursos);
            $avalidarRecursos = DadosCensoAluno::validarRecursos($aNecessidades, $aRecursos);

            if (count($avalidarNecessidades) > 0) {

                foreach ($avalidarNecessidades as $sMsgErroValidarNecessidades) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= $sMsgErroValidarNecessidades;
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if (count($avalidarRecursos) > 0) {

                foreach ($avalidarRecursos as $sMsgErroValidarRecursos) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= $sMsgErroValidarRecursos;
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if (($oAlunos->registro60->recurso_auxilio_prova_ampliada_16 == 1 && $oAlunos->registro60->recurso_auxilio_prova_ampliada_20 == 1) ||
              ($oAlunos->registro60->recurso_auxilio_prova_ampliada_16 == 1 && $oAlunos->registro60->recurso_auxilio_prova_ampliada_24 == 1) ||
              ($oAlunos->registro60->recurso_auxilio_prova_ampliada_20 == 1 && $oAlunos->registro60->recurso_auxilio_prova_ampliada_24 == 1)
            ) {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Deve ser informado somente 1 tipo de de recurso para avaliação no INEP, entre Prova Ampliada";
                $sMsgErro .= " (Fonte Tamanho 16), Prova Ampliada (Fonte Tamanho 20) e Prova Ampliada (Fonte Tamanho 24).";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            /**
             * Validações do registro 70 do Layout do Censo
             */
            $oRetornoDocumentacao = DadosCensoAluno::registroDocumentacaoValido($oAlunos);

            if (!$oRetornoDocumentacao->lDadosValidos) {

                $oExportacaoCenso->logErro($oRetornoDocumentacao->sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = $oRetornoDocumentacao->lDadosValidos;
            }

            if ($oAlunos->registro70->numero_cpf != "") {

                if (!DBString::isCPF($oAlunos->registro70->numero_cpf)) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= $oAlunos->registro70->numero_cpf . " não é um CPF válido";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro70->numero_identidade != "") {

                if ($oAlunos->registro60->nacionalidade_aluno == 3) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Número de identidade deve ser preenchido apenas por alunos com nacionalidade Brasileira ";
                    $sMsgErro .= "ou Brasileira - nascido no exterior ou naturalizado";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if ($oAlunos->registro70->orgao_emissor_identidade == "" && $oAlunos->registro70->uf_identidade == "") {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Órgão Emissor da Identidade e UF da Identidade devem ser preenchidos.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                $lValidaCertidao = false;
            } else {

                $lValidaCertidao = true;
            }

            if ($oAlunos->registro70->orgao_emissor_identidade != "" && $oAlunos->registro70->numero_identidade == "") {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Órgão emissor da identidade preenchido sem a informação do campo Número da identidade.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if ($oAlunos->registro70->orgao_emissor_identidade != "" && $oAlunos->registro70->data_expedicao_identidade == "") {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Órgão emissor da identidade preenchido sem a informação do campo Data Expedição Identidade.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if ($oAlunos->registro70->complemento_identidade != "") {

                if ($oAlunos->registro70->numero_identidade == "" ||
                  $oAlunos->registro70->orgao_emissor_identidade == "" ||
                  $oAlunos->registro70->uf_identidade == ""
                ) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Número da identidade, Órgão Emissor da Identidade e UF da Identidade devem ser preenchidos.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro70->data_expedicao_identidade != "") {

                if ($oAlunos->registro70->numero_identidade == "" ||
                  $oAlunos->registro70->orgao_emissor_identidade == "" ||
                  $oAlunos->registro70->uf_identidade == ""
                ) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Número da identidade, Órgão Emissor da Identidade e UF da Identidade devem ser preenchidos.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if ($lTemDataCenso && !empty($oAlunos->registro70->data_expedicao_identidade)) {

                    $oDataExpedicao = new DBDate($oAlunos->registro70->data_expedicao_identidade);
                    if (DBDate::calculaIntervaloEntreDatas($oDataExpedicao, $oDataCenso, 'd') > 0) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "A data de expedição da identidade deve ser anterior a data de referência do censo";
                        $sMsgErro .= "( {$oExportacaoCenso->getDataCenso()} )";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }
            }

            if (($oAlunos->registro60->nacionalidade_aluno == 1 || $oAlunos->registro60->nacionalidade_aluno == 2) && $lValidaCertidao) {

                if (empty($oAlunos->registro70->tipo_certidao_civil) && $oAlunos->registro70->justificativa_falta_documentacao == '') {

                    if ($oAlunos->registro70->codigo_cartorio != '') {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "O Cartório  foi preenchido sem a informação da certidão";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }
                if ($oAlunos->registro70->certidao_civil == 1 && $oAlunos->registro70->justificativa_falta_documentacao == '') {


                    if ($oAlunos->registro70->tipo_certidao_civil == "") {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Tipo de Certidão Civil deve ser preenchido.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if ($oAlunos->registro70->numero_termo == "") {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Número do Termo deve ser preenchido.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if ($oAlunos->registro70->uf_cartorio == "") {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "UF do Cartório deve ser preenchido.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if (trim($oAlunos->registro70->codigo_cartorio) == '') {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Campo Cartório de emissão não informado. ";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if ($oAlunos->registro70->municipio_cartorio == '') {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Campo município do cartório de emissão não informado. ";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }

                if ($oAlunos->registro70->certidao_civil == 2) {

                    if ($oAlunos->registro70->numero_matricula == "") {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Número da Matrícula (Registro Civil - Certidão Nova) deve ser preenchido.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                    try {
                        DadosCensoAluno::validarCertidadaoNova($oAlunos->registro70->numero_matricula,
                          $oExportacaoCenso->getAnoCenso());
                    } catch (Exception $eErroCertidao) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Número da Matrícula (Registro Civil - Certidão Nova) inválida." . $eErroCertidao->getMessage();
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if ($oAlunos->registro70->tipo_certidao_civil != "" ||
                      $oAlunos->registro70->numero_termo != "" ||
                      $oAlunos->registro70->folha != "" ||
                      $oAlunos->registro70->livro != "" ||
                      $oAlunos->registro70->data_emissao_certidao != "" ||
                      $oAlunos->registro70->uf_cartorio != "" ||
                      $oAlunos->registro70->municipio_cartorio != ""
                    ) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Os seguintes campos não devem ser preenchidos: Tipo de Certidão Civil, Número do Termo, Folha, ";
                        $sMsgErro .= "Livro, Data de Emissão da Certidão, UF do Cartório e Município do Cartório.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if ($oAlunos->registro70->numero_matricula == "") {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Número da Matrícula (Registro Civil - Certidão nova) deve ser preenchido.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }
            }
            $sDataEmissao = implode("-", array_reverse(explode("/", $oAlunos->registro70->data_emissao_certidao)));
            $sDataNascimento = implode("-", array_reverse(explode("/", $oAlunos->registro60->data_nascimento)));
            if ($sDataEmissao != "" && db_strtotime($sDataEmissao) < db_strtotime($sDataNascimento)) {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Data de Emissão da Certidão não deve ser menor que a data de nascimento.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if ($oAlunos->registro70->folha != "" ||
              $oAlunos->registro70->livro != "" ||
              $oAlunos->registro70->data_emissao_certidao != ""
            ) {

                if ($oAlunos->registro60->nacionalidade_aluno == 3 && $oAlunos->registro70->certidao_civil != 1) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Deve ser selecionada a nacionalidade Brasileira ou Brasileira - nascido no exterior ou ";
                    $sMsgErro .= "naturalizado, e a certidão de nascimento deve ser do modelo antigo.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro70->documento_estrangeiro_passaporte != "") {

                if ($oAlunos->registro60->nacionalidade_aluno != 3) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Deve ser selecionada a nacionalidade Estrangeira para utilizar o documento estrangeiro.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro70->numero != '' && !DBString::isSomenteAlfanumerico(str_replace(" ", "",
                $oAlunos->registro70->numero), true)
            ) {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Caracteres inválidos no número do endereço do aluno.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if ($oAlunos->registro70->folha != '' && !DBString::isSomenteAlfanumerico(str_replace(" ", "",
                $oAlunos->registro70->folha), true, true)
            ) {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Caracteres inválidos no campo folha dos dados da certidão do aluno.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if ($oAlunos->registro70->livro != '' && !DBString::isSomenteAlfanumerico(str_replace(" ", "",
                $oAlunos->registro70->livro), true, true, true)
            ) {

                $sMsgErro = "Aluno(a) {$sAluno}: \n";
                $sMsgErro .= "Caracteres inválidos no livro da certidão do aluno.";
                $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                $lDadosValidos = false;
            }

            if ($oAlunos->registro70->cep != "") {

                if ($oAlunos->registro70->endereco == "" ||
                  $oAlunos->registro70->municipio == "" ||
                  $oAlunos->registro70->uf == ""
                ) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Endereço, Município e UF devem ser preenchidos.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }

            if ($oAlunos->registro70->numero != "" ||
              $oAlunos->registro70->complemento != "" ||
              $oAlunos->registro70->bairro != ""
            ) {

                if ($oAlunos->registro70->cep == "" ||
                  $oAlunos->registro70->endereco == "" ||
                  $oAlunos->registro70->municipio == "" ||
                  $oAlunos->registro70->uf == ""
                ) {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "CEP, Endereço, Município e UF devem ser preenchidos.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }
            }


            $aMatriculaTurno = array();
            /**
             * Validações do registro 80 do Layout do Censo
             */
            foreach ($oAlunos->registro80 as $oMatricula) {

                if (!in_array($oMatricula->tipo_turma, $aMatriculaTurno)) {
                    $aMatriculaTurno[$oMatricula->turnoreferente] = $oMatricula->tipo_turma;
                } else {

                    if (in_array($oMatricula->tipo_turma, $aMatriculaTurno)
                      && !empty($aMatriculaTurno[$oMatricula->turnoreferente])
                      && $aMatriculaTurno[$oMatricula->turnoreferente] == $oMatricula->tipo_turma
                      && $oMatricula->tipo_turma == "AEE"
                    ) {
                        continue;
                    }

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "Aluno(a) matriculado em mais de uma turma, no mesmo turno (conflito de horários).";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;
                }

                if ($oMatricula->transporte_escolar_publico == 0 && $oMatricula->poder_publico_transporte_escolar != "") {

                    $sMsgErro = "Aluno(a) {$sAluno}: \n";
                    $sMsgErro .= "O campo 'Poder Público responsável pelo transporte escolar' não pode ser informado, Aluno não";
                    $sMsgErro .= " utiliza transporte público.";
                    $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                    $lDadosValidos = false;

                }

                if ($oMatricula->transporte_escolar_publico == 1) {

                    if ($oMatricula->poder_publico_transporte_escolar == "") {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Deve ser informado o poder público responsável.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if ($oMatricula->rodoviario_vans_kombi == 0 &&
                      $oMatricula->rodoviario_microonibus == 0 &&
                      $oMatricula->rodoviario_onibus == 0 &&
                      $oMatricula->rodoviario_bicicleta == 0 &&
                      $oMatricula->rodoviario_tracao_animal == 0 &&
                      $oMatricula->rodoviario_outro == 0 &&
                      $oMatricula->aquaviario_embarcacao_5_pessoas == 0 &&
                      $oMatricula->aquaviario_embarcacao_5_a_15_pessoas == 0 &&
                      $oMatricula->aquaviario_embarcacao_15_a_35_pessoas == 0 &&
                      $oMatricula->aquaviario_embarcacao_mais_de_35_pessoas == 0 &&
                      $oMatricula->ferroviario_trem_metro == 0
                    ) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Ao menos uma das opções de transporte público deve ser selecionada.";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }
                }

                $oTurma = DadosCensoAluno::getTurmaAluno($oExportacaoCenso, $oMatricula->codigo_turma_entidade_escola);
                $aEtapasMultiEtapa = array(12, 13, 22, 23, 24, 51, 56, 58, 64);
                $aEtapasPermitidas[12] = array(4, 5, 6, 7, 8, 9, 10, 11);
                $aEtapasPermitidas[13] = array(4, 5, 6, 7, 8, 9, 10, 11);
                $aEtapasPermitidas[22] = array(14, 15, 16, 17, 18, 19, 20, 21, 41);
                $aEtapasPermitidas[23] = array(14, 15, 16, 17, 18, 19, 20, 21, 41);
                $aEtapasPermitidas[24] = array(4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19, 20, 21, 41);
                $aEtapasPermitidas[51] = array(43, 44);
                $aEtapasPermitidas[56] = array(1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19, 20, 21, 41);
                $aEtapasPermitidas[58] = array(46, 47);
                $aEtapasPermitidas[64] = array(39, 40);
                if (!empty($oTurma)) {

                    if ($oTurma->etapa_ensino_turma == 3 && !in_array($oMatricula->turma_unificada, array(1, 2))) {

                        $sMsgErro = "Aluno(a) {$sAluno}: \n";
                        $sMsgErro .= "Deve ser informada a turma Unificada do Aluno";
                        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                        $lDadosValidos = false;
                    }

                    if (in_array($oTurma->etapa_ensino_turma, $aEtapasMultiEtapa)) {

                        if (!in_array($oMatricula->codigo_etapa_multi_etapa,
                          $aEtapasPermitidas[$oTurma->etapa_ensino_turma])
                        ) {

                            $sMsgErro = "Aluno(a) {$sAluno}: \n";
                            $sMsgErro .= "Etapa do aluno em turma multietapa fora das etapas permitidas. Turma do Aluno:{$oTurma->nome_turma}.";
                            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_ALUNO);
                            $lDadosValidos = false;

                        }
                    }
                }
            }
        }

        return $lDadosValidos;
    }

    /**
     * Valida os Necessidades especiais do aluno
     *
     * @param $aNecessidades
     * @param $aRecursos
     * @return array|boolean
     */
    function validarNecessidades($aNecessidades, $aRecursos)
    {

        $aErroMsg = array();
        $aNecessidadesDoAluno = array();
        $iContadorErros = 0;

        foreach ($aNecessidades as $sTipoDeficiencia => $iNecessidade) {

            if ($iNecessidade == 1) {

                $aNecessidadesDoAluno[$sTipoDeficiencia] = $iNecessidade;
            }
        }

        if (count($aNecessidadesDoAluno) == 0) {
            return $aErroMsg;
        } else {

            foreach ($aNecessidadesDoAluno as $sTipoDeficiencia => $iNecessidade) {

                switch ($sTipoDeficiencia) {

                    case 'tipos_defic_transtorno_cegueira':

                        if ($aRecursos['recurso_auxilio_transcricao'] == 0 &&
                          $aRecursos['recurso_auxilio_ledor'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_braille'] == 0 &&
                          $aRecursos['recurso_auxilio_nenhum'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Cegueira.";
                        }

                        break;

                    case 'tipos_defic_transtorno_baixa_visao':

                        if ($aRecursos['recurso_auxilio_transcricao'] == 0 &&
                          $aRecursos['recurso_auxilio_ledor'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_ampliada_16'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_ampliada_20'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_ampliada_24'] == 0 &&
                          $aRecursos['recurso_auxilio_nenhum'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Baixa Visão.";
                        }

                        break;

                    case 'tipos_defic_transtorno_surdez':

                        if ($aRecursos['recurso_auxilio_leitura_labial'] == 0 &&
                          $aRecursos['recurso_auxilio_interprete_libras'] == 0 &&
                          $aRecursos['recurso_auxilio_nenhum'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Surdez.";
                        }

                        break;

                    case 'tipos_defic_transtorno_auditiva':

                        if ($aRecursos['recurso_auxilio_leitura_labial'] == 0 &&
                          $aRecursos['recurso_auxilio_interprete_libras'] == 0 &&
                          $aRecursos['recurso_auxilio_nenhum'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Deficiência Auditiva.";
                        }

                        break;

                    case 'tipos_defic_transtorno_surdocegueira':

                        if ($aRecursos['recurso_auxilio_ledor'] == 0 &&
                          $aRecursos['recurso_auxilio_transcricao'] == 0 &&
                          $aRecursos['recurso_auxilio_interprete'] == 0 &&
                          $aRecursos['recurso_auxilio_interprete_libras'] == 0 &&
                          $aRecursos['recurso_auxilio_leitura_labial'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_ampliada_16'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_ampliada_20'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_ampliada_24'] == 0 &&
                          $aRecursos['recurso_auxilio_prova_braille'] == 0 &&
                          $aRecursos['recurso_auxilio_nenhum'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Surdocegueira.";
                        }

                        break;

                    default :

                        if ($aRecursos['recurso_auxilio_transcricao'] == 1 ||
                          $aRecursos['recurso_auxilio_ledor'] == 1
                        ) {

                            if ($sTipoDeficiencia != 'tipos_defic_transtorno_def_fisica' &&
                              $sTipoDeficiencia != 'tipos_defic_transtorno_def_intelectual' &&
                              $sTipoDeficiencia != 'tipos_defic_transtorno_def_autismo_infantil' &&
                              $sTipoDeficiencia != 'tipos_defic_transtorno_def_asperger' &&
                              $sTipoDeficiencia != 'tipos_defic_transtorno_def_sindrome_rett' &&
                              $sTipoDeficiencia != 'tipos_defic_transtorno_desintegrativo_infancia'
                            ) {

                                $aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Informada.";
                            }
                        }

                        if ($sTipoDeficiencia == 'tipos_defic_transtorno_altas_habilidades') {

                            if ($aNecessidades['tipos_defic_transtorno_cegueira'] == 0 && $aNecessidades['tipos_defic_transtorno_baixa_visao'] == 0) {

                                if ($aRecursos['recurso_auxilio_transcricao'] != 0 && $aRecursos['recurso_auxilio_ledor'] != 0) {

                                    $aErroMsg[$iContadorErros++] = "Foram selecionados recursos de avaliaï¿½ï¿½o invï¿½lidos para a Necessidade Especial Altas habilidades/Superdotaï¿½ï¿½o.";
                                }
                            }
                        }

                        break;
                }
            }
        }

        return $aErroMsg;
    }

    function validarRecursos($aNecessidades, $aRecursos)
    {

        $aErroMsg = array();
        $aRecursosDoAluno = array();
        $iContadorErros = 0;

        foreach ($aRecursos as $sTipoRecurso => $iRecurso) {

            if ($iRecurso == 1) {

                $aRecursosDoAluno[$sTipoRecurso] = $iRecurso;
            }
        }

        $aNecessidadesMarcadas = array();
        foreach ($aNecessidades as $sNecessidade => $iNecessidade) {
            if ($iNecessidade == 1) {
                $aNecessidadesMarcadas[$sNecessidade] = $iNecessidade;
            }
        }
        if (count($aRecursosDoAluno) == 0) {

            return $aErroMsg;
        } else {

            foreach ($aRecursosDoAluno as $sTipoRecurso => $iNecessidade) {

                switch ($sTipoRecurso) {

                    case 'recurso_auxilio_ledor':

                        if ($aNecessidades['tipos_defic_transtorno_cegueira'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_baixa_visao'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_surdocegueira'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_def_fisica'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_def_intelectual'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_def_autismo_infantil'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_def_asperger'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_def_sindrome_rett'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_desintegrativo_infancia'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Auxílio ledor.";
                        }

                        break;

                    case ($sTipoRecurso == 'recurso_auxilio_interprete' || $sTipoRecurso == 'recurso_auxilio_interprete_libras'):

                        if ($aNecessidades['tipos_defic_transtorno_surdocegueira'] == 0) {

                            $aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Guia-intérprete / Intérprete de Libras.";
                        }

                        break;

                    case 'recurso_auxilio_leitura_labial':

                        if ($aNecessidades['tipos_defic_transtorno_surdez'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_auditiva'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Leitura Labial.";
                        }

                        break;

                    case ($sTipoRecurso == 'recurso_auxilio_prova_ampliada_16' || $sTipoRecurso == 'recurso_auxilio_prova_ampliada_20' || $sTipoRecurso == 'recurso_auxilio_prova_ampliada_24'):

                        if ($aNecessidades['tipos_defic_transtorno_baixa_visao'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_surdocegueira'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Prova Ampliada.";
                        }

                        break;

                    case 'recurso_auxilio_prova_braille':

                        if ($aNecessidades['tipos_defic_transtorno_cegueira'] == 0 &&
                          $aNecessidades['tipos_defic_transtorno_surdocegueira'] == 0
                        ) {

                            $aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Prova Braille.";
                        }

                        break;

                }
            }
        }

        if (count($aRecursosDoAluno) > 0 && count($aNecessidadesMarcadas) == 0) {
            $aErroMsg[$iContadorErros++] = "O Aluno possui Recursos  de avaliacoes informados, mas nao possui nenhuma necessidade especial";
        }

        return $aErroMsg;
    }

    public function registroDocumentacaoValido($oAlunos)
    {

        $oRetorno = new stdClass();
        $oRetorno->lDadosValidos = true;
        $oRetorno->sMsgErro = "";
        $iSituacaoDocumentacao = 0;

        foreach ($oAlunos->registro80 as $oRegistro80) {

            if (isset($oRegistro80->ed47_situacaodocumentacao)) {
                $iSituacaoDocumentacao = $oRegistro80->ed47_situacaodocumentacao;
            }
        }

        if ($iSituacaoDocumentacao == 0) {

            if (
              empty($oAlunos->registro70->numero_identidade) &&
              empty($oAlunos->registro70->complemento_identidade) &&
              empty($oAlunos->registro70->orgao_emissor_identidade) &&
              empty($oAlunos->registro70->uf_identidade) &&
              empty($oAlunos->registro70->data_expedicao_identidade) &&
              empty($oAlunos->registro70->certidao_civil) &&
              empty($oAlunos->registro70->tipo_certidao_civil) &&
              empty($oAlunos->registro70->numero_termo) &&
              empty($oAlunos->registro70->folha) &&
              empty($oAlunos->registro70->livro) &&
              empty($oAlunos->registro70->data_emissao_certidao) &&
              empty($oAlunos->registro70->uf_cartorio) &&
              empty($oAlunos->registro70->municipio_cartorio) &&
              empty($oAlunos->registro70->codigo_cartorio) &&
              empty($oAlunos->registro70->numero_matricula) &&
              empty($oAlunos->registro70->numero_cpf) &&
              empty($oAlunos->registro70->documento_estrangeiro_passaporte)
            ) {

                $oRetorno->sMsgErro = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo}";
                $oRetorno->sMsgErro .= " - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
                $oRetorno->sMsgErro .= "Foi selecionada a opção 'Possui Documentação', porém sem nenhum documento preenchido.";
                $oRetorno->lDadosValidos = false;
            }
        }

        return $oRetorno;
    }

    public static function validarCertidadaoNova($sCertidao, $iAnoCenso, $iAnoNascimento = null)
    {

        if (strlen($sCertidao) <> 32) {
            throw new Exception("Número da certidão inválida. deve ser 12 caracteres");
        }

        $sAcervo = substr($sCertidao, 6, 2);
        $sNumeroServico = substr($sCertidao, 8, 2);
        $iAnoCertidao = substr($sCertidao, 10, 4);
        $iTipoLivro = substr($sCertidao, 14, 1);
        $iDigitoCertidao = substr($sCertidao, 30, 2);
        if (!in_array($sAcervo, array('01', '02'))) {
            throw new Exception("Tipo do acervo inválido. deve ser '01' ou '02'. Foi informado '{$sAcervo}'");
        }

        if ($sNumeroServico != 55) {
            throw new Exception("Número do serviço deve ser '55'. Foi informado '{$sNumeroServico}'");
        }
        if ($iAnoCertidao < 1905 && $iAnoCertidao > $iAnoCenso) {
            throw new Exception("Ano da certidão fora do intervalo válido. Deve estar entre 1905 e {$iAnoCenso}. Foi informado '{$sNumeroServico}'");
        }

        if (in_array($iTipoLivro, array(4, 5, 6))) {
            throw new Exception("Tipo do Livro deve ser diferente de 4,5 ou 6. Foi informado '{$sNumeroServico}'");
        }

        if (!is_null($iAnoNascimento) && $iAnoCertidao < $iAnoNascimento) {
            throw new Exception("O ano de registro da certidão nova não pode ser anterior à data de nascimento.");
        }

        $iCalculo1 = self::calcularPesoCertidao($sCertidao, 2);

        $iDigito1 = $iCalculo1 % 11;
        if ($iDigito1 == 10) {
            $iDigito1 = 1;
        }

        $iCalculo2 = self::calcularPesoCertidao($sCertidao, 1, 31);
        $iDigito2 = $iCalculo2 % 11;
        if ($iDigito2 == 10) {
            $iDigito2 = 1;
        }
        $iDigitoVerificador = $iDigito1 . $iDigito2;
        if ($iDigitoVerificador != $iDigitoCertidao) {
            throw new Exception("Dígito verificador não confere. Informado ($iDigitoCertidao)");
        }
    }

    protected static function calcularPesoCertidao($sCertidao, $iPeso, $iCaracteres = 30)
    {

        $iCalculo = 0;
        for ($i = 0; $i < $iCaracteres; $i++) {

            if ($i == 8 || $i == 9 || $i == 14) {
                $iPeso++;
                if ($iPeso > 10) {
                    $iPeso = 0;
                }
                continue;
            }
            $iCaractere = (int)substr($sCertidao, $i, 1);
            $iCalculo = $iCalculo + ($iCaractere * $iPeso);
            $iPeso++;
            if ($iPeso > 10) {
                $iPeso = 0;
            }
        }

        return $iCalculo;
    }

    /**
     * Retorna a turma em que o Aluno esta Matriculado
     * @param IExportacaoCenso $oArquivo
     * @param                  $iCodigoTurma
     * @return mixed
     */
    public static function getTurmaAluno(IExportacaoCenso $oArquivo, $iCodigoTurma)
    {

        foreach ($oArquivo->getDadosProcessadosTurma() as $oTurma) {

            if ($oTurma->codigo_turma_entidade_escola == $iCodigoTurma) {
                return $oTurma;
            }
        }
    }

    /**
     * Retorna os dados de identificacao do aluno;
     */
    public function getDadosIdentificacao($lTurmaNormal = true)
    {

        if (empty($this->oDadosAluno) && $lTurmaNormal) {
            $this->getDados();
        }
        if (empty($this->oDadosAluno) && !$lTurmaNormal) {
            $this->getDadosAEE();
        }

        return $this->oDadosAluno;
    }

    protected function getDados()
    {

        $oDaoMatricula = new cl_matricula();

        $sCamposAluno = $this->getCamposQueryGetDados();
        $sCamposAluno .= " matricula.ed60_i_turma,  ";
        $sCamposAluno .= " turma.ed57_i_codigoinep,  ";
        $sCamposAluno .= " turma.ed57_i_codigo,  ";

        $sCamposAluno .= " (select ed133_censoetapa ";
        $sCamposAluno .= "    from seriecensoetapa  ";
        $sCamposAluno .= "   where ed133_ano   = {$this->iAnoCenso} ";
        $sCamposAluno .= "     and ed133_serie = serie.ed11_i_codigo) as codcensomatricula,  ";

        $sCamposAluno .= " turmacensoetapa.ed132_censoetapa as ed11_i_codcenso,";
        $sCamposAluno .= " ensino.ed10_i_tipoensino,  ";
        $sCamposAluno .= " matricula.ed60_i_codigo,  ";
        $sCamposAluno .= " case when ed342_sequencial is not null then ed343_turmacenso else turma.ed57_i_codigo end as codigo_turma,";
        // Sempre que tem turma unificada, busca a etapa da turma unificada
        $sCamposAluno .= " case when ed342_sequencial is not null ";
        $sCamposAluno .= "   then (select ed134_censoetapa from censoetapaturmacenso where ed134_turmacenso = ed342_sequencial ) ";
        $sCamposAluno .= "   else null end as etapa_unificada, ";

        $sCamposAluno .= " turnoreferente.ed231_i_referencia as turnoreferente,  ";
        $sCamposAluno .= " turma.ed57_i_tipoatend as codigo_tipo_turma  ";

        $sWhereMatricula = " turma.ed57_i_escola = {$this->iEscola} ";
        $sWhereMatricula .= "  AND calendario.ed52_i_ano = {$this->iAnoCenso} ";
        $sWhereMatricula .= "  AND aluno.ed47_i_codigo   = {$this->iCodigoAluno} ";
        $sWhereMatricula .= "  AND ed60_d_datamatricula <= '{$this->sDataCenso}' ";
        $sWhereMatricula .= "  AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
        $sWhereMatricula .= "       OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->sDataCenso}'))";

        $sSqlMatricula = $oDaoMatricula->sql_query_censo("", $sCamposAluno, "ed60_i_codigo DESC LIMIT 1",
          $sWhereMatricula);
        $rsMatricula = $oDaoMatricula->sql_record($sSqlMatricula);

        $this->setDadosIdenficacao($rsMatricula);
        $this->setDocumentoEndereco($rsMatricula);
        $this->setDadosMatricula($rsMatricula);
        unset($rsMatricula);
    }

    /**
     * Retorna os campos necessários para buscar os dados dos alunos
     * @return string
     */
    public function getCamposQueryGetDados()
    {

        $sCampos = " trim(aluno.ed47_c_codigoinep) as ed47_c_codigoinep,  ";
        $sCampos .= " aluno.ed47_i_codigo,  ";
        $sCampos .= " trim(aluno.ed47_v_nome) as ed47_v_nome,  ";
        $sCampos .= " trim(aluno.ed47_c_nis) as ed47_c_nis,  ";
        $sCampos .= " aluno.ed47_d_nasc,  ";
        $sCampos .= " aluno.ed47_v_sexo,  ";
        $sCampos .= " trim(aluno.ed47_c_raca) as ed47_c_raca,  ";
        $sCampos .= " aluno.ed47_i_filiacao,  ";
        $sCampos .= " trim(aluno.ed47_v_mae) as ed47_v_mae,  ";
        $sCampos .= " trim(aluno.ed47_v_pai) as ed47_v_pai,  ";
        $sCampos .= " aluno.ed47_i_nacion, ";
        $sCampos .= " pais.ed228_i_paisonu,  ";
        $sCampos .= " aluno.ed47_i_censoufnat,  ";
        $sCampos .= " aluno.ed47_i_censomunicnat,  ";
        $sCampos .= " trim(aluno.ed47_v_ident) as ed47_v_ident,  ";
        $sCampos .= " trim(aluno.ed47_v_identcompl) as ed47_v_identcompl,  ";
        $sCampos .= " aluno.ed47_i_censoorgemissrg,  ";
        $sCampos .= " aluno.ed47_i_censoufident, ";
        $sCampos .= " aluno.ed47_d_identdtexp,  ";
        $sCampos .= " trim(aluno.ed47_c_certidaotipo) as ed47_c_certidaotipo, ";
        $sCampos .= " trim(aluno.ed47_c_certidaonum) as ed47_c_certidaonum,  ";
        $sCampos .= " trim(aluno.ed47_c_certidaofolha) as ed47_c_certidaofolha,  ";
        $sCampos .= " trim(aluno.ed47_c_certidaolivro) as ed47_c_certidaolivro, ";
        $sCampos .= " trim(aluno.ed47_c_certidaodata::varchar) as ed47_c_certidaodata,  ";
        $sCampos .= " trim(censocartorio.ed291_i_codigocenso::varchar) as ed47_i_censocartorio,  ";
        $sCampos .= " trim(ed47_certidaomatricula) as ed47_certidaomatricula,";
        $sCampos .= " aluno.ed47_i_censoufcert,  ";
        $sCampos .= " trim(aluno.ed47_v_cpf) as ed47_v_cpf,  ";
        $sCampos .= " trim(aluno.ed47_c_passaporte) as ed47_c_passaporte,  ";
        $sCampos .= " trim(aluno.ed47_v_cep) as ed47_v_cep,  ";
        $sCampos .= " trim(aluno.ed47_v_ender) as ed47_v_ender,  ";
        $sCampos .= " trim(aluno.ed47_c_numero) as ed47_c_numero,  ";
        $sCampos .= " trim(aluno.ed47_v_compl) as ed47_v_compl,  ";
        $sCampos .= " trim(aluno.ed47_v_bairro) as ed47_v_bairro,  ";
        $sCampos .= " aluno.ed47_i_censoufend,  ";
        $sCampos .= " aluno.ed47_i_censomunicend,  ";
        $sCampos .= " ed47_i_censomuniccert,";
        $sCampos .= " trim(aluno.ed47_c_atenddifer) as ed47_c_atenddifer,  ";
        $sCampos .= " aluno.ed47_i_transpublico,  ";
        $sCampos .= " trim(aluno.ed47_c_transporte) as ed47_c_transporte,  ";
        $sCampos .= " case when trim(aluno.ed47_c_zona) = 'RURAL' then 2 else 1 end  as ed47_c_zona,  ";
        $sCampos .= " aluno.ed47_situacaodocumentacao as ed47_situacaodocumentacao,  ";

        return $sCampos;
    }

    /**
     * Cria o objeto de Retorno com os dados do Aluno;
     *
     * @param $rsDadosAluno
     * @return Object;
     */
    protected function setDadosIdenficacao($rsDadosAluno)
    {

        $oDadosAluno = db_utils::fieldsMemory($rsDadosAluno, 0);
        $iRacaAluno = 0;
        switch (trim($oDadosAluno->ed47_c_raca)) {

            case  "BRANCA":
                $iRacaAluno = 1;
                break;

            case "PRETA":

                $iRacaAluno = 2;
                break;

            case "PARDA":

                $iRacaAluno = 3;
                break;

            case 'AMARELA' :

                $iRacaAluno = 4;
                break;

            case 'INDÍGENA' :
                $iRacaAluno = 5;
                break;

            default:

                $iRacaAluno = 0;
                break;
        }

        $iTipoFiliacao = 0;
        if (trim($oDadosAluno->ed47_v_mae) != "" || trim($oDadosAluno->ed47_v_pai) != "") {
            $iTipoFiliacao = 1;
        }

        $this->oDadosAluno = new stdClass();
        $this->oDadosAluno->tipo_registro = 60;
        $this->oDadosAluno->identificacao_unica_aluno = $oDadosAluno->ed47_c_codigoinep;
        $this->oDadosAluno->codigo_aluno_entidade_escola = $oDadosAluno->ed47_i_codigo;
        $this->oDadosAluno->nome_completo = $this->removeCaracteres($oDadosAluno->ed47_v_nome, 1, false);
        $this->oDadosAluno->numero_identificacao_social = $oDadosAluno->ed47_c_nis;
        $this->oDadosAluno->data_nascimento = db_formatar($oDadosAluno->ed47_d_nasc, "d");
        $this->oDadosAluno->sexo = $oDadosAluno->ed47_v_sexo == 'M' ? 1 : 2;
        $this->oDadosAluno->cor_raca = $iRacaAluno;
        $this->oDadosAluno->filiacao = $iTipoFiliacao;
        $this->oDadosAluno->nome_mae = $this->removeCaracteres($oDadosAluno->ed47_v_mae, 1, false);
        $this->oDadosAluno->nome_pai = $this->removeCaracteres($oDadosAluno->ed47_v_pai, 1, false);
        $this->oDadosAluno->nacionalidade_aluno = $oDadosAluno->ed47_i_nacion;
        $this->oDadosAluno->pais_origem = $oDadosAluno->ed228_i_paisonu;
        $this->oDadosAluno->uf_nascimento = $oDadosAluno->ed47_i_censoufnat;
        $this->oDadosAluno->municipio_nascimento = $oDadosAluno->ed47_i_censomunicnat;
        $this->oDadosAluno->alunos_deficiencia_transtorno_desenv_superdotacao = 0;

        $aNecessidades = $this->getDeficiencias();
        $sNecessidades = '';

        if (count($aNecessidades) > 0) {
            $this->oDadosAluno->alunos_deficiencia_transtorno_desenv_superdotacao = 1;
            $sNecessidades = 0;
        }

        $this->oDadosAluno->tipos_defic_transtorno_cegueira = isset($aNecessidades[101]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_baixa_visao = isset($aNecessidades[102]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_surdez = isset($aNecessidades[103]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_auditiva = isset($aNecessidades[104]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_surdocegueira = isset($aNecessidades[105]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_def_fisica = isset($aNecessidades[106]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_def_intelectual = isset($aNecessidades[107]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_def_autismo_infantil = isset($aNecessidades[109]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_def_asperger = isset($aNecessidades[110]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_def_sindrome_rett = isset($aNecessidades[111]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_desintegrativo_infancia = isset($aNecessidades[112]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_altas_habilidades = isset($aNecessidades[113]) ? 1 : $sNecessidades;
        $this->oDadosAluno->tipos_defic_transtorno_def_multipla = $sNecessidades;

        if (($this->oDadosAluno->tipos_defic_transtorno_cegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_cegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_surdez == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_surdez == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_auditiva == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_auditiva == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_surdocegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_surdocegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_cegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_auditiva == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 1 && $this->oDadosAluno->tipos_defic_transtorno_surdez == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 1 && $this->oDadosAluno->tipos_defic_transtorno_auditiva == 1) ||
          ($this->oDadosAluno->tipos_defic_transtorno_def_fisica == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1)
        ) {
            $this->oDadosAluno->tipos_defic_transtorno_def_multipla = 1;
        }
        /**
         * Validamos os recursos especiais do aluno
         */
        $aRecursosEspeciais = $this->getRecursosAvaliacao();
        $sRecursosEspeciais = '';
        if (count($aNecessidades) > 0 && $this->oDadosAluno->tipos_defic_transtorno_altas_habilidades == 0) {
            $sRecursosEspeciais = 0;
        }

        $this->oDadosAluno->recurso_auxilio_ledor = isset($aRecursosEspeciais[101]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_transcricao = isset($aRecursosEspeciais[102]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_interprete = isset($aRecursosEspeciais[103]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_interprete_libras = isset($aRecursosEspeciais[104]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_leitura_labial = isset($aRecursosEspeciais[105]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_prova_ampliada_16 = isset($aRecursosEspeciais[106]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_prova_ampliada_20 = isset($aRecursosEspeciais[107]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_prova_ampliada_24 = isset($aRecursosEspeciais[108]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_prova_braille = isset($aRecursosEspeciais[109]) ? 1 : $sRecursosEspeciais;
        $this->oDadosAluno->recurso_auxilio_nenhum = isset($aRecursosEspeciais[110]) ? 1 : $sRecursosEspeciais;
        if ($this->oDadosAluno->tipos_defic_transtorno_altas_habilidades == 1) {

            if ($this->oDadosAluno->tipos_defic_transtorno_cegueira == 0 && $this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 0) {

                $this->oDadosAluno->recurso_auxilio_nenhum = 0;
            }
            if (
              $this->oDadosAluno->tipos_defic_transtorno_cegueira == 0
              && $this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 0
              && $this->oDadosAluno->tipos_defic_transtorno_surdez == 0
              && $this->oDadosAluno->tipos_defic_transtorno_auditiva == 0
              && $this->oDadosAluno->tipos_defic_transtorno_surdocegueira == 0
              && $this->oDadosAluno->tipos_defic_transtorno_def_fisica == 0
              && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 0
              && $this->oDadosAluno->tipos_defic_transtorno_def_autismo_infantil == 0
              && $this->oDadosAluno->tipos_defic_transtorno_def_asperger == 0
              && $this->oDadosAluno->tipos_defic_transtorno_def_sindrome_rett == 0
              && $this->oDadosAluno->tipos_defic_transtorno_desintegrativo_infancia == 0
              && $this->oDadosAluno->tipos_defic_transtorno_def_multipla == 0
              && $this->getAnoCenso() > 2014
            ) {
                $this->oDadosAluno->recurso_auxilio_nenhum = '';
            }
        }

        return $this->oDadosAluno;
    }

    /**
     * Retorna as necessidades especiais do aluno
     */
    protected function getDeficiencias()
    {

        $aNecessidades = array();
        $oDaoAlunoNecessidades = db_utils::getDao("alunonecessidade");
        $sWhere = "ed214_i_aluno = {$this->getCodigoAluno()}";
        $sCampos = "distinct ed48_i_codigo as codigo, ";
        $sCampos .= "ed48_c_descr  as necessidade ";
        $sSqlNecessidades = $oDaoAlunoNecessidades->sql_query(null, $sCampos, "ed48_i_codigo", $sWhere);
        $rsNecessidades = $oDaoAlunoNecessidades->sql_record($sSqlNecessidades);
        $iTotalNecessidades = $oDaoAlunoNecessidades->numrows;
        for ($iNecessidade = 0; $iNecessidade < $iTotalNecessidades; $iNecessidade++) {

            $oDadosNecessidade = db_utils::fieldsMemory($rsNecessidades, $iNecessidade);
            $aNecessidades[$oDadosNecessidade->codigo] = $oDadosNecessidade;
        }

        return $aNecessidades;
    }

    /**
     * Retorna o codigo do aluno
     */
    public function getCodigoAluno()
    {
        return $this->iCodigoAluno;
    }

    /**
     * Retorna os recursos para avaliacao vinculados ao aluno
     * @return array
     */
    public function getRecursosAvaliacao()
    {

        $oDaoAlunoRecursoAvaliacaoInep = db_utils::getDao("alunorecursosavaliacaoinep");
        $sWhereAlunoRecursoAvaliacaoInep = "ed327_aluno = {$this->iCodigoAluno}";
        $sCamposAlunoRecursoAvaliacaoInep = "alunorecursosavaliacaoinep.ed327_sequencial as codigo_aluno_recurso";
        $sCamposAlunoRecursoAvaliacaoInep .= ", recursosavaliacaoinep.ed326_sequencial as codigo_recurso_avaliacao";
        $sCamposAlunoRecursoAvaliacaoInep .= ", recursosavaliacaoinep.ed326_descricao as descricao_recurso_avaliacao";
        $sCamposAlunoRecursoAvaliacaoInep .= ", aluno.ed47_i_codigo as codigo_aluno";
        $sSqlAlunoRecursoAvaliacaoInep = $oDaoAlunoRecursoAvaliacaoInep->sql_query(null,
          $sCamposAlunoRecursoAvaliacaoInep,
          null,
          $sWhereAlunoRecursoAvaliacaoInep);
        $rsAlunoRecursoAvaliacaoInep = $oDaoAlunoRecursoAvaliacaoInep->sql_record($sSqlAlunoRecursoAvaliacaoInep);
        $iTotalAlunoRecursoAvaliacaoInep = $oDaoAlunoRecursoAvaliacaoInep->numrows;

        $aRecursosAvaliacaoInep = array();
        if ($iTotalAlunoRecursoAvaliacaoInep > 0) {

            for ($iContador = 0; $iContador < $iTotalAlunoRecursoAvaliacaoInep; $iContador++) {

                $oDadosAlunoRecursoAvaliacao = db_utils::fieldsMemory($rsAlunoRecursoAvaliacaoInep, $iContador);
                $aRecursosAvaliacaoInep[$oDadosAlunoRecursoAvaliacao->codigo_recurso_avaliacao] = $oDadosAlunoRecursoAvaliacao;
            }
        }

        return $aRecursosAvaliacaoInep;
    }

    /**
     * Retorna o Ano do censo
     */
    public function getAnoCenso()
    {
        return $this->iAnoCenso;
    }

    /**
     * Retorna os Documentos do aluno
     */
    protected function setDocumentoEndereco($rsAluno)
    {

        $iCertidaoNova = 1;
        $oDadosDocumento = db_utils::fieldsMemory($rsAluno, 0);

        if ($oDadosDocumento->ed47_certidaomatricula != "") {
            $iCertidaoNova = 2;
        } else {
            if ($oDadosDocumento->ed47_situacaodocumentacao != 0) {

                $iCertidaoNova = '';
                $oDadosDocumento->ed47_c_certidaotipo = '';
            } else {
                if ($oDadosDocumento->ed47_situacaodocumentacao == 0 && empty($oDadosDocumento->ed47_c_certidaotipo)) {

                    $iCertidaoNova = '';
                    $oDadosDocumento->ed47_c_certidaotipo = '';
                }
            }
        }

        switch ($oDadosDocumento->ed47_c_certidaotipo) {

            case 'C':

                $iTipoCertidao = 2;
                break;

            case 'N':

                $iTipoCertidao = 1;
                break;

            default:

                $iTipoCertidao = '';
                break;
        }

        /**
         * Caso a certidao for nova, não devemos informar os dados do cartorio;
         */
        if ($iCertidaoNova == 2) {

            $iTipoCertidao = '';
            $oDadosDocumento->ed47_c_certidaotipo = '';
            $oDadosDocumento->ed47_c_certidaonum = '';
            $oDadosDocumento->ed47_c_certidaofolha = '';
            $oDadosDocumento->ed47_c_certidaolivro = '';
            $oDadosDocumento->ed47_c_certidaodata = '';
            $oDadosDocumento->ed47_i_censoufcert = '';
            $oDadosDocumento->ed47_i_censomuniccert = '';
            $oDadosDocumento->ed47_i_censocartorio = '';
        }

        if ($oDadosDocumento->ed47_i_nacion == 3) {

            $iCertidaoNova = '';
            $iTipoCertidao = '';
            $oDadosDocumento->ed47_c_certidaonum = '';
            $oDadosDocumento->ed47_c_certidaofolha = '';
            $oDadosDocumento->ed47_c_certidaolivro = '';
            $oDadosDocumento->ed47_c_certidaodata = '';
            $oDadosDocumento->ed47_i_censoufcert = '';
            $oDadosDocumento->ed47_i_censomuniccert = '';
            $oDadosDocumento->ed47_i_censocartorio = '';
        }

        $this->oDadosDocumento = new stdClass();
        $this->oDadosDocumento->tipo_registro = 70;
        $this->oDadosDocumento->identificacao_unica_aluno = $oDadosDocumento->ed47_c_codigoinep;
        $this->oDadosDocumento->codigo_aluno_entidade = $oDadosDocumento->ed47_i_codigo;
        $this->oDadosDocumento->numero_identidade = $oDadosDocumento->ed47_v_ident;
        $this->oDadosDocumento->complemento_identidade = $oDadosDocumento->ed47_v_identcompl;
        $this->oDadosDocumento->orgao_emissor_identidade = $oDadosDocumento->ed47_i_censoorgemissrg;
        $this->oDadosDocumento->uf_identidade = $oDadosDocumento->ed47_i_censoufident;
        $this->oDadosDocumento->data_expedicao_identidade = db_formatar($oDadosDocumento->ed47_d_identdtexp, "d");
        $this->oDadosDocumento->certidao_civil = $iCertidaoNova;
        $this->oDadosDocumento->tipo_certidao_civil = $iTipoCertidao;
        $this->oDadosDocumento->numero_termo = $oDadosDocumento->ed47_c_certidaonum;
        $this->oDadosDocumento->folha = $this->removeCaracteres(strtoupper($oDadosDocumento->ed47_c_certidaofolha), 8);
        $this->oDadosDocumento->livro = strtoupper($oDadosDocumento->ed47_c_certidaolivro);
        $this->oDadosDocumento->data_emissao_certidao = db_formatar($oDadosDocumento->ed47_c_certidaodata, "d");
        $this->oDadosDocumento->uf_cartorio = $oDadosDocumento->ed47_i_censoufcert;
        $this->oDadosDocumento->municipio_cartorio = $oDadosDocumento->ed47_i_censomuniccert;
        $this->oDadosDocumento->codigo_cartorio = $oDadosDocumento->ed47_i_censocartorio;
        $this->oDadosDocumento->numero_matricula = $oDadosDocumento->ed47_certidaomatricula;
        $this->oDadosDocumento->numero_cpf = $oDadosDocumento->ed47_v_cpf;
        $this->oDadosDocumento->documento_estrangeiro_passaporte = $this->removeCaracteres(strtoupper($oDadosDocumento->ed47_c_passaporte),
          8);
        $this->oDadosDocumento->localizacao_zona_residencia = $oDadosDocumento->ed47_c_zona;
        $this->oDadosDocumento->numero_identificacao_social = $this->oDadosAluno->numero_identificacao_social;
        $this->oDadosDocumento->justificativa_falta_documentacao = $oDadosDocumento->ed47_situacaodocumentacao;

        if ($oDadosDocumento->ed47_situacaodocumentacao == 0) {
            $this->oDadosDocumento->justificativa_falta_documentacao = '';
        } else {

            $this->oDadosDocumento->numero_identidade = '';
            $this->oDadosDocumento->orgao_emissor_identidade = '';
            $this->oDadosDocumento->uf_identidade = '';
            $this->oDadosDocumento->data_expedicao_identidade = '';
            $this->oDadosDocumento->certidao_civil = '';
            $this->oDadosDocumento->tipo_certidao_civil = '';
            $this->oDadosDocumento->numero_termo = '';
            $this->oDadosDocumento->folha = '';
            $this->oDadosDocumento->livro = '';
            $this->oDadosDocumento->data_emissao_certidao = '';
            $this->oDadosDocumento->uf_cartorio = '';
            $this->oDadosDocumento->municipio_cartorio = '';
            $this->oDadosDocumento->codigo_cartorio = '';
            $this->oDadosDocumento->numero_matricula = '';
        }

        $this->oDadosDocumento->cep = $oDadosDocumento->ed47_v_cep;
        $this->oDadosDocumento->endereco = $this->removeCaracteres($oDadosDocumento->ed47_v_ender, 7);
        $this->oDadosDocumento->numero = $this->removeCaracteres($oDadosDocumento->ed47_c_numero, 7);
        $this->oDadosDocumento->complemento = $this->removeCaracteres($oDadosDocumento->ed47_v_compl, 7);
        $this->oDadosDocumento->bairro = $this->removeCaracteres($oDadosDocumento->ed47_v_bairro, 7);
        $this->oDadosDocumento->uf = $oDadosDocumento->ed47_i_censoufend;
        $this->oDadosDocumento->municipio = $oDadosDocumento->ed47_i_censomunicend;
        $this->oDadosDocumento->situacaodocumentacao = (int)$oDadosDocumento->ed47_situacaodocumentacao;

        return $this->oDadosDocumento;
    }

    /**
     * Monta os dados do registro 80 - Dados de matricula do aluno
     */
    public function setDadosMatricula($rsMatricula)
    {

        $sTurmaMultiEtapa = "";
        $oDadosMatricula = db_utils::fieldsMemory($rsMatricula, 0);
        $aSeriesValidasMultiEtapaEja = array(12, 13, 22, 23, 51, 56, 58, 64);
        if ($this->getAnoCenso() > 2014) {
            $aSeriesValidasMultiEtapaEja = array(12, 13, 22, 23, 24, 56, 64, 72);
        }
        $aTransportes = $this->getDadosTransportePublico();
        if (in_array($oDadosMatricula->ed11_i_codcenso, $aSeriesValidasMultiEtapaEja)) {
            $sTurmaMultiEtapa = $oDadosMatricula->codcensomatricula;
        }

        $sTurmaUnificada = '';
        if (!empty($oDadosMatricula->etapa_unificada)) {

            if ($oDadosMatricula->etapa_unificada == 3 && in_array($oDadosMatricula->ed11_i_codcenso, array(1, 2))) {
                $sTurmaUnificada = $oDadosMatricula->ed11_i_codcenso;
            }
            if (in_array($oDadosMatricula->etapa_unificada, $aSeriesValidasMultiEtapaEja)) {
                $sTurmaMultiEtapa = $oDadosMatricula->codcensomatricula;
            }
        }

        $this->aDadosMatricula = array();

        $oDadosMatricula->tipo_registro = 80;
        $oDadosMatricula->identificacao_unica_aluno = $oDadosMatricula->ed47_c_codigoinep;
        $oDadosMatricula->codigo_aluno_entidade_escola = $oDadosMatricula->ed47_i_codigo;
        $oDadosMatricula->codigo_turma_inep = $oDadosMatricula->ed57_i_codigoinep;
        $oDadosMatricula->codigo_turma_entidade_escola = $oDadosMatricula->codigo_turma;
        $oDadosMatricula->codigo_matricula_aluno = '';
        $oDadosMatricula->turma_unificada = $sTurmaUnificada;
        $oDadosMatricula->codigo_etapa_multi_etapa = $sTurmaMultiEtapa;
        $oDadosMatricula->recebe_escolarizacao_outro_espaco = $oDadosMatricula->ed47_c_atenddifer;
        $oDadosMatricula->transporte_escolar_publico = $oDadosMatricula->ed47_i_transpublico;
        $oDadosMatricula->poder_publico_transporte_escolar = $oDadosMatricula->ed47_c_transporte;
        $oDadosMatricula->forma_ingresso_aluno_escola_federal = '';
        $oDadosMatricula->tipo_turma = "NORMAL";

        if ($oDadosMatricula->codigo_tipo_turma == 5) {
            $oDadosMatricula->tipo_turma = "AEE";
        }

        $this->aDadosMatricula[] = $oDadosMatricula;

        $aMatriculasAEE = $this->getMatriculasAtividadeEspecial();
        foreach ($aMatriculasAEE as $oMatriculaAEE) {
            $this->aDadosMatricula[] = $oMatriculaAEE;
        }

        foreach ($this->aDadosMatricula as $oMatricula) {
            $sValorDefaultTransporte = '';
            if ($oMatricula->transporte_escolar_publico == 1) {
                $sValorDefaultTransporte = '0';
            }
            $oMatricula->rodoviario_vans_kombi = isset($aTransportes[1]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->rodoviario_microonibus = isset($aTransportes[2]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->rodoviario_onibus = isset($aTransportes[3]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->rodoviario_bicicleta = isset($aTransportes[4]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->rodoviario_tracao_animal = isset($aTransportes[5]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->rodoviario_outro = isset($aTransportes[6]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->aquaviario_embarcacao_5_pessoas = isset($aTransportes[7]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->aquaviario_embarcacao_5_a_15_pessoas = isset($aTransportes[8]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->aquaviario_embarcacao_15_a_35_pessoas = isset($aTransportes[9]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->aquaviario_embarcacao_mais_de_35_pessoas = isset($aTransportes[10]) ? 1 : $sValorDefaultTransporte;
            $oMatricula->ferroviario_trem_metro = isset($aTransportes[11]) ? 1 : $sValorDefaultTransporte;

        }
        unset($aTransportes);

        return $this->aDadosMatricula;
    }

    /**
     * Retorna os dados de Transporte publico do aluno
     */
    public function getDadosTransportePublico()
    {

        $oDaoAlunoTransporte = db_utils::getDao("alunocensotipotransporte");
        $sWhere = "ed311_aluno = {$this->getCodigoAluno()}";
        $sCampos = "ed312_sequencial as codigo,";
        $sCampos .= "ed312_descricao as descricao";
        $sSqlTransportePublico = $oDaoAlunoTransporte->sql_query_tipo_transporte(null,
          $sCampos,
          "ed311_censotipotransporte limit 3",
          $sWhere
        );
        $rsTransportePublico = $oDaoAlunoTransporte->sql_record($sSqlTransportePublico);
        $iTotalLinhas = $oDaoAlunoTransporte->numrows;
        $aMeiosTransporte = array();
        for ($iTransporte = 0; $iTransporte < $iTotalLinhas; $iTransporte++) {

            $oDadosTransporte = db_utils::fieldsMemory($rsTransportePublico, $iTransporte);
            $aMeiosTransporte[$oDadosTransporte->codigo] = $oDadosTransporte;
        }

        return $aMeiosTransporte;
    }

    /**
     * Alterado para buscar somente alunos vínculados em turmas AC/AEE matrículados na escola
     */
    public function getMatriculasAtividadeEspecial()
    {

        $aMatriculaAEE = array();
        $oDaoTurmaAEE = new cl_turmaacmatricula();
        $sCamposMatricula = " distinct ed268_i_codigo, ed47_c_codigoinep, ed47_i_codigo, ed268_i_codigoinep, ed47_i_transpublico,";
        $sCamposMatricula .= " ed47_c_transporte, ed231_i_referencia, ed268_i_tipoatend,";
        $sCamposMatricula .= " (select array_accum(ed214_i_codigo) from alunonecessidade where ed214_i_escola = 99 ";
        $sCamposMatricula .= " and ed214_i_aluno = 535) as ed214_i_codigo";
        $sWhereMatricula = "      turmaac.ed268_i_escola = {$this->iEscola} ";
        $sWhereMatricula .= "  and turma.ed57_i_escola = {$this->iEscola} ";
        $sWhereMatricula .= "  and calendario.ed52_i_ano = {$this->iAnoCenso} ";
        $sWhereMatricula .= "  and aluno.ed47_i_codigo   = {$this->iCodigoAluno} ";
        $sWhereMatricula .= "  and (ed60_d_datamatricula <= '{$this->sDataCenso}' AND ed60_c_concluida = 'N' AND ed60_c_ativa = 'S') ";
        $sWhereMatricula .= "  and ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
        $sWhereMatricula .= "       OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->sDataCenso}'))";
        $sWhereMatricula .= "  and ed269_d_data <= '{$this->sDataCenso}'";

        $sSqlMatriculas = $oDaoTurmaAEE->sql_query_censo(null, $sCamposMatricula, null, null);
        $sSqlMatriculas .= " INNER JOIN turma     on ed57_i_codigo = ed60_i_turma ";
        $sSqlMatriculas .= " where {$sWhereMatricula} ";

        $rsMatriculaAEE = $oDaoTurmaAEE->sql_record($sSqlMatriculas);

        if ($oDaoTurmaAEE->numrows > 0) {

            for ($i = 0; $i < $oDaoTurmaAEE->numrows; $i++) {

                $oMatricula = db_utils::fieldsMemory($rsMatriculaAEE, $i);
                $oDadosMatricula = new stdClass();

                if ($oMatricula->ed268_i_tipoatend == 5 && empty($oMatricula->ed214_i_codigo)) {
                    continue;
                }

                $oDadosMatricula->tipo_registro = 80;
                $oDadosMatricula->identificacao_unica_aluno = $oMatricula->ed47_c_codigoinep;
                $oDadosMatricula->codigo_aluno_entidade_escola = $oMatricula->ed47_i_codigo;
                $oDadosMatricula->codigo_turma_inep = $oMatricula->ed268_i_codigoinep;
                $oDadosMatricula->codigo_turma_entidade_escola = $oMatricula->ed268_i_codigo;
                $oDadosMatricula->codigo_matricula_aluno = '';
                $oDadosMatricula->turma_unificada = '';
                $oDadosMatricula->codigo_etapa_multi_etapa = '';
                $oDadosMatricula->recebe_escolarizacao_outro_espaco = '';
                $oDadosMatricula->transporte_escolar_publico = $oMatricula->ed47_i_transpublico;
                $oDadosMatricula->poder_publico_transporte_escolar = $oMatricula->ed47_c_transporte;
                $oDadosMatricula->forma_ingresso_aluno_escola_federal = '';
                $oDadosMatricula->turnoreferente = $oMatricula->ed231_i_referencia;
                $oDadosMatricula->tipo_turma = "AEE";

                $aMatriculaAEE[] = $oDadosMatricula;
            }

        }

        return $aMatriculaAEE;
    }

    /**
     * Busca os dados dos alunos vínculados em turmas AEE que não tenham matrícula na escola
     */
    protected function getDadosAEE()
    {

        $sCampos = $this->getCamposQueryGetDados();
        $sCampos .= " turmaac.ed268_i_codigoinep as ed57_i_codigoinep, ";
        $sCampos .= " turmaac.ed268_i_codigo     as codigo_turma, ";
        $sCampos .= " '' as codcensomatricula, ";
        $sCampos .= " '' as ed11_i_codcenso, ";
        $sCampos .= " turmaacmatricula.ed269_i_codigo as ed60_i_codigo, ";
        $sCampos .= " turnoreferente.ed231_i_referencia as turnoreferente, ";
        $sCampos .= " ed268_i_tipoatend as codigo_tipo_turma ";


        $sWhere = "     ed268_i_tipoatend = 5 ";
        $sWhere .= " and ed269_d_data  <= '{$this->sDataCenso}'";
        $sWhere .= " and ed52_i_ano     = {$this->iAnoCenso} ";
        $sWhere .= " and ed268_i_escola = {$this->iEscola} ";
        $sWhere .= " and ed47_i_codigo  = {$this->iCodigoAluno} ";


        $oDaoTurmaAEE = new cl_turmaacmatricula();
        $sSqlMatriculaAEE = $oDaoTurmaAEE->queryDadosAlunoCenso(null, $sCampos, null, $sWhere);
        $rsMatriculaAEE = db_query($sSqlMatriculaAEE);

        if (!$rsMatriculaAEE) {
            throw new DBException("Erro ao buscar dados do aluno {$this->iCodigoAluno} de turma AEE.\n" . pg_last_error());
        }

        $this->setDadosIdenficacao($rsMatriculaAEE);
        $this->setDocumentoEndereco($rsMatriculaAEE);
        $this->setDadosMatricula($rsMatriculaAEE);
        unset($rsMatriculaAEE);
    }

    /**
     * Retorna os dados do registro de tipo 70;
     * @return stdClass;
     */
    public function getDadosEnderecoDocumento($lTurmaNormal = true)
    {

        if (empty($this->oDadosDocumento) && $lTurmaNormal) {
            $this->getDados();
        }

        if (empty($this->oDadosDocumento) && !$lTurmaNormal) {
            $this->getDadosAEE();
        }

        return $this->oDadosDocumento;
    }

    /**
     * Retorna os dados do registro de tipo 80;
     * @return stdClass;
     */
    public function getDadosMatricula($lTurmaNormal = true)
    {

        if (empty($this->aDadosMatricula) && $lTurmaNormal) {
            $this->getDados();
        }

        if (empty($this->aDadosMatricula) && !$lTurmaNormal) {
            $this->getDadosAEE();
        }

        return $this->aDadosMatricula;
    }

    /**
     * Define o ano do censo
     */
    public function setAnoCenso($iAnoCenso)
    {
        $this->iAnoCenso = $iAnoCenso;
    }

    /**
     * Define a data do censo
     */
    public function setDataCenso($dtCenso)
    {
        $this->sDataCenso = $dtCenso;
    }

    /**
     * Realiza a atualização dos dados do aluno conforme os dados do censo
     */
    public function atualizarDados(DBLayoutLinha $oLinha)
    {

        $oDaoAluno = $this->preencherDaoAluno($oLinha);
        $oDaoAluno->ed47_i_codigo = $this->getCodigoAluno();
        $oDaoAluno->alterar($this->getCodigoAluno());
        if ($oDaoAluno->erro_status == '0') {
            throw new Exception("Erro na alteração dos dados do Aluno. Erro da classe: " . $oDaoAluno->erro_msg);
        }
        $this->atualizarNecessidadesEspeciais($oLinha);
    }

    /**
     * Preenche os dados da dao cl_aluno
     */
    protected function preencherDaoAluno(DBLayoutLinha $oLinha)
    {

        $oDaoAluno = db_utils::getdao('aluno');
        $oDaoAluno->ed47_i_censoorgemissrg = "";
        $oDaoAluno->ed47_i_censocartorio = "";
        $oDaoAluno->ed47_i_pais = "";
        $oDaoAluno->oid = "";
        $oDaoAluno->ed47_c_bolsafamilia = "N";

        if (!empty($oLinha->nome_mae)) {
            $oDaoAluno->ed47_v_mae = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_mae);
        }

        if (!empty($oLinha->filiacao_1)) {
            $oDaoAluno->ed47_v_mae = str_replace(array('ª', 'º'), array('', ''), $oLinha->filiacao_1);
        }

        if (!empty($oLinha->nome_pai)) {
            $oDaoAluno->ed47_v_pai = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_pai);
        }

        if (!empty($oLinha->filiacao_2)) {
            $oDaoAluno->ed47_v_pai = str_replace(array('ª', 'º'), array('', ''), $oLinha->filiacao_2);
        }

        if ($oLinha->data_nascimento != "") {
            $oDaoAluno->ed47_d_nasc = importacaoCenso::formataData($oLinha->data_nascimento);
        }

        if ($oLinha->sexo != "") {
            $oDaoAluno->ed47_v_sexo = $oLinha->sexo == 1 ? "M" : "F";
        }

        $oDaoAluno->ed47_c_raca = $this->getRaca(trim($oLinha->cor_raca));
        if ($oLinha->pais_origem != "") {
            $oDaoAluno->ed47_i_pais = importacaoCenso::getPais($oLinha->pais_origem);
        }

        if ($oLinha->nacionalidade_aluno != "") {
            $oDaoAluno->ed47_i_nacion = $oLinha->nacionalidade_aluno;
        } else {
            $oDaoAluno->ed47_i_nacion = 1;
        }
        if ($oLinha->uf_nascimento != "") {
            $oDaoAluno->ed47_i_censoufnat = $oLinha->uf_nascimento;
        }

        if ($oLinha->municipio_nascimento != "") {
            $oDaoAluno->ed47_i_censomunicnat = $oLinha->municipio_nascimento;
        }
        $oDaoAluno->ed47_c_codigoinep = $oLinha->identificacao_unica_aluno;
        $oDaoAluno->ed47_c_nis = $oLinha->numero_identificacao_social;
        $oDaoAluno->ed47_i_filiacao = $oLinha->filiacao;
        $oDaoAluno->ed47_c_atenddifer = '3';
        $oDaoAluno->ed47_v_ender = 'NAO INFORMADO';
        $oDaoAluno->ed47_i_transpublico = "";
        $oDaoAluno->ed47_situacaodocumentacao = 0;

        return $oDaoAluno;
    }

    /**
     * Converte a raça do censo para o formato do e-cidaade, retornando o nome da cor/raça.
     * @param integer $iCodigoRacaCenso Código da cor/raça do censo
     * @return string
     */
    public function getRaca($iCodigoRacaCenso)
    {

        $sRaca = '';
        switch (trim($iCodigoRacaCenso)) {

            case 1:
                $sRaca = 'BRANCA';
                break;

            case 2:

                $sRaca = 'PRETA';
                break;
            case 3:

                $sRaca = 'PARDA';
                break;

            case 4:

                $sRaca = 'AMARELA';
                break;

            case 5:

                $sRaca = 'INDÍGENA';
                break;

            default :

                $sRaca = 'NÃO DECLARADA';
                break;
        }

        return $sRaca;
    }

    protected function atualizarNecessidadesEspeciais(DBLayoutLinha $oLinha)
    {

        if (isset($oLinha->alunos_deficiencia_transtorno_desenv_superdotacao)) {

            $oDaoAlunoNecessidade = db_utils::getdao('alunonecessidade');
            $oDaoAlunoNecessidade->excluir(null, "ed214_i_aluno = {$this->getCodigoAluno()}");

            $aNecessidade = array();

            trim($oLinha->tipos_defic_transtorno_cegueira) == 1 ? $aNecessidade[] = 101 : '';
            trim($oLinha->tipos_defic_transtorno_baixa_visao) == 1 ? $aNecessidade[] = 102 : '';
            trim($oLinha->tipos_defic_transtorno_surdez) == 1 ? $aNecessidade[] = 103 : '';
            trim($oLinha->tipos_defic_transtorno_auditiva) == 1 ? $aNecessidade[] = 104 : '';
            trim($oLinha->tipos_defic_transtorno_surdocegueira) == 1 ? $aNecessidade[] = 105 : '';
            trim($oLinha->tipos_defic_transtorno_def_fisica) == 1 ? $aNecessidade[] = 106 : '';
            trim($oLinha->tipos_defic_transtorno_def_intelectual) == 1 ? $aNecessidade[] = 107 : '';
            trim($oLinha->tipos_defic_transtorno_def_multipla) == 1 ? $aNecessidade[] = 108 : '';
            trim($oLinha->tipos_defic_transtorno_def_autismo_infantil) == 1 ? $aNecessidade[] = 109 : '';
            trim($oLinha->tipos_defic_transtorno_def_asperger) == 1 ? $aNecessidade[] = 110 : '';
            trim($oLinha->tipos_defic_transtorno_def_sindrome_rett) == 1 ? $aNecessidade[] = 111 : '';
            trim($oLinha->tipos_defic_transtorno_desintegrativo_infancia) == 1 ? $aNecessidade[] = 112 : '';
            trim($oLinha->tipos_defic_transtorno_altas_habilidades) == 1 ? $aNecessidade[] = 113 : '';
            $iTam = count($aNecessidade);

            for ($iContNecessidade = 0; $iContNecessidade < $iTam; $iContNecessidade++) {

                if ($aNecessidade[$iContNecessidade] > 0) {

                    $oDaoAlunoNecessidade->ed214_i_necessidade = $aNecessidade[$iContNecessidade];
                    $oDaoAlunoNecessidade->ed214_c_principal = 'NAO';
                    $oDaoAlunoNecessidade->ed214_i_apoio = 1;
                    $oDaoAlunoNecessidade->ed214_d_data = 'null';
                    $oDaoAlunoNecessidade->ed214_i_tipo = 1;
                    $oDaoAlunoNecessidade->ed214_i_escola = 'null';
                    $oDaoAlunoNecessidade->ed214_i_aluno = $this->getCodigoAluno();
                    $oDaoAlunoNecessidade->incluir(null);

                    if ($oDaoAlunoNecessidade->erro_status == '0') {

                        throw new Exception("Erro na inclusão das necessidades do aluno. Erro da classe: " .
                          $oDaoAlunoNecessidade->erro_msg
                        );

                    }

                }
            }
        }
    }

    /**
     * Adiciona um novo aluno
     * @param DBLayoutLinha $oLinha
     */
    public function adicionarNovoAluno(DBLayoutLinha $oLinha)
    {

        $oDaoAluno = $this->preencherDaoAluno($oLinha);
        $oDaoAluno->ed47_c_atenddifer = '3';
        $oDaoAluno->ed47_v_ender = 'NAO INFORMADO';
        $oDaoAluno->ed47_i_transpublico = '0';
        $oDaoAluno->ed47_i_estciv = 1;
        if (!empty($oLinha->nome_completo)) {
            $oDaoAluno->ed47_v_nome = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo);
        }
        $oDaoAluno->incluir(null);
        if ($oDaoAluno->erro_status == '0') {
            throw new Exception("Erro na inclusão do novo Aluno. Erro da classe: " . $oDaoAluno->erro_msg);
        }

        $this->iCodigoAluno = $oDaoAluno->ed47_i_codigo;
        $this->atualizarNecessidadesEspeciais($oLinha);

        return true;
    }

    /**
     * funcao que seleciona e atualiza os dados de endereco  e documentos(certidao, identidade.) do aluno, registro 70
     *
     * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
     * @throws Exception
     */
    function atualizarEnderecoDocumentos($oLinha)
    {

        $oDaoAluno = new cl_aluno($this->getCodigoAluno());
        $oDaoAluno->ed47_v_ident = $oLinha->numero_identidade;

        if (isset($oLinha->complemento_identidade) && trim($oLinha->complemento_identidade) != '') {
            $oDaoAluno->ed47_v_identcompl = $oLinha->complemento_identidade;
        }

        $oDaoAluno->ed47_i_censoorgemissrg = $oLinha->orgao_emissor_identidade;
        $oDaoAluno->ed47_i_censoufident = $oLinha->uf_identidade;

        if (trim($oLinha->data_expedicao_identidade) != "") {
            $oDaoAluno->ed47_d_identdtexp = importacaoCenso::formataData($oLinha->data_expedicao_identidade);
        }

        $oDaoAluno->ed47_c_certidaotipo = '';

        if ($oLinha->tipo_certidao_civil == 1) {
            $oDaoAluno->ed47_c_certidaotipo = 'N';
        } else if ($oLinha->tipo_certidao_civil == 2) {
            $oDaoAluno->ed47_c_certidaotipo = 'C';
        }

        $oDaoAluno->ed47_c_certidaonum = $oLinha->numero_termo;
        $oDaoAluno->ed47_c_certidaofolha = $oLinha->folha;
        $oDaoAluno->ed47_c_certidaolivro = $oLinha->livro;

        if (trim($oLinha->data_emissao_certidao) != "") {
            $oDaoAluno->ed47_c_certidaodata = importacaoCenso::formataData($oLinha->data_emissao_certidao);
        }

        $oDaoAluno->ed47_i_censocartorio = importacaoCenso::getCartorio($oLinha->codigo_cartorio, null);
        $oDaoAluno->ed47_i_censoufcert = $oLinha->uf_cartorio;
        $oDaoAluno->ed47_v_cpf = $oLinha->numero_cpf;

        if ($oLinha->certidao_civil == 2) {

            $iCartorio = substr($oLinha->numero_matricula, 0, 6);
            $oDaoAluno->ed47_i_censocartorio = importacaoCenso::getCartorio(null, null, $iCartorio);
            $sTipoAcervo = substr($oLinha->numero_matricula, 6, 2);
            $sNumeroServico = substr($oLinha->numero_matricula, 8, 2);
            $sAnoRegistro = substr($oLinha->numero_matricula, 10, 4);
            $sTipoLivro = substr($oLinha->numero_matricula, 14, 1);
            $oDaoAluno->ed47_c_certidaolivro = substr($oLinha->numero_matricula, 15, 5);
            $oDaoAluno->ed47_c_certidaofolha = substr($oLinha->numero_matricula, 20, 3);
            $oDaoAluno->ed47_c_certidaonum = substr($oLinha->numero_matricula, 23, 7);
            $sCodigoVerificador = substr($oLinha->numero_matricula, 30, 2);
            $oDaoAluno->ed47_certidaomatricula = $oLinha->numero_matricula;
        }

        $oDaoAluno->ed47_c_passaporte = $oLinha->documento_estrangeiro_passaporte;

        if (isset($oLinha->numero_identificacao_social) && trim($oLinha->numero_identificacao_social) != '') {
            $oDaoAluno->ed47_c_nis = $oLinha->numero_identificacao_social;
        }

        if ($oLinha->localizacao_zona_residencia == 1) {
            $oDaoAluno->ed47_c_zona = 'URBANA';
        } else {
            $oDaoAluno->ed47_c_zona = 'RURAL';
        }

        if (!empty($oLinha->cep)) {

            $oDaoAluno->ed47_v_cep = $oLinha->cep;
            $oDaoAluno->ed47_v_ender = $oLinha->endereco;
            $oDaoAluno->ed47_c_numero = $oLinha->numero;
            $oDaoAluno->ed47_v_compl = $oLinha->complemento;
            $oDaoAluno->ed47_v_bairro = substr($oLinha->bairro, 0, 40);
            $oDaoAluno->ed47_i_censoufend = $oLinha->uf;

            if (trim($oLinha->municipio) != "") {
                $oDaoAluno->ed47_i_censomunicend = importacaoCenso::getCensoMunicipioCertidao($oLinha->municipio);
            }
        }

        $oDaoAluno->ed47_i_censomuniccert = $oLinha->municipio_cartorio;
        $oDaoAluno->ed47_i_codigo = $this->getCodigoAluno();

        $oDaoAluno->alterar($this->getCodigoAluno());

        if ($oDaoAluno->erro_status == '0') {
            throw new Exception("Erro na alteração do endereço do aluno.Erro da classe: " . $oDaoAluno->erro_msg);
        }

        if (!empty($oDaoAluno->ed47_v_bairro)) {

            $oDaoBairro = db_utils::getdao('bairro');
            $sWhereBairro = "to_ascii(j13_descr,'LATIN1') = '{$oDaoAluno->ed47_v_bairro}'";
            $sSqlBairro = $oDaoBairro->sql_query_file("", "j13_codi", "", $sWhereBairro);
            $rsBairro = $oDaoBairro->sql_record($sSqlBairro);

            if ($oDaoBairro->numrows > 0) {

                $oDaoBairroAluno = db_utils::getdao('alunobairro');
                $oDaoBairroAluno->excluir(null, "ed225_i_aluno = {$this->iCodigoAluno}");
                $oDaoBairroAluno->ed225_i_aluno = $this->getCodigoAluno();
                $oDaoBairroAluno->ed225_i_bairro = db_utils::fieldsmemory($rsBairro, 0)->j13_codi;
                $oDaoBairroAluno->incluir(null);

                if ($oDaoBairroAluno->erro_status == '0') {
                    throw new Exception("Erro na alteração do bairro do aluno. Erro da classe: " . $oDaoBairroAluno->erro_msg);
                }//fecha o if erro_status
            }
        }
    }

    /**
     * Atualiza os dados dos transportes utilizados pelo aluno
     */
    public function atualizarDadosTransporte($oLinha)
    {

        $oDaoAluno = db_utils::getdao('aluno');
        $oDaoAluno->ed47_c_atenddifer = $oLinha->recebe_escolarizacao_outro_espaco;
        $oDaoAluno->ed47_i_transpublico = $oLinha->transporte_escolar_publico;
        if ($oLinha->transporte_escolar_publico == "") {
            $oLinha->poder_publico_transporte_escolar = "0";
        }
        $oDaoAluno->ed47_c_transporte = "{$oLinha->poder_publico_transporte_escolar}";
        $oDaoAluno->ed47_i_codigo = $this->getCodigoAluno();
        $oDaoAluno->alterar($this->getCodigoAluno());
        if ($oDaoAluno->erro_status == '0') {
            throw new Exception("Erro na alteração dos dados adicionais do aluno. Erro da classe: " . $oDaoAluno->erro_msg);
        }

        $oDaoAlunoCensoTransporte = db_utils::getDao("alunocensotipotransporte");
        $oDaoAlunoCensoTransporte->excluir(null, "ed311_aluno={$this->getCodigoAluno()}");
        if ($oDaoAlunoCensoTransporte->erro_status == 0) {
            throw new Exception("Erro na alteração dos dados de transportes do aluno. Erro da classe: " . $oDaoAluno->erro_msg);
        }
        /**
         * Atualizamos os transportes utilizados pelos alunos para a locomoção até a escola.
         */
        $aTransportes = array();
        $oLinha->rodoviario_vans_kombi == 1 ? $aTransportes[] = 1 : '';
        $oLinha->rodoviario_microonibus == 1 ? $aTransportes[] = 2 : '';
        $oLinha->rodoviario_onibus == 1 ? $aTransportes[] = 3 : '';
        $oLinha->rodoviario_bicicleta == 1 ? $aTransportes[] = 4 : '';
        $oLinha->rodoviario_tracao_animal == 1 ? $aTransportes[] = 5 : '';
        $oLinha->rodoviario_outro == 1 ? $aTransportes[] = 6 : '';
        $oLinha->aquaviario_embarcacao_5_pessoas == 1 ? $aTransportes[] = 7 : '';
        $oLinha->aquaviario_embarcacao_5_a_15_pessoas == 1 ? $aTransportes[] = 8 : '';
        $oLinha->aquaviario_embarcacao_15_a_35_pessoas == 1 ? $aTransportes[] = 9 : '';
        $oLinha->aquaviario_embarcacao_mais_de_35_pessoas == 1 ? $aTransportes[] = 10 : '';
        $oLinha->ferroviario_trem_metro == 1 ? $aTransportes[] = 11 : '';

        foreach ($aTransportes as $iTipoTransporte) {

            $oDaoAlunoCensoTransporte->ed311_aluno = $this->getCodigoAluno();
            $oDaoAlunoCensoTransporte->ed311_censotipotransporte = $iTipoTransporte;
            $oDaoAlunoCensoTransporte->incluir(null);
            if ($oDaoAlunoCensoTransporte->erro_status == 0) {
                throw new Exception("Erro na alteração dos dados de transportes do aluno. Erro da classe: " . $oDaoAluno->erro_msg);
            }
        }
    }

}

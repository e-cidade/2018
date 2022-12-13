<?php
/**
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("fpdf151/scpdf.php"));

define("MSG_SAU2EMITIRATESTADO", "saude.ambulatorial.sau2_emitiratestado.");

$oGet = DBString::utf8_decode_all(db_utils::postMemory($_GET));

try {

  if ( empty($oGet->iFaa) || empty($oGet->sNome)) {
    throw new Exception( _M( MSG_SAU2EMITIRATESTADO . "informe_paciente" ) );
  }

  if ( empty($oGet->dtAtendimento) && empty($oGet->sHora)) {
    throw new Exception( _M( MSG_SAU2EMITIRATESTADO . "informe_data_hora" ) );
  }

  $oUPS               = new UnidadeProntoSocorro(db_getsession('DB_coddepto'));
  $oDados             = new stdClass();
  $oDados->sUPS       = $oUPS->getDepartamento()->getNomeDepartamento();
  $oDados->sUF        = $oUPS->getDepartamento()->getInstituicao()->getUf();
  $oDados->sMunicipio = $oUPS->getDepartamento()->getInstituicao()->getMunicipio();

  unset($sUPS);

  $oDados->lInformouCID = !empty($oGet->sCID);

  /**
   * Busca as informações referentes ao médico
   */
  $sWhere     = " sd27_i_codigo = {$oGet->iEspecMedico} ";
  $sCampos    = " z01_nome, sd04_v_registroconselho, rh70_estrutural, rh70_descr, sd51_v_descricao ";
  $oDaoMedico = new cl_medicos();
  $sSqlMedico = $oDaoMedico->sql_query_vinculos(null, $sCampos, null, $sWhere);
  $rsMedico   = db_query($sSqlMedico);

  if ( !$rsMedico ) {

    $oMsgErro->sErro = pg_last_error();
    throw new DBException( _M( MSG_SAU2EMITIRATESTADO . "erro_buscar_dados_medico", $oMsgErro ) );
  }

  if ( pg_num_rows($rsMedico) == 0 ) {
    throw new DBException( _M( MSG_SAU2EMITIRATESTADO . "dados_medicos_nao_encontrados") );
  }

  /**
   * Preenche as informações do médico
   */
  $oDadosMedico          = db_utils::fieldsMemory($rsMedico, 0);
  $oDados->oData         = new DBDate($oGet->dtAtendimento);
  $oDados->sMedico       = $oDadosMedico->z01_nome;
  $oDados->iDocumentos   = $oDadosMedico->sd04_v_registroconselho;
  $oDados->sOrgaoEmissor = $oDadosMedico->sd51_v_descricao;
  $oDados->iCBO          = $oDadosMedico->rh70_estrutural;
  $oDados->sCBO          = $oDadosMedico->rh70_descr;
  $oDados->sDataExtenso  = "{$oDados->sMunicipio}-{$oDados->sUF}, {$oDados->oData->dataPorExtenso()}.";

  $oPdf = new scpdf();

  $oPdf->Open();
  $oPdf->setMargins(10, 8);
  $oPdf->SetFont('Arial','B',8);
  $oPdf->addPage();

  /**
   * Cabeçalho padrão dos atestados
   */
  $oPdf->Cell(0, 3.2, "MINISTÉRIO DA SAÚDE", 0, 1);
  $oPdf->Cell(0, 3.2, "MUNICÍPIO DE {$oDados->sMunicipio} - {$oDados->sUF}", 0, 1);
  $oPdf->Cell(0, 3.2, "{$oDados->sUPS}", 0, 1);
  $oPdf->Line(10, 22, 195, 22);

  $oPdf->Image("imagens/sus.jpg", 170, 8, 23);

  /**
   * Valida qual modelo de atestado deve ser impresso
   */
  switch($oGet->iTipoAtestado) {

    case 1:

      atestadoPadrao($oPdf, $oGet, $oDados);
      break;

    case 2:

      atestadoEmBranco($oPdf, $oGet, $oDados);
      break;
  }

  imprimeAssinatura($oPdf, $oDados);

  $oPdf->Output();
} catch (Exception $oErro) {

  $sErro = str_replace("\n", "",  $oErro->getMessage() );
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sErro);
}
/**
 * Imprime o atestado padrão
 * @param FPDF $oPdf
 * @param $oGet
 * @param $oDados
 * @throws DBException
 * @throws Exception
 */
function atestadoPadrao(FPDF $oPdf, $oGet, $oDados) {

  if ( empty($oGet->iDocumento) ) {
    throw new Exception( _M( MSG_SAU2EMITIRATESTADO . "informe_documento" ) );
  }

  if ( empty($oGet->iDias) ) {
    throw new Exception( _M( MSG_SAU2EMITIRATESTADO . "informe_dias_afastamento" ) );
  }

  if ( empty($oGet->iEspecMedico) ) {
    throw new Exception( _M( MSG_SAU2EMITIRATESTADO . "informe_cbo" ) );
  }

  $oProntuario  = new Prontuario($oGet->iFaa);
  $oDados->sCPF = $oProntuario->getCGS()->getCpf();
  $oDados->sCNS = $oProntuario->getCGS()->getCartaoSusAtivo();

  unset($oProntuario);

  $DAOEstado = new cl_cadenderestado();
  $sWhere    = "db71_sigla = '" . $oDados->sUF . "'";
  $sQuery    = $DAOEstado->sql_query_file(null, "db71_descricao", null, $sWhere);
  $rsEstado  = db_query($sQuery);
  $oMsgErro  = new stdClass();

  if (!$rsEstado) {

    $oMsgErro->sErro = pg_last_error();
    throw new DBException( _M( MSG_SAU2EMITIRATESTADO . "erro_buscar_estado", $oMsgErro ) );
  }

  if ( pg_num_rows($rsEstado) == 0 ) {

    $oMsgErro->sErro = $oDados->sUF;
    throw new DBException( _M( MSG_SAU2EMITIRATESTADO . "estado_nao_encontrado", $oMsgErro ) );
  }

  $oDados->sEstado = db_utils::fieldsMemory($rsEstado, 0)->db71_descricao;

  /**
   * 5024 - ATESTADO MÉDICO
   *
   *  ------------ Variáveis ---------------
   * $nome              - Nome do paciente
   * $documento         - "portador do documento " + documento selecionado a ser apresentado
   * $ups               - Unidade de atendimento da FAA
   * $data              - Data da consulta
   * $hora              - Hora da consulta
   * $cid               - ", com diagnóstico segundo CID " + estrutural do CID( sd70_c_cid )
   * $dias              - Número de dias informado antes da impressão
   * $nome_paciente     - Nome do paciente
   * $nome_profissional - Nome do Médico
   * ---------------------------------------
   */
  $oLibDocumento       = new libdocumento(5024,null);
  $oLibDocumento->nome = $oGet->sNome;
  $oLibDocumento->ups  = $oDados->sUPS;
  $oLibDocumento->data = $oGet->dtAtendimento;
  $oLibDocumento->hora = $oGet->sHora;
  $oLibDocumento->dias = $oGet->iDias . " (" . DBString::numeroPorExtenso($oGet->iDias) . ") dia(s)";

  switch ($oGet->iDocumento) {

    case '1':

      $oLibDocumento->documento = "portador do CNS " . $oDados->sCNS;
      break;

    case '2':

      $oLibDocumento->documento = "portador do CPF " . $oDados->sCPF;
      break;
  }

  if ($oDados->lInformouCID) {

    $oLibDocumento->cid               = ", com diagnóstico segundo CID {$oGet->sCID}";
    $oLibDocumento->nome_paciente     = $oGet->sNome;
    $oLibDocumento->nome_profissional = $oDados->sMedico;
  }

  $oDados->aParagrafos = $oLibDocumento->getDocParagrafos();

  // escrita do cabeçalho
  $oPdf->SetY(40);
  $oPdf->SetFont('Arial', 'B', 15);
  $oPdf->Cell(0, 5, $oDados->aParagrafos[1]->oParag->db02_texto, 0, 1, 'C');

//escrita do texto
  $oPdf->SetY( $oPdf->GetY() + 15);
  $oPdf->SetFont('Arial', '', 10);
  $oPdf->MultiCell(0, 5, $oDados->aParagrafos[2]->oParag->db02_texto, 0, 'J');
}

/**
 * Imprime o atestado padrão, com o contéudo digitado pelo médico
 * @param FPDF $oPdf
 * @param $oGet
 * @param $oDados
 */
function atestadoEmBranco(FPDF $oPdf, $oGet, $oDados) {

  $oPdf->SetY(40);
  $oPdf->SetFont('arial', 'b', 15);
  $oPdf->Cell(0, 4, 'ATESTADO', 0, 1, 'C');

  $oPdf->SetY(60);
  $oPdf->SetFont('arial', '', 10);
  $oPdf->MultiCell(0, 5, $oGet->sConteudo, 0, 'J');

  $oMovimentacao = new MovimentacaoFichaAtendimento($oGet->iMovimentacao);
  $oMovimentacao->setData($oDados->oData);
  $oMovimentacao->setFichaAtendimento($oGet->iFaa);
  $oMovimentacao->setHora($oGet->sHora);
  $oMovimentacao->setObservacao($oGet->sConteudo);
  $oMovimentacao->setSetorAmbulatorial(new SetorAmbulatorial($oGet->iSetorAmbulatorial));
  $oMovimentacao->setSituacao(MovimentacaoFichaAtendimento::SITUACAO_ATESTADO_EM_BRANCO);
  $oMovimentacao->setUsuarioSistema(new UsuarioSistema(db_getsession("DB_id_usuario")));
  $oMovimentacao->salvar();
}

/**
 * Imprime a assinatura do médico
 * @param FPDF $oPdf
 * @param $oDados
 */
function imprimeAssinatura(FPDF $oPdf, $oDados) {

  //escrita da data
  $oPdf->SetY( $oPdf->GetY() + 30);
  $oPdf->Cell(0, 5, $oDados->sDataExtenso, 0, 1, 'R');

//escrita assinatura

  $oPdf->SetY( $oPdf->GetY() + 15);
  $oPdf->SetFont('Arial', '', 8);

  $iPosicaoLinhaAssinatura =  $oPdf->w / 3;
  $oPdf->Line($iPosicaoLinhaAssinatura, $oPdf->GetY(), $iPosicaoLinhaAssinatura * 2, $oPdf->GetY());
  $oPdf->Cell(0, 4, $oDados->sMedico, 0, 1, 'C');
  $oPdf->Cell(0, 4, "{$oDados->sCBO}", 0, 1, 'C');
  $oPdf->Cell(0, 4, "{$oDados->sOrgaoEmissor} {$oDados->iDocumentos}", 0, 1, 'C');

  if ( $oDados->lInformouCID ) {

    $oPdf->SetFont('Arial', '', 10);
    $oPdf->SetY( $oPdf->GetY() + 35);
    $oPdf->MultiCell(0, 5, $oDados->aParagrafos[3]->oParag->db02_texto, 0, 'J');
    $oPdf->SetY( $oPdf->GetY() + 25);

    $oPdf->SetFont('Arial', '', 8);
    $oPdf->Line($iPosicaoLinhaAssinatura, $oPdf->GetY(), $iPosicaoLinhaAssinatura * 2, $oPdf->GetY());
    $oPdf->Cell(0, 4, "ASSINATURA DO CIDADÃO OU RESPONSÁVEL", 0, 1, 'C');
  }
}
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once("fpdf151/scpdf.php");

$oGet = db_utils::postMemory($_GET);

$oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession("DB_coddepto"));

//Dados instituicao
$oDaoDBConfig = new cl_db_config();
$sCampos      = "nomeinst, logo, ender, munic, telef, email ";
$sSqlConfig   = $oDaoDBConfig->sql_query_file(db_getsession("DB_instit"), $sCampos);
$rsConfig     = db_query($sSqlConfig);

if ( !$rsConfig ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao buscar os dados da Instituição.');
}

$oDadosInstituicao = db_utils::fieldsMemory($rsConfig, 0);

//Dados paciente
$oDaoProntuario    = new cl_prontuarios();
$sCamposProntuario = " z01_nome as medico, z01_v_nome as paciente, sd103_data, sd103_hora, sd103_observacao, la08_c_descr ";
$sSqlProntuario    = $oDaoProntuario->sql_query_requisicao_exames($oGet->iProntuario, $sCamposProntuario);
$rsProntuario      = db_query($sSqlProntuario);

if ( !$rsProntuario ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao buscar os dados da Requisição de Exames.');
}

$iLinhas = pg_num_rows($rsProntuario);

if ( $iLinhas == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum exame cadastrado para esta FAA.');
}

$oDadosRequisicao = new stdClass();
for ($i = 0; $i < $iLinhas; $i++) {

  $oDados = db_utils::fieldsMemory($rsProntuario, $i);

  $oDadosRequisicao->sMedico     = $oDados->medico;
  $oDadosRequisicao->sPaciente   = $oDados->paciente;
  $oDadosRequisicao->oData       = new DBDate($oDados->sd103_data);
  $oDadosRequisicao->sHora       = $oDados->sd103_hora;
  $oDadosRequisicao->sObservacao = $oDados->sd103_observacao;
  $oDadosRequisicao->aExame[]    = $oDados->la08_c_descr;
}

$oPdf = new scpdf();
$oPdf->Open();
//Configuracoes iniciais
$oPdf->AliasNbPages();
$oPdf->AddPage();
$oPdf->SetMargins(15, 10);
$oPdf->SetAutoPageBreak(true, '10');
// $oPdf->line(2,148.5,208,148.5);
$oPdf->setfillcolor(245);
//Retangulo principal
$oPdf->RoundedRect(10, 10, 192, 279,  2, '','1234');
//Retangulo do CGS
$oPdf->RoundedRect(12, 30, 188, 14,   2, '','1234');
//Retangulo das requisições
$oPdf->RoundedRect(12, 46, 188, 200,   2, '','1234');
//Retangulo de Obs.
$oPdf->RoundedRect(12, 248, 94, 39,   2, '','1234');

$oPdf->cell( 20, 13, $oPdf->Image('imagens/files/' . $oDadosInstituicao->logo, 16, 11, 13), 0, 0, 'L', false );
//Cabecalho
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(20, 4,$oDadosInstituicao->nomeinst, 0, 0, 'L', false);
$oPdf->cell(140, 4,'RECIBO DE REQUISIÇÃO DE EXAMES', 0, 1, 'R',false);
$oPdf->Setfont('Arial', '', 8);
$oPdf->cell(51, 4,$oDadosInstituicao->ender, 0, 1, 'R', false);
$oPdf->cell(32.2, 4,$oDadosInstituicao->munic, 0, 1, 'R', false);
$oPdf->cell(34, 4,$oDadosInstituicao->telef, 0, 1, 'R', false);
$oPdf->cell(54.2, 4,$oDadosInstituicao->email, 0, 1, 'R', false);
$oPdf->ln(5);
//1º Retangulo interno
//1ª Linha
$oPdf->SetY(32);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(21, 4,'CGS:', 0, 0);
$oPdf->Setfont('Arial', '', 8);
$oPdf->cell(75, 4,$oDadosRequisicao->sPaciente, 0, 0);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(10, 4,'Hora:', 0, 0);
$oPdf->Setfont('Arial', '', 8);
$oPdf->cell(10, 4,$oDadosRequisicao->sHora, 0, 0);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(10, 4,'Data:', 0, 0);
$oPdf->Setfont('Arial', '', 8);
$oPdf->cell(10, 4,$oDadosRequisicao->oData->convertTo(DBDate::DATA_PTBR), 0, 1);
//2ª Linha
$oPdf->SetY(38);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(21, 4,'Departamento:', 0, 0);
$oPdf->Setfont('Arial', '', 8);
$oPdf->cell(75, 4,$oDepartamento->getNomeDepartamento(), 0, 0);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(18, 4,'Profissional:', 0, 0);
$oPdf->Setfont('Arial', '', 8);
$oPdf->cell(80, 4,$oDadosRequisicao->sMedico, 0, 1);

//2º Retangulo interno (Requisicoes)
//Cabecalho
$oPdf->SetY(48);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(182, 4,'Exames', "B", 0);
$oPdf->ln(5);
$oPdf->Setfont('Arial', '', 8);
foreach($oDadosRequisicao->aExame as $sExame) {
  $oPdf->cell(180, 5,$sExame, 0, 1);
}
//3º Retangulo interno (Obs)
$oPdf->SetY(249);
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->cell(8, 4,'Observação', 0, 1);
$oPdf->Setfont('Arial', '', 7);
$oPdf->MultiCell(91, 3.5, substr($oDadosRequisicao->sObservacao, 0, 550), 0);

//Assinatura, Local e Data.
$oPdf->Setfont('Arial', 'B', 8);
$oPdf->line(120,265,190,265);
$oPdf->SetY(265);
$oPdf->SetX(120);
$oPdf->cell(70, 6,'Assinatura do Profissional', 0, 1, 'C');

$oPdf->SetX(120);

$oData = new DBDate( date('Y-m-d') );
$oPdf->cell(70, 6,$oDadosInstituicao->munic . ", " . $oData->dataPorExtenso(), 0, 0, 'C');
$oPdf->Output("arquivo.pdf");

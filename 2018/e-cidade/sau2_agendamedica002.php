<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
 * $Id: sau2_agendamedica002.php,v 1.5 2016/09/13 16:31:57 dbrafael.nery Exp $                                
 */
require_once modification("fpdf151/pdf.php");
require_once modification("libs/db_sql.php");
require_once modification("libs/db_utils.php");

define("FONTE",  7);
define("ALTURA", 4.2);
define("QUEBRA", true);
define("BORDA",  true);
define("FUNDO",  true);

$oGet                 = db_utils::postMemory($_GET);
$parametros           = JSON::create()->parse(urldecode($oGet->parametros));
$oDao                 = new cl_undmedhorario();
$parametros->dtInicio = new DBDate($parametros->dtInicio);
$parametros->dtFim    = new DBDate($parametros->dtFim);

$head2  = "Relatório da agenda médica do profissional";
$head3  = mb_strtoupper("    {$parametros->iProfissional} - {$parametros->sProfissional}");
$head6  = "Período";
$head7  = "    De {$parametros->dtInicio->getDate(DBDate::DATA_PTBR)} ";
$head7 .= " até {$parametros->dtFim->getDate(DBDate::DATA_PTBR)} ";


$rsDados = db_query($sSql = $oDao->sql_query_agenda_medico(
  $parametros->dtInicio->getDate(),
  $parametros->dtFim->getDate(),
  $parametros->iProfissional,
  db_getsession("DB_coddepto"),
  array_map(function($oDadosEspecialidade){
    return $oDadosEspecialidade->iEspecialidade;
  }, $parametros->aEspecialidades)
));

if (!$rsDados) {
  db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode("Erro ao buscar os dados da agenda médica."));
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setAutoPageBreak(false);
$pdf->setFillColor(222);

$pdf->addpage('L');


$dados = tratarDados($rsDados);

foreach ($dados as $especialidade => $registros) {
  criarCabecalho($pdf, $especialidade);

  foreach ($registros as $registro) {
    criarRegistro($pdf,
      $registro["data"],
      $registro["tipo_ficha"],
      $registro["hora_inicio"],
      $registro["hora_fim"],
      $registro["fichas"],
      $registro["reservas"],
      $registro["motivo"]
    );
  }


// "reservas"
  $pdf->ln(ALTURA);
}

$pdf->Output();

/**
 * Cria um novo registro de Cabecalho
 *
 * @param  PDF $pdf
 * @param  String $especialidade
 * @return void
 */
function criarCabecalho(&$pdf, $especialidade = null) {

  static $ultimoUtilizado;

  if (verificarQuebra($pdf, ALTURA * 3)) {
    $pdf->addPage('L');
  }

  if(!!$especialidade) {
    $ultimoUtilizado = $especialidade;
  }

  /**
   * Descrição da especialidade
   */
  $pdf->setfont('arial', 'B', 8);
  $pdf->cell(tamanhoColuna("10%"), ALTURA, 'ESPECIALIDADE:', "TBL", !QUEBRA, "L", FUNDO);
  $pdf->setfont('arial', '', 8);
  $pdf->cell(tamanhoColuna("90%"), ALTURA, mb_strtoupper($ultimoUtilizado), "TBR", QUEBRA ,"L", FUNDO);
  /**
   * Cabeçalho das informações
   */
  $pdf->setfont('arial', 'B', 8);
  $pdf->cell(tamanhoColuna("13%"), ALTURA, 'DIA',         true, !QUEBRA ,"C", FUNDO);
  $pdf->cell(tamanhoColuna("25%"), ALTURA, 'TIPO FICHA',  true, !QUEBRA ,"C", FUNDO);
  $pdf->cell(tamanhoColuna("8%"),  ALTURA, 'HORA INÍCIO', true, !QUEBRA ,"C", FUNDO);
  $pdf->cell(tamanhoColuna("8%"),  ALTURA, 'HORA FIM',    true, !QUEBRA ,"C", FUNDO);
  $pdf->cell(tamanhoColuna("5%"),  ALTURA, 'FICHAS',      true, !QUEBRA ,"C", FUNDO);
  $pdf->cell(tamanhoColuna("7%"),  ALTURA, 'RESERVAS',    true, !QUEBRA ,"C", FUNDO);
  $pdf->cell(tamanhoColuna("34%"), ALTURA, 'AUSÊNCIA',    true,  QUEBRA ,"C", FUNDO);
  $pdf->setfont('arial', '', 8);
}

function criarRegistro(&$pdf, $data, $tipoFicha, $horaInicial, $horaFinal, $numeroFichas, $reservas, $observacoes) {

  if (verificarQuebra($pdf, ALTURA)) {
    $pdf->addPage('L');
    criarCabecalho($pdf);
  }

  $pdf->setfont('arial', '', 8);
  $pdf->cell(tamanhoColuna("13%"), ALTURA, $data,         true, !QUEBRA ,"L", !FUNDO);
  $pdf->cell(tamanhoColuna("25%"), ALTURA, $tipoFicha,    true, !QUEBRA ,"L", !FUNDO);
  $pdf->cell(tamanhoColuna("8%"),  ALTURA, $horaInicial,  true, !QUEBRA ,"C", !FUNDO);
  $pdf->cell(tamanhoColuna("8%"),  ALTURA, $horaFinal,    true, !QUEBRA ,"C", !FUNDO);
  $pdf->cell(tamanhoColuna("5%"),  ALTURA, $numeroFichas, true, !QUEBRA ,"C", !FUNDO);
  $pdf->cell(tamanhoColuna("7%"),  ALTURA, $reservas,     true, !QUEBRA ,"C", !FUNDO);
  $pdf->cell(tamanhoColuna("34%"), ALTURA, $observacoes,  true,  QUEBRA ,"L", !FUNDO);
  $pdf->setfont('arial', '', 8);
}

function verificarQuebra(&$pdf, $tamanhoConteudo = ALTURA) {

  $margem = 10;
  $posicaoEstimada = $pdf->getY() + $tamanhoConteudo;
  $posicaoLimite = $pdf->h - $margem;

  if ($posicaoEstimada > $posicaoLimite) {
    return true;
  }
  return false;
}

function tamanhoColuna($percentual) {

  $tamanhoMaximo = 279;
  $percentual = +$percentual;

  return ($tamanhoMaximo * $percentual) / 100;
}


function tratarDados($rsDados) {

  $dados = array();
  db_utils::makeCollectionFromRecord($rsDados, function($registro) use (&$dados) {

     $dados[$registro->especialidade][] = array(

      "data"        => trim(db_formatar($registro->data_atendimento, "d") . " - " . $registro->dia_semana),
      "tipo_ficha"  => trim($registro->tipo_ficha),
      "hora_inicio" => $registro->hora_inicial,
      "hora_fim"    => $registro->hora_final,
      "fichas"      => $registro->fichas,
      "reservas"    => $registro->reservas,
      "motivo"      => $registro->motivo_afastamento,
      // $registro->data_afastamento,
      // $registro->hora_inicio_afastamento,
      // $registro->hora_fim_afastamento,
    );
  });
  return $dados;
}

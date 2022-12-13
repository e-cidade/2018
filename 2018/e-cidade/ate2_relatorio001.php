<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


require ('fpdf151/pdf.php');
include ("classes/db_tecnico_classe.php");
include ("classes/db_clientes_classe.php");
include ("classes/db_atendimento_classe.php");
include ("classes/db_atenditem_classe.php");
include ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clclientes = new cl_clientes;
$cltecnico = new cl_tecnico;
$clatendimento = new cl_atendimento;
$clatenditem = new cl_atenditem;
$clrotulo = new rotulocampo;


$clrotulo->label('nome');
$clrotulo->label('at01_codcli');
$clrotulo->label('at02_dataini');
$clrotulo->label('at02_datafim');
//echo $clatendimento->sql_query($at02_codatend);exit;

$result = $clatendimento->sql_record($clatendimento->sql_query($at02_codatend));
// db_criatabela($result);exit;


db_fieldsmemory($result, 0);


$where = "at03_codatend = $at02_codatend";
$result1 = $cltecnico->sql_record($cltecnico->sql_query_usuarios($at02_codatend, '', '*', '', $where));
$result = $clclientes->sql_record($clclientes->sql_query($at01_codcli));

db_fieldsmemory($result, 0);

$data = getdate();
$mes = db_formatar($data['month'], 's', 0, 2, 'e');
$mes1 = db_formatar($data['mon'], 's', 0, 2, 'e');
$dia = db_formatar($data['mday'], 's', 0, 2, 'e');
$ano = db_formatar($data['year'], 's', 0, 2, 'e');
$hora = db_formatar($data['hours'], 's', 0, 2, 'e');
$min = db_formatar($data['minutes'], 's', 0, 2, 'e');
$sec = db_formatar($data['seconds'], 's', 0, 2, 'e');

//db_criatabela($result);exit;

$pdf = new PDF(); // abre a classe
$head1 = "RELATÓRIO DE VISITAS";
$Letra = 'arial';
$pdf->SetFont($Letra, 'B', 8);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->Ln(5);
$pdf->Cell(3, 1, "CLIENTE: ", 0, 0, "L", 0);
$pdf->Ln(2);
$pdf->SetFont($Letra, '', 7);
$pdf->MultiCell(0, 6, 'NOME: '.$at01_nomecli, 0, "J", 0, 30);
$pdf->MultiCell(0, 6, 'CIDADE: '.$at01_cidade, 0, "J", 0, 30);
$pdf->MultiCell(0, 6, 'ENDEREÇO: '.$at01_ender, 0, "J", 0, 30);
$pdf->MultiCell(0, 6, '', 'B', '', 0, 30);
$pdf->Ln(3);
$pdf->SetFont($Letra, 'B', 8);
if ($at02_solicitado != "") {
	$pdf->Cell(3, 1, "ASSUNTO: ", 0, 0, "L", 0);
	$pdf->Ln(3);
	$pdf->SetFont($Letra, 'I', 7);
	$pdf->MultiCell(0, 6, ''.$at02_solicitado, 0, "J", 0, 30);
	$pdf->MultiCell(0, 6, '', 'B', '', 0, 30);
	$pdf->Ln(3);
}
if ($at02_feito != "") {
	$pdf->SetFont($Letra, 'B', 8);
	$pdf->Cell(3, 1, "SERVIÇOS EFETUADOS: ", 0, 0, "L", 0);
	$pdf->SetFont($Letra, 'I', 7);
	$pdf->Ln(3);
	$pdf->MultiCell(0, 6, ''.$at02_feito, 0, "J", 0, 30);
	$pdf->MultiCell(0, 6, '', 'B', '', 0, 30);
	$pdf->Ln(3);
}
if ($at02_observacao != "") {
	$pdf->SetFont($Letra, 'B', 8);
	$pdf->Cell(3, 1, "OBSERVAÇÕES: ", 0, 0, "L", 0);
	$pdf->SetFont($Letra, 'I', 7);
	$pdf->Ln(3);
	$pdf->MultiCell(0, 6, ''.$at02_observacao, 0, "J", 0, 30);
	$pdf->MultiCell(0, 6, '', 'B', '', 0, 30);
	$pdf->Ln(3);
}
$pdf->SetFont($Letra, 'B', 8);
$pdf->Cell(3, 1, "TÉCNICO(S) DO ATENDIMENTO: ", 0, 0, "L", 0);
$pdf->Ln(3);
$pdf->SetFont($Letra, '', 7);
$pdf->SetFillColor(225);
if (pg_numrows($result1) > 1) {
	for ($i = 0; $i < pg_numrows($result1); $i ++) {
		db_fieldsmemory($result1, $i);
		$pdf->MultiCell(190, 4, ''.$nome, 0, "J", 0);
	}
} else {
	db_fieldsmemory($result1, 0);
	$pdf->MultiCell(190, 4, ''.$nome, 0, "J", 0);
}
$pdf->MultiCell(0, 6, '', 'B', '', 0, 30);
$pdf->Ln(5);
if ($pdf->GetY() > 240) {
	$pdf->AddPage();
	$pdf->Ln(40);
}
$result  = $clatenditem->sql_record($clatenditem->sql_query(null,"at05_solicitado,at05_feito",null,"at05_codatend = $at02_codatend"));
$numrows = $clatenditem->numrows;
if ($clatenditem->numrows > 0) {
	$posicao = $pdf->getY();
	$pdf->SetFont($Letra, 'B', 7);
	$pdf->SetFillColor(225);
	$pdf->Cell(90, 4, "SOLICITADO: ", 1, 0, "L", 1);
	$pdf->Cell(90, 4, "EXECUTADO: ", 1, 1, "L", 1);
	$pdf->SetWidths(array (90, 90));
	for ($x = 0; $x < $numrows; $x ++) {
		db_fieldsmemory($result, $x);
		$pdf->SetFont($Letra, 'I', 6);
		if ($pdf->GetY() > 240) {
			$pdf->AddPage();
			$pdf->Cell(90, 4, "SOLICITADO: ", 1, 0, "L", 1);
			$pdf->Cell(90, 4, "EXECUTADO: ", 1, 1, "L", 1);
		}
		if(strlen($at05_solicitado) == 0) {
			$at05_solicitado = "";
		}
		if(strlen($at05_feito) == 0) {
		    $at05_feito = "";	
		}
		$pdf->Row(array ($at05_solicitado, $at05_feito), 3);
	}
}
//db_criatabela($result);exit;

$pdf->Ln(2);
$pos = $pdf->GetY();
$pos1 = $pdf->GetX();
if ($pdf->GetY() > 240) {
	$pdf->AddPage();
	$pdf->Ln(40);
}
$pdf->SetY($pos);
$pdf->SetX($pos1);
$pdf->SetFont($Letra, '', 7);
$pdf->Ln(10);
$pdf->MultiCell(190, 4, "INÍCIO DA VISITA: ".db_formatar($at02_dataini, 'd').' - '.$at02_horaini."    FINAL DA VISITA: ".db_formatar($at02_datafim, 'd').' - '.$at02_horafim, 0, "L", 0);
$pdf->Ln(5);
$pdf->MultiCell(0, 4, 'Porto Alegre, '.$dia.' de '.db_mes($mes1).' de '.$ano, 0, "L", 0);
$pdf->Ln(5);
$y = $pdf->getY();
$pdf->MultiCell(90, 6, '------------------------------------------------------------'."\n"."TÈCNICO", 0, "C", 0);
$pdf->SetY($y);
$pdf->setX(110);
$pdf->MultiCell(90, 6, '------------------------------------------------------------'."\n"."RESPONSÁVEL", 0, "C", 0);
$pdf->output();
?>
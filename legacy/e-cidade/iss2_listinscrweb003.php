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

require ('fpdf151/pdf1.php');
include ("dbforms/db_funcoes.php");
include ("classes/db_listainscrcab_classe.php");
include ("classes/db_listainscr_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_issbase_classe.php");

parse_str ( $HTTP_SERVER_VARS ['QUERY_STRING'] );
$cllistainscr = new cl_listainscr ( );
$cllistainscrcab = new cl_listainscrcab ( );
$clcgm = new cl_cgm ( );
$clissbase = new cl_issbase ( );
$clrotulo = new rotulocampo ( );
$clrotulo->label ( 'nome' );
$result = $cllistainscr->sql_record ( $cllistainscr->sql_query ( $p12_codigo ) );
$numrows = $cllistainscr->numrows;
$pdf = new PDF1 ( ); // abre a classe
$Letra = 'arial';
$pdf->SetFont ( $Letra, 'B', 8 );
$pdf->Open (); // abre o relatorio
$pdf->AliasNbPages (); // gera alias para as paginas
$pdf->AddPage (); // adiciona uma pagina
$pdf->SetTextColor ( 0, 0, 0 );
$pdf->SetFillColor ( 235 );
$sSql = "select codigo,
                 nomeinst,
                 ender,
                 munic,
                 uf,
                 telef,
                 email,
                 ident,
                 tx_banc,
                 numbanco,
                 url,
                 logo,
                 figura,
                 dtcont,
                 diario,
                 pref,
                 vicepref,
                 fax,
                 cgc,
                 cep,
                 bairro,
                 tpropri,
                 prefeitura,
                 tsocios
                   
            from db_config 
           where codigo = " . db_getsession ( 'DB_instit' );

//die($sSql);
$rsSql = pg_query ( $sSql );

if (pg_numrows ( $rsSql ) > 0) {
	db_fieldsmemory ( $rsSql, 0 );
}

$total = 0;
if ($cllistainscr->numrows > 0) {
	db_fieldsmemory ( $result, 0 );
	$result1 = $clcgm->sql_record ( $clcgm->sql_query ( $p11_numcgm ) );
	db_fieldsmemory ( $result1, 0 );
	$pdf->MultiCell ( 190, 4, "" . $z01_nome . ", vem pela presente requerer habilitação e/ou desabilitação de acesso via internet do Cadastro Fiscal do Município de " . @$munic . " ( " . @$url . "/dbpref) dos contribuintes a seguir relacionados conforme o tipo de lançamento:", 0, "J", 0, 15 );
	$pdf->ln ( 5 );
	$posicao = $pdf->getY ();
	$pdf->SetFont ( $Letra, 'B', 7 );
	$pdf->SetFillColor ( 235 );
	$pdf->Cell ( 25, 4, "INSCRIÇÃO ", 1, 0, "C", 1 );
	$pdf->Cell ( 35, 4, "CNPJ / CPF", 1, 0, "C", 1 );
	$pdf->Cell ( 26, 4, "TELEFONE ", 1, 0, "C", 1 );
	$pdf->Cell ( 70, 4, "NOME ", 1, 0, "C", 1 );
	$pdf->Cell ( 34, 4, "TIPO LANÇAMENTO ", 1, 1, "C", 1 );
	$pdf->SetFillColor ( 255 );
	for($x = 0; $x < $numrows; $x ++) {
		$total += $i;
		db_fieldsmemory ( $result, $x );
		$pdf->SetFont ( $Letra, 'I', 6 );
		$pdf->Cell ( 25, 4, "" . $p12_inscr, 1, 0, "C", 1 );
		$pdf->Cell ( 35, 4, "" . db_formatar ( $p12_cnpj, 'cnpj' ), 1, 0, "C", 1 );
		$pdf->Cell ( 26, 4, "" . $p12_fone, 1, 0, "C", 1 );
		$result1 = $clissbase->sql_record ( $clissbase->sql_query ( "", "z01_nome", "", " issbase.q02_inscr = $p12_inscr" ) );
		db_fieldsmemory ( $result1, 0 );
		$pdf->Cell ( 70, 4, "" . $z01_nome, 1, 0, "L", 1 );
		if (isset ( $p12_tipolanc ) && $p12_tipolanc == 1) {
			$pdf->Cell ( 34, 4, "" . "CLIENTE NOVO", 1, 1, "L", 1 );
		} else {
			$pdf->Cell ( 34, 4, "" . "EX CLIENTE", 1, 1, "L", 1 );
		}
		if ($pdf->GetY () > 270) {
			$pdf->AddPage ();
			$pdf->SetFillColor ( 235 );
			$pdf->Cell ( 25, 4, "INSCRIÇÃO ", 1, 0, "C", 1 );
			$pdf->Cell ( 35, 4, "CNPJ ", 1, 0, "C", 1 );
			$pdf->Cell ( 26, 4, "TELEFONE ", 1, 0, "C", 1 );
			$pdf->Cell ( 70, 4, "NOME ", 1, 1, "C", 1 );
			$pdf->Cell ( 34, 4, "TIPO LANÇAMENTO ", 1, 1, "C", 1 );
			$pdf->SetFillColor ( 255 );
		}
	}
	if ($p11_processado == 't') {
		$situacao = "Lista já Processada pela Prefeitura";
	} elseif ($p11_fechado == 't') {
		$situacao = "Lista fechada pelo escritório";
	} else {
		$situacao = "";
	}
	$pdf->Ln ( 4 );
	if ($pdf->GetY () > 270) {
		$pdf->AddPage ();
	}
	$posicao = $pdf->getY ();
	$pdf->SetFillColor ( 235 );
	$pdf->Rect ( 110, $posicao, 90, 50, "DF" );
	$pdf->SetFillColor ( 255 );
	$pdf->Text ( 140, ($posicao + 25), "COLAR ETIQUETA DO CRC" );
	$pdf->Cell ( 95, 4, "SITUAÇÃO: " . $situacao, 0, 1, "L", 1 );
	$pdf->Cell ( 95, 4, "TOTAL DE INSCRIÇÕES: " . $total, 0, 1, "L", 1 );
} else {
	$result = $cllistainscrcab->sql_record ( $cllistainscrcab->sql_query ( $p12_codigo ) );
	db_fieldsmemory ( $result, 0 );
	$result1 = $clcgm->sql_record ( $clcgm->sql_query ( $p11_numcgm ) );
	db_fieldsmemory ( $result1, 0 );
	$pdf->Cell ( 190, 4, "LISTA " . $p12_codigo . " SEM INSCRIÇÕES CADASTRADAS - ESCRITÓRIO CONTÁBIL " . $z01_nome . "", 1, 1, "C", 1 );
}
$pdf->SetFillColor ( 255 );
$pdf->Cell ( 40, 4, "CÓDIGO DA LISTA: " . $p11_codigo, 0, 1, "L", 1 );
$pdf->Cell ( 35, 4, "DATA: " . db_formatar ( $p11_data, 'd' ), 0, 1, "L", 1 );
$pdf->Cell ( 35, 4, "HORA: " . $p11_hora, 0, 1, "L", 1 );
$pdf->Cell ( 80, 4, "CONTATO: " . $p11_contato, 0, 1, "L", 1 );
$pdf->Ln ( 40 );
$pdf->Cell ( 190, 4, "---------------------------------------------------------------------------------------------------------------------------", 0, 1, "R", 1 );
$pdf->Cell ( 190, 4, "NOME E ASSINATURA DO RESPONSÁVEL                                      ", 0, 1, "R", 1 );
$pdf->output ();
?>
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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("classes/db_cgs_und_classe.php");
$clcgs_und = new cl_cgs_und ( );
$clcgs_und->rotulo->label ();
$clrotulo = new rotulocampo ( );
parse_str ( $HTTP_SERVER_VARS ['QUERY_STRING'] );
db_postmemory ( $HTTP_POST_VARS );

$campos = "";
$camposgroup = "";
$count = "count(z01_i_cgsund)";
$vir = "";
$campos_where = array ();
$Ncampos_where = array ();
$index = 0;
$index1 = 0;
$campos_head = array ();
$Ncampos_head = array ();
$Ntam_campos = array ();
if (isset ( $Cz01_v_nome )) {
	$campos .= $vir . "z01_v_nome";
	$camposgroup .= $vir . "z01_v_nome";
	$campos_where [$index] = "z01_v_nome";
	$campos_head [$index] = "NOME/RAZÃO SOCIAL";
	$vir = ",";
	$index ++;
}
if (isset ( $z01_v_cgccpf )) {
	$campos .= $vir . "case when length(trim(translate(z01_v_cgccpf,'0',''))) > 0 and length(trim(z01_v_cgccpf)) > 0 then trim(z01_v_cgccpf) else '' end as z01_v_cgccpf";
	$camposgroup .= $vir . "case when length(trim(translate(z01_v_cgccpf,'0',''))) > 0 and length(trim(z01_v_cgccpf)) > 0 then trim(z01_v_cgccpf) else '' end ";
	$campos_where [$index] = "z01_v_cgccpf";
	$campos_head [$index] = "CPF/CNPJ";
	$vir = ",";
	$index ++;
}
if (isset ( $z01_v_ender )) {
	$campos .= $vir . "z01_v_ender";
	$camposgroup .= $vir . "z01_v_ender";
	$campos_where [$index] = "z01_v_ender";
	$campos_head [$index] = "ENDEREÇO";
	$vir = ",";
	$index ++;
}
if (isset ( $z01_i_numero )) {
	$campos .= $vir . "z01_i_numero";
	$camposgroup .= $vir . "z01_i_numero";
	$campos_where [$index] = "z01_i_numero";
	$campos_head [$index] = "NÚMERO";
	$vir = ",";
	$index ++;
}
if (isset ( $z01_v_compl )) {
	$campos .= $vir . "z01_v_compl";
	$camposgroup .= $vir . "z01_v_compl";
	$campos_where [$index] = "z01_v_compl";
	$campos_head [$index] = "COMPLEMENTO";
	$vir = ",";
	$index ++;
}
if (isset ( $z01_v_bairro )) {
	$campos .= $vir . "z01_v_bairro";
	$camposgroup .= $vir . "z01_v_bairro";
	$campos_where [$index] = "z01_v_bairro";
	$campos_head [$index] = "BAIRRO";
	$vir = ",";
	$index ++;
}
if (isset ( $z01_v_munic )) {
	$campos .= $vir . "z01_v_munic";
	$camposgroup .= $vir . "z01_v_munic";
	$campos_where [$index] = "z01_v_munic";
	$campos_head [$index] = "MUNICÍPIO";
	$vir = ",";
	$index ++;
}

$where1 = " where 1=1 ";

if (isset ( $Cz01_v_nome ) && isset ( $z01_v_nome ) && trim ( $z01_v_nome ) != "") {
	$where1 .= " and trim(z01_v_nome) like '" . $z01_v_nome . "%' ";
}

if ($zerados == 'n') {
	$where1 .= " and length(trim(translate(z01_v_cgccpf,'0',''))) > 0 and length(trim(z01_v_cgccpf)) > 0";
}

$result_cgs_und = $clcgs_und->sql_record ( "select $campos,$count from cgs_und $where1 group by $camposgroup having $count>1 order by $camposgroup" );
$numrows_cgs_und = $clcgs_und->numrows;
if ($numrows_cgs_und == 0) {
	db_redireciona ( 'db_erros.php?fechar=true&db_erro=Não existem duplos no cgs_und com os dados informados.' );
}
$head1 = "SELEÇÃO DE DUPLOS NO CGS POR";
$head3 = @$campos_head [0];
$head4 = @$campos_head [1];
$head5 = @$campos_head [2];
$head6 = @$campos_head [3];
$head7 = @$campos_head [4];
$head8 = @$campos_head [5];
$head9 = @$campos_head [6];

$pdf = new PDF ( );
$pdf->Open ();
$pdf->AliasNbPages ();
$pdf->setfillcolor ( 235 );
$alt = 4;
$troca = 0;
$total = 0;

$z01_cgccpf_setado = isset ( $z01_v_cgccpf );

for($i = 0; $i < $numrows_cgs_und; $i ++) {
	db_fieldsmemory ( $result_cgs_und, $i );
	
	if ($z01_cgccpf_setado == true) {
		if ($zerados == "m" and $z01_v_cgccpf != "") {
			continue;
		}
	}
	
	$where = "";
	$and = "      ";
	for($ii = 0; $ii < sizeof ( $campos_where ); $ii ++) {
		if (! isset ( $$campos_where [$ii] ) || $$campos_where [$ii] == "") {
			$where .= $and . ($campos_where [$ii] != "z01_i_numero" ? "(trim(" . $campos_where [$ii] . ")='" . $$campos_where [$ii] . "' or " . $campos_where [$ii] . " is null)" : $campos_where [$ii] . "=" . $$campos_where [$ii]);
		} else {
			$where .= $and . ($campos_where [$ii] != "z01_i_numero" ? "trim(" . $campos_where [$ii] . ")='" . $$campos_where [$ii] . "'" : $campos_where [$ii] . "=" . $$campos_where [$ii]);
		}
		$and = "      and      ";
	}
	if ($where == "") {
		$where == " 1=1 ";
	}
	$result_dadoscgs_und = $clcgs_und->sql_record ( $clcgs_und->sql_query_file ( null, "z01_i_cgsund,z01_v_nome,trim(z01_v_cgccpf) as z01_v_cgccpf,z01_v_ender,z01_i_numero,z01_v_cep,z01_v_compl,z01_v_cxpostal,z01_v_bairro,z01_v_munic,z01_v_uf", "z01_i_cgsund", $where ) );
	$numrows_dadoscgs_und = $clcgs_und->numrows;
	for($iii = 0; $iii < $numrows_dadoscgs_und; $iii ++) {
		db_fieldsmemory ( $result_dadoscgs_und, $iii ); //echo "<BR><br>";
		if (isset ( $z01_v_cgccpf ) && strlen ( $z01_v_cgccpf ) == 11) {
			$z01_v_cgccpf = db_formatar ( $z01_v_cgccpf, "cpf" );
		} else if (isset ( $z01_v_cgccpf ) && strlen ( $z01_v_cgccpf ) == 14) {
			$z01_v_cgccpf = db_formatar ( $z01_v_cgccpf, "cnpj" );
		}
		$z01_v_cep1 = "";
		$z01_v_cep2 = "";
		if (isset ( $z01_v_cep ) && trim ( $z01_v_cep ) != "") {
			$z01_v_cep1 = substr ( $z01_v_cep, 0, 5 );
			$z01_v_cep2 = substr ( $z01_v_cep, 5, 3 );
		}
		$muda_pag = false;
		if ($pdf->gety () > $pdf->h - 32 || $troca == 0) {
			if ($pdf->gety () > $pdf->h - 32) {
				$muda_pag = true;
			}
			$pdf->addpage ( "L" );
			$pdf->setfont ( 'arial', 'b', 8 );
			$pdf->cell ( 75, $alt, @$campos_head [0], "LT", 0, "L", 1 );
			$pdf->cell ( 25, $alt, @$campos_head [1], "T", 0, "L", 1 );
			$pdf->cell ( 75, $alt, @$campos_head [2], "T", 0, "L", 1 );
			$pdf->cell ( 20, $alt, @$campos_head [3], "T", 0, "L", 1 );
			$pdf->cell ( 40, $alt, @$campos_head [4], "T", 0, "L", 1 );
			$pdf->cell ( 40, $alt, @$campos_head [5], "TR", 1, "L", 1 );
			if ($index > 5) {
				$pdf->cell ( 75, $alt, @$campos_head [6], "L", 0, "L", 1 );
				$pdf->cell ( 200, $alt, "", "R", 1, "R", 1 );
			}
			$pdf->cell ( 15, $alt, $RLz01_i_cgsund, 1, 0, "C", 1 );
			$pdf->cell ( 50, $alt, $RLz01_v_nome, 1, 0, "C", 1 );
			$pdf->cell ( 25, $alt, $RLz01_v_cgccpf, 1, 0, "C", 1 );
			$pdf->cell ( 60, $alt, $RLz01_v_ender, 1, 0, "C", 1 );
			$pdf->cell ( 15, $alt, $RLz01_v_cep, 1, 0, "C", 1 );
			$pdf->cell ( 25, $alt, $RLz01_v_compl, 1, 0, "C", 1 );
			$pdf->cell ( 25, $alt, $RLz01_v_cxpostal, 1, 0, "C", 1 );
			$pdf->cell ( 30, $alt, $RLz01_v_bairro, 1, 0, "C", 1 );
			$pdf->cell ( 30, $alt, $RLz01_v_munic, 1, 1, "C", 1 );
			$pdf->ln ( 3 );
			$troca = 1;
		}
		if ($iii == 0 || $muda_pag == true) {
			$pdf->setfont ( 'arial', 'b', 7 );
			$pdf->cell ( 75, $alt, @$$campos_where [0], "LT", 0, "L", 1 );
			$pdf->cell ( 25, $alt, @$$campos_where [1], "T", 0, "L", 1 );
			$pdf->cell ( 75, $alt, @$$campos_where [2], "T", 0, "L", 1 );
			$pdf->cell ( 20, $alt, @$$campos_where [3], "T", 0, "L", 1 );
			$pdf->cell ( 40, $alt, @$$campos_where [4], "T", 0, "L", 1 );
			if ($index >= 5) {
				$pdf->cell ( 40, $alt, @$$campos_where [5], "TR", 1, "L", 1 );
			} else {
				$pdf->cell ( 10, $alt, "", "T", 0, "R", 1 );
				$pdf->cell ( 15, $alt, "Registros:", "LT", 0, "L", 1 );
				$pdf->cell ( 15, $alt, @$count, "TR", 1, "R", 1 );
			}
			if ($index > 5) {
				$pdf->cell ( 75, $alt, @$$campos_where [6], "L", 0, "L", 1 );
				$pdf->cell ( 170, $alt, "", 0, 0, "R", 1 );
				$pdf->cell ( 15, $alt, "Registros:", "LT", 0, "L", 1 );
				$pdf->cell ( 15, $alt, @$count, "TR", 1, "R", 1 );
			}
		}
		$pdf->setfont ( 'arial', '', 7 );
		$pdf->cell ( 15, $alt, $z01_i_cgsund, 1, 0, "C", 0 );
		$pdf->cell ( 50, $alt, substr ( $z01_v_nome, 0, 31 ), 1, 0, "L", 0 );
		$pdf->cell ( 25, $alt, $z01_v_cgccpf, 1, 0, "R", 0 );
		$pdf->cell ( 60, $alt, substr ( $z01_v_ender, 0, 35 ) . ", " . $z01_i_numero, 1, 0, "L", 0 );
		$pdf->cell ( 15, $alt, $z01_v_cep1 . "-" . $z01_v_cep2, 1, 0, "C", 0 );
		$pdf->cell ( 25, $alt, $z01_v_compl, 1, 0, "L", 0 );
		$pdf->cell ( 25, $alt, $z01_v_cxpostal, 1, 0, "R", 0 );
		$pdf->cell ( 30, $alt, substr ( $z01_v_bairro, 0, 19 ), 1, 0, "L", 0 );
		$pdf->cell ( 30, $alt, substr ( $z01_v_munic, 0, 16 ) . "/" . $z01_v_uf, 1, 1, "L", 0 );
		$total ++;
	}
	if ($i + 1 != $numrows_cgs_und) {
		$pdf->ln ( 3 );
	}
}
$pdf->setfont ( 'arial', 'b', 8 );
$pdf->cell ( 275, $alt, 'TOTAL DE REGISTROS ENCONTRADOS  :  ' . $total, "T", 0, "L", 0 );
$pdf->Output ();
?>
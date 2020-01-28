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
include ("classes/db_solicita_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_empautitem_classe.php");
include ("classes/db_pcproc_classe.php");
include ("classes/db_pcprocitem_classe.php");
include ("classes/db_pcorcam_classe.php");
include ("classes/db_pcorcamitem_classe.php");
include ("classes/db_pcorcamforne_classe.php");
$clsolicita = new cl_solicita;
$clsolicitem = new cl_solicitem;
$clpcproc = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clpcorcam = new cl_pcorcam;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamforne = new cl_pcorcamforne;
$clempautitem   = new cl_empautitem;
$clrotulo = new rotulocampo;
$clrotulo->label("e55_item");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e55_descr");
$clrotulo->label("e55_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("e55_sequen");
$clrotulo->label("e55_quant");
$clrotulo->label("e55_vltot");
$clsolicita->rotulo->label();
$listar = 'n';
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$and   = "";
$where = "";
$info  = "";
$inform= "";
function monta_where($inp = "", $par = "", $descr_inp = "") {
	global $and;
	$param_solicita = "";
	$where_solicitacao = "";

	if (isset ($inp) && trim($inp) != "") {
		if ($par == "S") {
			$param_solicita = " in ";
		} else
			if ($par == "N") {
				$param_solicita = " not in ";
			}
		$where_solicitacao .= $and.$descr_inp.$param_solicita." (".$inp.") ";
		$and = " and ";
	}
	return $where_solicitacao;
}

$autori = monta_where($inp_depart, $par_depart," pc10_depto ");
if (isset ($pc10_dataINI_dia) && trim($pc10_dataINI_dia) != "" && isset ($pc10_dataINI_mes) && trim($pc10_dataINI_mes) != "" && isset ($pc10_dataINI_ano) && trim($pc10_dataINI_ano) != "") {
	$dt_ini = $pc10_dataINI_ano."-".$pc10_dataINI_mes."-".$pc10_dataINI_dia;
}
if (isset ($pc10_dataFIM_dia) && trim($pc10_dataFIM_dia) != "" && isset ($pc10_dataFIM_mes) && trim($pc10_dataFIM_mes) != "" && isset ($pc10_dataFIM_ano) && trim($pc10_dataFIM_ano) != "") {
	$dt_fim = $pc10_dataFIM_ano."-".$pc10_dataFIM_mes."-".$pc10_dataFIM_dia;
}


if (isset ($dt_ini) && trim($dt_ini) != "" || isset ($dt_fim) && trim($dt_fim) != "") {
	if (isset ($dt_ini) && isset ($dt_fim)) {
		$autori = $autori.$and." pc10_data between '".$dt_ini."' and '".$dt_fim."' ";
		$inform = "Período entre ".db_formatar($dt_ini,'d')." e ".db_formatar($dt_fim,'d');
	} else
		if (isset ($dt_ini)) {
			$autori = $autori.$and." pc10_data >= '".$dt_ini."' ";
			$inform = "Período posterior a ".db_formatar($dt_ini,'d');
		} else
			if (isset ($dt_fim)) {
				$autori = $autori.$and." pc10_data <= '".$dt_fim."' ";
				$inform = "Período anterior a ".db_formatar($dt_fim,'d');
			}
	$and = " and ";
}

$usuari = monta_where($inp_usuarios, $par_usuarios, " pc10_login  ");
if (isset ($autori) && trim($autori) != "") {
	$where .= $autori;
}
if (isset ($usuari) && trim($usuari) != "") {
	$where .= $usuari;
}

if ($ordem == "pc10_data") {
	$info = "DATA DE EMISSÃO";
} else
	if ($ordem == "pc10_numero") {
		$info = "NÚMERO DA SOLICITAÇÃO";
	} else
		if ($ordem == "pc10_depto") {
			$info = "CÓDIGO DO DEPARTAMENTO";
		} else
			if ($ordem == "descrdepto") {
				$info = "DESCRIÇÃO DO DEPARTAMENTO";
			} else
				if ($ordem == "nome") {
				  $info = "NOME DO USUÁRIO";
				}

//die($clpcprocitem->sql_query_pcmater(null,"pc80_codproc,pc80_data,pc80_usuario,pc80_resumo,pc10_numero,pc10_data,pc10_resumo",$ordem,$where));
//die($clpcprocitem->sql_query_pcmater(null,"distinct e54_autori,pc80_codproc,pc80_data,pc80_usuario,descrdepto as departamento,pc80_resumo,pc10_numero,pc10_data,pc10_resumo",$ordem,$where));
$result_solicita = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater(null,
      "distinct  e54_autori,e60_codemp,e60_numemp,pc80_codproc,pc80_data,login,pc05_descr,z01_nome,
			          pc80_usuario,descrdepto as departamento,pc80_resumo,pc10_numero,pc10_data,pc10_resumo",
								$ordem,$where));
 
/*die($clpcprocitem->sql_query_pcmater(null,
     "distinct  e54_autori,e60_codemp,e60_numemp,pc80_codproc,pc80_data,login,pc05_descr,z01_numcgm,
			          pc80_usuario,descrdepto as departamento,pc80_resumo,pc10_numero,pc10_data,pc10_resumo",
								$ordem,$where));*/
//$result_solicita = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater(null,"distinct  ",$ordem,$where));
//echo pg_last_error();
$numrows_solicita = $clpcprocitem->numrows;
//db_criatabela($result_solicita);exit;
if($numrows_solicita == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontradas solicitações com os dados informados.');
}

///////////////////////////////////////////////////////////////////////
$head4 = "SOLICITAÇÕES EM PROCESSO DE COMPRAS";
$head5 = @$inform;
//$head6 = @$inform;
$head7 = "ORDEM DE SELEÇÃO POR ".$info;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 4;
$total = 0;
$c = 1;
$quanttot = 0;
$valortot = 0;
$conttot = 0;
//    $pdf->addpage("L");    
for ($i = 0; $i < $numrows_solicita; $i ++) {
			
	db_fieldsmemory($result_solicita, $i);
//	echo "$pc80_codproc, $pc80_resumo<br>";
	if ($c == 1) {
		$c = 0;
	} else {
		$c = 1;
	}
	if ($pdf->gety() > $pdf->h - 32 || $troca != 0) {
		$pdf->addpage("L");
		$pdf->setfont('arial', 'b', 8);
		$pdf->cell(19, $alt, "solicitação", 1, 0, "L", 1);
		$pdf->cell(19, $alt, "Emissão", 1, 0, "C", 1);
		$pdf->cell(50, $alt, "Credor", 1, 0, "C", 1);
		$pdf->cell(50, $alt, "Tipo de compra", 1, 0, "C", 1);
		$pdf->cell(50, $alt, "Departamento", 1, 0, "C", 1);
		$pdf->cell(30, $alt, "Usuário", 1, 0, "C", 1);
		$pdf->cell(20, $alt, "Empenho", 1, 0, "C", 1);
		$pdf->cell(20, $alt, "Número", 1, 0, "C", 1);
		$pdf->cell(25, $alt, "Valor Total", 1, 1, "C", 1);
		if ($listar == 's') {

			$pdf->cell(15, $alt, $RLe55_item, 1, 0, "C", 1);
			$pdf->cell(50, $alt, $RLpc01_descrmater, 1, 0, "C", 1);
			$pdf->cell(50, $alt, $RLe55_descr, 1, 0, "C", 1);
			$pdf->cell(15, $alt, $RLe55_codele, 1, 0, "C", 1);
			$pdf->cell(53, $alt, "Estrutural", 1, 0, "C", 1);
			$pdf->cell(50, $alt, $RLo56_descr, 1, 0, "C", 1);
			$pdf->cell(15, $alt, $RLe55_sequen, 1, 0, "C", 1);
			$pdf->cell(15, $alt, "Quant.", 1, 0, "C", 1);
			$pdf->cell(20, $alt, 'Valor', 1, 1, "C", 1);
		}
		
		$c = 0;
		$troca = 0;
	}
	
  $valortot = 0;	//dados empsolicita
	if ($e54_autori != ''){
	   $result_valortot = $clempautitem->sql_record($clempautitem->sql_query_file($e54_autori));
     $valortot = 0;
	   for ($x = 0; $x < $clempautitem->numrows; $x ++) {
		    db_fieldsmemory($result_valortot, $x);
		    $valortot = $valortot + $e55_vltot;
		 }
 }	
	
	//-----------------
//  echo "aqui -> solicita ->$i<br>"; 
	$pdf->setfont('arial', 'b', 7);
	$pdf->cell(19, $alt, @ $e54_autori, 0, 0, "C", $c);
	$pdf->cell(19, $alt, @ $pc10_data, 0, 0, "C", $c);
	$pdf->cell(50, $alt, substr(@ $z01_nome, 0, 31), 0, 0, "L", $c);
	$pdf->cell(50, $alt, substr(@ $pc05_descr, 0, 31), 0, 0, "L", $c);
	$pdf->cell(50, $alt, substr(@ $departamento, 0, 31), 0, 0, "L", $c);
	$pdf->cell(30, $alt, substr(@ $login, 0, 30), 0, 0, "L", $c);
	$pdf->cell(20, $alt, @ $e60_codemp, 0, 0, "C", $c);
	$pdf->cell(20, $alt, @ $e60_numemp, 0, 0, "C", $c);
	$pdf->cell(25, $alt, db_formatar(@ $valortot, 'f'), 0, 1, "R", $c);
	//------------------
	if ($listar == 's') {
		 if ($e54_autori !=''){ 
	
  		$result_itens = $clempautitem->sql_record($clempautitem->sql_query($e54_autori, null, "e55_item,pc01_descrmater,e55_descr,e55_codele,o56_descr,e55_sequen,e55_quant,e55_vltot"));
	  	//  fc_estruturaldotacao(e56_anousu,e56_coddot);
		  $quanttot = 0;
  		$valortot = 0;
		  $conttot = 0;
		  for ($y = 0; $y < $clempautitem->numrows; $y ++) {
			  if ($pdf->gety() > $pdf->h - 32 || $troca != 0) {
				  $pdf->addpage("L");
				  $pdf->setfont('arial', 'b', 8);
				  $pdf->cell(19, $alt, "Solicitação", 1, 0, "L", 1);
				  $pdf->cell(19, $alt, "Emissão", 1, 0, "C", 1);
				  $pdf->cell(50, $alt, "Credor", 1, 0, "C", 1);
				  $pdf->cell(50, $alt, "Tipo de compra", 1, 0, "C", 1);
				  $pdf->cell(50, $alt, "Departamento", 1, 0, "C", 1);
				  $pdf->cell(30, $alt, "Usuário", 1, 0, "C", 1);
				  $pdf->cell(20, $alt, "Empenho", 1, 0, "C", 1);
				  $pdf->cell(20, $alt, "Número", 1, 0, "C", 1);
				  $pdf->cell(25, $alt, "Valor Total", 1, 1, "C", 1);
				  $pdf->cell(15, $alt, $RLe55_item, 1, 0, "C", 1);
				  $pdf->cell(50, $alt, $RLpc01_descrmater, 1, 0, "C", 1);
				  $pdf->cell(50, $alt, $RLe55_descr, 1, 0, "C", 1);
				  $pdf->cell(15, $alt, $RLe55_codele, 1, 0, "C", 1);
				  $pdf->cell(53, $alt, "Estrutural", 1, 0, "C", 1);
				  $pdf->cell(50, $alt, $RLo56_descr, 1, 0, "C", 1);
				  $pdf->cell(15, $alt, $RLe55_sequen, 1, 0, "C", 1);
				  $pdf->cell(15, $alt, "Quant.", 1, 0, "C", 1);
				  $pdf->cell(20, $alt, 'Valor', 1, 1, "C", 1);

				  $c = 0;
				  $troca = 0;
			  }
			  db_fieldsmemory($result_itens, $y);
			  $pdf->setfont('arial', '', 7);
			  $pdf->cell(15, $alt, $e55_item, 0, 0, "C", $c);
			  $pdf->cell(50, $alt, substr($pc01_descrmater, 0, 31), 0, 0, "L", $c);
			  $pdf->cell(50, $alt, substr($e55_descr, 0, 30), 0, 0, "L", $c);
			  $pdf->cell(15, $alt, $e55_codele, 0, 0, "C", $c);
			  $pdf->cell(53, $alt, 'strut', 0, 0, "C", $c);
			  $pdf->cell(50, $alt, substr(@ $o56_descr, 0, 30), 0, 0, "L", $c);
			  $pdf->cell(15, $alt, $e55_sequen, 0, 0, "C", $c);
			  $pdf->cell(15, $alt, $e55_quant, 0, 0, "C", $c);
			  $pdf->cell(20, $alt, db_formatar($e55_vltot, 'f'), 0, 1, "R", $c);
			  $quanttot +=  $e55_quant;
			  $valortot +=  $e55_vltot;
			  $conttot ++;
		  }
		  if ($conttot > 1) {
			  $pdf->setfont('arial', 'b', 7);
			  $pdf->cell(248, $alt, 'TOTAL:', 0, 0, "R", $c);
			  $pdf->cell(15, $alt, $quanttot, 0, 0, "C", $c);
			  $pdf->cell(20, $alt, db_formatar($valortot, 'f'), 0, 1, "R", $c);
		  }
		 }
		//break;
	}

	$total ++;
}
$pdf->setfont('arial', 'b', 8);
$pdf->cell(282, $alt, 'TOTAL DE SOLICITIÇÕES  :  '.$total, "T", 1, "L", 0);
$pdf->Output();
?>
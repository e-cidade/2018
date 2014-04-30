<?php
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

include ("fpdf151/scpdf.php");
include ("fpdf151/impcarne.php");
include ("classes/db_saltes_classe.php");

$clsaltes = new cl_saltes;

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

// Dados
$sql = "select slip.*,
               z01_numcgm , 
	       z01_nome , 
	       c60_descr as descr_debito, 
	       p2.k13_descr as descr_credito, 
	       c50_codhist as db_hist, 
	       c50_descr as descr_hist,
	       k18_motivo,
	       coalesce(k18_codigo,0) as k18_codigo
	from slip
	       left outer join slipanul		on slip.k17_codigo = slipanul.k18_codigo
	       left outer join slipnum 		on slip.k17_codigo = slipnum.k17_codigo
	       left outer join cgm 		on slipnum.k17_numcgm = cgm.z01_numcgm
	       left outer join conplanoreduz 	on slip.k17_debito = c61_reduz and
	              	                           c61_instit     = ".db_getsession('DB_instit')." and
                                                   c61_anousu = ".db_getsession("DB_anousu")."
	       left outer join conplano 	on c61_codcon = c60_codcon and 
                                                   c60_anousu = ".db_getsession("DB_anousu")."
	       left outer join saltes p2 	on slip.k17_credito = p2.k13_reduz
	       left outer join conhist 		on slip.k17_hist = conhist.c50_codhist
        where slip.k17_codigo in($slips) and k17_instit = ".db_getsession('DB_instit');

$dados = pg_exec($sql);


      
// se houverem registros, monta um array
$array_recursos =  array();

// print_r($array_recursos); exit;


if (pg_numrows($dados) == 0) {
	echo "<script>
	         alert('Documento de Slip não Cadastrado.');
	         window.close();
	         </script>";
	exit;
}

db_fieldsmemory($dados,0);

$sqlcai = "select * from caiparametro where k29_instit = ".db_getsession('DB_instit');
$resultcai = pg_exec($sqlcai) or die($sqlcai);
if (pg_numrows($resultcai) == 0) {
	$k29_modslipnormal = 36;
	$k29_modsliptransf = 36;
} else {
	db_fieldsmemory($resultcai, 0);
	if ($k29_modslipnormal != 36 and $k29_modslipnormal != 37 and $k29_modslipnormal != 381) {
		$k29_modslipnormal = 36;
	}
	if ($k29_modsliptransf != 36 and $k29_modsliptransf != 37 and $k29_modslipnormal != 381) {
	  $k29_modsliptransf = 36;
	}
}

$quantdeb = 0;
if ($k17_debito > 0) {
	$clsaltes->sql_record($clsaltes->sql_query_file($k17_debito)); 
	$quantdeb = $clsaltes->numrows;
}

$quantcre = 0;
if ($k17_credito > 0) {
	$clsaltes->sql_record($clsaltes->sql_query_file($k17_credito)); 
	$quantcre = $clsaltes->numrows;
}

if ($quantdeb > 0 and $quantcre > 0) {
	$codmodelo = $k29_modsliptransf;
} else {
	$codmodelo = $k29_modslipnormal;
}

$pdf1 = new scpdf();
$pdf1->Open();

$pdf = new db_impcarne($pdf1, 36);
$pdf->objpdf->AddPage();
$pdf->objpdf->SetTextColor(0, 0, 0);
  
 // trecho para relatorio
$head1 = "Texto numero 1";
$head2 = "Texto numero 2";
$head3 = "Texto numero 3";
$head4 = "Texto numero 4";
//$head5 = "Texto numero 5";
$head6 = "Texto numero 6";
$head7 = "Texto numero 7";
$head8 = "Texto numero 8";
$head9 = "Texto numero 9";
$head10 = "Texto numero 10";
  // trecho para relatorio
  
$sql = "select * from db_config where codigo = ".db_getsession('DB_instit');
$dadospref = pg_exec($sql);
db_fieldsmemory($dadospref, 0);

$pdf->dados    = $dados;
$pdf->recursos = $array_recursos;
  
$pdf->logo		 = $logo;
$pdf->nomeinst     = $nomeinst;
$pdf->ender        = $ender;
$pdf->munic		 = $munic;
$pdf->telef		 = $telef;
$pdf->email		 = $email;
$pdf->logo		 = $logo;
$pdf->imprime();
$pdf->objpdf->AliasNbPages();
$pdf->objpdf->settopmargin(1);


$pdf->objpdf->Output();

?>

?>
<?
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

require(modification('fpdf151/pdf.php'));
db_postmemory($HTTP_POST_VARS);

$sqlcgm="select z01_numcgm ,z01_nome, z01_cgccpf from cgm where z01_numcgm = $cgm";
$resultcgm = db_query($sqlcgm);
$linhascgm=pg_num_rows($resultcgm);


$pdf = new PDF(); // abre a classe
$head1 = "RELATÓRIO DE RETENÇÃO COMO PRESTADOR";

if (($data1 !="--") && ($data2!="--")){
	$where = " and q21_dataop>='$data1' and q21_dataop<='$data2' ";
	$head2 = "PERÍODO: ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
}else{
	$where="";
}
$sqlprestador = "
				select      q20_numcgm as cgm,
				            cgmtomador.z01_nome as tomador,
				            q20_mes,
				            q20_ano,
				            q21_nota,
				            q21_serie,
				            q21_valorser,
				            q21_aliq,
				            q21_valor,
					          q21_dataop,
                    q20_numpre,
                    case when (select dtpago from arreidret inner join disbanco on disbanco.idret = arreidret.idret where arreidret.k00_numpre = q20_numpre) is not null then (select dtpago from arreidret inner join disbanco on disbanco.idret = arreidret.idret where arreidret.k00_numpre = q20_numpre) else (select min(k00_dtpaga) from arrepaga where k00_numpre = q20_numpre) end as k00_dtpaga,
                    (select min(k00_conta) from arrepaga where k00_numpre = q20_numpre) as k00_conta
				from cgm
				inner join issplanit on cgm.z01_cgccpf=q21_cnpj
				inner join issplan on q20_planilha = q21_planilha
				inner join cgm cgmtomador on q20_numcgm = cgmtomador.z01_numcgm
				where q20_numcgm <> cgm.z01_numcgm and cgm.z01_numcgm= $cgm and q21_status = 1 and q20_situacao <> 5
				$where
				";
//die($sqlprestador);
$resultprestador = db_query($sqlprestador);
$linhasprestador = pg_num_rows($resultprestador);

$Letra = 'arial';
$pdf->SetFont($Letra,'B',8);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage("L"); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

	if($linhascgm>0){
		db_fieldsmemory($resultcgm,0);
		$pdf->Cell(240,6,"PRESTADOR: ". $z01_nome,0,1,"L",0);
		$pdf->Cell(240,6,"CGM: ". $z01_numcgm,0,1,"L",0);
		$pdf->Cell(240,6,"CPF/CNPJ: ". $z01_cgccpf,0,1,"L",0);
		$pdf->Cell(240,6,"",0,1,"C",0);
	}



$pdf->Cell(15,6,"CGM",0,0,"C",1);
$pdf->Cell(50,6,"TOMADOR",0,0,"C",1);
$pdf->Cell(15,6,"MÊS",0,0,"C",1);
$pdf->Cell(15,6,"ANO",0,0,"C",1);
$pdf->Cell(15,6,"NOTA",0,0,"C",1);
$pdf->Cell(15,6,"SERIE",0,0,"C",1);
$pdf->Cell(30,6,"VLR SERVIÇO",0,0,"R",1);
$pdf->Cell(15,6,"ALIQUOTA",0,0,"C",1);
$pdf->Cell(30,6,"VLR IMPOSTO",0,0,"R",1);
$pdf->Cell(30,6,"DT OPER",0,0,"C",1);
$pdf->Cell(20,6,"NUMPRE",0,0,"C",1);
$pdf->Cell(15,6,"DT PGTO",0,0,"C",1);
$pdf->Cell(10,6,"CONTA",0,1,"C",1);

$totalserv=0;
$totalimp=0;
$totalreg=0;

	if($linhasprestador>0){
		for($i=0;$i < $linhasprestador;$i++){
			db_fieldsmemory($resultprestador,$i);
			$pdf->Cell(15,6,$cgm,0,0,"C",0);
			$pdf->Cell(50,6,substr($tomador,0,30),0,0,"L",0);
			$pdf->Cell(15,6,$q20_mes,0,0,"C",0);
			$pdf->Cell(15,6,$q20_ano,0,0,"C",0);
			$pdf->Cell(15,6,$q21_nota,0,0,"C",0);
			$pdf->Cell(15,6,$q21_serie,0,0,"C",0);
			$pdf->Cell(30,6,db_formatar($q21_valorser,'f'),0,0,"R",0);
			$pdf->Cell(15,6,$q21_aliq,0,0,"C",0);
			$pdf->Cell(30,6,db_formatar($q21_valor,'f'),0,0,"R",0);
			$pdf->Cell(30,6,db_formatar($q21_dataop,'d'),0,0,"C",0);
			$pdf->Cell(20,6,$q20_numpre,0,0,"C",0);
			$pdf->Cell(15,6,db_formatar($k00_dtpaga,'d'),0,0,"C",0);
			$pdf->Cell(10,6,$k00_conta,0,1,"C",0);
      $totalserv+=$q21_valorser;
      $totalimp+=$q21_valor;
      $totalreg++;

		}
	}
  $pdf->ln(3);
  $pdf->Cell(125,6,"TOTAL DE REGISTROS: $totalreg",0,0,"L",0);
  $pdf->Cell(30,6,db_formatar($totalserv,'f'),0,0,"R",0);
  $pdf->Cell(15,6,"",0,0,"R",0);
  $pdf->Cell(30,6,db_formatar($totalimp,'f'),0,0,"R",0);

$pdf->output();
?>
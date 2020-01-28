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

include("libs/db_conecta.php");
//include("libs/db_stdlib.php");
require('fpdf151/pdf.php');
db_postmemory($HTTP_GET_VARS);

$sqlcgm="select z01_nome,z01_numcgm,z01_cgccpf from cgm where z01_numcgm=$cgm";
$resultcgm=db_query($sqlcgm);
db_fieldsmemory($resultcgm,0);

$sqlbanco="select * from bancos where codbco=$banco";
$resultbanco=db_query($sqlbanco);
db_fieldsmemory($resultbanco,0);

//echo"<br>dados pessoais";
//echo"<br>cgm = $z01_numcgm";
//echo"<br>nome = $z01_nome";
//echo"<br>cpf= $z01_cgccpf";
//echo"<br><br>dados do debito";
//echo"<br>cod = $cod";
//echo"<br>banco = $banco... $nomebco";
 
//pdf ####################################################

$pdf = new PDF(); // abre a classe
$pdf->SetFont('arial','B',10);
$head1 = "CODIGO PARA DÉBITO ";
$head2 = "$cod ";
$Letra = 'arial';
$pdf->Open(); // abre o relatorio
$pdf->SetFont('arial','B',8);
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->Ln(5);
$pdf->SetFont('arial','B',12);
$pdf->Cell(190,6,"CÓDIGO PARA DEBITO EM CONTA: ". $cod,1,1,"C",1);
$pdf->Ln(5);
$pdf->SetFont('arial','B',8);
$pdf->Cell(190,6,"DADOS DO CONTRIBUINTE",0,0,"C",1);
$pdf->Ln(10);
$pdf->SetFont('arial','',8);
$pdf->Cell(110,6,'NOME: '.@$z01_nome,0,1,"J",0);
$pdf->Cell(110,6,'CPF/CNPJ: '.@$z01_cgccpf,0,1,"J",0);

$pdf->Cell(110,6,$tipomi.": ".$mat_ins,0,1,"J",0);
$pdf->Cell(110,6,'CGM: '.@$z01_numcgm,0,1,"J",0);
$pdf->Ln(5);
$pdf->SetFont('arial','B',8);
$pdf->Cell(190,6,"DADOS DOS DÉBITOS",0,0,"C",1);
$pdf->Ln(5);
$pdf->Cell(100,6,"DATA:".date("d/m/Y"),0,1,"J",0);
$pdf->Cell(100,6,"BANCO:". $nomebco,0,1,"J",0);
$pdf->Ln(5);
$pdf->Cell(50,6,"CÓDIGO DE ARRECADAÇÃO",1,0,"C",1);
$pdf->Cell(30,6,"PARCELA",1,0,"C",1);
$pdf->Cell(30,6,"VENCIMENTO",1,0,"C",1);
$pdf->Cell(30,6,"VALOR",1,1,"C",1);

// separa numpres e pega data de vencimento.....
$np = split("N",$numpres);
  	$total=count($np);
  	for ($i = 0; $i < $total; $i++) {
		$np[$i] = $np[$i];
	  	if($np[$i]!=0){
			$parcela = split("P",$np[$i]);
		  	$par[$i]=$parcela[1];
		  	$num[$i]=$parcela[0];
		 		   
			$sqldeb="select k00_numpre, k00_numpar, k00_dtvenc,sum(k00_valor) as k00_valor
					 from arrecad 
					 where k00_numpre= $num[$i]  and k00_numpar= $par[$i] and k00_numcgm=$cgm
					 group by k00_numpre, k00_numpar, k00_dtvenc";
			
			$resultdeb=db_query($sqldeb);
			db_fieldsmemory($resultdeb,0);
			$pdf->SetFont('arial','',8);
			$pdf->Cell(50,6,"$k00_numpre",1,0,"C",0);
			$pdf->Cell(30,6,"$k00_numpar",1,0,"C",0);
			$pdf->Cell(30,6,db_formatar($k00_dtvenc,'d'),1,0,"C",0);
			$pdf->Cell(30,6,"$k00_valor",1,1,"R",0);
			//echo"<br>numpre = $k00_numpre";
			//echo"....numpar = $k00_numpar";
		   // echo"....data = ".db_formatar($k00_dtvenc,'d');
		    
	  	}
  	}



//$pdf->Cell(largura,altura,'conteudo',borda,quebra linha,"J",fundo);

$pdf->output();

?>
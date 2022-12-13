<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("agu3_conscadastro_002_classe.php"));

// Linha Em Branco
function LinhaBrancoPDF($Pdf) {
	$Pdf->setX(5);
	$Pdf->Cell(200,4,"","",1,"C",0);
}

function MensagemPDF($Pdf, $Mensagem) {
	$Pdf->setX(5);
	$Pdf->SetFont('Arial','',9);
	$Pdf->Cell(200,4,$Mensagem,"",1,"C",0);
}


// Titulo Quebras
function TituloPDF($Pdf, $Titulo) {
	LinhaBrancoPDF($Pdf);
	$Pdf->setX(5);
	$Pdf->SetFont('Arial','B',9);
	$Pdf->Cell(200,4, $Titulo,"LRBT",1,"C",1);
	LinhaBrancoPDF($Pdf);
}

db_postmemory($_SESSION);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$Consulta = new ConsultaAguaBase(0);
$rotulo = new rotulocampo();

	$Matric = $parametro;
	$Consulta->SetMatric($Matric);
	$result = $Consulta->RecordSetAguaBase();


	$claguabase = $Consulta->GetAguaBaseDAO();
	$claguabase->rotulo->label();

	db_fieldsmemory($result, 0);

	$head3 = "Dados do Imóvel";
	$head4 = $RLx01_matric." ".$x01_matric;
	$head5 = $RLx01_distrito . " " . $x01_distrito . " - " . $RLx01_zona . " " . $x01_zona . " - " . $RLx01_quadra . " " . $x01_quadra;
	//"Setor: ".$j34_setor." Quadra: ".$j34_quadra." Lote: ".$j34_lote;
	$pdf->AddPage();
	$pdf->SetFillColor(220);
	$pdf->SetFont('Arial','B',9);

	// DADOS CADASTRAIS DO IMÓVEL
	TituloPDF($pdf, "DADOS CADASTRAIS DO IMÓVEL");

	//  MATRIC  :  PROPRIETARIO
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4, $RLx01_matric,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,"$x01_matric","",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_numcgm,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,"$z01_nome","",1,"L",0);

	// LOGRADOURO  :  BAIRRO
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_codrua,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,$x01_codrua." - ".$j14_nome.", ".$x01_numero,"",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_codbairro,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,$j13_descr,"",1,"L",0);

	// DISTRITO  :  ZONA
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_distrito,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,$x01_distrito,"",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_zona,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,$x01_zona,"",1,"L",0);

	// QUADRA  :  NUMERO
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_quadra,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,$x01_quadra,"",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_numero,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,$x01_numero,"",1,"L",0);

	// ORIENTACAO  :  ROTA
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_orientacao,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,$x01_orientacao,"",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_rota,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,$x01_rota,"",1,"L",0);

	// ECONOMIAS  :  CADASTRO
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_qtdeconomia,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,$x01_qtdeconomia,"",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_dtcadastro,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,db_formatar($x01_dtcadastro,"d"),"",1,"L",0);

	// PONTOS  :  OBSERVACOES
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_qtdponto,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(80,4,$x01_qtdponto,"",0,"L",0);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,4,$RLx01_obs,"",0,"L",0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,4,empty($x01_obs)?"Nenhuma":$x01_obs,"",1,"L",0);

  // Condomínio

  $resultCondominio = $Consulta->RecordSetAguaCondominio();

  if(pg_numrows($resultCondominio)>0) {
    db_fieldsmemory($resultCondominio, 0);
    $infoCondominio=$x31_codcondominio." ( Matrícula: ".$x31_matric." - ".$dl_proprietario." ) ";
    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Condomínio","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$infoCondominio","",1,"L",0);
  }






// CARACTERÍSTICAS DO IMÓVEL
	TituloPDF($pdf, "CARACTERÍSTICAS DO IMOVEL");

	$result = $Consulta->RecordSetAguaBaseCar();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas > 0) {
		$lado = 0;
		$pdf->setX(5);
		$pdf->SetFont('Arial','',9);
		for($indy=0; $indy<$linhas; $indy++) {
			db_fieldsmemory($result, $indy);

			$pdf->Cell(15,4,$j31_codigo,"",0,"R",1);
			$descr = substr($j31_descr,0,20).' ('.substr($j32_descr,0,20).')';
			$pdf->Cell(80,4,$descr,"",$lado,"L",0);
			if($lado==0) {
				$pdf->setX(100);
				$lado = 1;
			} else {
				$pdf->Ln(1);
				$pdf->setX(5);
				$lado = 0;
			}
		}
	} else {
	  	MensagemPDF($pdf, "Sem características cadastradas.");
	}

	// ISENÇÕES
	TituloPDF($pdf, "ISENÇÕES");
	$result = $Consulta->RecordSetAguaIsencaoRec();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas>0) {
		$pdf->SetFont('Arial','',9);
		for($indy=0; $indy<$linhas; $indy++) {
			db_fieldsmemory($result, $indy);

			$pdf->SetX(5);
			$pdf->Cell(18,4,db_formatar($x10_dtini,'d'),"",0,"L",1);
			$pdf->Cell(5,4,"a","",0,"C",0);
			$pdf->Cell(18,4,db_formatar($x10_dtfim,'d'),"",0,"L",1);
			$pdf->Cell(40,4,$x29_descr,"",0,"L",0);
			$pdf->Cell(20,4,db_formatar($x26_percentual,'f')."%","",0,"R",0);
			$pdf->Cell(40,4,$x25_descr,"",1,"L",0);
		}
	} else {
	   	MensagemPDF($pdf, "Sem Isenções cadastradas.");
	}

	// CONSTRUÇÕES
	TituloPDF($pdf, "CONSTRUÇÕES");
	$result = $Consulta->RecordSetAguaConstrCar();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas>0) {

		$rotulo->label("x11_numero");
		$rotulo->label("x11_complemento");
		$rotulo->label("x11_area");
		$rotulo->label("x11_pavimento");
		$rotulo->label("x11_qtdfamilia");
		$rotulo->label("x11_qtdpessoas");
		$rotulo->label("j31_codigo");
		$rotulo->label("j31_descr");
		$rotulo->label("j32_descr");
		$rotulo->label("x12_codconstr");

		$lado = 0;
    $ant  = null;
		for($indy=0; $indy<$linhas; $indy++) {
			db_fieldsmemory($result, $indy);

			if($x11_codconstr <> $ant) {
				$ant = $x11_codconstr;

				$pdf->SetX(5);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(200,4,$RLx12_codconstr . " " . $x12_codconstr,"",1,"C",1);

				// NUMERO  :  COMPLEMENTO
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx11_numero,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(60,4,$x11_numero,"",0,"L",0);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx11_complemento,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(60,4,$x11_complemento,"",1,"L",0);

				// AREA  :  PAVIMENTO
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx11_area,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(60,4,$x11_area,"",0,"L",0);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx11_pavimento,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(60,4,$x11_pavimento,"",1,"L",0);

				// QTDFAMILIA  :  QTDPESSOAS
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx11_qtdfamilia,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(60,4,$x11_qtdfamilia,"",0,"L",0);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx11_qtdpessoas,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(60,4,$x11_qtdpessoas,"",1,"L",0);

				LinhaBrancoPDF($pdf);
			}

			$pdf->SetFont('Arial','',9);
			$pdf->Cell(15,4,$j31_codigo,"",0,"R",1);
			$descr = substr($j31_descr,0,20).' ('.substr($j32_descr,0,20).')';
			$pdf->Cell(80,4,$descr,"",$lado,"L",0);
			if($lado==0) {
				$pdf->setX(100);
				$lado = 1;
			} else {
				$pdf->Ln(1);
				$pdf->setX(5);
				$lado = 0;
			}
		} // Fim For
		LinhaBrancoPDF($pdf);
	} else {
	   	MensagemPDF($pdf, "Sem Construções cadastradas (TERRENO).");
	}


	// ENDERECO ENTREGA
	TituloPDF($pdf, "ENDEREÇO DE ENTREGA");

	$result = $Consulta->RecordSetAguaBaseCorresp();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas>0) {
		$pdf->SetFont('Arial','B',9);
		$pdf->SetX(5);

		$rotulo->label("x02_codrua");
		$rotulo->label("x02_numero");
		$rotulo->label("x02_codbairro");
		$rotulo->label("x02_complemento");
		$rotulo->label("x02_rota");
		$rotulo->label("x02_orientacao");

		$rotulo->label("j13_descr");
		$rotulo->label("j14_nome");


		for($indy=0; $indy<$linhas; $indy++) {
			db_fieldsmemory($result, $indy);

			// LOGRADOURO  :  BAIRRO
			$pdf->setX(5);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx02_codrua,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(70,4,$x02_codrua." - ".$j14_nome,"",0,"L",0);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx02_codbairro,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(70,4,$j13_descr,"",1,"L",0);

			// NUMERO  :  COMPLEMENTO
			$pdf->setX(5);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx02_numero,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(70,4,$x02_numero,"",0,"L",0);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx02_complemento,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(70,4,$x02_complemento,"",1,"L",0);

			// ROTA  :  ORIENTACAO
			$pdf->setX(5);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx02_rota,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(70,4,$x02_rota,"",0,"L",0);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx02_orientacao,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(70,4,$x02_orientacao,"",1,"L",0);

		}
	} else {
	   	MensagemPDF($pdf, "Sem Endereço de Entrega Cadastrado.");
	}


	// HIDROMETROS
	TituloPDF($pdf, "HIDRÔMETROS");
	$result = $Consulta->RecordSetAguaHidroMatric();

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas>0) {
		$pdf->SetFont('Arial','B',9);
		$pdf->SetX(5);

		$rotulo->label("x04_nrohidro");
		$rotulo->label("x04_qtddigito");
		$rotulo->label("x04_dtinst");
		$rotulo->label("x04_leitinicial");
		$rotulo->label("x15_diametro");
		$rotulo->label("x03_nomemarca");
		$rotulo->label("x28_dttroca");
		$rotulo->label("x28_obs");


		for($indy=0; $indy<$linhas; $indy++) {
			db_fieldsmemory($result, $indy);

			//x04_nrohidro,      x04_qtddigito,  x04_dtinst,
			//x04_leitinicial,   x15_diametro,   x03_nomemarca,
			//x28_dttroca,       x28_obs

			if($indy>0) {
				LinhaBrancoPDF($pdf);
			}

			// NRO HIDRO  :  QTD DIGITOS  : DT INSTALACAO
			$pdf->setX(5);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(40,4,$RLx04_nrohidro,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,4,$x04_nrohidro,"",0,"L",0);

			$pdf->SetX(70);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(40,4,$RLx04_qtddigito,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,4,$x04_qtddigito,"",0,"L",0);

			$pdf->SetX(130);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx04_dtinst,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(40,4,db_formatar($x04_dtinst,'d'),"",1,"L",0);

			// LEITURA INICIAL  :  DIAMETRO  :  MARCA
			$pdf->setX(5);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(40,4,$RLx04_leitinicial,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,4,$x04_leitinicial,"",0,"L",0);

			$pdf->SetX(70);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(40,4,$RLx15_diametro,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,4,$x15_diametro,"",0,"L",0);

			$pdf->SetX(130);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(30,4,$RLx03_nomemarca,"",0,"L",0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(40,4,$x03_nomemarca,"",1,"L",0);

			if(!empty($x28_dttroca)) {
				// DATA TROCA  :  OBSERVACOES
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx28_dttroca,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(10,4,db_formatar($x28_dttroca,'d'),"",0,"L",0);

				$pdf->SetX(70);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(40,4,$RLx28_obs,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(110,4,$x28_obs,"",1,"L",0);

			}
		}
	} else {
	   	MensagemPDF($pdf, "Sem Hidrômetros cadastradas.");
	}

	// LEITURAS
	TituloPDF($pdf, "LEITURAS");
	$result = $Consulta->RecordSetAguaLeitura(12);

	if($result) {
		$linhas = pg_numrows($result);
	} else {
		$linhas = 0;
	}

	if($linhas>0) {
		$pdf->SetFont('Arial','B',8);
		$pdf->SetX(5);

		$rotulo->label("x21_exerc");
		$rotulo->label("x21_mes");
		$rotulo->label("x17_descr");
		$rotulo->label("x21_dtleitura");
		$rotulo->label("x21_dtinc");
		$rotulo->label("x21_leitura");
		$rotulo->label("x21_consumo");
		$rotulo->label("x21_excesso");
		$rotulo->label("x21_numcgm");
		$rotulo->label("login");

		$pdf->Cell(8,4,$RLx21_exerc,"",0,"L",0);
		$pdf->Cell(6,4,$RLx21_mes,"",0,"L",0);
		$pdf->Cell(30,4,$RLx17_descr,"",0,"L",0);
		$pdf->Cell(20,4,$RLx21_dtleitura,"",0,"L",0);
		$pdf->Cell(20,4,$RLx21_dtinc,"",0,"L",0);
		$pdf->Cell(15,4,$RLx21_leitura,"",0,"L",0);
		$pdf->Cell(15,4,$RLx21_consumo,"",0,"L",0);
		$pdf->Cell(15,4,$RLx21_excesso,"",0,"L",0);
		$pdf->Cell(40,4,$RLx21_numcgm,"",0,"L",0);
		$pdf->Cell(10,4,$RLlogin,"",1,"L",0);

		$pdf->SetFont('Arial','',8);

		$max = 12;

		for($indy=0; $indy<$linhas; $indy++) {
			db_fieldsmemory($result, $indy);

			$pdf->SetX(5);
			$pdf->Cell(8,4,$x21_exerc,"",0,"L",$indy%2);
			$pdf->Cell(6,4,$x21_mes,"",0,"L",$indy%2);
			$pdf->Cell(30,4,$x17_descr,"",0,"L",$indy%2);
			$pdf->Cell(20,4,db_formatar($x21_dtleitura,'d'),"",0,"L",$indy%2);
			$pdf->Cell(20,4,db_formatar($x21_dtinc,'d'),"",0,"L",$indy%2);
			$pdf->Cell(15,4,db_formatar($x21_leitura,'f'),"",0,"R",$indy%2);
			$pdf->Cell(15,4,db_formatar($x21_consumo,'f'),"",0,"R",$indy%2);
			$pdf->Cell(15,4,db_formatar($x21_excesso,'f'),"",0,"R",$indy%2);
			$pdf->Cell(40,4,substr($x21_numcgm,0,20),"",0,"L",$indy%2);
			$pdf->Cell(10,4,$login,"",1,"L",$indy%2);

		}
	} else {
	   	MensagemPDF($pdf, "Sem Leituras cadastradas.");
	}

	if( $geracalculo == "true" ) {

		// CALCULO
		TituloPDF($pdf, "DEMONSTRATIVO DO CÁLCULO");
		$result = $Consulta->RecordSetAguaCalc();

		if($result) {
			$linhas = pg_numrows($result);
		} else {
			$linhas = 0;
		}

		if($linhas>0) {

			$rotulo->label("x22_area");
			$rotulo->label("x22_numpre");
			$rotulo->label("x19_conspadrao");
			$rotulo->label("x19_descr");
			$rotulo->label("x21_consumo");
			$rotulo->label("x21_excesso");

			$rotulo->label("x23_valor");
			$rotulo->label("x25_descr");
			$rotulo->label("x25_receit");
			$rotulo->label("k02_descr");

			for($indy=0; $indy<$linhas; $indy++) {
				db_fieldsmemory($result, $indy);

				$nomemes = strtoupper(db_mes($x22_mes));

				$pdf->SetX(5);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(200,4,$nomemes . " /  " . $x22_exerc,"",1,"C",1);

				//  X22_AREA  :  X22_NUMPRE
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,4, $RLx22_area,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(80,4,db_formatar($x22_area,'f')." m2","",0,"L",0);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,4,$RLx22_numpre,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(70,4,$x22_numpre,"",1,"L",0);

				//  X19_CONSPADRAO  :  X19_DESCR
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,4, $RLx19_conspadrao,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(80,4,db_formatar($x19_conspadrao,'f')." m3","",0,"L",0);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,4,$RLx19_descr,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(70,4,$x19_descr,"",1,"L",0);

				//  X21_CONSUMO  :  X21_EXCESSO
				$pdf->setX(5);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,4, $RLx21_consumo,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(80,4,db_formatar($x21_consumo+$x21_excesso,'f')." m3","",0,"L",0);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(20,4,$RLx21_excesso,"",0,"L",0);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(70,4,db_formatar($x21_excesso,'f')." m3","",1,"L",0);

				LinhaBrancoPDF($pdf);

				$pdf->SetFont('Arial','B',9);
				$pdf->SetX(5);
				$pdf->Cell(20,4,$RLx25_receit,"",0,"L",0);
				$pdf->Cell(60,4,$RLk02_descr,"",0,"L",0);
				$pdf->Cell(60,4,$RLx25_descr,"",0,"L",0);
				$pdf->Cell(20,4,$RLx23_valor,"",1,"R",0);

				$rCalcVal = $Consulta->RecordSetAguaCalcVal($x22_codcalc);

				$nSoma = 0;

				$pdf->SetFont('Arial','',9);
				for($indz=0; $indz<pg_numrows($rCalcVal);$indz++) {
					db_fieldsmemory($rCalcVal, $indz);
					$nSoma += $x23_valor;

					$pdf->SetX(5);
					$pdf->Cell(20,4,$x25_receit,"",0,"L",$indz%2);
					$pdf->Cell(60,4,$k02_descr,"",0,"L",$indz%2);
					$pdf->Cell(60,4,$x25_descr,"",0,"L",$indz%2);
					$pdf->Cell(20,4,db_formatar($x23_valor,'f'),"",1,"R",$indz%2);

				}

				$pdf->SetX(5);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(140,4,"T O T A L   C A L C U L A D O","",0,"L",$indz%2);
				$pdf->Cell(20,4,db_formatar($nSoma,'f'),"",1,"R",$indz%2);

			}
		} else {
		   	MensagemPDF($pdf, "Sem Cálculo gerado.");
		}
	}

$pdf->Output();

<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include ("classes/db_inicialdoc_classe.php");
include ("classes/db_inicial_classe.php");
include ("classes/db_processoforoinicial_classe.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_iptubase_classe.php");
include ("classes/db_issbase_classe.php");
include ("classes/db_promitente_classe.php");
include ("classes/db_propri_classe.php");
include ("classes/db_advog_classe.php");
include ("classes/db_socios_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_termoini_classe.php");
include ("classes/db_termo_classe.php");
include ("classes/db_arrecad_classe.php");
include ("classes/db_averba_classe.php");
include ("fpdf151/pdf1.php");
include ("classes/db_db_docparag_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clinicialdoc           = new cl_inicialdoc;
$clinicial              = new cl_inicial;
$clprocessoforoinicial  = new cl_processoforoinicial;
$cldb_config            = new cl_db_config;
$cladvog                = new cl_advog;
$cliptubase             = new cl_iptubase;
$clpromitente           = new cl_promitente;
$clpropri               = new cl_propri;
$clcgm                  = new cl_cgm;
$clsocios               = new cl_socios;
$clissbase              = new cl_issbase;
$cltermoini             = new cl_termoini;
$cltermo                = new cl_termo;
$clarrecad              = new cl_arrecad;
$claverba               = new cl_averba;
$cldb_docparag          = new cl_db_docparag;

$pdf = new PDF1();
$pdf->Open();
$pdf->AliasNbPages();

$grupodocumento = 0;

if (isset ($dadosini) && $dadosini != "") {
	$matriz = split("xx", $dadosini);
}
for ($q = 0; $q < sizeof($matriz); $q ++) {
	if ($matriz[$q] != "") {
		$dadosi = split("ww", $matriz[$q]);
		$inicial = $dadosi[0];
		$chave = $dadosi[1];
		$modo = $dadosi[2];

		//Traz data atual--------------------------------------------------------------------------------------------

		$dia = date('d', db_getsession("DB_datausu"));
		$mes = date('m', db_getsession("DB_datausu"));
		$ano = date('Y', db_getsession("DB_datausu"));
		$mes = db_mes($mes);

		//-----------------Rotina que busca o numpre e pega a data da ultima parcela---------------------------------
		$result_numpre = $cltermoini->sql_record($cltermoini->sql_query(null, $inicial, "distinct v07_numpre as numpre,parcel as termo"));
		if ($cltermoini->numrows != 0) {
			db_fieldsmemory($result_numpre, 0);
			$result_ultdata = $clarrecad->sql_record($clarrecad->sql_query_file_instit(null, "k00_dtvenc as ult_parcela", "k00_numpar desc limit 1", "arrecad.k00_numpre=$numpre and k00_instit = ".db_getsession('DB_instit') ));
			if ($clarrecad->numrows != 0) {
				db_fieldsmemory($result_ultdata, 0, true);
			}
		  $result_resp = $cltermo->sql_record($cltermo->sql_query($termo, "z01_nome as responsavel",null,null," v07_parcel = $termo and v07_instit = ".db_getsession('DB_instit') ));
			if ($cltermo->numrows > 0) {
				db_fieldsmemory($result_resp, 0);
			} else {
				$responsavel = "";
			}
		}
		//----------------------------------------------------------------------------------------------------------

		$res = $clinicial->sql_record($clinicial->sql_query($inicial, "a.z01_nome as advogado,v57_oab",null,"v50_inicial = $inicial and v50_instit = ".db_getsession('DB_instit')));
		$numrows = $clinicial->numrows;
		db_fieldsmemory($res, 0); //pega advogado

		$result = $clinicial->sql_record($clinicial->sql_query($inicial, "v54_descr as localiza",null," v50_inicial = $inicial and v50_instit = ".db_getsession('DB_instit') ));
		db_fieldsmemory($result, 0); //pega localiza

		$sWhere  = "processoforoinicial.v71_inicial = {$inicial} and processoforoinicial.v71_anulado is false"; 
		$result  = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query(null,"v70_codforo,v53_descr",null,$sWhere));
		$numrows = $clprocessoforoinicial->numrows;
		db_fieldsmemory($result, 0); //pega codigo do processo

		$resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"), "nomeinst as prefeitura,ender as enderpref,munic"));
		db_fieldsmemory($resul, 0); //pega o dados da prefa

		$datac = date("d-m-Y");
		$dat = split("-", $datac);
		$mes = db_mes($dat[1]);
		$data = $dat[0]." de ".$mes." de ".$dat[2].".";

		if ($modo == "matricula") {
			$j01_matric = $chave;
			$sql_p = "select z01_nome as proprietario, pql_localizacao, j40_refant from proprietario where j01_matric=$j01_matric";
			$result = pg_query($sql_p);
			db_fieldsmemory($result, 0); //pega o nome do proprietario
			//---------------------Busca nome do antigo proprietario do imovel----------------

			$result_propriant = $claverba->sql_record($claverba->sql_query_nomeant(null, "z01_nome as antigo", "j55_codave desc", "j55_matric=$j01_matric"));
			if ($claverba->numrows != 0) {
				db_fieldsmemory($result_propriant, 0);
			}
			$testando = 123;
			//--------------------------------------------------------------------------------
		} else
			if ($modo == "inscricao") {
				$q02_inscr = $chave;
				$result = $clissbase->sql_record($clissbase->sql_query($q02_inscr, "cgm.z01_nome as proprietario, q02_inscmu"));
				db_fieldsmemory($result, 0); //pega o nome do proprietario
			}
		if (isset ($atuender) && $atuender == true) {
			$documento = 17;
		}

		if (isset ($pagaparcela) && $pagaparcela == true) {
			$documento = 22;
      $grupodocumento = 4;
		}
		if (isset ($cancelparcela) && $cancelparcela == true) {
			$documento = 21;
		}
		if (isset ($fazparcela) && $fazparcela == true) {
			$documento = 18;
      $grupodocumento = 3;
		}
		if (isset ($inclupropri) && $inclupropri == true) {
			$documento = 20;
			if ($modo == "matricula") {
				$reiptu = $cliptubase->sql_record($cliptubase->sql_query($j01_matric, "cgm.z01_nome as proprietario"));
				$numiptu = $cliptubase->numrows;
			} else
				if ($modo == "inscricao") {
					$reiptu = $clissbase->sql_record($clissbase->sql_query($q02_inscr, "cgm.z01_nome as proprietario"));
					$numiptu = $clissbase->numrows;
				}
			if ($numiptu != 0) {
				db_fieldsmemory($reiptu, 0);
			}

		}
		if (isset ($trocapropri) && $trocapropri == true) {
			$documento = 19;
		}

		if (isset ($trocapropri) && $trocapropri == true || isset ($pagaparcela) && $pagaparcela == true || isset ($cancelparcela) && $cancelparcela == true || isset ($fazparcela) && $fazparcela == true) {
			if ($modo == "matricula") {
				$k = "";
				$proprietario = "";
				$reiptu = $cliptubase->sql_record($cliptubase->sql_query($j01_matric, "cgm.z01_nome as nome"));
				$numiptu = $cliptubase->numrows;
				if ($numiptu != 0) {
					db_fieldsmemory($reiptu, 0);
					$proprietario .= $nome;
					$k = " E/OU ";
				}
				$repro = $clpropri->sql_record($clpropri->sql_query($j01_matric, "", "cgm.z01_nome as nome,cgm.z01_numcgm"));
				$numpropri = $clpropri->numrows;
				if ($numpropri != 0) {
					for ($xi = 0; $xi < $numpropri; $xi ++) {
						db_fieldsmemory($repro, $xi);
						$proprietario .= $k.$nome;
						$k = " E/OU ";
					}
				}
				$repromi = $clpromitente->sql_record($clpromitente->sql_query($j01_matric, "", "cgm.z01_nome as nome,j41_tipopro as tipopro,cgm.z01_numcgm"));
				$numpromi = $clpromitente->numrows;
				if ($numpromi != 0) {
					for ($xy = 0; $xy < $numpromi; $xy ++) {
						$proprietario .= $k.$nome;
						$k = " E/OU ";
					}
				}
				if (isset ($cgmatu)) {
					$matricgm = split("x", $cgmatu);
					$outrospropri = "";
					$xe = "";
					$inic = false;
					$tamanho = sizeof($matricgm);
					$dados_atu = "";
					for ($j = 0; $j < $tamanho; $j ++) {
						$numcgm = $matricgm[$j];
						$re = $clcgm->sql_record($clcgm->sql_query_file($numcgm, "z01_nome as nome,z01_numcgm,z01_ender as endereco,z01_numero as numero,z01_cep,z01_munic,z01_uf,z01_compl,z01_bairro,z01_cep,z01_cgccpf,z01_cxpostal"));
						db_fieldsmemory($re, 0);
						$tam_cgccpf = strlen($z01_cgccpf);
						if ($tam_cgccpf == 14) {
							$cpfcnpj = "CNPJ: ";
						} else
							if ($tam_cgccpf == 11) {
								$cpfcnpj = "CPF: ";
							}

						$dados_atu .= $nome. ($z01_cgccpf != "" ? ", ".$cpfcnpj.$z01_cgccpf.", " : "")." $endereco,  $numero, $z01_compl - $z01_munic/$z01_uf - $z01_cep ". ($z01_cxpostal != "" ? ", $z01_cxpostal" : "")." ";

						if ($tamanho == 1) {
							$outrospropri = "o Sr. ".$nome;
						} else {
							if ($inic == false) {
								$outrospropri .= "os Srs. ";
								$inic = true;
							}
							$outrospropri .= $xe.$nome;

							if ($j +2 == $tamanho) {
								$xe = " e ";
							} else {
								$xe = ", ";
							}
						}
					}

				}

			} else
				if ($modo == "inscricao") {

					$k = "";
					$proprietario = "";

					$reiptu = $clissbase->sql_record($clissbase->sql_query($q02_inscr, "z01_numcgm,z01_nome as nome"));
					db_fieldsmemory($reiptu, 0);

					$proprietario .= $k.$nome;
					$k = " E/OU ";
					$reso = $clsocios->sql_record($clsocios->sql_query_file("", $z01_numcgm, "q95_numcgm"));
					$numso = $clsocios->numrows;
					if ($numso != 0) {
						for ($xr = 0; $xr < $numso; $xr ++) {
							db_fieldsmemory($reso, $xr);
							$re = $clcgm->sql_record($clcgm->sql_query_file($q95_numcgm, "z01_nome as nome"));
							db_fieldsmemory($re, 0);
							$proprietario .= $k.$nome;
							$k = " E/OU ";
						}
					}
				}
		}
		$pdf->AddPage();
		//    $pdf->SetLeftMargin(35);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFillColor(220);
		
		//echo "grupo - " . $grupodocumento . "<br>documento  -  ".$documento;
		//die();
		
		
    if ( $grupodocumento > 0 ) {
  		$resulta = $cldb_docparag->sql_record($cldb_docparag->sql_query(null, "", "db_docparag.*,db02_texto,db02_alinhamento,db02_espaca,db02_alinha,db02_inicia", "db04_ordem"," db03_tipodoc = $grupodocumento"));
    
      //echo "SQL - <br>".$cldb_docparag->sql_query(null, "", "db_docparag.*,db02_texto,db02_alinhamento,db02_espaca,db02_alinha,db02_inicia", "db04_ordem"," db03_tipodoc = $grupodocumento");
      //die();
    } else {
  		$resulta = $cldb_docparag->sql_record($cldb_docparag->sql_query($documento, "", "db_docparag.*,db02_alinhamento,db02_texto,db02_espaca,db02_alinha,db02_inicia", "db04_ordem"));
    }
		$numrows = $cldb_docparag->numrows;
		if ($cldb_docparag->numrows == 0) {
      $resulta = $cldb_docparag->sql_record($cldb_docparag->sql_query(null, "", "db_docparag.*,db02_texto,db02_alinhamento,db02_espaca,db02_alinha,db02_inicia", "db04_ordem", " db03_tipodoc = 3"));
		  $numrows = $cldb_docparag->numrows;
		}
		$pdf->SetXY('10', '60');
		for ($i = 0; $i < $numrows; $i ++) {
			db_fieldsmemory($resulta, $i);
			$pdf->SetFont('Arial', '', 12);
			$pdf->SetX($db02_alinha);
			$texto = db_geratexto($db02_texto);
			if ($texto == "*tabela01*") {
				$cgms = split("x", $nums);
				for ($t = 0; $t < sizeof($cgms); $t ++) {
					if ($cgms[$t] != "") {
						$numcgm = split("y", $cgms[$t]);
						$tipos = array ("PROPRIETÁRIO PRINCIPAL", "OUTRO PROPRIETÁRIO", "PROMITENTE COMPRADOR", "PROMITENTE COMPRADOR PRINCIPAL", "SÓCIO");
						$reiptu = $clcgm->sql_record($clcgm->sql_query_file($numcgm[0], "z01_nome as nome,z01_numcgm,z01_ender as endereco,z01_numero as numero,z01_cep,z01_munic,z01_uf,z01_compl,z01_bairro,z01_cep,z01_cgccpf,z01_cxpostal"));

						db_fieldsmemory($reiptu, 0);
						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "NOME:".$nome. ($z01_cgccpf != '' ? " CPF:$z01_cgccpf" : ""), "0", "J", 0, "");
						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "TIPO:".$tipos[$numcgm[1]], "0", "J", 0, "");
						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "ENDEREÇO:$endereco, $numero, $z01_compl $z01_munic-$z01_uf", "0", "J", 0, "");
						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "CEP:$z01_cep", "0", "J", 0, "");
						$pdf->Ln();
					}
				}
			} else
				if ($texto == "*nomes*" || $texto == "*atupropri*") {
					if ($texto == "*atupropri*") {
						$cgm = $cgmatu;
					}
					$matricgm = split("x", $cgm);
					$outrospropri = "";
					$xe = "";
					$inic = false;
					$tamanho = sizeof($matricgm);
					for ($j = 0; $j < $tamanho; $j ++) {
						$numcgm = $matricgm[$j];
						$re = $clcgm->sql_record($clcgm->sql_query_file($numcgm, "z01_nome as nome,z01_numcgm,z01_ender as endereco,z01_numero as numero,z01_cep,z01_munic,z01_uf,z01_compl,z01_bairro,z01_cep,z01_cgccpf,z01_cxpostal"));
						db_fieldsmemory($re, 0);

						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "NOME:".$nome. ($z01_cgccpf != '' ? " CPF:$z01_cgccpf" : ""), "0", "J", 0, "");
						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "ENDEREÇO:$endereco, $numero, $z01_compl $z01_munic-$z01_uf", "0", "J", 0, "");
						$pdf->SetX($db02_alinha);
						$pdf->MultiCell("0", 4, "CEP:".$z01_cep."". ($z01_cxpostal != "" ? ", CAIXA POSTAL:$z01_cxpostal" : ""), "0", "J", 0, "");
						$pdf->Ln();

						if ($tamanho == 1) {
							$outrospropri = "o Sr. ".$nome;
						} else {
							if ($inic == false) {
								$outrospropri .= "os Srs. ";
								$inic = true;
							}
							$outrospropri .= $xe.$nome;

							if ($j +2 == $tamanho) {
								$xe = " e ";
							} else {
								$xe = ", ";
							}
						}
					}
				}
			elseif ($texto == "*antpropri*") {
				$matricgm = split("x", $cgmant);
				$outrospropri = "";
				$xe = "";
				$inic = false;
				$tamanho = sizeof($matricgm);
				for ($j = 0; $j < $tamanho; $j ++) {
					$numcgm = $matricgm[$j];
					$re = $clcgm->sql_record($clcgm->sql_query_file($numcgm, "z01_nome as nome,z01_numcgm,z01_ender as endereco,z01_numero as numero,z01_cep,z01_munic,z01_uf,z01_compl,z01_bairro,z01_cep,z01_cgccpf,z01_cxpostal"));
					db_fieldsmemory($re, 0);

					$pdf->SetX($db02_alinha);
					$pdf->MultiCell("0", 4, "NOME:".$nome. ($z01_cgccpf != '' ? " CPF:$z01_cgccpf" : ""), "0", "J", 0, "");
					$pdf->SetX($db02_alinha);
					$pdf->MultiCell("0", 4, "ENDEREÇO:$endereco, $numero, $z01_compl $z01_munic-$z01_uf", "0", "J", 0, "");
					$pdf->SetX($db02_alinha);
					$pdf->MultiCell("0", 4, "CEP:".$z01_cep."". ($z01_cxpostal != "" ? ", CAIXA POSTAL:$z01_cxpostal" : ""), "0", "J", 0, "");
					$pdf->Ln();

					if ($tamanho == 1) {
						$outrospropri = "o Sr. ".$nome;
					} else {
						if ($inic == false) {
							$outrospropri .= "os Srs. ";
							$inic = true;
						}
						$outrospropri .= $xe.$nome;

						if ($j +2 == $tamanho) {
							$xe = " e ";
						} else {
							$xe = ", ";
						}
					}
				}
			} else {
				$pdf->MultiCell("0", 4 + $db02_espaca, $texto, "0", $db02_alinhamento, 0, $db02_inicia +0);
			}
		}
		$pdf->SetTextColor(0,0,0);
   		$pdf->SetFillColor(220);
   		$yy = $pdf->h - 11;
   		$pdf->SetFont('Arial','',5);
   		$pdf->Text(10,$yy,'Controle Administrativo nº '.$inicial);
	}
}
$pdf->Output();
?>
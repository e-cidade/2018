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

	include("fpdf151/pdf.php");
	include("libs/db_sql.php");
	include("classes/db_veiccadcentral_classe.php");
	$clrotulo = new rotulocampo;
	$clveiccadcentral = new cl_veiccadcentral;
	
	parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
	//db_postmemory($HTTP_SERVER_VARS,2);exit;
	
	if($quebra == 's') {
	  $head6 = "Quebra de página : SIM";
	} else {
	  $head6 = "Quebra de página : NÃO";
	}
	$head3 = "CENTRAIS DE VEÍCULOS  ";
	
	if ($codcentral == "0") {
	  $where = "";
	} else {
	  $where = "ve36_sequencial=$codcentral";
	}
	
	$xordem = 'codigo_central,codigo_veiculo';
	$sql    = "ve36_sequencial as codigo_central,
	           descrdepto      as central,
	           ve01_codigo     as codigo_veiculo,
	           ve01_placa      as placa,
	           ve20_descr      as descr_tipo,
	           ve22_descr      as descr_modelo,
	           ve21_descr      as descr_marca,
	           ve01_anofab     as ano_fabricacao,
	           ve01_ranavam    as renavam,
	           case 
	           when (select ve04_veiculo from veicbaixa where ve04_veiculo = veiccentral.ve40_veiculos) is not null
	           then 'SIM'
	           else 'NAO'
	           end as baixado";
	
	$sVeiculoCentral = $clveiccadcentral->sql_query_veiculoscentral(null,$sql,$xordem,$where);
	$result_central =  $clveiccadcentral->sql_record($sVeiculoCentral);
	
	$sql   = "veicmotoristas.ve05_codigo as codigo_motorista,
	          z01_nome as motorista,
	          ve30_descr as CNH,
            ve05_dtvenc as datavenc,
            case 
            when ve05_veiccadmotoristasit=1
            then 'ATIVO'
            else 'INATIVO'
            end as situacao";
	
	$iResultCentral = pg_numrows($result_central);
	
	/**
	 * Caso não retorne 0 linhas será exibida a mensagem de erro abaixo
	 */
	if ($iResultCentral == 0) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros .');
	}
	
	db_fieldsmemory($result_central, 0);
	if ($codcentral == "0") {
	  $head5 = "Central : TODAS";
	}	else {
	  $head5 = "Central : $central";
	}
	
	
	$pdf   = new PDF(); 
	$pdf->Open(); 
	$pdf->AliasNbPages(); 
	$total = 0;
	$pdf->setfillcolor(235);
	$pdf->setfont('arial','b',8);
	$troca = 1;
	$alt   = 4;
	$alt2  = 3;
	
	$passa           = false;
	$codigocentral   = "";
	$passamotorista  = true;
	$p               = 0;
	$pp              = 0;
	
	
	for ($x = 0; $x < pg_numrows($result_central); $x++) {
	  
	  db_fieldsmemory($result_central, $x);
	  if ($codcentral != "0" ) {
	    $codigocentral = $codigo_central;
	  }
	
	  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
	    $pdf->addpage();
	  }
	
		// Preenche com 0 (zero) a esquerda
		$iRenavam = str_pad($renavam, 10, 0, STR_PAD_LEFT);
	  
	  //veiculos
	  if ($passa==false ) {
	    
	    $codigocentral = $codigo_central;
	    $pdf->setfont('arial', 'b', 8);
	    $pdf->cell(195, $alt, $codigo_central."-".$central, 0, 1, "L", 0);
	    $pdf->cell(195, $alt, "", 0, 1, "L", 0);
	    $passa = true;
	
	    $pdf->cell(13,$alt, "CodVeic", 1, 0, "C", 1);
	    $pdf->cell(13,$alt, "Placa", 1, 0, "C", 1);
	    $pdf->cell(27,$alt, "Descr. Tipo", 1, 0, "C", 1);
	    $pdf->cell(43,$alt, "Descr. Modelo", 1, 0, "C", 1);
	    $pdf->cell(30,$alt, "Descr. Marca", 1, 0, "C", 1);
	    $pdf->cell(23,$alt, "Ano Fabricação", 1, 0, "C", 1);
	    $pdf->cell(23,$alt, "Renavam", 1, 0, "C", 1);
	    $pdf->cell(20,$alt, "Baixado", 1, 1, "C", 1);
	    $pdf->setfont('arial','',6);
	    $p     = 0;
	    $troca = 0;
	  }
	
	
	  if ($codigocentral == $codigo_central) {
	  	
	    $pdf->setfont('arial', '', 6);
	    $pdf->cell(13, $alt, $codigo_veiculo, 0, 0, "C", $p);
	    $pdf->cell(13, $alt, substr($placa, 0 , 7), 0, 0, "C", $p);
	    $pdf->cell(27, $alt, substr($descr_tipo, 0, 23), 0, 0, "L", $p);
	    $pdf->cell(43, $alt, substr($descr_modelo, 0, 22), 0, 0, "L", $p);
	    $pdf->cell(30, $alt, substr($descr_marca, 0, 23), 0, 0, "L", $p);
	    $pdf->cell(23, $alt, $ano_fabricacao, 0, 0, "C", $p);
	    $pdf->cell(23, $alt, $iRenavam, 0, 0, "C", $p);
	    $pdf->cell(20, $alt, $baixado, 0, 1, "C", $p);
	  } else {
	    //motorista
	    
	  	$sVeiculosMotoristas = $clveiccadcentral->sql_query_veiculosmotoristas(null,$sql,
																																				  	"veicmotoristas.ve05_codigo",
																																				  	"veicmotoristascentral.ve41_veiccadcentral = $codigocentral");
	    $result_motoristas =  $clveiccadcentral->sql_record($sVeiculosMotoristas);
	    
	    if ($clveiccadcentral->numrows > 0) {
	      
	    	$pdf->cell(195, $alt, "", 0, 1, "L", 0);
	      for($y= 0; $y < pg_numrows($result_motoristas); $y++) {

	      	db_fieldsmemory($result_motoristas, $y);
	        if ($passamotorista == true) {
	          $pdf->setfont('arial', 'b', 8);
	          $pdf->cell(13, $alt2, "CodMot", 1, 0, "C", 1);
	          $pdf->cell(70, $alt2, "Nome", 1, 0, "C", 1);
	          $pdf->cell(23, $alt2, "Cat. CNH", 1, 0, "C", 1);
	          $pdf->cell(33, $alt2, "Validade da Carteira", 1, 0, "C", 1);
	          $pdf->cell(13, $alt2, "Situação", 1, 1, "C", 1);
	          $pdf->setfont('arial', '', 6);
	        }
	         
	        $pdf->setfont('arial', '', 6);
	        $pdf->cell(13, $alt2, "$codigo_motorista", 0, 0, "C", $pp);
	        $pdf->cell(70, $alt2, substr($motorista,0,36), 0, 0, "L", $pp);
	        $pdf->cell(23, $alt2, substr($cnh , 0, 10), 0, 0, "C", $pp);
	        $pdf->cell(33, $alt2, db_formatar($datavenc, 'd'), 0, 0, "C", $pp);
	        $pdf->cell(13, $alt2, "$situacao", 0, 1, "C", $pp);
	        $passamotorista = false;
	        
	        if ($pp == 0) {
	          $pp = 1;
	        } else {
	          $pp = 0;
	        }
	
	      }
	
	      $passamotorista = true;
	    }
	    //veiculos
	    if ($quebra == "s") {
	      $pdf->addpage();
	
	    }
	    $pdf->setfont('arial', 'b', 8);
	    $pdf->cell(195, $alt, "", 0, 1, "L", 0);
	    $pdf->cell(195, $alt,$codigo_central."-".$central,0,1,"L",0);
	    $pdf->cell(195, $alt,"",0,1,"L",0);

	    $pdf->cell(13, $alt, "CodVeic", 1,0, "C", 1);
	    $pdf->cell(13, $alt, "Placa", 1, 0, "C", 1);
	    $pdf->cell(27, $alt, "Descr. Tipo", 1, 0, "C", 1);
	    $pdf->cell(43, $alt, "Descr. Modelo", 1, 0, "C", 1);
	    $pdf->cell(30, $alt, "Descr. Marca", 1, 0, "C", 1);
	    $pdf->cell(23, $alt, "Ano Fabricação", 1, 0, "C", 1);
	    $pdf->cell(23, $alt, "Renavam", 1, 0, "C", 1);
	    $pdf->cell(20, $alt, "Baixado", 1, 1, "C", 1);
	
	    $p = 0;
	    $pdf->setfont('arial', '', 6);
	    $pdf->cell(13, $alt, $codigo_veiculo, 0, 0, "C", $p);
	    $pdf->cell(13, $alt, substr($placa, 0, 7), 0, 0, "C", $p);
	    $pdf->cell(27, $alt, substr($descr_tipo, 0, 23), 0, 0, "L", $p);
	    $pdf->cell(43, $alt, substr($descr_modelo, 0, 22), 0, 0, "L", $p);
	    $pdf->cell(30, $alt, substr($descr_marca, 0, 23), 0, 0, "L", $p);
	    $pdf->cell(23, $alt, $ano_fabricacao, 0, 0, "C", $p);
	    $pdf->cell(23, $alt, $iRenavam, 0, 0, "C", $p);
	    $pdf->cell(20, $alt, $baixado, 0, 1, "C", $p);
	
	  }
	  if ($p == 0) {
	    $p = 1;
	  } else {
	    $p = 0;
	  }
	  $codigocentral = $codigo_central;
	  $total ++;
	}
	//motorista
	
	$sVeiculosMotoristasSql = $clveiccadcentral->sql_query_veiculosmotoristas(null,$sql,
																																						"veicmotoristas.ve05_codigo",
																																						"veicmotoristascentral.ve41_veiccadcentral = $codigo_central");
	$result_motoristas =  $clveiccadcentral->sql_record($sVeiculosMotoristasSql);
	
	if ($clveiccadcentral->numrows > 0) {
		
	  $pdf->cell(195, $alt, "", 0, 1, "L", 0);
	  for ($y= 0; $y < pg_numrows($result_motoristas); $y++) {
	  	
	    db_fieldsmemory ($result_motoristas, $y);
	    if ($passamotorista == true) {
	    
	      $pdf->setfont('arial', 'b', 8);
	      $pdf->cell(13, $alt2, "CodMot", 1, 0, "C", 1);
	      $pdf->cell(70, $alt2, "Nome", 1, 0, "C", 1);
	      $pdf->cell(23, $alt2, "Cat. CNH", 1, 0, "C", 1);
	      $pdf->cell(33, $alt2, "Validade da Carteira", 1, 0, "C", 1);
	      $pdf->cell(13, $alt2, "Situação", 1, 1, "C", 1);
	    }
	    $pdf->setfont('arial', '', 6);
	    $pdf->cell(13, $alt2, "$codigo_motorista", 0, 0, "C", $pp);
	    $pdf->cell(70, $alt2, substr($motorista, 0, 36), 0, 0, "L", $pp);
	    $pdf->cell(23, $alt2, substr($cnh, 0, 10), 0, 0, "C", $pp);
	    $pdf->cell(33, $alt2, db_formatar($datavenc, 'd'), 0, 0, "C", $pp);
	    $pdf->cell(13, $alt2, "$situacao", 0, 1, "C", $pp);
	    $passamotorista = false;
	
	    if ($pp == 0){
	      $pp = 1;
	    } else {
	      $pp = 0;
	    }
	  }
	  $passamotorista = true;
	}
	/*
	   $pdf->setfont('arial','b',8);
	   $pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
	 */
	$pdf->output();
?>
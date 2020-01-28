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
include("classes/db_veiculos_classe.php");
include("classes/db_veiculoscomb_classe.php");

$clveiculos     = new cl_veiculos;
$clveiculoscomb = new cl_veiculoscomb;

$clveiculos->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("ve06_veiccadcomb");
$clrotulo->label("ve40_veiccadcentral");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$where=" ve36_coddepto = " . db_getsession("DB_coddepto");
if ($tipo!=""){
	$where .= " and ve01_veiccadtipo = $tipo ";
}
if ($comb!=""){
	$where .= " and ve06_veiccadcomb = $comb ";
}
if ($busca=='b'){
	$where .= " and ve01_ativo = '0' ";
}else if ($busca=='n'){
	$where .= " and ve01_ativo = '1' ";
}
if (($dtaquis != "--") && ($dtaquis1 != "--")) {
	$where .= " and  ve01_dtaquis  between '$dtaquis' and '$dtaquis1'  ";
	$dtaquis = db_formatar($dtaquis, "d");
	$dtaquis1 = db_formatar($dtaquis1, "d");
	$info = "Aquisição De $dtaquis até $dtaquis1.";
} else if ($dtaquis != "--") {
	$where .= " and  ve01_dtaquis >= '$dtaquis'  ";
	$dtaquis = db_formatar($dtaquis, "d");
	$info = "Aquisição Apartir de $dtaquis.";
} else if ($dtaquis1 != "--") {
	$where .= "and ve01_dtaquis <= '$dtaquis1'   ";
	$dtaquis1 = db_formatar($dtaquis1, "d");
	$info = "Aquisição Até $dtaquis1.";
}

$head3 = "CADASTRO DE VEÍCULOS";
$head4 = @$info;

$campos = "distinct ve01_codigo,ve01_placa,ve20_descr,ve21_descr,ve22_descr,ve23_descr,ve25_descr,ve32_descr,
                    ve01_ranavam,ve01_chassi,ve01_certif,ve01_placanum,ve01_quantpotencia,ve31_descr,ve31_descrcompleta,
                    ve01_medidaini,ve01_quantcapacidad,ve24_descr,ve01_dtaquis,ve30_descr,ve01_anofab,ve01_anomod,cp05_localidades,
                    descrdepto,ve01_ativo,ve07_sigla";
$result = $clveiculos->sql_record($clveiculos->sql_query_veiculo(null,$campos,null,$where));
if ($clveiculos->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem veículos cadastrados.');
}

//db_criatabela($result);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;
for($x = 0; $x < $clveiculos->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(10,$alt,/*$RLve01_codigo*/"Cod.",1,0,"C",1);
      $pdf->cell(10,$alt,$RLve01_placa,1,0,"C",1);
      $pdf->cell(30,$alt,$RLve01_veiccadtipo,1,0,"C",1);
      $pdf->cell(30,$alt,$RLve01_veiccadmarca,1,0,"C",1);
      $pdf->cell(30,$alt,$RLve01_veiccadmodelo,1,0,"C",1);
      $pdf->cell(30,$alt,$RLve01_veiccadcor,1,0,"C",1);
      $pdf->cell(30,$alt,$RLve01_veiccadproced,1,0,"C",1);
      $pdf->cell(30,$alt,$RLve01_veiccadcateg,1,0,"C",1);
      $pdf->cell(20,$alt,$RLve01_ranavam,1,0,"C",1);
      $pdf->cell(35,$alt,$RLve01_chassi,1,0,"C",1);
	  $pdf->cell(25,$alt,/*$RLve01_certif*/"Nº Certif.",1,1,"C",1);
      
      $pdf->cell(20,$alt,/*$RLve01_placanum*/"Placa em Nº",1,0,"C",1);      
      $pdf->cell(35,$alt,$RLve01_veiccadpotencia,1,0,"C",1);
      $pdf->cell(20,$alt,$RLve01_medidaini,1,0,"C",1);
      $pdf->cell(35,$alt,/*$RLve01_veiccadtipocapacidade*/"Capacidade",1,0,"C",1);
      $pdf->cell(20,$alt,/*$RLve01_dtaquis*/"Aquisição",1,0,"C",1);
      $pdf->cell(30,$alt,$RLve06_veiccadcomb,1,0,"C",1);
      $pdf->cell(20,$alt,/*$RLve01_veiccadcategcnh*/"CNH Exigida",1,0,"C",1);
      $pdf->cell(10,$alt,/*$RLve01_anofab*/"Fab.",1,0,"C",1);
      $pdf->cell(10,$alt,/*$RLve01_anomod*/"Mod.",1,0,"C",1);
      $pdf->cell(35,$alt,$RLve01_ceplocalidades,1,0,"C",1);
      $pdf->cell(35,$alt,$RLve40_veiccadcentral,1,0,"C",1);
      $pdf->cell(10,$alt,$RLve01_ativo,1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',6);
   $pdf->cell(10,$alt,$ve01_codigo,0,0,"C",$p);
   $pdf->cell(10,$alt,$ve01_placa,0,0,"C",$p);
   $pdf->cell(30,$alt,$ve20_descr,0,0,"L",$p);
   $pdf->cell(30,$alt,$ve21_descr,0,0,"L",$p);
   $pdf->cell(30,$alt,$ve22_descr,0,0,"L",$p);
   $pdf->cell(30,$alt,$ve23_descr,0,0,"L",$p);
   $pdf->cell(30,$alt,$ve25_descr,0,0,"L",$p);
   $pdf->cell(30,$alt,$ve32_descr,0,0,"L",$p);
   $pdf->cell(20,$alt,$ve01_ranavam,0,0,"C",$p);
   $pdf->cell(35,$alt,$ve01_chassi,0,0,"C",$p);       
   $pdf->cell(25,$alt,$ve01_certif,0,1,"C",$p);
   
   $pdf->cell(20,$alt,$ve01_placanum,0,0,"C",$p);      
   $pdf->cell(35,$alt,$ve01_quantpotencia." ".$ve31_descr."-".$ve31_descrcompleta,0,0,"L",$p);
   $pdf->cell(20,$alt,$ve01_medidaini." ".$ve07_sigla,0,0,"C",$p);
   $pdf->cell(35,$alt,$ve01_quantcapacidad." ".$ve24_descr,0,0,"L",$p);
   $pdf->cell(20,$alt,db_formatar($ve01_dtaquis,"d"),0,0,"C",$p);

   $result_combustiveis = $clveiculoscomb->sql_record($clveiculoscomb->sql_query(null,"ve06_padrao,ve26_descr",null,"ve06_veiculos = $ve01_codigo"));
   if ($clveiculoscomb->numrows > 0){
     $virgula   = "";
     $vet_comb  = array(array("descr","padrao"));
     $cont_comb = 0;
     for($xx = 0; $xx < $clveiculoscomb->numrows; $xx++){
        db_fieldsmemory($result_combustiveis,$xx);

        $vet_comb["descr"][$cont_comb] = $ve26_descr;

        if ($ve06_padrao == "t"){
          $padrao = 1;
        } else {
          $padrao = 0;
        }

        $vet_comb["padrao"][$cont_comb] = $padrao;
        $cont_comb++;
     }
        
     $valor = "";
     for($xx = 0; $xx < $cont_comb; $xx++){
        if ($vet_comb["padrao"][$xx] == 1){
          $valor = $vet_comb["descr"][$xx];
          break;
        }
     }

     $virgula = ", ";
     for($xx = 0; $xx < $cont_comb; $xx++){
        if ($vet_comb["padrao"][$xx] == 0 && $vet_comb["descr"][$xx] != ""){
          $valor .= $virgula.$vet_comb["descr"][$xx];
        }

        $virgula = ", ";
     }
     if (strlen(trim($valor)) > 20){
       $valor = substr(trim($valor),0,20)."...";
     }
   } else {
    $valor = "Nenhum combústivel cadastrado.";
   }

   $pdf->cell(30,$alt,$valor,0,0,"L",$p);
   $pdf->cell(20,$alt,$ve30_descr,0,0,"L",$p);
   $pdf->cell(10,$alt,$ve01_anofab,0,0,"C",$p);
   $pdf->cell(10,$alt,$ve01_anomod,0,0,"C",$p);
   $pdf->cell(35,$alt,substr($cp05_localidades,0,26),0,0,"L",$p);
   $pdf->cell(35,$alt,substr($descrdepto,0,26),0,0,"L",$p);
   if ($ve01_ativo=="f"){
   	$ativo="Não";
   }else{
   	$ativo="Sim";
   }
   $pdf->cell(10,$alt,$ativo,0,1,"C",$p);  
   
   if ($p==0){
   	$p=1;
   }else{
   	$p=0;
   }   
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"R",0);
$pdf->Output();
?>
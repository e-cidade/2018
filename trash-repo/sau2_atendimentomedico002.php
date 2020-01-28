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
require_once("libs/db_utils.php");
include("classes/db_prontproced_ext_classe.php");
include("classes/db_cgs_und_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//$clprontuarios = new cl_prontuarios_ext;
$clprontproced = new cl_prontproced_ext;
$clcgs_und     = new cl_cgs_und;

//$clprontuarios->rotulo->label();
$clprontproced->rotulo->label();
$clcgs_und->rotulo->label();

$str_where = "1=1";
if($listaprof != ""){
  $head7 = "Prof.:".$listaprof;  
  if( $verprof == "com" ){
   	$str_where .= " and sd03_i_codigo in ($listaprof)";
  }else if($listaprof != "" ){
  	$str_where .= " and sd03_i_codigo not in ($listaprof)";
  }
}

if($listaups != ""){
  $head7 = "Ups .:".$listaups;  
  if( $verups == "com" ){
   	$str_where .= " and sd02_i_codigo in ($listaups)";
  }else if($listaups != "" ){
	  $str_where .= " and sd02_i_codigo not in ($listaups)";
  }
}

$hoje= date("Y-m-d",db_getsession("DB_datausu"));

if($data1 != "//" && $data2 != "//"){
   $str_where .= " and sd29_d_data between '$data1' and '$data2' ";
   $head5 = "Período:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
}else if($data1 != "//" && $data2 == "//"){
   $str_where .= " and sd29_d_data between '$data1' and '$hoje' ";
   $head5 = "Período: ".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".date("d/m/Y",db_getsession("DB_datausu"));
}else if($data1 == "//" && $data2 != "//"){
   $str_where .= " and sd29_d_data <= '$data2'";
}else if($data1 == "//" && $data2 == "//"){
   $str_where .= " and sd29_d_data <= '$hoje'";
}
   

$str_ordem  = ($quebra == "ups"?"sd02_i_codigo, ":"");
$str_ordem  .= ($ordem  == "a"?" z01_nome":" sd03_i_codigo");

$sql = $clprontproced->sql_query_ext("","
							sd02_i_codigo,
							descrdepto,
							sd03_i_codigo,
							sd03_i_cgm,
							z01_nome,
							rh70_estrutural,
							rh70_descr,
							sd24_i_codigo,
							z01_i_cgsund,
							z01_v_nome,
							sd63_c_procedimento,
							sd63_c_nome
								", $str_ordem, $str_where
						); 
            
$res_prontproced = $clprontproced->sql_record($sql); 
//echo "<BR> $sql";

if( $clprontproced->numrows == 0){
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
	exit;
}


$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head3 = "Relatório de Atendimento Médico";


$intProf       = 0;
$intUPS        = 0;
$intTotalatend = 0;
$intTotalgeral = 0;

for ($i = 0;$i < $clprontproced->numrows;$i++){
	db_fieldsmemory($res_prontproced,$i);
	
	$booPagina = ($pdf->gety() > $pdf->h - 25 );
	//Quebra por UPS
	if( ($quebra == "ups" && $i > 0 && $intUPS != $sd02_i_codigo ) || ($intProf == 0 && $intUPS == 0) || ($booPagina) ) {
		$pdf->addpage();	
		$pdf->setfillcolor(200);		
	}
	
	//Profissional
	if( $intProf != $sd03_i_codigo || $booPagina ){
 		if( $intProf != 0 && $intProf != $sd03_i_codigo ){
			$pdf->setfont('times','b',7);
	 		$pdf->cell(50,5,"TOTAL DE ATENDIMENTO:","T",0,"L",0);
			$pdf->setfont('times','',7);
	 		$pdf->cell(140,5,$intTotalatend,"T",1,"L",0);
	 		$intTotalgeral += $intTotalatend;
	 		$intTotalatend = 0;	 			
 			$pdf->cell(0,5,"",0,1,"L",0);
 		}
		if( ($pdf->gety() > $pdf->h -35 ) ){
			$booPagina = ($pdf->gety() > $pdf->h -35 );
			$pdf->addpage();
		}
		
 		$pdf->setfont('times','b',8);
 		$pdf->cell(20,5,"Profissional:",0,0,"L",1);
		$pdf->setfont('times','',8);
 		$pdf->cell(80,5,$sd03_i_codigo." - ".$z01_nome,0,0,"L",1);
		$pdf->setfont('times','b',8);
 		$pdf->cell(10,5,"CBO:",0,0,"L",1);
		$pdf->setfont('times','',8);
 		$pdf->cell(80,5,$rh70_estrutural." - ".$rh70_descr,0,1,"L",1);
 		$pdf->setfont('times','b',8);
    $pdf->cell(20,5,"Matricula(s):",0,0,"L",1);
    $pdf->setfont('times','',8);
    
    $oDaoRhPessoal = db_utils::getdao('rhpessoal');
    $sSql          = $oDaoRhPessoal->sql_query_func_rhpessoal("","rh01_regist,rh05_recis",""," rh01_numcgm=$sd03_i_cgm ");
    $rsMatriculas  = $oDaoRhPessoal->sql_record($sSql);
    $sMatricula    = "";
    $sSep          = "";
    for ($iX=0; $iX < $oDaoRhPessoal->numrows; $iX++){
      
    	$oMatricula  = db_utils::fieldsmemory($rsMatriculas, $iX);
      $sMatricula .= $sSep.$oMatricula->rh01_regist;
      if($oMatricula->rh05_recis == null){
        $sMatricula .= "(ativa)";
      }
      $sSep        = ", ";
    	
    }
    
    $pdf->cell(80,5,$sMatricula,0,0,"L",1);
    $pdf->setfont('times','b',8);
    $pdf->cell(10,5,"",0,0,"L",1);
    $pdf->setfont('times','',8);
    $pdf->cell(80,5,"",0,1,"L",1);
	}
	//UPS
	if( $intUPS != $sd02_i_codigo || $intProf != $sd03_i_codigo || $booPagina ){
	  if( $quebra == "ups") {
  		$pdf->setfont('times','b',8);
 	  	$pdf->cell(10,5,"UPS:",0,0,"L",0);
		  $pdf->setfont('times','',8);
 		  $pdf->cell(105,5,$sd02_i_codigo." - ".$descrdepto,0,1,"L",0);
    }
 		$intUPS = $sd02_i_codigo;
 		$intProf = $sd03_i_codigo; 		
 		//Cabeçalho Procedimentos
		$pdf->setfont('times','b',8);
 		$pdf->cell(10,5,"FAA:","B",0,"L",0);
 		$pdf->cell(10,5,"Cód.:","B",0,"L",0);
 		$pdf->cell(60,5,"Paciente:","B",0,"L",0);
	  if( $quebra != "ups") {
 		  $pdf->cell(100,5,"Procedimento","B",0,"L",0);
 		  $pdf->cell(10,5,"UPS","B",1,"R",0);
    }else{
 		  $pdf->cell(105,5,"Procedimento","B",1,"L",0);
    }
		$pdf->setfont('times','',8); 		
	}
	
	//Procedimentos
	$pdf->setfont('times','',7); 		
	$pdf->cell(10,4,$sd24_i_codigo,0,0,"L",0);
 	$pdf->cell(10,4,$z01_i_cgsund,0,0,"L",0);
 	$pdf->cell(60,4,substr(trim($z01_v_nome),0,37),0,0,"L",0);
	if( $quebra != "ups") {
 	  $pdf->cell(100,4,substr(trim($sd63_c_procedimento)." - ".trim($sd63_c_nome),1,70),0,0,"L",0);
 	  $pdf->cell(10,4,$sd02_i_codigo,0,1,"R",0);
  }else{
 	  $pdf->cell(105,4,substr(trim($sd63_c_procedimento)." - ".trim($sd63_c_nome),1,70),0,1,"L",0);
  }
 	$intTotalatend++;
}

$pdf->setfont('times','b',7);
$pdf->cell(50,5,"TOTAL DE ATENDIMENTO:","T",0,"L",0);
$pdf->setfont('times','',7);
$pdf->cell(140,5,$intTotalatend,"T",1,"L",0);
$intTotalgeral += $intTotalatend;
$pdf->setfont('times','b',7);
$pdf->cell(50,5,"TOTAL GERAL:",0,0,"L",0);
$pdf->setfont('times','',7);
$pdf->cell(140,5,$intTotalgeral,0,1,"L",0);

$pdf->Output();
?>
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


$clrotulo = new rotulocampo;
$clrotulo->label('q02_inscr');
$clrotulo->label('q02_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('q03_descr');
$clrotulo->label('q12_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$lImpCab  = true;
$iTamCamp = 48;


$txt_where="";
if ($lista!=""){
  if (isset($ver) and $ver=="com"){
    $txt_where= $txt_where." and q03_ativ in ($lista)";
  } else {
    $txt_where= $txt_where." and q03_ativ not in ($lista)";
  }	 
}  

if ($baix == "t") {
	$where = "";
	$info  = "Todas Inscrições";
}else if ($baix == "b") {
	$where = "and q02_dtbaix is not null";
	$info  = "Inscrições Baixadas";
}else if ($baix == "c"){
	$where = "and q02_dtbaix is null";
	$info  = "Inscrições não Baixadas ";
}
 
if(isset($bairroInscr)&& trim($bairroInscr) != ""){
  $where .= " and q13_bairro in ({$bairroInscr})";
}

switch($selAgrupa){
	case "n":
	 $cabCod    = "";
	 $Troca			= "a";
	 $Cab				= "Troca";
	 $lImpCab		= false;
	 $ColCampo1 = "q03_descr"; 
	 $ColCampo2 = "q12_descr";  
   $ColCampo3 = "endereco" ; 
   $ColCampo4 = "j13_descr"; 
	 $ColLabel1 = "Atividade Principal"; 
	 $ColLabel2 = "Descrição da Classe"; 
   $ColLabel3 = "Endereço";
   $ColLabel4 = "Bairro da Inscrição";
	 $Orderby   = "order by ";
	 $iTamCamp  = 40;
	 $cabAgrupa = "Sem Agrupamento";
	break;
	
	case "a":
	 $cabCod    = "";
	 $Cab				= "q03_descr"; // Atividade Principal
	 $ColCampo1 = "q12_descr"; // Classe 
   $ColCampo2 = "j13_descr"; // Bairro
   $ColCampo3 = "endereco" ; // Endereço
	 $ColLabel1 = "Descrição da Classe"; 
   $ColLabel2 = "Bairro da Inscrição";
   $ColLabel3 = "Endereço";
	 $Orderby   = "order by q03_descr,";
	 $cabAgrupa = "Agrupado por Atividade";
	break;
	
	case "b":
	 $cabCod		= "j13_codi";	 // Código Bairro da Inscrição
	 $Cab				= "j13_descr"; // Bairro da Inscrição
	 $ColCampo1 = "q03_descr"; // Atividade Principal
	 $ColCampo2 = "q12_descr"; // Classe 
   $ColCampo3 = "endereco" ; // Endereço
	 $ColLabel1 = "Atividade Principal"; 
   $ColLabel2 = "Classe";
   $ColLabel3 = "Endereço";
	 $Orderby   = "order by j13_descr, ";
	 $cabAgrupa = "Agrupado por Bairro";
	break;
	
	case "c":
	 $cabCod    = "";
	 $Cab		    = "q12_descr"; // Classe
	 $ColCampo1 = "q03_descr"; // Atividade Principal
   $ColCampo2 = "j13_descr"; // Bairro
   $ColCampo3 = "endereco" ; // Endereço
	 $ColLabel1 = "Atividade Principal"; 
   $ColLabel2 = "Bairro da Inscrição";
   $ColLabel3 = "Endereço";
	 $Orderby   = "order by q12_descr,";
	 $cabAgrupa = "Agrupado por Classe";
	break;

}

switch($selOrdem){
	case "i":
		$Orderby .= " q02_inscr";
	  $cabOrdem = "Ordenado por Inscrição  ";
	break;
	case "c":
		$Orderby .= " q02_numcgm";
	  $cabOrdem = "Ordenado por CGM  ";
	break;
	case "n":
		$Orderby .= " z01_nome";
	  $cabOrdem = "Ordenado por Nome  ";
	break;
	case "a":
		$Orderby .= " q03_descr";
	  $cabOrdem = "Ordenado por Atividade  ";
	break;
	case "l":
		$Orderby .= " q12_descr";
	  $cabOrdem = "Ordenado por Classe  ";
	break;
	case "b":
		$Orderby .= " j13_descr";
	  $cabOrdem = "Ordenado por Bairro  ";
	break;

}

$head2 = "CADASTRO GERAL DE INSCR. ALVARA";
$head3 = $cabAgrupa;
$head4 = $cabOrdem;
$head5 = $info;

$sql  = " select q02_inscr,																								";
$sql .= "        q02_numcgm,																							";
$sql .= "        z01_nome ,																								";
$sql .= "			   z01_cgccpf,																							";
$sql .= "			 	 z01_ender||','||z01_numero as endereco,                  ";
$sql .= "     	 q03_descr,																								"; 
$sql .= "        q12_descr,																								";
$sql .= "        j13_codi,																								";
$sql .= "        j13_descr																								";
$sql .= "   from issbase																									";
$sql .= "			inner join cgm			 on z01_numcgm = q02_numcgm							";
$sql .= "			left  join issbairro on q13_inscr  = q02_inscr							";
$sql .= "     left  join bairro    on j13_codi   = q13_bairro							";
$sql .= "			left  join ativprinc on q88_inscr  = q02_inscr							";
$sql .= "			left  join tabativ	 on q07_seq		 = q88_seq								";
$sql .= "			 									  and q07_inscr  = q88_inscr							";
$sql .= "			left  join ativid		 on q03_ativ	 = q07_ativ								";
$sql .= "			left  join clasativ  on q82_ativ	 = q03_ativ								";
$sql .= "			left  join classe    on q12_classe = q82_classe							";
$sql .= " where 1=1																												";
$sql .= "	$where																													";
$sql .= "	$txt_where																											";
$sql .= " $Orderby																												";

//die($sql);
$result = pg_exec($sql);

if (pg_numrows($result) == 0){ 
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$TrocaCab = "";
$alt   = 4;
$total = 0;
$p     = 0;
$pdf->addpage("L");
for($x = 0; $x <pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
	 
		if($lImpCab){
			if($$Cab != $TrocaCab && $x != 0){
				$pdf->setfont('arial','b',8);
				$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",1,"L",0);
				$pdf->ln();
				$total = 0;
			}
		}

		if($pdf->gety() > $pdf->h - 30 || $TrocaCab != $$Cab){   
			if($pdf->gety() > $pdf->h - 30){
				$pdf->addpage("L");
		  }
			if($lImpCab){
				$pdf->ln(2);
				$pdf->setfont('arial','b',10);
				$pdf->cell(0,$alt,(isset($$cabCod)?$$cabCod."  ":"").$$Cab,0,1,"L",0);
				$pdf->ln(2);
			}
			
			$TrocaCab = $$Cab;
			$pdf->setfont('arial','b',8);
      $pdf->cell(10,$alt,"Inscr"							,1,0,"C",1);
      $pdf->cell(25,$alt,"CGC/CPF"						,1,0,"C",1);
      $pdf->cell(15,$alt,"Numcgm"							,1,0,"C",1);
      $pdf->cell(62,$alt,$RLz01_nome					,1,0,"C",1);
      $pdf->cell($iTamCamp,$alt,$ColLabel1    ,1,0,"C",1); 
      $pdf->cell($iTamCamp,$alt,$ColLabel2 	 	,1,0,"C",1); 
			if($lImpCab){
				$pdf->cell(70,$alt,$ColLabel3	        ,1,1,"C",1);
			}else{ 
				$pdf->cell(55,$alt,$ColLabel3					,1,0,"C",1);
				$pdf->cell(28,$alt,$ColLabel4					,1,1,"C",1);
      }
			$p = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(10,$alt,$q02_inscr										 ,0,0,"C",$p);
   $pdf->cell(25,$alt,$z01_cgccpf										 ,0,0,"L",$p);
   $pdf->cell(15,$alt,$q02_numcgm										 ,0,0,"C",$p);
   $pdf->cell(62,$alt,$z01_nome											 ,0,0,"L",$p);
   $pdf->cell($iTamCamp,$alt,substr($$ColCampo1,0,25),0,0,"L",$p); 
   $pdf->cell($iTamCamp,$alt,substr($$ColCampo2,0,25),0,0,"L",$p); 
	 if($lImpCab){
    $pdf->cell(70,$alt,$$ColCampo3				           ,0,1,"L",$p);
	 }else{
    $pdf->cell(55,$alt,$$ColCampo3									 ,0,0,"L",$p);
		$pdf->cell(28,$alt,$$ColCampo4									 ,0,1,"L",$p);
   }
	 if ($p==0){
     $p=1;
   }else $p=0;
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>
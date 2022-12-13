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

include("fpdf151/scpdf.php");
include("classes/db_contlot_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contlotv_classe.php");
include("classes/db_contricalc_classe.php");
include("classes/db_editalserv_classe.php");
include("classes/db_editalrua_classe.php");
include("libs/db_sql.php");
$clcontlot = new cl_contlot;
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clcontlotv = new cl_contlotv;
$cleditalserv = new cl_editalserv;
$cleditalrua = new cl_editalrua;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$result= $cleditalrua->sql_record($cleditalrua->sql_query("","d01_numero,d02_contri,j14_nome,d01_data","j14_nome","d02_codedi=$edital"));
$num =   $cleditalrua->numrows;
$lin=0;
$pri=false;
$pripag="true";
$pdf = new SCPDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
db_fieldsmemory($result,0);
$head1 = "EDITAL:$d01_numero  DATA:$d01_data";
$totpago="";
$totdevido="";
$cont=0;
$contot=0;
for($i=0;$i<$num;$i++) {
   db_fieldsmemory($result,$i);
   $result03= $clcontricalc->sql_record($clcontricalc->sql_query_file(null,"d09_contri",null,"d09_contri = $d02_contri"));
   if($clcontricalc->numrows>0){
      $existe_calculado="sim"; 
   }
   if($clcontricalc->numrows>0){
     $cabec="";
     $pri01="false";
     $propag="true";
     $result01=pg_query("
    		select distinct	j01_matric,z01_nome,lote.j34_idbql,lote.j34_area,j34_setor,j34_quadra,j34_lote,j34_zona,d05_testad
 		      from contlot
		           inner join lote on j34_idbql = d05_idbql
		           inner join iptubase on j34_idbql = j01_idbql
			   inner join  cgm on j01_numcgm = z01_numcgm
		       where d05_contri= $d02_contri order by j34_idbql
     	   	");
     $numrows01=pg_numrows($result01);
     $linha = 60;
     if($pri01=="false"){// testa quando e uma nova contribucao
  
        $pri01="true";	
        $y=$pdf->GetY();
        if($y>160 || $pripag=="true"){
  	  $pripag="false";
          $pdf->AddPage("L");
    	  $propag="false";
        } 
        $cabec="1";
        $pdf->SetFont('Arial','B',7);
        $reso= $cleditalrua->sql_record($cleditalrua->sql_query($d02_contri,"d02_codedi,j14_nome,d02_profun"));
        db_fieldsmemory($reso,0);
        $pdf->ln();
        $pdf->Cell("60",6,"CONTRIBUIÇÃO:".$d02_contri,1,0,"L",1);
        $pdf->Cell("205",6,"RUA:".$j14_nome,1,1,"L",1);
        $pdf->Cell(60,4,"PROPRIETÁRIO",1,0,"C",1);
        $pdf->Cell(16,4,"MATRICULA",1,0,"C",1);
        $pdf->Cell(10,4,"SETOR",1,0,"C",1);
        $pdf->Cell(12,4,"QUADRA",1,0,"C",1);
        $pdf->Cell(8,4,"LOTE",1,0,"C",1);
        $pdf->Cell(8,4,"ZONA",1,0,"C",1);
        $pdf->Cell(16,4,"TESTADA",1,0,"C",1);
        $pdf->Cell(12,4,"AREA",1,0,"C",1);
        $pdf->Cell(17,4,"VALOR",1,0,"C",1);
        $pdf->Cell(18,4,"VALOR",1,0,"C",1);
        $pdf->Cell(28,4,"VALOR DA ",1,0,"C",1);
        $pdf->Cell(30,4,"VALOR DEVIDO",1,0,"C",1);
        $pdf->Cell(30,4,"VALOR PAGO",1,1,"C",1);
        $pdf->Cell(60,4,"","LRB",0,"C",1);
        $pdf->Cell(16,4,"","LRB",0,"C",1);
        $pdf->Cell(10,4,"","LRB",0,"C",1);
        $pdf->Cell(12,4,"","LRB",0,"C",1);
        $pdf->Cell(8,4,"","LRB",0,"C",1);
        $pdf->Cell(8,4,"","LRB",0,"C",1);
        $pdf->Cell(16,4,"EM METROS","LRB",0,"C",1);
        $pdf->Cell(12,4,"EM M2","LRB",0,"C",1);
        $pdf->Cell(17,4,"VENAL EM R$","LRB",0,"C",1);
        $pdf->Cell(18,4,"POR M2 EM R$","LRB",0,"C",1);
        $pdf->Cell(28,4,"CONTRIBUIÇÃO EM R$","LRB",0,"C",1);
        $pdf->Cell(30,4,"","LRB",0,"C",1);
        $pdf->Cell(30,4,"","LRB",1,"C",1);
     }   
     $pri02="false";   
     for($b=0; $b<$numrows01; $b++){
      $y02=$pdf->getY();
      $Letra = 'Times';
      if($y02>180){
        $pdf->AddPage("L");
	$sql = "select nomeinst,bairro,cgc,ender,upper(munic) as munic,uf,telef,email,url,logo,db12_extenso
			from db_config 
			inner join db_uf on db12_uf=uf
			where codigo = ".db_getsession("DB_instit");
	$result05 = pg_query($sql);
	global $nomeinst;
	global $ender;
	global $munic;
	global $cgc;
	global $bairro;
	global $uf;
	global $logo;
	//echo $sql;
	db_fieldsmemory($result05,0);
	/// seta a margem esquerda que veio do relatorio
	$S = $pdf->lMargin;
	$pdf->SetLeftMargin(10);
	$posini = ($pdf->w/2)-12;
	$pdf->Image("imagens/files/".$logo,$posini,8,24);
	//$pdf->Image('imagens/files/'.$logo,2,3,30);
	$pdf->Ln(35);
	$pdf->SetFont($Letra,'',10);
	$pdf->MultiCell(0,4,$db12_extenso,0,"C",0);
	$pdf->SetFont($Letra,'B',13);
	$pdf->MultiCell(0,6,$nomeinst,0,"C",0);
	$pdf->SetFont($Letra,'B',12);
	$pdf->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
	$pdf->Ln(10);
	$pdf->SetLeftMargin($S);
        $pri02="false";
        $cabec="";
        $propag="true";
      }  
    $pdf->SetFont($Letra,'',7);
    $pdf->line(10,$pdf->h-12,290,$pdf->h-12);
    $pdf->text(10,$pdf->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
//    $pdf->text(90,$pdf->h-8,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"));
    $pdf->text(270,$pdf->h-8,'Página '.$pdf->PageNo().' de {nb}',0,1,'R');
    $pdf->SetFont($Letra,'B',12);
      if($pri02=="false" && $propag=="true" && $cabec!="1"){  
        $pri02="true";
        $pdf->SetFont('Arial','B',7);
        $reso= $cleditalrua->sql_record($cleditalrua->sql_query($d02_contri,"d02_codedi,j14_nome,d02_profun"));
        db_fieldsmemory($reso,0);
        $pdf->Cell("60",6,"CONTRIBUIÇÃO:".$d02_contri,1,0,"L",1);
        $pdf->Cell("205",6,"RUA:".$j14_nome,1,1,"L",1);
        $pdf->Cell(60,4,"PROPRIETÁRIO",1,0,"C",1);
        $pdf->Cell(16,4,"MATRICULA",1,0,"C",1);
        $pdf->Cell(10,4,"SETOR",1,0,"C",1);
        $pdf->Cell(12,4,"QUADRA",1,0,"C",1);
        $pdf->Cell(8,4,"LOTE",1,0,"C",1);
        $pdf->Cell(8,4,"ZONA",1,0,"C",1);
        $pdf->Cell(16,4,"TESTADA",1,0,"C",1);
        $pdf->Cell(12,4,"AREA",1,0,"C",1);
        $pdf->Cell(17,4,"VALOR",1,0,"C",1);
        $pdf->Cell(18,4,"VALOR",1,0,"C",1);
        $pdf->Cell(28,4,"VALOR DA ",1,0,"C",1);
        $pdf->Cell(30,4,"VALOR DEVIDO",1,0,"C",1);
        $pdf->Cell(30,4,"VALOR PAGO",1,1,"C",1);
        $pdf->Cell(60,4,"","LRB",0,"C",1);
        $pdf->Cell(16,4,"","LRB",0,"C",1);
        $pdf->Cell(10,4,"","LRB",0,"C",1);
        $pdf->Cell(12,4,"","LRB",0,"C",1);
        $pdf->Cell(8,4,"","LRB",0,"C",1);
        $pdf->Cell(8,4,"","LRB",0,"C",1);
        $pdf->Cell(16,4,"EM METROS","LRB",0,"C",1);
        $pdf->Cell(12,4,"EM M2","LRB",0,"C",1);
        $pdf->Cell(17,4,"VENAL EM R$","LRB",0,"C",1);
        $pdf->Cell(18,4,"POR M2 EM R$","LRB",0,"C",1);
        $pdf->Cell(28,4,"CONTRIBUIÇÃO EM R$","LRB",0,"C",1);
        $pdf->Cell(30,4,"","LRB",0,"C",1);
        $pdf->Cell(30,4,"","LRB",1,"C",1);
      }

      db_fieldsmemory($result01,$b);
      $result04=$clcontricalc->sql_record($clcontricalc->sql_query_file(null, "d09_contri,d09_numpre", "d09_contri = $d02_contri and d09_matric = $j01_matric"));
      if($clcontricalc->numrows>0){
	$cont++;
        $result02= $clcontrib->sql_record($clcontrib->sql_query_file($d02_contri,$j01_matric,"d07_valor,d07_venal"));
	db_fieldsmemory($result02,0);
	$m2=($d02_profun*$d05_testad); 
	
        $result07= $cleditalserv->sql_record($cleditalserv->sql_query($d02_contri,"","d04_vlrcal,d04_mult"));
        $numrows07=$cleditalserv->numrows;
	$valmetro="";
	for($u=0; $u<$numrows07; $u++){
	  db_fieldsmemory($result07,$u);
	  $valmetro+=$d04_vlrcal;
	}
        $pdf->SetFont('Times','',6);
        $pdf->Cell(60,4,substr($z01_nome,0,35),1,0,"L",0);
        $pdf->Cell(16,4,$j01_matric,1,0,"C",0);
        $pdf->Cell(10,4,$j34_setor,1,0,"C",0);
        $pdf->Cell(12,4,$j34_quadra,1,0,"C",0);
        $pdf->Cell(8,4,$j34_lote,1,0,"C",0);
        $pdf->Cell(8,4,$j34_zona,1,0,"C",0);
        $pdf->Cell(16,4,db_formatar($d05_testad,'p'),1,0,"C",0);
        $pdf->Cell(12,4,db_formatar($m2,'p'),1,0,"C",0);
        $pdf->Cell(17,4,db_formatar($d07_venal,'f'),1,0,"C",0);
        $pdf->Cell(18,4,db_formatar($valmetro,'f'),1,0,"C",0);
        $pdf->Cell(28,4,db_formatar($d07_valor,'f'),1,0,"C",0);
        
	db_fieldsmemory($result04,0);
	$result09=debitos_numpre($d09_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"1") or die($d09_numpre);
        db_fieldsmemory($result09,0);
        $result08= pg_query("select sum(k00_valor) from arrepaga where k00_numpre = $d09_numpre");
        db_fieldsmemory($result08,0);

        $pdf->Cell(30,4,db_formatar($total,'f'),1,0,"C",0);
        $pdf->Cell(30,4,db_formatar($sum,'f'),1,0,"C",0);
        $pdf->ln();
	if($numrows01==($b+1)){
          $pdf->SetFont('Arial','B',6);
          $pdf->Cell(60,4,"REGISTROS DA CONTRIBUIÇÃO:".($cont),0,0,"L",0);
	  $contot+=$cont;
	  $cont=0;
	}
	$totpago+=$sum;
	$totdevido+=$total;
      }
     }   
   }  
}
if(empty($existe_calculado)){
  echo "
    <script>
     window.close();
     opener.alert('Não existe contribuição calculada para este edital.');
    </script>
  ";
}
$pdf->SetFont('Arial','B',7);
$pdf->Cell(16,4,"",0,0,"C",0);
$pdf->Cell(10,4,"",0,0,"C",0);
$pdf->Cell(12,4,"",0,0,"C",0);
$pdf->Cell(8,4,"",0,0,"C",0);
$pdf->Cell(8,4,"",0,0,"C",0);
$pdf->Cell(16,4,"",0,0,"C",0);
$pdf->Cell(12,4,"",0,0,"C",0);
$pdf->Cell(17,4,"",0,0,"C",0);
$pdf->Cell(18,4,"",0,0,"C",0);
$pdf->Cell(28,4,"TOTAL",1,0,"C",1);
$pdf->Cell(30,4,db_formatar($totdevido,'f'),1,0,"C",1);
$pdf->Cell(30,4,db_formatar($sum,'f'),1,1,"C",1);
$pdf->Ln();
$pdf->Cell(60,6,"Total de registros do edital:$contot",0,1,"L",0);
$pdf->Ln(5);
$pdf->Output();

?>
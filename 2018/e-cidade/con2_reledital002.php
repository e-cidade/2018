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

$clcontlot = new cl_contlot;
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clcontlotv = new cl_contlotv;
$cleditalserv = new cl_editalserv;
$cleditalrua = new cl_editalrua;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//db_postmemory($HTTP_GET_VARS,2);exit;
db_postmemory($HTTP_GET_VARS);

$result= $cleditalrua->sql_record($cleditalrua->sql_query("","d01_numero,d02_contri,j14_nome,d01_data,d01_perc,d02_valorizacao","j14_nome","d02_codedi=$edital"));

$num =   $cleditalrua->numrows;

if( $num < 1 ){
  echo "
  <script>
  window.close();
  opener.alert('Não foram encontradas contribuições para este edital.');
  </script>
  ";
}
$cont=0;
$contot=0;


$valorcont=0;
$totvalorcont=0;

$contriz = '';
$virgz = '';

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
$head1 = "EDITAL:$d01_numero "; 
//" - DATA:".db_formatar($d01_data,"d");
for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i);

  $clcontrib->sql_record($clcontrib->sql_query_file($d02_contri,"","d07_valor,d07_venal"));

  if($clcontrib->numrows>0){
    
    $cabec="";
    $pri01="false";
    $propag="true";
    $sql01="select distinct 
    j01_matric,
    j40_refant,
    z01_nome,
    lote.j34_idbql,
    lote.j34_area,
    j34_setor,
    j34_quadra,
    j34_lote,
    j34_zona,
    d41_testada + d41_eixo as d05_testad
    from contlot
    inner join lote on j34_idbql = d05_idbql
    inner join iptubase on j34_idbql = j01_idbql
    left  join iptuant on j40_matric = j01_matric
    inner join cgm on j01_numcgm = z01_numcgm
    inner join editalruaproj on d11_contri = d05_contri
    inner join projmelhoriasmatric on d41_codigo = d11_codproj and d41_matric = j01_matric 
    where d05_contri= $d02_contri 
    order by j40_refant";
    $result01=pg_query($sql01) or die($sql01);
    
    $sqlsoma = "	select sum(d41_testada + d41_eixo) as total_testada 
    from contlot 
    inner join lote on j34_idbql = d05_idbql
    inner join iptubase on j34_idbql = j01_idbql
    inner join editalruaproj on d11_contri = d05_contri
    inner join projmelhoriasmatric on d41_codigo = d11_codproj and d41_matric = j01_matric 
    where d05_contri = $d02_contri";
    $resultsoma = pg_exec($sqlsoma) or die($sqlsoma);
    if (pg_numrows($resultsoma) == 0) {
      $total_testada = 0;
    } else {
      db_fieldsmemory($resultsoma, 0);
    }
    
    $numrows01=pg_numrows($result01);
    $linha = 60;
    if($pri01=="false"){// testa quando e uma nova contribucao
      
      $pri01="true";	
      $y=$pdf->GetY();
      if($y>160 || $pripag=="true"){
        $pripag="false";
        
        $pdf->AddPage("L");
        
        $sql = "select nomeinst,bairro,cgc,ender,upper(munic) as munic,uf,telef,email,url,logo, db12_extenso
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
        //echo $sql;
        db_fieldsmemory($result05,0);
        /// seta a margem esquerda que veio do relatorio
        $S = $pdf->lMargin;
        $pdf->SetLeftMargin(10);
        $Letra = 'Times';
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
        $propag="false";
        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(280,10,"ANEXO I",1,1,"C",1);
        $pdf->ln(2);
      }else{
        $pdf->ln();
      } 
      
      $pdf->SetFont($Letra,'',7);
      $pdf->line(10,$pdf->h-12,290,$pdf->h-12);
      $pdf->text(10,$pdf->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
      //    $pdf->text(90,$pdf->h-8,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"));
    $pdf->text(270,$pdf->h-8,'Página '.$pdf->PageNo().' de {nb}',0,1,'R');
      $pdf->SetFont($Letra,'B',12);
      /*    $pdf->SetFont('Arial','',5);
      $pdf->text(10,$this->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
      $pdf->SetFont('Arial','I',8);
      $pdf->SetY(-10);
      $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
      $nome = substr($nome,strrpos($nome,"/")+1);
      $pdf->Cell(0,10,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"),"T",0,'C');
    $pdf->Cell(0,10,'Página '.$pdf->PageNo().' de {nb}',0,1,'R');
      */	
      
      $cabec="1";
      $reso= $cleditalrua->sql_record($cleditalrua->sql_query($d02_contri,"d02_codedi,j14_nome,d02_profun,d02_valorizacao"));
      db_fieldsmemory($reso,0);
      
      $pdf->SetFont('Arial','B',7);
      
      $pdf->Cell("60",6,"CONTRIBUIÇÃO:".$d02_contri,1,0,"L",1);
      $pdf->Cell("220",6,"RUA:".$j14_nome,1,1,"L",1);
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
      $pdf->Cell(20,4,"CUSTO INDI-",1,0,"C",1);
      $pdf->Cell(25,4,"VALOR DO IMÓVEL",1,0,"C",1);
      $pdf->Cell(25,4,"VALORIZAÇÃO",1,0,"C",1);
      $pdf->Cell(33,4,"VALOR",1,1,"C",1);
      
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
      $pdf->Cell(20,4,"VIDUAL EM R$","LRB",0,"C",1);
      $pdf->Cell(25,4,"DEPOIS DA OBRA","LRB",0,"C",1);
      $pdf->Cell(25,4,"DO IMÓVEL EM R$","LRB",0,"C",1);
      $pdf->Cell(33,4,"DA CONTRIBUIÇÃO","LRB",1,"C",1);
    }   
    $pri02="false";   
    for($b=0; $b<$numrows01; $b++){
      $y02=$pdf->getY();
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
        //echo $sql;
        db_fieldsmemory($result05,0);
        /// seta a margem esquerda que veio do relatorio
        $S = $pdf->lMargin;
        $pdf->SetLeftMargin(10);
        $Letra = 'Times';
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
      
      if($pri02=="false" && $propag=="true" && $cabec!="1"){
        $pri02="true";
        $reso= $cleditalrua->sql_record($cleditalrua->sql_query($d02_contri,"d02_codedi,j14_nome,d02_profun,d02_valorizacao"));
        db_fieldsmemory($reso,0);
        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(280,10,"ANEXO I",1,1,"C",1);
        $pdf->ln(2);
        $pdf->SetFont('Arial','B',7);
        
        $pdf->Cell("60",6,"CONTRIBUIÇÃO:".$d02_contri,1,0,"L",1);
        $pdf->Cell("220",6,"RUA:".$j14_nome,1,1,"L",1);
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
        $pdf->Cell(20,4,"CUSTO INDI-",1,0,"C",1);
        $pdf->Cell(25,4,"VALOR DO IMÓVEL",1,0,"C",1);
        $pdf->Cell(25,4,"VALORIZAÇÃO",1,0,"C",1);
        $pdf->Cell(33,4,"VALOR",1,1,"C",1);
        
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
        $pdf->Cell(20,4,"VIDUAL EM R$","LRB",0,"C",1);
        $pdf->Cell(25,4,"DEPOIS DA OBRA","LRB",0,"C",1);
        $pdf->Cell(25,4,"DO IMÓVEL EM R$","LRB",0,"C",1);
        $pdf->Cell(33,4,"DA CONTRIBUIÇÃO","LRB",1,"C",1);
        
      }
      
      db_fieldsmemory($result01,$b);
      $result02= $clcontrib->sql_record($clcontrib->sql_query_file($d02_contri,$j01_matric,"d07_valor,d07_venal"));
      if($clcontrib->numrows>0){
        $cont++;
        db_fieldsmemory($result02,0);
        $m2=($d02_profun*$d05_testad); 
        
        $result07= $cleditalserv->sql_record($cleditalserv->sql_query($d02_contri,"","d04_quant,d04_vlrcal,d04_vlrval,d04_mult"));
        $numrows07=$cleditalserv->numrows;
        $valmetro="";
        $valmetroval="";
        for($u=0; $u<$numrows07; $u++){
          db_fieldsmemory($result07,$u);
          $resgate = (($d02_profun * 2) * $d04_quant * $d04_vlrcal) * (100 - $d01_perc) / 100;
          if (1==2) {
            if ($d04_vlrcal == 0) {
              //	    $d04_vlrcal = $d04_vlrval;
              $valmetro+=$d04_vlrval;
              $valmetroval+=$d04_vlrval * $d04_mult  ;
            } else {
              $valmetro+=$d04_vlrcal;
              $valmetroval+=($d04_vlrcal == $d04_vlrval?$d04_vlrcal:$d04_vlrval  * $d04_mult);
            }
          } else {
            if ($d04_forma == 1) {
              $valmetro+=$d04_vlrcal;
              $valmetroval+=$d04_vlrcal * $d04_mult;
            } elseif ($d04_forma == 2) {
              $valmetro+=$d04_vlrval;
              $valmetroval+=$d04_vlrval * $d04_mult;
            } elseif ($d04_forma == 3) {
              $valmetro+=$d04_vlrcal;
              $valmetroval+=$d04_vlrcal * $d04_mult;
            }
          }
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
        $pdf->Cell(18,4,$valmetro - ($valmetro * $d01_perc / 100),1,0,"C",0);
        
        $valorizacao = ($d07_valor+$d07_venal);
        //$valorizacao += $valorizacao * $d02_valorizacao / 100;
        
        if (1==2) {
          if (isset($d04_vlrcal)&&isset($d04_vlrval)&&$d04_vlrcal == $d04_vlrval) {
            $valorizacaoval = $valorizacao;
          } else {
            $valmetroval = ($valmetroval - ($valmetroval * $d01_perc / 100));
            $valorizacaoval = round(($valmetroval * $m2) + $d07_venal,2);
          }
        } else {
          if ($d04_forma == 1) {
            $valorizacaoval = $valorizacao;
          } elseif ($d04_forma == 2) {
            $valmetroval = ($valmetroval - ($valmetroval * $d01_perc / 100));
            $valorizacaoval = round(($valmetroval * $m2) + $d07_venal,2);
          } elseif ($d04_forma == 3) {
            $valorizacaoval = $d07_venal + ($d07_venal * $d02_valorizacao / 100);
          }
        }
        
        //        $pdf->Cell(20,4,db_formatar($d07_valor,'f'),1,0,"C",0);
        
        if ($d04_forma == 1) {
          $custoindividual = round(($valmetro - ($valmetro * $d01_perc / 100)) * $m2,2);
        } elseif ($d04_forma == 2) {
          $custoindividual = round(($valmetro - ($valmetro * $d01_perc / 100)) * $m2,2);
        } elseif ($d04_forma == 3) {
          $custoindividual = ($m2) / ($total_testada * $d02_profun) * ($d04_quant * $d02_profun * 2);
          //					  die("xxx: $total_testada - m2: $m2 - prof: $d02_profun - quant: $d04_quant");
          //						$custoindividual = round(($valmetro - ($valmetro * $d01_perc / 100)) * $m2,2);
        }
        $pdf->Cell(20,4,db_formatar($custoindividual,'f'),1,0,"C",0);
        
        if (1==2) {
          if (isset($d04_vlrcal)&&isset($d04_vlrval)&&$d04_vlrcal == $d04_vlrval) {
            $pdf->Cell(25,4,db_formatar($valorizacao,'f'),1,0,"C",0);
          } else {
            $pdf->Cell(25,4,db_formatar($valorizacaoval,'f'),1,0,"C",0);
          }
        } else {
          if ($d04_forma == 1) {
            $pdf->Cell(25,4,db_formatar($valorizacaoval,'f'),1,0,"C",0);
          } elseif ($d04_forma == 2) {
            $pdf->Cell(25,4,db_formatar($valorizacao,'f'),1,0,"C",0);
          } elseif ($d04_forma == 3) {
            $pdf->Cell(25,4,db_formatar($valorizacaoval,'f'),1,0,"C",0);
          }
        }
        
        $pdf->Cell(25,4,db_formatar($valorizacaoval-$d07_venal,'f'),1,0,"C",0);
        $valorizacaodoimovel = $valorizacaoval-$d07_venal;
        if ($j01_matric == 700) {
          //	  die("valorizacaoval: $valorizacaoval - d07_venal: $d07_venal - d07_valor: $d07_valor");
          //	  die("custoindividual: $custoindividual - valorizacaodoimovel: $valorizacaodoimovel");
        }
        //$valori = db_formatar((($valorizacaoval - $d07_venal) > $d07_valor?$d07_valor:$valorizacaoval - $d07_venal),'p');
        if ($d04_forma == 1) {
          $valori = db_formatar(($custoindividual <= $valorizacaodoimovel?$custoindividual:$valorizacaodoimovel),'p');
        } elseif ($d04_forma == 2) {
          $valori = db_formatar(($custoindividual <= $valorizacaodoimovel?$custoindividual:$valorizacaodoimovel),'p');
        } elseif ($d04_forma == 3) {
          $valori = $custoindividual * $d04_vlrcal * ((100 - $d01_perc) / 100);
          if ($valori > $valorizacaoval-$d07_venal) {
            $valori = $valorizacaoval-$d07_venal;
          }
        }
        $pdf->Cell(33,4,"         ".db_formatar($valori,"f"),1,0,"C",0);
        $pdf->ln();
        
        $valorcont += $valori;
        //echo  " <br>valorcont $valorcont      -----valor".$valori."<br>";
        
        if($numrows01==($b+1)){
          // echo "<br><br><br><br>".$valorcont."<br>";
          $pdf->SetFont('Arial','B',6);
          $pdf->Cell(60,4,"REGISTROS DA CONTRIBUIÇÃO: ".($cont),1,0,"L",0);
          $pdf->Cell(162,4,"",0,0,"L",0);
          $pdf->Cell(25,4,"SUBTOTAL:",1,0,"L",0);
          $pdf->Cell(33,4,"R$: " . db_formatar($valorcont,'f'),1,0,"C",0);
          $contot+=$cont;
          $totvalorcont+=$valorcont;
          $cont=0;
          $valorcont=0;
        }
      }	
    }   
    
  }else{
    $contriz .= $virgz.$d02_contri;
    $virgz = ', ';
    continue;
    /*
    die($clcontrib->sql_query_file($d02_contri,$j01_matric,"d07_valor,d07_venal"));
    echo "
    <script>
    window.close();
    opener.alert('Não existe matrículas selecionadas para esta contribuição.');
    </script>
    ";
    */
  }  
  
}

$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,6,"Total de registros do edital: ",0,0,"L",0);
$pdf->Cell(10,6,"",0,0,"L",0);
$pdf->Cell(20,6,$contot,0,0,"D",0);

$pdf->Cell(20,6,"",0,0,"L",0);
$pdf->Cell(60,6,"Total de valores do edital: ",0,0,"L",0);
$pdf->Cell(10,6,"",0,0,"L",0);
$pdf->Cell(20,6,"R$: " . db_formatar($totvalorcont,'f'),0,1,"D",0);
if($contriz!=''){
  $pdf->Cell(10,6,"Contribuições com matriculas não selecionadas: $contriz ",0,0,"L",0);
}  

$pdf->Ln(5);
$pdf->Output();

?>
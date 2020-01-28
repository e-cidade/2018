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

include("libs/db_sql.php");
require("fpdf151/pdf.php");
include("classes/db_tabativ_classe.php");
include("classes/db_issporte_classe.php");
include("classes/db_classe_classe.php");

$cl_tabativ = new cl_tabativ;
$cl_porte = new cl_issporte;
$cl_classe = new cl_classe;

$Dadosporte = str_replace("XX",",",$Dadosporte);
$Dadosclasse = str_replace("XX",",",$Dadosclasse);
$Dadosativ = str_replace("XX",",",$Dadosativ);

db_postmemory($HTTP_SERVER_VARS);

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "SECRETARIA DA FAZENDA";
$head4 = "Relatório de Inscrições Por Atividade";
$head6 = 'Relatório '.$opcao;
$linha = 60;
$pdf->setfillcolor(235);
$TPagina = 40;
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total1 = 0;
$total2 = 0;
$p = "0";
 $sql = "select
           q41_codclasse
          ,q12_descr
	  ,q45_codporte
	  ,q40_descr
          ,q02_inscr
	  ,q02_numcgm
	  ,q02_fanta
          ,q03_ativ
	  ,q03_descr
	  ,z01_ender
        from issbase
          inner join  cgm           on  q02_numcgm    = z01_numcgm
          inner join  tabativ       on  q02_inscr     = q07_inscr
          inner join  issbaseporte  on  q02_inscr     = q45_inscr
          inner join  issporte      on  q40_codporte  = q45_codporte
          inner join  issportetipo  on  q41_codporte  = q45_codporte
	  inner join  classe        on  q41_codclasse = q12_classe
	  inner join  ativid        on  q07_ativ      = q03_ativ
	where q07_ativ      in ($Dadosativ)
	  and q45_codporte  in ($Dadosporte)
	  and q41_codclasse in ($Dadosclasse)
	order by
          q41_codclasse,
          q45_codporte
	";
   $result = pg_exec($sql);
   $numrows = pg_numrows($result);
   if($numrows == 0){
    echo " <div align=\"center\">Nenhum registro cadastrado <br> <input type=\"button\" value=\"Fechar\" onclick=\"window.close()\"></div>";
    exit;
   }
   $cod_classe = "";
   $cod_classe_porte = "";
   
   for($x=0; $x< $numrows; $x++) {
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Inscrição",1,0,"C",1);
      $pdf->cell(15,$alt,"Cgm",1,0,"C",1);
      $pdf->cell(80,$alt,"Razão",1,0,"L",1);
      $pdf->cell(50,$alt,"Endereço",1,0,"L",1);
      $pdf->cell(90,$alt,"Atividade",1,1,"L",1);
      
      $troca = 0;
     }
     
     if ($cod_classe_porte != $q41_codclasse . $q45_codporte) {
       // imprime cabecalho do porte;
       if($cod_classe_porte <> ""){
        $pdf->cell(250,$alt," Total de inscrições do porte ".$total2,"TB",1,"L",0);
        $total2=0;
       } 
     }     
     
    if ($cod_classe != $q41_codclasse) {
       // imprime cabecalho da classe;
       if($cod_classe <> ""){
       $pdf->cell(250,$alt," Total de inscrições da classe ".$total1,"TB",1,"L",0);
       $total1=0;
       }       
       $pdf->cell(250,$alt,$q41_codclasse." - ".$q12_descr,0,1,"L",0);
       $cod_classe = $q41_codclasse;
     }

     if ($cod_classe_porte != $q41_codclasse . $q45_codporte) {     
       $pdf->cell(10,$alt,"",0,0,"L",0);
       $pdf->cell(250,$alt,$q45_codporte." - ".$q40_descr,0,1,"L",0);
       $cod_classe_porte = $q41_codclasse . $q45_codporte;
     }     
     
     // imprime dados da inscricao;
     $pdf->cell(15,$alt,$q02_inscr,0,0,"C",$p);
     $pdf->cell(15,$alt,$q02_numcgm,0,0,"C",$p);
     $pdf->cell(80,$alt,$q02_fanta,0,0,"L",$p);
     $pdf->cell(50,$alt,$z01_ender,0,0,"L",$p);
     $pdf->cell(90,$alt,$q03_ativ." - ".$q03_descr,0,1,"L",$p);
     $total2 += 1;
     $total1 += 1;
     if($p == 0){
      $p = 1;
     }
     else{
      $p = 0; 
     }
     
   }
 $pdf->cell(250,$alt," Total de inscrições do porte ".$total2,"TB",1,"L",0);   
 $pdf->cell(250,$alt," Total de inscrições da classe ".$total1,"TB",1,"L",0);  
 
$pdf->Output();

?>
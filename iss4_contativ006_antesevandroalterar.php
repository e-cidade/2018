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

//busca as classes
$result_classe = $cl_classe->sql_record($cl_classe->sql_query("","q12_classe,q12_descr","","q12_classe IN ($Dadosclasse)"));
//busca os portes
$result_porte = $cl_porte->sql_record($cl_porte->sql_query("","q40_codporte,q40_descr","","q40_codporte IN ($Dadosporte)"));
//busca os dados do relatório com o porte e a classe

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
$total = 0;
 for($c=0; $c < $cl_classe->numrows; $c++){
  db_fieldsmemory($result_classe,$c);   
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Inscrição",1,0,"L",1);
      $pdf->cell(20,$alt,"Cgm",1,0,"L",1);
      $pdf->cell(60,$alt,"Razão",1,0,"L",1);
      $pdf->cell(55,$alt,"Endereço",1,0,"L",1);
      $pdf->cell(40,$alt,"Atividade",1,1,"L",1);
      $troca = 0;
   }
  $pdf->cell(195,$alt,$q12_classe." - ".$q12_descr,0,1,"L",0);
  for($p=0; $p < $cl_porte->numrows; $p++){
   db_fieldsmemory($result_porte,$p);   
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Inscrição",1,0,"L",1);
      $pdf->cell(20,$alt,"Cgm",1,0,"L",1);
      $pdf->cell(60,$alt,"Razão",1,0,"L",1);
      $pdf->cell(55,$alt,"Endereço",1,0,"L",1);
      $pdf->cell(40,$alt,"Atividade",1,1,"L",1);
      
      $troca = 0;
    }
    $pdf->cell(190,$alt,"   ".$q40_codporte." - ".$q40_descr,"B",1,"L",0);
   //$sql = "select *
      //from issbaseporte where q45_codporte = $q40_codporte";
      /*a
           inner join empresa b on a.q45_inscr = b.q02_inscr
	   inner join tabativ d on a.q45_inscr = d.q07_inscr
	   inner join issporte c on a.q45_codporte = c.q40_codporte
        and d.q07_ativ in ($Dadosativ)
	and a.q45_codporte = $q40_codporte*/
$sql = "	
	select
        q41_codclasse,
        q45_codporte,
        q02_inscr
        from issbase
        inner join cgm                  on q02_numcgm = z01_numcgm
        inner join tabativ              on q07_inscr = q02_inscr
        inner join issbaseporte         on q45_inscr = q02_inscr
        inner join issporte             on q40_codporte = q45_codporte
        inner join issportetipo         on q41_codporte = q45_codporte
        order by
        q41_codclasse,
        q45_codporte
	";
	
   $result = pg_exec($sql);
   $numrows = pg_numrows($result);
    if($numrows == 0){
     $pdf->cell(195,$alt,"                  Nenhum registro para o porte",0,1,"L",0);
     continue;
    }
   for($x=0; $x< $numrows; $x++)
    {
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Inscrição",1,0,"L",1);
      $pdf->cell(20,$alt,"Cgm",1,0,"L",1);
      $pdf->cell(60,$alt,"Razão",1,0,"L",1);
      $pdf->cell(55,$alt,"Endereço",1,0,"L",1);
      $pdf->cell(40,$alt,"Atividade",1,1,"L",1);
      
      $troca = 0;
    }
     //dados
     $pdf->cell(15,$alt,$q45_inscr,0,1,"C",0);
     
     
    }
  }
}  

$pdf->Output();

?>
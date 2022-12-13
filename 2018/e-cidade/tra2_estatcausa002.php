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
  //$rs = pg_exec($sql);
 $pdf = new pdf();
 $head3 = 'Resumo dos acidentes Em Sapiranga';
 $head4 = 'Acidentos por Causa';
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->setx(10);
 $pdf->setfillcolor(204);
 $pdf->setfont("Arial","B",7);
 $pdf->Cell(75,5,"Causa de Acidentes",1,1,"C",1);
 $sql1 = "select tr02_id,
                 tr02_descr,
                 tr02_sigla
          from   causas
          order by tr02_id";
 $rs1 = pg_exec($sql1);
 $num_rows = pg_num_rows($rs1) + 2;
 $pdf->setfont("Arial","",7);
 while ($ln1 = pg_fetch_array($rs1)){
    $pdf->setx(10);
    $pdf->cell(50,5,$ln1["tr02_descr"],1,0,"L");
    $pdf->cell(25,5,$ln1["tr02_sigla"],1,1,"C");
 }
 $x = 0;
 $pdf->Cell(75,5,"Total",1,1,"C");
  for ($i = 1; $i <= 12 ;$i++){
      switch ($i){
         case 1  : $mes = "Jan";
         break;
         case 2  : $mes = "Fev";
         break;
         case 3  : $mes = "Mar";
         break;
         case 4  : $mes = "Abril";
         break;
         case 5  : $mes = "Mai";
         break;
         case 6  : $mes = "Jun";
         break;
         case 7  : $mes = "Jul";
         break;
         case 8  : $mes = "Ago";
         break;
         case 9  : $mes = "Set";
         break;
         case 10 : $mes = "Out";
         break;
         case 11 : $mes = "Nov";
         break;
         case 12 : $mes = "Dez";
         break;


      }
      $pdf->setxy(85+$x,35);
      $pdf->setfont("Arial","B",7);
      $pdf->cell(10,5,$mes,1,1,"C",1);
      $sum = 0;
      $pdf->setfont("Arial","",7);
     for ($k = 1; $k < $num_rows ;$k++){
         $pdf->setx(85+$x);
         $sql2 = "select tr07_idcausa,count(*) as quantidade
              from   acidentes
              where  extract(month from tr07_data) = $i
              and    tr07_idcausa                 = $k
              and    extract(year from tr07_data)  = $ano
              group by tr07_idcausa";
         // echo $sql2;exit;
          $rs2      = pg_exec($sql2);
          $numrows2 = pg_num_rows($rs2);
          $ln       = pg_fetch_array($rs2);
          $sum      +=  $ln["quantidade"];
          if ($numrows2 > 0){
              $pdf->cell(10,5,$ln["quantidade"],1,1,"C");
          }else if ($k == ($num_rows -1)){
             $pdf->cell(10,5,$sum,1,1,"C");
          }else{
             $pdf->cell(10,5,"",1,1,"C");
          }
       }
  $x +=10;

  }

$pdf->output();
?>
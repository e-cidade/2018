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
 $head4 = 'Acidentos por Tipo';
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $x = 0;
 $pdf->setx(10);
 $pdf->setfillcolor(204);
 $pdf->Cell(75,5,"Tipos de Acidentes",1,1,"C",1);
 $sql1 = "select tr01_id,
                 tr01_descr,
                 tr01_sigla
          from   tipo_acidentes
          order by tr01_id";
 $rs1 = pg_exec($sql1);
 $num_rows = pg_num_rows($rs1) + 2;
 while ($ln1 = pg_fetch_array($rs1)){
    $pdf->setx(10);
    $pdf->cell(50,5,$ln1["tr01_descr"],1,0,"L");
    $pdf->cell(25,5,$ln1["tr01_sigla"],1,1,"C");
 }
 $pdf->Cell(75,5,"Total",1,1,"C");
  for ($i = 1; $i <= 12 ;$i++){
      $pdf->setxy(85+$x,35);
      $pdf->cell(10,5,substr(db_mes($i),0,3),1,1,"C",1);
      $sum = 0;
     for ($k = 1; $k < $num_rows ;$k++){
         $pdf->setx(85+$x);
         $sql2 = "select tr07_tipoacid,count(*) as quantidade
              from   acidentes
              where  extract(month from tr07_data) = $i
              and    tr07_tipoacid                 = $k
              and    extract(year from tr07_data)  = $ano
              group by tr07_tipoacid";
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
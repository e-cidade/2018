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
 $head4 = 'Acidentos por Intervalo de Hora';
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->setx(10);
 $pdf->setfillcolor(204);
 $pdf->cell(30,5,"Intervalo",1,1,"C",1);
 $pdf->cell(30,5,"00:00 - 01:00",1,1,"C");
 $pdf->cell(30,5,"01:00 - 02:00",1,1,"C");
 $pdf->cell(30,5,"02:00 - 03:00",1,1,"C");
 $pdf->cell(30,5,"03:00 - 04:00",1,1,"C");
 $pdf->cell(30,5,"04:00 - 05:00",1,1,"C");
 $pdf->cell(30,5,"05:00 - 06:00",1,1,"C");
 $pdf->cell(30,5,"06:00 - 07:00",1,1,"C");
 $pdf->cell(30,5,"07:00 - 08:00",1,1,"C");
 $pdf->cell(30,5,"08:00 - 09:00",1,1,"C");
 $pdf->cell(30,5,"09:00 - 10:00",1,1,"C");
 $pdf->cell(30,5,"10:00 - 11:00",1,1,"C");
 $pdf->cell(30,5,"11:00 - 12:00",1,1,"C");
 $pdf->cell(30,5,"12:00 - 13:00",1,1,"C");
 $pdf->cell(30,5,"13:00 - 14:00",1,1,"C");
 $pdf->cell(30,5,"14:00 - 15:00",1,1,"C");
 $pdf->cell(30,5,"15:00 - 16:00",1,1,"C");
 $pdf->cell(30,5,"16:00 - 17:00",1,1,"C");
 $pdf->cell(30,5,"17:00 - 18:00",1,1,"C");
 $pdf->cell(30,5,"18:00 - 19:00",1,1,"C");
 $pdf->cell(30,5,"19:00 - 20:00",1,1,"C");
 $pdf->cell(30,5,"20:00 - 21:00",1,1,"C");
 $pdf->cell(30,5,"21:00 - 22:00",1,1,"C");
 $pdf->cell(30,5,"22:00 - 23:00",1,1,"C");
 $pdf->cell(30,5,"23:00 - 24:00",1,1,"C");
 for ($i = 1; $i <= 12 ;$i++){
      $pdf->setxy(40+$x,35);
      $pdf->cell(10,5,$i,1,1,"C",1);
      $resta = 24;
      $sum   = 0;
      for ($k = 0; $k < $resta ;$k++){
          if ($k == 23){
              $m = ($k).":01:00";
              $M = "23:59:59";
          }else{
              $m = "$k:01:00";
              $M = ($k+1).":00:00";
          }
          $pdf->setx(40+$x);
          if ($k != 24){
          $sql2 = "select x.horas,count(*) as total from
                    (select (case when tr07_hora between '00:00:00' and '01:00:00'  then '00:00 - 01:00'
                                  when tr07_hora between '01:00:00' and '02:00:00'  then '01:00 - 02:00'
                                  when tr07_hora between '02:00:00' and '03:00:00'  then '02:00 - 03:00'
                                  when tr07_hora between '03:00:00' and '04:00:00'  then '03:00 - 04:00'
                                  when tr07_hora between '04:00:00' and '05:00:00'  then '04:00 - 05:00'
                                  when tr07_hora between '05:00:00' and '06:00:00'  then '05:00 - 06:00'
                                  when tr07_hora between '06:00:00' and '07:00:00'  then '06:00 - 07:00'
                                  when tr07_hora between '07:00:00' and '08:00:00'  then '07:00 - 08:00'
                                  when tr07_hora between '08:00:00' and '09:00:00'  then '08:00 - 09:00'
                                  when tr07_hora between '09:00:00' and '10:00:00'  then '09:00 - 10:00'
                                  when tr07_hora between '10:00:00' and '11:00:00'  then '10:00 - 11:00'
                                  when tr07_hora between '11:00:00' and '12:00:00'  then '11:00 - 12:00'
                                  when tr07_hora between '12:00:00' and '13:00:00'  then '12:00 - 13:00'
                                  when tr07_hora between '13:00:00' and '14:00:00'  then '13:00 - 14:00'
                                  when tr07_hora between '14:00:00' and '15:00:00'  then '14:00 - 15:00'
                                  when tr07_hora between '15:00:00' and '16:00:00'  then '15:00 - 16:00'
                                  when tr07_hora between '16:00:00' and '17:00:00'  then '16:00 - 17:00'
                                  when tr07_hora between '17:00:00' and '18:00:00'  then '17:00 - 18:00'
                                  when tr07_hora between '18:00:00' and '19:00:00'  then '18:00 - 19:00'
                                  when tr07_hora between '19:00:00' and '20:00:00'  then '19:00 - 20:00'
                                  when tr07_hora between '20:00:00' and '21:00:00'  then '20:00 - 21:00'
                                  when tr07_hora between '21:00:00' and '22:00:00'  then '21:00 - 22:00'
                                  when tr07_hora between '22:00:00' and '23:00:00'  then '22:00 - 23:00'
                                  when tr07_hora between '23:00:00' and '23:59:59'  then '23:00 - 24:00'
                                  end) as horas
                       from   acidentes where  extract(month from tr07_data) = $i
                       and    tr07_hora between '$m' and '$M'
                       and    extract(year from tr07_data) = $ano) as x
                group by horas";

          $rs2      = pg_exec($sql2);
            $numrows2 = pg_num_rows($rs2);
           // if (!$rs2 or ($k == 14 and $i == 8)){
          //   echo $sql2;exit;
          // }
          }
          $ln       = pg_fetch_array($rs2);
          $sum +=  $ln["quantidade"];
          if ($numrows2 > 0){
              $pdf->cell(10,5,$ln["total"],1,1,"C");
          }else{
             $pdf->cell(10,5,"",1,1,"C");

          }
       }
       $x += 10;
 }
 $pdf->output();
 ?>
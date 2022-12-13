<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
// $con = pg_connect("host=192.168.0.3 dbname=sapiranga user=postgres");
 $pdf = new pdf("L","mm","A4");
 $head3 = 'Resumo dos acidentes Em Sapiranga';
 $head4 = 'Acidentos por Endereço';
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->setxy(10,35);
 $pdf->setfillcolor(204);
 $pdf->setfont("Arial","B",7);
 $pdf->Cell(85,5,"Enderecos",1,1,"C",1);
 $x = 0;
 $sql1 = "SELECT rua
from  (select (trim(r1.j14_nome)::varchar|| 
      (case when tr07_esquina = 1 then ' x '||r2.j14_nome
            else ', '||tr07_local2::varchar(40) end)) as rua 
       FROM acidentes inner join ruas r1 on tr07_local1 = r1.j14_codigo 
            left outer join ruas r2 on tr07_local2 = r2.j14_codigo) as x group by rua order by rua;";
 $rs1 = pg_exec($sql1);
 $num_rows = pg_num_rows($rs1) + 2;

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
      
      
      $pdf->setxy(90+$x,35);
      $pdf->setfont("Arial","B",7);
      $pdf->cell(10,5,$mes,1,1,"C",1);
      $sum = 0;
      $x +=10;
   } 
 while ($ln1 = pg_fetch_array($rs1)){
    $pdf->setfont("Arial","",6); 
    $pdf->setx(10);
    $pdf->cell(80,5,$ln1["rua"],1,0,"L");
    for ($i = 1; $i <= 12 ;$i++){
       
        $sql2 = "SELECT rua,count(rua) as total
                 FROM  (select (trim(r1.j14_nome)::varchar|| 
                          /*1;3A*/
                          (case when tr07_esquina = 1 then ' x '||r2.j14_nome
                                 else ', '||tr07_local2::varchar(40) end)) as rua 
                    FROM acidentes inner join ruas r1 on tr07_local1 = r1.j14_codigo 
                         left outer join ruas r2 on tr07_local2 = r2.j14_codigo
                    where extract(month from tr07_data) = $i
		    and   extract(year from tr07_data) = $ano) as x 
              where rua = '".$ln1["rua"]."'
              group by rua;";  
       $rs2 = pg_query($sql2);
       $pdf->setfont("Arial","",7);
       if (pg_num_rows($rs2) > 0){
          $pdf->cell(10,5,pg_result($rs2,0,"total"),1,0,"C");     
       }else{
          $pdf->cell(10,5,"",1,0,"C");  
       }
       $x +=10;
    }
    $pdf->ln(); 
                             
    
 }
 
$pdf->output();
?>
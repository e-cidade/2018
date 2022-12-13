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
$pdf = new pdf();
$head3 = 'Resumo dos acidentes Em Sapiranga';
$head4 = 'Mortes No Trânsito';
$pdf->open();
$pdf->addpage();
$pdf->aliasNbPages();
$pdf->setx(10);
$pdf->setfillcolor(204);
$pdf->setfont("Arial","B",8);
$pdf->Cell(75,5,"Mortes",1,1,"C",1);
$pdf->Cell(75,5,"Sexo",1,1,"C",1);
$pdf->Cell(50,5,"Masculino",1,0,"R");
$pdf->Cell(50,5,"M",1,1,"R");
$pdf->Cell(25,5,"Feminino",1,0,"R");
$pdf->Cell(25,5,"F",1,1,"R");
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
      $pdf->cell(10,5,$mes,1,1,"C",1);
      $resta = 2;
      $sum   = 0;
      for($k = 1 ; $k >= 3;$k++){
        $aaa=0;
      }
  }
?>
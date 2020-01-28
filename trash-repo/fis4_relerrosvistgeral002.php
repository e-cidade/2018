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
db_postmemory($HTTP_SERVER_VARS);
//db_msgbox($numlote);exit;
$head3 = "RELATORIO DE VISTORIAS NÃO CALCULADAS";
$result = pg_query("select y05_codvist,
                           q02_inscr,
            						   z01_nome,
						               y04_msgretorno 
          				     from vistoriaslotevist
            						  left join vistretornocalc on y04_codmsg  = y05_codmsg
            						  inner join vistorias       on y05_codvist = y70_codvist
            						                            and y70_instit  = ".db_getsession('DB_instit')." 
            						  inner join vistinscr       on y71_codvist = y05_codvist
            						  inner join issbase         on q02_inscr   = y71_inscr
            						  inner join cgm             on z01_numcgm  = q02_numcgm
          					where y05_codmsg != 9 
                		  and y05_vistoriaslote = $numlote");


$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe inscrições cadastradas para as classes selecionadas.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < pg_numrows($result);$x++)
{
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
   {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->setfillcolor(215);
      $pdf->cell(15,$alt,"Cód Vist",1,0,"C",1);
      $pdf->cell(15,$alt,"Inscrição",1,0,"C",1);
      $pdf->cell(70,$alt,"Nome",1,0,"C",1);
      $pdf->cell(90,$alt,"Status",1,1,"C",1);
      $pdf->cell(190,1,"",0,1,"C",0);
	  
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   if(($x%2)==0){
       $cor = 245;	 
   }else{
       $cor = 235;	 
   }
   $pdf->setfillcolor($cor);
   $pdf->cell(15,$alt,$y05_codvist,0,0,"C",1);
   $pdf->cell(15,$alt,$q02_inscr,0,0,"C",1);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",1);
   $pdf->cell(90,$alt,$y04_msgretorno,0,1,"L",1);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>
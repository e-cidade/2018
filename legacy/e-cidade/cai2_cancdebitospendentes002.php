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
include("classes/db_cancdebitos_classe.php");
include("classes/db_cancdebitosreg_classe.php");
include("classes/db_cgm_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcancdebitos    = new cl_cancdebitos;
$clcancdebitosreg = new cl_cancdebitosreg;
$clcgm = new cl_cgm;
$clcancdebitos->rotulo->label();
$clcancdebitosreg->rotulo->label();
$result = $clcancdebitos->sql_record($clcancdebitos->sql_pendentes("*","","k20_codigo=$k20_codigo"));
if($clcancdebitos->numrows == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
db_fieldsmemory($result,0);
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head1 = "Relatório dos Débitos a serem cancelados";
$head2 = "Data/Hora:".$k20_data."/".$k20_hora;
$head3 = "Usuário:".$k20_usuario."-".$nome;
$pri = true;
$numcgm = "";
$tvlrhis      = 0;
$tvlrcor      = 0;
$tvlrjuros    = 0;
$tvlrmulta    = 0;
$tvlrdesconto = 0;
	 
$tgvlrhis      = 0;
$tgvlrcor      = 0;
$tgvlrjuros    = 0;
$tgvlrmulta    = 0;
$tgvlrdesconto = 0;
	 
 for ($i = 0;$i < $clcancdebitos->numrows;$i++){
  db_fieldsmemory($result,$i);

  $result_deb = debitos_numpre($k21_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$k21_numpar);
  db_fieldsmemory($result_deb,0);

	$resultcgm = $clcgm->sql_record($clcgm->sql_query($k00_numcgm,"z01_numcgm, z01_nome"));
	db_fieldsmemory($resultcgm, 0);

  if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
   $pdf->addpage();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',7);
   $pdf->cell(50,4,$RLk20_descr,1,0,"L",1);
   $pdf->cell(120,4,$k20_descr,1,1,"L",1);
   $pdf->cell(50,4,$RLk21_obs,1,0,"L",1);
   $pdf->cell(120,4,$k21_obs,1,1,"L",1);
   $pdf->cell(25,4,$RLk21_numpre,1,0,"C",0);
   $pdf->cell(20,4,$RLk21_numpar,1,0,"C",0);
   $pdf->cell(20,4,$RLk21_receit,1,0,"C",0);
   $pdf->cell(21,4,"Vlr. Hist.",1,0,"R",0);
   $pdf->cell(21,4,"Vlr. Cor.",1,0,"R",0);
   $pdf->cell(21,4,"Juros",1,0,"R",0);
   $pdf->cell(21,4,"Multa",1,0,"R",0);
   $pdf->cell(21,4,"Desconto",1,1,"R",0);
   $pri = false;
  }
  if($numcgm != $z01_numcgm){

   if ($numcgm != 0) {	
     $pdf->cell(25,4,"",1,0,"C",0);
     $pdf->cell(20,4,"",1,0,"C",0);
     $pdf->cell(20,4,"",1,0,"C",0);
     $pdf->cell(21,4,$tvlrhis,1,0,"R",0);
     $pdf->cell(21,4,$tvlrcor,1,0,"R",0);
     $pdf->cell(21,4,$tvlrjuros,1,0,"R",0);
     $pdf->cell(21,4,$tvlrmulta,1,0,"R",0);
     $pdf->cell(21,4,$tvlrdesconto,1,1,"R",0);
   }
   
   $pdf->cell(170,4,$z01_numcgm." - ".$z01_nome,1,1,"L",1);
      
  }
   $pdf->cell(25,4,$k21_numpre,1,0,"C",0);
   $pdf->cell(20,4,$k21_numpar,1,0,"C",0);
   $pdf->cell(20,4,$k21_receit,1,0,"C",0);
   $pdf->cell(21,4,$vlrhis,1,0,"R",0);
   $pdf->cell(21,4,$vlrcor,1,0,"R",0);
   $pdf->cell(21,4,$vlrjuros,1,0,"R",0);
   $pdf->cell(21,4,$vlrmulta,1,0,"R",0);
   $pdf->cell(21,4,$vlrdesconto,1,1,"R",0);
/*
   $tvlrhis      = $vlrhis;
   $tvlrcor      = $vlrcor;
   $tvlrjuros    = $vlrjuros;
   $tvlrmulta    = $vlrmulta;
   $tvlrdesconto = $vlrdesconto;
  */ 
   $tgvlrhis      += $vlrhis;
   $tgvlrcor      += $vlrcor;
   $tgvlrjuros    += $vlrjuros;
   $tgvlrmulta    += $vlrmulta;
   $tgvlrdesconto += $vlrdesconto;
   
   $numcgm = $z01_numcgm;
 }
 $pdf->cell(65,4,"TOTAIS:",1,0,"R",0);
 $pdf->cell(21,4,$tgvlrhis,1,0,"R",0);
 $pdf->cell(21,4,$tgvlrcor,1,0,"R",0);
 $pdf->cell(21,4,$tgvlrjuros,1,0,"R",0);
 $pdf->cell(21,4,$tgvlrmulta,1,0,"R",0);
 $pdf->cell(21,4,$tgvlrdesconto,1,1,"R",0);
$pdf->Output();
?>
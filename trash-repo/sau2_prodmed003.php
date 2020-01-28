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
include("classes/db_prontuarios_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();
$unidade = str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
$sql = "select cgm1.z01_nome as nomemed,
               medicos.sd03_i_codigo,
               unidades.sd02_i_codigo,
               db_depart.descrdepto,
               rhcbo.rh70_estrutural,
               rhcbo.rh70_descr,
               count(*) as quantidade
           from prontproced
          inner join sau_procedimento	on sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento 
          inner join prontuarios 		on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
          inner join especmedico 		on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional
          inner join rhcbo 				on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo
          inner join unidademedicos 	on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
          inner join medicos 			on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico
          inner join cgm as cgm1 		on cgm1.z01_numcgm = medicos.sd03_i_cgm
          inner join db_depart 			on db_depart.coddepto = unidademedicos.sd04_i_unidade
          inner join unidades 			on unidades.sd02_i_codigo = db_depart.coddepto
          
         where sd29_d_data BETWEEN '$data1' and '$data2'
           and sd02_i_codigo in($unidade)
         GROUP BY cgm1.z01_nome,
               medicos.sd03_i_codigo,
               db_depart.descrdepto,
               unidades.sd02_i_codigo,
               rhcbo.rh70_estrutural,
               rhcbo.rh70_descr
         order by cgm1.z01_nome,unidades.sd02_i_codigo
                 ";


//echo"SQL = [$sql] ";
$result = $clprontuarios->sql_record($sql);
//db_criatabela($result);
//exit;

if($clprontuarios->numrows == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Relatório dos Produtividade Medica";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pri = true;
$T  = 0;
$TG = 0;
$m = 0;
$u = 0;
$zero = 0;
 for ($i = 0;$i < $clprontuarios->numrows;$i++){
     db_fieldsmemory($result,$i);
     if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
        $pdf->addpage();
        $pdf->setfillcolor(235);
        $pdf->setfont('arial','b',7);
        $pri = false;
     }
     if( $m == 0 || $m != $sd03_i_codigo." - ".$nomemed ){
          if($zero==1){
              $pdf->setfont('arial','b',7);
              $pdf->cell(160,4," Total= $T ",1,1,"R",1);
          }
          $zero=1;
          $pdf->cell(160,4," ",0,1,"L",0);
          $pdf->cell(160,4,$sd03_i_codigo." - ".$nomemed,1,1,"L",1);
          $m = $sd03_i_codigo." - ".$nomemed;
          $T=0;
     }
     if($u != $sd02_i_codigo || $u == 0){
          $pdf->setfont('arial','b',7);
          $pdf->cell(5,4,"",0,0,"C",0);
          $pdf->cell(155,4,$sd02_i_codigo." - ".$descrdepto,1,1,"L",1);
          $u = $sd02_i_codigo;
     }
     $pdf->cell(10,4,"",0,0,"C",0);
     $pdf->cell(90,4,$rh70_estrutural." - ".$rh70_descr,1,0,"L",0);
     $pdf->cell(60,4,$quantidade,1,1,"R",0);
     $T += $quantidade;
     $TG += $quantidade;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(160,2,"",0,1,"L",0);
  $pdf->cell(100,4,"Total Geral",1,0,"L",1);
  $pdf->cell(60,4,$TG,1,1,"L",0);
  $pdf->setfont('arial','b',7);
  $pdf->Output();
?>
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
include("classes/db_unidades_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
set_time_limit(0);
$clunidades = new cl_unidades;
$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();
$clunidades->rotulo->label("sd02_i_codigo");
$clunidades->rotulo->label("sd02_c_nome");

$unidade = str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
$sql_und = " SELECT  distinct  prontuarios.sd24_i_unidade,
                       db_depart.descrdepto
                  FROM unidades
                 INNER JOIN db_depart    on db_depart.coddepto = unidades.sd02_i_codigo
                 INNER JOIN prontuarios  on prontuarios.sd24_i_unidade = unidades.sd02_i_codigo

                 WHERE prontproced.sd29_d_data BETWEEN '$data1' AND '$data2'
                   AND unidades.sd02_i_codigo in ($unidade)";



$sql_rel = " SELECT    prontproced.sd29_i_procedimento,
                       especmedico.sd27_i_especialidade,
                       proctipoatend.sd20_i_tipoatend,
                       prontuarios.sd24_i_grupoatend,
                       procfaixaetaria.sd16_i_faixaetaria,
                       count(*) as tot
                  FROM prontproced
                 INNER JOIN prontuarios     on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
                 INNER JOIN procedimentos   on procedimentos.sd09_i_codigo = prontproced.sd29_i_procedimento
                 INNER JOIN especmedico     on especmedico.sd27_i_codigo = prontproced.sd29_i_especmed
                 INNER JOIN proctipoatend   on proctipoatend.sd20_i_codigo = prontproced.sd29_i_proctipoatend
                 INNER JOIN grupoatend      on grupoatend.sd15_i_codigo = prontuarios.sd24_i_grupoatend
                 INNER JOIN procfaixaetaria on procfaixaetaria.sd16_i_codigo = prontproced.sd29_i_procafaixaetaria
                 WHERE prontproced.sd29_d_data BETWEEN '$data1' AND '$data2'
                   AND prontuarios.sd24_i_unidade in ($unidade)";
                 if(@$pab == "S"){
                      $sql_rel .= " AND procedimentos.sd09_b_pab = 't'";
                 }
                 if(@$pab == "N"){
                      $sql_rel .= " AND procedimentos.sd09_b_pab = 'f'";
                 }
                 $sql_rel .="
                               GROUP BY prontproced.sd29_i_procedimento,
                                        especmedico.sd27_i_especialidade,
                                        proctipoatend.sd20_i_tipoatend,
                                        prontuarios.sd24_i_grupoatend,
                                        procfaixaetaria.sd16_i_faixaetaria
                               ORDER BY prontproced.sd29_i_procedimento,
                                        especmedico.sd27_i_especialidade,
                                        proctipoatend.sd20_i_tipoatend,
                                        prontuarios.sd24_i_grupoatend,
                                        procfaixaetaria.sd16_i_faixaetaria";

$query = @pg_query($sql_rel);
$linhas = @pg_num_rows($query);

if($linhas == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
$query_und = @pg_query($sql_und);
$linhas_und = @pg_num_rows($query_und);


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Relatório do Boletim de Produção Ambulatorial";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$b=0;
$pri = true;
$TOT1=0;
$TOT2=0;
$TOT3=0;
$TOT4=0;
$TOT5=0;
$TOT6=0;
for($x=0; $x < $linhas; $x++){
     db_fieldsmemory($query,$x);
     if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){

          if( $pri == true ){
             $pdf->addpage();
             $pdf->setfillcolor(235);
             $pdf->setfont('arial','b',7);
             $pdf->cell(180,10,"UNIDADE(S) SELECIONADA(S)",1,1,"C",0);
             for( $x_und=0; $x_und < $linhas_und; $x_und++ ){
                  db_fieldsmemory($query_und,$x_und);
                  $pdf->cell(30,4,$sd24_i_unidade,1,0,"C",1);
                  $pdf->cell(150,4,$descrdepto,1,1,"C",1);
             }
          }

          $pdf->addpage();
          $pdf->setfillcolor(235);
          $pdf->setfont('arial','b',7);
          $pdf->cell(30,4,"Procedimento",1,0,"C",1);
          $pdf->cell(30,4,"Especialidade",1,0,"C",1);
          $pdf->cell(30,4,"Tipo de Atendimento",1,0,"C",1);
          $pdf->cell(30,4,"Grupo de Atendimento",1,0,"C",1);
          $pdf->cell(30,4,"Faixa Etaria",1,0,"C",1);
          $pdf->cell(30,4,"Quantidade",1,1,"C",1);
          $pri = false;
     }
     //if($b != $sd24_i_unidade){
     //     $pdf->setfont('arial','b',7);
     //     $pdf->cell(180,4,$sd24_i_unidade." - ".$descrdepto,1,1,"L",1);
     //}
     $pdf->setfont('arial','',7);
     $pdf->cell(30,4,$sd29_i_procedimento,1,0,"C",0);
     $pdf->cell(30,4,$sd27_i_especialidade,1,0,"C",0);
     $pdf->cell(30,4,$sd20_i_tipoatend,1,0,"C",0);
     $pdf->cell(30,4,$sd24_i_grupoatend,1,0,"C",0);
     $pdf->cell(30,4,$sd16_i_faixaetaria,1,0,"C",0);
     $pdf->cell(30,4,$tot,1,1,"C",0);

     $TOT1+=$sd29_i_procedimento;
     $TOT2+=$sd27_i_especialidade;
     $TOT3+=$sd20_i_tipoatend;
     $TOT4+=$sd24_i_grupoatend;
     $TOT5+=$sd16_i_faixaetaria;
     $TOT6+=$tot;
     $b = $sd24_i_unidade;
}
$pdf->setfont('arial','b',7);
$pdf->cell(180,4,"TOTAL DAS UNIDADES",1,1,"C",1);
$pdf->cell(30,4,$TOT1,1,0,"C",0);
$pdf->cell(30,4,$TOT2,1,0,"C",0);
$pdf->cell(30,4,$TOT3,1,0,"C",0);
$pdf->cell(30,4,$TOT4,1,0,"C",0);
$pdf->cell(30,4,$TOT5,1,0,"C",0);
$pdf->cell(30,4,$TOT6,1,1,"C",0);
$pdf->Output();
?>
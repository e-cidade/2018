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

/*$unidade = str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
*/
$sql_und = " SELECT  coddepto,
                       db_depart.descrdepto
                  FROM unidades
                 INNER JOIN db_depart    on db_depart.coddepto = unidades.sd02_i_codigo
                 WHERE unidades.sd02_i_codigo in ($unidades)";


/*
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

*/
$sql_rel = "select db_depart.coddepto, descrdepto,
       cp06_logradouro,
       numero,
       z01_nome,
       case when sd30_i_turno = 1 then 'Manhã'
            when sd30_i_turno = 2 then 'Tarde'
            when sd30_i_turno = 3 then 'Noite'
       end as turno,
       case when sd30_i_diasemana = 0 then 'Domingo'
            when sd30_i_diasemana = 1 then 'Segunda'
            when sd30_i_diasemana = 2 then 'Terça'
            when sd30_i_diasemana = 3 then 'Quarta'
            when sd30_i_diasemana = 4 then 'Quinta'
            when sd30_i_diasemana = 5 then 'Sexta'
            when sd30_i_diasemana = 6 then 'Sábado'
       end as semana,
       sd30_c_horaini, sd30_c_horafim, sd30_i_fichas, sd30_i_reservas
  from unidademedicos
 inner join unidades      on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade
 inner join db_depart     on db_depart.coddepto = unidades.sd02_i_codigo
  left join db_departender on db_departender.coddepto = db_depart.coddepto
  left join logradcep     on logradcep.j65_lograd = db_departender.codlograd
  left join ceplogradouros on ceplogradouros.cp06_codlogradouro = logradcep.j65_ceplog
 inner join undmedhorario on undmedhorario.sd30_i_undmed = unidademedicos.sd04_i_codigo
 inner join medicos       on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico
 inner join cgm           on cgm.z01_numcgm = medicos.sd03_i_codigo
 where unidades.sd02_i_codigo in ($unidades)
 order by unidades.sd02_i_codigo, z01_nome
";
//die( ">>>>>".$sql_rel );
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
$head2 = "Relatório das Unidades Ambulatorial";
//$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
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
                  $pdf->cell(30,4,$coddepto,1,0,"C",1);
                  $pdf->cell(150,4,$descrdepto,1,1,"C",1);
             }
          }
     
          $pdf->addpage();

          $pdf->setfont('arial','b',7);
          $pdf->cell(185,4,$coddepto." - ".$descrdepto.", ".$cp06_logradouro.", ".$numero,1,1,"L",1);
          $b = $coddepto;

          $pdf->setfillcolor(235);
          $pdf->setfont('arial','b',7);
          $pdf->cell(80,4,"Profissional",1,0,"C",1);
          $pdf->cell(20,4,"Turno",1,0,"C",1);
          $pdf->cell(20,4,"Dia da Semana",1,0,"C",1);
          $pdf->cell(20,4,"Inidio",1,0,"C",1);
          $pdf->cell(20,4,"Fim",1,0,"C",1);
          $pdf->cell(10,4,"Fichas",1,0,"C",1);
          $pdf->cell(15,4,"Reservas",1,1,"C",1);
          $pri = false;
          
     }
     if($b != $coddepto){
          $pdf->setfont('arial','b',7);
          $pdf->cell(185,4,$coddepto." - ".$descrdepto.", ".$cp06_logradouro.", ".$numero,1,1,"L",1);
          $b = $coddepto;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(80,4,$z01_nome,1,0,"L",0);
     $pdf->cell(20,4,$turno,1,0,"C",0);
     $pdf->cell(20,4,$semana,1,0,"C",0);
     $pdf->cell(20,4,$sd30_c_horaini,1,0,"C",0);
     $pdf->cell(20,4,$sd30_c_horafim,1,0,"C",0);
     $pdf->cell(10,4,$sd30_i_fichas,1,0,"C",0);
     $pdf->cell(15,4,$sd30_i_reservas,1,1,"C",0);

}
$pdf->Output();
?>
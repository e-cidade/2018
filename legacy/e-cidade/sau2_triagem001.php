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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//$unidade = @$unidade;
$descrdepto = db_getsession("DB_coddepto");
//Monta SQL
$sql_pront = "select * 
                from prontuarios
               inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
                left join especmedico on especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional
                left join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
                left join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico
                left join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm	
                left join rhcbo on rhcbo.rh70_sequencial =  especmedico.sd27_i_rhcbo
                left join unidades on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade	
                left join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
               where sd24_i_unidade in ($unidade)
                 and ( sd24_v_motivo is null or sd24_v_motivo = '' )
               order by sd24_i_unidade, sd24_i_codigo";

$query_pront = @pg_query($sql_pront) or die(pg_errormessage());
$linhas = @pg_num_rows($query_pront);
if($linhas == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatï¿½rio<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head1 = "Relatório de Triagem";
if( !empty($unidade) ){
 $head3 = "Unidade: $unidade - ".pg_result($query_pront,0,'descrdepto');
}
$pri=true;

for ($i = 0;$i < $linhas;$i++){
  db_fieldsmemory($query_pront,$i);
  if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
    $pdf->addpage('L');
    $pdf->setfillcolor(200);
    $pdf->setfont('arial','b',8);

    $pdf->setfont('arial','b',8);
    $pdf->cell(10,4,"FAA",1,0,"L",1);
    $pdf->cell(70,4,"Nome",1,0,"L",1);
    $pdf->cell(70,4,"Profissional da Triagem",1,0,"L",1);
    $pdf->cell(12,4,"CBO",1,0,"L",1);
    $pdf->cell(50,4,"Motivo",1,0,"L",1);
    $pdf->cell(21,4,"Pressão",1,0,"L",1);
    $pdf->cell(21,4,"Peso",1,0,"L",1);
    $pdf->cell(21,4,"Temperatura",1,1,"L",1);
    $pri=false;
  }
  $pdf->setfont('arial','',7);

  $sd24_f_peso=$sd24_f_peso==0?"":$sd24_f_peso;
  $sd24_f_temperatura=$sd24_f_temperatura==0?"":$sd24_f_temperatura;

  $pdf->cell(10,6,$sd24_i_codigo,1,0,"L",0);
  $pdf->cell(70,6,$z01_v_nome,1,0,"L",0);
  $pdf->cell(70,6,$z01_nome,1,0,"L",0);
  $pdf->cell(12,6,$rh70_estrutural,1,0,"L",0);
  $pdf->cell(50,6,$sd24_v_motivo,1,0,"L",0);
  $pdf->cell(21,6,$sd24_v_pressao,1,0,"L",0);
  $pdf->cell(21,6,$sd24_f_peso,1,0,"L",0);
  $pdf->cell(21,6,$sd24_f_temperatura,1,1,"L",0);
}
$pdf->Output();
@pg_free_result($query);
?>
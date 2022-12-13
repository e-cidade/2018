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
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
$unidade = @$unidade;
//Monta SQL

if( !empty($unidade) ){
     $sql_pront = "SELECT count(*) as qtd,
                    id_usuario,
                    nome,
                    descrdepto
              FROM prontuarios
             INNER JOIN unidades    on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade
             INNER JOIN db_depart   on db_depart.coddepto = unidades.sd02_i_codigo
             INNER JOIN prontproced on prontproced.sd29_i_prontuario = prontuarios.sd24_i_codigo
             INNER JOIN db_usuarios  on  db_usuarios.id_usuario = prontproced.sd29_i_usuario
             WHERE prontproced.sd29_d_cadastro BETWEEN '$data1' AND '$data2'
               AND unidades.sd02_i_codigo = $unidade
             GROUP BY id_usuario,
                      nome,
                      descrdepto
             ORDER BY nome
            ";
     $sql_agenda = "SELECT count(*) as qtd,
                    id_usuario,
                    nome,
                    descrdepto
              FROM agendamentos
             INNER JOIN undmedhorario  on undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor   
             INNER JOIN unidademedicos on unidademedicos.sd04_i_codigo = undmedhorario.sd30_i_undmed
             INNER JOIN unidades       on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade
             INNER JOIN db_depart      on db_depart.coddepto = unidades.sd02_i_codigo
             INNER JOIN db_usuarios    on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario
             WHERE agendamentos.sd23_d_agendamento BETWEEN '$data1' AND '$data2'
               AND unidades.sd02_i_codigo = $unidade
             GROUP BY id_usuario,
                      nome,
                      descrdepto
             ORDER BY nome
            ";
}else{
     $sql_pront = "SELECT count(*) as qtd,
                    id_usuario,
                    nome
              FROM prontuarios
             INNER JOIN unidades    on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade
             INNER JOIN prontproced on prontproced.sd29_i_prontuario = prontuarios.sd24_i_codigo
             INNER JOIN db_usuarios  on  db_usuarios.id_usuario = prontproced.sd29_i_usuario
             WHERE prontproced.sd29_d_cadastro BETWEEN '$data1' AND '$data2'
             GROUP BY id_usuario,
                      nome
             ORDER BY nome
            ";
     $sql_agenda = "SELECT count(*) as qtd,
                    id_usuario,
                    nome
              FROM agendamentos
             INNER JOIN undmedhorario  on undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor   
             INNER JOIN unidademedicos on unidademedicos.sd04_i_codigo = undmedhorario.sd30_i_undmed
             INNER JOIN unidades    on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade
             INNER JOIN db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario
             WHERE agendamentos.sd23_d_agendamento BETWEEN '$data1' AND '$data2'
             GROUP BY id_usuario,
                      nome
             ORDER BY nome
            ";

}
$query_pront = pg_query($sql_pront);
$linhas_pront = pg_num_rows($query_pront);
$query_agenda = pg_query($sql_agenda);
$linhas_agenda = pg_num_rows($query_agenda);

if($linhas_pront == 0 and $linhas_agenda == 0){
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
$head1 = "Relatório de FAA por usuários";
$head2 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
if( !empty($unidade) && pg_num_rows( $query_pront ) > 0){
    $head3 = "Unidade: $unidade - ".pg_result($query_pront,0,'descrdepto');
}
$pdf->addpage();
$pdf->setfillcolor(195);

$pdf->setfont('arial','b',9);
$pdf->cell(190,5,"ATENDIMENTO: AGENDAMENTOS",0,1,"C",0);

$pdf->setfont('arial','b',7);
$pdf->cell(25,5," CÓDIGO",0,0,"C",1);
$pdf->cell(120,5,"     USUÁRIO",0,0,"L",1);
$pdf->cell(45,5,"ATENDIMENTOS   ",0,1,"R",1);
$s_total_agenda = 0;
   
for($i = 0;$i < $linhas_agenda;$i++){
 
     db_fieldsmemory($query_agenda,$i);
     $pdf->setfont('arial','',7);
     $pdf->cell(25,4," $id_usuario",0,0,"L",0);
     $pdf->cell(120,4,"   $nome",0,0,"L",0);
     $pdf->cell(40,4,(number_format($qtd, 0, '', '.')),0,0,"R",0);
     $pdf->cell(5,4,"",0,1,"R",0);

     $s_usuario=1;
     for($reg=0;$reg<$i;$reg++){
   
         $s_usuario++;
     }

     $s_total_agenda += $qtd;
}

$pdf->setfont('arial','b',7);
$pdf->setfillcolor(235);
$pdf->cell(95,5,"TOTAL DE USUÁRIOS:  $s_usuario",0,0,"L",1);
$pdf->cell(90,5,"ATENDIMENTOS NO PERÍODO:  ".(number_format($s_total_agenda, 0, '', '.')),0,0,"R",1);
$pdf->cell(5,5,"",0,1,"R",1);

$pdf->cell(190,9,"",0,1,"C",0);

$pdf->setfont('arial','b',9);
$pdf->cell(190,5,"ATENDIMENTO: PRONTUÁRIOS",0,1,"C",0);

$pdf->setfillcolor(195);
$pdf->setfont('arial','b',7);
$pdf->cell(25,5," CÓDIGO",0,0,"C",1);
$pdf->cell(120,5,"     USUÁRIO",0,0,"L",1);
$pdf->cell(45,5,"ATENDIMENTOS   ",0,1,"R",1);
$s_total_pront = 0;

for($i = 0;$i < $linhas_pront;$i++){

    db_fieldsmemory($query_pront,$i);
    $pdf->setfont('arial','',7);
    $pdf->cell(25,4," $id_usuario",0,0,"L",0);
    $pdf->cell(120,4,"   $nome",0,0,"L",0);
    $pdf->cell(40,4,(number_format($qtd, 0, '', '.')),0,0,"R",0);
    $pdf->cell(5,4,"",0,1,"R",0);
          
    $s_usuario=1;
    for($reg=0;$reg<$i;$reg++){
  
        $s_usuario++;
    }
    $s_total_pront += $qtd;
}

$pdf->setfont('arial','b',7);
$pdf->setfillcolor(235);
$pdf->cell(95,5,"TOTAL DE USUÁRIOS:  $s_usuario",0,0,"L",1);
$pdf->cell(90,5,"ATENDIMENTOS NO PERÍODO:  ".(number_format($s_total_pront, 0, '', '.')),0,0,"R",1);
$pdf->cell(5,5,"",0,1,"R",1);
      

$total_geral=$s_total_agenda+$s_total_pront;
$pdf->setfont('arial','b',9);
$pdf->setfillcolor(240);
$pdf->cell(190,4,"",0,1,"L",0);
$pdf->cell(185,5,"TOTAL GERAL DE ATENDIMENTOS :  ".(number_format($total_geral, 0, '', '.')),0,0,"R",1);
$pdf->cell(5,5,"",0,1,"R",1);

$pdf->Output();
@pg_free_result($query);
?>
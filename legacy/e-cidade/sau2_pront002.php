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
include("classes/db_prontproced_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clprontproced = new cl_prontproced;
$sql = "select sd24_c_atendimento,
               sd24_i_cgm,
               cgm.z01_nome,
               sd02_i_codigo,
               sd02_c_razao,
               sd24_i_usuario,
               nome,
               sd24_c_motivo,
               to_char(sd24_d_data,'dd/mm/yyyy') as sd24_d_data,
               sd24_c_hora,
               sd29_i_medico,
               cgm1.z01_nome as nomemed,
               sd29_i_especialidade,
               sd05_c_descr,
               sd29_i_procedimento,
               sd09_c_descr,
               sd29_i_atendtipo,
               sd14_c_descr,
               sd29_t_tratamento
          from prontuarios
         inner join db_usuarios    on db_usuarios.id_usuario       = prontuarios.sd24_i_usuario
         inner join cids           on cids.sd22_c_codigo           = prontuarios.sd24_c_cid
         inner join unidades       on unidades.sd02_i_codigo       = prontuarios.sd24_i_unidade
         inner join grupoatend     on grupoatend.sd15_i_codigo     = prontuarios.sd24_i_grupoatend
         inner join cgm            on cgm.z01_numcgm               = prontuarios.sd24_i_cgm
         left join prontproced     on prontuarios.sd24_i_id        = prontproced.sd29_i_prontuario
         left join procedimentos   on procedimentos.sd09_i_codigo  = prontproced.sd29_i_procedimento
         left join grupoproc       on grupoproc.sd11_c_codigo      = procedimentos.sd09_c_grupoproc
         left join especialidades  on especialidades.sd05_i_codigo = prontproced.sd29_i_especialidade
         left join medicos         on medicos.sd03_i_id            = prontproced.sd29_i_medico
         left join cgm as cgm1     on cgm1.z01_numcgm              = medicos.sd03_i_codigo
         left join atendtipo       on atendtipo.sd14_i_codigo      = prontproced.sd29_i_atendtipo
         where sd24_c_atendimento = '$Num'";
$query = $clprontproced->sql_record($sql);
if($clprontproced->numrows == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum registro encontrado<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
db_fieldsmemory($query,0);
 $pdf = new PDF();
 $pdf->Open();
 $pdf->AliasNbPages();
 $head2 = "Dados do Prontuario";
 $head3 = "Numero do Atendimento: ".substr($sd24_c_atendimento,0,4)." | ".substr($sd24_c_atendimento,4,2)." | ".substr($sd24_c_atendimento,6,5);
 $head3 = "Data/Hora:".$sd24_d_data."/".$sd24_c_hora;
  $pdf->addpage();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','',7);
  $pdf->cell(192,4,"PRONTUARIO ".$sd24_i_id,1,1,"L",1);
  $pdf->cell(22,4,"Atendimento",1,0,"L",1);
  $pdf->cell(78,4,"Paciênte",1,0,"L",1);
  $pdf->cell(77,4,"Unidade",1,0,"L",1);
  $pdf->cell(15,4,"Usuário",1,1,"L",1);
  $pdf->cell(22,4,$sd24_c_atendimento,1,0,"L",0);
  $pdf->cell(78,4,$sd24_i_cgm." - ".$z01_nome,1,0,"L",0);
  $pdf->cell(77,4,$sd02_i_codigo." - ".$sd02_c_razao,1,0,"L",0);
  $pdf->cell(15,4,$sd24_i_usuario,1,1,"L",0);
  $pdf->cell(192,5,"Motivo:",1,1,"L",1);
  $pdf->cell(192,5,$sd24_c_motivo,1,1,"L",0);
  $pdf->cell(55,4,"Médico",1,0,"L",1);
  $pdf->cell(40,4,"Especialidade",1,0,"L",1);
  $pdf->cell(55,4,"Procedimento",1,0,"L",1);
  $pdf->cell(42,4,"Tipo de Atendimento",1,1,"L",1);
  for($x=0;$x<$clprontproced->numrows;$x++){
   db_fieldsmemory($query,$x);
   $pdf->cell(55,4,$sd29_i_medico." - ".$nomemed,1,0,"L",0);
   $pdf->cell(40,4,$sd29_i_especialidade." - ".$sd05_c_descr,1,0,"L",0);
   $pdf->cell(55,4,$sd29_i_procedimento." - ".$sd09_c_descr,1,0,"L",0);
   $pdf->cell(42,4,$sd29_i_atendtipo." - ".$sd14_c_descr,1,1,"L",0);
   $pdf->cell(192,4,"Tratamento",1,1,"L",1);
   $pdf->cell(192,12,$sd29_t_tratamento,1,1,"L",0);
  }
 $pdf->Output();
?>
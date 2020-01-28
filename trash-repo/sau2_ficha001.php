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
include("classes/db_agendamentos_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clagendamentos = new cl_agendamentos;

$sql = "select sd01_c_siasus,
               sd01_i_familia,
               sd02_i_codigo,
               sd02_c_razao,
               sd02_c_endereco,
               sd02_c_cidade,
               sd02_i_numero,
               sd02_c_bairro,
               sd02_c_cep,
               sd02_c_siasus,
               cgm.z01_numcgm,
               cgm.z01_nome,
               cgm.z01_ender,
               cgm.z01_numero,
               cgm.z01_bairro,
               cgm.z01_munic,
               cgm.z01_cep,
               case when cgm.z01_sexo = 'M' then 'MASCULINO'
               when cgm.z01_sexo = 'F' then 'FEMININO' end,
               sd03_i_codigo,
               cgm1.z01_nome as sd03_c_nome,
               sd03_i_crm,
               sd23_c_atendimento,
               to_char(sd23_d_consulta,'dd/mm/yyyy') as sd23_d_consulta,
               to_char(sd23_d_data,'dd/mm/yyyy') as sd23_d_data,
               sd23_c_hora2,
               sd23_c_hora
               from agendamentos
               inner join cgm            on  cgm.z01_numcgm = sd23_i_cgm
               inner join cgs            on  z01_numcgm     = sd01_i_cgm
               inner join db_usuarios    on  id_usuario     = sd23_i_usuario
               inner join especialidades on  sd05_i_codigo  = sd23_i_especialidade
               inner join unidades       on  sd02_i_codigo  = sd23_i_unidade
               inner join medicos        on  sd03_i_id      = sd23_i_medico
                   inner join cgm as cgm1    on  sd03_i_codigo  = cgm1.z01_numcgm
               where sd23_c_atendimento = '$Agenda'";

$query = $clagendamentos->sql_record($sql);
db_fieldsmemory($query,0);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
 $head2 = "Ficha de Atendimento";
 $head3 = "Numero do Atendimento: ".substr($sd23_c_atendimento,0,4)." | ".substr($sd23_c_atendimento,4,2)." | ".substr($sd23_c_atendimento,6,5);
 $head4 = "Data: ".$sd23_d_data;
 $head5 = "Hora: ".$sd23_c_hora2;
  $pdf->addpage();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','',7);
  $pdf->cell(190,4,"FICHA DE ATENDIMENTO",1,1,"L",1);
  $pdf->cell(40,4,"NUMERO:",1,0,"L",0);
  $pdf->cell(150,4,substr($sd23_c_atendimento,0,4)." | ".substr($sd23_c_atendimento,4,2)." | ".substr($sd23_c_atendimento,6,5),1,1,"L",0);
  $pdf->cell(40,4,"DATA PARA ATENDIMENTO:",1,0,"L",0);
  $pdf->cell(150,4,$sd23_d_consulta." /".$sd23_c_hora,1,1,"L",0);
  $pdf->cell(190,4,"1. UNIDADE PRESTADORA DE ATENDIMENTO",1,1,"L",1);
  $pdf->cell(40,4,"UNIDADE:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_i_codigo,1,1,"L",0);
  $pdf->cell(40,4,"RAZÃO:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_c_razao,1,1,"L",0);
  $pdf->cell(40,4,"ENDEREÇO:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_c_endereco,1,1,"L",0);
  $pdf->cell(40,4,"CIDADE:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_c_cidade,1,1,"L",0);
  $pdf->cell(40,4,"N°:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_i_numero,1,1,"L",0);
  $pdf->cell(40,4,"BAIRRO:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_c_bairro,1,1,"L",0);
  $pdf->cell(40,4,"SIA/SUS:",1,0,"L",0);
  $pdf->cell(150,4,$sd02_siasus,1,1,"L",0);
  $pdf->cell(190,4,"2. IDENTIFICAÇÃO DO PACIENTE",1,1,"L",1);
  $pdf->cell(40,4,"CGM:",1,0,"L",0);
  $pdf->cell(150,4,$z01_numcgm,1,1,"L",0);
  $pdf->cell(40,4,"CARTÃO SUS:",1,0,"L",0);
  $pdf->cell(150,4,$sd01_c_siasus,1,1,"L",0);
  $pdf->cell(40,4,"FAMÍLIA:",1,0,"L",0);
  $pdf->cell(150,4,$sd01_i_familia,1,1,"L",0);
  $pdf->cell(40,4,"NOME:",1,0,"L",0);
  $pdf->cell(150,4,$z01_nome,1,1,"L",0);
  $pdf->cell(40,4,"ENDEREÇO:",1,0,"L",0);
  $pdf->cell(150,4,$z01_ender,1,1,"L",0);
  $pdf->cell(40,4,"CIDADE:",1,0,"L",0);
  $pdf->cell(150,4,$z01_munic,1,1,"L",0);
  $pdf->cell(40,4,"NUMERO:",1,0,"L",0);
  $pdf->cell(150,4,$z01_numero,1,1,"L",0);
  $pdf->cell(40,4,"CEP:",1,0,"L",0);
  $pdf->cell(150,4,$z01_cep,1,1,"L",0);
  $pdf->cell(40,4,"BAIRRO:",1,0,"L",0);
  $pdf->cell(150,4,$z01_bairro,1,1,"L",0);
  $pdf->cell(40,4,"CEP:",1,0,"L",0);
  $pdf->cell(150,4,$z01_cep,1,1,"L",0);
  $pdf->cell(190,4,"3. IDENTIFICAÇÃO DO MÉDICO",1,1,"L",1);
  $pdf->cell(40,4,"NOME: ",1,0,"L",0);
  $pdf->cell(150,4,$sd03_c_nome,1,0,"L",0);
$pdf->Output();
?>
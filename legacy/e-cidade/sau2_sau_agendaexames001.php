<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include(modification("fpdf151/scpdf.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_stdlibwebseller.php"));

include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_sau_agendaexames_ext_classe.php"));


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
set_time_limit(0);

$clsau_agendaexames = new cl_sau_agendaexames_ext;
$clrotulo       = new rotulocampo;

$clsau_agendaexames->rotulo->label();
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_v_telef");



$res_agendaexames = $clsau_agendaexames->sql_record( $clsau_agendaexames->sql_query_ext( $s113_i_codigo ) );

if($clsau_agendaexames->numrows == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}

$res_dbconfig    = $clsau_agendaexames->sql_record("select * from db_config where codigo = ".db_getsession("DB_instit") );
$obj_dbconfig    = db_utils::fieldsMemory($res_dbconfig,0);

$obj_agendaexames = db_utils::fieldsMemory($res_agendaexames,0); 

$dia = substr($obj_agendaexames->z01_d_nasc,8,2);
$mes = substr($obj_agendaexames->z01_d_nasc,5,2);
$ano = substr($obj_agendaexames->z01_d_nasc,0,4);

$idade = calcage( $dia, $mes, $ano, date("d"), date("m"), date("Y") );

$dia_con = substr($obj_agendaexames->s113_d_exame,8,2);
$mes_con = substr($obj_agendaexames->s113_d_exame,5,2);
$ano_con = substr($obj_agendaexames->s113_d_exame,0,4);

$dia_atd = substr($obj_agendaexames->s113_d_agendamento,8,2);
$mes_atd = substr($obj_agendaexames->s113_d_agendamento,5,2);
$ano_atd = substr($obj_agendaexames->s113_d_agendamento,0,4);

$pdf = new SCPDF();
$pdf->Open();
$pdf->AliasNbPages();

$pdf->addpage();
$pdf->setfillcolor(235);

$pdf->rect( $pdf->getX(), $pdf->getY(), 190, 60, "D");

$pdf->cell(20,1,"",0,1,"C",0);

$pdf->setfont('times','b',10);
$pdf->cell(190,4,$obj_dbconfig->nomeinst,0,1,"C",0);
$pdf->cell(190,4,"COMPROVANTE DE AGENDAMENTO DE EXAME",0,1,"C",0);
$pdf->cell(20,4,"",0,1,"C",0);

$pdf->setfont('times','b',8);
$pdf->cell(20,4,"No",0,0,"L",0);
$pdf->cell(40,4,"Data",0,0,"L",0);
$pdf->cell(40,4,"Semana",0,0,"L",0);
$pdf->cell(20,4,"Hora",0,1,"L",0);

$pdf->setfont('times','',8);
$pdf->cell(20,4,$s113_i_codigo,0,0,"L",0);
$pdf->cell(40,4,"$dia_con/$mes_con/$ano_con",0,0,"L",0);
$pdf->cell(40,4,utf8_decode($diasemana) ,0,0,"L",0);
$pdf->cell(20,4,"$obj_agendaexames->s113_c_hora",0,1,"L",0);

$pdf->setfont('times','b',8);
$pdf->cell(20,4,"Prestador",0,1,"L",0);
$pdf->setfont('times','',8);
$pdf->cell(20,4,"{$obj_agendaexames->z01_numcgm} - {$obj_agendaexames->z01_nome}" ,0,1,"L",0);

$pdf->setfont('times','b',8);
$pdf->cell(100,4,"Paciente",0,0,"L",0);
$pdf->cell(20,4,"Data Nasc.",0,1,"L",0);
$pdf->setfont('times','',8);
$pdf->cell(100,4,"{$obj_agendaexames->z01_i_cgsund} - {$obj_agendaexames->z01_v_nome}" ,0,0,"L",0);
$pdf->cell(20,4,"$dia/$mes/$ano" ,0,1,"L",0);

$pdf->setfont('times','b',8);
$pdf->cell(100,4,"Exame",0,0,"L",0);
$pdf->cell(20,4,"",0,1,"L",0);
$pdf->setfont('times','',8);
$pdf->cell(100,4,"{$obj_agendaexames->sd63_c_procedimento} - {$obj_agendaexames->sd63_c_nome}" ,0,0,"L",0);
$pdf->cell(20,4,"" ,0,1,"L",0);

$pdf->cell(190,4,str_repeat("-",200),0,1,"L",0);

$sHora = substr( $obj_agendaexames->s113_c_cadastro, 0, 8 );

$pdf->setfont('times','b',8);
$pdf->cell(100,4,"Atendente",0,0,"L",0);
$pdf->cell(40,4,"Data Atend.",0,0,"L",0);
$pdf->cell(40,4,"Hora Atend.",0,1,"L",0);
$pdf->setfont('times','',8);
$pdf->cell(100,4,"{$obj_agendaexames->s113_i_login} - {$obj_agendaexames->nome}",0,0,"L",0);
$pdf->cell(40,4,"$dia_atd/$mes_atd/$ano_atd",0,0,"L",0);
$pdf->cell(40,4,"{$sHora}",0,1,"L",0);


$pdf->Output();
?>
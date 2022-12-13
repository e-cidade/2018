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

$clunidades = new cl_unidades;
$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();
$clunidades->rotulo->label("sd02_i_codigo");
$clunidades->rotulo->label("sd02_c_nome");

 $unidade = str_replace("X",",",$unidades);
 $data1 = str_replace("X","-",$data1);
 $data2 = str_replace("X","-",$data2);

 $SQL = "select * from prontuarios
          inner join unidades on sd24_i_unidade = sd02_i_codigo
          inner join cgm on sd24_i_cgm = z01_numcgm
          where sd24_i_unidade in($unidade)
            and sd24_d_data BETWEEN '$data1' and '$data2'
	    and sd24_i_id not in(select sd29_i_prontuario from prontproced)
          order by sd24_i_unidade";
//echo $SQL; exit;
 $Query = pg_query($SQL);
 $Linhas = pg_num_rows($Query);
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Relatório dos Prontuarios Sem Procedimentos";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
 $b="X";
 $pri = true;
  for($x=0; $x < $Linhas; $x++){
   db_fieldsmemory($Query,$x);

   if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
    $pdf->addpage("L");
    $pdf->setfillcolor(235);
    $pdf->setfont('arial','b',7);
    $pdf->cell(10,4,"ID",1,0,"C",1);
    $pdf->cell(17,4,"Atendimento",1,0,"C",1);
    $pdf->cell(10,4,"CGM",1,0,"C",1);
    $pdf->cell(60,4,"Nome",1,0,"L",1);
    $pdf->cell(12,4,"Unidade",1,0,"C",1);
    $pdf->cell(65,4,"Razao",1,0,"L",1);
    $pdf->cell(17,4,"Grupo Atend.",1,0,"C",1);
    $pdf->cell(10,4,"CID",1,0,"C",1);
    $pdf->cell(15,4,"Data",1,0,"C",1);
    $pdf->cell(12,4,"Hora",1,0,"C",1);
    $pdf->cell(40,4,"Motivo",1,0,"C",1);
    $pdf->cell(11,4,"Usuário",1,1,"C",1);
    $pri = false;
   }
  /*if($b != $sd02_i_unidade){
   if($b == "X"){
    $pdf->setfont('arial','b',7);
    $pdf->cell(280,4,$sd02_i_codigo." - ".$sd02_c_razao,1,1,"L",1);
    $b = $sd02_i_unidade;
   }
   $pdf->cell(280,4,"TOTAL DAS UNIDADES: ".$TOT1,1,1,"C",1);
  } */
 $pdf->setfont('arial','',7);
 $pdf->cell(10,4,$sd24_i_id,1,0,"C",0);
 $pdf->cell(17,4,$sd24_c_atendimento,1,0,"C",0);
 $pdf->cell(10,4,$sd24_i_cgm,1,0,"C",0);
 $pdf->cell(65,4,$z01_nome,1,0,"C",0);
 $pdf->cell(12,4,$sd24_i_unidade,1,0,"C",0);
 $pdf->cell(70,4,$sd02_c_nome,1,0,"C",0);
 $pdf->cell(17,4,$sd24_i_grupoatend,1,0,"C",0);
 $pdf->cell(10,4,$sd24_c_cid,1,0,"C",0);
 $pdf->cell(15,4,$sd24_d_data,1,0,"C",0);
 $pdf->cell(12,4,$sd24_c_hora,1,0,"C",0);
 $pdf->cell(40,4,$sd24_c_motivo,1,0,"C",0);
 $pdf->cell(11,4,$sd24_i_usuario,1,1,"C",0);
 $TOT1++;
 $b = $sd02_i_codigo;
}
 $pdf->setfont('arial','b',7);
 $pdf->cell(280,4,"TOTAL DAS UNIDADES: ".$TOT1,1,1,"C",1);
$pdf->Output();
?>
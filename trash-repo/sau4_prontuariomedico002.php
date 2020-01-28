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

include("fpdf151/pdfwebseller2.php");
//include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_prontuariomedico_classe.php");
include("classes/db_cgs_und_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

set_time_limit(0);

$clprontuariomedico = new cl_prontuariomedico;
$clcgs_und = new cl_cgs_und;

$result = $clcgs_und->sql_record( $clcgs_und->sql_query( $cgs ) );

db_fieldsmemory($result,0);


$query = @pg_query($clprontuariomedico->sql_query("","*","sd32_d_atendimento desc ","sd32_i_numcgs = $cgs" ));
$linhas = @pg_num_rows($query);

if($linhas == 0){
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

$head3 = "Prontuário Médico";
$head4 = "Família...:".$sd33_v_descricao;
$head5 = "Micro Área:".$sd34_v_descricao;

//$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
$pri = true;

$ec = array("1"=>"Solteiro",
           "2"=>"Casado",
           "3"=>"Viúvo",
           "4"=>"Separado Judicialmente",
           "5"=>"União Consensual",
           "9"=>"Ignorado");
           
$sexo= array("F"=>"Feminino",
          "M"=>"Masculino");

for($x=0; $x < $linhas; $x++){
     db_fieldsmemory($query,$x);

     $z01_d_nasc = substr($z01_d_nasc,8,2)."/".substr($z01_d_nasc,5,2)."/".substr($z01_d_nasc,0,4);
     $sd32_d_atendimento = substr($sd32_d_atendimento,8,2)."/".substr($sd32_d_atendimento,5,2)."/".substr($sd32_d_atendimento,0,4);

     if (  ($pdf->gety() > $pdf->h -30) || $pri){
          $pdf->addpage();
          $pdf->header();
          $pdf->setfillcolor(235);
          $pdf->setfont('arial','b',12);
          $pdf->cell(192,8,"PRONTUÁRIO MÉDICO",0,1,"C",0);
          $pdf->setfont('arial','b',7);
          $pdf->cell(96,4,"Nome: ".$z01_i_numcgs."-".$z01_v_nome,1,0,"L",1);
          $pdf->cell(96,4,"Sexo:".$sexo[$z01_v_sexo],1,1,"L",1);
          $pdf->cell(96,4,"Data de Nasc.: ".$z01_d_nasc,1,0,"L",1);
          $pdf->cell(96,4,"Munic. Nasc: ".$z01_c_naturalidade,1,1,"L",1);
          $pdf->cell(96,4,"Nome do Pai: ".$z01_v_pai,1,0,"L",1);
          $pdf->cell(96,4,"Nome da Mãe: ".$z01_v_mae,1,1,"L",1);
          $pdf->cell(96,4,"Endereço: ".$z01_v_ender.", ".$z01_i_numero.", ".$z01_v_compl,1,0,"L",1);
          $pdf->cell(96,4,"Telefone: ".$z01_v_telef,1,1,"L",1);
          $pdf->cell(96,4,"Bairro: ".$z01_v_bairro,1,0,"L",1);
          $pdf->cell(96,4,"Estado Civil: ".$ec[ $z01_i_estciv ],1,1,"L",1);
          $pdf->cell(96,4,"Documento RG: ".$z01_v_ident,1,0,"L",1);
          $pdf->cell(96,4,"Cartão SUS: ".$z01_c_cartaosus,1,1,"L",1);
          $head1 = str_pad("Continuação", 50, " ", STR_PAD_LEFT);
          $head7 = "Nome: ".$z01_i_numcgs."-".$z01_v_nome;

          $pdf->cell(30,8,"IMUNIZAÇÃO: ",1,0,"L",0);
          $pdf->cell(162,8,"C.N.S.: ",1,1,"L",0);

          $pdf->cell(20,4,"DATA",1,0,"C",1);
          $pdf->cell(20,4,"HORA",1,0,"C",1);
          $pdf->cell(90,4,"CONSULTAS - VISITAS - EXAMES DE LABORATÓRIO - PRESCRIÇÕES",1,0,"C",1);
          $pdf->cell(45,4,"PROFISSIONAL",1,0,"C",1);
          $pdf->cell(17,4,"ASSINATURA ",1,1,"C",1);
          $pri = false;
     }

     $pdf->setfont('arial','',7);
     $pdf->SetWidths(array(20,20,90,45,17));
     $pdf->SetAligns(array("C","C","L","L","L"));
     $nbx="";
     $pdf->Row(array("$sd32_d_atendimento","$sd32_c_horaatend","$sd32_t_descricao","$z01_nome", "$nbx"), 4);
}


$pdf->Output();
?>
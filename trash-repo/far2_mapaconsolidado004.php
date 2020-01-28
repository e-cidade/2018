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
include("dbforms/db_funcoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_far_retiradaitens_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clfar_retiradaitens = new cl_far_retiradaitens;
$ano=date('Y');

$x=data_farmacia($ano,$lv);
$y= $x[0];
$y1=$x[1];

$result2 = $clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query_retiradaitens(null,"fa03_c_descr,descrdepto,z01_nome,far_retirada.*,fa06_i_codigo,fa06_i_retirada,m60_descr,m77_lote,m77_dtvalidade,m61_descr,case when fa09_f_quant is null then fa06_f_quant else fa09_f_quant end as fa09_f_quant","fa04_i_codigo desc"," fa04_d_data BETWEEN '$y' AND '$y1'"));
if($clfar_retiradaitens->numrows == 0){
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
   exit;
   }
db_fieldsmemory($result,0);	

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELAÇÃO MENSAL DE NOTIFICAÇÃO";
$head2 = "";

 $pdf->ln(5);
 $pdf->addpage('L');
 $total=0;
 $cont=0;
 $largura=$pdf->getX();
 $altura=$pdf->getY();
 $pdf->setfont('arial','b',8);
 $pdf->rect( 220, 35, 60, 60, "D");
 //$pdf->cell(35,25,"Carimbo do C.N.P.J.",0,1,"C",0);
 $pdf->setY(35);
 $pdf->setX(20);
 $pdf->cell(150,5,"SECRETARIA DE SAÚDE:________________________________",0,1,"C",0);
 $pdf->setY(45);
 $pdf->setX(20);
 $pdf->cell(150,2,"AUTORIDADE SANITÁRIA:________________________________",0,1,"C",0);
 $pdf->setY(50);
 $pdf->setX(51);
 $pdf->cell(167,4,"NOME DA UNIDADE DE SAÚDE:  ",0,1,"L",0);
 $pdf->line(98,53,210,53);
 $pdf->setY(55);
 $pdf->setX(30);
 $pdf->line(51,60,210,60);
 $pdf->setY(63);
 $pdf->setX(51);
 $pdf->cell(167,4,"CÓDIGO: ",0,1,"L",0);
 $pdf->line(65,66,210,66);
 $pdf->setY(70);
 $pdf->setX(51);
 $pdf->cell(167,4,"ENDEREÇO: ",0,1,"L",0);
 $pdf->line(70,73,210,73);
 $pdf->setY(75);
 $pdf->setX(30);
 $pdf->line(51,80,210,80);
 $pdf->setY(83);
 $pdf->setX(51);
 $pdf->cell(167,4,"MUNICÍPIO E UNIDADE FEDERAL: ",0,1,"L",0);
 $pdf->line(99,86,210,86);
 $pdf->setY(97);
 $pdf->setX(170);
 $pdf->cell(175,5,"EXERCÍCIO:__________________",0,1,"C",0);
 $pdf->setY(104);
 $pdf->setX(162);
 $pdf->cell(175,5,"PERÍODO TRIMESTRAL:".$y. 'A' .$y1,0,1,"C",0);
 $pdf->setfont('arial','b',10);
 $pdf->setY(90);
 $pdf->setX(51);
 $pdf->cell(167,5,"MAPA TRIMESTRAL DO CONSOLIDADO DAS",0,1,"C",0);
 $pdf->setY(95);
 $pdf->setX(51);
 $pdf->cell(167,5,"PRESCRIÇÕES DE MEDICAMENTOS SUJEITOS",0,1,"C",0);
 $pdf->setY(100);
 $pdf->setX(51);
 $pdf->cell(167,5,"A CONTROLE ESPECIAL - MCPM",0,1,"C",0);
 $pdf->setY(110);
 $pdf->setX(51);
 $pdf->cell(167,5,"TALIDOMIDA",0,1,"C",0);
 $pdf->setY(115);    
 $pdf->cell(20,4,"",1,0,"L",0);
 $pdf->cell(115,4,"N° DE ATENDIMENTOS",1,0,"L",0);
 $pdf->cell(140,4,"QUANTIDADE DE COMPRIMIDOS POR PROGRAMA",1,1,"L",0);
 $pdf->cell(20,4,"MESES",1,0,"L",0);
 $pdf->cell(55,4,"N° DE PACIENTES ATENDIDOS",1,0,"L",0);
 $pdf->cell(60,4,"N° DE NOTIFICAÇÕES ATENDIDAS",1,0,"L",0);
 $pdf->cell(25,4,"HANSENÍASE",1,0,"L",0);
 $pdf->cell(25,4,"AIDS",1,0,"L",0);
 $pdf->cell(65,4,"DOENÇA CRÔNICO DEGENERATIVA",1,0,"L",0);
 $pdf->cell(25,4,"TOTAL",1,1,"L",0);
 for($i=0; $i<12; $i++){
  $pdf->cell(20,4,"xxxxx",1,0,"L",0);
  $pdf->cell(55,4,"xxxxx",1,0,"L",0);
  $pdf->cell(60,4,"xxxxx",1,0,"L",0);
  $pdf->cell(25,4,"xxxxx",1,0,"L",0);
  $pdf->cell(25,4,"xxxxx",1,0,"L",0);
  $pdf->cell(65,4,"xxxxx",1,0,"L",0);
  $pdf->cell(25,4,"xxxxx",1,1,"L",0);
 }
 $pdf->setfont('arial','b',8);
 $pdf->setY(175); 
 $pdf->cell(100,4,"NOME/RG DO RESPONSÁVEL TÉCNICO: ",0,1,"L",0);
 $pdf->cell(70,4,"RECEBIDO POR :",0,0,"L",0);
 $pdf->cell(80,4,"RG : ",0,0,"L",0);
 $pdf->cell(90,4,"ÓRGÃO/SETOR :",0,0,"L",0);
 $pdf->cell(110,4,"DATA :",0,1,"L",0); 
 $pdf->cell(70,4,"CONFERIDO POR: ",0,0,"L",0);
 $pdf->cell(80,4,"RG :",0,0,"L",0);
 $pdf->cell(90,4,"ÓRGÃO/SETOR : ",0,0,"L",0);
 $pdf->cell(110,4,"DATA :",0,1,"L",0); 
$pdf->Output();
?>
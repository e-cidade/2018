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
include("classes/db_far_controle_classe.php");
include("classes/db_far_controlemed_classe.php");
include("classes/db_far_retiradaitens_classe.php");
include("classes/db_cgs_und_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clfar_controle = new cl_far_controle;
$clfar_controlemed = new cl_far_controlemed;
$clfar_retiradaitens = new cl_far_retiradaitens;
$clcgs_und = new cl_cgs_und;
$coddepto= db_getsession("DB_coddepto");
$descrdepto= db_getsession("DB_nomedepto");
$nome=db_getsession("DB_instit");
$ano=date("Y");
$hoje=date("Y-m-d",db_getsession("DB_datausu"));
$sqlpref  = "select nomeinst from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);



/*$result6=$clfar_controle->sql_record($clfar_controle->sql_query("","fa11_i_codigo","","fa04_d_data between '$fa10_d_dataini' and '$fa10_d_datafim' or fa10_d_dataini<= '$hoje' and fa10_d_datafim is null and fa04_i_cgsund=$fa11_i_cgsund")); 
if($clfar_controle->numrows>0){
   	  db_fieldsmemory($result6,0);
}*/

$result = $clfar_controlemed->sql_record($clfar_controlemed->sql_query("","*","","fa10_d_dataini<= '$hoje' and (fa10_d_datafim is null or fa10_d_datafim>= '$hoje') and fa11_i_cgsund=$fa11_i_cgsund"));
if($clfar_controlemed->numrows>0){
   	  db_fieldsmemory($result,0);
}		
//die($clfar_retiradaitens->sql_query_retiradaitens("","m61_descr","","fa04_d_data between '$fa10_d_dataini' and '$fa10_d_datafim' or fa10_d_dataini<= '$hoje' and fa10_d_datafim is null and fa04_i_cgsund=$fa11_i_cgsund"));
/*$result2=$clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query_retiradaitens("","m61_descr","","fa04_d_data between '$fa10_d_dataini' and '$fa10_d_datafim' and fa04_i_cgsund=$fa11_i_cgsund"));
if($clfar_retiradaitens->numrows>0){
   	db_fieldsmemory($result2,0);
}*/
/*$result8=$clcgs_und->sql_record($clcgs_und->sql_query("","z01_c_cartaosus","","z01_i_cgsund=$fa11_i_cgsund"));
if($clfar_retiradaitens->numrows>0){
   	db_fieldsmemory($result2,0);
}*/
if($clfar_controlemed->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Carteirinha de Medicamento";
$pdf->ln(5);
$pdf->addpage('P');
$cont=0;
$altura = $pdf->getY();
$largura = $pdf->getX();

$pdf->rect( $pdf->getX(), $pdf->getY()+10, 95, 45, "D");
$pdf->setfont('arial','b',7);
$pdf->text( $pdf->getX()+15, $pdf->getY()+13, $nomeinst);
$pdf->text( $pdf->getX()+25, $pdf->getY()+17,$descrdepto);
$pdf->setfont('arial','b',10);
$pdf->text( $pdf->getX()+22, $pdf->getY()+21, "Carteirinha de Medicamento");
$pdf->setfont('arial','',10);
$pdf->text( $pdf->getX()+35, $pdf->getY()+25, $ano);
$pdf->setfont('arial','b',7);
$pdf->text( $pdf->getX()+3, $pdf->getY()+30, "CGS:".$fa11_i_cgsund."-".$z01_v_nome);
$pdf->text( $pdf->getX()+3, $pdf->getY()+35, "Mãe:".$z01_v_mae);
$pdf->text( $pdf->getX()+3, $pdf->getY()+40, "Nasc:".db_formatar($z01_d_nasc,'d'));
$pdf->text( $pdf->getX()+60, $pdf->getY()+40, "Sexo:".$z01_v_sexo);
$pdf->text( $pdf->getX()+3, $pdf->getY()+45, "Cartão SUS:".$z01_c_cartaosus);
$pdf->text( $pdf->getX()+3, $pdf->getY()+50, "Data de Validade: 31/12/".$ano);

$pdf->rect( $pdf->getX()+95, $pdf->getY()+10, 95, 45, "D");
$pdf->setfont('arial','b',7);
$pdf->text( $pdf->getX()+125, $pdf->getY()+15, "Lista de Medicamento");
$pdf->text( $pdf->getX()+98, $pdf->getY()+18, "Cód.");
$pdf->text( $pdf->getX()+105, $pdf->getY()+18, "Descrição");
$pdf->text( $pdf->getX()+145, $pdf->getY()+18, "Qtde");
$pdf->text( $pdf->getX()+152, $pdf->getY()+18, "Unid.");
$pdf->text( $pdf->getX()+165, $pdf->getY()+18, "Freq.");
$pdf->text( $pdf->getX()+173, $pdf->getY()+18, "Programa");
$pdf->text( $pdf->getX()+98, $pdf->getY()+46, "Obs:");

$altura+=21;
for($s=0; $s < $clfar_controlemed->numrows; $s++){	
   	 db_fieldsmemory($result,$s);
	  if($cont==8){
	   //$pdf->ln(5);
       //$pdf->addpage('P');
	   $pdf->rect( $pdf->getX(), $pdf->getY()+80, 95, 45, "D");
       $pdf->setfont('arial','b',7);
       $pdf->text( $pdf->getX()+15, $pdf->getY()+83, $nomeinst);
       $pdf->text( $pdf->getX()+25, $pdf->getY()+87,$descrdepto);
       $pdf->setfont('arial','b',10);
       $pdf->text( $pdf->getX()+22, $pdf->getY()+91, "Carteirinha de Medicamento");
       $pdf->setfont('arial','',10);
       $pdf->text( $pdf->getX()+35, $pdf->getY()+95, $ano);
       $pdf->setfont('arial','b',7);
       $pdf->text( $pdf->getX()+3, $pdf->getY()+100, "CGS:".$fa11_i_cgsund."-".$z01_v_nome);
       $pdf->text( $pdf->getX()+3, $pdf->getY()+106, "Mãe:".$z01_v_mae);
       $pdf->text( $pdf->getX()+3, $pdf->getY()+110, "Nasc:".db_formatar($z01_d_nasc,'d'));
       $pdf->text( $pdf->getX()+60, $pdf->getY()+110, "Sexo:".$z01_v_sexo);
       $pdf->text( $pdf->getX()+3, $pdf->getY()+115, "Cartão SUS:".$z01_c_cartaosus);
       $pdf->text( $pdf->getX()+3, $pdf->getY()+120, "Data de Validade: 31/12/".$ano);
	   $pdf->rect( $pdf->getX()+95, $pdf->getY()+10, 95, 45, "D");
	   $pdf->setfont('arial','b',7);
	   $pdf->rect( $pdf->getX()+95, $pdf->getY()+80, 95, 45, "D");
       $pdf->text( $pdf->getX()+125, $pdf->getY()+85, "Continuação");
       $pdf->text( $pdf->getX()+98, $pdf->getY()+88, "Cód.");
       $pdf->text( $pdf->getX()+105, $pdf->getY()+88, "Descrição");
       $pdf->text( $pdf->getX()+145, $pdf->getY()+88, "Qtde");
       $pdf->text( $pdf->getX()+152, $pdf->getY()+88, "Unid.");
       $pdf->text( $pdf->getX()+165, $pdf->getY()+88, "Freq.");
       $pdf->text( $pdf->getX()+173, $pdf->getY()+88, "Programa");
       $pdf->text( $pdf->getX()+98, $pdf->getY()+116, "Obs:");
	   $altura+= 47;
       $cont=0;
	  }  
  $pdf->setfont('arial','',7);	  
  $pdf->text( $pdf->getX()+98,  $altura, $fa10_i_codigo);
  $pdf->text( $pdf->getX()+105, $altura,substr($m60_descr,0,26));
  $pdf->text( $pdf->getX()+145, $altura,$fa10_i_quantidade);
  $pdf->text( $pdf->getX()+152, $altura,substr($m61_descr,0,8));
  $pdf->text( $pdf->getX()+165, $altura,$fa10_i_prazo);
  $pdf->text( $pdf->getX()+173, $altura,substr($fa12_c_descricao,0,8));
  $cont++;
  $altura+=3;
} 	
  $altura=80; 
  $pdf->text( $pdf->getX()+105, $altura+1,substr($fa11_t_obs,0,53));
  $pdf->text( $pdf->getX()+105, $altura+4,substr($fa11_t_obs,53,55));
  if($cont==8){
  $altura+=70;
  $pdf->text( $pdf->getX()+105, $altura+1,substr($fa11_t_obs,0,53));
  $pdf->text( $pdf->getX()+105, $altura+4,substr($fa11_t_obs,53,55));
  }
$pdf->Output();
?>
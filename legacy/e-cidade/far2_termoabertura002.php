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
include("classes/db_far_farmacia_classe.php");
include("classes/db_far_modelolivro_classe.php");
include("classes/db_far_fechalivro_classe.php");
$fa13_i_departamento = db_getsession("DB_coddepto");
$clfar_farmacia = new cl_far_farmacia;
$clfar_modelolivro = new cl_far_modelolivro;
$clfar_fechalivro = new cl_far_fechalivro;
$sql = $clfar_farmacia->sql_query("","*",""," fa13_i_departamento=$fa13_i_departamento");
$result = $clfar_farmacia->sql_record($sql);
$sqlmodelivro = $clfar_modelolivro->sql_query("","*",""," fa16_i_codigo=$livro");
$resultmodelivro = $clfar_modelolivro->sql_record($sqlmodelivro);
if($clfar_modelolivro->numrows>0){
 db_fieldsmemory($resultmodelivro,0);
}
//die($clfar_fechalivro->sql_query("$fa26_i_codigo","*","",""));
$sql_fechalivro=$clfar_fechalivro->sql_query("$fa26_i_codigo","*","fa26_i_codigo desc","fa26_i_livro=$fa16_i_codigo");
$result_fechamento=$clfar_fechalivro->sql_record($sql_fechalivro);
if($clfar_fechalivro->numrows>0){
 db_fieldsmemory($result_fechamento,0);
}




//db_criatabela($result);
//exit;
if($clfar_farmacia->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
$dia=date("d");
 $mes=date("m");
 $ano=date("Y");
 switch ($mes){
case 1: $mes = "JANEIRO"; break;
case 2: $mes = "FEVEREIRO"; break;
case 3: $mes = "MARÇO"; break;
case 4: $mes = "ABRIL"; break;
case 5: $mes = "MAIO"; break;
case 6: $mes = "JUNHO"; break;
case 7: $mes = "JULHO"; break;
case 8: $mes = "AGOSTO"; break;
case 9: $mes = "SETEMBRO"; break;
case 10: $mes = "OUTUBRO"; break;
case 11: $mes = "NOVEMBRO"; break;
case 12: $mes = "DEZEMBRO"; break;

}

 
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
 db_fieldsmemory($result,0);
 $head1 = "TERMO DE ABERTURA / ENCERRAMENTO";
 $head2 = "UNIDADE : ".$fa13_i_departamento." - ".$descrdepto;
 $head3 = "DATA : ".db_formatar($fa26_d_dataini,'d')	."  Á  " . db_formatar($fa26_d_datafim,'d');
 $head4 = "HORA: ".$fa26_c_hora;
 $pdf->addpage();
 $pdf->ln(5);
 $texto = "                                                                                                                    Este livro contém ". $fa26_i_numpag. " folhas numeradas tipograficamente à 
                  máquina, servirá para o 
                  Registro: Livro: N°    ".$fa16_i_codigo.    "  Modelo :    ".$fa16_c_livro."
                  UPS:  ".$descrdepto.   "  
                  Farmácia:  ".$descrdepto."
                  Farmacêutico(a): ".$z01_nome."
                  Estabelecido em  : ".$z01_ender."                                            N°: " .$z01_numero."
                  Na cidade de : ". $munic."                                                                          Estado de : " .$z01_uf." 
                  Inscrição Estadual N°: ".$fa13_c_inscestadual."
                  Inscrição no Cadastro Geral de Contribuintes do Ministério da Fazenda , N°: ".$fa13_c_inscmf."
                  Data do Livro:  ".	db_formatar($fa26_d_dataini,'d')	."  Á  " . db_formatar($fa26_d_datafim,'d')  ;                           
 $pdf->setfont('arial','',12);
 $pdf->cell(190,15,"TERMO DE ABERTURA / ENCERRAMENTO",0,1,"C",0);
 $pdf->cell(190,20,"",0,1,"C",0);
 $pdf->setfont('arial','',9);
 $pdf->multicell(190,15,$texto,0,"J",0,0);
 $pdf->cell(190,10,"",0,1,"C",0);
 $pdf->cell(96,2,"$munic, $dia de $mes de $ano.",0,1,"C",0);
 $pdf->line(192,262,90,262);
 $pdf->cell(255,24,"(Assinatura e carimbo da Autoridade Sanitária)",0,1,"C",0);
$pdf->Output();
?>
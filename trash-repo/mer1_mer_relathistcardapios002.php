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

include("fpdf151/pdfwebseller.php");
include("libs/db_sql.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapioitem_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clrotulo           = new rotulocampo;
$clmer_cardapioitem = new cl_mer_cardapioitem;
$departamento       = db_getsession("DB_coddepto");
$descrdepto         = db_getsession("DB_nomedepto");
if ($opcao==1) {
	
  if ($periodo==1) {
  	
    $weeke=date("w", mktime(0,
                            0,
                            0,
                            date("m",db_getsession("DB_datasu")),
                            date("d",db_getsession("DB_datasu")),
                            date("Y",db_getsession("DB_datasu"))
                          ));
    $inicio=date("Y-m-d",mktime(0,
                                0,
                                0,
                                date("m",db_getsession("DB_datasu")),
                                date("d",db_getsession("DB_datasu"))+(2-($weeke+1)),
                                date("y",db_getsession("DB_datasu"))
                              ));
    $fim=date("Y-m-d",mktime(0,
                             0,
                             0,
                             date("m",db_getsession("DB_datasu")),
                             date("d",db_getsession("DB_datasu"))+(6-($weeke+1)),
                             date("y",db_getsession("DB_datasu"))
                           ));
    
  } else {
  	
    $ano    = date("Y",db_getsession("DB_datasu"));
    $mes    = date("m",db_getsession("DB_datasu"));
    $inicio = $ano."-".$mes."-01";
    $fim    = date("Y/m/t", mktime(0, 0, 0, $mes, 1, $ano));
    
  }  
}
$result2 = $clmer_cardapioitem->sql_record($clmer_cardapioitem->sql_query_relatorio(null,
                                                                                    "mer_cardapio.*,
                                                                                    me07_f_quantidade,
                                                                                    me07_i_codigo,
                                                                                    me13_d_data,
                                                                                    m60_descr",
                                                                                    "me01_i_codigo desc",
                                                                                    "me07_i_cardapio=me01_i_codigo  
                                                                                     AND me13_d_data BETWEEN '$inicio' 
                                                                                     AND '$fim'"
                                                                                   ));
if ($clmer_cardapioitem->numrows == 0) {?>

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
db_fieldsmemory($result,0);	

$inicio = db_formatar($inicio,'d');
$fim    = db_formatar($fim,'d');
$pdf    = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1  = "RELATÓRIO DE CARDÁPIOS";
$head2  = "Lista de Cardapios";
$head3  = "Período: ".$inicio." à ".$fim;
$pdf->ln(5);
$pdf->addpage('L');
$total  = 0;
$cont   = 0;
$d      = 0;
$pdf->setfont('arial','b',10);
$pdf->cell(35,4,"Código ",0,0,"L",0);
$pdf->cell(60,4,"Cardápio ",0,0,"L",0);
$pdf->cell(60,4,"Departamento ",0,0,"L",0);
$pdf->cell(30,4,"Versão ",0,0,"L",0);
$pdf->cell(35,4,"Data ",0,0,"L",0);
$pdf->cell(25,4,"Identificação Cardápio ",0,1,"L",0);
$pdf->cell(5,5,"",0,0,"L",0);
$pdf->cell(5,5,"",0,0,"L",0);
$pdf->cell(5,5,"",0,0,"L",0);
$pdf->cell(30,5,"Código ",0,0,"L",0);
$pdf->cell(10,5,"",0,0,"L",0);
$pdf->cell(70,5,"Item ",0,0,"L",0);
$pdf->cell(30,5,"Quantidade ",0,1,"L",0);
for ($s=0; $s < $clmer_cardapioitem->numrows; $s++) {
	
  db_fieldsmemory($result2,$s);	  
  if ($cont==12) {
  	
    $pdf->ln(5);
    $pdf->addpage('L');
    $pdf->setfont('arial','b',10);
    $pdf->cell(35,4,"Código ",0,0,"L",0);
    $pdf->cell(60,4,"Cardápio ",0,0,"L",0);
    $pdf->cell(60,4,"Departamento ",0,0,"L",0);
    $pdf->cell(30,4,"Versão ",0,0,"L",0);
    $pdf->cell(35,4,"Data ",0,0,"L",0);
    $pdf->cell(25,4,"Identificação Cardápio ",0,1,"L",0);
    $pdf->cell(5,5,"",0,0,"L",0);
    $pdf->cell(5,5,"",0,0,"L",0);
    $pdf->cell(5,5,"",0,0,"L",0);
    $pdf->cell(30,5,"Código ",0,0,"L",0);
    $pdf->cell(10,5,"",0,0,"L",0);
    $pdf->cell(70,5,"Item ",0,0,"L",0);
    $pdf->cell(30,5,"Quantidade ",0,1,"L",0);
	$cont=0;
	$pdf->setfont('arial','',8);
	
  } 
  if ($d != $me01_i_codigo) {
  	 	
    $pdf->setfont('arial','',8);
	$pdf->setfillcolor(240);
    $pdf->cell(35,4,"$me01_i_codigo",0,0,"L",1);
    $pdf->cell(60,4,substr($me01_c_nome,0,40),0,0,"L",1);
    $pdf->cell(65,4,substr($departamento."-" .$descrdepto,0,80),0,0,"L",1);
    $pdf->cell(25,4,"$me01_f_versao",0,0,"L",1);
    $pdf->cell(35,4,db_formatar($me13_d_data,'d'),0,0,"L",1);
    $pdf->cell(40,4,"$me01_i_id",0,1,"L",1);
	$d = $me01_i_codigo; 
	$total +=1;
	
  }
	 
  $pdf->cell(5,5,"",0,0,"L",0);
  $pdf->cell(5,5,"",0,0,"L",0);
  $pdf->cell(5,5,"",0,0,"L",0);
  $pdf->cell(40,6,"$me07_i_codigo",0,0,"L",0);
  $pdf->cell(80,6,substr($m60_descr,0,40),0,0,"L",0);
  $pdf->cell(20,6,"$me07_f_quantidade",0,1,"L",0);
  $cont++;
  
}   
$pdf->cell(70,4,"",0,0,"C",0); 
$pdf->line(10,$pdf->getY(),285,$pdf->getY());
$pdf->cell(320,4,"TOTAL DE REGISTROS :" .$total,0,1,"C",0);
$pdf->Output();
?>
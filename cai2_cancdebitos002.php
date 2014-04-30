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
include("classes/db_cancdebitos_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcancdebitos = new cl_cancdebitos;
$clcancdebitos->rotulo->label();
$debitos = str_replace("X",",",$lista);
 $sql = "SELECT k21_numpre,
                k21_numpar,
		to_char(k23_data,'dd/mm/yyyy') as k23_data,
                k24_vlrhis,
		k24_vlrcor,
		k24_juros,
		k24_multa,
		k24_desconto,
		nome,
                to_char(k23_data,'dd/mm/yyyy') as k23_data
           FROM cancdebitosreg
	  INNER JOIN cancdebitosproc on cancdebitosproc.k23_codigo = cancdebitosreg.k21_sequencia 
	  INNER JOIN cancdebitosprocreg on cancdebitosprocreg.k24_codigo = cancdebitosproc.k23_codigo
	  INNER JOIN db_usuarios     on cancdebitosproc.k23_usuario = db_usuarios.id_usuario
	  INNER JOIN arrecant        on k00_numpre = k21_numpre and k00_numpar = k21_numpar
          WHERE cancdebitosproc.k23_data BETWEEN '$dat1' AND '$dat2' ";
  if($debitos!= ""){
   if($ver == "sem"){
     $sql.=" AND k00_tipo NOT IN($debitos) ";
   }else{
     $sql.=" AND k00_tipo IN($debitos) ";
   }
  }
  if($usu != ""){
    $sql.= " AND k23_usuario = $usu ";
  }
  $sql .= " ORDER BY cancdebitosreg.k21_numpre, cancdebitosreg.k21_numpar ";
//echo $sql; exit;  
  $result = $clcancdebitos->sql_record($sql);
   if($clcancdebitos->numrows == 0){
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
$head2 = "Relatório dos Débitos Cancelados";
$head3 = "Periodo:".$dat1." à ".$dat2;
$pri = true;
$p = 0;
 for($x=0; $x < $clcancdebitos->numrows; $x++){
  db_fieldsmemory($result,$x);
  
  //multa
  // "select fc_multa(".$k00_receit.",'".$k00_dtvenc."','".$k00_dtoper."','".$k00_dtvenc."',".db_getsession('DB_anousu').") as multa";
  
  if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
   $pdf->addpage();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',7);
   $pdf->cell(15,4,"Numpre",1,0,"C",1);
   $pdf->cell(10,4,"Parcela",1,0,"C",1);
   $pdf->cell(60,4,"Usuário",1,0,"C",1);
   $pdf->cell(15,4,"Data",1,0,"C",1);
   $pdf->cell(10,4,"Juro",1,0,"C",1);
   $pdf->cell(10,4,"Multa",1,0,"C",1);
   $pdf->cell(18,4,"Hist",1,0,"C",1);
   $pdf->cell(18,4,"Corrig.",1,0,"C",1);
   $pdf->cell(18,4,"Total",1,1,"C",1);
   $pri = false;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(15,4,$k21_numpre,0,0,"C",$p);
  $pdf->cell(10,4,$k21_numpar,0,0,"L",$p);
  $pdf->cell(60,4,$nome,0,0,"C",$p);
  $pdf->cell(15,4,$k23_data,0,0,"C",$p);
  $pdf->cell(10,4,$k24_juros,0,0,"L",$p);
  $pdf->cell(10,4,$k24_multa,0,0,"L",$p);
  $pdf->cell(18,4,$k24_vlrhis,0,0,"L",$p);
  $pdf->cell(18,4,$k24_vlrcor,0,0,"L",$p);
  $pdf->cell(18,4,$k24_juros+$k24_multa+$k24_vlrhis,0,1,"L",$p);
  if($p == 0){
   $p = 1;
  }else{
   $p = 0;
  }
 }
 $pdf->Output();
?>
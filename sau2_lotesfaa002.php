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
include("libs/db_utils.php");
include("libs/db_stdlibwebseller.php");

include("classes/db_sau_lotepront_ext_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
set_time_limit(0);

$clsau_lotepront = new cl_sau_lotepront_ext;

$res_sau_lotepront = $clsau_lotepront->sql_record( $clsau_lotepront->sql_query_ext(null, "*", "sd59_i_lote, sd59_i_prontuario", "sd59_i_lote in ($lotesfaa)")) ;

if($clsau_lotepront->numrows == 0){
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
$head2 = "Relatório de Lotes FAA";
$pdf->addpage();

$loteant = 0;
for( $i=0; $i < $clsau_lotepront->numrows; $i++ ){
	$obj_sau_lotepront = db_utils::fieldsMemory($res_sau_lotepront, $i);
	
	if( $loteant != $obj_sau_lotepront->sd59_i_lote){
		$loteant = $obj_sau_lotepront->sd59_i_lote;
		$dia = substr($obj_sau_lotepront->sd58_d_data,8,2);
		$mes = substr($obj_sau_lotepront->sd58_d_data,5,2);
		$ano = substr($obj_sau_lotepront->sd58_d_data,0,4);
		$hora = substr($obj_sau_lotepront->sd58_c_hora, 0, 8);
		
		$pdf->setfont('arial','b',10);
		$pdf->cell(20,4,"Lote",1,0,"C",0);
		$pdf->cell(20,4,"Login",1,0,"C",0);
		$pdf->cell(20,4,"Data",1,0,"C",0);
		$pdf->cell(20,4,"Hora",1,0,"C",0);		
		$pdf->cell(20,4,"FAA's",1,0,"C",0);
		$pdf->cell(80,4,"Paciente",1,1,"C",0);
		
		$pdf->setfont('arial','',10);		
		$pdf->cell(20,4,"{$obj_sau_lotepront->sd59_i_lote}",1,0,"C",0);
		$pdf->cell(20,4,"{$obj_sau_lotepront->login}",1,0,"C",0);
		$pdf->cell(20,4,"$dia/$mes/$ano",1,0,"C",0);		
		$pdf->cell(20,4,"$hora",1,0,"C",0);		
	}else{
		//$pdf->cell(0,4,"",0,1,"L",0);
		$pdf->cell(80,4,"","L",0,"L",0);
	}
	$pdf->cell(20,4,"{$obj_sau_lotepront->sd59_i_prontuario}",1,0,"C",0);
	$pdf->cell(80,4,"{$obj_sau_lotepront->z01_v_nome}",1,1,"L",0);
	
	
}

$pdf->Output();
?>
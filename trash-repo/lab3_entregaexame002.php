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

//modulo farmacia
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_lab_entrega_classe.php");

$cllab_entrega = new cl_lab_entrega;
$cllab_entrega->rotulo->label();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br>
<center>

<form name="formconsulta">
	<input name="dataini" value="<?=@$dataini?>" type="hidden">
	<input name="datafim" value="<?=@$datafim?>" type="hidden">
	<input name="laboratorio" value="<?=@$laboratorio?>" type="hidden">
	<input name="setor" value="<?=@$setor?>" type="hidden">
	<input name="exame" value="<?=@$exame?>" type="hidden">
	<input name="requisicao" value="<?=@$requisicao?>" type="hidden">
	<input name="labsetor" value="<?=@$labsetor?>" type="hidden">	

</form>
<table>
 <tr>
  <td align="center" valign="top">
  	
<?
   if(isset($consultar)){
     $campos = "la22_i_codigo as dl_Requisição,
                 la22_d_data as dl_Data_Requisição,
                 la22_i_cgs as dl_CGS_Paciente,
                 a.z01_v_nome as dl_Paciente,
                 la31_i_cgs as dl_Código_Retirado_por,
                 cgs_und.z01_v_nome as dl_Retirado_por,
                 la08_c_descr as dl_Exame,
                 la15_c_descr as dl_Material,
                 la33_c_descr as dl_Tipo_Documento,
                 la31_c_documento as dl_Número_Documento,
                 la32_d_data as dl_Coleta,
                 la31_d_data as dl_Entrega,
                 la31_c_hora as dl_Hora_Entrega,
                 la31_i_usuario as dl_Código_Usuario,
                 nome as dl_Login
              ";
    $where = "";
    if(isset($dataini) && $datafim!=""){
      @$d1= substr(@$dataini,6,4)."-".substr(@$dataini,3,2)."-".substr(@$dataini,0,2);
      @$d2= substr(@$datafim,6,4)."-".substr(@$datafim,3,2)."-".substr(@$datafim,0,2);
      $where = " la31_d_data between '$d1' and '$d2'";
  
    }
    if(isset($laboratorio) && $laboratorio!=""){
     $where .= " and la02_i_codigo=$laboratorio";
  
    }
    if(isset($labsetor) && $labsetor!=""){
     $where .= " and la24_i_codigo=$labsetor";
  
    }
    if(isset($exame) && $exame!=""){
     $where .= " and la08_i_codigo=$exame";
  
    }
    if(isset($requisicao) && $requisicao!=""){
     $where .= " and la22_i_codigo=$requisicao";
    }
     
    $sql = $cllab_entrega->sql_query_consulta(""," distinct ".$campos,"",$where);
   if(isset($consultar)){
    $repassa = array("chave_la02_i_codigo"=>@$chave_la02_i_codigo);
   }
   db_lovrot($sql,25,"","","","","NoMe",$repassa);
    
   }
   ?>
    </td>
  </tr>
</table>
  <center>
<table>
   <tr>
    <td>
       <input name="imprimir" type="submit" id="imprimir" value="Imprimir" onclick='js_imprimir();'>       
  </td>
 </tr>
</table>
</center>
<script>
function js_imprimir(){

		  sDataini = 'dataini='+document.formconsulta.dataini.value;
		  sDatafim = '&datafim='+document.formconsulta.datafim.value;
		  iLaboratorio = '&laboratorio='+document.formconsulta.laboratorio.value;
		  iLabsetor = '&labsetor='+document.formconsulta.setor.value;		  
		  iExame = '&exame='+document.formconsulta.exame.value;
		  iRequisicao = '&requisicao='+document.formconsulta.requisicao.value;
		  
		  jan = window.open('lab2_entrega001.php?'+sDataini+sDatafim+iLaboratorio+iExame+iLabsetor+iRequisicao,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		  jan.moveTo(0,0);

}
</script>
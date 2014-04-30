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
//MODULO: material
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veicretirada_classe.php");
include("classes/db_veicdevolucao_classe.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veictipoabast_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveicretirada  = new cl_veicretirada;
$clveicdevolucao = new cl_veicdevolucao;
$clveiculos      = new cl_veiculos;
$clveictipoabast = new cl_veictipoabast;

$clveicretirada->rotulo->label();
$clveicdevolucao->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ve60_codigo");
$clrotulo->label("ve60_veiculo");
$clrotulo->label("ve01_codigo");
$clrotulo->label("ve01_placa");
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("ve01_codigo");
$clrotulo->label("ve01_placa");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
$clrotulo->label("nome");

if (isset($codigo)&&$codigo!=""){
  $result = $clveicretirada->sql_record($clveicretirada->sql_query($codigo)); 
  db_fieldsmemory($result,0);

  $result = $clveiculos->sql_record($clveiculos->sql_query($ve60_veiculo,"ve01_veictipoabast"));
  db_fieldsmemory($result,0);

  $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,"ve07_sigla"));
  if ($clveictipoabast->numrows > 0){
    db_fieldsmemory($result_veictipoabast,0);
  } 
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
<style>
<?//$cor="#999999"?>
.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr> 
<td  align="center" valign="top" > 
  <form name="form1" method="post" action="">
  <center>
  <fieldset>
      		<legend><strong>DADOS RETIRADA</strong></legend>
  <table border="0">
    <tr>
    <td nowrap title="<?=@$Tve60_codigo?>">
       <?=@$Lve60_codigo?>
    </td>
    <td> 
<?
db_input('ve60_codigo',10,$Ive60_codigo,true,'text',3,"")
?>
    </td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve60_veiculo?>">
       <?=@$Lve60_veiculo?>
    </td>
    <td> 
<?
db_input('ve60_veiculo',10,$Ive60_veiculo,true,'text',3,"")
?>
       <?
db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
       ?>
    </td>
  
    <td nowrap title="<?=@$Tve60_veicmotoristas?>">
       <?=@$Lve60_veicmotoristas?>
    </td>
    <td> 
<?
db_input('ve60_veicmotoristas',10,$Ive60_veicmotoristas,true,'text',3,"")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve60_datasaida?>">
       <?=@$Lve60_datasaida?>
    </td>
    <td> 
<?
db_inputdata('ve60_datasaida',@$ve60_datasaida_dia,@$ve60_datasaida_mes,@$ve60_datasaida_ano,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve60_horasaida?>">
       <?=@$Lve60_horasaida?>
    </td>
    <td> 
<?
db_input('ve60_horasaida',5,$Ive60_horasaida,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve60_medidasaida?>">
       <?=@$Lve60_medidasaida?>
    </td>
    <td> 
<?
db_input('ve60_medidasaida',15,$Ive60_medidasaida,true,'text',3,"");
if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
  db_input("ve07_sigla",3,0,true,"text",3);
}
?>
    </td>
  
    <td nowrap title="<?=@$Tve60_destino?>">
       <?=@$Lve60_destino?>
    </td>
    <td> 
<?
db_input('ve60_destino',40,$Ive60_destino,true,'text',3,"")
?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tve60_coddepto?>">
       <?=@$Lve60_coddepto?>
    </td>
    <td> 
<?
db_input('ve60_coddepto',5,$Ive60_coddepto,true,'text',3,"")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  
    <td nowrap title="<?=@$Tve60_usuario?>">
       <?=@$Lve60_usuario?>
    </td>
    <td> 
<?
db_input('ve60_usuario',10,$Ive60_usuario,true,'text',3,"")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve60_data?>">
       <?=@$Lve60_data?>
    </td>
    <td> 
<?
db_inputdata('ve60_data',@$ve60_data_dia,@$ve60_data_mes,@$ve60_data_ano,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve60_hora?>">
       <?=@$Lve60_hora?>
    </td>
    <td> 
<?
db_input('ve60_hora',5,$Ive60_hora,true,'text',3,"")
?>
    </td>
  </tr>
  
 </table>
 </fieldset>
  <fieldset>
      		<legend><strong>DADOS DEVOLUÇÃO</strong></legend>
  <table border="0">
<?

$result_dev = $clveicdevolucao->sql_record($clveicdevolucao->sql_query(null,"*",null,"ve61_veicretirada=$codigo"));
if ($clveicdevolucao->numrows>0){ 
   db_fieldsmemory($result_dev,0);
?>
  
   <tr>
    <td nowrap title="<?=@$Tve61_codigo?>">
       <?=@$Lve61_codigo?>
    </td>
    <td> 
<?
db_input('ve61_codigo',10,$Ive61_codigo,true,'text',3,"")
?>
    </td>
    <td></td>
    <td></td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tve60_veiculo?>">
       <?=@$Lve60_veiculo?>
    </td>
    <td> 
<?
db_input('ve60_veiculo',10,$Ive60_veiculo,true,'text',3,"")
?>
       <?
db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
       ?>
    </td>
    <td nowrap title="<?=@$Tve61_veicmotoristas?>">
       <?=@$Lve61_veicmotoristas?>
    </td>
    <td> 
<?
db_input('ve61_veicmotoristas',10,$Ive61_veicmotoristas,true,'text',3,"")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve61_datadevol?>">
       <?=@$Lve61_datadevol?>
    </td>
    <td> 
<?
db_inputdata('ve61_datadevol',@$ve61_datadevol_dia,@$ve61_datadevol_mes,@$ve61_datadevol_ano,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve61_horadevol?>">
       <?=@$Lve61_horadevol?>
    </td>
    <td> 
<?
db_input('ve61_horadevol',5,$Ive61_horadevol,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve61_medidadevol?>">
       <?=@$Lve61_medidadevol?>
    </td>
    <td> 
<?
db_input('ve61_medidadevol',15,$Ive61_medidadevol,true,'text',3,"");
if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
  db_input("ve07_sigla",3,0,true,"text",3);
}
?>
    </td>
  
    <td nowrap title="<?=@$Tve61_usuario?>">
       <?=@$Lve61_usuario?>
    </td>
    <td> 
<?
db_input('ve61_usuario',10,$Ive61_usuario,true,'text',3,"")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve61_data?>">
       <?=@$Lve61_data?>
    </td>
    <td> 
<?
db_inputdata('ve61_data',@$ve61_data_dia,@$ve61_data_mes,@$ve61_data_ano,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve61_hora?>">
       <?=@$Lve61_hora?>
    </td>
    <td> 
<?
db_input('ve61_hora',5,$Ive61_hora,true,'text',3,"")
?>
    </td>
  </tr>
   <?
}else{
	?>
	<tr>
	<td><b>Veiculo não foi devolvido.</b></td>
	</tr>
	<?
}
   ?>
 </table>
 </fieldset>
   </center>
</form>
</td>
</tr>
</table>
<script>
</script>
</body>
</html>
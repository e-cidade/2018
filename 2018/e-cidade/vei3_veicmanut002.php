<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_veicmanut_classe.php");
include("classes/db_veicmanutitem_classe.php");
include("classes/db_veicmanutoficina_classe.php");
include("classes/db_veicmanutretirada_classe.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veictipoabast_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveicmanut         = new cl_veicmanut;
$clveicmanutoficina  = new cl_veicmanutoficina;
$clveicmanutretirada = new cl_veicmanutretirada;
$clveiculos          = new cl_veiculos;
$clveictipoabast     = new cl_veictipoabast;

$clveicmanut->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve28_descr");
$clrotulo->label("ve01_placa");
$clrotulo->label("ve65_veicretirada");
$clrotulo->label("ve60_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("ve66_veiccadoficinas");
$clrotulo->label("ve62_observacao");

if (isset($codigo)&&$codigo!=""){
  $result = $clveicmanut->sql_record($clveicmanut->sql_query($codigo)); 
  db_fieldsmemory($result,0);

  $result_oficina=$clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null,"*",null,"ve66_veicmanut=$codigo"));
  if ($clveicmanutoficina->numrows>0){
  	db_fieldsmemory($result_oficina,0);
  }

  $result_retirada=$clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null,"*",null,"ve65_veicmanut=$codigo"));
  if ($clveicmanutretirada->numrows>0){
   	db_fieldsmemory($result_retirada,0);
  }
   
  $result = $clveiculos->sql_record($clveiculos->sql_query($ve62_veiculos,"ve01_veictipoabast"));
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
      		<legend><strong>DADOS MANUTENÇÃO</strong></legend>
  <table border="0">
    <tr>
    <td nowrap title="<?=@$Tve62_codigo?>">
       <?=@$Lve62_codigo?>
    </td>
    <td> 
<?
db_input('ve62_codigo',10,$Ive62_codigo,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve62_veiculos?>">
       <?=@$Lve62_veiculos?>
    </td>
    <td> 
<?
db_input('ve62_veiculos',10,$Ive62_veiculos,true,'text',3,"")
?>
       <?
db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve62_dtmanut?>">
       <?=@$Lve62_dtmanut?>
    </td>
    <td> 
<?
db_inputdata('ve62_dtmanut',@$ve62_dtmanut_dia,@$ve62_dtmanut_mes,@$ve62_dtmanut_ano,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve62_vlrmobra?>">
       <?=@$Lve62_vlrmobra?>
    </td>
    <td> 
<?
db_input('ve62_vlrmobra',15,$Ive62_vlrmobra,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve62_vlrpecas?>">
       <?=@$Lve62_vlrpecas?>
    </td>
    <td> 
<?
db_input('ve62_vlrpecas',15,$Ive62_vlrpecas,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve62_descr?>">
       <?=@$Lve62_descr?>
    </td>
    <td> 
<?
db_input('ve62_descr',60,$Ive62_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve62_notafisc?>">
       <?=@$Lve62_notafisc?>
    </td>
    <td> 
<?
db_input('ve62_notafisc',10,$Ive62_notafisc,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve62_medida?>">
       <?=@$Lve62_medida?>
    </td>
    <td> 
<?
db_input('ve62_medida',15,$Ive62_medida,true,'text',3,"");
if (isset($ve07_sigla) && trim($ve07_sigla)!=""){
  db_input("ve07_sigla",3,0,true,"text",3);
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve62_veiccadtiposervico?>">
       <?=@$Lve62_veiccadtiposervico?>
    </td>
    <td> 
<?
db_input('ve62_veiccadtiposervico',10,$Ive62_veiccadtiposervico,true,'text',3,"")
?>
       <?
db_input('ve28_descr',40,$Ive28_descr,true,'text',3,'')
       ?>
    </td>
  
    <td nowrap title="<?=@$Tve66_veiccadoficinas?>">
       <?=@$Lve66_veiccadoficinas?>
    </td>
    <td> 
<?
db_input('ve66_veiccadoficinas',10,$Ive66_veiccadoficinas,true,'text',3,"")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>  
<tr>
    <td nowrap title="<?=@$Tve65_veicretirada?>">
       <?=@$Lve65_veicretirada?>
    </td>
    <td> 
<?
db_input('ve65_veicretirada',10,$Ive65_veicretirada,true,'text',3,"")
?>
       <?
db_input('ve60_codigo',10,$Ive60_codigo,true,'hidden',3,'')
       ?>
    </td>
    <td nowrap title="<?=@$Tve62_usuario?>">
       <?=@$Lve62_usuario?>
    </td>
    <td> 
<?
db_input('ve62_usuario',10,$Ive62_usuario,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve62_data?>">
       <?=@$Lve62_data?>
    </td>
    <td> 
<?
db_inputdata('ve62_data',@$ve62_data_dia,@$ve62_data_mes,@$ve62_data_ano,true,'text',3,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tve62_hora?>">
       <?=@$Lve62_hora?>
    </td>
    <td> 
<?
db_input('ve62_hora',5,$Ive62_hora,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
  	<td nowrap title="<?=@$Tve62_observacao?>">
  	  <?=@$Lve62_observacao?>
  	</td>
  	<td>
  		<?php db_textarea('ve62_observacao', 5, 50, $Ive62_observacao, true, 'text', 3); ?>
  	</td>
  </tr>
  
  
 </table>
 </fieldset>
 <fieldset>
      		<legend><strong>DADOS ITENS</strong></legend>
 <table>
    <tr>
      <td align=center>
       <iframe name="itens" id="itens" src="vei3_veicmanutitem001.php?codigo=<?=$codigo?>" width="720" height="150" marginwidth="0" marginheight="0" frameborder="0">
       </iframe>
       <br>
       <br>

      </td>
    </tr>
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
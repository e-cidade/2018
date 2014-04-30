<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgs_und_ext_classe.php");

db_postmemory($HTTP_POST_VARS);


if(!isset($pesquisar))
	//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
	// quando vinha um valor por get não mudava quando alterava o valor do nome
	
if(isset($alterar_cgs)){
	parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
	?>
	<script>
		location.href ="sau1_cgs_und002.php?chavepesquisa=<?=$chave_z01_i_cgsund?>";
	</script>
	<?
}

$clcgs_und = new cl_cgs_und_ext;
$clrotulo = new rotulocampo;
$clcgs_und->rotulo->label("z01_i_cgsund");
$clcgs_und->rotulo->label("z01_v_nome");
$clcgs_und->rotulo->label("z01_v_cgccpf");
$clcgs_und->rotulo->label("z01_v_ident");
$clrotulo->label("DBtxt30");
$clrotulo->label("DBtxt31");
$clrotulo->label("s115_c_cartaosus");


//die($redireciona);
?>

<script>
function js_novo_cgs(){
	location.href ="sau1_cgs_und000.php?+id=1+&db_menu=false&retornacgs=<?=@$retornacgs?>&retornanome=<?=@$retornanome?>&redireciona=<?=@$redireciona?>";
}
function js_altera_cgs(){
//	location.href ="sau1_cgs_und000.php?+id=2+&db_menu=false&fechar=true&retornacgs=<?=@$retornacgs?>&retornanome=<?=@$retornanome?>&redireciona=<?=@$redireciona?>";
  location.href ="sau1_cgs_und002.php?retornacgs=<?@$retornacgs?>&retornanome=<?=@$retornanome?>&redireciona=<?=@$redireciona?>";
}
</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
 team = new Array(
 <?
 	# Seleciona todos os calendários
 	$sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
 				FROM microarea
 				ORDER BY sd34_v_descricao";
 	$sql_result = db_query($sql1);
 	$num = pg_num_rows($sql_result);
 	$conta = "";
 	while ($row=pg_fetch_array($sql_result)){
 		$conta = $conta+1;
 		$cod_micro = $row["sd34_i_codigo"];
 		echo "new Array(\n";
 		$sub_sql = "SELECT sd35_i_codigo,sd33_v_descricao
 					FROM familiamicroarea
 					inner join familia on sd33_i_codigo = sd35_i_familia
 					WHERE sd35_i_microarea = '$cod_micro'
 					ORDER BY sd33_v_descricao ";
 		$sub_result = db_query($sub_sql);
 		$num_sub = pg_num_rows($sub_result);
 		if ($num_sub>=1){
 			echo "new Array(\"\", ''),\n";
 			$conta_sub = "";
 			while ($rowx=pg_fetch_array($sub_result)){
 				$codigo_fam=$rowx["sd35_i_codigo"];
 				$nome_fam=$rowx["sd33_v_descricao"];
 				$conta_sub=$conta_sub+1;
 				if ($conta_sub==$num_sub){
 					echo "new Array(\"$nome_fam\", $codigo_fam)\n";
 					$conta_sub = "";
 				}else{
 					echo "new Array(\"$nome_fam\", $codigo_fam),\n";
 				}
 			}
 		}else{
 			echo "new Array(\"Microarea sem familias cadastradas.\", '')\n";
 		}
 		if ($num>$conta){
 			echo "),\n";
 		}
 	}
 	echo ")\n";
 	echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
 var i, j;
 var prompt;
 // empty existing items
 for (i = selectCtrl.options.length; i >= 0; i--) {
  selectCtrl.options[i] = null;
 }
 prompt = (itemArray != null) ? goodPrompt : badPrompt;
 if (prompt == null) {
  selectCtrl.options[0] = new Option('','');
  j = 0;
 }else{
  selectCtrl.options[0] = new Option(prompt);
  j = 1;
 }
 if (itemArray != null) {
  // add new items
  for (i = 0; i < itemArray.length; i++){
   selectCtrl.options[j] = new Option(itemArray[i][0]);
   if (itemArray[i][1] != null){
    selectCtrl.options[j].value = itemArray[i][1];
   }
   <?if(isset($chave_z01_i_familiamicroarea)&&$chave_z01_i_familiamicroarea!=""){?>
    if(<?=$chave_z01_i_familiamicroarea?>==itemArray[i][1]){
     indice = i;
    }
   <?}?>
   j++;
  }
  <?if(isset($chave_z01_i_familiamicroarea)&&$chave_z01_i_familiamicroarea!=""){?>
   selectCtrl.options[indice].selected = true;
  <?}else{?>
   selectCtrl.options[0].selected = true;
  <?}?>
 }
}

</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
    <form name="form2" method="post" action="" >
   <table width="100%" border="0" align="center" cellspacing="0">
    <tr>
     <td>
      <b>CGS:</b>&nbsp;&nbsp; <?db_input('z01_i_cgsund',6,$Iz01_i_cgsund,true,'text',4,"","chave_z01_i_cgsund");?><br>
      <b>Nasc:</b> <?db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',4,"",'chave_z01_d_nasc');?>
      <b>Nome:</b> <?db_input('z01_v_nome',30,$Iz01_v_nome,true,'text',4,"onblur='js_nome(this)'",'chave_z01_v_nome');?>
     </td>
     <td>
      <b>Identidade:</b>&nbsp;&nbsp; <?db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',1,"","chave_z01_v_ident");?><br>
      <b>Cartão SUS:</b> <?db_input('s115_c_cartaosus',15,@$Is115_c_cartaosus,true,'text',4,"",'chave_s115_c_cartaosus');?>
     </td>
     <td>
      <b>Micro:</b>&nbsp;&nbsp;&nbsp;
      <select name="chave_z01_v_micro" onChange="fillSelectFromArray(this.form.chave_z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       $sql1 = "SELECT sd34_i_codigo,sd34_v_descricao
               FROM microarea
               ORDER BY sd34_v_descricao";
       $sql_result = db_query($sql1);
       while($row=pg_fetch_array($sql_result)){
        $cod_micro=$row["sd34_i_codigo"];
        $desc_micro=$row["sd34_v_descricao"];
        ?>
        <option value="<?=$cod_micro;?>" <?=$cod_micro==@$chave_z01_v_micro?"selected":""?>><?=$desc_micro;?></option>
        <?
       }
       ?>
      </select><br>
      <b>Família:</b>
      <select name="chave_z01_i_familiamicroarea" style="font-size:9px;width:200px;height:18px;" onchange="if(this.value=='')document.form2.chave_z01_v_micro.value='';">
       <option value=""></option>
      </select>
      <?if((isset($chave_z01_i_familiamicroarea)&&$chave_z01_i_familiamicroarea!="")||(isset($chave_z01_v_micro)&&$chave_z01_v_micro!="")){?>
       <script>fillSelectFromArray(document.form2.chave_z01_i_familiamicroarea, team[document.form2.chave_z01_v_micro.selectedIndex-1]);</script>
      <?}?>
     </td>
    </tr>
    <tr>
     <td colspan="3" align="center">
      <input name="pesquisar2" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button"  id="limpar" value="Limpar" onClick="js_limpar();">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar('<?=@$campoFoco?>');">
      <?if(isset($retornacgs) || @$redireciona!=""){
         $disabled="";
        }else if(!isset($retornacgs) && @$redireciona==""){
         $disabled="";
        }else{
      	 $disabled="disabled";
      	}
      ?>
      <!--<input name="novo_cgs" type="button" id="novo_cgs" value="Novo CGS"  onclick="js_novo_cgs();" <?=$disabled?> >
      <input name="alterar_cgs" type="submit" id="alterar_cgs" value="Altera CGS" <?=$disabled?> > -->
     </td>
    </tr>
   </table>
    </form>
  </td>
 </tr>
 <tr>
 	<td align="center" valign="top">
	<?
	if(!isset($pesquisa_chave)){
		if(isset($campos)==false){
			if(file_exists("funcoes/db_func_cgs_und_ext.php")==true){
				include("funcoes/db_func_cgs_und_ext.php");
			}else{


				$campos = "z01_i_cgsund, 
                   z01_v_nome,
                   s115_c_cartaosus,   
                   z01_d_nasc,   
                   z01_v_sexo,
                   z01_v_ender,
                   z01_i_numero,
                   z01_v_bairro,
                   z01_v_ident
                   ";   

			}
		}
		
		if(isset($chave_z01_i_cgsund) && (trim($chave_z01_i_cgsund)!="") ){
			$sql = $clcgs_und->sql_query($chave_z01_i_cgsund,$campos,"z01_i_cgsund");
		}else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
			$sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," to_ascii(z01_v_nome) like to_ascii('$chave_z01_v_nome%') ");
		}else if(isset($chave_z01_v_ident) && (trim($chave_z01_v_ident)!="") ){
			$sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," z01_v_ident = '$chave_z01_v_ident' ");
		}else if(isset($chave_z01_v_ident) && (trim($chave_z01_d_nasc)!="") ){
			$chave_z01_d_nasc = substr($chave_z01_d_nasc,6,4)."-".substr($chave_z01_d_nasc,3,2)."-".substr($chave_z01_d_nasc,0,2);
			$sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," z01_d_nasc = '$chave_z01_d_nasc' ");
		}else if(isset($chave_s115_c_cartaosus) && (trim($chave_s115_c_cartaosus)!="") ){
			$sql = $clcgs_und->sql_query_ext("",$campos,"z01_v_nome"," s115_c_cartaosus = '$chave_s115_c_cartaosus' ");
		}else if(isset($chave_z01_i_familiamicroarea) && (trim($chave_z01_i_familiamicroarea)!="") ){
			$sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," z01_i_familiamicroarea = '$chave_z01_i_familiamicroarea' ");
		}else if(isset($chave_z01_v_micro) && (trim($chave_z01_v_micro)!="") ){
			$sql = $clcgs_und->sql_query("",$campos,"z01_v_nome"," familiamicroarea.sd35_i_microarea = $chave_z01_v_micro ");
		}else{
		     //$sql = $clcgs_und->sql_query("",$campos,"z01_i_cgsund","");
		}
		
		$repassa = array();
		if(isset($chave_z01_i_cgsund)){
			$repassa = array("chave_z01_i_cgsund"=>@$chave_z01_i_cgsund, 
							"chave_z01_v_nome"=>@$chave_z01_v_nome,
							"chave_z01_v_ident"=>@$chave_z01_v_ident,
							"chave_z01_c_cartaosus"=>@$chave_s115_c_cartaosus,
							"chave_z01_i_familiamicroarea"=>@$chave_z01_i_familiamicroarea);
		}
		if( isset($sql) ){
			db_lovrot( $sql,15,"()","",$funcao_js,"","NoMe",$repassa);
		}
	}else{
		if($pesquisa_chave!=null && $pesquisa_chave!=""){
			$result = $clcgs_und->sql_record($clcgs_und->sql_query($pesquisa_chave));
			if($clcgs_und->numrows!=0){
				db_fieldsmemory($result,0);
				echo "<script>".$funcao_js."('$z01_v_nome','$z01_v_ender','$z01_i_numero','$z01_v_ident',false);</script>";
			}else{
				echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
			}
		}else{
			echo "<script>".$funcao_js."('',false);</script>";
		}
	}
	
	
	?>
	</td>
 </tr>
</table>
</body>
</html>
<script>
function js_nome(nome){
	if( nome != "" ){
		//document.form2.pesquisar.focus();
	}
}
/**
 * Botoão Fechar
 * campoFoco = foco de retorno quando fechar
 */
function js_fechar( campoFoco ){
	if( campoFoco != undefined && campoFoco != '' ){

		eval( "parent.document.getElementById('"+campoFoco+"').focus(); " );
		eval( "parent.document.getElementById('"+campoFoco+"').select(); " );
	}
	parent.db_iframe_cgs_und.hide();
} 

function js_limpar(){
	document.form2.chave_z01_v_nome.value="";
	document.form2.chave_z01_i_cgsund.value="";
	document.form2.chave_z01_v_ident.value="";
	document.form2.chave_s115_c_cartaosus.value="";
	document.form2.chave_z01_v_micro.value="";
	document.form2.chave_z01_i_familiamicroarea.value="";
}
document.form2.chave_z01_v_nome.focus();

</script>
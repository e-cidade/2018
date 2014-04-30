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

//MODULO: atendimento
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_usuarios            = new cl_db_usuarios;
$cltarefaenvol->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at40_descr");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     // $at45_tarefa = "";
     $at45_sequencial = "";
     $at45_usuario = "";
     $at45_perc = "";
     $nome      = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
  	<td nowrap colspan="2" align="right">
		<input name="bt_voltar" type="button" value="Voltar" title="Voltar" onClick="js_voltar();">
  	</td>
  </tr>	
  <tr>
    <td nowrap title="<?=@$Tat45_tarefa?>">
       <?=@$Lat45_tarefa?>
    </td>
    <td> 
<?
db_input('at45_tarefa',10,$Iat45_tarefa,true,'text',3,"")
?>
    </td>
  </tr>
<!--
  <tr>
    <td nowrap title="<?=@$Tat45_tarefa?>">
       <?
       db_ancora(@$Lat45_tarefa,"js_pesquisaat45_tarefa(true);",$db_opcao);
       ?>
    </td>
    <td>
-->     
<?
db_input('at45_sequencial',10,$Iat45_sequencial,true,'hidden',3,"")
?>
       <?
//db_input('at40_descr',1,$Iat40_descr,true,'text',3,'')
       ?>
<!--       
    </td>
  </tr>
-->  
  <tr>
    <td nowrap title="<?=@$Tat45_usuario?>">
       <?=@$Lat45_usuario?>
    </td>
    <td> 
<?
if(isset($at45_usuario)&&$at45_usuario!="") {
	$at45_usuant = $at45_usuario;
	db_input('at45_usuant',10,"",true,'hidden',3,"");
}
db_selectrecord('at45_usuario',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(($db_opcao==2?null:@$at45_usuario),"id_usuario,nome","id_usuario",null))),true,$db_opcao,"");
//db_input('at45_usuario',10,$Iat45_usuario,true,'text',$db_opcao," onchange='js_pesquisaat45_usuario(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat45_perc?>">
       <?=@$Lat45_perc?>
    </td>
    <td> 
<?
// db_input('at45_perc',15,$Iat45_perc,true,'text',$db_opcao,"")
  $matriz = array("0"=>"0%",
                  "10"=>"10%", 
                  "20"=>"20%",
                  "30"=>"30%",
                  "40"=>"40%",
                  "50"=>"50%", 
                  "60"=>"60%",
                  "70"=>"70%",
                  "80"=>"80%",
                  "90"=>"90%",
                  "100"=>"100%");             
  db_select("at45_perc", $matriz,true,$db_opcao); 
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("at45_sequencial"=>@$at45_sequencial,"at45_tarefa"=>@$at45_tarefa);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
//	 $cliframe_alterar_excluir->sql     = $cltarefaenvol->sql_query_file(null,"*",null,"at45_tarefa=$at45_tarefa");
	 $cliframe_alterar_excluir->sql     = $cltarefaenvol->sql_query(null,"*",null,"at45_tarefa=$at45_tarefa");
	 $cliframe_alterar_excluir->campos  ="nome,at45_perc";
	 $cliframe_alterar_excluir->legenda="USUÁRIOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_voltar() {
  parent.mo_camada('tarefa')
  top.corpo.iframe_tarefa.document.form1.bt_voltar.click();	
}
</script>
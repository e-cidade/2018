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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clatendcadareamod->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at25_descr");
$clrotulo->label("nome_modulo");
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
     $at26_sequencia = "";
     $at26_id_item = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr><td>
<fieldset><legend><b>Cadastro de Módulos</b></legend>
<table border="0" width="690">
  <tr>
    <td nowrap title="<?//=@$Tat26_sequencia?>">
       <?//=@$Lat26_sequencia?>
    </td>
    <td> 
			<?
			db_input('at26_sequencia',6,$Iat26_sequencia,true,'hidden',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat26_codarea?>">
        <b>Área:</b>
       <?
       //db_ancora(@$Lat26_codarea,"js_pesquisaat26_codarea(true);",3);
       ?>
    </td>
    <td> 
			<?
			db_input('at26_codarea',10,$Iat26_codarea,true,'text',3," readonly onchange='js_pesquisaat26_codarea(false);'")
			?>
      <?
      db_input('at25_descr',40,$Iat25_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat26_id_item?>">
       <?
       db_ancora(@$Lat26_id_item,"js_pesquisaat26_id_item(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
		db_input('at26_id_item',10,$Iat26_id_item,true,'text',$db_opcao," onchange='js_pesquisaat26_id_item(false);'")
		?>
    <?
    db_input('nome_modulo',40,$Inome_modulo,true,'text',3,'')
    ?>
    </td>
  </tr>
</table>
</fieldset>
</td></tr>
</table>

   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
   <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("at26_sequencia"=>@$at26_sequencia);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clatendcadareamod->sql_query(@$at26_sequencia,"*","nome_modulo"," at26_codarea = $at26_codarea");
	 $cliframe_alterar_excluir->campos  ="at26_sequencia,at26_codarea,nome_modulo,at26_id_item";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
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
function js_pesquisaat26_codarea(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atendcadareamod','db_iframe_atendcadarea','func_atendcadarea.php?funcao_js=parent.js_mostraatendcadarea1|at26_sequencial|at25_descr','Pesquisa',true);
  }else{
     if(document.form1.at26_codarea.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_atendcadareamod','db_iframe_atendcadarea','func_atendcadarea.php?pesquisa_chave='+document.form1.at26_codarea.value+'&funcao_js=parent.js_mostraatendcadarea','Pesquisa',false);
     }else{
       document.form1.at25_descr.value = ''; 
     }
  }
}
function js_mostraatendcadarea(chave,erro){
  document.form1.at25_descr.value = chave; 
  if(erro==true){ 
    document.form1.at26_codarea.focus(); 
    document.form1.at26_codarea.value = ''; 
  }
}
function js_mostraatendcadarea1(chave1,chave2){
  document.form1.at26_codarea.value = chave1;
  document.form1.at25_descr.value = chave2;
  db_iframe_atendcadarea.hide();
}
function js_pesquisaat26_id_item(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atendcadareamod','db_iframe_db_modulos','func_db_modulos.php?funcao_js=parent.js_mostradb_modulos1|id_item|nome_modulo','Pesquisa',true);
  }else{
     if(document.form1.at26_id_item.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_atendcadareamod','db_iframe_db_modulos','func_db_modulos.php?pesquisa_chave='+document.form1.at26_id_item.value+'&funcao_js=parent.js_mostradb_modulos','Pesquisa',false);
     }else{
       document.form1.nome_modulo.value = ''; 
     }
  }
}
function js_mostradb_modulos(chave,erro){
  document.form1.nome_modulo.value = chave; 
  if(erro==true){ 
    document.form1.at26_id_item.focus(); 
    document.form1.at26_id_item.value = ''; 
  }
}
function js_mostradb_modulos1(chave1,chave2){
  document.form1.at26_id_item.value = chave1;
  document.form1.nome_modulo.value = chave2;
  db_iframe_db_modulos.hide();
}
</script>
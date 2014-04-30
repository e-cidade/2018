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
$cltarefaanexos->rotulo->label();
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
     $at25_sequencial = "";
     $at25_obs = "";
     //$at25_anexo = "";
   }
} 
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<center>
<table border="0">
   <tr>
	 <td colspan = "2" align="center" > <b> <br>INCLUIR ANEXOS<br></b>
	 </td>
	   
  </tr>
  <tr>
    <td nowrap title="<?//=@$Tat25_sequencial?>">
       <?//=@$Lat25_sequencial?>
    </td>
    <td> 
<?
db_input('at25_sequencial',10,$Iat25_sequencial,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat25_tarefa?>">
       <?
       db_ancora(@$Lat25_tarefa,"js_pesquisaat25_tarefa(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('at25_tarefa',10,$Iat25_tarefa,true,'text',3," onchange='js_pesquisaat25_tarefa(false);'")
?>
       <?
//db_input('at40_descr',1,$Iat40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat25_anexo?>">
       <?=@$Lat25_anexo?>
    </td>
    <td> 
<?

   	  db_input("anexoarq",30,0,true,"file",1);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat25_obs?>">
       <?=@$Lat25_obs?>
    </td>
    <td> 
<?

   	  db_textarea("at25_obs",0,50,$Iat25_obs,true,"text",1);
?>
    </td>
  </tr>
  
  <tr>
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
	 $chavepri= array("at25_sequencial"=>@$at25_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cltarefaanexos->sql_query_file(null,"*",null,"at25_tarefa=$at25_tarefa");
	 $cliframe_alterar_excluir->campos  ="at25_nomearq,at25_obs";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
   <?
   if(@$vis==1){
   	echo"
   	<tr>
    <td colspan='2' align='center'>
      <a class='links' href='ate3_constarefaanexos.php?at25_tarefa=$at25_tarefa'> Visualizar anexos</a></td>
    </td>
   </tr>";
   }
   ?>
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
function js_pesquisaat25_tarefa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_tarefaanexos','db_iframe_tarefa','func_tarefa.php?funcao_js=parent.js_mostratarefa1|at40_sequencial|at40_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.at25_tarefa.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_tarefaanexos','db_iframe_tarefa','func_tarefa.php?pesquisa_chave='+document.form1.at25_tarefa.value+'&funcao_js=parent.js_mostratarefa','Pesquisa',false);
     }else{
       document.form1.at40_descr.value = ''; 
     }
  }
}
function js_mostratarefa(chave,erro){
  document.form1.at40_descr.value = chave; 
  if(erro==true){ 
    document.form1.at25_tarefa.focus(); 
    document.form1.at25_tarefa.value = ''; 
  }
}
function js_mostratarefa1(chave1,chave2){
  document.form1.at25_tarefa.value = chave1;
  document.form1.at40_descr.value = chave2;
  db_iframe_tarefa.hide();
}
</script>
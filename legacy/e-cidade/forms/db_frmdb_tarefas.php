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

//MODULO: configuracoes
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$cldb_tarefas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("db80_descr");

if(isset($db79_id_usuario)){
if(isset($db_opcaoal)){
    $db_opcao=3;
      $db_botao=false;
}else{
  $db_botao=true;
}
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
     $db_opcao=33;
    }
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
      $db79_tarefasit  = "";
      $db79_descr      = "";
      $db79_data       = "";
      $db79_hora       = "";
    }
}
}

?>
<form name="form1" method="post" action="con1_db_tarefas002.php">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb79_id_usuario?>">
       <?=@$Ldb79_id_usuario?>
    </td>
    <td> 
<?
@db_input('db79_codigo',9,$db79_codigo,true,"hidden",$db_opcao);
db_input('db79_id_usuario',9,$Idb79_id_usuario,true,'text',3," onchange='js_pesquisadb79_id_usuario(false);'");
?>
       <?
db_input('nome',50,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb79_tarefasit?>">
       <?=@$Ldb79_tarefasit?>
    </td>
    <td> 
<?
$result=$cldb_tarefasit->sql_record($cldb_tarefasit->sql_query_file(null,"db80_codigo,db80_descr"));
db_selectrecord("db79_tarefasit",$result,true,$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb79_descr?>">
       <?=@$Ldb79_descr?>
    </td>
    <td> 
<?
db_textarea('db79_descr',2,55,$Idb79_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
<br><br><br>
     <?
        if(isset($db79_id_usuario)){
         $sql_item = $cldb_tarefas->sql_query($db79_id_usuario,"*","db79_data,db79_hora asc","db79_id_usuario = $db79_id_usuario"); 
	 $chavepri= array("db79_id_usuario"=>$db79_id_usuario,"db79_codigo"=>@$db79_codigo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
 	 $cliframe_alterar_excluir->sql     = $sql_item;
         $cliframe_alterar_excluir->campos  ="db79_codigo, db79_descr, db80_descr, db79_data, db79_hora";
	 $cliframe_alterar_excluir->legenda="TAREFAS CADASTRADAS PARA O USUÁRIO";
	 $cliframe_alterar_excluir->iframe_height ="220";
	 $cliframe_alterar_excluir->iframe_width ="750";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
        }
       ?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_tarefas','func_db_usuarios.php?funcao_js=parent.js_preenchepesquisa|id_usuario','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_tarefas.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>
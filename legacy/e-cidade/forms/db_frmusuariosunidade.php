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

//MODULO: saude
$clusuariosunidade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd02_c_nome");
$clrotulo->label("nome");

if(isset($sd25_i_unidade)){
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
      $sd25_i_usuario  = "";
      $nome  = "";
    }
}
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<tr>
    <td nowrap title="<?=@$Tsd25_i_unidade?>">
       <?
       db_ancora(@$Lsd25_i_unidade,"js_pesquisasd25_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd25_i_unidade',10,$Isd25_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd25_i_unidade(false);'")
?>
       <?
db_input('sd02_c_nome',40,$Isd02_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd25_i_usuario?>">
       <?
       db_ancora(@$Lsd25_i_usuario,"js_pesquisasd25_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd25_i_usuario',10,$Isd25_i_usuario,true,'text',$db_opcao," onchange='js_pesquisasd25_i_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd25_b_ativo?>">
       <?=@$Lsd25_b_ativo?>
    </td>
    <td> 
<?
$x = array('t'=>'Sim','f'=>'Não');
db_select('sd25_b_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input type="button" value="Cancelar" onclick="js_cancelar()">
<br><br><br>
<table>
 <tr>
  <td>
   <?
     if(isset($sd25_i_unidade)){
      $sql_item = $clusuariosunidade->sql_query($sd25_i_unidade,$sd25_i_usuario,"*","sd25_i_usuario","sd25_i_unidade = $sd25_i_unidade");
      $chavepri= array("sd25_i_unidade"=>$sd25_i_unidade,"sd25_i_usuario"=>$sd25_i_usuario);
	  $cliframe_alterar_excluir->chavepri=$chavepri;
 	  $cliframe_alterar_excluir->sql     = $sql_item;
      $cliframe_alterar_excluir->campos  ="id_usuario, nome,sd25_b_ativo";
	  $cliframe_alterar_excluir->legenda="USUÁRIOS CADASTRADOS PARA UNIDADE";
	  $cliframe_alterar_excluir->iframe_height ="220";
 	  $cliframe_alterar_excluir->iframe_width ="600";
 	  $cliframe_alterar_excluir->scroll ="yes";
 	  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     }
    ?>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisasd25_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd25_i_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd25_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = ''; 
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.sd02_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd25_i_unidade.focus(); 
    document.form1.sd25_i_unidade.value = ''; 
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd25_i_unidade.value = chave1;
  document.form1.sd02_c_nome.value = chave2;
  db_iframe_unidades.hide();
}
function js_pesquisasd25_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd25_i_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd25_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd25_i_usuario.focus(); 
    document.form1.sd25_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd25_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_usuariosunidade','func_unidades.php?funcao_js=parent.js_preenchepesquisa|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_usuariosunidade.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
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
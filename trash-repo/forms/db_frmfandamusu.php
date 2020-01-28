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

//MODULO: fiscal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clfandamusu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y39_data");
$clrotulo->label("nome");
if(isset($opcao) && $opcao == "alterar" && !isset($pesqandam)){
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamusu002.php?chavepesquisa=$y40_codandam&chavepesquisa1=$y40_id_usuario'</script>";}
if(isset($opcao) && $opcao == "excluir" && !isset($pesqandam)){
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamusu003.php?chavepesquisa=$y40_codandam&chavepesquisa1=$y40_id_usuario'</script>";
}
if(isset($opcao) && $opcao == "alterar" && isset($pesqandam)){
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamnotiusu002.php?chavepesquisa=$y40_codandam&chavepesquisa1=$y40_id_usuario'</script>";
}
if(isset($opcao) && $opcao == "excluir" && isset($pesqandam)){
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamnotiusu003.php?chavepesquisa=$y40_codandam&chavepesquisa1=$y40_id_usuario'</script>";
}
$db_opcao =3;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty40_codandam?>">
       <?=@$Ly40_codandam?>
    </td>
    <td> 
<?
db_input('y40_codandam',20,$Iy40_codandam,true,'text',$db_opcao," onchange='js_pesquisay40_codandam(false);'")
?>
       <?
db_input('y39_data',10,$Iy39_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty40_id_usuario?>">
       <?=$Ly40_id_usuario;
       ?>
    </td>
    <td> 
<?
db_input('y40_id_usuario',5,$Iy40_id_usuario,true,'text',$db_opcao," onchange='js_pesquisay40_id_usuario(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty40_obs?>">
       <?=@$Ly40_obs?>
    </td>
    <td> 
<?
$db_opcao=2;
db_textarea('y40_obs',3,50,$Iy40_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>  
  <tr>
    <td align="top" colspan="2">
   <?
    $db_opcao = 1;
    $chavepri= array("y40_codandam"=>@$y40_codandam,"y40_id_usuario"=>@$y40_id_usuario);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->opcoes=2;
    $cliframe_alterar_excluir->campos="y40_codandam,y40_id_usuario,y40_obs,nome";
    $cliframe_alterar_excluir->sql=$clfandamusu->sql_query("","","*",""," y40_codandam = ".@$y39_codandam."");
    $cliframe_alterar_excluir->legenda="Fiscais da Vistoria";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Usuário Cadastrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
       ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisay40_codandam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_fandam','func_fandam.php?funcao_js=parent.js_mostrafandam1|y39_codandam|y39_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_fandam','func_fandam.php?pesquisa_chave='+document.form1.y40_codandam.value+'&funcao_js=parent.js_mostrafandam','Pesquisa',false);
  }
}
function js_mostrafandam(chave,erro){
  document.form1.y39_data.value = chave; 
  if(erro==true){ 
    document.form1.y40_codandam.focus(); 
    document.form1.y40_codandam.value = ''; 
  }
}
function js_mostrafandam1(chave1,chave2){
  document.form1.y40_codandam.value = chave1;
  document.form1.y39_data.value = chave2;
  db_iframe_fandam.hide();
}
function js_pesquisay40_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.y40_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y40_id_usuario.focus(); 
    document.form1.y40_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y40_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_fandamusu','func_fandamusu.php?funcao_js=parent.js_preenchepesquisa|y40_codandam|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_fandamusu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
+"&chavepesquisa1="+chave1}
</script>
<?
  echo "<script>parent.document.formaba.fiscais.focus()</script>";
?>
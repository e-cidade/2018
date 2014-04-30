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
$cllevusu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y60_data");
$clrotulo->label("nome");
$clrotulo->label("y60_contato");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
?>
<form name="form1" method="post" action="fis1_levusu001.php">
<center>
<table border="0">
  <tr>
  <td align='center'>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty61_codlev?>">
       <?=$Ly61_codlev?>
    </td>
    <td> 
<?
db_input('y61_codlev',6,$Iy61_codlev,true,'text',3);
db_input('y60_contato',40,$Iy60_contato,true,'text',3,'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty61_id_usuario?>">
       <?
       db_ancora(@$Ly61_id_usuario,"js_pesquisay61_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y61_id_usuario',6,$Iy61_id_usuario,true,'text',$db_opcao," onchange='js_pesquisay61_id_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty61_obs?>">
       <?=@$Ly61_obs?>
    </td>
    <td> 
<?
db_textarea('y61_obs',0,47,$Iy61_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'> 
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if(isset($opcao)){
?>
<input name="novo" type="button" value="Novo" onclick="js_novo();" >
<?
}
?>
    </td>
  </tr>
</table>
  </td>
 </tr>
 <tr>
   <td>
     <table cellpadding='0' cellspacing='0'>
<tr>
  <td valign="top" >  
   <?
	if(isset($db_opcaoal)){
	            db_input("db_opcaoal",10,"",true,"hidden",3);
					    }
						 
    $chavepri= array("y61_codlev"=>$y61_codlev,"y61_id_usuario"=>@$y61_id_usuario);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->sql     = $cllevusu->sql_query($y61_codlev,"","y61_codlev,y61_id_usuario,nome,y61_obs");
    $cliframe_alterar_excluir->campos  ="y61_id_usuario,nome,y61_obs";
    $cliframe_alterar_excluir->legenda="FISCAIS";
    $cliframe_alterar_excluir->iframe_height ="140";
    $cliframe_alterar_excluir->iframe_width ="700";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
  </tr>


 </table>
 </center>
</form>
<script>
function js_novo(){
  obj=document.createElement('input');
  obj.setAttribute('name','novo');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','ok');
  document.form1.appendChild(obj);
  document.form1.submit();
}
function js_pesquisay61_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_levusu','db_iframe_db_usuarios','func_cadfiscais.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_levusu','db_iframe_db_usuarios','func_cadfiscais.php?pesquisa_chave='+document.form1.y61_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y61_id_usuario.focus(); 
    document.form1.y61_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y61_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
</script>
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

//MODULO: patrim
$clbenscomissao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
      if($db_opcao==1){
 	   $db_action="pat1_benscomissao004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="pat1_benscomissao005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="pat1_benscomissao006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt60_codcom?>">
       <?=@$Lt60_codcom?>
    </td>
    <td> 
<?
$t60_instit = db_getsession("DB_instit");
db_input("t60_instit",10,$It60_instit,true,"hidden",3,"");
db_input('t60_codcom',8,$It60_codcom,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt60_dataini?>">
       <?=@$Lt60_dataini?>
    </td>
    <td> 
<?
db_inputdata('t60_dataini',@$t60_dataini_dia,@$t60_dataini_mes,@$t60_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt60_datafim?>">
       <?=@$Lt60_datafim?>
    </td>
    <td> 
<?
db_inputdata('t60_datafim',@$t60_datafim_dia,@$t60_datafim_mes,@$t60_datafim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt60_id_usuario?>">
       <?
       db_ancora(@$Lt60_id_usuario,"js_pesquisat60_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('t60_id_usuario',5,$It60_id_usuario,true,'text',$db_opcao," onchange='js_pesquisat60_id_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat60_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_benscomissao','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.t60_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_benscomissao','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.t60_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.t60_id_usuario.focus(); 
    document.form1.t60_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.t60_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_benscomissao','db_iframe_benscomissao','func_benscomissao.php?funcao_js=parent.js_preenchepesquisa|t60_codcom','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_benscomissao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
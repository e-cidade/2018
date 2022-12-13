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

//MODULO: caixa
$clarrevenclog->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk75_sequencial?>">
       <?=@$Lk75_sequencial?>
    </td>
    <td> 
<?
db_input('k75_sequencial',10,$Ik75_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk75_instit?>">
       <?
       db_ancora(@$Lk75_instit,"js_pesquisak75_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k75_instit',10,$Ik75_instit,true,'text',$db_opcao," onchange='js_pesquisak75_instit(false);'")
?>
       <?
db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk75_usuario?>">
       <?
       db_ancora(@$Lk75_usuario,"js_pesquisak75_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k75_usuario',10,$Ik75_usuario,true,'text',$db_opcao," onchange='js_pesquisak75_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk75_data?>">
       <?=@$Lk75_data?>
    </td>
    <td> 
<?
db_inputdata('k75_data',@$k75_data_dia,@$k75_data_mes,@$k75_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk75_hora?>">
       <?=@$Lk75_hora?>
    </td>
    <td> 
<?
db_input('k75_hora',10,$Ik75_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak75_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.k75_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k75_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.k75_instit.focus(); 
    document.form1.k75_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k75_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisak75_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.k75_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.k75_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.k75_usuario.focus(); 
    document.form1.k75_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.k75_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_arrevenclog','func_arrevenclog.php?funcao_js=parent.js_preenchepesquisa|k75_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_arrevenclog.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
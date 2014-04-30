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
$clvistoriaslote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("y77_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty06_vistoriaslote?>">
       <?=@$Ly06_vistoriaslote?>
    </td>
    <td> 
<?
db_input('y06_vistoriaslote',10,$Iy06_vistoriaslote,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty06_data?>">
       <?=@$Ly06_data?>
    </td>
    <td> 
<?
db_inputdata('y06_data',@$y06_data_dia,@$y06_data_mes,@$y06_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty06_hora?>">
       <?=@$Ly06_hora?>
    </td>
    <td> 
<?
db_input('y06_hora',10,$Iy06_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty06_usuario?>">
       <?
       db_ancora(@$Ly06_usuario,"js_pesquisay06_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y06_usuario',10,$Iy06_usuario,true,'text',$db_opcao," onchange='js_pesquisay06_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty06_codtipo?>">
       <?
       db_ancora(@$Ly06_codtipo,"js_pesquisay06_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y06_codtipo',10,$Iy06_codtipo,true,'text',$db_opcao," onchange='js_pesquisay06_codtipo(false);'")
?>
       <?
db_input('y77_descricao',50,$Iy77_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay06_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.y06_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.y06_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y06_usuario.focus(); 
    document.form1.y06_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y06_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisay06_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipovistorias','func_tipovistorias.php?funcao_js=parent.js_mostratipovistorias1|y77_codtipo|y77_descricao','Pesquisa',true);
  }else{
     if(document.form1.y06_codtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipovistorias','func_tipovistorias.php?pesquisa_chave='+document.form1.y06_codtipo.value+'&funcao_js=parent.js_mostratipovistorias','Pesquisa',false);
     }else{
       document.form1.y77_descricao.value = ''; 
     }
  }
}
function js_mostratipovistorias(chave,erro){
  document.form1.y77_descricao.value = chave; 
  if(erro==true){ 
    document.form1.y06_codtipo.focus(); 
    document.form1.y06_codtipo.value = ''; 
  }
}
function js_mostratipovistorias1(chave1,chave2){
  document.form1.y06_codtipo.value = chave1;
  document.form1.y77_descricao.value = chave2;
  db_iframe_tipovistorias.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vistoriaslote','func_vistoriaslote.php?funcao_js=parent.js_preenchepesquisa|y06_vistoriaslote','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vistoriaslote.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
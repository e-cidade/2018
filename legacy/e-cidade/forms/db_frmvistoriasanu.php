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
$clvistoriasanu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y70_id_usuario");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty28_codigo?>">
       <?=@$Ly28_codigo?>
    </td>
    <td> 
<?
db_input('y28_codigo',8,$Iy28_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty28_codvist?>">
       <?
       db_ancora(@$Ly28_codvist,"js_pesquisay28_codvist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y28_codvist',10,$Iy28_codvist,true,'text',$db_opcao," onchange='js_pesquisay28_codvist(false);'")
?>
       <?
//db_input('y70_id_usuario',10,$Iy70_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <!--
  <tr>
    <td nowrap title="<?=@$Ty28_data?>">
       <?=@$Ly28_data?>
    </td>
    <td> 
<?
db_inputdata('y28_data',@$y28_data_dia,@$y28_data_mes,@$y28_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty28_hora?>">
       <?=@$Ly28_hora?>
    </td>
    <td> 
<?
db_input('y28_hora',5,$Iy28_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty28_usuario?>">
       <?
       db_ancora(@$Ly28_usuario,"js_pesquisay28_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y28_usuario',10,$Iy28_usuario,true,'text',$db_opcao," onchange='js_pesquisay28_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  -->
  <tr>
    <td nowrap title="<?=@$Ty28_motivo?>">
       <?=@$Ly28_motivo?>
    </td>
    <td> 
<?
db_textarea('y28_motivo',0,50,$Iy28_motivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay28_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist','Pesquisa',true);
  }else{
     if(document.form1.y28_codvist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y28_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
     }else{
        
     }
  }
}
function js_mostravistorias(chave,erro){   
  if(erro==true){ 
    document.form1.y28_codvist.focus(); 
    document.form1.y28_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1){
  document.form1.y28_codvist.value = chave1;  
  db_iframe_vistorias.hide();
}
function js_pesquisay28_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.y28_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.y28_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y28_usuario.focus(); 
    document.form1.y28_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y28_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vistoriasanu','func_vistoriasanu.php?funcao_js=parent.js_preenchepesquisa|y28_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vistoriasanu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
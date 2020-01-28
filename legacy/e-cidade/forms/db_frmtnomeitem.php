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

//MODULO: teste
$cltnomeitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("yy_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tyx_coditem?>">
       <?=@$Lyx_coditem?>
    </td>
    <td> 
<?
db_input('yx_coditem',4,$Iyx_coditem,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tyx_codigo?>">
       <?
       db_ancora(@$Lyx_codigo,"js_pesquisayx_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('yx_codigo',4,$Iyx_codigo,true,'text',$db_opcao," onchange='js_pesquisayx_codigo(false);'")
?>
       <?
db_input('yy_nome',40,$Iyy_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tyx_valor?>">
       <?=@$Lyx_valor?>
    </td>
    <td> 
<?
db_input('yx_valor',15,$Iyx_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tyx_sexo?>">
       <?=@$Lyx_sexo?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('yx_sexo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tyx_tipo?>">
       <?=@$Lyx_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Casado','2'=>'Solteiro');
db_select('yx_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisayx_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tnomes','func_tnomes.php?funcao_js=parent.js_mostratnomes1|yy_codigo|yy_obs','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tnomes','func_tnomes.php?pesquisa_chave='+document.form1.yx_codigo.value+'&funcao_js=parent.js_mostratnomes','Pesquisa',false);
  }
}
function js_mostratnomes(chave,erro){
  document.form1.yy_nome.value = chave; 
  if(erro==true){ 
    document.form1.yx_codigo.focus(); 
    document.form1.yx_codigo.value = ''; 
  }
}
function js_mostratnomes1(chave1,chave2){
  document.form1.yx_codigo.value = chave1;
  document.form1.yy_nome.value = chave2;
  db_iframe_tnomes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tnomeitem','func_tnomeitem.php?funcao_js=parent.js_preenchepesquisa|yx_coditem','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tnomeitem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
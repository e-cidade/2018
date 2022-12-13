<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: cemiterio
$clquadracemit->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm14_i_codigo");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
&nbsp;
<fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm22_i_codigo?>">
       <?=@$Lcm22_i_codigo?>
    </td>
    <td>
<?
db_input('cm22_i_codigo',10,$Icm22_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm22_i_cemiterio?>">
       <?
       db_ancora(@$Lcm22_i_cemiterio,"js_pesquisacm22_i_cemiterio(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm22_i_cemiterio',10,$Icm22_i_cemiterio,true,'text',$db_opcao," onchange='js_pesquisacm22_i_cemiterio(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm22_c_quadra?>">
       <?=@$Lcm22_c_quadra?>
    </td>
    <td>
<?
db_input('cm22_c_quadra',3,$Icm22_c_quadra,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
  <td><strong>Tipo:</strong>
  </td>
  <td>
   <?
    $x = array('C'=>'Campa','J'=>'Jazigo','O'=>'Ossário','S'=>'Sepultura');
    db_select('cm22_c_tipo',$x,true,$db_opcao,"");
   ?>
  </tr>
   </table>
  </center>
  </fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacm22_i_cemiterio(mostra){
  if(mostra==true){
    //js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cemiterio','func_cemiterio.php?funcao_js=parent.js_mostracemiterio1|cm14_i_codigo|cm14_i_codigo','Pesquisa',true);
    js_OpenJanelaIframe('','db_iframe_cemiterio','func_cemiterio.php?funcao_js=parent.js_mostracemiterio1|cm14_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm22_i_cemiterio.value != ''){
        //js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cemiterio','func_cemiterio.php?pesquisa_chave='+document.form1.cm22_i_cemiterio.value+'&funcao_js=parent.js_mostracemiterio','Pesquisa',false);
        js_OpenJanelaIframe('','db_iframe_cemiterio','func_cemiterio.php?pesquisa_chave='+document.form1.cm22_i_cemiterio.value+'&funcao_js=parent.js_mostracemiterio','Pesquisa',false);
     }else{
       document.form1.cm14_i_codigo.value = '';
     }
  }
}
function js_mostracemiterio(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.cm22_i_cemiterio.focus();
    document.form1.cm22_i_cemiterio.value = '';
  }
}
function js_mostracemiterio1(chave1,chave2){
  document.form1.cm22_i_cemiterio.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cemiterio.hide();
}
function js_pesquisa(){
  //js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_quadracemit','func_quadracemit.php?funcao_js=parent.js_preenchepesquisa|cm22_i_codigo','Pesquisa',true);
  js_OpenJanelaIframe('','db_iframe_quadracemit','func_quadracemit.php?funcao_js=parent.js_preenchepesquisa|cm22_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_quadracemit.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
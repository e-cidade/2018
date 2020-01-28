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

//MODULO: Laboratório
$cllab_turnohora->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la01_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla07_i_codigo?>">
       <?=@$Lla07_i_codigo?>
    </td>
    <td> 
<?
db_input('la07_i_codigo',10,$Ila07_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla07_i_laboratorio?>">
       <?
       db_ancora(@$Lla07_i_laboratorio,"js_pesquisala07_i_laboratorio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la07_i_laboratorio',10,$Ila07_i_laboratorio,true,'text',$db_opcao," onchange='js_pesquisala07_i_laboratorio(false);'")
?>
       <?
db_input('la02_c_descr',10,$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla07_i_turno?>">
       <?
       db_ancora(@$Lla07_i_turno,"js_pesquisala07_i_turno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la07_i_turno',10,$Ila07_i_turno,true,'text',$db_opcao," onchange='js_pesquisala07_i_turno(false);'")
?>
       <?
db_input('la01_c_descr',10,$Ila01_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla07_c_inicio?>">
       <?=@$Lla07_c_inicio?>
    </td>
    <td> 
<?
db_input('la07_c_inicio',5,$Ila07_c_inicio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla07_c_fim?>">
       <?=@$Lla07_c_fim?>
    </td>
    <td> 
<?
db_input('la07_c_fim',5,$Ila07_c_fim,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisala07_i_laboratorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_laboratorio','func_lab_laboratorio.php?funcao_js=parent.js_mostralab_laboratorio1|la02_i_codigo|la02_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la07_i_laboratorio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_laboratorio','func_lab_laboratorio.php?pesquisa_chave='+document.form1.la07_i_laboratorio.value+'&funcao_js=parent.js_mostralab_laboratorio','Pesquisa',false);
     }else{
       document.form1.la02_c_descr.value = ''; 
     }
  }
}
function js_mostralab_laboratorio(chave,erro){
  document.form1.la02_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la07_i_laboratorio.focus(); 
    document.form1.la07_i_laboratorio.value = ''; 
  }
}
function js_mostralab_laboratorio1(chave1,chave2){
  document.form1.la07_i_laboratorio.value = chave1;
  document.form1.la02_c_descr.value = chave2;
  db_iframe_lab_laboratorio.hide();
}
function js_pesquisala07_i_turno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_turno','func_lab_turno.php?funcao_js=parent.js_mostralab_turno1|la01_i_codigo|la01_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la07_i_turno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_turno','func_lab_turno.php?pesquisa_chave='+document.form1.la07_i_turno.value+'&funcao_js=parent.js_mostralab_turno','Pesquisa',false);
     }else{
       document.form1.la01_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_turno(chave,erro){
  document.form1.la01_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la07_i_turno.focus(); 
    document.form1.la07_i_turno.value = ''; 
  }
}
function js_mostralab_turno1(chave1,chave2){
  document.form1.la07_i_turno.value = chave1;
  document.form1.la01_c_descr.value = chave2;
  db_iframe_lab_turno.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_turnohora','func_lab_turnohora.php?funcao_js=parent.js_preenchepesquisa|la07_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_turnohora.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
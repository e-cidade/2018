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
$cllab_medico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("la22_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla38_i_codigo?>">
       <?=@$Lla38_i_codigo?>
    </td>
    <td> 
<?
db_input('la38_i_codigo',10,$Ila38_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla38_i_medico?>">
       <?
       db_ancora(@$Lla38_i_medico,"js_pesquisala38_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la38_i_medico',10,$Ila38_i_medico,true,'text',$db_opcao," onchange='js_pesquisala38_i_medico(false);'")
?>
       <?
db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla38_i_requisicao?>">
       <?
       db_ancora(@$Lla38_i_requisicao,"js_pesquisala38_i_requisicao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la38_i_requisicao',10,$Ila38_i_requisicao,true,'text',$db_opcao," onchange='js_pesquisala38_i_requisicao(false);'")
?>
       <?
db_input('la22_i_codigo',10,$Ila22_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisala38_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|sd03_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la38_i_medico.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.la38_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.sd03_i_codigo.value = ''; 
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.sd03_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la38_i_medico.focus(); 
    document.form1.la38_i_medico.value = ''; 
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.la38_i_medico.value = chave1;
  document.form1.sd03_i_codigo.value = chave2;
  db_iframe_medicos.hide();
}
function js_pesquisala38_i_requisicao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_requisicao','func_lab_requisicao.php?funcao_js=parent.js_mostralab_requisicao1|la22_i_codigo|la22_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la38_i_requisicao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_requisicao','func_lab_requisicao.php?pesquisa_chave='+document.form1.la38_i_requisicao.value+'&funcao_js=parent.js_mostralab_requisicao','Pesquisa',false);
     }else{
       document.form1.la22_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_requisicao(chave,erro){
  document.form1.la22_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la38_i_requisicao.focus(); 
    document.form1.la38_i_requisicao.value = ''; 
  }
}
function js_mostralab_requisicao1(chave1,chave2){
  document.form1.la38_i_requisicao.value = chave1;
  document.form1.la22_i_codigo.value = chave2;
  db_iframe_lab_requisicao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_medico','func_lab_medico.php?funcao_js=parent.js_preenchepesquisa|la38_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_medico.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
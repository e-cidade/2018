<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clbensguarda->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("t20_descr");
if ($db_opcao == 1) {
  $db_action = "pat1_bensguarda004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action = "pat1_bensguarda005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "pat1_bensguarda006.php";
}
?>
<form name="form1" method="post" action="<?=$db_action ?>">
<fieldset style="margin-top: 35px; width: 500px;">
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt21_codigo ?>">
       <?=@$Lt21_codigo ?>
    </td>
    <td> 
      <?
        $t21_instit = db_getsession("DB_instit");
        db_input("t21_instit", 10, $It21_instit, true, "hidden", 3, "");
        db_input('t21_codigo', 10, $It21_codigo, true, 'text', 3, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt21_numcgm ?>">
       <?
       db_ancora(@$Lt21_numcgm, "js_pesquisat21_numcgm(true);", $db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('t21_numcgm', 10, $It21_numcgm, true, 'text', $db_opcao, " onchange='js_pesquisat21_numcgm(false);'");
        db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt21_tipoguarda ?>">
       <?
       db_ancora(@$Lt21_tipoguarda, "js_pesquisat21_tipoguarda(true);", $db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('t21_tipoguarda', 10, $It21_tipoguarda, true, 'text', $db_opcao,
                 " onchange='js_pesquisat21_tipoguarda(false);'");
        db_input('t20_descr', 40, $It20_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt21_data ?>">
       <?=@$Lt21_data ?>
    </td>
    <td> 
      <?
      db_inputdata('t21_data', @$t21_data_dia, @$t21_data_mes, @$t21_data_ano, true, 'text', $db_opcao, "")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt21_obs ?>">
       <?=@$Lt21_obs ?>
    </td>
    <td> 
      <?
      db_textarea('t21_obs', 0, 50, $It21_obs, true, 'text', $db_opcao, "")
      ?>
    </td>
  </tr>
</table>
</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> />
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisat21_numcgm(mostra) {
  
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo.iframe_bensguarda','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  } else {
    
     if (document.form1.t21_numcgm.value != '') {
        
        js_OpenJanelaIframe('top.corpo.iframe_bensguarda','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.t21_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     } else {
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.t21_numcgm.focus(); 
    document.form1.t21_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.t21_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisat21_tipoguarda(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bensguarda','db_iframe_benstipoguarda','func_benstipoguarda.php?funcao_js=parent.js_mostratipoguarda1|t20_codigo|t20_descr','Pesquisa',true);
  }else{
     if(document.form1.t21_tipoguarda.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_bensguarda','db_iframe_benstipoguarda','func_benstipoguarda.php?pesquisa_chave='+document.form1.t21_tipoguarda.value+'&funcao_js=parent.js_mostratipoguarda','Pesquisa',false);
     }else{
       document.form1.t20_descr.value = ''; 
     }
  }
}
function js_mostratipoguarda(chave,erro){
  document.form1.t20_descr.value = chave; 
  if(erro==true){ 
    document.form1.t21_tipoguarda.focus(); 
    document.form1.t21_tipoguarda.value = ''; 
  }
}
function js_mostratipoguarda1(chave1,chave2){
  document.form1.t21_tipoguarda.value = chave1;
  document.form1.t20_descr.value = chave2;
  db_iframe_benstipoguarda.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_bensguarda','db_iframe_bensguarda','func_bensguarda.php?funcao_js=parent.js_preenchepesquisa|t21_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave) {
  
  db_iframe_bensguarda.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
  }
  ?>
}
</script>
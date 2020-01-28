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

//MODULO: material
$clmatestoqueinimei->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");
$clrotulo->label("m70_codmatmater");
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("m80_matestoqueitem");
if(isset($m70_codmatmater) && trim($m70_codmatmater)!=""){
  $result_matestoque = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_matestoque(null,"distinct m70_codmatmater,m60_descr,m60_quantent,descrdepto,m70_quant,m70_valor",""," m70_codmatmater=$m70_codmatmater and m70_coddepto=$m70_coddepto"));
  $numrows_matestoque = $clmatestoqueinimei->numrows;
}
?>
<form name="form1" method="post" action="">
<center>
<BR><BR>
<table border="0">
<tr>
<td nowrap title="<?=@$Tm70_codmatmater?>" align="right" >
<?
db_ancora(@$Lm70_codmatmater,"js_pesquisam70_codmatmater(true);",((isset($m70_codmatmater) && trim($m70_codmatmater)!="" && (isset($numrows_matestoque) && $numrows_matestoque>0))?"3":"1"));
?>
</td>
<td align="left" nowrap>     
<? 
db_input('m70_codmatmater',10,$Im70_codmatmater,true,"text",((isset($m70_codmatmater) && trim($m70_codmatmater)!="" && (isset($numrows_matestoque) && $numrows_matestoque>0))?"3":"1"),"onchange='js_pesquisam70_codmatmater(false);'");
?>
<? 
db_input('m60_descr',40,$Im60_descr,true,"text",3);
?>
</td>
</tr>
<?
if(isset($m70_codmatmater) && trim($m70_codmatmater)!=""){
  $db_opcao = 2;
  if(isset($numrows_matestoque) && $numrows_matestoque>0){
		echo "rotina indisponivel";
		exit;
		//$db_botao=true;
    ?>
    <tr>
    <td colspan='2' >
    <iframe name="iframe_matestoquesai" id="iframe_matestoquesai" marginwidth="0" marginheight="0" frameborder="0" src="mat1_matestoquesaiiframe001.php?m70_codmatmater=<?=$m70_codmatmater?>" width="760" height="300"></iframe>
    </td>
    </tr>
    </table>
    </center>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick='js_verificarcampos();'>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <input name="voltar" type="button" id="voltar" value="Voltar" onclick="document.location.href='mat1_matestoquesai002.php'" >
    <?
  }else{
    echo "  <tr>";
    echo "    <td align='center' colspan='2'><BR><BR><BR><BR><BR><BR><BR><BR><BR>";
    echo "      <strong>Nenhum registro deste material encontrado em estoque.</strong>";
    echo "    </td>";
    echo "  </tr>";
    echo "</table>";
    echo "</center>";
  }
}else{
  echo "</table>";
  echo "</center>";
  echo "<input name='pesquisar' type='button' id='pesquisar' value='Pesquisar' onclick='js_pesquisa();'>";
}
?>
</form>
<script>
function js_verificarcampos(){
  x = iframe_matestoquesai.document.form1;
  virg = "";
  x.valores.value = "";
  for(i=0;i<x.length;i++){
    if(x.elements[i].type == 'text'){
      if(x.elements[i].value!="" && x.elements[i].value!=0 && x.elements[i].name!="valores"){
        x.valores.value += virg + x.elements[i].name;
        virg = ",";
      }
    }
  }
  if(iframe_matestoquesai.document.form1.valores.value!=""){
    obj= iframe_matestoquesai.document.createElement('input');
    obj.setAttribute('name','incluir');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','incluir');
    iframe_matestoquesai.document.form1.appendChild(obj);
    iframe_matestoquesai.document.form1.submit();
  }else{
    alert('Informe a quantidade de saída dos itens.');
    iframe_matestoquesai.document.form1.elements[1].focus();
  }
}
function js_pesquisam70_codmatmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
    if(document.form1.m70_codmatmater.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.m70_codmatmater.value+'&funcao_js=parent.js_mostramatmater','Pesquisa',false);
    }else{
      document.form1.m60_descr.value = ''; 
    }
  }
}
function js_mostramatmater(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true){ 
    document.form1.m70_codmatmater.focus(); 
    document.form1.m70_codmatmater.value = '';    
  }else{
    document.form1.submit();
  }
}
function js_mostramatmater1(chave1,chave2){
  document.form1.m70_codmatmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
  document.form1.submit();
}
function js_pesquisam82_matestoqueini(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueini','func_matestoqueini.php?funcao_js=parent.js_mostramatestoqueini1|m80_codigo|m80_matestoqueitem','Pesquisa',true);
  }else{
    if(document.form1.m82_matestoqueini.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueini','func_matestoqueini.php?pesquisa_chave='+document.form1.m82_matestoqueini.value+'&funcao_js=parent.js_mostramatestoqueini','Pesquisa',false);
    }else{
      document.form1.m80_matestoqueitem.value = ''; 
    }
  }
}
function js_mostramatestoqueini(chave,erro){
  document.form1.m80_matestoqueitem.value = chave; 
  if(erro==true){ 
    document.form1.m82_matestoqueini.focus(); 
    document.form1.m82_matestoqueini.value = ''; 
  }
}
function js_mostramatestoqueini1(chave1,chave2){
  document.form1.m82_matestoqueini.value = chave1;
  document.form1.m80_matestoqueitem.value = chave2;
  db_iframe_matestoqueini.hide();
}
function js_pesquisam82_matestoqueitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?funcao_js=parent.js_mostramatestoqueitem1|m71_codlanc|m71_codmatestoque','Pesquisa',true);
  }else{
    if(document.form1.m82_matestoqueitem.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?pesquisa_chave='+document.form1.m82_matestoqueitem.value+'&funcao_js=parent.js_mostramatestoqueitem','Pesquisa',false);
    }else{
      document.form1.m71_codmatestoque.value = ''; 
    }
  }
}
function js_mostramatestoqueitem(chave,erro){
  document.form1.m71_codmatestoque.value = chave; 
  if(erro==true){ 
    document.form1.m82_matestoqueitem.focus(); 
    document.form1.m82_matestoqueitem.value = ''; 
  }
}
function js_mostramatestoqueitem1(chave1,chave2){
  document.form1.m82_matestoqueitem.value = chave1;
  document.form1.m71_codmatestoque.value = chave2;
  db_iframe_matestoqueitem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueinimei','func_matestoqueinimei.php?funcao_js=parent.js_preenchepesquisa|m82_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matestoqueinimei.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
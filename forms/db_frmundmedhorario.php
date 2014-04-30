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
<?
//MODULO: saude
$clundmedhorario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed32_i_codigo");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("sd04_i_unidade");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd04_i_unidade?>">
       <?
       db_ancora(@$Lsd04_i_unidade,"js_pesquisasd04_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd04_i_unidade(false);'")
?>
       <?
db_input('descrdepto',80,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
</table>
</center>
<input name="aba2" type="button" id="aba2" value="Médicos" onclick="js_undmedicos();" >
</form>
<script>
function js_undmedicos(){
 if(document.form1.sd04_i_unidade.value==""){
  alert("Informe a Unidade para continuar.");
 }else{
  undmed = document.form1.sd04_i_unidade.value;
  parent.mo_camada('a2');
  parent.document.formaba.a2.disabled = false;
  parent.iframe_a2.document.location.href='sau1_undmedhorario004.php?sd04_i_unidade='+undmed;
 }
}
function js_pesquisasd30_i_diasemana(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_diasemana','func_diasemana.php?funcao_js=parent.js_mostradiasemana1|ed32_i_codigo|ed32_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_diasemana.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_diasemana','func_diasemana.php?pesquisa_chave='+document.form1.sd30_i_diasemana.value+'&funcao_js=parent.js_mostradiasemana','Pesquisa',false);
     }else{
       document.form1.ed32_i_codigo.value = ''; 
     }
  }
}
function js_mostradiasemana(chave,erro){
  document.form1.ed32_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_diasemana.focus(); 
    document.form1.sd30_i_diasemana.value = ''; 
  }
}
function js_mostradiasemana1(chave1,chave2){
  document.form1.sd30_i_diasemana.value = chave1;
  document.form1.ed32_i_codigo.value = chave2;
  db_iframe_diasemana.hide();
}
function js_pesquisasd30_i_undmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostraunidademedicos1|sd04_i_codigo|sd04_i_medico','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_undmed.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd30_i_undmed.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
     }else{
       document.form1.sd04_i_medico.value = ''; 
     }
  }
}
function js_mostraunidademedicos(chave,erro){
  document.form1.sd04_i_medico.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_undmed.focus(); 
    document.form1.sd30_i_undmed.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd30_i_undmed.value = chave1;
  document.form1.sd04_i_medico.value = chave2;
  db_iframe_unidademedicos.hide();
}
function js_pesquisasd04_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_unidade.value != ''){
        js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd04_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd04_i_unidade.focus();
    document.form1.sd04_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd04_i_unidade.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}
function js_pesquisasd04_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_medico.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd04_i_medico.focus();
    document.form1.sd04_i_medico.value = '';
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd04_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_undmedhorario','func_undmedhorario.php?funcao_js=parent.js_preenchepesquisa|sd30_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_undmedhorario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
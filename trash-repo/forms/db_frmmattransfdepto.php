<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m60_descr");
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("m80_matestoqueitem");
$result_departamentoorigem = $cldb_depart->sql_record($cldb_depart->sql_query_file(db_getsession("DB_coddepto"),"coddepto as departamentoorigem,descrdepto as descrdepartamentoorigem"));
db_fieldsmemory($result_departamentoorigem,0);

$result_usuarioonline = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession("DB_id_usuario"),"id_usuario,nome"));
db_fieldsmemory($result_usuarioonline,0);

if(isset($departamentodestino)){
  $result_departamentodestino = $cldb_depart->sql_record($cldb_depart->sql_query_file($departamentodestino,"coddepto as departamentodestino,descrdepto as descrdepartamentodestino"));
  db_fieldsmemory($result_departamentodestino,0);
}
?>
<form name="form1" method="post" action="">
<center>
<BR>
<table border="0">
  <tr>
    <td nowrap title="<?=(@$Tid_usuario)?>" align="right" >
<?
db_ancora(@$Lid_usuario,"",3);
?>
    </td>
    <td align="left" nowrap>
<?
db_input('id_usuario',10,@$Iid_usario,true,"text",3);
?>
<?
db_input('nome',40,$Inome,true,"text",3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Departamento de origem" align="right" >
<?
db_ancora("<strong>Departamento de origem:</strong>","",3);
?>
    </td>
    <td align="left" nowrap>
<?
db_input('coddepto',10,$Icoddepto,true,"text",3,"","departamentoorigem");
?>
<?
db_input('descrdepto',40,$Idescrdepto,true,"text",3,"","descrdepartamentoorigem");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>" align="right" ><b>Departamento destino:</b>

<?
//db_ancora("<strong>Departamento destino:</strong>","js_pesquisacoddepto(true);",(isset($mostrapesquisa)?"3":"1"));
?>
    </td>
    <td align="left" nowrap>
<?
$result = $cldb_almox->sql_record($cldb_almox->sql_query(null,"coddepto,descrdepto","descrdepto", " coddepto <> " . db_getsession("DB_coddepto")));
db_selectrecord("coddepto",$result,true,(isset($mostrapesquisa)?"3":"1"),"","departamentodestino");
//db_input('coddepto',10,$Icoddepto,true,"text",(isset($mostrapesquisa)?"3":"1"),"onchange='js_pesquisacoddepto(false);js_verificarcampos(true);'","departamentodestino");
?>
<?
//db_input('descrdepto',40,$Idescrdepto,true,"text",3,"","descrdepartamentodestino");
?>
    </td>
  </tr>
</table>
</center>
<input name="enviar" type="button" id="db_opcao" value="Enviar dados" <?=($db_botao==false?"disabled":"")?> onclick='js_verificarcampos(false);'>
<?
  if(isset($mostrapesquisa)){
    echo "<input name='pesquisar' type='button' id='pesquisar' value='Pesquisar' onclick='js_pesquisa();' >";
  }
?>
</form>
<script>
function js_verificarcampos(TorF){
  if(document.form1.departamentodestino.value==""){
    top.corpo.iframe_itens.location.href = 'mat1_mattransfitens001.php';
    parent.document.formaba.itens.disabled=true;
    if(TorF==false){
      alert("Informe o departamento de destino dos itens.");
    }
  }else if(TorF==false){
    top.corpo.iframe_itens.location.href = 'mat1_mattransfitens001.php?departamentodestino='+document.form1.departamentodestino.value+'&departamentoorigem='+document.form1.departamentoorigem.value;
    parent.document.formaba.itens.disabled=false;
    parent.mo_camada('itens');
    document.form1.submit();
  }
}
function js_pesquisacoddepto(mostra){
  qry = "&chave_t93_depart=<?=($departamentoorigem)?>";
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_depart','db_iframe_depart','func_db_departalmox.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto'+qry,'Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.departamentodestino.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_depart','db_iframe_depart','func_db_departalmox.php?pesquisa_chave='+document.form1.departamentodestino.value+'&funcao_js=parent.js_mostradepart'+qry,'Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.descrdepartamentodestino.value = "";
     }
  }
}
function js_mostradepart(chave,erro){
  document.form1.descrdepartamentodestino.value = chave;
  if(erro==true){
    document.form1.departamentodestino.focus();
    document.form1.departamentodestino.value = '';
  }
}
function js_mostradepart1(chave1,chave2){
  document.form1.departamentodestino .value = chave1;
  document.form1.descrdepartamentodestino.value = chave2;
  db_iframe_depart.hide();
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
  qry  = "&chave_m80_codtipo=7";
  qry += "&chave_m80_coddepto=<?=db_getsession("DB_coddepto")?>";
  qry += "&naoinill=7";
  js_OpenJanelaIframe('top.corpo.iframe_depart','db_iframe_matestoqueini','func_matestoqueini.php?funcao_js=parent.js_preenchepesquisa|m80_codigo'+qry,'Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matestoqueini.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

$("departamentodestino").observe("change", function() {

	if ($F("departamentoorigem") == $F("departamentodestino")) {

		alert("Departamento de destino não pode ser o mesmo de origem.");
		$("db_opcao").disabled = true;
  } else {
	  $("db_opcao").disabled = false;
  }
});
</script>
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

include("classes/db_db_depusu_classe.php");
$cldb_depusu = new cl_db_depusu;
//MODULO: material
$clmatrequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
$clrotulo->label("m42_codigo");
/*
if (isset($m40_codigo)&&$m40_codigo!=""){
  $result_jahatend=$clmatrequiitem->sql_record($clmatrequiitem->sql_query_atend(null,'*',null,"m41_codmatrequi=$m40_codigo and m43_codigo is not null"));
  if ($clmatrequiitem->numrows!=0){
    $opcao= $db_opcao;
    $db_opcao=3;
    $db_botao=false;
    db_msgbox('Já existe um atendimento para esta requisição!!');
  }
}
*/
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Dados da Requisição</b></legend>
<table border="0">
<tr>
<td nowrap title="<?=@$Tm40_codigo?>">
<b>Código Requisição: </b> 
<?//=@$Lm40_codigo?>
</td>
<td> 
<?
db_input('m40_codigo',10,$Im40_codigo,true,'text',3,"");
db_input('m80_codigo',10,'',true,'hidden',3,"");
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tm40_codigo?>">
<b>Código Atendimento: </b> 
<?//=@$Lm40_codigo?>
</td>
<td> 
<?
db_input('m42_codigo',10,$Im42_codigo,true,'text',3,"")
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tm40_data?>">
<?=@$Lm40_data?>
</td>
<td> 
<?
db_inputdata('m40_data',@$m40_data_dia,@$m40_data_mes,@$m40_data_ano,true,'text',3,"")
?>
</td>
</tr>
<tr>
<td>
<?

$result_almox = $cldb_almox->sql_record($cldb_almox->sql_query_file(null,"*", null, " m91_depto = " . db_getsession("DB_coddepto")));
if ($cldb_almox->numrows == 0) {
	echo "Você só pode acessar essa rotina logado em um almoxarifado!";
	exit;
}

?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tlogin?>">
<?
db_ancora(@$Lm40_login,"js_pesquisalogin(true);",$db_opcao);
?>
</td>
<td> 
<?

db_input('login',10,$Im40_login,true,'text',$db_opcao," onchange='js_pesquisalogin(false);'")
?>
<?
db_input('nome',40,$Inome,true,'text',3,'')
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tm40_depto?>">
<?
db_ancora(@$Lm40_depto,"js_pesquisadepartamento(true);",3);
?>
</td>
<td> 
<?
if ($db_opcao==1){
  if (isset($login)&&$login!=""){
    $result_depusu = $cldb_depusu->sql_record($cldb_depusu->sql_query_departalmox( $login,
                                                                                   db_getsession("DB_coddepto"),
                                                                                   "db_depusu.coddepto as departamento, db_depart.descrdepto",
                                                                                   null,
                                                                                   " db_almox.m91_depto = ".db_getsession("DB_coddepto")."
                                                                                 and db_depusu.id_usuario = {$login}
																																								 and exists (select 1 from db_usuarios where usuarioativo = 1 and id_usuario = {$login})  
                                                                                 and db_depart.instit = ".db_getsession('DB_instit') ) );
    if ($cldb_depusu->numrows>0){
      db_selectrecord('departamento',$result_depusu,true,$db_opcao);
    } else {
			echo "Nenhum departamento disponível!";
		}
  }else{
    echo "<b>Selecione um Usuário</b>";
  }
}else if ($db_opcao==3){
  db_input('departamento',6,$Im40_depto,true,'text',$db_opcao," onchange='js_pesquisadepartamento(false);'");
  db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
}
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tm40_hora?>">
<?=@$Lm40_hora?>
</td>
<td> 
<?
db_input('m40_hora',5,$Im40_hora,true,'text',3,"")
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tm40_obs?>">
<?=@$Lm40_obs?>
</td>
<td> 
<?
db_textarea('m40_obs',10,50,$Im40_obs,true,'text',$db_opcao,"")
?>
</td>
</tr>
</table>
</fieldset>
</td>
</tr>
</table>
</center>
<?
/*if (isset($opcao)){
  $db_opcao=@$opcao;
}*/
if ($cldb_depusu->numrows>0){
?>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Incluir"))?>" <?=($db_opcao==3?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >-->
<?
}
?>
</form>
<script>
function js_pesquisadepartamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true,0);
  }else{
    if(document.form1.departamento.value != ''){ 
      js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.departamento.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
    }else{
      document.form1.descrdepto.value = ''; 
    }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.departamento.focus(); 
    document.form1.departamento.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.departamento.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisalogin(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,0);
  }else{
    if(document.form1.login.value != ''){ 
      js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
    }else{
      document.form1.nome.value = ''; 
    }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.login.focus(); 
    document.form1.login.value = ''; 
  }else{
    document.form1.submit();        
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
  document.form1.submit();        
}
function js_pesquisa(){
	js_OpenJanelaIframe('','db_iframe_matrequi','func_matrequi.php?funcao_js=parent.js_preenchepesquisa|m40_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matrequi.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;\n";
    if($db_opcao==3||$db_opcao==33){
      echo " parent.iframe_matrequiitem.location.href='mat1_matrequiitemalt001.php?m40_codigo='+chave+'&db_opcao=3'; \n";
    }else{
      echo " parent.iframe_matrequiitem.location.href='mat1_matrequiitemalt001.php?m40_codigo='+chave;\n";
    }
    echo " parent.document.formaba.matrequiitem.disabled = false;\n";
  }
  ?>
}
</script>
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

//MODULO: empenho
$clempautoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("pc50_descr");
$clrotulo->label("e57_codhist");
if($db_opcao==1){
  $ac="emp1_empautoriza004.php";
}else if($db_opcao==2 || $db_opcao==22){
  $ac="emp1_empautoriza005.php";
}else if($db_opcao==3 || $db_opcao==33){
  $ac="emp1_empautoriza006.php";
}
?>
<form name="form1" method="post" action="<?=$ac?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te54_autori?>">
       <?=@$Le54_autori?>
    </td>
    <td> 
<?
db_input('e54_autori',6,$Ie54_autori,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_numcgm?>">
       <?
       db_ancora(@$Le54_numcgm,"js_pesquisae54_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e54_numcgm',10,$Ie54_numcgm,true,'text',$db_opcao," onchange='js_pesquisae54_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_codcom?>">
       <?
       db_ancora(@$Le54_codcom,"js_pesquisae54_codcom(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
if(isset($e54_codcom) && $e54_codcom==''){
  $pc50_descr='';
}
  $result=$clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom as e54_codcom,pc50_descr"));
  db_selectrecord("e54_codcom",$result,true,$db_opcao,"","","","0","js_reload(this.value)");

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_tipol?>">
       <?=@$Le54_tipol?>
    </td>
    <td> 
<?
if(isset($tipocompra) || isset($e54_codcom)){
   if(isset($e54_codcom) && empty($tipocompra)){
     $tipocompra=$e54_codcom;
   }
   $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,null,"l03_tipo,l03_descr",'',"l03_codcom=$tipocompra"));
   if($clcflicita->numrows>0){
     db_selectrecord("e54_tipol",$result,true,1,"","","");
     $dop=$db_opcao;
   }else{
     $e54_tipol='';
     $e54_numerl='';
      db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
      $dop='3';
   }  
}else{   
      $dop='3';
     $e54_tipol='';
     $e54_numerl='';
  db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
}  
?>
       <?=@$Le54_numerl?>
<?
db_input('e54_numerl',8,$Ie54_numerl,true,'text',$dop);
?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Te54_codtipo?>">
       <?=$Le54_codtipo?>
    </td>
    <td> 
<?
  $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
  db_selectrecord("e54_codtipo",$result,true,$db_opcao);

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te57_codhist?>">
       <?=$Le57_codhist?>
    </td>
    <td> 
<?
  $result=$clemphist->sql_record($clemphist->sql_query_file(null,"e40_codhist,e40_descr"));
  db_selectrecord("e57_codhist",$result,true,$db_opcao,"","","","Nenhum");

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_destin?>">
       <?=@$Le54_destin?>
    </td>
    <td> 
<?
db_input('e54_destin',40,$Ie54_destin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_resumo?>">
       <?=@$Le54_resumo?>
    </td>
    <td> 
<?
db_textarea('e54_resumo',0,40,$Ie54_resumo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_reload(valor){
  obj=document.createElement('input');
  obj.setAttribute('name','tipocompra');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',valor);
  document.form1.appendChild(obj);
  document.form1.submit();
}
function js_pesquisae54_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,0);
  }else{
     if(document.form1.e54_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e54_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.e54_numcgm.focus(); 
    document.form1.e54_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e54_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisae54_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.e54_login.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.e54_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.e54_login.focus(); 
    document.form1.e54_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.e54_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_empautoriza','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.js_preenchepesquisa|e54_autori','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_empautoriza.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
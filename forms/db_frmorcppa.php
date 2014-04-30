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

//MODULO: orcamento
$clorcppa->rotulo->label();
$clrotulo = new rotulocampo;
$clorcdotacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o54_anousu");
$clrotulo->label("o22_codproduto");
$clrotulo->label("o22_descrprod");
$clrotulo->label("o55_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o56_descr");
$clrotulo->label("o15_descr");
$clrotulo->label("o61_codigo");
$clrotulo->label("o21_codleippa");
$clrotulo->label("o21_descr");
$clrotulo->label("o23_indica");
$clrotulo->label("o10_descr");
      
      
      if($db_opcao==1){
 	   $db_action="orc1_orcppa004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="orc1_orcppa004.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="orc1_orcppa006.php";
      }  

if(isset($atualizar)){
  $o23_orgao='';
  $o23_unidade='';
  $o23_funcao='';
  $o23_subfuncao='';
  $o23_programa='';
  $o23_acao='';
  $o23_elemento='';
  $o23_codigo='';
  $o23_produto='';
  $o22_descprod ='';
  $o40_descr='';
  $o41_descr='';
  $o52_descr='';
  $o53_descr='';
  $o54_descr='';
  $o56_descr='';
  $o55_descr='';
  $o15_descr='';
  $o15_contra_recurso='';

 if($estrutural  ==  ""){
       $tot='0';
 }else{
   $matriz=split('\.',$estrutural); 
   $tot=count($matriz);
 }
 
 for($i=0; $i<$tot; $i++){
   if($matriz[$i]==''){
     continue;  
   }

   
   switch($i){
     case 0://orgao
          $result = $clorcorgao->sql_record($clorcorgao->sql_query_file(db_getsession("DB_anousu"),$matriz[$i],'o40_descr,o40_orgao as o23_orgao'));
          if($clorcorgao->numrows>0){
            db_fieldsmemory($result,0);
	  }else{
	    $o40_descr='Chave ('.$matriz[$i].') não encontrado';
	    $o23_orgao='';
	  }  
	  break;
     case 1://unidade
          if($o23_orgao!=''){  
	    $result = $clorcunidade->sql_record($clorcunidade->sql_query_file(db_getsession("DB_anousu"),$o23_orgao,$matriz[$i],'o41_descr,o41_unidade as o23_unidade'));
	    if($clorcunidade->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $o23_unidade='';
	      $o41_descr='Chave ('.$matriz[$i].') não encontrado';
	    }  
	  }else{
	    $o23_unidade='';
	    $o41_descr='Chave ('.$matriz[$i].') não encontrado';
	  }  
 	  break;
     case 2://funcao
	    $result = $clorcfuncao->sql_record($clorcfuncao->sql_query_file($matriz[$i],'o52_descr,o52_funcao as o23_funcao'));
	    if($clorcfuncao->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $o23_funcao='';
	      $o52_descr='Chave ('.$matriz[$i].') não encontrado';
	    }  
 	  break;
     case 3://subfuncao	
	    $result = $clorcsubfuncao->sql_record($clorcsubfuncao->sql_query_file($matriz[$i],'o53_descr,o53_subfuncao as o23_subfuncao'));
	    if($clorcsubfuncao->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $o23_subfuncao='';
	      $o53_descr='Chave ('.$matriz[$i].') não encontrado';
	      }  
 	  break;
     case 4://programa
	    $result = $clorcprograma->sql_record($clorcprograma->sql_query_file(db_getsession("DB_anousu"),$matriz[$i],'o54_descr,o54_programa as o23_programa'));
	    if($clorcprograma->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $o23_programa='';
	      $o54_descr='Chave ('.$matriz[$i].') não encontrado';
	    }  
	  break;
     case 5://projativ
	    $result = $clorcprojativ->sql_record($clorcprojativ->sql_query_file(db_getsession("DB_anousu"),$matriz[$i],'o55_descr,o55_projativ as o23_acao'));
	    if($clorcprojativ->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $o23_acao='';
	      $o55_descr='Chave ('.$matriz[$i].') não encontrado';
	    }  
	  break;
     case 6://orcproduto
	    $result = $clorcproduto->sql_record($clorcproduto->sql_query_file($matriz[$i],"o22_codproduto as o23_produto, o22_descrprod"));
	    if($clorcproduto->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $o23_produto = '';
	      $o22_descrprod='Chave ('.$matriz[$i].') não encontrado';
	    }  
	  break;
   } 
 }

 
}
      

//esta variavel eh criada quando eh incluido um novo orgao
if(isset($orgao_nov)){
   $o23_orgao = $orgao_nov;
}
if(isset($unidade_nov)){
   $o23_unidade = $unidade_nov;
}
?>


<script>
function js_troca(){

            obj=document.createElement('input');
            obj.setAttribute('name','atualizar');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','true');
            document.form1.appendChild(obj);

  document.form1.submit();
  
}

function js_reload(){
            obj=document.createElement('input');
            obj.setAttribute('name','load');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','true');
            document.form1.appendChild(obj);
  document.form1.submit();
}

/*
function js_ver(){
 var evt = (evt) ? evt : (window.event) ? window.event : "";
 if( evt.charCode  == 13 ){
 }
} 
*/

function js_verificar(){
   return true;
} 
function js_consultar(){
  obj = document.form1;
  query = 'o23_codleippa='+obj.o23_codleippa.value;

  if(obj.o23_orgao.value != '0'){
    query += "&o23_orgao="+obj.o23_orgao.value;
  }
  if(obj.o23_unidade.value != '0' && obj.o23_unidade.value != ''){
    query += "&o23_unidade="+obj.o23_unidade.value;
  }
  if(obj.o23_funcao.value != ''){
    query += "&o23_funcao="+obj.o23_funcao.value;
  }
  if(obj.o23_subfuncao.value != ''){
    query += "&o23_subfuncao="+obj.o23_subfuncao.value;
  }
  if(obj.o23_programa.value != ''){
    query += "&o23_programa="+obj.o23_programa.value;
  }
  if(obj.o23_acao.value != ''){
    query += "&o23_acao="+obj.o23_acao.value;
  }
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcppa','func_orcppa02.php?'+query+'&funcao_js=parent.js_preenchepesquisa|o23_codppa','Pesquisa',true,'0','1','770','390');
}

function js_nov_orgao(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcorgao','orc1_orcorgao001.php?novo=true&funcao_js=parent.js_ret_orgao','Pesquisa',true,'0','1','770','390');
}
function js_ret_orgao(cod,descr){
  obj = document.form1;

      obj=document.createElement('input');
      obj.setAttribute('name','orgao_nov');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value',cod);
      document.form1.appendChild(obj);
      document.form1.submit();

}

function js_nov_unidade(){
  orgao = document.form1.o23_orgao.value;
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','orc1_orcunidade001.php?orgao='+orgao+'&novo=true&funcao_js=parent.js_ret_unidade','Pesquisa',true,'0','1','770','390');
}
function js_ret_unidade(cod,descr){
  obj = document.form1;

      obj=document.createElement('input');
      obj.setAttribute('name','unidade_nov');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value',cod);
      document.form1.appendChild(obj);
      document.form1.submit();

}

function js_nov_funcao(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcfuncao','orc1_orcfuncao001.php?novo=true&funcao_js=parent.js_ret_funcao','Pesquisa',true,'0','1','770','390');
}
function js_ret_funcao(cod,descr){
  obj = document.form1;
  obj.o23_funcao.value = cod;
  obj.o52_descr.value = descr;
}

function js_nov_subfuncao(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcsubfuncao','orc1_orcsubfuncao001.php?novo=true&funcao_js=parent.js_ret_subfuncao','Pesquisa',true,'0','1','770','390');
}
function js_ret_subfuncao(cod,descr){
  obj = document.form1;
  obj.o23_subfuncao.value = cod;
  obj.o53_descr.value = descr;
}

function js_nov_programa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcprograma','orc1_orcprograma001.php?novo=true&funcao_js=parent.js_ret_programa','Pesquisa',true,'0','1','770','390');
}
function js_ret_programa(cod,descr){
  obj = document.form1;
  obj.o23_programa.value = cod;
  obj.o54_descr.value = descr;
}

function js_nov_acao(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcprojativ','orc1_orcprojativ001.php?novo=true&funcao_js=parent.js_ret_acao','Pesquisa',true,'0','1','770','390');
}
function js_ret_acao(cod,descr){
  obj = document.form1;
  obj.o23_acao.value = cod;
  obj.o55_descr.value = descr;
}
function js_nov_produto(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcproduto','orc1_orcproduto001.php?novo=true&funcao_js=parent.js_ret_produto','Pesquisa',true,'0','1','770','390');
}
function js_ret_produto(cod,descr){
  obj = document.form1;
  obj.o23_produto.value = cod;
  obj.o22_descrprod.value = descr;
}
</script>


<form name="form1" method="post" action="<?=$db_action?>"  >
<center>
<table border="0" cellspacing='0' >
<?
 if(empty($o23_codleippa)){
?>
  <tr>
    <td nowrap title="<?=@$To21_codleippa?>">
       <?=$Lo21_codleippa?>
    </td>
    <td> 
<?
  $result=$clorcppalei->sql_record($clorcppalei->sql_query_file(null,"o21_codleippa,o21_descr||' '||o21_anoini||'-'||o21_anofim","o21_codleippa desc"));
  $numrows = $clorcppalei->numrows;
  db_selectrecord("o23_codleippa",$result,true,$db_opcao,"","","","0","js_reload()");

?>
    </td>
  </tr>
<?
 }else{
?>  
  <tr>
    <td nowrap title="<?=@$To23_codppa?>">
       <?=$Lo23_codppa?>
    </td>
    <td> 
       <?
db_input('o23_codppa',4,$Io23_codppa,true,'text',3);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_codleippa?>">
       <?=$Lo23_codleippa?>
    </td>
    <td> 
<?
db_input('o23_codleippa',2,$Io23_codleippa,true,'text',3);
  $result=$clorcppalei->sql_record($clorcppalei->sql_query_file($o23_codleippa,"substr(o21_descr,1,30)||' '||o21_anoini||'-'||o21_anofim as o21_descr"));
  db_fieldsmemory($result,0);
db_input('o21_descr',62,$Io21_descr,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_orgao?>">
      <b> Estrutural</b>     
    </td>
    <td> 
<?
db_input('estrutural',20,0,true,'text',$db_opcao," onchange='js_troca();'")
?>
    </td>
  </tr>
  
  <tr>
    <td><?=$Lo23_orgao?></td>
    <td>
  <?
  $result = $clorcorgao->sql_record($clorcorgao->sql_query(null,null,"o40_orgao,o40_descr","o40_orgao","o40_anousu=".db_getsession("DB_anousu")." and o40_instit=".db_getsession("DB_instit")));
  db_selectrecord("o23_orgao",$result,true,2,"","","","0"," js_reload();");
  ?>
       
       <?
       db_ancora("Novo","js_nov_orgao();",$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td><?=$Lo23_unidade?></td>
    <td>
  <?
  if(isset($o23_orgao)){
    $result = $clorcunidade->sql_record($clorcunidade->sql_query(null,null,null,"o41_unidade,o41_descr||' -'||o41_anousu as o41_descr","o41_unidade","o41_anousu=".db_getsession("DB_anousu")."  and o41_orgao=$o23_orgao " ));
    db_selectrecord("o23_unidade",$result,true,2,"","","",($clorcunidade->numrows>1?"0":""));
  }else{
    db_input("o23_unidade",6,0,true,"hidden",0);
  }
  ?>
       
       <?
       db_ancora("Novo","js_nov_unidade();",$db_opcao);
       ?>
    </td>
  	</tr>
  <tr>
    <td nowrap title="<?=@$To23_funcao?>">
       <?
       db_ancora(@$Lo23_funcao,"js_pesquisao23_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o23_funcao',2,$Io23_funcao,true,'text',$db_opcao," onchange='js_pesquisao23_funcao(false);'")
?>
       <?
db_input('o52_descr',62,$Io52_descr,true,'text',3,'')
       ?>

       <?db_ancora("Novo","js_nov_funcao();",$db_opcao); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_subfuncao?>">
       <?
       db_ancora(@$Lo23_subfuncao,"js_pesquisao23_subfuncao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o23_subfuncao',3,$Io23_subfuncao,true,'text',$db_opcao," onchange='js_pesquisao23_subfuncao(false);'")
?>
       <?
db_input('o53_descr',61,$Io53_descr,true,'text',3,'')
       ?>
       
       <?
       db_ancora("Novo","js_nov_subfuncao();",$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_programa?>">
       <?
       db_ancora(@$Lo23_programa,"js_pesquisao23_programa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o23_programa',4,$Io23_programa,true,'text',$db_opcao," onchange='js_pesquisao23_programa(false);'")
?>
       <?
db_input('o54_descr',60,$Io54_anousu,true,'text',3,'');
       ?>
       <?db_ancora("Novo","js_nov_programa();",$db_opcao); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_indica?>">
       <?
       db_ancora(@$Lo23_indica,"js_pesquisao23_indica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o23_indica',4,$Io23_indica,true,'text',$db_opcao," onchange='js_pesquisao23_indica(false);'")
?>
       <?
db_input('o10_descr',60,$Io10_descr,true,'text',3,'');
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To23_sitinicial?>" valign='top'>
       <?=$Lo23_sitinicial?>
    </td>
    <td> 
       <?
	 db_textarea('o23_sitinicial',1,95,$Io23_sitinicial,true,'text',$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_sitfinal?>" valign='top'>
       <?=$Lo23_sitfinal?>
    </td>
    <td> 
       <?
	 db_textarea('o23_sitfinal',1,95,$Io23_sitfinal,true,'text',$db_opcao);
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To23_acao?>">
       <?
       db_ancora(@$Lo23_acao,"js_pesquisao23_projativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o23_acao',4,$Io23_acao,true,'text',$db_opcao," onchange='js_pesquisao23_projativ(false);'")
?>
       <?
db_input('o55_descr',60,$Io55_descr,true,'text',3,'')
       ?>
       
       <?db_ancora("Novo","js_nov_acao();",$db_opcao); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_acaotxt?>" valign='top'>
       <?=$Lo23_acaotxt?>
    </td>
    <td> 
       <?
	 db_textarea('o23_acaotxt',3,95,$Io23_acaotxt,true,'text',$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To22_codproduto?>">
       <?
       db_ancora(@$Lo22_codproduto,"js_produto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o23_produto',6,$Io22_codproduto,true,'text',$db_opcao," onchange='js_produto(false);'")
?>
       <?
db_input('o22_descrprod',58,$Io22_descrprod,true,'text',3,'')
       ?>
       <?db_ancora("Novo","js_nov_produto();",$db_opcao); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To23_unimed?>">
       <?=$Lo23_unimed?>
    </td>
    <td> 
       <?
db_input('o23_unimed',4,$Io23_unimed,true,'text',$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick=" return js_verificar();"  >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      <input name="consultar" type="button" id="consutar" value="Consultar" onclick="js_consultar();" >
      <input name="novo" type="button" id="novo" value="Novo" onclick="js_novo();" >
    </td>
  </tr>  
<?
 }
?>  
</table>
</center>
</form>
<script>
function js_novo(){
         parent.document.formaba.orcppaval.disabled=true;
  <?
  if(isset($o23_codppa)){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chave_nova=$o23_codppa';";
  }else{
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."';";

  }  
  ?>  
}
function js_produto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcproduto','func_orcproduto.php?funcao_js=parent.js_mostraproduto1|o22_codproduto|o22_descrprod','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcproduto','func_orcproduto.php?pesquisa_chave='+document.form1.o23_produto.value+'&funcao_js=parent.js_mostraproduto','Pesquisa',false,0);
  }
}
function js_mostraproduto(chave,erro){
  document.form1.o22_descrprod.value = chave; 
  if(erro==true){ 
    document.form1.o23_produto.focus(); 
    document.form1.o23_produto.value = ''; 
  }
}
function js_mostraproduto1(chave1,chave2){
  document.form1.o23_produto.value = chave1;
  document.form1.o22_descrprod.value = chave2;
  db_iframe_orcproduto.hide();
}


function js_pesquisao23_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o23_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false,0);
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_orgao.focus(); 
    document.form1.o23_orgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o23_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao23_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o23_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false,0);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_unidade.focus(); 
    document.form1.o23_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o23_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao23_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o23_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false,0);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_unidade.focus(); 
    document.form1.o23_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o23_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao23_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o23_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false,0);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_unidade.focus(); 
    document.form1.o23_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o23_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao23_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcfuncao','func_orcfuncao.php?funcao_js=parent.js_mostraorcfuncao1|o52_funcao|o52_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcfuncao','func_orcfuncao.php?pesquisa_chave='+document.form1.o23_funcao.value+'&funcao_js=parent.js_mostraorcfuncao','Pesquisa',false,0);
  }
}
function js_mostraorcfuncao(chave,erro){
  document.form1.o52_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_funcao.focus(); 
    document.form1.o23_funcao.value = ''; 
  }
}
function js_mostraorcfuncao1(chave1,chave2){
  document.form1.o23_funcao.value = chave1;
  document.form1.o52_descr.value = chave2;
  db_iframe_orcfuncao.hide();
}
function js_pesquisao23_subfuncao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcsubfuncao','func_orcsubfuncao.php?funcao_js=parent.js_mostraorcsubfuncao1|o53_subfuncao|o53_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcsubfuncao','func_orcsubfuncao.php?pesquisa_chave='+document.form1.o23_subfuncao.value+'&funcao_js=parent.js_mostraorcsubfuncao','Pesquisa',false,0);
  }
}
function js_mostraorcsubfuncao(chave,erro){
  document.form1.o53_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_subfuncao.focus(); 
    document.form1.o23_subfuncao.value = ''; 
  }
}
function js_mostraorcsubfuncao1(chave1,chave2){
  document.form1.o23_subfuncao.value = chave1;
  document.form1.o53_descr.value = chave2;
  db_iframe_orcsubfuncao.hide();
}
function js_pesquisao23_programa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o23_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false,0);
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_programa.focus(); 
    document.form1.o54_descr.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o23_programa.value = chave1;
  document.form1.o54_descr.value = chave2;
  db_iframe_orcprograma.hide();
}

function js_pesquisao23_indica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcindica','func_orcindica.php?funcao_js=parent.js_mostraorcindica1|o10_indica|o10_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcindica','func_orcindica.php?pesquisa_chave='+document.form1.o10_indica.value+'&funcao_js=parent.js_mostraorcindica','Pesquisa',false,0);
  }
}
function js_mostraorcindica(chave,erro){
  document.form1.o10_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_indica.focus(); 
    document.form1.o10_descr.value = ''; 
  }
}
function js_mostraorcindica1(chave1,chave2){
  document.form1.o23_indica.value = chave1;
  document.form1.o10_descr.value = chave2;
  db_iframe_orcindica.hide();
}

function js_pesquisao23_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o23_acao.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false,0);
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o23_acao.focus(); 
    document.form1.o23_acao.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o23_acao.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcppa','db_iframe_orcppa','func_orcppa.php?funcao_js=parent.js_preenchepesquisa|o23_codppa','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
    db_iframe_orcppa.hide();
    <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    ?>
}
</script>
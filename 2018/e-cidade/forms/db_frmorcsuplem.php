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
$clorcsuplem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o48_descr");
$clrotulo->label("o39_texto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To46_codsup?>">
       <?=@$Lo46_codsup?>
    </td>
    <td> 
     <? db_input('o46_codsup',4,$Io46_codsup,true,'text',3,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap ><b> Projeto </b> </td>
    <td> 
       <? db_input('o46_codlei',4,$Io46_codlei,true,'text',3) ?>
       <? db_input('o39_descr',60,'',true,'text',3) ?>

    </td>
  </tr>
 
  <tr>
    <td nowrap title="<?=@$To46_tiposup?>"><b> Tipo </b></td>
    <td> <? 
      	  if ($db_opcao==1){
              $rtipo = $clorcsuplemtipo->sql_record($clorcsuplemtipo->sql_query_file("","o48_tiposup as o46_tiposup,o48_descr","o48_tiposup"));  
	      db_fieldsmemory($rtipo,0);
              db_selectrecord("o46_tiposup",$rtipo,false,$db_opcao);
	  } else {  
              db_input('o46_tiposup',6,'',true,'true',3);
	  } 
	 ?>
    </td>
  </tr>
 
  
  <tr>
    <td nowrap title="<?=@$To46_data?>">
       <?=@$Lo46_data?>
    </td>
    <td> <? db_inputdata('o46_data',@$o46_data_dia,@$o46_data_mes,@$o46_data_ano,true,'text',$db_opcao,"") ?>  </td>
  </tr>

  <tr>
    <td> &nbsp;  </td>
    <td> &nbsp;</td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To46_obs?>"> &nbsp;  </td>
    <td><font color=red> <i> * O texto abaixo será no Segundo Artigo do Projeto    </i></font></td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To39_texto?>"> <?=@$Lo39_texto?>  </td>
    <td> 
        <? 
           @$ro = $clorcprojeto->sql_record($clorcprojeto->sql_query_file($o46_codlei,"o39_texto"));	   
	   //db_criatabela($ro);
	   if (@pg_numrows($ro)>0){
             @db_fieldsmemory($ro,0);
 	   }  
	   if ($o39_texto ==""){
             $o39_texto = "Art 2. -  Para cobertura do Crédito aberto de acordo com o Art 1.,"; 
             $o39_texto.= " será usado como recurso as seguintes reduções orçamentárias:   ";
	   }  
	   db_textarea('o39_texto',7,60,$Io39_texto,true,'text',1); ?>
    </td>
  </tr>
  </table>
  </center>

 <? if (($db_opcao==2)||($db_opcao==3))   { ?>
  <input name="alterar" type="submit" value="Alterar">
 <? } ?> 
 
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

<!--
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
 -->
</form>
<script>
function js_pesquisao46_tiposup(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplemtipo','func_orcsuplemtipo.php?funcao_js=parent.js_mostraorcsuplemtipo1|o48_tiposup|o48_descr','Pesquisa',true);
  }else{
     if(document.form1.o46_tiposup.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplemtipo','func_orcsuplemtipo.php?pesquisa_chave='+document.form1.o46_tiposup.value+'&funcao_js=parent.js_mostraorcsuplemtipo','Pesquisa',false);
     }else{
       document.form1.o48_descr.value = ''; 
     }
  }
}
function js_mostraorcsuplemtipo(chave,erro){
  document.form1.o48_descr.value = chave; 
  if(erro==true){ 
    document.form1.o46_tiposup.focus(); 
    document.form1.o46_tiposup.value = ''; 
  }
}
function js_mostraorcsuplemtipo1(chave1,chave2){
  document.form1.o46_tiposup.value = chave1;
  document.form1.o48_descr.value = chave2;
  db_iframe_orcsuplemtipo.hide();
}
function js_pesquisao46_codlei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto_np.php?funcao_js=parent.js_mostraorcprojeto1|o39_codproj|o39_descr','Pesquisa',true);
  }
}
function js_mostraorcprojeto1(chave1,chave2){
  document.form1.o46_codlei.value = chave1;
  document.form1.o39_descr.value = chave2; 
  db_iframe_orcprojeto.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?o46_codlei='+chave1+'&o39_descr='+chave2+'&passou=true'";
  ?>

}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplem','func_orcsuplem.php?funcao_js=parent.js_preenchepesquisa|o46_codsup','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcsuplem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
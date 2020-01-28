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

//MODULO: recursoshumanos
$clrhestagioagenda->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h50_sequencial");
$clrotulo->label("z01_nome");
$clrotulo->label("rh01_admiss");
$btnOkOnclick = null;
      if($db_opcao==1){
 	   $db_action="rec1_rhestagioagenda004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="rec1_rhestagioagenda005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	      $db_action="rec1_rhestagioagenda006.php";
        $btnOkOnclick = "onclick='confirm(\"Excluir o Agendamento do Estágio ira excluir todo as avaliações realizadas;\\nConfirma? \")'"; 
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table>
  <tr>
     <td>
       <fieldset> 
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th57_sequencial?>">
       <?=@$Lh57_sequencial?>
    </td>
    <td> 
<?
db_input('h57_sequencial',10,$Ih57_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th57_rhestagio?>">
       <?
       db_ancora(@$Lh57_rhestagio,"js_pesquisah57_rhestagio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h57_rhestagio',10,$Ih57_rhestagio,true,'text',$db_opcao," onchange='js_pesquisah57_rhestagio(false);'")
?>
       <?
db_input('h50_descr',40,$Ih50_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th57_regist?>">
       <?
       db_ancora(@$Lh57_regist,"js_pesquisah57_regist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h57_regist',10,$Ih57_regist,true,'text',$db_opcao," onchange='js_pesquisah57_regist(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh01_admiss?>">
       <?=@$Lrh01_admiss?>
    </td>
    <td> 
<?
db_inputData('rh01_admiss',@$rh01_admiss_dia,@$rh01_admiss_mes,@$rh01_admiss_ano,$Irh01_admiss,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=$btnOkOnclick?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah57_rhestagio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagioagenda','db_iframe_rhestagio','func_rhestagio.php?funcao_js=parent.js_mostrarhestagio1|h50_sequencial|h50_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.h57_rhestagio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagioagenda','db_iframe_rhestagio','func_rhestagio.php?pesquisa_chave='+document.form1.h57_rhestagio.value+'&funcao_js=parent.js_mostrarhestagio','Pesquisa',false,'0');
     }else{
       document.form1.h50_descr.value = ''; 
     }
  }
}
function js_mostrarhestagio(chave,erro){
  document.form1.h50_descr.value = chave; 
  if(erro==true){ 
    document.form1.h57_rhestagio.focus(); 
    document.form1.h57_rhestagio.value = ''; 
  }
}
function js_mostrarhestagio1(chave1,chave2){
  document.form1.h57_rhestagio.value = chave1;
  document.form1.h50_descr.value = chave2;
  db_iframe_rhestagio.hide();
}
function js_pesquisah57_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagioagenda','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome|rh01_admiss','Pesquisa',true,'0');
  }else{
     if(document.form1.h57_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagioagenda','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h57_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false,0);
     }else{
       document.form1.rh01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro,chave2){
  document.form1.z01_nome.value = chave; 
  document.form1.rh01_admiss.value = chave2; 
  if(erro==true){ 
    document.form1.h57_regist.focus(); 
    document.form1.h57_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2,chave3){
  document.form1.h57_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  data = chave3.split("-");
  document.form1.rh01_admiss.value = data[2]+"/"+data[1]+"/"+data[0]; 
  document.form1.rh01_admiss_dia.value = data[2]; 
  document.form1.rh01_admiss_mes.value = data[1]; 
  document.form1.rh01_admiss_ano.value = data[0]; 
  db_iframe_rhpessoal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_rhestagioagenda','db_iframe_rhestagioagenda','func_rhestagioagenda.php?funcao_js=parent.js_preenchepesquisa|h57_sequencial','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagioagenda.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
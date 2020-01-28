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

//MODULO: saude
$clsau_procsiasih->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd92_c_nome");
$clrotulo->label("sd93_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd94_i_codigo?>">
       <?=@$Lsd94_i_codigo?>
    </td>
    <td>
<?
db_input('sd94_i_codigo',5,$Isd94_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd94_i_procedimento?>">
       <?
       db_ancora(@$Lsd94_i_procedimento,"js_pesquisasd94_i_procedimento(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd94_i_procedimento',5,$Isd94_i_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd94_i_procedimento(false);'")
?>
       <?
db_input('sd63_c_nome',60,$Isd63_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd94_i_siasih?>">
       <?
       db_ancora(@$Lsd94_i_siasih,"js_pesquisasd94_i_siasih(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd94_i_siasih',5,$Isd94_i_siasih,true,'text',$db_opcao," onchange='js_pesquisasd94_i_siasih(false);'")
?>
       <?
db_input('sd92_c_nome',60,$Isd92_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd94_i_tipoproc?>">
       <?=@$Lsd94_i_tipoproc?>
    </td>
    <td>
       <?
       include("classes/db_sau_tipoproc_classe.php");
       $clsau_tipoproc = new cl_sau_tipoproc;
       $result = $clsau_tipoproc->sql_record($clsau_tipoproc->sql_query("","*"));
       db_selectrecord("sd94_i_tipoproc",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd94_i_anocomp?>">
       <?=@$Lsd94_i_anocomp?>/<?=@$Lsd94_i_mescomp?>
    </td>
    <td>
<?
db_input('sd94_i_anocomp',4,$Isd94_i_anocomp,true,'text',$db_opcao,""); echo "/";
db_input('sd94_i_mescomp',2,$Isd94_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd94_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procedimento1|sd63_i_codigo|sd63_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd94_i_procedimento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?pesquisa_chave='+document.form1.sd94_i_procedimento.value+'&funcao_js=parent.js_mostrasau_procedimento','Pesquisa',false);
     }else{
       document.form1.sd63_c_nome.value = '';
     }
  }
}
function js_mostrasau_procedimento(chave,erro){
  document.form1.sd63_c_nome.value = chave;
  if(erro==true){
    document.form1.sd94_i_procedimento.focus();
    document.form1.sd94_i_procedimento.value = '';
  }
}
function js_mostrasau_procedimento1(chave1,chave2){
  document.form1.sd94_i_procedimento.value = chave1;
  document.form1.sd63_c_nome.value = chave2;
  db_iframe_sau_procedimento.hide();
}
function js_pesquisasd94_i_siasih(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_siasih','func_sau_siasih.php?funcao_js=parent.js_mostrasau_siasih1|sd92_i_codigo|sd92_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd94_i_siasih.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_siasih','func_sau_siasih.php?pesquisa_chave='+document.form1.sd94_i_siasih.value+'&funcao_js=parent.js_mostrasau_siasih','Pesquisa',false);
     }else{
       document.form1.sd92_c_nome.value = '';
     }
  }
}
function js_mostrasau_siasih(chave,erro){
  document.form1.sd92_c_nome.value = chave;
  if(erro==true){
    document.form1.sd94_i_siasih.focus();
    document.form1.sd94_i_siasih.value = '';
  }
}
function js_mostrasau_siasih1(chave1,chave2){
  document.form1.sd94_i_siasih.value = chave1;
  document.form1.sd92_c_nome.value = chave2;
  db_iframe_sau_siasih.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_procsiasih','func_sau_procsiasih.php?funcao_js=parent.js_preenchepesquisa|sd94_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_procsiasih.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
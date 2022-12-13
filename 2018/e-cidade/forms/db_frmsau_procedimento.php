<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
$clsau_procedimento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd69_c_nome");
$clrotulo->label("sd65_c_nome");
$clrotulo->label("sd64_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd63_i_codigo?>">
       <?=@$Lsd63_i_codigo?>
    </td>
    <td>
<?
db_input('sd63_i_codigo',5,$Isd63_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_c_procedimento?>">
       <?=@$Lsd63_c_procedimento?>
    </td>
    <td>
<?
db_input('sd63_c_procedimento',10,$Isd63_c_procedimento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_c_nome?>">
       <?=@$Lsd63_c_nome?>
    </td>
    <td>
<?
db_input('sd63_c_nome',50,$Isd63_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_complexidade?>">
       <?=@$Lsd63_i_complexidade?>
    </td>
    <td>
       <?
       include("classes/db_sau_complexidade_classe.php");
       $clsau_complexidade = new cl_sau_complexidade;
       $result = $clsau_complexidade->sql_record($clsau_complexidade->sql_query("","*"));
       db_selectrecord("sd63_i_complexidade",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_c_sexo?>">
       <?=@$Lsd63_c_sexo?>
    </td>
    <td>
<?
$x = array('F'=>'Feminino','I'=>'Indiferente/Ambos','M'=>'Masculino','N'=>'Não se Aplica');
db_select('sd63_c_sexo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_execucaomax?>">
       <?=@$Lsd63_i_execucaomax?>
    </td>
    <td>
<?
db_input('sd63_i_execucaomax',4,$Isd63_i_execucaomax,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_maxdias?>">
       <?=@$Lsd63_i_maxdias?>
    </td>
    <td>
<?
db_input('sd63_i_maxdias',4,$Isd63_i_maxdias,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_pontos?>">
       <?=@$Lsd63_i_pontos?>
    </td>
    <td>
<?
db_input('sd63_i_pontos',4,$Isd63_i_pontos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_idademin?>">
       <?=@$Lsd63_i_idademin?>
    </td>
    <td>
<?
db_input('sd63_i_idademin',4,$Isd63_i_idademin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_idademax?>">
       <?=@$Lsd63_i_idademax?>
    </td>
    <td>
<?
db_input('sd63_i_idademax',4,$Isd63_i_idademax,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_f_sh?>">
       <?=@$Lsd63_f_sh?>
    </td>
    <td>
<?
db_input('sd63_f_sh',10,$Isd63_f_sh,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_f_sa?>">
       <?=@$Lsd63_f_sa?>
    </td>
    <td>
<?
db_input('sd63_f_sa',10,$Isd63_f_sa,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_f_sp?>">
       <?=@$Lsd63_f_sp?>
    </td>
    <td>
<?
db_input('sd63_f_sp',10,$Isd63_f_sp,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_financiamento?>">
       <?=@$Lsd63_i_financiamento?>
    </td>
    <td>
       <?
       include("classes/db_sau_financiamento_classe.php");
       $clsau_financiamento = new cl_sau_financiamento;
       $result = $clsau_financiamento->sql_record($clsau_financiamento->sql_query("","sd65_i_codigo, sd65_c_nome",""));
       db_selectrecord("sd63_i_financiamento",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_rubrica?>">
       <?
       db_ancora(@$Lsd63_i_rubrica,"js_pesquisasd63_i_rubrica(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd63_i_rubrica',5,$Isd63_i_rubrica,true,'text',$db_opcao," onchange='js_pesquisasd63_i_rubrica(false);'")
?>
       <?
db_input('sd64_c_nome',50,$Isd64_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd63_i_anocomp?>">
       <?=@$Lsd63_i_anocomp?>/<?=@$Lsd63_i_mescomp?>
    </td>
    <td>
<?
db_input('sd63_i_anocomp',4,$Isd63_i_anocomp,true,'text',$db_opcao,""); echo '/';
db_input('sd63_i_mescomp',2,$Isd63_i_mescomp,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd63_i_complexidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_complexidade','func_sau_complexidade.php?funcao_js=parent.js_mostrasau_complexidade1|sd69_i_codigo|sd69_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd63_i_complexidade.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_complexidade','func_sau_complexidade.php?pesquisa_chave='+document.form1.sd63_i_complexidade.value+'&funcao_js=parent.js_mostrasau_complexidade','Pesquisa',false);
     }else{
       document.form1.sd69_c_nome.value = '';
     }
  }
}
function js_mostrasau_complexidade(chave,erro){
  document.form1.sd69_c_nome.value = chave;
  if(erro==true){
    document.form1.sd63_i_complexidade.focus();
    document.form1.sd63_i_complexidade.value = '';
  }
}
function js_mostrasau_complexidade1(chave1,chave2){
  document.form1.sd63_i_complexidade.value = chave1;
  document.form1.sd69_c_nome.value = chave2;
  db_iframe_sau_complexidade.hide();
}
function js_pesquisasd63_i_financiamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_financiamento','func_sau_financiamento.php?funcao_js=parent.js_mostrasau_financiamento1|sd65_i_codigo|sd65_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd63_i_financiamento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_financiamento','func_sau_financiamento.php?pesquisa_chave='+document.form1.sd63_i_financiamento.value+'&funcao_js=parent.js_mostrasau_financiamento','Pesquisa',false);
     }else{
       document.form1.sd65_c_nome.value = '';
     }
  }
}
function js_mostrasau_financiamento(chave,erro){
  document.form1.sd65_c_nome.value = chave;
  if(erro==true){
    document.form1.sd63_i_financiamento.focus();
    document.form1.sd63_i_financiamento.value = '';
  }
}
function js_mostrasau_financiamento1(chave1,chave2){
  document.form1.sd63_i_financiamento.value = chave1;
  document.form1.sd65_c_nome.value = chave2;
  db_iframe_sau_financiamento.hide();
}
function js_pesquisasd63_i_rubrica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_rubrica','func_sau_rubrica.php?funcao_js=parent.js_mostrasau_rubrica1|sd64_i_codigo|sd64_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd63_i_rubrica.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_rubrica','func_sau_rubrica.php?pesquisa_chave='+document.form1.sd63_i_rubrica.value+'&funcao_js=parent.js_mostrasau_rubrica','Pesquisa',false);
     }else{
       document.form1.sd64_c_nome.value = '';
     }
  }
}
function js_mostrasau_rubrica(chave,erro){
  document.form1.sd64_c_nome.value = chave;
  if(erro==true){
    document.form1.sd63_i_rubrica.focus();
    document.form1.sd63_i_rubrica.value = '';
  }
}
function js_mostrasau_rubrica1(chave1,chave2){
  document.form1.sd63_i_rubrica.value = chave1;
  document.form1.sd64_c_nome.value = chave2;
  db_iframe_sau_rubrica.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_procedimento','func_sau_procedimento.php?funcao_js=parent.js_preenchepesquisa|sd63_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_procedimento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
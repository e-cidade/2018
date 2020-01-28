<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: secretariadeeducacao
$cltermoresultadofinal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed10_i_codigo");
$clrotulo->label("ed10_c_descr");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend><b>Termo de Resultado Final</b></legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Ted110_ensino?>">
           <?
             db_input('ed110_sequencial',10,$Ied110_sequencial,true,'hidden',$db_opcao,"");
             db_ancora(@$Led110_ensino,"js_pesquisaed110_ensino(true);",$db_opcao);
           ?>
        </td>
        <td>
          <?
            db_input('ed110_ensino', 10,$Ied110_ensino, true, 'text',
                     $db_opcao," onchange='js_pesquisaed110_ensino(false);'");
            db_input('ed10_c_descr', 26,$Ied10_i_codigo, true, 'text', 3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted110_descricao?>">
           <?=@$Led110_descricao?>
        </td>
        <td>
          <?
            db_input('ed110_descricao',40,$Ied110_descricao,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted110_abreviatura?>">
           <?=@$Led110_abreviatura?>
        </td>
        <td>
          <?
            db_input('ed110_abreviatura', 10, $Ied110_abreviatura,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted110_referencia?>">
           <?=@$Led110_referencia?>
        </td>
        <td>
          <?
            $x = array('A'=>'Progressão','P'=>'Parcialmente Aprovado','R'=>'Retenção');
            db_select('ed110_referencia',$x,true,$db_opcao, "style='width:100%;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted110_ano?>">
           <?=@$Led110_ano?>
        </td>
        <td>
          <?
            db_input('ed110_ano', 10, $Ied110_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed110_ensino(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ensino','func_ensino.php?funcao_js=parent.js_mostraensino1|ed10_i_codigo|ed10_c_descr','Pesquisa',true);
  }else{
     if(document.form1.ed110_ensino.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_ensino','func_ensino.php?pesquisa_chave='+document.form1.ed110_ensino.value+'&funcao_js=parent.js_mostraensino','Pesquisa',false);
     }else{
       document.form1.ed10_i_codigo.value = '';
     }
  }
}
function js_mostraensino(chave,erro){
  document.form1.ed10_c_descr.value = chave;
  if(erro==true){
    document.form1.ed110_ensino.focus();
    document.form1.ed110_ensino.value = '';
  }
}
function js_mostraensino1(chave1,chave2){
  document.form1.ed110_ensino.value = chave1;
  document.form1.ed10_c_descr.value = chave2;
  db_iframe_ensino.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_termoresultadofinal','func_termoresultadofinal.php?funcao_js=parent.js_preenchepesquisa|ed110_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_termoresultadofinal.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
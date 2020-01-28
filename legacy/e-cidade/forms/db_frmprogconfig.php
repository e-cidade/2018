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

//MODULO: educação
$clprogconfig->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted110_i_codigo?>">
   <?=@$Led110_i_codigo?>
  </td>
  <td>
   <?db_input('ed110_i_codigo',10,$Ied110_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_inicio?>">
   <?=@$Led110_i_inicio?>
  </td>
  <td>
   <?db_input('ed110_i_inicio',10,$Ied110_i_inicio,true,'text',$db_opcao,"")?> <b>anos</b>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_intervalo?>">
   <?=@$Led110_i_intervalo?>
  </td>
  <td>
   <?db_input('ed110_i_intervalo',10,$Ied110_i_intervalo,true,'text',$db_opcao,"")?> <b>anos</b>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_ptgeral?>">
   <?=@$Led110_i_ptgeral?>
  </td>
  <td>
   <?db_input('ed110_i_ptgeral',10,$Ied110_i_ptgeral,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_ptantiguidade?>">
   <?=@$Led110_i_ptantiguidade?>
  </td>
  <td>
   <?db_input('ed110_i_ptantiguidade',10,$Ied110_i_ptantiguidade,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_ptconvocacao?>">
   <?=@$Led110_i_ptconvocacao?>
  </td>
  <td>
   <?db_input('ed110_i_ptconvocacao',10,$Ied110_i_ptconvocacao,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_ptavaladmin?>">
   <?=@$Led110_i_ptavaladmin?>
  </td>
  <td>
   <?db_input('ed110_i_ptavaladmin',10,$Ied110_i_ptavaladmin,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_ptavalpedag?>">
   <?=@$Led110_i_ptavalpedag?>
  </td>
  <td>
   <?db_input('ed110_i_ptavalpedag',10,$Ied110_i_ptavalpedag,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_ptconhecimento?>">
   <?=@$Led110_i_ptconhecimento?>
  </td>
  <td>
   <?db_input('ed110_i_ptconhecimento',10,$Ied110_i_ptconhecimento,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_numfaltas?>">
   <?=@$Led110_i_numfaltas?>
  </td>
  <td>
   <?db_input('ed110_i_numfaltas',10,$Ied110_i_numfaltas,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_numsuspdisc?>">
   <?=@$Led110_i_numsuspdisc?>
  </td>
  <td>
   <?db_input('ed110_i_numsuspdisc',10,$Ied110_i_numsuspdisc,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted110_i_numadvert?>">
   <?=@$Led110_i_numadvert?>
  </td>
  <td>
   <?db_input('ed110_i_numadvert',10,$Ied110_i_numadvert,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_progconfig','func_progconfig.php?funcao_js=parent.js_preenchepesquisa|ed110_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_progconfig.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>
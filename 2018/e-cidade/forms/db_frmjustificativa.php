<?php
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

//MODULO: Educação
$oDaoJustificativa->rotulo->label();
?>
<form name="form1" method="post" action="">
 <center>
  <table border="0">
   <tr>
    <td nowrap title="<?=@$Ted06_i_codigo?>">
     <?=@$Led06_i_codigo?>
    </td>
    <td>
     <?db_input('ed06_i_codigo',15,$Ied06_i_codigo,true,'text',3,"")?>
    </td>
   </tr>
   
   <tr>
    <td nowrap title="<?=@$Ted06_abreviatura?>">
     <?=@$Led06_abreviatura?>
    </td>
    <td>
     <?db_input('ed06_abreviatura', 3,$Ied06_abreviatura,true,'text',$db_opcao,"")?>
    </td>
   </tr>
   
   <tr>
    <td nowrap title="<?=@$Ted06_c_descr?>">
     <?=@$Led06_c_descr?>
    </td>
    <td>
     <?db_input('ed06_c_descr',100,$Ied06_c_descr,true,'text',$db_opcao,"")?>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Ted06_c_ativo?>">
     <?=@$Led06_c_ativo?>
    </td>
    <td>
     <?
      $x = array('S'=>'SIM','N'=>'NÃO');
      db_select('ed06_c_ativo',$x,true,$db_opcao,"");
     ?>
   </td>
  </tr>
 </table>
 </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
         type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  <input name="novoRegistro" type="button" id="novoRegistro" value="Novo Registro" onclick="js_novoRegistro();"
         <?=($db_opcao == 1 ? "disabled" : "")?> />
</form>
<script>

function js_pesquisa() {
	
  js_OpenJanelaIframe('','db_iframe_justificativa',
		              'func_justificativa.php?funcao_js=parent.js_preenchepesquisa|ed06_i_codigo',
		              'Pesquisa Justificativas',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_justificativa.hide();
  <?
   if ($db_opcao != 1) {
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
 ?>
 
}

function js_novoRegistro() {

  location.href = "edu1_justificativa001.php";

}

function js_novo() {
  parent.location.href="edu1_justificativaabas001.php";
}
</script>
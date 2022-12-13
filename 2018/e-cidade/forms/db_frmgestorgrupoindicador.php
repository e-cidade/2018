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

//MODULO: Gestor BI
$clgestorgrupoindicador->rotulo->label();
require_once ('libs/db_libdicionario.php');
?>
<form name="form1" method="post" action="">
<center>

<table style="margin-top:15px;">
<tr><td align=center>

<fieldset>
<legend><b>Grupo de Indices</b></legend>

<table border="0" >
  <tr>
    <td nowrap title="<?=@$Tg03_sequencial?>">
       <?=@$Lg03_sequencial?>
    </td>
    <td> 
<?
db_input('g03_sequencial',10,$Ig03_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg03_descricao?>">
       <?=@$Lg03_descricao?>
    </td>
    <td> 
       <?db_input('g03_descricao',40,$Ig03_descricao,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tg03_datalimite?>">
       <?=@$Lg03_datalimite?>
    </td>
    <td> 
       <?db_inputdata('g03_datalimite',@$g03_datalimite_dia,@$g03_datalimite_mes,@$g03_datalimite_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?= @$Tg03_projeto ?>" >
      <?=@$Lg03_projeto ?>
    </td>
    <td>
      <?
        $x = getValoresPadroesCampo('g03_projeto');
        db_select('g03_projeto',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>
  </fieldset>
  
</td></tr>
</table>

  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_gestorgrupoindicador','func_gestorgrupoindicador.php?funcao_js=parent.js_preenchepesquisa|g03_sequencial|g03_descricao','Pesquisa',true);
}

function js_preenchepesquisa(chave,chave2){

  db_iframe_gestorgrupoindicador.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
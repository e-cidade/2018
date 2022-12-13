<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: issqn
$clcnae->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<fieldset><legend><b>Cadastro de CNAE</b></legend>
<table border="0">
  <tr>
     <td align="left"><b>Tipo:</b></td>
       <td align="left" > 
         <?
           $arraymostra = array("" =>"- Selecione -", "S"=> "Sintética ", "A" => "Analítica ");
           db_select("Tipo",$arraymostra,1,1,"");
         ?>
      </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tq71_sequencial?>">
       <?=@$Lq71_sequencial?>
    </td>
    <td> 
<?
db_input('q71_sequencial',10,$Iq71_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq71_estrutural?>">
       <?=@$Lq71_estrutural?>
    </td>
    <td> 
<?
db_input('q71_estrutural',10,$Iq71_estrutural,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq71_descr?>">
       <?=@$Lq71_descr?>
    </td>
    <td> 
<?
db_input('q71_descr',80,$Iq71_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
 </fieldset>  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
                 type="submit" id="db_opcao" 
                 onclick="return js_validaTipo()"
                 value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
                 <?=($db_botao==false?"disabled":"")?> >
                 
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_validaTipo(){
  if( document.getElementById('Tipo').value == '' ){
    alert('É preciso informar o tipo de cadastro para o CNAE');
    return false;
  }
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cnae','func_cnae.php?funcao_js=parent.js_preenchepesquisa|q71_sequencial|q72_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave, chave2){
  db_iframe_cnae.hide();
   
  <?
  if($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chave2='+chave2";
  }
  ?>
}
</script>
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


require_once("libs/db_utils.php");

require_once("classes/db_acordocomissao_classe.php");
require_once("classes/db_acordocomissaomembro_classe.php");

require_once("model/AcordoComissao.model.php");
require_once("model/AcordoComissaoMembro.model.php");


//MODULO: Acordos
$clacordocomissao->rotulo->label();
      if($db_opcao==1){
 	   $db_action="aco1_acordocomissao004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="aco1_acordocomissao005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="aco1_acordocomissao006.php";
      }  
?>

<form class="container" name="form1" method="post" action="<?=$db_action?>">
  <fieldset>
    <legend>Comissão</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tac08_sequencial?>">
          <?=@$Lac08_sequencial?>
        </td>
        <td> 
          <?
            db_input('ac08_sequencial',10,$Iac08_sequencial,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tac08_descricao?>">
          <?=@$Lac08_descricao?>
        </td>
        <td> 
          <?
            db_input('ac08_descricao',44,$Iac08_descricao,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tac08_datainicial?>">
          <?=@$Lac08_datainicial?>
        </td>
        <td> 
          <?
            db_inputdata('ac08_datainicial',@$ac08_datainicial_dia,@$ac08_datainicial_mes,@$ac08_datainicial_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tac08_datafim?>">
          <?=@$Lac08_datafim?>
        </td>
        <td> 
          <?
            db_inputdata('ac08_datafim',@$ac08_datafim_dia,@$ac08_datafim_mes,@$ac08_datafim_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <legend><?=@$Lac08_observacao?></legend>
        		<?
        		  db_textarea('ac08_observacao',3,48,$Iac08_observacao,true,'text',$db_opcao,"")
        		?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_acordocomissao','db_iframe_acordocomissao','func_acordocomissao.php?funcao_js=parent.js_preenchepesquisa|ac08_sequencial','Pesquisa',true,'0','1');
}

function js_preenchepesquisa(chave){
  db_iframe_acordocomissao.hide();

  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    //echo "js_consultaAcordo(chave)";
  }
  ?>
}

</script>
<script>

$("ac08_sequencial").addClassName("field-size2");
$("ac08_descricao").addClassName("field-size9");
$("ac08_datainicial").addClassName("field-size2");
$("ac08_datafim").addClassName("field-size2");

</script>
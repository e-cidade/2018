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


//MODULO: patrim
$clbensplaca->rotulo->label();
$clbens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("");

$result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
if ($clcfpatriplaca->numrows > 0) {
	db_fieldsmemory($result, 0);
}
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Procedimentos - Alterar Placa</legend>
    <table class="form-container">
      <tr>
        <td  title="<?=@$Tt52_bem?>">
          <?=@$Lt52_bem?>
        </td>
        <td> 
          <?
            db_input('t52_bem', 8, $It52_bem, true, 'text', 3, "")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt52_descr?>">
          <?=@$Lt52_descr?>
        </td>
        <td> 
          <?
            db_input('t52_descr', 51, $It52_descr, true, 'text', 3, "")
          ?>
        </td>
      </tr>
    <tr>
        <td title="<?=@$Tt52_ident?>">     
          <?
            db_input('t52_bem', 8, "", true, 'hidden', 3, "");
            $fj = "";
            if ($t07_confplaca == 1 || $t07_confplaca == 2) {
            	$opc = 3;
            }
            if ($t07_confplaca == 3) {
          ?>
    	    <?db_ancora(@$Lt52_ident,"js_pesquisa_texto(true);",3); ?>
          <?$fj="onchange='js_buscplaca(this.value);'";
            }else{?>	
          <?=@$Lt52_ident?>&nbsp;&nbsp;
          <?}?>
        </td>
        <td>
          <? 
          	db_input('t52_ident', 20, $It52_ident, true, 'text', $opc, $fj);
          	if ($t07_confplaca == 3 || $t07_confplaca == 2) {
          		db_input('t52_ident_seq', 8,$It41_placaseq, true, 'text', 1, "");
          	}
          	$placa_ant = @$t52_ident;
          	db_input('placa_ant', 20,"", true, 'hidden', 3, "");
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt41_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Lt41_obs?></legend>
            <?
              db_textarea('t41_obs',3,48,$It41_obs,true,'text',1)
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
  js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_preenchepesquisa|t52_bem','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bens.hide();
  <?


	if ($db_opcao != 1) {
		echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	}
?>
}
</script>
<script>

$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size9");
$("t52_ident").addClassName("field-size4");

</script>
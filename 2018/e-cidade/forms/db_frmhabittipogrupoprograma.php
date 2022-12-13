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

//MODULO: Habitacao
$clhabittipogrupoprograma->rotulo->label();
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Dados Tipo Grupo</b></legend>
<table border="0" align="center">
  <tr>
    <td nowrap title="<?=@$Tht02_sequencial?>">
      <b>Código:</b>
    </td>
    <td> 
			<?
			  db_input('ht02_sequencial',10,$Iht02_sequencial,true,'text',3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tht02_descricao?>">
      <?=@$Lht02_descricao?>
    </td>
    <td> 
			<?
			  db_input('ht02_descricao',52,$Iht02_descricao,true,'text',3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="2">
      <fieldset>
      <legend><?=@$Lht02_obs?></legend>
      <table>
			  <tr>
			    <td nowrap title="<?=@$Tht02_obs?>"> 
			      <?
			        db_textarea('ht02_obs',8,60,$Iht02_obs,true,'text',3,"");
			      ?>
			    </td>
			  </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="2">
      <fieldset class="fieldsetinterno">
        <table align="left" cellpadding="0" cellspacing="0" border="0" width="100%">
				  <tr>
				    <td nowrap title="<?=@$Tht02_datainicial?>">
				      <?=@$Lht02_datainicial?>
				    </td>
				    <td> 
				      <?
				        db_inputdata('ht02_datainicial',@$ht02_datainicial_dia,@$ht02_datainicial_mes,@$ht02_datainicial_ano,true,'text',3,"");
				      ?>
				    </td>
				    <td nowrap title="<?=@$Tht02_datafinal?>">
				       <?=@$Lht02_datafinal?>
				    </td>
				    <td> 
				      <?
				        db_inputdata('ht02_datafinal',@$ht02_datafinal_dia,@$ht02_datafinal_mes,@$ht02_datafinal_ano,true,'text',3,"");
				      ?>
				    </td>
				  </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</fieldset>
<table border="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisa() {

  var sUrl = 'func_habittipogrupoprograma.php?funcao_js=parent.js_preenchepesquisa|ht02_sequencial';
  js_OpenJanelaIframe('','db_iframe_habittipogrupoprograma', sUrl, 'Pesquisa', true,'0');
}

function js_preenchepesquisa(chave) {

  db_iframe_habittipogrupoprograma.hide();
  <?
    echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
</script>
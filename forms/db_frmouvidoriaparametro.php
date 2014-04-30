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

//MODULO: ouvidoria
$clouvidoriaparametro->rotulo->label();

$oDaoDocumentoTemplate = db_utils::getDao('db_documentotemplate');
$sCampos = "db82_sequencial, db82_descricao";
$sWhere  = " db82_templatetipo = 27";
$sSqlDocumentoTemplate = $oDaoDocumentoTemplate->sql_query_file(null, $sCampos, null, $sWhere);
$rsDocumentoTemplate   = $oDaoDocumentoTemplate->sql_record($sSqlDocumentoTemplate);
?>
<form name="form1" method="post" action="">
<center>
<table style="margin-top: 20px;">
<tr>
<td>
<fieldset>
<legend><b>Parâmetros da Ouvidoria</b></legend>
	<table border="0" >
		  <tr>
		    <td nowrap title="<?=@$Tov06_tiponumprocesso?>">
		       <?=@$Lov06_tiponumprocesso?>
		    </td>
		    <td> 
				<?
				$x = array('1'=>'Sequencial infinito','2'=>'Sequencial reiniciado a cada virada de ano');
				db_select('ov06_tiponumprocesso',$x,true,$db_opcao,"");
				?>
		    </td>
		  </tr>
		  <tr>
		    <td><b>Ficha de Atendimento</b></td>
		    <td>
		      <?php 
  		      db_selectrecord("documento", $rsDocumentoTemplate, true, $db_opcao,"","","","0-Selecione...");
		      ?>
		    </td>
		  </tr>
		  
		</table>
</fieldset>
</td>
</tr>
</table>

  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<!-- input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" -->
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ouvidoriaparametro','func_ouvidoriaparametro.php?funcao_js=parent.js_preenchepesquisa|ov_06_instit|ov06_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_ouvidoriaparametro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>
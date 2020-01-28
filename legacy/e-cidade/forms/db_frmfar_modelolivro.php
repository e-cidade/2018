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

//MODULO: Farmacia
$clfar_modelolivro->rotulo->label ();
$clrotulo = new rotulocampo ( );
$clrotulo->label ( "fa15_i_codigo" );
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
		<td nowrap title="<?=@$Tfa16_i_codigo?>">
       <?=@$Lfa16_i_codigo?>
    </td>
		<td> 
<?
db_input ( 'fa16_i_codigo', 10, $Ifa16_i_codigo, true, 'text', 3, "" )?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tfa16_c_livro?>">
       <?=@$Lfa16_c_livro?>
    </td>
		<td> 
<?
db_input ( 'fa16_c_livro', 52, $Ifa16_c_livro, true, 'text', $db_opcao, "" )?>
    </td>
	</tr>
</table>
<input
	name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
	<?=($db_botao == false ? "disabled" : "")?>> <input name="pesquisar"
	type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

</center>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_far_modelolivro','func_far_modelolivro.php?funcao_js=parent.js_preenchepesquisa|fa16_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_modelolivro.hide();
  <?
		if ($db_opcao != 1) {
			echo " location.href = '" . basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] ) . "?chavepesquisa='+chave";
		}
		?>
}
</script>
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


//MODULO: contabilidade
$clconhistdoc->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc53_coddoc?>">
       <?=@$Lc53_coddoc?>
    </td>
    <td> 
     <? db_input('c53_coddoc',4,$Ic53_coddoc,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc53_descr?>">
       <?=@$Lc53_descr?>
    </td>
    <td> 
     <? db_input('c53_descr',50,$Ic53_descr,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc53_tipo?>">
       <?=@$Lc53_tipo?>
    </td>
    <td> 
<?

$x = array ('10' => 'Empenho', '11' => 'Anula��o de Empenho', '20' => 'Liquida��o', '21' => 'Anula��o de Liquida��o', '30' => 'Pagamento Empenho', '31' => 'Estorno Pagamento ', '40' => 'Suplementa��o', '41' => 'Estorno Suplementa��o', '50' => 'Transposi��o', '51' => 'Estorno Transporsi��o', '60' => 'Redu��o', '61' => 'Estorno Redu��o', '70' => 'Redu��o Transposi��o', '71' => 'Estorno Redu��o Transp.', '100' => 'Arrecada��o Receita', '101' => 'Estorno Receita', '110' => 'Previsao Adicional receita', '111' => 'Estorno previsao Adicional', '1000' => 'Encerramento de Exerc�cio','2000' => 'Abertura de Exerc�cio');
db_select('c53_tipo', $x, true, $db_opcao, "");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_preenchepesquisa|c53_coddoc','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conhistdoc.hide();
  <?


if ($db_opcao != 1) {
	echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
}
?>
}
</script>
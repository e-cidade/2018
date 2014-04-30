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

//MODULO: Configuracoes
include("dbforms/db_classesgenericas.php");
$cldb_estrut = new cl_db_estrut;

$cltipoempresa->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top:25px;">
<tr><td align=center>

<fieldset>
<legend><b>Tipos de Empresas</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb98_sequencial?>">
       <?=@$Ldb98_sequencial?>
    </td>
    <td> 
<?
db_input('db98_sequencial',10,$Idb98_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb98_descricao?>">
       <?=@$Ldb98_descricao?>
    </td>
    <td> 
<?
db_input('db98_descricao',40,$Idb98_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
 
  <tr>
    <td nowrap>       
    </td>
    <td>
     <?
        $cldb_estrut->autocompletar = true;
        $cldb_estrut->mascara  = true;
        $cldb_estrut->reload   = false;
        $cldb_estrut->input    = false;
        $cldb_estrut->size     = 15;
        $cldb_estrut->nome     = "db98_estrutural";
        $cldb_estrut->db_opcao = $db_opcao;
        $cldb_estrut->db_mascara(10000);
     ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tdb98_dataini?>">
       <?=@$Ldb98_dataini?>
    </td>
    <td> 
<?
db_inputdata('db98_dataini',@$db98_dataini_dia,@$db98_dataini_mes,@$db98_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb98_datafin?>">
       <?=@$Ldb98_datafin?>
    </td>
    <td> 
<?
db_inputdata('db98_datafin',@$db98_datafin_dia,@$db98_datafin_mes,@$db98_datafin_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
</table>

</fieldset>

</td></tr>
</table>  
  
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

<? if($db_opcao != 1 && $db_opcao != 11) {  ?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo" onclick="window.location.href='con1_tipoempresa001.php'">
<? } ?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tipoempresa','func_tipoempresa.php?funcao_js=parent.js_preenchepesquisa|db98_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipoempresa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
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

//MODULO: inflatores
$clinflan->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ti01_codigo?>">
       <?=@$Li01_codigo?>
    </td>
    <td> 
<?
db_input('i01_codigo',5,$Ii01_codigo,true,'text',$opcaocodigo,"onchange=js_validainflat(this);")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_descr?>">
       <?=@$Li01_descr?>
    </td>
    <td> 
<?
db_input('i01_descr',40,$Ii01_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_pict?>">
       <?=@$Li01_pict?>
    </td>
    <td> 
<?
db_input('i01_pict',12,$Ii01_pict,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_dm?>">
       <?=@$Li01_dm?>
    </td>
    <td> 
	  <?
	  //$x = array('0'=>'Diário','1'=>'Mensal','2'=>'Anual');
	  $x = array('1'=>'Diário','0'=>'Mensal','2'=>'Anual');
	  db_select('i01_dm',$x,true,$db_opcao,"");
	  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_tipo?>">
       <?=@$Li01_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Divide Valor pelo indice da data base e multiplica pelo indice da data atual','2'=>'Aplica índice mês a mês sobre o valor','3'=>'Aplica índice do mês do vencimento sobre o valor','9'=>'Sem correção ( Moeda Corrente Nacional )');
db_select('i01_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_percen?>">
       <?=@$Li01_percen?>
    </td>
    <td> 
<?
db_input('i01_percen',1,$Ii01_percen,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ti01_calc?>">
       <?=@$Li01_calc?>
    </td>
    <td> 
<?
db_input('i01_calc',1,$Ii01_calc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_validainflat(obj){
    if(obj.value.lenght > 5){
	    alert('O codigo do inflator pode ter no máximo 5 caracteres ! ');	  
        obj.value = obj.value.substr(1,5); 
	}
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_preenchepesquisa|i01_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_inflan.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
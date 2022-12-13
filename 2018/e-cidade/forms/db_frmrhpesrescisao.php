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

//MODULO: pessoal
$clrhpesrescisao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh05_regist?>">
       <?=@$Lrh05_regist?>
    </td>
    <td> 
<?
db_input('rh05_regist',6,$Irh05_regist,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh05_recis?>">
       <?=@$Lrh05_recis?>
    </td>
    <td> 
<?
db_inputdata('rh05_recis',@$rh05_recis_dia,@$rh05_recis_mes,@$rh05_recis_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh05_causa?>">
       <?=@$Lrh05_causa?>
    </td>
    <td> 
<?
db_input('rh05_causa',2,$Irh05_causa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh05_caub?>">
       <?=@$Lrh05_caub?>
    </td>
    <td> 
<?
db_input('rh05_caub',2,$Irh05_caub,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh05_aviso?>">
       <?=@$Lrh05_aviso?>
    </td>
    <td> 
<?
db_inputdata('rh05_aviso',@$rh05_aviso_dia,@$rh05_aviso_mes,@$rh05_aviso_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh05_taviso?>">
       <?=@$Lrh05_taviso?>
    </td>
    <td> 
<?
db_input('rh05_taviso',1,$Irh05_taviso,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh05_mremun?>">
       <?=@$Lrh05_mremun?>
    </td>
    <td> 
<?
db_input('rh05_mremun',15,$Irh05_mremun,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpesrescisao','func_rhpesrescisao.php?funcao_js=parent.js_preenchepesquisa|rh05_regist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpesrescisao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
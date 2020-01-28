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

//MODULO: saude
$clsau_cid->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd70_i_codigo?>">
       <?=@$Lsd70_i_codigo?>
    </td>
    <td>
<?
db_input('sd70_i_codigo',5,$Isd70_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd70_c_cid?>">
       <?=@$Lsd70_c_cid?>
    </td>
    <td>
<?
db_input('sd70_c_cid',4,$Isd70_c_cid,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd70_c_nome?>">
       <?=@$Lsd70_c_nome?>
    </td>
    <td>
<?
db_input('sd70_c_nome',60,$Isd70_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd70_i_agravo?>">
       <?=@$Lsd70_i_agravo?>
    </td>
    <td>
      <?
       include("classes/db_sau_agravo_classe.php");
       $clsau_agravo = new cl_sau_agravo;
       $result = $clsau_agravo->sql_record($clsau_agravo->sql_query("","*"));
       db_selectrecord("sd70_i_agravo",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd70_c_sexo?>">
       <?=@$Lsd70_c_sexo?>
    </td>
    <td>
<?
$x = array('F'=>'Feminino','I'=>'Indiferente/Ambos','M'=>'Masculino');
db_select('sd70_c_sexo',$x,true,$db_opcao,"");
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
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_cid','func_sau_cid.php?funcao_js=parent.js_preenchepesquisa|sd70_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_cid.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
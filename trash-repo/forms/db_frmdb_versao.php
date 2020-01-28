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

//MODULO: configuracoes

$cldb_versao->rotulo->label();
      if($db_opcao==1){
 	   $db_action="con1_db_versao004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_db_versao005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="con1_db_versao006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
<?
db_input('db30_codver',6,$Idb30_codver,true,'hidden',3,"")
?>
  <tr>
    <td nowrap title="<?=@$Tdb30_codversao?>">
       <?=@$Ldb30_codversao?>
    </td>
    <td> 
<?
if(isset($versao) && $versao == 'n'){
//  echo $cldb_versao->sql_query_file('',"max(db30_codversao||'P'||db30_codrelease+1)");
   $result = $cldb_versao->sql_record($cldb_versao->sql_query_file('',"max(db30_codversao::text||'P'::text||(db30_codrelease+1)::text)"));
   db_fieldsmemory($result,0);
   if(trim($max) == ''){
    echo "<script>alert('Não existe versão para esta release! Cadastre primeiro uma versao')</script>";
    exit;
   }
   $matriz= split("P",$max);
   $db30_codversao = $matriz[0]; 
//   $db30_codrelease = $matriz[1];
}
db_input('db30_codversao',6,$Idb30_codversao,true,'text',3,"");
db_input('versao',6,0,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb30_codrelease?>">
       <?=@$Ldb30_codrelease?>
    </td>
    <td> 
<?
db_input('db30_codrelease',6,$Idb30_codrelease,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb30_data?>">
       <?=@$Ldb30_data?>
    </td>
    <td> 
<?
if(!isset($db30_data_dia)){
   $db30_data_dia     = date('d',db_getsession("DB_datausu") );
   $db30_data_mes  = date('m',db_getsession("DB_datausu") );
   $db30_data_ano   = date('Y',db_getsession("DB_datausu") );
}

db_inputdata('db30_data',@$db30_data_dia,@$db30_data_mes,@$db30_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb30_obs?>">
       <?=@$Ldb30_obs?>
    </td>
    <td> 
<?
db_textarea('db30_obs',8,70,$Idb30_obs,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo.iframe_db_versao','db_iframe_db_versao','func_db_versao.php?funcao_js=parent.js_preenchepesquisa|db30_codver','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_versao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
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

//MODULO: configuracoes
include("dbforms/db_classesgenericas.php");
//echo 'codcpd '.$db34_codcpd;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;

$cldb_versaocpdarq->rotulo->label();
      
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $db34_obs = "";
     $db34_descr = "";
     $db34_arq = "";
   }
} 
?>
<form name="form2" method="post" action="">
<center>
<table border="0">
<?
db_input('db34_codarq',6,$Idb34_codarq,true,'hidden',$db_opcao,"")
?>
  <tr>
    <td nowrap title="<?=@$Tdb34_codcpd?>">
       <?=@$Ldb34_codcpd?>
    </td>
    <td> 
<?
if(isset($codcpd))
  $db34_codcpd = $codcpd;

db_input('db34_codcpd',6,$Idb34_codcpd,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb34_descr?>">
       <?=@$Ldb34_descr?>
    </td>
    <td> 
<?
$x = array("SQL"=>"SQL","MENUS"=>"MENUS","ARQ"=>"ARQUIVO");
db_select('db34_descr',$x,$Idb34_descr,2);
//db_input('db34_descr',15,$Idb34_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb34_obs?>">
       <?=@$Ldb34_obs?>
    </td>
    <td> 
<?
db_textarea('db34_obs',2,70,$Idb34_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb34_arq?>">
       <?=@$Ldb34_arq?>
    </td>
    <td> 
<?
db_textarea('db34_arq',6,70,$Idb34_arq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >-->
</form>
  <table>
    <tr>
     <td valign="top"  align="center">
     <?
       $chavepri= array("db34_codarq"=>@$db34_codarq);
       $cliframe_alterar_excluir->chavepri=$chavepri;
//       echo $cldb_versaocpdarq->sql_query_file(null,"*","","db34_codcpd=".@$db34_codcpd);
       $cliframe_alterar_excluir->sql     = $cldb_versaocpdarq->sql_query_file(null,"*","","db34_codcpd=".@$db34_codcpd);
       $cliframe_alterar_excluir->campos  ="db34_codarq,db34_codcpd,db34_descr,db34_obs,db34_arq";
       $cliframe_alterar_excluir->legenda="ANEXOS LANÇADOS";
       $cliframe_alterar_excluir->iframe_height ="110";
       $cliframe_alterar_excluir->iframe_width ="700";
       $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
     </td>
    </tr>
  </table>
<script>

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_versaocpdarq','func_db_versaocpdarq.php?funcao_js=parent.js_preenchepesquisa|db34_codcpd','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  alert(chave);
  db_iframe_db_versaocpdarq.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
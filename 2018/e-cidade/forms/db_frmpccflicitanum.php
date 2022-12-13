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

include("classes/db_db_config_classe.php");
//MODULO: licitação
$cldb_config = new cl_db_config;
$clpccflicitanum->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
  <legend align="center">
    <b>Configuração dos Editais</b>
  </legend>
<table border="0">
  <br>
  <tr>
    <td nowrap title="<?=@$Tl24_instit?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <b>Instituição :</b>
    </td>
    <td> 
			<?
			  $l24_instit=db_getsession("DB_instit"); 
			  $result_instit=$cldb_config->sql_record($cldb_config->sql_query_file());
			  if (isset($l24_instit)&&$l24_instit!=""){
			   	echo "<script>document.form1.l24_instit.selected=$l24_instit;</script>";
			  }
			  db_selectrecord("l24_instit",$result_instit,true,3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl24_anousu?>">
      <?=@$Ll24_anousu?>
    </td>
    <td> 
			<?
				$l24_anousu = db_getsession('DB_anousu');
				db_input('l24_anousu',6,$Il24_anousu,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl24_numero?>">
      <?=@$Ll24_numero?>
    </td>
    <td> 
			<?
		  	db_input('l24_numero',8,$Il24_numero,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  </table>
  </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisal24_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.l24_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.l24_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.l24_instit.focus(); 
    document.form1.l24_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.l24_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pccflicitanum','func_pccflicitanum.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pccflicitanum.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
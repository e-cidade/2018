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

//MODULO: material
$clmatpedido->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
if (isset($m97_sequencial)&&$m97_sequencial!=""){
  $result_itematend=$clmatpedidoitem->sql_record($clmatpedidoitem->sql_query_anulacao(null,'*',null,"m98_matpedido=$m97_sequencial and (m99_codigo is not null OR m101_sequencial is not null) "));
  if ($clmatpedidoitem->numrows!=0){
       $opcao= $db_opcao;
       $db_opcao=3;
       $db_botao=false;
       db_msgbox('Já existe um atendimento ou uma anulação para esta solicitação!!');
  }
}

?>
  <form name="form1" method="post" action="">
  <center>
  <table border="0">
    <tr>
    <td nowrap title="<?=@$Tm97_sequencial?>">
      <b>Sequencial: </b> 
      <?//=@$Lm97_codigo?>
    </td>
    <td> 
	<?
		db_input('m97_sequencial',10,$Im97_sequencial,true,'text',3,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm97_data?>">
       <?=@$Lm97_data?>
    </td>
    <td> 
	<?
		db_inputdata('m97_data',@$m97_data_dia,@$m97_data_mes,@$m97_data_ano,true,'text',3,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm97_coddepto?>">
       <?
       		db_ancora(@$Lm97_coddepto,"js_pesquisam97_coddepto(true);",3);
       ?>
    </td>
    <td> 
	<?
		db_input('m97_coddepto',10,$Im97_coddepto,true,'text',3," onchange='js_pesquisam97_coddepto(false);'")
	?>
    <?
		db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm97_login?>">
       <?
    	   db_ancora(@$Lm97_login,"js_pesquisam97_login(true);",3);
       ?>
    </td>
    <td> 
	<?
		db_input('m97_login',10,$Im97_login,true,'text',3," onchange='js_pesquisam97_login(false);'")
	?>
    <?
		db_input('nome',40,$Inome,true,'text',3,'')
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm97_hora?>">
       <?=@$Lm97_hora?>
    </td>
    <td> 
	<?
		db_input('m97_hora',10,$Im97_hora,true,'text',3,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm97_db_almox?>">
       <?=@$Lm97_db_almox?>
    </td>
    <td> 
	<?
		$result_depusu = $cldb_depusu->sql_record($cldb_depusu->sql_query_almoxusu(null,null,"distinct m91_codigo as almoxarifado, db_depart.descrdepto", null, " db_depusu.id_usuario = " . db_getsession("DB_id_usuario")));
		if ($cldb_depusu->numrows>0){
			db_selectrecord('m97_db_almox',$result_depusu,true,($db_opcao == 1?1:3));
		} else {
			echo "Nenhum almoxarifado disponível!";
		}
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm97_obs?>">
       <?=@$Lm97_obs?>
    </td>
    <td> 
	<?
		db_textarea('m97_obs',10,50,$Im97_obs,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  </table>
  </center>
  <?
  	if (isset($opcao)){
    	$db_opcao=@$opcao;
  	}
  ?>
  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam97_coddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.m97_coddepto.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.m97_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.m97_coddepto.focus(); 
    document.form1.m97_coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.m97_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisam97_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.m97_login.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.m97_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.m97_login.focus(); 
    document.form1.m97_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.m97_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_matpedido','func_matpedido.php?funcao_js=parent.js_preenchepesquisa|m97_sequencial','Pesquisa',true,"0");
}
function js_preenchepesquisa(chave){
  db_iframe_matpedido.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;\n";
    if($db_opcao==3||$db_opcao==33){
      echo " parent.iframe_matpedidoitem.location.href='mat1_matpedidoitem001.php?m97_sequencial='+chave+'&db_opcao=3'; \n";
    }else{
      echo " parent.iframe_matrequiitem.location.href='mat1_matpedidoitem001.php?m97_sequencial='+chave;\n";
    }
    echo " parent.document.formaba.matpedidoitem.disabled = false;\n";
  }
  ?>
}
</script>
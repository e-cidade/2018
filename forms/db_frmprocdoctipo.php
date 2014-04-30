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

//MODULO: protocolo
$clprocdoctipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p51_descr");
$clrotulo->label("p56_descr");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($grupo)){
	$iGrupo = $grupo;
}
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>location.href='pro1_procdoctipo002.php?chavepesquisa=$p57_codigo&chavepesquisa1=$p57_coddoc&grupo=$grupo'</script>";}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>location.href='pro1_procdoctipo003.php?chavepesquisa=$p57_codigo&chavepesquisa1=$p57_coddoc&grupo=$grupo'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" style="margin-top: 20px;">
	<tr align="center"><td colspan="2">
	<fieldset>
	<table>
	  <tr>
		    <td nowrap title="<?=@$Tp57_codigo?>" align="right">
		       <?
		      db_ancora('<b>Tipo de Processo:</b>',"js_pesquisap57_codigo(true);","");
		       ?>
		    </td>
		    <td> 
					<?
					db_input('p57_codigo',3,$Ip57_codigo,true,'text',$db_opcao," onchange='js_pesquisap57_codigo(false);'");
					db_input('p51_descr',50,$Ip51_descr,true,'text',3,'');
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tp57_coddoc?>" align="right">
		      <?
		      db_ancora('<b>Documento:</b>',"js_pesquisap57_coddoc(true);","");
		      ?>
		    </td>
		    <td> 
					<?
					db_input('p57_coddoc',3,$Ip57_coddoc,true,'text',$db_opcao," onchange='js_pesquisap57_coddoc(false);'");
					$p57_coddoc_old = @$p57_coddoc;
					db_input('p57_coddoc',5,$Ip57_coddoc,true,'hidden',$db_opcao,"",'p57_coddoc_old');
					db_input('p56_descr',50,$Ip56_descr,true,'text',3,'');
					db_input('grupo',10,$iGrupo,true,'hidden',$db_opcao);
				 ?>
		    </td>
		  </tr>
		  <tr>
		    <td align="center" colspan="2">
		    	 
		      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		    </td>
		  </tr>
		  </table>
		  </fieldset>
		</td></tr>
		  
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("p57_codigo"=>@$p57_codigo,"p57_coddoc"=>@$p57_coddoc);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="p57_codigo,p51_descr,p57_coddoc,p56_descr";
    $campos = "p57_codigo,p51_descr,p57_coddoc,p56_descr";
    $cliframe_alterar_excluir->sql=$clprocdoctipo->sql_query("","",$campos,""," p57_codigo = ".@$p57_codigo."");
    $cliframe_alterar_excluir->legenda="Documentos por Tipo";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum documento por tipo  Cadastrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisap57_codigo(mostra){
	if(document.getElementById('p57_codigo').value == "" && mostra == false){
		document.getElementById('p57_codigo').value = "";
		document.getElementById('p51_descr').value = "";
	}else{
	  if(mostra==true){
	    db_iframe.jan.location.href = 'func_tipoproc.php?funcao_js=parent.js_mostratipoproc1|0|1&grupo=<?=$iGrupo;?>';
	    db_iframe.mostraMsg();
	    db_iframe.show();
	    db_iframe.focus();
	  }else{
	    db_iframe.jan.location.href = 'func_tipoproc.php?pesquisa_chave='+document.form1.p57_codigo.value+'&funcao_js=parent.js_mostratipoproc&grupo=<?=$iGrupo;?>';
	  }
	 }
}
function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if(erro==true){ 
    document.form1.p57_codigo.focus(); 
    document.form1.p57_codigo.value = ''; 
  }else{
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?p57_codigo="+document.form1.p57_codigo.value+"&p51_descr="+chave+"&grupo="+document.form1.grupo.value;
  }  
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.p57_codigo.value = chave1;
  document.form1.p51_descr.value = chave2;
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?p57_codigo="+chave1+"&p51_descr="+chave2+"&grupo="+document.form1.grupo.value;
  db_iframe.hide();
}
function js_pesquisap57_coddoc(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_procdoc.php?funcao_js=parent.js_mostraprocdoc1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_procdoc.php?pesquisa_chave='+document.form1.p57_coddoc.value+'&funcao_js=parent.js_mostraprocdoc';
  }
}
function js_mostraprocdoc(chave,erro){
  document.form1.p56_descr.value = chave; 
  if(erro==true){ 
    document.form1.p57_coddoc.focus(); 
    document.form1.p57_coddoc.value = ''; 
  }
}
function js_mostraprocdoc1(chave1,chave2){
  document.form1.p57_coddoc.value = chave1;
  document.form1.p56_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_procdoctipo.php?funcao_js=parent.js_preenchepesquisa|0|1';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave,chave1){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&chavepesquisa1="+chave1;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
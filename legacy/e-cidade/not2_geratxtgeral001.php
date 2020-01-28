<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clpostgresqlutils = new PostgreSQLUtils;
$clrotulo          = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$clrotulo->label('k15_codigo');
$clrotulo->label('z01_nome');

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
	
	db_msgbox(_M('tributario.notificacoes.not2_geratxtgeral001.problema_indices_debitos'));
	$db_botao = false; 
	$db_opcao = 3;
} else {
	
	$db_botao = true;
	$db_opcao = 4;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_emite(tiporel) {

	if (document.form1.k60_codigo.value == '') {
		alert(_M('tributario.notificacoes.not2_geratxtgeral001.selecione_lista'));
		return false;
	}
	
  if (document.form1.modelo.value == 3) {
    url = "cai4_emitenotificacaotxt.php"; 
  } else {
    url = "not2_geratxtgeral002.php";
  }
  
  url += '?ordem='+document.form1.ordem.value;
  url += '&tiporel='+tiporel;
  url += '&lista='+document.form1.k60_codigo.value;
  url += '&quantidade='+document.form1.quantidade.value;
  url += '&tipo='+document.form1.tipo.value;
  url += '&modelo='+document.form1.modelo.value;
  url += '&k60_datavenc='+document.form1.dtvencimento.value;
  
  js_OpenJanelaIframe('top.corpo','db_iframe_txt',url,'Pesquisa',true);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">

    
				 
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Emissão Geral TXT</legend>   
	<table class="form-container">
	  <tr>
	    <td title="<?=@$Tk60_codigo?>" >
	      <?
	        db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",$db_opcao);
	      ?>
	    </td>
	    <td>
	      <?
	        db_input('k60_codigo',10,$Ik60_codigo,true,'text',$db_opcao,"onchange='js_pesquisalista(false);'");
	        db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td title="Ordem para a emissão da lista">
	      Ordem:
	    </td>
	    <td>
	      <?
	        $xx = array("a"=>"Alfabética","n"=>"Numérica",'t'=>'Notificação');
	        db_select('ordem',$xx,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td title="Modelo" >
	      Modelo:
	    </td>
	    <td>
	      <?
	        $xx = array('1'=>'CBR454', '2'=>'IGC702' ,'3' => "Gráfica" );
	        db_select('modelo',$xx,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td title="Tipo">
	      Tipo:
	    </td>
	    <td>
	      <?
	        $xx = array('t'=>'Teste', 'f'=>'Final');
	        db_select('tipo',$xx,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr> 
	  <tr>
	    <td>
	      Quantidade de registros a processar:
	    </td>
	    <td>
	      <?
	        db_input('quantidade',10,'',true,'text',$db_opcao);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td>
	      Data de Vencimento:
	    </td>
	    <td>
	      <?
	        $data        = date("Y-m-d",DB_getsession('DB_datausu'));
	        $aData       = explode("-", $data); 
	        $dtDataVenc  = date("Y-m-d",mktime(0,0,0,$aData[1], $aData[2]+30, $aData[0]));
	        $aDtDataVenc = explode("-", $dtDataVenc);
	        db_inputdata("dtvencimento",$aDtDataVenc[2],$aDtDataVenc[1],$aDtDataVenc[0], $dbcadastro=true,
	                     $dbtype='text',$db_opcao);
	      ?>
	    </td>
	  </tr>
	</table>
  </fieldset>
  <input name="db_opcao" type="button" id="db_opcao" value="Processar" onClick="js_emite(1);" 
	<?=($db_botao ? '' : 'disabled')?>>
</form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisalista(mostra){
     if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_lista','func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr','Pesquisa',true);
     }else{
       js_OpenJanelaIframe('top.corpo','db_iframe_lista','func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa',false);
     }
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  }
}

function js_mostralista1(chave1,chave2){
     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe_lista.hide();
}

// cadban
function js_pesquisacadban(mostra){
     if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?funcao_js=parent.js_mostracadban1|k15_codigo|z01_nome','Pesquisa',true);
     }else{
       js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?pesquisa_chave='+document.form1.k15_codigo.value+'&funcao_js=parent.js_mostracadban','Pesquisa',false);
     }
}

function js_mostracadban(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
     document.form1.k15_codigo.focus();
     document.form1.k15_codigo.value = '';
  }
}

function js_mostracadban1(chave1,chave2){
     document.form1.k15_codigo.value = chave1;
     document.form1.z01_nome.value = chave2;
     db_iframe_cadban.hide();
}
</script>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
$("ordem").setAttribute("rel","ignore-css");
$("ordem").addClassName("field-size4");
$("modelo").setAttribute("rel","ignore-css");
$("modelo").addClassName("field-size4");
$("tipo").setAttribute("rel","ignore-css");
$("tipo").addClassName("field-size4");
$("quantidade").addClassName("field-size2");
$("dtvencimento").addClassName("field-size2");

</script>
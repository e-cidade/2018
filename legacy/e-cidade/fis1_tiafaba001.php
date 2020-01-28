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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("classes/db_tiaf_classe.php");
include("classes/db_tiafprazo_classe.php");
include("classes/db_tiafcgm_classe.php");
include("classes/db_tiafinscr_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$cltiaf = new cl_tiaf;
$cltiafcgm = new cl_tiafcgm;
$cltiafinscr = new cl_tiafinscr;
$cltiafprazo = new cl_tiafprazo;
$cltiaf->rotulo->label();
$cltiafprazo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$inclui=true;
$sqlerro = false;
/////////////// VARIAVEIS DO FORMULARIO /////////////

$tipobotao = "Incluir";                 //tipo do botao
echo "<script> passa = 's'; </script>"; //variavel js para a func saber se passa para proxima ab ou naun
$tipoancgeral = "1";                    // TIpo geral das ancoras
$tipoanctiaf = "3";                     //tipo da ancora do tiaf 
$db_opcao = "1";                        //opcao geral dos campos

/////////////// ROTINA DE INCLUS?O NO BANCO //////////////
 
if (isset($incluir)){
	db_inicio_transacao();
	$prazo = $y96_prazo_ano.$y96_prazo_mes.$y96_prazo_dia; 
	$data  = $y90_data_ano.$y90_data_mes.$y90_data_dia;
	if (isset($prazo) && $prazo <= $data){
		db_msgbox("A data do prazo n?o pode ser menor que data do TIAF !");
		$sqlerro=true;
	}
	$cltiaf->y90_data = $y90_data_ano."-".$y90_data_mes."-".$y90_data_dia;
	$cltiaf->y90_atend = "false";
	$cltiaf->incluir($cltiaf->y90_codtiaf);
	$cltiafprazo->y96_prazo = $y96_prazo_ano."-".$y96_prazo_mes."-".$y96_prazo_dia;
	$cltiafprazo->y96_codtiaf = $cltiaf->y90_codtiaf;
	$cltiafprazo->incluir($cltiafprazo->y96_codigo);		
	if (isset($z01_numcgm)){
		$cltiafcgm->y95_numcgm = $z01_numcgm;
		$cltiafcgm->y95_codtiaf = $cltiaf->y90_codtiaf;
		$cltiafcgm->incluir($cltiaf->y90_codtiaf);
	}
	if (isset($q02_inscr)){
		$cltiafinscr->y94_inscr = $q02_inscr;
		$cltiafinscr->y94_codtiaf = $cltiaf->y90_codtiaf;
		$cltiafinscr->incluir($cltiaf->y90_codtiaf);
	}
	if ($cltiaf->erro_status==0 && $inclui == true){
    	$sqlerro=true;
    }else{
        $erro_msg= $cltiaf->erro_msg;
    }
	db_fim_transacao($sqlerro);
} 

////////////////////////////////////////////////////////////////
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	    <?
	    	include("forms/db_frmtiaf001.php");
	    ?> 
	</td>
  </tr>
</table>
</body>
</html>
<script>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_preenchepesquisa|y90_codtiaf','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_tiaf.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}

function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe2.hide();
}

function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}

function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe.hide();
}

function js_validacampos(){
	if (document.form1.q02_inscr.value =="" && document.form1.z01_numcgm.value ==""){
		alert ("Preencha a inscri??o ou cgm ! ");
		return false;
	}else if (document.form1.q02_inscr.value !="" && document.form1.z01_numcgm.value !=""){
		alert ("Preencha somente a inscri??o ou cgm ! ");
		return false;
	}else if (document.form1.y90_data_dia.value =="" || document.form1.y90_data_mes.value =="" || document.form1.y90_data_ano.value ==""){
		alert ("Preencha a corretamente a data ! ");
		return false;
	}else if (document.form1.y96_prazo_dia.value =="" || document.form1.y96_prazo_mes.value =="" || document.form1.y96_prazo_ano.value ==""){
		alert ("Preencha a corretamente o prazo ! ");
		return false;
	}else{
	    return true;
	}
}

</script>

<?
if(isset($incluir)){
  if($cltiaf->erro_status=="0"){
    $cltiaf->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($cltiaf->erro_campo!=""){
      echo "<script> document.form1.".$cltiaf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltiaf->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    if ($sqlerro == false){
	    echo "<script>
	               parent.iframe_g2.location.href='fis1_tiafaba002.php?y90_codtiaf=".$cltiaf->y90_codtiaf."';\n
	               parent.iframe_g1.location.href='fis1_tiafaba001.php?chavepesquisa=".@$codigo."';\n
	               parent.mo_camada('g2');
	               parent.document.formaba.matrequiitem.disabled = false;\n
		 </script>";
    }
  };
};
?>
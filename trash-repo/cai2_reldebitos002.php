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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

$clpostgresqlutils = new PostgreSQLUtils;
$aux               = new cl_arquivo_auxiliar;
$clrotulo          = new rotulocampo;

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 1;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {
   rec = ""
   vir = "";
   for(y=0;y<parent.iframe_receitas.document.getElementById('tabrec').length;y++){
     rec += vir + parent.iframe_receitas.document.getElementById('tabrec').options[y].value;
     vir = ",";
   }
   document.form1.receitas.value = rec;
   
   
   hist = ""
   vir = "";
   for(y=0;y<parent.iframe_historico.document.getElementById('histcalc').length;y++){
     hist += vir + parent.iframe_historico.document.getElementById('histcalc').options[y].value;
     vir = ",";
   }

   document.form1.historico.value = hist;

   deb = ""
   vir = "";
   for(y=0;y<parent.iframe_debitos.document.getElementById('arretipo').length;y++){
     deb += vir + parent.iframe_debitos.document.getElementById('arretipo').options[y].value;
     vir = ",";
   }
   document.form1.debitos.value = deb;
  
   document.form1.selerec.value  =  parent.iframe_receitas.document.form1.selerec.value;
   document.form1.selehist.value =  parent.iframe_historico.document.form1.selehist.value;
   document.form1.seledeb.value  =  parent.iframe_debitos.document.form1.seledeb.value;
  jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect();" >
<form name="form1" method="post" action="cai2_reldebitos006.php" target='rel'>
<input type="hidden" name="receitas" value="">
<input type="hidden" name="historico" value="">
<input type="hidden" name="debitos" value="">
<input type="hidden" name="selerec" value="">
<input type="hidden" name="selehist" value="">
<input type="hidden" name="seledeb" value="">
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	  <br>
	<center>
  <table border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td nowrap title="Data de operação do débito">
	    <b>Data de operação de </b>
	  </td>
	  <td>
			<?
			  db_inputdata('dtini',"","","",true,'text',$db_opcao,"")
			?>
	  <b> À </b>
			<?
			  db_inputdata('dtfim',"","","",true,'text',$db_opcao,"")
			?>

          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Td40_codigo?>">
	    <b>Vencimento de </b>
	  </td>
	  <td>
			<?
			  db_inputdata('dataini',"","","",true,'text',$db_opcao,"")
			?>
	  <b> À </b>
			<?
			  db_inputdata('datafim',"","","",true,'text',$db_opcao,"")
			?>

          </td>
        </tr>
	<tr>
	  <td>
	     <b>Registros:</b>
	  </td>
	  <td>
			<?
			  db_input('registros',6,0,true,'text',$db_opcao)
			?>	  
    </td>
	</tr>
	<tr>
	  <td>
	    <b>Modelo:</b>
	  </td>
	  <td>
			<? 
			  $result=array("resumido"=>"Resumido","completo"=>"Completo");
			  db_select("modelo",$result,true,$db_opcao);
			?>
	  </td>
	</tr>
	<tr>
	  <td>
	    <b>Origem:</b>
	  </td>
	  <td>
			<? 
			  $result=array("cgm"=>"Numcgm","matricula"=>"Matrícula","inscricao"=>"Inscricao");
			  db_select("origem",$result,true,$db_opcao);
			?>	 
   </td>
	</tr>
	<tr>
	  <td>
	    <b>Ordenar por:</b>
	  </td>
	  <td>
			<? 
			  $result=array("z01_nome"=>"Nome","vlrcor"=>"Valor corrigido","k22_data"=>"Vencimento","total"=>"Valor total");
			  db_select("order",$result,true,$db_opcao);
			
			  $result=array("asc"=>"Ascendente","desc"=>"Descendente");
			  db_select("ordem",$result,true,$db_opcao);
			?>
	  </td>
	</tr>
        <tr>
	  <td align="center" colspan="2" align="center">
	    <br>
	    <input name="gerar" type="submit" onClick="js_relatorio();" value="Gerar relatório"
	           <?=($db_botao ? '' : 'disabled')?>>
	  </td>
        </tr>
       </table>
      </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>
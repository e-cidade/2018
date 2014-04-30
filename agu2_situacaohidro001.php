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
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("x21_exerc");
$clrotulo->label("x21_mes");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>

<script>
function js_emite(){
  var sit   = document.form1.situacao.value;
  var ano   = document.form1.iAno.value;
  var mes   = document.form1.iMes.value;
  var filtro = document.form1.filtro.value;
  
  today     = new Date();
  var todayYear  = today.getFullYear();
  var todayMonth = today.getMonth()+1;


  var nomeMesInput  = retornaMesNome(mes);
  
  var nomeMes       = retornaMesNome(todayMonth);

  sit += "&filtro="+filtro;
 
	if(todayMonth < 10) { todayMonth = "0"+todayMonth; }
	
  if(document.getElementById('anomes').style.visibility == "visible") {
		if(ano != ''){
		  if(ano.length == 4) {
			  if(mes != ''){
				  if(mes.length == 2){
					  if((mes >= 1) && (mes <= 12)){
					  	sit += "&ano="+ano+"&mes="+mes;
					  }else {
						  alert('Número do mês inválido. De 01(Janeiro) até 12(Dezembro). Ex.: '+todayMonth+' ('+nomeMes+')');
						  document.form1.iMes.focus();
						  return false;
					  }
				  }else {
					  alert('Formato do mês inválido. Ex.: 0'+mes+' ('+nomeMesInput+')');
					  document.form1.iMes.focus();
					  return false;
				  }
			  }else {
				  alert('O mês deve ser informado.');
				  document.form1.iMes.focus();
				  return false;
			  }
		  }else {
			  alert('Formato do ano inválido. Ex.: '+todayYear);
			  document.form1.iAno.focus();
			  return false;
		  }
	  }else{
		  alert('O ano deve ser informado.');
		  document.form1.iAno.focus();
		  return false;
	  }
  }
  jan = window.open('agu2_situacaohidro002.php?situacao='+sit,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function retornaMesNome(numero) {
	var numeroMes = parseInt(numero);
	var fNomeMes = "";

	switch(numeroMes) {
  	case 1 : fNomeMes = 'Janeiro';  break;
  	case 2 : fNomeMes = 'Fevereiro'; break;
  	case 3 : fNomeMes = 'Março'; break;
  	case 4 : fNomeMes = 'Abril'; break;
  	case 5 : fNomeMes = 'Maio'; break;
  	case 6 : fNomeMes = 'Junho'; break;
  	case 7 : fNomeMes = 'Julho'; break;
  	case 8 : fNomeMes = 'Agosto'; break;
  	case 9 : fNomeMes = 'Setembro'; break;
  	case 10 : fNomeMes = 'Outubro'; break; 
  	case 11 : fNomeMes = 'Novembro'; break;
  	case 12 : fNomeMes = 'Dezembro'; break;
  }	
		return fNomeMes;
}

function hidden(selectValue) {
	if(selectValue == 1) {
		document.getElementById('anomes').style.visibility = "hidden";
	}else if(selectValue == 2) {
		document.getElementById('anomes').style.visibility = "visible";
	}
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style="margin: 25px auto;">
<form name="form1" method="post" action="">
<table align="center">
	<tr>
		<td><b>Situação do hidrômetro:</b></td>
		<td><?
		$sql = "select x17_codigo, x17_descr from aguasitleitura order by x17_codigo" ;
		$result = pg_query($sql);
		db_selectrecord('situacao',$result,true,1,"","","","");
		?></td>
	</tr>

	<tr>
		<td><strong>Listar</strong></td>
		<td><?
		$iListar = 1;
		$x = array("1"=>"&Uacute;ltima Situa&ccedil;&atilde;o", "2"=>"Informar Ano/M&ecirc;s");
		db_select("iListar", $x, true, 1, "onchange=\"hidden(this.value)\"");
		?></td>
	</tr>

	<tr>
	 <td><strong>Filtro</strong></td>
	 <td>
	 <?
	   $a = array('1'=>'Todas', '2'=>'Matr&iacute;culas COM situa&ccedil;&atilde;o de corte', '3'=>'Matr&iacute;culas SEM situa&ccedil;&atilde;o de corte');
	   db_select('filtro', $a, true, 1); 
	 ?>
	 </td>
	</tr>

	<tr id="anomes" style="visibility: hidden;">
		<td><strong>Ano/M&ecirc;s (AAAA/MM)</strong></td>
		<?
		$iAno = date("Y",db_getsession("DB_datausu"));
		$iMes = date("m",db_getsession("DB_datausu"));
		?>
		<td><?db_input("iAno", 5, $Ix21_exerc, true, "text", 1, "", "", "", "", 4)?> / <?db_input("iMes", 2, $Ix21_mes, true, "text", 1, "", "", "", "", 2)?>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center">
		  <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
		</td>
	</tr>
</table>
		<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
</form>
</body>
</html>
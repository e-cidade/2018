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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_inserir(valor) {
  var F = document.form1;
  F.data_dia.value = valor.substr(0,2);
  F.data_mes.value = valor.substr(3,2);
  F.data_ano.value = valor.substr(6,4); 
  document.form1.action = 'ipa4_atenmed001.php?meddir=1';
  F.pesquisar.click();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<?
	$medico = pg_exec("select aa01_codig,aa01_nome,aa01_espec 
	                   from medicos 
					   where aa01_codlog = ".db_getsession("DB_id_usuario")."
					   and aa01_tipoate >= 2 ");
	if(pg_numrows($medico) == 0) {
	  $DB_MSG = "Usuário não cadastrado como médico.";
	} else {
	  db_fieldsmemory($medico,0);
	  session_register("codmed");
	  session_register("nomemed");
	  session_register("especmed");
	  db_putsession("codmed",$aa01_codig);
      db_putsession("nomemed",$aa01_nome);
	  db_putsession("especmed",$aa01_espec);
	?><br>
	<form name="form1" method="post" action="ipa4_atenmed001.php">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
	      <td nowrap>&nbsp;&nbsp;<strong>Nome:</strong></td>
        <td>
		  <table border="0" cellspacing="5" cellpadding="0">
            <tr>
			  <td bgcolor="#FFFFFF" nowrap>&nbsp;<?=$aa01_nome?>&nbsp;</td>
		    </tr>
		  </table>
		</td>
		  <td width="80" align="right" nowrap><strong>Data:</strong>&nbsp;&nbsp;&nbsp;</td>
        <td nowrap>
	    <?
		include("dbforms/db_funcoes.php");
		db_data("data",@$data_dia,@$data_mes,@$data_ano);
		?>&nbsp;
		</td>
     </tr>
     <tr>
       <td align="center" colspan="5">
       <br>
         <input type="submit" name="pesquisar" value="Pesquisar"></td>
       </td>
     </tr>  
      </tr>
    </table>
	</form>
	<?
	if(isset($HTTP_POST_VARS["pesquisar"])) {
	  $data = $HTTP_POST_VARS["data_ano"]."-".$HTTP_POST_VARS["data_mes"]."-".$HTTP_POST_VARS["data_dia"];
	  if($data != date("Y-m-d",db_getsession("DB_datausu")))
	    $DB_MSG = "Agenda de outro dia, verifique";
	}
	$sql = "select distinct w12_descr,ag02_codage,ag02_descr,to_char(ag02_dataini,'DD-MM-YYYY') as dataini,ag20_data,to_char(ag20_data,'DD-MM-YYYY') as datastr
	                     from agenda
						 inner join agendam
						 on ag20_codage = ag02_codage
                         left outer join agenmed 
						 on agenmed.ag06_codage = ag02_codage
                         left outer join agenesp 
						 on agenesp.ag05_codage = ag02_codage
						 left outer join especial
						 on w12_codigo = agenesp.ag05_codesp
                         where '".(isset($data)?$data:date("Y-m-d",db_getsession("DB_datausu")))."' = agendam.ag20_data
						 and ((agenmed.ag06_codmed is not null and agenmed.ag06_codmed = $aa01_codig) or (agenesp.ag05_codesp is not null and agenesp.ag05_codesp = $aa01_espec))";
	$agenda = pg_exec($sql);
	$numrows = pg_numrows($agenda);
	if($numrows == 0) {
	  $DB_MSG = "Não existe agenda para esta data.";		
	//else if($numrows == 1) {
//	  db_redireciona("ipa4_atenmed002.php?".base64_encode("nome=".$aa01_nome."&codage=".pg_result($agenda,0,"ag02_codage")."&descricao=".pg_result($agenda,0,"ag02_descr")."&dataini=".pg_result($agenda,0,"ag20_data")."&datastr=".pg_result($agenda,0,"datastr")));
	} else {
	  if(isset($meddir))
        db_redireciona("ipa4_atenmed002.php?".base64_encode("nome=".$aa01_nome."&codage=".pg_result($agenda,0,"ag02_codage")."&descricao=".pg_result($agenda,0,"ag02_descr")."&dataini=".pg_result($agenda,0,"ag20_data")."&datastr=".pg_result($agenda,0,"datastr")));
	?>
	<center>
	<table border="0" width="80%" cellpadding="3" cellspacing="1">
	  <tr bgcolor="#CCFF99">
	  <th nowrap>Código da Agenda</th>
	  <th nowrap>Descrição da Agenda</th>
	  <th nowrap>Data de Início</th>
	  <th nowrap>Data Atual</th>
	  <th nowrap>Médico Resp ou Especialidade</th>
	  </tr>
	  <?
	  $cor1 = "#F7F4A2";
	  $cor2 = "#F1ED58";
	  $cor = "";
	  for($i = 0;$i < $numrows;$i++) {
	    ?> 
		<tr bgcolor="<? echo $cor = ($cor==$cor1?$cor2:$cor1) ?>" style="cursor: hand" onClick="location.href='ipa4_atenmed002.php?<?=base64_encode("nome=".$aa01_nome."&codage=".pg_result($agenda,$i,"ag02_codage")."&descricao=".pg_result($agenda,$i,"ag02_descr")."&dataini=".pg_result($agenda,$i,"ag20_data")."&datastr=".pg_result($agenda,$i,"datastr") )?>'">
		  <td nowrap><?=pg_result($agenda,$i,"ag02_codage")?>&nbsp;</td>
		  <td nowrap><?=pg_result($agenda,$i,"ag02_descr")?>&nbsp;</td>
		  <td nowrap><?=pg_result($agenda,$i,"dataini")?>&nbsp;</td>
		  <td nowrap><?=pg_result($agenda,$i,"datastr")?>&nbsp;</td>
		  <td nowrap><?=pg_result($agenda,$i,"w12_descr")?>&nbsp;</td>		  		  		  		  
		</tr>
		<?
	  }
	  ?>
	</table>
	</center>	
	<?
	} // fim do else do if($numrows == 0)
  }//fim do else do if(pg_numrows($medico) == 0) {
  if(1==2 && !isset($HTTP_POST_VARS["pesquisar"])) {
  	$codmed = str_pad(trim(db_getsession("codmed")),6," ",STR_PAD_LEFT);
	$result = @pg_exec("select distinct to_char(ag30_data,'DD-MM-YYYY') as data,ag30_data
	                   from agenate
					   inner join agenmed
					   on ag30_codage = ag06_codage
					   where ag06_codmed = '$codmed'
					   and ag30_codigo||ag30_data||ag30_hora not in(select ag40_codigo||ag40_data||ag40_hora from atendmed where ag40_medico = '$codmed')
					   order by ag30_data desc limit 15");
    $numrows = @pg_numrows($result);
	echo "<center><table><tr bgcolor=\"#CCFF99\"><th>Datas</th></tr>\n";
	for($i = 0;$i < $numrows;$i++)
	  echo "<tr onClick=\"js_inserir(document.getElementById('linha".$i."').innerText)\" style=\"cursor:hand\" bgcolor=\"=".($i%2==0?"#CCFF99":"#AABB99")."\"><td id=\"linha".$i."\">".@pg_result($result,$i,0)."</td></tr>\n";
	echo "</table></center>\n";
  }
    ?>
	</td>
  </tr>
</table>
<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>
<?
if(isset($DB_MSG)) {
  db_msgbox($DB_MSG);
}
?>
  <Script>
    document.form1.data_dia.select();
  </Script>
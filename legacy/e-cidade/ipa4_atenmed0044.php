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
/*
DB_login=dbseller&DB_id_usuario=1&DB_porta=3055&DB_instit=1&DB_modulo=576
&DB_nome_modulo=ipasem&DB_anousu=2003&DB_datausu=1061485231&codmed=251&nomemed=HERIBERT ADAM&especmed=9
&COD_atendimento=5&w03_codigo=745&w01_regist=579 
*/
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if(isset($exclenc)) {
  $ex = split("#",$exclenc);
  pg_exec("delete from encaminhamento where codate = ".$ex[0]." and codpaciente = '".str_pad($ex[1],6," ",STR_PAD_LEFT)."' and codespec = ".$ex[2]) or die("Erro(14) excluindo encaminhamentos");
}

if(isset($HTTP_POST_VARS["encaminhar"])) {
  $codate = db_getsession("COD_atendimento");
  if(db_getsession("w03_codigo") != "") {
    $depen = '1';
    $codpaciente = db_getsession("w03_codigo");
  } else {
    $depen = '0';
    $codpaciente = db_getsession("w01_regist");
  }
  $codespec = $HTTP_POST_VARS["especial"];
  $data = date("Y-m-d",db_getsession("DB_datausu"));	
  $codmed = db_getsession("codmed");
  $motivo = $HTTP_POST_VARS["motivo"];
  pg_exec("insert into encaminhamento values($codate,'".str_pad(trim($codpaciente),6," ",STR_PAD_LEFT)."',$codespec,'$data','".str_pad(trim($codmed),6," ",STR_PAD_LEFT)."','$depen','$motivo')") or die("Erro(19) inserindo em encaminhamento");
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_iniciar() {
  js_trocacordeselect();
}
function js_verifica() {
  return true;
}
function js_submeter() {
  if(document.form1.especial.selectedIndex == -1) {
    alert("Selecione alguma especialidade");
	return false;
  }
  if(document.form1.motivo.value.length == 0) {
    alert("Campo motivo não pode ser vazio.");
	return false;
  }
  return true;
}
</script>
<style type="text/css">
<!--
.borda {
	border-top-width: 2px;
	border-top-style: inset;
	border-top-color: #999999;
	border-right-width: 2px;
	border-right-style: inset;
	border-right-color: #999999;
	border-bottom-width: 1px;
	border-bottom-style: inset;
	border-bottom-color: #999999;		
}
a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-decoration: none;
	font-weight: bold;
	color:#999999;	
}
a:hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-decoration: none;
	font-weight: bold;	
	color:black;
}
.bordaRL {
	border-top-width: 2px;
	border-top-style: inset;
	border-top-color: #999999;
	border-right-width: 2px;
	border-right-style: inset;
	border-right-color: #999999;
	border-left-width: 2px;
	border-left-style: inset;
	border-left-color: #999999;	
	border-bottom-width: 1px;
	border-bottom-style: inset;
	border-bottom-color: #999999;	
}
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
}-->
</style>
</head>

<body bgcolor=#CCCCCC bgcolor="#FFFF64" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0041.php" onClick="return js_verifica()">Consultas 
            Anteriores</a></td>
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0042.php" onClick="return js_verifica()">Consulta</a></td>
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0043.php" onClick="return js_verifica()">Receita</a></td>
          <td width="140" align="center" nowrap bgcolor="#FFFF64"><strong>Encaminhamentos</strong></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="bordaRL"><a href="ipa4_atenmed0045.php" onClick="return js_verifica()">Exames</a></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="330" valign="top" align="center" bgcolor="#FFFF64">
	   <form name="form1" method="post" onSubmit="return js_submeter()"  action="ipa4_atenmed0044.php">
        <table width="60%" border="0" cellspacing="0" cellpadding="10">
          <tr> 
            <td> <strong>Especialidade:</strong><br>
              <select style="font-size:9px" name="especial" size="10" id="especial">
                <?
			  $result = pg_exec("select w12_codigo,w12_descr from especial order by w12_descr");
			  $numrows = pg_numrows($result);
			  for($i = 0;$i < $numrows;$i++) {
			    db_fieldsmemory($result,$i);
			    echo "<option value=\"".$w12_codigo."\">".$w12_descr."</option>\n";			  
			  }
			  ?>
              </select> </td>
            <td><input type="submit" style="background-color:#FF9B59" name="encaminhar" value="Encaminhar">
              <br>
              <input type="button" onClick="window.open('ipa4_atenmed0144.php?regist=<?=db_getsession('w01_regist')?>&codigo=<?=db_getsession('w03_codigo')?>','',' width=600,height=500,scrollbars=1,resizable=1')" name="Button" value="Imprimir"> </td>
          </tr>
          <tr> 
            <td colspan="2" nowrap><strong>Motivo:&nbsp; 
              <input name="motivo" type="text" id="motivo" size="50" maxlength="500">
              </strong></td>
          </tr>
        </table>
		<?		
		  if(db_getsession("w03_codigo") != "") {
            $codpaciente = db_getsession("w03_codigo");
          } else {
            $codpaciente = db_getsession("w01_regist");
          }	
			$result = pg_exec("select to_char(e.data,'DD-MM-YYYY') as data,esp.w12_descr,m.aa01_nome,(CASE WHEN e.dependente = '1' THEN d.w03_nome ELSE cg.j01_nome END) as paciente,(CASE WHEN e.dependente = '1' THEN 'Sim' ELSE 'Não' END) as dep,e.motivo,codate,codpaciente,codespec
			                   from encaminhamento e
							   inner join medicos m
							   on m.aa01_codig = e.codmedico
							   inner join especial esp
							   on esp.w12_codigo = e.codespec
							   left outer join depen d
							   on d.w03_codigo = e.codpaciente
							   left outer join cadastro c
							   on c.w01_regist = e.codpaciente
							   left outer join cgipa cg
							   on c.w01_numcgi = cg.j01_numero
							   where trim(codpaciente) = trim($codpaciente)
							   order by data desc");
			$numrows = pg_numrows($result);
			if($numrows > 0) {
			  echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n";
			  echo "<tr bgcolor=\"#FDB393\">
			          <th>&nbsp;</th>
			          <th nowrap>Data</th>
					  <th nowrap>Nome Paciente</th>
					  <th nowrap>É Dependente</th>
					  <th nowrap>Nome Médico</th>
					  <th nowrap>Especialidade</th>					  					  
					</tr>\n";
			  for($i = 0;$i < $numrows;$i++) {
			    db_fieldsmemory($result,$i);
			    echo "<tr bgcolor=\"".($i%2==0?"#FFC68C":"#FEBCBC")."\" title=\"$motivo\">
				        <td><input type=\"button\" onclick=\"if(confirm('Quer mesmo excluir este item?')==true) { location.href = 'ipa4_atenmed0044.php?".base64_encode("exclenc=$codate#".trim($codpaciente)."#$codespec")."' ; return true; } else return false;\" name=\"excluirEnc\" value=\"Ex\"></td>
				        <td nowrap>$data</td>
						<td nowrap>$paciente</td>
						<td nowrap>$dep</td>
						<td nowrap>$aa01_nome</td>
						<td nowrap>$w12_descr</td>
					  </tr>";
			  }
			  echo "</table>\n";
			}
	  ?>
      </form> 
	</td>
  </tr>
</table>
</body>
</html>
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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($exclexame)) {
  pg_exec("delete from atendmedexa where id_exame = $exclexame") or die("Erro(8) excluindo atendmedexa");
}

if(isset($HTTP_POST_VARS["gerarexame"])) {
  db_postmemory($HTTP_POST_VARS);
  include("ipa4_atenmed0145.php");
  exit;
}
if(isset($HTTP_POST_VARS["incluirexame"])) {
  $result = pg_exec("select max(id_exame) + 1 from atendmedexa");
  $id_exame = pg_result($result,0,0);
  $id_exame = $id_exame==""?"1":$id_exame;
  $codate = db_getsession("COD_atendimento");
  if(db_getsession("w03_codigo") != "") {
    $depen = '1';
    $codpaciente = db_getsession("w03_codigo");
  } else {
    $depen = '0';
    $codpaciente = db_getsession("w01_regist");
  }
  $codespec = $HTTP_POST_VARS["favoritos"];
  $data = date("Y-m-d",db_getsession("DB_datausu"));	
  $codmed = db_getsession("codmed");
  //$motivo = $HTTP_POST_VARS["motivo"];
  pg_exec("insert into atendmedexa values($id_exame,$codate,'".str_pad(trim($codpaciente),6," ",STR_PAD_LEFT)."',$codespec,'$data','".str_pad(trim($codmed),6," ",STR_PAD_LEFT)."','$depen','')") or die("Erro(26) inserindo em atendmedexa");
/*
  db_postmemory($HTTP_POST_VARS);
  $tam = sizeof($exames);
  pg_exec("begin");
  pg_exec("delete from atendmedexa where ag40_codigo = ".db_getsession("COD_atendimento"));
  for($i = 0;$i < $tam;$i++) 
    pg_exec("insert into atendmedexa values(".db_getsession("COD_atendimento").",".$exames[$i].")") or die("Erro(12) inserindo em atendmedexa");
  pg_exec("commit");
  */
}
if(isset($HTTP_POST_VARS["excluirexames"])) {
  db_postmemory($HTTP_POST_VARS);
  $tam = sizeof($exames);
  pg_exec("begin");
  for($i = 0;$i < $tam;$i++) 	
    pg_exec("delete from atendmedexa where codexa = ".$exames[$i]." and ag40_codigo = ".db_getsession("COD_atendimento"));
  pg_exec("commit");
}

if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(codexa) + 1 from exames");
  $codexa = pg_result($result,0,0);
  $codexa = $codexa==""?"1":$codexa;
  pg_exec("insert into exames values($codexa,".db_getsession("DB_id_usuario").",'$descr')") or die("Erro(12) inserindo em exames");
}
if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from exames where codexa = ".$HTTP_POST_VARS["favoritos"]) or die("Erro(15) deletando tabela exames");
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_resultado(valor) {
    document.getElementById('Iresultado').style.visibility = 'visible';
    Iresultado.location.href = 'ipa4_atenmedresultado.php?id_exame=' + valor;
}
function js_verifica() {
  return true;
}
function js_iniciar() {
  js_trocacordeselect();
}
function js_inserir(texto,valor) {
  document.form1.elements['exames[]'].options[document.form1.elements['exames[]'].length] = new Option(texto,valor);
  js_trocacordeselect();
}
function js_incluir() {
  if(document.form1.descr.value=='') { 
    alert('Informe algum favorito para inclusão');
	return false;
  }
  return true;
}
function js_excluir() {
  if(document.form1.elements['favoritos'].selectedIndex == -1) {
    alert('Selecione algum exame para exclusão');
	return false;
  }
  return confirm('Quer realmente excluir este registro?');
}
function js_submeter() {
  var F = document.form1.exames;
  document.form1.target = '';
  for(var i = 0;i < F.length;i++)
    F.options[i].selected = true;
}
function js_gerarexame() {
  jan = window.open('ipa4_atenmed0145.php?codate=<?=db_getsession("COD_atendimento")?>&regist=<?=db_getsession('w01_regist')?>&codigo=<?=db_getsession('w03_codigo')?>','','height=500,width=600,scrollbars=1');
  jan.moveTo(100,5);
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
<iframe id="Iresultado" name="Iresultado" style="position:absolute; left:113px; top:115px; width:517px; height:199px; z-index:15; visibility: hidden;">gdgfgd</iframe>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0041.php" onClick="return js_verifica()">Consultas 
            Anteriores</a></td>
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0042.php" onClick="return js_verifica()">Consulta</a></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="borda"><a href="ipa4_atenmed0043.php" onClick="return js_verifica()">Receita</a></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="borda"><a href="ipa4_atenmed0044.php" onClick="return js_verifica()">Encaminhamento</a></td>
          <td width="140" align="center" nowrap bgcolor="#FFFF64"><strong>Exames</strong></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="258" valign="top" bgcolor="#FFFF64" align="center">
	  <form name="form1" method="post" action="ipa4_atenmed0045.php">
        <table border="0" cellspacing="0" cellpadding="5">
          <tr> 
            <td width="50%" valign="top" nowrap> <br> <input name="incluirexame" type="submit" onClick="if(document.form1.favoritos.selectedIndex == -1) { alert('Voce deve selecionar algum exame primeiro.'); return false; }" id="incluirexame2" value="Salvar Exames"> 
              &nbsp; 
              <!--input name="excluirexames" type="submit" onClick="return confirm('Quer realmente excluir este registro?')" id="excluirexames" value="Excluir Exames"-->
              <br> <br> <br> <input name="gerarexame" style="background-color:#FF9B59" type="button" id="gerarexame" onClick="js_gerarexame()" value="Gerar Exame"> 
            </td>
            <td width="50%" valign="top" nowrap> <strong>Exames Favoritos:</strong><br> 
              <select ondblclick="js_inserir(this.options[this.selectedIndex].text,this.options[this.selectedIndex].value)" style="width:136px;font-size:9px" name="favoritos" size="10" id="select">
                <?			 
			  $result = pg_exec("select codexa,descr 
			                     from exames 
								 where codmed = ".db_getsession("DB_id_usuario")." order by upper(descr)");
			  $numrows = pg_numrows($result);
			  for($i = 0;$i < $numrows;$i++) {
			    db_fieldsmemory($result,$i);
			    echo "<option value=\"".$codexa."\">".trim($descr)."</option>\n";
			  }
			  ?>
              </select> <br> <input style="width:136px" name="descr" type="text" id="descr2" maxlength="100"> 
              <br> <input name="incluir" onClick="return js_incluir()" type="submit" id="incluir" value="Incluir"> 
              <input name="excluir" onClick="return js_excluir()" type="submit" id="excluir2" value="Excluir"> 
            </td>
          </tr>
        </table>      
      </form>
	  		<?			
			 if(db_getsession("w03_codigo") != "") {
               $depen = '1';
               $codpaciente = db_getsession("w03_codigo");
             } else {
               $depen = '0';
               $codpaciente = db_getsession("w01_regist");
             }
			$result = pg_exec("select to_char(e.data,'DD-MM-YYYY') as data,esp.descr,m.aa01_nome,
			(CASE WHEN e.dependente = '1' THEN d.w03_nome ELSE cg.j01_nome END) as paciente,
			(CASE WHEN e.dependente = '1' THEN 'Sim' ELSE 'Não' END) as dep,e.resultado,e.id_exame,e.codate
			                   from atendmedexa e
							   inner join medicos m
							   on m.aa01_codig = e.codmedico
							   inner join exames esp
							   on esp.codexa = e.codexa
							   left outer join depen d
							   on d.w03_codigo = e.codpaciente
							   left outer join cadastro c
							   on c.w01_regist = e.codpaciente
							   left outer join cgipa cg
							   on c.w01_numcgi = cg.j01_numero
							   where trim(e.codpaciente) = trim('$codpaciente')
							   order by data desc");
			$numrows = pg_numrows($result);
			if($numrows > 0) {
			  echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n";
			  echo "<tr bgcolor=\"#FDB393\">
			          <th>&nbsp;</th>
			          <th nowrap>Cód</th>
					  <th nowrap>Cód Atendimento</th>
			          <th nowrap>Data</th>
					  <th nowrap>Nome Paciente</th>
					  <th nowrap>É Dependente</th>
					  <th nowrap>Nome Médico</th>
					  <th nowrap>Exame</th>
					</tr>\n";
			  for($i = 0;$i < $numrows;$i++) {
			    db_fieldsmemory($result,$i);
			    echo "<tr bgcolor=\"".($i%2==0?"#FFC68C":"#FEBCBC")."\" title=\"$resultado\">
						<td><input type=\"button\" onclick=\"if(confirm('Quer mesmo excluir este item') == true) { location.href='ipa4_atenmed0045.php?".base64_encode("exclexame=$id_exame")."' ; return true; } else { return false; } \" name=\"exexa\" value=\"Ex\"></td>
						<td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$id_exame</td>				
                        <td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$codate</td>						
				        <td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$data</td>
						<td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$paciente</td>
						<td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$dep</td>
						<td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$aa01_nome</td>
						<td style=\"cursor:hand\" onClick=\"js_resultado('$id_exame');\" nowrap>$descr</td>												
					  </tr>";
			  }
			  echo "</table>\n";
			}
	  ?>
	  </td>
  </tr>
</table>
</body>
</html>
<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

db_postmemory($_POST);
db_postmemory($_GET);

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["atualizar"])) {
  db_postmemory($HTTP_POST_VARS);
  $tam = sizeof($campos);
  db_query("BEGIN");
  $result = db_query("select codcam,codsequencia
                       from db_sysarqcamp
                      where codsequencia != 0
                        and codarq = $dbh_tabela");
  if(pg_numrows($result)>0){
    for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i);
      $matcam[$i] = $codcam;
      $matseq[$i] = $codsequencia;
    }
  }
  db_query("delete from db_sysarqcamp where codarq = $dbh_tabela") or die("Erro(14) excluindo db_sysarqcamp");
  for($i = 0;$i < $tam;$i++){
    $codseq = 0;
    if(isset($matcam)){
      for($x=0;$x<sizeof($matcam);$x++){
        if($matcam[$x]==$campos[$i]){
          $codseq = $matseq[$x];
        }
      }
    }
    db_query("insert into db_sysarqcamp values($dbh_tabela,".$campos[$i].",".($i + 1).",".$codseq.")") or die("Erro(16) inserindo em db_sysarqcamp");
  }
  db_query("END");
  db_redireciona('sys3_campos001.php?'.base64_encode("tabelacod=$dbh_tabela"));
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_naoorganizados() {
  var F = document.form1;
  var SI = F.naoorganizados.selectedIndex;

  if(SI != -1) {
    F.elements['campos[]'].options[F.elements['campos[]'].options.length] = new Option(F.naoorganizados.options[SI].text,F.naoorganizados.options[SI].value)
    F.naoorganizados.options[SI] = null;
  //    if(SI <= (F.naoorganizados.length - 1))
  //        F..options[SI].selected = true;
      js_trocacordeselect();
  }
}
function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	F.options[SI - 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    document.form1.naoorganizados.options[document.form1.naoorganizados.length] = new Option(F.options[SI].text,F.options[SI].value);
    F.options[SI] = null;
	js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelect(texto,valor) {
  var F = document.getElementById("campos");
  F.options[F.length] = new Option(texto,valor);
}
function js_procurar() {
  if(document.form1.procuracampo.value == "") {
    alert("Informe algum argumento para pesquisa");
  	document.form1.procuracampo.focus();
  	return false;
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_pesquisa','sys1_campos003.php?campo=' + document.form1.procuracampo.value);

  //jan = window.open('sys1_campos003.php?campo=' + document.form1.procuracampo.value,'','width=220,height=310,location=0');
  //jan.moveTo(450,150);
  return true;
}
function js_selecionar() {
  var F = document.getElementById("campos").options;
  if(document.form1.dbh_tabela.value == "0") {
    alert("Escolha uma tabela, digitando o nome ou parte dele, e clique em tabela.");
	return false;
  }
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
</script>
<style type="text/css">
<!--
td {
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<table width="790" height="100%" border="0" align='center' cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
      <form name="form1" method="post" onSubmit="return js_selecionar()">
        <table width="46%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="10%">

              <table width="100%" border="0" cellspacing="0">
                <tr>
                  <td width="26%"><strong>Módulo:</strong>
                  </td>
                  <td width="74%">
	          			  <select name="dbh_modulo" size="1" onChange="this.form.submit();">
                      <?
										  echo '<option value="0">Nenhum...</option>';
										  $result = db_query("select codmod,nomemod from db_sysmodulo order by nomemod");
										  for($i=0;$i<pg_numrows($result);$i++){
										    echo '<option value="'.pg_result($result,$i,"codmod").'" '.(isset($HTTP_POST_VARS["dbh_modulo"]) && $HTTP_POST_VARS["dbh_modulo"] == pg_result($result,$i,"codmod")?"selected":"").'>'.pg_result($result,$i,"nomemod").'</option>';
										  }
										  ?>
                    </select>
         				  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Tabela:</strong>
                  </td>
                  <td>
                    <select name="dbh_tabela" size="1" style="width:180px" onChange="this.form.submit();">
                      <?
                        echo '<option value="0">Nenhum...</option>';
                        $sql = "select m.codarq,nomearq
                                from db_sysarquivo a
                               inner join db_sysarqmod m on a.codarq = m.codarq ";
                              if(isset($HTTP_POST_VARS["dbh_modulo"]) && $HTTP_POST_VARS["dbh_modulo"] != 0){
                           $sql .= " where m.codmod = ".$HTTP_POST_VARS["dbh_modulo"];
                        }
                        $sql .= " order by nomearq";
                        $result = db_query($sql);
                        for($i=0;$i<pg_numrows($result);$i++){
                          echo '<option value="'.pg_result($result,$i,"codarq").'" '.(isset($HTTP_POST_VARS["dbh_tabela"]) && $HTTP_POST_VARS["dbh_tabela"] == pg_result($result,$i,"codarq")?"selected":"").'>'.pg_result($result,$i,"nomearq").'</option>';
                        }
                      ?>
                    </select>
                  </td>
                </tr>
              </table>

            </td>
            <td width="90%" colspan='2'><br>

              <strong>Campos j&aacute; relacionados:</strong><br>
              <input name="procuracampo" type="text" id="procuracampo5">
              <input name="procurarcampo" onClick="return js_procurar()" type="button" id="procurarcampo6" value="Procurar...">

            </td>


          </tr>
          <tr>
            <td> <strong> Campos sem tabela:</strong><Br>
              <select name="naoorganizados" size="17" ondblclick="js_naoorganizados()" style="width:250px">
                <?
		        $result = db_query("select db_syscampo.codcam,db_syscampo.nomecam
			                   from db_syscampo
					        left join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampo.codcam
					   where substr(db_syscampo.nomecam,1,2) != 'DB' and db_sysarqcamp.codcam is null
                                           order by db_syscampo.codcam desc
 				          ");
                $numrows = pg_numrows($result);
		        if($numrows > 0) {
		          for($i = 0;$i < $numrows;$i++) {
			        echo "<option value=\"".pg_result($result,$i,"codcam")."\">".pg_result($result,$i,"nomecam")."</option>\n";
			      }
		        }
		        ?>
              </select> </td>
            <td> <strong>Campos:</strong><br>
              <select name="campos[]" id="campos" size="17" style="width:250px" multiple>
              <?
		      if(isset($HTTP_POST_VARS["dbh_tabela"])) {
		        $result = db_query("select c.codcam,c.nomecam from db_syscampo c inner join db_sysarqcamp ac on ac.codcam = c.codcam where ac.codarq = $dbh_tabela order by ac.seqarq");
                $numrows = pg_numrows($result);
		        if($numrows > 0) {
		          for($i = 0;$i < $numrows;$i++) {
			        echo "<option value=\"".pg_result($result,$i,"codcam")."\">".pg_result($result,$i,"nomecam")."</option>\n";
			      }
		       }
		     }
		     ?>
             </select>
		   </td>
            <td align="center" valign="middle">
     			    <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" title='Mover para cima' />
              <br/><br/>
              <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" title='Mover para baixo' />
              <br/><br/>
			        <img style="cursor:hand" onClick="js_excluir()" src="skins/img.php?file=Controles/bt_excluir.png" title='Remover da seleção' />
			      </td>
          </tr>
        </table>
        <center>
    			<input name="atualizar" onClick="Botao = 'incluir'" accesskey="a" type="submit" value="Atualizar">
        </center>
      </form>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
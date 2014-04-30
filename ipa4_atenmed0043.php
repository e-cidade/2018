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

require("libs/db_conecta.php");
require("libs/db_stdlib.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
if(isset($HTTP_POST_VARS["atualizar"])) {
  db_postmemory($HTTP_POST_VARS);
  $dataat = $dataat_ano."-".$dataat_mes."-".$dataat_dia;
  if($dataat == "--")
    $dataat = "null";
  else if(!checkdate($dataat_mes,$dataat_dia,$dataat_ano))
    db_erro("Campo \"Atestado a partir da data\" com data inválida");
  else
    $dataat = "'$dataat'";
  @$ag40_altcid = $ag40_altcid==""?$ag40_altcid='f':'t';
  pg_exec("begin");
  pg_exec("update atendmed set  ag40_recint = '".@$ag40_recint."',
                                 ag40_recext = '".@$ag40_recext."',
                                 ag40_altcid = '".@$ag40_altcid."',
			         ag40_tipocons = '".@$ag40_tipocons."',
                                 ag40_tipoform = '".@$ag40_tipoform."',
                                 ag40_taconsulta = '".@$ag40_taconsulta."',
                                 ag40_tacurativo = '".@$ag40_tacurativo."',
                                 ag40_tarevisao	= '".@$ag40_tarevisao."',
				 ag40_diasatestado = '".@$ag40_diasatestado."',
				 ag40_dataatestado  = ".@$dataat."
				where ag40_codigo = $codigo") or die("Erro(62) atualizando atendmed");
  pg_exec("delete from atendmedcid where ag40_codigo = $codigo") or die("Erro(30) excluindo tabela atendmedcid");
  for($i = 0;$i < sizeof(@$ag40_cid);$i++) {
    $result = pg_exec("insert into atendmedcid values($codigo,'".$ag40_cid[$i]."')");
	if(pg_cmdtuples($result) == 0) {
	  pg_exec("rollback");
	  db_erro("Erro(32) incluindo em atendmedcid");	  
	}
  }
  pg_exec("end");
}

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
}

-->
</style>
<script>
aondeC = 'ag40_recint';
function js_verifica() {
  if(tam_receita != document.form1.ag40_recint.value.length)
    return confirm('Houve modificações na receita e ainda não foram salvas.\n Deseja continuar?');
  if(tam_obs != document.form1.ag40_recext.value.length)
    return confirm('Houve modificações no campo observações e ainda não foram salvas.\n Deseja continuar?');
}
function js_desabilitar() {
  document.form1.ag40_diasatestado.disabled = true;
  document.form1.dataat_dia.disabled = true;
  document.form1.dataat_mes.disabled = true;
  document.form1.dataat_ano.disabled = true;		
}
function js_habilitar() {
  document.form1.ag40_diasatestado.disabled = false;
  document.form1.dataat_dia.disabled = false;
  document.form1.dataat_mes.disabled = false;
  document.form1.dataat_ano.disabled = false;		
  document.form1.ag40_diasatestado.focus();
}

function js_iniciar() {
  if(document.form1)
    document.form1.ag40_recint.focus();
  tam_receita = document.form1.ag40_recint.value.length;
  tam_obs = document.form1.ag40_recext.value.length;
  
  if(document.getElementById("tipoc3").checked == true)
    js_habilitar();
  else
    js_desabilitar() 
  if(document.getElementById("tipoc4").checked == true)	
    document.form1.imprimirate.value = 'Imprimir Comprovante';
//  cid = document.form1.ag40_cid.selectedIndex;
  js_trocacordeselect();
}
function js_procurar() {
  if(document.form1.p.value == 'L') {
    document.form1.codcid.value = '';
	document.form1.descr.value = '';
	document.form1.p.value = 'P';
	document.form1.b_inc.disabled = true;
	document.form1.codcid.focus();
  } else {
    procuracid.location.href = 'ipa4_atenmedconscid.php?codcid=' +   document.form1.codcid.value + '&descr=' + document.form1.descr.value;
  }
}
function js_inserir(chave,chave1){
  document.form1.codcid.value = chave;
  document.form1.descr.value = chave1;
  document.getElementById('procuracid').style.visibility = 'hidden';
  document.form1.b_inc.disabled = false;
  document.form1.p.value = 'L';
}  
function js_inccid() {
  document.form1.elements['ag40_cid[]'].options[document.form1.elements['ag40_cid[]'].options.length] = new Option(document.form1.descr.value,document.form1.codcid.value);
  document.form1.codcid.value = '';
  document.form1.descr.value = '';
  document.form1.p.value = 'P';
  document.form1.b_inc.disabled = true;
//  document.form1.b_exc.disabled = true;  
  document.form1.codcid.focus();  
  js_trocacordeselect();
}
function js_exccid() {
  var str = new String(document.form1.codcid.value);
  document.form1.codcid.value = str.toUpperCase();
  document.form1.elements['ag40_cid[]'].options[document.form1.elements['ag40_cid[]'].selectedIndex] = null;
  document.form1.b_inc.disabled = true;
//  document.form1.b_exc.disabled = true;    
  js_trocacordeselect();
}
function js_submeter() {
  var F = document.form1;
  for(i = 0;i < F.elements['ag40_cid[]'].options.length;i++){
    F.elements['ag40_cid[]'].options[i].selected = true;
  }
  for(i=0;i<document.form1.ag40_tipocons.length;i++){
    if(document.form1.ag40_tipocons[i].checked == false){
      var x = 1;
    }else{
      if(document.form1.ag40_tipocons[i].checked == true){
        var x = 0;
	break;
      }
    }
  }
  if(x == 1){
    alert('Campo Tipo de Consulta deve ser preenchido!');
    return false;
  }  
  for(i=0;i<document.form1.ag40_tipoform.length;i++){
    if(document.form1.ag40_tipoform[i].checked == false){
      var x = 1;
    }else{
      if(document.form1.ag40_tipoform[i].checked == true){
        var x = 0;
	break;
      }
    }
  }
  if(x == 1){
    alert('Campo Tipo de Formulário deve ser preenchido!');
    return false;
  }
  if(document.getElementById('tipoc3').checked == true){
    if(document.form1.ag40_diasatestado.value == ""){
      alert('Informe os dias de Atestado');
      document.form1.ag40_diasatestado.focus();
      return false;
    }else{
      return true;
    }
  }else{
    return true;
  }
return false;      
}
function js_imprimiratestado() {
  js_submeter();
  jan = window.open('ipa4_atenmed0143.php?ag40_codigo=<?=db_getsession("COD_atendimento")?>','imprimir','height=500,width=650,scrollbars=1');
  jan.moveTo(100,5);
}
function js_imprimirreceita() {
  js_submeter();
  jan = window.open('ipa4_atenmed0243.php?ag40_codigo=<?=db_getsession("COD_atendimento")?>','imprimir','height=500,width=650,scrollbars=1');
  jan.moveTo(100,5);
}
</script>
</head>

<body bgcolor=#CCCCCC bgcolor="#FFFF64" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0041.php" onClick="return js_verifica()">Consultas 
            Anteriores</a></td>
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0042.php" onClick="return js_verifica()">Consulta</a></td>
          <td width="140" align="center" nowrap bgcolor="#FFFF64"><strong>receita</strong></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="borda"><a href="ipa4_atenmed0044.php" onClick="return js_verifica()">Encaminhamento</a></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="borda"><a href="ipa4_atenmed0045.php" onClick="return js_verifica()">Exames</a></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="334" valign="top" bgcolor="#FFFF64">	
	<?
	$result = pg_exec("select * from atendmed where ag40_codigo = ".db_getsession("COD_atendimento"));
	if(pg_numrows($result) > 0)
	  db_fieldsmemory($result,0);
	?>
	<form name="form1" method="post" onSubmit="return js_submeter()">
	<input type="hidden" name="codigo" value="<?=db_getsession('COD_atendimento')?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr> 
            <td valign="middle">
<table width="136" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td nowrap> 
				    <fieldset>
                    <legend><strong>Tipo de Consulta:&nbsp;</strong></legend>
                    <input type="radio" name="ag40_tipocons" id="tipoc1" value="pa" <? echo (isset($ag40_tipocons)?($ag40_tipocons=="pa"?"checked":""):"") ?>>
                    <label for="tipoc1">Pronto Atendimento</label>
                    <br>
                    <input type="radio" name="ag40_tipocons" id="tipoc2" value="cm" <? echo (isset($ag40_tipocons)?($ag40_tipocons=="cm"?"checked":""):"checked") ?>>
                    <label for="tipoc2">Consultorio Medico</label>
                    </fieldset>
                    <fieldset>
                    <legend><strong>Tipo do Formulario:&nbsp;</strong></legend>
                    <input type="radio" name="ag40_tipoform" onClick="(this.checked?js_habilitar():js_desabilitar());this.form.imprimirate.value = 'Imprimir Atestado'" id="tipoc3" value="a" <? echo (isset($ag40_tipoform)?($ag40_tipoform=="a"?"checked":""):"") ?>>
                    <label for="tipoc3">Atestado</label>
                    <br>
                    <input type="radio" name="ag40_tipoform" onClick="js_desabilitar();this.form.imprimirate.value = 'Imprimir Comprovante'" id="tipoc4" value="c" <? echo (isset($ag40_tipoform)?($ag40_tipoform=="c"?"checked":""):"checked") ?>>
                    <label for="tipoc4">Comprovante</label>
                    
                    </fieldset>
                    <fieldset>
                    <legend><strong>Tipo de Atendimento:&nbsp;</strong></legend>
                    <input type="checkbox" name="ag40_taconsulta" id="tipoc5" value="1" <? echo (isset($ag40_taconsulta)?($ag40_taconsulta=="1"?"checked":""):"checked") ?>>
                    <label for="tipoc5">Consulta</label>
                    <br>
                    <input type="checkbox" name="ag40_tacurativo" id="tipoc6" value="1" <? echo (isset($ag40_tacurativo)?($ag40_tacurativo=="1"?"checked":""):"") ?>>
                    <label for="tipoc6">Curativo</label>
                    <br>
                    <input type="checkbox" name="ag40_tarevisao" id="tipoc7" value="1" <? echo (isset($ag40_tarevisao)?($ag40_tarevisao=="1"?"checked":""):"") ?>>
                    <label for="tipoc7">Revisão</label>
                    </fieldset><br>
					<input type="checkbox" name="ag40_altcid" id="tipoc8" value="t" <? echo (isset($ag40_altcid)?($ag40_altcid=="t"?"checked":""):"") ?>>
                    <label for="tipoc8">Autoriza CID</label><br>
					<strong>Dias de Atestado:</strong><br>
					<input type="text" name="ag40_diasatestado" value="<?=@$ag40_diasatestado?>" size="3" maxlength="3"><br>
					<strong>Atestado a partir da data:</strong><Br>
					<?
 					  $dataat_ano = date('Y');  
					  $dataat_mes = date('m');  
					  $dataat_dia = date('d');  
					db_data("dataat","$dataat_dia","$dataat_mes","$dataat_ano");
					?>
					</td>
                </tr>
              </table> </td>
            <td width="55%" valign="top" nowrap>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td nowrap>
	            <strong>Uso Interno:</strong><br> <textarea name="ag40_recint" onClick="aondeC = this.name" cols="47" rows="5"><?=@$ag40_recint?></textarea>
                    <strong><br>
                    Uso Externo:</strong><br> <textarea name="ag40_recext" onClick="aondeC = this.name" cols="47" rows="5"><?=@$ag40_recext?></textarea>
					<!--/fieldset-->
				  </td>
                </tr>
                <tr>
                  <td nowrap>
				    <fieldset>
					<legend><strong>CID:</strong></legend>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap><iframe id="procuracid" name="procuracid" style="background-color: #cccccc;position:absolute; left:96px; top:20px; width:470px; height:230px; z-index:10; visibility: hidden;"></iframe> 
                          <input name="codcid" type="text" id="codcid2" size="4"> 
                          &nbsp; <input name="descr" type="text" id="descr2" size="45"> 
                          <input type="button" name="p" value="Procurar" onClick="js_procurar()"> 
                        </td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><select style="width:50ex;" name="ag40_cid[]" multiple size="3">
                                  <?
					$result = pg_exec("select c.codcid,c.descr 
					                   from cid10 c
									   inner join atendmedcid a
									   on a.ag40_codcid = c.codcid
									   where ag40_codigo = ".db_getsession("COD_atendimento"));
					$numrows = pg_numrows($result);
					if($numrows > 0)
					  for($i = 0;$i < $numrows;$i++) {
					    db_fieldsmemory($result,$i);
					    echo "<option value=\"".$codcid."\">".$descr."</option>\n";
					  }
					?>
                                </select></td>
                              <td valign="middle">
							    <input type="button" style="width:60px" name="b_inc" value="Incluir" onClick="js_inccid()" disabled>
                                <input type="button" style="width:60px" name="b_exc" value="Excluir" onClick="js_exccid()"> 
                              </td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>                    
                    </fieldset>
				  </td>
                </tr>                
              </table>
            </td>
            <td valign="top">
			  <table border="0" cellpadding="0" cellspacing="0">
			  <tr><td><iframe src="ipa4_atenmedfavoritos.php" frameborder="0" scrolling="no" width="150" height="230"></iframe></td></tr>
			  <tr><td>
                <input type="submit" style="background-color:#FF9B59" onClick="this.form.target = ''" name="atualizar" value="Atualizar"><br><br>
                <input name="imprimirrec" type="submit" onClick="js_imprimirreceita()" style="width:130px" id="imprimirrec2" value="Imprimir Receita">
                <input name="imprimirate" onClick="js_imprimiratestado()" type="submit" style="width:130px"  value="Imprimir Atestado">              
			  </td></tr>
			  </table>
			</td>
          </tr>
        </table>
      </form>
	</td>
  </tr>
</table>
</body>
</html>
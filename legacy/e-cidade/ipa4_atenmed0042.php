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
include("dbforms/db_funcoes.php");
if(isset($HTTP_POST_VARS["atualizar"])) {
  @$ag40_horafimate = date("H:i");
  db_postmemory($HTTP_POST_VARS);
  if(!checkdate($ag40_data_mes,$ag40_data_dia,$ag40_data_ano))
    db_erro("Erro(10) Data Inválida");
  else
    $data = $ag40_data_ano."-".$ag40_data_mes."-".$ag40_data_dia;

  if(!empty($w03_codigo)) {
    pg_exec("update depen set w03_obsmed = '$obsmed' where w03_codigo = '".db_formatar($w03_codigo,"s"," ",6)."'") or die("Erro(15) atualizando depen");
  } else {
    pg_exec("update cadastro set w01_obsmed = '$obsmed' where w01_regist = '".db_formatar($w01_regist,"s"," ",6)."'") or die("Erro(17) atualizando cadastro");
  }

  $codmed = trim(db_getsession("codmed"));
  $codmed = db_formatar($codmed,"s"," ",6);
  $result = pg_exec("select ag40_codigo from atendmed where ag40_codigo = $codigo");  
  if(pg_numrows($result) == 0) {    
    $result = pg_exec("select nextval('atendmed_ag40_codate_seq')");
	$codate = pg_result($result,0,0);
    pg_exec("insert into atendmed(ag40_codate,
	                              ag40_codigo,
                                  ag40_data,
                                  ag40_hora,
                                  ag40_pressao,
                                  ag40_temperatura,
								  ag40_altura,
								  ag40_freqcard,
								  ag40_freqresp,                                  
                                  ag40_diag,
								  ag40_peso,                               
								  ag40_medico,
                                  ag40_espec,
								  ag40_horainiate,
								  ag40_horafimate)
						  values($codate,
						         $codigo,
								 '$data',
								 '$ag40_hora',
								 '$ag40_pressao',
								 '$ag40_temperatura',
								 '$ag40_altura',
								 '$ag40_freqcard',
								 '$ag40_freqresp',
								 '$ag40_diag',
								 '$ag40_peso',
								 '".$codmed."',
								 ".db_getsession("especmed").",
								 '".$ag40_horainiate."',
								 '".$ag40_horafimate."')") or die("Erro(43) inserindo em atendmed");
  } else {  
  @$ag40_horafimate = date("H:i");
    pg_exec("update atendmed set  ag40_data = '$data',
                                  ag40_hora = '$ag40_hora',
                                  ag40_pressao = '$ag40_pressao',
                                  ag40_temperatura = '$ag40_temperatura',
								  ag40_altura = '$ag40_altura',
								  ag40_freqcard = '$ag40_freqcard',
								  ag40_freqresp = '$ag40_freqresp',                           
                                  ag40_diag =  '$ag40_diag',
								  ag40_peso = '$ag40_peso',                    
								  ag40_horainiate = '".$ag40_horainiate."',
								  ag40_horafimate = '".$ag40_horafimate."'								  
					where ag40_codigo = $codigo") or die("Erro(62) atualizando atendmed");	
  }
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
.bordaT {
	border-top-width: 2px;
	border-top-style: inset;
	border-top-color: #999999;
	border-right-width: 2px;
	border-right-style: inset;
	border-right-color: #999999;
	border-bottom-width: 1px;
	border-bottom-style: inset;
	border-bottom-color: #999999;		
	border-left-width: 2px;
	border-left-style: inset;
	border-left-color: #999999;
}
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
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

input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}-->
</style>
<script>
function js_iniciar() {
  var F = document.form1;
  if(document.form1) {
    document.form1.ag40_data_dia.select();	
  }
  tam_data = F.ag40_data_dia.value + F.ag40_data_mes.value + F.ag40_data_ano.value;
  tam_hora = F.ag40_hora.value;
  tam_pressao = F.ag40_pressao.value;
  tam_temperatura = F.ag40_temperatura.value;
  tam_altura = F.ag40_altura.value;
  tam_freqcard = F.ag40_freqcard.value;
  tam_freqresp = F.ag40_freqresp.value;
  tam_diag = F.ag40_diag.value.length;  
  tam_obsmed = F.obsmed.value.length;
  tam_peso = F.ag40_peso.value;  	
}
function js_verificar() {
  var F = document.form1;
  if(tam_data != F.ag40_data_dia.value + F.ag40_data_mes.value + F.ag40_data_ano.value)
    return confirm('Houve modificações da data e ainda não foram salvas.\n Deseja continuar?');
  if(tam_hora != F.ag40_hora.value)
    return confirm('Houve modificações do campo hora e ainda não foram salvas.\n Deseja continuar?');
  if(tam_pressao != F.ag40_pressao.value)
    return confirm('Houve modificações no campo pressao e ainda não foram salvas.\n Deseja continuar?');
  if(tam_temperatura != F.ag40_temperatura.value)
    return confirm('Houve modificações no campo temperatura e ainda não foram salvas.\n Deseja continuar?');
  if(tam_altura != F.ag40_altura.value)
    return confirm('Houve modificações no campo altura e ainda não foram salvas.\n Deseja continuar?');
  if(tam_freqcard != F.ag40_freqcard.value)
    return confirm('Houve modificações no campo frequencia cardíaca e ainda não foram salvas.\n Deseja continuar?');
  if(tam_freqresp != F.ag40_freqresp.value)
    return confirm('Houve modificações no campo frequencia respiratória e ainda não foram salvas.\n Deseja continuar?');
  if(tam_peso != F.ag40_peso.value)
    return confirm('Houve modificações no campo peso e ainda não foram salvas.\n Deseja continuar?');
  if(tam_diag != F.ag40_diag.value.length)
    return confirm('Houve modificações no campo diagnóstico e ainda não foram salvas.\n Deseja continuar?');							
  if(tam_obsmed != F.obsmed.value.length)
    return confirm('Houve modificações no campo observações e ainda não foram salvas.\n Deseja continuar?');							
}
</script>
</head>

<body bgcolor=#CCCCCC bgcolor="#FFFF64" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="140" class="borda" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0041.php" onClick="return js_verificar()">Consultas 
            Anteriores</a></td>
          <td width="140" align="center" nowrap bgcolor="#FFFF64"><strong>Consulta</strong></td>
          <td width="140" class="bordaT" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0043.php" onClick="return js_verificar()">receita</a></td>
          <td width="140" class="bordaT" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0044.php">Encaminhamento</a></td>
          <td width="140" class="bordaT" align="center" nowrap bgcolor="#EAEAEA"><a href="ipa4_atenmed0045.php">Exames</a></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="330" valign="top" bgcolor="#FFFF64">	
	<?
	$result = pg_exec("select *,to_char(ag40_data,'DD') as ag40_data_dia,to_char(ag40_data,'MM') as ag40_data_mes,to_char(ag40_data,'YYYY') as ag40_data_ano 
	                   from atendmed 
					   where ag40_codigo = ".db_getsession("COD_atendimento"));
	if(pg_numrows($result) > 0)
	  db_fieldsmemory($result,0);
	else {
	  $ag40_data_dia = date("d");
	  $ag40_data_mes = date("m");
	  $ag40_data_ano = date("Y");
	  //$ag40_hora = date("H:i");
	}
	$result = pg_exec("select ag30_hora from agenate where ag30_codigo = ".db_getsession("COD_atendimento"));
	if(pg_numrows($result) > 0)
	  $ag40_hora = pg_result($result,0,0);
	if(db_getsession("w03_codigo") != "") {
	  $sql = "select w03_obsmed from depen where w03_codigo = '".db_formatar(db_getsession("w03_codigo"),"s"," ",6)."'";
	  $result = pg_exec($sql);
	  if(pg_numrows($result) > 0)
	    $obsmed = pg_result($result,0,0);
    } else if(db_getsession("w01_regist") != "") {
	  $result = pg_exec("select w01_obsmed from cadastro where w01_regist = '".db_formatar(db_getsession("w01_regist"),"s"," ",6)."'");
      if(pg_numrows($result) > 0)
	    $obsmed = pg_result($result,0,0);
	}
	?>
	<form name="form1" method="post">
	<input type="hidden" name="codigo" value="<?=db_getsession('COD_atendimento')?>">
	    <table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr> 
            <td nowrap><strong>Data:</strong></td>
            <td nowrap><strong>Hora:</strong></td>
            <td nowrap><strong>Pressão:</strong></td>
            <td nowrap><strong>Temperatura:</strong></td>
            <td nowrap><strong>Altura:</strong></td>
            <td nowrap><strong>F. C.</strong></td>
          </tr>
          <tr> 
            <td nowrap> 
              <?
			db_data("ag40_data","$ag40_data_dia","$ag40_data_mes","$ag40_data_ano");
			?>
            </td>
            <td nowrap><input name="ag40_hora" type="text" value="<?=@$ag40_hora?>" size="5" maxlength="5"></td>
            <td nowrap><input name="ag40_pressao" type="text" value="<?=@$ag40_pressao?>" size="6" maxlength="20"></td>
            <td nowrap><input name="ag40_temperatura" type="text" value="<?=@$ag40_temperatura?>" size="6" maxlength="20"></td>
            <td nowrap><input name="ag40_altura" type="text" value="<?=@$ag40_altura?>" size="6" maxlength="20"></td>
            <td nowrap><input name="ag40_freqcard" type="text" value="<?=@$ag40_freqcard?>" size="6" maxlength="20"></td>
          </tr>
          <tr>
            <td nowrap><strong>F. R.</strong><input name="ag40_freqresp" type="text" value="<?=@$ag40_freqresp?>" size="6" maxlength="20"></td>
            <td nowrap><strong>Peso:</strong>
            <input name="ag40_peso" type="text" id="ag40_peso" value="<?=@$ag40_peso?>" size="6" maxlength="20"></td>
            <td colspan="6" nowrap><strong>Hora de In&iacute;cio: 
              <input name="ag40_horainiate" type="text" value="<? echo @$ag40_horainiate==""?date("H:i"):$ag40_horainiate ?>" size="5" maxlength="5">
              &nbsp;&nbsp;Hora de Fim: 
              <input name="ag40_horafimate" type="text" value="<?=$ag40_horafimate?>" size="5" maxlength="5">
              &nbsp;&nbsp;&nbsp; 
              <input type="hidden" name="w03_codigo" value="<?=db_getsession("w03_codigo")?>">
              <input type="hidden" name="w01_regist" value="<?=db_getsession("w01_regist")?>">
              <input type="submit" style="background-color:#FF9B59" name="atualizar" value="Atualizar">
              </strong></td>
          </tr>
          <tr valign="middle"> 
            <td colspan="8" nowrap>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="40"><strong>Diag:</strong></td>
                  <td>
				    <textarea name="ag40_diag" cols="70" rows="10"><?=@$ag40_diag?></textarea>
                  </td>
                </tr>
              </table>
              </td>
          </tr>
          <tr align="center"> 
            <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="40"> <strong>Obs:</strong> </td>
                  <td> <textarea name="obsmed" cols="70" rows="4" id="textarea"><?=@$obsmed?></textarea> 
                  </td>
                </tr>
              </table></td>
          </tr>
        </table>
		</form>
	</td>
  </tr>
</table>
</body>
</html>
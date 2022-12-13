<?php
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

session_start();

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issplan_classe.php");
require_once("classes/db_issplanit_classe.php");
require_once("classes/db_issplananula_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_cancdebitos_classe.php");
require_once("classes/db_cancdebitosreg_classe.php");
require_once("classes/db_cancdebitosproc_classe.php");
require_once("classes/db_cancdebitosprocreg_classe.php");
require_once("classes/db_cancdebitosissplan_classe.php");

$sqlerro     = false;
$clissplanit = new cl_issplanit;
$ip          = $HTTP_SERVER_VARS['REMOTE_ADDR'];

if(isset($anular)){

  if(isset($ultima) and $ultima == "sim"){

  	$clissplanit->q21_sequencial = $q21_sequencial;
		$clissplanit->q21_status     = 3 ;
		$clissplanit->alterar($q21_sequencial);
		if ($clissplanit->erro_status == 0) {

      $sqlerro = true;
      $erro_msg = $clissplanit->erro_msg;
    }
  }
	if($sqlerro== false){
  	$sqlerro = db_anulaPlanilha($planilha,$motivo,$ip);
	}
}
?>
<html>
<head>
<title>Anulação de planilha</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script type="text/javascript">
	function js_voltar(most){

		mes        = document.form1.mes.value;
 	  ano        = document.form1.ano.value;
 	  numcgm     = document.form1.numcgm.value;
		plan       = document.form1.planilha.value;
	  inscricaow = document.form1.inscricaow.value;
		location.href = 'planilha.php?plan='+plan+'&mes='+mes+'&ano='+ano+'&numcgm='+numcgm+'&mostra='+most+'&inscricaow='+inscricaow;
	}
</script>
<style type="text/css">
<?php db_estilosite(); ?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<center>
	<form name="form1" >
  	<input name="planilha"       value="<?=$planilha?>"       type="hidden" />
  	<input name="mes"            value="<?=$mes?>"            type="hidden" />
  	<input name="ano"            value="<?=$ano?>"            type="hidden" />
  	<input name="numcgm"         value="<?=$numcgm?>"         type="hidden" />
  	<input name="inscricaow"     value="<?=$inscricaow?>"     type="hidden" />
    <input name="q21_sequencial" value="<?=$q21_sequencial?>" type="hidden" />
  	<input name="ultima"         value="<?=$ultima?>"         type="hidden" />
    <table width="500px" border="0" cellpadding="0" cellspacing="0" class="texto">
      <tr>
        <td width="10%"><strong>Planilha:</strong></td>
  		  <td> <?php echo $planilha; ?></td>
  		</tr>
  	  <tr>
      	<td width="10%"><strong>Data:</strong></td>
  		  <td><?php echo date("d/m/Y");?></td>
  		</tr>
  		<tr>
      	<td width="10%"><strong>Motivo:</strong></td>
  		  <td> <textarea rows="3" cols="70" name="motivo" ></textarea> </td>
  		</tr>
  		<tr>
      	<td colspan="2" align="center">
  			 <input name="anular" value="Anular planilha" type="submit" class="botao">
  			 <input name="voltar" value="Voltar" type="button" class="botao" onclick="js_voltar(1);">
  			</td>
  		</tr>
    </table>
	</form>
</center>
</body>
<html>

<?php

	if(isset($anular)){

	  if($sqlerro == true){
			db_msgbox("Anulação não efetuada.");
	  }else{

	  	db_msgbox("Anulação efetuada com sucesso");
			echo "<script> js_voltar(6); </script> ";
	  }
	}

function db_anulaPlanilha($planilha,$motivo, $ip){

	$clissplananula = new cl_issplananula;
  $clissplan      = new cl_issplan;
  $clarrecad      = new cl_arrecad;
  $clcancdebitos  = new cl_cancdebitos;

	$data = date("Y-m-d");
	$hora = date("H:i");

  $usuario = db_getsession("id");
  if($usuario == ""){
	  $usuario = 1;
  }
	$sql = "select * from issplan where q20_planilha = $planilha";
	$result = db_query($sql);
	$linhas = pg_num_rows($result);
	if($linhas > 0){
		$q20_numpre = pg_result($result,0,"q20_numpre");
	}

	$sqlerro = false;
	db_inicio_transacao();

	//gravar na issplananula: os dados da anulação
	$clissplananula->q76_planilha   = $planilha;
	$clissplananula->q76_data       = $data;
	$clissplananula->q76_hora       = $hora;
	$clissplananula->q76_motivo     = "Planilha anulada DBPref. ".$motivo ;
	$clissplananula->q76_ip         = $ip;
	$clissplananula->q76_id_usuario = $usuario;
	$clissplananula->incluir(null);
	if ($clissplananula->erro_status == 0) {

    $sqlerro = true;
    $erro_msg = $clissplananula->erro_msg;
  }

	//alterar a situação da issplan para anulada
	$clissplan->q20_planilha = $planilha;
	$clissplan->q20_situacao = 5;
	$clissplan->alterar($planilha);
	if ($clissplan->erro_status == 0) {

    $sqlerro  = true;
    $erro_msg = $clissplan->erro_msg;
  }
  if($q20_numpre > 0){

		//gravar na cancdebitos, cancdebitosreg, cancdebitosproc, cancdebitosprocreg
		$sqltipo    = "select w10_tipo from db_confplan ";
		$resulttipo = db_query($sqltipo);
		$linhastipo = pg_num_rows($resulttipo);
		if($linhastipo > 0){
			$w10_tipo = pg_result($resulttipo,0,"w10_tipo");
		}else{

			$sqlerro = true;
			$erro_msg = "Deve-se configurar a planilha (db_confplan)";
		}
	  $clcancdebitos->k20_descr           = "anulação de planilha no dbpref.";
		$clcancdebitos->k20_hora            = $hora;
		$clcancdebitos->k20_data            = $data;
		$clcancdebitos->k20_usuario         = $usuario;
		$clcancdebitos->k20_instit          = db_getsession("DB_instit");
    $clcancdebitos->k20_cancdebitostipo = 1;
		$clcancdebitos->tipo                = $w10_tipo;
    $clcancdebitos->numpre              = $q20_numpre;
    $clcancdebitos->numpar              = 1;
    $clcancdebitos->k21_obs             = "Planilha anulada DBPref. ".$motivo;
    $clcancdebitos->usuario             = $usuario;
		$clcancdebitos->planilha            = $planilha;
		$clcancdebitos->incluir_cancelamento(true);
		if ($clcancdebitos->erro_status == "0") {

	    $sqlerro = true;
	    $erro_msg = $clcancdebitos->erro_msg;
	  }

		//grava na cancdebitosissplan
		//deletar da arrecad e gravar na arrecant
		$clarrecad->excluir_arrecad_inc_arrecant($q20_numpre,1, true);
		if ($clarrecad->erro_status == 0) {

	    $sqlerro = true;
	    $erro_msg = $clarrecad->erro_msg;
	  }
	}
	db_fim_transacao($sqlerro);

	return $sqlerro;
}
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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
require ("libs/db_utils.php");
include ("classes/db_protprocesso_classe.php");
include ("classes/db_procandam_classe.php");
include ("classes/db_proctransfer_classe.php");
include ("classes/db_proctransferproc_classe.php");
include ("classes/db_proctransand_classe.php");
include ("classes/db_proctransferintand_classe.php");
include ("classes/db_proctransferint_classe.php");
include ("classes/db_procandamint_classe.php");
include ("classes/db_procandamintand_classe.php");
include ("classes/db_arqproc_classe.php");
include ("classes/db_arqandam_classe.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_protparam_classe.php");

//se $db21_usasisagua = 't' utiliza o modulo agua
db_sel_instit(null, "db21_usasisagua");

db_postmemory($_SERVER);
db_postmemory($_POST);

$utils = new db_utils();
$oGet = $utils->postMemory(array_merge($_GET, $_POST));

$clprotprocesso       = new cl_protprocesso;
$clprocandam          = new cl_procandam;
$clproctransfer       = new cl_proctransfer;
$clproctransferproc   = new cl_proctransferproc;
$clproctransand       = new cl_proctransand;
$clproctransferintand = new cl_proctransferintand;
$clproctransferint    = new cl_proctransferint;
$clprocandamint       = new cl_procandamint;
$clprocandamintand    = new cl_procandamintand;
$clarqproc            = new cl_arqproc;
$clarqandam           = new cl_arqandam;
$clprotparam          = new cl_protparam;

$cod_procandamint = 0;
$arquiv = false;
$arqant = false;
$sWhere = "";
$sAnd   = "";

// seleciona o nome da instituição pelo código do processo e coloca o resultado na váriavel $nomeinstabrev
if (isset($codproc) && !empty($codproc)) {

  $sWhere .= " {$sAnd} p58_codproc = {$codproc} ";
  $sAnd    = " and ";
}

if (isset($numeroprocesso) && !empty($numeroprocesso)) {

  $aPartesNumero = explode("/", $numeroprocesso);
  $iAno = db_getsession("DB_anousu");
  if (count($aPartesNumero) > 1) {
    $iAno = $aPartesNumero[1];
  }

  $iNumero = $aPartesNumero[0];
  $sWhere .= " {$sAnd} p58_ano = {$iAno} and p58_numero = '{$iNumero}'";
  $sAnd    = " and ";
}

$sSqlDbConfig  = "select nomeinstabrev from db_config where codigo = ";
$sSqlDbConfig .= " (select p58_instit from protprocesso where {$sWhere})";
$sRetorno      = db_query($sSqlDbConfig);
$rsResultado   = pg_fetch_assoc($sRetorno);
$nomeinstabrev = $rsResultado["nomeinstabrev"];
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script>
function js_imprime(cod) {

  jan = window.open('pro2_relconspro002.php?codproc='+cod,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
</head>

<div id='int_perc1' align="left" style="position: absolute; top: 30%; left: 35%; float: left; width: 200; background-color: #ECEDF2; padding: 5px; margin: 0px; border: 1px #C2C7CB solid; margin-left: 10px; font-size: 80%; visibility: hidden">
  <div style="border: 1px #ffffff solid; margin: 8px 3px 3px 3px;">
	  <div id='int_perc2' style="width: 100%; background-color: #eaeaea;" align="center">
	    <img src="imagens/processando.gif" align="middle" />
	    Processando...
	  </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<form name=form1 action="">
<center>
<table width="100%" class="texto">
	<tr>
		<td>
		<?
		  $result_param = $clprotparam->sql_record($clprotparam->sql_query(null,"*",null,"p90_instit=".db_getsession("DB_instit")));

		  if ($clprotparam->numrows > 0) {
			  db_fieldsmemory($result_param, 0);
		  }

		  if (isset($oGet->codproc) || isset($oGet->numeroprocesso)) {

        //Validação se o CPF/CNPJ passado para pesquisa do processo de protocolo é o mesmo do titular do processo.
        if (isset($oGet->cpf) && !empty($oGet->cpf)) {

				  $sWhere .= " {$sAnd} z01_cgccpf = '".ereg_replace("[./-]", "", $oGet->cpf)."' ";
				  $sAnd    = " and ";
        } else if (isset($oGet->cgc) && !empty($oGet->cgc)) {

				  $sWhere .= " {$sAnd} z01_cgccpf = '".ereg_replace("[./-]", "", $oGet->cgc)."' ";
				  $sAnd    = " and ";
        }

        $sSqlProtProcesso    = $clprotprocesso->sql_query(null, "*", null, $sWhere);
        $result_protprocesso = $clprotprocesso->sql_record($sSqlProtProcesso);
        // se existem linhas processa, se não exibe mensagem que não encontrou processos com o código fornecido

        if ($clprotprocesso->numrows > 0) {

          db_fieldsmemory($result_protprocesso, 0);

          if (empty($codproc)) {
            $codproc = $p58_codproc;
          }

				  echo "<table width='100%' class='texto'border=0>
                <tr>
                  <td width=280px><b>NÚMERO DE CONTROLE DO PROCESSO:</b></td>
                  <td nowrap colspan='3'>$codproc</td>
                </tr>
	              <tr>
			            <td width=280px><b>NÚMERO DO PROCESSO:</b></td>
                  <td width=350pxnowrap>$p58_numero/$p58_ano </td>
                  <td width=100px><b>NOME:</b></td>
                  <td nowrap>$z01_nome</td>
		            </tr>
		            <tr>
                  <td><b>DATA:</b></td>
                  <td nowrap>".db_formatar($p58_dtproc, 'd')."</td>
                  <td><b>HORA:</b></td>
                  <td nowrap>$p58_hora&nbsp;</td>
		            </tr>
		            <tr>
                  <td><b>TIPO:</b></td>
			            <td nowrap>$p51_descr</td>
			            <td><b>ATENDENTE:</b></td>
			            <td nowrap>$nome</td>
		            </tr>
	              <tr>
                  <td><b>DEPARTAMENTO:</b> </td>
                  <td colspan='3' nowrap>$p58_coddepto-$descrdepto</td>
                </tr>
                <tr>
                  <td><b>INSTITUIÇÃO:</b> </td>
                  <td colspan='3' nowrap>$p58_instit-$nomeinst</td>
		            </tr>
		            <tr>
                  <td><b>REQUERENTE:</b> </td>
			            <td nowrap>$p58_requer</td>
			            <td colspan=2>
                    <input name='imprimir' class='botao' type='button' value='Imprimir Consulta' onclick='js_imprime($codproc);'>
                    <input class='botao' type='button' value='Voltar' onclick=\"history.back()\">
	 		            </td>
		            </tr>
		            <tr>
                  <td ><b>OBSERVAÇÃO:</b> </td>
                  <td colspan='3'>". ($p58_obs == "" ? "&nbsp;" : nl2br($p58_obs))."</td>
                </tr>
                </table>";

				  if($db21_usasisagua == 't') {
	          echo "<table width='100%' class='tab' cellspacing=0 cellpading=0 border=1>
				          <tr>
		                <td style='background-color:#CCCCCC' colspan='7' align='center'><b>Andamentos</b></td>
		              </tr>
				          <tr>
										<td style='background-color:#CCCCCC' align='center'><b>Data</b></td>
										<td style='background-color:#CCCCCC' align='center'><b>Hora</b></td>
										<td style='background-color:#CCCCCC' align='center'><b>Depto</b></td>
										<td style='background-color:#CCCCCC' align='center'><b>Insti</b></td>
										<td style='background-color:#CCCCCC' align='center'><b>Ocorrencia</b></td>
		                <td style='background-color:#CCCCCC' align='center'><b>Despacho</b></td>
				          </tr>";
				  } else {
				  	echo "<table width='100%' class='tab' cellspacing=0 cellpading=0 border=1>
                  <tr>
                    <td style='background-color:#CCCCCC' colspan='7' align='center'><b>Andamentos</b></td>
                  </tr>
                  <tr>
                    <td style='background-color:#CCCCCC' align='center'><b>Data</b></td>
                    <td style='background-color:#CCCCCC' align='center'><b>Hora</b></td>
                    <td style='background-color:#CCCCCC' align='center'><b>Depto</b></td>
                    <td style='background-color:#CCCCCC' align='center'><b>Insti</b></td>
                    <td style='background-color:#CCCCCC' align='center'><b>Login</b></td>
                    <td style='background-color:#CCCCCC' align='center'><b>Ocorrencia</b></td>
                    <td style='background-color:#CCCCCC' align='center'><b>Despacho</b></td>
                  </tr>";
				  }

          $result_proctransferproc = $clproctransferproc->sql_record($clproctransferproc->sql_query_file(null, null, "*", "p63_codtran", "p63_codproc = {$codproc}"));
				  if ($clproctransferproc->numrows != 0) {

				  	if($db21_usasisagua == 't') {
	            echo "<tr>
	                    <td>".db_formatar($p58_dtproc, 'd')."</td>
	                    <td>$p58_hora</td>
	                    <td>$p58_coddepto-$descrdepto</td>
	                    <td>$nomeinstabrev</td>
	                    <td>Processo Criado</td>
	                    <td>&nbsp</td>
	                  </tr>";
				  	} else {
				  		echo "<tr>
                      <td>".db_formatar($p58_dtproc, 'd')."</td>
                      <td>$p58_hora</td>
                      <td>$p58_coddepto-$descrdepto</td>
                      <td>$nomeinstabrev</td>
                      <td>$nome</td>
                      <td>Processo Criado</td>
                      <td>&nbsp</td>
                    </tr>";
				  	}

            $tramite = 0;
            $exe = $clproctransferproc->numrows - 1;

            for ($y = 0; $y < $clproctransferproc->numrows; $y ++) {
              db_fieldsmemory($result_proctransferproc, $y);

              $result_proctransfer = $clproctransfer->sql_record($clproctransfer->sql_query_deps(null, "atual.instit,instiatual.nomeinstabrev,p62_codtran,p62_dttran,p62_hora,p62_coddepto,p62_coddeptorec,atual.descrdepto as deptoatual,destino.descrdepto as deptodestino,destino.coddepto as coddeptodestino, usu_atual.nome as nome, proctransfer.p62_id_usorec as idusuariodestino, usu_destino.login as loginusuariodestino", null, "p62_codtran = $p63_codtran"));

              if ($clproctransfer->numrows != 0) {
                db_fieldsmemory($result_proctransfer, 0);

							if ($tramite == 0) {
								if($db21_usasisagua == 't') {
	                echo "<tr>
	                        <td>".db_formatar($p62_dttran, 'd')."</td>
	                        <td>$p62_hora&nbsp;</td>
	                        <td>$p62_coddepto-$deptoatual</td>
	                        <td>$nomeinstabrev</td>
	                        <td>Tramite Inicial $p62_codtran p/ Departamento: $deptodestino </td>
	                        <td>&nbsp</td>
	                      </tr>";
								} else {
									echo "<tr>
                          <td>".db_formatar($p62_dttran, 'd')."</td>
                          <td>$p62_hora&nbsp;</td>
                          <td>$p62_coddepto-$deptoatual</td>
                          <td>$nomeinstabrev</td>
                          <td>$nome  </td>
                          <td>Tramite Inicial $p62_codtran p/ Departamento: $deptodestino </td>
                          <td>&nbsp</td>
                        </tr>";
								}
								$tramite = 1;

							} else {

                $result_proctransand = $clproctransand->sql_record($clproctransand->sql_query_consandam("", "p64_codandam", null, "p64_codtran = $p62_codtran and p61_codproc = $codproc  "));

								if ($clproctransand->numrows != 0) {
                  db_fieldsmemory($result_proctransand, 0);

									$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "procandam.*", null, "p61_codandam = $p64_codandam"));

                  if ($clprocandam->numrows != 0) {
                    db_fieldsmemory($result_procandam, 0);
									}

								}

                $result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = ".@$p61_codandam));

								if ($p62_coddepto == $p62_coddeptorec && $clarqandam->numrows != 0) {

                  $arquiv = true;

								} else {
                  if($db21_usasisagua == 't') {
	                  echo "<tr>
	                          <td>".db_formatar($p62_dttran, 'd')."</td>
	                          <td>$p62_hora&nbsp</td>
	                          <td>$p62_coddepto-$deptoatual</td>
	                          <td>$nomeinstabrev</td>
	                          <td>Transferência $p62_codtran p/ o Departamento: $coddeptodestino - $deptodestino" . ((int) $idusuariodestino > 0?" - usuário especificado: $idusuariodestino - $loginusuariodestino":" (sem usuário especificado)") . "</td>
	                          <td>&nbsp</td>
	                        </tr>";
									} else {
										echo "<tr>
                            <td>".db_formatar($p62_dttran, 'd')."</td>
                            <td>$p62_hora&nbsp</td>
                            <td>$p62_coddepto-$deptoatual</td>
                            <td>$nomeinstabrev</td>
                            <td>$nome </td>
                            <td>Transferência $p62_codtran p/ o Departamento: $coddeptodestino - $deptodestino" . ((int) $idusuariodestino > 0?" - usuário especificado: $idusuariodestino - $loginusuariodestino":" (sem usuário especificado)") . "</td>
                            <td>&nbsp</td>
                          </tr>";
									}

								}

							}

							$result_proctransand = $clproctransand->sql_record($clproctransand->sql_query_consandam("", "*", null, "p64_codtran = $p62_codtran and p61_codproc = $codproc  "));

							if ($clproctransand->numrows != 0) {
								db_fieldsmemory($result_proctransand, 0);

								$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", null, "p61_codandam = $p64_codandam"));

								if ($clprocandam->numrows != 0) {
									db_fieldsmemory($result_procandam, 0);

									if($db21_usasisagua == 't') {
	                  echo "<tr>
	                          <td>".db_formatar($p61_dtandam, 'd')."</td>
	                          <td>$p61_hora&nbsp</td>
	                          <td>$p61_coddepto-$descrdepto</td>
	                          <td>$nomeinstabrev</td>";
									} else {
										echo "<tr>
                            <td>".db_formatar($p61_dtandam, 'd')."</td>
                            <td>$p61_hora&nbsp</td>
                            <td>$p61_coddepto-$descrdepto</td>
                            <td>$nomeinstabrev</td>
                            <td>$nome </td>";
									}

                  if ($arquiv == true) {

                    $result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = $p61_codandam"));

                    if ($clarqandam->numrows != 0) {
											db_fieldsmemory($result_arqandam, 0);
											$arqant = true;
											if ($p69_arquivado == 't') {
												echo "<td><b>Processo Arquivado </b></td>";
											} else {
												echo "<td><b>Desarquivamento</b></td>";
											}
                    }

									} else {
                    echo "<td>Recebeu Transferência</td>";
									}

									echo "<td>$p61_despacho&nbsp</td> </tr>";

									$result_procandamint_des = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_codandam = $p61_codandam and p78_publico <> 'f' "));

									if ($clprocandamint->numrows != 0) {

										for ($x = 0; $x < $clprocandamint->numrows; $x ++) {
                      db_fieldsmemory($result_procandamint_des, $x);
                      if ($p78_transint == 't') {
												break;
											} else {
												if($db21_usasisagua == 't') {
	                        echo "<tr>
	                                <td>".db_formatar($p78_data, 'd')."</td>
	                                <td>$p78_hora&nbsp</td>
	                                <td>$p61_coddepto-$descrdepto</td>
			                            <td>$nomeinstabrev</td>
			                            <td>Despacho Interno</td>
			                            <td>$p78_despacho </td>
	                              </tr>";
												} else {
													echo "<tr>
                                  <td>".db_formatar($p78_data, 'd')."</td>
                                  <td>$p78_hora&nbsp</td>
                                  <td>$p61_coddepto-$descrdepto</td>
                                  <td>$nomeinstabrev</td>
                                  <td>$nome</td>
                                  <td>Despacho Interno</td>
                                  <td>$p78_despacho </td>
                                </tr>";
												}
												$cod_procandamint = $p78_sequencial;
											}
										}
									}

									$result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));

									if ($clproctransferintand->numrows != 0) {

										for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {
											db_fieldsmemory($result_proctransferintand, $yy);

											$result_proctransferint = $clproctransferint->sql_record($clproctransferint->sql_query_andusu(null, "p88_codigo,p88_data,p88_hora,p88_despacho,p88_publico,atual.nome as usuatual,destino.nome as usudestino", null, "p88_codigo=$p87_codtransferint  "));

											if ($clproctransferint->numrows != 0) {
												db_fieldsmemory($result_proctransferint, 0);

												if($db21_usasisagua == 't') {

													echo "<tr>
	                                <td>".db_formatar($p88_data, 'd')."</td>
	                                <td>$p88_hora&nbsp</td>
	                                <td>$p61_coddepto-$descrdepto</td>
	                                <td>$nomeinstabrev</td>
	                                <td>Transferência Interna para $usudestino</td>";

												} else {

													echo "<tr>
                                  <td>".db_formatar($p88_data, 'd')."</td>
                                  <td>$p88_hora&nbsp</td>
                                  <td>$p61_coddepto-$descrdepto</td>
                                  <td>$nomeinstabrev</td>
                                  <td>$usuatual</td>
                                  <td>Transferência Interna para $usudestino</td>";
												}

                        if ( $p88_publico != "f" ) {
                          echo "<td>$p88_despacho</td>";
												} else {
													echo "<td>&nbsp;</td>";
												}

												echo "</tr>";

												$result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));

                        if ($clprocandamintand->numrows != 0) {
													db_fieldsmemory($result_procandamintand, 0);

													$result_procandamint_trans = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam and p78_publico <> 'f'"));

													if ($clprocandamint->numrows != 0) {

                            for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {
                              db_fieldsmemory($result_procandamint_trans, $xx);

                              if ($xx > 0) {
                                if ($cod_usu != $p78_usuario) {
                                  break;
                                }
															}

                              if ($p78_transint == 't') {

                              	if($db21_usasisagua == 't') {
	                                echo "<tr>
	                                        <td>".db_formatar($p78_data, 'd')."</td>
	                                        <td>$p78_hora&nbsp</td>
			                                    <td> $p61_coddepto-$descrdepto</td>
			                                    <td>$nomeinstabrev</td>
			                                    <td>Recebeu Transferência Interna</td>
	                                        <td></td>
	                                      </tr>";
                              	} else {
                              		echo "<tr>
                                          <td>".db_formatar($p78_data, 'd')."</td>
                                          <td>$p78_hora&nbsp</td>
                                          <td> $p61_coddepto-$descrdepto</td>
                                          <td>$nomeinstabrev</td>
                                          <td>$nome</td>
                                          <td>Recebeu Transferência Interna</td>
                                          <td></td>
                                        </tr>";
                              	}

															} else {
																if($db21_usasisagua == 't') {
	                                echo "<tr>
	                                        <td>".db_formatar($p78_data, 'd')."</td>
			                                    <td>$p78_hora&nbsp</td>
			                                    <td>$p61_coddepto-$descrdepto</td>
			                                    <td>$nomeinstabrev</td>
			                                    <td>Despacho Interno</td>
			                                    <td>$p78_despacho </td>
	                                      </tr>";
																} else {
																	echo "<tr>
                                          <td>".db_formatar($p78_data, 'd')."</td>
                                          <td>$p78_hora&nbsp</td>
                                          <td>$p61_coddepto-$descrdepto</td>
                                          <td>$nomeinstabrev</td>
                                          <td>$nome</td>
                                          <td>Despacho Interno</td>
                                          <td>$p78_despacho </td>
                                        </tr>";
																}
															}

															$cod_usu = $p78_usuario;
															$cod_procandamint = $p78_sequencial;

														}
													}
												}

											}

										} // fim do for

									}

								}
							}
						}/* else {
						db_msgbox("Processo não encontrado para as informações!");
						echo"<script>location.href='digitaconsultaprocesso.php?outro=123456'</script>";
						}*/

						$arquiv = false;
						if (isset ($p90_andatual) && $p90_andatual == "t") {
							if ($y == $clproctransferproc->numrows - 1) {
								if($db21_usasisagua == 't') {
									echo "<tr>
                          <td>".db_formatar($p61_dtandam, 'd')."</td>
			                    <td>$p61_hora&nbsp</td>
			                    <td>$p61_coddepto-$descrdepto</td>
			                    <td>$nomeinstabrev</td>
			                    <td><b>Andamento atual</b></td>
			                    <td>$p58_despacho&nbsp</td>
                        </tr>";
								} else {
									echo "<tr>
                          <td>".db_formatar($p61_dtandam, 'd')."</td>
		                      <td>$p61_hora&nbsp</td>
		                      <td>$p61_coddepto-$descrdepto</td>
		                      <td>$nomeinstabrev</td>
		                      <td>$nome</td>
		                      <td><b>Andamento atual</b></td>
		                      <td>$p58_despacho&nbsp</td>
                        </tr>";
								}
							}
						}

					}

				} else {

					$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", "p61_codandam", "p61_codproc = $codproc"));
					if ($clprocandam->numrows != 0) {

						for ($xy = 0; $xy < $clprocandam->numrows; $xy ++) {
							db_fieldsmemory($result_procandam, $xy);
							if($db21_usasisagua == 't') {
								echo "<tr>
			                  <td>".db_formatar($p61_dtandam, 'd')."</td>
			                  <td>$p61_hora&nbspaqui</td>
			                  <td>$p61_coddepto-$descrdepto</td>
			                  <td>$nomeinstabrev</td>
			                  <td>Recebeu Processo &nbsp</td>
			                  <td>$p61_despacho</td>
	                   </tr>";
							} else {
								echo "<tr>
		                    <td>".db_formatar($p61_dtandam, 'd')."</td>
		                    <td>$p61_hora&nbspaqui</td>
		                    <td>$p61_coddepto-$descrdepto</td>
		                    <td>$nomeinstabrev</td>
		                    <td>$nome</td>
		                    <td>Recebeu Processo &nbsp</td>
		                    <td>$p61_despacho</td>
                      </tr>";
							}

							$result_procandamint_des = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_codandam = $p61_codandam and p78_publico <> 'f'"));
							if ($clprocandamint->numrows != 0) {

								for ($x = 0; $x < $clprocandamint->numrows; $x ++) {
									db_fieldsmemory($result_procandamint_des, $x);

									if ($p78_transint == 't') {
										break;
									} else {
										if($db21_usasisagua == 't') {
											echo "<tr>
			                        <td>".db_formatar($p78_data, 'd')."</td>
			                        <td>$p78_hora&nbsp</td>
			                        <td>$p61_coddepto-$descrdepto</td>
			                        <td>$nomeinstabrev</td>
			                        <td>Despacho Interno</td>
			                        <td>$p78_despacho </td>
			                      </tr>";
										} else {
											echo "<tr>
		                          <td>".db_formatar($p78_data, 'd')."</td>
		                          <td>$p78_hora&nbsp</td>
		                          <td>$p61_coddepto-$descrdepto</td>
		                          <td>$nomeinstabrev</td>
		                          <td>$nome</td>
		                          <td>Despacho Interno</td>
		                          <td>$p78_despacho </td>
		                        </tr>";
										}
										$cod_procandamint = $p78_sequencial;
									}

								}
							}

							$result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));
							if ($clproctransferintand->numrows != 0) {

								for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {
									db_fieldsmemory($result_proctransferintand, $yy);

									$result_proctransferint = $clproctransferint->sql_record($clproctransferint->sql_query_andusu(null, "p88_codigo,p88_data,p88_hora,p88_despacho,p88_publico,atual.nome as usuatual,destino.nome as usudestino", null, "p88_codigo=$p87_codtransferint"));
									if ($clproctransferint->numrows != 0) {
										db_fieldsmemory($result_proctransferint, 0);

										if($db21_usasisagua == 't') {
											echo "<tr>
                              <td>".db_formatar($p88_data, 'd')."</td>
                              <td>$p88_hora&nbsp</td>
                              <td>$p61_coddepto-$descrdepto</td>
                              <td>$nomeinstabrev</td>
                              <td>Transferência Interna para $usudestino</td>";

										} else {
											echo "<tr>
                              <td>".db_formatar($p88_data, 'd')."</td>
                              <td>$p88_hora&nbsp</td>
                              <td>$p61_coddepto-$descrdepto</td>
		                          <td>$nomeinstabrev</td>
		                          <td>$usuatual</td>
                              <td>Transferência Interna para $usudestino</td>";
										}
										if ( $p88_publico != "f" ) {
											echo "<td>$p88_despacho</td>";
										} else {
											echo "<td>&nbsp;</td>";
										}

										echo "</tr>";


										$result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));
										if ($clprocandamintand->numrows != 0) {
											db_fieldsmemory($result_procandamintand, 0);

											$result_procandamint_trans = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam and p78_publico <> 'f'"));
											if ($clprocandamint->numrows != 0) {

												for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {
													db_fieldsmemory($result_procandamint_trans, $xx);

													if ($xx > 0) {
														if ($cod_usu != $p78_usuario) {
															break;
														}
													}

													if ($p78_transint == 't') {

                            if($db21_usasisagua == 't') {
															echo "<tr>
                                      <td>".db_formatar($p78_data, 'd')."</td>
                                      <td>$p78_hora&nbsp</td>
			                                <td>$p61_coddepto-$descrdepto</td>
			                                <td>$nomeinstabrev</td>
			                                <td>Recebeu Transferência Interna</td>
			                                <td>$p78_despacho</td>
                                    </tr>";
														} else {
															echo "<tr>
                                      <td>".db_formatar($p78_data, 'd')."</td>
		                                  <td>$p78_hora&nbsp</td>
		                                  <td>$p61_coddepto-$descrdepto</td>
		                                  <td>$nomeinstabrev</td>
		                                  <td>$nome</td>
		                                  <td>Recebeu Transferência Interna</td>
		                                  <td>$p78_despacho</td>
                                    </tr>";
														}

													} else {
														if($db21_usasisagua == 't') {

															echo "<tr>
                                      <td>".db_formatar($p78_data, 'd')."</td>
			                                <td>$p78_hora&nbsp</td>
			                                <td>$p61_coddepto-$descrdepto</td>
			                                <td>$nomeinstabrev</td>
			                                <td>Despacho Interno</td>
			                                <td>$p78_despacho </td>
                                    </tr>";

														} else {

															echo "<tr>
                                      <td>".db_formatar($p78_data, 'd')."</td>
		                                  <td>$p78_hora&nbsp</td>
		                                  <td>$p61_coddepto-$descrdepto</td>
		                                  <td>$nomeinstabrev</td>
		                                  <td>$nome</td>
		                                  <td>Despacho Interno</td>
		                                  <td>$p78_despacho </td>
                                    </tr>";

														}

													}

													$cod_usu = $p78_usuario;
													$cod_procandamint = $p78_sequencial;

												}

											}

										}

									}

								}

							}

						}

					}

				}

			} else {

				if (isset($cgc) && $cgc != "" || isset($cpf) && $cpf != "" ) {
					db_msgbox("AVISO:\\nNenhum registro encontrado para o número de processo com o CNPJ/CPF informado!");
					echo"<script>location.href='digitaconsultaprocesso.php?outro=123456'</script>";
				} else {
					db_msgbox("AVISO:\\nNenhum registro encontrado para o número de processo informado!");
					echo"<script>location.href='digitaconsultaprocesso.php?outro=123456'</script>";
				}
			}

			if ($arqant == false) {

				$result_arqproc = $clarqproc->sql_record($clarqproc->sql_query_file(null, null, "*", null, "p68_codproc = $codproc"));

        if ($clarqproc->numrows != 0) {
          db_fieldsmemory($result_arqproc, 0);

          $result_procandam_arq = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", "p61_codandam desc limit 1", "p61_codproc = $codproc"));

					if ($clprocandam->numrows != 0) {
						db_fieldsmemory($result_procandam_arq, 0);

						if($db21_usasisagua == 't') {
							echo "<tr>
			                <td>".db_formatar($p61_dtandam, 'd')."</td>
			                <td>$p61_hora&nbsp</td>
			                <td>$p61_coddepto-$descrdepto</td>
			                <td>$nomeinstabrev</td>
			                <td><b>Processo Arquivado</b></td>
			                <td>$p61_despacho / Cod. Arquivamento: $p68_codarquiv &nbsp</td>
	                  </tr>";
						} else {
							echo "<tr>
                      <td>".db_formatar($p61_dtandam, 'd')."</td>
                      <td>$p61_hora&nbsp</td>
                      <td>$p61_coddepto-$descrdepto</td>
                      <td>$nomeinstabrev</td>
                      <td>$nome</td>
                      <td><b>Processo Arquivado</b></td>
                      <td>$p61_despacho / Cod. Arquivamento: $p68_codarquiv &nbsp</td>
                    </tr>";
						}

            if (isset ($p90_andatual) && $p90_andatual == "t") {
            	if($db21_usasisagua == 't') {
		            echo "<tr>
		                    <td>".db_formatar($p61_dtandam, 'd')."</td>
			                  <td>$p61_hora&nbsp</td>
			                  <td>$p61_coddepto-$descrdepto</td>
			                  <td>$nomeinstabrev</td>
			                  <td><b>Andamento atual</b></td>
			                  <td>$p58_despacho&nbsp</td>
		                  </tr>";
            	} else {

            		echo "<tr>
                        <td>".db_formatar($p61_dtandam, 'd')."</td>
                        <td>$p61_hora&nbsp</td>
                        <td>$p61_coddepto-$descrdepto</td>
                        <td>$nomeinstabrev</td>
                        <td>$nome</td>
                        <td><b>Andamento atual</b></td>
                        <td>$p58_despacho&nbsp</td>
                      </tr>";

            	}

            }

					}

				}

			}

			echo "<table>";
		}
		?>
		</td>
	</tr>
</table>
</center>
</form>
<script>
  document.getElementById('int_perc1').style.visibility='hidden';
</script>
</body>
</html>
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tabrec_classe.php");
require_once("classes/db_proced_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_arreinscr_classe.php");
require_once("classes/db_arreold_classe.php");
require_once("classes/db_divida_classe.php");
require_once("classes/db_divold_classe.php");
require_once("classes/db_divmatric_classe.php");
require_once("classes/db_divimporta_classe.php");
require_once("classes/db_divimportareg_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("classes/db_arrecadcompos_classe.php");
require_once("classes/db_arreckey_classe.php");
require_once("classes/db_dividaprotprocesso_classe.php");

db_postmemory($_POST);
db_postmemory($_GET);

$oPost            = db_utils::postMemory($_POST);
$oGet             = db_utils::postMemory($_GET);
$oProcesso        = db_getsession("oDadosProcesso");

$lProcessoSistema = (int)$oProcesso->lProcessoSistema;
$iProcesso        = $oProcesso->iProcesso;
$sTitular         = $oProcesso->sTitular;
$dDataProcesso    = $oProcesso->dDataProcesso;

$cltabrec               = new cl_tabrec;
$clarrecad              = new cl_arrecad;
$clarrematric           = new cl_arrematric;
$clarreinscr            = new cl_arreinscr;
$clarreold              = new cl_arreold;
$clproced               = new cl_proced;
$cldivida               = new cl_divida;
$cldivold               = new cl_divold;
$cldivmatric            = new cl_divmatric;
$cldivimporta           = new cl_divimporta;
$cldivimportareg        = new cl_divimportareg;
$clarrecadcompos        = new cl_arrecadcompos;
$clarreckey             = new cl_arreckey;
$oDaoDividaprotprocesso = new cl_dividaprotprocesso;

$teste                  = false;
$where                  = "";
$where2                 = "";
$subselect              = "";
$and                    = "";
$xnumpre                = "";
$xnumpre2               = "";
$vir                    = "";
$order_k00_numpar       = "";
$hoje                   = $datavenc;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<table width="100%" height="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" width="100%" align="center" valign="top">
      <center>
      <form name="form1" method="post" action="">
      <table height="100%" width="100%"  border="0" cellspacing="5" cellpadding="0">
	    </td>
	    <td align="center">
	      <?


if (isset ($procreg) && $procreg != "") {
	db_criatermometro('termometro', 'Concluido...', 'blue', 1);
}
?>
	    </td>
<?


$wherereceita = "";

if (isset ($chave_origem) && trim($chave_origem) != "" && isset ($chave_destino) && trim($chave_destino) != "") {
	if (isset ($procreg) && $procreg == 't') {

	    $teste      = substr($codreceita, -1);
	    $codreceita = substr($codreceita, 0, strlen($codreceita) - 1);
	    if ($teste != ',') {
		    $codreceita .= $teste;
	    }
	    $teste1     = substr($codreceita, 0, 1);
	    $codreceita = substr($codreceita, 1, strlen($codreceita));
	    if ($teste1 != ',') {
		    $codreceita = $teste1.$codreceita;
	    }
	    $wherereceita = " and k00_receit in ($codreceita) and k00_valor <> 0 ";
	}
	if ($tipoparc == "a") {
		$venc = "";
	} else {
		$venc = " and k00_dtvenc <= '".$hoje."'";
	}

	if ($tipoparc == "t") {

		$subsql = "select distinct k00_numpre
		                      from (select arrecad.k00_numpre,
				                   max(k00_dtvenc)
				              from arrecad
											     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
													                      and arreinstit.k00_instit = ".db_getsession('DB_instit')."
				            where k00_tipo = $chave_origem
				                             $wherereceita
				            group by arrecad.k00_numpre) as xxx
		            		where max <= '$hoje'";
	} else {

		$subsql = " select distinct arrecad.k00_numpre
		                       from arrecad
											     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
													                      and arreinstit.k00_instit = ".db_getsession('DB_instit')."
		              	 where k00_tipo = $chave_origem
				                   $venc
		                       $wherereceita ";
	}

	$sql0 = " select tabrec.k02_codigo,
	                  tabrec.k02_drecei,
	             		  contrec
	             from (select k00_receit, count(*) as contrec
		                 from ($subsql) as x
	                		inner join arrecad on arrecad.k00_numpre = x.k00_numpre
	                   	where 1=1 $venc $wherereceita
	                 		group by k00_receit) as y
	            inner join tabrec on k02_codigo = y.k00_receit ";

	$result0  = $cltabrec->sql_record($sql0);
	$numrows0 = $cltabrec->numrows;
	if ($numrows0 == 0) {

		echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
		echo "<script>
		            parent.document.form1.gerar.disabled=true;
                alert('Nenhum tipo de débito encontrado com este código!');
		          </script>";
    echo "<script>top.corpo.db_iframe.hide();</script>";
		echo "<script>top.corpo.location.href='div4_importadivida001.php'</script>";

	}
	$sql1     = " select v03_codigo, v03_descr ";
	$sql1    .= "   from proced ";
	$sql1    .= "       inner join tabrec on k02_codigo = v03_receit ";
	$sql1    .= "    and v03_instit = ".db_getsession('DB_instit');
	$result1  = $clproced->sql_record($sql1);
	$numrows1 = $clproced->numrows;
	if (!isset ($procreg)) {

		$vir            = "";
		$cod_k02_codigo = "";
		$vir1           = "";
		$cod_v03_codigo = "";
		echo "<div id='filtro' style='visibility:visible'>";

		if (isset ($uni) && $uni == "p") {

		  echo " <tr>
		                <td>
	                	 <strong> Data de vencimento : </strong>
		                </td>
		                <td>
		  	  	<select name='dtop' onchange='js_controladata(this.value);'>
			             <option value=1> Menor vencimento das parcelas abertas      </option>
		              	     <option value=2> Maior vencimento das parcelas abertas      </option>
				     <option value=3> Escolher data para vencimento </option>
	            	  	</select>
				</td>
				</tr>";
			echo "<tr>
      	      <td colspan=3>";
			echo "<div id='divdataoper' style='visibility:hidden'>
			        <table border=0>
	      			<tr>
	             	<td>
				<strong> Data de vencimento das dividas : </strong>
			        </td>
        	    		<td>&nbsp &nbsp &nbsp ";
					db_inputdata("dtvencuni", "", "", "", true, 'text', 1);
			echo "  </td>
			         </tr>
			     </table>
			     </div>
                             </td>
		             </tr>";
		}

		echo "<tr>
		  	    <td nowrap align='center' valign='top'><strong> Receita </strong>     </td>
	          <td nowrap align='center' valign='top'><strong> Descrição </strong>   </td>
	          <td nowrap align='center' valign='top'><strong> Procedência </strong> </td>
			      <td nowrap align='center' valign='top'><strong> Regist </strong>      </td>
		      </tr> ";
		$totcontrec = 0;
		for ($i = 0; $i < $numrows0; $i ++) {

		  db_fieldsmemory($result0, $i);
			$cod_k02_codigo .= $vir.$k02_codigo;
			$vir             = ",";
			echo "
			    <tr>
			      <td nowrap align='left' valign='top'>";
				db_input("$k02_codigo", "8", "", true, "text", 3, "", "k02_codigo");
			echo "</td>
		                <td nowrap> ";
				db_input("$k02_drecei", 40, "", true, "text", 3, "", "k02_drecei");
			echo " </td> ";
			echo " <td>
			           <select name=\"v03_descr\" onchange=\"js_troca();\" id=\"v03_descr\">
	               <option value=\"0\" >Escolha uma procedência</option>
			         ";
			for ($ii = 0; $ii < $numrows1; $ii ++) {

			  db_fieldsmemory($result1, $ii);
				echo " <option value=\"$v03_codigo\" >$v03_codigo - $v03_descr</option>";
				if ($ii == 0) {

				  $cod_v03_codigo .= $vir1.$v03_codigo;
					$vir1 = ",";
				}
			}
			echo " </select>
			      </td>
			      <td nowrap align='left' valign='top'>";
			db_input("$contrec", "10", "", true, "text", 3, "", "contrec");
			$totcontrec += $contrec;
		}
		echo "<tr>
		          <td nowrap align='center' valign='top'><strong>  </strong>                    </td>
		          <td nowrap align='center' valign='top'><strong>  </strong>                    </td>
		          <td nowrap align='right'  valign='top'><strong>Total de registros : </strong> </td>
		          <td nowrap align='left'   valign='top'><strong>$totcontrec </strong>          </td>
		        </tr> ";
		echo "</div>";
	}

	/*  para importacao de charqueadas => se marcau para unificar
	    os debitos em um unico registro na divida esse input guarda a data de
	    vencimento q sera gravado na divida */
	db_input("uni", 40, "0", true, "hidden", 3);
	db_input("chave_origem", 40, "0", true, "hidden", 3);
	db_input("chave_destino", 40, "0", true, "hidden", 3);
	db_input("cod_k02_codigo", 40, "0", true, "hidden", 3);
	db_input("cod_v03_codigo", 40, "0", true, "hidden", 3);
	db_input("procreg", 40, "0", true, "hidden", 3);
	db_input("codreceita", 40, "0", true, "hidden", 3);
	db_input("tipodata", 40, "0", true, "hidden", 3);

	echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
}
?>
      </table>
      </form>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript">
function js_controladata(val){

   if(val != 3){
      document.getElementById('divdataoper').style.visibility='hidden';
   }else{
      document.getElementById('divdataoper').style.visibility='visible';
   }
}
function js_troca(){

  vir       = '';
  pass      = 'f';
  codigo    = '';
  codreceit = '';
  cont      = 0;
  for (i = 0; i < document.form1.length; i++) {

    if (document.form1.elements[i].type == "select-one") {

      if (document.form1.elements[i].value !=0 && document.form1.elements[i].name != 'dtop') {

        codigo    += vir+document.form1.elements[i].value;
        codreceit += vir+document.form1.elements[i-2].value;
        vir        =',';
        pass       = 't';
      } else {
	      cont++;
      }
    }
  }
  if (pass=='t') {
    parent.document.form1.gerar.disabled = false;
  } else {
    parent.document.form1.gerar.disabled = true;
  }
  document.form1.cod_v03_codigo.value = codigo;
  document.form1.codreceita.value     = codreceit;
}
</script>
<?
if (isset ($procreg) && $procreg == 't') {

  if (isset ($cod_k02_codigo) && trim($cod_k02_codigo) != "" && isset ($cod_v03_codigo) && trim($cod_v03_codigo) != "") {

    $teste = true;
		if (isset ($uni) && $uni == "p") {

		  $ek00_numpar = "";
			if (isset ($dtop) && $dtop == 1) {
				$maxdtvenc = 'min(k00_dtvenc) as k00_dtvenc';
			}	else if (isset ($dtop) && $dtop == 2) {
				$maxdtvenc = 'max(k00_dtvenc) as k00_dtvenc';
			}	else if (isset ($dtop) && $dtop == 3) {

			  $dtvencuni = $dtvencuni;
				$maxdtvenc = "'"."$dtvencuni_ano-$dtvencuni_mes-$dtvencuni_dia"."' as k00_dtvenc ";
			}
			$min_numdig = " min(k00_numdig) as k00_numdig ";
			$min_hist   = " min(k00_hist) as k00_hist ";
		} else {

      $order_k00_numpar = " ,k00_numpar ";
			$ek00_numpar      = " k00_numpar, ";
			$maxdtvenc        = " k00_dtvenc ";
			$min_numdig       = " k00_numdig ";
			$min_hist         = " k00_hist ";
		}
		/****************************************************************************************/
		$sql_pesq = "  select x.k00_numpre,
					  $ek00_numpar
					  k00_receit,
					  sum(k00_valor) as val,
					  k00_numcgm,
					  max(k00_dtoper) as k00_dtoper,
					  $maxdtvenc,
					  k00_numtot,
					  $min_numdig,
					  k00_tipo

			      from ($subsql) as x
				      inner join arrecad    on arrecad.k00_numpre = x.k00_numpre
  			      inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
						                       and arreinstit.k00_instit = ".db_getsession('DB_instit')."
			      where 1=1
			            $venc
				    $wherereceita
			       group by x.k00_numpre,
					$ek00_numpar
					k00_receit,
					k00_numcgm,	";
		if (isset($uni) && $uni == "p") {

		} else {

			$sql_pesq .= "$maxdtvenc,";
			$sql_pesq .= "$min_numdig,";
		}
		$sql_pesq .= "		k00_numtot,
                 			k00_tipo
					      order by x.k00_numpre,
					               k00_receit
                         $order_k00_numpar  ";
		$result_pesq_divida = db_query($sql_pesq);
		$numrows = pg_numrows($result_pesq_divida);

		if (isset ($numrows) && $numrows == 0) {
			db_msgbox("Nenhum registro para o filtro selecionado !");
		}
		$codigo_k02 = split(",", $codreceita);
		$codigo_v03 = split(",", $cod_v03_codigo);
		$sqlerro    = false;
		$dataini    = date("Y-m-d", db_getsession('DB_datausu'));
		$horaini    = db_hora();

		db_inicio_transacao();
		$numpre_par_rec = "";

// vou tirar a inclusão daqui e por depois do for

		$cldivimporta->v02_usuario = db_getsession('DB_id_usuario');
		$cldivimporta->v02_instit  = db_getsession('DB_instit');
		$cldivimporta->v02_data    = $dataini;
		$cldivimporta->v02_hora    = $horaini;
		$cldivimporta->v02_tipo    = 2;
		$cldivimporta->v02_datafim = $dataini;
		$cldivimporta->v02_horafim = db_hora();
		$cldivimporta->incluir(null);
		if ((int)$cldivimporta->erro_status == 0) {

			db_msgbox($cldivimporta->erro_msg);
			$sqlerro = true;
		}
		$iDivImporta = $cldivimporta->v02_divimporta ;

		echo "<script>document.getElementById('filtro').style.visibility='hidden';</script>";

		/*========================================   F O R   Q   I M P O R T A   O S   R E G I S T R O S   ====================================================================*/
		if (isset ($uni) && $uni == "p") {
			$dtvencuni = "$dtvencuni_ano-$dtvencuni_mes-$dtvencuni_dia";
		}

		for ($i = 0; $i < $numrows; $i ++) {

			db_fieldsmemory($result_pesq_divida, $i, true);
			db_atutermometro($i, $numrows, 'termometro');

			for ($ii = 0; $ii < sizeof($codigo_v03); $ii ++) {


			  /**
			   * Não será mais permitida a importação de valores zerados ou negativos
			   */
			  if ($val <= 0) {
          continue;
			  }
				$cod_k02_codigo = $codigo_k02[$ii];
				$cod_v03_codigo = $codigo_v03[$ii];

				if ($k00_receit == $cod_k02_codigo && $sqlerro == false) {

					if ($numpre_par_rec <> str_pad($k00_numpre, 10, "0", STR_PAD_LEFT).str_pad($k00_receit, 5, "0", STR_PAD_LEFT)) {

						$whe     = "";
						$whe_div = "";
						if (isset ($uni) && $uni == "p") {

						  $whe     = "k00_numpre = $k00_numpre";
							$whe_div = "divida.v01_numpre = $k00_numpre";
						}else{

							$whe     = "k00_numpre = $k00_numpre and k00_numpar = $k00_numpar";
							$whe_div = "divida.v01_numpre = $k00_numpre and divida.v01_numpar = $k00_numpar";
						}

						$sSqlTipoDebito  = "select distinct k03_tipo ";
						$sSqlTipoDebito .= "  from arrecad ";
						$sSqlTipoDebito .= "       inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
						$sSqlTipoDebito .= " where {$whe}";
						$result_tipo = db_query($sSqlTipoDebito);
						db_fieldsmemory($result_tipo, 0);
						$iCadTipo = $k03_tipo;

						$sqljadivida = "select v03_receit as receita_nova, v01_coddiv
						                  from divida
                         					 inner join proced on v01_proced = v03_codigo
																	                  and v01_instit = ".db_getsession('DB_instit')."
																	                  and v03_instit = ".db_getsession('DB_instit')."
                 						where {$whe_div}";
						$resultjadivida = db_query($sqljadivida);

						$jaexiste        = false;
						$numrowsjadivida = pg_numrows($resultjadivida);

							// Trata tipo de Debito 20 - Saneamento Basico
							if ($numrowsjadivida > 0 && $k03_tipo = 20) {

								$cldivmatric->excluir(null, " divmatric.v01_coddiv in (select v01_coddiv from divida where {$whe_div})");
    						$sqldeletejadivida  = " delete from divida	where {$whe_div} ";
    						$sqldeletejadivida .= "    and divida.v01_instit = ".db_getsession('DB_instit') ;
								db_query($sqljadivida);
							}
						$nextval_numpre = db_query("select nextval('numpref_k03_numpre_seq') as numpre_novo");
						db_fieldsmemory($nextval_numpre, 0);

						$numpre_par_rec    = str_pad($k00_numpre, 10, "0", STR_PAD_LEFT).str_pad($k00_receit, 5, "0", STR_PAD_LEFT);
						$result_arrematric = $clarrematric->sql_record($clarrematric->sql_query_file($k00_numpre, 0, "k00_matric"));

						if ($clarrematric->numrows > 0) {

							db_fieldsmemory($result_arrematric, 0);
							$clarrematric->k00_numpre = $numpre_novo;
							$clarrematric->k00_matric = $k00_matric;
							$clarrematric->k00_perc   = 100;

							$clarrematric->incluir($numpre_novo, $k00_matric);

							if ((int)$clarrematric->erro_status == '0') {

								$sqlerro = true;
								break;
							}
						}

						$result_arreinscr = $clarreinscr->sql_record($clarreinscr->sql_query_file($k00_numpre, 0, "k00_inscr"));
						if ($clarreinscr->numrows > 0) {

							db_fieldsmemory($result_arreinscr, 0);
							$clarreinscr->k00_numpre = $numpre_novo;
							$clarreinscr->k00_inscr  = $k00_inscr;
							$clarreinscr->k00_perc   = 100;
							$clarreinscr->incluir($numpre_novo, $k00_inscr);

							if ((int)$clarreinscr->erro_status == '0') {

								$sqlerro = true;
								break;
							}
						}
					}

					/**
					 * inclui na divida
           */
          $v01_obs                  = $cldivida->resumo_importacao($k00_numpre, $iCadTipo);
          $exerc_div                = $cldivida->getExercicioDivida($k00_numpre, $iCadTipo, substr($k00_dtoper, 6, 4));
          $cldivida->v01_exerc      = $exerc_div;
					$cldivida->v01_instit     = db_getsession('DB_instit');
					$cldivida->v01_numcgm     = $k00_numcgm;
					$cldivida->v01_dtinsc     = date("Y-m-d",db_getsession('DB_datausu') );
					$cldivida->v01_dtinclusao = date('Y-m-d',db_getsession('DB_datausu'));
					$cldivida->v01_numpre     = $numpre_novo;

          $dt_venc = explode("/", $k00_dtvenc);

          if ( is_array($dt_venc) && count($dt_venc) > 1) {
					  $dt_venc_data = $dt_venc[2]."-".$dt_venc[1]."-".$dt_venc[0];
          }else{
            $dt_venc_data = $k00_dtvenc;
          }

					$dt_oper = split("/", $k00_dtoper);
					$dt_oper_data =  $dt_oper[2]."-".$dt_oper[1]."-".$dt_oper[0];

					if (isset ($uni) && $uni == 'p') {

						$cldivida->v01_numtot = 1;
						$cldivida->v01_numpar = 1;
						$cldivida->v01_dtvenc = $dt_venc_data;
						$cldivida->v01_dtoper = $dt_oper_data;
					} else {

						$cldivida->v01_numtot = $k00_numtot;
						$cldivida->v01_numpar = $k00_numpar;
						$cldivida->v01_dtvenc = $dt_venc_data;
						$cldivida->v01_dtoper = $dt_oper_data;
					}

					$k00_numdig           = 1;
					$cldivida->v01_numdig = $k00_numdig;
					$cldivida->v01_vlrhis = $val;
					$cldivida->v01_proced = $cod_v03_codigo;
					$cldivida->v01_obs    = pg_escape_string($v01_obs);
					$cldivida->v01_livro  = "";
					$cldivida->v01_folha  = "";
					$cldivida->v01_valor  = $val;
					$sqlcoddiv            = " select nextval('divida_v01_coddiv_seq') as v01_coddiv ";
					$resultcoddiv         = db_query($sqlcoddiv) or die($sqlcoddiv);
					db_fieldsmemory($resultcoddiv, 0);

					/*
					 * verificamos se o processo selecionado for externo
					 */
					if ($lProcessoSistema == 0) {

					  $cldivida->v01_processo   = $iProcesso;
					  $cldivida->v01_titular    = $sTitular;
					  $cldivida->v01_dtprocesso = $dDataProcesso;
					}

					$cldivida->incluir($v01_coddiv);
					$erro_msg = $cldivida->erro_msg."--- Inclusao Divida";
					if ((int)$cldivida->erro_status == 0) {

						$erro_msg = $cldivida->erro_msg."--- Inclusao Divida";
						db_msgbox($erro_msg);
						$sqlerro = true;
						break;
					}

					/**
					 * se o processo for interno
					 * incluimos na dividaprotprocesso
					 */

					if ($lProcessoSistema == 1 && $iProcesso != null) {

					  $oDaoDividaProtprocesso = db_utils::getDao('dividaprotprocesso');
					  $oDaoDividaProtprocesso->v88_divida       = $cldivida->v01_coddiv;
					  $oDaoDividaProtprocesso->v88_protprocesso = $iProcesso;
					  $oDaoDividaProtprocesso->incluir(null);
					  if ((int)$oDaoDividaProtprocesso->erro_status == 0) {

					    $erro_msg = $oDaoDividaProtprocesso->erro_msg."--- Inclusao DividaProtprocesso";
					    db_msgbox($erro_msg);
					    $sqlerro = true;
					  }

					}

					if (isset($v01_coddiv) && $v01_coddiv != null) {

  					$cldivimportareg->v04_divimporta = $cldivimporta->v02_divimporta;
  					$cldivimportareg->v04_coddiv     = $v01_coddiv;
  					$cldivimportareg->incluir();
  					if ((int)$cldivimportareg->erro_status == 0) {

  						$sqlerro = true;
  						$erro_msg = $cldivimportareg->erro_msg;
  						break;
  					}

				  }

					/* inclui no arrecad */
					$result_pes_proced = $clproced->sql_record($clproced->sql_query_file(null, "v03_receit,k00_hist", null, " v03_codigo=$cod_v03_codigo"));

					db_fieldsmemory($result_pes_proced, 0);
					$v03_hist = $k00_hist;
					$clarrecad->k00_numpre = $numpre_novo;

					if (isset ($uni) && $uni == 'p') {

						$clarrecad->k00_numtot = 1;
						$clarrecad->k00_numpar = 1;
						$clarrecad->k00_dtvenc = $dt_venc_data;
						$clarrecad->k00_dtoper = $dt_oper_data;
					} else {

						$clarrecad->k00_numpar = $k00_numpar;
						$clarrecad->k00_dtvenc = $dt_venc_data;
						$clarrecad->k00_dtoper = $dt_oper_data;
						$clarrecad->k00_numtot = $k00_numtot;
					}

					$clarrecad->k00_numcgm = $k00_numcgm;
					$clarrecad->k00_receit = $v03_receit;
					$clarrecad->k00_hist   = $v03_hist;
					$clarrecad->k00_valor  = $val;
					$clarrecad->k00_numdig = $k00_numdig;
					$clarrecad->k00_tipo   = $chave_destino;
					$clarrecad->k00_tipojm = "0";
					$clarrecad->incluir();

					if ((int)$clarrecad->erro_status == 0) {

					  $sqlerro  = true;
						$erro_msg = $clarrecad->erro_msg."--- Inclusao Arrecad";
						break;
					}

					/*inclui divold*/

          /**
           * Caso o usuario tenha marcado para unificar debitos por numpre e receita
           */
					if (isset ($uni) && $uni == 'p') {

						$sqlBk = " select * from arrecad where k00_numpre = $k00_numpre and k00_receit = $k00_receit";
						$rsBk  = db_query($sqlBk) or die($sqlBk);
						$intBk = pg_numrows($rsBk);

						if ($intBk == 0) {

							db_msgbox ("k00_numpre: $k00_numpre receita: $k00_receit nao encontrado no arrecad");
							$sqlerro = true;
							exit;
						}

            $nTotalCorrecaoReceita  = 0;
            $nTotalJurosReceita     = 0;
            $nTotalMultaReceita     = 0;
            $nTotalHistoricoReceita = 0;

						for ($www = 0; $www < $intBk; $www ++) {

							db_fieldsmemory($rsBk, $www);

							if ($sqlerro == false) {

								$cldivold->k10_coddiv  = $cldivida->v01_coddiv;
								$cldivold->k10_numpre  = $k00_numpre;
								$cldivold->k10_numpar  = $k00_numpar;
								$cldivold->k10_receita = $k00_receit;
								$cldivold->incluir(null);

								if ((int)$cldivold->erro_status == 0) {

									$sqlerro = true;
									$erro_msg = $cldivold->erro_msg."--- Inclusao DIVOLD";
									break;
								}
							}

							if ($sqlerro == false) {

                $iNumPreUnificado = $k00_numpre;
                $iNumParUnificado = $k00_numpar;
                $iReceitUnificado = $k00_receit;
								$clarrecad->excluir_arrecad($k00_numpre,$k00_numpar,true,$k00_receit);

								if ((int)$clarrecad->erro_status == 0) {

									$sqlerro = true;
									$erro_msg = $clarrecad->erro_msg."--- Exclusao Arrecad";
									break;
								}
							}

							/**
							 * Verifica se numpre/numpar/receita estao lancandos no ArrecadCompos
							 */
              $sWhereUnificado      = "     arreckey.k00_numpre = {$iNumPreUnificado} ";
              $sWhereUnificado     .= " and arreckey.k00_numpar = {$iNumParUnificado} ";
              $sWhereUnificado     .= " and arreckey.k00_receit = {$iReceitUnificado} ";
              $sCampos              = " k00_vlrhist,k00_correcao,k00_juros,k00_multa ";
              $sSqlArrecadCompos    = $clarrecadcompos->sql_query(null, $sCampos, null, $sWhereUnificado);
              $rsArrecadCompos      = $clarrecadcompos->sql_record($sSqlArrecadCompos);
              $iLinhasArrecadCompos = $clarrecadcompos->numrows;

              if ($iLinhasArrecadCompos > 0) {

                $oArrecadacao = db_utils::fieldsMemory($rsArrecadCompos, 0);

                $nTotalCorrecaoReceita  += $oArrecadacao->k00_correcao;
                $nTotalJurosReceita     += $oArrecadacao->k00_juros;
                $nTotalMultaReceita     += $oArrecadacao->k00_multa;
                $nTotalHistoricoReceita += $oArrecadacao->k00_vlrhist;
              }
						}

						/**
						 * Inclui os dados unificados no ArreckKey
						 */
            $clarreckey->k00_numpre = $clarrecad->k00_numpre;
            $clarreckey->k00_numpar = $clarrecad->k00_numpar;
            $clarreckey->k00_receit = $clarrecad->k00_receit;
            $clarreckey->k00_hist   = $clarrecad->k00_hist;
            $clarreckey->k00_tipo   = $clarrecad->k00_tipo;
            $clarreckey->incluir(null);

            if ($clarreckey->erro_status = "0") {

              $sqlerro  = true;
              $erro_msg = $clarreckey->erro_msg . "--- Erro ao incluir ArrecKey";
              break;
            }

            /**
             * Inclui os dados unificados no ArrecadCompos
             */
            $clarrecadcompos->k00_arreckey = $clarreckey->k00_sequencial;
            $clarrecadcompos->k00_vlrhist  = "{$nTotalHistoricoReceita}";
            $clarrecadcompos->k00_correcao = "{$nTotalCorrecaoReceita}";
            $clarrecadcompos->k00_juros    = "{$nTotalJurosReceita}";
            $clarrecadcompos->k00_multa    = "{$nTotalMultaReceita}";
            $clarrecadcompos->incluir(null);

            if ($clarrecadcompos->erro_status == "0") {

              $sqlerro  = true;
              $erro_msg = $clarrecadcompos->erro_msg . "--- Erro ao incluir ArrecadCompos";
              break;
            }

					} else {

					  /********************************************************************************
					   * Caso o usuario nao tenha marcado a opÃ§Ã£o para unificar, o sistema segue daqui
					   */
						if ($sqlerro == false) {

							$cldivold->k10_coddiv  = $cldivida->v01_coddiv;
							$cldivold->k10_numpre  = $k00_numpre;
							$cldivold->k10_numpar  = $k00_numpar;
							$cldivold->k10_receita = $k00_receit;
							$cldivold->incluir(null);

							if ((int)$cldivold->erro_status == 0) {

								$sqlerro = true;
								$erro_msg = $cldivold->erro_msg."--- Inclusao DIVOLD";
								break;
							}
						}

						if ($sqlerro == false) {

						  $iNumPreNaoUnificado = $k00_numpre;
						  $iNumParNaoUnificado = $k00_numpar;
						  $iReceitNaoUnificado = $k00_receit;
							$clarrecad->excluir_arrecad($k00_numpre,$k00_numpar,true,$k00_receit);

							if ((int)$clarrecad->erro_status == 0) {

								$sqlerro = true;
								$erro_msg = $clarrecad->erro_msg."--- Exclusao Arrecad";
								break;
							}
						}

						/**
						 * Verifica se numpre/numpar/receita estao lancados em arreckey e arrecadcompos
						 */
					  $sWhere               = "     arreckey.k00_numpre = {$iNumPreNaoUnificado} ";
            $sWhere              .= " and arreckey.k00_numpar = {$iNumParNaoUnificado} ";
            $sWhere              .= " and arreckey.k00_receit = {$iNumParNaoUnificado} ";
            $sCampos              = " k00_vlrhist,k00_correcao,k00_juros,k00_multa ";
            $sSqlArrecadCompos    = $clarrecadcompos->sql_query(null, $sCampos, null, $sWhere);
            $rsArrecadCompos      = $clarrecadcompos->sql_record($sSqlArrecadCompos);
            $iLinhasArrecadCompos = $clarrecadcompos->numrows;

            if ($iLinhasArrecadCompos > 0) {

              $oArrecadacao = db_utils::fieldsMemory($rsArrecadCompos, 0);

              if ($sqlerro == false) {

                // Insere em arreckey
                $clarreckey->k00_numpre = $clarrecad->k00_numpre;
                $clarreckey->k00_numpar = $clarrecad->k00_numpar;
                $clarreckey->k00_receit = $clarrecad->k00_receit;
                $clarreckey->k00_hist   = $clarrecad->k00_hist;
                $clarreckey->k00_tipo   = $clarrecad->k00_tipo;
                $clarreckey->incluir(null);

                if ($clarreckey->erro_status == "0") {

                  $erro_msg = $clarreckey->erro_msg;
                  $sqlerro  = true;
                  break;
                }
              }

              if ($sqlerro == false) {

                // Insere ArrecCompos
                $clarrecadcompos->k00_arreckey = $clarreckey->k00_sequencial;
                $clarrecadcompos->k00_vlrhist  = $oArrecadacao->k00_vlrhist;
                $clarrecadcompos->k00_correcao = $oArrecadacao->k00_correcao;
                $clarrecadcompos->k00_juros    = $oArrecadacao->k00_juros;
                $clarrecadcompos->k00_multa    = $oArrecadacao->k00_multa;
                $clarrecadcompos->incluir(null);

                if ($clarrecadcompos->erro_status == "0") {

                  $erro_msg = $clarrecadcompos->erro_msg;
                  $sqlerro  = true;
                  break;
                }
              }
            }
          }
        }
		  }
    }

		$datafim = date("Y-m-d");
		$horafim = date("H:i");

		if (isset($cldivimporta->v02_divimporta) && $cldivimporta->v02_divimporta != null) {

		  $cldivimporta->v02_divimporta = $iDivImporta;
		  $cldivimporta->v02_datafim = $datafim;
  		$cldivimporta->v02_horafim = $horafim;
  		$cldivimporta->alterar($cldivimporta->v02_divimporta);
  		if ((int)$cldivimporta->erro_status == 0) {

  			db_msgbox($cldivimporta->erro_msg);
  			$sqlerro = true;
  		}
		}
	}

	$chave_origem = "";
	$chave_destino = "";

	db_fim_transacao($sqlerro);
}

if ($teste == true) {
	if ($erro_msg != "") {

		echo "<script>parent.document.getElementById('process').style.visibility='hidden';</script>";
		db_msgbox($erro_msg);
		echo "<script>top.corpo.db_iframe.hide();</script>";
		echo "<script>top.corpo.location.href='div4_importadivida001.php'</script>";
	}
}
?>
<?php
/**
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_termoprotprocesso_classe.php"));

$mat_numpre = split("#",base64_decode(@$HTTP_SERVER_VARS['QUERY_STRING']));

$iTipo  = $mat_numpre[0];
$numpre = $mat_numpre[1];
$numpar = $mat_numpre[2];

db_postmemory($HTTP_POST_VARS);

$sSql     = "select k03_tipo from arretipo where k00_tipo = {$iTipo} and k00_instit = ".db_getsession('DB_instit');
$rsResult = db_query($sSql) or die($sSql);

db_fieldsmemory($rsResult, 0);

if ($numpar > 0) {
  $sAnd = " and k28_numpar = {$numpar} ";
} else {
  $sAnd = "";
}

$sSqlArrejust  = " select distinct id_usuario,nome,k27_data,k27_hora,k27_dias,k27_obs, k28_numpar ";
$sSqlArrejust .= " from arrejust                                                                  ";
$sSqlArrejust .= "      inner join arrejustreg on k28_arrejust=k27_sequencia                      ";
$sSqlArrejust .= "      inner join db_usuarios on k27_usuario = id_usuario                        ";
$sSqlArrejust .= " where k28_numpre = {$numpre}                                                   ";
$sSqlArrejust .= "      {$sAnd}                                                                   ";
$sSqlArrejust .= "  and k27_data+k27_dias > '".date("Y-m-d",db_getsession("DB_datausu"))."'       ";
$sSqlArrejust .= "  and k27_instit = ".db_getsession('DB_instit')."                               ";

$rsArrejust     = db_query($sSqlArrejust);
$iLinhaArrejust = pg_num_rows($rsArrejust);

if ($numpar > 0) {
  $sAnd1 = " and k00_numpar = {$numpar} ";
} else {
  $sAnd1 = "";
}

$sSqlArrevenc   = "select * from arrevenc where k00_numpre = {$numpre} {$sAnd1}";
$rsArrevenc     = db_query($sSqlArrevenc);
$iLinhaArrevenc = pg_num_rows($rsArrevenc);

$usuario = db_getsession("DB_id_usuario");

/*
 * Verificamos se o botão de Simular a anulação do parcelamento deverá estar desabilitado caso o parâmetro
 * de utilização da partilha do módulo jurídico esteja habilitado e se o parcelamento possuir recibo emitido com custas
 * e esse recibo esteja presente em um arquivo de remessa (processoforopartilhacusta/partilhaarquivoreg)
 */
$sBloqueio       = "";
$oDaoParJuridico = db_utils::getDao("parjuridico");
$rsParJuridico   = $oDaoParJuridico->sql_record($oDaoParJuridico->sql_query(db_getsession("DB_anousu"),db_getsession("DB_instit"), "v19_partilha"));

if ($oDaoParJuridico->numrows > 0 ) {

  $lPartilha = db_utils::fieldsMemory($rsParJuridico,0)->v19_partilha;

  if ($lPartilha != "f") {

    $sSqlVerificaRecibo  = " select *                                                                                                                                 ";
    $sSqlVerificaRecibo .= " from recibopaga                                                                                                                          ";
  	$sSqlVerificaRecibo .= "      inner join processoforopartilhacusta on processoforopartilhacusta.v77_numnov             = recibopaga.k00_numnov                    ";
  	$sSqlVerificaRecibo .= "      inner join partilhaarquivoreg        on partilhaarquivoreg.v79_processoforopartilhacusta = processoforopartilhacusta.v77_sequencial ";
  	$sSqlVerificaRecibo .= " where recibopaga.k00_numpre = {$numpre}                                                                                                  ";

    $rsVerificaRecibo = db_query($sSqlVerificaRecibo);

    if (pg_num_rows($rsVerificaRecibo) > 0 ) {
      $sBloqueio = " Parcelamento possui recibo emitido com custas em arquivo de remessa. Anulação não permitida!";
    }

  }

}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
fieldset legend {
  font-weight:bold;
}
fieldset {
  margin: 10px;
}
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
table.linhaZebrada {
  width: 98%;
}
table.linhaZebrada tr td:nth-child(even) {
  background-color : #FFF;
  padding-left     : 5px;
}
table.linhaZebrada tr td:nth-child(odd) {
  font-weight:bold;
  width      :150px;
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_AbreJanelaRelatorio() {
  window.open('div2_termoparc_002.php?parcel='+document.form1.v07_parcel.value,'','width=790,height=530,scrollbars=1,location=0');
}

function js_anula() {
  var usu = <?php echo $usuario; ?>;
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_anulaparc1','cai4_anulaparc002.php?parcel='+document.form1.v07_parcel.value+'&usu='+usu,'Pesquisa',true);
}

</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<center>

<?php
// select na termoreparc para saber se eh um reparcelamento
$sSqlReparcelamento  = " select *                                                                                  ";
$sSqlReparcelamento .= "   from termo                                                                              ";
$sSqlReparcelamento .= "        inner join termoreparc  on v07_parcel            = v08_parcel                      ";
$sSqlReparcelamento .= "        inner join arrecad      on v07_numpre            = k00_numpre                      ";
$sSqlReparcelamento .= "        inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre              ";
$sSqlReparcelamento .= "                               and arreinstit.k00_instit = ".db_getsession('DB_instit')     ;
$sSqlReparcelamento .= "        inner join cgm          on v07_numcgm            = z01_numcgm                      ";
$sSqlReparcelamento .= "        left  join arrematric a on v07_numpre            = a.k00_numpre                    ";
$sSqlReparcelamento .= "        left  join arreinscr  i on v07_numpre            = i.k00_numpre                    ";
$sSqlReparcelamento .= " where v07_numpre = $numpre and v07_instit = ".db_getsession('DB_instit')                   ;

$rsReparc   = db_query($sSqlReparcelamento);
$intNumrows = pg_numrows($rsReparc);


if ($intNumrows > 0) {
    db_fieldsmemory($rsReparc,0);

?>
  <table width="100%">
    <tr>
      <td align="center"><strong style="font-size:14px">Reparcelamento</strong></td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="1" cellspacing="0" bgcolor="#999999">
          <tr>
            <td width="42%" align="right">C&oacute;digo do Parcelamento:</td>
            <td width="58%">&nbsp; <?php echo $v07_parcel; ?></td>
          </tr>
          <tr>
            <td align="right">Data Parcelamento:</td>
            <td>&nbsp; <?php echo $v07_dtlanc; ?></td>
          </tr>
          <tr>
            <td align="right">Total Parcelas:</td>
            <td>&nbsp; <?php echo $v07_totpar; ?></td>
          </tr>
          <tr>
            <td align="right">Valor Total Parcelado:</td>
            <td>&nbsp; <? echo db_formatar($v07_valor,'f'); ?></td>
          </tr>
          <tr>
            <td align="right">Valor Entrada:</td>
            <td>&nbsp; <?php echo $v07_vlrent; ?></td>
          </tr>
          <tr>
            <td align="right">Data Primeira parcela:</td>
            <td>&nbsp; <?php echo $v07_datpri; ?></td>
          </tr>

          <form name="form1" method="post">
            <tr>
              <td align="right">Termo:</td>
              <td>
                <input type="button" name="Submit3" value="Visualizar o Termo" onclick="js_AbreJanelaRelatorio();">
                <input type="hidden" name="v07_parcel" value="<?php echo $v07_parcel; ?>">
            		<?php
              		$mostrabotao      = db_permissaomenu(db_getsession("DB_anousu"),81,2537);
              		$mostrabotaoBySim = db_permissaomenu(db_getsession("DB_anousu"),81,8393);

              		if ($mostrabotao == "true" || $mostrabotaoBySim == "true") {
              			if (@$mostra != "nao") {
              		?>
              				<input type="button" name="anula" value="Simular Anulação de Parcelamento" onclick="js_anula();" >
              		<?php
              			}
              		}
            		?>
              </td>
            </tr>
          </form>

          <tr>
            <td align="right">Contribu&iacute;nte:</td>
            <td>&nbsp; <?php echo $z01_nome; ?></td>
          </tr>
          <tr>
            <td align="right">Nome Respons&aacute;vel:</td>
            <td>&nbsp; <?php echo $z01_nome; ?></td>
          </tr>
          <tr>
            <td align="right">C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
            <td> &nbsp; <?php echo $k00_numpre; ?></td>
          </tr>
          <tr>
            <td align="right">Hist&oacute;rico:</td>
            <td> &nbsp; <?php echo @$v07_hist; ?> Protocolo: <?php echo $oProtocolo->v27_protprocesso; ?></td>
          </tr>
          <tr>
            <td align="right">Matr&iacute;cula Im&oacute;vel:</td>
            <td> &nbsp;
              <?php
                if (@$k00_matric != "") {
                  echo $k00_matric;
                }
              ?>
            </td>
          </tr>
          <tr>
            <td align="right">Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
            <td> &nbsp;
              <?php
                if (@$k00_inscr != "") {
                  echo $k00_inscr;
                }
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <?
} else {

	if ($k03_tipo == 1) {

	  // Débito tipo 1 - I.P.T.U
	  require_once(modification("forms/db_frmgerfinanctipo1.php"));

	} else if ($k03_tipo == 4) {

	  // Débito tipo 4 - CONTRIBUIÇÃO MELHORIA
	  require_once(modification("forms/db_frmgerfinanctipo4.php"));

	} else if ($k03_tipo == 5) {

	  // Débito tipo 5 - DÍVIDA ATIVA
	  require_once(modification("forms/db_frmgerfinanctipo5.php"));

	} else if ($k03_tipo == 7) {

	  // Débito tipo 7 - DIVERSOS
	  require_once(modification("forms/db_frmgerfinanctipo7.php"));

	} else if ($k03_tipo == 6 || $k03_tipo == 13 || $k03_tipo == 16 || $k03_tipo == 17  || $k03_tipo == 30 ) {

	  // Débito tipo 6 - PARCELAMENTO DIVIDA ATIVA
	  //            13 - PARCELAMENTO DE INICIAL D. ATIVA
	  //            16 - PARCELAMENTO DIVERSO
	  //            17 - PARCELAMENTO DE CONTRIB. MELHORIA
	  //            30 - PARCELAMENTO DO FORO
	  require_once(modification("forms/db_frmgerfinanctipo6.php"));

	} else if($k03_tipo == 2 || $k03_tipo == 9) {

	  // Débito tipo 2 - ISSQN FIXO
	  //             9 - ALVARÁ
		require_once(modification("forms/db_frmgerfinanctipo2.php"));

	} else if ($k03_tipo == 3) {

	  // Débito tipo 3 - ISSQN VARIÁVEL
	  require_once(modification("forms/db_frmgerfinanctipo3.php"));

	} else if ($k03_tipo == 15) {

	  // Débito tipo 15 - CERTIDÃO DO FORO
    require_once(modification("forms/db_frmgerfinanctipo15.php"));
  } else if ($k03_tipo == 20) {

    // Débito tipo 20 - Saneamento Básico
    require_once(modification("forms/db_frmgerfinanctipo137.php"));
  }
	?>

<?php
	if ($iLinhaArrejust > 0) {
?>
	  <table width="100%">
  	  <tr>
  	    <td align="center" style="font-size:14px">
  	      <b>Débitos justificados</b>
  	    </td>
  	  </tr>
  	  <tr>
    	  <td>
      	  <table width="97%" cellspacing="0" class="tab_cinza" style="font-size:14px">
        	  <tr>
          	  <th align="center">Parcela</th>
          		<th align="center">Data</th>
          		<th align="center">Hora</th>
          		<th align="center">Quant. de dias</th>
          		<th align="center">Usuário</th>
          		<th align="center">Observação</th>
        		</tr>
      		<?php
        	  for ($iContArrejust = 0; $iContArrejust < $iLinhaArrejust; $iContArrejust++) {
        	      db_fieldsmemory($rsArrejust, $iContArrejust);
            	  echo "
                      <tr>
                    	  <td align='center'>$k28_numpar</td>
                    		<td align='center'>".db_formatar($k27_data,"d")."</td>
                    		<td align='center'>$k27_hora</td>
                    		<td align='center'>$k27_dias</td>
                    		<td align='center'>$nome</td>
                    		<td align='center'>$k27_obs</td>
                  		</tr>
                ";
        		}
      		?>
      		</table>
    		</td>
  		</tr>
		</table>

		<br>

		<?php
  }

  if ($iLinhaArrevenc > 0) {
	  ?>
	  <table width="100%">
  	  <tr>
  	    <td align="center" style="font-size:14px">
  	      <b>Prorrogação do Vencimento</b>
  	    </td>
  	  </tr>
  	  <tr>
    	  <td>
      	  <table width="97%"  cellspacing="0" class="tab_cinza" style="font-size:14px">
        	  <tr>
          	  <th align="center">Parcela</th>
          		<th align="center">Data inicial</th>
          		<th align="center">Data final</th>
          		<th align="center">Observação</th>
        		</tr>
        		<?php
               for ($iContArrevenc = 0; $iContArrevenc < $iLinhaArrevenc; $iContArrevenc++) {
                 db_fieldsmemory($resultarrevenc, $iContArrevenc);
                 if ($k00_dtfim != "") {
                   $dDataFim = db_formatar($k00_dtfim,"d");
                 } else {
                   $dDataFim = "";
                 }
                 echo "
                       <tr>
                         <td align='center'>$k00_numpar</td>
                      	 <td align='center'>".db_formatar($k00_dtini,"d")."</td>
                      	 <td align='center'>$dDataFim</td>
                      	 <td align='center'>$k00_obs</td>
                    	 </tr>
                 ";
              }
        		?>
      		</table>
    		</td>
  		</tr>
		</table>

		<br>

		<?php
  }
}

if ($numpar > 0) {

  require_once(modification('classes/db_arrevenc_classe.php'));
  $clarrevenc = new cl_arrevenc;

  $sCampos = " k00_dtini,k00_dtfim,((case when k00_dtfim is null then current_date else k00_dtfim end )+1)-k00_dtini as dia ";
  $sWhere  = " k00_numpre = {$numpre} and k00_numpar = {$numpar} ";

  $rsArrevenc  = $clarrevenc->sql_record($clarrevenc->sql_query("", $sCampos, "k00_dtini", $sWhere));

  if ($rsArrevenc != false && $clarrevenc->numrows > 0 ) {
?>
    <table width="100%" border="1" cellspacing="0" bgcolor="#999999">
      <tr>
        <td bgcolor="#CCCCCC" align="center" colspan="3" style="font-size:14px"><strong>Prorrogações de Vencimentos Efetuadas</strong></td>
      </tr>
      <tr>
        <td> Data Inicial</td>
        <td> Data Final</td>
        <td> Dias</td>
      </tr>
      <?php
        for ($iContArrevenc = 0; $iContArrevenc < $clarrevenc->numrows; $iContArrevenc++) {
          db_fieldsmemory($rsArrevenc, $iContArrevenc, true);
          echo "<tr>";
            echo "<td>$k00_dtini</td>";
            echo "<td>".($k00_dtfim==""?"Hoje":$k00_dtfim)."</td>";
            echo "<td>$dia</td>";
          echo "<tr>";
        }
      ?>
    </table>
    <?php
  }
}

require_once(modification('classes/db_arrehist_classe.php'));
$clarrehist = new cl_arrehist;

$sWhere = " k00_numpre = $numpre ".($numpar > 0 ? "and k00_numpar = $numpar" : "");

$clarrehist->sql_query(null, "*", "k00_dtoper", $sWhere);

$rsArrehist = $clarrehist->sql_record($clarrehist->sql_query(null,"*","k00_dtoper"," k00_numpre = $numpre ".($numpar > 0 ? "and k00_numpar = $numpar" : "")));

if ($rsArrehist != false && $clarrehist->numrows > 0 ) {
?>
  <table width="100%" border="1" cellspacing="0" bgcolor="#999999">
    <tr>
      <td bgcolor="#CCCCCC" align="center" colspan="5" style="font-size:14px"><strong>Históricos</strong></td>
    </tr>
    <tr style='font-weight:bold'>
      <td> Histórico</td>
      <td> Data Lançamento</td>
      <td> Usuário</td>
      <td> Hora</td>
      <td> Histórico</td>
    </tr>
    <?php
      for ($iContArrehist = 0; $iContArrehist < $clarrehist->numrows; $iContArrehist++) {
        db_fieldsmemory($rsArrehist, $iContArrehist, true);
        echo "<tr>";
          echo "<td>$k01_descr</td>";
          echo "<td>$k00_dtoper</td>";
          echo "<td>$nome</td>";
          echo "<td>$k00_hora</td>";
          echo "<td>$k00_histtxt</td>";
        echo "<tr>";
      }
    ?>
  </table>

<?php
}
?>

<fieldset>
  <legend>Lançamentos Efetuados</legend>
  <?php
   if (@$k00_tipo == 33) {
     $sArquivoIframe = 'cai3_gerfinanc666.php';
   } else {
     $sArquivoIframe = 'cai3_gerfinanc555.php';
   }
  ?>
  <iframe width="100%" height="300" border="0" src="<?php echo $sArquivoIframe; ?>?numpre=<?php echo $numpre; ?>&numpar=<?php echo $numpar; ?>"></iframe>
</fieldset>

</center>
</body>
</html>

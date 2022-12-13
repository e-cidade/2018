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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

include ("classes/db_issvar_classe.php");
include ("classes/db_issvarold_classe.php");
include ("classes/db_issvardiv_classe.php");
include ("classes/db_issvarnotas_classe.php");
include ("classes/db_issvarnotasold_classe.php");
include ("classes/db_issvarlev_classe.php");
include ("classes/db_levanta_classe.php");
include ("classes/db_levvalor_classe.php");
include ("classes/db_levinscr_classe.php");
include ("classes/db_numpref_classe.php");
include ("classes/db_cadvenc_classe.php");
include ("classes/db_parissqn_classe.php");
include ("classes/db_db_confplan_classe.php");
include ("classes/db_arrecad_classe.php");
include ("classes/db_arreold_classe.php");
include ("classes/db_arrecant_classe.php");
include ("classes/db_arreinscr_classe.php");
include ("classes/db_issvarlevold_classe.php");

$cllevanta = new cl_levanta;
$cllevvalor = new cl_levvalor;
$clissvarlevold = new cl_issvarlevold;
$cllevinscr = new cl_levinscr;
$clissvar = new cl_issvar;
$clissvarold = new cl_issvarold;
$clissvardiv = new cl_issvardiv;
$clissvarlev = new cl_issvarlev;
$clnumpref = new cl_numpref;
$clcadvenc = new cl_cadvenc;
$clparissqn = new cl_parissqn;
$cldb_confplan = new cl_db_confplan;
$clarrecad = new cl_arrecad;
$clarreold = new cl_arreold;
$clarrecant = new cl_arrecant;
$clarreinscr = new cl_arreinscr;
$clissvarnotas = new cl_issvarnotas;
$clissvarnotasold = new cl_issvarnotasold;
$clrotulo = new rotulocampo;
$clrotulo->label("y60_codlev");
$clrotulo->label("z01_nome");
if (isset ($cancelar)) {
	db_inicio_transacao();

	$sqlerro = false;
	if (isset ($y60_codlev) && ($y60_codlev != "")) {
		$codlev = $y60_codlev;
		$result_issvarlev = $clissvarlev->sql_record($clissvarlev->sql_query_file(null, $codlev));


		for ($y = 0; $y < $clissvarlev->numrows; $y ++) {
			db_fieldsmemory($result_issvarlev, $y);

      /* se ja esta em divida nao deixa cancelar */
      $rsIssvardiv = $clissvardiv->sql_record($clissvardiv->sql_query_file(null, $q18_codigo));
      if($clissvardiv->numrows != 0){
          $erro_msg = "Levantamento $codlev em dívida ativa ! \\n Cancele a importação e tente novamente.";
          $sqlerro = true;
          break;
      }

			$result_issvar = $clissvar->sql_record($clissvar->sql_query_file(null, "*", null, "q05_codigo=$q18_codigo"));
			db_fieldsmemory($result_issvar, 0);


			$result_arrecant = $clarrecant->sql_record($clarrecant->sql_query_file(null, "*", null, "k00_numpre=$q05_numpre and k00_numpar=$q05_numpar"));
			if ($clarrecant->numrows != 0) {
				$erro_msg = "Levantamento em processo de pagamento ou cancelamento !!\\n         Cancelamento Abortado!! ";
				$sqlerro = true;
				break;
			}
		}
		$numrows = $clissvarlev->numrows;
		for ($yy = 0; $yy < $numrows; $yy ++) {
			db_fieldsmemory($result_issvarlev, $yy);
			$result_issvar = $clissvar->sql_record($clissvar->sql_query_file(null, "*", null, "q05_codigo=$q18_codigo"));
			if ($sqlerro == false) {
				db_fieldsmemory($result_issvar, 0);
				$clarrecad->excluir(null, "k00_numpre=$q05_numpre and k00_numpar=$q05_numpar");
			}
			if ($sqlerro == false) {
				$clissvarlev->excluir($q18_codigo);
				if ($clissvarlev->erro_status == 0) {
					$erro_msg = $clissvarlev->erro_msg;
					$sqlerro = true;
					break;
				}
			}
			if ($sqlerro == false) {
				$clissvar->excluir($q18_codigo);
				if ($clissvar->erro_status == 0) {
					$erro_msg = $clissvar->erro_msg;
					$sqlerro = true;
					break;
				}
			}
		}

		$result_issvarlevold = $clissvarlevold->sql_record($clissvarlevold->sql_query_inf(null, " distinct * ", null, "y85_codlev=$codlev"));
		for ($x = 0; $x < $clissvarlevold->numrows; $x ++) {
			db_fieldsmemory($result_issvarlevold, $x);
			if ($sqlerro == false) {
				// $clissvar->q05_codigo=$q22_codigo;
				$clissvar->q05_numpre = $q22_numpre;
				$clissvar->q05_numpar = $q22_numpar;
				$clissvar->q05_valor = $q22_valor;
				$clissvar->q05_ano = $q22_ano;
				$clissvar->q05_mes = $q22_mes;
				$clissvar->q05_histor = $q22_histor;
				$clissvar->q05_aliq = $q22_aliq;
				$clissvar->q05_bruto = $q22_bruto;
				$clissvar->q05_vlrinf = $q22_vlrinf;
				$clissvar->incluir($q22_codigo);
				if ($clissvar->erro_status == 0) {
					$erro_msg = $clissvar->erro_msg;
					$sqlerro = true;
					break;
				}
			}
      $result_issvarnotasold  = $clissvarnotasold->sql_record($clissvarnotasold->sql_query(null,'*',null,"q16_codigo = $q22_codigo"));
			 if ($clissvarnotasold->numrows > 0){
		     for ($xx = 0; $xx < $clissvarnotasold->numrows; $xx ++) {
		  	  db_fieldsmemory($result_issvarnotasold, $xx);
		      if ($sqlerro == false) {

				     $clissvarnotas->q06_codigo = $q16_codigo;
				     $clissvarnotas->q06_seq    = $q16_seq;
				     $clissvarnotas->q06_nota   = $q16_nota;
				     $clissvarnotas->q06_valor  = $q16_valor;
				     $clissvarnotas->incluir($q22_codigo,$q16_seq);
             $clissvarnotasold->excluir(null,"q16_codigo=$q16_codigo");
				     if ($clissvarnotas->erro_status == 0) {
					     $erro_msg = $clissvarnotas->erro_msg;
					     $sqlerro = true;
					   break;
					  }
				  }
			  }
			 }
	   $delete = "delete from issvarold where q22_codigo=$q22_codigo";
		 db_query($delete);
 			$result_arreold = $clarreold->sql_record($clarreold->sql_query_file(null, "*", null, "k00_numpre=$q22_numpre and k00_numpar=$q22_numpar"));
			if ($sqlerro == false) {

				db_fieldsmemory($result_arreold, 0);
				$clarrecad->k00_numpre = $k00_numpre;
				$clarrecad->k00_numpar = $k00_numpar;
				$clarrecad->k00_numcgm = $k00_numcgm;
				$clarrecad->k00_dtoper = $k00_dtoper;
				$clarrecad->k00_receit = $k00_receit;
				$clarrecad->k00_hist   = $k00_hist;
				$clarrecad->k00_valor  = $k00_valor;
				$clarrecad->k00_dtvenc = $k00_dtvenc;
				$clarrecad->k00_numtot = $k00_numtot;
				$clarrecad->k00_numdig = $k00_numdig;
				$clarrecad->k00_tipo = $k00_tipo;
				$clarrecad->k00_tipojm = $k00_tipojm;
				$clarrecad->incluir();
				if ($clarrecad->erro_status == 0) {

					$erro_msg = $clarrecad->erro_msg;
					$sqlerro = true;
					break;
				}
				if ($sqlerro == false) {
					$clarreold->excluir_where("k00_numpre=$q22_numpre and k00_numpar=$q22_numpar");
				}
			}
		}

		$cllevanta->y60_importado = 'false';
		$cllevanta->alterar($codlev);
		if ($cllevanta->erro_status == 0) {

			$erro_msg = $cllevanta->erro_msg;
			$sqlerro = true;
		}
	}
	db_fim_transacao($sqlerro);
}
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
  <div class="container">
    <form name="form1" method="post" action="">
     <fieldset>
       <legend>Cancelar Exportação</legend>
         <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap title="<?=@$Ty60_codlev?>">
              <?php
                db_ancora(@$Ly60_codlev, "js_lev(true);", 1);
              ?>
            </td>
            <td>
              <?php
                db_input('y60_codlev', 6, $Iy60_codlev, true, 'text', 1, " onchange='js_lev(false);'");
                db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
              ?>
            </td>
          </tr>
         </table>
     </fieldset>
     <input name="cancelar" type="submit"  onClick="return js_cancel();" value="Processar">
    </form>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">
function  js_cancel(){

  if(document.form1.y60_codlev.value == ''){

    alert("Campo Levantamento é de preenchimento obrigatório.");
    return false;
  }
  return true;
}

function js_lev(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta03.php?funcao_js=parent.js_mostralev1|y60_codlev|DBtxtnome_origem','Pesquisa',true);
  }else{

    lev = document.form1.y60_codlev.value;
    if(lev != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta03.php?pesquisa_chave='+lev+'&funcao_js=parent.js_mostralev','Pesquisa',false);
    }else{
      document.form1.z01_nome.value='';
    }
  }
}
function js_mostralev(chave,erro){

  if(erro==true){

    alert('Levantamento inválido.');
    document.form1.y60_codlev.value="";
    document.form1.y60_codev.focus();
  } else{
    document.form1.z01_nome.value = chave;
  }
}
function js_mostralev1(chave1,chave2){
  document.form1.y60_codlev.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
</script>
<?php
if (isset ($cancelar)) {

	if ($sqlerro == true) {

		db_msgbox($erro_msg);
	} else {

		db_msgbox("Cancelamento efetivado com sucesso!!");
		echo "<script>top.corpo.location.href='fis4_cancelimport001.php';</script>";
	}
}
?>
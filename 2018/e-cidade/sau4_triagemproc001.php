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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");

define("MENSAGEM_TRIAGEM_PROCEDIMENTO", "saude.ambulatorial.sau4_triagemproc001.");

db_postmemory( $_POST );

$clprontuarios    = new cl_prontuarios;
$clprontproced    = new cl_prontproced_ext;
$clprontcid       = new cl_prontcid;
$clprontprofatend = new cl_prontprofatend_ext;
$clprontprocedcid = new cl_prontprocedcid();
$oDaoCgm          = new cl_cgm();

$db_opcao  = 1;
$db_botao  = true;
$db_botao1 = false;

$sd29_d_data_dia = date("d", time());
$sd29_d_data_mes = date("m", time());
$sd29_d_data_ano = date("Y", time());
$sd29_c_hora     = date("H:i", time());

/**
 * Pesquisa por medico
 * O Profissional informado na tela é o vinculo da especialidade do médico em uma unidade (especmedico x unidademedicos)
 */
$sCampos    = "z01_nome, sd03_i_codigo, z01_numcgm, sd27_i_codigo";
$sJoins     = "inner join db_usuacgm     on cgmlogin                     = z01_numcgm ";
$sJoins    .= "inner join db_usuarios    on db_usuarios.id_usuario       = db_usuacgm.id_usuario ";
$sJoins    .= "inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm ";
$sJoins    .= "inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo ";
$sJoins    .= "inner join especmedico    on especmedico.sd27_i_undmed    = unidademedicos.sd04_i_codigo ";
$sJoins    .= "inner join unidades       on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade ";
$sWhere     = " sd02_i_codigo = ".db_getsession("DB_coddepto");
$sWhere    .= " and db_usuacgm.id_usuario = ".db_getsession("DB_id_usuario");
$sSql       = $oDaoCgm->sql_query_file(null,$sCampos);
$sSql      .= $sJoins.' where '.$sWhere;
$rs         = $oDaoCgm->sql_record($sSql);
$lProfSaude = false;

if ( $oDaoCgm->numrows > 0 ) {

  $oProfissional       = db_utils::fieldsmemory($rs, 0);
  $z01_nome            = $oProfissional->z01_nome;
  $sd03_i_codigo       = $oProfissional->sd03_i_codigo;
  $z01_numcgm          = $oProfissional->z01_numcgm;
  $sd29_i_profissional = $oProfissional->sd27_i_codigo;
  $lProfSaude          = true;
}

$sCampos = "m.*, rhcbo.*, especmedico.*, medicos.*, prontprofatend.*, z01_v_nome";
$sWhere  = "s104_i_prontuario = {$chavepesquisaprontuario}";
$result_prontprofatend = db_query( $clprontprofatend->sql_query_ext( null, $sCampos , "s104_i_codigo", $sWhere ) );

if ( pg_num_rows($result_prontprofatend) > 0 ) {

	$obj_prontprofatend = db_utils::fieldsMemory( $result_prontprofatend, 0 );
	if ( !isset($sd29_i_profissional) ) {

		if ( $clprontprofatend->sql_prontproced($chavepesquisaprontuario, $obj_prontprofatend->s104_i_profissional) ) {

	 		db_fieldsmemory( $result_prontprofatend, 0 );

			if ( !isset($incluir) && !isset($alterar) && !isset($excluir) ) {
	 			$sd29_i_profissional = $obj_prontprofatend->s104_i_profissional;
	 		}
		}
	}
}

if ( isset($opcao) ) {

 $db_botao1 = true;
 $db_opcao  = $opcao == "alterar" ? 2 : 3;
 $result    = $clprontproced->sql_record( $clprontproced->sql_query_ext($sd29_i_codigo) );

 db_fieldsmemory($result,0);
}

if ( isset($incluir) ) {

	if ( isset($sd24_c_digitada) && $sd24_c_digitada == "N" ) {

		db_inicio_transacao();

		 if ( pg_num_rows($result_prontprofatend) > 0 ) {

		 	if ( $clprontprofatend->sql_prontproced($chavepesquisaprontuario, $sd29_i_profissional) ) {

		 		?><!-- atualiza aba paciente - profissional atendimento -->
		 		<script type="text/javascript">
		 		  parent.iframe_a1.document.form1.sd03_i_codigo.value   = '<?=$sd03_i_codigo?>';
		 		  parent.iframe_a1.document.form1.z01_nome.value        = '<?=$z01_nome ?>';
		 		  parent.iframe_a1.document.form1.rh70_estrutural.value = '<?=$rh70_estrutural ?>';
		 		  parent.iframe_a1.document.form1.rh70_descr.value      = '<?=$rh70_descr ?>';
		 		</script>
		 		<?php
		 	}
		 }

    $clprontproced->sd29_sigilosa     = 'false';
		$clprontproced->sd29_i_prontuario = $chavepesquisaprontuario;
		$clprontproced->sd29_i_usuario    = DB_getsession("DB_id_usuario");
		$clprontproced->sd29_d_cadastro   = date("Y-m-d",db_getsession("DB_datausu"));
		$clprontproced->sd29_c_cadastro   = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
		$clprontproced->incluir("");

		if ( (int)$sd70_i_codigo > 0 && $clprontproced->erro_status != '0' ) {

			$clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
			$clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
			$clprontprocedcid->incluir(null);

			if ( $clprontprocedcid->numrows_incluir == 0 ) {

				$clprontproced->erro_msg    = $clprontprocedcid->erro_msg;
				$clprontproced->erro_status = $clprontprocedcid->erro_status;
			}
		}

		db_fim_transacao();
	} else {
    db_msgbox( _M( MENSAGEM_TRIAGEM_PROCEDIMENTO . "faa_ja_digitada" ) );
	}
} else if ( isset($alterar) ) {

	db_inicio_transacao();
	if ( pg_num_rows($result_prontprofatend) > 0 ) {

		if ( $clprontprofatend->sql_prontproced($chavepesquisaprontuario, $sd29_i_profissional) ) {

			$clprontprofatend->s104_i_codigo       = $obj_prontprofatend->s104_i_codigo;
			$clprontprofatend->s104_i_prontuario   = $obj_prontprofatend->s104_i_prontuario;
			$clprontprofatend->s104_i_profissional = $sd29_i_profissional;
			$clprontprofatend->alterar($obj_prontprofatend->s104_i_codigo);
			?><!-- atualiza aba paciente - profissional atendimento -->
			<script type="text/javascript">
			  parent.iframe_a1.document.form1.sd03_i_codigo.value   = '<?=$sd03_i_codigo?>';
			  parent.iframe_a1.document.form1.z01_nome.value        = '<?=$z01_nome ?>';
			  parent.iframe_a1.document.form1.rh70_estrutural.value = '<?=$rh70_estrutural ?>';
			  parent.iframe_a1.document.form1.rh70_descr.value      = '<?=$rh70_descr ?>';
			</script>
			<?php
		}
	}

	$clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
	$clprontproced->alterar($sd29_i_codigo);
	$clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");

	if ( (int)$sd70_i_codigo > 0 ) {

		$clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
		$clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
		$clprontprocedcid->incluir(null);

		if( $clprontprocedcid->numrows_incluir == 0 ) {

			$clprontproced->erro_msg    = $clprontprocedcid->erro_msg;
			$clprontproced->erro_status =  $clprontprocedcid->erro_status;
		}
	}

	db_fim_transacao();
} else if ( isset($excluir) ) {

	db_inicio_transacao();
	$clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");
  $clprontproced->excluir($sd29_i_codigo);
	db_fim_transacao();
} else if (isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario) ) {

  $sCamposProntuarios = "prontuarios.*, rh70_descr as cbo_triagem, m.z01_nome as profissional_triagem";
  $result             = $clprontuarios->sql_record($clprontuarios->sql_query($chavepesquisaprontuario, $sCamposProntuarios));

  db_fieldsmemory( $result, 0 );

  $sCamposProcedimentos = "prontuarios.*, cgs_und.*, medicos.*, m.*, rhcbo.*, prontproced.sd29_i_profissional ";
  $sWhereProcedientos   = "sd29_i_prontuario = $chavepesquisaprontuario and sd03_i_codigo = {$sd03_i_codigo}";
	$res_proced = $clprontproced->sql_record($clprontproced->sql_query_nolote_ext(null, $sCamposProcedimentos,null, $sWhereProcedientos));

	if ( $clprontproced->numrows > 0 ) {
	  db_fieldsmemory($res_proced,0);
	}

} else if (isset($emitirfaa) ) {
  die(">>>>".$chavepesquisaprontuario);
}

$oGet = db_utils::postMemory( $_GET );

if( isset( $oGet->iProfissional ) && isset( $oGet->iEspecialidade ) && isset( $oGet->sProfissional ) ) {

  $oDaoEspecMedico    = new cl_especmedico();
  $sCamposEspecMedico = "rh70_sequencial,  rh70_estrutural, rh70_descr";
  $sWhereEspecMedico  = "    sd04_i_unidade = " . db_getsession("DB_coddepto") . " and sd04_i_medico = {$oGet->iProfissional} ";
  $sWhereEspecMedico .= "and rh70_sequencial = {$oGet->iCbo}";

  $sSqlDadosProcedimento = $oDaoEspecMedico->sql_query_especmedico( null, $sCamposEspecMedico, null, $sWhereEspecMedico );
	$rsEspecialidades      = db_query($sSqlDadosProcedimento);
	if (pg_num_rows($rsEspecialidades) > 0) {

		db_fieldsmemory($rsEspecialidades, 0);
		$sd29_i_profissional = $oGet->iEspecialidade;
	}

  $sd03_i_codigo = $oGet->iProfissional;
  $z01_nome      = $oGet->sProfissional;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
<?php
try{
  db_app::load("estilos.css");
}catch (Exception $eException){
  die( $eException->getMessage() );
}
?>
</head>
<body class="body-default" >
  <div class="container">
    <?php
    require_once("forms/db_frmsau_triagemproc.php");
    ?>
  </div>

</body>
</html>
<script>
js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);
</script>
<?php
if( isset($incluir) || isset($alterar) ) {

  if ( $clprontproced->erro_status == "0" ) {

    $clprontproced->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $clprontproced->erro_campo != "" ) {

      echo "<script> document.form1.".$clprontproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontproced->erro_campo.".focus();</script>";
    }
  } else {

    $clprontproced->erro(true, false);
    db_redireciona("sau4_triagemproc001.php?chavepesquisaprontuario=$chavepesquisaprontuario");
  }
} else if ( isset($excluir) ) {

  if ( $clprontcid->erro_status == "0" ) {
  	$clprontcid->erro(true, false);
  } else if ( $clprontuarios->erro_status == "0" ) {
  	$clprontuarios->erro(true, false);
  } else {

    $clprontproced->erro(true, false);
    db_redireciona("sau4_triagemproc001.php?chavepesquisaprontuario=$chavepesquisaprontuario");
  }
} else if ( isset($prosseguir) ) {

	$clprontproced->sql_record( $cliframe_alterar_excluir->sql );

	if( $clprontproced->numrows > 0 ) {
		?>
			<script>
				parent.document.formaba.a4.disabled = false;
				parent.iframe_a4.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>&cgs=<?=$clprontuarios->sd24_i_numcgs?>&chaveprofissional=<?=@$sd29_i_profissional?>';
				parent.mo_camada('a4');
			</script>
		<?php
	}else{
    db_msgbox( _M( MENSAGEM_TRIAGEM_PROCEDIMENTO . "lancar_procedimento" ) );
	}
}
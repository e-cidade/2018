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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_utils.php");

$oPost = db_utils::postMemory($_POST);

$cl_db_config		    = new cl_db_config();
$clcadconvenio 	        = new cl_cadconvenio();
$clcadarrecadacao	    = new cl_cadarrecadacao();
$clcadtipoconvenio      = new cl_cadtipoconvenio();
$clconveniocobranca     = new cl_conveniocobranca();
$clconvenioarrecadacao  = new cl_convenioarrecadacao();
$clcadconveniogrupotaxa = new cl_cadconveniogrupotaxa;
$clGrupoTaxa            = new cl_grupotaxa;

$db_opcao = 2;
$db_botao = true;
$lSqlErro = false;
$sMsgErro = "";


if (isset($oPost->alterar)) {

  db_inicio_transacao();


  $lIncluiCobranca 	  = false;
  $lAlteraCobranca 	  = false;
  $lExcluiCobranca 	  = false;
  $lIncluiArrecadacao = false;
  $lAlteraArrecadacao = false;
  $lExcluiArrecadacao = false;


  $sSqlCadconveniogrupotaxa = $clcadconveniogrupotaxa->sql_query_file(null, "ar39_sequencial", null, "ar39_cadconvenio = {$ar11_sequencial}" );
  $rsCadconveniogrupotaxa   = $clcadconveniogrupotaxa->sql_record($sSqlCadconveniogrupotaxa);
  if ($clcadconveniogrupotaxa->numrows > 0) {

  	 db_fieldsmemory($rsCadconveniogrupotaxa,0);
  }
 // echo $ar39_sequencial; die();
  if ($oPost->ar11_cadtipoconvenio == 3 || $oPost->ar11_cadtipoconvenio == 4 ) {

  	if ( $oPost->ar11_cadtipoconvenio == 3 ) {
  	  $sWhere   = "     ar11_instit 		 = ".db_getsession('DB_instit');
  	  $sWhere  .= " and ar11_cadtipoconvenio = 3 ";
  	  $sWhere  .= " and ar11_sequencial     != ".$oPost->ar11_sequencial;
	  $sMsgTipo = "ARRECADAÇÃO";
  	} else {
  	  $sWhere   = "     ar11_instit 		 = ".db_getsession('DB_instit');
  	  $sWhere  .= " and ar11_cadtipoconvenio = 4 ";
  	  $sWhere  .= " and ar11_cadtipoconvenio = 3 ";
	  $sWhere  .= " and ar11_sequencial     != ".$oPost->ar11_sequencial;
  	  $sMsgTipo = "CAIXA PADRÃO";
  	}

  	$rsConsultaConvenios = $clcadconvenio->sql_record($clcadconvenio->sql_query(null,"*",null,$sWhere));

  	if ( $clcadconvenio->numrows > 0) {
  	  $lSqlErro = true;
  	  $sMsgErro = " Já possui convenio do tipo {$sMsgTipo} cadastrado !";
  	}
  }


  if ( !$lSqlErro ) {

	  $rsConsultaModalidade = $clcadtipoconvenio->sql_record($clcadtipoconvenio->sql_query($oPost->ar11_cadtipoconvenio,"ar12_cadconveniomodalidade"));
	  $oModalidade 			= db_utils::fieldsMemory($rsConsultaModalidade,0);

	  $rsConsultaModalidadeAtual = $clcadconvenio->sql_record($clcadconvenio->sql_query($oPost->ar11_sequencial,"ar15_sequencial"));
	  $oModalidadeAtual		   = db_utils::fieldsMemory($rsConsultaModalidadeAtual,0);

	  $clcadconvenio->ar11_cadtipoconvenio = $oPost->ar11_cadtipoconvenio;
	  $clcadconvenio->ar11_instit		   = db_getsession('DB_instit');
	  $clcadconvenio->ar11_nome			   = $oPost->ar11_nome;
	  $clcadconvenio->alterar($oPost->ar11_sequencial);

	  if ( $clcadconvenio->erro_status == 0 ) {
	  	$sMsgErro = $clcadconvenio->erro_msg;
	  	$lSqlErro = true;
	  }

	  if (!$lSqlErro) {

	   	if ($oModalidade->ar12_cadconveniomodalidade == $oModalidadeAtual->ar15_sequencial ) {

			  if ($oModalidadeAtual->ar15_sequencial == "1") {
			   	$lAlteraCobranca = true;
			  } else if ($oModalidadeAtual->ar15_sequencial == "2") {
			   	$lAlteraArrecadacao = true;
			  }

	   	} else {

	  	  if ($oModalidade->ar12_cadconveniomodalidade == "1") {
		   		if ($oModalidadeAtual->ar15_sequencial == "2") {
		   		  $lExcluiArrecadacao = true;
		   		  $lIncluiCobranca    = true;
		   		} else {
		   		  $lIncluiCobranca    = true;
		   		}
	   	  } else if ($oModalidade->ar12_cadconveniomodalidade == "2") {
	   	  	if ($oModalidadeAtual->ar15_sequencial == "1") {
		   		  $lExcluiCobranca    = true;
		   		  $lIncluiArrecadacao = true;
		   		} else {
		   		  $lIncluiArrecadacao = true;
		   		}
	   	  } else {
	   	  	if ($oModalidadeAtual->ar15_sequencial == "1") {
	   		    $lExcluiCobranca    = true;
	   	  	} else if ($oModalidadeAtual->ar15_sequencial == "2") {
			      $lExcluiArrecadacao = true;
	   	  	}
	   	  }

	   	}

	  }

	  if ($lExcluiArrecadacao) {
	    $clconvenioarrecadacao->excluir(null," ar14_cadconvenio = ".$oPost->ar11_sequencial);
	  	if ($clconvenioarrecadacao->erro_status == 0) {
		  $sMsgErro = $clconvenioarrecadacao->erro_msg;
		  $lSqlErro = true;
	  	}

	  }

	  if ($lExcluiCobranca) {
		$clconveniocobranca->excluir(null," ar13_cadconvenio = ".$oPost->ar11_sequencial);
	  	if ($clconveniocobranca->erro_status == 0) {
		  $sMsgErro = $clconveniocobranca->erro_msg;
		  $lSqlErro = true;
	    }
	  }

	  if ($lAlteraArrecadacao) {
	    $clconvenioarrecadacao->alterar($oPost->ar14_sequencial);
	  	if ($clconvenioarrecadacao->erro_status == 0) {
		  $sMsgErro = $clconvenioarrecadacao->erro_msg;
		  $lSqlErro = true;
	  	}
	  }

	  if ($lAlteraCobranca) {
            if ($oPost->ar11_cadtipoconvenio == 6) {

              $clconveniocobranca->ar13_responsavelnossonumero = 't';

              if (in_array($oPost->ar13_carteira, array(11, 21))) {
                $clconveniocobranca->ar13_responsavelnossonumero = 'f';
              }
            }

		$clconveniocobranca->alterar($oPost->ar13_sequencial);
	  	if ($clconveniocobranca->erro_status == 0) {
		  $sMsgErro = $clconveniocobranca->erro_msg;
		  $lSqlErro = true;
	    }
	  }

	  if ($lIncluiArrecadacao) {

	  	$rsCadArrecacadao = $clcadarrecadacao->sql_record($clcadarrecadacao->sql_query_file(null,"ar16_sequencial",null," ar16_instit = ".db_getsession('DB_instit')));

	  	if ( $clcadarrecadacao->numrows > 0 ) {
	  	  $oCadArrecadacao  = db_utils::fieldsMemory($rsCadArrecacadao,0);
	    } else {
	      $lSqlErro = true;
	      $sMsgErro = "Configurar convênio arrecadação!";
	    }

	    if (!$lSqlErro) {
	      $clconvenioarrecadacao->ar14_cadarrecadacao = $oCadArrecadacao->ar16_sequencial;
	  	  $clconvenioarrecadacao->ar14_cadconvenio    = $clcadconvenio->ar11_sequencial;
	  	  $clconvenioarrecadacao->incluir(null);

	  	  if ($clconvenioarrecadacao->erro_status == 0) {
		    $sMsgErro = $clconvenioarrecadacao->erro_msg;
		    $lSqlErro = true;
	  	  }
	    }
	  }

	  if ($lIncluiCobranca) {

	    $clconveniocobranca->ar13_cadconvenio = $clcadconvenio->ar11_sequencial;
	    $clconveniocobranca->incluir(null);

	    if ($clconveniocobranca->erro_status == 0) {
		   $sMsgErro = $clconveniocobranca->erro_msg;
		   $lSqlErro = true;
	  	}

	  }
  }

  if(!empty($oPost->ar37_sequencial)) {
  	$clcadconveniogrupotaxa->ar39_cadconvenio = $ar11_sequencial;
	  $clcadconveniogrupotaxa->ar39_grupotaxa   = $ar37_sequencial;

	  if($clcadconveniogrupotaxa->numrows > 0) {
	  	$clcadconveniogrupotaxa->ar39_sequencial = $ar39_sequencial;
	  	$clcadconveniogrupotaxa->alterar($clcadconveniogrupotaxa->ar39_sequencial);
	  } else {
	  	$clcadconveniogrupotaxa->incluir(null);
	  }

  	if($clcadconveniogrupotaxa->erro_status == "0") {
	    $sMsgErro = $clcadconveniogrupotaxa->erro_msg;
	    $lSqlErro = true;
	  }
  }

  db_fim_transacao($lSqlErro);

} else if (isset($chavepesquisa)) {

	$sSqlGrupoTaxa = $clcadconveniogrupotaxa->sql_query_file(null, "ar39_grupotaxa ", null, "ar39_cadconvenio = {$chavepesquisa}" );
	$rsGrupoTaxa   = $clcadconveniogrupotaxa->sql_record($sSqlGrupoTaxa);
	if ($clcadconveniogrupotaxa->numrows > 0) {

		db_fieldsmemory($rsGrupoTaxa, 0);
    	$ar37_sequencial = $ar39_grupotaxa;

		$sSqlDescrTaxa = $clGrupoTaxa->sql_query($ar37_sequencial,"ar37_descricao", null, null);
		$rsDescrTaxa   = $clGrupoTaxa->sql_record($sSqlDescrTaxa);
		db_fieldsmemory($rsDescrTaxa,0);
	}

	$sCampos  = "cadconvenio.*,                 ";
  $sCampos .= "conveniocobranca.*,            ";
	$sCampos .= "convenioarrecadacao.*,         ";
	$sCampos .= "a.db89_codagencia as agencia14,";
	$sCampos .= "b.db89_codagencia as agencia13 ";

  $rsConsultaConvenio = $clcadconvenio->sql_record($clcadconvenio->sql_query_arrecad_cobranc($chavepesquisa,$sCampos));

	db_fieldsmemory($rsConsultaConvenio,0);

	$rsConsultaConfig = $cl_db_config->sql_record($cl_db_config->sql_query_file(db_getsession('DB_instit'),"codigo,nomeinst"));
	$oConfig	      = db_utils::fieldsMemory($rsConsultaConfig,0);
	$nomeinst	 	    = $oConfig->nomeinst;
 	$ar11_instit 	  = $oConfig->codigo;

 	if ( $ar11_cadtipoconvenio == 5 ) {
 	  $ar13_carteira_selsicob = $ar13_carteira;
 	} else if ( $ar11_cadtipoconvenio == 6 ) {
 	  $ar13_carteira_selsigcb = $ar13_carteira;
 	}

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" onLoad="<?=(isset($chavepesquisa)?"js_validaTipo('{$ar11_cadtipoconvenio}')":"")?>">
    <?php
	    include modification("forms/db_frmcadconvenios.php");
      db_menu();
    ?>
  </body>
</html>
<?php
  if (isset($oPost->alterar)) {

  	if ($lSqlErro) {
  	  db_msgbox($sMsgErro);
  	  echo "<script>location.href = '';</script>";
  	} else {
	  $clcadconvenio->erro(true,true);
  	}

  } else if (!isset($chavepesquisa)) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }

?>

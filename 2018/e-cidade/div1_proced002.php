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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_proced_classe.php"));
require_once(modification("classes/db_tipoproced_classe.php"));
require_once(modification("classes/db_procedenciaagrupa_classe.php"));
require_once(modification("classes/db_procedarretipo_classe.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("classes/db_recparproc_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

$clproced					 = new cl_proced;
$cltipoproced      = new cl_tipoproced;
$clprocedAgrupa 	 = new cl_procedenciaagrupa;
$clrecparproc			 = new cl_recparproc;
$clprocedarretipo  = new cl_procedarretipo;
$clarretipo        = new cl_arretipo;
$somenteTipoReceitaPrincipal = true;

$sMessageAgrupa   = "";
$db_opcao         = 22;
$db_botao         = false;
$db_opcaoagrupa   = 22;
if (isset($alterar)) {


	db_inicio_transacao();
  $sqlerro	= false;
  $db_opcao = 2;
  $db_opcaoagrupa = 2;
  $clproced->v03_procedtipo = $v03_procedtipo;
  if ($v03_codigo == $v24_procedagrupa) {

    $sqlerro  = true;
    $erro_msg = "Procedência não pode ser agrupada com ela mesma!";

  }
  if (!$sqlerro) {

    $clproced->alterar($v03_codigo);
    $erro_msg = $clproced->erro_msg;
    if ($clproced->erro_status==0){
      $sqlerro = true;
    }

  }
  if ($sqlerro == false){

    $clrecparproc->excluir($v03_codigo);
    if ($clrecparproc->erro_status == 0){
      $sqlerro  = true;
      $erro_msg = $clrecparproc->erro_msg;
    }

  }

  if ($sqlerro == false and trim($receita) <> ""){
    $clrecparproc->receita	  = $receita;
    $clrecparproc->v03_codigo = $v03_codigo;
    $clrecparproc->incluir($v03_codigo);
    if ($clrecparproc->erro_status == 0){
      $sqlerro  = true;
      $erro_msg = $clrecparproc->erro_msg;
    }
  }

  if($sqlerro == false){
		$rsVerificaArretipo = $clprocedarretipo->sql_record($clprocedarretipo->sql_query_file(null,"*",null,"v06_proced = {$v03_codigo}"));
		if($clprocedarretipo->numrows > 0){
			$oArretipo = db_utils::fieldsMemory($rsVerificaArretipo,0);
			$clprocedarretipo->excluir(null,"v06_proced = {$oArretipo->v06_proced}");
			if($clprocedarretipo->erro_status == 0){
				$sqlerro  = true;
				$erro_msg = $clprocedarretipo->erro_msg;
			}
		}

		if($sqlerro == false && $v06_arretipo != 0){
			$clprocedarretipo->v06_proced   = $v03_codigo;
			$clprocedarretipo->v06_arretipo = $v06_arretipo;
			$clprocedarretipo->incluir(null);

			if($clprocedarretipo->erro_status == 0){
				$sqlerro  = true;
				$erro_msg = $clprocedarretipo->erro_msg;
			}
  	}
	}

	if (!$sqlerro && $v24_procedagrupa !== "") {

  	/**
     * Verificamos se a procedencia já está agrupando outras procedencias
     */
    $sSqlAgrupador =  $clprocedAgrupa->sql_query_agrupa(null,
                                                        "v24_procedagrupa, agrupa.v03_descr as v24_procedagrupadescr",
                                                         null,
                                                         "v24_proced = {$v24_procedagrupa}"
                                                         );

    $rsAgrupador  =  $clprocedAgrupa->sql_record($sSqlAgrupador);
    if ($clprocedAgrupa->numrows > 0) {

      $oProcedenciaAgrupa = db_utils::fieldsMemory($rsAgrupador, 0);
      $erro_msg = "Procendencia {$v24_procedagrupa} já está agrupada na procedência {$oProcedenciaAgrupa->v24_procedagrupa}";
      $sqlerro  = true;

    }
	}

	if (!$sqlerro) {
	  $clprocedAgrupa->excluir(null, "v24_proced = {$v03_codigo}");
	  if ($clprocedAgrupa->erro_status == 0) {

	    $sqlerro  = true;
      $erro_msg = $clprocedAgrupa->erro_msg;

	  }

	  if (!$sqlerro && $v24_procedagrupa != "") {

	    $clprocedAgrupa->v24_proced      = $v03_codigo;
	    $clprocedAgrupa->v24_procedgrupa = $v24_procedagrupa;
	    $clprocedAgrupa->incluir(null);
	    if ($clprocedAgrupa->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $clprocedAgrupa->erro_msg;

      }
	  }
	}

	db_fim_transacao($sqlerro);

}else if(isset($chavepesquisa)){

	$db_opcao       = 2;
	$db_opcaoagrupa = 2;

  $result   = $clproced->sql_record($clproced->sql_query($chavepesquisa));
	db_fieldsmemory($result,0);

	$result_recparproc = $clrecparproc->sql_record($clrecparproc->sql_query(null,"receita,k02_descr as descr_2",null,"recparproc.v03_codigo = $chavepesquisa"));
  if($clrecparproc->numrows != 0){
    db_fieldsmemory($result_recparproc,0);
  }

	$rsArretipo = $clprocedarretipo->sql_record($clprocedarretipo->sql_query(null,"v06_arretipo",null,"v06_proced = {$chavepesquisa}"));
  if($clprocedarretipo->numrows > 0){
    db_fieldsmemory($rsArretipo,0);
	}

  $sSqlProcedenciaAgrupar = $clprocedAgrupa->sql_query_agrupa(null,
                                                              "v24_procedagrupa, agrupa.v03_descr as v24_procedagrupadescr",
                                                              null,
                                                              "v24_proced = {$chavepesquisa}"
                                                              );
  $rsProcedenciaAgrupa = $clprocedAgrupa->sql_record($sSqlProcedenciaAgrupar);
  if ($clprocedAgrupa->numrows == 1) {
    db_fieldsmemory($rsProcedenciaAgrupa, 0);
  }

  /**
   * Verificamos se a procedencia já está agrupando outras procedencias
   */
  $sSqlAgrupador =  $clprocedAgrupa->sql_query_agrupa(null,
                                                      "v24_procedagrupa, agrupa.v03_descr as v24_procedagrupadescr",
                                                       null,
                                                       "v24_procedagrupa = {$chavepesquisa}"
                                                       );

  $rsAgrupador  =  $clprocedAgrupa->sql_record($sSqlAgrupador);
  if ($clprocedAgrupa->numrows > 0) {
    $db_opcaoagrupa = 3;
  }
	$db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  onLoad="a=1" >

	<?
	include(modification("forms/db_frmproced.php"));
	?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clproced->erro_status=="0"||$sqlerro==true){
    //$clproced->erro(true,false);
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clproced->erro_campo!=""){
      echo "<script> document.form1.".$clproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clproced->erro_campo.".focus();</script>";
    };
  }else{
    $clproced->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>

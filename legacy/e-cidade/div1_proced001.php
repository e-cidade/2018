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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_proced_classe.php"));
include(modification("classes/db_procedarretipo_classe.php"));
include(modification("classes/db_arretipo_classe.php"));
include(modification("classes/db_recparproc_classe.php"));
include(modification("classes/db_recparprocdiver_classe.php"));
include(modification("classes/db_procedenciaagrupa_classe.php"));
include(modification("classes/db_tipoproced_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);

$clrecparproc			 = new cl_recparproc;
$clrecparprocdiver = new cl_recparprocdiver;
$clproced					 = new cl_proced;
$cltipoproced      = new cl_tipoproced;
$clprocedarretipo  = new cl_procedarretipo;
$clarretipo				 = new cl_arretipo;
$clprocedAgrupa    = new cl_procedenciaagrupa;
$db_opcao = 1;
$db_opcaoagrupa = 1;
$db_botao = true;
$somenteTipoReceitaPrincipal = true;


if(isset($incluir)) {

	//echo $v03_procedtipo; die();

  db_inicio_transacao();
  $sqlerro = false;


	$clproced->v03_instit = db_getsession('DB_instit');
	$clproced->v03_procedtipo = $v03_procedtipo;
  $clproced->incluir(null);
  $erro_msg = $clproced->erro_msg;
  if ($clproced->erro_status == 0){
		$sqlerro = true;
  }
  if ($sqlerro == false){
    $clrecparproc->receita		= $receita;
    $clrecparproc->v03_codigo	= $v03_codigo;
    $clrecparproc->incluir($clproced->v03_codigo);
    if ($clrecparproc->erro_status == 0){
      $sqlerro	= true;
      $erro_msg = $clrecparproc->erro_msg;
		}
  }

  if ($sqlerro == false){
		if($v06_arretipo != 0 ){
			$clprocedarretipo->v06_proced   = $clproced->v03_codigo;
			$clprocedarretipo->v06_arretipo = $v06_arretipo;
			$clprocedarretipo->incluir(null);

			if($clprocedarretipo->erro_status == 0){
				$sqlerro	= true;
				$erro_msg = $clprocedarretipo->erro_msg;
			}
		}
	}

 if (!$sqlerro && $v24_procedagrupa != "") {

      $clprocedAgrupa->v24_proced      = $clproced->v03_codigo;
      $clprocedAgrupa->v24_procedgrupa = $v24_procedagrupa;
      $clprocedAgrupa->incluir(null);
      if ($clprocedAgrupa->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $clprocedAgrupa->erro_msg;

      }
    }
/*
  if ($receitad != "") {
	$clrecparprocdiver->receita=$receitad;
	$clrecparprocdiver->incluir(@$procdiver);
  }
*/
	db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC onLoad="a=1" >


<?
	include(modification("forms/db_frmproced.php"));
?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
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
?>

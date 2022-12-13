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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubase_classe.php");
include("classes/db_obraslote_classe.php");
include("classes/db_obraspropri_classe.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_parprojetos_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_obrashabiteprot_classe.php");
include("classes/db_obrashabiteprotoff_classe.php");

db_postmemory($HTTP_POST_VARS);

$cliptubase						= new cl_iptubase;
$clobraslote					= new cl_obraslote;
$clparprojetos				= new cl_parprojetos;
$clobraspropri				= new cl_obraspropri;
$clobrashabite			  = new cl_obrashabite;
$clobrashabiteprot	  = new cl_obrashabiteprot;
$clobrashabiteprotoff = new cl_obrashabiteprotoff;

$db_opcao = 1;
$db_botao = true;
$lLimpa   = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){

	$rsParProjetos = $clparprojetos->sql_record($clparprojetos->sql_query_file(db_getsession('DB_anousu')));

	if ($clparprojetos->numrows > 0) {

		$sqlerro = false;

		db_inicio_transacao();

		$clobrashabite->incluir($ob09_codhab);

		if($clobrashabite->erro_status == 0){
				$erro = $clobrashabite->erro_msg;
				db_msgbox($erro);
				$sqlerro = true;
		}

		$oParProjetos = db_utils::fieldsMemory($rsParProjetos,0);

		if ($oParProjetos->ob21_numeracaohabite == 2 ) {
		    $clparprojetos->ob21_ultnumerohabite = $oParProjetos->ob21_ultnumerohabite + 1;
			$clparprojetos->ob21_anousu = db_getsession('DB_anousu');
		    $clparprojetos->alterar(db_getsession('DB_anousu'));
			if($clparprojetos->erro_status == 0){
					$erro = $clparprojetos->erro_msg;
					db_msgbox($erro);
					$sqlerro = true;
			}
		}

		if ($iValSis == 1) {

			if(isset($ob19_codproc) && $ob19_codproc != ""){
					$clobrashabiteprot->ob19_codproc = $ob19_codproc;
					$clobrashabiteprot->ob19_codhab  = $clobrashabite->ob09_codhab;
					$clobrashabiteprot->incluir();

					if($clobrashabiteprot->erro_status == 0){
						 $erro = $clobrashabiteprot->erro_msg;
						 db_msgbox($erro);
						 $sqlerro = true;
					}
			}

		}else{

			$clobrashabiteprotoff->ob22_codhab  = $clobrashabite->ob09_codhab;
			$clobrashabiteprotoff->ob22_codproc = $ob22_codproc;
			$clobrashabiteprotoff->ob22_titular = $ob22_titular;
			$clobrashabiteprotoff->ob22_data    = $ob22_data_ano."-".$ob22_data_mes."-".$ob22_data_dia;
			$clobrashabiteprotoff->incluir(null);
			if($clobrashabiteprotoff->erro_status == 0){
				$erro = $clobrashabiteprotoff->erro_msg;
				db_msgbox($erro);
				$sqlerro = true;
			}
		}

	db_fim_transacao($sqlerro);

	}else{

	  $oParms          = new stdClass();
	  $oParms->iAnoUsu = db_getsession('DB_anousu');
	  db_msgbox(_M('tributario.projetos.db_frmobrashabite.paramentro_nao_configurado', $oParms));
    //db_msgbox('Não está configurado os parametros do módulo projetos para o exercício: '.db_getsession('DB_anousu').'!');
	  $sqlerro = true;
	}


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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
				<?
					include("forms/db_frmobrashabite.php");
				?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){
  if($clobrashabite->erro_status=="0"){
    $clobrashabite->erro(true,false);
    $db_botao=true;

		echo " <script>";
    echo "  document.form1.db_opcao.disabled=false;";

		if ($iValSis == 2) {
			echo "  document.getElementById('procManual').style.display  = '';		  ";
			echo "  document.getElementById('procSistema').style.display = 'none'; ";
 	  }else{
			echo "  document.getElementById('procManual').style.display  = 'none'; ";
			echo "  document.getElementById('procSistema').style.display = '';		  ";
  	}
    echo " </script>";

		if($clobrashabite->erro_campo!=""){
      echo "<script> document.form1.".$clobrashabite->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobrashabite->erro_campo.".focus();</script>";
    };
  }else{
    $clobrashabite->erro(true,true);
  };
};
?>
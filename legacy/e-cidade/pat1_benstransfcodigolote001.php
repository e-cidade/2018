<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_benstransf_classe.php"));
require_once(modification("classes/db_benstransfdes_classe.php"));
require_once(modification("classes/db_benstransfcodigo_classe.php"));
require_once(modification("classes/db_benstransfdiv_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clbenstransf             = new cl_benstransf();
$clbenstransfdes          = new cl_benstransfdes();
$clbenstransfcodigo       = new cl_benstransfcodigo;
$clbenstransfdiv          = new cl_benstransfdiv;
$oDaoBensTransfOrigemDestino = new cl_benstransforigemdestino();
$db_opcao                 = 22;
$db_botao                 = false;

if( isset($oPost->transf) ){

  $lSqlErro = false;

  db_inicio_transacao();


  $aListaBens = explode(",", $oPost->lista);


  $rsConsultaTransfDestino = $clbenstransfdes->sql_record($clbenstransfdes->sql_query($oPost->t95_codtran));

  $rsConsultaTranfDiv = $clbenstransfdiv->sql_record($clbenstransfdiv->sql_query_file(null,"*",null,"t31_codtran = $oPost->t95_codtran"));

  if ( $clbenstransfdiv->numrows > 0 ) {
  	$clbenstransfdiv->excluir(null,"t31_codtran = $oPost->t95_codtran");
  	if ($clbenstransfdiv->erro_status==0){
      $lSqlErro = true;
    }
    $sMsgErro = $clbenstransfdiv->erro_msg;
  }
  if (!$lSqlErro) {

    $oDaoBensTransfOrigemDestino->excluir(null, "t34_transferencia={$oPost->t95_codtran}");
    if ($oDaoBensTransfOrigemDestino->erro_status == 0) {

      $lSqlErro = true;
      $sMsgErro = $oDaoBensTransfOrigemDestino->erro_msg;
    }
  }
  if ( !$lSqlErro ) {

	  $rsConsultaTransfCodigo = $clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file($oPost->t95_codtran));

	  if ( $clbenstransfcodigo->numrows > 0 ) {
	  	$clbenstransfcodigo->excluir($oPost->t95_codtran);
	    if($clbenstransfcodigo->erro_status==0){
	      $lSqlErro = true;
	    }
	    $sMsgErro = $clbenstransfcodigo->erro_msg;
	  }
  }


  if ( !$lSqlErro ) {
  	if ( $clbenstransfdes->numrows > 0 ) {
	  	$oTransfDes = db_utils::fieldsMemory($rsConsultaTransfDestino,0);

	    if ( trim($oTransfDes->t94_divisao) != "" && $oTransfDes->t94_divisao != 0 )  {

	    	foreach ( $aListaBens as $iInd => $sDadosBem ) {

	    		$aBem     = explode("|",$sDadosBem);
	    		$iCodBem  = $aBem[0];
	    		$iSitucao = $aBem[1];

	  		  $clbenstransfdiv->t31_codtran = $oPost->t95_codtran;
	  		  $clbenstransfdiv->t31_bem     = $iCodBem;
	  		  $clbenstransfdiv->t31_divisao = $oTransfDes->t94_divisao;
	  		  $clbenstransfdiv->incluir(null);

	  	   	if ($clbenstransfdiv->erro_status==0){
	      		$lSqlErro = true;
	        }

	        $sMsgErro = $clbenstransfdiv->erro_msg;
          if (!$lSqlErro) {

            $oDaoBensTransfOrigemDestino->t34_transferencia       = $oPost->t95_codtran;
            $oDaoBensTransfOrigemDestino->t34_bem                 = $iCodBem;
            $oDaoBensTransfOrigemDestino->t34_divisaoorigem       = !empty($oTransfDes->t93_divisao) ? $oTransfDes->t93_divisao : null;
            $oDaoBensTransfOrigemDestino->t34_divisaodestino      = !empty($oTransfDes->t94_divisao) ? $oTransfDes->t94_divisao : null;
            $oDaoBensTransfOrigemDestino->t34_departamentoorigem  = $oTransfDes->t93_depart;
            $oDaoBensTransfOrigemDestino->t34_departamentodestino = $oTransfDes->t94_depart;
            $oDaoBensTransfOrigemDestino->incluir(null);
            if ($oDaoBensTransfOrigemDestino->erro_status == "0") {

              $sMsgErro = $oDaoBensTransfOrigemDestino->erro_msg;
              $lSqlErro = true;
            }
          }
	    	}
	  	}


		  if( !$lSqlErro ){

		  	foreach ( $aListaBens as $iInd => $sDadosBem ){

		  		$aBem     = explode("|",$sDadosBem);
		      $iCodBem  = $aBem[0];
		      $iSitucao = $aBem[1];

		  	  $clbenstransfcodigo->t95_codbem  = $iCodBem;
		  	  $clbenstransfcodigo->t95_codtran = $oTransfDes->t93_codtran;
		  	  $clbenstransfcodigo->t95_histor  = $oTransfDes->t93_obs;
		  	  $clbenstransfcodigo->t95_situac  = (trim($iSitucao) != ""?$iSitucao:4);

		      $clbenstransfcodigo->incluir($oTransfDes->t93_codtran,$iCodBem);

		      if($clbenstransfcodigo->erro_status==0){
		        $lSqlErro = true;
		      }

		      $sMsgErro = $clbenstransfcodigo->erro_msg;

		  	}
		  }
	  }
  }

  db_fim_transacao($lSqlErro);

} else if ( isset($oGet->iCodTransf) && trim($oGet->iCodTransf) != ""  ) {

	$t95_codtran = $oGet->iCodTransf;

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript"       type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript"       type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript"       type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos.css"            rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;<?=(!isset($oPost->transf)?"js_pesquisaBens();":"")?>" >
<table align="center" style="padding-top:15px;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" bgcolor="#CCCCCC">
    <center>
			<?
		  	include(modification("forms/db_frmbenstransfcodigolote.php"));
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if( isset($oPost->transf) ){

	db_msgbox($sMsgErro);

  if( $lSqlErro ){
      echo "<script> document.form1.".$clbenstransfcodigo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenstransfcodigo->erro_campo.".focus();</script>";
  }else{
      echo "<script>
              js_pesquisaBens();
            </script>";
  }
}
?>
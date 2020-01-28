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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_propri_classe.php"));
require_once(modification("classes/db_itbipropriold_classe.php"));
require_once(modification("classes/db_itbi_classe.php"));
require_once(modification("classes/db_itbilogin_classe.php"));
require_once(modification("classes/db_itbinome_classe.php"));
require_once(modification("classes/db_itbinomecgm_classe.php"));
require_once(modification("classes/db_itbimatric_classe.php"));
require_once(modification("classes/db_itburbano_classe.php"));
require_once(modification("classes/db_itbirural_classe.php"));
require_once(modification("classes/db_itbiruralcaract_classe.php"));
require_once(modification("classes/db_itbidadosimovel_classe.php"));
require_once(modification("classes/db_itbidadosimovelsetorloc_classe.php"));
require_once(modification("classes/db_itbiformapagamentovalor_classe.php"));
require_once(modification("classes/db_localidaderural_classe.php"));
require_once(modification("classes/db_itbilocalidaderural_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$cliptubase                = new cl_iptubase();
$clpropri                  = new cl_propri();
$clitbi                    = new cl_itbi();
$clitbinome                = new cl_itbinome();
$clitbinomecgm             = new cl_itbinomecgm();
$clitbipropriold           = new cl_itbipropriold();
$clitbilogin               = new cl_itbilogin();
$clitbimatric              = new cl_itbimatric();
$clitbirural               = new cl_itbirural();
$clitbiruralcaract         = new cl_itbiruralcaract();
$clitburbano               = new cl_itburbano();
$clitbidadosimovel         = new cl_itbidadosimovel();
$clitbidadosimovelsetorloc = new cl_itbidadosimovelsetorloc();
$clitbiformapagamentovalor = new cl_itbiformapagamentovalor();
$cllocalidaderural         = new cl_localidaderural();
$clitbilocalidaderural     = new cl_itbilocalidaderural();

$db_opcao = 2;
$db_botao = false;
$lSqlErro = false;

if(isset($oPost->alterar)){

  db_inicio_transacao();

  $clitbi->it01_areaterreno    = $oPost->it01_areaterreno;
  $clitbi->it01_areaedificada  = "0";
  $clitbi->it01_obs            = $oPost->it01_obs;
  $clitbi->it01_areatrans      = $oPost->it01_areatrans;
  $clitbi->it01_mail           = $oPost->it01_mail;
  $clitbi->it01_finalizado     = false;
  $clitbi->it01_origem         = 1;
  $clitbi->it01_id_usuario     = db_getsession('DB_id_usuario');
  $clitbi->it01_coddepto       = db_getsession('DB_coddepto');
  $clitbi->it01_data           = date('Y-m-d',db_getsession('DB_datausu'));
  $clitbi->it01_hora           = db_hora();

  $clitbi->alterar($oPost->it01_guia);

  if ( $clitbi->erro_status == 0 ) {
    $lSqlErro = true;
  }

  $sMsgErro = $clitbi->erro_msg;

  $oGet->tipo = $oPost->tipo;

  if($oPost->tipo == "urbano"){

    $clitburbano->it05_guia           = $oPost->it01_guia;
    $clitburbano->it05_frente         = $oPost->it05_frente;
    $clitburbano->it05_fundos         = $oPost->it05_fundos;
    $clitburbano->it05_esquerdo       = $oPost->it05_esquerdo;
    $clitburbano->it05_direito        = $oPost->it05_direito;
    $clitburbano->it05_itbisituacao   = $oPost->it05_itbisituacao;

    $clitburbano->alterar($oPost->it01_guia);

    if($clitburbano->erro_status == 0){
      $sMsgErro = $clitburbano->erro_msg;
    $lSqlErro = true;
    }

  } else if($oPost->tipo == "rural") {

	    $clitbirural->it18_guia        = $oPost->it01_guia;
	    $clitbirural->it18_frente      = $oPost->it18_frente;
	    $clitbirural->it18_fundos      = $oPost->it18_fundos;
	    $clitbirural->it18_prof        = $oPost->it18_prof;
	    $clitbirural->it18_localimovel = $oPost->it18_localimovel;
	    $clitbirural->it18_distcidade  = $oPost->it18_distcidade;

		  if ( $oPost->lFrenteLogradouro == 'n' ) {
		      $clitbirural->it18_nomelograd  = " ";
		  } else {
		    $clitbirural->it18_nomelograd  = $oPost->it18_nomelograd;
		  }

	    $clitbirural->it18_area      = $oPost->it01_areaterreno;

	    $clitbirural->alterar($oPost->it01_guia);

	    // Excluimos todas as localidades e apos inserimos a nova
	    $clitbilocalidaderural->excluir('',' it33_guia = ' . $clitbi->it01_guia );

	    $clitbilocalidaderural->it33_guia 					 = $clitbi->it01_guia;
	    $clitbilocalidaderural->it33_localidaderural = $oPost->j137_sequencial;
	    $clitbilocalidaderural->incluir( null );

	    if ( $clitbirural->erro_status == 0 ) {
	      $lSqlErro = true;
	    }

	    $sMsgErro   = $clitbirural->erro_msg;

		  if ( !$lSqlErro ) {

		    $aListaCaracImovel = explode("|",$oPost->valorCaracImovel);
		    foreach ( $aListaCaracImovel as $aChave) {

		      $aListaDadosCaracImovel = split("X", $aChave);

		      $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query_file($oPost->it01_guia, $aListaDadosCaracImovel[0]));
		      $clitbiruralcaract->it19_guia   = $oPost->it01_guia;
		      $clitbiruralcaract->it19_codigo = isset($aListaDadosCaracImovel[0]) ? $aListaDadosCaracImovel[0] : 0 ;
		      $clitbiruralcaract->it19_valor  = isset($aListaDadosCaracImovel[1]) ? $aListaDadosCaracImovel[1] : 0 ;
		      $clitbiruralcaract->it19_tipocaract = 1;
		      if ($clitbiruralcaract->numrows > 0) {
		       $clitbiruralcaract->alterar($oPost->it01_guia,$aListaDadosCaracImovel[0], 1);
		      } else {
		      	$clitbiruralcaract->incluir($oPost->it01_guia,$aListaDadosCaracImovel[0]);
		      }
		      if ( $clitbiruralcaract->erro_status == 0 ) {
		        $sMsgErro = $clitbiruralcaract->erro_msg;
		        $lSqlErro = true;
		        break;
		      }
	      }
		  }

	    if ( !$lSqlErro ) {

	    $aListaCaracUtil = explode("|",$oPost->valorCaracUtil);
	    foreach ( $aListaCaracUtil as $aChave) {

	      $aListaDadosCaracUtil = split("X",$aChave);

	      $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query_file($oPost->it01_guia, $aListaDadosCaracUtil[0]));
	      $clitbiruralcaract->it19_guia   = $oPost->it01_guia;
        $clitbiruralcaract->it19_codigo = isset($aListaDadosCaracUtil[0]) ? $aListaDadosCaracUtil[0] : 0 ;
        $clitbiruralcaract->it19_valor  = isset($aListaDadosCaracUtil[1]) ? $aListaDadosCaracUtil[1] : 0 ;
        $clitbiruralcaract->it19_tipocaract = '2';

	      if ($clitbiruralcaract->numrows > 0) {
	        $clitbiruralcaract->alterar($oPost->it01_guia,$aListaDadosCaracUtil[0], 2);
	      } else {
	      	$clitbiruralcaract->incluir($oPost->it01_guia,$aListaDadosCaracUtil[0]);
	      }

		    if ( $clitbiruralcaract->erro_status == 0 ) {
   	      $sMsgErro = $clitbiruralcaract->erro_msg;
		      $lSqlErro = true;
		      break;
		    }

	    }
	  }
  }

  $rsDadosImovel = $clitbidadosimovel->sql_record($clitbidadosimovel->sql_query_file(null,"*",null," it22_itbi = {$oPost->it01_guia}"));
  $iDadosImovel  = $clitbidadosimovel->numrows;

  $clitbidadosimovel->it22_sequencial  = $oPost->it22_sequencial;
  $clitbidadosimovel->it22_itbi        = $oPost->it01_guia;
  $clitbidadosimovel->it22_setor       = $oPost->it22_setor;
  $clitbidadosimovel->it22_quadra      = $oPost->it22_quadra;
  $clitbidadosimovel->it22_lote        = $oPost->it22_lote;
  $clitbidadosimovel->it22_descrlograd = $oPost->it22_descrlograd;
  $clitbidadosimovel->it22_numero      = $oPost->it22_numero;
  $clitbidadosimovel->it22_compl       = $oPost->it22_compl;
  $clitbidadosimovel->it22_matricri    = $oPost->it22_matricri;
  $clitbidadosimovel->it22_quadrari    = $oPost->it22_quadrari;
  $clitbidadosimovel->it22_loteri      = $oPost->it22_loteri;

  if ( $iDadosImovel > 0 ) {
  $clitbidadosimovel->alterar($clitbidadosimovel->it22_sequencial);
  } else {
    $clitbidadosimovel->incluir(null);
  }

  if ( $clitbidadosimovel->erro_status == 0 ) {
    $sMsgErro = $clitbidadosimovel->erro_msg;
    $lSqlErro = true;
  }

  db_fim_transacao($lSqlErro);

} else if (isset($oGet->chavepesquisa)) {

  /**
   * Consultamos as caracteristicas de imóvel da guia para iniciarmos o campo hidden do formulário(valorCaracImovel)
   */
  $sSql  = "select *                                                                 ";
  $sSql .= "  from caracter                                         ";
  $sSql .= "       inner join cargrup      on  cargrup.j32_grupo         = caracter.j31_grupo          ";
  $sSql .= "        left join itbiruralcaract     on  caracter.j31_codigo       = itbiruralcaract.it19_codigo   ";
  $sSql .= "        left join itbitipocaract on itbitipocaract.it31_sequencial = itbiruralcaract.it19_tipocaract ";
  $sSql .= " where it19_tipocaract = 1 and it19_guia = {$oGet->chavepesquisa}";

  $rsConsultaRuralCaract = db_query($sSql);

  if ( empty($rsConsultaRuralCaract) ) {
    echo "<script>alert('Erro ao buscar características de imóvel da guia');</script>";
  }

  $aConsultaRuralCaract = db_utils::getCollectionByRecord($rsConsultaRuralCaract);

  $sPrefixo         = "";
  $valorCaracImovel = "";

  foreach ($aConsultaRuralCaract as $iIndice => $oConsultaRuralCaract) {

    $valorCaracImovel .= $sPrefixo . $oConsultaRuralCaract->it19_codigo . "X" .  $oConsultaRuralCaract->it19_valor;
    $sPrefixo          = "|";
  }

   $db_opcao  = 2;
   $it22_itbi = $oGet->chavepesquisa;

   $rsDadosITBI = $clitbi->sql_record($clitbi->sql_query_dados($oGet->chavepesquisa,'*, it33_localidaderural as j137_sequencial, it33_localidaderural as j137_descricao'));

   if ($clitbi->numrows > 0) {
     db_fieldsmemory($rsDadosITBI,0);
     if ( !empty($it05_guia) ){
       $oGet->tipo = "urbano";
       $oPost->tipo = "urbano";
     } else {
       $oGet->tipo = "rural";
       $oPost->tipo = "rural";
     }
   }

   $db_botao = true;

      echo " <script>

              parent.document.formaba.dados.disabled    = false;
              parent.document.formaba.transm.disabled   = false;
              parent.document.formaba.compnome.disabled = false;
              parent.document.formaba.constr.disabled   = false;

              parent.iframe_transm.location.href   = 'itb1_itbinome001.php?tiponome=t&it03_guia=".$oGet->chavepesquisa."';
              parent.iframe_compnome.location.href = 'itb1_itbinomecomp001.php?tiponome=c&it03_guia=".$oGet->chavepesquisa."';
              parent.iframe_constr.location.href   = 'itb1_itbiconstr001.php?it08_guia=".$oGet->chavepesquisa."&tipo=".$oGet->tipo."';

            </script>";

} else {

  $db_opcao   = 22;
  $oGet->tipo = "urbano";
}

if (isset($oGet->alteraguialib)) {
  if ($oGet->alteraguialib != 2) {
    echo "<script>parent.mo_camada('datas');</script> ";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript"src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript"src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>

<?
  $sLogradouro             = @$it18_nomelograd;
  $sFuncaoFrenteLogradouro = '';
  if ($sLogradouro == " " || $sLogradouro == null ) {
  	$sFuncaoFrenteLogradouro = "js_frenteLogradouro('n');";
  }

?>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?=$sFuncaoFrenteLogradouro ?>" >
<form name="form1" method="post" action="">
  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
      <?
        include(modification("forms/db_frmitbilibdadosimovel.php"));
      ?>
    </td>
    </tr>
  </table>
</form>
</body>
</html>
<?
if(isset($oPost->alterar)){

  if( !$lSqlErro ){

    db_msgbox($sMsgErro);
    $db_botao = true;
    echo "<script>document.form1.db_opcao.disabled = false;</script>";

    if ($clitbidadosimovel->erro_campo != "") {
      echo "<script> document.form1.".$clitbidadosimovel->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbidadosimovel->erro_campo.".focus();</script>";
    }
  } else {
    db_msgbox($sMsgErro);
  }
}

if( $db_opcao == 22 ){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
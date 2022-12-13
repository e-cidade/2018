<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("libs/db_conecta.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_slip_classe.php");
include("classes/db_slipnum_classe.php");
include("classes/db_sliprecurso_classe.php");
include("classes/db_empparametro_classe.php");
require('model/agendaPagamento.model.php');

/**
 * Chamada de função criada para bloquear o acesso ao usuário no menu
 */
db_validarMenuPCASP(db_getsession("DB_itemmenu_acessado", false));

$clslip          = new cl_slip;
$clslipnum       = new cl_slipnum;
$clsliprecurso   = new cl_sliprecurso;
$clempparamentro = new cl_empparametro;
$oPost           = db_utils::postMemory($_POST);
db_postmemory($HTTP_POST_VARS);
// parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$db_erro = "";
$inclusao = false;
$db_opcao = 22;
$desabilitabotao=false;
if (isset($oPost->confirmar)) {

  db_inicio_transacao();
  $lSqlErro = false;

  /**
   * Verificamos se o slip não possui nenhuma configuração naagenda de pagamentos.
   *
   */
  $sSqlMov       = "select e97_codforma,";
  $sSqlMov      .= "       e96_descr,    ";
  $sSqlMov      .= "       e90_codgera, ";
  $sSqlMov      .= "       e81_codmov, ";
  $sSqlMov      .= "       e91_cheque ";
  $sSqlMov      .= "  from empageslip";
  $sSqlMov      .= "       inner join empagemov      on e89_codmov   = e81_codmov";
  $sSqlMov      .= "       inner join empagemovforma on e81_codmov   = e97_codmov";
  $sSqlMov      .= "       inner join empageforma    on e97_codforma = e96_codigo";
  $sSqlMov      .= "       left  join empageconfche  on e81_codmov   = e91_codmov and e91_ativo is true";
  $sSqlMov      .= "       left  join empageconfgera on e81_codmov   = e90_codmov";
  $sSqlMov      .= " where e89_codigo = {$oPost->numslip}";
  $sSqlMov      .= "   and e81_cancelado is null";
  $rsMov         = db_query($sSqlMov);
  if ($rsMov && pg_num_rows($rsMov) > 0) {

    $aMovimentos = db_utils::getColectionByRecord($rsMov);
    $sMsgErro    = "Não foi possível anular o slip {$oPost->numslip}!\\n";
    $sMsgErro   .= "Slip está com os seguintes movimentos configurados:\\n";
    $sVirgula    = "";
    foreach ($aMovimentos as $oMovimento) {

      if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque != "") {
        $sMsgErro .= " - {$oMovimento->e81_codmov}, Cheque {$oMovimento->e91_cheque}.\\n";
      } else if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque == "") {
        $sMsgErro .= " - {$oMovimento->e81_codmov}, configurado para emissão de cheque.\\n";
      } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera != "") {
        $sMsgErro .= " - {$oMovimento->e81_codmov}, no arquivo {$oMovimento->e90_codgera}.\\n";
      } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera == "") {
        $sMsgErro .= " - {$oMovimento->e81_codmov}, configurado para emissão de arquivo texto.\\n";
      } else {
        $sMsgErro .= " - {$oMovimento->e81_codmov}, configurado para {$oMovimento->e96_descr}.\\n";
      }
      $sVirgula = ", ";
    }
    $clslip->erro_status = 0;
    $clslip->erro_msg    = $sMsgErro;
    $lSqlErro            = true;
  }

  if (!$lSqlErro) {
    $oDaoPlaCaixaRecSlip = db_utils::getDao("placaixarecslip");
    $oDaoPlaCaixaRecSlip->excluir(null,"k110_slip = {$oPost->numslip}");
    if ( $oDaoPlaCaixaRecSlip->erro_status == 0 ) {
      $clslip->erro_status = 0;
      $clslip->erro_msg    = $oDaoPlaCaixaRecSlip->erro_msg;
      $lSqlErro            = true;
    }
  }

  if (!$lSqlErro) {
    $oDaorhSlipFolhaSlip = db_utils::getDao("rhslipfolhaslip");
  	$oDaorhSlipFolhaSlip->excluir(null,"rh82_slip = {$oPost->numslip}");
		if ( $oDaorhSlipFolhaSlip->erro_status == 0 ) {
      $clslip->erro_status = 0;
      $clslip->erro_msg    = $oDaorhSlipFolhaSlip->erro_msg;
      $lSqlErro            = true;
		}
  }

  if (!$lSqlErro) {

    $oDaoSlipAnul = db_utils::getDao("slipanul");
    $oDaoSlipAnul->k18_codigo = $oPost->numslip;
    $oDaoSlipAnul->k18_motivo = $oPost->k18_obs;
    $oDaoSlipAnul->incluir($oPost->numslip);
    if ($oDaoSlipAnul->erro_status == 0) {

      $clslip->erro_status = 0;
      $clslip->erro_msg    = $oDaoSlipAnul->erro_msg;
      $lSqlErro            = true;

    }
  }
  if (!$lSqlErro) {

    $clslip->k17_codigo   = $oPost->numslip;
    $clslip->k17_situacao = 4;
    $clslip->k17_dtanu    = date("Y-m-d",db_getsession("DB_datausu"));
    $clslip->alterar($oPost->numslip);
    if ($clslip->erro_status == 0) {
      $lSqlErro            = true;
    }
  }
  //Verificar se existe k108_slip na slipempagemovslips se existir excluir os registros
  $oDaoSlipMov  = db_utils::getDao("slipempagemovslips");
  $rsDaoSlipMov = $oDaoSlipMov->sql_record($oDaoSlipMov->sql_query_file(null,"*",null,"k108_slip = ".$oPost->numslip));
  if ($oDaoSlipMov->numrows > 0){
  	$oDaoSlipMov->excluir(null, "k108_slip = {$oPost->numslip}");
  	if ($oDaoSlipMov->erro_status == '0'){

  		$lErro    = true;
      $sMsgErro = $oDaoSlipMov->erro_msg;
      break;

  	}
  }

  $oDaoSlipCorrente = db_utils::getDao("slipcorrente");
  $sSqlCorrente     = $oDaoSlipCorrente->sql_query_file(null,"*", null,"k112_slip= {$oPost->numslip}");
  $rsCorrente       = $oDaoSlipCorrente->sql_record($sSqlCorrente);
  if ($oDaoSlipCorrente->numrows > 0) {

    $iNumRows = $oDaoSlipCorrente->numrows;
    for ($i = 0; $i < $iNumRows; $i++) {

      $oCorrente  = db_utils::fieldsMemory($rsCorrente, $i);
      $oDaoSlipCorrente->k112_ativo       = "false";
      $oDaoSlipCorrente->k112_sequencial  = $oCorrente->k112_sequencial;
      $oDaoSlipCorrente->alterar($oCorrente->k112_sequencial);
      if ($oDaoSlipCorrente->erro_status == 0) {

        $lErro    = true;
        $sMsgErro = $oDaoSlipCorrente->erro_msg;
        break;

      }
    }
  }
  db_fim_transacao($lSqlErro);
}
if (isset($chavepesquisa) && $chavepesquisa !="") {

    $db_opcao=3;
    $sql = $clslip->sql_query_alteracao(null, "slip.*,z01_numcgm, z01_nome,
                                               k13_descr as descrcredito,
                                               c60_descr as descrdebito,
                                              c50_codhist,c50_descr", "",
                                              " slip.k17_codigo = $chavepesquisa
                                              and k17_instit = " . db_getsession("DB_instit"));

    $result = $clslip->sql_record($sql);
    if($clslip->numrows > 0){
        db_fieldsmemory($result,0);
        $debito = $k17_debito;
        $credito = $k17_credito;
        $numslip = $k17_codigo;
        $codhist = $k17_hist;
        $altera = true;
     }else{
        db_msgbox("Slip não encontrado ! Verifique o número e instituição ou tente novamente !  ");
     }
}
$read_only = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" bgcolor="#CCCCCC">
    <?
  	  include("forms/db_frmslipanula.php");
    ?>
    </td>
  </tr>
</table>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($clslip->erro_status == 0 && isset($oPost->confirmar)){
    echo "<script>alert('".$clslip->erro_msg."')</script>";
} else if (isset($oPost->confirmar) && !$lSqlErro) {

  echo "<script>alert('Slip Anulado com sucesso');\n";
  echo "location.href='cai4_anularslip001.php'\n</script>";

}
?>
<script>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slipanular.php?funcao_js=parent.js_preenchepesquisa|k17_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_slip.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	?>
}


</script>
<?
if (isset($chavepesquisa) && $chavepesquisa!=""){
  echo "
       <script>
	  js_adiciona_linha(false,document.form1.debito.value);
	  document.form1.k17_valor.focus();
       </script>
       ";

} else {
  echo "<script>js_pesquisa()</script>";
}

?>
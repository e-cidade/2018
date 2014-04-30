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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ('libs/db_utils.php');
require_once ("classes/db_rechumano_classe.php");
require_once ("classes/db_rechumanoescola_classe.php");
require_once ("classes/db_rechumanoativ_classe.php");
require_once ("classes/db_rechumanohoradisp_classe.php");
require_once ("classes/db_regenciahorario_classe.php");
require_once ("classes/db_relacaotrabalho_classe.php");
require_once ("classes/db_escola_classe.php");
require_once ("classes/db_efetividade_classe.php");
require_once ("classes/db_efetividaderh_classe.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);

$clrechumano         = new cl_rechumano;
$clrechumanoativ     = new cl_rechumanoativ;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clregenciahorario   = new cl_regenciahorario;
$clrelacaotrabalho   = new cl_relacaotrabalho;
$clrechumanoescola   = new cl_rechumanoescola;
$clefetividade       = new cl_efetividade;
$clefetividaderh     = new cl_efetividaderh;
$clescola            = new cl_escola;
$db_opcao            = 1;
$db_botao            = true;


if (isset($oPost->opcao) && $oPost->opcao == 'excluir') {
	
	$sSqlExclusao = $clrechumanoescola->sql_query_file($oPost->ed75_i_codigo);
	$rsExclusao   = $clrechumanoescola->sql_record($sSqlExclusao);
	db_fieldsmemory($rsExclusao, 0);
}

if (isset($incluir)) {

  $clrechumanoescola->pagina_retorno = " edu1_rechumanoescola001.php?ed75_i_rechumano=$ed75_i_rechumano ";
  $sCamposRechumanoEscola            = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
  $sCamposRechumanoEscola           .= " cgmcgm.z01_nome end as z01_nome,ed18_c_nome";
  $sWhereRechumanoEscola             = " ed75_i_rechumano = $ed75_i_rechumano AND ed75_i_escola = $ed75_i_escola";
  $sWhereRechumanoEscola            .= " and ed75_i_saidaescola is  null"; 
  $sSqlRechumanoEscola               = $clrechumanoescola->sql_query("",$sCamposRechumanoEscola,"",$sWhereRechumanoEscola);
  $sResultRechumanoEscola            = $clrechumanoescola->sql_record($sSqlRechumanoEscola);
  
  
  if ($clrechumanoescola->numrows > 0) {

    db_fieldsmemory($sResultRechumanoEscola,0);
    db_msgbox("Recurso Humano $z01_nome já está vinculado a escola $ed18_c_nome");
    echo "<script>location.href='".$clrechumanoescola->pagina_retorno."'</script>";

  } else {
  	
    db_inicio_transacao();
    
    $clrechumanoescola->ed75_c_simultaneo = 'N';
    if (!empty($ed75_c_simultaneo)) {
    	$clrechumanoescola->ed75_c_simultaneo = 'S';
    } 
    
    $clrechumanoescola->incluir($ed75_i_codigo);

    $sWhere            = " ed75_i_escola = {$ed75_i_escola} and ed75_i_rechumano = {$ed75_i_rechumano}";
    $sSqlRecHumanoAtiv = $clrechumanoativ->sql_query(null, "ed22_i_codigo", null, $sWhere);
    $rsRecHumanoAtiv   = $clrechumanoativ->sql_record($sSqlRecHumanoAtiv);
    $iLinhas           = $clrechumanoativ->numrows;

    if ($iLinhas > 0) {
    	
    	for ($i = 0; $i < $iLinhas; $i ++) {
    		
    		$iCodigoAtiv = db_utils::fieldsMemory($rsRecHumanoAtiv, $i)->ed22_i_codigo;
    		$clrechumanoativ->ed22_i_rechumanoescola = $clrechumanoescola->ed75_i_codigo;
    		$clrechumanoativ->ed22_i_codigo = $iCodigoAtiv;
    		$clrechumanoativ->alterar($iCodigoAtiv);
    	}
    }    
    
    db_fim_transacao();

  }
}

if (isset($alterar)) {
	
	$clrechumanoescola->ed75_c_simultaneo = 'N';
	if (!empty($ed75_c_simultaneo)) {
		$clrechumanoescola->ed75_c_simultaneo = 'S';
	}
  $db_opcao = 1;
  db_inicio_transacao();
  $clrechumanoescola->alterar($ed75_i_codigo);
  
  db_fim_transacao();
}

if (isset($excluir)) {
	
	
  db_inicio_transacao();
  $db_opcao = 3;
  $sSql     = " SELECT ed33_i_codigo FROM rechumanohoradisp inner join periodoescola on ed17_i_codigo = ed33_i_periodo ";
  $sSql    .= " WHERE ed33_i_rechumano = $ed75_i_rechumano AND ed17_i_escola = $ed75_i_escola";
  $clrechumanohoradisp->excluir(""," ed33_i_codigo in ($sSql)");
  
  if ($clrechumanohoradisp->erro_status != "0") {
  	
  	$sSqlEfetividade  = " SELECT ed97_i_codigo FROM efetividade inner join efetividaderh on ed98_i_codigo = ed97_i_efetividaderh ";
  	$sSqlEfetividade .= " WHERE ed97_i_rechumano = $ed75_i_rechumano AND ed98_i_escola = $ed75_i_escola ";
  	$sSqlEfetividade .= " order by ed97_i_codigo desc limit 1";
    $clefetividade->excluir(""," ed97_i_codigo = ($sSqlEfetividade)");
    
    if ($clefetividade->erro_status == "0") {
       	                       
      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clefetividade->erro_msg;
                     
    }
  }         
  if ($clrechumanohoradisp->erro_status != "0") {
    $clrechumanoativ->excluir(""," ed22_i_rechumanoescola = $ed75_i_codigo");
    if ($clrechumanoativ->erro_status == "0") {
       	                       
      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clrechumanoativ->erro_msg;
                     
    }
  }         
  if ($clrechumanohoradisp->erro_status != "0") {
    $clrelacaotrabalho->excluir(""," ed23_i_rechumanoescola = $ed75_i_codigo");
    if ($clrelacaotrabalho->erro_status == "0") {
       	                       
      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clrelacaotrabalho->erro_msg;
                     
    }
  }     
  if ($clrechumanohoradisp->erro_status != "0") {
  	
  	$sWhereExclui = " ed75_i_escola = {$ed75_i_escola} and ed75_i_rechumano = {$ed75_i_rechumano} ";
  	
    $clrechumanoescola->excluir(null, $sWhereExclui);
    if ($clrechumanoescola->erro_status == "0") {
       	                       
      $clrechumanohoradisp->erro_status = "0";
      $clrechumanohoradisp->erro_msg    = $clrechumanoescola->erro_msg;
                     
    }
  }   
  db_fim_transacao($clrechumanohoradisp->erro_status=="0");
  $anoatual               = date("Y");
  $sCampos                = "DISTINCT ed57_c_descr,ed11_c_descr,ed10_c_descr,ed15_c_nome,ed15_i_sequencia";
  $sWhere                 = " ed58_i_rechumano = $ed75_i_rechumano AND ed52_i_ano = $anoatual ";
  $sWhere                .= " AND ed57_i_escola = $ed75_i_escola and ed58_ativo is true  ";
  $sSqlRegenciaHorario    = $clregenciahorario->sql_query("",$sCampos,"ed15_i_sequencia,ed57_c_descr",$sWhere);
  $sResultRegenciaHorario = $clregenciahorario->sql_record($sSqlRegenciaHorario);
  if ($clregenciahorario->numrows > 0) {
  	
    $mensagem  = "ATENÇÃO!\\n\\n Esta matrícula($ed75_i_rechumano) tem horário(s) marcado(s) na(s) turma(s) abaixo ";
    $mensagem .= "relacionada(s) nesta escola neste ano de $anoatual:\\n\\n";
    for ($r = 0; $r < $clregenciahorario->numrows; $r++) {
    	
      db_fieldsmemory($sResultRegenciaHorario,$r);
      $mensagem .= " -> Turma $ed57_c_descr, Etapa $ed11_c_descr - $ed10_c_descr, Turno $ed15_c_nome\\n";
      
    }
    
    $mensagem .= "\\n\\n Se estes horários marcados não forem excluídos das turmas, esta matrícula não poderá ser ";
    $mensagem .= " vinculada a outra turma em outra escola nos horários referentes no ano de $anoatual.";
    db_msgbox($mensagem);
    
  }
  $clrechumanoescola->erro(true,false);
  echo "<script>";
  echo "parent.location.href = 'edu1_rechumanoabas001.php'";
  echo "</script>";
  exit;
}
$sCampos          = " case when ed20_i_tiposervidor = 1 ";
$sCampos         .= "   then ed284_i_rhpessoal ";
$sCampos         .= "   else ed285_i_cgm ";
$sCampos         .= "   end as identificacao, ";
$sCampos         .= " case when ed20_i_tiposervidor = 1 ";
$sCampos         .= "   then cgmrh.z01_nome ";
$sCampos         .= "   else cgmcgm.z01_nome ";
$sCampos         .= " end as z01_nome, ";
$sCampos         .= " ed20_i_tiposervidor ";
$sSqlRechumano    = $clrechumano->sql_query("",$sCampos,""," ed20_i_codigo = $ed75_i_rechumano");
$sResultRechumano = $clrechumano->sql_record($sSqlRechumano);
db_fieldsmemory($sResultRechumano,0);
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
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
       <fieldset style="width:95%"><legend><b>Escolas em que o Recurso Humano trabalha: </b></legend>
        <?include("forms/db_frmrechumanoescola.php");?>
       </fieldset>
      </center>
     </td>
    </tr>
   </table>
  </body>
</html>
<script>
  js_tabulacaoforms("form1", "ed75_i_escola", true, 1, "ed75_i_escola", true);
</script>
<?
if (isset($incluir)) {

  if ($clrechumanoescola->erro_status == "0") {

    $clrechumanoescola->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clrechumanoescola->erro_campo != "") {

      echo "<script> document.form1.".$clrechumanoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrechumanoescola->erro_campo.".focus();</script>";

    }
  } else {
    $clrechumanoescola->erro(true,true);
  }
}

if(isset($alterar)){
	if($clrechumanoescola->erro_status=="0"){
		$clrechumanoescola->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clrechumanoescola->erro_campo!=""){
			echo "<script> document.form1.".$clrechumanoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clrechumanoescola->erro_campo.".focus();</script>";
		}
	}else{
		db_redireciona("edu1_rechumanoescola001.php?ed75_i_rechumano=$ed75_i_rechumano");
	}
}

if (isset($cancelar)) {

  $clrechumanoescola->pagina_retorno = "edu1_rechumanoescola001.php?ed75_i_rechumano=$ed75_i_rechumano";
  echo "<script>location.href='".$clrechumanoescola->pagina_retorno."'</script>";

}
?>
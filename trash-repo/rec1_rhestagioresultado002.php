<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhestagioresultado_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_portaria_classe.php");
include("classes/db_portariatipo_classe.php");
include("classes/db_assenta_classe.php");
include("classes/db_portariaassenta_classe.php");
include("classes/db_tipoasse_classe.php");

$oGet                 = db_utils::postMemory($_GET);
$oPost                = db_utils::postMemory($_POST);
$clrhestagioresultado = new cl_rhestagioresultado;
$clportaria           = new cl_portaria;
$classenta            = new cl_assenta;
$clportariaassenta    = new cl_portariaassenta;
$clportariatipo       = new cl_portariatipo;
$db_opcao             = 22;
$db_botao             = false;
$lSqlErro             = false;
if(isset($oPost->alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $rEstagio = $clrhestagioresultado->sql_record(
              $clrhestagioresultado->sql_query_resultado(null,"*",null,"h65_sequencial = {$oPost->h65_sequencial}"));
  $oEstagio = db_utils::fieldsMemory($rEstagio,0);  
  //Pegamos o tipo da portaria pelo resultado do estagio.
  if ($oPost->h65_resultado == "A"){
     $iTipoAsse = $oEstagio->h50_assentaaprova;
  }else if ($oPost->h65_resultado == "R"){
     $iTipoAsse = $oEstagio->h50_assentareprova;
  }
  $rPortaria = $clportariatipo->sql_record($clportariatipo->sql_query(null,"h30_sequencial,h30_amparolegal",null,
                                            " h30_tipoasse = {$iTipoAsse}"));
  if ($clportariatipo->numrows > 0 ){
      $oTipoPortaria = db_utils::fieldsMemory($rPortaria,0);
  }else{
     
     $lSqlErro = true;
     $sErroMsg = "Erro[1] - Tipo do assentamento ({$iTipoAsse}) sem tipo de portaria cadastrado.";  
  }
  $dataAux = explode("/",$oPost->h65_data);
  if (count($dataAux) != 3){
      
     $lSqlErro = true;
     $sErroMsg = "Erro[3] - data da portaria inválida.";  
      
  }
  if (!$lSqlErro){
  
     $sSQLDatas  = "select min(h64_data) as data_ini,";
     $sSQLDatas .= "       max(h64_data) as data_fim, ";
     $sSQLDatas .= "       (max(h64_data) - min(h64_data)) as tdias ";
     $sSQLDatas .= "  from rhestagioagendadata ";
     $sSQLDatas .= " where h64_estagioagenda = {$oEstagio->h57_sequencial}";
     $rDatas     = pg_query($sSQLDatas);
     $oDatas     = db_utils::fieldsMemory($rDatas,0);
     $dataInformada          = implode(array_reverse($dataAux));
     $classenta->h16_histor  = substr($oPost->h65_observacao,   0, 240);
     $classenta->h16_assent  = $iTipoAsse;
     $classenta->h16_nroport = "{$oPost->h31_numero}/{$dataAux[2]}";
     $classenta->h16_hist2   = substr($oPost->h65_observacao, 240, 240);
     $classenta->h16_dtlanc  = $dataInformada;
     $classenta->h16_dtterm  = $oDatas->data_fim;
     $classenta->h16_codigo  = $oEstagio->h16_codigo;
     $classenta->alterar($oEstagio->h16_codigo);
  }
  if (!$lSqlErro){

      $clportaria->h31_portariatipo = $oTipoPortaria->h30_sequencial;
      $clportaria->h31_amparolegal  = $oTipoPortaria->h30_amparolegal;
      $clportaria->h31_anousu       = $dataAux[2];
      $clportaria->h31_usuario      = db_getsession("DB_id_usuario");
      $clportaria->h31_numero       = $oPost->h31_numero;
      $clportaria->h31_dtportaria   = $dataInformada;
      $clportaria->h31_dtlanc       = $dataInformada;
      $clportaria->h31_dtinicio     = $dataInformada;
      $clportaria->h31_sequencial   = $oEstagio->h31_sequencial;
      $clportaria->alterar($oEstagio->h31_sequencial);
      if ($clportaria->erro_status == 0){

        $lSqlErro = true;
        $sErroMsg = "Erro[4] - Portaria não alterada.\\n{$clportaria->erro_msg}";  
      }
   }
  if (!$lSqlErro){
     $clrhestagioresultado->alterar($oPost->h65_sequencial);

  }
  if ($lSqlErro){
 
    $clrhestagioresultado->erro_status = "0";
  }
  db_fim_transacao($lSqlErro);
}else if(isset($oGet->chavepesquisa)){
    $db_opcao = 2;
    $campos     = "distinct h65_sequencial,h65_data,h65_observacao,h31_numero,";
    $campos    .= " h57_sequencial, h57_regist, h50_minimopontos,z01_nome, rh01_admiss,h55_nroaval,fc_calculapontosestagio(h57_sequencial,'t') as pontos";
    $rResultado = $clrhestagioresultado->sql_record(
                   $clrhestagioresultado->sql_query_resultado(null,"$campos",null,"h57_sequencial = {$oGet->chavepesquisa}"));
   if ($clrhestagioresultado->numrows > 0){

     $db_opcao            = 1;
     $oResultado          = db_utils::fieldsMemory($rResultado,0);
     $z01_nome            = $oResultado->z01_nome;
     $h65_rhestagioagenda = $oResultado->h57_sequencial;
     $h65_pontos          = $oResultado->pontos;
     if ($oResultado->h65_sequencial != null){

         $datap          = explode("-",$oResultado->h65_data);
         $h65_sequencial = $oResultado->h65_sequencial;
         $h65_data_dia   = $datap[2];
         $h65_data_mes   = $datap[1];
         $h65_data_ano   = $datap[0];
         $h65_observacao = $oResultado->h65_observacao;
         $h31_numero     = $oResultado->h31_numero;
         $db_opcao       = 2;
         $db_botao       = true;
         
      
     }
     if ($oResultado->h50_minimopontos > $oResultado->pontos){
        $h65_resultado = "R"; 
     }else{
        $h65_resultado = "A"; 
     }
   }else{
     db_msgbox('Nao foi encontrado dados do estágio ');
   }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhestagioresultado.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($oPost->alterar)){
  if($clrhestagioresultado->erro_status=="0"){
    db_msgbox($sErroMsg);
    $clrhestagioresultado->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clrhestagioresultado->erro_campo!=""){
      echo "<script> document.form1.".$clrhestagioresultado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhestagioresultado->erro_campo.".focus();</script>";
    }
  }else{
    $clrhestagioresultado->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","h65_rhestagioagenda",true,1,"h65_rhestagioagenda",true);
</script>
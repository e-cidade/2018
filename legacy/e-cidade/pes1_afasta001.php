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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libpessoal.php");
require_once("classes/db_afasta_classe.php");
require_once("classes/db_codmovsefip_classe.php");
require_once("classes/db_movcasadassefip_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("classes/db_rhpessoalmov_classe.php");
require_once("classes/db_pontofx_classe.php");
require_once("classes/db_pontofs_classe.php");
require_once("classes/db_rhrubricas_classe.php");
require_once("classes/db_inssirf_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clafasta          = new cl_afasta;
$clrhpessoal       = new cl_rhpessoal;
$clrhpessoalmov    = new cl_rhpessoalmov;
$clcodmovsefip     = new cl_codmovsefip;
$clmovcasadassefip = new cl_movcasadassefip;
$clpontofx         = new cl_pontofx;
$clpontofs         = new cl_pontofs;
$clrhrubricas      = new cl_rhrubricas;
$clinssirf         = new cl_inssirf;
$db_botao          = true;
$db_opcao          = 1;
$afasta            = " disabled ";
$aVerificaAfastamento = array();
$aRetornoNulo = array();

if (isset($incluir)) {

  db_inicio_transacao();

  $r45_dtafas = $r45_dtafas_ano."-".$r45_dtafas_mes."-".$r45_dtafas_dia;
  $r45_dtreto = null;
  $dbwhere1 = " r45_dtafas <= '".$r45_dtafas."' ";
  $dbwhere2 = " (r45_dtafas >= '".$r45_dtafas."' and r45_dtreto >= '".$r45_dtafas."') ";
  
  if(trim($r45_dtreto_ano) != "" && trim($r45_dtreto_mes) != "" && trim($r45_dtreto_dia) != ""){
    $r45_dtreto = $r45_dtreto_ano."-".$r45_dtreto_mes."-".$r45_dtreto_dia;
  }
  
  $sqlerro = false;
 
  //r45_dtreto
  $sSqlRetornoNulo  = $clafasta->sql_query_file(null, "r45_codigo", null, "r45_anousu = {$r45_anousu} and r45_mesusu = {$r45_mesusu} and r45_regist = {$r45_regist} and r45_dtreto is null ");
  $rsSqlRetornoNulo = $clafasta->sql_record($sSqlRetornoNulo);
  $aRetornoNulo     = db_utils::getCollectionByRecord($rsSqlRetornoNulo);
  if(count($aRetornoNulo) > 0){
    
    $sqlerro  = true;
    $erro_msg = "O Servidor já possui afastamento sem retorno \\n Verifique afastamentos Anteriores";
  }  
  
  //r45_dtreto
  $sWhereVerificaAfastamento = "r45_anousu = {$r45_anousu} and r45_mesusu = {$r45_mesusu} and r45_regist = {$r45_regist} ";
  $dtRetorno                 = "";
  $dtLancamento              = implode("-", array_reverse(explode("/",$r45_dtafas)));
  
  if($r45_dtreto != null || $r45_dtreto != ''){
  	
  	$dtRetorno                  = implode("-", array_reverse(explode("/",$r45_dtreto)));
  	$sWhereVerificaAfastamento .= " and  ( r45_dtafas ,  r45_dtreto) overlaps ";
  	$sWhereVerificaAfastamento .= "      (  '{$dtLancamento}'::date,  '{$dtRetorno}'::date) ";
  } else {
  	$sWhereVerificaAfastamento .= " and  r45_dtafas >= '{$dtLancamento}' ";
  }
  
  $sSqlVerificaAfastamento   = $clafasta->sql_query_file(null, "r45_codigo", null, $sWhereVerificaAfastamento);
  $rsVerificaAfastamento     = $clafasta->sql_record($sSqlVerificaAfastamento);
  $aVerificaAfastamento      = db_utils::getCollectionByRecord($rsVerificaAfastamento);
  if(count($aVerificaAfastamento) > 0){
  	
  	$sqlerro  = true;
  	$erro_msg = "O Servidor já possui afastamento para o período selecionado \\n Verifique afastamentos Anteriores";
  }

  if ($sqlerro == false) {

    $clafasta->r45_anousu = $r45_anousu;
    $clafasta->r45_mesusu = $r45_mesusu;
    $clafasta->r45_regist = $r45_regist;
    $clafasta->r45_dtafas = $r45_dtafas;
    $clafasta->r45_dtreto = $r45_dtreto;
    $clafasta->r45_situac = $r45_situac;
    $clafasta->r45_dtlanc = date("Y-m-d",db_getsession("DB_datausu"));
    $clafasta->r45_codafa = $r45_codafa;
    $clafasta->r45_codret = $r45_codret;
    $clafasta->r45_obs    = $r45_obs;

    $clafasta->incluir(null);
    $erro_msg             = $clafasta->erro_msg;
    
    if($clafasta->erro_status == "0"){
      $sqlerro  = true;
    }
    if($sqlerro == false){
      $arr_possiveis = Array(2,3,4,5,6,7,8);
      if(in_array($r45_situac,$arr_possiveis)){

        $result_pontofx = $clpontofx->sql_record($clpontofx->sql_query_file(db_anofolha(),db_mesfolha(),$r45_regist));
        $numrows_pontofx = $clpontofx->numrows;

				$subpes = db_anofolha();
				$subpes.= db_mesfolha();
	
        //dias_pagto($r45_regist,$r45_dtreto,$r45_dtafas);
        global $dias_pagamento, $data_afastamento, $dtfim,$subpes;
        $data_ultimo_dia = db_dias_mes(db_anofolha(),db_mesfolha(),true);
        
        if($r45_dtreto == '' || $r45_dtreto > $data_ultimo_dia ){
          
           $result_dias_trab = db_query("select fc_dias_trabalhados(".$r45_regist.",".db_anofolha().",".db_mesfolha().",true,".db_getsession("DB_instit").") as dias_pagamento");
          if(pg_numrows($result_dias_trab) > 0){
            db_fieldsmemory($result_dias_trab, 0);
          }
        }else{
          $result_dias_trab = db_query("select fc_dias_trabalhados(".$r45_regist.",".db_anofolha().",".db_mesfolha().",false,".db_getsession("DB_instit").") as dias_pagamento");
          if(pg_numrows($result_dias_trab) > 0){
            db_fieldsmemory($result_dias_trab, 0);
          }
        }   
        $result_tbprev = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query_file(null,db_getsession('DB_instit'),"(rh02_tbprev + 2) as rh02_tbpev","","rh02_anousu = ".db_anofolha()." and rh02_mesusu = ".db_mesfolha()." and rh02_regist = ".$r45_regist." and rh02_instit = ".db_getsession("DB_instit")));

        if($clrhpessoalmov->numrows > 0){
          db_fieldsmemory($result_tbprev, 0);
        }
        $result_inssirfsau = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".trim($rh02_tbpev)."' and trim(r33_rubsau) <> '' "));
        $numrows_sau = $clinssirf->numrows;
  
        $result_inssirfmat = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".trim($rh02_tbpev)."' and trim(r33_rubmat) <> '' "));
        $numrows_mat = $clinssirf->numrows;

        $result_inssirfaci = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".trim($rh02_tbpev)."' and trim(r33_rubaci) <> '' "));
        $numrows_aci = $clinssirf->numrows;


        /**
         * Realiza a proporcionalização do ponto
         */
        $oDataRetorno = null;

        if(isset($r45_dtreto) && !empty($r45_dtreto)) {
          $oDataRetorno = new DBDate($r45_dtreto);
        }

        $oCompetencia = DBPessoal::getCompetenciaFolha(); 
        $oServidor = ServidorRepository::getInstanciaByCodigo($r45_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
        $oProporcionalizacaoPontoSalario = new ProporcionalizacaoPontoSalario($oServidor->getPonto(Ponto::SALARIO), $r45_situac, $oDataRetorno);
        $oProporcionalizacaoPontoSalario->processar();
      }
    }
    db_fim_transacao($sqlerro);
  }
}
function dias_pagto($registro=null,$r45_dtreto,$r45_dtafas){
  
  global $dias_pagamento, $data_afastamento, $dtfim,$subpes;
  
  $dias_mes = ndias(db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dtini = db_ctod("01/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dtfim = db_ctod(db_str($dias_mes,2,0,"0")."/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
  $dias_pagamento = 30;
  $afastado = 1;
  $data_afastamento = date("Y-m-d",db_getsession("DB_datausu"));
  if( db_mktime($r45_dtreto) >= db_mktime($dtini) || db_empty($r45_dtreto)){
     $afastado = $r45_situac;
     if( !db_empty($r45_dtreto) ){
        if( db_mktime($r45_dtafas) > db_mktime($dtfim) ){
           $afastado = 1;
        }
        if(isset($datafim) || !db_empty($datafim)){
          if( db_mktime($r45_dtreto) < db_mktime($datafim) ){
             $afastado = 1;
          }
        }
     }
     if( $afastado != 1){
        if( ( db_mktime($r45_dtreto)==0 || db_mktime($r45_dtreto) > db_mktime($dtfim)  ) && db_mktime($r45_dtafas) >= db_mktime($dtini) ){
           $dias_pagamento = db_datedif($r45_dtafas,$dtini);
        }else if( ( db_empty( $r45_dtreto) || db_mktime($r45_dtreto) > db_mktime($dtfim)  ) && db_mktime($r45_dtafas) < db_mktime($dtini) ){ 
           $dias_pagamento = 0;
        }else if( db_mktime($r45_dtafas) < db_mktime($dtini) && db_mktime($r45_dtreto) <= db_mktime($dtfim) ){ 
           $dias_pagamento = db_datedif($dtfim,$r45_dtreto);
           if( $dias_pagamento > 0 ){
     	 if( $dias_mes > 30){
     	     $dias_pagamento -= 1;
     	 }else if( $dias_mes == 29){ 
     	     $dias_pagamento = (30 - db_day($r45_dtreto));
     	 }
           }
        }else if( db_mktime($r45_dtafas) >= db_mktime($dtini) && db_mktime($r45_dtreto) <= db_mktime($dtfim)){ 
           $dias_pagamento = ceil(((db_mktime($dtfim) - db_mktime($r45_dtreto) + db_mktime($r45_dtafas) - db_mktime($dtini))/86400));
           if( !db_empty($dias_pagamento)){
     	 if( $dias_mes > 30){
     	    $dias_pagamento -= 1;
     	 }else if( $dias_mes < 30){ 
     	    $dias_pagamento += (30 - $dias_mes);
     	 }
           }
        }
        $data_afastamento = $r45_dtafas;
     }
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      require_once("forms/db_frmafasta.php");
      
      
        if(count($aRetornoNulo) > 0){
          echo "<script> 
                 js_afastamentosAnteriores({$r45_regist});
                 js_OpenJanelaIframe('','func_pesquisa','pes3_conspessoal002_detalhes.php?solicitacao=Afastamentos&parametro={$r45_regist}&ano={$r45_anousu}&mes={$r45_mesusu}','CONSULTA DE FUNCIONÁRIOS',true,'20'); 
               </script>";
        } 
      if(count($aVerificaAfastamento) > 0){
			    echo "<script> 
			           js_afastamentosAnteriores({$r45_regist});
			           js_OpenJanelaIframe('','func_pesquisa','pes3_conspessoal002_detalhes.php?solicitacao=Afastamentos&parametro={$r45_regist}&ano={$r45_anousu}&mes={$r45_mesusu}','CONSULTA DE FUNCIONÁRIOS',true,'20'); 
			         </script>";
			  }      
      
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
if(isset($incluir)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{
    $clafasta->erro(true,true);
  };
};
?>
<script>
js_tabulacaoforms("form1","r45_regist",true,1,"r45_regist",true);
</script>
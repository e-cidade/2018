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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empempitem_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empparametro_classe.php");
include("libs/db_libdocumento.php");
include("libs/db_utils.php");
include("classes/db_pcparam_classe.php");

$clmatordem      = new cl_matordem;
$clmatparam      = new cl_matparam;
$clmatordemitem  = new cl_matordemitem;
$cldb_almox			 = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;
$clempempenho    = new cl_empempenho;
$clempempitem    = new cl_empempitem;
$clempparametro  = new cl_empparametro;
$clcgm           = new cl_cgm;
$clpcparam       = new cl_pcparam;
db_postmemory($_GET);
$oPost = db_utils::postMemory($_POST);
$result    = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));

if($result != false && $clempparametro->numrows > 0){
  $oParam = db_utils::fieldsMemory($result,0);
}

if (isset($oPost->incluir)) {

  db_inicio_transacao();
  $aFornecedores = array();
  $nValorTotal   = 0;
  $lSqlErro      = false;

  for ($i = 0; $i < count($oPost->itensOrdem); $i++) {

    if (!$lSqlErro) {

      $sValor      = "valor{$oPost->itensOrdem[$i]}";
      $sQuantidade = "quantidade{$oPost->itensOrdem[$i]}";
      //se o usuário escolheu gerar a ordem de compra por fornecedor.....
      $rsEmpItem    = $clempempitem->sql_record($clempempitem->sql_query(null,null,
                                                "e60_numcgm,e60_numemp",
                                                null, 
                                                "e62_sequencial = {$oPost->itensOrdem[$i]}"
                                               ));
      $oEmpItem = db_utils::fieldsMemory($rsEmpItem, 0);
      if ($emitir == "F") {


        //calculamos o total da ordem.
        if ($clempempitem->numrows != 0) {

          if (array_key_exists($oEmpItem->e60_numcgm,$aFornecedores)) {
            $aFornecedores[$oEmpItem->e60_numcgm] += $oPost->$sValor;
          }else{
            $aFornecedores[$oEmpItem->e60_numcgm] = $oPost->$sValor;
          }
        }

      } else {
        if (array_key_exists($oEmpItem->e60_numemp,$aFornecedores)){
          $aFornecedores[$oEmpItem->e60_numemp] += $oPost->$sValor;
        }else{
          $aFornecedores[$oEmpItem->e60_numemp] = $oPost->$sValor;
        }
      }
    }
  }

  $m51_data="{$oPost->m51_data_ano}-{$oPost->m51_data_mes}-{$oPost->m51_data_dia}";
  reset($aFornecedores);
  $cods = "";
  $vir  = "";
  for ($x = 0; $x < count($aFornecedores); $x++ ) {

    $cgm = key($aFornecedores);
    if ($emitir == "E") {

      $rsCgm = $clempempenho->sql_record($clempempenho->sql_query_file($cgm,"e60_numcgm as numcgm"));
      if ($clempempenho->numrows!=0) {

        $oCGM = db_utils::fieldsMemory($rsCgm, 0);
        $clmatordem->m51_numcgm = $oCGM->numcgm;

      }
    } else {
      $clmatordem->m51_numcgm = @$cgm;
    }

    $clmatordem->m51_data       = $m51_data; 
    $clmatordem->m51_depto      = $oPost->coddepto;    
    $clmatordem->m51_obs        = $oPost->m51_obs;
    $clmatordem->m51_tipo       = 1;
    $clmatordem->m51_valortotal = $aFornecedores[$cgm];
    $clmatordem->incluir(null);
    $cods  .= $vir.$clmatordem->m51_codordem;
    $vir    = ",";
    if($clmatordem->erro_status==0){
      $lSqlErro = true;      
    }
    $erro_msg = $clmatordem->erro_msg;
    $codigo   = $clmatordem->m51_codordem;
    for ($i = 0; $i < count($oPost->itensOrdem); $i++) {

      if (!$lSqlErro) {
        
        $rsEmpItem    = $clempempitem->sql_record($clempempitem->sql_query(null,null,
                                                                          "e60_numcgm,e60_numemp,e62_sequen",
                                                                          null, 
                                                                          "e62_sequencial = {$oPost->itensOrdem[$i]}"
                                                                           )
                                                  );

        if ($clempempitem->numrows !=0 ) {

          $oForne = db_utils::fieldsMemory($rsEmpItem,0);
          $iChave = $oForne->e60_numcgm;
          if ($emitir=="E") {
               $iChave = $oForne->e60_numemp;
          }

          if ($cgm == $iChave) {

            $sValor      = "valor{$oPost->itensOrdem[$i]}";
            $sQuantidade = "quantidade{$oPost->itensOrdem[$i]}";
            $nValorUnitario = "vlrunitario{$oPost->itensOrdem[$i]}";
            $clmatordemitem->m52_codordem = $codigo;
            $clmatordemitem->m52_numemp   = $oForne->e60_numemp;
            $clmatordemitem->m52_sequen   = $oForne->e62_sequen;
            $clmatordemitem->m52_quant    = $oPost->$sQuantidade; 
            $clmatordemitem->m52_valor    = $oPost->$sValor;
            $clmatordemitem->m52_vlruni   = $oPost->$sValor/$oPost->$sQuantidade;
            $clmatordemitem->m52_vlruni   = "".round($oPost->$nValorUnitario, $oParam->e30_numdec)."";
            $clmatordemitem->incluir(null);
            if ($clmatordemitem->erro_status==0) {

              $erro_msg = $clmatordemitem->erro_msg;	
              $lSqlErro = true;	      			
              break;	      			

            }
          }
        }
      }
    }
    if (isset($oPost->manda_mail) && $oPost->manda_mail != "") {

      $sqlCgm   = "select z01_email
         from cgm 
        inner join db_usuacgm  c on cgmlogin     = z01_numcgm
        inner join db_usuarios u on c.id_usuario =  u.id_usuario
        where z01_numcgm = ".$clmatordem->m51_numcgm."
        and usuext = 1";
      $rsCgm    = pg_query($sqlCgm);
      if (pg_num_rows($rsCgm) > 0 ){

        db_fieldsmemory($rsCgm,0);
        if ($iPoist->z01_email != ''){

          $headers  = "Content-Type:text/html;";  	  	
          $objteste = new libdocumento(1750);
          $corpo    = $objteste->emiteDocHTML();
          $mail     = mail($z01_email,"Ordem de Compra Nº $codigo",$corpo,$headers);
        }
      }
    }
    next($aFornecedores);
  }
  db_fim_transacao($lSqlErro);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>  
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script> 
<script language="JavaScript" type="text/javascript" src="scripts/notaliquidacao.js"></script> 
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
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
<?
include("forms/db_frmordemcomprageral.php");
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($oPost->incluir)){
  if($lSqlErro == true){ 
    db_msgbox($erro_msg);
    if($clmatordem->erro_campo!=""){
      echo "<script> document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatordem->erro_campo.".focus();</script>";
    } 
  }else{ 
    db_msgbox("Ordens de Compra $cods Incluidas com Sucesso"); 
    echo "        <script>
      if(confirm('Deseja imprimir as ordens de compra?')){
        jan = window.open('emp2_ordemcompra002.php?cods=$cods','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);

      }	 
    location.href='emp4_ordemcomprageral01.php';
    </script>
      ";
  }

}
?>
</body>
</html>
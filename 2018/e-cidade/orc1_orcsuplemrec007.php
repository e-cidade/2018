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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcsuplemrec_classe.php");
include("classes/db_orcsuplemval_classe.php");
include("classes/db_orcprojeto_classe.php");
include("classes/db_orcreceita_classe.php"); 
db_app::import("orcamento.suplementacao.*");
db_postmemory($HTTP_POST_VARS);

$clorcsuplemrec = new cl_orcsuplemrec;
$clorcsuplemval = new cl_orcsuplemval;
$clorcprojeto   = new cl_orcprojeto();
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcreceita = new cl_orcreceita;    // receita

$clorcsuplemrec->rotulo->label();
$clorcreceita->rotulo->label();

$anousu = db_getsession("DB_anousu");
$db_botao = true;

$op = 1;
$db_opcao = 1;
$limpa_dados = false;

if(isset($incluir)){
   $limpa_dados = true;
   // usuario clicou no botao incluir da tela
   db_inicio_transacao();
   $sqlerro = false;
   if (isset($o85_codrec) && $o85_codrec != "") {
     
   $clorcsuplemrec->o85_anousu = $anousu;
   $clorcsuplemrec->o85_codrec = $o85_codrec;
   $clorcsuplemrec->o85_codsup = $o46_codsup;
   $clorcsuplemrec->incluir($o46_codsup,$o85_codrec);
   if ($clorcsuplemrec->erro_status == 0){
      $sqlerro = true;
      $limpa_dados = false;
   }
 } else if (isset($o06_sequencial) && $o06_sequencial != "") {
    
    /**
     * incluimos a projecao para criarmos a suplementação
     */
    $oDaoReceitaPPA = db_utils::getDao("orcsuplemreceitappa");
    $oDaoReceitaPPA->o137_orcsuplem            = $o46_codsup;
    $oDaoReceitaPPA->o137_ppaestimativareceita = $o06_sequencial;
    $oDaoReceitaPPA->o137_valor                = abs($o85_valor);
    $oDaoReceitaPPA->incluir(null);
    if ($oDaoReceitaPPA->erro_status == 0) {
      
      $sqlerro = true;
      db_msgbox($oDaoReceitaPPA->erro_msg);
      $limpa_dados = false;
    }   
  }
  db_fim_transacao($sqlerro);
 
}elseif(isset($opcao) && $opcao=="excluir" ){
   $limpa_dados = true;
   db_inicio_transacao();
   $sqlerro  = false;
   
   if ($tipo == 1) {
   $clorcsuplemrec->excluir($o46_codsup,$o85_codrec);
   if ($clorcsuplemrec->erro_status == 0){
      $sqlerro = true;
      $limpa_dados = false;
   }  
   db_msgbox($clorcsuplemrec->erro_msg);
   } else if ($tipo == 2) {
     
     $oDaoReceitaPPA = db_utils::getDao("orcsuplemreceitappa");
     $oDaoReceitaPPA->excluir($o85_codrec);
     if ($oDaoReceitaPPA->erro_status == 0) {
      $sqlerro = true;
     }
     db_msgbox($oDaoReceitaPPA->erro_msg); 
   }
   db_fim_transacao($sqlerro);
}   

if ($limpa_dados ==true){
    $o85_codrec="";
    $o50_estrutreceita=""; 
    $o57_descr="";  
    $o70_codigo=""; 
    $o15_descr="";    
    $total_rec="";  
    $o85_valor = "";
}
  
/**
 * verifica o tipo da Suplementacao
 */
$sSqlDadosProjeto = $clorcprojeto->sql_query_projeto($o39_codproj, "o138_sequencial");
$rsDadosProjeto   = $clorcprojeto->sql_record($sSqlDadosProjeto);
$oDadosProjeto    = db_utils::fieldsMemory($rsDadosProjeto, 0);
//------------------------------------------
//--------------------------------------
// calcula total das reduções de receita
$oSuplementacao = new Suplementacao($o46_codsup);
$soma_receitas  = $oSuplementacao->getValorReceita();
//--------------------------------------
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	   include("forms/db_frmorcsuplemrec.php");
	?>
    </center>
	</td>
  </tr>
</table>

</body>
</html>
<?
if(isset($incluir)){
  if($clorcsuplemrec->erro_status=="0"){
    $clorcsuplemrec->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcsuplemrec->erro_campo!=""){
      echo "<script> document.form1.".$clorcsuplemrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcsuplemrec->erro_campo.".focus();</script>";
    };
  }else{
    $clorcsuplemrec->erro(true,false);
  };
};
?>
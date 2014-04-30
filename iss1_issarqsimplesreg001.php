<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_utils.php");
include("classes/db_issarqsimplesreg_classe.php");
include("classes/db_issarqsimples_classe.php");
include("classes/db_issarqsimplesregissbase_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clissarqsimplesreg        = new cl_issarqsimplesreg;
$clissarqsimples           = new cl_issarqsimples;
$clissarqsimplesregissbase = new cl_issarqsimplesregissbase;
$db_opcao = 22;
$db_botao = false;

if (isset($alterar) || isset($excluir) || isset($incluir)) {
  $sqlerro = false;
}
if (isset($incluir)) {
  
  try {

    db_inicio_transacao();
    
    $clissarqsimplesreg->incluir($q23_sequencial);
    if ($clissarqsimplesreg->erro_status==0) {
      throw Exception ("Erro ao incluir registros na tabela issarqsimplesreg.\n".$clissarqsimplesreg->erro_msg);
    }
    
    /**
     * Buscando os dados das inscricoes que estao vinculadas ao CNPJ informado para realizar a vinculacao atraves
     * da tabela issarqsimplesregissbase
     */
    $sSql     = $clissarqsimplesregissbase->sql_query_inscricao($q23_cnpj);
    $rsResult = $clissarqsimplesregissbase->sql_record($sSql);
    
    if ($clissarqsimplesregissbase->numrows > 0) {
      
      for ($iContador = 0; $iContador < $clissarqsimplesregissbase->numrows; $iContador++) {
    
        $oDadosIssArq = db_utils::fieldsMemory($rsResult, $iContador);
        $clissarqsimplesregissbase->q134_issarqsimplesreg = $q23_sequencial;
        $clissarqsimplesregissbase->q134_inscr            = $oDadosIssArq->q02_inscr;
        $clissarqsimplesregissbase->incluir(null);
      }
    }
    
    db_fim_transacao();
  } catch (Exception $oException) {
    db_fim_transacao(true);
  }
  
} else if (isset($alterar)) {
  
  try {
    
    db_inicio_transacao();
    
    $clissarqsimplesreg->q23_acao = $q23_acao;
    $clissarqsimplesreg->alterar($q23_sequencial);
    
    $sWhere = "q134_issarqsimplesreg = {$q23_sequencial}";
    $clissarqsimplesregissbase->excluir(null, $sWhere);
    
    /**
     * Buscando os dados das inscricoes que estao vinculadas ao CNPJ informado para realizar a vinculacao atraves
     * da tabela issarqsimplesregissbase
     */
    $sSql     = $clissarqsimplesregissbase->sql_query_inscricao($q23_cnpj);
    $rsResult = $clissarqsimplesregissbase->sql_record($sSql);
    
    if ($clissarqsimplesregissbase->numrows > 0) {
      
      for ($iContador = 0; $iContador < $clissarqsimplesregissbase->numrows; $iContador++) {
    
        $oDadosIssArq = db_utils::fieldsMemory($rsResult, $iContador);
        $clissarqsimplesregissbase->q134_issarqsimplesreg = $q23_sequencial;
        $clissarqsimplesregissbase->q134_inscr            = $oDadosIssArq->q02_inscr;
        $clissarqsimplesregissbase->incluir(null);
        if ($clissarqsimplesregissbase->erro_status == 1) {
          $erro_msg = $clissarqsimplesreg->erro_msg;
        }
      }
    }
    if ($clissarqsimplesreg->erro_status == 0) {
      throw Exception ("Erro ao alterar registros na tabela issarqsimplesreg.\n".$clissarqsimplesreg->erro_msg);
    } else {
      $erro_msg = $clissarqsimplesreg->erro_msg;
    }
    
    db_fim_transacao();
  } catch(Exception $oException) {
    db_fim_transacao(true);
  }
} else if (isset($excluir)) {
  
  try {
    
    db_inicio_transacao();
    $clissarqsimplesreg->excluir($q23_sequencial);
    if($clissarqsimplesreg->erro_status==0){
      throw Exception ("Erro ao excluir registros na tabela issarqsimplesreg.\n".$clissarqsimplesreg->erro_msg);
    }
    db_fim_transacao();
  } catch(Exception $oException) {
    db_fim_transacao(true);
  }
} else if (isset($opcao)) {
  
   $result = $clissarqsimplesreg->sql_record($clissarqsimplesreg->sql_query($q23_sequencial,"*"));
   if($result!=false && $clissarqsimplesreg->numrows>0){
     db_fieldsmemory($result,0);
   }
}
$db_botao = false;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmissarqsimplesreg.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clissarqsimplesreg->erro_campo!=""){
        echo "<script> document.form1.".$clissarqsimplesreg->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clissarqsimplesreg->erro_campo.".focus();</script>";
    }
}
?>
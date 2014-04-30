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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_tabrec_classe.php");
include("classes/db_tabplansaldorecurso_classe.php");
include("dbforms/db_funcoes.php");
$cltabrec = new cl_tabrec;
$cltabplansaldorecurso = new cl_tabplansaldorecurso;
$db_opcao = 1;
$db_botao = true;
$anousu =  db_getsession("DB_anousu");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$codjmOK = true;
if (isset($incluir)) {
    
  $cltabplansaldorecurso->k111_creditoatualizado = $k111_creditoinicial;
  $cltabplansaldorecurso->k111_debitoatualizado  = $k111_debitoinicial;
  $cltabplansaldorecurso->k111_anousu            = db_getsession("DB_anousu");
  if ($k111_dataimplantacao!= "") {
    $cltabplansaldorecurso->k111_dataatualizacao   = implode("-", array_reverse(explode("/", $k111_dataimplantacao)));
  }
  $cltabplansaldorecurso->incluir(null);
} else if(isset($alterar)){
  
  db_inicio_transacao();
  $cltabplansaldorecurso->k111_creditoatualizado = $k111_creditoinicial;
  $cltabplansaldorecurso->k111_debitoatualizado  = $k111_debitoinicial;
  $cltabplansaldorecurso->k111_sequencial        = $k111_sequencial;
  if ($k111_dataimplantacao!= "") {
    $cltabplansaldorecurso->k111_dataatualizacao   = implode("-", array_reverse(explode("/", $k111_dataimplantacao)));
  }
  $cltabplansaldorecurso->alterar($k111_sequencial);
  db_fim_transacao();  
}else if(isset($k04_sequencial) && trim($k04_sequencial) != ""){
  $result_tabplansaldorecurso = $cltabplansaldorecurso->sql_record($cltabplansaldorecurso->sql_query_tabrec($k04_sequencial," *, k04_codjm as testajuroemulta "));
  if($cltabplansaldorecurso->numrows > 0){
    db_fieldsmemory($result_tabplansaldorecurso, 0);
  }

  if($opcao == "alterar"){
    $db_opcao = 2; 
  }else{
    $db_opcao = 3; 
  }
} else if(isset($k111_sequencial) && trim($k111_sequencial) != ""){
  
  $sSqlSaldo = $cltabplansaldorecurso->sql_query($k111_sequencial,"*");
  $result_tabplansaldorecurso = $cltabplansaldorecurso->sql_record($sSqlSaldo);
  if($cltabplansaldorecurso->numrows > 0){
    db_fieldsmemory($result_tabplansaldorecurso, 0);
  }

  if($opcao == "alterar"){
    $db_opcao = 2; 
  }else{
    $db_opcao = 3; 
  }  
}else if(isset($k04_receit)){
  
  $result = $cltabrec->sql_record($cltabrec->sql_query($k111_tabplan));
  db_fieldsmemory($result,0);

}
if ((isset($incluir) || isset($alterar) || isset($excluir)) && $cltabplansaldorecurso->erro_status != "0") {
   
  $k111_sequencial          = "";
  $k111_dataimplantacao     = "";
  $k111_dataimplantacao_dia = "";
  $k111_dataimplantacao_mes = "";
  $k111_dataimplantacao_ano = "";
  $k111_debitoinicial       = "";
  $k111_creditoinicial      = "";
  $k111_creditoatualizado   = "";
  $k111_debitoatualizado    = "";
  $k111_recurso             = "";
  $k111_dataatualizacao_dia = "";
  $k111_dataatualizacao_mes = "";
  $k111_dataatualizacao_ano = "";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <?
      include("forms/db_frmtabplansaldorecurso.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($cltabplansaldorecurso->erro_status=="0"){
    $cltabplansaldorecurso->erro(true,false);
    if($cltabplansaldorecurso->erro_campo!=""){
      echo "<script> document.form1.".$cltabplansaldorecurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltabplansaldorecurso->erro_campo.".focus();</script>";
    };
  }else{
    $cltabplansaldorecurso->erro(true,false);
  };
}
if($codjmOK == false){
  db_msgbox("ALERTA: Código de juro e multa do período atual diferente da receita.\\nVerifique.");
}
?>
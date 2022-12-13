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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcparamseqorcparamseqcoluna_classe.php");
include("classes/db_orcparamseq_classe.php");
include("classes/db_orcparamrel_classe.php");
include("dbforms/db_funcoes.php");
require_once("model/relatorioContabil.model.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
db_postmemory($_GET);
$clorcparamseqorcparamseqcoluna = new cl_orcparamseqorcparamseqcoluna;
$clorcparamseq = new cl_orcparamseq;
$clorcparamrel = new cl_orcparamrel;
$db_opcao = 22;
$db_botao = false;
$oRelatorio = new relatorioContabil($o116_codparamrel, false);
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clorcparamseqorcparamseqcoluna->o116_sequencial = $o116_sequencial;
$clorcparamseqorcparamseqcoluna->o116_codseq = $o116_codseq;
$clorcparamseqorcparamseqcoluna->o116_codparamrel = $o116_codparamrel;
$clorcparamseqorcparamseqcoluna->o116_orcparamseqcoluna = $o116_orcparamseqcoluna;
$clorcparamseqorcparamseqcoluna->o116_ordem = $o116_ordem;
  */
}
if(isset($incluir)){
  
  if ($sqlerro==false) {
    
    db_inicio_transacao();
    foreach ($o116_periodo as $iPeriodo) {
      
      $clorcparamseqorcparamseqcoluna->o116_periodo = $iPeriodo; 
      $clorcparamseqorcparamseqcoluna->incluir($o116_sequencial);
      $erro_msg = $clorcparamseqorcparamseqcoluna->erro_msg;
      if($clorcparamseqorcparamseqcoluna->erro_status==0){
        $sqlerro=true;
      }
    }
    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {
  
   if($sqlerro==false) {
    
    db_inicio_transacao();
    foreach ($o116_periodo as $iPeriodo) {
      
      $sWhere       = " o116_codseq                 = {$o116_codseq} "; 
      $sWhere      .= " and  o116_codparamrel       =  {$o116_codparamrel} "; 
      $sWhere      .= " and  o116_periodo           =  {$iPeriodo} "; 
      $sWhere      .= " and  o116_orcparamseqcoluna =  {$colunaoriginal} "; 
      $sWhere      .= " and  o116_ordem             =  {$ordemoriginal} "; 
      $sSqlColunas = $clorcparamseqorcparamseqcoluna->sql_query_file(null,"*", null, $sWhere);
      $rsColunas   = $clorcparamseqorcparamseqcoluna->sql_record($sSqlColunas);
      if ($clorcparamseqorcparamseqcoluna->numrows > 0) {

        $oColuna = db_utils::fieldsMemory($rsColunas, 0);
        $clorcparamseqorcparamseqcoluna->o116_periodo    = $iPeriodo; 
        $clorcparamseqorcparamseqcoluna->o116_sequencial = $oColuna->o116_sequencial; 
        $clorcparamseqorcparamseqcoluna->alterar($oColuna->o116_sequencial);
        
      } else {
        
        $clorcparamseqorcparamseqcoluna->o116_periodo = $iPeriodo; 
        $clorcparamseqorcparamseqcoluna->incluir(null);
        $erro_msg = $clorcparamseqorcparamseqcoluna->erro_msg;
      }
      $erro_msg = $clorcparamseqorcparamseqcoluna->erro_msg;
      if($clorcparamseqorcparamseqcoluna->erro_status==0){
        $sqlerro=true;
      }
    }
    $erro_msg = $clorcparamseqorcparamseqcoluna->erro_msg;
    if($clorcparamseqorcparamseqcoluna->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clorcparamseqorcparamseqcoluna->excluir($o116_sequencial);
    $erro_msg = $clorcparamseqorcparamseqcoluna->erro_msg;
    if($clorcparamseqorcparamseqcoluna->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clorcparamseqorcparamseqcoluna->sql_record($clorcparamseqorcparamseqcoluna->sql_query($o116_sequencial));
   if($result!=false && $clorcparamseqorcparamseqcoluna->numrows>0){
     db_fieldsmemory($result,0);
   }

}                               
if (isset($o116_codparamrel) && $o116_codparamrel != "") {

  $sSqlColunas =
 $rsColunas = $clorcparamrel->sql_record($clorcparamrel->sql_query(null,"*",null,"o42_codparrel = $o116_codparamrel"));
 if($rsColunas!=false && $clorcparamrel->numrows>0){
   db_fieldsmemory($rsColunas,0);
 }

  $sSqlDadosLinha = $clorcparamseq->sql_query_file($o116_codparamrel, $o116_codseq);
  $rsDadosLinhas  = $clorcparamseq->sql_record($sSqlDadosLinha);
  if ($rsDadosLinhas) {
    db_fieldsmemory($rsDadosLinhas, 0);
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
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBContextComplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<br /> <br />
<center>
	<?
  	include("forms/db_frmorcparamseqorcparamseqcoluna.php");
	?>
</center>

</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clorcparamseqorcparamseqcoluna->erro_campo!=""){
        echo "<script> document.form1.".$clorcparamseqorcparamseqcoluna->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcparamseqorcparamseqcoluna->erro_campo.".focus();</script>";
    }
}
?>
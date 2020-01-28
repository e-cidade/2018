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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_pcorcamtroca_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamval_classe.php");
$clpcorcamtroca = new cl_pcorcamtroca;
$clpcorcamjulg  = new cl_pcorcamjulg;
$clpcorcamval   = new cl_pcorcamval;
$clrotulo       = new rotulocampo;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);

$db_opcao = 1;
$db_botao = false;

$sqlerro  = false;
if (isset($trocar)) {
  
  if (trim($pc25_motivo) == '') {
    
    $erro_msg = "Campo motivo da troca da pontuação não informado ou contendo apenas espaço.";
    $sqlerro  = true;
  }
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    $clpcorcamtroca->pc25_orcamitem = $pc25_orcamitem;
    $clpcorcamtroca->pc25_motivo    = $pc25_motivo;
  
    $clpcorcamtroca->pc25_forneant  = $pc24_orcamforne_ant;
    $clpcorcamtroca->pc25_forneatu  = $pc24_orcamforne;
  
    $clpcorcamtroca->incluir(null);
    $erro_msg = $clpcorcamtroca->erro_msg;
    if ($clpcorcamtroca->erro_status == 0) {
      $sqlerro=true;
    }
    if ($sqlerro == false) {
      
      $arr_troca    = array();
      $result_troca = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_file(null,null,"pc24_orcamforne as pc24_orcamforne_sql,pc24_pontuacao","pc24_orcamforne","pc24_orcamitem=$pc25_orcamitem and (pc24_orcamforne=$pc24_orcamforne_ant or pc24_orcamforne=$pc24_orcamforne)"));    
      for ($i = 0; $i < $clpcorcamjulg->numrows; $i++) {
        
        db_fieldsmemory($result_troca,$i);
        $arr_troca[$pc24_orcamforne_sql] = $pc24_pontuacao;
      }
      $clpcorcamjulg->pc24_orcamitem = $pc25_orcamitem;
      $clpcorcamjulg->pc24_pontuacao = $arr_troca[$pc24_orcamforne_ant];
      $clpcorcamjulg->pc24_orcamforne= $pc24_orcamforne;
      $clpcorcamjulg->alterar($pc25_orcamitem,$pc24_orcamforne);
      if ($clpcorcamjulg->erro_status == 0) {
        
        $erro_msg = $clpcorcamjulg->erro_msg;
        $sqlerro=true;
      }
      if ($sqlerro == false) {
        
        $clpcorcamjulg->pc24_orcamitem = $pc25_orcamitem;
        $clpcorcamjulg->pc24_pontuacao = $arr_troca[$pc24_orcamforne];
        $clpcorcamjulg->pc24_orcamforne= $pc24_orcamforne_ant;
        $clpcorcamjulg->alterar($pc25_orcamitem,$pc24_orcamforne_ant);
        if ($clpcorcamjulg->erro_status == 0) {
          
          $erro_msg = $clpcorcamjulg->erro_msg;
          $sqlerro  = true;
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
  <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
      include("forms/db_frmtrocpcorcamtroca.php");
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
if(isset($trocar)){
  if($sqlerro==true){
    $erro_msg = str_replace("\n","\\n",$erro_msg);
    db_msgbox($erro_msg);
    if($clpcorcamtroca->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcamtroca->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcamtroca->erro_campo.".focus();</script>";
    }  
  }else{
    echo "<script> top.corpo.location.href = 'com1_pcorcamtroca001.php?sol=$sol&pc20_codorc=$orcamento'; </script>";
  }
}
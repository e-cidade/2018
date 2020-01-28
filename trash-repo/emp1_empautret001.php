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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_pagordemtiporec_classe.php");
include ("classes/db_empautret_classe.php");
include ("classes/db_empretencao_classe.php");
$clpagordemtiporec = new cl_pagordemtiporec;
$clempautret = new cl_empautret;
$clempretencao = new cl_empretencao;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$op = 1;
$db_opcao = 1;
$db_botao = true;
$instit = db_getsession("DB_instit");
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
  $clempautret->db_deleteEmpAutRet($e66_autori);
  $erro_msg = $clempautret->erro_msg;
  if($clempautret->erro_status == "0"){
    $sqlerro = true;
  }

  if($sqlerro == false && trim($valores_selecionados) != ""){
    $arr_dados_linha = split("\|",$valores_selecionados);
    for($i=0; $i<count($arr_dados_linha); $i++){
      $arr_dados = split("_",$arr_dados_linha[$i]);
      $clempretencao->e65_receita = $arr_dados[1];
      $clempretencao->e65_aliquota= $arr_dados[2];
      $clempretencao->e65_valor   = $arr_dados[3];
      $clempretencao->incluir(null);
      $retencao_inclui = $clempretencao->e65_seq;
      if($clempretencao->erro_status == "0"){
        $sqlerro = true;
        $erro_msg = $clempretencao->erro_msg;
        break;
      }

      if($sqlerro == false){
        $clempautret->incluir($e66_autori, $retencao_inclui);
        $erro_msg = $clempautret->erro_msg;
        if($clempautret->erro_status == "0"){
          $sqlerro = true;
          break;
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
<style type="text/css">
.table_header{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
    font-size: 10px;
}
.tr_tab{
  background-color:white;
  font-size: 8px;
  height : 8px;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmempautret.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
js_mostrardiv(true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  db_msgbox($erro_msg);
}
?>
<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_db_layoutcampos_classe.php"));
include(modification("classes/db_db_layoutlinha_classe.php"));
include(modification("classes/db_db_layouttxt_classe.php"));
include(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_layoutcampos = new cl_db_layoutcampos;
$cldb_layoutlinha = new cl_db_layoutlinha;
$cldb_layouttxt = new cl_db_layouttxt;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$cldb_layoutcampos->db52_codigo = $db52_codigo;
$cldb_layoutcampos->db52_layoutlinha = $db52_layoutlinha;
$cldb_layoutcampos->db52_nome = $db52_nome;
$cldb_layoutcampos->db52_descr = $db52_descr;
$cldb_layoutcampos->db52_layoutformat = $db52_layoutformat;
$cldb_layoutcampos->db52_posicao = $db52_posicao;
$cldb_layoutcampos->db52_default = $db52_default;
  */
}
function verificaConflitos($sErro, $controle,$linha, $inicio, $tamanho, $sAcao, $iCodigo = ''){

  $sCondicao = "=";
  if ($sAcao == 3 or $sAcao == 2){
     $sCondicao = ">";
  }
  global $cldb_layoutcampos;
  $sCampos    = "db52_codigo,db53_tamanho,db52_nome,db52_tamanho,db52_posicao";
  $sWhere     = "db52_layoutlinha = {$linha} ";
  $sWhere    .= "and db52_posicao {$sCondicao} {$inicio}";
  if ($iCodigo != ''){
    $sWhere    .= "and db52_codigo <> {$iCodigo}";
  }


   //die($cldb_layoutcampos->sql_query(null,$sCampos,null,$sWhere));
   $rsVerifica =  $cldb_layoutcampos->sql_record($cldb_layoutcampos->sql_query(null,$sCampos,null,$sWhere));
   if ($cldb_layoutcampos->numrows > 0){

     $oLinha   = db_utils::fieldsMemory($rsVerifica,0);
     $sCalculo = "db52_posicao+{$tamanho}";
     if ($sAcao == 3){
       $sCalculo = "db52_posicao-{$tamanho}";
     }
     $sUpdateLinhas  = "update db_layoutcampos";
     $sUpdateLinhas .= "   set db52_posicao     = {$sCalculo}";
     $sUpdateLinhas .= " where db52_layoutlinha = {$linha}";
     $sUpdateLinhas .= "   and db52_posicao >= {$inicio}";
     if ($iCodigo != ''){
        $sUpdateLinhas .= "   and db52_codigo  <> {$iCodigo}";
     }
     //die($sUpdateLinhas);
     $rsUpdateLinhas =  db_query($sUpdateLinhas);
     return true;
   }
   echo $controle;
   return true;
}
if(isset($incluir)){

  if($sqlerro==false){

    db_inicio_transacao();
    /*
     * Consultamos para ver se nao a registros usando a posições indicada pelo usuário;
     *
    */
    $erro_msg = '';

    $cldb_layoutcampos->incluir($db52_codigo);
    $erro_msg = $cldb_layoutcampos->erro_msg;
    if($cldb_layoutcampos->erro_status==0){
      $sqlerro=true;
    }else{
    verificaConflitos($erro_msg, $sqlerro, $db52_layoutlinha, $db52_posicao, $db52_tamanho,1,$cldb_layoutcampos->db52_codigo);
    }
    db_fim_transacao($sqlerro);
  }

}else if(isset($alterar)){

  if($sqlerro == false){

    db_inicio_transacao();
    $cldb_layoutcampos->alterar($db52_codigo);
    $erro_msg = $cldb_layoutcampos->erro_msg;

    if($cldb_layoutcampos->erro_status==0){
      $sqlerro=true;
    }else{
      verificaConflitos($erro_msg, $sqlerro, $db52_layoutlinha, $db52_posicao, ($db52_tamanho - $db52_tamanho_old),2,$db52_codigo);
    }

    db_fim_transacao($sqlerro);
  }

}else if(isset($excluir)){

  if($sqlerro==false){
    db_inicio_transacao();
    verificaConflitos($erro_msg, $sqlerro, $db52_layoutlinha, $db52_posicao, $db52_tamanho,3,$db52_codigo);
    $cldb_layoutcampos->excluir($db52_codigo);
    $erro_msg = $cldb_layoutcampos->erro_msg;

    if($cldb_layoutcampos->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }

}else if(isset($opcao)){

  $result = $cldb_layoutcampos->sql_record($cldb_layoutcampos->sql_query($db52_codigo));
  if($result!=false && $cldb_layoutcampos->numrows>0){
    db_fieldsmemory($result,0);
    $db52_tamanho_old = $db52_tamanho;
  }

}else if(isset($db52_layoutlinha)){

  $result_linha = $cldb_layoutlinha->sql_record($cldb_layoutlinha->sql_query_file($db52_layoutlinha));
  if($cldb_layoutlinha->numrows > 0){
    db_fieldsmemory($result_linha, 0);
  }
  if(isset($chave_pesquisa)){

    $result_campos = $cldb_layoutcampos->sql_record($cldb_layoutcampos->sql_query($chave_pesquisa, "db52_nome, db52_descr, db52_layoutformat, db53_descr, db52_posicao, db52_tamanho, db52_ident, db52_imprimir, db52_default, db52_obs, db52_alinha"));
    if($cldb_layoutcampos->numrows>0){
      db_fieldsmemory($result_campos, 0);
      $db52_tamanho_old = $db52_tamanho;
    }
  }
}
if(isset($db_opcaoal)){

  $db_opcao=33;
  $db_botao=false;

}else if(isset($opcao) && $opcao=="alterar"){

  $db_botao=true;
  $db_opcao = 2;

}else if(isset($opcao) && $opcao=="excluir"){

  $db_opcao = 3;
  $db_botao=true;

}else{

  $db_opcao = 1;
  $db_botao=true;

  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){

    $db52_codigo = "";
    $db52_nome = "";
    $db52_descr = "";
    $db52_layoutformat = "";
    $db53_descr = "";
    $db52_posicao = "";
    $db52_default = "";
    $db52_tamanho = "";
    $db52_ident = "";
    $db52_obs = "";
    $db52_quebraapos = 0;

  }
}
if(!isset($db52_quebraapos)){
  $db52_quebraapos = 0;
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include(modification("forms/db_frmdb_layoutcampos.php"));
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
<?if($db_opcao == 1 || $db_opcao == 2){?>
js_tabulacaoforms("form1","db52_nome",true,1,"db52_nome",true);
<?}else{?>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
<?}?>
</script>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
    if($cldb_layoutcampos->erro_campo!=""){
        echo "<script> document.form1.".$cldb_layoutcampos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cldb_layoutcampos->erro_campo.".focus();</script>";
    }
  }
}
?>
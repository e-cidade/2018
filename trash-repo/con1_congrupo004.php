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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_congrupo_classe.php");
include("classes/db_conplanogrupo_classe.php");
include("classes/db_conplano_classe.php");

db_postmemory($HTTP_POST_VARS);

$clcongrupo      = new cl_congrupo;
$clconplanogrupo = new cl_conplanogrupo;
$clconplano      = new cl_conplano;

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (!isset($c21_codcon)){
  exit;
}

$db_opcao = 1;
$db_botao = true;
$anousu   = db_getsession("DB_anousu");

if (isset($novo)){
  unset($c20_sequencial);
  unset($sequencial);
  unset($c20_descr);
}

if (isset($opcao)){
  $dbwhere = "";
  if (isset($c21_sequencial) && trim(@$c21_sequencial) != ""){
    $dbwhere = "and c21_sequencial = $c21_sequencial";
  }

  $res_conplanogrupo = $clconplanogrupo->sql_record($clconplanogrupo->sql_query(null,"c21_sequencial,c20_sequencial,c20_descr",null,"c21_codcon = $c21_codcon and c21_anousu = $c21_anousu $dbwhere"));
  if ($clconplanogrupo->numrows > 0){
    db_fieldsmemory($res_conplanogrupo,0);
  }
}

if (isset($opcao) && $opcao == "alterar"){
  $sequencial = $c21_sequencial;
  $db_opcao = 2;
}

if (isset($opcao) && $opcao == "excluir"){
  $sequencial = $c21_sequencial;
  $db_opcao = 3;
}

if(isset($incluir)){
  $erro_msg = "";
  $sqlerro  = false;
  db_inicio_transacao();

  $clconplanogrupo->c21_codcon   = $c21_codcon;
  $clconplanogrupo->c21_anousu   = $c21_anousu;
  $clconplanogrupo->c21_congrupo = $c20_sequencial;

  $clconplanogrupo->incluir(null);
  $erro_msg = $clconplanogrupo->erro_msg;
  if ($clconplanogrupo->erro_status == 0){
    $sqlerro = true;
    $clcongrupo->erro_campo = "c20_sequencial";
  }

  if ($sqlerro == false){
// Duplica grupo para proximos exercicios    
    $sql_conplano = "select max(c60_anousu) as c60_anousu from conplano";
    $res_conplano = $clconplano->sql_record($sql_conplano);
    $numrows      = $clconplano->numrows;
    $contador     = 0;
    if ($numrows > 0) {
      db_fieldsmemory($res_conplano,0);
      $contador = $c60_anousu - $anousu;
    }

    for ($i=0; $i < $contador; $i++) {
      // Verifica se conta ja existe para proximo exercicio se existir duplica grupo senao, nao faz nda
      $clconplanogrupo->sql_record($clconplanogrupo->sql_query_file(null,"*",null,"c21_codcon = $c21_codcon and 
                                                                                   c21_anousu = ".(($anousu + $i) + 1)." and
                                                                                   c21_congrupo = $c20_sequencial"));
      if ($clconplanogrupo->numrows == 0){
        $clconplanogrupo->c21_codcon   = $c21_codcon;
        $clconplanogrupo->c21_anousu   = ($anousu + $i) + 1; 
        $clconplanogrupo->c21_congrupo = $c20_sequencial;

        $clconplanogrupo->incluir(null);
        $erro_msg = $clconplanogrupo->erro_msg;
        if ($clconplanogrupo->erro_status == 0){
          $sqlerro = true;
          break;
        }
      }
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    unset($c20_sequencial);
    unset($sequencial);
    unset($c20_descr);
  }
}

if (isset($alterar)){
  $c21_sequencial = $sequencial;
  $erro_msg = "";
  $sqlerro  = false;
  db_inicio_transacao();

  $clconplanogrupo->c21_sequencial = $c21_sequencial;
  $clconplanogrupo->c21_codcon     = $c21_codcon;
  $clconplanogrupo->c21_anousu     = $c21_anousu;
  $clconplanogrupo->c21_congrupo   = $c20_sequencial;

  $clconplanogrupo->alterar($c21_sequencial);
  $erro_msg = $clconplanogrupo->erro_msg;
  if ($clconplanogrupo->erro_status == 0){
    $sqlerro = true;
    $clcongrupo->erro_campo = "c20_sequencial";
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 2;
}

if (isset($excluir)){
  $c21_sequencial = $sequencial;
  $erro_msg = "";
  $sqlerro  = false;
  db_inicio_transacao();

  $clconplanogrupo->excluir($c21_sequencial);
  $erro_msg = $clconplanogrupo->erro_msg;
  if ($clconplanogrupo->erro_status == 0){
    $sqlerro = true;
    $clcongrupo->erro_campo = "c20_sequencial";
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $db_opcao = 1;
    unset($c20_sequencial);
    unset($sequencial);
    unset($c20_descr);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <center>
	<?
	include("forms/db_frmconplanogrupo.php");
	?>
    </center>
</table>
</body>
</html>
<?
if(isset($incluir)||isset($alterar)||isset($excluir)){
  if($sqlerro == true){
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcongrupo->erro_campo!=""){
      echo "<script> document.form1.".$clcongrupo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcongrupo->erro_campo.".focus();</script>";
    }
  }

  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }
}
?>
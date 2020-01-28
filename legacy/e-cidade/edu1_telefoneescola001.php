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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("classes/db_telefoneescola_classe.php"));
require_once(modification("classes/db_escola_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_jsplibwebseller.php"));

db_postmemory($_POST);
$cltelefoneescola = new cl_telefoneescola;
$clescola         = new cl_escola;
$cldb_depart      = new cl_db_depart;
$ed18_i_codigo    = db_getsession("DB_coddepto");
$db_opcao         = 1;
$db_botao         = true;

if (isset($alterar)||isset($alterar1)) {

  db_inicio_transacao();
  $db_opcao = 2;

  $lTelefoneValido = validaTelefone($ed26_i_numero);
  if (isset($alterar1)) {

    $clescola->ed18_c_email    = $ed18_c_email;
    $clescola->ed18_c_homepage = $ed18_c_homepage;
    $clescola->ed18_i_codigo   = $ed18_i_codigo;
    $clescola->alterar($ed18_i_codigo);
  } else {

    if ($lTelefoneValido) {
      $cltelefoneescola->alterar($ed26_i_codigo);
    }
  }
  db_fim_transacao();
}

$result       = $clescola->sql_record($clescola->sql_query($ed18_i_codigo));
$result_depto = $cldb_depart->sql_record($cldb_depart->sql_query_file("","*","","coddepto = $ed18_i_codigo"));
db_fieldsmemory($result_depto,0);

if ($clescola->numrows != 0) {

  db_fieldsmemory($result,0);
  $db_opcao  = 2;
  $db_opcao1 = 1;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>

   <fieldset style="width:95%"><legend><b>Contato da Escola</b></legend>
    <?include(modification("forms/db_frmtelefoneescola.php"));?>
   </fieldset>

  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)||isset($incluir1)){

  $lTelefoneValido = validaTelefone($ed26_i_numero);
  if ($lTelefoneValido) {

    db_inicio_transacao();

    if(isset($incluir1)) {
      $clescola->incluir($ed18_i_codigo);
    }else{
      $cltelefoneescola->incluir($ed26_i_codigo);
    }
    db_fim_transacao();
    if($cltelefoneescola->erro_status == "0") {

      $cltelefoneescola->erro(true,false);
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if($cltelefoneescola->erro_campo != "") {

        echo "<script> document.form1.".$cltelefoneescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cltelefoneescola->erro_campo.".focus();</script>";
      };
    } else {
      $cltelefoneescola->erro(true,true);
    };
  }
};

if(isset($alterar)||isset($alterar1)){
 if($cltelefoneescola->erro_status=="0"){
  $cltelefoneescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cltelefoneescola->erro_campo!=""){
   echo "<script> document.form1.".$cltelefoneescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cltelefoneescola->erro_campo.".focus();</script>";
  };
 }else{
  $cltelefoneescola->erro(true,true);
 };
}
if(isset($excluir)||isset($excluir1)){
 db_inicio_transacao();
 $db_opcao = 3;
    if(isset($excluir1)){
     $clescola->excluir($ed18_i_codigo);
   }else{
     $cltelefoneescola->excluir($ed26_i_codigo);
   }
 db_fim_transacao();
 if($cltelefoneescola->erro_status=="0"){
  $cltelefoneescola->erro(true,false);
 }else{
  $cltelefoneescola->erro(true,true);
 };
}
if(isset($cancelar)){
 echo "<script>location.href='".$cltelefoneescola->pagina_retorno."'</script>";
}


/**
 * Validamos se o telefone eh valido.
 * 1 - Se o telefone foi informado com ao menos 2 algarismos diferentes
 * 2 - Se ao informar um numero com 9 casas decimais, este comeca com 9
 */
function validaTelefone( $sTelefone ) {

  $iPrimeiroNumero = substr($sTelefone, 0, 1);
  $iTotalNumeros   = strlen($sTelefone);
  $sString         = str_replace($iPrimeiroNumero, " ", $sTelefone, $iContador);

  if ($iContador == $iTotalNumeros) {

    db_msgbox('Por favor, informe um telefone válido.');
    return false;
  }
  if ($iTotalNumeros == 9 && $iPrimeiroNumero != 9) {

    db_msgbox('Ao informar um telefone com 9 casas decimais, obrigatoriamente a primeira deve ser o número 9.');
    return false;
  }
  return true;
}
?>
<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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


require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao_excluir = false;
$db_botao = true;
$db_opcao = 6;

$oDaoIptuBase  = new cl_iptubase();
$oDaoIptuEnder = new cl_iptuender();

$oDaoIptuEnder->rotulo->label();

if (isset($alterando)) {
  $j43_matric = $j01_matric;
}

/**
 * Verifica se o endereço selecionado é do municipio
 */
$oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

if (empty($oPrefeitura)) {
 $oPrefeitura = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
}

if (isset($atualizar)) {

   db_inicio_transacao();

   /**
    * Caso o endereço seja do municipio seta a UF e Municipio
    */
   if ($iEnderecoMunicipio == 1) {

     $j43_uf    = $oPrefeitura->getUf();
     $j43_munic = $oPrefeitura->getMunicipio();
   }

   $sSqlVerifica = $oDaoIptuEnder->sql_query($j43_matric, "j43_matric");
   $rsVerifica   = $oDaoIptuEnder->sql_record($sSqlVerifica);

   $oDaoIptuEnder->j43_uf    = $j43_uf;
   $oDaoIptuEnder->j43_munic = $j43_munic;

   if ($oDaoIptuEnder->numrows == 0) {
     $oDaoIptuEnder->incluir($j43_matric);
   } else {
     $oDaoIptuEnder->alterar($j43_matric);
   }

   db_fim_transacao();

} else if(isset($excluir)) {

  db_inicio_transacao();
  $oDaoIptuEnder->excluir($j43_matric);
  db_fim_transacao();

} else if(isset($j43_matric)) {

  $sSqlBusca = $oDaoIptuEnder->sql_query($j43_matric,"iptuender.*, cgm.z01_nome");
  $rsBusca   = $oDaoIptuEnder->sql_record($sSqlBusca);

  if ($oDaoIptuEnder->numrows != 0) {

    db_fieldsmemory($rsBusca ,0);
    $db_botao_excluir = true;

    $lMunicipio = mb_strtoupper($oPrefeitura->getUf()) == $j43_uf && mb_strtoupper($oPrefeitura->getMunicipio()) == mb_strtoupper($j43_munic);
    $iEnderecoMunicipio = $lMunicipio ? 1 : 0;

  } else {

    $sSqlBusca = $oDaoIptuBase->sql_query($j43_matric, "z01_nome");
    $rsBusca   = $oDaoIptuBase->sql_record($sSqlBusca);

    db_fieldsmemory($rsBusca,0);
  }
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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <?php include modification("forms/db_frmiptuender.php"); ?>
  </body>
</html>
<?php
if(isset($atualizar)||isset($excluir)){
  if($oDaoIptuEnder->erro_status=="0"){
    $oDaoIptuEnder->erro(true,false);
    if($oDaoIptuEnder->erro_campo!=""){
      echo "<script>document.form1.".$oDaoIptuEnder->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script>document.form1.".$oDaoIptuEnder->erro_campo.".focus();</script>";
    }
  }else{
    $oDaoIptuEnder->erro(true,false);
    db_redireciona("cad1_iptuenderalt.php?j43_matric=$j43_matric");
  }
}
?>
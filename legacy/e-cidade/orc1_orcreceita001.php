<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_conplanoorcamentoanalitica_classe.php"));
require_once(modification("classes/db_orcreceita_classe.php"));
require_once(modification("classes/db_orcparametro_classe.php"));
require_once(modification("classes/db_orcfontes_classe.php"));
require_once(modification("classes/db_orcfontesdes_classe.php"));
require_once(modification("classes/db_concarpeculiar_classe.php"));

db_postmemory($_POST);

$clconplanoorcamentoanalitica = new cl_conplanoorcamentoanalitica;
$clorcreceita                 = new cl_orcreceita;
$clorcfontes                  = new cl_orcfontes;
$clorcfontesdes               = new cl_orcfontesdes;
$clorcparametro               = new cl_orcparametro;
$clestrutura                  = new cl_estrutura;
$clconcarpeculiar             = new cl_concarpeculiar;

$iAnoUsu  = db_getsession("DB_anousu");
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)) {

  $sqlerro=false;
  db_inicio_transacao();
  $sSqlConsulta = $clorcfontes->sql_query_file(null,null,"o57_codfon as o70_codfon",'',"o57_fonte='".str_replace(".","",$o50_estrutreceita)."' and o57_anousu = {$iAnoUsu}");
  $result       = $clorcfontes->sql_record($sSqlConsulta);
  if($clorcfontes->numrows>0){

    db_fieldsmemory($result,0);

    /**
     * Verificamos se existe a receita cadastrada no sistema no ano.
     */
    $sWhere  = "o70_anousu = {$iAnoUsu}";
    $sWhere .= " and o70_codfon = {$o70_codfon}";
    $sWhere .= " and o70_concarpeculiar = '{$o70_concarpeculiar}'";
    $sSqlReceitaJaLancada = $clorcreceita->sql_query_file(null, null, "*", "o70_instit", $sWhere);
    $rsReceitaJaLancada   = $clorcfontes->sql_record($sSqlReceitaJaLancada);

    if ($clorcfontes->numrows > 0) {

      $oDadosReceita =  db_utils::fieldsMemory($rsReceitaJaLancada, 0);
      $oInstituicao = new Instituicao($oDadosReceita->o70_instit);
      $clorcreceita->erro_msg = "Receita {$o50_estrutreceita} já cadastrada para o ano de {$iAnoUsu} na instituição {$oInstituicao->getDescricao()}";
      $sqlerro = true;
    }

    if (!$sqlerro) {

      $clorcreceita->o70_codfon         = $o70_codfon;
      $clorcreceita->o70_instit         = db_getsession("DB_instit");
      $clorcreceita->o70_concarpeculiar = $o70_concarpeculiar;

      $clorcreceita->incluir($o70_anousu, $o70_codrec);
      if ($clorcreceita->erro_status == 0) {

        db_msgbox($clorcreceita->erro_msg);
        $sqlerro = true;
      }
    }
// Faz update no recurso da conta conforme previsao
    if($sqlerro==false) {
      $rs_conplanoreduz = $clconplanoorcamentoanalitica->sql_record($clconplanoorcamentoanalitica->sql_query_file(null,null,"c61_reduz,c61_anousu","c61_codcon","c61_codcon=$o70_codfon and c61_instit=".db_getsession("DB_instit")));
      $rows             = $clconplanoorcamentoanalitica->numrows;
      if($rows>0) {
        for($x=0;$x<$rows;$x++){
          db_fieldsmemory($rs_conplanoreduz,$x);
          $clconplanoorcamentoanalitica->c61_instit = db_getsession("DB_instit");
          $clconplanoorcamentoanalitica->c61_codigo = $o70_codigo;
          $clconplanoorcamentoanalitica->c61_anousu = $c61_anousu;
          $clconplanoorcamentoanalitica->c61_reduz = $c61_reduz;
          $clconplanoorcamentoanalitica->alterar($c61_reduz,$c61_anousu);
          if($clconplanoorcamentoanalitica->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clconplanoorcamentoanalitica->erro_msg;
          }
        }
      }
    }

    db_fim_transacao($sqlerro);
    $errox_msg=$clorcreceita->erro_msg;
  }else{
    $sqlerro=true;
    $errox_msg="Verifique o código da fonte!";
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
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr>
            <td width="360" height="18">&nbsp;</td>
            <td width="263">&nbsp;</td>
            <td width="25">&nbsp;</td>
            <td width="140">&nbsp;</td>
        </tr>
    </table>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
                <center>
                  <?
                  include(modification("forms/db_frmorcreceita.php"));
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
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($errox_msg);
    if($clorcreceita->erro_campo!=""){
      echo "<script> document.form1.".$clorcreceita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcreceita->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($errox_msg);
    db_redireciona("orc1_orcreceita001.php");
  };
};
?>
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_jsplibwebseller.php"));

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clrechumano       = new cl_rechumano;
$clrechumanoescola = new cl_rechumanoescola;
$cldb_uf           = new cl_db_uf;
$clrhpessoal       = new cl_rhpessoal;
$clrhpesdoc        = new cl_rhpesdoc;
$clrhraca          = new cl_rhraca;
$clrhinstrucao     = new cl_rhinstrucao;
$clrhestcivil      = new cl_rhestcivil;
$clrhnacionalidade = new cl_rhnacionalidade;
$clpais            = new cl_pais;
$clcensouf         = new cl_censouf;
$clcensoorgemissrg = new cl_censoorgemissrg;
$clcensomunic      = new cl_censomunic;
$clcensocartorio   = new cl_censocartorio;

$db_opcao  = 22;
$db_opcao1 = 3;
$db_botao  = false;

if (isset($alterar)) {

  $db_opcao  = 2;
  $db_opcao1 = 3;

  db_inicio_transacao();
  $clrechumano->ed20_i_rhregime = $rh30_codreg;
  $clrechumano->alterar($ed20_i_codigo);
  db_fim_transacao();
} else if (isset($chavepesquisa)) {

  $escola    = db_getsession("DB_coddepto");
  $db_opcao  = 2;
  $db_opcao1 = 3;

  include(modification("funcoes/db_func_rechumanonovo.php"));

  $sSqlRecHumano = $clrechumano->sql_query( "", $camposrechumano, "", "ed20_i_codigo = {$chavepesquisa}" );
  $result        = $clrechumano->sql_record( $sSqlRecHumano );

  db_fieldsmemory( $result, 0 );

  $temregistro = 1;
  $db_botao    = true;
  ?>
  <script>
   parent.document.formaba.a2.disabled    = false;
   parent.document.formaba.a2.style.color = "black";
   parent.document.formaba.a3.disabled    = false;
   parent.document.formaba.a3.style.color = "black";
   parent.document.formaba.a4.disabled    = false;
   parent.document.formaba.a4.style.color = "black";
   parent.document.formaba.a5.disabled    = false;
   parent.document.formaba.a5.style.color = "black";
   parent.document.formaba.a6.disabled    = false;
   parent.document.formaba.a6.style.color = "black";
   parent.document.formaba.a7.disabled    = false;
   parent.document.formaba.a7.style.color = "black";
   parent.document.formaba.a8.disabled    = false;
   parent.document.formaba.a8.style.color = "black";
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href      = 'edu1_telefonerechumano001.php?ed30_i_rechumano=<?=$ed20_i_codigo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href      = 'edu1_formacao001.php?ed27_i_rechumano=<?=$ed20_i_codigo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a8.location.href      = 'edu1_rechumanoescola001.php?ed75_i_rechumano=<?=$ed20_i_codigo?>&ed20_i_tiposervidor=<?=$ed20_i_tiposervidor?>';
   <?php
   $sComplementoUrl  = $ed20_i_tiposervidor == 1 ? "&identificacao={$ed284_i_rhpessoal}" : "&identificacao={$z01_numcgm}";
   $sComplementoUrl .= "&z01_nome=" . addslashes( $z01_nome );
   $sComplementoUrl .= "&ed20_i_tiposervidor=$ed20_i_tiposervidor";

   $sNome = base64_encode($z01_nome);


   if( isset( $ed75_i_codigo ) && !empty( $ed75_i_codigo ) ) {

   ?>

     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = 'edu1_rechumanoativ001.php?ed75_i_rechumano=<?=$ed20_i_codigo?><?=$sComplementoUrl?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href = 'edu1_relacaotrabalho001.php?ed75_i_rechumano=<?=$ed20_i_codigo?>&sNome=<?=$sNome?>';
   <?php
   } else {

   ?>

     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = 'edu1_rechumanoativ001.php?ed75_i_rechumano=<?=$ed20_i_codigo?><?=$sComplementoUrl?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href = 'edu1_relacaotrabalho001.php?ed75_i_rechumano=<?=$ed20_i_codigo?>&sNome=<?=$sNome?>';
   <?php
   }
   ?>
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href = 'edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a7.location.href = 'edu1_rechumanohorario001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a9.location.href = 'edu1_rechumanonecessidade.php?iRecursoHumano=<?=$ed20_i_codigo?>';
  </script>
  <?php
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <br><br>
          <fieldset align="left" style="width:95%"><legend><b>Alteração de Recurso Humano</b></legend>
            <?include(modification("forms/db_frmrechumano.php"));?>
          </fieldset>
        </td>
      </tr>
    </table>
  </body>
</html>
<?php
if( isset( $alterar ) ) {

  if ($clrechumano->erro_status == "0") {

    $clrechumano->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clrechumano->erro_campo != "") {

      echo "<script> document.form1.".$clrechumano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrechumano->erro_campo.".focus();</script>";
    }
  } else {
    db_redireciona("edu1_rechumano002.php?chavepesquisa=$ed20_i_codigo");
  }
}
if( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
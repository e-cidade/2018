<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_caddisciplina_classe.php");
require_once("classes/db_censodisciplina_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clcaddisciplina = new cl_caddisciplina;
$oDaoCadDisciplina      = db_utils::getdao('caddisciplina');
$oDaoAreaConhecimento   = db_utils::getdao('areaconhecimento');
$oDaoCensoCadDisciplina = db_utils::getdao('censocaddisciplina');
$oDaoCensoDisciplina    = db_utils::getdao('censodisciplina');

$db_opcao    = 1;
$db_botao    = true;
$lDiscGlobal = false;
if (isset($incluir)) {

  db_inicio_transacao();
  
  if ($ed232_areaconhecimento != '') {
    $clcaddisciplina->ed232_areaconhecimento = $ed232_areaconhecimento;
  }
  $clcaddisciplina->incluir(null);

  if (isset($webauxilia)) {

    $aDisciplinas = explode("|", $webauxilia);

    for ($iCont = 0; $iCont < count($aDisciplinas); $iCont++) {
      
      $oDaoCensoCadDisciplina = db_utils::getdao('censocaddisciplina');
      
      if (!empty($aDisciplinas[$iCont])) {
        
        $oDaoCensoCadDisciplina->ed294_censodisciplina = $aDisciplinas[$iCont];
        $oDaoCensoCadDisciplina->ed294_caddisciplina   = $clcaddisciplina->ed232_i_codigo;
        $oDaoCensoCadDisciplina->incluir(null);
      
      }

    }

  }

  db_fim_transacao();

}

if (isset($alterar)) {

  $db_opcao = 2;
  
  db_inicio_transacao();
  $clcaddisciplina->ed232_areaconhecimento = $ed232_areaconhecimento;
  $clcaddisciplina->alterar($ed232_i_codigo);

  if (isset($webauxilia)) {

    $oDaoCensoCadDisciplina->excluir("", " ed294_caddisciplina = $ed232_i_codigo ");
    
    if ($oDaoCensoCadDisciplina->erro_status != 0) {
    
      $aDisciplinas = explode("|", $webauxilia);
      
      for ($iCont = 0; $iCont < count($aDisciplinas); $iCont++) {
      
        $oDaoCensoCadDisciplina = db_utils::getdao('censocaddisciplina');
      
        if (!empty($aDisciplinas[$iCont])) {
        
          $oDaoCensoCadDisciplina->ed294_censodisciplina = $aDisciplinas[$iCont];
          $oDaoCensoCadDisciplina->ed294_caddisciplina   = $clcaddisciplina->ed232_i_codigo;
          $oDaoCensoCadDisciplina->incluir(null);
      
        }

      }

    }

  }

  db_fim_transacao();

}

if (isset($excluir)){
  
  $db_opcao = 3;
  
  db_inicio_transacao();
  $oDaoCensoCadDisciplina->excluir("", "ed294_caddisciplina = $ed232_i_codigo");
  $clcaddisciplina->excluir($ed232_i_codigo);
  db_fim_transacao();

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
    <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
          <br>
          <center>
            <fieldset style="width:95%"><legend><b>Cadastro de Disciplinas</b></legend>
              <?
                include("forms/db_frmcaddisciplina.php");
              ?>
            </fieldset>
          </center>
        </td>
      </tr>
    </table>
    <?
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
              db_getsession("DB_anousu"), db_getsession("DB_instit")
             );
    ?>
  </body>
</html>

<script>
  js_tabulacaoforms("form1","ed232_c_descr",true,1,"ed232_c_descr",true);
</script>

<?

if (isset($incluir)) {

  if ($clcaddisciplina->erro_status == "0") {

    $clcaddisciplina->erro(true, false);
    $db_botao = true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clcaddisciplina->erro_campo != "") {

      echo "<script> document.form1.".$clcaddisciplina->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaddisciplina->erro_campo.".focus();</script>";
    
    }

  } else {
    $clcaddisciplina->erro(true, true);
  }

}

if (isset($alterar)) {

  if ($clcaddisciplina->erro_status == "0") {

    $clcaddisciplina->erro(true, false);
    $db_botao = true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clcaddisciplina->erro_campo != "") {

      echo "<script> document.form1.".$clcaddisciplina->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaddisciplina->erro_campo.".focus();</script>";
    
    }

  } else {
    $clcaddisciplina->erro(true, true);
  }

}

if (isset($excluir)) {

  if($clcaddisciplina->erro_status == "0") {
    $clcaddisciplina->erro(true, false);
  } else {
    $clcaddisciplina->erro(true, true);
  }

}

if (isset($cancelar)) {
  echo "<script>location.href='".$clcaddisciplina->pagina_retorno."'</script>";
}

?>
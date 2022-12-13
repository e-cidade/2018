<?PHP
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_conlancamcompl_classe.php"));
require_once(modification("classes/db_conlancamdig_classe.php"));
require_once(modification("classes/db_conlancamdoc_classe.php"));
require_once(modification("classes/db_conplano_classe.php"));

db_postmemory($HTTP_POST_VARS);

$clconplano = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig = new cl_conlancamdig;
$clconlancamdoc = new cl_conlancamdoc;
$clconlancam = new cl_conlancam;

$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession('DB_anousu');

if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])
    == "Incluir") {

  db_putsession("ldia", "$c70_data_dia");
  db_putsession("lmes", "$c70_data_mes");
  db_putsession("llote", "$c78_chave");

  $erro = true;
  if ($c69_debito == "" || $c69_debito == "0") {
    echo "<script> alert('Conta Débito não informada ! '); </script>";
  } else if ($c69_credito == "" || $c69_credito == "0") {
    echo "<script> alert('Conta Credito não informada !  '); </script>";
  } else if ($c69_credito == $c69_debito) {
    echo "<script> alert('Contas não podem ser iguais !  '); </script>";
  } else if ($c69_valor == "" || $c69_valor == "0") {
    echo "<script> alert('Valor não informado ! '); </script>";
  } else if ($c69_codhist == "" || $c69_codhist == "0") {
    echo "<script> alert('Histórico não informado !  '); </script>";
  } else {
    $erro = false;
    db_inicio_transacao();
    $clconlancam->c70_anousu = db_getsession('DB_anousu');
    $clconlancam->c70_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
    $clconlancam->c70_valor = $c69_valor;
    $clconlancam->incluir("");
    $codlan = $clconlancam->c70_codlan; //pega o codigo gerado

    $erro = !EventoContabil::vincularLancamentoNaInstituicao($codlan, db_getsession('DB_instit'));
    if ($c78_chave != "") {
      $clconlancamdig->c78_chave = $c78_chave;
      $clconlancamdig->c78_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
      $clconlancamdig->incluir($codlan);
    }
    if ($c72_complem != "") {
      $clconlancamcompl->c72_complem = $c72_complem;
      $clconlancamcompl->incluir($codlan);
    }

    if ($c71_coddoc == "2000") {
      $clconlancamdoc->c71_codlan = $codlan;
      $clconlancamdoc->c71_coddoc = '2000';
      $clconlancamdoc->c71_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
      $clconlancamdoc->incluir($codlan);
    } else if ($c71_coddoc == "1000") {
      $clconlancamdoc->c71_codlan = $codlan;
      $clconlancamdoc->c71_coddoc = '1000';
      $clconlancamdoc->c71_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
      $clconlancamdoc->incluir($codlan);
    }

    // ------- verifica se o sistema de contas esta correto, por exemplo
    // ------- não pode haver lançemento entre contas Patrimonial X Financeiro
    $r = $clconplano->sql_record($clconplano->sql_query(null, null, "c52_descrred as sistema_debito", null," c61_anousu=$anousu and c61_reduz=$c69_debito"));
    db_fieldsmemory($r, 0);
    $r = $clconplano->sql_record($clconplano->sql_query(null, null, "c52_descrred as sistema_credito",null, "c61_anousu=$anousu and c61_reduz=$c69_credito"));
    db_fieldsmemory($r, 0);
    if ($c71_coddoc == '1000' || $c71_coddoc == '2000') {
      // nda
    } elseif (!USE_PCASP && (($sistema_debito == 'F') && ($sistema_credito == 'P')
                             || ($sistema_debito == 'P') && ($sistema_credito == 'F'))) {
    	
      $erro = true;
      db_msgbox("Não é permitido lançamentos entre o sistema Financeiro e Patrimonial !");
      db_redireciona();
    }
    // ------------------------ * ------------------- * --------------------------
    $clconlancamval->c69_anousu = $anousu;
    $clconlancamval->c69_codlan = $codlan; // codigo do lançamento
    $clconlancamval->c69_codhist = $c69_codhist; // chave estrangeira
    $clconlancamval->c69_debito = $c69_debito;
    $clconlancamval->c69_credito = $c69_credito;
    $clconlancamval->c69_valor = $c69_valor;
    $clconlancamval->c69_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
    $clconlancamval->incluir("");

    if (USE_PCASP) {
      
      $oDaoConLancamDoc = db_utils::getDao('conlancamdoc');
      $oDaoConLancamDoc->c71_codlan = $codlan;
      $oDaoConLancamDoc->c71_coddoc = $iDocumento;
      $oDaoConLancamDoc->c71_data   = "{$c70_data_ano}-{$c70_data_mes}-{$c70_data_dia}";
      $oDaoConLancamDoc->incluir($codlan);
      if ($oDaoConLancamDoc->erro_status == "0") {
  
        db_msgbox("Não foi possível vincular o documento ao lançamento. Procedimento abortado.");
        $erro = true;
      }
    }

    db_fim_transacao($erro);
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>
  	<?php
  	  if (USE_PCASP) {
        require_once(modification("forms/db_frmconlancamval.php"));
      } else {
require_once(modification("forms/db_frmconlancamval_old.php"));
      }
    ?>
</center>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])
    == "Incluir") {
  if ($clconlancamval->erro_status == "0") {
    $clconlancamval->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clconlancamval->erro_campo != "") {
      echo "<script> document.form1." . $clconlancamval->erro_campo
          . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clconlancamval->erro_campo
          . ".focus();</script>";
    }
    ;
  } else {
    $clconlancamval->erro(true, true);
  }
  ;
}
?>
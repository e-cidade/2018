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
require_once(modification("dbforms/db_funcoes.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clpccflicitapar = new cl_pccflicitapar;
$clcflicita      = new cl_cflicita;
$clliclicita     = new cl_liclicita;

$db_opcao = 22;
$db_botao = false;

$anousu = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");

$sMensagemInclusaoAlteracao = "Ano já cadastrado para a modalidade.";
$sMensagemExclusao          = "Já existe licitação desta modalidade cadastrada para o ano informado.";

if (isset($alterar) || isset($excluir) || isset($incluir)) {
    $sqlerro = false;
}

if (isset($incluir)) {
    if ($sqlerro == false) {
        $sWhere          = "l25_codcflicita = {$l25_codcflicita} and l03_instit = {$instit} and l25_anousu = {$l25_anousu}";
        $result_verifica = $clpccflicitapar->sql_record($clpccflicitapar->sql_query_modalidade(null, "*", null, $sWhere));

        if ($clpccflicitapar->numrows > 0) {
            $erro_msg                     = $sMensagemInclusaoAlteracao;
            $clpccflicitapar->erro_status = 0;
            $sqlerro                      = true;
            $l25_anousu                   = "";
            $l25_numero                   = "";
        }

        if (!$sqlerro) {
            db_inicio_transacao();

            $clpccflicitapar->l25_codcflicita = $l25_codcflicita;
            $clpccflicitapar->l25_anousu      = $l25_anousu;
            $clpccflicitapar->incluir(null);

            $erro_msg = $clpccflicitapar->erro_msg;

            if ($clpccflicitapar->erro_status == 0) {
                $sqlerro = true;
            }

            db_fim_transacao($sqlerro);
        }
    }
} else {
    if (isset($alterar)) {
        if ($sqlerro == false) {
            db_inicio_transacao();

            $sWhere          = "l25_codcflicita = {$l25_codcflicita} and l03_instit = {$instit} and l25_numero = {$l25_numero}";
            $result_verifica = $clpccflicitapar->sql_record($clpccflicitapar->sql_query_modalidade(null, "*", null, $sWhere));

            if ($clpccflicitapar->numrows > 0) {
                $erro_msg                     = $sMensagemInclusaoAlteracao;
                $clpccflicitapar->erro_status = 0;
                $sqlerro                      = true;
            }

            if (!$sqlerro) {
                $clpccflicitapar->alterar($l25_codigo);

                $erro_msg = $clpccflicitapar->erro_msg;

                if ($clpccflicitapar->erro_status == 0) {
                    $sqlerro = true;
                }
            }

            db_fim_transacao($sqlerro);
        }
    } else {
        if (isset($excluir)) {
            if ($sqlerro == false) {

                $sWhere          = "l25_codcflicita = {$l25_codcflicita} and l03_instit = {$instit} and l25_anousu = {$l25_anousu} and l25_numero = {$l25_numero}";
                $result_verifica = $clpccflicitapar->sql_record($clpccflicitapar->sql_query_modalidade(null, "*", null, $sWhere));

                if ($clliclicita->numrows > 0) {
                    $erro_msg                 = $sMensagemExclusao;
                    $clpccflicitapar->erro_status = 0;
                    $sqlerro                  = true;
                }

                if (!$sqlerro) {
                    db_inicio_transacao();

                    $clpccflicitapar->excluir($l25_codigo);

                    $erro_msg = $clpccflicitapar->erro_msg;

                    if ($clpccflicitapar->erro_status == 0) {
                        $sqlerro = true;
                    }

                    db_fim_transacao($sqlerro);
                }
            }
        } else {
            if (isset($opcao)) {
                $result = $clpccflicitapar->sql_record($clpccflicitapar->sql_query($l25_codigo));

                if ($result != false && $clpccflicitapar->numrows > 0) {
                    db_fieldsmemory($result, 0);
                }
            }
        }
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
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
        <?php
        include(modification("forms/db_frmpccflicitapar.php"));
        ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {
    db_msgbox($erro_msg);
    if ($clpccflicitapar->erro_campo!="") {
        echo "<script> document.form1.".$clpccflicitapar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpccflicitapar->erro_campo.".focus();</script>";
    }
}

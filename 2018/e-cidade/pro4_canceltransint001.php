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

require(modification('libs/db_stdlib.php'));
require(modification('libs/db_conecta.php'));
include(modification('libs/db_sessoes.php'));
include(modification('libs/db_usuariosonline.php'));
include(modification('classes/db_proctransferint_classe.php'));
include(modification('classes/db_proctransferintusu_classe.php'));
include(modification('classes/db_proctransferintand_classe.php'));
include(modification('dbforms/db_funcoes.php'));

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\MensageriaProcesso;

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clproctransferint = new cl_proctransferint;
$clproctransferintusu = new cl_proctransferintusu;
$clproctransferintand = new cl_proctransferintand;

if (isset($cancel)) {
    db_inicio_transacao();
    $sqlerro = false;

    $resultado = db_query("SELECT p61_codproc FROM procandam WHERE p61_codandam IN ({$listaproc})");

    if ($resultado) {
        $processos = db_utils::getCollectionByRecord($resultado);

        foreach ($processos as $processo) {
            MensageriaProcesso::enviar($processo->p61_codproc, true);
        }
    } else {
        $sqlerro = true;
    }

    $result_cont = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", null,
        "p87_codtransferint=$codtransfer"));

    if ($clproctransferintand->numrows == $contador) {
        $clproctransferintand->excluir(null, "p87_codtransferint=$codtransfer");
        $erro = $clproctransferintand->erro_msg;
        if ($clproctransferintand->erro_status == 0) {
            $sqlerro = true;
        }
        if ($sqlerro == false) {
            $clproctransferintusu->excluir(null, "p89_codtransferint=$codtransfer");
            if ($clproctransferintusu->erro_status == 0) {
                $sqlerro = true;
            }
        }
        if ($sqlerro == false) {
            $clproctransferint->excluir($codtransfer);
            $erro = $clproctransferint->erro_msg;
            if ($clproctransferint->erro_status == 0) {
                $sqlerro = true;
            }
        }
    } else {
        $clproctransferintand->excluir(null, "p87_codtransferint=$codtransfer and p87_codandam in ($listaproc)");
        $erro = $clproctransferintand->erro_msg;
        if ($clproctransferintand->erro_status == 0) {
            $sqlerro = true;
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
    <script>
    </script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" style='margin-top: 25px' marginheight="0"
      onLoad="a=1">
<?php

include(modification('forms/db_frmcanceltransint.php'));

if (isset($cancel)) {
    db_msgbox($erro);
    if ($sqlerro == true) {
        echo "<script> document.form1." . $clproctransferintand->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clproctransferintand->erro_campo . ".focus();</script>";
    } else {
        echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='pro4_canceltransint001.php';</script>";
    }
}
?>
</body>
<?php

db_menu(
    db_getsession('DB_id_usuario'),
    db_getsession('DB_modulo'),
    db_getsession('DB_anousu'),
    db_getsession('DB_instit')
);

?>
</html>
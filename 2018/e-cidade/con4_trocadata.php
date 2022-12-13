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
$data_mes = '';
$data_dia = '';
$data_ano = '';

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);

$erro     = false;
$sErroSmg = 'Data inválida, verifique.';

if (isset($atualiza)) {
    $oPost = db_utils::postMemory($_POST);

    $oDataAtual = new DBDate($oPost->dataservidor);
    $oDataNova  = new DBDate($oPost->data);

    if ($oDataNova->getTimeStamp() > $oDataAtual->getTimeStamp()) {
        $sErroSmg .= ' Data do Sistema deve ser menor que a Data do Servidor.';
        $erro = true;
    }

    $datatime = mktime(12, 0, 0, $data_mes, $data_dia, $data_ano);
    $verdata  = checkdate($data_mes, $data_dia, $data_ano);
    if ($verdata && !$erro) {
        db_putsession("DB_datausu", $datatime);
        db_putsession("DB_anousu", date("Y", $datatime));

        // atualiza arquivo com a data para posterior verificacao
        $sSqlDataUsuarios  = "delete from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
        $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);

        $sSqlDataUsuarios  = "insert into db_datausuarios( id_usuario,                             ";
        $sSqlDataUsuarios .= "                             data )                                  ";
        $sSqlDataUsuarios .= "                    values ( ".db_getsession("DB_id_usuario").",     ";
        $sSqlDataUsuarios .= "                             '{$data_ano}-{$data_mes}-{$data_dia}' ) ";
        $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);
    } else {
        $erro = true;
    }
}

if (isset($adata) && !$erro) {
    $datatime = mktime(12, 0, 0, date("m"), date("d"), date("Y"));
    db_putsession("DB_datausu", $datatime);
    db_putsession("DB_anousu", date("Y", $datatime));

    $sSqlDataUsuarios  = "delete from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
    $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);
}

$lMostrarMenu = true;
if (!empty($lParametroExibeMenu) && $lParametroExibeMenu === "false") {
    $lMostrarMenu = false;
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
    <body>
        <div class="container">
            <form name="form1" method="post">
                <fieldset>
                <legend>Retorna data do sistema</legend>
                    <table class="form-container">
                        <tr>
                            <td><label for="data">Data do Sistema:</label></td>
                            <td>
                                <?php
                                $sSqlDataUsuarios  = "select * from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
                                $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);
                                if (pg_numrows($rsSqlDataUsuarios) > 0) {
                                    $anousu = substr(pg_result($rsSqlDataUsuarios, 0, 'data'), 0, 4);
                                    $mesusu = substr(pg_result($rsSqlDataUsuarios, 0, 'data'), 5, 2);
                                    $diausu = substr(pg_result($rsSqlDataUsuarios, 0, 'data'), 8, 2);
                                } else {
                                    $anousu = date("Y", db_getsession("DB_datausu"));
                                    $mesusu = date("m", db_getsession("DB_datausu"));
                                    $diausu = date("d", db_getsession("DB_datausu"));
                                }

                                db_inputdata('data', $diausu, $mesusu, $anousu, true, 'text', 1, "");
                                ?>
                            </td>
                            <td align="left" >
                                <input name="atualiza" type="submit" value="Atualizar">
                            </td>
                        </tr>

                        <tr>
                            <td ><label for="dataservidor">Data do Servidor:</label></td>
                            <td >
                                <?php
                                    $datatime = mktime(12, 0, 0, date("m"), date("d"), date("Y"));

                                    $anousu   = date("Y", $datatime);
                                    $mesusu   = date("m", $datatime);
                                    $diausu   = date("d", $datatime);

                                    db_inputdata('dataservidor', $diausu, $mesusu, $anousu, true, 'text', 3, "");
                                ?>
                            </td>
                            <td  >
                               <input name="adata" type="submit" value="Atualizar">
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </div>

        <?php
        if ($lMostrarMenu) {
            db_menu();
        }
        ?>
    </body>
</html>
<?php
if (isset($atualiza) || isset($adata)) {
    $sParentAdicional = "";
    if (!$lMostrarMenu) {
        $sParentAdicional = "parent.";
    }

    $sJs  = "<script>";

    $sJs .= "   var oDtAtual = parent.{$sParentAdicional}bstatus.document.getElementById('dtatual'); ";
    $sJs .= "   var oDtAnoUsu = parent.{$sParentAdicional}bstatus.document.getElementById('dtanousu'); ";
    $sJs .= "   var sDtAtual = '".date("d/m/Y", db_getsession("DB_datausu"))."'; ";
    $sJs .= "   var sDtAnoUsu = '".db_getsession("DB_anousu")."'; ";

    $sJs .= "   if (oDtAtual === null && oDtAnoUsu === null) {";
    $sJs .= "       oDtAtual = (window.CurrentWindow || parent.CurrentWindow).bstatus.document.querySelector('#content div:nth-child(3) span');";
    $sJs .= "       oDtAnoUsu = (window.CurrentWindow || parent.CurrentWindow).bstatus.document.querySelector('#content div:nth-child(3) span:nth-child(2)');";
    $sJs .= "       sDtAtual = '<b>Data:</b> ' + sDtAtual;";
    $sJs .= "       sDtAnoUsu = '<b>Exercício:</b> ' + sDtAnoUsu;";
    $sJs .= "   } ";

    $sJs .= "   oDtAtual.innerHTML = sDtAtual;";
    $sJs .= "   oDtAnoUsu.innerHTML = sDtAnoUsu;";

    $sJs .= "</script>";

    echo $sJs;
}

if ($erro == true) {
    echo "<script>alert('$sErroSmg')</script>";
}
?>

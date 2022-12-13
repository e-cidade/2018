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
include(modification('libs/db_sql.php'));
include(modification('libs/db_sessoes.php'));
include(modification('libs/db_usuariosonline.php'));
include(modification('dbforms/db_funcoes.php'));
include(modification('classes/db_proctransferint_classe.php'));
include(modification('classes/db_proctransferintand_classe.php'));
include(modification('classes/db_proctransferintusu_classe.php'));
include(modification('classes/db_protprocesso_classe.php'));
include(modification('classes/db_procandamint_classe.php'));
include(modification('classes/db_procandamintand_classe.php'));

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\MensageriaProcesso;

$clprocandamint = new cl_procandamint;
$clprocandamintand = new cl_procandamintand;
$clproctransferint = new cl_proctransferint;
$clproctransferintand = new cl_proctransferintand;
$clproctransferintusu = new cl_proctransferintusu;
$clprotprocesso = new cl_protprocesso;

$clproctransferint->rotulo->label();
$clprotprocesso->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('p61_id_usuario');
$clrotulo->label('p68_codproc');
$clrotulo->label('p89_usuario');
$clrotulo->label('nome');

db_postmemory($HTTP_POST_VARS);

if (isset($incluir)) {
    db_inicio_transacao();
    $sqlerro = false;
    $data = date("Y-m-d", db_getsession("DB_datausu"));
    $clproctransferint->p88_despacho = $p88_despacho;
    $clproctransferint->p88_data = $data;
    $clproctransferint->p88_hora = db_hora();
    $clproctransferint->p88_usuario = db_getsession("DB_id_usuario");
    $clproctransferint->p88_publico = $p88_publico;
    $clproctransferint->incluir(null);
    $erro_msg = $clproctransferint->erro_msg;
    if ($clproctransferint->erro_status == 0) {
        $sqlerro = true;
    }

    $codigo = $clproctransferint->p88_codigo;

    if ($sqlerro == false) {
        $clproctransferintusu->p89_codtransferint = $codigo;
        $clproctransferintusu->p89_usuario = $p89_usuario;
        $clproctransferintusu->incluir();
        $erro_msg = $clproctransferintusu->erro_msg;
        if ($clproctransferintusu->erro_status == 0) {
            $sqlerro = true;
        }
    }
    if ($sqlerro == false) {
        $vt = $HTTP_POST_VARS;
        $ta = sizeof($vt);
        reset($vt);
        for ($i = 0; $i < $ta; $i++) {
            $chave = key($vt);
            if (substr($chave, 0, 5) == "CHECK") {
                $dados = split("_", $chave);
                $result1 = $clprotprocesso->sql_record($clprotprocesso->sql_query_file($dados[1],
                    'p58_codandam, p58_codproc'));
                db_fieldsmemory($result1, 0);
                $clproctransferintand->p87_codtransferint = $codigo;
                $clproctransferintand->p87_codandam = $p58_codandam;
                $clproctransferintand->incluir();
                $erro_msg = $clproctransferintand->erro_msg;
                if ($clproctransferintand->erro_status == 0) {
                    $sqlerro = true;
                }
                if (isset($p89_usuario) && $p89_usuario && $p89_usuario != db_getsession('DB_id_usuario') && isset($p58_codproc) && $p58_codproc) {
                    MensageriaProcesso::enviar($p58_codproc);
                }
            }
            $proximo = next($vt);
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
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script>
        function js_marca(obj) {
            var OBJ = document.form1;
            for (i = 0; i < OBJ.length; i++) {
                if (OBJ.elements[i].type == 'checkbox') {
                    OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
                }
            }
            return false;
        }
    </script>
    <style>
        .cabec {
            text-align: center;
            color: darkblue;
            background-color: #aacccc;
            font-weight: bold;
            border-color: darkblue;
        }

        .corpo {
            color: black;
            background-color: #ccddcc;
        }
    </style>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" style='margin-top: 25px' topmargin="0" marginwidth="0" marginheight="0"
      onLoad="a=1" bgcolor="#cccccc">
<center>
    <form name="form1" method="post" target="" action="pro4_tranferinter001.php">
        <table>
            <tr>
                <td>
                    <fieldset>
                        <legend><b> Dados do Despacho interno</b></legend>
                        <table>
                            <tr>
                                <td nowrap title="<?= @$Tp88_despacho ?>" align='left' colspan=2>
                                    <?= @$Lp88_despacho ?>
                                    <?
                                    db_textarea('p88_despacho', 0, 80, $Ip88_despacho, true, 'text', 1, "")
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td width=50 nowrap title="<?= @$Tp89_usuario ?>">
                                    <?= @$Lp89_usuario; ?>
                                </td>
                                <td>
                                    <?
                                    $sqlusu = "SELECT 
                     U.id_usuario,nome
	      FROM db_usuarios U 
	             INNER JOIN db_depusu D ON U.id_usuario  = D.id_usuario
   	      WHERE  D.coddepto = " . db_getsession("DB_coddepto") . "
	      ORDER BY nome ";
                                    echo "<select  name='p89_usuario' size='-1'>";
                                    echo "<option value=0>Selecione</Option>";
                                    $rs = db_query($sqlusu);
                                    for ($i = 0; $i < pg_num_rows($rs); $i++) {
                                        db_fieldsmemory($rs, $i);
                                        echo "<option value='" . $id_usuario . "'>" . $nome . "</option>";
                                    }
                                    echo "</select>";
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tp88_publico ?>">
                                    <?= @$Lp88_publico ?>
                                </td>
                                <td>
                                    <?
                                    $x = array("t" => "Sim", "f" => "Não");
                                    db_select('p88_publico', $x, true, 1, "");

                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan=2 align='center'>
                    <input name="incluir" type="submit" value="Transferir">
                </td>
            </tr>
            <td colspan=2 align='center'>
                <table>
                    <tr>
                        <td class='cabec' title='Inverte marcação' align='center'>
                            <a title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
                        <td class='cabec' align='center'>Número de Controle</td>
                        <td class='cabec' align='center'>Número do Processo</td>
                        <td class='cabec' align='center'><b>Situação</b></td>
                        <td class='cabec' align='center'>Data do Processo</td>
                        <td class='cabec' align='center'>Hora</td>
                        <td class='cabec' align='center'>Nome/Razão Social</td>
                    </tr>
                    <?
                    $sql = "SELECT * FROM (
				   SELECT p58_codproc,
					  p58_requer,
					  p58_dtproc,
					  p58_numero||'/'||cast(p58_ano AS VARCHAR) AS p58_numero,
					  p58_hora,
					  z01_nome,
					  p58_codandam,
					  p61_id_usuario,
					  p61_codandam,
					  arqproc.p68_codproc
				   FROM   protprocesso
					  INNER JOIN cgm ON p58_numcgm = z01_numcgm
					  INNER JOIN procandam ON p58_codandam = p61_codandam
					  LEFT JOIN arqproc ON arqproc.p68_codproc = protprocesso.p58_codproc
				   WHERE ( p61_coddepto = " . db_getsession("DB_coddepto") . ")  ) AS x																	                   
				   WHERE   x.p68_codproc IS NULL ORDER BY p58_codproc DESC ";
                    $result = db_query($sql);
                    $numrows = pg_numrows($result);
                    if ($numrows > 0) {
                    } else {
                        echo "<br><br><b>Sem Processos!!</b>";
                    }
                    $usuario = db_getsession("DB_id_usuario");

                    for ($i = 0; $i < $numrows; $i++) {
                        if ($i == 0) {
                            echo "<br><br>";
                        }
                        db_fieldsmemory($result, $i);
                        $passou = true;
                        $sql_proc = "select  p63_codproc,p63_codtran 
	                      from proctransferproc 
                       where p63_codproc = $p58_codproc";

                        $result_proc = db_query($sql_proc);
                        if (pg_numrows($result_proc) != 0) {
                            for ($yy = 0; $yy < pg_numrows($result_proc); $yy++) {
                                db_fieldsmemory($result_proc, $yy);
                                $sql_and = "select * from proctransand where p64_codtran=$p63_codtran";
                                $result_and = db_query($sql_and);
                                if (pg_numrows($result_and) == 0) {
                                    $passou = false;
                                }
                            }
                        }

                        if ($passou == true) {
                            $result_andam = $clprocandamint->sql_record($clprocandamint->sql_query_file(null,
                                "p78_sequencial", "p78_data desc ,p78_hora desc limit 1 ",
                                "p78_codandam=$p61_codandam"));
                            $numrows_andam = $clprocandamint->numrows;

                            if ($numrows_andam != 0) {
                                $result_trand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null,
                                    "p87_codtransferint", "p87_codtransferint desc limit 1",
                                    "p87_codandam=$p61_codandam "));
                                $numrows_trand = $clproctransferintand->numrows;
                                if ($numrows_trand != 0) {
                                    db_fieldsmemory($result_trand, 0);
                                    $result_intand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null,
                                        "*", null, "p86_codandam=$p61_codandam and p86_codtrans=$p87_codtransferint"));
                                    $numrows_intand = $clprocandamintand->numrows;
                                    if ($numrows_intand != 0) {
                                        db_fieldsmemory($result_andam, 0);
                                        $result_usu = $clprocandamint->sql_record($clprocandamint->sql_query_file(null,
                                            "*", "p78_data desc,p78_hora desc limit 1 ",
                                            "p78_codandam=$p61_codandam and p78_usuario=$usuario and p78_sequencial=$p78_sequencial"));
                                        $numrows_usu = $clprocandamint->numrows;
                                        if ($numrows_usu != 0) {
                                            echo "
              		   <tr>
              		      <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
              		      <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
              		      <td  class='corpo'  align='center' title='Situação'><label style=\"cursor: hand\"><small>Liberado</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                                    'd') . "</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
              		   </tr>";
                                        } else {
                                            echo "
              		   <tr>
              		      <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' disabled  name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
              		      <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
              		      <td  class='corpo'  align='center' title='Situção'><label style=\"cursor: hand\"><small>Processo com outro usuário</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                                    'd') . "</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
              		      <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
              		   </tr>";
                                        }
                                    } else {
                                        $result_usudes = $clproctransferintusu->sql_record($clproctransferintusu->sql_query_usu(null,
                                            "*",
                                            null, "p89_codtransferint=$p87_codtransferint"));
                                        db_fieldsmemory($result_usudes, 0);
                                        echo "
            		   <tr>
             		      <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' disabled  name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
             		      <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
             		      <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
             		      <td  class='corpo'  align='center' title='Situação'><label style=\"cursor: hand\"><small>Em Transferência p/ $nome</small></label></td>
             		      <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                                'd') . "</small></label></td>
             		      <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
             		      <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
             		   </tr>";
                                    }
                                } else {
                                    $result_trans = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null,
                                        "*", null, "p87_codandam=$p61_codandam"));
                                    $numrows_trans = $clproctransferintand->numrows;
                                    if ($numrows_trans != 0) {
                                        db_fieldsmemory($result_trans, 0);
                                        $result_usudes = $clproctransferintusu->sql_record($clproctransferintusu->sql_query_usu(null,
                                            "*", null,
                                            "p89_codtransferint=$p87_codtransferint"));
                                        db_fieldsmemory($result_usudes, 0);
                                        echo "
	       <tr>
		  <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' disabled  name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
		  <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
		  <td  class='corpo'  align='center' title='Situação'><label style=\"cursor: hand\"><small>Em Transferência p/ $nome </small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                                'd') . "</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
		  <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
	       </tr>";
                                    } else {
                                        echo "
	       <tr>
		  <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
		  <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
		  <td  class='corpo'  align='center' title='Situação'><label style=\"cursor: hand\"><small>Liberado</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                                'd') . "</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
		  <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
	       </tr>";
                                    }
                                }
                            } else {
                                $result_trans = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null,
                                    "*", null, "p87_codandam=$p61_codandam"));
                                $numrows_trans = $clproctransferintand->numrows;
                                if ($numrows_trans != 0) {
                                    db_fieldsmemory($result_trans, 0);
                                    $result_usudes = $clproctransferintusu->sql_record($clproctransferintusu->sql_query_usu(null,
                                        "*", null,
                                        "p89_codtransferint=$p87_codtransferint"));
                                    db_fieldsmemory($result_usudes, 0);
                                    echo "
	       <tr>
		  <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' disabled  name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
		  <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
		  <td  class='corpo'  align='center' title='Situação'><label style=\"cursor: hand\"><small>Em Transferência p/ $nome</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                            'd') . "</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
		  <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
	       </tr>";
                                } else {
                                    echo "
	       <tr>
		  <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p58_codproc' id='CHECK_" . $p58_codproc . "'></td>
		  <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>$p58_numero</small></label></td>
		  <td  class='corpo'  align='center' title='Situação'><label style=\"cursor: hand\"><small>Liberado</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>" . db_formatar($p58_dtproc,
                                            'd') . "</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
		  <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
	       </tr>";
                                }
                            }
                        }
                    }
                    echo "
	   </table>";


                    ?>
                    </td>
                    </tr>
                </table>
    </form>
</center>
<? db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
    db_getsession("DB_instit")); ?>
<script>
    function js_pesquisa_usuario(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_db_usuarios', 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome', 'Pesquisa', true);
        } else {
            if (document.form1.p89_usuario.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_db_usuarios', 'func_db_usuarios.php?pesquisa_chave=' + document.form1.p89_usuario.value + '&funcao_js=parent.js_mostradb_usuarios', 'Pesquisa', false);
            } else {
                document.form1.nome_dest.value = '';
            }
        }
    }

    function js_mostradb_usuarios(chave, erro) {
        document.form1.nome_dest.value = chave;
        if (erro == true) {
            document.form1.p89_usuario.focus();
            document.form1.p89_usuario.value = '';
        }
    }

    function js_mostradb_usuarios1(chave1, chave2) {
        document.form1.p89_usuario.value = chave1;
        document.form1.nome_dest.value = chave2;
        db_iframe_db_usuarios.hide();
    }
</script>
<?php

if (isset($incluir)) {
    db_msgbox($erro_msg);
    if ($sqlerro == true) {
        echo "<script> document.form1." . $clproctransferint->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clproctransferint->erro_campo . ".focus();</script>";
    } else {
        echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='pro4_tranferinter001.php';</script>";
    }
}

?>
</body>
</html>
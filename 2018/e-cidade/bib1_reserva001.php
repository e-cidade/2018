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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$clreserva  = new cl_reserva;
$clcarteira = new cl_carteira;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;
$erro      = false;
$depto     = db_getsession("DB_coddepto");

$sql    = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);;
$linhas = pg_num_rows($result);

if ($linhas != 0) {
  db_fieldsmemory($result,0);
}

if (isset($incluir)) {

  $datadigitada = $bi14_datareserva_ano.$bi14_datareserva_mes.$bi14_datareserva_dia;
  $horadigitada = str_replace(":","",$bi14_hora);
  $horaatual    = date("Hi");
  
  $sCamposCarteira = "bi16_leitor as codleitor, bi16_validade";
  $sWhereCarteira  = " bi16_codigo = $bi14_carteira AND bi07_biblioteca = $bi17_codigo";
  $sSqlCarteira    = $clcarteira->sql_query("", $sCamposCarteira, "bi16_validade desc", $sWhereCarteira);
  $resultY         = $clcarteira->sql_record($sSqlCarteira);
  
  if ($bi14_carteira != "" && $clcarteira->numrows == 0) {
    
    ?>
    <script>
     if (confirm("Leitor NÃO possui Carteira cadastrada. Deseja cadastrar carteira para o leitor?")) {
       location.href = "bib1_leitor000.php?opcao=2&chavepesquisa=<?=$bi14_carteira?>";
     }
    </script>
    <?
    $erro = true;
  } else if (   $bi14_carteira != "" && $clcarteira->numrows > 0 
             && str_replace("-", "", pg_result($resultY, 0, 'bi16_validade')) - date("Ymd") < 0) {

    db_fieldsmemory($resultY,0);
    ?>
    <script>
     if (confirm("Leitor está com Carteira VENCIDA. Deseja validar outra carteira para o leitor?")) {
       location.href = "bib1_leitor000.php?opcao=2&chavepesquisa=<?=$codleitor?>";
     }
    </script>
    <?
    $erro = true;
  } else if ($datadigitada - date("Ymd") < 0) {

    db_msgbox("Data da Reserva deve ser igual ou posterior a data de hoje!");
    $erro = true;
  } else if ($datadigitada - date("Ymd") == 0 && $horadigitada < $horaatual) {

    db_msgbox("Hora da Reserva informada já ultrapassada!");
    $erro = true;
  } else {

    db_inicio_transacao();
    $clreserva->bi14_situacao = "A";
    $clreserva->bi14_usuario  = db_getsession("DB_id_usuario");
    $clreserva->bi14_data     = date("Y-m-d");
    $clreserva->incluir($bi14_codigo);
    db_fim_transacao();
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
   <br>
   <center>
   <fieldset style="display:table"><legend><b>Cadastro de Reservas</b></legend>
    <?require_once ("forms/db_frmreserva.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:92%"><legend><b>Reservas em aberto:</b></legend>
    <?
    $campos = "ov02_nome as ov02_nome2,
               bi14_codigo as bi14_codigo2,
               bi14_data as bi14_data2,
               bi14_datareserva as bi14_datareserva2,
               bi14_hora as bi14_horareserva2,
               bi06_titulo as bi06_titulo2";
    
    $sOrderReserva = "bi14_datareserva desc, bi14_hora desc";
    $sWhereReserva = " bi07_biblioteca = $bi17_codigo AND bi14_situacao = 'A'";
    $sSqlReserva   = $clreserva->sql_query_acervo_leitorcidadao("", $campos, $sOrderReserva, $sWhereReserva);
    $result        = $clreserva->sql_record($sSqlReserva);
    
    if ($clreserva->numrows > 0) {
      ?>
      <table width="740" border="1" cellspacing="0" cellpadding="0">
        <tr align="center" bgcolor="#888888">
          <td><b>Código</b></td>
          <td><b>Leitor</b></td>
          <td><b>Título</b></td>
          <td><b>Data</b></td>
          <td><b>Reserva para</b></td>
          <td><b>Hora</b></td>
          <td><b>Situação</b></td>
        </tr>
      <?
      for ($y = 0; $y < $clreserva->numrows; $y++) {

        db_fieldsmemory($result,$y);
        ?>
        <tr bgcolor="#f3f3f3" 
            onclick="location.href='bib1_reserva002.php?chavepesquisa=<?=$bi14_codigo2?>'" 
            onmouseover="bgColor='#DEB887';" 
            onmouseout="bgColor='#f3f3f3';" 
            style="Cursor:hand;">
          <td align="center" >
            <?=$bi14_codigo2?>
          </td>
          <td>
            <?=$ov02_nome2?>
          </td>
          <td>
            <?=$bi06_titulo2?>
          </td>
          <td align="center" >
            <?=db_formatar($bi14_data2, 'd')?>
          </td>
          <td align="center" >
            <?=db_formatar($bi14_datareserva2, 'd')?>
          </td>
          <td align="center" >
            <?=$bi14_horareserva2?>
          </td>
          <?
          $difdata = str_replace("-","",$bi14_datareserva2) - date("Ymd");
          $difhora = str_replace(":","",$bi14_horareserva2) - date("Hi");
          
          if ($difdata < 0 || ($difdata == 0 && $difhora < 0) ) {

            $situacao = "VENCIDA";
            $cor      = "red";
          } else {

            $situacao = "NORMAL";
            $cor = "green";
          }
          ?>
          <td align="center" style="color:#FFFFFF;background:<?=$cor?>">
           <?=$situacao?>
          </td>
        </tr>
        <?
      }
     ?>
     </table>
     <?
    } else {
      echo "Nenhuma reserva em aberto.";
    }
    ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</center>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","bi14_carteira",true,1,"bi14_carteira",true);
function MostraID(id) {
  document.getElementById(id).style.visibility = "visible";
}

function OcultaID(id) {
  document.getElementById(id).style.visibility = "hidden";
}
</script>
<?
if (isset($incluir)) {

  if ($clreserva->erro_status == "0") {

    $clreserva->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clreserva->erro_campo != "") {

      echo "<script> document.form1.".$clreserva->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clreserva->erro_campo.".focus();</script>";
    }
  } else {

    if ($erro == false) {

      $clreserva->erro(true,false);
      db_redireciona("bib1_reserva002.php?chavepesquisa=".$clreserva->bi14_codigo);
    } else {
      $clreserva->erro(true,true);
    }
  }
}
?>
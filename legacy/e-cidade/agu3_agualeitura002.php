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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_agualeitura_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clagualeitura = new cl_agualeitura;
$clagualeitura->rotulo->label();

$sql = "";
if (isset($matric) && trim($matric) != "") {

  $campos = "
    x21_codleitura,
    x21_aguacontrato,
    x21_exerc,
    x21_mes,
    x21_dtleitura as db_x21_dtleitura,
    x21_leitura,
    x17_descr,
    
    (case when x21_excesso >= 0 then
      x21_consumo + x21_excesso
    else
      x21_consumo
    end) as x21_consumo,
    
    (case when x21_excesso < 0 then
      0
    else
      x21_excesso
    end) as x21_excesso,

    x21_saldo,
    fc_agua_saldocompensado(x21_exerc, x21_mes, x04_matric) as x34_saldoutilizado,
    
    (CASE WHEN x21_tipo = 1 THEN
      'Digitação Manual'
    WHEN x21_tipo = 2 THEN
      'Exportada Coletor'
    ELSE
      'Importada Coletor'
    END) as x21_tipo,
    
    (CASE WHEN x21_status = 1 THEN
      'Ativo'
    WHEN x21_status = 2 THEN
      'Inativo'
    ELSE
      'Cancelado'
    END) as x21_status,

    x21_dtleitura,
    x21_numcgm,
    z01_nome
  ";

  $dbwhere = "";

  if(!isset($chave_x21_exerc) && !isset($chave_x21_mes) && !isset($chave_x21_exercfim) && !isset($chave_x21_mesfim)){
    if(isset($ano) && trim($ano) != "" && isset($mes) && trim($mes) != "") {
      $chave_x21_exercfim = $ano;
      $chave_x21_mesfim = $mes;
      $dbwhere .= " fc_anousu_mesusu(x21_exerc, x21_mes) between fc_anousu_mesusu($ano,1) and fc_anousu_mesusu($ano,$mes) and ";
    } else {
      if(isset($ano) && trim($ano) != ""){
        $dbwhere.= " x21_exerc = ".$ano." and ";
        $chave_x21_exercfim = $ano;
      }
      if(isset($mes) && trim($mes) != ""){
        $dbwhere.= " x21_mes = ".$mes." and ";
        $chave_x21_mesfim = $mes;
      }
    }
    $chave_x21_mes = 1;
  }else{
    if(trim($chave_x21_exerc) != "" && trim($chave_x21_mes) != "" && trim($chave_x21_exercfim) != "" && trim($chave_x21_mesfim) != ""){
      $dbwhere.= " trim(to_char(x21_exerc,'0000'))||trim(to_char(x21_mes,'00')) between '".db_formatar($chave_x21_exerc,'s','0',4,'e',0).db_formatar($chave_x21_mes,'s','0',2,'e',0)."' and '".db_formatar($chave_x21_exercfim,'s','0',4,'e',0).db_formatar($chave_x21_mesfim,'s','0',2,'e',0)."' and ";
    }else if(trim($chave_x21_exerc) != "" && trim($chave_x21_mes) != ""){
      $dbwhere.= " trim(to_char(x21_exerc,'0000'))||trim(to_char(x21_mes,'00')) > '".db_formatar($chave_x21_exerc,'s','0',4,'e',0).db_formatar($chave_x21_mes,'s','0',2,'e',0)."' and ";
    }else if(trim($chave_x21_exercfim) != "" && trim($chave_x21_mesfim) != ""){
      $dbwhere.= " trim(to_char(x21_exerc,'0000'))||trim(to_char(x21_mes,'00')) < '".db_formatar($chave_x21_exercfim,'s','0',4,'e',0).db_formatar($chave_x21_mesfim,'s','0',2,'e',0)."' and ";
    }
  }

  $sql = $clagualeitura->sql_query_anteriores("",$campos,"x21_exerc desc, x21_mes desc, x21_codleitura desc",$dbwhere." x04_matric = ".$matric );
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
<form name="form1">
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap title="<?=@$Tx21_exerc?>" align="right">
      <b><?=@$RLx21_exerc?>&nbsp;/&nbsp;<?=@$RLx21_mes?> inicial:</b>
    </td>
    <td nowrap>
      <?
      if(!isset($chave_x21_exerc) || (isset($chave_x21_exerc) && trim($chave_x21_exerc) == "")){
	$chave_x21_exerc = db_getsession("DB_anousu");
      }
      db_input('x21_exerc',4,$Ix21_exerc,true,'text',1,"","chave_x21_exerc");
      ?>
      <b>&nbsp;/&nbsp;</b>
      <?
      if(!isset($chave_x21_mes) || (isset($chave_x21_mes) && trim($chave_x21_mes) == "")){
	$chave_x21_mes = date("m",db_getsession("DB_datausu"));
      }
      db_input('x21_mes',2,$Ix21_mes,true,'text',1,"","chave_x21_mes");
      db_input('matric',4,0,true,'hidden',3,"","");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx21_exerc?>" align="right">
      <b><?=@$RLx21_exerc?>&nbsp;/&nbsp;<?=@$RLx21_mes?> final:</b>
    </td>
    <td nowrap>
      <?
      if(!isset($chave_x21_exerc) || (isset($chave_x21_exerc) && trim($chave_x21_exerc) == "")){
	$chave_x21_exerc = db_getsession("DB_anousu");
      }
      db_input('x21_exerc',4,$Ix21_exerc,true,'text',1,"","chave_x21_exercfim");
      ?>
      <b>&nbsp;/&nbsp;</b>
      <?
      if(!isset($chave_x21_mes) || (isset($chave_x21_mes) && trim($chave_x21_mes) == "")){
	$chave_x21_mes = date("m",db_getsession("DB_datausu"));
      }
      db_input('x21_mes',2,$Ix21_mes,true,'text',1,"","chave_x21_mesfim");
      ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2" valign="top" bgcolor="#CCCCCC">
      <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_anterior.hide();">
    </td>
  </tr>
</table>
</form>
<?php db_lovrot($sql,20,"()"); ?>
</center>
</body>
</html>

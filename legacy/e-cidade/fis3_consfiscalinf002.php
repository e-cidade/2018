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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_fiscal_classe.php");
require_once("classes/db_fiscallocal_classe.php");
require_once("classes/db_fiscalexec_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clfiscal      = new cl_fiscal;
$clfiscallocal = new cl_fiscallocal;
$clfiscalexec  = new cl_fiscalexec;
$sql    = " ";
$result = $clfiscal->sql_record($clfiscal->sql_query($codfiscal));
$num    = pg_numrows($result);

?>

<html>
<head>
<title>Dados da Inscri&ccedil;&atilde;o - BCI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>

<?
  if ($num > 0) {  // verifica se a matricula passada como parametro encontrou registro no sql acima
    db_fieldsmemory($result,0,true);
?>

<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC">
    <td colspan="4" align="center"><font color="#333333"><strong><b>&nbsp;DADOS DO
      AUTO DE INFRA&Ccedil;&Atilde;O&nbsp;</b></strong></font><font color="#666666"><strong>
      </strong></font></td>
  </tr>
  <tr>
    <td width="100" align="right" nowrap bgcolor="#CCCCCC">&nbsp;AUTO n&ordm;:&nbsp;</td>
    <td width="165" align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$y50_codauto?>
      &nbsp; </strong></font></td>
    <td width="100"  align="right" nowrap bgcolor="#CCCCCC">&nbsp;N° DO BLOCO: </td>
    <td width="165" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;<?=$y50_numbloco?>
      </strong></font></td>
  </tr>
  <?
  $result_busca= $clauto->sql_record($clauto->sql_query_busca($codauto));
  if ($clauto->numrows>0){
    db_fieldsmemory($result_busca,0);
  }
  ?>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;IDENTIFICAÇÃ0:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$dl_identificacao?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">C&oacute;digo Ident.:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$dl_codigo?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Tipo de Fiscalização:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$tipo?>
      &nbsp; </strong></font></td>


    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Obs:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=substr($y50_obs,0,25)?>
      &nbsp; </strong></font></td>


  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Departamento:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$coddepto."-".$descrdepto?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Nome da Pessoa Autuada:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$y50_nome?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Data:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$y50_data?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Hora:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$y50_hora?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Vencimento Atual:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$y50_dtvenc?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Prazo p/ Recurso:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$y50_prazorec?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
     <td align="center" nowrap bgcolor="#CCCCCC" colspan=4 ><b>Endereço Registrado:&nbsp;</b></td>
  </tr>
  <?
  $result_local=$clautolocal->sql_record($clautolocal->sql_query($codauto));
  if ($clautolocal->numrows>0){
    db_fieldsmemory($result_local,0,true);
  }
  ?>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Rua:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$j14_nome?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">n&deg; : </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$y14_numero?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Bairro:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp;
      <?=$j13_descr?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Complemento :&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp;
      <?=$y14_compl?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="center" nowrap bgcolor="#CCCCCC" colspan=4 ><b>Endereço Localizado:&nbsp;</b></td>
  </tr>
  <?
  $result_exec=$clautoexec->sql_record($clautoexec->sql_query($codauto));
  if ($clautoexec->numrows>0){
    db_fieldsmemory($result_exec,0,true);
  }
  ?>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Rua:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$j14_nome?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">n&deg; : </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$y15_numero?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Bairro:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp;
      <?=$j13_descr?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Complemento :&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp;
      <?=$y15_compl?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td colspan="4" align="left"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
      <table width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr >
            <td >
	    <table  border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="fis3_consauto002_detalhes.php?solicitacao=Proced&auto=<?=$codauto?>" target="iframeDetalhes">&nbsp;Procedências&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="fis3_consauto002_detalhes.php?solicitacao=Receita&auto=<?=$codauto?>" target="iframeDetalhes">&nbsp;Receitas&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consauto002_detalhes.php?auto=<?=$codauto?>&solicitacao=Fiscais" target="iframeDetalhes">&nbsp;Fiscais&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consauto002_detalhes.php?auto=<?=$codauto?>&solicitacao=Testemunha" target="iframeDetalhes">&nbsp;Testemunha&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consauto002_detalhes.php?auto=<?=$codauto?>&solicitacao=Calculo" target="iframeDetalhes">&nbsp;C&aacute;lculo&nbsp;</a></td>
              </tr>
            </table>
          <td width="88%" align="left">
	    <iframe align="middle" width="100%"  frameborder="0" marginheight="0" marginwidth="0" name="iframeDetalhes" >
            </iframe>
	  </td>
        </tr>
      </table></td>
  </tr>
</table>

<?
  } else {  // caso nao tenha retornado nenhum registro é mostrado uma tabela informando que a matricula nao foi localizada
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><strong>Pesquisa do Auto n&deg;
      &nbsp;<?//=$numeroDaInscricao?>&nbsp;
      n&atilde;o retornou nenhum registro.</strong></td>
  </tr>
</table>
<?
  } // fim da verificacao
?>
</body>
</html>
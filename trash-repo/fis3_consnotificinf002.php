<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_fiscal_classe.php");
include("classes/db_fiscalocal_classe.php");
include("classes/db_fiscexec_classe.php");
include("classes/db_fiscalbaixa_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfiscal = new cl_fiscal;
$clfiscalocal = new cl_fiscalocal;
$clfiscexec = new cl_fiscexec;
$clfiscalbaixa = new cl_fiscalbaixa;
$sql = " ";
//echo ($clfiscal->sql_query($codfiscal,"*",null," y30_instit = ".db_getsession('DB_instit')));
$result = $clfiscal->sql_record($clfiscal->sql_query($codfiscal,"*",null," y30_codnoti = $codfiscal and y30_instit = ".db_getsession('DB_instit')));
$num = pg_numrows($result);
  
?>

<html>
<head> 
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>
</head>
<body>

<?
  if ($num > 0) { 
    db_fieldsmemory($result,0,true);
?>

<table  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC"> 
    <td colspan="4" align="center"><font color="#333333"><strong><b>&nbsp;DADOS DA 
      NOTIFICA&Ccedil;&Atilde;O&nbsp;</b></strong></font><font color="#666666"><strong> 
      </strong></font></td>
  </tr>
  <tr> 
    <td width="100" align="right" nowrap bgcolor="#CCCCCC">&nbsp;NOTIFICA&Ccedil;&Atilde;O&nbsp; n&ordm;:&nbsp;</td>
    <td width="165" align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$y30_codnoti?>
      &nbsp; </strong></font></td>
    <td width="100"  align="right" nowrap bgcolor="#CCCCCC">&nbsp;N° DO BLOCO: </td>
    <td width="165" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;<?=$y30_numbloco?>
      </strong></font></td>
  </tr>
  <?
  
//  die($clfiscal->sql_query_busca($codfiscal," dl_noti = $codfiscal and y30_instit = ".db_getsession('DB_instit')));
  $result_busca= $clfiscal->sql_record($clfiscal->sql_query_busca($codfiscal," dl_noti = $codfiscal and y30_instit = ".db_getsession('DB_instit') ) );
  if ($clfiscal->numrows>0){
    db_fieldsmemory($result_busca,0);
  }
  ?>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;IDENTIFICAÇÃ0:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$dl_identifica?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">C&oacute;digo Ident.:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$dl_codigo?>
      &nbsp; </strong></font></td>
  </tr>
  
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;        &nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?
           
      ?>
      &nbsp; </strong></font></td>


    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Obs:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=substr($y30_obs,0,25)?>
      &nbsp; </strong></font></td>


  </tr>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Departamento:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=$coddepto."-".$descrdepto?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Nome da Pessoa Autuada:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=$y30_nome?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Data:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=db_formatar($y30_data,'d')?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Hora:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=$y30_hora?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Vencimento Atual:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=$y30_dtvenc?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Prazo p/ Recurso:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong> 
      <?=$y30_prazorec?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
     <td align="center" nowrap bgcolor="#CCCCCC" colspan=4 ><b>Endereço Registrado:&nbsp;</b></td>
  </tr>
  <?
  $result_local=$clfiscalocal->sql_record($clfiscalocal->sql_query($codfiscal));
  if ($clfiscalocal->numrows>0){
    db_fieldsmemory($result_local,0,true);
  }
  ?>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">Rua:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=@$j14_nome?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">n&deg; : </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=@$y12_numero?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">Bairro:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp; 
      <?=@$j13_descr?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Complemento :&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp; 
      <?=@$y12_compl?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td align="center" nowrap bgcolor="#CCCCCC" colspan=4 ><b>Endereço Localizado:&nbsp;</b></td>
  </tr>
  <?
  $result_exec=$clfiscexec->sql_record($clfiscexec->sql_query($codfiscal));
  if ($clfiscexec->numrows>0){
    db_fieldsmemory($result_exec,0,true);
  }
  ?>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">Rua:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=@$j14_nome?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">n&deg; : </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=@$y13_numero?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td align="right" nowrap bgcolor="#CCCCCC">Bairro:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp; 
      <?=@$j13_descr?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Complemento :&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp; 
      <?=@$y13_compl?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td colspan="4" align="left"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
      <table width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr > 
            <td >
	    <table  border="0" cellspacing="2" cellpadding="0">
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="fis3_consnotific002_detalhes.php?solicitacao=Proced&fiscal=<?=$codfiscal?>" target="iframeDetalhes">&nbsp;Procedências&nbsp;</a></td>
              </tr> 
              <!--<tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="fis3_consnotific002_detalhes.php?solicitacao=Receita&fiscal=<?=$codfiscal?>" target="iframeDetalhes">&nbsp;Receitas&nbsp;</a></td>
              </tr>
              --> 
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consnotific002_detalhes.php?fiscal=<?=$codfiscal?>&solicitacao=Fiscais" target="iframeDetalhes">&nbsp;Fiscais&nbsp;</a></td>
              </tr>
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consnotific002_detalhes.php?fiscal=<?=$codfiscal?>&solicitacao=Testemunha" target="iframeDetalhes">&nbsp;Testemunha&nbsp;</a></td>
              </tr>
              <!--
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consnotific002_detalhes.php?fiscal=<?=$codfiscal?>&solicitacao=Calculo" target="iframeDetalhes">&nbsp;C&aacute;lculo&nbsp;</a></td>
              </tr>
              -->
              <?
              $result_baixa=$clfiscalbaixa->sql_record($clfiscalbaixa->sql_query_file(null,"*",null,"y47_codnoti = $codfiscal"));
              if ($clfiscalbaixa->numrows>0){
              ?>
              <tr> 
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="fis3_consnotific002_detalhes.php?fiscal=<?=$codfiscal?>&solicitacao=Baixa" target="iframeDetalhes">&nbsp;Fiscais&nbsp;</a></td>
              </tr>
              <?
              }
              ?>
            </table>
          <td width="88%" align="left"> <iframe align="middle" width="100%"  frameborder="0" marginheight="0" marginwidth="0" name="iframeDetalhes" > 
            </iframe> </td>
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
    <td align="center"><strong>Pesquisa da Notificação n&deg;
      &nbsp;<?//=$numeroDaInscricao?>&nbsp;
      n&atilde;o retornou nenhum registro.</strong></td>
  </tr>
</table>
<? 
  } // fim da verificacao
?>
</body>
</html>
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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");

$sqluf = "select db12_uf,db12_extenso from db_config  inner join db_uf on db12_uf=uf  where codigo = ".db_getsession('DB_instit');
$resultuf = db_query($sqluf);
db_fieldsmemory($resultuf,0);
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr align="center"> 
    <td height="66" colspan="10"><img src="imagens/logo_alegrete.jpg" width="48" height="64"></td>
  </tr>
  <tr align="center"> 
    <td colspan="10"><font size="1" face="Arial, Helvetica, sans-serif">PREFEITURA 
      MUNICIPAL DE ALEGRETE</font></td>
  </tr>
  <tr align="center"> 
    <td colspan="10"><font size="1" face="Arial, Helvetica, sans-serif"><?=$db12_extenso?></font></td>
  </tr>
  <tr bordercolor="#000000"> 
    <td colspan="13" align="left"><font size="2" face="Arial, Helvetica, sans-serif">RELAT&Oacute;RIO 
      DE RETEN&Ccedil;&Otilde;ES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">COMPET&Ecirc;NCIA</font><font size="1" face="Arial, Helvetica, sans-serif">______/2004</font></td>
  </tr>
  <tr align="center" bordercolor="#000000" bgcolor="#CCCCCC"> 
    <td colspan="10"><font size="2" face="Arial, Helvetica, sans-serif"><strong>DADOS 
      DO TOMADOR DO SERVI&Ccedil;O</strong></font></td>
  </tr>
  <tr> 
    <td colspan="14" bordercolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">NOME 
      OU RAZ&Atilde;O SOCIAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">INSCRI&Ccedil;&Atilde;O 
      MUNICIPAL</font></td>
  </tr>
  <tr align="center" bgcolor="#CCCCCC"> 
    <td colspan="10"><font size="2" face="Arial, Helvetica, sans-serif"><strong>DADOS 
      DO PRESTADOR DO SERVI&Ccedil;O</strong></font></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td width="4%" height="26"><font size="1" face="Arial, Helvetica, sans-serif">CNPJ</font></td>
    <td width="7%"><font size="1" face="Arial, Helvetica, sans-serif">INSC. MUNICIPAL*</font></td>
    <td width="27%"><font size="1" face="Arial, Helvetica, sans-serif">NOME OU 
      RAZ&Atilde;O SOCIAL</font></td>
    <td width="28%"><font size="1" face="Arial, Helvetica, sans-serif">SERVI&Ccedil;O 
      PRESTADO</font></td>
    <td colspan="2"><font size="1" face="Arial, Helvetica, sans-serif"> NOTA FISCAL</font></td>
    <td width="4%"><font size="1" face="Arial, Helvetica, sans-serif">S&Eacute;RIE 
      </font></td>
    <td width="8%"><font size="1" face="Arial, Helvetica, sans-serif">VALOR DO 
      SERVI&Ccedil;O</font></td>
    <td width="8%"><font size="1" face="Arial, Helvetica, sans-serif">VALOR DO 
      IMPOSTO</font></td>
    <td width="10%"><font size="2" face="Arial, Helvetica, sans-serif">total</font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="10"><font size="1" face="Arial, Helvetica, sans-serif">Respons&aacute;vel 
      p/ informa&ccedil;&otilde;es:<br>
      Telefone:<br>
      O pagamento do imposto dever&aacute; ser efetuado at&eacute; o dia 20 do 
      m&ecirc;s subseq&uuml;ente ao da compet&ecirc;ncia<br>
      e a respectiva guia de recolhimento solicitada junto ao Setor de Tributos.<br>
      * Preenchimento obrigat&oacute;rio apenas para empresas sediadas no munic&iacute;pio 
      de Guaíba - RS.</font></td>
  </tr>
  <tr align="center"> 
    <td colspan="10"><font size="2" face="Arial, Helvetica, sans-serif">MAIORES 
      INFORMA&Ccedil;&Otilde;ES: atendimento@guaiba.rs.gov.br OU PELO FONE: 480-2306</font></td>
  </tr>
</table>

</body>
</html>
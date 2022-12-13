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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
if(!isset($munic)){
   echo "<script>alert('Municipio Inválido.');location.href=index.php;</script>";
   exit;
}
?>
<html><!-- InstanceBegin template="/Templates/modelo.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documento sem t&iacute;tulo</title>
<!-- InstanceEndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="head" -->


<!-- InstanceEndEditable -->
</head>
<body bgcolor=#CCCCCC bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- InstanceBeginEditable name="inicio" --><!-- InstanceEndEditable --> 
<table width="770" height="430" cellspacing="0" >
  <tr> 
    <td width="49%" height="92"><div align="center"> <strong><font color="#000000" size="7"><em>Essencial</em></font></strong> 
      </div></td>
    <td width="51%"><div align="center"><a href="http://www.dbseller.com.br"><img src="imagens/topo1.gif" alt="" width="248" height="90" border="0"></a></div></td>
  </tr>
  <tr bgcolor="#ACB0DF"> 
    <td height="331" colspan="2"><!-- InstanceBeginEditable name="corpo" -->
      <table width="100%" align="center" id="tabela_rel" <? if($libera_relatorio==false) echo "style=\"visibility:hidden\""?>>
        <tr> 
          <td align="center"><font size="+1" face="Arial, Helvetica, sans-serif">
		  <?
		  $result = pg_exec("select * from municipio where substr(cgcte,1,3)= '$munic'");
          if(pg_numrows($result) ==0){
            echo "<script>alert('Municipio Inválido.');location.href=index.php;</script>";
            exit;
		  } 
		  echo $nome;  
		  ?>
          </font>
		  </td>
        </tr>
        <tr> 
          <td width="100%" align="center"> <br> <font color="#003366" face="Courier New, Courier, mono">Relat&oacute;rios 
            Dispon&iacute;veis</font><br> <br> </td>
        </tr>
        <tr> 
          <td align="center"><a href="relatorio_vendas_empresas.php?munic=<?=$munic?>"><font color="#000000" size="2" face="Courier New, Courier, mono">Relat&oacute;rio 
            de Acompanhamento do Movimento das Venda das Empresas</font></a><font color="#000000"><br>
            </font> </td>
        </tr>
        <tr> 
          <td align="center"><a href="relatorio_representa.php?munic=<?=$munic?>"><font color="#000000" size="2" face="Courier New, Courier, mono">Relat&oacute;rio 
            de Representatividade do valor adicionado</font></a></td>
        </tr>
      </table>
      <!-- InstanceEndEditable --></td>
  </tr>
  <tr align="center" bgcolor="#9999CC"> 
    <td colspan="2"><font color="#000099" size="1">DBSeller Informática Ltda - 
      www.dbseller.com.br</font></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>
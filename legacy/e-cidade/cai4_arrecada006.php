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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.cancelapagto {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 15px;
	width: 100px;
	background-color: #AAAF96;
	border: none;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#AAAF96" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
		   
  <table width="597" height="19" border="0" cellpadding="0" cellspacing="0" id="tab">
    <tr bgcolor="#BDC6BD"> 
      <th width="80" align="left" nowrap style="font-size:12px"> Receita</th>
      <th width="198" align="left" nowrap style="font-size:12px">Descri&ccedil;&atilde;o</th>
      <th width="143" align="right" nowrap bgcolor="#BDC6BD" style="font-size:12px">Valor Corrigido</th>
    </tr>
    <?
	if(isset($codcla)){
	  $result = pg_exec("select * 
	                     from disrec
						      inner join tabrec on k00_receit = k02_codigo
				    	 where codcla = $codcla");
	  if(pg_numrows($result)!=0){
        $totalvlr = 0;
	    for($i=0;$i<pg_numrows($result);$i++){
		  db_fieldsmemory($result,$i);
		  $totalvlr += $vlrrec;
		  ?> 
          <tr bgcolor="#BDC6BD"> 
            <td width="80" align="left" nowrap style="font-size:12px"><?=$k00_receit?></td>
            <td width="198" nowrap style="font-size:12px"><?=$k02_drecei?></td>
            <td width="143" align="right" nowrap bgcolor="#BDC6BD" style="font-size:12px"><?=db_formatar($vlrrec,'f')?></td>
          </tr>
		  <?
		}
 	    ?> 
        <tr bgcolor="#BDC6BD"> 
          <td width="80" align="left" nowrap style="font-size:12px"></td>
          <td width="198" nowrap style="font-size:12px"><b>Total :</b></td>
          <td width="143" align="right" nowrap bgcolor="#BDC6BD" style="font-size:12px"><b><?=$totalvlr?></b></td>
        </tr>
		<?
	  }
	}
	?>
  </table>
</center>			
</body>
</html>
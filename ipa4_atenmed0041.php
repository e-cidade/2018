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

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.bordaR {
	border-top-width: 2px;
	border-top-style: inset;
	border-top-color: #999999;
	border-right-width: 2px;
	border-right-style: inset;
	border-right-color: #999999;
	border-bottom-width: 1px;
	border-bottom-style: inset;
	border-bottom-color: #999999;	
}
.bordaRL {
	border-top-width: 2px;
	border-top-style: inset;
	border-top-color: #999999;
	border-right-width: 2px;
	border-right-style: inset;
	border-right-color: #999999;
	border-left-width: 2px;
	border-left-style: inset;
	border-left-color: #999999;	
	border-bottom-width: 1px;
	border-bottom-style: inset;
	border-bottom-color: #999999;	
}
.bordaB {
	border-bottom-width: 1px;
	border-bottom-style: inset;
	border-bottom-color: #999999;	
}
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-decoration: none;
	font-weight: bold;
	color:#999999;	
}
a:hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-decoration: none;
	font-weight: bold;	
	color:black;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}-->
</style>
</head>

<body bgcolor=#CCCCCC bgcolor="#FFFF64" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="140" align="center" nowrap bgcolor="#FFFF64"><strong>Consultas 
            Anteriores</strong></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="bordaRL"><a href="ipa4_atenmed0042.php">Consulta</a></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="bordaR"><a href="ipa4_atenmed0043.php">Receita</a></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="bordaR"><a href="ipa4_atenmed0044.php">Encaminhamento</a></td>
          <td width="140" align="center" nowrap bgcolor="#EAEAEA" class="bordaR"><a href="ipa4_atenmed0045.php">Exames</a></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="330" align="center" valign="middle" bgcolor="#FFFF64"> <br>
      <?
	$sql = "select * 
	        from atendmed
			     inner join agenate on ag30_codigo = ag40_codigo
			     inner join medicos on ag40_medico = aa01_codig
			where ".(db_getsession("w03_depen") != ""?"trim(ag30_depend) = trim('".db_getsession("w03_depen")."')":"trim(ag30_regist) = trim('".db_getsession("w01_regist")."') and trim(ag30_depend) = ''")."
			and ag40_codigo <> ".db_getsession("COD_atendimento")."
			order by ag40_data desc
			limit 10";
	$result = pg_exec($sql);
	$numrows = pg_numrows($result);
	if($numrows == 0) {
	  echo "<h3>Sem Consultas Anteriores</h3>\n";
	} else {
	  for($i = 0;$i < $numrows;$i++) {
	    db_fieldsmemory($result,$i);
	    ?>
	    <table bgcolor="<? echo $i%2==0?"#FFBBBB":"#FF7171" ?>" width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr> 
            
          <td nowrap><strong>Data:</strong></td>
            
          <td nowrap><strong>Hora:</strong></td>
            
          <td nowrap><strong>Pressão:</strong></td>
            
          <td nowrap><strong>Temperatura:</strong></td>
            
          <td nowrap><strong>Altura:</strong></td>
            
          <td nowrap><strong>F. C.</strong></td>
            
          <td nowrap><strong>F. R.</strong></td>
            
          <td nowrap><strong>Peso:</strong></td>			
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td nowrap><?=db_formatar(@$ag40_data,'d')?>&nbsp;</td>
            <td nowrap><?=@$ag40_hora?>&nbsp;</td>
            <td nowrap><?=@$ag40_pressao?>&nbsp;</td>
            <td nowrap><?=@$ag40_temperatura?>&nbsp;</td>
            <td nowrap><?=@$ag40_altura?>&nbsp;</td>
            <td nowrap><?=@$ag40_freqcard?>&nbsp;</td>
            <td nowrap><?=@$ag40_freqresp?>&nbsp;</td>
            <td nowrap><?=@$ag40_peso?>&nbsp;</td>			
          </tr>		
          <tr> 
            
          <td colspan="7" nowrap><strong>Diagnóstico:</strong></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td colspan="7"><? echo str_replace("\n","<br>","$ag40_diag"); ?>&nbsp;</td>
          </tr>		
          <tr> 
          <td colspan="7" nowrap><strong>Médico:</strong></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td colspan="7"><? echo str_replace("\n","<br>","$aa01_nome"); ?>&nbsp;</td>
          </tr>		
        </table><br>
	    <?
      }
	}
	?>	
	</td>
  </tr>
</table>
</body>
</html>
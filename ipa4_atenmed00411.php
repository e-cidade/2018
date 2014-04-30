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
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
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
    <td height="30">
    &nbsp;
    </td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center" nowrap bgcolor="#FFFF64"><strong>Consultas 
            Anteriores</strong></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="330" align="center" valign="middle" bgcolor="#FFFF64"> <br>
      <?
        $tam = strlen($codigo);
	$x = ""; 
	for($i=0;$i < $tam;$i++){
	  $x .= "X";
	}
	$codigo = str_replace($x," ",$codigo);
	$sql = "select * 
	        from atendmed
			     inner join agenate on ag30_codigo = ag40_codigo
			     inner join medicos on ag40_medico = aa01_codig
			where ag30_regist = '$codigo'
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
            <td colspan="8"><? echo str_replace("\n","<br>","$ag40_diag"); ?>&nbsp;</td>
          </tr>		
          <tr> 
          <td colspan="7" nowrap><strong>Médico:</strong></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td colspan="8"><? echo str_replace("\n","<br>","$aa01_nome"); ?>&nbsp;</td>
          </tr>		
        </table><br>
	    <?
      }
	}
	?>	
	</td>
  </tr>
</table>
<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>
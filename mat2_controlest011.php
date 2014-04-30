<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$aux = new cl_arquivo_auxiliar;
$cliframe_seleciona = new cl_iframe_seleciona;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >

  <table    align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td align='right' ></td>
         <td ></td>
      </tr>
       <tr> 
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os depósitos selecionados</option>
                    <option name="condicao1" value="sem">Sem os depósitos selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
      <br><br>
        <td colspan=2 >
        
         <?
           $sql_marca = "";
           if (isset($m91_depto) && trim($m91_depto)!=""){
            $sql_marca = "select m91_depto, descrdepto 
                            from db_almox 
                           inner join db_depart on coddepto = m91_depto 
                           where m91_depto in ($m91_depto)
                           order by descrdepto";
           }
           $sql = "select m91_depto, descrdepto 
                     from db_almox 
                    inner join db_depart on coddepto = m91_depto
                    where instit = ".db_getsession("DB_instit")."  
                    order by descrdepto";
           $cliframe_seleciona->campos  = "m91_depto, descrdepto";
           $cliframe_seleciona->legenda="Almoxarifados";
           $cliframe_seleciona->sql=$sql;    
           $cliframe_seleciona_grupo->sql_marca=$sql_marca;
           $cliframe_seleciona->iframe_height ="250";
           $cliframe_seleciona->iframe_width ="380";
           $cliframe_seleciona->iframe_nome ="departamentos"; 
           $cliframe_seleciona->chaves ="m91_depto";
           $cliframe_seleciona->iframe_seleciona(4)
           
                 
        	?>
       </td>
      </tr> 

  </form>
    </table>
</body>
</html>
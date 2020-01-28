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
include("classes/db_procandam_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransand_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <center>
    <table bgcolor="#cccccc">
     <tr>
        <td>
        <? if (isset($codtran)){
              $sqlproc = "select p63_codproc,
                                 (case when p58_requer isnull then z01_nome else p58_requer end) as requer,
                                 p51_descr,
                                 p58_numero||'/'||p58_ano as p58_numero,
                                 p58_obs,
                                 to_char(p58_dtproc,'YYYY') as anoproc,
                                 to_char(p58_dtproc,'DD/MM/YYYY') as p58_dtproc
                          from   proctransferproc
                                 inner join protprocesso on  p63_codproc = p58_codproc
				                         inner join proctransfer on p63_codtran = p62_codtran
                                 inner join cgm on p58_numcgm = z01_numcgm
                                 inner join tipoproc on p58_codigo = p51_codigo
                          where p63_codtran = $codtran";

         ?>
         <table border=1 cellspacing=0 style="border:1px solid black">
           <tr>
             <td bgcolor="#999999" align=center><b>N. Controle</b></td>
             <td bgcolor="#999999" align=center><b>Processo</b></td>
             <td bgcolor="#999999" align=center><b>Requerente</b></td>
             <td bgcolor="#999999" align=center width=125><b>Descrição</b></td>
             <td bgcolor="#999999" align=center width=125><b>Data</b></td>
             <td bgcolor="#999999" align=center width="125"><b>Observação</b></td>
          </tr>
         <?

             // db_lovrot($sqlandam,10,"","","");
            $rs = db_query($sqlproc);
            $j = 0;
            for ($i = 0;$i < pg_num_rows($rs);$i++){
                db_fieldsmemory($rs,$i);
	      
                if ($j % 2 == 0 ){
                    $cor = "bgcolor='#CCCCCC'";
                }else{
                    $cor = "bgcolor='#FFFFFF'";
                }
                echo "<tr>";
                echo "<td $cor><a name='$i' href='' onClick='js_mostraproc(\"$p63_codproc\")'>Inf ->".$p63_codproc."</a></td>";
                echo "<td $cor>{$p58_numero}</td>";
                echo "<td $cor>".$requer."</td>";
                echo "<td $cor>".$p51_descr."</td>";
                echo "<td $cor>".$p58_dtproc."</td>";
                echo "<td $cor>";
                echo $p58_obs==""?"&nbsp;":nl2br($p58_obs);
                echo "</td>";
		echo "</tr>"; 
                $j++;
          }
         }
        ?>
       </table>
        </td>
     </tr>
    </table>
    </center>
</body>
</html> 
<script>
function js_mostraproc(proc){
  js_OpenJanelaIframe('top.corpo','db_iframe_proc','pro3_mosprocandam.php?codproc='+proc,'pesquisa',true);
}
</script>
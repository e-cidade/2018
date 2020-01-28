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
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
include("classes/db_orcfontes_classe.php");
include("classes/db_orcfontesdes_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(!isset($codfon)){
  echo "<script> parent.alert('Fonte nao informada. Verifique!')</script>";
  exit;
}
if(!isset($valor) || $valor+0 ==0 ){
  echo "<script> parent.document.form1.confirma.value = 'Desdobrar a Receita';</script>";
  exit;
}

$anousu = db_getsession("DB_anousu");


$clorcfontes = new cl_orcfontes;
$clorcfontesdes = new cl_orcfontesdes;
$clorcfontes->rotulo->label();
$clorcfontesdes->rotulo->label();
$result = $clorcfontes->sql_record($clorcfontes->sql_query($codfon,$anousu));
if($clorcfontes->numrows==0){
  echo "<script> parent.alert('Fonte nao informada. Verifique!')</script>";
  exit;
}
db_fieldsmemory($result,0);

$mae  = db_le_mae_rec_sin($o57_fonte,false);

$sql = "select * from orcfontes inner join orcfontesdes on o57_codfon = o60_codfon and o60_anousu = ".db_getsession("DB_anousu")." 
        where o57_fonte like '$mae%' and o57_anousu = $anousu";

$result = pg_query($sql);
if(pg_numrows($result)==0){
  echo "<script> parent.alert('Verifique as fontes da receita!')</script>";
  exit;
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
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="20%"><?=$Lo57_fonte?></td>
    <td width="50%" align="left"><?=$Lo57_descr?></td>
    <td width="10%" align="center"><?=$Lo60_perc?></td>
    <td width="20%"><strong>Valor</strong></td>
  </tr>
  <?
  global $vlrperc;
  $vlrtot = $valor;
  $vlrsoma = 0;
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
    
    $vlrperc = $vlrtot * ( $o60_perc / 100 );
    $vlrsoma = $vlrsoma + $vlrperc;
    if($vlrsoma > $vlrtot){
      $vlrperc = $vlrperc - ($vlrsoma - $vlrtot);
    }
   
   
    echo "<tr>";
    echo "<td width=\"20%\">$o57_fonte</td>";
    echo "<td width=\"50%\" align=\"left\">$o57_descr</td>";
    echo "<td width=\"10%\" align=\"center\">".$o60_perc."%</td>";
    echo "<td width=\"20%\">";
    $vlrperc = $vlrperc;
    db_input("vlrperc",15,4,true,'text',3);
    echo "</td>";
    echo "</tr>";
    
  }
  
  ?>
</table>
</body>
</html>
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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_issporte_classe.php");

$cl_porte = new cl_issporte;
$clrotulo = new rotulocampo;
$clrotulo->label("q40_codporte");
$clrotulo->label("q40_descr");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

 $result = $cl_porte->sql_record($cl_porte->sql_query("","q40_codporte,q40_descr","q40_descr"));
if($cl_porte->numrows == 0)
 {
   echo "Nenhum porte disponível";
 }

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_marca() {
  
  var ID = document.getElementById('marca');
  //var BT = document.getElementById('btmarca');
  if(!ID)
    return false;
  var F = document.form1;
  if(ID.innerHTML == 'D') {
    var dis = false;
	ID.innerHTML = 'M';
  } else {
    var dis = true;
	ID.innerHTML = 'D';
  }
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox"){
        F.elements[i].checked = dis;
    }
  }
}


function js_relatorio(opcao){
 var F = document.form1;
  Dadosporte = "";
  sep = "";
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
      Dadosporte += sep+F.elements[i].value;
      marcas = true;
      sep = "XX";
    }
  }  
jan = window.open('iss4_contativ006.php?Dadosativ=<?=$Dados?>&Dadosclasse=<?=$Dados1?>&Dadosporte='+Dadosporte+'&opcao='+opcao,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '); 
 
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" bgcolor="#CCCCCC" topmargin="0" marginwidth="0" marginheight="0">
<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <br>
      <form name="form1" method="post" action="">
      <div align="center">
       <input type="button" name="relatorio" value="Relatório" style="width:150" onclick="js_relatorio('analitico')">
      </div> 
       <br><br><br>  
	 <table border="0" width="100%" cellspacing="1" cellpadding="0" id="classes">
          <tr height="20" bgcolor="#FFCC66">
            <th class="borda" align="center" style="font-size:12px" nowrap><a id="marca" href="#" style="color:black" onclick="js_marca();return false">D</a></th>      
	    <th class="borda" align="center" style="font-size:12px" nowrap><?=$Lq40_codporte?></th>
            <th class="borda" align="left" style="font-size:12px" nowrap><?=$Lq40_descr?></th>
          </tr>
	  <?
	    $cor = '#E4F471';
            for($i = 0;$i < $cl_porte->numrows;$i++) {
              db_fieldsmemory($result,$i);
	      if ($cor == '#E4F471'){
		 $cor = '#EFE029';
	      }elseif ($cor == '#EFE029'){
		 $cor = '#E4F471';
		
	      }
	  ?>
          <tr style="cursor: hand; height: 20px" bgcolor="<?=$cor?>">
            <td height="20px" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
              <input type="checkbox" value="<?=$q40_codporte?>"  name="check<?=$i?>" checked onclick="js_verifica()">
            </td>
            <td height="20px" class="borda" style="font-size:11px" align="center" nowrap><?=$q40_codporte?></td>
	    <td height="20px" class="borda" style="font-size:11px" align="left" nowrap><?=$q40_descr?></td>
          </tr>
	  <?
          }
	  ?>
        </table>   
      </form>
</table>
</body>
</html>
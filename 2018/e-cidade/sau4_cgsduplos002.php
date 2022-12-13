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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_db_syscampo_classe.php");
include("classes/db_db_sysarqcamp_classe.php");
include("classes/db_db_syscampodep_classe.php");

$cldb_syscampo = new cl_db_syscampo;
$cldb_syscampodep = new cl_db_syscampodep;
$cldb_sysarqcamp = new cl_db_sysarqcamp;


$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_v_ender");
$clrotulo->label("z01_v_munic");
$clrotulo->label("z01_d_nasc");
$clrotulo->label("z01_v_mae");
$clrotulo->label("z01_v_cgccpf");

$db_opcao = 1;
$db_botao = true;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$nome = str_replace('|','%',$z01_nome);
//echo "nome = $nome";
$z01_nome= $nome;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_marca(obj) {

  var OBJ = document.form1;
  for (i=0;i<OBJ.length;i++) {

    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
    }
  }
  return false;
}
function js_desab(cod) {

  obj=document.getElementsByTagName("INPUT")
  var marcado=false;
  for (i=0; i<obj.length; i++) {

    if(obj[i].type=='checkbox') {

      nome = obj[i].name.substring(4);
      if (nome==cod) {

        obj[i].checked=false;
        obj[i].disabled=true;
      } else {
        obj[i].disabled=false;
      }
    }
  }

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<form name='form1'>

<?php
	 
  if($z01_nome!="") {

    $sql = "select z01_i_cgsund,z01_v_nome, z01_d_nasc,z01_v_mae, z01_v_ender,z01_v_munic,z01_v_cgccpf
              from cgs_und
             where z01_v_nome like '$z01_nome%'
               and z01_i_cgsund not in (select s127_i_numcgs from sau_cgscorreto where s127_b_proc is false)
               and z01_i_cgsund not in (select s128_i_numcgs from sau_cgserrado
                                      inner join sau_cgscorreto on s127_i_codigo = s128_i_codigo
                                                               and s127_b_proc is false)
             order by z01_v_nome,z01_v_cgccpf,z01_d_nasc,z01_v_mae,z01_v_munic,z01_v_ender";
    $result = db_query($sql);

    echo "<table border='1' cellspacing='0' cellpadding='0' style='border-style:outset;width: 100%;'> ";
    echo "<tr >
		        <td style='border-style:outset' align='center'><b>Pri</b></td>
		        <td style='border-style:outset' align='center'><b><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>Sec</a></b></td>
		        <td style='border-style:outset' align='center'><b>$RLz01_i_cgsund</b></td>
		        <td style='border-style:outset' align='center'><b>$RLz01_v_cgccpf</b></td>
		        <td style='border-style:outset' align='center'><b>$RLz01_v_nome</b></td>

		        <td style='border-style:outset' align='center'><b>$RLz01_d_nasc</b></td>
		        <td style='border-style:outset' align='center'><b>$RLz01_v_mae</b></td>

		        <td style='border-style:outset' align='center'><b>$RLz01_v_ender</b></td>
		        <td style='border-style:outset' align='center'><b>$RLz01_v_munic</b></td>
	       </tr>";

    for($i=0;$i<pg_numrows($result);$i++) {

	    db_fieldsmemory($result,$i);
	    echo "<tr>
	            <td><input type='radio' name='pri' value='$z01_i_cgsund' ".($i==0?"checked":"")." onclick='js_desab(\"$z01_i_cgsund\");'></td>
	            <td><input type='checkbox' name='sec_$z01_i_cgsund' value='$z01_i_cgsund' ".($i==0?"disabled=true":"")." ></td>
	            <td nowrap>$z01_i_cgsund&nbsp</td>
	            <td nowrap>$z01_v_cgccpf&nbsp</td>
	            <td nowrap>$z01_v_nome&nbsp</td>
	            <td nowrap>". db_formatar($z01_d_nasc, 'd') ."&nbsp</td>
	            <td nowrap>$z01_v_mae&nbsp</td>
	            <td nowrap>$z01_v_ender&nbsp</td>
	            <td nowrap>$z01_v_munic&nbsp</td>
	            </tr>
	         ";
    }
    echo "</table>";
  }
?>    
</form>

</body>
</html>
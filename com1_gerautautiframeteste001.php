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
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamval_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamval = new cl_pcorcamval;
$clrotulo = new rotulocampo;
$clrotulo->label("pc80_codproc");
$clrotulo->label("e54_codcom");
$clrotulo->label("e54_codtipo");
$db_opcao=1;
$db_botao=true;

if(isset($incluir)){
  $valor = split(",",$valores);
  for($i=0;$i<sizeof($valor);$i++){
    $splitei = split("_",$valor[$i]);
    for($ii=0;$ii<sizeof($splitei);$ii++){
      // 'aut_".($contador-1)."_".$pc01_codmater."_".$pc13_coddot."_".$z01_numcgm."'
    }
    $txt = str_replace("aut","txt",$valor[$i]);
    $e54_valor = $$txt;
  }
}

$numrows_itens = 0;
if(isset($pc80_codproc) && trim($pc80_codproc)!=""){
  $result_itens = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_geraut(null,null,"pc01_codmater,pc01_descrmater,pc13_coddot,pc13_codele,z01_numcgm,z01_nome,sum(pc23_valor) as pc23_valor,sum(pc23_quant) as pc23_quant","z01_numcgm,pc13_coddot,pc01_codmater","pc81_codproc=$pc80_codproc and pc24_pontuacao=1 group by pc01_codmater,pc01_descrmater,pc13_coddot,pc13_codele,z01_numcgm,z01_nome"));
  $numrows_itens = $clpcorcamjulg->numrows;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    db_input('valores',8,0,true,'hidden',3);
    if($numrows_itens==0){
      echo "                                                                                                                                                                                                                                                                                   <br><br><br><br><br><br><br>
            <strong>Não existem itens para realizar gerar autorização neste processo.</strong>\n
           ";
      echo "
            <script>
	      parent.document.form1.incluir.disabled=true;
            </script>
           ";
    }else{
      $bordas = "bordas";
      echo "<center>";
      echo "<table border='1' align='center'>\n";
      echo "<tr>";
      echo "  <td colspan='11' align='center'>$Lpc80_codproc";
      echo "    ";db_input('pc80_codproc',8,$Ipc80_codproc,true,'text',3);
      echo "    ";db_input('e54_codcom',8,$Ie54_codcom,true,'hidden',3);
      echo "    ";db_input('e54_codtipo',8,$Ie54_codtipo,true,'hidden',3);
      echo "  </td>";
      echo "</tr>";
      echo "<tr bgcolor=''>\n";
      echo "  <td nowrap class='bordas02' align='center' title='Marcar todos os itens de todas autorizações'><strong>";db_ancora("M","js_marcatudo();",1);echo "</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Descrição</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Fornecedor</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Dotação</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Quant.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Val Unit.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Val Tot.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Reserva</strong></td>\n";
      echo "</tr>\n";
      
      $dot_ant = "";
      $forn_ant= "";
      $contador= 1;
      for($i=0;$i<$numrows_itens;$i++){
	db_fieldsmemory($result_itens,$i);
        if($dot_ant!=$pc13_coddot || $forn_ant!=$z01_numcgm){
	  if($contador!=1){
	    echo "<tr>\n";
	    echo "  <td nowrap colspan='11'align='left'><strong>&nbsp;</strong></td>\n";
	    echo "<tr>\n";
	  }
	  echo "</tr>\n";
          echo "  <td nowrap colspan='1' class='bordas' align='center' title='Marcar apenas itens da $contador&ordf; autorização'><strong>";db_ancora("A","js_marcaautoriza('$contador');",1);echo "</strong></td>\n";
	  echo "  <td nowrap colspan='10' class='bordas' align='left'><strong>".($contador)."&ordf; AUTORIZAÇÃO </strong></td>\n";
	  echo "</tr>\n";
	  $dot_ant = $pc13_coddot;
	  $forn_ant= $z01_numcgm;
	  $contador++;
	}
/*
        $result_altext = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o80_codres,o80_valor","","o80_coddot = $pc13_coddot and o82_solicitem = $pc13_codigo"));
        if($clorcreservasol->numrows>0){
          db_fieldsmemory($result_altext,0,true);
          if($o80_valor<$pc13_valor){
            $altcoddot = true;
          }
        }
*/
	echo "<tr>\n";
	echo "  <td nowrap class='$bordas' align='center' ><input type='checkbox' name='aut_".($contador-1)."_".$pc01_codmater."_".$pc13_coddot."_".$z01_numcgm."' value='aut_".($contador-1)."_".$pc01_codmater."_".$pc13_coddot."_".$z01_numcgm."' onclick='js_disabled(\"aut_".($contador-1)."_".$pc01_codmater."_".$pc13_coddot."_".$z01_numcgm."\",\"txt_".($contador-1)."_".$pc01_codmater."_".$pc13_coddot."_".$z01_numcgm."\");'><input type='hidden' name='txt_".($contador-1)."_".$pc01_codmater."_".$pc13_coddot."_".$z01_numcgm."' value='$pc23_valor' disabled></td>\n";
	echo "  <td nowrap class='$bordas' align='center' >$pc01_codmater</td>\n";
	echo "  <td class='$bordas' align='left' >  ".ucfirst(strtolower($pc01_descrmater))."</td>\n";
	echo "  <td class='$bordas' align='left' >$z01_nome</td>\n";
	echo "  <td nowrap class='$bordas' align='center' >$pc13_coddot</td>\n";
	echo "  <td nowrap class='$bordas' align='right'  >$pc23_quant</td>\n";
	echo "  <td nowrap class='$bordas' align='right'  >R$ ".db_formatar($pc23_valor/$pc23_quant,"f")."</td>\n";
	echo "  <td nowrap class='$bordas' align='right'  >R$ ".db_formatar(($pc23_valor),"f")."</td>\n";
	echo "  <td nowrap class='$bordas' align='center' ><strong>Não</strong></td>\n";
	echo "</tr>\n";
      } 
      echo "</table>\n";
      echo "</center>";
    }
    ?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>
function js_troca(codigo,orcamento,sol){
  top.corpo.document.location.href = 'com1_trocpcorcamtroca001.php?pc25_orcamitem='+codigo+'&orcamento='+orcamento+'&sol='+sol;
}
function js_marcatudo(){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      if(x.elements[i].checked==true){
        x.elements[(i+1)].disabled = true;
	x.elements[i].checked=false;
      }else{
        x.elements[(i+1)].disabled = false;
	x.elements[i].checked=true;
      }      
    }
  }
}
function js_marcaautoriza(valor){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      splitei = x.elements[i].value.split("_");
      if(splitei[1]==valor){
	if(x.elements[i].checked==true){
          x.elements[(i+1)].disabled = true;
	  x.elements[i].checked=false;
	}else{
          x.elements[(i+1)].disabled = false;
	  x.elements[i].checked=true;
	}
      }
    }
  }
}
function js_disabled(me,campo){
  if(eval('document.form1.'+me+'.checked')==true){
    eval('document.form1.'+campo+'.disabled=false');
  }else{
    eval('document.form1.'+campo+'.disabled=true');
  }
}
</script>
</body>
</html>
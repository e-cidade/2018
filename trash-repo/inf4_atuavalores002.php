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
include("classes/db_infcab_classe.php");
include("classes/db_infcor_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS,2);

$clinfcab = new cl_infcab;
$clinfcor = new cl_infcor;

if ( isset($i03_descr) && $i03_descr != "" ) {

  $clinfcab->i03_descr       = $i03_descr;
  $clinfcab->i03_numcgm      = $i03_numcgm;
  $clinfcab->i03_dtbase      = $i03_dtbase;
  $clinfcab->i03_dtlanc      = date("Y-m-d",db_getsession('DB_anousu'));
  $clinfcab->i03_id_usuario = db_getsession('DB_id_usuario');

  db_inicio_transacao();

  if ( isset($i03_codigo) ) {
    $clinfcab->alterar($i03_codigo);
//    $clinfcab->erro(true,false);
  } else {
    $clinfcab->incluir(0);
//    $clinfcab->erro(true,false);
  }

  $clinfcor->excluir($clinfcab->i03_codigo);
//  $clinfcor->erro(true,false);
     
  for($i=1;$i<=$i04_linhas;$i++){

     $i04_dados = "i04_dados$i";

     $matriz = split("#",$$i04_dados);

     $clinfcor->i04_codigo   = $clinfcab->i03_codigo;
     $clinfcor->i04_seq	     = $i;
     $clinfcor->i04_obs	     = $matriz[0];
     $clinfcor->i04_dtoper   = $matriz[1];
     $clinfcor->i04_dtvenc   = $matriz[2];
     $clinfcor->i04_valor    = db_formatar($matriz[3],'p');
     $clinfcor->i04_receit   = $matriz[4];
     $clinfcor->i04_correcao = db_formatar($matriz[5],'p');
     $clinfcor->i04_juros    = db_formatar($matriz[6],'p');
     $clinfcor->i04_multa    = db_formatar($matriz[7],'p');

     $clinfcor->incluir($clinfcab->i03_codigo,$i);
//     $clinfcor->erro(true,false);

  }

  db_fim_transacao();
  $clinfcab->erro(true,false);
  
}

?>

<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_removelinha(linha) {
  var tab = (document.all)?document.all.tab:document.getElementById('tab');
  for(i=0;i<tab.rows.length;i++){
    if(linha == tab.rows[i].id){
      tab.deleteRow(i);
	  break;
	}
  }
}

function js_alteralinha(linha) {
  var tab = document.getElementById('tab');
  for(i=0;i<tab.rows.length;i++){
    if(linha == tab.rows[i].id){
      // atualiza dados
      parent.document.form1.i04_obs.value = tab.rows[i].cells[0].innerHTML;
      
      parent.document.form1.i04_dtoper_dia.value = tab.rows[i].cells[1].innerHTML.substring(0,2);
      parent.document.form1.i04_dtoper_mes.value = tab.rows[i].cells[1].innerHTML.substring(3,5);
      parent.document.form1.i04_dtoper_ano.value = tab.rows[i].cells[1].innerHTML.substring(6,10);
      parent.document.form1.i04_dtoper.value = tab.rows[i].cells[1].innerHTML.substring(0,2)+'/'+tab.rows[i].cells[1].innerHTML.substring(3,5)+'/'+tab.rows[i].cells[1].innerHTML.substring(6,10);
      
      parent.document.form1.i04_dtvenc_dia.value = tab.rows[i].cells[2].innerHTML.substring(0,2);
      parent.document.form1.i04_dtvenc_mes.value = tab.rows[i].cells[2].innerHTML.substring(3,5);
      parent.document.form1.i04_dtvenc_ano.value = tab.rows[i].cells[2].innerHTML.substring(6,10);
      parent.document.form1.i04_dtvenc.value = tab.rows[i].cells[2].innerHTML.substring(0,2)+'/'+tab.rows[i].cells[2].innerHTML.substring(3,5)+'/'+tab.rows[i].cells[2].innerHTML.substring(6,10);

      parent.document.form1.i04_valor.value = tab.rows[i].cells[3].innerHTML;
      
      parent.document.form1.i04_obs.value = tab.rows[i].cells[0].innerHTML;
      
      parent.document.form1.i04_receit.value = tab.rows[i].cells[4].innerHTML;
      
      tab.deleteRow(i);
      break;
    }
  }
}

</script>
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
<form name="form1" method="post" action="">
  <input name="i03_codigo"  value="" type="hidden">
  <input name="i03_descr"  value="" type="hidden">
  <input name="i03_numcgm" value="" type="hidden">
  <input name="i03_dtbase" value="" type="hidden">
  <table width="100%" border="1" cellpadding="3" cellspacing="0" id="tab">
    <tr bgcolor="#BDC6BD"> 
      <th width="20" align="left"   nowrap style="font-size:8px">Observa&ccedil;&atilde;o</th>
      <th width="20" align="left"   nowrap style="font-size:8px">Operacao</th>
      <th width="20" align="left"   nowrap style="font-size:8px">Vencimento</th>
      <th width="20" align="left"   nowrap style="font-size:8px">Valor</th>
      <th width="20" align="left"   nowrap style="font-size:8px">Receita</th>
      <th width="20" align="right"  nowrap style="font-size:8px">Correção</th>
      <th width="20" align="right"  nowrap style="font-size:8px">Juros</th>
      <th width="20" align="right"  nowrap style="font-size:8px">Multa</th>
      <th width="20" align="right" nowrap style="font-size:8px">Total</th>
      <th width="20" align="center" nowrap style="font-size:8px">Excluir</th>
      <th width="20" align="center" nowrap style="font-size:8px">Alterar</th>
    </tr>

    <?
    if ( isset($i03_codigo) ) {

       $sql="select * from infcor where i04_codigo = $i03_codigo";
       
       $result=pg_exec($sql);

       for($s=0;$s<pg_numrows($result);$s++){

         db_fieldsmemory($result,$s);

	 echo "<tr>";
	 echo "<td>" . $i04_obs                        . "</td>";
	 echo "<td>" . db_formatar($i04_dtoper,'d')    . "</td>";
	 echo "<td>" . db_formatar($i04_dtvenc,'d')    . "</td>";
	 echo "<td>" . db_formatar($i04_valor,'f')     . "</td>";
	 echo "<td>" . $i04_receit                     . "</td>";
	 echo "<td>" . db_formatar($i04_correcao,'f')  . "</td>";
	 echo "<td>" . db_formatar($i04_juros,'f')     . "</td>";
	 echo "<td>" . db_formatar($i04_multa,'f')     . "</td>";
	 echo "<td>" . db_formatar($i04_correcao + $i04_juros + $i04_multa,'f') . "</td>";
	 echo "<td><input class='cancelapagto' value='<E>' type='button' onclick='js_removelinha("."'id_".$s."'".")'></td>";
	 echo "</tr>";

       }
    }
    
    ?>
    
  </table>
  </form>
</center>			
</body>
</html>
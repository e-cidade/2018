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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
?>
</script>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css">
</style>
<script language="JavaScript" type="text/JavaScript">
</script>
<script>
function js_marca() {
  var ID = document.getElementById('marca');
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

function js_relatorio() {

	// verifica se o usuário preencheou o campo do ano
	if (document.getElementById("ano").value == "") {
		alert("É necessário informar o ano antes de continuar!");
		document.form1.ano.focus();
		return false;
	}
		
  var ativ = '';
  var virg = '';
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
        ativ = ativ+virg+F.elements[i].value;
        virg = '-';
     }
  }
  jan = window.open('iss4_pgtoclasse006.php?tipo='+document.form1.tipo.value+'&totais='+document.form1.totais.value+'&quebrar='+document.form1.quebrar.value+'&ordem='+document.form1.ordem.value+'&mesini='+document.form1.mes_ini.value+'&mesfim='+document.form1.mes_fin.value+'&ano='+document.form1.ano.value+'&atividades='+ativ+'&classes=<?=$_GET["dados"]?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_relatorio1(){
  var ativ = '';
  var virg = '';
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
        ativ = ativ+virg+F.elements[i].value;
        virg = '-';
     }
  }
  jan = window.open('iss4_pgtoclasse005.php?tipo='+document.form1.tipo.value+'&totais='+document.form1.totais.value+'&quebrar='+document.form1.quebrar.value+'&ordem='+document.form1.ordem.value+'&datai='+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value+'&dataf='+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value+'&atividades='+ativ+'&opcao=sintetico&classes=<?=$_GET["dados"]?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function validaAno() {
	var idAno = document.getElementById("ano");
	if ( Number(idAno.value) < 1970 || Number(idAno.value) > 2099 ) {
		alert("Ano digitado inválido!");
		idAno.value = null;
		idAno.focus();
	}
}

</script>
</head>
<style type="text/css">

th {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}

td {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}

</style>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
$sql = "select q03_ativ,
               q03_descr 
	        from ativid 
					  inner join clasativ on q82_ativ = q03_ativ 
	        where q82_classe in ($dados)"; 

$result = pg_exec($sql);
if(pg_numrows($result) == 0 ){
  db_redireciona("db_erros.php?fechar=true&db_erro=Não Existe Atividade Para o Intervalo Digitado.&pagina_retorno=iss4_pgtoclasse001.php");
  exit;
}

$numrows = pg_numrows($result);
  echo "<form name=\"form1\" id=\"form1\" method=\"post\" target=\"relatorio\">\n";
  echo "<table valign=\"top\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
  echo "<tr align=\"top\">";
  echo "<td width=\"43%\" height=\"30\" colspan=\"6\" bordercolor=\"#FFFFCC\"><div align=\"center\"><font size=\"2\">"; 

  $aMeses = array();
  $aMeses[1]  = "janeiro";
  $aMeses[2]  = "fevereiro";
  $aMeses[3]  = "março";
  $aMeses[4]  = "abril";
  $aMeses[5]  = "maio";
  $aMeses[6]  = "junho";
  $aMeses[7]  = "julho";
  $aMeses[8]  = "agosto";    
  $aMeses[9]  = "setembro";
  $aMeses[10] = "outubro";
  $aMeses[11] = "novembro";
  $aMeses[12] = "dezembro";	

	echo "<strong> Mês inicial : </strong>\n";
  db_select('mes_ini', $aMeses, 'text', 2);
 
	echo "<strong> até : </strong>\n";
 	db_select('mes_fin', $aMeses, 'text' ,2); 

	echo "<strong> No ano de : </strong>\n";
  db_input("ano", 4, 1, null, "text", 1, "onchange='validaAno();'", null, null, "", 4);

  $x = array("p"=>"Pagamento","c"=>"Competência");
  db_select('tipo',$x,'text',2); 

  $y = array("m"=>"Valores por mes","t"=>"Somente totais");
  db_select('totais',$y,'text',2); 

	echo "<br />";

  $z = array("a"=>"Alfabética","v"=>"Valor decrescente","n"=>"Código da atividade");
  db_select('ordem',$z,'text',2);

  $a = array("s"=>"Quebrar por atividade","n"=>"Não quebrar por atividade");
  db_select('quebrar',$a,'text',2);

  echo "&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp";
//  echo "<input name=\"analitico\" id=\"emite2\" type=\"button\" value=\"Analítico\" onClick=\"return js_relatorio()\">&nbsp;&nbsp;";
  echo "<input name=\"processar\" id=\"processar\" type=\"button\" value=\"Processar\" return onClick=\"js_relatorio()\">";
  echo "</tr>";
  echo "<tr bgcolor=\"#FFCC66\">\n";
  echo "<th class=\"borda\" align=\"center\" style=\"font-size:13px\" nowrap><a id=\"marca\" href=\"#\" style=\"color:black\" onclick=\"return js_marca(); return false\">D</a></th>\n";
  echo "<th class=\"borda\" align=\"center\" style=\"font-size:13px\" nowrap>Código</th>\n";
  echo "<th class=\"borda\" align=\"left\" style=\"font-size:13px\" nowrap>Descrição</th>\n";
  echo "<th class=\"borda\" align=\"center\" style=\"font-size:13px\" nowrap>&nbsp;</th>\n";
  echo "<th class=\"borda\" align=\"center\" style=\"font-size:13px\" nowrap>Código</th>\n";
  echo "<th class=\"borda\" align=\"left\" style=\"font-size:13px\" nowrap>Descrição</th>\n";
  echo "</tr>\n";
    for($i = 0;$i < $numrows;$i++) {
       db_fieldsmemory($result,$i);
       echo "<tr style=\"cursor: hand\" bgcolor=\"".(@$cor = (@$cor=="#E4F471"?"#EFE029":"#E4F471"))."\">";
       echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" id=\"check$i\" nowrap>";
       echo "<input type=\"checkbox\" value=\"".$q03_ativ."\"  name=\"check$i\" checked>";
       echo "</td>\n";
       echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" nowrap>".$q03_ativ."</td>\n";
       echo "<td class=\"borda\" style=\"font-size:11px\" align=\"left\" nowrap>".$q03_descr."</td>\n";
       if ( ($i + 1) < $numrows ) {  
          $i++;
          db_fieldsmemory($result,$i);
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" id=\"check$i\" nowrap>";
          echo "<input type=\"checkbox\" value=\"".$q03_ativ."\"  name=\"check$i\" checked>";
          echo "</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" nowrap>".$q03_ativ."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"left\" nowrap>".$q03_descr."</td>\n";
       }
       echo "</tr>";
    }
  echo "&nbsp;</font> <font size=\"2\"> </font></div>";
  echo "<div align=\"center\"> <font size=\"2\"> </font></div></td>";

  echo "</form>\n";
  echo "</table>\n";   

?>
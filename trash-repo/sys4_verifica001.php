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

$rstab = pg_exec("select d.codmod,d.nomemod,m.codarq,a.nomearq,a.tipotabela
                  from   db_sysarquivo a
                         inner join db_sysarqmod m on a.codarq = m.codarq
                         inner join db_sysmodulo d on d.codmod = m.codmod 
			 where ativo is true
	          order by nomemod,nomearq");

function db_fputs($variavel,$conteudo){

  $GLOBALS['fd'] .= $conteudo;

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function mo_camada(camada){
   alvo = document.getElementById(camada);
   divs  = document.getElementsByTagName("DIV");
   for (var j = 0; j < divs.length; j++){
      if (divs[j].className =="tabela" && alvo.id == divs[j].id){
         alvo.style.visibility = "visible";
      }else if (divs[j].className =="tabela" && alvo.id != divs[j].id){
         divs[j].style.visibility = "hidden";
      }
   }
}
function valida_arquivo(){
    if (document.form1.arquivo.value == ""){
       alert('Selecione um arquivo!'); 
       return false;
    }else{

       js_OpenJanelaIframe('top.corpo','db_iframe_modulo','sys4_verifica002.php?qual_arquivo='+document.form1.arquivo.value+'&tipodif='+document.form1.tipodif.value,'Verifica Estrutura',true);
      
       return true
    }
}
function valida_modulo(tipo){

    if (tipo == 1) {
      if (document.form1.modulos.value == "") {
	 alert('Selecione um módulo!'); 
	 return false;
      }
    } else {
      if (confirm('Este processo demora... tem certeza que deseja continuar?') == false) {
	return false;
      }
    }

    js_OpenJanelaIframe('top.corpo','db_iframe_modulo','sys4_verifica002.php?qual_modulo='+document.form1.modulos.value+'&tipodif='+document.form1.tipodif.checked+'&tipo='+tipo,'Verifica Estrutura',true);
    return true;
}
function js_marca(obj){

  var F = document.form1;
  for(i=0;i<F.length;i++){
    if(F.elements[i].type == 'checkbox' && F.elements[i].name != obj.name){
      F.elements[i].checked = false;
    }
  }
  document.form1.arquivo.value = obj.value;
  document.getElementById('arqproc').value = obj.name;

}
	
</script>
<style type="text/css">
.tabela {border:1px solid black; top:25px; left:150}
.input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
.tdblack {

    border-bottom:1px solid black;

}
.cl_iframe {
   border: 1px solid #999999;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="js_trocacordeselect()">
<table width=790 bgcolor="#CCCCCC">

  <tr>
     <td height=25>&nbsp;</td>
  </tr>		
</table>
<form method="post" name="form1" >
  <table border="2" cellspacing="0" cellpadding="0" bgcolor="#cccccc" style='border:1px solid black'>
  <tr> 
     <td colspan=4 align='center' style='border-bottom:1px solid black'><font size='4'><b>Módulos</b></font></td>
  </tr>
  <tr>
  <td colspan=2>
 <? 
   $rsmod = pg_exec("select m.codmod,m.nomemod 
     	                from   db_sysmodulo m
	               	       inner join db_sysarqmod s on s.codmod = m.codmod
			       where ativo is true
		        group by m.codmod,m.nomemod
   	                order by nomemod");
      echo  "<select  name='modulos' size='20' onchange=\"mo_camada(document.form1.modulos.value);\">";
     for ($i = 0;$i < pg_numrows($rsmod); $i++) {
          echo "<option value='".trim(pg_result($rsmod,$i,"codmod"))."'>".trim(pg_result($rsmod,$i,"nomemod"))."</option>\n";  
      }
 ?>
  </select></td></tr>
 <?
   // cria as layers com o conteúdo das tabelas
   $j = 0;
   $modulo = "";
  //define quantos checkboxes iram ficar por linha da tabela.
   $quebratab = 1;
   while ($j < pg_numrows($rstab)){
      db_fieldsmemory($rstab,$j); 
      if ($modulo == $nomemod){
	if ($quebratab == 4){
            $quebratab = 1;	
            echo "</tr><tr>";
        }else{
            $quebratab++;
        }
        echo "<td width=135 ><input type='checkbox' name='".$nomearq."' id='id_$nomearq' value='".$codarq."' onclick=\"js_marca(this)\"><font color='".($tipotabela=='2'?'red':($tipotabela=='0'?'green':'blue'))."'><label for='id_$nomearq'><b>".$nomearq."</b></label></font></td>\n";
      }else{
         $quebratab=1;
         echo "</table>";
         echo "</div><div id='".$codmod."'style='position:absolute; visibility:hidden' class='tabela'>";
         echo "<table border=0 cellspacing=0>";
         echo "<td colspan=4 align='center' style='border-bottom:1px solid black'><font size='4'><b>Tabelas - módulo $nomemod</b></font></td></tr>";
         echo "<tr>
	       <td align='center' style='border-bottom:1px solid black'><font size='2'>Tipos de Tabelas:</font></td>
	       <td align='center' bgcolor='green' style='border-bottom:1px solid black'><font size='2' color='white'><b>Manutenção</b></font></td>
	       <td align='center' bgcolor='blue' style='border-bottom:1px solid black'><font size='2' color='white'><b>Parâmetro</b></font></td>
	       <td align='center' bgcolor='red' style='border-bottom:1px solid black'><font size='2' color='white'><b>Dependência</b></font></td>
	       </tr>";
	 
         echo "<tr>\n<td width=135 ><input type='checkbox' name='$nomearq' id='id_$nomearq' value='".$codarq."' onclick=\"js_marca(this)\"><font color='".($tipotabela=='2'?'red':($tipotabela=='0'?'green':'blue'))."'><label for='id_$nomearq'><b>".$nomearq."</b></label></font></td>\n";
     }
   $modulo = $nomemod;
   $j++;
   }
    echo "</tr>";
echo "</table>";
   echo "</div>";     
        

  ?>      
  <tr>
     <td colspan=2 align='center'>
     <input type="button"   name="b_modulo"       value="Processar módulo"  class="input" onclick="valida_modulo(1)"><br>
     <input type="button"   name="b_todosmodulo"  value="Todos os módulos"  class="input" onclick="valida_modulo(2)"><br>
     <input type="button"   name="b_arquivo"      value="Processar arquivo" class="input" onclick="valida_arquivo()"><br>
     Tipos diferentes:
     <input type="checkbox" id="tipodif">
     <input type="hidden"  name="arquivo" value="">
     </td>
  </tr>
  </table>

  
</td>
<td>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  </form> 
</body>
</html>
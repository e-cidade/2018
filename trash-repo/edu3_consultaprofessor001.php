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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rechumano_classe.php");
include("classes/db_rechumanoescola_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrechumano       = new cl_rechumano;
$clrechumanoescola = new cl_rechumanoescola;
$clrotulo          = new rotulocampo;
$clrechumano->rotulo->label("ed20_i_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("ed18_i_escola");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
</head>
<script>
nextfield = "campo1"; // nome do primeiro campo
netscape  = "";
ver       = navigator.appVersion;
len       = ver.length;
for (iln = 0; iln < len; iln++)
  if (ver.charAt(iln) == "(")
    break;
netscape = (ver.charAt(iln+1).toUpperCase() != "C");
function keyDown(DnEvents) {
    
  k = (netscape) ? DnEvents.which : window.event.keyCode;
  if (k == 13) { // pressiona tecla enter
    if (nextfield == 'done') {
      return true; // envia quando termina os campos
    } else {
      eval(" document.getElementById('"+nextfield+"').focus()" );
      return false;
    }
  }
}
document.onkeydown = keyDown;
if(netscape)
 document.captureEvents(Event.KEYDOWN|Event.KEYUP);
function js_redireciona(chave) {
	
  js_OpenJanelaIframe('','db_iframe_professor','edu3_consultaprofessor002.php?chavepesquisa='+chave,
	  	              'Consulta de Professores',true
	 	             );
 
}
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<center>
<fieldset style="width:95%"><legend><b>Consulta de Professores</b></legend>
<table width="100%" border="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <table width="100%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
     <td width="20%" nowrap>
      <b>Matrícula:</b>
     </td>
     <td>
      <?db_input("ed284_i_rhpessoal",10,@$Ied284_i_rhpessoal,true,"text",1,"onFocus=\"nextfield='pesquisar2'\"");?>
     </td>
    </tr>
    <tr>
     <td width="20%" nowrap>
      <b>CGM:</b>
     </td>
     <td>
      <?db_input("ed285_i_cgm",10,@$Ied285_i_cgm,true,"text",1,"onFocus=\"nextfield='pesquisar2'\"");?>
     </td>
    </tr>
    <tr>
     <td>
      <?=$Lz01_nome?>
     </td>
     <td>
      <?db_input("z01_nome",50,$Iz01_nome,true,"text",1,"onFocus=\"nextfield='pesquisar2'\"");?>
     </td>
    </tr>
    <tr>
     <td nowrap>
      <b>Escola:</b>
     </td>
     <td>
      <?
      $result1 = $clrechumanoescola->sql_record($clrechumanoescola->sql_query("",
                                                                              "DISTINCT ed18_i_codigo,ed18_c_nome",
                                                                              "ed18_c_nome",
                                                                              ""
                                                                             )
                                               );
      $linhas1 = pg_num_rows($result1);
      ?>
      <select name="ed75_i_escola" id= "ed75_i_escola" style="font-size:10px;width:300px" 
              onFocus="nextfield='pesquisar2'">
       <option value=''></option>
       <?
        for ($x=0;$x<$linhas1;$x++) {

          db_fieldsmemory($result1,$x);
          echo "<option value='$ed18_i_codigo' ".(@$ed75_i_escola==$ed18_i_codigo?"selected":"").">$ed18_c_nome</option>";

        }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td align="center" colspan="3">
      <input name="pesquisar" type="button" id="pesquisar2" value="Pesquisar" onclick="js_pesquisar2();" 
             onFocus="nextfield='done'">
      <input name="limpar" type="button" value="Limpar" onClick="location.href='edu3_consultaprofessor001.php'">
     </td>
    </tr>
   </table>
   </form>
  </td>
 </tr>
</table>
</fieldset>
<table width="100%">
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   if (isset($pesquisar)) {
   	
    ?><fieldset style="width:95%"><legend><b>Registros</b></legend><?
    $campos  = " distinct on (z01_nome) case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome, ";
    $campos .= "          case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as z01_numcgm, ";
    $campos .= "          case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as z01_cgccpf ";
    $where   = "";
    if (isset($ed284_i_rhpessoal) && (trim($ed284_i_rhpessoal) != "")) {
      $where .= " AND ed284_i_rhpessoal = $ed284_i_rhpessoal";
    }
    if (isset($ed285_i_cgm) && (trim($ed285_i_cgm) != "")) {
      $where .= " AND ( ed285_i_cgm = $ed285_i_cgm OR cgmrh.z01_numcgm = $ed285_i_cgm ) ";
    }
    if (isset($z01_nome) && (trim($z01_nome) != "")) {
      $where .=" AND to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome 
                 else cgmcgm.z01_nome end) like '".TiraAcento(strtoupper($z01_nome))."%'";     
    }
    if (isset($ed75_i_escola) && (trim($ed75_i_escola) != "")) {
      $where .= " AND ed75_i_escola = $ed75_i_escola";
    }
    $sql     = $clrechumano->sql_query_escola("",$campos,"z01_nome"," ed01_c_regencia = 'S' ".$where);
    $repassa = array();
    if (isset($chave_ed18_i_codigo)) {
     $repassa = array("ed20_i_codigo" => $ed20_i_codigo,
                      "z01_nome"		  => $z01_nome,
                      "ed18_i_escola" => $ed18_i_escola,
                      "atividaderh"   => $atividaderh
                     );
    }
    if (isset($pesquisar)) {
      db_lovrot(@$sql,15,"()","","js_redireciona|z01_numcgm","","NoMe",$repassa);
    }
    ?></fieldset><?
   }
   ?>
   </td>
  </tr>
</table>
</center>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
<script>
js_tabulacaoforms("form1","z01_nome",true,1,"z01_nome",true);
function js_pesquisar2() {
	
  ed284_i_rhpessoal = document.getElementById("ed284_i_rhpessoal").value;
  ed285_i_cgm       = document.getElementById("ed285_i_cgm").value;	  
  nome              = document.getElementById("z01_nome").value;
  escola            = document.getElementById("ed75_i_escola").value;
  location.href = "edu3_consultaprofessor001.php?pesquisar&ed284_i_rhpessoal="+ed284_i_rhpessoal+
                  "&ed285_i_cgm="+ed285_i_cgm+"&z01_nome="+URLEncode(nome)+"&ed75_i_escola="+escola;
  
}
</script>
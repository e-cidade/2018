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

session_start();
if(isset($outro)){
 setcookie("cookie_codigo_cgm");
 header("location:digitainscricao.php");
}
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_cgm_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_issbase_classe.php");
$clcgm = new cl_cgm;
$clissbase = new cl_issbase;
$clempempenho = new cl_empempenho;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$dblink="corpoprincipal.php";
db_logs("","",0,"Digita Codigo do Fornecedor.");
$db_verificaip = db_verifica_ip();
if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),this.cpf);\"";
}else{
  $onsubmit = "";
}



?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script language="JavaScript" src="scripts/prototype.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<table width="100%" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
 <tr>
  <td><br></td>
 </tr>
</table>
<?if($id_usuario!=""){?>
<form name="form">
  <table width="100%" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
   <tr align="center">
    <td>
     <input type="button" id="abertos" name="abertos" value="Ver Empenhos Abertos" onclick="js_verabertos()">
     <input type="button" id="todos" name="todos" value="Ver Todos os Empenhos" onclick="js_vertodos()">
     <input type="button" id="imprimir" name="imprimir" value="Imprimir" 
            onclick='js_geraRelatorio();' />
     <input type="button" id="voltar" name="voltar" value="Voltar" onclick="history.back()">
    </td>
   </tr>
   <tr align="center">
     <td >
       <label style="font-weight: bold;">Filtar por NF</label>
       <input type="text"   id="numeroNF" name="numeroNF" value="" />
       <input type="button" id="pesquisaNF" name="pesquisaNF" value="Pesquisar" onclick="js_pesquisaNF();" />
     </td>
   </tr>
  </table>
</form>
<iframe id="iframe" name="iframe" src="consultafornecedor.php?numcgm=<?=$id_usuario?>" width="100%" height="280"></iframe>
<?}elseif($w13_permfornsemlog == "f"){?>
 <table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
  <tr height="220">
   <td align="center">
    <img src="imagens/atencao.gif"><br>
    Para acessar suas informações, efetue login.
   </td>
  </tr>
 </table>
<?}elseif($w13_permfornsemlog == "t"){
//verifica se está logado
if(@$codigo_cgm!="" || @$_COOKIE["cookie_codigo_cgm"]!=""){
 $usuario = $codigo_cgm==""?$_COOKIE["cookie_codigo_cgm"]:$codigo_cgm;
// die($clcgm->sql_query("","cgm.z01_cgccpf, cgm.z01_nome, cgm.z01_numcgm","","cgm.z01_numcgm = $usuario"));
 $result  = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_cgccpf, cgm.z01_nome, cgm.z01_numcgm","","cgm.z01_numcgm = $usuario"));
 $linhas  = $clcgm->numrows;
 if($linhas!=0){
  db_fieldsmemory($result,$x);
  //11 14
  if(strlen($z01_cgccpf)>11){
   $cgc = $z01_cgccpf;
   $cpf = "";
  }else{
   $cgc = "";
   $cpf = $z01_cgccpf;
  }
   db_redireciona("digitafornecedor.php?id_usuario=$z01_numcgm&cpf=$cpf&cgc=$cgc");
  }
 }else{?>
 <form name="form1" method="post" action="digitafornecedor.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
   <tr>
    <td width="50%" height="30" align="right">Nº Fornecedor / CGM:&nbsp;</td>
    <td width="50%" height="30"><input name="codigo_cgm" type="text" class="digitacgccpf" id="codigo_cgm" size="10" maxlength="10"></td>
   </tr>
   <tr>
    <td width="50%" height="30" align="right">CNPJ:&nbsp;</td>
    <td width="50%" height="30"><input name="cgc" type="text" class="digitacgccpf" id="cgc"  onKeyDown="FormataCNPJ(this,event)" size="18" maxlength="18"></td>
   </tr>
   <tr>
    <td width="50%" height="30" align="right">CPF:&nbsp;</td>
    <td width="50%" height="30"><input name="cpf" type="text" class="digitacgccpf" id="cpf" onKeyDown="FormataCPF(this,event)" size="14" maxlength="14"></td>
   </tr>
   <tr>
    <td width="50%" height="30">&nbsp;</td>
    <td width="50%" height="30">
     <input class="botao" type="submit" name="pesquisa" value="Pesquisa" class="botaoconfirma">
    </td>
   </tr>
  </table>
 </form>
<?}
}?>
</body>
</html>
<script>

  var sTipoPesquisa = 'abertos';
  iNumeroNF = null;

  function js_verabertos() {
 
    $('numeroNF').value = "";
    iNumeroNF = null;
    sTipoPesquisa = "abertos";
    document.form.abertos.disabled=true;
    document.getElementById('iframe').src="consultafornecedor.php?numcgm=<?=$id_usuario?>&tipo_consulta=abertos";
  }
 
  function js_vertodos() {
   
    $('numeroNF').value = "";
    iNumeroNF = null;
    sTipoPesquisa = "todos";
    document.form.todos.disabled=true;
    document.getElementById('iframe').src="consultafornecedor.php?numcgm=<?=$id_usuario?>&tipo_consulta=todos";
  }
   
  function js_pesquisaNF() {
    
    iNumeroNF = $F('numeroNF');
    sTipoPesquisa = "todos";
    document.getElementById('iframe').src="consultafornecedor.php?numcgm=<?=$id_usuario?>&nota_fiscal="+iNumeroNF;
  }
  
  function js_geraRelatorio() {
  
    var sURL = 'numcgm=<?=$id_usuario?>&nota_fiscal='+iNumeroNF+'&tipo_consulta='+sTipoPesquisa;
    
    window.open('relatorio_empenho.php?'+sURL, 'relatorio', 'width=780,height=500'); 
  }
 
</script>
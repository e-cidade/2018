<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");
require_once("classes/db_inicial_classe.php");
require_once("classes/db_processoforoinicial_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_promitente_classe.php");
require_once("classes/db_propri_classe.php");
require_once("classes/db_jurpeticoes_classe.php");
require_once("classes/db_parjuridico_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clinicial             = new cl_inicial;
$clprocessoforoinicial = new cl_processoforoinicial;
$cliptubase            = new cl_iptubase;
$clpromitente          = new cl_promitente;
$clpropri              = new cl_propri;
$cljurpeticoes         = new cl_jurpeticoes;
$oDaoParJuridico       = new cl_parjuridico();
$aParametrosJuridico   = $oDaoParJuridico->getParametrosJuridico(db_getsession('DB_instit'), db_getsession('DB_anousu'));
$oParametrosJuridico   = $aParametrosJuridico[0];

$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v52_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v54_descr");
$clrotulo->label("z01_nome");

$db_botao=1;
$db_opcao=1;
$dadosini="";

if(isset($iniciais)){
  
  $matriz = split("x",$iniciais);
  for($s=0; $s < sizeof($matriz); $s++){
    $inicial=$matriz[$s];
    if($inicial!=""){
      $sqlerro=false;
      db_inicio_transacao();
      $cljurpeticoes->v60_inicial = $inicial;
      $cljurpeticoes->v60_tipopet = 1;
      $cljurpeticoes->v60_data    = date('Y-m-d',db_getsession('DB_datausu'));
      $cljurpeticoes->v60_hora    = db_hora();
      $cljurpeticoes->v60_usuario = db_getsession('DB_id_usuario');
      $cljurpeticoes->v60_texto   = "null";
      $cljurpeticoes->incluir(null);
      if ($cljurpeticoes->erro_status==0){
        $erro=$cljurpeticoes->erro_msg;
        $sqlerro=true;
        db_msgbox($erro);
      }
      db_fim_transacao($sqlerro);

      $res = $clinicial->sql_record($clinicial->sql_query($inicial,"a.z01_nome as advogado,v57_oab",null," v50_inicial = $inicial  and v50_instit = ".db_getsession('DB_instit') )); 
      $numrows= $clinicial->numrows;
      if($numrows==0){
        echo   "<script>parent.location.href='jur2_parcelamento.php?testini=false';</script>";
      }
      
      $sWhere = "processoforoinicial.v71_inicial = {$inicial} and processoforoinicial.v71_anulado is false";
      $result = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query(null,"v70_codforo",null,$sWhere));
      $numrows= $clprocessoforoinicial->numrows;
      if($numrows==0){
        echo   "<script>parent.location.href='jur2_parcelamento.php?codforo=false';</script>";
      }

      $sql="select k00_inscr,k00_matric from inicialnumpre
      left outer join arreinscr on arreinscr.k00_numpre=v59_numpre
      left outer join arrematric on arrematric.k00_numpre=v59_numpre
      where v59_inicial = $inicial";
      
      $result = pg_query($sql);
      if (pg_numrows($result)!=0){
        db_fieldsmemory($result,0);
      }
      
      if(isset($k00_matric)&&$k00_matric!=""){
        $modo="matricula";
        $j01_matric = $k00_matric;
        $chave = $j01_matric;
      }
      if(isset($k00_inscr)&&$k00_inscr!=""){
        $modo="inscricao";
        $q02_inscr = $k00_inscr;
        $chave = $q02_inscr;
      }
      
      $dadosini .= "xx".@$inicial."ww".@$chave."ww".@$modo;
      $peticao   = "iCodigoPeticao={$cljurpeticoes->v60_peticao}";
      
    } 
  }
  $sProgramaRelatorio = empty($oParametrosJuridico->v19_templateparcelamento) ? "jur2_geradoc.php" : "jur2_peticao003.php";
  
  echo "
  <script>
    jan = window.open('{$sProgramaRelatorio}?dadosini=$dadosini&sTipoPeticao=parcelamento&fazparcela=true&{$peticao}','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  </script>";  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
<script>
function js_pesquisar(inicial){
  location.href="jur2_parcelamento.php?v50_inicial="+inicial;
}
function js_marca(obj){
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox'){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
    }
  }
  return false;
}
function js_gera(){
  obj = document.getElementsByTagName("INPUT");
  var nums="";
  var t="";
  var ent=false;
  for(var i=0; i<obj.length; i++){
    if(obj[i].type=="checkbox"){
      if(obj[i].checked){
        nums += t+obj[i].value;
        var ent=true;
      } 	 
      t="x";
    }
  }
  if(ent==false){
    alert("Marque um das opções!");
  }else{
    obj=document.getElementsByTagName("INPUT");
    var inis="";
    var t="";
    var ent=false;
    for(var i=0; i<obj.length; i++){
      if(obj[i].type=="checkbox"){
        if(obj[i].checked){
          inis += t+obj[i].value;
          var ent=true;
          t="x";
        } 	 
      }
    }
    document.form1.iniciais.value=inis;  
    document.form1.submit();
  }  
}
</script>
</head>
<body bgcolor=#CCCCCC  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="596" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
<form name="form1" method="post" action="">
<tr>
<td valign="top">
<input type="hidden" name="iniciais">
<br><br>
<table border="1"  align="center">	    
<tr>
<td colspan="6" align="center">
<b>INICIAIS COM PARCELAMENTO EM ANDAMENTO</b>
</td>
</tr>  
<tr>
<td><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'><b>T</b></a></td>
<td><b>Inicial</b></td>
<td><b>Situação</b></td>
<td><b>Localização</b></td>
<td><b>Advogado</b></td>
</tr>
<?
$result=$clinicial->sql_record($clinicial->sql_query_sitpar("","cgm.z01_nome,v50_inicial,v52_descr,v54_descr","v50_inicial"," v50_instit = ".db_getsession('DB_instit')." and v60_inicial is null"));
$numrows=$clinicial->numrows;
for($i=0; $i<$numrows; $i++){
  db_fieldsmemory($result,$i);
  echo "<tr>  
  <td align='left'><input name='check_".$i."' type='checkbox' value='$v50_inicial'></td>
  <td>$v50_inicial</td>
  <td>$v52_descr</td>
  <td>$v54_descr</td>
  <td>$z01_nome</td>
  </tr>";
  
}  
?>  
</table>
</td>
</tr>
</form>
</table>
</body>
</html>
<script>
function js_veri(){
  if(document.form1.v50_inicial.value==""){
    alert("Indique uma inicial!");
    return false;
  }
  return true;
}
function js_pesquisav50_inicial(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicial1|0';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostrainicial';
  }
}
function js_mostrainicial1(chave){
  document.form1.v50_inicial.value=chave;    
  db_iframe.hide();
}
function js_mostrainicial(chave,erro){
  if(erro==true){
    document.form1.v50_inicial.focus(); 
  }else{
    document.form1.v50_inicial.value=chave;    
  }
}
</script>
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
include("classes/db_inicial_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_processoforoinicial_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_promitente_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_socios_classe.php");
include("classes/db_cgm_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clinicial             = new cl_inicial;
$clissbase             = new cl_issbase;
$clprocessoforoinicial = new cl_processoforoinicial;
$cliptubase            = new cl_iptubase;
$clpromitente          = new cl_promitente;
$clpropri              = new cl_propri;
$clsocios              = new cl_socios;
$clcgm                 = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");


$db_botao=1;
$db_opcao=1;
$retorno = false;
$monitora = false;

if(isset($pesquisar)){
  $inicial=$v50_inicial;
  $res = $clinicial->sql_record($clinicial->sql_query($inicial,"z01_nome as advogado,v57_oab",null," v50_inicial = $inicial and v50_instit = ".db_getsession('DB_instit') ));
  $numrows= $clinicial->numrows;
  if($numrows==0){
    db_redireciona("jur2_atuender.php?testini=false");
  }else{
    db_fieldsmemory($res,0);//pega advogado
  }
  
  $sWhere = "processoforoinicial.71_inicial = {$inicial} and processoforoinicial is false";
  $result = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query(null,"v70_codforo",null,$sWhere)); 
  $numrows= $clprocessoforoinicial->numrows;
  if($numrows==0){
    db_redireciona("jur2_atuender.php?codforo=false");
  }else{
    db_fieldsmemory($result,0);//pega codigo do processo
  }
  
  
  
  $sql =" select distinct k00_inscr,k00_matric 
	          from inicial
                 inner join inicialcert           on v50_inicial = v51_inicial 
                 inner join processoforoinicial   on v71_inicial = v51_inicial
                                                 and v71_anulado is false
                 inner join certid                on v13_certid  = v51_certidao
								                                 and v13_instit  = ".db_getsession('DB_instit')."
                 inner join certdiv               on v14_certid  = v13_certid
                 inner join divida                on v14_coddiv  = v01_coddiv
								                                 and v01_instit  = ".db_getsession('DB_instit')."
                 left outer join arreinscr        on arreinscr.k00_numpre  = v01_numpre
                 left outer join arrematric       on arrematric.k00_numpre = v01_numpre
           where v50_instit  = ".db_getsession('DB_instit')." 
					   and v50_inicial = $inicial ";
  
  $result = pg_query($sql);
  db_fieldsmemory($result,0);
  
  if($k00_matric!=""){
    $modo="matricula";
    $j01_matric = $k00_matric;
    $chave = $j01_matric;
  }
  if($k00_inscr!=""){
    $modo="inscricao";
    $q02_inscr = $k00_inscr;
    $chave = $q02_inscr;
  }
  $retorno=true;
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
<?
if(isset($inicial)){
  $dadosini = "xx".$inicial."ww".$chave."ww".$modo;
  ?>
  function js_geratu(){
    
    obj=document.getElementsByTagName("INPUT");
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
      jan = window.open('jur2_geradoc.php?atuender=true&dadosini=<?=$dadosini?>&nums='+nums,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      location.href="jur2_atuender.php";
    }  
  }
  <?
}
?>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
<tr> 
<td align="center" valign="top" bgcolor="#cccccc">     
<form name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
<br>
<br>
<?
if($retorno==true){
  ?>  
  <tr>
  <td  align="center" colspan="2">
  <b>Confirma  endereços:</b>      
  </td>
  </tr>
  <? 
  if(isset($j01_matric)){     
    
    $reiptu = $cliptubase->sql_record($cliptubase->sql_query($j01_matric,"cgm.z01_nome as nome,cgm.z01_numcgm")); 
    $numiptu=$cliptubase->numrows;
    if($numiptu!=0){
      db_fieldsmemory($reiptu,0);
      echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."y0' name='proprinci' checked></td><td><b>Proprietário principal:</b>$nome</td></tr>";
    }
    $repro = $clpropri->sql_record($clpropri->sql_query($j01_matric,"","cgm.z01_nome as nome,cgm.z01_numcgm")); 
    $numpropri=$clpropri->numrows;
    if($numpropri!=0){
      for($xi=0;$xi<$numpropri;$xi++){
        db_fieldsmemory($repro,$xi);
        echo "<tr><td><input type='checkbox'  id='check' value='".$z01_numcgm."y1'  name='propri$xi' checked></td><td><b>Outro proprietário:</b>$nome</td></tr>";
      }
    } 
    $repromi = $clpromitente->sql_record($clpromitente->sql_query($j01_matric,"","cgm.z01_nome as nome,j41_tipopro as tipopro,cgm.z01_numcgm")); 
    $numpromi=$clpromitente->numrows;
    if($numpromi!=0){
      for($xy=0;$xy<$numpromi;$xy++){
        db_fieldsmemory($repromi,$xy);
        if($tipopro=="f"){
          echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."y2'  name='promi$xy' checked></td><td><b>Promitente comprador:</b>$nome</td></tr>";
          
        }else{
          echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."y3' name='promi$xi' checked></td><td><b>Promitente comprador prinicipal:</b>$nome</td></tr>";
        }  
      }
    } 
  }else if(isset($q02_inscr)){
    $reiptu = $clissbase->sql_record($clissbase->sql_query($q02_inscr,"cgm.z01_numcgm,z01_nome as nome")); 
    db_fieldsmemory($reiptu,0);
    echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."y4' name='proprinci' checked></td><td><b>Principal:</b>$nome</td></tr>";
    
    $reso = $clsocios->sql_record($clsocios->sql_query_file("",$z01_numcgm,"q95_numcgm")); 
    $numso=$clsocios->numrows;
    if($numso!=0){
      for($xr=0;$xr<$numso;$xr++){
        db_fieldsmemory($reso,$xr);
        $re = $clcgm->sql_record($clcgm->sql_query_file($q95_numcgm,"z01_nome as nome,z01_numcgm"));
        db_fieldsmemory($re,0);
        echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."y4' name='proprinci$xr' checked></td><td><b>Sócio:</b>$nome</td></tr>";
        
      }
    } 
    
  } 	
  
  ?>  
  <tr>
  <td colspan="2">
  <input type="button" name="geratu" value="Confirmar" onclick="js_geratu()">
  <input type="button" name="voltar" value="Voltar"  onclick="location.href='jur2_atuender.php'">
  </td>
  </tr>
  
  <?
}else if($retorno==false){
  ?>  
  <tr>
  <td nowrap title="<?=@$Tv50_inicial?>">
  <?
  db_ancora(@$Lv50_inicial,"js_pesquisav50_inicial(true);",1);
  ?>
  </td>
  <td> 
  <?
  db_input('v50_inicial',8,$Iv50_inicial,true,'text',1," onchange='js_pesquisav50_inicial(false);'")
  ?>
  </td>
  </tr>
  <tr>   
  <td colspan="2" align="center">
  <br>
  <input type="submit" name="pesquisar" value="Emitir">
  </td>   	 
  </tr>	 
  
  <?
}
?>     
</table> 	 
</form>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
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
    alert("Inicial Inválida! Verifique.");
    document.form1.v50_inicial.focus(); 
  }else{
    document.form1.v50_inicial.value=chave;    
  }
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
if(isset($codforo) && $codforo!="" && $retorno==false){
  db_msgbox("Inicial sem o codigo do processo do fórum lançado!");
  empty($codforo);;
}
if(isset($testini) && $testini!="" && $retorno==false){
  db_msgbox("Inicial não existe!");
  empty($codforo);;
}
?>
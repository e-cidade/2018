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
require("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_promitente_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$db_opcao = 1;

if(!isset($_self)){
 $sql = "select db21_regracgmiptu, db21_regracgmiss from db_config where codigo = ".db_getsession("DB_instit");
 $res = pg_query($sql);
 db_fieldsmemory($res, 0);
}

//echo 
if ( $opcao == 'matricula' ){
  $clsqlamatriculas = new cl_iptubase;
  $sql = $clsqlamatriculas->sqlmatriculas_nome_numero($numcgm, $db21_regracgmiptu);
}elseif ( $opcao == 'socios' ){
  $clsqlinscricoes = new cl_issbase;
  $sql = $clsqlinscricoes->sqlinscricoes_socios(0,$numcgm,"*");
}elseif ( $opcao == 'inscricao' ){
  $clsqlinscricoes = new cl_issbase;
  $sql = $clsqlinscricoes->sqlinscricoes_nome($numcgm);
}elseif($opcao == 'proprietario'){
  $clsqlpropri = new cl_propri;
  $sql = $clsqlpropri->sql_query($matricula);
  $propripromi = 'PROPRIETÁRIOS';
}elseif($opcao == 'promitente'){
  $clsqlpromi = new cl_promitente;
  $sql = $clsqlpromi->sql_query($matricula);
  $propripromi = 'PROMITENTES';
}
$result = pg_exec($sql) or die($sql);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_mostracgm(cgm){
  js_OpenJanelaIframe('top.corpo','db_iframe_cgm','prot3_conscgm002.php?fechar=func_nome&numcgm='+cgm,'Pesquisa',true);
}
function js_mostrabic_matricula(matricula){
  js_OpenJanelaIframe('top.corpo','db_iframe_matric','cad3_conscadastro_002.php?cod_matricula='+matricula,'Pesquisa',true);
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
function js_mostrabic_inscricao(inscricao){
  js_OpenJanelaIframe('top.corpo','db_iframe_inscr','iss3_consinscr003.php?numeroDaInscricao='+inscricao,'Pesquisa',true);
}


function js_relatorio(){
  
  var regracgm =  document.form1.regracgm2.value;
  if (document.form1.regracgm) {
    regracgm = document.form1.regracgm.value;
  }
  jan = window.open('cai3_gerfinanc017.php?opcao=<?=$opcao?>&matricula='+
                     document.form1.matricula.value+'&inscricao='+document.form1.inscricao.value+
                     '&numcgm='+document.form1.numcgm.value+'&regracgm='+document.form1.regracgm.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
	  
}

function js_self(){
 document.form1.db21_regracgmiptu.value = document.form1.regracgm.value
 document.form1.submit();
}
</script>
<style type="text/css">
<!--
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
<script>
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


</script>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>

<form name="form1" method="post">
<input type="hidden" name="db21_regracgmiptu" value="<?=$db21_regracgmiptu?>">	
<input type="hidden" name="_self" value="">

<tr>
<!--<td colspan="5" align="center"><font face="Arial" size="3"><strong>Outras Matrículas</strong><font><br></td>-->
</tr>
<tr>&nbsp;&nbsp;</tr>
<table border="1" cellpadding="0" cellspacing="0">

<tr bgcolor="#FFCC66">
<?
  if ( $opcao == 'matricula' ){
   //busca informações do loteloc se o campo j18_utilizaloc da tabela cfiptu estiver habilita
    include("classes/db_loteloc_classe.php");
    include("classes/db_cfiptu_classe.php");
    $clloteloc = new cl_loteloc;
    $clcfiptu  = new cl_cfiptu;
    $utilizaloc = $clcfiptu->sql_record($clcfiptu->sql_query("","j18_utilizaloc","","j18_anousu = ".db_getsession("DB_anousu")));
    if($clcfiptu->numrows > 0) {
      db_fieldsmemory($utilizaloc,0);
    } else { 
      $j18_utilizaloc = 'f';
    }
?>
     <th class="borda" style="font-size:12px" nowrap>Matrícula</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo imovel</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo proprietario</th>
     <th class="borda" style="font-size:12px" nowrap>Rua/Avenida</th>
     <th class="borda" style="font-size:12px" nowrap>Numero</th>
     <th class="borda" style="font-size:12px" nowrap>Compl</th>
     <th class="borda" style="font-size:12px" nowrap>Bairro</th>
     <th class="borda" style="font-size:12px" nowrap>ID Lote</th>
     <th class="borda" style="font-size:12px" nowrap>Setor</th>
     <th class="borda" style="font-size:12px" nowrap>Quadra</th>
     <th class="borda" style="font-size:12px" nowrap>Lote</th>
     <th class="borda" style="font-size:12px" nowrap>Área M2</th>
     <?if($j18_utilizaloc != 'f'){?>
      <th class="borda" style="font-size:12px" nowrap>Setorloc</th>
      <th class="borda" style="font-size:12px" nowrap>Quadraloc</th>
      <th class="borda" style="font-size:12px" nowrap>Loteloc</th>
     <?}?>
     <th class="borda" style="font-size:12px" nowrap></th>
   </tr>
<?
  }elseif ( $opcao == 'inscricao' ) {
?>
     <th class="borda" style="font-size:12px" nowrap>Inscrição</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo</th>
     <th class="borda" style="font-size:12px" nowrap>Nome Fantasia</th>
     <th class="borda" style="font-size:12px" nowrap>Data Início</th>
     <th class="borda" style="font-size:12px" nowrap>Data Baixa</th>
     <th class="borda" style="font-size:12px" nowrap></th>
   </tr>
<?
  }elseif ( $opcao == 'socios' || $opcao == 'proprietario' ||$opcao == 'promitente') {
?>
     <th class="borda" style="font-size:12px" nowrap>Numcgm</th>
     <th class="borda" style="font-size:12px" nowrap>Nome</th>
     <th class="borda" style="font-size:12px" nowrap>Endereço</th>
     <th class="borda" style="font-size:12px" nowrap>Bairro</th>
     <th class="borda" style="font-size:12px" nowrap>Município</th>
     <th class="borda" style="font-size:12px" nowrap></th>
   </tr>
<?
  }

$cor="#EFE029";
    for($x=0;$x<pg_numrows($result);$x++){
	  db_fieldsmemory($result,$x,true);
        if($cor=="#EFE029")
           $cor="#E4F471";
        else if($cor=="#E4F471")
	   $cor="#EFE029";
        if ( $opcao == 'matricula' ) {
?>
           <tr title="Clique aqui para verificar os dados" style="cursor: hand" onclick='js_mostrabic_matricula(<?=$j01_matric?>);return false;'>
           <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$j01_matric?>&nbsp;</td>
           <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$j01_tipoimp?>&nbsp;</td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$proprietario?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$nomepri?> <!-- //j14_nome --> </td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j39_numero?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j39_compl?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j13_descr?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j01_idbql?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j34_setor?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j34_quadra?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j34_lote?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$j34_area?></td>
	    <?
	     if($j18_utilizaloc != 'f'){
	      $resultloc = $clloteloc->sql_record($clloteloc->sql_query($j01_idbql,"j06_setorloc,j06_quadraloc,j06_lote"));
	      if($clloteloc->numrows > 0){
               db_fieldsmemory($resultloc,0);
	      }
	    ?>
             <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=@$j06_setorloc?></td>
             <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=@$j06_quadraloc?></td>
             <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=@$j06_lote?></td>
           <?}?>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><a href=''>&nbsp;mais detalhes</a></td>
           </tr>
<?
        }elseif ( $opcao == 'inscricao' ) { 
?>
           <tr title="Clique aqui para verificar os dados" style="cursor: hand"  onclick='js_mostrabic_inscricao(<?=$q02_inscr?>);return false;'>
           <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$q02_inscr?>&nbsp;</td>
           <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$proprietario?>&nbsp;</td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$z01_nomefanta?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$q02_dtinic?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$q02_dtbaix.'   '?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><a href=''>&nbsp;mais detalhes</a></td>
           </tr>
	   
<?
  }elseif ( $opcao == 'socios' || $opcao == 'proprietario' ||$opcao == 'promitente' ) {
?>
           <tr title="Clique aqui para verificar os dados" style="cursor: hand"  onclick='js_mostracgm(<?=$z01_numcgm?>);return false;'>
           <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$z01_numcgm?>&nbsp;</td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$z01_nome?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$z01_ender.', '.$z01_numero.' '.$z01_compl?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$z01_bairro.'  '?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$z01_munic?></td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><a href=''>&nbsp;mais detalhes</a></td>
           </tr>
<?  
        }
   }
?>
</table>
<table>
<tr>
   <td colspan="6" >&nbsp;&nbsp;</td>
</tr>

<?

if($opcao == 'matricula') {
?>	
<tr>
<td colspan="2">
Regra para Emissao:
</td>
<td colspan="4">
<?

$sql_regracgm = "
	select db_syscampodef.defcampo, db_syscampodef.defdescr
	from db_syscampo
	inner join db_syscampodef on db_syscampodef.codcam = db_syscampo.codcam
	where db_syscampo.nomecam = 'db21_regracgmiptu'
	and   db_syscampodef.defcampo = '$db21_regracgmiptu'
	union all
	select db_syscampodef.defcampo, db_syscampodef.defdescr
	from db_syscampo
	inner join db_syscampodef on db_syscampodef.codcam = db_syscampo.codcam
	where db_syscampo.nomecam = 'db21_regracgmiptu' 
	and   db_syscampodef.defcampo <> '$db21_regracgmiptu';
	";
//die($sql_regracgm);

$result_regracgm = pg_query($sql_regracgm);
db_selectrecord("regracgm", $result_regracgm, true, @$db_opcao, " ", "", "", "", "js_self()");

?>
</td>
</tr>
<?
}
?>


<tr>
<input type="hidden" name="matricula" value="<?=@$matricula?>">
<input type="hidden" name="numcgm" value="<?=@$numcgm?>">
<input type="hidden" name="inscricao" value="<?=@$q02_inscr?>">
<input type="hidden" name="regracgm2" value="<?=@$db21_regracgmiptu?>">
<td colspan="6" align="center"><input type="button" value="Imprimir" title="Imprime Relatório" onClick="js_relatorio();";></td>
</tr>
</table>
</form>
</center>
</body>
</html>
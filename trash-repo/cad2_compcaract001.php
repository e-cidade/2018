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

include("classes/db_face_classe.php");
include("classes/db_carface_classe.php");

$clface = new cl_face;
$clcarface = new cl_carface;


$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

//MODULO: cadastro
$clface->rotulo->label();
$clcarface->rotulo->tlabel();
$clrotulo->label("j30_descr");
$clrotulo->label("j14_nome");

$db_opcao=1;

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_carface(){

  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=F&codigo='+documen
   }else{
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=F&codigo='+document.form1.j37_face.value
   }

    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}


function js_pesquisaj37_setor(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_setor.php?funcao_js=parent.js_mostrasetor1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_setor.php?pesquisa_chave='+document.form1.j37_setor.value+'&funcao_js=parent.js_mostrasetor';
  }
}
function js_mostrasetor(chave,erro){
  document.form1.j30_descr.value = chave;
  if(erro==true){
    document.form1.j37_setor.focus();
    document.form1.j37_setor.value = '';
  }
}
function js_mostrasetor1(chave1,chave2){
  document.form1.j37_setor.value = chave1;
  document.form1.j30_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisaj37_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j37_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.j37_codigo.focus();
    document.form1.j37_codigo.value = '';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j37_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_face.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}



function js_emite(){
  datai  = '';
  dataf  = '';
  setor  = '';
  quadra = '';
  rua    = '';
/* 
j37_setor  j37_quadra  j37_codigo 
*/
  if (document.form1.j37_setor.value != ''){
     setor = document.form1.j37_setor.value;
  }
  if (document.form1.j37_quadra.value != ''){
     quadra = document.form1.j37_quadra.value;
  }
  if (document.form1.j37_codigo.value != ''){
     rua = document.form1.j37_codigo.value;
  }else{
     alert('Rua inválida!');
	 return false;
  }
  if (document.form1.baixadas.value == 's'){
	 if(document.form1.baixai_ano.value != '' && document.form1.baixai_mes.value != '' && document.form1.baixai_dia.value!= ''){
         datai = document.form1.baixai_ano.value+'-'+document.form1.baixai_mes.value+'-'+document.form1.baixai_dia.value;
	 }
	 if(document.form1.baixaf_ano.value != '' && document.form1.baixaf_mes.value != '' && document.form1.baixaf_dia.value!= ''){
	     dataf = document.form1.baixaf_ano.value+'-'+document.form1.baixaf_mes.value+'-'+document.form1.baixaf_dia.value;	 
	 }
  }
  jan = window.open('cad2_compcaract002.php?quadra='+quadra+'&setor='+setor+'&rua='+rua+'&baixadas='+document.form1.baixadas.value+'&datai='+datai+'&dataf='+datai+'&grupof='+document.form1.carface.value+'&grupol='+document.form1.carlote.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_periodo(){
  if (document.form1.baixadas.value == 's'){
     document.getElementById('baix').style.visibility = 'visible';
  }else{
     document.form1.baixai_dia.value = '';
     document.form1.baixai_mes.value = '';
     document.form1.baixai_ano.value = '';
     document.form1.baixai.value = '';
     document.form1.baixaf_dia.value = '';
     document.form1.baixaf_mes.value = '';
     document.form1.baixaf_ano.value = '';	 
     document.form1.baixaf.value = '';	 
     document.getElementById('baix').style.visibility = 'hidden';
  }
}


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table border=0 align="center">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>

 <tr>
    <td nowrap title="<?=@$Tj37_setor?>">
       <?
       db_ancora(@$Lj37_setor,"js_pesquisaj37_setor(true);",$db_opcao);
       ?>
    </td>
    <td>
	  <?
	    db_input('j37_setor',4,$Ij37_setor,true,'text',$db_opcao," onchange='js_pesquisaj37_setor(false);'")
	  ?>
      <?
	    db_input('j30_descr',40,$Ij30_descr,true,'text',3,'')
      ?>
    <td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tj37_quadra?>">
       <?=@$Lj37_quadra?>
    </td>
    <td>
	  <?
	  db_input('j37_quadra',4,$Ij37_quadra,true,'text',$db_opcao,"")
	  ?>
    <td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tj37_codigo?>">
       <?
       db_ancora(@$Lj37_codigo,"js_pesquisaj37_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
	  <?
	     db_input('j37_codigo',4,$Ij37_codigo,true,'text',$db_opcao," onchange='js_pesquisaj37_codigo(false);'")
	  ?>
      <?
         db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
      ?>
    <td>
  </tr>
      <tr>
        <td nowrap align='right'>
         <b>Grupo de caracteristicas do lote :</b>
        </td>
        <td nowrap>
        <?
		  $sqlGrupol  = "select j32_grupo,j32_descr from cargrup where j32_tipo = 'L' order by j32_grupo";
          $rsGrupol   = pg_query($sqlGrupol);
          $intNumrows =	pg_numrows($rsGrupol);
		  for($i=0;$i<$intNumrows;$i++){
              db_fieldsmemory($rsGrupol,$i);
		      $lote[$j32_grupo] = $j32_grupo." - ".$j32_descr; 
		  }
          db_select("carlote",$lote,true,2); 
	    ?>
        </td>
      </tr>
      <tr>
        <td nowrap align='right'>
         <b>Grupo de caracteristicas da face :</b>
        </td>
        <td nowrap> 
     	<?
		  $sqlGrupof   = "select j32_grupo,j32_descr from cargrup where j32_tipo = 'F' order by j32_grupo";
          $rsGrupof    = pg_query($sqlGrupof);
          $intNumrowsf = pg_numrows($rsGrupof);
          if ($intNumrowsf == 0) {
             db_msgbox('Não existem características por faces de quadra!');
             exit;
          }

		  for($x=0;$x<$intNumrowsf;$x++){
              db_fieldsmemory($rsGrupof,$x);
		      $face[$j32_grupo] = $j32_grupo." - ".$j32_descr; 
		  }
          db_select("carface",$face,true,2,""); 
	    ?>
        </td>
      </tr>
      <tr>
	  <tr align="top">
		  <td ><div align="left"><font size="2">
			  <b>Processar matriculas  : </b>
        </td>
        <td nowrap> 
			  <?
			  $ll = array ("t" => "Todas", "n" => "Não baixadas", "s" => "Baixadas");
			  db_select('baixadas', $ll, true, 2,"onchange='js_periodo();'" );
			  ?>
		  </td>
	  </tr>
	</table>
	<div id='baix' style='visibility:hidden'>
		<table width="448" align="center" border=0 cellspacing="2" cellspacing="0" cellpadding="0">
		<tr align="top">
			<td width=""  colspan="6"><div align="right"><font size="2">
				<b>Periodo de baixa :</b>
			 </td>
			 <td nowrap>  
				<?
				db_inputdata('baixai', "", "", "", true, 'text', 1, "");
				echo " a ";
				db_inputdata('baixaf', "", "", "", true, 'text', 1, "");
				?>
			</td>
		</tr>
		</table>
	</div>
    <table  width=""  align="center" border=0 cellspacing="2" cellspacing="0" cellpadding="0">
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
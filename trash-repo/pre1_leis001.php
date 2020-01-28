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

$dir = opendir("bin");
clearstatcache();
readdir($dir);
readdir($dir);
$bool = 1;
$verifica_execucao = 0;
while($str = readdir($dir)) {
  if(!is_executable("bin/".$str)) {
    $verifica_execucao = 1;
    if($bool) {
	  echo "<br><br><b>Arquivos sem permissão de execução, necessita 0754. Verifique!</b><br><br>";
	  $bool = 0;
	}
	echo "<b>bin/$str</b><br>";
  }
}
closedir($dir);
if($verifica_execucao)
  exit;

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($retorno)) {
  $result = pg_exec("select id_lei,numerolei,to_char(datalei,'DD') as datalei_dia,to_char(datalei,'MM') as datalei_mes,to_char(datalei,'YYYY') as datalei_ano,ementa from db_leis where id_lei = $retorno");
  db_fieldsmemory($result,0);  
}

if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($_FILES["arq"]);
  if($size == 0) {
    echo "O arquivo $name não foi encontrado ou ele está vazio. Verifique o seu caminho e o seu tamanho e tente novamente.<Br>";
	echo "<a href=\"pre1_leis001.php\">Voltar para cadastro de leis</a>\n";
	exit;
  }
  $ext = strtolower(substr($name,(strlen($name) - 4),strlen($name)));
  $ahtml = $tmp_name.".html";
  $atxt = $tmp_name.".txt";
  switch($ext) {
    case ".doc":
      $adoc = $tmp_name.".doc";
      system("cp $tmp_name $adoc");
	  system("chmod 666 $adoc");	  
      system("bin/wvHtml --targetdir=/tmp $adoc ".substr($ahtml,strrpos($ahtml,"/") + 1));
      system("export LESS='-MM -i';export LESSKEY=/etc/lesskey;export LESSOPEN='|lesspipe \"%s\"';less $ahtml > $atxt");
      //system("rm -f	$adoc");
	  break;
	case ".sdw":
	  $asdw = $tmp_name.".sdw";
	  system("cp $tmp_name $asdw");
	  system("chmod 666 $asdw");
	  system("bin/sdw2txt $asdw > $atxt");
	  system("bin/txt2html.pl $atxt > $ahtml");
     // system("rm -f	$asdw");	  
	  break;
	case ".txt":
      system("cp $tmp_name $atxt");
	  system("chmod 666 $atxt");	  
	  system("bin/txt2html.pl $atxt > $ahtml");
	  break;
	default:
	  echo "O arquivo $name, está com extenção inválida.<Br>
	        Extenções Válidas:<Br>
			<b>.doc</b> Word<br>
			<b>.sdw</b> Star Office<br>
			<b>.txt</b> Texto<br><br>
			<a href=\"\" onclick=\"location.href='cadastroleis.php';return false\">Voltar</a>\n";
	  exit;
	  break;
  }
  $fp = fopen($atxt,"r");
  $atxtB = fread($fp,filesize($atxt));
  fclose($fp);
  //system("rm -f $atxt");
  $fp = fopen($ahtml,"r");
  $ahtmlB = fread($fp,filesize($ahtml));
  fclose($fp);  
  /*
  $ahtmlB = str_replace("'","\'",$ahtmlB);
  $ahtmlB = str_replace('"','\"',$ahtmlB);
  $atxtB = str_replace("'","\'",$atxtB);
  $atxtB = str_replace('"','\"',$atxtB);
  */
  //ajusta acentos...
  $acentosfonte = array("Âº","Ã•","Ãµ",'"',"'","Ã£","Ã".chr(-125),"Ã¡","Ã".chr(-127),"Ã©","Ã".chr(-119),"Ã­","Ã".chr(-115),"Ã³","Ã".chr(-109),"Ãº","Ã".chr(-102),"Ã§","Ã".chr(-121),"Ã¢","Ã".chr(-126),"Ãª","Ã".chr(-118),"Ã®","Ã".chr(-114),"Ã´","Ã".chr(-108),"Ã»","Ã".chr(-101),"Ã".chr(-96),"Ã".chr(-128),"Ã¼","Ã".chr(-100));
  $acentostradu = array("º","Õ","õ",'\"',"\'","ã", "Ã","á","Á","é","É","í","Í","ó","Ó","ú","Ú","ç","Ç","â","Â","ê","Ê","î","Î","ô","Ô","û","Û","à","À","ü","Ü");
//  echo "<br><br><Br>TAM1 ".sizeof($acentosfonte);
//  echo "TAM2 ".sizeof($acentostradu);
//  exit;
  $tamacentos = sizeof($acentosfonte);
  for($i = 0;$i < $tamacentos;$i++) {
    $ahtmlB = str_replace($acentosfonte[$i],$acentostradu[$i],$ahtmlB);  
    $atxtB = str_replace($acentosfonte[$i],$acentostradu[$i],$atxtB);  	
  }  
  system("rm -f $ahtml");
  db_postmemory($HTTP_POST_VARS);  
  $result = pg_exec("SELECT max(id_lei) + 1 FROM db_leis");
  $id_lei = pg_result($result,0,0)==""?"1":pg_result($result,0,0);   
  $result = pg_exec("INSERT INTO db_leis VALUES($id_lei,
                                                '$numerolei',
  											    '$datalei_ano-$datalei_mes-$datalei_dia',
  												'$ementa',
  												'$atxtB',
												'$ahtmlB')") or die("Erro(56) inserindo em db_leis: ".pg_errormessage());
 db_redireciona();
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($_FILES["arq"]);
  db_postmemory($HTTP_POST_VARS);
  if($name == "") {
    pg_exec("update db_leis set
	           numerolei = '$numerolei',
			   datalei = '$datalei_ano-$datalei_mes-$datalei_dia',
			   ementa = '$ementa'
			 where id_lei = $id_lei") or die("Erro(81) atualizando db_leis: ".pg_errormessage());
    db_redireciona();
  } else {
    if($size == 0) {
    echo "O arquivo $name não foi encontrado ou ele está vazio. Verifique o seu caminho e o seu tamanho e tente novamente.<Br>";
	echo "<a href=\"cadastroleis.php\">Voltar para cadastro de leis</a>\n";
	exit;
  }
  $ext = strtolower(substr($name,(strlen($name) - 4),strlen($name)));
  $ahtml = $tmp_name.".html";
  $atxt = $tmp_name.".txt";
  switch($ext) {
    case ".doc":
      $adoc = $tmp_name.".doc";
      system("cp $tmp_name $adoc");
	  system("chmod 666 $adoc");
      system("bin/wvHtml --targetdir=/tmp $adoc ".substr($ahtml,strrpos($ahtml,"/") + 1));
      system("export LESS='-MM -i';export LESSKEY=/etc/lesskey;export LESSOPEN='|lesspipe \"%s\"';less $ahtml > $atxt");
      //system("rm -f	$adoc");
	  break;
	case ".sdw":
	  $asdw = $tmp_name.".sdw";
	  system("cp $tmp_name $asdw");
	  system("chmod 666 $asdw");	  
	  system("bin/sdw2txt $asdw > $atxt");
	  system("bin/txt2html.pl $atxt > $ahtml");
      system("rm -f	$asdw");	  
	  break;
	case ".txt":
      system("cp $tmp_name $atxt");
	  system("chmod 666 $atxt");	  
	  system("bin/txt2html.pl $atxt > $ahtml");
	  break;
	default:
	  echo "O arquivo $name, está com extenção inválida.<Br>
	        Extenções Válidas:<Br>
			<b>.doc</b> Word<br>
			<b>.sdw</b> Star Office<br>
			<b>.txt</b> Texto<br><br>
			<a href=\"\" onclick=\"location.href='cadastroleis.php';return false\">Voltar</a>\n";
	  exit;
	  break;
  }
  $fp = fopen($atxt,"r");
  $atxtB = fread($fp,filesize($atxt));
  fclose($fp);
  system("rm -f $atxt");
  $fp = fopen($ahtml,"r");
  $ahtmlB = fread($fp,filesize($ahtml));  
  fclose($fp);
  $ahtmlB = str_replace("'","\'",$ahtmlB);
  $ahtmlB = str_replace('"','\"',$ahtmlB);
  $atxtB = str_replace("'","\'",$atxtB);
  $atxtB = str_replace('"','\"',$atxtB);
  system("rm -f $ahtml");  
  db_postmemory($HTTP_POST_VARS);  
  }
  pg_exec("update db_leis set
	           numerolei = '$numerolei',
			   datalei = '$datalei_ano-$datalei_mes-$datalei_dia',
			   ementa = '$ementa',
			   texto = '$atxtB',
			   documento = '$ahtmlB'
		   where id_lei = $id_lei") or die("Erro(139) atualizando db_leis: ".pg_errormessage());  
  db_redireciona();
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from db_leis where id_lei = ".$HTTP_POST_VARS["id_lei"]) or die("Erro(141) excluindo db_leis: ".pg_errormessage());
  db_redireciona();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submeter() {
  var F = document.form1;
  
  if(F.numerolei.value == '') {
    alert('Campo numero da lei não pode ser vazio.');
	F.numerolei.focus();
	return false;
  } else if(F.datalei_dia.value == '' || F.datalei_mes.value == '' || F.datalei_ano.value == '') {
    alert('Campo data da lei não pode ser vazio');
	F.datalei_dia.focus();
	return false;	
  } else if(F.ementa.value == '') {
    alert('Campo ementa não pode ser vazio.');
	F.ementa.focus();
	return false;	
  } 
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">	
	
	<br><br><br>
<center>
  <form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return js_submeter()">
  <input type="hidden" name="id_lei" value="<?=@$id_lei?>">
    <table width="55%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="26%" height="25" nowrap><strong>N&uacute;mero da Lei:</strong></td>
        <td width="74%" height="25">
		<input name="numerolei" type="text" id="numerolei" value="<?=@$numerolei?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Data da Lei:</strong></td>
        <td height="25">
		   <?
		     include("dbforms/db_funcoes.php");
			 db_data("datalei",@$datalei_dia,@$datalei_mes,@$datalei_ano);
		   ?>
		<!--input name="datalei_dia" type="text" id="datalei_dia" value="<?=@$datalei_dia?>" onkeyUp="js_digitadata(this.name)" size="2" maxlength="2">
          / 
          <input name="datalei_mes" type="text" id="datalei_mes" value="<?=@$datalei_mes?>" onkeyUp="js_digitadata(this.name)" size="2" maxlength="2">
          /
          <input name="datalei_ano" type="text" id="datalei_ano" value="<?=@$datalei_ano?>" size="4" maxlength="4"-->
		</td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Ementa:</strong></td>
        <td height="25"><input name="ementa" type="text" id="ementa" value="<?=@$ementa?>" size="50" maxlength="200"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Arquivo:</strong></td>
        <td height="25"><input name="arq" type="file" id="arq" size="35"></td>
      </tr>
      <tr> 
        <td height="25" nowrap>&nbsp;</td>
        <td height="25"><input name="incluir" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>>
          &nbsp; 
          <input name="alterar" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>>
          &nbsp; 
          <input name="excluir" type="submit" id="excluir" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>></td>
      </tr>
    </table>
  </form>
</center>
<?
  echo "<center>\n";
  db_lov("select id_lei as db_id_lei,numerolei as \"Número da Lei\",to_char(datalei,'DD-MM-YYYY') as data,ementa from db_leis order by db_id_lei",20,"pre1_leis001.php"); 
  echo "</center>\n";
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
	</td>
  </tr>
</table>
</body>
</html>
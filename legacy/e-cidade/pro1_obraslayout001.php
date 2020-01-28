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
include("classes/db_obraslayout_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_obrasresp_classe.php");
include("classes/db_obrastiporesp_classe.php");
include("classes/db_obraspropri_classe.php");
include("classes/db_obraslote_classe.php");
include("classes/db_obraslotei_classe.php");
include("classes/db_obrasender_classe.php");
include("classes/db_obrasalvara_classe.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_obrasconstr_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_obras_classe.php");
include("classes/db_db_cepmunic_classe.php");
db_postmemory($HTTP_POST_VARS);
$clobraslayout = new cl_obraslayout;
$clobras = new cl_obras;
$clobrasresp = new cl_obrasresp;
$clobraspropri = new cl_obraspropri;
$clobrastiporesp = new cl_obrastiporesp;
$clobraslote = new cl_obraslote;
$clobraslotei = new cl_obraslotei;
$clobrasender = new cl_obrasender;
$clobrasalvara = new cl_obrasalvara;
$clobrashabite = new cl_obrashabite;
$clobrasconstr = new cl_obrasconstr;
$cldb_config = new cl_db_config;
$cldb_cepmunic = new cl_db_cepmunic;
$db_opcao = 1;
$db_botao = true;
$ob01_codobra = @$ob14_codobra;
$espaco = ",";
if(isset($imprimir)){
  $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
  umask(74);
  $tmpfile = tempnam("tmp",'obras').'.zip';
  $fd = fopen($tmpfile,"w");
  $result = $cldb_config->sql_record($cldb_config->sql_query("","cgc,nomeinst,munic,db_cepmunic.db10_codibge","","trim(munic) like trim(db_cepmunic.db10_munic)"));
  db_fieldsmemory($result,0);
  $depart =  str_pad("SECRETARIA DE PLANEJAMENTO E URBANISMO",55," ",STR_PAD_RIGHT);
  $nomeinst = str_pad($nomeinst,55," ",STR_PAD_RIGHT);
  $munic = str_pad($munic,30," ",STR_PAD_RIGHT);
  $linha1 = "1".substr($db10_codibge,0,5).date("Ymd").date("His").date("Ym")."1".$cgc."44".substr($nomeinst,0,55).substr($depart,0,55).$munic."\n";
  fputs($fd,$linha1);
  $resob = $clobrashabite->sql_record($clobrashabite->sql_query("","*",""," ob01_codobra = $ob01_codobra"));
  $numrows = $clobrashabite->numrows;
  if($numrows > 0){
    db_inicio_transacao();
    for($i=0;$i<$numrows;$i++){
      db_fieldsmemory($resob,$i);
      $result = $clobraspropri->sql_record($clobraspropri->sql_query($ob01_codobra));
      if($clobraspropri->numrows > 0){
	db_fieldsmemory($result,0);
      }
      $z01_cgccpf = trim($z01_cgccpf);
      if(strlen($z01_cgccpf) == 11){
	$tipoi = "3";
      }elseif(strlen($z01_cgccpf) == 14){
	$tipoi = "1";
      }elseif(strlen($z01_cgccpf) == 0 && $z01_ident != ""){
	$tipoi = "2";
	$z01_cgccpf = $z01_ident;
      }
      $z01_cgccpf = str_pad($z01_cgccpf,14," ",STR_PAD_RIGHT);
      $z01_nome = str_pad($z01_nome,55," ",STR_PAD_RIGHT);
      $z01_ender = str_pad($z01_ender,55," ",STR_PAD_RIGHT);
      $z01_bairro = str_pad($z01_bairro,20," ",STR_PAD_RIGHT);
      $z01_cep = str_pad($z01_cep,8," ",STR_PAD_RIGHT);
      $result = $cldb_cepmunic->sql_record($cldb_cepmunic->sql_query("","*",""," trim(db10_munic) = trim('$z01_munic')"));
      if($cldb_cepmunic->numrows > 0){
	db_fieldsmemory($result,0);
      }else{
	echo "<script>
		alert('Município sem código do IBGE cadastrado!')
		location.href='pro4_geralayout.php';
	      </script>";
	exit;
      }
      $z01_telef = str_pad($z01_telef,12," ",STR_PAD_RIGHT);
      $ddd = ""; 
      for($j=0;$j<4;$j++){
	$ddd .= $espaco;
      }
      $ddd = str_replace(","," ",$ddd);
      $z01_email = str_pad($z01_email,60," ",STR_PAD_RIGHT);
      $ob02_cod = str_pad($ob02_cod,2," ",STR_PAD_RIGHT);
      $ender_bairro_cep_cod = '';
      for($j=0;$j<89;$j++){
	$ender_bairro_cep_cod .= $espaco;
      }
      $ender_bairro_cep_cod = str_replace(","," ",$ender_bairro_cep_cod);
      $result = $clobrasresp->sql_record($clobrasresp->sql_query("","z01_ident as ident,z01_cgccpf as cgccpf,z01_nome as nome,z01_ender as ender,z01_bairro as bairro,z01_cep as cep,z01_uf as uf,z01_munic as munic",""," ob01_codobra = $ob01_codobra"));
      if($clobrasresp->numrows > 0){
	db_fieldsmemory($result,0);
      }
      $cgccpf = trim($cgccpf);
      if(strlen($cgccpf) == 11){
	$tpi = "3";
      }elseif(strlen($cgccpf) == 14){
	$tpi = "1";
      }elseif(strlen($cgccpf) == 0 && $ident != ""){
	$tpi = "2";
	$cgccpf = $ident;
      }
      $cgccpf = str_pad($cgccpf,14," ",STR_PAD_RIGHT);
      $nome = str_pad($nome,55," ",STR_PAD_RIGHT);
      $ender = str_pad($ender,55," ",STR_PAD_RIGHT);
      $bairro = str_pad($bairro,20," ",STR_PAD_RIGHT);
      $cep = str_pad($cep,8," ",STR_PAD_RIGHT);
      $uf = str_pad($uf,2," ",STR_PAD_RIGHT);
      $result = $cldb_cepmunic->sql_record($cldb_cepmunic->sql_query("","db10_codibge as codibge",""," trim(db10_munic) = trim('$munic')"));
      if($cldb_cepmunic->numrows > 0){
	db_fieldsmemory($result,0);
      }else{
	echo "<script>
		alert('Município sem código do IBGE cadastrado!')
		location.href='pro4_geralayout.php';
	      </script>";
	exit;
      }
      $ob02_cod = str_pad($ob02_cod,2," ",STR_PAD_LEFT);
      $dadosconstr = '';
      for($j=0;$j<88;$j++){
	$dadosconstr .= $espaco;
      }
      $dadosconstr = str_replace(","," ",$dadosconstr);
      $linha2 = "2".$tipoi.$z01_cgccpf.$z01_nome.$z01_ender.$z01_bairro.$z01_cep.$z01_uf.$db10_codibge.$ddd.$z01_telef.$ddd.$z01_telef.substr($z01_email,0,60).$ob02_cod.date("Ymd").$ender_bairro_cep_cod.$tpi.$cgccpf.$nome.$ender.$bairro.$cep.$uf.$codibge; 
    /////////////////////////////////////////////////////// 
      $result = $clobrasalvara->sql_record($clobrasalvara->sql_query($ob01_codobra));
      if($clobrasalvara->numrows > 0){
	db_fieldsmemory($result,0);
      }
      $result = $clobrasender->sql_record($clobrasender->sql_query("","*",""," ob01_codobra = $ob01_codobra and ob07_codconstr = $ob09_codconstr"));
      if($clobrasender->numrows > 0){
	db_fieldsmemory($result,0);
	$j14_nome =  str_pad($j14_nome,55," ",STR_PAD_LEFT);
	$j13_descr =  str_pad($j13_descr,20," ",STR_PAD_LEFT);
	$ob07_inicio = str_pad(str_replace("-","",$ob07_inicio),8," ",STR_PAD_LEFT);
	$ob07_fim =    str_pad(str_replace("-","",$ob07_fim),8," ",STR_PAD_LEFT);
	$ob08_area =  str_pad(str_replace(" ","",db_formatar($ob08_area,'f')),10," ",STR_PAD_LEFT);
	$ob07_unidades =  str_pad($ob07_unidades,5," ",STR_PAD_LEFT);
	$ob07_pavimentos =  str_pad($ob07_pavimentos,5," ",STR_PAD_LEFT);
      }
      $ob04_alvara =  str_pad($ob04_alvara,15," ",STR_PAD_LEFT);
      $ob01_nomeobra = str_pad($ob01_nomeobra,55," ",STR_PAD_LEFT);
      $ob04_data = str_replace("-","",$ob04_data);
      $linha2 = $linha2.$ob04_alvara.$ob04_data.$ob01_nomeobra.$j14_nome.$j13_descr.$cep.$z01_uf.$codibge.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ddd.$ob07_inicio.$ob07_fim.$ob08_ocupacao.$ob08_tipoconstr.$ob08_area.$ob07_unidades.$ob07_pavimentos;
      if($ob09_parcial == "t"){
	$hab = 'P';
      }else{
	$hab = 'T';
      }
      $ob09_habite =  str_pad($ob09_habite,15," ",STR_PAD_LEFT);
      $ob09_area =  str_pad(str_replace(" ","",db_formatar($ob09_area,'f')),10," ",STR_PAD_LEFT);
      $linha3 = "\n3".$ob09_habite.str_replace("-","",$ob09_data).$ob09_area.$hab;  
      fputs($fd,$linha2);
      fputs($fd,$linha3);
    }
      $ob09_habite =  str_pad($ob09_habite,15," ",STR_PAD_LEFT);
    $linha4 = "\n4".str_pad($i,6," ",STR_PAD_LEFT);  
    fputs($fd,$linha4);
    fclose($fd);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
    <center>
        
	<?
	if(isset($imprimir)){
          echo "<br><strong><a style='color:black'  href='http://192.168.1.15/~dbpaulo/dbportal2/$tmpfile'> Arquivo gerado em:  ".$tmpfile."<br>Clique aqui para salvar</a></strong><br><br>";
	}
	include("forms/db_frmobraslayout.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  if($clobraslayout->erro_status=="0"){
    $clobraslayout->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clobraslayout->erro_campo!=""){
      echo "<script> document.form1.".$clobraslayout->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobraslayout->erro_campo.".focus();</script>";
    };
  }else{
    $clobraslayout->erro(true,true);
  };
};
?>
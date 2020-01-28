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

set_time_limit(0);
include("db_conecta.php");
global $configsite;
//die("select * from confsite where w01_cod = $configsite and w01_instit = ".db_getsession("DB_instit"));
$result = db_query("select * from confsite where w01_cod = $configsite and w01_instit = ".db_getsession("DB_instit"));
db_fieldsmemory($result,0);
$result2 = db_query("select
codigo    ,
nomeinst  ,
ender     ,
munic     ,
uf        ,
telef     ,
email     ,
ident     ,
tx_banc   ,
numbanco  ,
url       ,
logo      ,
figura    ,
dtcont    ,
diario    ,
pref      ,
vicepref  ,
fax       ,
cep       ,
bairro    ,
tpropri   ,
prefeitura,
tsocios
from db_config where codigo = ".db_getsession('DB_instit'));
db_fieldsmemory($result2,0);

if (file_exists("classes/db_configdbpref_classe.php")) {
	require_once("classes/db_configdbpref_classe.php");
} else {
	require_once("../classes/db_configdbpref_classe.php");
}
$clconfigdbpref = new cl_configdbpref;
$result3 = $clconfigdbpref->sql_record($clconfigdbpref->sql_query(db_getsession("DB_instit"),"*"));
db_fieldsmemory($result3,0);

function db_geratexto($texto){
  $texto .= "#";
  $txt = split("#",$texto);
  $texto1  = '';
  for($x=0;$x<sizeof($txt);$x++){
     if(substr($txt[$x],0,1) == "$"){
        $txt1 = substr($txt[$x],1);
                global $$txt1;
        $texto1 .= $$txt1;
     }else if(substr($txt[$x],0,2) == '\n'){
        $texto1 .= "\n";
     }else if(substr($txt[$x],0,2) == '\t'){
        $texto1 .= "\t";
     }else{
        $texto1 .= $txt[$x];
     }
  }
  return $texto1;
}

function db_estilosite(){
echo"

.bordas {
        border: ".$GLOBALS['w01_bordamenu']." ".$GLOBALS['w01_estilomenu'].";
        border-color: ".$GLOBALS['w01_corbordamenu'].";
        background-color: ".$GLOBALS['w01_corfundomenu'].";
        cursor: hand;
}
.linksmenu {
        font-style: ".$GLOBALS['w01_estilofontemenu'].";
        font-size: ".$GLOBALS['w01_tamfontemenu'].";
        font-family: ".$GLOBALS['w01_fontemenu'].";
        font-weight: ".$GLOBALS['w01_wfontemenu'].";
        color: ".$GLOBALS['w01_corfontemenu'].";
        text-decoration: ".$GLOBALS['w01_linhafontemenu'].";
}
.texto{
		font-size: ".$GLOBALS['w01_tamfontesite'].";
        font-style: ".$GLOBALS['w01_estilofontesite'].";
        font-family: ".$GLOBALS['w01_fontesite'].";
        color: ".$GLOBALS['w01_cortexto'].";
}
.titulo{
		font-size: ".$GLOBALS['w01_tamfontesite'].";
        font-weight: bold;
        font-family: ".$GLOBALS['w01_fontesite'].";
        color: ".$GLOBALS['w01_cortexto'].";
}
.radioOption {
        font-size: ".$GLOBALS['w01_tamfontesite'].";
        font-style: ".$GLOBALS['w01_estilofontesite'].";
        font-family: ".$GLOBALS['w01_fontesite'].";
        font-weight: ".$GLOBALS['w01_wfontesite'].";
        color: ".$GLOBALS['w01_corfontesite'].";

}
a.links {
        font-size: ".$GLOBALS['w01_tamfontesite'].";
        font-style: ".$GLOBALS['w01_estilofontesite'].";
        font-family: ".$GLOBALS['w01_fontesite'].";
        font-weight: ".$GLOBALS['w01_wfontesite'].";
        color: ".$GLOBALS['w01_corfontesite'].";
        text-decoration: ".$GLOBALS['w01_linhafontesite'].";
}
a.links:hover {
        font-size: ".$GLOBALS['w01_tamfonteativo'].";
        font-style: ".$GLOBALS['w01_estilofonteativo'].";
        font-family: ".$GLOBALS['w01_fonteativo'].";
        font-weight: ".$GLOBALS['w01_wfonteativo'].";
        color: ".$GLOBALS['w01_corfonteativo'].";
        text-decoration: ".$GLOBALS['w01_linhafonteativo'].";
}
a.links:visited {
        font-size: ".$GLOBALS['w01_tamfontesite'].";
        font-style: ".$GLOBALS['w01_estilofontesite'].";
        font-family: ".$GLOBALS['w01_fontesite'].";
        font-weight: ".$GLOBALS['w01_wfontesite'].";
        color: ".$GLOBALS['w01_corfontesite'].";
        text-decoration: ".$GLOBALS['w01_linhafontesite'].";
}
body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
}

input {
        font-family: ".$GLOBALS['w01_fonteinput'].";
        font-style: ".$GLOBALS['w01_estilofonteinput'].";
        font-size: ".$GLOBALS['w01_tamfonteinput'].";
        color: ".$GLOBALS['w01_corfonteinput'].";
        background-color: ".$GLOBALS['w01_corfundoinput'].";
        border: ".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordainput'].";
}
.readonly {
        font-family: ".$GLOBALS['w01_fonteinput'].";
        font-style: ".$GLOBALS['w01_estilofonteinput'].";
        font-size: ".$GLOBALS['w01_tamfonteinput'].";
        color: #000000;
        background-color: #EEEEE2;
        border: ".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordainput'].";

 }

select {
        font-family: ".$GLOBALS['w01_fonteinput'].";
        font-style: ".$GLOBALS['w01_estilofonteinput'].";
        font-size: ".$GLOBALS['w01_tamfonteinput'].";
        color: ".$GLOBALS['w01_corfonteinput'].";
        background-color: ".$GLOBALS['w01_corfundoinput'].";
        border: ".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordainput'].";
}
textarea{
        font-family: ".$GLOBALS['w01_fonteinput'].";
        font-style: ".$GLOBALS['w01_estilofonteinput'].";
        font-size: ".$GLOBALS['w01_tamfonteinput'].";
        color: ".$GLOBALS['w01_corfonteinput'].";
        background-color: ".$GLOBALS['w01_corfundoinput'].";
        border: ".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordainput'].";
}
table.lov {
	border-collapse: collapse;
}
table.lov th {
	border:".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordamenu'].";  ;
	font-family: ".$GLOBALS['w01_fontemenu'].";
    font-size: ".$GLOBALS['w01_tamfontemenu'].";
    color: ".$GLOBALS['w01_corfontemenu'].";
}
table.lov td {
	border:".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordamenu'].";  ;
	font-size: ".$GLOBALS['w01_tamfontesite'].";
    font-style: ".$GLOBALS['w01_estilofontesite'].";
    font-family: ".$GLOBALS['w01_fontesite'].";
    color: ".$GLOBALS['w01_cortexto'].";
}
table.tab {
	border-collapse: collapse;
}
table.tab th {
	border:".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordamenu'].";  ;
	background-color: ".$GLOBALS['w01_corfundomenu'].";
	font-family: ".$GLOBALS['w01_fontemenu'].";
    font-size: ".$GLOBALS['w01_tamfontemenu'].";
    color: ".$GLOBALS['w01_corfontemenu'].";
}
table.tab td {
	border:".$GLOBALS['w01_bordainput']." ".$GLOBALS['w01_estiloinput']." ".$GLOBALS['w01_corbordamenu'].";  ;
	background-color: ".$GLOBALS['w01_corbody'].";
	font-size: ".$GLOBALS['w01_tamfontesite'].";
    font-style: ".$GLOBALS['w01_estilofontesite'].";
    font-family: ".$GLOBALS['w01_fontesite'].";
    color: ".$GLOBALS['w01_cortexto'].";
}

.botao{
      font-family: ".$GLOBALS['w01_fontebotao'].";
      font-size: ".$GLOBALS['w01_tamfontebotao'].";
      font-style: ".$GLOBALS['w01_estilofontebotao'].";
      font-weight: ".$GLOBALS['w01_wfontebotao'].";
      color: ".$GLOBALS['w01_corfontebotao'].";
      background-color: ".$GLOBALS['w01_corfundobotao'].";
      border: ".$GLOBALS['w01_bordabotao']." ".$GLOBALS['w01_estilobotao']." ".$GLOBALS['w01_corbordabotao'].";
      }
.botao:disabled{
	  font-family: ".$GLOBALS['w01_fontebotao'].";
      font-size: ".$GLOBALS['w01_tamfontebotao'].";
      font-style: ".$GLOBALS['w01_estilofontebotao'].";
      font-weight: ".$GLOBALS['w01_wfontebotao'].";
      color: #999999;
      background-color: #CCCCCC;
      border:1px;
}
div.menuBar,
div.menuBar a.menuButton,
div.menu,
div.menu a.menuItem {
  font-family:".$GLOBALS['w01_fontemenu'].";
  font-size:  ".$GLOBALS['w01_tamfontemenu'].";
  font-style: ".$GLOBALS['w01_estilofontemenu']." ;
  font-weight:".$GLOBALS['w01_wfontemenu']." ;
  color: ".$GLOBALS['w01_corfontemenu'].";
  border: ".$GLOBALS['w01_bordamenu']." ".$GLOBALS['w01_estilomenu'].";
  border-color: ".$GLOBALS['w01_corbordamenu'].";
}

div.menuBar {
  border: ".$GLOBALS['w01_bordamenu']." ".$GLOBALS['w01_estilomenu'].";
  border-color: ".$GLOBALS['w01_corbordamenu'].";
  background-color: ".$GLOBALS['w01_corbody'].";
  padding: 3px 3px 3px 3px;
  text-align: left;
}

div.menuBar a.menuButton {
  background-color:".$GLOBALS['w01_corfundomenu'].";
  border: 1px solid;
  border-color:".$GLOBALS['w01_corbordamenu'].";
  color: ".$GLOBALS['w01_corfontemenu'].";
  cursor: default;
  left: 0px;
  margin: 1px;
  padding: 1px 6px 1px 6px;
  position: relative;
  text-decoration: none;
  top: 0px;
  z-index: 100;
}

div.menuBar a.menuButton:hover {
  background-color: ".$GLOBALS['w01_corfundomenuativo'].";<!-- cor do fundo qd passa o mouse -->
  color: ".$GLOBALS['w01_corfontemenu']."; <!--  cor da fonte quando passa o mouse por cima do menu-->
}

div.menuBar a.menuButtonActive,
div.menuBar a.menuButtonActive:hover {
  background-color:".$GLOBALS['w01_corfundomenu'].";   <!-- cor do fundo qd clica -->
  border-color: ".$GLOBALS['w01_corbordamenu'].";
  color: ".$GLOBALS['w01_corfontemenu'].";  <!--  cor da fonte do menu principal qd clica-->
  left: 1px;
  top: 1px;
}

div.menu {
  background-color: ".$GLOBALS['w01_corfundomenu']."; <!--  cor do fundo dos submenus -->
  left: 0px;
  padding: 0px 0px 0px 0px;
  position: absolute;
  top: 0px;
  visibility: hidden;
  z-index: 101;
}

div.menu a.menuItem {
  color:".$GLOBALS['w01_corfontemenu']."; <!-- cor dda fonte dos submenus -->
  cursor: default;
  display: block;
  padding: 3px 1em;
  text-decoration: none;
  white-space: nowrap;
}

div.menu a.menuItem:hover, div.menu a.menuItemHighlight {
  background-color: ".$GLOBALS['w01_corfundomenuativo']."; <!-- cor do fundo dos submenus qd passa o mouse por cima-->

}

div.menu a.menuItem span.menuItemText {}

div.menu a.menuItem span.menuItemArrow {
  margin-right: -.75em;
}

div.menu div.menuItemSep {
  border-top: 1px solid #909090;
  border-bottom: 1px solid #f0f0f0;
  margin: 4px 2px;
}

";
}

function db_rodape() {

	require_once ("libs/db_utils.php");
	require_once ("libs/JSON.php");

	$oJSON = new Services_JSON();
	$sRodape      = "<center>";

	$oDaoDBConfig = db_utils::getDao("db_config");
	$oInstituicao = $oDaoDBConfig->getParametrosInstituicao();

	if ( !empty($oInstituicao->uf) ) {

	  $oEstados     = $oJSON->decode(file_get_contents('imagens/estados/estados.json'));
		$sSiglaEstado = strtolower($oInstituicao->uf);

		if ( !empty($oEstados->$sSiglaEstado) ) {

		  $oEstado   = $oEstados->$sSiglaEstado;
		  if (file_exists($oEstado->sCaminhoImagem)) {
			  $sRodape  .= "	<a target='_blank' href='{$oEstado->sUrl}' style='margin-right:20px;text-decoration:none;'>           ";
			  $sRodape  .= "  	<img src='{$oEstado->sCaminhoImagem}' title='{$oEstado->sTextoAlternativo}' width='37' height='18'> ";
			  $sRodape  .= " </a>                         ";
      }
		}
	}

	if ( file_exists('imagens/gov_br.jpg') ) {

  	$sRodape .= "	<a target='_blank'  href='http://www.brasil.gov.br' style='margin-right:20px;text-decoration:none;'>                            ";
  	$sRodape .= "		<img src='imagens/gov_br.jpg' width='69' height='18'>                                                                         ";
  	$sRodape .= "  </a>                                                                                                                           ";
	}

	if ( file_exists('imagens/adobe.gif') ) {

  	$sRodape .= "	<a target='_blank'  href='http://www.adobe.com/products/acrobat/readstep2.html' style='margin-right:20px;text-decoration:none;'>";
  	$sRodape .= "		<img src='imagens/adobe.gif'>                                                                                                 ";
  	$sRodape .= "	</a>                                                                                                                            ";
	}
  $sRodape .= "  <font style='margin-right:20px;'>                                                                                              ";
	$sRodape .= "		<br /><br />                                                                                                                  ";
	$sRodape .= "		copyright " . date('Y') . " &copy;                                                                                            ";
	$sRodape .= "	  <a target='_blank' href='http://www.dbseller.com.br'>www.dbseller.com.br </a>                                                 ";
	$sRodape .= "  </font>                                                                                                                        ";
	$sRodape .= "</center>                                                                                                                        ";

	echo $sRodape;
}

function db_numpre_sp($qn,$qnp="x",$qnt="x",$qnd="x"){
//#00#//db_numpre_sp
//#10#//Esta funcao coloca a mascara no numpre SEM os pontos entre os número
//#15#//db_numpre_sp($qn,$qnp="x",$qnt="x",$qnd="x");
//#20#//qn  : Número do numpre, normalmento k00_numpre
//#20#//qnp : Número da parcela do numpre
//#20#//qnt : Número da quantidade de parcelas do numpre
//#20#//qnd : Dígito verificador do numpre
//#40#//Código de arrecadação formatado SEM os pontos
//#99#//Exemplo:
//#99#//db_numpre_sp(123456,1,12,0); // numpre 123456 - parcela 1 - total de parcelas 12 - digito 0
//#99#//Retorno será : 001234560010120
//#99#//
//#99#//Para formatar os números o sistema utiliza a função |db_formatar|
  $retorno = db_formatar($qn,'s',"0",8,"e");
  if($qnp!="x" ){
   // $retorno .= ".000";
    $retorno .= db_formatar($qnp,'s',"0",3,"e");
  }
  if($qnt!="x"){
    $retorno .= db_formatar($qnt,'s',"0",3,"e");
  }
  if($qnd!="x"){
    $retorno .= db_formatar($qnd,'s',"0",1,"e");
  }
  return $retorno;
}


function db_getsession($str) {
  global $DB_INSTITUICAO;
  switch($str) {
    case "DB_instit":
      return @$DB_INSTITUICAO;
    case "DB_anousu":
      return date("Y");
    case "DB_datausu":
      return time();
    case "DB_id_usuario":
    	  if(trim($_SESSION[$str]) == '' || trim($_SESSION[$str]) == 0){
			       	$sqluser  = "select id_usuario ";
						  $sqluser .= "  from db_usuarios ";
						  $sqluser .= " where login = 'dbpref' ";

						  $resultuser = db_query($sqluser);
						  if (pg_num_rows($resultuser)>0) {
						    $usuario = pg_result($resultuser, 0, 0);
						  } else {
						    $usuario = 1;
						  }
						  db_putsession("DB_id_usuario", $usuario);
    	  }else{
    	  	return $_SESSION[$str];
    	  }
    default:
      return @$_SESSION[$str];
  }
}


function db_logon($index=false,$w13_liberaatucgm="f",$w13_liberaescritorios="1",$w13_liberaimobiliaria="f"){

$usuario = db_getsession("DB_login");
$cgm     = @$_SESSION["CGM"];
$hora    = db_getsession("hora");

if($usuario != ""){
  if (isset($cgm) and trim($cgm)<>'') {
    $sql1="select nome,d.id_usuario as id_usuario,u.cgmlogin as cgmlogin
                       from db_usuarios d
                         inner join db_usuacgm u on u.id_usuario = d.id_usuario
                       where usuext     = 1
                         and u.cgmlogin = ".@$cgm;
    //die($sql1);
    $result = db_query($sql1);

    $nomeusuario = pg_result($result,0,'nome');
    $id_usuario  = pg_result($result,0,'id_usuario');
    $cgmlogin    = pg_result($result,0,'cgmlogin');
    $cgmlogin_teste    = pg_result($result,0,'cgmlogin');
  } else {
    $nomeusuario    = null;
    $id_usuario     = null;
    $cgmlogin       = null;
    $cgmlogin_teste = null;
  }
 // $h = "50";
//  $style = "style=\"border: 3px outset white\"";

  if(strlen($nomeusuario) > 10){
    $pos = strpos($nomeusuario," ");
    $usu = substr($nomeusuario,0,$pos);
    if(strlen($usu) <=5){
      $usu1 = trim(strstr($nomeusuario," "));
      $pos = strpos($usu1," ");
      $usu1 = substr($usu1,0,$pos);
      $usuario = $usu." ".$usu1;
    }else{
      $usuario = $usu;
    }
  }
  $img = "<img src=\"imagens/menu.gif\" border=\"0\">";

  $user = "<a href=\"index.php?".base64_encode("again=1")."\"><b>$img Logout &nbsp;&nbsp;&nbsp; </b></a>";
  $user .=  "<a href=\"trocasenha.php?".base64_encode("id_usuario=".@$id_usuario)."\" target=\"CentroPref\"><b>$img Configurações</b></a><br>";
  if($w13_liberaatucgm=="t"){
   //$user .= "<a href=\"atualizaendereco.php?".base64_encode("id_usuario=".@$cgmlogin_teste."&cgmlogin=".$cgmlogin_teste)."\" target=\"CentroPref\"><b>$img Atualizar Dados</b></a><br>";
  }

  $cgmlogin = (!isset($cgmlogin) or trim($cgmlogin)=='')?'NULL':$cgmlogin;

  if($w13_liberaescritorios=="2"){
   ///verifica se é escritório para cadastrar seus clientes
   include("classes/db_cadescrito_classe.php");
   $clcadescrito = new cl_cadescrito;
   $result  = $clcadescrito->sql_record($clcadescrito->sql_query("","*","","q86_numcgm = $cgmlogin")); // select
   $escrito = $clcadescrito->numrows;
	   if($escrito > 0){
	   	$sGetVars = "?id=".base64_encode(@$id_usuario)
	   	             ."&cgm=".base64_encode($cgmlogin)
	   	             ."&escrito=true&nome=".base64_encode($nomeusuario);
	    $user .= "<a href=\"informe_clientes.php".$sGetVars."\" target=\"CentroPref\">
	               <b>$img Informar Clientes</b></a><br>";
	   }
   }

   if($w13_liberaescritorios=="3"){
   ///verifica se é escritório para cadastrar seus clientes
   include("classes/db_cadescrito_classe.php");
   $clcadescrito = new cl_cadescrito;
   $result  = $clcadescrito->sql_record($clcadescrito->sql_query("","*","","q86_numcgm = $cgmlogin")); // select
   $escrito = $clcadescrito->numrows;
      if($escrito > 0){
        $sGetVars = "?id=".base64_encode(@$id_usuario)
                   ."&cgm=".base64_encode($cgmlogin)
                   ."&escrito=true&nome=".base64_encode($nomeusuario);
        $user .= "<a href=\"informe_clientes.php".$sGetVars."\" target=\"CentroPref\">
                  <b>$img Informar Clientes</b></a><br>";
	   }
   }

   if($w13_liberaescritorios=="4"){
   ///verifica se é escritório para cadastrar seus clientes
   include("classes/db_cadescrito_classe.php");
   $clcadescrito = new cl_cadescrito;
   $result  = $clcadescrito->sql_record($clcadescrito->sql_query("","*","","q86_numcgm = $cgmlogin")); // select
   $escrito = $clcadescrito->numrows;
      if($escrito > 0){
        $sGetVars = "?id=".base64_encode(@$id_usuario)
                   ."&cgm=".base64_encode($cgmlogin)
                   ."&escrito=true&nome=".base64_encode($nomeusuario);
        $user .= "<a href=\"informe_clientes.php".$sGetVars."\" target=\"CentroPref\">
                  <b>$img Informar Clientes</b></a><br>";
	   }
   }

   if($w13_liberaescritorios=="5"){
   ///verifica se é escritório para cadastrar seus clientes
   include("classes/db_cadescrito_classe.php");
   $clcadescrito = new cl_cadescrito;
   $result  = $clcadescrito->sql_record($clcadescrito->sql_query("","*","","q86_numcgm = $cgmlogin")); // select
   $escrito = $clcadescrito->numrows;
      if($escrito > 0){
        $sGetVars = "?id=".base64_encode(@$id_usuario)
                   ."&cgm=".base64_encode($cgmlogin)
                   ."&escrito=true&nome=".base64_encode($nomeusuario);
        $user .= "<a href=\"informe_clientes.php".$sGetVars."\" target=\"CentroPref\">
                  <b>$img Informar Clientes</b></a><br>";
	   }
  }

echo "<table ><tr><td>$user</td></tr></table> " ;

}else{
  $h = "0";
  $style = "";
  $cor = "";
}
}



function db_montamenus($index=false,$w13_liberaatucgm="f",$w13_liberaescritorios="1",$w13_liberaimobiliaria="f"){
$sql = "SELECT distinct m_arquivo,m_descricao
        FROM db_menupref
             left outer join db_menuacesso on m_codigo = db06_m_codigo
        WHERE  ";
$sql .= db_getsession("DB_login")!=""?"(m_ativo = '1')":"(m_publico = 't' and  m_ativo = '1')";
if($index==false){
  $sql .= (db_getsession("DB_acesso")!=""?" or (m_ativo = '1' and (db06_idtipo = 1 or db06_idtipo = ".db_getsession("DB_acesso")."))":"");
}
$sql .= "  ORDER BY m_descricao
        ";
$result_dtw = db_query($sql);

$numrows_dtw = pg_numrows($result_dtw);
$usuario = db_getsession("DB_login");
$hora = db_getsession("hora");
if($usuario != ""){
  $result = db_query("select nome, d.id_usuario as id_usuario, u.cgmlogin as cgmlogin
                        from db_usuarios d
                             inner join db_usuacgm u on u.id_usuario = d.id_usuario
                       where usuext     = 1
                         and u.cgmlogin = ".@$usuario);

  $nomeusuario    = pg_result($result,0,'nome');
  $id_usuario     = pg_result($result,0,'id_usuario');
  $cgmlogin       = pg_result($result,0,'cgmlogin');
  $cgmlogin_teste = pg_result($result,0,'cgmlogin');
  $h = "50";
  $style = "style=\"border: 3px outset white\"";
  if(strlen($nomeusuario) > 10){
    $pos = strpos($nomeusuario," ");
    $usu = substr($nomeusuario,0,$pos);
    if(strlen($usu) <=5){
      $usu1 = trim(strstr($nomeusuario," "));
      $pos  = strpos($usu1," ");
      $usu1 = substr($usu1,0,$pos);
      $usuario = $usu." ".$usu1;
    }else{
      $usuario = $usu;
    }
  }
  $img = "<img src=\"imagens/menu.gif\" border=\"0\">";
  $user = "<span class=\"texto\">";
  $user .= "<a href=\"index.php?".base64_encode("again=1")."\"><b>$img Logout &nbsp;&nbsp;&nbsp; </b></a>";
  $user .=  "<a href=\"trocasenha.php?".base64_encode("id_usuario=".@$id_usuario)."\" target=\"CentroPref\"><b>$img Configurações</b></a><br>";

}

$totalmenu = (100/($numrows_dtw + 1));
$nummenu =$numrows_dtw +1;

echo "

<table width=\"100%\" border =\"0\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
		<td width=\"$totalmenu%\"  id=\"coluna\" align=\"center\" height=\"20\" class=\"bordas\" onMouseOut=\"js_restaurafundo(this,'".$GLOBALS['w01_corfundomenu']."')\" onMouseOver=\"js_trocafundo(this,'".$GLOBALS['w01_corfundomenuativo']."')\">
		<a class=\"linksmenu\" href=\"centro_pref.php\" target=\"CentroPref\">
		  Página Inicial

		</td>
	";
for($i = 0;$i < $numrows_dtw;$i++) {
  $arquivo = pg_result($result_dtw,$i,"m_arquivo");//m_arquivo é o arquivo php q o menu chama exarquivo = digitadae.php
  $nome = substr($arquivo,0,strlen($arquivo) - 4);// substr(digitadae.php,0,"conta qts letras tem em digitadae.php - 4")
                                                  //retorna digitadae... então serve para tirar o .php
  $descricao = pg_result($result_dtw,$i,"m_descricao");// pega a descriçao "DAI 2004 "

echo "<td width=\"$totalmenu%\"  id=\"coluna$i\" align=\"center\" height=\"20\" class=\"bordas\" onMouseOut=\"js_restaurafundo(this,'".$GLOBALS['w01_corfundomenu']."')\"onMouseOver=\"js_trocafundo(this,'".$GLOBALS['w01_corfundomenuativo']."')\">
				<a class=\"linksmenu\" href=\"$arquivo?".base64_encode("id_usuario=".@$cgmlogin_teste."&nomeusuario=".@$nomeusuario)."\" target=\"CentroPref\">
				$descricao
				</a>
			</td>
";
/*
  echo "> <a class=\"linksmenu\" href=\"$arquivo\" target=\"CentroPref\">
         $descricao
         </a><br>";
  */

//echo "  <option value='$i'> $descricao </a></option>";

/*  echo"
       <table width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
       <tr>
         <td id=\"coluna$i\" align=\"center\" height=\"20\" class=\"bordas\" onMouseOut=\"js_restaurafundo(this,'".$GLOBALS['w01_corfundomenu']."')\"onMouseOver=\"js_trocafundo(this,'".$GLOBALS['w01_corfundomenuativo']."')\">
	 <a class=\"linksmenu\" href=\"$arquivo?".base64_encode("id_usuario=".@$cgmlogin_teste."&nomeusuario=".@$nomeusuario)."\" target=\"CentroPref\">
	 $descricao
	 </a>
      </td>
       </tr>
      </table>";
  */
  }
echo "</tr>
</table>";

//echo"  </select>";

/*echo "</span><table bgcolor=\"$cor\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" $style>
<tr><br>
				<td nowrap width=\"100%\">".@$user."
				</td>
			</tr>
		</table>";
					*/
}


function db_mes($xmes) {
$Mes = "";

   if ( $xmes == '01' ) {
        $Mes = 'janeiro';
   }else if ( $xmes == '02') {
        $Mes = 'fevereiro';
   }else if ( $xmes == '03') {
        $Mes = 'março';
   }else if ( $xmes == '04') {
        $Mes = 'abril';
   }else if ( $xmes == '05') {
        $Mes = 'maio';
   }else if ( $xmes == '06') {
        $Mes = 'junho';
   }else if ( $xmes == '07') {
        $Mes = 'julho';
   }else if ( $xmes == '08') {
        $Mes = 'agosto';
   }else if ( $xmes == '09') {
        $Mes = 'setembro';
   }else if ( $xmes == '10') {
        $Mes = 'outubro';
   }else if ( $xmes == '11') {
        $Mes = 'novembro';
   }else if ( $xmes == '12') {
        $Mes = 'dezembro';
  }
  return $Mes;
}

//////////////////////////////////////
function db_data($nome,$dia="",$mes="",$ano="") {
    ?>
    <input name="<?=$nome."_dia"?>" type="text" id="<?=$nome."_dia"?>" value="<?=$dia?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
    <input name="<?=$nome."_mes"?>" type="text" id="<?=$nome."_mes"?>" value="<?=$mes?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
    <input name="<?=$nome."_ano"?>" type="text" id="<?=$nome."_ano"?>" value="<?=$ano?>" size="4" maxlength="4" autocomplete="off">
    <?
}
/*************************************/






function db_verifica_ip(){

   global $SERVER, $HTTP_SERVER_VARS;
   if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ){
     $db_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
   }else{
     $db_ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   }

   include("db_acessa.php");

   for($i=1;$i-1<sizeof($db_acessa);$i++){
     if($db_acessa[$i][1]== $db_ip){
       if($db_acessa[$i][2]==false){
         db_redireciona('index.php?erroscripts=Você não tem permissão de acesso.');
       }
     }
   }
   $pode_acessar = "0";
   for($i=1;$i-1<sizeof($db_acessa);$i++){
     $aster = strpos("#".$db_acessa[$i][1],"*");
     if($aster!=0){
       $quantos = substr($db_acessa[$i][1],0,$aster-1);
       if(substr($db_acessa[$i][1],0,strlen($quantos)) == substr($db_ip,0,strlen($quantos))){
         if($db_acessa[$i][2] == false){
           db_redireciona('index.php?erroscripts=Sistema em Manutenção. Volte mais tarde.');
         }
         $pode_acessar = "1";
         break;
       }
     }else{
       if($db_acessa[$i][1]== $db_ip){
         $pode_acessar = "1";
         break;
       }
     }
   }
   return $pode_acessar;
}

function digito_bco($num){
  $y = 3;
  $tot = 0;
  for($n=0;$n<10;$n++){
  //FOR N = 1 TO 10
    $tot += ( substr($num,$n,1) * $y );
    //TOT += VAL(SUBSTR(D08_CEDENT+NUM,N,1)) * Y
    $y -= 1;
        //Y -= 1
    if($y < 2){
      $y = 9;
    }
  }
  $u = ($tot%11);
  //U = (INT((TOT/11))*11-TOT)*-1
  $u = 11 - $u;
  //U = 11 - U
  if($u > 9 ){
    $u = 0;
  }
  return $u;
}

// classe para gerar sql e recordset

//////////////////
function digito_mod10($d_digitavel){
  $a = 2;
  $tot_x = 0;
  for($x=strlen($d_digitavel);$x>0;$x--){
    //FOR I=LEN(D_DIGITAVEL) TO 1 STEP -1
    $total_x = ((int)substr($d_digitavel,$x-1,1)*$a);
    //TOTAL_X = (VAL(SUBSTR(D_DIGITAVEL,I,1))*A)
    if($total_x>9){
    //IF TOTAL_X > 9
       $total_x = $total_x - 9;
       //TOTAL_X = TOTAL_X - 9
    }
    $tot_x += $total_x;
    //TOT_X += TOTAL_X
    if($a==2){
       $a = 1;
    }else{
       $a = 2;
    }
  }
  //*
  //** digito do 2o. campo => resto
  $resto = ($tot_x%10);
  //RESTO = TOT_X - (INT(TOT_X / 10) * 10)
  if($resto>0){
     $resto = 10 - $resto;
         //RESTO = 10 - RESTO
  }
  return $resto;
}


function calc_dac ( $valor_dac,$dtvenc,$numbco ){
  $banco="104";
  $moeda="9";
  //VALORAUX = VALOR_DAC * -1
  $valor= db_formatar($valor_dac*100,'s','0',10);
  //*
  if($dtvenc < date('Y-m-d',mktime( 0,0,0,7,3,2000))){
     $fator = "1000";
  }else{
     $fator = db_difdate($dtvenc,'1997-10-07');
  }
  $numbco = db_formatar($numbco,'s',10,'0'); // tamanho 10
  $campo = $banco.$moeda.$fator.$valor.$numbco."046100600000037";
  $y = 4;
  $tot_x = 0;
  for($i=0;$i<43;$i++){
    //FOR I=1 TO 43
        $tot_x += ( substr($campo,$i,1)*$y );
    // TOT_X += (VAL(SUBSTR(CAMPO,I,1))*Y)
        $y -= 1;
    // Y --
        if($y<2){
          $y = 9;
        }
    // IF Y < 2
    //    Y = 9
     //ENDIF
  }
  //NEXT
  $u = ($tot_x%11);
  //U = (INT((TOT_X/11))*11-TOT_X)*-1
  $u = 11 - $u;
  //U = 11 - U
  if($u<2 || $u > 9){
    $u = 1;
  }
  //IF U < 2 OR U > 9
  // U = 1
  //ENDIF
  //*
  $dac = $u;
  //DAC = STR(U,1)
  //*
  //** linha digitavel
  //*
  $campo_livre = $numbco."046100600000037";
  //CAMPO_LIVRE = SUBSTR(NUMBCO,1,10)+"046100600000037"
  // echo "L1-";
  global $digitavel_1;
  $digitavel_1 = $banco.$moeda.substr($campo_livre,0,5);
  // echo $digitavel_1."\n";
  //DIGITAVEL_1 = BCO+MOEDA+SUBSTR(CAMPO_LIVRE,1,5)
  global $digitavel_2;
  $digitavel_2 = substr($campo_livre,5,10);
  //echo "\n";
  //DIGITAVEL_2 = SUBSTR(CAMPO_LIVRE,6,10)
  global $digitavel_3;
  $digitavel_3 = substr($campo_livre,15,10);
  //echo "\n";
  //DIGITAVEL_3 = SUBSTR(CAMPO_LIVRE,16,10)
  global $digitavel_4;
  $digitavel_4 = $dac;
  //echo "\n";
  //DIGITAVEL_4 = DAC
  global $digitavel_5;
  if($valor!=0){
     $digitavel_5 = $valor;
 //echo "\n";
     //DIGITAVEL_5 = TRANSFORM(VAL(VALOR),"99999999999999")
  }else{
     $digitavel_5 = "000";
     //DIGITAVEL_5 = "000"
  }
  $digitavel_1 .= digito_mod10($digitavel_1);
  //DIGITAVEL_1 += DIGITO_MOD10(DIGITAVEL_1)
  $digitavel_2 .= digito_mod10($digitavel_2);
  //DIGITAVEL_2 += DIGITO_MOD10(DIGITAVEL_2)
  $digitavel_3 .= digito_mod10($digitavel_3);
  //DIGITAVEL_3 += DIGITO_MOD10(DIGITAVEL_3)
  global $codigo_barras;
  $codigo_barras = $banco.$moeda.$dac.$valor.$numbco."046100600000037";
  //$cod_b = BCO+MOEDA+DAC+VALOR+SUBSTR(NUMBCO,1,10)+"046100600000037"
}

class cl_query {
    var $numrows = 0;
    var $numfields = 0;
    var $sql = null;
    var $result = null;
    var $resultado = null;
    function sql_query($arquivo="cgm",$campos="*",$ordem="",$where="",$group=""){
      $sql = " select $campos ";
      if($arquivo!="")
        $sql .= " from $arquivo ";
      if($where!="")
         $sql = $sql." where $where";
      if($group!="")
         $sql = $sql." group by $group";
      if($ordem!="")
         $sql = $sql." order by $ordem";
      $this->sql = $sql;
    }
    function sql_record($sql){
      $result = db_query($sql) or die($sql);
      $this->result = $result;
      if($result!=false){
         $this->numrows = pg_numrows($result);
         $this->numfields = pg_numfields($result);
      }else{
         $this->numrows = 0;
         $this->numfields = 0;
      }
    }
    function sql_result($recordset="",$linha="0",$coluna="0"){
        $resultado = pg_result($recordset,$linha,$coluna);
        $this->resultado = $resultado;
    }
    function sql_banco($sql){
      $result = db_query($sql);
      if($result!=false){
        return true;
      }else{
        return false;
      }
    }
    function sql_insert($tabela="", $dados=""){
      $sqlin = "insert into $tabela values ( $dados )";
      db_query($sqlin) or die($sqlin);
    }
    function sql_update($tabela="", $dados="", $where="", $mostrar=false){
      $sqlin= "update $tabela set $dados where $where";
			if ($mostrar == true) {
				die($sqlin);
			}
      db_query($sqlin) or die($sqlin);
    }
}
function db_difdate($dat1,$dat2){
 /* Dat1 e Dat2 no formato "YYYY-MM-DD" */
  $tmp_dat1 = mktime(0,0,0, substr($dat1,5,2),substr($dat1,8,2),substr($dat1,0,4));
  $tmp_dat2 = mktime(0,0,0, substr($dat2,5,2),substr($dat2,8,2),substr($dat2,0,4));
  $yeardiff = date('Y',$tmp_dat1)-date('Y',$tmp_dat2);
  $diff = date('z',$tmp_dat1)-date('z',$tmp_dat2) + floor($yeardiff /4)*1461;
  for ($yeardiff = $yeardiff % 4; $yeardiff>0; $yeardiff--){
       $diff += 365 + date('L',mktime(0,0,0,1,1,intval(substr((($tmp_dat1>$tmp_dat2) ? $dat1 : $dat2),0,4))-$yeardiff+1));
  }
  return $diff;
}
//redireciona para uma url
function redireciona($url="0") {
  if($url == "0")
    $url = $GLOBALS["PHP_SELF"];
  echo "<script>location.href='$url'</script>\n";
  exit;
}
function printfieldsmemory($recordset,$indice){
 $fm_numfields = pg_numfields($recordset);
  for ($i=0;$i < $fm_numfields;$i ++){
    $matriz[$i] = pg_fieldname($recordset,$i);
    $$matriz[$i] = trim(pg_result($recordset,$indice,$matriz[$i]));
    echo $matriz[$i]." - ".$$matriz[$i]."<br>";
  }
}

function db_date($mes=0,$dia=0,$ano=0,$operacao="-",$quantidade=1,$formato="dma",$tipo="/") {
/*Função para formatação da data*/
if( $ano != 0 ) {
 $diminui = 0;
  if($operacao == '-')
    $dia = $dia - $quantidade;
  if($dia < 0 )
    $diminui = $dia;
  else
    $dia = $dia + $quantidade;
  if($dia > 31 )
    $diminui = $dia - 31;
  while(!checkdate($mes,$dia,$ano)) {
    if( $operacao == "-" ) {
      $dia = $dia - 1;
      if ($dia < 1 ) {
        $dia = 31 + $diminui;
        $mes = $mes - 1;
        if($mes < 1 ) {
          $mes = 12;
          $ano = $ano - 1;
        }
      }
    } else  {
      $dia = $dia + 1;
      if($dia > 31 ) {
        $dia = 1 + $diminui;
        $mes = $mes + 1;
        if($mes > 12 ) {
          $mes = 1;
          $ano = $ano + 1;
        }
      }
    }
  }
} else {
  if(empty($mes)) {
    return "&nbsp";
  } else {
    $tipo = $dia;
    $ano = substr($mes,0,4);
    $dia = substr($mes,8,2);
    $mes = substr($mes,5,2);
  }
}
if( $dia < 10 )
  $dia = "$dia";
if( $mes < 10 )
  $mes = "$mes";
if($formato == "dma")
  $retorno = "$dia$tipo$mes$tipo$ano";
else if($formato == "dam")
  $retorno = "$dia$tipo$ano$tipo$mes";
else if($formato == "mda")
  $retorno = "$mes$tipo$dia$tipo$ano";
else if ($formato == "mad")
  $retorno = "$mes$tipo$dia$tipo$ano";
else if ($formato == "adm")
  $retorno = "$ano$tipo$dia$tipo$mes";
else if ($formato == "amd")
  $retorno = "$ano$tipo$mes$tipo$dia";
return $retorno;
}
function mostra_msgbox() {
   global $erro_mensagem;
  if($erro_mensagem!=""){
    echo "<script>alert('".$erro_mensagem."');</script>\n";
  }
}
function db_msgbox2($str) {
    echo "<script>alert('".$str."');</script>\n";
}
function postmemory($vetor,$verNomeIndices = 0) {
  if(!is_array($vetor)) {
    echo "Erro na função postmemory: Parametro não é um array válido.<Br>\n";
        return false;
  }
  $tam_vetor = sizeof($vetor);
  reset($vetor);
  if($verNomeIndices > 0)
    echo "<br><br>\n";
  for($i = 0;$i < $tam_vetor;$i++) {
    $matriz[$i] = key($vetor);
        global $$matriz[$i];
        $$matriz[$i] = $vetor[$matriz[$i]];
        if($verNomeIndices == 1)
          echo "$".$matriz[$i]."<br>\n";
        else if($verNomeIndices == 2)
          echo "$".$matriz[$i]." = '".$$matriz[$i]."';<br>\n";
    next($vetor);
  }
  if($verNomeIndices > 0)
        echo "<br><br>\n";
}

function msgbox($msg) {
  echo "<script>alert('$msg')</script>\n";
}

function mens_OnHelp() {
  global $nome_help;
  ?>
  onhelp="MM_showHideLayers('<?=$nome_help?>','',(document.getElementById('<?=$nome_help?>').style.visibility=='visible'?'hide':'show'));<?=(isset($DB_SELLER)?'js_db_iframe_helph()':'')?>;return false"
  <?
}
function mens_div() {
  global $larg_div;
  global $alt_div;
  global $x_div;
  global $y_div;
  global $cor_div;
  global $nome_help;
  global $texto_help;
  global $HTTP_SERVER_VARS;
  global $DB_SELLER;
//onMouseDown="js_MD_Div()" onMouseUp="js_MU_Div()" onMouseOut="js_MU_Div()" onMouseMove="js_moverDiv()";
?>
<div id="<?=$nome_help?>" style="border: 3px outset #6C6C6C;position:absolute; left:<?=$x_div?>px; top:<?=$y_div?>px; height:160px; z-index:1; background-color: <?=$cor_div?>; layer-background-color: #00FFFF; visibility: hidden;">
<div id="div2" align="right" style="background-color: blue;">
  <table height="30" background="imagens/corcabecalho.jpg" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 3px outset #cccccc; background-position-y: 0px; ">
    <tr>
      <td width="100%" align="left" valign="top"  nowrap>
        <table border="0" style="background-repeat: no-repeat; background-position-y: 0px"  background="imagens/botaoajuda.jpg"  cellpadding="0" cellspacing="0">
          <tr>
            <td width="150"><img border="0" src="imagens/botaoajuda.jpg"></img>
            </td>
          </tr>
        </table>
        <?
        if(isset($DB_SELLER)){
          ?>
      </td>
      <td width="150" style="cursor: hand" valign="top"  nowrap onClick="js_db_iframe_help()" >
        <table border="0" cellpadding="0"  cellspacing="0">
          <tr>
            <td><img border="0" onMouseOut="this.src = 'imagens/botaoconfig.jpg'" onMouseOver="this.src = 'imagens/botaoconfigon.jpg'" width="150" src="imagens/botaoconfig.jpg"></img>
            </td>
          </tr>
        </table>
          <script>
          function js_db_iframe_help(){
            MM_showHideLayers('<?=$nome_help?>','','hide');
            document.getElementById('div3').style.visibility='visible';
            document.getElementById('div3').style.height='100%';
            document.getElementById('div3').style.width='100%';
          }
          function js_db_iframe_helph(){
            document.getElementById('div3').style.visibility='hidden';
          }
          </script>
          <?
        }
        ?>
        </td >
        <td width="80" align="right"  nowrap>
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td style="cursor: hand" onMouseOver="this.hideFocus=true" align="right" valign="center" ><img src="imagens/jan_mini_off.gif"></td><td><img src="imagens/jan_max_off.gif"></img></td><td><img id="figura" src="imagens/jan_fechar_on.gif" onClick="MM_showHideLayers('<?=$nome_help?>','','hide');<?=(isset($DB_SELLER)?'js_db_iframe_helph()':'')?>;return false"  onMouseDown="this.src = 'imagens/jan_fechar_off.gif'" onMouseOut="this.src = 'imagens/jan_fechar_on.gif'"></img></td>
            </tr>
          </table>
        </td>
    <tr>
  </table>
</div>
<iframe name="texto_help" src="db_frmtexto_help.php?nomepagina=<?=$nome_help?>" frameborder="0" align="top" width="100%" height="127"></iframe>
</div>
<?
if(isset($DB_SELLER)){
  ?>
  <div id="div3" style="background-color:blue;border: thin inset #CCCCCC; position:absolute;  visibility: hidden" >
     <iframe style="width:100% ; height:100%" name="helpiframe" src="pre4_mensagens001.php?codhelp=<?=$nome_help?>"></iframe>
  </div>
  <?
}
}

function mens_help($mens="") {
  global $larg_div;
  global $alt_div;
  global $x_div;
  global $y_div;
  global $cor_div;
  global $nome_help;
  global $texto_help;
  global $HTTP_SERVER_VARS;
  global $conn;

  if($mens==""){

    $mens = basename($HTTP_SERVER_VARS['PHP_SELF']);
    $uo = strrpos($mens,"\.");

    if($uo>0)
       $mens = substr($mens,0,$uo-1);
  }

  $instit = db_getsession("DB_instit");
  $result = db_query($conn,"select * from db_confmensagem where cod = '{$mens}_help' and instit = {$instit}");
  if (pg_numrows($result) == 0 ) {
  	$result = db_query($conn,"insert into db_confmensagem (cod,mens,alinhamento,instit) values ('{$mens}_help','Help da Página','600&80&250&150&#FFFFFF&',{$instit})");
		$result = db_query($conn,"select * from db_confmensagem where cod = '{$mens}_help' and instit = {$instit}");
  }

  $nome_help  = pg_result($result,0,"cod");
  $texto_help = pg_result($result,0,"mens");
  $param_help = split("&",pg_result($result,0,"alinhamento"));
  $larg_div   = $param_help[0];

  $alt_div  = $param_help[1];
  $x_div    = $param_help[2];
  $y_div    = $param_help[3];
  $cor_div  = $param_help[4];
}

/************************************************************/
class cl_abre_arquivo {
  var $nomearq = null;
  var $arquivo = null;
  function cl_abre_arquivo($nomearq=""){
    global $HTTP_SERVER_VARS;
    $Dirroot = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
    if($nomearq==""){
      $nomearq = tempnam("tmp","");
    }
    $this->arquivo = fopen($Dirroot.'/'.$nomearq,"w");
        $this->nomearq = $Dirroot.'/'.$nomearq;
    if($this->arquivo==false){
      return false;
    }else{
      return true;
    }
  }
}

class janela {
  var $nome;
  var $arquivo;
  var $iniciarVisivel = true;
  var $largura = "400";
  var $altura = "400";
  var $posX = "10";
  var $posY = "10";
  var $scrollbar = "auto"; // pode ser tb, 0 ou 1
  var $corFundoTitulo = "#2C7AFE";
  var $corTitulo = "white";
  var $fonteTitulo = "Arial, Helvetica, sans-serif";
  var $tamTitulo = "11";
  var $titulo = "DBSeller Informática Ltda";
  var $janBotoes = "101";

 function janela($nome,$arquivo) {
    $this->nome = $nome;
        $this->arquivo = $arquivo;
  }
  function mostrar() {
    if($this->iniciarVisivel == true)
          $this->iniciarVisivel = "visible";
        else
          $this->iniciarVisivel = "hidden";
    ?>
        <div id="Jan<? echo $this->nome ?>" style=" background-color: #c0c0c0;border: 0px outset #666666;position:absolute; left:<? echo $this->posX ?>px; top:<? echo $this->posY ?>px; width:<? echo $this->largura ?>px; height:<? echo $this->altura ?>px; z-index:1; visibility: <? echo $this->iniciarVisivel ?>;"><table width="100%" height="100%" style="border-color: #f0f0f0 #606060 #404040 #d0d0d0;border-style: solid;  border-width: 2px;"  border="0" cellspacing="0" cellpadding="2"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr id="CF<? echo $this->nome ?>" style="white-space: nowrap;background-color:<? echo $this->corFundoTitulo ?>"><td nowrap onmousedown="js_engage(document.getElementById('Jan<? echo $this->nome ?>'),event)" onmouseup="js_release(document.getElementById('Jan<? echo $this->nome ?>'),event)" onmousemove="js_dragIt(document.getElementById('Jan<? echo $this->nome ?>'),event)" onmouseout="js_release(document.getElementById('Jan<? echo $this->nome ?>'),event)" width="80%" style="cursor:hand;font-weight: bold;color: <? echo $this->corTitulo ?>;font-family: <? echo $this->fonteTitulo ?>;font-size: <? echo $this->tamTitulo ?>px">&nbsp;<? echo $this->titulo ?></td><td width="20%" align="right" valign="middle" nowrap><?$kp=0x4;$m = $kp & $this->janBotoes;$kp >>= 1;?><img <? echo $m?'style="cursor:hand"':"" ?> src=<? echo $m?"imagens/jan_mini_on.gif":"imagens/jan_mini_off.gif" ?> title="Minimizar" border="0" onClick="js_MinimizarJan(this,'<? echo $this->nome ?>')"><?$m = $kp & $this->janBotoes;$kp >>= 1;?><img <? echo $m?'style="cursor:hand"':"" ?> src=<? echo $m?"imagens/jan_max_on.gif":"imagens/jan_max_off.gif" ?> title="Maximizar" border="0" onClick="js_MaximizarJan(this,'<? echo $this->nome ?>')"><?$m = $kp & $this->janBotoes;$kp >>= 1;?><img <? echo $m?'style="cursor:hand"':"" ?> src=<? echo $m?"imagens/jan_fechar_on.gif":"imagens/jan_fechar_off.gif" ?> title="Fechar" border="0" onClick="js_FecharJan(this,'<? echo $this->nome ?>')"></td></tr></table></td></tr><tr><td width="100%" height="100%"><iframe frameborder="1" style="border-color:#C0C0F0" height="100%" width="100%" id="IF<? echo $this->nome ?>" name="IF<? echo $this->nome ?>" scrolling="<? echo $this->scrollbar ?>" src="<? echo $this->arquivo ?>"></iframe></td></tr></table></div><script><? echo $this->nome ?> = new janela(document.getElementById('Jan<? echo $this->nome ?>'),document.getElementById('CF<? echo $this->nome ?>'),IF<? echo $this->nome ?>);</script>
        <?
  }
}


//////////// CLASSE ROTULO  ///////////

/// ESTA CLASSE CRIA AS VARIAVEIS DE LABEL E TITLE DAS PÁGINAS ///
class rotulovelho {
  var $tabela;
  function rotulo($tabela) {
    $this->tabela = $tabela;
  }
  function label($nome = "") {
    $result = db_query("select c.descricao,c.rotulo,c.nomecam

                           from db_syscampo c

                                           inner join db_sysarqcamp s

                                           on s.codcam = c.codcam

                                           inner join db_sysarquivo a

                                           on a.codarq = s.codarq

                                           where a.nomearq = '".$this->tabela."'

                                           ".($nome != ""?"and trim(c.nomecam) = trim('$nome')":""));

        $numrows = pg_numrows($result);

        for($i = 0;$i < $numrows;$i++) {

          $variavel = trim("L".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = "<strong>".pg_result($result,$i,"rotulo").":</strong>";

            $variavel = trim("T".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"descricao")."\n\nCampo:".trim("T".pg_result($result,$i,"nomecam"));

        }

  }

}

class rotulo {
  var $tabela;
  function rotulo($tabela) {
    $this->tabela = $tabela;
  }
  function label($nome = "") {
    $result = db_query("select c.descricao,c.rotulo,c.nomecam,c.tamanho,c.nulo,c.maiusculo,c.autocompl,c.conteudo,c.aceitatipo
                           from db_syscampo c
                                           inner join db_sysarqcamp s
                                           on s.codcam = c.codcam
                                           inner join db_sysarquivo a
                                           on a.codarq = s.codarq
                                           where a.nomearq = '".$this->tabela."'
                                           ".($nome != ""?"and trim(c.nomecam) = trim('$nome')":""));
        $numrows = pg_numrows($result);
        for($i = 0;$i < $numrows;$i++) {
      /// variavel com o tipo de campo
            $variavel = trim("I".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = pg_result($result,$i,"aceitatipo");
      /// variavel para determinar o autocomplete
            $variavel = trim("A".pg_result($result,$i,"nomecam"));
          global $$variavel;
          if(pg_result($result,$i,"autocompl")=='f'){
            $$variavel = "off";
          }else{
            $$variavel = "on";
          }
      /// variavel para preenchimento obrigatorio
            $variavel = trim("U".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = pg_result($result,$i,"nulo");
      /// variavel para colocar maiusculo
            $variavel = trim("G".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = pg_result($result,$i,"maiusculo");
      /// variavel para colocar no erro do javascript de preenchimento de campo
            $variavel = trim("S".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = pg_result($result,$i,"rotulo");
      /// variavel para colocar como label de campo
          $variavel = trim("L".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = "<strong>".ucfirst(pg_result($result,$i,"rotulo")).":</strong>";
          /// vaariavel para colocat na tag title dos campos
            $variavel = trim("T".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = ucfirst(pg_result($result,$i,"descricao"))."\n\nCampo:".pg_result($result,$i,"nomecam");
          /// variavel para incluir o tamanhoda tag maxlength dos campos
            $variavel = trim("M".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = pg_result($result,$i,"tamanho");
          /// variavel para controle de campos nulos
            $variavel = trim("N".pg_result($result,$i,"nomecam"));
          global $$variavel;
          $$variavel = pg_result($result,$i,"nulo");
          if($$variavel=="t")
             $$variavel = "style=\"background-color:#E6E4F1\"";

          else

             $$variavel = "";

        }

  }

   function tlabel($nome = "") {

    $result = db_query("select c.nomearq,c.descricao,c.nomearq,c.rotulo

                           from db_sysarquivo c

                                           where trim(c.nomearq) = '".$this->tabela."'");

         $numrows = pg_numrows($result);

    if($numrows>0){

      $variavel = trim("L".pg_result($result,0,"nomearq"));

          global $$variavel;

          $$variavel = "<strong>".pg_result($result,0,"rotulo").":</strong>";

            $variavel = trim("T".pg_result($result,0,"nomearq"));

          global $$variavel;

          $$variavel = pg_result($result,0,"descricao");

        }

  }

}



class rotulocampo {

  function label($campo = "") {

    $result = db_query("select c.descricao,c.rotulo,c.nomecam,c.tamanho,c.nulo,c.maiusculo,c.autocompl,c.conteudo,c.aceitatipo

                           from db_syscampo c

                                           where trim(c.nomecam) = trim('$campo')");

        $numrows = pg_numrows($result);

        for($i = 0;$i < $numrows;$i++) {



      /// variavel com o tipo de campo

            $variavel = trim("I".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"aceitatipo");

      /// variavel para determinar o autocomplete

            $variavel = trim("A".pg_result($result,$i,"nomecam"));

          global $$variavel;

          if(pg_result($result,$i,"autocompl")=='f'){

            $$variavel = "off";

          }else{

            $$variavel = "on";

          }

      /// variavel para preenchimento obrigatorio

            $variavel = trim("U".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"nulo");

      /// variavel para colocar maiusculo

            $variavel = trim("G".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"maiusculo");

      /// variavel para colocar no erro do javascript de preenchimento de campo

            $variavel = trim("S".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"rotulo");

      /// variavel para colocar como label de campo

          $variavel = trim("L".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = "<strong>".ucfirst(pg_result($result,$i,"rotulo")).":</strong>";

          /// vaariavel para colocat na tag title dos campos

            $variavel = trim("T".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = ucfirst(pg_result($result,$i,"descricao"))."\n\nCampo:".pg_result($result,$i,"nomecam");

          /// variavel para incluir o tamanhoda tag maxlength dos campos

            $variavel = trim("M".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"tamanho");

          /// variavel para controle de campos nulos

            $variavel = trim("N".pg_result($result,$i,"nomecam"));

          global $$variavel;

          $$variavel = pg_result($result,$i,"nulo");

          if($$variavel=="t")

             $$variavel = "style=\"background-color:#E6E4F1\"";

          else

             $$variavel = "";

        }

  }

}

class rotulolov {

  var $titulo = null;

  var $title = null;

  var $tamanho = null;

  function label($nome = "") {

    if(substr($nome,0,3)=="dl_"){

           $this->titulo = substr($nome,3);

           $this->title  = substr($nome,3);

           $this->tamanho= 0;

        }else{

      $result = db_query("select c.descricao,c.rotulo,c.tamanho

                            from db_syscampo c

                                            where trim(c.nomecam) = trim('$nome')");

          $numrows = pg_numrows($result);

      if($numrows != 0){

         $this->titulo  = ucfirst(pg_result($result,0,"rotulo"));

         $this->title   = ucfirst(pg_result($result,0,"descricao"));

         $this->tamanho = pg_result($result,0,"tamanho");

          }else{

         $this->titulo  = "";

         $this->title   = "";

         $this->tamanho = "";

          }

        }

  }

}





//header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');



//Variavel com a URL absoluta, menos o arquivo

$DB_URL_ABS = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].substr($HTTP_SERVER_VARS['PHP_SELF'],0,strrpos($HTTP_SERVER_VARS['PHP_SELF'],"/")+1);

//Variavel com a URL absoluta da pagina que abriu a atual, menos o arquivo

if(isset($HTTP_SERVER_VARS["HTTP_REFERER"]))

  $DB_URL_REF = substr($HTTP_SERVER_VARS["HTTP_REFERER"],0,strrpos($HTTP_SERVER_VARS["HTTP_REFERER"],"/")+1);



//troca os caracteres especiais em tags html



//if(basename($HTTP_SERVER_VARS['PHP_SELF']) != "/~dbjoao/dbportal2/pre4_mensagens001.php") {

if(basename($HTTP_SERVER_VARS['PHP_SELF']) != "pre4_mensagens001.php") {

  $tam_vetor = sizeof($HTTP_POST_VARS);

  reset($HTTP_POST_VARS);

  for($i = 0;$i < $tam_vetor;$i++) {

    if(gettype($HTTP_POST_VARS[key($HTTP_POST_VARS)]) != "array")

      $HTTP_POST_VARS[key($HTTP_POST_VARS)] = ($HTTP_POST_VARS[key($HTTP_POST_VARS)]);

    next($HTTP_POST_VARS);

  }

  $tam_vetor = sizeof($HTTP_GET_VARS);

  reset($HTTP_GET_VARS);

  for($i = 0;$i < $tam_vetor;$i++) {

    if(gettype($HTTP_GET_VARS[key($HTTP_GET_VARS)]) != "array")

      $HTTP_GET_VARS[key($HTTP_GET_VARS)] = ($HTTP_GET_VARS[key($HTTP_GET_VARS)]);

    next($HTTP_GET_VARS);

  }

}



// Verifica se esta sendo passado algum comando SQL

function db_verfPostGet($post) {

  $tam_vetor = sizeof($post);

  reset($post);

  for($i = 0;$i < $tam_vetor;$i++) {

    if(key($post) != 'corpofuncao' && key($post) != 'eventotrigger')

          $dbarraypost = (gettype($post[key($post)])!="array"?$post[key($post)]:"");

      if(db_indexOf(strtoupper($dbarraypost),"INSERT") > 0 ||

             db_indexOf(strtoupper($dbarraypost),"UPDATE") > 0 ||

             db_indexOf(strtoupper($dbarraypost),"DELETE") > 0 ||

         db_indexOf(strtoupper($dbarraypost),"EXEC(")  > 0 ||

         db_indexOf(strtoupper($dbarraypost),"SYSTEM(")  > 0 ||

         db_indexOf(strtoupper($dbarraypost),"PASSTHRU(")  > 0) {

            echo "<script>alert('Voce está passando parametros inválidos e sera redirecionado.');location.href='http://localhost/dbportal/modulos.php'</script>\n";

        exit;

      }

    //$post[key($post)] = htmlspecialchars(gettype($post[key($post)])!="array"?$post[key($post)]:"");

    next($post);

  }

}

db_verfPostGet($HTTP_POST_VARS);

db_verfPostGet($HTTP_GET_VARS);





function db_criatabela($result) {

  $numrows = pg_numrows($result);
  $numcols = pg_numfields($result);

  ?> <br><br><table border="1" cellpadding="0" cellspacing="0"> <?

  echo "<tr bgcolor=\"#00CCFF\">\n";

  for($j = 0;$j < $numcols;$j++) {

    echo "<td>".pg_fieldname($result,$j)."</td>\n";

  }

  $cor = "#07F89D";

  echo "</tr>\n";

  for($i = 0;$i < $numrows;$i++) {

    echo "<tr bgcolor=\"".($cor = ($cor == "#07F89D"?"#51F50A":"#07F89D"))."\">\n";

        for($j = 0;$j < $numcols;$j++) {

          echo "<td nowrap>".pg_result($result,$i,$j)."</td>\n";

        }

        echo "</tr>\n";

  }

  ?> </table><br><br> <?

}







//retorna o tamanho do maior registro

function db_getMaxSizeField($recordset,$campo = 0) {

  $numrows = pg_numrows($recordset);

  $val = strlen(trim(pg_result($recordset,0,$campo)));

  for($i = 1;$i < $numrows;$i++) {

    $field = strlen(trim(pg_result($recordset,$i,$campo)));

    if($val < $field)

          $val = $field;

  }

  return (int)$val;

}

//Pega um vetor e cria variaveis globais pelo indice do vetor

//atualiza a classe dos arquivos

function db_postmemory($vetor,$verNomeIndices = 0) {

  if(!is_array($vetor)) {

    echo "Erro na função postmemory: Parametro não é um array válido.<Br>\n";

        return false;

  }

  $tam_vetor = sizeof($vetor);

  reset($vetor);

  if($verNomeIndices > 0)

    echo "<br><br>\n";

  for($i = 0;$i < $tam_vetor;$i++) {

    $matriz[$i] = key($vetor);

        global $$matriz[$i];

        $$matriz[$i] = $vetor[$matriz[$i]];

        if($verNomeIndices == 1)

          echo "$".$matriz[$i]."<br>\n";

        else if($verNomeIndices == 2)

          echo "$".$matriz[$i]." = '".$$matriz[$i]."';<br>\n";

    next($vetor);

  }

  if($verNomeIndices > 0)

        echo "<br><br>\n";

}

function db_numpre($qn,$qnp="",$qnt="",$qnd=""){

  $retorno = db_formatar($qn,'s',"0",8,"e");

  if($qnp!=""){

    $retorno .= ".000";

  }

  if($qnt!=""){

    $retorno .= ".".db_formatar($qnt,'s',"0",3,"e");

  }

  if($qnd!=""){

    $retorno .= ".".db_formatar($qnd,'s',"0",1,"e");

  }

  return $retorno;

}



// retorna uma string formatada, retorna false se alguma opção estiver errada

// $tipo pode ser:

// "b" formata boolean s / n

// "f" formata a string pra float

// "d" formata a string pra data

// "v" tira a formatação

// "cpf" formata cpf

// "cnpj" formata cnpj

// "s"  Preenche uma string para um certo tamanho com outra string

// se for "s":

//   $caracter             caracter ou espaço pra acrecentar a esquerda, direita ou meio

//   $quantidade           tamanho que ficará a string com os espaços ou caracteres

//   $TipoDePreenchimento  informa se vai aplicar a string a:

//                         esquerda       "e"

//                         direita        "d"

//                         ambos os lados "a"



function db_formatar($str,$tipo,$caracter=" ",$quantidade=0,$TipoDePreenchimento="e",$casasdecimais=2) {

  switch($tipo) {
    case "p" :

      $str = $str == null ? 0 :$str;
      if ($quantidade == 0) {
        return str_pad(number_format($str, $casasdecimais, ".", ""), 15, "$caracter", STR_PAD_LEFT);
      } else {
        return str_pad(number_format($str, $casasdecimais, ".", ""), $quantidade, "$caracter", STR_PAD_LEFT);
      }

    case "cpf":

       return substr($str,0,3).".".substr($str,3,3).".".substr($str,6,3)."-".substr($str,9,2);

    case "cnpj":

       return substr($str,0,2).".".substr($str,2,3).".".substr($str,5,3)."/".substr($str,8,4)."-".substr($str,12,2);

           //90.832.619/0001-55

    case "b":

      if($str==false){

             return 'N';

      }else{

             return 'S';

      }

    case "f":

          if($quantidade==0)

            return str_pad(number_format($str,2,",","."),15,$caracter,STR_PAD_LEFT);

      else

        return str_pad(number_format($str,2,",","."),$quantidade,$caracter,STR_PAD_LEFT);

    case "d":

      $data = split("-",$str);

      return $data[2]."/".$data[1]."/".$data[0];

    case "s":

          if($TipoDePreenchimento == "e") {

        return str_pad($str,$quantidade,$caracter,STR_PAD_LEFT);

          } else if($TipoDePreenchimento == "d") {

        return str_pad($str,$quantidade,$caracter,STR_PAD_RIGHT);

          } else if($TipoDePreenchimento == "a") {

        return str_pad($str,$quantidade,$caracter,STR_PAD_BOTH);

          }

        case "v":

          if(strpos($str,",") != "") {

            $str = str_replace(".","",$str);

            $str = str_replace(",",".",$str);

                return $str;

          } else if(strpos($str,"-") != "") {

        $str = split("-",$str);

                return $str[2]."-".$str[1]."-".$str[0];

          } else if(strpos($str,"/") != "") {

            $str = split("/",$str);

                return $str[2]."-".$str[1]."-".$str[0];

          }

          break;

  }

  return false;

}



//Cria veriaveis globais de todos os campos do recordset no indice $indice

// $recordset = recordset

// $indice = linha do record set

// $formatar = true para retornar as variaveis formatadas

function db_fieldsmemory($recordset,$indice,$formatar="", $mostravar=false){

  $fm_numfields = @pg_numfields($recordset);

  for ($i = 0;$i < $fm_numfields;$i++){

    $matriz[$i] = pg_fieldname($recordset,$i);


    global $$matriz[$i];

        $aux = trim(pg_result($recordset,$indice,$matriz[$i]));

        if(!empty($formatar)) {

            switch(pg_fieldtype($recordset,$i)) {

            case "float8":

            case "float4":

            case "float":

          $$matriz[$i] = number_format($aux,2,",",".");

                  break;

                case "date":

          if($aux!=""){

                    $data = split("-",$aux);

                    $$matriz[$i] = $data[2]."/".$data[1]."/".$data[0];

                  }else{

                    $$matriz[$i] = "";

                  }

                  break;

                default:

          $$matriz[$i] = $aux;

                  break;

          }


        } else

            switch(pg_fieldtype($recordset,$i)) {

                case "date":

                  $datav = split("-",$aux);

          $split_data = $matriz[$i]."_dia";

          global $$split_data;

          $$split_data =  @$datav[2];

          $split_data = $matriz[$i]."_mes";

          global $$split_data;

          $$split_data =  @$datav[1];

          $split_data = $matriz[$i]."_ano";

          global $$split_data;

          $$split_data =  @$datav[0];

          $$matriz[$i] = $aux;

                  break;

                default:

          $$matriz[$i] = $aux;

                  break;

          }
          $$matriz[$i] = stripslashes($aux);
                        if ($mostravar == true)
                        echo $matriz[$i]."->".$$matriz[$i]."<br>";
  }

}









///////  Calcula Digito Verificador

///////  sCampo - Valor  Ipeso - Qual peso 10 11



function db_CalculaDV($sCampo, $iPeso = 11){

    $mult = 2;

        $i = 0;

        $iDigito = 0;

        $iSoma1 = 0;

        $iDV1 = 0;

        $iTamCampo = strlen($sCampo);

        for ($i=$iTamCampo - 1; $i > -1; $i--){

                $iDigito = $sCampo[$i];

                $iSoma1 = intval($iSoma1,10) + intval(($iDigito * $mult),10);

                $mult++;

        if($mult > 9)

                  $mult = 2;

        }

        $iDV1 = ($iSoma1 % 11);

        if ($iDV1 < 2)

                $iDV1 = 0;

        else

            $iDV1 = 11 - $iDV1;

        return $iDV1;

}



//funcao para a db_CalculaDV

function db_Calcular_Peso($iPosicao, $iPeso) {

  return ($iPosicao % ($iPeso - 1)) + 2;

}





//formata uma string pra cgc ou cpf

function db_cgccpf($str) {

  if(strlen($str) == 14)

    return substr($str,0,2).".".substr($str,2,3).".".substr($str,5,3)."/".substr($str,8,4)."-".substr($str,12,2);

  else if(strlen($str) == 11)

    return substr($str,0,3).".".substr($str,3,3).".".substr($str,6,3)."-".substr($str,9,2);

  else return $str;

}



function verifica_data($dia,$mes,$ano){

  while ( (checkdate($mes,$dia,$ano) == false) or  ( ( date("w",mktime(0,0,0,$mes,$dia,$ano)) == "0" ) or ( date("w",mktime(0,0,0,$mes,$dia,$ano)) == "6" ) ) ){

    if ( $dia > 31 ){

           $dia = 1;

       $mes ++;

           if ( $mes > 12 ){

              $mes = 1;

                  $ano ++;

        }

    }else{

          $dia ++;

        }

  }

  return $ano."-".$mes."-".$dia;

}





function db_vencimento($dt=""){

  if(empty($dt))

    $dt = db_getsession("DB_datausu");

  $data = date("Y-m-d",$dt);

  if ( (date("H",$dt) >= "16" ) ) {

    $data = verifica_data(date("d",$dt)+1,date("m",$dt),date("Y",$dt));

//      echo $data;

  } else {

      if ( ( date("w",mktime(0,0,0,date("m",$dt),date("d",$dt),date("Y",$dt))) == "0" ) or ( date("w",mktime(0,0,0,date("m",$dt),date("d",$dt),date("Y",$dt))) == "6" )  ) {

        $data = verifica_data(date("d",$dt)+1,date("m",$dt),date("Y",$dt));

          }

  }

//  echo $data;

  return $data;

}





//mostra uma mensagem na tela

function db_msgbox($msg) {

  echo "<script>alert('$msg')</script>\n";

}



//redireciona para uma url

function db_redireciona($url="0") {

  if($url == "0")

    $url = $GLOBALS["PHP_SELF"];

  echo "<script>location.href='$url'</script>\n";

  exit;

}



//retorna uma variável de sessão

/*

function db_getsession($var) {

  global $HTTP_SESSION_VARS;

  if(!class_exists("crypt_hcemd5"))

    include("db_calcula.php");

  $rand = 195728462;

  $key = "alapuchatche";

  $md = new Crypt_HCEMD5($key, $rand);

  return $md->decrypt($HTTP_SESSION_VARS[$var]);

}

*/



//retorna uma variável de sessão

//atualiza uma variável de sessao

function db_putsession($var,$valor) {
	$_SESSION[$var] = $valor;
}


//retorna uma string do inicio de $str, até primeiro caractere da ocorrencia em $pos

function db_strpos($str,$pos) {

  return substr($str,0,(strpos($str,$pos)==""?strlen($str):strpos($str,$pos)));

}



//imprime uma mensagem de erro, com um link pra voltar pra página anterior

function db_erro($msg,$voltar=1) {

  $uri = $GLOBALS["PHP_SELF"];

  echo "$msg<br>\n";

  if($voltar == 1)

    echo "<a href=\"$uri\">Voltar</a>\n";

  exit;

}



//Tipo a parseInt do javascript

function db_parse_int($str) {

  $num = array("0","1","2","3","4","5","6","7","8","9","0");

  $tam = strlen($str);

  $aux = "";

  for($i = 0;$i < $tam;$i++) {

    if(in_array($str[$i],$num))

          $aux .= $str[$i];

  }

  return $aux;

}



// Tipo o indexOf do javascript

function db_indexOf($str,$proc) {

  // 0 nao encontrou

  // > 0 encontrou

  return strlen(strstr($str,$proc));

}

function db_hora($id_timestamp=0,$formato="H:i"){
//#00#//db_hora
//#10#// retorna a hora do servidor em no formato HH:MM
//#15#//$hora = db_hora($timestamp,$formato);
//#20#//$id_timestamp =        Data e hora no formato timestamp
//#20#//$formato      = Formato do retorno da hora ou data
//#20#//                Padrao: H:i - Hora e minuto com :.
//#99#//Os tipos de formato de retorno são:
//#99#//a        Meridiano da Hora no formato am ou pm
//#99#//A        Meridiano da Hora no formato AM or PM
//#99#//B        Hora na internet de 000 a 999
//#99#//c        Formato ISO 8601 da data (PHP 5) 2004-02-12T15:19:21+00:00
//#99#//d        Dia do Mes com dois digitos 01 to 31
//#99#//D        3 primeiras letras do dia
//#99#//F        Nome do mes
//#99#//g        Hora no formato de 1 a 12
//#99#//G        Hora no formato 24 horas ( 0 a 23 )
//#99#//h        Hora no formato 12 com zeros 01 through 12
//#99#//H        Hora no formato 24 horas 00 through 23
//#99#//i        Minutos com zero a esquerda 00 to 59
//#99#//j        Dia do mes sem zero a esquerda 1 to 31
//#99#//l       Nome Dia da semana
//#99#//m        Mes numericpo com dois digitos  01 a 12
//#99#//M        3 primeiras letras do nome do mes Jan through Dec
//#99#//n        Mes numerico sem zero a esquerda 1 a 12
//#99#//O        Diferença para hora Greenwich (GMT) em horas        Example: +0200
//#99#//r        Data no formato RFC 2822 Exemplo: Thu, 21 Dec 2000 16:01:07 +0200
//#99#//s        Segundos com zeros a esquerda 00 through 59
//#99#//S        Ordinal sufixo em Ingles do mes, 2 caracteres st, nd, rd or th.
//#99#//t        Numero de dias do mes 28 a 31
//#99#//T        Zona da hora setada na máquina        Exemplo: EST, MDT ...
//#99#//U        Segundos em relação a 1/1/1970  timestamp.
//#99#//w        Nnumero do dia da semana 0 a 6
//#99#//W        Numero da semana do ano conforme ISO-8601
//#99#//Y        Ano com 4 digitos Exemplo: 1999 or 2003
//#99#//y        Ano em 2 digitos Exemplo: 99 or 03
//#99#//z        Dia do ano da data 0 a 365
//#99#//Z        Hora em segundos do timezona. -43200 a 43200
   if($id_timestamp!=0){
     return date($formato,$id_timestamp);
   }else{
     return date($formato);
   }
}


function db_lovrot($query, $numlinhas, $arquivo = "", $filtro = "%", $aonde = "_self", $campos_layer = "", $NomeForm = "NoMe", $variaveis_repassa = array (), $automatico = true, $totalizacao = array()) {

	//Observação : Quando utilizar o parametro automatico, coloque no parametro NomeForm o seguinte "NoMe" e em variaveis_repassa array().

	//#00#//db_lovrot
	//#10#//Esta funcao é utilizada para mostrar registros na tela, podendo páginar os dados
	//#15#//db_lovrot($query,$numlinhas,$arquivo="",$filtro="%",$aonde="_self",$campos_layer="",$NomeForm="NoMe",$variaveis_repassa=array());
	//#20#//$query               Select que será executado
	//#20#//$numlinhas           Número de linhas a serem mostradas
	//#20#//$arquivo             Arquivo que será executado quando der um click em uma linha
	//#20#//                     Na versão com iframe deverá ser colocado "()"
	//#20#//$filtro              Filtro que será gerado, normamente ""
	//#20#//$aonde               Nome da função que será executada quando der um click
	//#20#//$campos_layer        Campos que serão colocados na layer quando passar o mouse ( não esta implementado )
	//#20#//$NomeForm            Nome do formulário para colocar variáveis complementares Padrão = "NoMe"
	//#20#//$variaveis_repassa   Array com as variáveis a serem reoassadas para o programa
	//#99#//Exemplo:
	//#99#//$js_funcao = "";
	//#99#//db_lovrot("select z01_nome from cgm limit 1","()","",$js_funcao);
	//#99#//
	//#99#//O cabeçalho da tabela o sistema pega pelo nome do campo e busca na documentação, colcando o label
	//#99#//Quando não desejar colocar o label da documentacao, o nome do campo deverá ser iniciado com dl_ e o sistema retirará
	//#99#//estes caracteres e colocará o primeiro caracter em maiusculo
	//#99#//Criado parametro novo, $totalizacao = array() que devere fornecer os campos que desejar fazer somatorio, conforme
	//#99#//exemplo abaixo:
	//#99#//
	//#99#//$totalizacao["e60_vlremp"] = "e60_vlremp"; totaliza o campo
	//#99#//$totalizacao["e60_vlranu"] = "e60_vlranu"; totaliza o campo
	//#99#//$totalizacao["e60_vlrpag"] = "e60_vlrpag"; totaliza o campo
	//#99#//$totalizacao["e60_vlrliq"] = "e60_vlrliq"; totaliza o campo
	//#99#//$totalizacao["dl_saldo"] = "dl_saldo";     totaliza o campo ( neste caso, o campo é um alias no sql)
	//#99#//$totalizacao["totalgeral"] = "z01_nome";   indica qual o campo sera colocado o total


	global $BrowSe;
//cor do cabecalho
global $db_corcabec;
$db_corcabec = $db_corcabec == "" ? "#CDCDFF" : $db_corcabec;
//cor de fundo de cada registro
global $cor1;
global $cor2;
$cor1 = $GLOBALS['w01_corfundomenu'];
$cor2 = $GLOBALS['w01_corfundomenuativo'];
$mensagem = "Clique Aqui";
$cor1 = $cor1 == "" ? "#97B5E6" : $cor1;
$cor2 = $cor2 == "" ? "#E796A4" : $cor2;
global $HTTP_POST_VARS;
$tot_registros = "tot_registros".$NomeForm;
$offset = "offset".$NomeForm;
//recebe os valores do campo hidden

if (isset ($HTTP_POST_VARS["totreg".$NomeForm])) {
	$$tot_registros = $HTTP_POST_VARS["totreg".$NomeForm];
} else {
	$$tot_registros = 0;
}
if (isset ($HTTP_POST_VARS["offset".$NomeForm])) {
	$$offset = $HTTP_POST_VARS["offset".$NomeForm];
} else {
	$$offset = 0;
}
if(isset($HTTP_POST_VARS["recomecar"])){
	$recomecar = $HTTP_POST_VARS["recomecar"];
}
// se for a primeira vez que é rodado, pega o total de registros e guarda no campo hidden
if ( ( empty ($$tot_registros) && !empty ($query) ) || isset($recomecar)) {
	if(isset($recomecar)){
		$query = db_getsession("dblov_query_inicial");
	}
	$Dd1 = "disabled";
	if(count($totalizacao)>0 || isset($totalizacao_rep)){
		$total_campos = "";
		$sep_total_campos = "";
		reset($totalizacao);
		for ($j = 0; $j < count($totalizacao); $j ++) {
			if( key($totalizacao) == $totalizacao[key($totalizacao)] ){
				$total_campos .= $sep_total_campos."sum(".$totalizacao[key($totalizacao)].") as ".$totalizacao[key($totalizacao)];
				$sep_total_campos = ",";
			}
			next($totalizacao);
		}
		reset($totalizacao);
		$tot = db_query("select count(*),$total_campos
	from ($query) as temp");
	}else{

		$tot = db_query("select count(*) from ($query) as temp");
	}
	//$tot = 0;

	db_putsession("dblov_query_inicial",$query);

	$$tot_registros = pg_result($tot, 0, 0);
	if ($$tot_registros == 0)
	$Dd2 = "disabled";
}


if(isset($HTTP_POST_VARS["nova_quantidade_linhas"]) && $HTTP_POST_VARS["nova_quantidade_linhas"]!=''){
	$HTTP_POST_VARS["nova_quantidade_linhas"] = $HTTP_POST_VARS["nova_quantidade_linhas"] + 0;
	$numlinhas = $HTTP_POST_VARS["nova_quantidade_linhas"];
}

// testa qual botao foi pressionado
if (isset ($HTTP_POST_VARS["pri".$NomeForm])) {
	$$offset = 0;
	$Dd1 = "disabled";
	$query = str_replace("\\", "", $HTTP_POST_VARS["filtroquery"]);
} else
if (isset ($HTTP_POST_VARS["ant".$NomeForm])) {
	// if(isset("filtroquery"]);
	$query = str_replace("\\", "", @ $HTTP_POST_VARS["filtroquery"]);
	if ($$offset <= $numlinhas) {
		$$offset = 0;
		$Dd1 = "disabled";
	} else
	$$offset = $$offset - $numlinhas;
} else
if (isset ($HTTP_POST_VARS["prox".$NomeForm])) {
	$query = str_replace("\\", "", $HTTP_POST_VARS["filtroquery"]);
	//    if($numlinhas >= ($$tot_registros - $$offset - $numlinhas)) {

	if (($$offset + ($numlinhas * 2)) >= $$tot_registros) {
		$Dd2 = "disabled";
	}

	if ($numlinhas >= ($$tot_registros - $$offset)) {
		//$$offset = $$tot_registros - $numlinhas;
		if ($$tot_registros - $$offset - $numlinhas >= $numlinhas)
		$$offset = $numlinhas;
		else
		$$offset = $$offset + $numlinhas;

		if ($$offset > $$tot_registros)
		$$offset = 0;
		$Dd2 = "disabled";
	} else
	$$offset = $$offset + $numlinhas;
} else
if (isset ($HTTP_POST_VARS["ult".$NomeForm])) {
	$query = str_replace("\\", "", $HTTP_POST_VARS["filtroquery"]);
	$$offset = $$tot_registros - $numlinhas;
	if ($$offset < 0) {
		$$offset = 0;
	}
	$Dd2 = "disabled";
} else {
	reset($HTTP_POST_VARS);
	for ($i = 0; $i < sizeof($HTTP_POST_VARS); $i ++) {
		$ordem_lov = substr(key($HTTP_POST_VARS), 0, 11);
		if ($ordem_lov == 'ordem_dblov') {
			$query = str_replace("\\", "", $HTTP_POST_VARS["filtroquery"]);
			$campo = substr(key($HTTP_POST_VARS), 11);

			$ordem_ordenacao = '';

			if(isset($HTTP_POST_VARS['ordem_lov_anterior'])){
				if($HTTP_POST_VARS['ordem_lov_anterior'] == $HTTP_POST_VARS[key($HTTP_POST_VARS)] ){
					$ordem_ordenacao = 'desc';
				}
			}

			if ($HTTP_POST_VARS["codigo_pesquisa"] != '') {
				$query_anterior = $query;
				$query_novo_filtro = "select * from (".$query.") as x where ".$campo." ILIKE '".$HTTP_POST_VARS["codigo_pesquisa"]."%' order by ".$campo.' '.$ordem_ordenacao;
				$query = $query_novo_filtro;
			} else {
				if ($HTTP_POST_VARS["distinct_pesquisa"] == '1') {
					$query_anterior = $query;
					$query = "select distinct on (".$campo.") * from (".$query.") as x order by ".$campo." ".$ordem_ordenacao;
					$query_novo_filtro = $query;
				}else{
					$query = "select * from (".$query.") as x order by ".$campo." ".$ordem_ordenacao;
				}
			}
			$$offset = 0;
			break;
		}
		next($HTTP_POST_VARS);
	}
}

$filtroquery = $query;
// executa a query e cria a tabela
if ($query == "") {
	exit;
}


$query .= " limit $numlinhas offset ".$$offset;
$result = db_query($query);
$NumRows = pg_numrows($result);

if ($NumRows == 0) {
	if (isset ($query_anterior)) {
		echo "<script>alert('Não existem dados para este filtro');</script>";


		if(count($totalizacao)>0 || isset($totalizacao_rep)){
			$total_campos = "";
			$sep_total_campos = "";
			reset($totalizacao);
			for ($j = 0; $j < count($totalizacao); $j ++) {
				if( key($totalizacao) == $totalizacao[key($totalizacao)] ){
					$total_campos .= $sep_total_campos."sum(".$totalizacao[key($totalizacao)].") as ".$totalizacao[key($totalizacao)];
					$sep_total_campos = ",";
				}
				next($totalizacao);
			}
			reset($totalizacao);
			$tot = db_query("select count(*),$total_campos
                            from ($query_anterior) as temp");
		}else{
			$tot = db_query("select count(*) from ($query_anterior) as temp");
		}
		//$tot = 0;
		$$tot_registros = pg_result($tot, 0, 0);


		$$tot_registros = pg_result($tot, 0, 0);

		$query = $query_anterior." limit $numlinhas offset ".$$offset;
		$result = db_query($query);
		$NumRows = pg_numrows($result);
		$filtroquery = $query_anterior;
	}
} else {
	if (isset ($query_anterior)) {
		$Dd1 = "disabled";

		if(count($totalizacao)>0 || isset($totalizacao_rep)){
			$total_campos = "";
			$sep_total_campos = "";
			reset($totalizacao);
			for ($j = 0; $j < count($totalizacao); $j ++) {
				if( key($totalizacao) == $totalizacao[key($totalizacao)] ){
					$total_campos .= $sep_total_campos."sum(".$totalizacao[key($totalizacao)].") as ".$totalizacao[key($totalizacao)];
					$sep_total_campos = ",";
				}
				next($totalizacao);
			}
			reset($totalizacao);
			$tot = db_query("select count(*),$total_campos
					from ($query_novo_filtro) as temp");
		}else{
			$tot = db_query("select count(*) from ($query_novo_filtro) as temp");
		}
		//$tot = 0;
		$$tot_registros = pg_result($tot, 0, 0);
		if ($$tot_registros == 0)
		$Dd2 = "disabled";
	}

}

// echo "<script>alert('$NumRows')</script>";

$NumFields = pg_numfields($result);
if (($NumRows < $numlinhas) && ($numlinhas < ($$tot_registros - $$offset - $numlinhas))) {
	$Dd1 = @ $Dd2 = "disabled";
}

echo "<script>
		function js_mostra_text(liga,nomediv,evt){

			evt = (evt)?evt:(window.event)?window.event:'';
			if(liga==true){

			document.getElementById(nomediv).style.top = 0; //evt.clientY;
			document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
			document.getElementById(nomediv).style.visibility = 'visible';
			}else
                    document.getElementById(nomediv).style.visibility = 'hidden';
			}
			function js_troca_ordem(nomeform,campo,valor){
			obj=document.createElement('input');
				obj.setAttribute('name',campo);
				obj.setAttribute('type','submit');
				obj.setAttribute('value',valor);
				obj.setAttribute('style','color:#FCA;background-color:transparent;border-style:none');
					eval('document.'+nomeform+'.appendChild(obj)');
				eval('document.'+nomeform+'.'+campo+'.click()');
			}
				function js_lanca_codigo_pesquisa(valor_recebido){
				document.navega_lov".$NomeForm.".codigo_pesquisa.value = valor_recebido;
			}
			function js_lanca_distinct_pesquisa(){
			document.navega_lov".$NomeForm.".distinct_pesquisa.value = 1;
			}
			function js_nova_quantidade_linhas(valor_recebido){
			valor_recebe = Number(valor_recebido);
          if(!valor_recebe){
			alert('Valor Inválido!');
			document.navega_lov".$NomeForm.".nova_quantidade_linhas.value = '';
                document.getElementById('quant_lista').value = '';
		}else{
		if(valor_recebe > 100){
			document.navega_lov".$NomeForm.".nova_quantidade_linhas.value = '100';
			document.getElementById('quant_lista').value = 100;
		}else{
			document.navega_lov".$NomeForm.".nova_quantidade_linhas.value = valor_recebido;
		}
		}
		}
		</script>";

		echo "<table id=\"TabDbLov\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\" class=\"lov\">\n";
/**** botoes de navegacao ********/
echo "<tr><td colspan=\"". ($NumFields +1)."\" nowrap> <form name=\"navega_lov".$NomeForm."\" method=\"post\">
	    <input type=\"submit\" name=\"pri".$NomeForm."\" value=\"Início\" ".@ $Dd1." class=\"botao\">
	    <input type=\"submit\" name=\"ant".$NomeForm."\" value=\"Anterior\" ".@ $Dd1." class=\"botao\">
	    <input type=\"submit\" name=\"prox".$NomeForm."\" value=\"Próximo\" ".@ $Dd2." class=\"botao\">
	    <input type=\"submit\" name=\"ult".$NomeForm."\" value=\"Último\" ".@ $Dd2." class=\"botao\">
			<input type=\"hidden\" name=\"offset".$NomeForm."\" value=\"".@ $$offset."\">
			<input type=\"hidden\" name=\"totreg".$NomeForm."\" value=\"".@ $$tot_registros."\">
			<input type=\"hidden\" name=\"codigo_pesquisa\" value=\"\">\n
			<input type=\"hidden\" name=\"distinct_pesquisa\" value=\"\">\n
			<input type=\"hidden\" name=\"filtro\" value=\"$filtro\">\n";
reset($variaveis_repassa);
if (sizeof($variaveis_repassa) > 0) {
	for ($varrep = 0; $varrep < sizeof($variaveis_repassa); $varrep ++) {
		echo "<input type=\"hidden\" name=\"".key($variaveis_repassa)."\" value=\"".$variaveis_repassa[key($variaveis_repassa)]."\">\n";
		next($variaveis_repassa);
	}
}

if (isset($ordem_lov) && (isset($ordem_ordenacao) && $ordem_ordenacao == '' ) ) {
	echo "<input type=\"hidden\" name=\"ordem_lov_anterior\" value=\"".$HTTP_POST_VARS[key($HTTP_POST_VARS)]."\">\n";
}
if(isset($HTTP_POST_VARS['nova_quantidade_linhas']) && $HTTP_POST_VARS['nova_quantidade_linhas'] == ''){
	$numlinhas = $HTTP_POST_VARS['nova_quantidade_linhas'];
}

echo "<input type=\"hidden\" name=\"nova_quantidade_linhas\" value=\"$numlinhas\" >\n";

if(isset($totalizacao) && isset($tot)){
	if(count($totalizacao)>0 ){
		$totNumfields = pg_numfields($tot);
		for($totrep=1;$totrep<$totNumfields;$totrep++){
			echo "<input type=\"hidden\" name=\"totrep_".pg_fieldname($tot,$totrep)."\" value=\"".db_formatar(pg_result($tot,0,$totrep),'f')."\">";
		}
		reset($totalizacao);
		$totrepreg = "";
		$totregsep = "";
		for($totrep=0;$totrep<count($totalizacao);$totrep++){
			$totrepreg .= $totregsep.key($totalizacao)."=".$totalizacao[key($totalizacao)];
			$totregsep = "|";
			next($totalizacao);
		}
		reset($totalizacao);
		echo "<input type=\"hidden\" name=\"totalizacao_repas\" value=\"".$totrepreg."\">";
	}
}else if(isset( $HTTP_POST_VARS["totalizacao_repas"]) ){
	$totalizacao_split = split("\|",$HTTP_POST_VARS["totalizacao_repas"]);
	for($totrep=0;$totrep<count($totalizacao_split);$totrep++){
		$totalizacao_sep = split("\=",$totalizacao_split[$totrep]);
		$totalizacao[$totalizacao_sep[0]] = $totalizacao_sep[1];
		if(isset($HTTP_POST_VARS["totrep_".$totalizacao_sep[0]])){
			echo "<input type=\"hidden\" name=\"totrep_".$totalizacao_sep[0]."\" value=\"".$HTTP_POST_VARS["totrep_".$totalizacao_sep[0]]."\">";
		}
	}
	echo "<input type=\"hidden\" name=\"totalizacao_repas\" value=\"".$HTTP_POST_VARS["totalizacao_repas"]."\">";
}

echo "<input type=\"hidden\" name=\"filtroquery\" value=\"".str_replace("\n", "", @ $filtroquery)."\">
          ". ($NumRows > 0 ? "
          Foram retornados <font color=\"red\"><strong>".$$tot_registros."</strong></font> registros.
		Mostrando de <font color=\"red\"><strong>". (@ $$offset +1)."</strong></font> até
          <font color=\"red\"><strong>". ($$tot_registros < (@ $$offset + $numlinhas) ? ($NumRows <= $numlinhas ? $$tot_registros : $NumRows) : ($$offset + $numlinhas))."</strong></font>." : "Nenhum Registro
		Retornado")."</form>
          </td></tr>\n";

/*********************************/
/***** Escreve o cabecalho *******/
if ($NumRows > 0) {
	echo "<tr>\n";
	// implamentacao de informacoes complementares
	//    echo "<td title='Outras Informações'>OI</td>\n";
	//se foi passado funcao
	if ($campos_layer != "") {
		$campo_layerexe = split("\|", $campos_layer);
		echo "<td nowrap bgcolor=\"$db_corcabec\" title=\"Executa Procedimento Específico.\" align=\"center\">Clique</td>\n";
	}

	$clrotulocab = new rotulolov();
	for ($i = 0; $i < $NumFields; $i ++) {
		if (strlen(strstr(pg_fieldname($result, $i), "db_")) == 0) {
			$clrotulocab->label(pg_fieldname($result, $i));
			//echo "<td nowrap bgcolor=\"$db_corcabec\" title=\"".$clrotulocab->title."\" align=\"center\"><b><u>".$clrotulocab->titulo."</u></b></td>\n";
			echo "<td nowrap bgcolor=\"$db_corcabec\" title=\"".$clrotulocab->title."\" align=\"center\"><input name=\"".pg_fieldname($result, $i)."\" value=\"".ucfirst($clrotulocab->titulo)."\" type=\"button\" onclick=\"js_troca_ordem('navega_lov".$NomeForm."','ordem_dblov".pg_fieldname($result, $i)."','".pg_fieldname($result, $i)."');\" style=\"text-decoration:underline;background-color:transparent;border-style:none\"> </td>\n";

		} else {
			if (strlen(strstr(pg_fieldname($result, $i), "db_m_")) != 0) {
				echo "<td nowrap bgcolor=\"$db_corcabec\" title=\"".substr(pg_fieldname($result, $i), 5)."\" align=\"center\"><b><u>".substr(pg_fieldname($result, $i), 5)."</u></b></td>\n";
			}
		}
	}
	echo "</tr>\n";
}
//cria nome da funcao com parametros
if ($arquivo == "()") {
	$arrayFuncao = split("\|", $aonde);
	$quantidadeItemsArrayFuncao = sizeof($arrayFuncao);

}

/********************************/
/****** escreve o corpo *******/

for ($i = 0; $i < $NumRows; $i ++) {
	echo '<tr >'."\n";
	// implamentacao de informacoes complementares
	//          echo '<td onMouseOver="document.getElementById(\'div'.$i.'\').style.visibility=\'visible\';" onMouseOut="document.getElementById(\'div'.$i.'\').style.visibility=\'hidden\';" >-></td>'."\n";
	if ($arquivo == "()") {
		$loop = "";
		$caracter = "";
		if ($quantidadeItemsArrayFuncao > 1) {
			for ($cont = 1; $cont < $quantidadeItemsArrayFuncao; $cont ++) {
				if (strlen($arrayFuncao[$cont]) > 3) {
					for ($luup = 0; $luup < pg_NumFields($result); $luup ++) {
						if (pg_FieldName($result, $luup) == "db_".$arrayFuncao[$cont]) {
							$arrayFuncao[$cont] = "db_".$arrayFuncao[$cont];
						}
					}
				}
				$loop .= $caracter."'".addslashes(str_replace('"','',@ pg_result($result, $i, (strlen($arrayFuncao[$cont]) < 4 ? (int) $arrayFuncao[$cont] : $arrayFuncao[$cont]))))."'";
				//$loop .= $caracter."'".pg_result($result,$i,(int)$arrayFuncao[$cont])."'";
				$caracter = ",";
			}
			$resultadoRetorno = $arrayFuncao[0]."(".$loop.")";
		} else {
			$resultadoRetorno = $arrayFuncao[0]."()";
		}
	}

	/*
		if($NumRows==1){
	if($arquivo!=""){
	echo "<td>$resultadoRetorno<td>";
	exit;
	}else{
	echo "<script>JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?0:trim(pg_result($result,0,0))))."','$aonde','width=800,height=600');</script>";
	exit;
	}
	}
	*/

	if (isset ($cor)) {
		$cor = $cor == $cor1 ? $cor2 : $cor1;
	} else {
		$cor = $cor1;
	}
	// implamentacao de informacoes complementares
	//    $mostradiv="";
	if ($campos_layer != "") {
		$campo_layerexe = split("\|", $campos_layer);
		echo "<td id=\"funcao_aux".$i."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a href=\"\" onclick=\"".$campo_layerexe[1]."($loop);return false\" ><strong>".$campo_layerexe[0]."&nbsp;</strong></a></td>\n";
	}
	for ($j = 0; $j < $NumFields; $j ++) {
		if (strlen(strstr(pg_fieldname($result, $j), "db_")) == 0 || strlen(strstr(pg_fieldname($result, $j), "db_m_")) != 0) {
			if (pg_fieldtype($result, $j) == "date") {
				if (pg_result($result, $i, $j) != "") {
					$matriz_data = split("-", pg_result($result, $i, $j));
					$var_data = $matriz_data[2]."/".$matriz_data[1]."/".$matriz_data[0];
				} else {
					$var_data = "//";
				}
				echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim($var_data)."</a>" : (trim($var_data)))."&nbsp;</td>\n";
			} else {
				if (pg_fieldtype($result, $j) == "float8" || pg_fieldtype($result, $j) == "float4") {
					$var_data = db_formatar(pg_result($result, $i, $j), 'f', ' ');
					echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000\" bgcolor=\"$cor\" align=right nowrap>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim($var_data)."</a>" : (trim($var_data)))."&nbsp;</td>\n";
				} else {
					if (pg_fieldtype($result, $j) == "bool") {
						$var_data = (pg_result($result, $i, $j) == 'f' || pg_result($result, $i, $j) == '' ? 'Não' : 'Sim');
						echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;align:right\" bgcolor=\"$cor\" nowrap>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim($var_data)."</a>" : (trim($var_data)))."&nbsp;</td>\n";
					} else {
						if (pg_fieldtype($result, $j) == "text") {
							$var_data = substr(pg_result($result, $i, $j), 0, 10)."...";
							echo "<td onMouseOver=\"js_mostra_text(true,'div_text_".$i."_".$j."',event);\" onMouseOut=\"js_mostra_text(false,'div_text_".$i."_".$j."',event)\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;align:right\" bgcolor=\"$cor\" nowrap>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim($var_data)."</a>" : (trim($var_data)))."&nbsp;</td>\n";

						} else {

							if (pg_fieldname($result, $j) == 'j01_matric')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Imóvel' onclick=\"js_JanelaAutomatica('iptubase','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'm80_codigo')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Lançamento' onclick=\"js_JanelaAutomatica('matestoqueini','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'm40_codigo')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Requisição' onclick=\"js_JanelaAutomatica('matrequi','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'm42_codigo')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Atendimento' onclick=\"js_JanelaAutomatica('atendrequi','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'm45_codigo')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Devolução' onclick=\"js_JanelaAutomatica('matestoquedev','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 't52_bem')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Bem' onclick=\"js_JanelaAutomatica('bem','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'q02_inscr')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Issqn' onclick=\"js_JanelaAutomatica('issbase','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'z01_numcgm')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Contribuinte/Empresa' onclick=\"js_JanelaAutomatica('cgm','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							//else if(pg_fieldname($result,$j)=='o58_coddot' )
							//  echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Dotação Orçamentária' onclick=\"js_JanelaAutomatica('orcdotacao','".(trim(pg_result($result,$i,$j)))."','".(trim(pg_result($result,$i,"o58_anousu")))."');return false;\">&nbsp;Inf->&nbsp;</a>".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ".($arquivo=="()"?"OnClick=\"".$resultadoRetorno.";return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result,$i,$j))."</a>":(trim(pg_result($result,$i,$j))))."&nbsp;</td>\n";
							//else if(pg_fieldname($result,$j)=='o59_coddot' )
							//  echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Dotação Orçamentária' onclick=\"js_JanelaAutomatica('orcdotacao','".(trim(pg_result($result,$i,$j)))."','".(trim(pg_result($result,$i,"o59_anousu")))."');return false;\">&nbsp;Inf->&nbsp;</a>".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ".($arquivo=="()"?"OnClick=\"".$resultadoRetorno.";return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result,$i,$j))."</a>":(trim(pg_result($result,$i,$j))))."&nbsp;</td>\n";
							//else if(pg_fieldname($result,$j)=='o61_coddot' )
							//  echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Dotação Orçamentária' onclick=\"js_JanelaAutomatica('orcdotacao','".(trim(pg_result($result,$i,$j)))."','".(trim(pg_result($result,$i,"o61_anousu")))."');return false;\">&nbsp;Inf->&nbsp;</a>".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ".($arquivo=="()"?"OnClick=\"".$resultadoRetorno.";return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result,$i,$j))."</a>":(trim(pg_result($result,$i,$j))))."&nbsp;</td>\n";
							//else if(pg_fieldname($result,$j)=='o70_codrec' )
							//  echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Receita Orçamentária' onclick=\"js_JanelaAutomatica('orcreceita','".(trim(pg_result($result,$i,$j)))."','".(trim(pg_result($result,$i,"o70_anousu")))."');return false;\">&nbsp;Inf->&nbsp;</a>".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ".($arquivo=="()"?"OnClick=\"".$resultadoRetorno.";return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result,$i,$j))."</a>":(trim(pg_result($result,$i,$j))))."&nbsp;</td>\n";
							//else if(pg_fieldname($result,$j)=='o71_codrec' )
							//  echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Receita Orçamentária' onclick=\"js_JanelaAutomatica('orcreceita','".(trim(pg_result($result,$i,$j)))."','".(trim(pg_result($result,$i,"o71_anousu")))."');return false;\">&nbsp;Inf->&nbsp;</a>".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ".($arquivo=="()"?"OnClick=\"".$resultadoRetorno.";return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result,$i,$j))."</a>":(trim(pg_result($result,$i,$j))))."&nbsp;</td>\n";
							//else if(pg_fieldname($result,$j)=='o74_codrec' )
							//  echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações Receita Orçamentária' onclick=\"js_JanelaAutomatica('orcreceita','".(trim(pg_result($result,$i,$j)))."','".(trim(pg_result($result,$i,"o74_anousu")))."');return false;\">&nbsp;Inf->&nbsp;</a>".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ".($arquivo=="()"?"OnClick=\"".$resultadoRetorno.";return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result,$i,$j))."</a>":(trim(pg_result($result,$i,$j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'e60_numemp' || pg_fieldname($result, $j) == 'e61_numemp' || pg_fieldname($result, $j) == 'e62_numemp')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações do Empenho' onclick=\"js_JanelaAutomatica('empempenho','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == 'e54_autori' || pg_fieldname($result, $j) == 'e55_autori' || pg_fieldname($result, $j) == 'e56_autori')
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações da Autorização de Empenho' onclick=\"js_JanelaAutomatica('empautoriza','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							if (pg_fieldname($result, $j) == "pc10_numero")
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap><a title='Informações da Solicitação' onclick=\"js_JanelaAutomatica('empsolicita','". (trim(pg_result($result, $i, $j)))."');return false;\">&nbsp;Inf->&nbsp;</a>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
							else
							echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" bgcolor=\"$cor\" nowrap>". ($arquivo != "" ? "<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;\" href=\"\" ". ($arquivo == "()" ? "OnClick=\"".$resultadoRetorno.";return false\">" : "onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=". ($BrowSe == 1 ? $i : trim(pg_result($result, $i, 0))))."','$aonde','width=800,height=600');return false\">").trim(pg_result($result, $i, $j))."</a>" : (trim(pg_result($result, $i, $j))))."&nbsp;</td>\n";
						}
					}
				}
			}
		}
	}
	echo "</tr>\n";

	// implamentacao de informacoes complementares
	//    $divmostra .= "</table>";
	//    $divmostra .= '<div id="div'.$i.'" name="div'.$i.'" style="position:absolute; left:30px; top:40px; z-index:1; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; layer-background-color: #CCCCCC;">';
	//    $divmostra .= '<table  border=\"1\"  align=\"center\" cellspacing=\"1\">';
	//    $divmostra .= '<tr>';
	//    $divmostra .= '<td> '.$mostradiv;
	//    $divmostra .= '</td> ';
	//    $divmostra .= '</tr> ';
	//    $divmostra .= '</table>';
	//    $divmostra .= '</div>';
}
//  echo $divmostra;
/******************************/

if(count($totalizacao) > 0 ){

	echo  "<tr>";
	for ($j = 0; $j < $NumFields; $j ++) {

		$key_elemento = array_search( pg_fieldname($result, $j), $totalizacao ) ;

		if( $key_elemento == true && pg_fieldname($result, $j) == $key_elemento && strlen(strstr(pg_fieldname($result, $j), "db_")) == 0 ){

			@$vertotrep = $HTTP_POST_VARS['totrep_'.$key_elemento];

			if(@$vertotrep!="" && !isset($tot)){
				echo "<td style=\"text-decoration:none;color:#000000\" bgcolor=\"white\" align=right nowrap> ".$vertotrep."&nbsp;</td>\n";
			}else if(isset($tot)){
				echo "<td style=\"text-decoration:none;color:#000000\" bgcolor=\"white\" align=right nowrap> ".db_formatar(pg_result($tot,0,$key_elemento),'f')."&nbsp;</td>\n";
			}else{
				echo "<td style=\"text-decoration:none;color:#000000\" bgcolor=\"white\" align=right nowrap> &nbsp;</td>\n";
			}
		}else{
			if ($key_elemento == 'totalgeral')
			echo  "<td align='right'><strong>Total Geral : </strong></td>";
			else if (strlen(strstr(pg_fieldname($result, $j), "db_")) == 0)
			echo  "<td></td>";
		}
	}
	echo "</tr>";

}

if ($NumRows > 0) {
	echo "<tr><td colspan=$NumFields >
              <input name='recomecar' type='button' value='Recomeçar' onclick=\"js_troca_ordem('navega_lov".$NomeForm."','recomecar','0');\" class=\"botao\">
						<strong>Indique o Conteúdo:</strong><input title='Digite o valor a pesquisar e clique sobre o campo (cabeçalho) a pesquisar' name=indica_codigo type=text onchange='js_lanca_codigo_pesquisa(this.value)' style='background-color:#E6E4F1'>
						<strong>Quantidade a Listar:</strong><input id=quant_lista name=quant_lista type=text onchange='js_nova_quantidade_linhas(this.value)' style='background-color:#E6E4F1' value='$numlinhas' size='5'>
						<strong>Mostra Diferentes:</strong><input title='Mostra os valores diferentes clicando no cabeçalho a pesquisar' name=mostra_diferentes type=checkbox onchange='js_lanca_distinct_pesquisa()' style='background-color:#E6E4F1'>
						</td>";
	echo "</tr>\n";
}

echo "</table>";

for ($i = 0; $i < $NumRows; $i ++) {
	for ($j = 0; $j < $NumFields; $j ++) {
		if (pg_fieldtype($result, $j) == "text") {
			$clrotulocab->label(pg_fieldname($result, $j));
			echo "<div id='div_text_".$i."_".$j."' style='position:absolute;left:10px; top:10px; visibility:hidden ; background-color:#6699CC ; border:2px outset #cccccc; align:left'>
						<table>
						<tr>
						<td align='left'>
						<font color='black' face='arial' size='2'><strong>".$clrotulocab->titulo."</strong>:</font><br>
						<font color='black' face='arial' size='1'>".str_replace("\n", "<br>", pg_result($result, $i, $j))."</font>
						</td>
						</tr>
						</table>
						</div>";
		}
	}

}
if ($automatico == true) {
	if (pg_numrows($result) == 1 && $$offset == 0) {
		echo "<script>".@ $resultadoRetorno."</script>";
	}
}

return $result;
}


//Insere um registro de log
function db_logs($matricula,$inscricao,$numcgm,$string) {

	$modulo = 5457; /* codigo modulo DBpref */

	if(db_getsession("DB_login") != "") {
		$usuario = db_getsession("DB_login");
	} else {
		$sqluser  = "select id_usuario ";
		$sqluser .= "  from db_usuarios ";
		$sqluser .= " where login ~ 'dbpref' ";

		$resultuser = db_query($sqluser);
		if (pg_num_rows($resultuser)>0) {
			$usuario = pg_result($resultuser, 0, 0);
		}

	}

	if ($usuario == "") {
		$usuario = 1;
	}

	$instit = db_getsession("DB_instit");

	$funcao = explode('?', basename($_SERVER["REQUEST_URI"]));

	$sqlmenu  = "select db_itensmenu.id_item ";
	$sqlmenu .= "  from db_itensmenu ";
	$sqlmenu .= "       join db_menu on db_menu.id_item_filho = db_itensmenu.id_item ";
	$sqlmenu .= " where trim(funcao) = '{$funcao[0]}' ";
	$sqlmenu .= "   and modulo = {$modulo} ";

  $resultmenu = db_query($sqlmenu);

	if ($resultmenu and pg_num_rows($resultmenu)>0) {
		$item = pg_result($resultmenu, 0, 0);
	} else {

    if (db_getsession('DB_itemmenu_acessado')) {
      $item = db_getsession('DB_itemmenu_acessado');
    } else {
		  $item = $modulo;
    }
	}

	$sqlseq = "select nextval('db_logsacessa_codsequen_seq')";
	$resultseq = db_query($sqlseq);
	$codsequen = pg_result($resultseq, 0, 0);

	// grava codigo na sessao
	db_putsession("DB_itemmenu_acessado",$item);
	db_putsession("DB_acessado",$codsequen);

	if ( (int)$matricula<>0 or (int)$inscricao<>0 or (int)$numcgm<>0 ) {
		$string .= " (";
		$string .= (int)$matricula<>0?"Matricula: $matricula":"";
		$string .= (int)$inscricao<>0?"Inscricao: $inscricao":"";
		$string .= (int)$numcgm<>0?"Numcgm: $numcgm":"";
		$string .= ")";
	}

	$data = date("Y-m-d");
	$hora = date("G:i:s");

	$sqlinsert  = "INSERT INTO db_logsacessa (codsequen, ip, data, hora, arquivo, obs, id_usuario, id_modulo, id_item, coddepto, instit) ";
	$sqlinsert .= "VALUES ({$codsequen}, ";
	$sqlinsert .= "        '{$_SERVER["REMOTE_ADDR"]}', ";
	$sqlinsert .= "        '{$data}', ";
	$sqlinsert .= "        '{$hora}', ";
	$sqlinsert .= "        '{$_SERVER["REQUEST_URI"]}', ";
	$sqlinsert .= "        '{$string}', ";
	$sqlinsert .= "        {$usuario}, ";
	$sqlinsert .= "        {$modulo}, ";
	$sqlinsert .= "        {$item}, ";
	$sqlinsert .= "        NULL, ";
	$sqlinsert .= "        {$instit}) ";

	$result = db_query($sqlinsert) or die(pg_last_error()." - SQL:".$sqlinsert);

	return;
}



/* Cria menus */



function db_menu($usuario,$modulo,$anousu,$instit) {

  global $HTTP_SERVER_VARS;

  global $conn;

  $menu = db_query($conn,

  "SELECT m.id_item,m.id_item_filho,m.menusequencia,i.descricao,i.help,i.funcao

                                     FROM db_menu m

                                           INNER JOIN db_permissao p

                                       ON p.id_item = m.id_item_filho

                                           INNER JOIN db_itensmenu i

                                           ON i.id_item = m.id_item_filho

                                           AND p.permissaoativa = '1'

                                         AND p.anousu = $anousu

                                         AND p.id_instit = $instit

                                         AND p.id_modulo = $modulo

                                     WHERE p.id_usuario = $usuario

                     AND m.modulo = $modulo

                                           AND i.itemativo = 1

                                     ORDER BY m.id_item,m.id_item_filho,m.menusequencia

                                         ");

                                         //order by id_item,menusequencia

//                                     ORDER BY m.id_item,m.id_item_filho,m.menusequencia

  $NumMenu = pg_numrows($menu);

  if ( $NumMenu != 0 ){

     echo "<div class=\"menuBar\" style=\"width:80%;position:absolute;left:0px;top:0px\">\n";

     for($i = 0;$i < $NumMenu;$i++) {

           $URI = pg_result($menu,$i,5) == ""?"":"http://".$HTTP_SERVER_VARS["HTTP_HOST"].substr($HTTP_SERVER_VARS["PHP_SELF"],0,strrpos($HTTP_SERVER_VARS["PHP_SELF"],"/"))."/".pg_result($menu,$i,5);

             if(pg_result($menu,$i,0) == $modulo) {

             echo "<a class=\"menuButton\" href=\"\" onclick=\"return buttonClick(event, 'Ijoao".pg_result($menu,$i,1)."');\" onmouseover=\"buttonMouseover(event, 'Ijoao".pg_result($menu,$i,"id_item_filho")."');\">".pg_result($menu,$i,"descricao")."</a>\n";

           }

     }

     echo "<a class=\"menuButton\" id=\"menuSomeTela\" href=\"\" onclick=\"someFrame(event,1); return false\">Tela</a>\n";

     echo "</div>\n";

         for($i = 0;$i < $NumMenu;$i++) {

            for($j = 0;$j < $NumMenu;$j++) {

         if(pg_result($menu,$j,"id_item") == pg_result($menu,$i,"id_item_filho")) {

               echo "<div id=\"Ijoao".pg_result($menu,$i,"id_item_filho")."\" class=\"menu\" onmouseover=\"menuMouseover(event)\">\n";

           for($a = 0;$a < $NumMenu;$a++) {

                     if(pg_result($menu,$j,"id_item") == pg_result($menu,$a,"id_item")) {

                           $verifica = 1;

               for($b = 0;$b < $NumMenu;$b++) {

                             if(pg_result($menu,$a,"id_item_filho") == pg_result($menu,$b,"id_item")) {

                   echo "<a class=\"menuItem\" href=\"\"  onclick=\"return false;\"  onmouseover=\"menuItemMouseover(event, 'Ijoao".pg_result($menu,$a,"id_item_filho")."');\">\n";

                   echo "<span class=\"menuItemText\">".pg_result($menu,$a,"descricao")."</span>\n";

                   echo "<span class=\"menuItemArrow\">&#9654;</span></a>\n";

                                   $verifica = 0;

                                   break;

                                 }

                           }

                           if($verifica == 1)

                 echo "<a class=\"menuItem\" href=\"".pg_result($menu,$a,"funcao")."\">".pg_result($menu,$a,"descricao")."</a>\n";

                         }

                   }

               echo "</div>\n";

                   break;

             }

           }

         }

         /*

         echo "<div id=\"I".pg_result($menu,0,1)."\" class=\"menu\" onmouseover=\"menuMouseover(event)\">\n";

     for($i = 0;$i < $NumMenu;$i++) {

           $URI = pg_result($menu,$i,5) == ""?"":"http://".$HTTP_SERVER_VARS["HTTP_HOST"].substr($HTTP_SERVER_VARS["PHP_SELF"],0,strrpos($HTTP_SERVER_VARS["PHP_SELF"],"/"))."/".pg_result($menu,$i,5);

       if(pg_result($menu,$i,0) != $modulo) {

         for($j = 0;$j < $NumMenu;$j++) {

           if(pg_result($menu,$i,0) == pg_result($menu,$j,1))

             echo "<a class=\"menuItem\" href=\"".$URI."\">".pg_result($menu,$i,3)."</a>\n";

         }

       }

     }

         echo "</div>\n";

         */

  } else {

    echo "Sem permissao de menu!";

  }





// emite o valor por extenso ( em moeda )

function db_extenso($valor=0, $maiusculas=false) {
    $rt = '';
    $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
"quatrilhões");
    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
"sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
"dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
"sete", "oito", "nove");
    $z=0;
    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for($i=0;$i<count($inteiro);$i++)
        for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
            $inteiro[$i] = "0".$inteiro[$i];

    $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
    for ($i=0;$i<count($inteiro);$i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
$ru) ? " e " : "").$ru;
        $t = count($inteiro)-1-$i;
        $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000")$z++; elseif ($z > 0) $z--;
        if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
//        $rt = '';
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

         if(!$maiusculas){
                          return($rt ? $rt : "zero");
         } else { /*
                         Trocando o " E " por " e ", fica muito + apresentável!
                     Rodrigo Cerqueira, rodrigobc@fte.com.br
                    */
                          if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
                          return (($rt) ? ($rt) : "Zero");
         }
}
}


function dateadd($dias,$datahoje = "?"){
if ($datahoje == "?")
{
  $datahoje = date("d") . "/" . date("m") . "/".date("Y");
}

  if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $datahoje, $sep))
  {
   $dia = $sep[1];
   $mes = $sep[2];
   $ano = $sep[3];
  }
  else
  {
    return "#Erro#";
  }

  if ($dias < 0)
  {
    $dias = $dias * -1;
    if($mes == "01" || $mes == "02" || $mes == "04" || $mes == "06" || $mes == "08" || $mes == "09" || $mes == "11"){
     for ($cont = $dias ; $cont > 0 ; $cont--)
     {
     $dia--;
      if($dia == 00)
      {
       $dia = 31;
       $mes = $mes -1;
        if($mes == 00)
        {
         $mes = 12;
         $ano = $ano - 1;
        }
      }
     }
    }

    if($mes == "05" || $mes == "07" || $mes == "10" || $mes == "12" )
    {
     for ($cont = $dias ; $cont > 0 ; $cont--)
     {
      $dia--;
      if($dia == 00)
       {
        $dia = 30;
        $mes = $mes -1;
       }
      }
    }

   if($ano % 4 == 0 && $ano%100 != 0)
   {
    if($mes == "03")
     {
      for ($cont = $dias ; $cont > 0 ; $cont--)
      {
        $dia--;
        if($dia == 00)
         {
          $dia = 29;
          $mes = $mes -1;
        }
       }
     }
   }
   else
   {
    if($mes == "03" )
    {
      for ($cont = $dias ; $cont > 0 ; $cont--)
       {
        $dia--;
        if($dia == 00)
         {
          $dia = 28;
          $mes = $mes -1;
         }
       }
     }
   }
  }
  else
  {
  $i = $dias;
  for($i = 0;$i<$dias;$i++)
  {
    if ($mes == 01 || $mes == 03 || $mes == 05 || $mes == 07 || $mes == 8 || $mes == 10 || $mes == 12)
    {
      if($mes == 12 && $dia == 31)
      {
        $mes = 01;
        $ano++;
        $dia = 00;
      }
    if($dia == 31 && $mes != 12)
    {
      $mes++;
      $dia = 00;
    }
  }
  if($mes == 04 || $mes == 06 || $mes == 09 || $mes == 11)
  {
    if($dia == 30)
    {
      $dia =  00;
      $mes++;
    }
  }

  if($mes == 02)
  {
    if($ano % 4 == 0 && $ano % 100 != 0)
    {
      if($dia == 29)
      {
        $dia = 00;
        $mes++;
      }
    }
    else
    {
      if($dia == 28)
      {
        $dia = 00;
        $mes++;
      }
    }
  }
  $dia++;
  }
  }
  if(strlen($dia) == 1){$dia = "0".$dia;}
  if(strlen($mes) == 1){$mes = "0".$mes;}
  $nova_data = $dia . "/" . $mes . "/" . $ano ;
  return $nova_data;
}

/* funcao para controlar as regras de emissao de carnes e recibos */

function db_criacarne($arretipo,$ip,$datahj,$instit,$tipomod){

  global $k47_sequencial;
  global $k47_descr;
  global $k47_obs;
  global $k47_altura;
  global $k47_largura;
  global $k47_orientacao;

  global $k47_tipoconvenio; // 1 se for arrecadacao ou 2 se for cobranca
  global $k22_cadban;       // codigo do banco no caso de ser cobranca

  $intnumexe   = 0;
  $intnumtipo  = 0;
  $intnumgeral = 0;
  $achou       = 0;

    //die("///////////".$arretipo." -- ".$ip." -- ".$datahj." -- ".$instit." -- ".$tipomod);
  $sqlexe = "  select * from cadmodcarne
                       inner join modcarnepadrao         on cadmodcarne.k47_sequencial                = modcarnepadrao.k48_cadmodcarne
                       left  join modcarnepadraocobranca on modcarnepadraocobranca.k22_modcarnepadrao = modcarnepadrao.k48_sequencial
                       inner join modcarnepadraotipo     on modcarnepadrao.k48_sequencial             = modcarnepadraotipo.k49_modcarnepadrao
                       inner join modcarneexcessao       on modcarneexcessao.k36_modcarnepadraotipo   = modcarnepadraotipo.k49_sequencial
                 where k36_ip         = '".$ip."'
                   and k49_tipo       = $arretipo
                   and k48_dataini    <='".$datahj."'
                   and k48_datafim    >='".$datahj."'
                   and k48_instit     = $instit
                   and k48_cadtipomod = $tipomod  ";
   // die("xxxxxxxxxxxxxxxxxxx ".$sqlexe);
  $rsModexe   = db_query($sqlexe);
  $intnumexe  = pg_numrows($rsModexe);
  if(isset($intnumexe) && $intnumexe > 0 ){
    db_fieldsmemory($rsModexe,0);
    //    db_msgbox("achou excessao");
    $achou = 1;
  }
  if($achou == 0){
    $sqltipo = " select * from cadmodcarne
                        inner join modcarnepadrao         on cadmodcarne.k47_sequencial                = modcarnepadrao.k48_cadmodcarne
                        left  join modcarnepadraocobranca on modcarnepadraocobranca.k22_modcarnepadrao = modcarnepadrao.k48_sequencial
                        inner join modcarnepadraotipo     on modcarnepadrao.k48_sequencial             = modcarnepadraotipo.k49_modcarnepadrao
                        left  join modcarneexcessao       on modcarneexcessao.k36_modcarnepadraotipo   = modcarnepadraotipo.k49_sequencial
           where k49_tipo = $arretipo
             and k48_dataini    <='".$datahj."'
             and k48_datafim    >='".$datahj."'
             and k48_instit     = $instit
             and k48_cadtipomod = $tipomod
             and modcarneexcessao.k36_modcarnepadraotipo is null
             ";
    //     die($sqltipo);
    $rsModtipo  = db_query($sqltipo);
    $intnumtipo = pg_numrows($rsModtipo);
    if(isset($intnumtipo) && $intnumtipo > 0){
      //        db_msgbox("achou tipo");
      db_fieldsmemory($rsModtipo,0);
      $achou = 1;
    }
  }
  if($achou == 0){
    $sqlgeral = " select * from cadmodcarne
                     inner join modcarnepadrao         on cadmodcarne.k47_sequencial                = modcarnepadrao.k48_cadmodcarne
                     left  join modcarnepadraocobranca on modcarnepadraocobranca.k22_modcarnepadrao = modcarnepadrao.k48_sequencial
                     left  join modcarnepadraotipo     on modcarnepadrao.k48_sequencial             = modcarnepadraotipo.k49_modcarnepadrao
                     left  join modcarneexcessao       on modcarneexcessao.k36_modcarnepadraotipo   = modcarnepadraotipo.k49_sequencial
            where k48_dataini    <= '".$datahj."'
              and k48_datafim    >= '".$datahj."'
              and k48_instit     = $instit
              and k48_cadtipomod = $tipomod
              and modcarnepadraotipo.k49_modcarnepadrao   is null
              and modcarneexcessao.k36_modcarnepadraotipo is null
            ";
        //die("akiiii".$sqlgeral);
    $rsModgeral  = db_query($sqlgeral);
    $intnumgeral = pg_numrows($rsModgeral);
    if($intnumgeral > 0){
      //        db_msgbox("achou padrao");
      db_fieldsmemory($rsModgeral,0);
      $achou = 1;
    }else{
      db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de carne não encontrado, contate o suporte !');
    }
  }

  //die($k47_sequencial."--".$k47_altura."--".$k47_largura."-".$k47_orientacao."-".$k22_cadban);

  unset($spdf);
  unset($pdf);

  if (isset($k47_altura) && $k47_altura != 0 && isset($k47_largura) && $k47_largura != 0 && isset($k47_orientacao) && $k47_orientacao != ""){
    $medidas = array ($k47_altura,$k47_largura);
    $spdf    = new scpdf($k47_orientacao, "mm",$medidas);
  }else{
    $spdf    = new scpdf();
  }
  $spdf->Open();
  $pdf    = new db_impcarne($spdf,$k47_sequencial);
  return $pdf;
}

function db_anofolha() {
  global $max;
  $sqlanomes = "select max(r11_anousu||lpad(r11_mesusu,2,0)) from cfpess where r11_instit = ".db_getsession("DB_instit");
  $resultanomes = db_query($sqlanomes);
  db_fieldsmemory($resultanomes, 0);
  $ano = substr($max, 0, 4);
  return $ano;
}
function db_mesfolha() {
  global $max;
  $sqlanomes = "select max(r11_anousu||lpad(r11_mesusu,2,0)) from cfpess where r11_instit = ".db_getsession("DB_instit");
  $resultanomes = db_query($sqlanomes);
  db_fieldsmemory($resultanomes, 0);
  $mes = substr($max, 4, 2);
  return $mes;
}

//coloca no tamanho e acrecenta caracteres '$qual' a esquerda
function db_sqlformatar($campo, $quant, $qual) {
  $aux = "";
  for ($i = strlen($campo); $i < $quant; $i ++)
		$aux .= $qual;
		return $aux.$campo;

}

function db_base_ativa() {
  //#00#//db_base_ativa
  //#10#//Esta funcao verifica se a variavel DB_NBASE esta setada para quando troca de base pelo info
  //#15#//db_base_ativa();
  //#20#//dbnbase=Nome da Base de Dados
  if (isset ($GLOBALS["DB_NBASE"])) {
    return $GLOBALS["DB_NBASE"];
  } else {
    return $GLOBALS["DB_BASEDADOS"];
  }
}

function UltimoDiaMes($ano,$mes){
  //verifica se é ano bisesto
  if($ano%4 == 0){
    if($ano%100 != 0){
      $fev = 29;
    }else{
      if($ano%400 == 0){
        $fev = 29;
      }else{
        $fev = 28;
      }
    }
  }else{
  $fev = 28;
  }
  $mes = $mes-1;
  $dia= Array(31,$fev,31,30,31,30,31,31,30,31,30,31);
  $ultmodia = $dia[$mes];
  return $ultmodia;
}

function db_sel_instit($instit=null,$campos=" * "){
  if($instit == null || trim($instit) == ""){
    $instit = db_getsession("DB_instit");
  }
  if(trim($campos) == ""){
    $campos = " * ";
  }
  $record_config = db_query("select ".$campos."
                            from db_config
                                 left join db_tipoinstit on db21_codtipo = db21_tipoinstit
                            where codigo = ".$instit);
  if($record_config == false){
    return false;
  }else{
    $num_rows = pg_numrows($record_config);
    if($num_rows > 0){
      $num_cols = pg_numfields($record_config);
      for($index=0; $index<$num_cols; $index++){
        $nam_campo = pg_fieldname($record_config, $index);
        global $$nam_campo;
        $$nam_campo = pg_result($record_config, 0, $nam_campo);
      }
    }
  }
  return $num_rows;
}



function db_buscaImagemBanco($cadban,$conn){
/*
 * $cadban = codigo k15_codigo da cadban
 * $conn   =  conexão
 */

  $sqlcodban = "select k15_codbco from cadban where k15_codigo = $cadban";
  $resultcadban = db_query($sqlcodban);
  $linhascadban = pg_num_rows($resultcadban);
  if($linhascadban >0){
  	//db_fieldsmemory($resultcadban,0);
	$k15_codbco = pg_result($resultcadban,0,"k15_codbco");
	$banco = str_pad($k15_codbco, 3, "0", STR_PAD_LEFT);
	// busca os dados do banco..logo etc
	$sqlBanco = "select  * from db_bancos where db90_codban = '".$banco."'";
	$resultBanco = db_query($sqlBanco);
	$linhasBanco = pg_num_rows($resultBanco);
	if($linhasBanco > 0 ){
	  //db_fieldsmemory($resultBanco,0);
	  $db90_digban = pg_result($resultBanco,0,"db90_digban");
	  $db90_abrev  = pg_result($resultBanco,0,"db90_abrev");
	  $db90_logo   = pg_result($resultBanco,0,"db90_logo");
	  // se não tiver os dados do banco na db_bancos não deve emitir o recibo.
	  if($db90_digban=="" || $db90_abrev=="" || $db90_logo==""){
	  	return false;
//	  	db_redireciona('db_erros.php?fechar=true&db_erro=Configure os dados(Digito verificador, Nome abreviado do banco e o Arquivo do logo) do Banco: '.$banco.'-'.$db90_descr.', no Cadastro de Bancos');
	  }
	  // seta os dados para o boleto passando as informações do logo
	  db_query ($conn, "begin");
	  $caminho = "tmp/".$banco.".jpg";
      pg_lo_export  ( "$db90_logo",$caminho ,$conn);
	  db_query ($conn, "commit");

	  $arr = array("numbanco"  =>$banco."-".$db90_digban,
	               "banco"     =>$db90_abrev ,
				   "imagemlogo"=>$caminho);

    return $arr;

	}else{
		// se não tiver o banco na db_bancos
		db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Banco cadastrado para o código'.$banco.' no Cadastro de Bancos'.$sqlBanco);
	}
  }
}

/***
 *
 * Funcao para montar uma string com o backtrace do PHP **SEM PARAMETROS***
 * nas chamadas de funções e métodos
 *
 */
function db_debug_backtrace() {
  $aBacktrace = debug_backtrace();
  $iCount     = count($aBacktrace);
  $sBacktrace = "";

  for($i=1; $i<$iCount; $i++) {
    $sBacktrace .=
      sprintf(" * #%s %s(%s) called at [%s:%d]\n",
        $i,
        $aBacktrace[$i]["function"],
        ($aBacktrace[$i]["args"]?"...":""),
        $aBacktrace[$i]["file"],
        $aBacktrace[$i]["line"]);
  }

  if($sBacktrace <> "") {
    $sBacktrace = "\n/***\n * e-cidadeonline backtrace\n{$sBacktrace} */\n";
  }

  return $sBacktrace;
}

/***
 *
 * Funcao wrapper para executar a PG_QUERY (PostgreSQL)
 *
 *
 */
function db_query($param1, $param2=null, $param3="SQL"){

  $sBackTrace = db_debug_backtrace();

  if($param2==null){
    $dbsql    = str_replace($param1, "#SQL", $sBackTrace) . $param1;
    $dbresult = pg_query($dbsql);
  }else{
    $dbsql    = str_replace($param2, "#SQL", $sBackTrace) . $param2;
    $dbresult = pg_query($param1, $dbsql);
  }

  db_tracelog($param3, $dbsql,(!$dbresult?true:false));

  return $dbresult;
}
function db_tracelog($descricao, $sql, $lErro){
//echo $sql.'<br/>';
  if(db_getsession("DB_traceLog", false) != null){
    $data = date("[d/m/Y  H:i:s]");
    $sql = str_replace("\n","",$sql);
    if ($lErro) {
      $sMensagem = "\"<b> ERRO :&nbsp;&nbsp;</b>\",\"<b style='color:#FF6666'>".addslashes($sql)."</b>\", \"<b>$data</b>\"";
    } else {
      $sMensagem = "\"<b>".addSlashes($descricao).":&nbsp;&nbsp;</b>\", \"".addslashes($sql)."\", \"<b>$data</b>\"";
    }
    echo "<script>top.js_enviaTraceLog($sMensagem)</script>";
    flush();
  }
}

function db_tracelogsaida($tipo, $descricao, $sql) {
  switch($tipo) {
    case "file": break;
    case "db": break;
    default: break;
  }
}
//////////////////////////////////////

/*************************************/


function validaUsuarioLogado() {

	if ( !session_is_registered('DB_acesso') ) {
	  die ('<table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
	          <tr height="220">
	            <td align="center">
	              <img src="imagens/atencao.gif"><br>
	               Usuário não logado no sistema!
	            </td>
	          </tr>
	        </table>');
	}
}

function db_translate($db_transforma = null,$expresAdicional = "",$stringAdicional = ""){

  // Array com expressões regulares
  $arr_regexp = Array(
  "/º/",
  "/ç/",
  "/Ç/",
  "/á|à|ã|â|ä/",
  "/Á|À|Ã|Â|Ä/",
  "/é|è|ê|ë/",
  "/É|È|Ê|Ë|&/",
  "/í|ì|î|ï/",
  "/Í|Ì|Î|Ï/",
  "/ó|ò|õ|ô|ö/",
  "/Ó|Ò|Õ|Ô|Ö/",
  "/ú|ù|û|ü/",
  "/Ú|Ù|Û|Ü/",
  "/'|;|:/",
  "/$expresAdicional/"
  );
  // Array com substitutos
  $arr_replac = Array("o","c","C","a","A","e","E","i","I","o","O","u","U"," ","$stringAdicional");

  // $arr_regexp[0] substituído por $arr_replac[0], ou seja, ç por c
  // $arr_regexp[1] substituído por $arr_replac[1], ou seja, Ç por C
  // $arr_regexp[2] substituído por $arr_replac[2], ou seja, á ou à ou ã ou â ou ä por a
  // $arr_regexp[3] substituído por $arr_replac[3], ou seja, Á ou À ou Ã ou Â ou Ä por A
  // $arr_regexp[n] substituído por $arr_replac[n]
  // ...
  $db_transforma = preg_replace($arr_regexp,$arr_replac,$db_transforma);

  return $db_transforma;

}

function db_strtotime($strData){

  if(empty($strData)) {
    return $strData;
  }

  if (substr(phpversion(),0,1) == 4) {
    return strtotime($strData,date('h:i'));
  } else if (substr(phpversion(),0,1) >= 5) {
    return(strtotime($strData));
  }
}


?>

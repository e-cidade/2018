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
if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?erroscripts=3'</script>";
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
$db_verifica_ip = db_verifica_ip();
mens_help();
$dblink="digitadae.php";
db_logs("","",0,"Digita inscricao do Contribuinte.");
postmemory($HTTP_POST_VARS);
$clquery = new cl_query;
if(isset($nova)){
   $clquery->sql_query("issbase"," q02_numcgm ","","q02_inscr  = $inscricaow");
   $clquery->sql_record($clquery->sql);
   $num=$clquery->numrows;
   if($num!=0){
     db_fieldsmemory($clquery->result,0);
     $clquery->sql_query("cgm","z01_nome, z01_numcgm",""," z01_numcgm = $q02_numcgm");
     $clquery->sql_record($clquery->sql);
     db_fieldsmemory($clquery->result,0);
   }else{
     redireciona("digitadae.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
   }  

}else{
  if(isset($first)){
    $inscricaow!=""?"":$inscricaow = 0 ;
    if ( !empty($cgc) ){
      $cgccpf = $cgc;
    }else{
      if (!empty($cpf) ){
        $cgccpf = $cpf;
      }else{
        $cgccpf = "";
      }
    }
    $cgccpf = str_replace(".","",$cgccpf);
    $cgccpf = str_replace("/","",$cgccpf);
    $cgccpf = str_replace("-","",$cgccpf);  
    $sql_exe = "select * from issbase inner join cgm on q02_numcgm = z01_numcgm where q02_inscr = $inscricaow";
    if($db_verifica_ip == "0" ) {
      $sql_exe = "select * from issbase inner join cgm on q02_numcgm = z01_numcgm where z01_cgccpf = '$cgccpf' and q02_inscr  = $inscricaow";
    }
    $result = db_query($sql_exe);
    if(pg_numrows($result) != 0){
      db_fieldsmemory($result,0);
    }else{
      redireciona("digitadae.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
    }  
    if(!isset($DB_LOGADO)){
      $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricaow)";
      $result = db_query($sql);
      if(pg_numrows($result)==0){
        db_redireciona("digitadae.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
        exit;
      }
      $result = pg_result($result,0,0);
      if($result=="0"){
        db_redireciona("digitadae.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
        exit;
      }
    } 
  }  
}
$result = db_query("select * from db_dae where w04_inscr = $inscricaow");
$ano = date("Y");
if(pg_numrows($result) == 0){
  $result = db_query("select nextval('seq_db_dae')");   
  $codigo = pg_result($result,0,0);
  db_query("insert into db_dae values($codigo,$inscricaow,'f','$ano')");
  exit;
}else{
  $codigo = pg_result($result,0,'w04_codigo');  
}
if(isset($salvaender)){
  $result = db_query("select * from db_daeend where w05_codigo = $codigo");
  if(pg_numrows($result) == 0){  
    $result = db_query("insert into db_daeend values($codigo,'$ruas',$numero,'$complemento','$bairro')");  
  }else{
    $result = db_query("update db_daeend set w05_codigo = $codigo, w05_rua = '$ruas', w05_numero = $numero, w05_compl = '$complemento', w05_bairro = '$bairro' ");  
  }
  db_redireciona("enderecodae.php?inscricaow=$inscricaow");
}elseif(isset($salvasocios)){
  $result = db_query("select * from db_daesocios where w06_codigo = $codigo");
  if(pg_numrows($result) != 0){  
    $result = db_query("delete from db_daesocios where w06_codigo = $codigo");  
  }
  //echo "<script>alert($tamanho)</script>";
  //exit;
  //db_postmemory($HTTP_POST_VARS,2);
  //exit;
  $item = 0;
  for($i=0;$i<($tamanho);$i++){
    $valores = "valores".($i + 1);
    $input = $$valores;
    if($input != ""){
      $matriz = split("#",$input);
      $cgccpf   =   $matriz[0];
      $rg       =   $matriz[1];
      $nome     =   $matriz[2];
      $ender    =   $matriz[3];
      $numero   =   $matriz[4];
      $compl    =   $matriz[5];
      $bairro   =   $matriz[6];
      $cep      =   $matriz[7];
      $uf       =   $matriz[8];
      $percentual = $matriz[9];
      if(isset($cgccpf) && $cgccpf != ""){
        $cgccpf = str_replace('.','',$cgccpf);
        $cgccpf = str_replace('-','',$cgccpf);
        $cgccpf = str_replace('/','',$cgccpf);
        $cgccpf = $cgccpf;
      }  
      if(isset($cep)){
        $cep = str_replace('-','',$cep);
      }  
      $result = db_query("insert into db_daesocios values($codigo,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual)");  
      $item += 1;
    }
  }  
  if(pg_numrows($result) == 0){  
    $result = db_query("insert into db_daesocios values($codigo,'$cgccpf',$rg,'$nome','$ender',$numero,'$compl','$bairro',$cep,'$uf',$percentual)");  
  }else{
    $result = db_query("update db_daesocios set w06_codigo = $codigo,w06_cgccpf = '$cgccpf', w06_rg = $rg, w06_nome = '$nome',w06_ender = '$ender', w06_numero = $numero, w06_compl = '$compl', w06_bairro = '$bairro', w06_cep = $cep, w06_uf = '$uf', w06_percent = $percentual where w06_codigo = $codigo");  
  }
  db_redireciona("sociosdae.php?".base64_encode('inscricaow=$inscricaow&primeira=1'));
}elseif(isset($salvavalores)){
  $result = db_query("select * from db_daevalores where w07_codigo = $codigo");
  if(pg_numrows($result) != 0){  
    $result = db_query("delete from db_daevalores where w07_codigo = $codigo");  
  }
  $item = 0;
  for($i=0;$i<($tamanho);$i++){
    $valores = "valores".($i + 1);
    $input = $$valores;
    if($input != ""){
    $matriz = split("#",$input);
      $mes = $matriz[0];
      $valor = $matriz[1];
      $aliquota = $matriz[2];
      $imposto = $matriz[3];
      $data = $matriz[4];
      $result = db_query("insert into db_daevalores values($codigo,$item,'$mes',$valor,$aliquota,$imposto,'$data')");  
      $item += 1;
    }
  }  
  db_redireciona("valoresdae.php?".base64_encode('inscricaow=$inscricaow&primeira=1'));
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js">
</script>
<script>
js_verificapagina("digitadae.php,opcoesdae.php,enderecodae.php,sociosdae.php,valoresdae.php,enviadae.php");
function js_trocaframe(div,obj){
  var id1 = new Array('endereco','socios','valores','enviadae');
  var id2 = new Array('endereco1','socios1','valores1','enviadae1');
  for(i = 0; i < id1.length; i++){
    document.getElementById(id1[i]).style.visibility = 'hidden';
    document.getElementById(id1[i]).style.top = '400';
    document.getElementById(id1[i]).style.left = '0';
    document.getElementById(id1[i]).style.position = 'absolute';
    document.getElementById(id2[i]).bgColor = '<?=$w01_corfundomenuativo?>';
  }  
  document.getElementById(div).style.visibility = 'visible';
  document.getElementById(div).style.position = 'relative';
  document.getElementById(div).style.top = '0';
  document.getElementById(div).style.left = '0';
  document.getElementById(div+'1').bgColor = '<?=$w01_corfundomenu?>';
  document.getElementById(div+'1').borderBottom = 'none';
}
</script>
<style type="text/css">
<?db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesissqn.php">
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%" align="left" valign="top"><img src="imagens/cabecalho.jpg">
	  </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table class="bordas" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td nowrap width="90%">
            &nbsp;<a href="index.php" class="links">Principal &gt;</a>
          </td>
	  <td align="center" width="10%" onClick="MM_showHideLayers('<?=$nome_help?>','',(document.getElementById('<?=$nome_help?>').style.visibility == 'visible'?'hide':'show'));">
	    <a href="#" class="links">Ajuda</a>
          </td>
       </tr>
     </table>  
   </td>
  </tr>
  <tr>
    <td align="left" valign="top">
      <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="15%" align="left" valign="top"> 
          <?
	    db_montamenus(); 
          ?>
	  </td>
          <td width="85%" align="left" valign="top" id="coluna"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <td>
		  <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-bottom: 0px">
		    <tr> 
		      <td width="25%" align="center">
			<table id="endereco1" width="100%" bgcolor="<?=$w01_corfundomenu?>" style=" border-bottom: none" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('endereco',this)">
			  <tr> 
			    <td width="25%" align="center">
			      <strong>Endereço</strong>
			    </td>
			  </tr>
			</table>  
		      </td>
		      <td width="25%" align="center">
			<table id="socios1" width="100%" bgcolor="<?=$w01_corfundomenuativo?>" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('socios',this)">
			  <tr> 
			    <td width="25%" align="center">
			      <strong>Sócios</strong>
			    </td>
			  </tr>
			</table>  
		      </td>
		      <td width="25%" align="center">
			<table id="valores1" width="100%" bgcolor="<?=$w01_corfundomenuativo?>" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('valores',this)">
			  <tr> 
			    <td width="25%" align="center">
			      <strong>Valores</strong>
			    </td>
			  </tr>
			</table>  
		      </td>
		      <td width="25%" align="center">
			<table id="enviadae1" width="100%" bgcolor="<?=$w01_corfundomenuativo?>" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('enviadae',this)">
			  <tr> 
			    <td width="25%" align="center">
			      <strong>Envia DAI</strong>
			    </td>
			  </tr>
			</table>  
		      </td>
		    </tr>
		    <tr>
		      <td height="350" align="top" valign="top" colspan="4">
			<div id="endereco" style="position:relative; z-index:11; visibility: visible; width: 90%; height: 350px">
			  <iframe frameborder="0" style=" border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="no" src="enderecodae.php?inscricaow=<?=$inscricaow?>" name="endereco">
			  </iframe>
			</div>
			<div id="socios" style="position:absolute; z-index:10; visibility: hidden; width: 90%; height: 350px">
			  <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="no"  src="sociosdae.php?inscricaow=<?=$inscricaow?>&primeira=1" name="socios" >
			  </iframe>
			</div>
			<div id="valores" style="position:absolute; z-index:9; visibility: hidden; width: 90%; height: 350px">
			  <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="yes" src="valoresdae.php?inscricaow=<?=$inscricaow?>&primeira=1" name="valores">
			  </iframe>
			</div>
			<div id="enviadae" style="position:absolute; z-index:8; visibility: hidden; width: 90%; height: 350px">
			  <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="no" src="enviadae.php?inscricaow=<?=$inscricaow?>" name="enviadae">
			  </iframe>
			</div>
		      </td>
		    </tr>
		  </table>
	        </td>
	      </tr>
	    </table>  
	  </td>    
	</tr>
      </table>
    </td>  
  </tr>
</table>
</center>
</form>
</body>
</html>
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
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitadae.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
    echo"<script>location.href='opcoesdae.php?nova=1'</script>";
}
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
     $clquery->sql_query("cgm","z01_nome, z01_numcgm",""," z01_numcgm = $q02_numcgm"); // select z01_nome, z01_numcgm from cgm where z01_numcgmz = q02_numcgm     
     $clquery->sql_record($clquery->sql);  // conta as linhas no banco
     db_fieldsmemory($clquery->result,0);  // cria variaveis (z01_nome, z01_numcgm) apartir dos campos
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
    if(!isset($DB_LOGADO)  && $m_publico !='t'){
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
  $result = db_query("select * from db_dae where w04_inscr = $inscricaow");
  db_fieldsmemory($result,0);// transforma o campo em variavel...todos?
  if($w04_enviado == 't'){ // se enviado = t , ja foi enviada
    echo "<script>var confirma = confirm('DAI já enviada, deseja reemitir o relatório?');
            if(confirma == true){
              window.open('daerelatorio.php?codigo=$w04_codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            }
          </script>  ";
    db_redireciona("digitadae.php");
    exit;
  }else{  // se DAI não foi enviada
    $codigo = $w04_codigo;  // codigo da DAI
  }  
}else{
  db_fieldsmemory($result,0);// transforma campos da tabela db_dae
  if($w04_enviado == 't'){  //################### não entendi porque denovo ###################
    echo "<script>var confirma = confirm('DAI já enviada, deseja reemitir o relatório?');
            if(confirma == true){
              window.open('daerelatorio.php?codigo=$w04_codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            }
          </script>  ";
    db_redireciona("digitadae.php");
    exit;
  }else{
    $codigo = $w04_codigo;  
  }  
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
<?
mens_div();
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesissqn.php">
<center>
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-bottom: 0px">
  <tr>
    <td colspan="5" align="center" style="font-family:arial; font-size:12px">
      <b><?=$q02_inscr." - ".$z01_nome?></b>
    </td>
  </tr>
  <tr>
    <td width="20%" align="center"> 
      <table id="endereco1" width="100%" bgcolor="<?=$w01_corfundomenu?>" style=" border-bottom: none" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('endereco',this)">
        <tr>
          <td width="25%" align="center">
            <strong>Endereço</strong>
          </td>
        </tr>
      </table>
    </td>
    <td width="20%" align="center">
      <table id="socios1" width="100%" bgcolor="<?=$w01_corfundomenuativo?>" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('socios',this)">
        <tr>
          <td width="25%" align="center">
            <strong>Sócios</strong>
          </td>
        </tr>
      </table>
    </td>
    <td width="20%" align="center">
      <table id="valores1" width="100%" bgcolor="<?=$w01_corfundomenuativo?>" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('valores',this)">
        <tr>
          <td width="25%" align="center">
            <strong>Valores</strong>
          </td>
        </tr>
      </table>
    </td>
   
   <td width="20%" align="center">
     <table id="valores2" width="100%" bgcolor="<?=$w01_corfundomenuativo?>" border="0" cellspacing="0" cellpadding="0" onClick="js_trocaframe('valores22',this)">
       <tr>
          <td width="25%" align="center">
              <strong>Valores 2</strong>
          </td>
       </tr>
     </table>
   </td>
   
   <td width="20%" align="center">
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
    <td height="150" align="top" valign="top" colspan="5">
      <div id="endereco" style="position:relative; z-index:11; visibility: visible; width: 100%; height: 350px">
        <iframe frameborder="0" style=" border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="no" src="enderecodae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" name="endereco">
        </iframe>
      </div>
      <div id="socios" style="position:absolute; z-index:10; visibility: hidden; width: 100%; height: 350px">
        <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="auto"  src="sociosdae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" name="socios" >
        </iframe>
      </div>
      <div id="valores" style="position:absolute; z-index:9; visibility: hidden; width: 100%; height: 350px">
        <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="auto" src="valoresdae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" name="valores">
        </iframe>
      </div>
      <div id="valores22" style="position:absolute; z-index:12; visibility: hidden; width: 100%; height: 350px">
        <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?><?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="auto" src="valores2.php " name="valores22">
        </iframe>
     </div>

      
      <div id="enviadae" style="position:absolute; z-index:8; visibility: hidden; width: 100%; height: 350px">
        <iframe frameborder="0" style="border: 1px <?=$w01_estilobotao?> <?=$w01_corbordabotao?>;border-top: 0px; width:100%; height:100%" scrolling="no" src="enviadae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" name="enviadae">
        </iframe>
      </div>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
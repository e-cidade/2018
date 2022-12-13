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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
db_mensagem("opcoesalvara_cab","opcoesalvara_rod");
mens_help();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<STYLE>
<!--
   .link color: white;
   { text-decoration: none; }

a:hover { color: black;
   text-decoration: none;
 }

.texto
 {
  font-family: courier new;
  font-size: 13px;
  color: #000000;
  text-decoration: none;
 }
</STYLE>
<script type="text/javascript" src="javascript/db_script.js">
</script>
<script>
js_verificapagina("certidaonome.php,certidaoinscr.php,certidaomatric.php,certidaoautentica.php");
function js_erromatric(matric){
  alert('Matrícula '+matric+' inválida');
}
function js_errocgc(cgc){
  alert('Número CNPJ/CPF '+cgc+' inválido');
}
function js_erroinscr(inscr){
  alert('Inscrição '+inscr+' inválida');
}
</script>
<title>Emissão de Certid&atilde;o</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="itbi.css" type="text/css">
<style type="text/css">
.unnamed1 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; line-height: normal; font-weight: bold; color: #996633; text-decoration: none}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  <? mens_OnHelp() ?> >
<?
mens_div();
include("processando.php");
$db_verifica_ip = db_verifica_ip();
$ip = getenv("REMOTE_ADDR");
  if(isset($cnpj) || isset($cpf)){
    if ( empty($HTTP_POST_VARS["cnpj"]) ){
      if ( empty($HTTP_POST_VARS["cpf"]) ){
        if(isset($matric)){
          echo "<script>js_erromatric('$matric');</script>";
          echo "<script>history.back();</script>";
          exit;
        }elseif(isset($inscr)){
          echo "<script>js_erroinscr('$inscr');</script>";
          echo "<script>history.back();</script>";
          exit;
        }else{
          echo "<script>js_errocgc('$cgccpf');</script>";
          echo "<script>history.back();</script>";
          exit;
        }
      }  
    }else{
      $cgccpf = $cnpj;
    }
  }else{
    $cgccpf = @$cnpj;
    $cgccpf = str_replace('.','',$cgccpf);
    $cgccpf = str_replace('/','',$cgccpf);
    $cgccpf = str_replace('-','',$cgccpf);
  }
  if($cgccpf == "00000000000000" || $cgccpf == "00000000000") {
    if(isset($matric)){
      echo "<script>js_erromatric('$matric');</script>";
      echo "<script>history.back();</script>";
      exit;
    }elseif(isset($inscr)){
      echo "<script>js_erroinscr('$inscr');</script>";
      echo "<script>history.back();</script>";
      exit;
    }else{
      echo "<script>js_errocgc('$cgccpf');</script>";
      echo "<script>history.back();</script>";
      exit;
    }
  exit;
  }
  $result = db_query("select z01_numcgm from cgm where z01_cgccpf = '".@$cgccpf."' limit 1");
  if(pg_num_rows(@$result) == 0){
    if(isset($matric)){
      echo "<script>js_erromatric('$matric');</script>";
      echo "<script>history.back();</script>";
      exit;
    }elseif(isset($inscr)){
      echo "<script>js_erroinscr('$inscr');</script>";
      echo "<script>history.back();</script>";
      exit;
    }else{
      echo "<script>js_errocgc('$cgccpf');</script>";
      echo "<script>history.back();</script>";
      exit;
    }
  }
  db_fieldsmemory($result,0);
  $numcgm = pg_result($result,0,'z01_numcgm');
//////////////////////////////////////////////////////////
if(isset($cgccpf) && !isset($matric)){
  $where = "where k00_numcgm = $z01_numcgm";
  $tipodados = "nome";
  $acesso = $cgccpf;
  $result = db_query("select cgm.* from cgm where z01_numcgm = '$numcgm' limit 1");
  if($result!=false)
    db_fieldsmemory($result,0);

}elseif(isset($matric)){
  $where = "inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre where k00_matric = $matric ";
  $matric = db_sqlformatar($matric,8,' ');
  $acesso = $matric;
  $result = db_query("select * from proprietario where j01_matric = '$matric'");
  if(pg_num_rows($result)==0){
    echo "<script>js_erromatric('$matric');</script>";
    echo "<script>history.back();</script>";
    exit;
  }else{
    $tipodados = "matric";
    $acesso = $matric;
    $result = db_query("select * from proprietario where j01_matric = '$matric'");
    db_fieldsmemory($result,0);
    $result = db_query("select * from cgm inner join iptubase on j01_matric = '$matric'");
    db_fieldsmemory($result,0);
  }
}elseif(isset($inscr)){
  $where = "inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre 
        where k00_inscr = $inscr ";
  $inscr = db_sqlformatar($inscr,6,' ');
  $result = db_query("select * from cgm inner join issbase on q02_inscr = '$inscr'");
  if(pg_num_rows($result)==0){
    echo "<script>js_erroinscr('$inscr');</script>";
    echo "<script>history.back();</script>";
    exit;
  }else{
    $tipodados = "inscr";
    $acesso = $inscr;
    db_fieldsmemory($result,0,0);
  }
}else{
  redireciona("gerador.php?".base64_encode('verifica='.@$verifica));
}
$dblink = "certidao.php";
$sql = "select k00_dtvenc
        from arrecad 
          $where
          and k00_dtvenc < '".date("Y-m-d",db_getsession("DB_datausu"))."' limit 1";
$result = db_query($sql);
if ( pg_numrows($result) > 0 ) {
  $tipo = 0;
}else{
  $sql = "select k00_dtvenc 
          from arrecad 
          $where";
  $result = db_query($sql);
  if ( pg_numrows($result) > 0 ) {
    $tipo = 1;
  }else{
    $tipo = 2;
  }
  if(($result!=false) && (pg_numrows($result)!=0)){
    db_fieldsmemory($result,0,0);
  }
}
if($tipo==0){
  include("certidao_positiva.php");
}else if($tipo==1){
  include("certidao_posneg.php");
}else{
  include("certidao_negativa.php");
}
?>

</body>
</html>
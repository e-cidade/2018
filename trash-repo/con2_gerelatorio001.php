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
$erro = false;
if(@$consul==true){
  $resu=pg_exec("select * from db_gerador where codger = $codigo");
  $sql=pg_result($resu,0,"sqlger");
}
if(isset($HTTP_POST_VARS["excluir"])){
  $codigo =  $HTTP_POST_VARS["codigo"];
  pg_exec("delete from db_gerador where codger='$codigo'");
  pg_exec("delete from db_gerpref where codger='$codigo'");
 $anome=$nome;
 $codigo="";
 $nome="";
 $sql="";
 $titulo="";
 $finalidade=""; 
 $ex=true; 
 echo "<script>";    
 echo "location.href=\"con2_gerelatorio001.php\";";
 echo "</script>";    
}
if(@$HTTP_POST_VARS["seta2"]==true){    
  $fsql = @pg_exec(str_replace('\\','',$sql));
  if($fsql==false){
     $erro=true;             
     $seta2=""; 
  }else{
    $seta2="";   
    $seta="";   
	
	//usado para evitar erros de codificação
	$sql = urlencode($sql);
     echo "<script>";    
  	 echo "window.open('con2_gerelatorio002.php?sql=".base64_encode($sql)."&nome=".$nome."&titulo=".$titulo."&finalidade=".$finalidade."' ,'Relatório','toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');";
     echo "</script>";    
  }
}

if(@$HTTP_POST_VARS["seta"]==true){    

  $codigo =  $HTTP_POST_VARS["codigo"];
  $nome =  $HTTP_POST_VARS["nome"];
  $sql =  $HTTP_POST_VARS["sql"];
  $titulo = $HTTP_POST_VARS["titulo"];
  $finalidade = $HTTP_POST_VARS["finalidade"];
  $fsql = @pg_exec(str_replace('\\','',$sql." limit 1"));
  if($codigo!=""){
    $conf=pg_exec("select * from db_gerador where codger !=$codigo");
  }else{
     $conf=pg_exec("select * from db_gerador ");
  }
  $num=pg_numrows($conf);
  for($i=0;$i < $num; $i++ ){
    $result=pg_result($conf,$i,1);
    if($result==$nome){
      $repete=true;
    } 
  }   
  if(!@$repete==true){
    if($fsql==false){
      $erro=true;
    }else{
      echo "<script>";    
      echo "location.href=\"con2_gerelatorio003.php?libera=".$libera."&codigo=".$codigo."&sql=".base64_encode($sql)."&nome=".$nome."&titulo=".$titulo."&finalidade=".$finalidade."\";";
      echo "</script>";    
      $seta="";   
      $seta2="";   
    }
  } 
}
db_postmemory($HTTP_POST_VARS);
?>  


<script>
  function testar2(){
     document.form1.seta2.value=true; 
     document.form1.submit();
  }
  function testar(){
     document.form1.seta2.value=''; 
    var sql=document.form1.sql.value;
    var nome=document.form1.nome.value;
    var titulo=document.form1.titulo.value;
    var finalidade=document.form1.finalidade.value;
    var avisa="";
      if(sql.indexOf("limit")!=-1){
        alert("O limite de linhas será escolhidos na próxima página!");
      }else{
         if(sql==""){
           avisa+="Sql\n";
         }
         if(nome==""){
           avisa+="Nome\n";
         }
         if(titulo==""){
           avisa+="Titulo\n";
         }
         if(finalidade==""){
           avisa+="Finalidade\n";
         }
         if(avisa!=""){
           alert("Favor preencher:\n"+avisa);
         }else{
           document.form1.seta.value=true; 
           document.form1.submit();
         }   
      }     
  }
function consulta(){
  location.href="con2_verrelatorio.php";
}
</script>

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
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
	<form name="form1" method="post" action="con2_gerelatorio001.php">
	<input name="codigo" type="hidden" value="<?=@$codigo?>">
	<input name="seta" type="hidden" value="<?=@$seta?>">
	<input name="seta2" type="hidden" value="<?=@$seta2?>">
	<input name="libera" type="hidden" value="<?=@$libera?>">

        <table width="62%" border="0" cellspacing="0">
          <tr> 
	    <td  width="24%">Nome:</td>
            <td width="76%"><input name="nome" type="text" id="nome" value="<?=@$nome?>" size="60" maxlength="60"></td>
            <td><input type="button" name="consultar" onClick="consulta()" value="Consultar Relatórios"></td>
          </tr>
          <tr> 
            <td>T&iacute;tulo:</td>
            <td><input name="titulo" type="text" id="titulo" value="<?=@$titulo?>" size="60" maxlength="60"></td>
          </tr>
          <tr>
            <td valign="top">Finalidade:</td>
            <td><input name="finalidade" type="text" id="finalidade" value="<?=@$finalidade?>" size="80" maxlength="200"></td>
          </tr>
          <tr> 
            <td valign="top">Sql:</td>
            <td><textarea name="sql" cols="80" rows="20" id="sql" value="<?=@$sql?>"><?=@$sql?></textarea></td>
          </tr>
          <tr align="center"> 
            <td colspan="5">
              <input type="button" name="gerar" onClick="testar2()" value="Gerar relatório">
          <? 
             if(@$libera!=true){
	      echo "<input type=\"button\" name=\"personalizar\"  value=\"Personalizar Relatório\" onclick=\"testar()\">";
             }   
            if(@$libera==true){
	      echo "<input type=\"button\" name=\"alterar\"  value=\"Alterar\" onclick=\"testar()\">";
	      echo "<input type=\"submit\" name=\"excluir\"  value=\"Excluir\">";
            }
	  ?>
	    </td>
          </tr>
        </table>
     </form>
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
     if(@$repete==true){
          echo "<script>";    
          echo "alert(\"O relatório ".$nome." ja existe\");";
          echo "</script>";    
     }
     if(@$ex==true){
          echo "<script>";    
          echo "alert(\"O relatório ".$anome." foi excluído\");";
          echo "</script>";    
     }
     if(@$erro==true){
          echo "<script>";    
          echo "alert(\"Verifique os parâmetros digitados\");";
          echo "document.form1.sql.focus();";
          echo "</script>";    

     }    
$VisualizaRelatorio = new janela("VisualizaRelatorio","");
$VisualizaRelatorio->posX=1;
$VisualizaRelatorio->posY=20;
$VisualizaRelatorio->largura=765;
$VisualizaRelatorio->altura=420;
$VisualizaRelatorio->titulo="Visualização de Relatórios";
$VisualizaRelatorio->iniciarVisivel = false;
$VisualizaRelatorio->mostrar();
?>
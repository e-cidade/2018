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

// funcao
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?

  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  // Tabelas

  $sql = "select nomearq as nometab
          from db_sysarquivo
	  where codarq = $codarq";
  $resulta = db_query($sql);

  db_fieldsmemory($resulta,0);
 
  $nometab = strtolower($nometab);
  $qr = "where nomearq = '$nometab'";
  $sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod, a.rotulo
                     from db_sysmodulo m
                     inner join db_sysarqmod am
                     on am.codmod = m.codmod
                     inner join db_sysarquivo a
                     on a.codarq = am.codarq
                     $qr
                     order by codmod";
  $result = db_query($sql);
  $numrows = pg_numrows($result);
  $RecordsetTabMod = $result;
  if($numrows == 0) {
    echo "Não foi encontrada nenhuma tabela com o argumento $nometab";
    exit;
  } else {
    
    $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
    $arquivo = $root."/"."func_".trim($nometab).".php";
    if(file_exists($arquivo) && !is_writable($arquivo)){
     ?>
     <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "func_<?=$nometab?>"</h6></td></tr></table>
     </body>
     </html>
     <?
     exit;
    } 

    //$arquivo = "/tmp/"."func_".trim($nometab).".php";
    umask(74);
    $fd = fopen($arquivo,"w");
    fputs($fd,"<?\n");
    for($i = 0;$i < $numrows;$i++) {
	  fputs($fd,'require("libs/db_stdlib.php");'."\n");
	  fputs($fd,'require("libs/db_conecta.php");'."\n");
	  fputs($fd,'include("libs/db_sessoes.php");'."\n");
	  fputs($fd,'include("libs/db_usuariosonline.php");'."\n");
	  fputs($fd,'include("dbforms/db_funcoes.php");'."\n");
      fputs($fd,'include("classes/db_'.trim(pg_result($result,$i,'nomearq')).'_classe.php");'."\n");
      fputs($fd,'db_postmemory($HTTP_POST_VARS);'."\n");
      fputs($fd,'parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);'."\n");
      fputs($fd,'$cl'.trim(pg_result($result,$i,'nomearq')).' = new cl_'.trim(pg_result($result,$i,'nomearq')).';'."\n");
	  $varpk = ""; 
      $pk = db_query("select a.nomearq,c.nomecam,c.tamanho,c.conteudo,p.sequen,p.camiden,d.nomecam as campoca, d.tamanho as catamanho
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                            inner join db_syscampo d   on d.codcam = p.camiden
                       where a.codarq = ".pg_result($result,$i,"codarq")."
		       order by p.sequen");
      $Npk = pg_numrows($pk);
      if(pg_numrows($pk) > 0) {
        for($p = 0;$p < $Npk;$p++) {
           fputs($fd,'$cl'.trim(pg_result($result,$i,'nomearq')).'->rotulo->label("'.trim(pg_result($pk,$p,'nomecam')).'");'."\n");
        } 
        if(pg_result($pk,0,'camiden')!=0) {
           fputs($fd,'$cl'.trim(pg_result($result,$i,'nomearq')).'->rotulo->label("'.trim(pg_result($pk,0,'campoca')).'");'."\n");
        } 
      }
      fputs($fd,"?>\n");
      fputs($fd,"<html>\n");
      fputs($fd,"<head>\n");
      fputs($fd,"  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>\n");
      fputs($fd,"  <link href='estilos.css' rel='stylesheet' type='text/css'>\n");
      fputs($fd,"  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>\n");
      fputs($fd,"</head>\n");

      fputs($fd,"<body>\n");
      fputs($fd,"  <form name=\"form2\" method=\"post\" action=\"\" class=\"container\">\n");
      fputs($fd,"    <fieldset>\n");
      fputs($fd,"      <legend>Dados para Pesquisa</legend>\n");
      fputs($fd,"      <table width=\"35%\" border=\"0\" align=\"center\" cellspacing=\"3\" class=\"form-container\">\n");

      if (pg_numrows($pk) > 0) {

        $Npk = pg_numrows($pk);
        
        for ($p = 0;$p < $Npk;$p++) {

          if ( strpos(pg_result($pk,$p,'nomecam'),'anousu') == 0 ) {

            fputs($fd,"        <tr>\n");
            fputs($fd,'          <td><label><?=$L'.trim(pg_result($pk,$p,'nomecam')).'?></label></td>' . "\n");
            fputs($fd,'          <td><? db_input("'.trim(pg_result($pk,$p,'nomecam')).'",'.trim(pg_result($pk,$p,'tamanho')).',$I'.trim(pg_result($pk,$p,'nomecam')).',true,"text",4,"","chave_'.trim(pg_result($pk,$p,'nomecam')).'"); ?></td>'."\n");
            fputs($fd,"        </tr>\n");
          }  
        }

        if ( pg_result($pk,0,'camiden') != 0 ) {

          fputs($fd,"        <tr>\n");
          fputs($fd,'          <td><label><?=$L'.trim(pg_result($pk,0,'campoca')).'?></label></td>' . "\n");
          fputs($fd,'          <td><? db_input("'.trim(pg_result($pk,0,'campoca')).'",'.trim(pg_result($pk,0,'tamanho')).',$I'.trim(pg_result($pk,0,'campoca')).',true,"text",4,"","chave_'.trim(pg_result($pk,0,'campoca')).'");?></td>'."\n");
          fputs($fd,"        </tr>\n");
        } 
      }
      fputs($fd,"      </table>\n");
      fputs($fd,"    </fieldset>\n");

      fputs($fd,"    <input name=\"pesquisar\" type=\"submit\" id=\"pesquisar2\" value=\"Pesquisar\">\n");
      fputs($fd,"    <input name=\"limpar\" type=\"reset\" id=\"limpar\" value=\"Limpar\" >\n");
      fputs($fd,"    <input name=\"Fechar\" type=\"button\" id=\"fechar\" value=\"Fechar\" onClick=\"parent.db_iframe_".trim(pg_result($result,$i,'nomearq')).".hide();\">\n");

      fputs($fd,"  </form>\n");

      fputs($fd,'      <?'."\n");
      fputs($fd,'      if(!isset($pesquisa_chave)){'."\n");
      fputs($fd,'        if(isset($campos)==false){'."\n");
      fputs($fd,'           if(file_exists("funcoes/db_func_'.trim(pg_result($result,$i,'nomearq')).'.php")==true){'."\n");
      fputs($fd,'             include("funcoes/db_func_'.trim(pg_result($result,$i,'nomearq')).'.php");'."\n");
      fputs($fd,'           }else{'."\n");
      
      if($Npk>0){
        fputs($fd,'           $campos = "'.trim(pg_result($result,$i,'nomearq')).'.*";'."\n");
      }else{
        fputs($fd,'           $campos = "'.trim(pg_result($result,$i,'nomearq')).'.oid,'.trim(pg_result($result,$i,'nomearq')).'.*";'."\n");
      }
      fputs($fd,'           }'."\n");
      fputs($fd,'        }'."\n");

      if(pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
	$regpk = 0;
        if(strpos(pg_result($pk,0,'nomecam'),'anousu')>0){
	  $regpk = 1;
	}
        fputs($fd,'        if(isset($chave_'.trim(pg_result($pk,$regpk,'nomecam')).') && (trim($chave_'.trim(pg_result($pk,$regpk,'nomecam')).')!="") ){'."\n");
        fputs($fd,'	         $sql = $cl'.trim(pg_result($result,$i,'nomearq')).'->sql_query(');
        for($p = 0;$p < $Npk;$p++) {
	  if($regpk==1 && $p ==0 ){
            fputs($fd,"db_getsession('DB_anousu'),");
	  }else{
            fputs($fd,'$chave_'.trim(pg_result($pk,$p,'nomecam')).",");
	  }
	}
        fputs($fd,'$campos,"'.trim(pg_result($pk,$regpk,'nomecam')).'");'."\n");
        if(pg_result($pk,0,'camiden')!=0) {
          fputs($fd,'        }else if(isset($chave_'.trim(pg_result($pk,0,'campoca')).') && (trim($chave_'.trim(pg_result($pk,0,'campoca')).')!="") ){'."\n");
          fputs($fd,'	         $sql = $cl'.trim(pg_result($result,$i,'nomearq')).'->sql_query(');
          $virgula = "";
          for($p = 0;$p < $Npk;$p++) {
            if($regpk==1 && $p ==0 ){
              fputs($fd,"db_getsession('DB_anousu')");
	    }else{
              fputs($fd,$virgula.'""');
            }
	    $virgula=",";
	  }
          fputs($fd,',$campos,"'.trim(pg_result($pk,0,'campoca')).'"," '.trim(pg_result($pk,0,'campoca')).' like \'$chave_'.trim(pg_result($pk,0,'campoca')).'%\' ");'."\n");
        } 
      }else{
        fputs($fd,'	         $sql = $cl'.trim(pg_result($result,$i,'nomearq')).'->sql_query();'."\n");
      }

      if($Npk>0){
        fputs($fd,'        }else{'."\n");
        fputs($fd,'           $sql = $cl'.trim(pg_result($result,$i,'nomearq')).'->sql_query(');	  
        $virgula="";
        for($p = 0;$p < $Npk;$p++) {
          if($regpk==1 && $p ==0 ){
            fputs($fd,"db_getsession('DB_anousu')");
          }else{
            fputs($fd,$virgula.'""');
	  }
          $virgula=",";
        }
        if($virgula==""){
          fputs($fd,$virgula.'""');	  
        }	  
        fputs($fd,',$campos,"');
        $virgula="";
        for($p = 0;$p < $Npk;$p++) {
          fputs($fd,$virgula.trim(pg_result($pk,$p,'nomecam')));
          $virgula="#";
        }
        fputs($fd,'","");'."\n");  
        fputs($fd,'        }'."\n");
      }

      fputs($fd,'        $repassa = array();'."\n");

      if(pg_numrows($pk) > 0) {

        
        $Npk = pg_numrows($pk);
        
        fputs($fd,'        if(isset($chave_'.trim(pg_result($pk,0,'campoca')).')){'."\n");
        
        fputs($fd,'          $repassa = array(');
        $separa='';
          
        fputs($fd,'"chave_'.trim(pg_result($pk,0,1)).'"=>$'.'chave_'.trim(pg_result($pk,0,1)));
        fputs($fd,',"chave_'.trim(pg_result($pk,0,'campoca')).'"=>$'.'chave_'.trim(pg_result($pk,0,'campoca')));
        
        fputs($fd,');'."\n");
        fputs($fd,'        }'."\n");
        
      }
      
      fputs($fd,"        echo '<div class=\"container\">';\n");
      fputs($fd,"        echo '  <fieldset>';\n");
      fputs($fd,"        echo '    <legend>Resultado da Pesquisa</legend>';\n");

      fputs($fd,'          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);'."\n");

      fputs($fd,"        echo '  </fieldset>';\n");
      fputs($fd,"        echo '</div>';\n");

      fputs($fd,'      }else{'."\n");
      fputs($fd,'        if($pesquisa_chave!=null && $pesquisa_chave!=""){'."\n");
	  
      fputs($fd,'          $result = $cl'.trim(pg_result($result,$i,'nomearq')).'->sql_record($cl'.trim(pg_result($result,$i,'nomearq')).'->sql_query('.(@$regpk==1?'db_getsession("DB_anousu"),':'').'$pesquisa_chave));'."\n");
      fputs($fd,'          if($cl'.trim(pg_result($result,$i,'nomearq')).'->numrows!=0){'."\n");
      fputs($fd,'            db_fieldsmemory($result,0);'."\n");
      fputs($fd,'            echo "<script>".$funcao_js."(');
      
      if($Npk>0){
        for($p = 0;$p < 1;$p++) {
           fputs($fd,"'$".trim(pg_result($pk,0,'campoca'))."'");
        }
      }else{
         fputs($fd,"'$"."oid'");
      }
      fputs($fd,',false);</script>";'."\n");
      fputs($fd,'          }else{'."\n");
      fputs($fd,'	         echo "<script>".$funcao_js."(\'Chave(".$pesquisa_chave.") não Encontrado\',true);</script>";'."\n");
      fputs($fd,'          }'."\n");
      fputs($fd,'        }else{'."\n");
      fputs($fd,'	       echo "<script>".$funcao_js."(\'\',false);</script>";'."\n");
      fputs($fd,'        }'."\n");
      fputs($fd,'      }'."\n");
      fputs($fd,'      ?>'."\n");
      fputs($fd,'</body>'."\n");
      fputs($fd,'</html>'."\n");
      fputs($fd,'<?'."\n");
      fputs($fd,'if(!isset($pesquisa_chave)){'."\n");
      fputs($fd,'  ?>'."\n");
      fputs($fd,'  <script>'."\n");
      if($Npk>0){
        for($p = 0;$p < 1;$p++) {
          if($regpk==1 && $p >0 ){
            fputs($fd,'document.form2.chave_'.trim(pg_result($pk,$p,'nomecam')).'.focus();'."\n");
            fputs($fd,'document.form2.chave_'.trim(pg_result($pk,$p,'nomecam')).'.select();'."\n");
          }
        }
      }
      fputs($fd,'  </script>'."\n");
      fputs($fd,'  <?'."\n");
      fputs($fd,'}'."\n");
      fputs($fd,'?>'."\n");
  	  // fim dos java scripts
    }
  } 
  if(pg_numrows($pk) > 0) {
  
    fputs($fd,'<script>'."\n");
    fputs($fd,'js_tabulacaoforms("form2","chave_'.trim(pg_result($pk,0,'campoca')).'",true,1,"chave_'.trim(pg_result($pk,0,'campoca')).'",true);'."\n");
    fputs($fd,'</script>'."\n");
  
  }

  fclose($fd);  
  // cria funcao de campos a serem mostrados
  $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
  $arquivo = $root."/funcoes/db_func_".trim($nometab).".php";
  //$arquivo = "/tmp/funcoes/db_func_".trim($nometab).".php";
  if(!is_writable($root."/funcoes")){
     ?>
     <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar em "funcoes" ou não existe.</h6></td></tr></table>
     </body>
     </html>
     <?
     exit;
  } 
  if(file_exists($arquivo) && !is_writable($arquivo)){
     ?>
     <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "funcoes/db_func_<?=$nometab?>"</h6></td></tr></table>
     </body>
     </html>
     <?
     exit;
  } 
  umask(74);
  $fd = fopen($arquivo,"w");
  fputs($fd,"<?\n");
  fputs($fd,'$campos = "'.$nometab.'.');
  $sql = "select trim(nomecam)
          from db_syscampo c
               inner join db_sysarqcamp p on p.codcam = c.codcam
	  where p.codarq = ".pg_result($result,0,"codarq")."
	  order by p.seqarq ";
  $resultc = db_query($sql);
  if(pg_numrows($resultc)>0){
    $arqq="";
    $temanousu = "";
    for($i = 0;$i<pg_numrows($resultc);$i++) {
      $nomec = pg_result($resultc,$i,0);
      if(strpos($nomec,'anousu')==0){
        fputs($fd,$arqq.$nomec);
        $arqq=",".$nometab.".";
      }else{
        $temanousu = $nomec;
      }
    }
    if($temanousu!=""){
       fputs($fd,$arqq.$temanousu." as db_".$temanousu);
    }
  }
  fputs($fd,'";'."\n");
  fputs($fd,"?>\n");
  
  
  fclose($fd);  
?>

<table width="100%"><tr><td align="center"><h3>Concluído...</h3></td></tr></table>

</body>
</html>
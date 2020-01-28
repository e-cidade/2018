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
?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

 function xx($fd,$parq){
	  $fk = pg_exec("select a.nomearq,c.nomecam,f.sequen,f.referen,c.codcam 
                     from db_sysforkey f 
                          inner join db_sysarquivo a on a.codarq = f.codarq 
                          inner join db_syscampo c   on c.codcam = f.codcam 
                     where a.codarq = ".$parq." 
					 order by f.referen,f.sequen");
      $encerra = false;
	  $qalias = "";
      if(pg_numrows($fk) > 0) {
        $Nfk = pg_numrows($fk);
        $arq = 0;
		$virgula = "";
        for($f = 0;$f < $Nfk;$f++) {
		  if($arq != pg_result($fk,$f,"referen")){
		    if($virgula != ""){
              fputs($fd,'";'."\n");
			}
            $arq = pg_result($fk,$f,"referen");
	        $qarq = pg_exec("select nomearq
                         from db_sysarquivo
						 where codarq = ".$arq);
	        if(strpos($GLOBALS["temalias"],trim(pg_result($qarq,0,0))) > 0){
                  if(strpos($GLOBALS["qualalias"],"a") > 0){
	             if(strpos($GLOBALS["qualalias"],"b") > 0){
  	               if(strpos($GLOBALS["qualalias"],"c") > 0){
	  	         $qalias="d";
		       }else{
		         $qalias="c";
                         $GLOBALS["qualalias"] .=  "-c";
		       }
		     }else{
		       $qalias="b";
                       $GLOBALS["qualalias"] .=  "-b";
		     }
		       
		  }else{
		     $qalias="a";
                     $GLOBALS["qualalias"] = "-a";
		  }
		}else{
		  $qalias ="";
		}
		$GLOBALS["temalias"] .= "-".trim(pg_result($qarq,0,0));
            fputs($fd,'     $sql .= "      inner join '.trim(pg_result($qarq,0,0))." ".($qalias==""?"":" as ".$qalias)." on ");
			$virgula = "";
		  }
	      $qk = pg_exec("select q.nomecam
                             from db_sysprikey p
			          inner join db_syscampo q on q.codcam = p.codcam 
			     where codarq = ".$arq." and
			           sequen = ".pg_result($fk,$f,"sequen"));
                  fputs($fd,$virgula.' '.($qalias==""?trim(pg_result($qarq,0,0)):" ".$qalias).'.'.trim(pg_result($qk,0,0))." = ".trim(pg_result($fk,$f,"nomearq")).".".trim(pg_result($fk,$f,"nomecam")));
		  $encerra = true;
		  $virgula = " and ";
        }
	  }	  
      if($encerra==true){
        fputs($fd,'";'."\n");
	  }
	  
	  }  




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
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
<? if(!isset($HTTP_POST_VARS["b_estrut"])) { ?>
  <form method="post" name="estrut">                
        <table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td><strong> 
              <label for="ra1">Módulo:</label>
              </strong> <input type="radio" class="radio" name="tabmod" id="ra1" value="m" > 
              &nbsp;&nbsp; <strong> 
              <label for="ra2">Tabela:</label>
              </strong> <input type="radio" class="radio" name="tabmod" value="t" id="ra2" checked> 
            </td>
          </tr>
          <tr> 
            <td><input name="nometab" type="text" id="nometab" value="<?=@$nometabela?>"></td>
          </tr>
          <tr> 
            <td> <input id="id_estrut" type="submit" name="b_estrut" value="Criar Classe PHP" class="botao"> 
            </td>
            <td>&nbsp;</td>
          </tr>
        </table>
	</form>
<?
} else {
  db_postmemory($HTTP_POST_VARS);
  $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
  $arquivo = $root."/classes/"."db_".$nometab."_classe.php";
  $fd = fopen($arquivo,"w");
    fputs($fd,"<?\n");
        fputs($fd,"//MODULO: ".trim(pg_result($result,$i,"nomemod"))."\n");
        fputs($fd,"//CLASSE DA ENTIDADE ".trim(pg_result($result,$i,"nomearq"))."\n");      
        fputs($fd,"   // funcao para inclusao\n");
     fputs($fd,"?>\n");
     fclose($fd);
     echo "<h3>Classe Gerada no Arquivo : $arquivo</h3>";
     //echo "<a href=\"$arquivo\">Copie esta classe. Clique Aqui.</a>\n";
     echo "<a href=\"sys4_criaclasse001.php\">Retorna</a>\n";
}
?>
	</td>
  </tr>
</table>
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
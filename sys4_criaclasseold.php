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
            <td><input name="nometab" type="text" id="nometab" value="<?echo $nometabela?>"></td>
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
  $arquivo = "tmp/"."db_".$nometab."_classe.php";
  $fd = fopen($arquivo,"w");
// Tabelas
  $nometab = strtolower($nometab);
  if(trim($nometab) == "" && $tabmod == "t")
    $nometab = "%";
  if(trim($nometab) == "" && $tabmod == "m") {
    db_msgbox("Voce precisa informar um módulo");
    db_redireciona();
  }
  if($tabmod == "t")
    if($nometab == "%")
      $qr = " ";
    else
      $qr = "where nomearq = '$nometab'";
  else if($tabmod == "m")
    $qr = "where nomemod = '$nometab'";
  $sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod, a.rotulo
                     from db_sysmodulo m
                     inner join db_sysarqmod am
                     on am.codmod = m.codmod
                     inner join db_sysarquivo a
                     on a.codarq = am.codarq
                     $qr
                     order by codmod";
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);
  $RecordsetTabMod = $result;
  if($numrows == 0) {
    if($tabmod == "t")
      db_msgbox("Não foi encontrada nenhuma tabela com o argumento $nometab");
    else if($tabmod == "m")
      db_msgbox("Não foi encontrada nenhum módulo com o nome de $nometab");
    db_redireciona();
  } else {
    fputs($fd,"<?\n");
    for($i = 0;$i < $numrows;$i++) {
      $campo = pg_exec("select c.nomecam,
		                       c.conteudo,
							   c.valorinicial,
							   c.rotulo,
							   s.nomesequencia,
							   s.codsequencia,
							   c.nulo
                          from db_syscampo c
                               inner join db_sysarqcamp a   on a.codcam = c.codcam
			                   inner join db_syssequencia s on s.codsequencia = a.codsequencia
                          where codarq = ".pg_result($result,$i,"codarq").
			              "order by a.seqarq");
	  $Ncampos = pg_numrows($campo);
	  if($Ncampos > 0) {
        fputs($fd,"//MODULO: ".trim(pg_result($result,$i,"nomemod"))."\n");
        fputs($fd,"//CLASSE DA ENTIDADE ".trim(pg_result($result,$i,"nomearq"))."\n");      
        fputs($fd,"class cl_".trim(pg_result($result,$i,"nomearq"))." { \n");      
		fputs($fd,"   // cria variaveis de erro \n");
		fputs($fd,'   var $rotulo     = null; '."\n");
		fputs($fd,'   var $query_sql  = null; '."\n");
		fputs($fd,'   var $numrows    = 0; '."\n");
		fputs($fd,'   var $erro_status= 1; '."\n");
		fputs($fd,'   var $erro_sql   = null; '."\n");
		fputs($fd,'   var $erro_banco = null; '." \n");
		fputs($fd,'   var $erro_msg   = null; '." \n");
		fputs($fd,'   var $pagina_retorno = null;'." \n");
		fputs($fd,"   // cria variaveis do arquivo \n");
		for($j = 0;$j < $Ncampos;$j++) {
		  $x = pg_result($campo,$j,"conteudo");
          if(substr($x,0,4)=="char" || 
		     substr($x,0,4)=="varc" || 
		     substr($x,0,4)=="text" ) {
		     $aspas = "null";
		  }else if(substr($x,0,4)=="date"){
		      $aspas = "data";
		  }else if(substr($x,0,4)=="bool"){
		      $aspas = "f";
		  }else{
		      $aspas = "0";
		  }
		  if($aspas == "data"){
		     fputs($fd,'   var $'.trim(pg_result($campo,$j,"nomecam"))."_dia = null; \n");
		     fputs($fd,'   var $'.trim(pg_result($campo,$j,"nomecam"))."_mes = null; \n");
		     fputs($fd,'   var $'.trim(pg_result($campo,$j,"nomecam"))."_ano = null; \n");
		     fputs($fd,'   var $'.trim(pg_result($campo,$j,"nomecam"))." = null; \n");
		  }else{
		     fputs($fd,'   var $'.trim(pg_result($campo,$j,"nomecam"))." = $aspas; \n");
		  }
        }
		fputs($fd,"   // cria propriedade com as variaveis do arquivo \n");
        $sql = '   var $campos = "'."\n"; 
        $espaco = "                 "; 
 		for($j = 0;$j < $Ncampos;$j++) {
          $sql .= $espaco.trim(pg_result($campo,$j,"nomecam"))." = ".trim(pg_result($campo,$j,"conteudo"))." = ".trim(pg_result($campo,$j,"rotulo"))." \n";
        }
		fputs($fd,$sql."                 ".'";'."\n" );
        // function construtor da classe
        fputs($fd,"   //funcao construtor da classe \n");
		fputs($fd,'   function cl_'.trim(pg_result($result,$i,"nomearq"))."() { \n");
        fputs($fd,"     //classes dos rotulos dos campos\n");
        fputs($fd,'     $this->rotulo = new rotulo("'.trim(pg_result($result,$i,"nomearq")).'"); '."\n");
        fputs($fd,'     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);'."\n");
        fputs($fd,'   }'."\n");

        // metodo para erro
        fputs($fd,"   //funcao erro \n");
		fputs($fd,'   function erro() { '."\n");
        fputs($fd,'     if($this->erro_status == "0" ){'."\n");
        fputs($fd,'        echo "<script>alert(\"".$this->erro_msg."\");</script>";'."\n");
        fputs($fd,'     }'."\n");
        fputs($fd,'   }'."\n");

        // inicio metodo INCLUIR
        fputs($fd,"   // funcao para inclusao\n");
		fputs($fd,"   function incluir (");
		// Chave Primaria
		$varpk = ""; 
        $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                       where a.codarq = ".pg_result($result,$i,"codarq"));
        if(pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
		  $virgula = "";
          for($p = 0;$p < $Npk;$p++) {
            $varpk .= $virgula."$".trim(pg_result($campo,$p,"nomecam"));
            fputs($fd,$virgula."$".trim(pg_result($campo,$p,"nomecam"))."=null");
			$virgula = ",";
          } 
        }
        fputs($fd,"){ \n");
		
		// inclusao de testes de erro em campos que aceitam null
		for($j=0;$j < $Ncampos;$j++) {
		  if(strpos($varpk,trim(pg_result($campo,$j,"nomecam")) ) == 0 ){
		    $nulo = pg_result($campo,$j,"nulo");
		    if($nulo == 'f' || $nulo == ''){
 	          fputs($fd,'     if($this->'.trim(pg_result($campo,$j,"nomecam"))." == null ){ \n");
		      fputs($fd,'       $this->erro_sql = " Campo '.trim(pg_result($campo,$j,"nomecam")).' nao declarado.";'."\n");
              fputs($fd,'       $this->erro_status = "0";'."\n");
		      fputs($fd,"       return false;\n");		  
		      fputs($fd,"     }\n");
		    }else{
               $valorinicial = pg_result($campo,$j,"valorinicial");
		       if($valorinicial != ''){
   	             fputs($fd,'     if($this->'.trim(pg_result($campo,$j,"nomecam"))." == null ){ \n");
		         fputs($fd,'       $this->'.trim(pg_result($campo,$j,"nomecam")).' = "'.$valorinicial.'";'."\n");
		         fputs($fd,"     }\n");
		  	   }
		    }
		  }
        }
		// verifica se tem sequencia para a chave primaria
		for($j = 0;$j < $Ncampos;$j++) {
          $x = pg_result($campo,$j,"codsequencia");
		  if( $x != 0){
		    fputs($fd,'     $result = @pg_query("select nextval(\''.trim(pg_result($campo,$j,"nomesequencia"))."')\"); \n");
   	        fputs($fd,'     if($result==false){'."\n");
		    fputs($fd,'       $this->erro_banco = str_replace("\n","",@pg_last_error());'."\n");
		    fputs($fd,'       $this->erro_sql   = "Verifique o cadastro da sequencia: '.trim(pg_result($campo,$j,"nomesequencia")).' do campo: '.trim(pg_result($campo,$j,"nomecam"))."\"; \n");
            fputs($fd,'       $this->erro_status = "0";'."\n");
		    fputs($fd,'       return false; '."\n");
		    fputs($fd,"     }\n");
		    if(strpos($varpk,trim(pg_result($campo,$j,"nomecam")) ) == 0 )
		      fputs($fd,'     $this->'.trim(pg_result($campo,$j,"nomecam")).' = pg_result($result,0,0); '."\n");
		    else
			  fputs($fd,'     $'.trim(pg_result($campo,$j,"nomecam")).' = pg_result($result,0,0); '."\n");
		  }
        }
		fputs($fd,'     $'.'result = @pg_query("insert into '.trim(pg_result($result,$i,"nomearq"))."(\n");
        $virgula = " ";
 		for($j = 0;$j < $Ncampos;$j++) {
		  fputs($fd,"                                      ".$virgula.trim(pg_result($campo,$j,"nomecam"))." \n");
          $virgula = ",";
        }
		fputs($fd,"                       )\n");
		fputs($fd,"                values (\n");
        $virgula = " ";
		for($j = 0;$j < $Ncampos;$j++) {
		  $x = pg_result($campo,$j,"conteudo");
          if(substr($x,0,4)=="char" || 
		     substr($x,0,4)=="varc" || 
		     substr($x,0,4)=="text" ) {
		     $aspas = "'";
		  }else if(substr($x,0,4)=="date"){
		      $aspas = "'";
		  }else if(substr($x,0,4)=="bool"){
		      $aspas = "'";
		  }else{
		      $aspas = "";
		  }
		  if(strpos($varpk,trim(pg_result($campo,$j,"nomecam")) ) == 0 )
		    fputs($fd,'                               '.$virgula.$aspas.'$this->'.trim(pg_result($campo,$j,"nomecam")).$aspas." \n");
		  else
		    fputs($fd,'                               '.$virgula.$aspas.'$'.trim(pg_result($campo,$j,"nomecam")).$aspas." \n");
          $virgula = ",";
        }
		fputs($fd,"                      )\");\n");
		fputs($fd,'     if($result==false){ '."\n");
        fputs($fd,'       $this->erro_banco = str_replace("\n","",@pg_last_error());'."\n");
        fputs($fd,'       $this->erro_sql   = "'.pg_result($result,$i,'rotulo').' ('.$varpk.') nao Incluído. Inclusao Abortada.";'."\n");
        fputs($fd,'       $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
        fputs($fd,'       $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
        fputs($fd,'       $this->erro_status = "0";'."\n");
        fputs($fd,'       return false;'."\n");
        fputs($fd,"     }\n");
        fputs($fd,'     $this->erro_banco = "";'."\n");
        fputs($fd,'     $this->erro_sql = "Inclusao efetuada com Sucesso";'."\n");
        fputs($fd,'     $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
        fputs($fd,'     $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
        fputs($fd,'     $this->erro_status = "1";'."\n");
        fputs($fd,'     return true;'."\n");
        fputs($fd,"   } \n");
        
        // cria funcao de alteracao
		
        fputs($fd,"   // funcao para alteracao\n");
		fputs($fd,"   function alterar (");
		// Chave Primaria
		$varpk = ""; 
        $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                       where a.codarq = ".pg_result($result,$i,"codarq"));
        if(pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
		  $virgula = "";
          for($p = 0;$p < $Npk;$p++) {
            $varpk .= $virgula."$".trim(pg_result($campo,$p,"nomecam"));
			
            fputs($fd,"$".trim(pg_result($campo,$p,"nomecam"))."=null");
			$virgula = ",";
          } 
        }else{
            fputs($fd,' $oid=null ');
		}
        fputs($fd,") { \n");
  
        fputs($fd,'     $sql = " update '.trim(pg_result($result,$i,"nomearq")).' set ";'."\n");
		
        fputs($fd,'     $virgula = "";'."\n");
		for($j = 0;$j < $Ncampos;$j++) {
	      $x = pg_result($campo,$j,"conteudo");
          if(substr($x,0,4)=="char" || 
		     substr($x,0,4)=="varc" || 
		     substr($x,0,4)=="text" ) {
 	         fputs($fd,'     if($this->'.trim(pg_result($campo,$j,"nomecam"))." != null ){ \n");
		     $aspas = "'";
		  }else if(substr($x,0,4)=="date"){
		     fputs($fd,'     if($this->'.trim(pg_result($campo,$j,"nomecam")).' != null && $this->'.trim(pg_result($campo,$j,"nomecam")).' != "--"){ '."\n");
             $aspas = "'";
		  }else if(substr($x,0,4)=="bool"){
		     fputs($fd,'     if($this->'.trim(pg_result($campo,$j,"nomecam"))." != null ){ \n");
             $aspas = "'";
		  }else{
		     fputs($fd,'     if($this->'.trim(pg_result($campo,$j,"nomecam"))." != null ){ \n");
             $aspas = "";
		  }
	      fputs($fd,'       $sql  .= $virgula." '.trim(pg_result($campo,$j,"nomecam")).' = '.$aspas.'$this->'.trim(pg_result($campo,$j,"nomecam")).$aspas.' ";'."\n");
          fputs($fd,'       $virgula = ",";'."\n");
		  fputs($fd,"     }");
		  if(substr($x,0,4)=="date"){
		     fputs($fd,"     else{ \n");
	         fputs($fd,'       $sql  .= $virgula." '.trim(pg_result($campo,$j,"nomecam")).' = null ";'."\n");
             fputs($fd,'       $virgula = ",";'."\n");
		     fputs($fd,"     }");
          }
		  fputs($fd,"\n");
		  
		}
        fputs($fd,'     $sql .= " where ');
		if(pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
		  $virgula = "";
          for($p = 0;$p < $Npk;$p++) {
            fputs($fd,$virgula." ".trim(pg_result($campo,$p,"nomecam"))." = ");
    	    $x = pg_result($campo,$p,"conteudo");
            if(substr($x,0,4)=="char" || 
		       substr($x,0,4)=="varc" || 
		       substr($x,0,4)=="text" || 
		       substr($x,0,4)=="bool" || 
		       substr($x,0,4)=="date" ) 
		       $aspas = "'";
		    else
		       $aspas = "";
			fputs($fd,$aspas."$".trim(pg_result($campo,$p,"nomecam")).$aspas."\n");
			$virgula = ",";
          } 
        }else{
           fputs($fd,'oid = $oid ');
		}
        fputs($fd,'";'."\n");
	    fputs($fd,'     $'.'result = @pg_exec($sql);'."\n");		
	    fputs($fd,'     if($result==false){ '."\n");
        fputs($fd,'       $this->erro_banco = str_replace("\n","",@pg_last_error());'."\n");
        fputs($fd,'       $this->erro_sql   = "'.pg_result($result,$i,'rotulo').' ('.$varpk.') nao Alterado. Alteracao Abortada.";'."\n");
        fputs($fd,'       $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
        fputs($fd,'       $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
        fputs($fd,'       $this->erro_status = "0";'."\n");
        fputs($fd,'       return false;'."\n");
        fputs($fd,"     }else{\n");
        fputs($fd,'       if(pg_affected_rows($result)==0){'."\n");
        fputs($fd,'         $this->erro_banco = "";'."\n");
        fputs($fd,'         $this->erro_sql = "'.pg_result($result,$i,'rotulo').' ('.$varpk.') nao foi Alterado. Alteracao Executada.";'."\n");
        fputs($fd,'         $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
        fputs($fd,'         $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
        fputs($fd,'         $this->erro_status = "1";'."\n");
        fputs($fd,'         return true;'."\n");
		fputs($fd,"       }else{\n");
        fputs($fd,'         $this->erro_banco = "";'."\n");
        fputs($fd,'         $this->erro_sql = "Alteracao efetuada com Sucesso";'."\n");
        fputs($fd,'         $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
        fputs($fd,'         $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
        fputs($fd,'         $this->erro_status = "1";'."\n");
        fputs($fd,'         return true;'."\n");
        fputs($fd,"       } \n");	 
        fputs($fd,"     } \n");	 
        fputs($fd,"   } \n");	 
	  
	    // cria funcao exclusao
        fputs($fd,"   // funcao para exclusao \n");
		fputs($fd,"   function excluir (");
		// Chave Primaria
		$varpk = ""; 
        $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                       where a.codarq = ".pg_result($result,$i,"codarq"));
        if(pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
		  $virgula = "";
          for($p = 0;$p < $Npk;$p++) {
            $varpk .= $virgula."$".trim(pg_result($campo,$p,"nomecam"));
            fputs($fd,"$".trim(pg_result($campo,$p,"nomecam"))."=null");
			$virgula = ",";
          } 
        }else{
            fputs($fd,' $oid=null ');
		}
        fputs($fd,") { \n");
  
        fputs($fd,'     $'.'result = @pg_exec(" delete from '.trim(pg_result($result,$i,"nomearq")). "\n");
        fputs($fd,'                    where ');
		if(pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
		  $virgula = "";
          for($p = 0;$p < $Npk;$p++) {		  
            fputs($fd,$virgula." ".trim(pg_result($campo,$p,"nomecam"))." = ");
  		    $x = pg_result($campo,$p,"conteudo");
            if(substr($x,0,4)=="char" || 
		       substr($x,0,4)=="varc" || 
		       substr($x,0,4)=="text" || 
   		       substr($x,0,4)=="bool" || 
               substr($x,0,4)=="date" ) 
		       $aspas = "'";
		    else
		       $aspas = "";
            fputs($fd,$aspas."$".trim(pg_result($campo,$p,"nomecam")).$aspas."\n");
			$virgula = ",";
          } 
        }else{
           fputs($fd,'oid = $oid ');
		}
        fputs($fd,'                    ");'."\n");
      }
	  fputs($fd,'     if($result==false){ '."\n");
      fputs($fd,'       $this->erro_banco = str_replace("\n","",@pg_last_error());'."\n");
      fputs($fd,'       $this->erro_sql   = "'.pg_result($result,$i,'rotulo').' ('.$varpk.') nao Excluído. Exclusão Abortada.";'."\n");
      fputs($fd,'       $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
      fputs($fd,'       $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
      fputs($fd,'       $this->erro_status = "0";'."\n");
      fputs($fd,'       return false;'."\n");
      fputs($fd,"     }else{\n");
      fputs($fd,'       if(pg_affected_rows($result)==0){'."\n");
      fputs($fd,'         $this->erro_banco = "";'."\n");
      fputs($fd,'         $this->erro_sql = "'.pg_result($result,$i,'rotulo').' ('.$varpk.') nao Encontrado. Exclusão não Efetuada.";'."\n");
      fputs($fd,'         $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
      fputs($fd,'         $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
      fputs($fd,'         $this->erro_status = "0";'."\n");
      fputs($fd,'         return true;'."\n");
	  fputs($fd,"       }else{\n");
      fputs($fd,'         $this->erro_banco = "";'."\n");
      fputs($fd,'         $this->erro_sql = "Exclusão efetuada com Sucesso";'."\n");
      fputs($fd,'         $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
      fputs($fd,'         $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
      fputs($fd,'         $this->erro_status = "1";'."\n");
      fputs($fd,'         return true;'."\n");
      fputs($fd,"       } \n");	 
      fputs($fd,"     } \n");	 
      fputs($fd,"   } \n");	 

      // gera o metodo do recordset
	  
      fputs($fd,"   // funcao do recordset \n");
      fputs($fd,'   function sql_record($sql) { '."\n");
	  fputs($fd,'     $'.'result = @pg_query($sql);'."\n");
      fputs($fd,'     if($result==false){'."\n");
      fputs($fd,'       $this->numrows    = 0;'."\n");
      fputs($fd,'       $this->erro_banco = str_replace("\n","",@pg_last_error());'."\n");
      fputs($fd,'       $this->erro_sql   = "Erro ao selecionar os registros.";'."\n");
      fputs($fd,'       $this->erro_msg   = "Usuário: \\\n\\\n ".$this->erro_sql." \\\n\\\n";'."\n");
      fputs($fd,'       $this->erro_msg   .=  str_replace('."'".'"'."'".',"",str_replace("'."'".'","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));'."\n");
      fputs($fd,'       $this->erro_status = "0";'."\n");
      fputs($fd,'       return false;'."\n");
      fputs($fd,'     }'."\n");
      fputs($fd,'     $this->numrows = pg_numrows($result);'."\n");
      fputs($fd,'     return $result;'."\n");
      fputs($fd,'   }'."\n");

      // gera o metodo sql
      fputs($fd,"   // funcao do sql \n");
      fputs($fd,"   function sql_query ( ");
      
      if(pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        $virgula = "";
        for($p = 0;$p < $Npk;$p++) {
          $varpk .= $virgula."$".trim(pg_result($campo,$p,"nomecam"));
          fputs($fd,"$".trim(pg_result($campo,$p,"nomecam"))."=null");
		  $virgula = ",";
        } 
      }
      fputs($fd,',$campos="*",$ordem=null){ '."\n");
      fputs($fd,'     $sql = "select ";'."\n");
      fputs($fd,'     if($campos != "*" ){'."\n");
      fputs($fd,'       $campos_sql = split("#",$campos);'."\n");
      fputs($fd,'       $virgula = "";'."\n");
      fputs($fd,'       for($i=0;$i<sizeof($campos_sql);$i++){'."\n");
      fputs($fd,'         $sql .= $virgula.$campos_sql[$i];'."\n");
      fputs($fd,'         $virgula = ",";'."\n");
      fputs($fd,'       }'."\n");
      fputs($fd,'     }else{'."\n");
      fputs($fd,'       $sql .= $campos;'."\n");
      fputs($fd,'     }'."\n");
	  fputs($fd,'     $sql .= " from '.trim(pg_result($result,$i,"nomearq")).' ";'."\n");
      if(pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        for($p = 0;$p < $Npk;$p++) {
	      $x = pg_result($campo,$p,"conteudo");
          if(substr($x,0,4)=="char" || 
		    substr($x,0,4)=="varc" || 
		    substr($x,0,4)=="text" || 
 	        substr($x,0,4)=="bool" || 
		    substr($x,0,4)=="date" ) 
		    $aspas = "'";
		  else
		    $aspas = "";
          if($p == 0){
             fputs($fd,"     if($".trim(pg_result($campo,$p,"nomecam"))."!=null ){"."\n");
             fputs($fd,'       $sql .= " where '.trim(pg_result($campo,$p,"nomecam"))." = ".$aspas."$".trim(pg_result($campo,$p,"nomecam")).$aspas."\"; \n");
             fputs($fd,'     } '."\n");
          }else{
             fputs($fd,"     if($".trim(pg_result($campo,$p,"nomecam"))."!=null ){"."\n");
             fputs($fd,"       if($".trim(pg_result($campo,$p-1,"nomecam"))."!=null ){"."\n");
             fputs($fd,'          $sql .= " and ";'."\n");
             fputs($fd,'       }else{'."\n");
             fputs($fd,'          $sql .= " where ";'."\n");
             fputs($fd,'       } '."\n");
             fputs($fd,'       $sql .= '.trim(pg_result($campo,$p,"nomecam"))." = ".$aspas."$".trim(pg_result($campo,$p,"nomecam")).$aspas."; \n");
             fputs($fd,'     } '."\n");		
          }
        } 
      }
	  
      fputs($fd,'     if($ordem != null ){'."\n");
	  fputs($fd,'       $sql .= " order by ";'."\n");
      fputs($fd,'       $campos_sql = split("#",$ordem);'."\n");
      fputs($fd,'       $virgula = "";'."\n");
      fputs($fd,'       for($i=0;$i<sizeof($campos_sql);$i++){'."\n");
      fputs($fd,'         $sql .= $virgula.$campos_sql[$i];'."\n");
      fputs($fd,'         $virgula = ",";'."\n");
      fputs($fd,'       }'."\n");
      fputs($fd,'     }'."\n");
      fputs($fd,'     return $sql;'."\n");
      fputs($fd,'  }'."\n");

      $sql = "select codigoclass 
	         from db_sysclasses
             where codarq = ".pg_result($result,0,'codarq');
	 $resultclass = pg_exec($sql);
	 if(pg_numrows($resultclass)>0){
	    for($c=0;$c<pg_numrows($resultclass);$c++){		   
//		   fputs($fd,trim(pg_result($recultclass,$c,'descrclass'))."\n");
		   fputs($fd,"   ".str_replace("\n","",trim(pg_result($resultclass,$c,'codigoclass')))."\n");
		}
	 } 		 

    fputs($fd,"}\n");
	}
  }  
fputs($fd,"?>\n");
fclose($fd);
echo "<h3>Classe Criada. Arquivo : $arquivo</h3>";
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
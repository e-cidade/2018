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
include("classes/db_arrevenc_classe.php");
include("classes/db_arrevenclog_classe.php");
include("classes/db_arreinstit_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clarrevenc = new cl_arrevenc;
$clarrevenclog = new cl_arrevenclog;
$clarreinstit = new cl_arreinstit;

if(isset($opcao) and $opcao=="alterar"){
	$db_opcao = 2;
}else if(isset($opcao) and $opcao=="excluir"){
	$db_opcao = 3;
} 
if($db_opcao==33){
	$db_botao = false;
	
}else{
  $db_botao = true;	
}

if ( isset( $alterar ) ) {
  
  $sqlerro = false;
  $erroParc = false;
  
  db_inicio_transacao();
  $db_opcao = 1;
  
  $clarreinstit->sql_record($clarreinstit->sql_query_file(null,"*",null,"k00_numpre = {$k00_numpre} and k00_instit = ".db_getsession('DB_instit') ) ); 
  if ( $clarreinstit->numrows == 0 ) {
    db_msgbox("Numpre de outra instituição inclusão abortada");
    $sqlerro = true;
  } else {

	if ( $sqlerro==false ) {
			
	  if ( $k00_numpar == 0 ) {
		
		$sqlPar   = "select distinct k00_numpar as parc from arrecad where k00_numpre = {$k00_numpre}";
		$rsPar    = pg_query($sqlPar);
		$linhaPar = pg_num_rows($rsPar);
		 
		 if ( $linhaPar>0 ) {

			for ( $i=0; $i<$linhaPar; $i++ ) {
			   db_fieldsmemory($rsPar,$i);
               $erroParc = db_verificaData($k00_dtini,$k00_dtfim,$k00_numpre,$parc);
						
	          if ( $erroParc==true ) {
		          db_msgbox("Ja existe uma prorrogação no período para o Numpre:$k00_numpre e Parcela:$parc ");
	          } else {
		         $clarrevenc->k00_sequencial= $k00_sequencial;
				 $clarrevenc->excluir($k00_sequencial);
				
		         $clarrevenc->k00_arrevenclog = $k75_sequencial;
				 $clarrevenc->k00_numpar      = $parc ;
	             $clarrevenc->incluir(null);
			      if ( $clarrevenc->erro_status=="0" ) {
			      	 $sqlerro = true;
					 $msgerro = $clarrevenc->erro_msg;
				  }
		      }
			}
		 }
      
	  } else {
				
		$clarrevenc->k00_sequencial= $k00_sequencial;
		$clarrevenc->excluir($k00_sequencial);
		if ( $clarrevenc->erro_status=="0" ) {
		  $sqlerro = true;
		  $msgerro = $clarrevenc->erro_msg;
		}
		
		// verifica se ja tem prorrogação para este periodo
		$sqlerro = db_verificaData($k00_dtini,$k00_dtfim,$k00_numpre,$k00_numpar);
		if ( $sqlerro==true ) {
		  $msgerro = "Ja existe uma prorrogação neste período para este Numpre e Parcela ";
		}
				
		$clarrevenc->k00_arrevenclog = $k75_sequencial;
		$clarrevenc->incluir(null);
		if ( $clarrevenc->erro_status=="0" ) {
		  $sqlerro = true;
		  $msgerro = $clarrevenc->erro_msg;
		}
		
	  }
	  
	}
	
  }
  
  db_fim_transacao($sqlerro);
	
  if ( $sqlerro == false ) {
	$k00_sequencial ="";
	$msgerro = "Alteração efetuada com sucesso.";
  } else {
	$db_opcao = 2;
  }
	
} 

if ( isset ( $incluir ) ) {
	
  $sqlerro  = false;
  $erroParc = false;
  $erro     = false;
  
  db_inicio_transacao();   
  $sqlerro = db_verificaData($k00_dtini,$k00_dtfim,$k00_numpre,$k00_numpar);
  if ( $sqlerro==true ) {
	$msgerro="Ja existe uma prorrogação no período para este Numpre e Parcela.";
  }  			

  if ( $sqlerro == false ) {
	
	if ( $k75_sequencial == "" ) {

	  $clarrevenclog->k75_instit = db_getsession("DB_instit");
	  $clarrevenclog->incluir(null);
	  if ( $clarrevenclog->erro_status=="0" ) {
		$sqlerro = true;
		$msgerro = $clarrevenclog->erro_msg;
	  }
     
	  $k75_sequencial = $clarrevenclog-> k75_sequencial;
	  
	}

	if ( $k00_numpar == 0 ) {
	  $sqlPar = "select distinct k00_numpar as parc from arrecad where k00_numpre = {$k00_numpre}";
	  $rsPar  = pg_query($sqlPar);
	  $linhaPar = pg_num_rows($rsPar);
	  
	  if ( $linhaPar>0 ) {
	  	
		for ( $i=0; $i<$linhaPar; $i++ ) {
		   db_fieldsmemory($rsPar,$i);
						
		   $erroParc = db_verificaData($k00_dtini,$k00_dtfim,$k00_numpre,$parc);
		   if ( $erroParc == true ) {
			 db_msgbox("Ja existe uma prorrogação no período para o Numpre:$k00_numpre e Parcela:$parc");

		   } else {
		   	
		     $clarrevenc->k00_arrevenclog = $k75_sequencial;
			 $clarrevenc->k00_numpar      = $parc ;
	         $clarrevenc->incluir(null);
			 if ( $clarrevenc->erro_status=="0" ) {
			    $sqlerro = true;
				$k75_sequencial = "";
				$msgerro = $clarrevenc->erro_msg;
			 }
			 
		   } 
		   
		}
					
	  }

    } else {
	  
	  $clarrevenc->k00_arrevenclog = $k75_sequencial;
	  $clarrevenc->incluir(null);
	  if ( $clarrevenc->erro_status=="0" ) {
		$sqlerro = true;
	    $k75_sequencial = "";
		$msgerro = $clarrevenc->erro_msg;
	  }
				
    }
		
  }

  db_fim_transacao($sqlerro);
  if ( $sqlerro == false ) {
	$msgerro = "Inclusão efetuada com sucesso.";
  }
	
} else if ( isset($excluir) ) {
  
  $sqlerro = false;
  db_inicio_transacao();
 
  $clarreinstit->sql_record($clarreinstit->sql_query_file(null,"*",null,"k00_numpre = {$k00_numpre} and k00_instit = ".db_getsession('DB_instit') ) ); 
  if ( $clarreinstit->numrows == 0 ) {
    db_msgbox("Numpre de outra instituiçã£o inclusão abortada");
    $sqlerro = true;
  } else {
  	
	$clarrevenc->k00_sequencial    = $k00_sequencial;
	$clarrevenc->excluir($k00_sequencial);
	if ( $clarrevenc->erro_status=="0" ) {
	   $sqlerro = true;
	   $msgerro = $clarrevenc->erro_msg;
	}
		
  }
  db_fim_transacao($sqlerro);
  if ( $sqlerro == false ) {
    $msgerro = "Exclusão efetuada com sucesso.";
  }
	
} else if ( isset($chavepesquisa) ) {
   $db_opcao = 1;
   $k75_sequencial = $chavepesquisa;
  
   /*
	 $result = $clarrevenc->sql_record($clarrevenc->sql_query(null,"*",null," k75_sequencial = {$chavepesquisa} " )); 
     db_fieldsmemory($result,0);
   */
}

 if ( isset($k75_sequencial) and $k75_sequencial!="" ) {
   $sqlCarrega = "select k75_usuario,login,k75_data,k75_hora,k00_numpre 
	                from arrevenclog 
	   				     inner join arrevenc    on k00_arrevenclog = k75_sequencial 
						 inner join db_usuarios on id_usuario      = k75_usuario
						 where k75_sequencial = {$k75_sequencial}";
    $rsCarrega = pg_query($sqlCarrega);
    $linhasCarrega= pg_num_rows($rsCarrega);
    if ( $linhasCarrega>0 ) {
 	  db_fieldsmemory($rsCarrega,0);
    }
  
   $db_opcaonumpre=3;

 } else {
   
   $k75_usuario  = db_getsession("DB_id_usuario");
   $login        = db_getsession("DB_login");
   $k75_data_dia = date("d",db_getsession("DB_datausu"));
   $k75_data_mes = date("m",db_getsession("DB_datausu"));
   $k75_data_ano = date("Y",db_getsession("DB_datausu"));
   $k75_hora     = date("H:i");
   
   $db_opcaonumpre= $db_opcao;
   
 }
 
 if ( isset($k00_sequencial) && $k00_sequencial!="" ) {	
   
   $sqlAlt = "select * from arrevenc where k00_sequencial =$k00_sequencial";
   $rsAlt = pg_query($sqlAlt);
   $linhasAlt = pg_num_rows($rsAlt);
   if ( $linhasAlt > 0 ) {
	 db_fieldsmemory($rsAlt,0);
   }
   
 }

 function db_verificaData($k00_dtini,$k00_dtfim,$k00_numpre,$k00_numpar){

   $dataini =   implode("-",array_reverse(explode("/",$k00_dtini)));
   $datafim =   implode("-",array_reverse(explode("/",$k00_dtfim)));
   $sqlPeriodo = "select * from arrevenc 
	                      where k00_numpre = {$k00_numpre}
							and k00_numpar = {$k00_numpar}
							and (k00_dtini,k00_dtfim) overlaps ( DATE '{$dataini}' - '1 day'::interval, DATE '{$datafim}' + '1 day'::interval)"; 
		
   $rsPeriodo = pg_query($sqlPeriodo);
   $linhasPeriodo = pg_num_rows($rsPeriodo);
   if ( $linhasPeriodo > 0 ) {
	// não pode incluir
     return true;
   }else{
	 return false;
   }
 }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
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
	
	include("forms/db_frmarrevenc.php");
	
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
if ( isset($incluir) or isset($alterar) or isset($excluir) ) {
  if ( $sqlerro == true ) {
    db_msgbox($msgerro);
  } else {
  	db_msgbox($msgerro);
	  echo "
	  <script>
	    document.form1.k00_dtfim.value = '';
		document.form1.k00_dtini.value = '';
		document.form1.k00_obs.value = '';
		document.form1.k00_sequencial.value = '';
	  </script>
	  ";
  }
		
}

if ( $db_opcao==33 or $db_opcao==22 ) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>
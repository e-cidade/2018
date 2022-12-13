<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_averbacao_classe.php");
include("classes/db_averbacgm_classe.php");
include("classes/db_averbaregimovel_classe.php");
include("classes/db_averbaescritura_classe.php");
include("classes/db_averbaprocesso_classe.php");
include("classes/db_averbatipo_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_averbaformalpartilha_classe.php");
include("classes/db_averbaformalpartilhacgm_classe.php");
include("classes/db_averbadecisaojudicial_classe.php");
include("classes/db_averbaguia_classe.php");
include("classes/db_averbaguiaitbi_classe.php");
include("classes/db_cgm_classe.php");
$cliptubase                  = new  cl_iptubase;
$claverbaregimovel           = new cl_averbaregimovel;
$claverbaprocesso            = new cl_averbaprocesso;
$claverbacao                 = new cl_averbacao;
$claverbacgm                 = new cl_averbacgm;
$claverbaescritura           = new cl_averbaescritura;
$claverbatipo                = new cl_averbatipo;
$claverbadecisaojudicial     = new cl_averbadecisaojudicial;
$claverbaformalpartilha      = new cl_averbaformalpartilha;
$claverbaformalpartilhacgm   = new cl_averbaformalpartilhacgm;
$claverbaguia                = new cl_averbaguia;
$claverbaguiaitbi            = new cl_averbaguiaitbi;
$clcgm                       = new cl_cgm;


$claverbaformalpartilha->rotulo->label();
$claverbaescritura->rotulo->label();
$claverbadecisaojudicial->rotulo->label();
$clcgm->rotulo->label();
$claverbaguia->rotulo->label();

db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $claverbacao->alterar($j75_codigo);
  if($claverbacao->erro_status==0){
    $sqlerro=true;
  }  
  $erro_msg = $claverbacao->erro_msg;
  $result_tipo = $claverbacao->sql_record($claverbacao->sql_query_file($j75_codigo,"j75_tipo"));
  db_fieldsmemory($result_tipo,0);
  
  if ($sqlerro==false){  	
  	if ($j93_averbagrupo==2){
  		$claverbaregimovel->j78_averbacao=$j75_codigo;
  		$claverbaregimovel->alterar($j75_codigo);
  		if($claverbaregimovel->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaregimovel->erro_msg;
  		}
  	}
  }
  if ($sqlerro==false){  	
  	if ($j93_averbagrupo==1){
  		$result_escr = $claverbaescritura->sql_record($claverbaescritura->sql_query(null,"*",null,"j94_averbacao =".$j75_codigo));
  		if ($claverbaescritura->numrows>0){
  			db_fieldsmemory($result_escr,0);
  			$claverbaescritura->j94_averbacao=$j75_codigo;
  			$claverbaescritura->j94_codigo= $j94_codigo;
  			$claverbaescritura->alterar($j94_codigo);
  			if($claverbaescritura->erro_status==0){
    			$sqlerro=true;
  				$erro_msg = $claverbaescritura->erro_msg;
  			}
  		}
  	}
  } 

  if ($sqlerro==false){

  	$result_proc = $claverbaprocesso->sql_record($claverbaprocesso->sql_query_file($j75_codigo));  	

  	if ($j77_codproc!=""){

  		if ($claverbaprocesso->numrows>0){

	  		$claverbaprocesso->j77_averbacao = $j75_codigo;
	  		$claverbaprocesso->alterar($j75_codigo);

	  		if ($claverbaprocesso->erro_status==0){

	  			$sqlerro=true;
	  			$erro_msg = $claverbaprocesso->erro_msg;	  			
	  		}

  		}else{

  			$claverbaprocesso->p77_averbacao = $j75_codigo;
	  		$claverbaprocesso->incluir($j75_codigo);

	  		if ($claverbaprocesso->erro_status==0){

	  			$sqlerro=true;
	  			$erro_msg = $claverbaprocesso->erro_msg;
	  		}
  		}
  	}else{

  		if ($claverbaprocesso->numrows>0){  			

	  		$claverbaprocesso->excluir($j75_codigo);
	  		if ($claverbaprocesso->erro_status==0){

	  			$sqlerro=true;
	  			$erro_msg = $claverbaprocesso->erro_msg;
	  		}
  		}
  	}
  }   
  
   if ($sqlerro==false){  	
  	 if ($j93_averbagrupo==4){
  		$claverbaformalpartilha->j100_sequencial = $j100_sequencial;
  		$claverbaformalpartilha->j100_averbacao  = $j75_codigo;
  		$claverbaformalpartilha->alterar($j100_sequencial);
  		if($claverbaformalpartilha->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaformalpartilha->erro_msg;
  		}
		//exclui o cgm
		$claverbaformalpartilhacgm->j102_sequencial = $j102_sequencial;
			$claverbaformalpartilhacgm->excluir("","j102_averbaformalpartilha = $j100_sequencial");
			if($claverbaformalpartilhacgm->erro_status==0){
    		  $sqlerro=true;
  			  $erro_msg = $claverbaformalpartilhacgm->erro_msg;
  	  	    }
		//inclui cgm se tiver
		if(isset($z01_numcgm1) and $z01_numcgm1!="" ){
		  $claverbaformalpartilhacgm->j102_numcgm     = $z01_numcgm1;
		  $claverbaformalpartilhacgm->j102_averbaformalpartilha = $j100_sequencial;
  		  $claverbaformalpartilhacgm->incluir(null);
  		  if($claverbaformalpartilhacgm->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaformalpartilhacgm->erro_msg;
  	  	  }
	    }
		
		
	 }
   }
   if ($sqlerro==false){
  	if ($j93_averbagrupo==5){
        $claverbadecisaojudicial->j101_sequencial = $j101_sequencial;
  		$claverbadecisaojudicial->j101_averbacao = $j75_codigo;
  		$claverbadecisaojudicial->alterar($j101_sequencial);
  		if($claverbadecisaojudicial->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbadecisaojudicial->erro_msg;
  		}
	  }
  }
  if ($sqlerro==false){
  	if ($j93_averbagrupo==6){
  	  if($guia==2){
  		//não
		$j104_guia = $guianao;
  	  }	
	  $claverbaguia->j104_guia       = $j104_guia;
  	  $claverbaguia->j104_sequencial = $j104_sequencial;
  	  $claverbaguia->j104_averbacao  = $j75_codigo;
	  $claverbaguia->alterar($j104_sequencial);
  	  if($claverbaguia->erro_status==0){
    	$sqlerro=true;
  		$erro_msg = $claverbaguia->erro_msg;
  	  }
	  //excluir guiaitbi
	  $claverbaguiaitbi->j103_sequencial=$j103_sequencial;
	  $claverbaguiaitbi->excluir("","j103_averbaguia = $j104_sequencial");
			if($claverbaguiaitbi->erro_status==0){
    	      $sqlerro=true;
  	  	      $erro_msg = $claverbaguiaitbi->erro_msg;
  	  }
	  if($guia==1){
	    if($j103_itbi!=""){
	  	  $claverbaguiaitbi->j103_averbaguia = $j104_sequencial;
	      $claverbaguiaitbi->incluir(null);
  	      if($claverbaguiaitbi->erro_status==0){
    	    $sqlerro=true;
  	  	    $erro_msg = $claverbaguiaitbi->erro_msg;
  	      }
		}
	  }
	}
  }
  
  
  db_fim_transacao($sqlerro);
  $db_opcao = 2;
  $db_botao = true;
}else if(isset($chavepesquisa)){
  
   $db_opcao = 2;
   $db_botao = true;
   $result = $claverbacao->sql_record($claverbacao->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result_proc = $claverbaprocesso->sql_record($claverbaprocesso->sql_query($j75_codigo,"j77_codproc,p58_numero, p58_ano, p58_requer"));
   if ($claverbaprocesso->numrows>0){

     db_fieldsmemory($result_proc,0);
     $p58_numero = $p58_numero . '/' . $p58_ano;
   }
   $result_reg = $claverbaregimovel->sql_record($claverbaregimovel->sql_query($j75_codigo));
   if ($claverbaregimovel->numrows>0){
   	db_fieldsmemory($result_reg,0);
   }
   $result_escr = $claverbaescritura->sql_record($claverbaescritura->sql_query(null,"*",null,"j94_averbacao =".$j75_codigo));
  	if ($claverbaescritura->numrows>0){
  		db_fieldsmemory($result_escr,0);
  	}
	if($j93_averbagrupo == 6){
	  $sqlguia = "select averbaguia.*,averbaguiaitbi.*,it03_nome 
							  from averbaguia 
							  left join averbaguiaitbi on j104_sequencial=j103_averbaguia  
							  left join itbinome on j104_guia = it03_guia 
							  where j104_averbacao = $chavepesquisa 
								      and upper(it03_tipo) = 'C' 
											and it03_princ is true ";
	 
	  $resultguia = db_query($sqlguia);
	  $linhasguia = pg_num_rows($resultguia);
	  if($linhasguia>0){
		db_fieldsmemory($resultguia,0);
		$nome = $it03_nome;
		 $guia = 1;
				
	  }else{
	  	//se não encotrar é pr é sem guia itbi
			$sqlGuiaSemItbi = "select * from averbaguia where j104_averbacao = $chavepesquisa ";
			$rsGuiaSemItbi  = db_query($sqlGuiaSemItbi);
			$linhasGuiaSemItbi = pg_num_rows($rsGuiaSemItbi);
			if($linhasGuiaSemItbi>0){
				db_fieldsmemory($rsGuiaSemItbi,0);
				$guianao = $j104_guia;
			  $guia = 2;
			}
	  }
	}
	if($j93_averbagrupo == 5){
	  $sqlsentenca = "select * from averbadecisaojudicial  
	                  where j101_averbacao = $chavepesquisa";
      $resultsentenca = db_query($sqlsentenca);
	  $linhassentenca = pg_num_rows($resultsentenca);
	  if($linhassentenca>0){
		db_fieldsmemory($resultsentenca,0);
		
	  }
	}
	if($j93_averbagrupo == 4){
	  $sqlformal = "select * from averbaformalpartilha 
	                left join averbaformalpartilhacgm on j102_averbaformalpartilha = j100_sequencial 
	                where j100_averbacao = $chavepesquisa";
	  $resultformal = db_query($sqlformal);
	  $linhasformal = pg_num_rows($resultformal);
	  if($linhasformal>0){
		db_fieldsmemory($resultformal,0);
		$z01_numcgm1 = $j102_numcgm;
	  }
	 
	}
   if ($j75_situacao==2){
   	$db_opcao = 3;
   	$db_botao = false;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmaverbacao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($claverbacao->erro_campo!=""){
      echo "<script> document.form1.".$claverbacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claverbacao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}

if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.averbacgm.disabled=false;
         top.corpo.iframe_averbacgm.location.href='cad1_averbacgm001.php?j76_averbacao=".@$j75_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('averbacgm');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}

 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
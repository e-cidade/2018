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

  	require("libs/db_stdlib.php");
  	require("libs/db_conecta.php");
  	//include("libs/db_sessoes.php");
  	include("libs/db_sql.php");
	parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

  	if($tipo_cert==1){
  		$tipo = "Positiva";
  	}else if($tipo_cert==0){
    	$tipo = "Regular";
  	}else{
    	$tipo = "Negativa";
  	}

?>
<html>
	<head>
    	<title>Documento sem t&iacute;tulo</title>
    	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
         <link href="estilos.css" rel="stylesheet" type="text/css">
    	<script language="JavaScript" src="scripts/scripts.js"></script>
			<script>

			function js_certidao(){
				origem   = '';
				titulo   = '';
				textarea = '';
				tipo     = '';
				codproc  = '';
				
			    titulo   = document.form1.origem.value;
			    origem   = document.form1.codorigem.value;
				codproc  = document.form1.codproc.value;	  
				textarea = document.form1.textarea.value;
				tipo     = document.form1.tipo.value;

		//		alert('cod-'+origem+'\ntitulo-'+titulo+'\ntext area-'+textarea+'\ntipo-'+tipo+'\ncodproc-'+codproc);
				if(confirm('Emite Certidão ' + (tipo==1?'Positiva':(tipo==0?'Regular':'Negativa')))==true){
					if(document.form1.cadrecibo.value == 't'){
						js_recibo(titulo,origem,codproc,textarea,tipo);	
					}else{
						jan = window.open('cai2_emitecnd001.php?titulo='+titulo+'&origem='+origem+'&textarea='+textarea+'&tipo='+tipo+'&codproc='+codproc,'','weidth='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
						jan.moveTo(0,0);
					}
				}
			}

			function js_recibo(titulo,origem,codproc,textarea,tipo){
				js_OpenJanelaIframe('top.corpo','db_recibo','cai4_recibo001.php?mostramenu=t&titulo='+titulo+'&origem='+origem+'&codproc='+codproc+'&textarea='+textarea+'&tipo='+tipo,'Cadastro de recibo',true);
			}

			function js_controlatextarea(objt,max,dv){
			  obj = eval('document.form1.'+objt);
			  atu = max-obj.value.length;
			  //document.getElementById(eval('dv')).innerHTML='Caracteres disponiveis : '+atu+' de '+max ;
			  if(obj.value.length > max){
				  alert('A mensagem pode ter no máximo '+max+' caracteres !');
				  obj.value = obj.value.substr(0,max);
				  //document.getElementById(eval('dv')).innerHTML='Caracteres disponiveis : 0 de '+max ;
				  //obj.select();
				  obj.focus();
			  }
			  if(obj.value.length == 0){
				  //document.getElementById(eval('dv')).innerHTML='';
			  }
			}

             
			
			</script>
  	</head>
  	<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" onload="parent.document.getElementById('processando').style.visibility = 'hidden';document.getElementById('codproc').focus();" topmargin="0" marginwidth="0" marginheight="0">
		<center>
			<form method="post" name="form1" target="certreg">
				<input type="hidden" name="tipo" value="">
    			<input type="hidden" name="cadrecibo" value="">
    			<input type="hidden" name="origem" value="">
    			<input type="hidden" name="codorigem" value="">
				<?	
					if(isset($matric)){
		  		?>
	  			<input type="hidden" name="matric" value="<?=$matric?>">	
	  	  		<?
					}else if(isset($numcgm)){
			  	?>
				<input type="hidden" name="numcgm" value="<?=$numcgm?>">
			  	<?
					}else if(isset($inscr)){
			  	?>
				<input type="hidden" name="inscr" value="<?=$inscr?>">
			  	<?
					}else{
			  	?>
				<input type="hidden" name="naolibera" value="naolibera">
			  	<?
					}
				?>
				<table width="100%">
					<tr>
				    	<td align="center"><font face="Arial, Helvetica, sans-serif"><strong>Certid&atilde;o 
		    		  		<?=@$tipo?> de D&eacute;bitos</strong></font></td>
		  	  		</tr>
		  			<tr>
		    			<td>
		      				<table width="100%">
		        				<tr> 
		          					<td width="14%" align="right"><font face="Arial, Helvetica, sans-serif"><strong>Processo:</strong></font></td>
		          					<td width="86%"><input name="codproc" type="text" id="codproc" size="10" maxlength="10">
		          					           
		        				</tr>
		        				<tr> 
		          					<td align="right" valign="top"><font face="Arial, Helvetica, sans-serif"><strong>Hist&oacute;rico:</strong></font></td>
		          					<td><textarea name="textarea" cols="60" rows="5" onkeyup='js_controlatextarea(this.name,900,"r");'></textarea></td>	
                                </tr>
		        				<tr> 
                                    <td align="center" colspan=2><div id='r'>  </div></td>

		        				</tr>
		      				</table>
		    			</td>
		  			</tr>
		  			<tr>
		    			<td align="center"><input name="certidao" type="button" id="certidao" value="Emite Certid&atilde;o" onClick="js_certidao();"></td>
		  			</tr>
				</table>
			</form>
		</center>
  	</body>
</html>
<script>
     document.form1.origem.value = parent.document.form2.tipo_filtro.value;
     document.form1.codorigem.value = parent.document.form2.cod_filtro.value;
//	 alert(document.form1.origem.value+' - '+document.form1.codorigem.value);

</script>

<?
//	db_postmemory($HTTP_POST_VARS,2);  
//	db_postmemory($HTTP_SERVER_VARS,2);
//  db_msgbox("tipo - ".$tipo_cert);  	

    flush();	
	db_postmemory($HTTP_POST_VARS);  
	db_postmemory($HTTP_SERVER_VARS);
	
  	if($tipo_cert==1){
	    echo "<script> document.form1.tipo.value = 1;</script>"; 
  	}else if($tipo_cert==0){
	    echo "<script> document.form1.tipo.value = 0;</script>"; 
  	}else{
	    echo "<script> document.form1.tipo.value = 2;</script>"; 
  	}
    flush();	
	$rsNumpref = db_query("select * from numpref where k03_anousu = ".db_getsession("DB_anousu"));
//	db_criatabela($rsNumpref);
	$numrows = pg_numrows($rsNumpref);
	if ($numrows>0){
	   db_fieldsmemory($rsNumpref,0);
       if(isset($k03_reccert) && $k03_reccert == 't'){
			echo "<script>document.form1.cadrecibo.value = 't';</script>"; 
	   }
	}
	
?>
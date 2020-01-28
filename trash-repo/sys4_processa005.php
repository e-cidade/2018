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

//forms
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<?
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  // Tabelas

  $sql = "select nomearq as nometab
          from db_sysarquivo
               where codarq = $codarq";
  $resulta = pg_exec($sql);
  db_fieldsmemory($resulta,0);

// Tabelas
  $qr = "where nomearq = '$nometab'";
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
     echo "Não foi encontrada nenhum módulo com o nome de $nometab";
  } else {
    $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
    $arquivo = $root."/forms/"."db_frm".trim($nometab).".php";
    //$arquivo = "/tmp/forms/"."db_frm".trim($nometab).".php";
    if(!is_writable($root."/forms")){
     ?>
     <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar em "forms/" ou não existe.</h6></td></tr></table>
     </body>
     </html>
     <?
     exit;
    } 
 
    if(file_exists($arquivo) && !is_writable($arquivo)){
     ?>
     <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "forms/db_frm<?=$nometab?>"</h6></td></tr></table>
     </body>
     </html>
     <?
     exit;
    } 

    umask(74);
    $fd = fopen($arquivo,"w");
    fputs($fd,"<?\n");
    for($i = 0;$i < $numrows;$i++) {
	  $varpk = ""; 
      $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                       where a.codarq = ".pg_result($result,$i,"codarq"));
      if(pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
		$virgula = "";
		$virconc = "";
        for($p = 0;$p < $Npk;$p++) {
          $varpk .= "##".trim(pg_result($pk,$p,"nomecam"));
        } 
      }
      $campo = pg_exec("select c.*
                          from db_syscampo c
                               inner join db_sysarqcamp a   on a.codcam = c.codcam
                          where codarq = ".pg_result($result,$i,"codarq").
			              " order by a.seqarq");
	  $Ncampos = pg_numrows($campo);
	  if($Ncampos > 0) {
        fputs($fd,"//MODULO: ".trim(pg_result($result,$i,"nomemod"))."\n");        
        fputs($fd,'$cl'.trim(pg_result($result,$i,"nomearq")).'->rotulo->label();'."\n");

        // testar se existe chaves estrangeiras deste arquivo
        $forkey = pg_exec("select distinct f.codcam,b.nomecam as nomecerto,f.referen, q.nomearq, c.camiden,x.nomecam as nomepri, a.nomecam, a.tamanho,f.tipoobjrel
                          from db_sysforkey f 
						       inner join db_sysprikey c on c.codarq = f.referen 
						       inner join db_syscampo a on a.codcam = c.camiden 
						       inner join db_syscampo x on x.codcam = c.codcam 
						       inner join db_syscampo b on b.codcam = f.codcam 
						       inner join db_sysarquivo q on q.codarq = f.referen 
                          where f.codarq = ".pg_result($result,$i,"codarq")); 
	    $Nforkey = pg_numrows($forkey);
		$campofk="";
		$campofktipo="";
	    if($Nforkey > 0) { 
          fputs($fd,'$clrotulo = new rotulocampo;'."\n");
		  for($fk=0;$fk<$Nforkey;$fk++){
		    $campofk .= "#".trim(pg_result($forkey,$fk,'codcam'));
		    if(trim(pg_result($forkey,$fk,'tipoobjrel')=="1")){
		      $campofktipo .= "#".trim(pg_result($forkey,$fk,'codcam'));
		    }
            fputs($fd,'$clrotulo->label("'.trim(pg_result($forkey,$fk,'nomecam')).'");'."\n");
		  }
        }
        fputs($fd,'?>'."\n");
        fputs($fd,'<form name="form1" method="post" action="">'."\n");
        fputs($fd,'<center>'."\n");
        fputs($fd,'<table border="0">'."\n");
	$gera_oid = false;
	for($j = 0;$j < $Ncampos;$j++) {
          fputs($fd,'  <tr>'."\n");
          //coluna label
          fputs($fd,'    <td nowrap title="<?=@$T'.trim(pg_result($campo,$j,"nomecam")).'?>">'."\n");
          if($varpk=="" && $gera_oid == false){
	     $gera_oid = true;
             fputs($fd,'    <input name="oid" type="hidden" value="<?=@$oid?>">'."\n");
	  }
	  $funcaojava = '""';
	  if( strpos($campofk,trim(pg_result($campo,$j,"codcam"))) > 0 ){

	     if( strpos($campofktipo,trim(pg_result($campo,$j,"codcam"))) == 0 ){
                fputs($fd,'       <?'."\n");
	        $funcaojava = '"js_pesquisa'.trim(pg_result($campo,$j,"nomecam")).'(true);"';
                fputs($fd,'       db_ancora(@$L'.trim(pg_result($campo,$j,"nomecam")).','.$funcaojava.',$db_opcao);'."\n");
                fputs($fd,'       ?>'."\n");
	        $funcaojava = '" onchange=\'js_pesquisa'.trim(pg_result($campo,$j,"nomecam")).'(false);\'"';
	     }else{
                fputs($fd,'       <?=@$L'.trim(pg_result($campo,$j,"nomecam")).'?>'."\n");
	     }
	  }else{
             fputs($fd,'       <?=@$L'.trim(pg_result($campo,$j,"nomecam")).'?>'."\n");
          }
          fputs($fd,'    </td>'."\n");
          fputs($fd,'    <td> '."\n");
 	  //$x = pg_result($campo,$j,"tipo");
 	  $xc = pg_result($campo,$j,"conteudo");
	  
	  // coloca select    
	  if( strpos($campofktipo,trim(pg_result($campo,$j,"codcam"))) > 0 ){
	    for($fk=0;$fk<$Nforkey;$fk++){
              if(pg_result($campo,$j,"codcam")==pg_result($forkey,$fk,'codcam') && pg_result($forkey,$fk,'tipoobjrel')==1 ){
                fputs($fd,'       <?'."\n");
	        fputs($fd,'       include("classes/db_'.trim(pg_result($forkey,$fk,'nomearq')).'_classe.php");'."\n");
	        fputs($fd,'       $cl'.trim(pg_result($forkey,$fk,'nomearq')).' = new cl_'.trim(pg_result($forkey,$fk,'nomearq')).';'."\n");
	        fputs($fd,'       $result = $cl'.trim(pg_result($forkey,$fk,'nomearq')).'->sql_record($cl'.trim(pg_result($forkey,$fk,'nomearq')).'->sql_query(');
		$virgulapk = "";
		for($pkk=0;$pkk<pg_numrows($pk);$pkk++){
		  fputs($fd,$virgulapk.'""');
                  $virgulapk = ",";
		}
		if($virgulapk==""){
		  fputs($fd,'""');
                }		  
		fputs($fd,',"",""));'."\n");
	        fputs($fd,'       db_selectrecord("'.trim(pg_result($campo,$j,"nomecam")).'",$result,true,$db_opcao);'."\n");
                fputs($fd,'       ?>'."\n");
	    
	      }
	    }
	    
          }else{


	  
          $verificadep = "select defcampo,defdescr
	                  from db_syscampodef
			  where codcam = ".pg_result($campo,$j,"codcam");
          $verres = pg_exec($verificadep);
	  if($verres==false || pg_numrows($verres)==0){

	  
          if(substr($xc,0,4)!="date"){  
            if( (substr($xc,0,3)=="cha") || ( substr($xc,0,3)=="var") || (substr($xc,0,3)=="flo") ){
		    if(strpos("--".$varpk,trim(pg_result($campo,$j,"nomecam")) ) != 0 ){
		      //chave primaria
		      fputs($fd,"<?"."\n");
		      fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',3,".$funcaojava.")"."\n");
		      fputs($fd,"?>"."\n");
		    }else{
		      fputs($fd,"<?"."\n");
		      fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
		      fputs($fd,"?>"."\n");
                    }
		    }else if(substr($xc,0,3)=="boo"){
		      fputs($fd,"<?"."\n");
		      fputs($fd,'$x = array("f"=>"NAO","t"=>"SIM");'."\n");
		      fputs($fd,"db_select('".trim(pg_result($campo,$j,"nomecam"))."',".'$x'.",true,$"."db_opcao,".$funcaojava.");"."\n");
		      fputs($fd,"?>"."\n");
		    }else if(substr($xc,0,3)=="tex"){
		      fputs($fd,"<?"."\n");
                      fputs($fd,"db_textarea('".trim(pg_result($campo,$j,"nomecam"))."'".',0,0,$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n"); 
		      fputs($fd,"?>"."\n");
		    }else{
		      if(strpos("--".$varpk,trim(pg_result($campo,$j,"nomecam")) ) != 0 ){
	                fputs($fd,"<?"."\n");
	                if(strpos(pg_result($campo,$j,"nomecam"),"anousu")>0){
  	                   fputs($fd,"$".trim(pg_result($campo,$j,"nomecam"))." = db_getsession('DB_anousu');"."\n");
			}
  	                fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
		        fputs($fd,"?>"."\n");
		      }else{ 
			fputs($fd,"<?"."\n");
  	                if(strpos(pg_result($campo,$j,"nomecam"),"anousu")>0){
  	                   fputs($fd,"$".trim(pg_result($campo,$j,"nomecam"))." = db_getsession('DB_anousu');"."\n");
  	                   fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',3,".$funcaojava.")"."\n");
			}else{
  	                   fputs($fd,"db_input('".trim(pg_result($campo,$j,"nomecam"))."'".','.trim(pg_result($campo,$j,"tamanho")).',$I'.trim(pg_result($campo,$j,"nomecam")).",true,'text',$"."db_opcao,".$funcaojava.")"."\n");
			}
		        fputs($fd,"?>"."\n");
	              }
		    }
          }else{
		    fputs($fd,"<?"."\n");
	        fputs($fd,"db_inputdata('".trim(pg_result($campo,$j,"nomecam"))."',@$".trim(pg_result($campo,$j,"nomecam"))."_dia,@$".trim(pg_result($campo,$j,"nomecam"))."_mes,@$".trim(pg_result($campo,$j,"nomecam"))."_ano,true,'text',$"."db_opcao,".$funcaojava.")"."\n");
		    fputs($fd,"?>"."\n");
		  }
 		  if($funcaojava != '""'){
		     // strpos($campofk,pg_result($campo,$j,"codcam")) > 0 ){
                     fputs($fd,'       <?'."\n");
		     for($fk=0;$fk<$Nforkey;$fk++){
                       if( pg_result($forkey,$fk,'codcam') == pg_result($campo,$j,"codcam")){
  	                 fputs($fd,"db_input('".trim(pg_result($forkey,$fk,"nomecam"))."'".','.trim(pg_result($forkey,$fk,"tamanho")).',$I'.trim(pg_result($forkey,$fk,"nomecam")).",true,'text',3,'')"."\n");
		      }
		  }
             fputs($fd,'       ?>'."\n");
	  }


	  }else{

	    fputs($fd,"<?"."\n");
	    fputs($fd,'$x = array(');
	    $virgula = "";
            for($ver=0;$ver<pg_numrows($verres);$ver++){
               
	       fputs($fd,$virgula."'".pg_result($verres,$ver,'defcampo')."'=>'".pg_result($verres,$ver,'defdescr')."'");
               $virgula = ",";
	    }
	    fputs($fd,");"."\n");
            fputs($fd,"db_select('".trim(pg_result($campo,$j,"nomecam"))."',".'$x'.",true,$"."db_opcao,".$funcaojava.");"."\n");
            fputs($fd,"?>"."\n");
	
	  }
	  
	  }

          fputs($fd,'    </td>'."\n");
          fputs($fd,'  </tr>'."\n");
		} 
        fputs($fd,'  </table>'."\n");     
        fputs($fd,'  </center>'."\n");     
        fputs($fd,'<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >'."\n");
        fputs($fd,'<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >'."\n");
        fputs($fd,'</form>'."\n");    
		//
		// escreve os java scripts para controle dos iframe
		fputs($fd,'<script>'."\n");
        for($fk=0;$fk<$Nforkey;$fk++){	
          fputs($fd,'function js_pesquisa'.trim(pg_result($forkey,$fk,"nomecerto")).'(mostra){'."\n");
          fputs($fd,'  if(mostra==true){'."\n");
          //fputs($fd,"    db_iframe.jan.location.href = 'func_".trim(pg_result($forkey,$fk,'nomearq')).".php?funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."1|0|1';"."\n");


          fputs($fd,"    js_OpenJanelaIframe('top.corpo','db_iframe_".trim(pg_result($forkey,$fk,'nomearq'))."','func_".trim(pg_result($forkey,$fk,'nomearq')).".php?funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."1|".trim(pg_result($forkey,$fk,'nomepri'))."|".trim(pg_result($forkey,$fk,'nomecam'))."','Pesquisa',true);"."\n");
	
	  
          //fputs($fd,"    db_iframe.mostraMsg();"."\n");
          //fputs($fd,"    db_iframe.show();"."\n");
          //fputs($fd,"    db_iframe.focus();"."\n"); 
          fputs($fd,"  }else{"."\n");
          fputs($fd,"     if(document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value != ''){ "."\n"); 
          fputs($fd,"        js_OpenJanelaIframe('top.corpo','db_iframe_".trim(pg_result($forkey,$fk,'nomearq'))."','func_".trim(pg_result($forkey,$fk,'nomearq')).".php?pesquisa_chave='+document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value+'&funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."','Pesquisa',false);"."\n");
	  fputs($fd,"     }else{"."\n");
          fputs($fd,"       document.form1.".trim(pg_result($forkey,$fk,'nomecam')).".value = ''; "."\n"); 
	  fputs($fd,"     }"."\n");
           //fputs($fd,"    db_iframe.jan.location.href = 'func_".trim(pg_result($forkey,$fk,'nomearq')).".php?pesquisa_chave='+document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value+'&funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."';"."\n");
          fputs($fd,"  }"."\n");
          fputs($fd,"}"."\n");
          fputs($fd,"function js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."(chave,erro){"."\n");
          fputs($fd,"  document.form1.".trim(pg_result($forkey,$fk,'nomecam')).".value = chave; "."\n"); 
		  
          fputs($fd,"  if(erro==true){ "."\n"); 
          fputs($fd,"    document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".focus(); "."\n"); 
          fputs($fd,"    document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value = ''; "."\n"); 
          fputs($fd,"  }"."\n");
		  
          fputs($fd,"}"."\n");

          fputs($fd,"function js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."1(chave1,chave2){"."\n");
          fputs($fd,"  document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value = chave1;"."\n");
          fputs($fd,"  document.form1.".trim(pg_result($forkey,$fk,'nomecam')).".value = chave2;"."\n");
          fputs($fd,"  db_iframe_".trim(pg_result($forkey,$fk,'nomearq')).".hide();"."\n");
          fputs($fd,"}"."\n");
        }
	fputs($fd,"function js_pesquisa(){"."\n");
		

        //fputs($fd,"  db_iframe.jan.location.href = 'func_".trim(pg_result($result,$i,'nomearq')).".php?funcao_js=parent.js_preenchepesquisa|0");

	
        if(pg_numrows($pk) > 0) {

          fputs($fd,"  js_OpenJanelaIframe('top.corpo','db_iframe_".trim(pg_result($result,$i,'nomearq'))."','func_".trim(pg_result($result,$i,'nomearq')).".php?funcao_js=parent.js_preenchepesquisa|".trim(pg_result($pk,0,'nomecam')));
	  
          $Npk = pg_numrows($pk);
		  $virgula = "";
		  $virconc = "";
          for($p = 1;$p < $Npk;$p++) {
	       fputs($fd,"|".trim(pg_result($pk,$p,'nomecam')));
          }	
	}else{
          fputs($fd,"  js_OpenJanelaIframe('top.corpo','db_iframe_".trim(pg_result($result,$i,'nomearq'))."','func_".trim(pg_result($result,$i,'nomearq')).".php?funcao_js=parent.js_preenchepesquisa|0");
	
	}
		
	//fputs($fd,"';"."\n");
	fputs($fd,"','Pesquisa',true);"."\n");
		
        //fputs($fd,"  db_iframe.mostraMsg();"."\n");
        //fputs($fd,"  db_iframe.show();"."\n");
        //fputs($fd,"  db_iframe.focus();"."\n");
        fputs($fd,"}"."\n");
        fputs($fd,"function js_preenchepesquisa(chave");
		
        if(pg_numrows($pk) > 1) {
          $Npk = pg_numrows($pk);
		  $virgula = "";
		  $virconc = "";
          for($p = 1;$p < $Npk;$p++) {
             fputs($fd,",chave".$p);
          }	
		}
		
	fputs($fd,"){"."\n");
        fputs($fd,"  db_iframe_".trim(pg_result($result,$i,'nomearq')).".hide();"."\n");
	fputs($fd,'  <?'."\n");
	fputs($fd,'  if($db_opcao!=1){'."\n");
        fputs($fd,"    echo \" location.href = '\".basename($"."GLOBALS[\"HTTP_SERVER_VARS\"][\"PHP_SELF\"]).\"?chavepesquisa='+chave");
	if(pg_numrows($pk) > 1) {
          $Npk = pg_numrows($pk);
	  $virgula = "";
	  $virconc = "";
          for($p = 1;$p < $Npk;$p++) {
             fputs($fd,"+'&chavepesquisa".$p."='+chave".$p);
          }	
	}
	
	fputs($fd,"\";"."\n");
	fputs($fd,"  }"."\n");
	fputs($fd,'  ?>'."\n");
		
	
	//	fputs($fd,';'."\n");
        fputs($fd,"}"."\n");
        fputs($fd,"</script>"."\n");
      	// fim dos java scripts
	  }
   }
  } 
  fclose($fd);  
  
?>

<table width="100%"><tr><td align="center"><h3>Concluído...</h3></td></tr></table>

</body>
</html>
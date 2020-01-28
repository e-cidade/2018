<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_procandam_classe.php");
require_once ("classes/db_proctransfer_classe.php");
require_once ("classes/db_protprocesso_classe.php");
require_once ("classes/db_proctransand_classe.php");
require_once ("dbforms/db_funcoes.php");
$db_opcao = 1;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clprocandam = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clprotprocesso = new cl_protprocesso;
$clproctransand = new cl_proctransand;
$rotulo = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_requer");
$rotulo->label("p58_numcgm");
$rotulo->label("p58_id_usuario");
$rotulo->label("p58_coddepto");
$rotulo->label("z01_nome");
$rotulo->label("numeroProcesso");

 if (!isset($grupo)) {
 	 $grupo = 1;
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
<script>
function js_mostra_andam(processo){ 
   js_OpenJanelaIframe('top.corpo','db_iframe','pro3_conspro002.php?codproc='+processo,'Pesquisa',true);
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="40">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form method="post" action="" name="form1">
	<table align="center">
	  <tr> 
	    <td> 
	      <fieldset>
	        <legend>
	          <b>Consulta de Processos</b>
	        </legend>
			    <table>
            <tr>
              <td nowrap title="<?=@$Tp58_codproc?>">
                 <strong>Numero do Processo:</strong>
              </td>
              <td> 
                <?
                  db_input('numeroProcesso',10,"",true,'text',$db_opcao,"");
                ?>
               </td>
            </tr>
          
					  <tr>
					    <td nowrap title="<?=@$Tp58_codproc?>">
					       <?
					         db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",$db_opcao);
					       ?>
					    </td>
					    <td> 
								<?
								  db_input('p58_codproc',10,$Ip58_codproc,true,'text',$db_opcao," onchange='js_pesquisap58_codproc(false);'");
								  db_input('p58_requer',40,$Ip58_requer,true,'text',3,'');
								?>
					    </td>
					  </tr>
					  <?php
			
			        if ($grupo == 1) {
					  
					  ?>
			      <tr>
			        <td title="<?=$Tp58_numcgm;?>">
			          <?
			            db_ancora(@$Lp58_numcgm,"js_pesquisap58_numcgm(true);",1);
			          ?>
			        </td>
			        <td>
			          <?
			            db_input("p58_numcgm",10,"",true,"text", $db_opcao, "onchange='js_pesquisap58_numcgm(false);'");
			            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
			          ?>
			        </td>      
			      </tr>  
			      <?php
			
			        }
			      
			      ?>
		      </table>
	      </fieldset>
	    </td>
	  </tr>
	  <tr>  
      <td align="center">
        <input type="submit" name="db_opcao" value="Consultar">
        <input type="reset" value="Limpar">
      </td>
    </tr>
	</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisap58_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?grupo=<?=$grupo?>&funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome&sCampoPesquisa=p58_codproc','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?grupo=<?=$grupo?>&pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprotprocesso&sCampoPesquisa=p58_codproc','Pesquisa',false);
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1; 
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_proc.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_procarquiv.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_pesquisap58_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_c','func_nome.php?funcao_js=parent.js_mostracgm1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_c','func_nome.php?pesquisa_chave='+document.form1.p58_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){ 
    document.form1.p58_numcgm.focus(); 
    document.form1.p58_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.p58_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_c.hide();
}

onLoad=document.form1.p58_codproc.select();
onLoad=document.form1.p58_codproc.focus();

</script>
<?

if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Consultar"){
 
	$sql = "select p58_codproc, 
                 z01_nome,
                 p51_descr,
                 p58_obs 
            from protprocesso 
                 inner join cgm      on p58_numcgm = z01_numcgm
                 inner join tipoproc on p58_codigo = p51_codigo 
           where p51_tipoprocgrupo = $grupo";
 
  $where = "";
 
  if (isset($numeroProcesso) && !empty($numeroProcesso)) {
    
    $aNumeroProcesso = explode("/", $numeroProcesso);
    $where .= " and p58_numero = '".$aNumeroProcesso[0]."'" ;
    if (count($aNumeroProcesso) > 1) {
      
      $where .= " and p58_ano = ".$aNumeroProcesso[1] ;
    }
  }
  
  if (@$p58_codproc != "") {
    $where .= " and p58_codproc = ".@ $p58_codproc ;     
  } elseif (@$p58_requer != ""){
    $where .= " and p58_requer = '".@ $p58_requer."'";
  }
 
  if (@$p58_numcgm != ""){
    $where .= " and p58_numcgm = ". @$p58_numcgm;
  }
  $sql = $sql.$where;
  
  $res = db_query($sql);

	if (pg_numrows($res) > 0) {
		
	  if (pg_numrows($res) > 1) {
	  	
	    echo"<script>
	    
	    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?grupo=$grupo&chave_p58_numcgm=$p58_numcgm&funcao_js=parent.js_mostraproc|p58_codproc','Pesquisa',true);
	    
	    function js_mostraproc(chave){
	      js_mostra_andam(chave);
	      db_iframe_proc.hide();
	    }
	    </script>";
	  } else {
	  	
	    db_fieldsmemory($res,0);
	    echo "<script>js_mostra_andam('$p58_codproc')</script>";
	  }
	  
	} else {
	  echo "<script>
			      alert('Processo não cadastrado!!!');
	          location.href = 'pro3_conspro001.php?grupo=$grupo'; 
			    </script>";
	}
}


?>
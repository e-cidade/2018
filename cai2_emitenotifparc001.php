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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k50_notifica');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$instit = db_getsession("DB_instit");

$db_botao = true;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emiteseed(){

  sQuery  = '?notifica='+document.form1.k50_notifica.value;
  sQuery += '&notifparc='+true;      
  jan = window.open('cai2_emitenotif004.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}


function js_emitear(){
  
  sQuery  = '?notifica='+document.form1.k50_notifica.value;
  sQuery += '&notifparc='+true;
  
  jan = window.open('cai2_emitenotif005.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}

function js_emite(tiporel){

  sQuery  = '?ordem='+document.form1.selOrdem.value;
  sQuery += '&notifparc='+true;
  sQuery += '&notifica='+document.form1.k50_notifica.value;
  sQuery += '&fonte='+document.form1.iFonte.value;
  sQuery += '&tiporel='+tiporel;
  sQuery += '&tipo=""';
  sQuery += '&tipoparc=true';
  sQuery += '&tratamento='+document.form1.selTipoSegPag.value;
  sQuery += '&imprimirmesmoembranco='+document.form1.selCgmVazio.value;
  sQuery += '&datavenc='+document.form1.datavenc_ano.value+'-'+document.form1.datavenc_mes.value+'-'+document.form1.datavenc_dia.value;
  sQuery += '&imprimirtimbre='+document.form1.selImpTimbre.value;  
  
  jan = window.open('cai2_emitenotif002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" style='padding-top:25px;'>
    <form name="form1" method="post" action="" >
      <tr>
        <td>
          <fieldset>
          	<legend align="center" ><b>Emissão de Notificações Parciais </b></legend>
          	<table>
          	  <tr>
		        <td align="right">
		          <?
					db_ancora($Lk50_notifica,"js_pesquisaNotif(true);",4);
		          ?>
		        </td>
		        <td align="left">
		          <?
		          	db_input("k50_notifica",10,$Ik50_notifica,true,"text",1,"js_pesquisaNotif(false);");
		          ?>
		        </td>
		      </tr>
              <tr>
		        <td align="right">
		          <b>Ordem :</b>
		        </td>
		        <td align="left">
		          <?
		          	$aOrdem = array("a"=>"Alfabética","n"=>"Numérica",'t'=>'Notificação');
					db_select('selOrdem',$aOrdem,true,4,"style='width:140px;'");
		          ?>
		        </td>
		      </tr>
		      <tr>
		        <td align="right">
		        	<b>Utilizar end do CGM quando estiver em branco:</b>
		        </td>
		        <td align="left">
		          <?
		          	$aCgmVazio= array("n"=>"Não","s"=>'Sim');
		          	db_select('selCgmVazio',$aCgmVazio,true,4,"style='width:140px;'");
		          ?>
		        </td>
		      </tr>
		      <tr>
		        <td align="right">
		        	<b>Emissão do Timbre:</b>
		        </td>
		        <td align="left">
		          <?
		          	$aImpTimbre = array("1"=>"Emitir Ambos","2"=>"Somente Interno","3"=>"Somente Externo","4"=>"Sem Timbre");
		          	db_select('selImpTimbre',$aImpTimbre,true,4,"style='width:140px;'");
		          ?>
		        </td>
		      </tr>
		      <tr>
				<td align="right">
				  <b>Vencimento Recibo:</b>
				<td align="left">
				  <?
		      	    db_inputdata('datavenc',"","","", true, 'text', 4)
				  ?>
			    </td>
			  </tr>
		      <tr>
		        <td align="right">
			      <b>Fonte do Texto : </b>
			    </td>
			    <td align="left">
				  <?
		 		    $iFonte=10;
		 		    db_input('iFonte', 10, "", true, 'text',1);
				  ?>
		        </td>
		      </tr>
    		  <tr>
		        <td align="right">
		        	<b>Tipo Segunda Página :</b>
		        </td>
		        <td align="left">
		          <?
		          
		            $aTipoSegundaPag["1"] = "Sempre do CGM";
		            
		            $sSqlOrdEndEnt  = " select defcampo,	  "; 
		            $sSqlOrdEndEnt .= "	       defdescr		  ";
		            $sSqlOrdEndEnt .= "   from db_syscampodef "; 
		            $sSqlOrdEndEnt .= "  where codcam = 9856  ";
		            
		            $rsOrdEndEnt   = pg_exec($sSqlOrdEndEnt) or die($sSqlOrdEndEnt);
		            $iNroOrdEndEnt = pg_num_rows($rsOrdEndEnt);
		            
		            for ($x=0; $x < $iNroOrdEndEnt; $x++) {
		              $oOrdEndEnt = db_utils::fieldsMemory($rsOrdEndEnt,$x);
		              $oOrdEndEnt->defcampo += 10;
		              $aTipoSegundaPag[$oOrdEndEnt->defcampo] = $oOrdEndEnt->defdescr;
		            }
		
		            db_select('selTipoSegPag',$aTipoSegundaPag,true,4,"style='width:250px;'");
		            
		          ?>
		        </td>
		      </tr>
    		</table>
    	  </fieldset>
  		</td>
 	  </tr>
	  <tr>
        <td colspan="2" align = "center"> 
         
         <!--   <input name="db_opcao"  type="button" id="db_opcao" value="Notificao sem pagina de endereco" onClick="js_emite(1);"> -->
         
         <input name="db_opcao"  type="button" id="db_opcao" value="Emite Notificação" onClick="js_emite(11);">
         <input name="db_opcao3" type="button" id="db_opcao" value="Aviso Débito" 					  onClick="js_emite(3);">
         <input name="db_opcao2" type="button" id="db_opcao" value="SEED" 							  onClick="js_emiteseed();">
         <input name="db_opcao4" type="button" id="db_opcao" value="AR" 							  onClick="js_emitear();">
        </td>
      </tr>
    </form>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaNotif(lMostra){
  if(lMostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_notificacoes','func_notidebitosreg.php?chave_nome=true&funcao_js=parent.js_mostraNotif1|k43_notifica','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_notificacoes','func_notidebitosreg.php?pesquisa_chave='+document.k50_notifica.value+'&funcao_js=parent.js_mostraNotif','Pesquisa',false);
  }
}

function js_mostraNotif(iNotif,lErro){
  if(lErro==true){ 
    document.form1.k50_notifica.focus(); 
    document.form1.k50_notifica.value = ''; 
  }else{
    document.form1.k50_notifica.value = iNotif;
  }  
}

function js_mostraNotif1(iNotif){
  document.form1.k50_notifica.value = iNotif;
  db_iframe_notificacoes.hide();
}

</script>
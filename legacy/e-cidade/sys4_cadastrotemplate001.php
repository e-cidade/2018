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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_relatorio_classe.php");
include("classes/db_db_geradorrelatoriotemplate_classe.php");

$oPost = db_utils::postMemory($_POST);
$oFile = db_utils::postMemory($_FILES);

$cldb_relatorio = new cl_db_relatorio();
$cldb_geradorrelatoriotemplate = new cl_db_geradorrelatoriotemplate();

$cldb_relatorio->rotulo->label();

  if (isset($oPost->method) && $oPost->method  == "incluir") {
  	
	 $lErro = false;
	 
     db_inicio_transacao();
     
	 $oidGrava = pg_lo_create();

	 $dados    = file_get_contents($oFile->arquivo['tmp_name']);
	 
	 if (!$dados) {
  		$sMsgErro = "Falha ao abrir o arquivo [{$oFile->arquivo['tmp_name']}].";	
  		$lErro    = true;
	 }

	 $objeto   = pg_lo_open($conn, $oidGrava, "w");
	
	 if (!$objeto) {
	   $sMsgErro = "Falha ao buscar objedo do banco de dados";
	   $lErro    = true;
	 }

	 $lObjetoEscrito = pg_lo_write($objeto, $dados);
	
	 if (!$lObjetoEscrito) {
  	   $sMsgErro = "Falha na escrita do objedo no banco de dados";
  	   $lErro    = true;
	 }

	 pg_lo_close($objeto);

	 $cldb_geradorrelatoriotemplate->db15_db_relatorio = $oPost->db63_sequencial;
	 $cldb_geradorrelatoriotemplate->db15_documento    = $oidGrava;
	
     $rsConsultaTemplate = $cldb_geradorrelatoriotemplate->sql_record($cldb_geradorrelatoriotemplate->sql_query(null,"db15_sequencial",null, " db15_db_relatorio = {$oPost->db63_sequencial}"));
    
	 if ( $cldb_geradorrelatoriotemplate->numrows > 0 ) {
	   $oTemplate = db_utils::fieldsMemory($rsConsultaTemplate,0);
	   $cldb_geradorrelatoriotemplate->db15_sequencial = $oTemplate->db15_sequencial;
	   $cldb_geradorrelatoriotemplate->alterar($oTemplate->db15_sequencial);
	 } else {
	   $cldb_geradorrelatoriotemplate->incluir(null);		
   	 }
	
	 if ($cldb_geradorrelatoriotemplate->erro_status == 0){
	   $sMsgErro = $cldb_geradorrelatoriotemplate->erro_msg;
  	   $lErro    = true;
	 }

	db_fim_transacao($lErro);
	
  }
	
  

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/geradorrelatorios.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
  <form name="form1" enctype="multipart/form-data" method="post" action="" >
    <table  style="padding-top:15px;" >
      <tr>
        <td>
  		  <fieldset>
  	  	    <legend align="center">
  	  		  <b>Cadastro de Documentos</b>
  	  		</legend>
    		<table>
		      <tr>
			    <td>
			      <b>
			        <?
			          db_ancora("Relatório :","js_pesquisaRelatorio(true)",1,"");
			        ?>
			      </b>
			    </td>
			    <td>
			      <?
				    db_input("db63_sequencial",10,$Idb63_sequencial,true,"text",1,"onChange='js_pesquisaRelatorio(false);'");
				    db_input("db63_nomerelatorio" ,40,"",true,"text",3,"");
				    db_input("method",40,"",true,"hidden",3,"");
			      ?>
			    </td>
			  </tr>
  			  <tr>
    		    <td nowrap title="Selecione o Arquivo fonte">
       			  <b> Arquivo :</b>
    			</td>
    		    <td> 
    			  <?
      				db_input('arquivo',43,'',true,'file',1,"");
    			  ?>
    		   </td>
		     </tr>
			</table>
		  </fieldset>
		</td>
	  </tr>
	  <tr align="center">
	  	<td>
  		  <input type="button" value="Incluir"   onClick="js_verificaTemplate();" />
	  	</td>
	  </tr>
	</table>	  	
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  function js_verificaRel(){
  	var iCodRelatorio = document.form1.db63_sequencial.value;
  	if ( iCodRelatorio == "" ) {
  	  alert("Preencha código do relatório");  	
   	} else {
	    js_consultaVariaveis(iCodRelatorio);
	  }
  }


  function js_consultaVariaveis(iCodRelatorio){
    
  	var sAcao = "consultaVariaveis";
  	
	var sQuery  = "sAcao="+sAcao;
  		sQuery += "&iCodRelatorio="+iCodRelatorio;
   	var url          = "sys4_cadastrotemplateRPC.php";
   	var oAjax        = new Ajax.Request( url, {
                                               method: 'post', 
                                               parameters: sQuery,
                                               onComplete: js_retornoVariaveis
                                              }
                                       );
  
                                           
  }
  
  function js_retornoVariaveis(oAjax){
    
    var iCodRelatorio = document.form1.db63_sequencial.value;
    var aParametros   = new Array();
    var aRetorno 	  = eval("("+oAjax.responseText+")");
    
    if (aRetorno.length > 0) {
      for(var i=0; i < aRetorno.length; i++){
        if(aRetorno[i].sLabel != ""){
		  var sDescricao = aRetorno[i].sLabel; 
	    } else {
	  	  var sDescricao = aRetorno[i].sNome;
	    }
		
	    var sVariavel   = prompt(sDescricao,aRetorno[i].sValor);
	    var objVariavel = new js_criaObjetoVariavel(aRetorno[i].sNome,sVariavel);
	    aParametros[i]  = objVariavel;
	    
      }
	}
	
	js_imprimeRelatorio(iCodRelatorio,js_downloadArquivo,aParametros.toSource()); 
  
  }
  


  function js_verificaTemplate(){
  
	var iCodRelatorio = document.form1.db63_sequencial.value;
	var sArquivo	  = document.form1.arquivo.value;
	
	if( sArquivo == "" || iCodRelatorio == ""){
		alert("Favor preencher todos os campos!");
		return false;
	}
	
 
  	js_divCarregando('Aguarde, processando...','msgBox');
  	
  	var sAcao   = "consultaTemplate";
  	var sQuery  = "sAcao="+sAcao;
    		sQuery += "&iCodRelatorio="+iCodRelatorio;
  		
   	var url     = "sys4_cadastrotemplateRPC.php";
   	var oAjax   = new Ajax.Request( url, {
                                          method: 'post', 
                                          parameters: sQuery,
                                          onComplete: js_retornoVerificaTemplate
                                         }
                                  );
  }
  
  
  function js_retornoVerificaTemplate(oAjax){
  	
  	js_removeObj('msgBox');
  	
  	var aRetorno = eval("("+oAjax.responseText+")");
	
	 	if ( aRetorno.templates.length > 0  ){
	  	  if ( confirm("Já existe um template cadastrado para esse relatório, deseja substituir ?") ) {
		    js_incluir();
		  } else {
		    return false;
		  }
		} else {
		  document.form1.method.value = "incluir";
		  js_incluir();	  
		}
	
  }
  
  
  function js_incluir(){
 	
    document.form1.method.value = "incluir"; 	
    document.form1.submit();

  }	
	
	
	
  function js_retornoInclusao(iCodRelatorio){
    if(confirm("Deseja Imprimir")){
      js_consultaVariaveis(iCodRelatorio);
   	}
  }	
	
	
  function js_pesquisaRelatorio(mostra){
    if (mostra==true) {
      js_OpenJanelaIframe('','db_iframe_db_relatorio','func_db_relatorio.php?lTemplate=true&funcao_js=parent.js_mostrarelatorio1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
    } else {
      if (document.form1.db63_sequencial.value != '') {
        js_OpenJanelaIframe('','db_iframe_db_relatorio','func_db_relatorio.php?lTemplate=true&pesquisa_chave='+document.form1.db63_sequencial.value+'&funcao_js=parent.js_mostrarelatorio','Pesquisa',false);
      } else {
        document.form1.db63_nomerelatorio.value = '';
      }
    }
  }


  function js_mostrarelatorio(chave,erro) {
    
    document.form1.db63_nomerelatorio.value = chave;
    
    if (erro==true) {
      document.form1.db63_sequencial.focus();
      document.form1.db63_sequencial.value = '';
    }
  }


  function js_mostrarelatorio1(chave1,chave2){
    document.form1.db63_sequencial.value 	 = chave1;
    document.form1.db63_nomerelatorio.value  = chave2;
    db_iframe_db_relatorio.hide();  
  }

</script>
<?
	
  if (isset($oPost->method) && $oPost->method  == "incluir") {
		if ($lErro) {
		  db_msgbox($sMsgErro);	 
		} else {
		  db_msgbox("Inclusão feita com sucesso!");
		}
  }	
?>

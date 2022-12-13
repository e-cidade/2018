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

 require_once ("libs/db_stdlib.php");
 require_once ("libs/db_conecta.php");
 require_once ("libs/db_sessoes.php");
 require_once ("libs/db_sql.php");
 require_once ("libs/db_utils.php");
 require_once ("libs/db_app.utils.php");
 require_once ("std/db_stdClass.php");
 require_once ("std/DBLargeObject.php");
 require_once ("classes/db_certidao_classe.php");
  
 parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

 if($tipo_cert==1){
 	$tipo = "Positiva";
 }else if($tipo_cert==0){
 	$tipo = "Regular";
 }else{
 	$tipo = "Negativa";
 }
 
 $sOrigem       = 'C';
 $iCodigoOrigem = '';
?>
<html>
	<head>
    	<title>Documento sem t&iacute;tulo</title>
    	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    	<?php
        db_app::load("estilos.css");
        db_app::load("scripts.js");
        db_app::load("strings.js");
        db_app::load("prototype.js");
        db_app::load("datagrid.widget.js");
        db_app::load("widgets/dbtextField.widget.js");
        db_app::load("widgets/dbtextFieldData.widget.js");
      ?>
  	</head>
  	
  	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  	  <form method="post" name="form1" target="certreg">
  	    <input type="hidden" id="tipoindconjunta" name="tipoindconjunta" value="<?=@$indconjunta?>">
        <input type="hidden" id="codproc"         name="codproc"         value="" />
        <input type="hidden" id="textarea"        name="textarea"        value="" />
        <input type="hidden" id="tipo"            name="tipo"            value="" />
        <input type="hidden" id="cadrecibo"       name="cadrecibo"       value="" />
        <input type="hidden" id="origem"          name="origem"          value="" />
        <input type="hidden" id="codorigem"       name="codorigem"       value="" />
		    <?	
          if(isset($matric)){
            echo "<input type='hidden' rel='tipo_emissor' name='matric' value='{$matric}'>";
            $sOrigem 			 = 'M';
            $iCodigoOrigem = $matric;
          } else if(isset($numcgm)){
            echo "<input type='hidden' rel='tipo_emissor' name='numcgm' value='{$numcgm}'>";
            $sOrigem 			 = 'C';
            $iCodigoOrigem = $numcgm;
          }else if(isset($inscr)){
            echo "<input type='hidden' rel='tipo_emissor' name='inscr'  value='{$inscr}'>";
            $sOrigem 			 = 'I';
            $iCodigoOrigem = $inscr;
          }else{
            echo "<input type='hidden' rel='tipo_emissor' name='naolibera' value='naolibera'>";
          }
        ?>
			</form>
			
      <div class='container'  style="width:90%; max-width:1000px;">
      
        <fieldset>
          <legend><strong>Certidões Emitidas</strong></legend>

       		  <div id="container-grid"></div>
          		
        </fieldset>
      <input name="certidao" type="button" id="certidao" value="Nova Certidão" onClick="parent.js_windowCertidao(js_Certidao, <?php echo $tipo_cert;?>);">
      </div>
  	</body>

<script type="text/javascript">
var sUrlRPC    = 'cai2_emitecnd.RPC.php';

var montarGrid = function(){
   
   oGridCertidoes                 = new DBGrid('GridCertidoes');
   oGridCertidoes.nameInstance    = 'oGridCertidoes';
   oGridCertidoes.sName           = 'GridCertidoes';
   oGridCertidoes.setCellAlign    (new Array("center","left","center","center","center","center","left","left","center"));
   oGridCertidoes.setHeader       (["Número","Tipo de Certidão","Emissão","Validade","Prazo para Reemissão","Origem","Emissor","Origem da Emissão","Opções"]);
   oGridCertidoes.aWidths		      = new Array('5%','10%','15%','8%','13.5%','5%','20%','11.5%','7%');
   oGridCertidoes.show($('container-grid'));
   oGridCertidoes.clearAll(true);
   
   var oParametros                = new Object();
   oParametros.sExec              = 'getCertidoes';
   oParametros.sOrigem            = '<? echo $sOrigem;       ?>';
   oParametros.iCodigoOrigem      = '<? echo $iCodigoOrigem; ?>';
   
   var oDadosRequisicao    		    = new Object();
   oDadosRequisicao.method 		    = 'post';
   oDadosRequisicao.asynchronous  = false;
   oDadosRequisicao.parameters    = 'json='+Object.toJSON(oParametros),
   oDadosRequisicao.onComplete    = function(oAjax){
       
     var oRetorno = eval("("+oAjax.responseText+")");
     if (oRetorno.iStatus == "2") {
         
       alert(oRetorno.sMensagem.urlDecode());
       return;
     }
   
     for(var iCertidao=0; iCertidao < oRetorno.aCertidoes.length; iCertidao++ ){
   
   	  var oDadosCertidao = oRetorno.aCertidoes[iCertidao];
   
   	  if( oDadosCertidao.tipo_certidao == 'p' ) {
   		  oDadosCertidao.tipo_certidao = "Positiva";
   		}else if( oDadosCertidao.tipo_certidao == 'r' ) {
   			oDadosCertidao.tipo_certidao = "Regular";
   		}else{
   			oDadosCertidao.tipo_certidao = "Negativa";
   		}
   	  
      if( oDadosCertidao.emissao_dbpref  == 't') {
      	oDadosCertidao.emissao_dbpref = 'e-Cidade Online';    
      }else{
      	oDadosCertidao.emissao_dbpref = 'e-Cidade';    
      }
   
      if( oDadosCertidao.habilita_reemissao == 't') {
      	oDadosCertidao.habilita_reemissao = '<a href="#" onclick="js_reemitirCertidao(' + oDadosCertidao.nro + ');">Reemitir</a>';
      }else{
    	  oDadosCertidao.prazo_reemissao    = '';
      	oDadosCertidao.habilita_reemissao = '';
      }
      
   	  oGridCertidoes.addRow([ oDadosCertidao.nro,              
                               oDadosCertidao.tipo_certidao,    
                               js_formatar( oDadosCertidao.data_emissao, 'd') + ' - ' + oDadosCertidao.hora_emissao.urlDecode(),     
                               js_formatar( oDadosCertidao.data_validade, 'd'),    
                               js_formatar( oDadosCertidao.prazo_reemissao, 'd'),
                               oDadosCertidao.origem.urlDecode(),           
                               oDadosCertidao.emissor.urlDecode(),          
                               oDadosCertidao.emissao_dbpref,   
                               oDadosCertidao.habilita_reemissao
 	 												]);
     }
     oGridCertidoes.renderRows();
   };
   
   var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
   
   parent.document.getElementById('processando').style.visibility = 'hidden';
};
     
document.form1.origem.value       = parent.document.form2.tipo_filtro.value;
document.form1.codorigem.value    = parent.document.form2.cod_filtro.value;

/**
 * Emite uma certidao negativa/positiva/regular de débitos
 *
 * @param iCodigoProcesso $iCodigoProcesso
 * @param sObservacoes    $sObservacoes 
 * @access public
 * @return void
 */
var js_Certidao = function ( iCodigoProcesso, sObservacoes ){
  
  origem          = '';
  titulo          = '';
  textarea        = '';
  tipo            = '';
  codproc         = '';
  tipoindconj     = '';

  codproc         = iCodigoProcesso;	  
  textarea        = sObservacoes;
  titulo          = document.getElementById('origem'         ).value;
  origem          = document.getElementById('codorigem'      ).value;
  tipo            = document.getElementById('tipo'           ).value;
  tipoindconjunta = document.getElementById('tipoindconjunta').value;
  textarea        = tagString(textarea);

  var sTipoCertidao = ( tipo == 1 ? 'Positiva' : ( tipo == 0 ? 'Regular' : 'Negativa' ) );

  if ( confirm( 'Emitir Certidão ' + sTipoCertidao + '?' ) ) {

    if ( document.form1.cadrecibo.value == 't' ) {
      js_recibo( titulo, origem, codproc, textarea, tipo);	
    } else {
      
      var sQuery  = "cai2_emitecnd001.php";
      sQuery     += '?tipocertidao=' + tipoindconjunta;
      sQuery     += '&titulo='       + titulo;
      sQuery     += '&origem='       + origem;
      sQuery     += '&textarea='     + textarea;
      sQuery     += '&tipo='         + tipo;
      sQuery     += '&codproc='      + codproc;
      var oJanela = window.open( sQuery ,'', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      oJanela.moveTo(0,0);
    }
  }

	if ( parent.window.$('EmissaoCertidao') ) {
    parent.window.$('EmissaoCertidao').outerHTML = '';
  }
  window.location.href = window.location.href;
}

function js_reemitirCertidao( iCertidaoSequencial ){

	var oParametros              				= new Object();
      oParametros.sExec        				= 'getCertidao';
      oParametros.iCertidaoSequencial = iCertidaoSequencial;
    
	var oDadosRequisicao    		        = new Object();
      oDadosRequisicao.method 		    = 'post';
      oDadosRequisicao.asynchronous   = false;
      oDadosRequisicao.parameters     = 'json='+Object.toJSON(oParametros),
      oDadosRequisicao.onComplete     = function(oAjax){
        
        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iStatus == "2") {
            
           alert(oRetorno.sMensagem.urlDecode());
           return;
        }

        var oJanela = window.open( oRetorno.sArquivo ,'', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        oJanela.moveTo(0,0);
      };
    
  var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
}

function js_recibo(titulo,origem,codproc,textarea,tipo){
  js_OpenJanelaIframe('top.corpo','db_recibo','cai4_recibo001.php?mostramenu=t&titulo='+titulo+'&origem='+origem+'&codproc='+codproc+'&textarea='+textarea+'&tipo='+tipo,'Cadastro de recibo',true);
}
montarGrid();
</script>
</html>

<?
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
$rsNumpref = db_query("select * from numpref where k03_anousu = ".db_getsession("DB_anousu")." and k03_instit = ".db_getsession('DB_instit') );
$numrows = pg_numrows($rsNumpref);
if ($numrows>0){
  db_fieldsmemory($rsNumpref,0);
  if(isset($k03_reccert) && $k03_reccert == 't'){
    echo "<script>document.form1.cadrecibo.value = 't';</script>"; 
  }
}
?>
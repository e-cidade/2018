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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);



$sMesFolha = db_mesfolha();
$sAnoFolha = db_anofolha();



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load('scripts.js');
  db_app::load('prototype.js');
  db_app::load('datagrid.widget.js');
  db_app::load('strings.js');
  db_app::load('grid.style.css');
  db_app::load('estilos.css');
?>
<script>


function js_emite(){

  var dtAnoIni           = $('anoIni').value;
  var dtMesIni           = $('mesIni').value;
  
  var dtAnoFini          = $('anofolha').value;
  var dtMesFini          = $('mesfolha').value;
  
  var sOrdem             = $('ordem').value;
  var sTipo              = $('tipo').value;
  var sQuebra            = $('quebraPagina').value;
  
  var sSelecaoMatriculas = "";
  var sVirgula           = "";

  var iMatriculaInicial  = '';
  var iMatriculaFinal    = '';


  if($('selecaomatriculas')){
  
	  var aOptions = $('selecaomatriculas').options;
	  for ( var iInd=0; iInd < aOptions.length; iInd++ ) {
	
	    sSelecaoMatriculas += sVirgula + aOptions[iInd].value;
	    sVirgula = ",";
	  
	  }
	  
  }
  
  if(sSelecaoMatriculas != ""){
    sSelecaoMatriculas = "("+sSelecaoMatriculas+")";
  } 
   
  if ($('matricdulainicial')){
    var iMatriculaInicial = $F('matricdulainicial');
    var iMatriculaFinal = $F('matricdulafinal');
  }
   
  var sQuery  = '?sOrdem='+sOrdem;
      sQuery += '&sSelecaoMatriculas='+sSelecaoMatriculas;
      sQuery += '&dAnoIni='+dtAnoIni;
      sQuery += '&dMesIni='+dtMesIni;
      sQuery += '&dAnoFini='+dtAnoFini;
      sQuery += '&dMesFini='+dtMesFini;
      sQuery += '&sQuebra='+sQuebra+'';
      sQuery += '&sTipo='+sTipo+'';
      sQuery += '&iMatriculaInicial='+iMatriculaInicial+'';
      sQuery += '&iMatriculaFinal='+iMatriculaFinal+'';
      
  var sUrl    = 'pes2_lotacoes_ppp002.php'+sQuery;

  //var sParam  = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
 // var jan     = window.open(sUrl,'',sParam);

  //    jan.moveTo(0,0);
      js_OpenJanelaIframe("","db_iframe_csv", sUrl, "Gerando Relatório",
          true,
          20,
          document.clientWidth / 2,
          document.clientWidth,
          document.clientHeight
       );

      
      
}

</script>  
<link href='estilos.css' rel='stylesheet' type='text/css'>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onLoad='a=1' bgcolor='#cccccc'>
<form name='form1' method='post' action='' >
  <table align='center' border='0' width='100%'>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>    
    <tr>
      <td>
        <table align='center' cellpadding='0' cellspacing='0'>
          <tr>
            <td>
              <fieldset>
                <legend>
                  <b>Arquivo de Alterações de Lotações</b>
                </legend>
                <table border='0' id='tblFormulario'>
                
                  <tr>
                    <td align='right'>
                      <b>Mês / Ano Inicial :</b>            
                    </td>
                    <td colspan='3'>
                      <? db_input('mesIni',2,'',true,'text',1); ?> 
                      &nbsp;/&nbsp;  
                      <? db_input('anoIni',4,'',true,'text',1); ?>                 
                    </td>
                  </tr>      
                      <? 
                      $geraform = new cl_formulario_rel_pes;
										  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
										  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
										  $geraform->usaorga = true;                      // PERMITIR SELEÇÃO DE ÓRGÃO
										  $geraform->usaloca = true;                      // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO
										  $geraform->usarecu = true;                      // PERMITIR SELEÇÃO DE RECURSO
										
										  $geraform->re1nome = "matricdulainicial";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
										  $geraform->re2nome = "matricdulafinal";                    // NOME DO CAMPO DA MATRÍCULA FINAL
										  $geraform->re3nome = "selecaomatriculas";                  // NOME DO CAMPO DE SELEÇÃO DE MATRÍCULAS
										  
										  $geraform->resumopadrao = "m";                  // TIPO DE RESUMO PADRAO
										  $geraform->filtropadrao = "m";                  // NOME DO DAS LOTAÇÕES SELECIONADAS
										  $geraform->strngtipores = "gm";              // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
										  $geraform->tipofol = false;                      // MOSTRAR DO CAMPO PARA TIPO DE FOLHA
										  $geraform->onchpad   = true;                    // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
										  $geraform->gera_form($sAnoFolha,$sMesFolha);
                      
                      
                      ?>  
                      
                      <script>
                      var aTabela = $('tblFormulario');
                      aTabela.rows[1].cells[0].innerHTML = "<b>Mês / Ano Final :</b>";
                      var sInputs = aTabela.rows[1].cells[1].innerHTML;
                      var sNovo = sInputs.split("&nbsp;/&nbsp;");
                      aTabela.rows[1].cells[1].innerHTML = sNovo[1] + "&nbsp;/&nbsp;" + sNovo[0];
                      </script> 
                                    
                  <tr>
                    <td align='right'>
                      <b>Quebrar Página por Servidor :</b>
                    </td>
                    <td colspan='3'>
                      <?
                        $aQuebra = array( 'n'=>'Não',
                                          's'=>'Sim'
                                        );
                        db_select('quebraPagina',$aQuebra,true,2," style='width:275px;'"); 
                      ?>          
                    </td>
                  </tr>  
                  
                  <tr>
                    <td align='right'>
                      <b>Ordem :</b>
                    </td>
                    <td colspan='3'>
                      <?
                        $aOrdem = array( 'm'=>'Matrícula',
                                         'n'=>'Nome'
                                        );
                        db_select('ordem',$aOrdem,true,2," style='width:275px;'"); 
                      ?>          
                    </td>
                  </tr>                           
                  <tr>
                    <td align='right'>
                      <b>Tipo :</b>
                    </td>
                    <td colspan='3'>
                      <?
                        $aTipo  = array( 'a'=>'Arquivo',
                                         'r'=>'Relatório'
                                        );
                        db_select('tipo',$aTipo,true,2," style='width:275px;'"); 
                      ?>          
                    </td>
                  </tr>                           
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <table align='center'>
                <tr>
                  <td>&nbsp;</td>
                </tr>              
                <tr>
                  <td>
                    <input  name='emitir' id='emitir' type='button' value='Processar' onclick='js_emite()'>
                  </td>
                </tr>
              </table>
            </td>
          </tr>          
        </table>
      </td>
    </tr> 
  </table>
</form>
<?
  db_menu(db_getsession('DB_id_usuario'),db_getsession('DB_modulo'),db_getsession('DB_anousu'),db_getsession('DB_instit'));
?>
</body>
</html>
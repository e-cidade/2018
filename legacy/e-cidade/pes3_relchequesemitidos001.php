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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_gerfcom_classe.php");

$oPost = db_utils::postMemory($_POST);

$clgerfcom = new cl_gerfcom();
$clrotulo  = new rotulocampo();

$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table align="center" style="padding-top:25px;">
  <tr>
    <td>
			<form name="form1" method="post" action="">
			  <fieldset>
			    <legend align="center">
			      <b>Relatório de Cheques Emitidos</b>
			    </legend>
					<table>
            <tr>
              <td align="right">
                <b>Tipo:</b>
              </td>
              <td>
                <?
                   $aTipo = array("f"=>"Funcionários",
                                  "p"=>"Pensão Alimentícia");
                   db_select("tipoGera",$aTipo,true,1,"onChange='document.form1.submit();'");
                ?>
              </td>             
            </tr>					
					  <?
					  
					  if(!isset($oPost->tipo)){
					    $tipo = "t";
					  } else {
					  	$tipo = $oPost->tipo;
					  }
					  
					  if(!isset($oPost->filtro)){
					    $filtro = "s";
					  } else {
					  	$filtro = $oPost->filtro;
					  }
					  
					  if(!isset($oPost->anofolha) || (isset($oPost->anofolha) && trim($oPost->anofolha) == "")){
					    $anofolha = db_anofolha();
					  } else {
					  	$anofolha = $oPost->anofolha;
					  }
					  
					  if(!isset($oPost->mesfolha) || (isset($oPost->mesfolha) && trim($oPost->mesfolha) == "")){
					    $mesfolha = db_mesfolha();
					  } else {
					  	$mesfolha = $oPost->mesfolha;
					  }
					  
					  include("dbforms/db_classesgenericas.php");
					  $geraform = new cl_formulario_rel_pes;
					
					  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
					  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
					  $geraform->usaorga = true;                      // PERMITIR SELEÇÃO DE ÓRGÃO
					  $geraform->usaloca = true;                      // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO
					
					  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
					  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATRÍCULA FINAL
					  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELEÇÃO DE MATRÍCULAS
					
					  $geraform->lo1nome = "lotaci";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
					  $geraform->lo2nome = "lotacf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
					  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELEÇÃO DE LOTAÇÕES
					
					  $geraform->or1nome = "orgaoi";                  // NOME DO CAMPO DO ÓRGÃO INICIAL
					  $geraform->or2nome = "orgaof";                  // NOME DO CAMPO DO ÓRGÃO FINAL
					  $geraform->or3nome = "selorg";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS 
					
					  $geraform->tr1nome = "locali";                  // NOME DO CAMPO DO LOCAL INICIAL
					  $geraform->tr2nome = "localf";                  // NOME DO CAMPO DO LOCAL FINAL
					  $geraform->tr3nome = "selloc";                  // NOME DO CAMPO DE SELEÇÃO DE LOCAIS
					
					  $geraform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
					  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO
					
					  $geraform->resumopadrao = "t";                  // NOME DO DAS LOTAÇÕES SELECIONADAS
					  $geraform->filtropadrao = "s";                  // NOME DO DAS LOTAÇÕES SELECIONADAS
					
					  $geraform->strngtipores = "glomt";              // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
					                                                  //                                       l - lotação,
					                                                  //                                       o - órgão,
					                                                  //                                       m - matrícula,
					                                                  //                                       t - local de trabalho
					
					  $geraform->tipofol     = true;                      // MOSTRAR DO CAMPO PARA TIPO DE FOLHA

            if ( isset($oPost->tipoGera) && $oPost->tipoGera == "p" ) {
               $aTipoFolha = array(
                                    "r14"=>"Salário",
                                    "r48"=>"Complementar",
                                    "r20"=>"Rescisão",
                                    "r35"=>"13o. Salário",
                                    "r52"=>"Férias"
                                  );
            } else {
               $aTipoFolha = array(
                                    "r14"=>"Salário",
                                    "r48"=>"Complementar",
                                    "r20"=>"Rescisão",
                                    "r35"=>"13o. Salário",
                                    "r22"=>"Adiantamento"
                                  );                                           
            }
					  
					  $geraform->arr_tipofol  = $aTipoFolha;
					  $geraform->complementar = "r48";                // VALUE DA COMPLEMENTAR PARA BUSCAR SEMEST 
					
					  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
					  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS
					  $geraform->campo_auxilio_orga = "faixa_orgao";  // NOME DO DOS ÓRGÃOS SELECIONADOS
					  $geraform->campo_auxilio_loca = "faixa_local";  // NOME DO DOS LOCAIS SELECIONADOS
					
					  $geraform->selecao = true;                      // CAMPO PARA ESCOLHA DA SELEÇÃO
					
					  $geraform->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
					  $geraform->gera_form($anofolha,$mesfolha);
					  ?>
					  <tr>
					    <td colspan="2" align = "center"> 
					      <input  name="imprimir" type="button" value="Imprimir" onclick="js_imprime();" >
					    </td>
					  </tr>
					</table>
				</fieldset>
      </form>
    </td>
  </tr>
</table>  
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_imprime(){

  var doc = document.form1;
  
  var sQuery  = '?tipofol='+doc.tipofol.value;
      sQuery += '&tipo='+doc.tipo.value;
      sQuery += '&anofolha='+doc.anofolha.value;
      sQuery += '&mesfolha='+doc.mesfolha.value;
      sQuery += '&tipoGera='+doc.tipoGera.value;
      sQuery += '&selecao='+doc.selecao.value;

  if(doc.filtro){    
    sQuery += '&filtro='+doc.filtro.value;
  }    
      
  if(doc.complementar){
    sQuery+= "&semest="+doc.complementar.value;
  }

  if(doc.selreg){
    if(doc.selreg.length > 0){
      faixareg = js_campo_recebe_valores();
      sQuery+= "&fregis="+faixareg;
    }
  }else if(doc.regisi){
    regini = doc.regisi.value;
    regfim = doc.regisf.value;
    sQuery+= "&regisi="+regini;
    sQuery+= "&regisf="+regfim;
  }

  if(doc.sellot){
    if(doc.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      sQuery+= "&flotac="+faixalot;
    }
  }else if(doc.lotaci){
    lotini = doc.lotaci.value;
    lotfim = doc.lotacf.value;
    sQuery+= "&lotaci="+lotini;
    sQuery+= "&lotacf="+lotfim;
  }

  if(doc.selloc){
    if(doc.selloc.length > 0){
      faixaloc = js_campo_recebe_valores();
      sQuery+= "&flocal="+faixaloc;
    }
  }else if(doc.locali){
    locini = doc.locali.value;
    locfim = doc.localf.value;
    sQuery+= "&locali="+locini;
    sQuery+= "&localf="+locfim;
  }

  if(doc.selorg){
    if(doc.selorg.length > 0){
      faixaorg = js_campo_recebe_valores();
      sQuery+= "&forgao="+faixaorg;
    }
  }else if(doc.orgaoi){
    orgini = doc.orgaoi.value;
    orgfim = doc.orgaof.value;
    sQuery+= "&orgaoi="+orgini;
    sQuery+= "&orgaof="+orgfim;
  }

  jan = window.open('pes3_relchequesemitidos002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}


</script>

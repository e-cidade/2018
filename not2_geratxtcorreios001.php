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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_libpostgres.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clpostgresqlutils = new PostgreSQLUtils;
$clrotulo          = new rotulocampo;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');

$instit = db_getsession("DB_instit");
if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox(_M('tributario.notificacoes.not2_geratxtcorreios001.problema_indices_debitos'));
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 4;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load('estilos.css');
  db_app::load('scripts.js');
  db_app::load('strings.js');
  db_app::load('prototype.js');
  
?>
</head>
<body bgcolor="#cccccc">


<form class="container" name="form1" method="post" action="">
  <fieldset>
  	<legend>Geração de Arquivo TXT para emissão pelos Correios</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tk60_codigo?>">
          <? db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",$db_opcao) ?>
        </td>
        <td>
          <?
	       db_input('k60_codigo',10,$Ik60_codigo,true,'text',$db_opcao,"onchange='js_pesquisalista(false);'");
           db_input('k60_descr',37,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Ordem para a emissão da lista">
          Ordernar por:
        </td>
        <td>
          <?
            $xx = array("a"=>"Alfabética",
      	   	 	      "n"=>"Numérica",
      	  		      "t"=>'Notificação',
	      			  "e"=>"Endereço de Entrega",
       	   	    	  "c"=>"Cidade/CEP" );
		    db_select('ordem',$xx,true,$db_opcao,"");
      	  ?>
        </td>
      </tr>
      <tr>
        <td nowrap>
          Tratamento do Endereço:
        </td>
        <td>
          <?
	        $xxx = array();
	        
	        if ( isset($k60_codigo) && trim($k60_codigo) != "" ) {
	        	
	          $sSqlLista  = " select k60_tipo					          ";
	          $sSqlLista .= "   from lista					            ";
	          $sSqlLista .= "  where k60_codigo = {$k60_codigo} ";
	        
	          $rsConsultaLista = db_query($sSqlLista);
	          $iLinhasConsulta = pg_num_rows($rsConsultaLista);
	        
	          $oLista = db_utils::fieldsMemory($rsConsultaLista,0);
	        
	          $xxx["1"] = "Sempre do CGM";
	             
	          if ( $oLista->k60_tipo == "M") {
	            $sqlordendent    = "select defcampo, defdescr from db_syscampodef where codcam = 9856";
	            $resultordendent = db_query($sqlordendent) or die($sqlordendent);
	            for ($xord=0; $xord < pg_numrows($resultordendent); $xord++) {
	              db_fieldsmemory($resultordendent, $xord);
	              $defcampo+=10;
	              $xxx["$defcampo"] = "$defdescr";
	            }
	          }
	        }
	        
	        db_select('tratamento',$xxx,true,$db_opcao,"");
      	  ?>
        </td>
      </tr>
      <tr>
        <td title="Escolha o intervalo das notificações a serem impressas ou deixe em branco para todas.">
          Qtde a processar :
        </td>
        <td>
          <input id="qtd" type="text" name="qtd" maxlength="10" size="10">
        </td>
      </tr>
      <tr>
        <td title="Serviço AR">
          Serviço AR:
        <td>
          <? db_input("lServAr", 1, null, true, "checkbox",1); ?>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <fieldset class="separator">
            <legend>Formatação da Notificação:</legend>
            <table class="form-container">
              <tr>
                <td>
                  Fonte:
                </td>
                <td>
                  <?
             	    $aFontes = array("16602"=>"16602 - Arial", "4099"=>"4099 - Currier", "16901"=>"16901 - Times New Roman");
             	    db_select("fonte", $aFontes, true, 1, "");
           		  ?>
                </td>
              </tr>
              <tr>
                <td>
                  Espaçamento entre linhas:
                </td>
                <td>
                  <?
             	    $aEspacamento = array("1.0"=>"1.0", "1.5"=>"1.5", "2.0"=>"2.0");
             	    db_select("espacamento", $aEspacamento, true, 1, "");
           		  ?>
                </td>
              </tr>
              <tr>
                <td>
                  Estilo da fonte:
                </td>
                <td>
                <?
             	  $aEstiloFonte = array(""=>"Normal", "I"=>"Itálico", "N"=>"Negrito", "IN"=>"Negrito e Itálico");
             	  db_select("estilofonte", $aEstiloFonte, true, 1, "");
           		?>
                </td>
              </tr>
              <tr>
                <td title="<?=@$Tk22_exerc?>">
                  Tamanho da fonte do texto:
                </td>
                <td>
                  <?
	        	    $tamanhofonte=10;
	        	    db_input('tamanhofonte', 10, "Tamanho da fonte do texto", true, 'text', @$db_opcao);
		          ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
      <td colspan=2>
        <fieldset class="separator">
          <legend>Dados do Boleto:</legend>
          <table class="form-container">
            <tr>
              <td title="Gerar Boleto para a notificação">
                Gera boleto:
              </td>
              <td>
                <? db_select('lBoleto', array('1' => 'Sim', '0' => 'Não'), true, 1, "onChange='js_boleto();'"); ?>
              </td>
            </tr>
            <tr id="datavenc" style="display: none;">
              <td nowrap title="Data para vencimento do boleto">
                Vencimento do Boleto:
              <td>
                <?db_inputdata('datavencimento', @$datavencimento_dia, @$datavencimento_mes, @$datavencimento_ano, true, 'text', 1) ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="Local para Pagto">
                Local para Pagto:
              <td>
                <? db_input('localpgto', 51, null, true, 'text', @$db_opcao); ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="db_opcao" type="button" id="db_opcao" value="Processar" onClick="js_processar();" <?=($db_botao ? '' : 'disabled')?>>
</form>
        
        
        
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_boleto() {
  if (document.form1.lBoleto.value == 1 ) {
    document.getElementById("datavenc").style.display  = '';
  } else {
    document.getElementById("datavenc").style.display  = 'none';
  } 
}

function js_pesquisalista(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
     }
}

function js_mostralista(chave,erro){

  document.form1.k60_descr.value = chave;
  
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  } else {
    document.form1.submit();
  }
  
}
function js_mostralista1(chave1,chave2){

     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
     document.form1.submit();
}

function js_processar() {

  var erro = false;
  //Realizamos todas as consistencias dos dados que são obrigatórios 
  if (document.form1.k60_codigo.value == "") {
    alert(_M('tributario.notificacoes.not2_geratxtcorreios001.informe_lista'));
    document.form1.k60_codigo.focus();
    erro = true;
  }   

  if (document.form1.tratamento.value == "") {
    alert(_M('tributario.notificacoes.not2_geratxtcorreios001.informe_tratamento_endereco'));
    document.form1.tratamento.focus();
    erro = true;
  }

  if (document.form1.tamanhofonte.value == "") {
    alert(_M('tributario.notificacoes.not2_geratxtcorreios001.informe_tamanho_fonte'));
    document.form1.tamanhofonte.focus();
    erro = true;
  }  

  if (document.form1.lBoleto.value == 1 && document.form1.datavencimento.value == "") {
    alert(_M('tributario.notificacoes.not2_geratxtcorreios001.informe_vencimento_boleto'));
    document.form1.datavencimento.focus();
    erro = true;    
  }  

  if ( erro == false ) {
    
    var obj = document.form1;
    var sQuery  = "lista="        +obj.k60_codigo.value;
        sQuery += "&ordem="       +obj.ordem.value;
        sQuery += "&tratamento="  +obj.tratamento.value;
        sQuery += "&qtd="         +obj.qtd.value;
        sQuery += "&fonte="       +obj.fonte.value;
        sQuery += "&espacamento=" +obj.espacamento.value;
        sQuery += "&estilofonte=" +obj.estilofonte.value;
        sQuery += "&tamanhofonte="+obj.tamanhofonte.value;
        sQuery += "&lServAr="     +obj.lServAr.checked ? 'S' : 'N';
        sQuery += "&lBoleto="     +obj.lBoleto.value;
        sQuery += "&datavenc="    +obj.datavencimento.value;
        sQuery += "&localpgto="   +obj.localpgto.value;
        
        js_OpenJanelaIframe("","db_iframe_correios", 'not2_geratxtcorreios002.php?'+sQuery, "Processamento de Notificação Correios",
            true,
            20,
            document.clientWidth / 2,
            document.clientWidth,
            document.clientHeight
         );
  }     
}
function fechar() {
  db_iframe_correios.hide();  
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>

<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
$("qtd").addClassName("field-size2");
$("localpgto").addClassName("field-size9");
$("lBoleto").setAttribute("rel","ignore-css");
$("lBoleto").addClassName("field-size2");
$("k60_codigo").addClassName("field-size2");
$("k60_codigo").addClassName("field-size2");

</script>
<?PHP
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_lote_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_conhistdoc_classe.php");
require_once("libs/db_app.utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clempempenho       = new cl_empempenho;
$aux                = new cl_arquivo_auxiliar;
$clconhistdoc       = new cl_conhistdoc;
$cllote             = new cl_lote;
$clrotulo           = new rotulocampo;
$cliframe_seleciona = new cl_iframe_seleciona;

$clempempenho->rotulo->label();

$cllote->rotulo->label();
$clrotulo->label("z01_nome");


db_app::load("scripts.js,
              prototype.js,
              DBLancador.widget.js,
              widgets/DBAncora.widget.js,
              strings.js,
              estilos.css,
              widgets/windowAux.widget.js,
              widgets/dbmessageBoard.widget.js,
              dbcomboBox.widget.js");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

</head>



<style>

  .ComboRazao {
    width: 220px;
  }
  #data1, #data2 {
    width: 70px;
  }

</style>

<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >

<center>
<form name="form1" method="post" action="con2_razaocontas002.php" >


<fieldset style="margin-top: 50px; width: 580px; text-align: left;">
<legend>Relatório de Razão por Contas</legend>
<table style="text-align: left" border='0'>
       <tr>
        <td nowrap align=left>
           <b>Período:</b>
	     </td>
	     <td nowrap align=left>
          <?
	         $dia=  date("d",db_getsession("DB_datausu"));
		       $mes=  date("m",db_getsession("DB_datausu"));
		       $ano=  date("Y",db_getsession("DB_datausu"));
		       $dia2= date("d",db_getsession("DB_datausu"));
		       $mes2= date("m",db_getsession("DB_datausu"));
		       $ano2= date("Y",db_getsession("DB_datausu"));
	         db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
           echo "<strong>a</strong>";
           db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
          ?>
       </td>
     </tr>

     <tr>
        <td nowrap align=left>
            <b>Tipo:</b>
	    </td>
	     <td align=left>
            <select name="relatorio" id='relatorio' class="ComboRazao">
                <option name="relatorio" value="a">Analítico</option>
                <option name="relatorio" value="s">Sintético</option>
                <option name="relatorio" value="e">Agrupar por Evento Contábil</option>

            </select>
         </td>
      </tr>
	    <tr>
       <td nowrap align=left>
	       <b>Imprimir Contrapartida:</b>
	     </td>
	      <td align=left>

	        <select id='contrapartida' name='contrapartida' class="ComboRazao">
	          <option value='on' >Sim</option>
	          <option value='off'>Não</option>
	        </select>
	       <!--  <input type=checkbox name=contrapartida checked> -->

        </td>
			</tr>
      <tr>
        <td nowrap align=left>
 	         <b>Imprimir Saldo por Dia:</b>
	      </td>
	      <td align=left>
	      	 <select id='saldopordia' name='saldopordia' class="ComboRazao">
	          <option value='n'>Não</option>
	          <option value='s'>Sim</option>
	        </select>

	        <!-- <input type=checkbox name=saldopordia > -->

        </td>
       </tr>

     <tr>
         <td nowrap align=left>
            <b>Imprimir Conta Sem Movimento:</b>
         </td>
         <td align=left>

	      	 <select id='contasemmov' name='contasemmov' class="ComboRazao">
	          <option value='n'>Não</option>
	          <option value='s'>Sim</option>
	        </select>
        <!--  <input type=checkbox name=contasemmov > -->
        </td>
    </tr>
     <tr>
        <td nowrap align=left>
            <b>Quebrar Página por Conta:</b>
	    </td>
	     <td align=left>
            <select id='quebrapaginaporconta' name="quebrapaginaporconta" class="ComboRazao">
                <option  value="n">Não </option>
                <option  value="s">Sim </option>
            </select>
         </td>
      </tr>
     <tr>
       <td align = "left"><strong> Estrutural: </strong></td><td>
         <input type='text' name='estrut_inicial' id='estrut_inicial' size='15' maxlength='10'  class="ComboRazao">
       </td>
     </tr>
</table>

</fieldset>

<div id='ctnLancadorDocumentos' style="margin-top: 10px; width: 600px;"> </div>

<div id='ctnLancadorContas' style="margin-top: 10px; width: 600px;"> </div>

<div style="margin-top: 10px;">
  <input type="button" id="emite" value="Emitir" onClick="js_imprimir()">
</div>

</form>

</center>


<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

  </body>
</html>


<script>


const CAMINHO_MENSAGEM_TELA = "financeiro.contabilidade.con2_razaocontas001.";

variavel = 1;

$('relatorio').observe("change", function() {

  $('contrapartida').disabled = false;
  $('contasemmov').disabled   = false;
  if ($F("relatorio") == "e") {
    
    $('contasemmov').disabled   = true;
    $('contrapartida').disabled = true;
  }
});
/**
 * Cria o lançador para os Documentos
 */
function js_criarLancadorDocumentos() {

	oLancadorDocumentos = new DBLancador("oLancadorDocumentos");
	oLancadorDocumentos.setNomeInstancia("oLancadorDocumentos");
	oLancadorDocumentos.setLabelAncora("Documentos: ");
	oLancadorDocumentos.setTextoFieldset("Documentos Selecionados");
	oLancadorDocumentos.setParametrosPesquisa("func_conhistdoc.php", ['c53_coddoc', 'c53_descr']);
	oLancadorDocumentos.setGridHeight("400px");
	oLancadorDocumentos.setTituloJanela("Pesquisar Documentos");
	oLancadorDocumentos.show($("ctnLancadorDocumentos"));
}
js_criarLancadorDocumentos();


/**
 * Cria o lançador para as contas
 */
function js_criarLancadorContas() {

	oLancadorContas = new DBLancador("oLancadorContas");
	oLancadorContas.setNomeInstancia("oLancadorContas");
	oLancadorContas.setLabelAncora("Contas: ");
	oLancadorContas.setTextoFieldset("Contas Selecionadas");
	oLancadorContas.setParametrosPesquisa("func_conplanoexe.php", ['c62_reduz', 'c60_descr']);
	oLancadorContas.setGridHeight("400px");
	oLancadorContas.setTituloJanela("Pesquisar Contas");
	oLancadorContas.show($("ctnLancadorContas"));
}
js_criarLancadorContas();


function js_imprimir() {

  var data1 = document.form1.data1_ano.value+"-"+document.form1.data1_mes.value+"-"+document.form1.data1_dia.value;
  var data2 = document.form1.data2_ano.value+"-"+document.form1.data2_mes.value+"-"+document.form1.data2_dia.value;

  var aContas     = oLancadorContas.getRegistros();
  var aDocumentos = oLancadorDocumentos.getRegistros();

  var relatorio            = $F('relatorio');
  var contrapartida        = $F('contrapartida');
  var saldopordia          = $F('saldopordia');
  var contasemmov          = $F('contasemmov');
  var quebrapaginaporconta = $F('quebrapaginaporconta');
  var estrut_inicial       = $F('estrut_inicial');
  var sDocumentos          = "";
  var sVirgulaDocumentos   = "";
  var sContas              = "";
  var sVirgulaContas       = "";
  var retorno              = true;

  //  montamos lista de documentos selecionados
  aDocumentos.each(function (oDocumentos, iIndice) {
    sDocumentos += sVirgulaDocumentos + oDocumentos.sCodigo;
    sVirgulaDocumentos = ", ";
  });

  //  montamos lista de contas selecionadas
  aContas.each(function (oContas, iIndice) {
    sContas += sVirgulaContas + oContas.sCodigo;
    sVirgulaContas = ", ";
  });

  if(data1.valueOf() > data2.valueOf()){

    alert( _M(CAMINHO_MENSAGEM_TELA + "data_inicial_maior_final") );
    return false;
  }

  if ( sDocumentos == '' ||  sDocumentos == null ){
    retorno = confirm( _M( CAMINHO_MENSAGEM_TELA + "sem_documentos_selecionados") );
  }
  
  if ( sContas == '' ||  sContas == null ){
    retorno = confirm( _M( CAMINHO_MENSAGEM_TELA + "sem_conta_selecionada") );
  }
  
  var data1 = js_formatar($F("data1"), 'd');
  var data2 = js_formatar($F("data2"), 'd');

  var sQuery  = "data1="                 + data1;
      sQuery += "&data2="                + data2;
      sQuery += "&variavel="             + variavel;
      sQuery += "&sDocumentos="          + sDocumentos;
      sQuery += "&lista="                + sContas;
      sQuery += "&relatorio="            + relatorio;
      sQuery += "&contrapartida="        + contrapartida;
      sQuery += "&saldopordia="          + saldopordia;
      sQuery += "&contasemmov="          + contasemmov;
      sQuery += "&quebrapaginaporconta=" + quebrapaginaporconta;
      sQuery += "&estrut_inicial="       + estrut_inicial;

      
  if ( retorno == true && $F('relatorio') == 'e') {

    oJanela = window.open('con2_razaoporcontas003.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);
  }

  if ( retorno == true && $F('relatorio') != 'e'){

    oJanela = window.open('con2_razaocontas002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);
  }


}

</script>
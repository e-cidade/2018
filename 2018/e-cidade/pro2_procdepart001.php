<?PHP
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempempenho       = new cl_empempenho;
$aux                = new cl_arquivo_auxiliar;
$cllote             = new cl_lote;
$clrotulo           = new rotulocampo;
$cliframe_seleciona = new cl_iframe_seleciona;
$clempempenho->rotulo->label();
$cllote->rotulo->label();
$clrotulo->label("z01_nome");

db_app::load("scripts.js, prototype.js, strings.js, estilos.css,widgets/DBLancador.widget.js, widgets/DBAncora.widget.js");

$ano  = db_getsession("DB_anousu");
$dia2 = "31";
$mes2 = "12";
$ano2 = db_getsession("DB_anousu");
list($mes,$dia) = split("-",date("m-d"));

$matriz = array("1" => "Processos iniciados no departamento",
                "2" => "Processos que estao no departamento(Ultimo andamento)",
                "3" => "Processos sem tramite inicial",
                "4" => "Processos movimentados no departamento");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<style type="text/css">

  .combos {
  
    width: 418px;
  }

</style>

</head>
<body bgcolor="#CCCCCC"  >

<center>
<form name="form1" method="post" action="pro2_procdepart002.php">

<fieldset style="width: 600px; margin-top: 50px;">
  <legend><strong>Relatório Processos/Departamento</strong></legend>

  <table align="left" >

    <tr>
      <td >
  	    <strong>Seleção:</strong>
  	  </td>
  	  <td>
  	    <?php db_select('tipo',$matriz,"",1);?>
      </td>
    </tr>
    
    <tr>
      <td nowrap >
        <strong> Ordem: </strong>
      </td>
      <td>    
        <select name="ordem" id='ordem' class="combos">
          <option name="ordem" value="p58_codproc">PROCESSO </option>
          <option name="ordem" value="p58_numcgm">CGM </option>
        </select>
      </td>     
    </tr> 
    
    <tr> 
      <td align="left">
        <strong>Opções:</strong>
      </td>
      <td>     
        <select name="ver" id='ver' class='combos'>
          <option name="condicao" value="com">Com os Departamentos selecionados</option>
          <option name="condicao" value="sem">Sem os Departamentos selecionadas</option>
        </select>
      </td>
    </tr>    
    
    <tr>
      <td>
        <strong>Período:</strong>
      </td>
      <td>     
        <?PHP
  	       db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
           echo " <strong> à </strong> ";
           db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
        ?>
      </td>
    </tr>  
    
    <tr>
      <td colspan="2">
        <div style="margin-top: 10px;" id="divLancadorDepartamentos"></div>
      </td>
    </tr>      
        
  </table>

</fieldset>

<div style="margin-top: 10px;">
  <input type="button" value="Relatório" onClick="js_seleciona()">
</div>

</form>
</center>


<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

</body>
</html>

<script>

oLancadorDepartamentos = new DBLancador("oLancadorDepartamentos");
oLancadorDepartamentos.setNomeInstancia("oLancadorDepartamentos");
oLancadorDepartamentos.setLabelAncora("Departamento: ");
oLancadorDepartamentos.setTextoFieldset("Departamentos Selecionados");
oLancadorDepartamentos.setParametrosPesquisa("func_db_depart.php", ['coddepto', 'descrdepto']);
oLancadorDepartamentos.setGridHeight("400px");
oLancadorDepartamentos.show($("divLancadorDepartamentos"));

variavel = 1;

function js_seleciona(){

  var aDepartamentosSelecionados = oLancadorDepartamentos.getRegistros();
  var aListaDepartamentos        = new Array();
  
  aDepartamentosSelecionados.each(function (oDepartamento, iIndice) {

    aListaDepartamentos.push(oDepartamento.sCodigo);
  });

   dt1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
   dt2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
   
   if (dt1 > dt2 ){
     
      alert('Data inicial não pode ser maior que a Data final.');
      return false;
   }

   var lista                              = aListaDepartamentos;
   var ver                                = $F('ver');
   var ordem                              = $F('ordem');
   var tipo                               = $F('tipo');
   var data1                              = js_formatar($F("data1"), "d");
   var data2                              = js_formatar($F("data2"), "d");
   var txtCodigooLancadorDepartamentos    = '';
   var txtDescricaooLancadorDepartamentos = '';
   var coddepto                           = '';
   var descrdepto                         = '';

   var sFonte                             = "pro2_procdepart002.php";
   var sQuery                             = '';
   
   sQuery  = "?lista="  + lista;
   sQuery += "&ver="    + ver;
   sQuery += "&ordem="  + ordem;
   sQuery += "&tipo="   + tipo;
   sQuery += "&data1="  + data1;
   sQuery += "&data2="  + data2;
   
   sQuery += "&txtCodigooLancadorDepartamentos="    + txtCodigooLancadorDepartamentos;
   sQuery += "&txtDescricaooLancadorDepartamentos=" + txtDescricaooLancadorDepartamentos;
   sQuery += "&coddepto="                           + coddepto;
   sQuery += "&descrdepto="                         + descrdepto;
   
   jan = window.open(sFonte + sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0); 
   
}
</script>


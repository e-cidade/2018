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
  require_once("dbforms/db_classesgenericas.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
  
  $oAux = new cl_arquivo_auxiliar();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 20px;">

  <center>
    <form id="form1" name="form1">
      <fieldset style="width: 450px; padding: 20px;">
      <legend><b>Período / Tipos de Local</b></legend>
      <table width="100%">
        <tr>
          <td nowrap="nowrap"><b>Período:</b></td>
          <td>
            <?
              db_inputdata('dtInicial', '','','', true, 'text', 1);
              echo "&nbsp;&nbsp;à&nbsp;&nbsp;";
              db_inputdata('dtFinal', '','','', true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Tipo de Local:</b></td>
          <td>
            <?
              $aTipoLocal = array("t"=>"Todos",
                                  "g"=>"Geral",
                                  "e"=>"Endereço",
                                  "d"=>"Departamento");
              db_select("sTipoLocal", $aTipoLocal, true, 1);
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
      
      <p align="center">
        <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onclick="js_imprimir();" />
      </p>
    </form>  
  </center>
</body>
</html>

<script>

  function js_imprimir() {
    
    if ($("dtInicial").value == "" || $("dtFinal").value == "") {
    
      alert("O campo período é obrigatório!");
      return false
    }
    
    /**
     *  Resgata todos os valores preenchido pelo usuário
     */
    var iOpcaoDepart        = parent.iframe_abaDepartProcesso.$('iOpcaoDepartamento').value;
    var sDepartamento       = parent.iframe_abaDepartProcesso.$('sDepartSelecionado').value;
    var iOpcaoTipoProc      = parent.iframe_abaTipoProcesso.$('iTiposProcesso').value;
    var sTipoProcesso       = parent.iframe_abaTipoProcesso.$('sTipoProcesso').value;
    var iOpcaoLocal         = parent.iframe_abaLocal.$('iOpcaoLocal').value;
    var sLocais             = parent.iframe_abaLocal.$('sLocais').value;
    var iOpcaoBairro        = parent.iframe_abaBairro.$('iOpcaoBairro').value;
    var sBairro             = parent.iframe_abaBairro.$('sBairro').value;
    var iOpcaoDepartDestino = parent.iframe_abaDepartDestino.$('iOpcaoDepartDestino').value;
    var sDepartDestino      = parent.iframe_abaDepartDestino.$('sDepartDestino').value;
    
    var sQueryString  = "ouv1_tipoprocdepart008.php?";
    sQueryString     += "dtInicial="+$("dtInicial").value;
    sQueryString     += "&dtFinal="+$("dtFinal").value;
    sQueryString     += "&iOpcaoDepart="+iOpcaoDepart;
    sQueryString     += "&sDepartamento="+sDepartamento;
    sQueryString     += "&iOpcaoTipoProc="+iOpcaoTipoProc;
    sQueryString     += "&sTipoProcesso="+sTipoProcesso;
    sQueryString     += "&iOpcaoLocal="+iOpcaoLocal;
    sQueryString     += "&sLocais="+sLocais;
    sQueryString     += "&iOpcaoBairro="+iOpcaoBairro;
    sQueryString     += "&sBairro="+sBairro;
    sQueryString     += "&iOpcaoDepartDestino="+iOpcaoDepartDestino;
    sQueryString     += "&sDepartDestino="+sDepartDestino;
    
//    alert(sQueryString);
    
    var oWindowOpen = window.open(sQueryString, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oWindowOpen.moveTo(0,0);
    
  }

</script>
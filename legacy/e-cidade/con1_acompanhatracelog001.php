<?php
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

/**
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
require_once ("libs/db_app.utils.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <style>
    #ctnAcompanhaTraceLog {

      background-color: #000000;
      color: #ffffff;
      min-height: 600px;
      text-align: left;
      padding: 10px;
      font-family: Arial, Helvetica, serif,sans-serif, verdana;
      font-size: 11px;
    }

    legend {
      font-family: Arial, Helvetica, serif,sans-serif, verdana;
      font-size: 11px;
    }

  </style>
</head>

<body style="margin-top: 30px; background-color: #CCCCCC;">

  <div align="center" id="ctnGlobal">
    <fieldset style="width: 98%">
      <legend><b>Acompanhamento do TraceLog</b></legend>
      <div id="ctnAcompanhaTraceLog">
      </div>
    </fieldset>
    <p>
      <input type="button" value="Atualizar" onclick="lerArquivo();" />
      <input type="button" value="Limpar"    onclick="limparContainer()" />
      <input type="button" value="Limpar Variável de Controle"    onclick="limparUltimaLinhaLidaDoTraceLog()" />
      <input type="checkbox" id="chkRolagemAutomatica" id="chkRolagemAutomatica"/><label for="chkRolagemAutomatica">Rolagem Automática</label>
    </p>
  </div>


  <script>

    var sNomeArquivo = window.location.search.split("=")[1];

    /**
     * Lê o arquivo de trace log
     */
    function lerArquivo () {

      new Ajax.Request("con1_ativatrace.RPC.php",
                      {method      : 'post',
                        asynchronous : false,
                        parameters   : 'json='+Object.toJSON({"sExec":"lerArquivo", "sArquivo":sNomeArquivo}),
                        onComplete   : escreverEmTela
                      });
    }

    /**
     * Escreve os dados lidos no arquivo no container principal
     * @param oAjax - retorno JSON
     */
    function escreverEmTela(oAjax) {

      var oResposta         = JSON.parse(oAjax.responseText);
      var oContainerDestino = $('ctnAcompanhaTraceLog');

      oResposta.aInstrucoesSQL.each(function (oDado, iLinha) {

        var sHtml = "Linha ["+oDado.iLinha+"] - " + oDado.sSql.urlDecode();

        var oDiv             = document.createElement('div');
        oDiv.id              = oDado.iLinha;
        oDiv.innerHTML       = sHtml;
        oDiv.style.marginTop = '10px';
        oDiv.width           = '100%';
        oDiv.style.color     = "#FFFFFF";

        if (oDado.lEmTransacao) {
          oDiv.style.color = "#27DB7E";
        }

        if (oDado.lErro) {
          oDiv.style.color = "red";
        }
        oContainerDestino.appendChild(oDiv);
      });

      if ($("chkRolagemAutomatica").checked) {
        rolagemAutomatica();
      }

    }

    lerArquivo();

    setInterval("lerArquivo()", 5000);

    /**
     * Direciona o usuário par ao final da página caso a opção de rolagem automática esteja ativada
     */
    function rolagemAutomatica() {
      window.scrollTo(0, document.body.scrollHeight);
    }

    /**
     * Limpa o container principal
     */
    function limparContainer() {
      $('ctnAcompanhaTraceLog').innerHTML = "";
    }

    /**
     * Limpa a variável de controle para ler o arquivo sendo assim o arquivo é lido por completo na próxima vez
     */
    function limparUltimaLinhaLidaDoTraceLog() {

      new Ajax.Request("con1_ativatrace.RPC.php",
                      {method      : 'post',
                        asynchronous : false,
                        parameters   : 'json='+Object.toJSON({"sExec":"limparUltimaLinhaLidaTraceLog"}),
                        onComplete   : function() {alert("Variável de controle limpada com sucesso.")}
                      });
    }
  </script>

</body>
</html>
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

</body>
</html>
<script>

//Vai retornar a query string da pagina atual como um objeto
var oGet = js_urlToObject();

function js_arquivo(sel){
  opener.js_arquivo_abrir(sel);
}

var tabela = document.createElement("TABLE");
    tabela.setAttribute("width","100%");
    tabela.setAttribute("class","bordas");
    tabela.setAttribute("cellspacing","5");
    tabela.setAttribute("border","1");
    document.body.appendChild(tabela);

function js_enviar_valores(arquivo,label,cabec,TorF){
  if(cabec == true){
    cabecalho = tabela.insertRow(0);
    cabecalho.setAttribute("id","cabec");
    cabecalho.setAttribute("align","left");
    cabecalho.setAttribute("bgcolor","#CCCCCC");
    cabecalho.setAttribute("class","bordas02");
    cabecalho.style.fontFamily = 'Arial, Helvetica, sans-serif';
    cabecalho.style.fontSize = '14px';

    document.getElementById('cabec').innerHTML = "<b>ARQUIVOS PARA DOWNLOAD</b>";
  }
  if(TorF != true){
     linha = tabela.insertRow(i+1);
    linha.setAttribute("id","linha"+(i+1));
    linha.setAttribute("align","left");
    linha.setAttribute("bgcolor","#CCCCCC");
    linha.setAttribute("class","bordas");
    linha.style.fontFamily = 'Arial, Helvetica, sans-serif';
    linha.style.fontSize = '12px';

    document.getElementById("linha"+(i+1)).innerHTML = "<a href='#' style='text-decoration:underline; color:black; cursor:hand;' onclick='js_arquivo(\""+arquivo+"\");'><strong>"+label+"</strong></a>";
  }else{

  	linha = tabela.insertRow(1);
    linha.setAttribute("id","links");
    linha.setAttribute("align","left");
    linha.setAttribute("bgcolor","#CCCCCC");
    linha.setAttribute("class","bordas");
    linha.style.fontFamily = 'Arial, Helvetica, sans-serif';
    linha.style.fontSize = '12px';

  	document.getElementById('links').innerHTML = "SEM ARQUIVO(S) PARA DOWNLOAD";
  }
  
  //Verifica o callback e faz redirect 
  if (oGet.callbackName) {    
    opener[oGet.callbackName]();
  }

}

if(opener.document.<?=($form)?>.query_arquivo){  
  if(opener.document.<?=($form)?>.query_arquivo.value != ""){
    arr_lista = opener.document.<?=($form)?>.query_arquivo.value.split("|");
    primeiro = true;
    for(i=0; i<arr_lista.length; i++){
      lista = arr_lista[i];
      arr_arquivo_label = lista.split("#");
      arquivo = arr_arquivo_label[0];
      label = arr_arquivo_label[1];

      if(label == ""){
        label = "ARQUIVO "+(i+1);
      }
      
      cabec = false;
      if(primeiro == true){
        primeiro = false;
        cabec = true;
      }
      if(arquivo != ""){
        js_enviar_valores(arquivo,label,cabec,false);
      }
    }
  }else{
    js_enviar_valores("","",true,true);
  }
}else{
  js_enviar_valores("","",true,true);
}
</script>
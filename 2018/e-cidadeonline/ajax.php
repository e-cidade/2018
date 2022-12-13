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

//faz a inclusão da biblioteca Sajax
require("include/Sajax.php");

// Baseado nos exemplos desenvolvidos por Leonardo Lorieri

/* funcao PHP que recebe o código e faz a pesquisa no banco de dados retornando o nome*/
function mostra_nome($codpes) {
        $con = mysql_connect("localhost","elton","elton");
        mysql_select_db("elton");
        $res = mysql_query("select nompes from pessoa where codpes=$codpes",$con);
        $row = mysql_fetch_object($res);
        $nompes = $row->nompes;
        mysql_close($con);
        return $nompes;
}

$sajax_request_type = "GET"; //forma como os dados serao enviados
sajax_init(); //inicia o SAJAX
sajax_export("mostra_nome"); // lista de funcoes a ser exportadas
sajax_handle_client_request();// serve instancias de clientes

?>
<html>
<head>
<title>Nome </title>
<script>
<?
sajax_show_javascript(); //gera o javascript
?>
function mostra(nome) { //esta funcao retorna o valor para o campo do formulario
        document.teste.nompes.value=nome;
}

function get_nome(c) { //esta funcao chama a funcao PHP exportada pelo Ajax
        cod = c.value;
        //chama a funcao x_mostra_nome que será gerada pelo sajax. 
		  //o primeiro parametro é o codigo e o segundo é a 
        //funcao JavaScript que tratara o retorno, no caso a mostra
        x_mostra_nome(cod, mostra);
}
</script>
</head>
<body>
<form name="teste">
<input type="text" name="codpes" onchange="get_nome(this)">
<input type="text" name="nompes">
</form>
</body>
</html>

Analisando o código fonte visualizado pelo navegador é possível verificar todo o código Javascript gerado pelo Sajax:

<html>
<head>
<title>Nome </title>
<script>
// remote scripting library
// (c) copyright 2005 modernmethod, inc
var sajax_debug_mode = false;
var sajax_request_type = "GET";

function sajax_debug(text) {
        if (sajax_debug_mode)
        alert("RSD: " + text)
}
function sajax_init_object() {
        sajax_debug("sajax_init_object() called..")
        var A;
        try {
                A=new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
                try {
                        A=new ActiveXObject("Microsoft.XMLHTTP");
                } catch (oc) {
                        A=null;
                }
        }
        if(!A && typeof XMLHttpRequest != "undefined")
        A = new XMLHttpRequest();
        if (!A)
        sajax_debug("Could not create connection object.");
        return A;
}

function sajax_do_call(func_name, args) {
        var i, x, n;
        var uri;
        var post_data;

        uri = "/saa/ajax.php";
        if (sajax_request_type == "GET") {
                if (uri.indexOf("?") == -1)
                uri = uri + "?rs=" + escape(func_name);
                else
                uri = uri + "&rs=" + escape(func_name);
                for (i = 0; i < args.length-1; i++)
                uri = uri + "&rsargs[]=" + escape(args[i]);
                uri = uri + "&rsrnd=" + new Date().getTime();
                post_data = null;
        } else {
                post_data = "rs=" + escape(func_name);
                for (i = 0; i < args.length-1; i++)
                post_data = post_data + "&rsargs[]=" + escape(args[i]);
        }

        x = sajax_init_object();
        x.open(sajax_request_type, uri, true);
        if (sajax_request_type == "POST") {
                x.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
                x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        }
        x.onreadystatechange = function() {
                if (x.readyState != 4)
                return;
                sajax_debug("received " + x.responseText);

                var status;
                var data;
                status = x.responseText.charAt(0);
                data = x.responseText.substring(2);
                if (status == "-")
                alert("Error: " + data);
                else
                args[args.length-1](data);
        }
        x.send(post_data);
        sajax_debug(func_name + " uri = " + uri + "/post = " + post_data);
        sajax_debug(func_name + " waiting..");
        delete x;
}


// wrapper for mostra_nome
function x_mostra_nome() {
        sajax_do_call("mostra_nome",
        x_mostra_nome.arguments);
}

function mostra(nome) { //esta funcao retorna o valor para o campo do formulario
        document.teste.nompes.value=nome;
}

function get_nome(c) { //esta funcao chama a funcao PHP exportada pelo Ajax
        cod = c.value;
        //chama a funcao x_mostra_nome que será gerada pelo sajax. 
		  //o primeiro parametro é o codigo e o segundo é a 
        //funcao JavaScript que tratara o retorno, no caso a mostra
        x_mostra_nome(cod, mostra);
}
</script>
</head>
<body>
<form name="teste">
<input type="text" name="codpes" onchange="get_nome(this)">
<input type="text" name="nompes">
</form>
</body>
</html>
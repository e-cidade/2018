<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_sysarqarq_classe.php");
include("dbforms/db_funcoes.php");
$cldb_sysarqarq = new cl_db_sysarqarq;
//
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($modulo_testa)){
  $vazio = 1;
  $rstab = pg_exec("select d.nomemod,m.codarq,a.nomearq,a.tipotabela,a.naolibclass,a.naolibform,a.naolibfunc,a.naolibprog
                  from   db_sysarquivo a
                         inner join db_sysarqmod m on a.codarq = m.codarq
                         inner join db_sysmodulo d on d.codmod = m.codmod 
	          where d.codmod = $modulo_testa
	          order by nomemod,nomearq");
  
}else if(isset($tabela)){
  $vazio = 2;
  $rstab = pg_exec("select *
                  from   db_sysarquivo 
	          where codarq = $tabela
	          ");
}else{
  $vazio = 0;
}

//
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function valida_submit(){
    if (document.form1.nome_arq == ""){
       alert('O nome do arquivo deve ser informado!!!'); 
       return false;
    }else{
       return true
    }
}

function js_marca(obj){
 location.href = 'sys4_processa011.php?tabela='+obj;
}

function js_tranca(obj){
  if(obj.checked == true){
    document.form1.g_form.checked=false;
    document.form1.g_form.disabled=true;

    document.form1.g_prog.checked=false;
    document.form1.g_prog.disabled=true;
  }else{
    document.form1.g_form.disabled=false;
    document.form1.g_prog.disabled=false;
  }
}
	
</script>
<style type="text/css">
.tabela {border:1px solid black; top:25px; left:150}
.input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 27px;
        border: 1px solid #999999;
}
.tdblack {

    border-bottom:1px solid black;

}
.cl_iframe {
   border: 1px solid #999999;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  marginwidth="0" marginheight="0" bgcolor="#cccccc" >
<form name="form1">
<table  width='100%' cellspacing="2" cellpadding="0" bgcolor="#cccccc" >
 <?
 if($vazio==1){
 
   // cria as layers com o conteúdo das tabelas
   $j = 0;
   $modulo = "";
  //define quantos checkboxes iram ficar por linha da tabela.
   $quebratab = 1;
   while ($j < pg_numrows($rstab)){
      db_fieldsmemory($rstab,$j);
      /*
        "0"  Manutenção
        "1"  Parâmetro
        "2"  Dependência
      */
      $sCorDiv = "#FFFFFF";
      if ($tipotabela == '0') {
        $sCorDiv = "#FFFFFF";
      }else if ($tipotabela == '1'){
        $sCorDiv = "#F8EC07";
      }else if ($tipotabela == '2'){
        $sCorDiv = "#FF3300";
      }

      if ($modulo == $nomemod){
    	  if ($quebratab == 4){
            $quebratab = 1;	
            echo "</tr><tr>";
        }else{
            $quebratab++;
        }
        echo "<td width=180>";
        echo "  <div onClick=\"js_marca('$codarq')\" name='".$nomearq."' style='background-color:".$sCorDiv."'>
                  ".$nomearq."
                </div> 
              </td>\n";
      }else{
         $quebratab=1;
         echo "</table>";
         echo "<table width='100%' cellspacing=0>";
         echo "<tr>
	               <td align='center' style='border-bottom:1px solid black'><font size='2'><b>Tipos de Tabelas:</b></td>
	               <td align='center' style='background-color:#FFFFFF;border-bottom:1px solid black'><font size='2'><b>Manutenção</b>       </td>
	               <td align='center' style='background-color:#F8EC07;border-bottom:1px solid black'><font size='2'><b>Parâmetro</b>        </td>
	               <td align='center' style='background-color:#FF3300;border-bottom:1px solid black'><font size='2'><b>Dependência</b>      </td>
	             </tr>";
         echo "<tr>\n
                 <td width=180 >";
				 echo "    <div onClick=\"js_marca('$codarq')\" name='".$nomearq."' style='background-color:".$sCorDiv."'>
                     ".$nomearq."
                   </div> 
                 </td> \n";

     }
     $modulo = $nomemod;
     $j++;
   }
   echo "</tr>";
   echo "</table>";
  }else if($vazio==2){
    db_fieldsmemory($rstab,0); 
    ?>
      <tr>
        <td align='center' colspan="3" class='tdblack'>
          <font size='2'> <b>Arquivo: </b></font>
       	  <input type='text' readonly name='arquivo' id='arquivo' value='<?=$codarq?>' size='5'>
       	  <input type='text' readonly name='arqnom' id='arqnom' value='<?=$nomearq?>' >
        </td>
      </tr>
      <tr>
        <td align='center' class='tdblack'><font size='2'><b>S</b></font>
        </td>
        <td align='center' class='tdblack'><font size='2'><b>Procedimentos</b></font>
        </td>
        <td align='center' class='tdblack'> <font size='2'><b> Estado </b></font>
        </td>
      </tr>
      <tr>
        <td>
      	  <input id='g_estrut' name='g_estrut' type='checkbox'>
        </td>
        <td>
          Processa Estrutura no Banco ...
        </td>
        <td>
	        <iframe id='processa002' src='' height='20' class='cl_iframe' ></iframe>
        </td>
      </tr>
      <tr>
        <td>
          <?
	        if($naolibclass=='f')
	          echo "<input id='g_classes' name='g_classes' type='checkbox' checked>";
        	?> 
        </td>
        <td>Gera Classes PHP ...</td>
        <td height="40">
          <?
	        if($naolibclass=='f'){
	          echo "<iframe id='processa003' src='' height='20' class='cl_iframe'></iframe> ";
	        }else{
	          echo "<strong>Sem acesso a geração de classe para esta tabela.</strong><br>";
	        }
      	  ?> 
        </td>
      </tr>
      <tr>
        <td>
	        <?
	        if($naolibfunc=='f'){
	          echo " <input id='g_funcao' name='g_funcao' type='checkbox' checked>";
          }
     	    ?> 
        </td>
        <td>Gera Função Pesquisa ...</td>
        <td height="40">
	        <?
	        if($naolibfunc=='f'){
	          echo "<iframe id='processa004' src='' height='20' class='cl_iframe'></iframe>";
	        }else{
	          echo "<strong>Sem acesso a geração de função para esta tabela.</strong><br>";
	        }
	        ?> 
        </td>
      </tr>
      <tr>
        <td>
	       <?
	       if($naolibform=='f'){
	         echo "<input id='g_form' name='g_form' type='checkbox' checked>";
         }
	       ?> 
        </td>
        <td>Gera Formulário Manutenção ...</td>
        <td height="40">
			    <?
			    if($naolibform=='f'){
			      echo "<iframe id='processa005' src='' height='20' class='cl_iframe'></iframe>";
			    }else{
		  	    echo "<strong>Sem acesso a geração de formulário para esta tabela.</strong><br>";
		  	  }
		  	  ?> 
        </td>
      </tr>
      <tr>
        <td>
	        <?
	        if($naolibprog=='f'){
	          echo "<input id='g_prog' name='g_prog' type='checkbox' checked>";
          }
	        ?>
        </td>
        <td >Gera Programas Manutenção ...</td>
        <td height="40">
	        <?
	        if($naolibprog=='f'){
	          echo "<iframe id='processa006' src='' height='20' class='cl_iframe'></iframe>";
	        }else{
	          echo "<strong>Sem acesso a geração de programas para esta tabela.</strong><br>";
	        }
	        ?> 
        </td>
      </tr>
      <tr>
        <td>
	        <input id='g_item' name='g_item' type='checkbox' >
	        <input id='caditem' name='caditem' type='hidden' value='' >
	        <input id='cadmodulo' name='cadmodulo' type='hidden' value='' >
        </td>
        <td>Gerando Itens Menu ...	 
   	      <input name="Seleciona" onClick="js_pesquisa();" accesskey="s" type="button" id="seleciona" value="Seleciona">    
        </td>
        <td>
	        <iframe id='processa007' src='' height='20' class='cl_iframe'></iframe>
        </td>
      </tr>      
   <?
   $result01 = $cldb_sysarqarq->sql_record($cldb_sysarqarq->sql_query_filho($tabela,null,"db_sysarqarq.codarq,nomearq"));
	 if($cldb_sysarqarq->numrows>0){
   ?>
    <tr>
      <td valign="top">
	      <input id='g_aba' name='g_aba' type='checkbox'  onclick='js_tranca(this);'>
      </td>
      <td nowrap valign='top'>
        <table cellspacing='0' cellpadding='0'>
	        <tr>
            <td align='left' valign='top'>Gera aba ...</td>  
            <td>
   	          <fieldset>
                <legend>
                  <small>ABAS</small>
                </legend>
                <?
                  db_selectmultiple("tabela_filho",$result01,3,1,"","","","");
                ?>
	            </fieldset>
            </td>
     	    </tr>
      	</table>
      </td>
      <td height="40">
	      <?
	      if($naolibfunc=='f'){
	        echo "<iframe id='processa008' src='' height='' class='cl_iframe'></iframe>";
        }else{
	        echo "<strong>Sem acesso a geração de aba para esta tabela.</strong><br>";
	      }
	      ?> 
      </td>
    </tr>
 <?}?>
      <tr>
      <td colspan="3" align="center">
	 <br>
	 <input name="processar" type="button" value="Processar Opções" onclick="processa_rotina();">
      </td>
   <?
  }
  ?>      
  </table>
</form>  
</body>
</html>

<script>

<?
if($vazio!=0){
?>

function processa_rotina(){
  
  document.getElementById('processa002').src = '';
	 
  <?
  if($naolibclass=='f')
    echo "document.getElementById('processa003').src = '';";
  if($naolibfunc=='f')
    echo "document.getElementById('processa004').src = '';";
  if($naolibform=='f')
    echo "document.getElementById('processa005').src = '';";
  if($naolibprog=='f')
    echo "document.getElementById('processa006').src = '';";
  ?>
  

  <?
  if($cldb_sysarqarq->numrows>0){ 
  ?>  

  if(document.getElementById('g_aba').checked==true){
    filhos = document.form1.tabela_filho;
    codfilhos='';
    sep = '';
    for(i=0; i<filhos.length; i++){
      if(filhos[i].selected==true){
        codfilhos += sep+filhos[i].value;
         sep='XX';
      }   
    }
    if(codfilhos==''){
      alert("Selecione uma tabela como filha, para poder gerar aba! ");
      return false;
    }else{
      document.getElementById('processa008').src = 'sys4_processa008.php?codarq='+document.form1.arquivo.value+'&codfilhos='+codfilhos;
    }    
  } 
<?
  }
?>
  
  document.getElementById('processa007').src = '';

  if(document.getElementById('g_estrut').checked==true){
    document.getElementById('processa002').src = 'sys4_processa002.php?codarq='+document.form1.arquivo.value;
  }

  <?
  if($naolibclass=='f')
    echo " if(document.getElementById('g_classes').checked==true)
             document.getElementById('processa003').src = 'sys4_processa003.php?codarq='+document.form1.arquivo.value;";
  if($naolibfunc=='f')
    echo " if(document.getElementById('g_funcao').checked==true)
             document.getElementById('processa004').src = 'sys4_processa004.php?codarq='+document.form1.arquivo.value;";
  if($naolibform=='f')
    echo " if(document.getElementById('g_form').checked==true)
             document.getElementById('processa005').src = 'sys4_processa005.php?codarq='+document.form1.arquivo.value;";
  if($naolibprog=='f')
    echo " if(document.getElementById('g_prog').checked==true)
             document.getElementById('processa006').src = 'sys4_processa006.php?codarq='+document.form1.arquivo.value;";
  ?>
  if(document.getElementById('g_item').checked==true)
    document.getElementById('processa007').src = 'sys4_processa007.php?codarq='+document.form1.arquivo.value+'&itemmenu='+document.getElementById('caditem').value+'&modulomenu='+document.getElementById('cadmodulo').value;
     
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe','con1_caditens002.php','Pesquisa',true);
}


function js_pesquisaitemcad(chave,qmodulo){
  db_iframe.hide();
  parent.document.getElementById('caditem').value = chave;
  parent.document.getElementById('cadmodulo').value = qmodulo;
  parent.document.getElementById('g_item').checked = chave;
}


</script>
<?
}
?>
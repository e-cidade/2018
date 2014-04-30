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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_divida_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$cldivida = new cl_divida;
$clrotulo->label("v01_livro");
$clrotulo->label("v01_dtoper");
$db_opcao = 1;
db_postmemory($HTTP_POST_VARS);
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor=#CCCCCC onLoad="if(document.form1) document.form1.elements[0].focus()" >


  <form class="container" name="form1" action="div4_processalivro002.php" target="livro" method="post">
    <fieldset>
      <legend>Reemissão do Livro - Dívida</legend>
      <table class="form-container">
 	    <tr>
	      <td nowrap title="Ordem Todas/Dívida Ativa/Parceladas" >
	       Tipo:
	      </td>
	      <td>
	       <? 
	        $tipo = array("r"=>"Resumido","c"=>"Completo");
	        db_select("tipo",$tipo,true,2); 
	       ?>
	      </td>
	    </tr>
	    <tr>
          <td>Complementar:</td>
	      <td>
	       <?
            $arr = array("nao" => "Não", "sim" => "Sim");
            db_select("complementar", $arr, true, 1, "");
       	   ?>
	   	  </td>
        </tr>
        <tr>
	      <td>Numero do livro:</td>
	      <td>
	       <?
	        if(empty($processar)){	   
	         $result=$cldivida->sql_record($cldivida->sql_query_file(null,"max(v01_livro) as v01_livro",""," v01_folha <> 0 and v01_instit = ".db_getsession('DB_instit') ));
  	         db_fieldsmemory($result,0);
	        } 	
            db_input('v01_livro',6,$Iv01_livro,true,'text',1)
	       ?>
	      </td>
	    </tr>
        <tr>
	      <td>Data para correção:</td>
	      <td>
	       <?
            if(empty($v01_dtoper_dia)){
	         $v01_dtoper_dia =  date("d",db_getsession("DB_datausu"));
	         $v01_dtoper_mes =  date("m",db_getsession("DB_datausu"));
	         $v01_dtoper_ano =  date("Y",db_getsession("DB_datausu"));
            }	
            db_inputdata('v01_dtoper',@$v01_dtoper_dia,@$v01_dtoper_mes,@$v01_dtoper_ano,true,'text',$db_opcao,"")
	       ?>
	      </td>
	    </tr>
      </table>
    </fieldset>
    <input name="processar" type='submit' value="Processar" onClick="return imprime()">
  </form>

  <? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
 </body>
</html>
<script>
 function imprime(){
  jan = window.open('','livro','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  return true;
 }
</script>
<script>

$("v01_livro").addClassName("field-size1");
$("v01_dtoper").addClassName("field-size2");

</script>
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
require_once("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e54_autori");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_abre(){
   obj = document.form1;
   query='';
   if(obj.e54_autori_ini.value!='' || obj.e54_autori_fim.value!=''){
     if(obj.e54_autori_ini.value!=''){
       query += "e54_autori_ini="+obj.e54_autori_ini.value;
     }
     if(obj.e54_autori_fim.value!=''){
       if(query!=''){
	 query += "&";
       }
       query += "e54_autori_fim="+obj.e54_autori_fim.value;
     }
   }else{
       if((obj.dtini_dia.value !='') && (obj.dtini_dia.value !='') && (obj.dtini_mes.value !='')){
	 query +="dtini_dia="+obj.dtini_dia.value+"&dtini_mes="+obj.dtini_mes.value+"&dtini_ano="+obj.dtini_ano.value;
       }
       if((obj.dtfim_dia.value !='') && (obj.dtfim_mes.value !='') && (obj.dtfim_ano.value !='')){
	 if(query!=''){
	   query += "&";
	 }
	 query +="dtfim_dia="+obj.dtfim_dia.value+"&dtfim_mes="+obj.dtfim_mes.value+"&dtfim_ano="+obj.dtfim_ano.value;
       }
   }

   if(query==''){
     alert("Selecione alguma autorização ou indique o período!");
   }else{
     query += "&informa_adic="+obj.informa_adic.value;
     query += "&dtInicial="+$F('dtini')+"&dtFinal="+$F('dtfim');
     jan    = window.open('emp2_emiteautori002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
   }
}
</script>
</head>
<body style="margin-top: 25px; background-color: #CCCCCC;" >
<center>
<form name='form1'>
  <fieldset style="width: 500px">
    <legend><strong>Reemissão da Autorização de Empenho</strong></legend>
      <table>
        <tr>
  	      <td nowrap align="right">
  	      <strong>Autorizações de</strong>
  	      </td>
  	    <td>
  	      <? db_input('e54_autori',8,$Ie54_autori,true,'text',$db_opcao,"onchange='jscopiacampo();'","e54_autori_ini")  ?>
  	      <strong> à </strong>
  	      <? db_input('e54_autori',8,$Ie54_autori,true,'text',$db_opcao,"","e54_autori_fim")  ?>
  	    </td>
      </tr>
       <tr>
       <td align="right" >
         <b> Período:</b>
       </td>
       <td>
        <?  db_inputdata('dtini',@$dia,@$mes,@$ano,true,'text',1,"");
            echo " a ";
            db_inputdata('dtfim',@$dia,@$mes,@$ano,true,'text',1,"");
         ?>
       </td>
       </tr>
       <tr>
         <td align-"right"><b>Informações adicionais:</b></td>
         <td>
  <?
    $matriz = array("PC"=>"Mostrar autorização de processo de compras",
                    "AU"=>"Mostrar somente autorização");
    db_select("informa_adic", $matriz,true,$db_opcao);
  ?>
         </td>
       </tr>
      </table>
  </fieldset>
  <p>
    <input name='pesquisar' type='button' value='Consultar' onclick='js_abre();'>
  </p>
</form>
</center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function jscopiacampo(){
  if(document.form1.e54_autori_fim.value == ""){
    document.form1.e54_autori_fim.value = document.form1.e54_autori_ini.value;
  }
}
</script>
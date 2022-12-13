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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");
$db_opcao = 1;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <center>
    <form name="form1" method="get" action="">
       <table>
         <tr>
            <td>
            <fieldset> <legend><b>Consulta de Avaliações</b></legend>
                <table>
                  <tr>
                   <td nowrap title="<?=@$Trh01_regist?>">
                   <?
                    db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",$db_opcao);
                   ?>
                   </td>
                   <td nowrap> 
                   <?
                    db_input('rh01_regist',10,$Irh01_regist,true,'text',$db_opcao," onchange='js_pesquisarh01_regist(false);'"); 
                    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
                   ?>
                   </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Período:</b>
                    </td>
                    <td nowrap>
                    <?
                      db_inputdata('h64_dataini',null,null,null,true,'text',$db_opcao,"");
                      echo " <b>A</b> ";
                      db_inputdata('h64_datafim',null,null,null,true,'text',$db_opcao,"");
                    ?>
                    </td>
                   </tr>
                   <tr>
                     <td>
                       <b>Filtro:</b>
                     </td>
                     <td>
                     <?
                      $opcoes = array(
                                      "t" => "Todos",
                                      "n" => "Não Aplicadas",
                                      "a" => "Aplicadas"
                                     );

                     db_select('filtro',$opcoes,true,$db_opcao,"");
                     
                     ?>
                     </td>
                </table>
              </fieldset>
           </td>
         </tr>
         <tr>
          <td colspan='2' style='text-align:center'>
            <input name="consultar" type="button" id="consultar" value="Consultar" onclick='js_montaQuery()';>
          </td>
         </tr>
       </table> 
    </form>
   </center> 
</body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhestagioagenda.php?funcao_js=parent.js_mostrarhpessoal1|h57_regist|z01_nome','Consulta Matrícula',true);
  }else{
     if(document.form1.rh01_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhestagioagenda.php?regist='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Consulta Matrícula',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}

function js_montaQuery(){

   if (($F('h64_dataini') == '' || $F('h64_datafim') == '') && $F('rh01_regist') == ''){
      alert('Preencha o periodo de datas  ou a matrícula do funcionário');
   }else{
      
      strQuery  = "dataInicial="+$F('h64_dataini')+'&dataFinal='+$F('h64_datafim');
      strQuery += "&iMatricula="+$F('rh01_regist');
      strQuery += "&sFiltro="+$F('filtro');
      js_OpenJanelaIframe('top.corpo','db_iframe_resultado','rec3_rhestagioavaliacoes002.php?'+strQuery,'Resultado da Consulta',true);

   }
}
</script>
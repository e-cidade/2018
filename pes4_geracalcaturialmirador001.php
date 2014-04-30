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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('r44_selec');
$clrotulo->label('r44_descr');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
 <center>
 
 <table style="margin-top:15px">
 <tr><td>
  <fieldset><legend><B>Cálculo Atuarial - Mirador</b></legend>

  <table  align="center">
    <form name="form1" method="post" action="pes4_geracalcaturialmirador002.php">
      
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competncia">
        <b>Ano / Mês :</b>
        </td>
        <td align="left">
          <?
           $ano = db_anofolha();
           db_input('ano',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $mes = db_mesfolha();
           db_input('mes',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      
      <tr> 
        <td align="left" nowrap title="Seleção:" >
	        <b>Tipo de Arquivo</b>
        </td>
        <td>
          <?
          $aTipoArquivo = array(""=>"Selecione...", "A"=>"Ativos", "I"=>"Inativos", "P"=>"Pensionistas");          
          db_select("tipo_arquivo", $aTipoArquivo, true, 1, "");
          ?>
        </td>
      </tr>
      
      <tr>
        <td align="left">
          <b>Data da Mudança do Regime:<b>
        </td>
        <td align="left">
			  <?
			    db_inputdata("data_mudanca", @$dia_mudanca, @$mes_mudanca, @$ano_mudanca, true, "text", 1);
			  ?>
        </td>
      </tr>
      
      <tr>
        <td align="left">
        <?
          db_ancora("<b>Seleção de Professores:</b>","js_pesquisaprof(true)",1);
        ?>
        </td>        
        <td align="left">
			  <?
			    db_input('r44_selec',4,$Ir44_selec,true,'text',2,'onchange="js_pesquisaprof(false)"');
          db_input('r44_descr',40,$Ir44_selec,true,'text',3,'');
        ?>
        </td>
      </tr>
    </table>
    </fieldset>
    </td></tr>
    </table>
    
    <table>
      <tr>
        <td>
        <?
          $aux_ins = new cl_arquivo_auxiliar;
          $aux_ins->cabecalho = "<strong>Insalubridade / Periculosidade</strong>";
          $aux_ins->codigo = "r06_codigo"; //chave de retorno da func
          $aux_ins->descr  = "r06_descr";   //chave de retorno
          $aux_ins->nomeobjeto = 'rubricas';
          $aux_ins->funcao_js = 'js_mostra_rubricas';
          $aux_ins->funcao_js_hide = 'js_mostra_rubricas1';
          $aux_ins->sql_exec  = "";
          $aux_ins->func_arquivo = "func_rubricas.php";  //func a executar
          $aux_ins->nomeiframe = "db_iframe_rubricas";
          $aux_ins->localjan = "";
          $aux_ins->onclick = "";
          $aux_ins->db_opcao = 2;
          $aux_ins->tipo = 2;
          $aux_ins->top = 0;
          $aux_ins->linhas = 5;
          $aux_ins->vwhidth = 250;
          $aux_ins->nome_botao = 'db_lanca_rubrica';
          $aux_ins->mostrar_botao_lancar = true;
          $aux_ins->obrigarselecao = false;
          $aux_ins->funcao_gera_formulario();
        ?>
        </td>
      </tr>
    </table>  
        
    
    
    <table>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Processar" onclick="return js_validapars()" >
 <!-- onclick="return js_validapars()"        <input name="verificar" type="submit" value="Download" > -->
        </td>
      </tr>
  </form>
    </table>
    </fieldset></td></tr>
    </table>
    </script>
    </center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaprof(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostraprof1|r44_selec|r44_descr','Pesquisa',true);
  }else{
     if(document.form1.r44_selec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostraprof','Pesquisa',false);
     }else{
       document.form1.r44_descr.value = '';
     }
  }
}
function js_mostraprof(chave,erro){
  document.form1.r44_descr.value = chave; 
  if(erro==true){ 
    document.form1.r44_selec.focus(); 
    document.form1.r44_selec.value = ''; 
  }
}
function js_mostraprof1(chave1,chave2){
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value   = chave2;
  db_iframe_selecao.hide();
  js_insSelectrubricas();
}
function js_validapars (){

   ano          = $F('ano');
   mes          = $F('mes');
   tipo_arquivo = $F('tipo_arquivo');

   if (ano == '' ||  mes == '' || tipo_arquivo == ''){
     
     alert('Verifique os Parametros!\n parametros obrigatórios nulos.');
	   return false;
	      
   }else{
      
     if (tipo_arquivo == 'A') {
       if ($F('data_mudanca')=='') {
         alert("Ao selecionar o Tipo de Arquivo 'Ativos', você deve selecionar uma Data da Mudança do Regime");
         return false;        
       }
     }
     
     for (var i=0; i< $('rubricas').options.length; i++)
       $('rubricas').options[i].selected = true;
      
     return true;
   }
}

</script>
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
include("libs/db_app.utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, estilos.css, prototype.js, arrays.js");
?>
<script>
function js_emite(){

  var aListaOrgaos    = parent.iframe_orgao.js_campo_recebe_valores();
  var sOpcoesOrgaos   = parent.iframe_orgao.$F('opcoesorgaos');
  
  var aListaUnidades  = parent.iframe_unidade.js_campo_recebe_valores();
  var sOpcoesUnidades = parent.iframe_unidade.$F('opcoesunidade');
  
  var aListaDeptos    = parent.iframe_departamento.js_campo_recebe_valores();
  var sOpcoesDeptos   = parent.iframe_departamento.$F('opcoesdepartamento');
  
  var aListaDivisoes  = parent.iframe_divisao.js_campo_recebe_valores();
  var sOpcoesDivisoes = parent.iframe_divisao.$F('opcoesdivisao');
  
  var aListaContas    = parent.iframe_contascontabeis.js_campo_recebe_valores();
  var sOpcoesContas   = parent.iframe_contascontabeis.$F('opcoescontas');

  var sUrl = '';
  
  sUrl  = 'pat2_bensfinanc002.php?';
  sUrl += 'tipoagrupa='+document.form1.tipoagrupa.value;
  sUrl += '&data_base='+$F('data_ini');
  sUrl += "&data_base_fim="+$F('data_fim');
  sUrl += '&baixaini='+$F('t52_baixainicio');
  sUrl += '&baixafim='+$F('t52_baixafim');
  sUrl += '&bemtipo='+$F('tipo_bens');
  sUrl += "&listaorgaos="+aListaOrgaos;
  sUrl += "&opcoesorgaos="+sOpcoesOrgaos;
  sUrl += "&listaunidades="+aListaUnidades;
  sUrl += "&opcoesunidades="+sOpcoesUnidades;
  sUrl += "&listadepto="+aListaDeptos;
  sUrl += "&opcoesdepto="+sOpcoesDeptos;
  sUrl += "&listadivisoes="+aListaDivisoes;
  sUrl += "&opcoesdivisoes="+sOpcoesDivisoes;
  sUrl += "&listacontas="+aListaContas;
  sUrl += "&opcoescontas="+sOpcoesContas;
  
  var aListaConvenio  = js_campo_recebe_valores();
  var iMostraConvenio = $F('vinculoconvenio');
  
  sUrl += "&listacedentes="+aListaConvenio;
  sUrl += "&listabens="+$F('opcao_baixados');
  sUrl += "&opcoescedentes="+iMostraConvenio;

  var jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');
  jan.moveTo(0,0);
}

</script>  
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Opções:</b>
        </legend>     
        <table  align="center">
          <form name="form1" method="post" action="">
            
            <tr>
              <td align="left" nowrap title="Tipo de Agrupamento do Valor" >
              <strong>Tipo Agrupa :&nbsp;&nbsp;</strong>
              </td>
              <td>
      	  <? 
      	  $tipo_ordem = array ("0" => "Classificação", 
      	                       "1" => "Plano de Contas", 
      	                       "2" => "Plano de Contas/Classificação",
      	                       "3" => "localizacao (orgão)",
      	                       "4" => "localizacao (orgão/unidade)",
      	                       "5" => "localizacao (orgão/unidade/departamento)",
      	                       "6" => "localizacao (orgão/unidade/departamento/divisão)",
      	                     );
      	  db_select("tipoagrupa",$tipo_ordem,true,2); 
      	  ?>
              </td>
            </tr>
            
            <tr>
              <td align="left" nowrap="nowrap" title="Tipo de Bens">
                <strong>Tipo de Bens</strong>
              </td>
              <td>
                <?
                  $aBemTipo = array(''=>'TODOS', '1'=>'M&Oacute;VEIS', '2'=>'IM&Oacute;VEIS', '3'=>'SEMOVENTES');
                  db_select('tipo_bens', $aBemTipo, true, 1);
                ?>
              </td>
            </tr>
            
            <tr >
              <td align="left" nowrap title="Data para Emissão" >
              <strong>Data de aquisição:&nbsp;&nbsp;</strong>
              </td>
              <td>
      	  <? 
                $data_dia = date("d",db_getsession("DB_datausu"));
                $data_mes = date("m",db_getsession("DB_datausu"));
                $data_ano = date("Y",db_getsession("DB_datausu"));
      	
                 db_inputdata('data_ini',@$data_dia,@$data_mes,@$data_ano,true,'text',2); 
                 echo "&nbsp;<b>A&nbsp;</b>";
                 db_inputdata('data_fim',@$data_dia,@$data_mes,@$data_ano,true,'text',2);
                ?>
              </td>
            </tr>
            <tr>
              <td><strong>Per&iacute;odo da Baixa</strong></td>
              <td>
              <?
                db_inputdata('t52_baixainicio',null, null, null, true,'text',1);
                echo "&nbsp;<b>A&nbsp;</b>";
                db_inputdata('t52_baixafim',null, null, null, true,'text',1);
              ?>
              </td>
            </tr>
            <tr>
               <td nowrap align="left" title="Bens a serem listados"><b>Listar:</b></td>
               <td nowrap title="">
               <?
                 $matriz_baix = array("t"=>"Todos","n"=>"Não Baixados", "b"=>"Baixados"); 
                 db_select("opcao_baixados",$matriz_baix,true,1);
               ?>
               </td>
            </tr>
            <tr>
             <td align="left" nowrap title="Tipo de Agrupamento do Valor" >
              <strong>Convênios :&nbsp;&nbsp;</strong>
              </td>
              <td>
              <? 
              $aConvenios = array (1 => "Ambos", 
                                   2 => "Apenas vinculado a convênios",
                                   3 => "Apenas não vinculado a convênios"
                                 );
              db_select("vinculoconvenio", $aConvenios, true, 2, "onchange='js_showCedentes()'"); 
              ?>
              </td>
            </tr>
            <tr id='listacedentes' style="display: none">
              <td  colspan='5' style='text-align: center'>
              <table >
               <?
                 $oListaCedente = new cl_arquivo_auxiliar;
                 $oListaCedente->cabecalho = "<strong>Convênios</strong>";
                 $oListaCedente->codigo = "t04_sequencial"; //chave de retorno da func
                 $oListaCedente->descr  = "z01_nome"; //chave de retorno
                 $oListaCedente->nomeobjeto = 'cedentes';
                 $oListaCedente->funcao_js = 'js_mostra';
                 $oListaCedente->funcao_js_hide = 'js_mostra1';
                 $oListaCedente->sql_exec  = "";
                 $oListaCedente->nome_botao  = "lancacedentes";
                 $oListaCedente->func_arquivo = "func_benscadcedente.php";  //func a executar
                 $oListaCedente->nomeiframe = "db_iframe_cedentes";
                 $oListaCedente->localjan   = "";
                 $oListaCedente->onclick    ="";
                 $oListaCedente->db_opcao   = 2;
                 $oListaCedente->tipo       = 2;
                 $oListaCedente->top        = 0;
                 $oListaCedente->linhas     = 5;
                 $oListaCedente->obrigarselecao = false;
                 $oListaCedente->vwhidth    = '100%';
                 $oListaCedente->funcao_gera_formulario();
               ?>
                 </table>
              </td>
            </tr>
         </table>
      </fieldset>
      </td>
    </tr>     
    <tr>
      <td colspan="2" align = "center"> 
        <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
     </td>
   </tr>
  </form>
</table>
</center>
</body>
</html>
<script>

function js_showCedentes() {
  
  if ($F('vinculoconvenio') == 2) {
    $('listacedentes').style.display = "";
  } else {
    $('listacedentes').style.display = "none";
  }
}
</script>
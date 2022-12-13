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

//MODULO: Cadastro
require_once("dbforms/db_classesgenericas.php");

$cl_iframeseleciona = new cl_iframe_seleciona();
$cliptutabelasconfig->rotulo->label();
$cliptutabelasconfigcampocorrecao->rotulo->label();
?>
<form name="form1" method="post" action="">
  <?
    db_input('j122_sequencial',10,$Ij122_sequencial,true,'hidden',3,"");
    db_input('listacampos',10,'',true,'hidden',3,"");
  ?>
  <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td valign="top">
	      <?	        
	        $sSqlSyscampo  = " select *                                                                    ";
	        $sSqlSyscampo .= "   from db_sysarqcamp                                                        ";
	        $sSqlSyscampo .= "        inner join db_syscampo on db_syscampo.codcam = db_sysarqcamp.codcam  "; 
	        $sSqlSyscampo .= "  where db_sysarqcamp.codarq = {$j121_codarq}                                ";
	        $sSqlSyscampo .= "   and  substring(conteudo,1,5) = 'float'                                    ";

	        $sCampos                            = "j123_codcam as codcam";
	        $sWhere                             = "j123_iptutabelasconfig = {$j122_sequencial}";
	        $sSqlIptuTabelasConfigCampoCorrecao = $cliptutabelasconfigcampocorrecao->sql_query_file(null, $sCampos, 
	                                                                                                null, $sWhere);
	                                                                                          
	        $cl_iframeseleciona->sql           = $sSqlSyscampo;
	        $cl_iframeseleciona->campos        = "codcam,  nomecam, descricao";
	        $cl_iframeseleciona->legenda       = "Lista Campos";
	        $cl_iframeseleciona->alignlegenda  = "left";
	        $cl_iframeseleciona->textocabec    = "darkblue";
	        $cl_iframeseleciona->textocorpo    = "black";
	        $cl_iframeseleciona->fundocabec    = "#aacccc";
	        $cl_iframeseleciona->fundocorpo    = "#ccddcc";
	        $cl_iframeseleciona->iframe_nome   = 'listacampos';
	        $cl_iframeseleciona->chaves        = "codcam";
	        $cl_iframeseleciona->tamfontecabec = '12';
	        $cl_iframeseleciona->tamfontecorpo = '10';      
	        $cl_iframeseleciona->sql_marca     = $sSqlIptuTabelasConfigCampoCorrecao;
	        $cl_iframeseleciona->marcador      = true;
	        $cl_iframeseleciona->iframe_seleciona($db_opcao);
	      ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
        <input name='atualizar' type='submit' id='atualizar' value='Atualizar' onclick='return js_valida();'> 
      </td>
    </tr>
  </table>
</form>
<script>
function js_valida(){
  
  var sVirgula = '';
  var sDados   = '';
    
  var oListaCampos  = listacampos.document.form1;
  var iNroRegistros = oListaCampos.elements.length;  
  for( var iInd=0; iInd < iNroRegistros; iInd++){
  
    if( oListaCampos.elements[iInd].type == "checkbox" && oListaCampos.elements[iInd].checked){
      sDados  += sVirgula+oListaCampos.elements[iInd].value;
      sVirgula = ', ';
    }    
  }
  
  $('listacampos').value = sDados;
}
</script>
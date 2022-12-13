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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$oGet   = db_utils::postMemory($_GET);
$codrel = $oGet->iCodRel;

$aux_recursos	= new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o124_descricao");

$db_opcao = 1;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
<link href="estilos.css"            rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" width="550">
    <form name="form1" method="post" action="" >
			<tr>
        <td align="center" colspan="3">
	  			<fieldset>
						<legend>
							 <b>Filtros</b>
						</legend>
						<table>
	            <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Perspectiva</b>","js_pesquisao125_cronogramaperspectiva(true);",$db_opcao);
                ?>
              </td>
              <td nowrap> 
                <?
                db_input('o124_sequencial',10,$Io124_sequencial,true,'text',$db_opcao,
                         " onchange='js_pesquisao125_cronogramaperspectiva(false);'")
                ?>
                <?
                db_input('o124_descricao',40,$Io124_descricao,true,'text',3,'');
                db_input('codrel',40,'',true,'hidden',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Nível:</b>
              </td> 
              <td>
                <?
                 $aNiveis = array(
                                  1 => "Orgão",
                                  2 => "Unidade",
                                  3 => "Função",
                                  4 => "Subfunção",
                                  5 => "Programa",
                                  6 => "Projeto/Atividade",
                                  7 => "Elemento",
                                  8 => "Recurso",
                                  9 => "Orgao /Unidade /Recurso / Anexo",
                                 );
                  db_select("nivel", $aNiveis,true,1);
                  ?>          
                </td>
              </tr>     
             <tr>
                <td align="center" colspan="2">
                <fieldset style="width: 500px;"><legend><b>Opções</b></legend>
                	<table>
                		<tr>
                			<td><b>Periodicidade:</b></td>
                			<td>
                			<? 
                				$x = array('1'=>'Mensal','2'=>'Bimestral');
                				db_select('iPeriodoImpr',$x,1,1);
                			?>
                			</td>
                		</tr>
                		<tr>
                			<td>&nbsp;</td>
                			<td><? 
                			     db_selinstit('',300,100);
                			     db_input('filtra_despesa', 10,'',true, 'hidden', 3);
                			    ?></td>
                		</tr>
                	</table>
                </fieldset>
                  
                </td>
              </tr>	            
	  			  </table>
	  			</fieldset>
				</td>
      </tr>
      <tr>
        <td align="center">
          <input  name="emite" id="emite" type="button" value="Visualizar" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
</body>
</html>
<script>

function js_pesquisao125_cronogramaperspectiva(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_cronogramaperspectiva',
                        'func_cronogramaperspectiva.php?funcao_js='+
                        'top.corpo.iframe_g1.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano',
                        'Perspectivas do Cronograma',true);
  }else{
     if(document.form1.o124_sequencial.value != ''){ 
        js_OpenJanelaIframe('',
                            'db_iframe_cronogramaperspectiva',
                            'func_cronogramaperspectiva.php?pesquisa_chave='+
                            document.form1.o124_sequencial.value+
                            '&funcao_js=top.corpo.iframe_g1.js_mostracronogramaperspectiva',
                            'Perspectivas do Cronograma',
                            false);
     } else {
     
       document.form1.o124_descricao.value = '';
       document.form1.ano.value             = ''
        
     }
  }
}

function js_mostracronogramaperspectiva(chave,erro, ano){
  document.form1.o124_descricao.value = chave; 
  if(erro==true) { 
    
    document.form1.o124_sequencial.focus(); 
    document.form1.o124_sequencial.value = '';
      
  }
}

function js_mostracronogramaperspectiva1(chave1,chave2,chave3) {

  document.form1.o124_sequencial.value = chave1;
  document.form1.o124_descricao.value  = chave2;
  db_iframe_cronogramaperspectiva.hide();
}
variavel = 0;
function js_emite() {

    if ($F('o124_sequencial') == '') {

      alert('O campo Perspectiva é de preenchimento obrigatório.');
      return;
    }
    if (document.form1.db_selinstit.value == '') {

      alert('Selecione uma ou mais Instituições.');
      return;

    }
    variavel++; 
    var sQuery  = "?iPerspectiva="+$F('o124_sequencial');
    document.form1.filtra_despesa.value = parent.iframe_filtros.js_atualiza_variavel_retorno();
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel;
    document.form1.action = "orc2_cronogramacotasdespesa002.php";
    document.form1.submit();
      
}  
</script>
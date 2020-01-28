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
                db_ancora("<b>Perspectiva</b>","js_pesquisao124_sequencial(true);",$db_opcao);
                ?>
              </td>
              <td nowrap> 
                <?
                db_input('o124_sequencial',10,$Io124_sequencial,true,'text',$db_opcao," onchange='js_pesquisao124_sequencial(false);'")
                ?>
                <?
                db_input('o124_descricao',40,$Io124_descricao,true,'text',3,'');
                db_input('codrel',40,'',true,'hidden',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                
                   		<?
							          // $aux = new cl_arquivo_auxiliar;
							         $aux_recursos->cabecalho = "<strong>Recursos</strong>";
							         $aux_recursos->codigo = "o15_codigo"; //chave de retorno da func
							         $aux_recursos->descr  = "o15_descr";   //chave de retorno
							         $aux_recursos->nomeobjeto = 'recursos';
							         $aux_recursos->funcao_js = 'js_mostra_rec';
							         $aux_recursos->funcao_js_hide = 'js_mostra_rec1';
							         $aux_recursos->sql_exec  = "";
							         $aux_recursos->func_arquivo = "func_orctiporec.php";  //func a executar
							         $aux_recursos->nomeiframe = "db_iframe_orctiporec";
							         $aux_recursos->localjan = "";
							         $aux_recursos->onclick = "";
							         $aux_recursos->db_opcao = 2;
							         $aux_recursos->tipo = 2;
							         $aux_recursos->top = 0;
							         $aux_recursos->linhas = 5;
							         $aux_recursos->vwhidth = 400;
							         $aux_recursos->nome_botao = 'db_lanca_recurso';
							         $aux_recursos->funcao_gera_formulario();
							        	?>
							  
              </td>
           </tr>						
              <tr>
                <td align="center" colspan="2">
                <fieldset style="width: 500px;"><legend><b>Opções</b></legend>
                	<table>
                		<tr>
                			<td><b>Forma de Impressão:</b></td>
                			<td>
                			<? 
                				$x = array('1'=>'Por Receita','2'=>'Por Recurso');
                				db_select('iFormaImpr',$x,1,1);
                			?>
                			</td>
                		</tr>
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
                			<td><? db_selinstit('',300,100); ?></td>
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

	function js_pesquisao124_sequencial(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('',
	                        'db_iframe_cronogramaperspectiva',
	                        'func_cronogramaperspectiva.php?funcao_js=parent.js_mostrappalei1|o124_sequencial|o124_descricao',
	                        'Pesquisa',
	                        true);
	  }else{
	     if(document.form1.o124_sequencial.value != ''){ 
	        js_OpenJanelaIframe('',
	                            'db_iframe_cronogramaperspectiva',
	                            'func_cronogramaperspectiva.php?pesquisa_chave='
	                            +document.form1.o124_sequencial.value+'&funcao_js=parent.js_mostrappalei',
	                            'Pesquisa',
	                            false);
	     }else{
	       document.form1.o124_descricao.value = ''; 
	     }
	  }
	}
	
	function js_mostrappalei(chave, erro) {
	
	  document.form1.o124_descricao.value = chave; 
	  if(erro==true){ 
	    document.form1.o124_sequencial.focus(); 
	    document.form1.o124_sequencial.value = ''; 
	  } else {
        js_getVersoesPPA($F('o124_sequencial'));
      }
	  
	}
	
	function js_mostrappalei1(chave1,chave2){
	  
	  document.form1.o124_sequencial.value = chave1;
	  document.form1.o124_descricao.value = chave2;
     // js_getVersoesPPA(chave1);
	  db_iframe_cronogramaperspectiva.hide();
	    
	}

  function js_emite(){   
 
    var doc = document.form1;
    
    if ( doc.db_selinstit.value == '' ) {
      alert('Nenhum instituição selecionada');
      return false;
    } 

    if ( doc.o124_sequencial.value == '' ) {
      alert('Nenhum Perspectiva selecionado!');
      return false;
    }
    
        
    if (doc.codrel.value == '' ) {
      alert('Código do relatório não informado!');
      return false;
    }    
    
    var listaRecursos="";
    if($('recursos')){
	    //Le os itens lançados na combo do orgao
			vir="";
		 	listaRecursos="";
		 
		 	for(x=0;x<document.form1.recursos.length;x++){
		  	listaRecursos+=vir+document.form1.recursos.options[x].value;
		  	vir=",";
		 	}
			
		}
    
    var sQuery  = '?iRec='+doc.o124_sequencial.value; 
        sQuery += '&sListaInstit='+doc.db_selinstit.value;
        sQuery += '&iCodRel='+doc.codrel.value;
        sQuery += '&slistaRecursos='+listaRecursos;
        sQuery += '&iPeriodoImpr='+$F('iPeriodoImpr');
        sQuery += '&iFormaImpr='+$F('iFormaImpr');
 		
 		
 
     var jan = window.open('orc2_orcprevmensalrec002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
         jan.moveTo(0,0);
  }
//js_drawSelectVersaoPPA($('verppa'));  
</script>
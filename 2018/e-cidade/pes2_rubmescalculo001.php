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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('r44_des');

//db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <style>
      select {
        width: 315px;
      } 
      fieldset{
        width: 500px; 
        margin: 25px auto 10px;
      }
    </style>  
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC>
    <form name="form1" method="post" action="" onSubmit="return js_verifica();" id="formularioRelatorio">
      <fieldset>
        <table  align="center">
          <legend><strong>Relatório por código</strong></legend>
          
            <tr>
              <td nowrap title="Ano / Mes de competência" >
              <strong>Ano / Mês :&nbsp;&nbsp;</strong>
              </td>
              <td>
                <?
                 $DBtxt23 = db_anofolha();
                 db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
                ?>
                &nbsp;/&nbsp;
                <?
                 $DBtxt25 = db_mesfolha();
                 db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
                ?>
              </td>
            </tr>
            
            <tr> 
                <td title="<?=$Trh27_rubric?>"> 
                  <?
                  db_ancora(@ $Lrh27_rubric, "js_pesquisarrubric(true);", 1);
                  ?>
                </td>
                <td> 
                  <?
                  db_input('rh27_rubric', 8, $Irh27_rubric, true, 'text', 1, " onchange='js_pesquisarrubric(false);'");
                  db_input('rh27_descr', 30, $Irh27_descr,  true, 'text', 3, '');
                  ?>
                </td>
            </tr>
            
            <tr title="Seleção">
              <td>
                <?php
                  db_ancora("Seleção", "js_pesquisaSelecao(true)", 1);
                ?>
              </td>
              <td> 
                <?php
                  db_input('r44_selec', 8,  1, true, 'text', "", "onchange='js_pesquisaSelecao(false)'");
                  db_input('r44_des',   30, "", true, 'text', 3);
                ?>
              </td>
            </tr>
            
            <tr title="Tipo de folha">
              <td><b>Ponto :&nbsp;&nbsp;</b></td>
              <td>
               <?php
                 $aTipos = array("s"=>"Salário",
                                 "c"=>"Complementar",
                                 "d"=>"13o. Salário",
                                 "r"=>"Rescisão",
                                 "a"=>"Adiantamento");
                 
                 if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                   $aTipos["u"] = "Suplementar"; 
                 }
                 
                 db_select('ponto', $aTipos, true, 4, "");
               ?>
              </td>
            </tr>
            
            <tr  title="Ordem">
              <td><b>Ordem :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $x = array("a"=>"Alfabética","n"=>"Numérica","r"=>"Recurso","l"=>"Lotação","v"=>"Valor","q"=>"Quantidade");
                 db_select('ordem',$x,true,4,"");
               ?>
              </td>
            </tr>
            
            <tr  title="Tipo de Ordem">
              <td><b>Tipo de Ordem :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $x = array("asc"=>"Ascendente","desc"=>"Descendente");
                 db_select('tipoordem',$x,true,4,"");
               ?>
              </td>
            </tr>
            
            <tr title="Caso Analítico mostra servidores da rubrica selecionada, Sintético mostra somente o número.">
              <td><b>Totalização :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $x = array("a"=>"Analítico","s"=>"Sintético");
                 db_select('total',$x,true,4,"");
               ?>
              </td>
            </tr>
            
            <tr  title="Tipo de Relatório">
              <td><b>Tipo :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $x = array("r"=>"Relatório","a"=>"Arquivo","p"=>"Planilha");
                 db_select('tipo',$x,true,4,"");
               ?>
              </td>
            </tr>
            
            <tr  title="Modelo da Página">
              <td><b>Página :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $xy = array("p"=>"Paisagem","r"=>"Retrato");
                 db_select('pagina',$xy,true,4,"");
               ?>
              </td>
            </tr>
            
            <tr  title="Dados Cadastrais atual ou por período">
              <td><b>Dados Cadastrais :&nbsp;&nbsp;</b></td>
              <td >
               <?
                 $xcad = array("a"=>"Atual","p"=>"Período");
                 db_select('mes_dados',$xcad,true,4,"");
               ?>
              </td>
            </tr>
        </table>
      </fieldset>
      <center>
        <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
      </center>
    </form>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
  <script>
    /**
    * Realiza a busca de rubricas, retornando o código e descrição da rubrica escolhida
    */
    function js_pesquisarrubric(lMostra) {
        
      if ( lMostra) {
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr','Pesquisa',true);
      } else {
          
         if ( $F(rh27_rubric) != '' ) {
             
           quantcaracteres = $F(rh27_rubric).length;
           
           for ( i=quantcaracteres;i<4;i++ ) {
             $(rh27_rubric).setValue("0"+$F(rh27_rubric));
           }
           
           js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+$F(rh27_rubric)+'&funcao_js=parent.js_mostrarubricas','Pesquisa',false);
         } else { 
           $(rh27_descr).setValue(''); 
         }
      }
    }

    /**
    * Trata o retorno da função js_pesquisarrubric()
    */
    function js_mostrarubricas(sChave, lErro) {
        
      $(rh27_descr).setValue(sChave);
      if ( lErro ) {
          
        $(rh27_rubric).setValue('');
        $(rh27_rubric).focus();
      }
    }

    /**
    * Trata o retorno da função js_pesquisarrubric()
    */
    function js_mostrarubricas1(sChave1,sChave2){
        
      $(rh27_rubric).setValue(sChave1);
      $(rh27_descr).setValue(sChave2);
      $(db_iframe_rhrubricas).hide();
    }

    /**
    * Realiza a busca de seleções retornando o código e descrição da rubrica escolhida;
    */
    function js_pesquisaSelecao(lMostra) {
        	  
    	if ( lMostra ) {
    	  js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_geraform_mostraselecao1|r44_selec|r44_descr&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
    	} else {
    	  if ( $F(r44_selec) != "" ) {
    	    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave=' + $F(r44_selec) + '&funcao_js=parent.js_geraform_mostraselecao&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
    	  } else {
    	    $(r44_des).setValue(""); 
    	  }
    	}
    }

    /**
    * Trata o retorno da função js_pesquisaSelecao().
    */
    function js_geraform_mostraselecao(sDescricao, lErro) {
      
    	if ( lErro ) { 

    	  $(r44_selec).setValue('');
    	  $(r44_selec).focus(); 
    	}
    	
    	$(r44_des).setValue(sDescricao); 
    }

    /**
    * Trata o retorno da função js_pesquisaSelecao();
    */
    function js_geraform_mostraselecao1(sChave1, sChave2) {
    	  
      $(r44_selec).setValue(sChave1);
      
      if( $(r44_des) ) {
        $(r44_des).setValue(sChave2);
      }
      
      db_iframe_selecao.hide();
    }

    /**
    * Emite o Relatorio a partir dos dados enviados
    */
    function js_emite(){
    	  
      var lEmite = true;
            
      if ( $F(rh27_rubric) == '' ) {
          
        if ( confirm ( "Você escolheu nenhuma rubrica. Imprimir todas ?" ) ) {
        	lEmite = true;
        } else {
        	lEmite = false;
        }
      }
      
      if ( lEmite ) {

        /**
        * Monta um objeto com os dados do formulario, para ser enviado para a geração do relatório
        */  
        var oDados = new Object();
        
        oDados.sTotalizacao     = $F(total);
        oDados.sTipoOrdem       = $F(tipoordem);
        oDados.sOrdem           = $F(ordem);
        oDados.sTipo            = $F(tipo);
        oDados.sPonto           = $F(ponto);
        oDados.sPagina          = $F(pagina);
        oDados.iRubrica         = $F(rh27_rubric);
        oDados.iSelecao         = $F(r44_selec);
        oDados.iAno             = $F(DBtxt23);
        oDados.iMes             = $F(DBtxt25);
        oDados.sDadosCadastrais = $F(mes_dados);
        
        if ( $F(tipo) == 'a' || $F(tipo) == 'p' ) {
          js_OpenJanelaIframe('top.corpo','db_iframe_bbrubmescalculo','pes2_rubmescalculo002.php?sParametros='+Object.toJSON(oDados),'Gerando Arquivo',false);
        } else {
            
          jan = window.open('pes2_rubmescalculo002.php?sParametros='+Object.toJSON(oDados),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          jan.moveTo(0,0);
        }
      }
    }

    /**
    * Realiza a abertura do arquivo quando for do tipo TXT.
    */
    function js_detectaarquivo(sArquivo){
        
    	var sListagem = sArquivo + "#Download arquivo TXT ";
      top.corpo.db_iframe_bbrubmescalculo.hide();
      js_montarlista(sListagem,"form1");
    }

    /**
    * Realiza a abertura do arquivo quando for do tipo CSV.
    */
    function js_detectaarquivo1(sArquivo){

    	var sListagem = sArquivo + "#Download arquivo CSV ";
    	top.corpo.db_iframe_bbrubmescalculo.hide();
      js_montarlista(sListagem,"form1");
    }
  </script>
</html>
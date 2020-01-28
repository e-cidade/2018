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
require_once ("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('r02_codigo');
$clrotulo->label('r02_descr');
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
          <legend><strong>Relatório de Padrões Por Ano</strong></legend>
          
            <tr>
              <td nowrap title="Ano de competência" >
              <strong>Ano :&nbsp;&nbsp;</strong>
              </td>
              <td>
                <?
                 $DBtxt23 = db_anofolha();
                 db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
                ?>
                &nbsp;&nbsp; a &nbsp;&nbsp;
                <?
                 $anofinal = db_anofolha();
                 db_input('anofinal',4,'',true,'text',2,'')
                ?>
              </td>
            </tr>
            
            <tr> 
                <td title="Informe o padrão ou deixe em branco para todos."> 
                  <?
                  db_ancora("Padrão:", "js_pesquisarpadrao(true);", 1);
                  ?>
                </td>
                <td> 
                  <?
                  db_input('r02_codigo', 8, $Ir02_codigo, true, 'text', 1, " onchange='js_pesquisarpadrao(false);'");
                  db_input('r02_descr', 30, $Ir02_descr,  true, 'text', 3, '');
                  ?>
                </td>
            </tr>
            
            <tr title="Tipo de folha">
              <td><b>Regime :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $x = array("t"=>"Todos","1"=>"Estatutários","2"=>"CLT","3"=>"Extra Quadro");
                 db_select('regime',$x,true,4,"");
               ?>
              </td>
            </tr>
            
            <tr  title="Ordem">
              <td><b>Ordem :&nbsp;&nbsp;</b></td>
              <td>
               <?
                 $x = array("p"=>"Padrão","r"=>"Regime");
                 db_select('ordem',$x,true,4,"");
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
    function js_pesquisarpadrao(lMostra) {
        
      if ( lMostra) {
        js_OpenJanelaIframe('top.corpo','db_iframe_padrao','func_padroes.php?funcao_js=parent.js_mostrapadrao1|r02_codigo|r02_descr','Pesquisa',true);
      } else {
          
         if ( $F(r02_codigo) != '' ) {
           
           js_OpenJanelaIframe('top.corpo','db_iframe_padrao','func_padroes.php?pesquisa_chave='+$F(r02_codigo)+'&funcao_js=parent.js_mostrapadrao','Pesquisa',false);
         } else { 
           $(r02_descr).setValue(''); 
         }
      }
    }

    /**
    * Trata o retorno da função js_pesquisarrubric()
    */
    function js_mostrapadrao(sChave, lErro) {
        
      $(r02_descr).setValue(sChave);
      if ( lErro ) {
          
        $(r02_codigo).setValue('');
        $(r02_codigo).focus();
      }
    }

    /**
    * Trata o retorno da função js_pesquisarrubric()
    */
    function js_mostrapadrao1(sChave1,sChave2){
        
      $(r02_codigo).setValue(sChave1);
      $(r02_descr).setValue(sChave2);
      $(db_iframe_padrao).hide();
    }

    /**
    * Emite o Relatorio a partir dos dados enviados
    */
    function js_emite(){

      /**
      * Monta um objeto com os dados do formulario, para ser enviado para a geração do relatório
      */  
      var oDados = new Object();
      if( $F(DBtxt23) > $F(anofinal) ){
        alert('O ano final não pode ser menor que o ano inicial. Verifique!');
        return false;
      }
      
      oDados.sOrdem           = $F(ordem);
      oDados.iRegime          = $F(regime);
      oDados.sPadrao          = $F(r02_codigo);
      oDados.iAno1            = $F(DBtxt23);
      oDados.iAno2            = $F(anofinal);
          
        jan = window.open('pes2_relpadroesanual002.php?sParametros='+Object.toJSON(oDados),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
    }

  </script>
</html>
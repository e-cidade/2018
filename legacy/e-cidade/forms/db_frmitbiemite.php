<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBseller Servicos de Informatica             
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

//MODULO: itbi
require_once("classes/db_db_config_classe.php");
$clitbicancela->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");
$cldb_config = new cl_db_config;

?>
<form name="form1" method="post" action="">
  <fieldset>
  <legend><b>Emite Guia de ITBI</b></legend>
    <table align="center" border="0">
      <tr>
        <td nowrap title="<?=@$Tit16_guia?>">
           <?
             db_ancora(@$Lit01_guia,"js_pesquisait01_guia(true);",$db_opcao);
           ?>
        </td>
        <td> 
    			<?
    			  db_input('it01_guia',10,$Iit01_guia,false,'text',3," onkeyup=\"js_onlyNumbers(this, event);\" onblur=\"js_onlyNumbers(this, event);\"");
    			?>
        </td>
      </tr>

      <?

      /*
           Implementação para Maricá e Carazinho
      */
      $iInstitSessao = db_getsession('DB_instit');
      $result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
      db_fieldsmemory($result, 0);

      $iUsuarioSessao = db_getsession('DB_id_usuario');
      $sUsuarioSessao = db_getsession('DB_login');
      $result_cartorio = db_query("select count(*) as quant_perfil_cartorio from db_usuarios a inner join db_permherda b on a.id_usuario = b.id_usuario and b.id_perfil = 4251 where a.id_usuario = $iUsuarioSessao");
      db_fieldsmemory($result_cartorio, 0);

      $bEmiteDeclarQuit = false;

      if ($db21_codcli == 19985) {
        if ( $quant_perfil_cartorio == 0 || $sUsuarioSessao == 'dbseller' ) {
          $bEmiteDeclarQuit = true;
        }
      } elseif ($db21_codcli == 18 || $db21_codcli == 74 || $db21_codcli == 15){
         $bEmiteDeclarQuit = true;
      }
      ?>
      <tr>
        <td align='right'>
          <b>Tipo :</b>
        </td>
        <td colspan='3'>
          <?
            if ($bEmiteDeclarQuit) {
                $aTipo = array( 'n'=>'Guia normal',
                                'q'=>'Declaração de quitação' );
            } else {
              $aTipo = array( 'n'=>'Guia normal' );
            }
            db_select('tipoguia',$aTipo,true,2," style='width:275px;'"); 
          ?>          
        </td>
      </tr>	

    </table>
  </fieldset>
  <input name="emitirguia" type="button" id="emitirguia" value="Emitir Guia" <?=($db_botao==false?"disabled":"")?> 
         onClick="return js_emitir()" />    
</form>  
<script>

function js_onlyNumbers(oObjeto, e) {
  
  oObjeto.value = oObjeto.value.replace(/[^0-9]/g, '');
}

var mensagem = "tributario.itbi.db_frmitbiemite.";

function js_emitir() {
  
  if(isNaN($F('it01_guia')) || $F('it01_guia') == '') {
     
    alert(_M(mensagem + "codigo_itbi_invalido"));
    $('it01_guia').value = '';
    $('it01_guia').focus();
    return false;
  }

  var iGuia  = document.form1.it01_guia.value;
  var iTipo  = document.form1.tipoguia.value;
  var sParam = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+
                (screen.height-100)+",width="+(screen.width-100);
                
  if (iGuia != "") {
    window.open('reciboitbi.php?itbi='+iGuia+'&tipoguia='+iTipo,"",sParam);
    return true;
  } else {
    alert('Escolha uma guia para emissão!')
    return false;
  }
}

function js_pesquisait01_guia(mostra){
  if(mostra==true){
    var sUrl = 'func_itbiliberadomostrarguias.php?mostrarguias=l&funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia';
    js_OpenJanelaIframe('top.corpo','db_iframeitbi',sUrl,'Pesquisa',true);
  }
}

function js_mostraitbi1(chave1,chave2){
  document.form1.it01_guia.value = chave1;
  db_iframeitbi.hide();
}

</script>
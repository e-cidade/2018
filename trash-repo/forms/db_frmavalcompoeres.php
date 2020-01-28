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

//MODULO: educação
require_once ("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clavalcompoeres->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ed41_i_codigo");
$clrotulo->label("ed43_i_codigo");

$db_botao1 = false;

if ( isset( $opcao ) && $opcao == "alterar" ) {
  
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao1 = true;
} else if( isset( $opcao ) && $opcao == "excluir" || isset( $db_opcao ) && $db_opcao == 3 ) {
  
  $db_botao1 = true;
  $db_opcao  = 3;
  $db_opcao1 = 3;
} else {
  
  if ( isset( $alterar ) ) {
    
    $db_opcao  = 2;
    $db_botao1 = true;
  } else {
    $db_opcao = 1;
  }
}

$sCamposProcRsultado = "ed43_i_sequencia as resultselec,ed43_i_formaavaliacao as formaresult";
$sWhereProcResultado = "ed43_i_codigo = {$ed44_i_procresultado}";
$sSqlProcResultado   = $clprocresultado->sql_query( "", $sCamposProcRsultado , "", $sWhereProcResultado );
$result              = $clprocresultado->sql_record( $sSqlProcResultado );
db_fieldsmemory( $result, 0 );
?>
<form name="form1" method="post" action="">
  <div class="center">
    <table border="0" width="100%">
      <tr>
        <td valign="top">
          <br>
          <table border="0">
           <tr>
             <td nowrap title="<?=@$Ted44_i_codigo?>">
               <?=@$Led44_i_codigo?>
             </td>
             <td>
               <?db_input( 'ed44_i_codigo', 15, $Ied44_i_codigo, true, 'text', 3 );?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$Ted44_i_procresultado?>">
               <?db_ancora(@$Led44_i_procresultado,"",3);?>
             </td>
             <td>
              <?php
                db_input( 'ed44_i_procresultado', 15, $Ied44_i_procresultado, true, 'text', 3 );
                db_input( 'ed42_c_descr',         30, @$Ied42_c_descr,        true, 'text', 3 );
              ?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$Ted44_i_procavaliacao?>">
               <?db_ancora( "<b>Elementos:</b>", "js_pesquisaed44_i_procavaliacao(true);", $db_opcao1 );?>
             </td>
             <td>
               <?php
                 db_input( 'ed44_i_procavaliacao', 15, $Ied44_i_procavaliacao, true, 'text', 3 );
                 db_input( 'ed09_c_descr',         30, @$Ied09_c_descr,        true, 'text', 3 );
               ?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$Ted44_c_minimoaprov?>">
               <?=@$Led44_c_minimoaprov?>
             </td>
             <td>
               <?php
                 if ( $forma == "NIVEL" ) {

                   $sSqlConceito = $clconceito->sql_query( "", "*", "ed39_i_sequencia", " ed39_i_formaavaliacao = {$formaresult}" );
                   $result3      = $clconceito->sql_record( $sSqlConceito );
               ?>
                   <select name='ed44_c_minimoaprov' <?=$db_opcao == 3 ? "disabled" : ""?>>
               <?
                   echo "<option value=''></option>";
                   for ( $z = 0; $z < $clconceito->numrows; $z++ ) {

                     db_fieldsmemory( $result3, $z );
                     $selected = trim( $ed44_c_minimoaprov) == trim( $ed39_c_conceito ) ? "selected" : "";
                     echo "<option value='{$ed39_c_conceito}' {$selected}>{$ed39_c_conceito}</option>";
                   }
                   echo "</select>";
                 } else if ( $forma == "NOTA" ) {

                   $sSqlFormaAvaliacao = $clformaavaliacao->sql_query( "", "*", "", " ed37_i_codigo = {$formaresult}" );
                   $result4            = $clformaavaliacao->sql_record( $sSqlFormaAvaliacao );
                   db_fieldsmemory( $result4, 0 );
               ?>
                   <select name='ed44_c_minimoaprov' <?=$db_opcao == 3 ? "disabled" : ""?>>
               <?
                   echo "<option value=''></option>";
                   for ( $z = $ed37_i_menorvalor; $z <= $ed37_i_maiorvalor; $z = $z + $ed37_i_variacao ) {

                     $selected = isset( $ed44_c_minimoaprov ) && trim( $ed44_c_minimoaprov ) == $z ? "selected" : "";
                     echo "<option value='{$z}' {$selected}>".number_format( $z, 2, ".", "." )."</option>";
                   }
                   echo "</select>";
                 }?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$Ted44_c_obrigatorio?>">
               <?=@$Led44_c_obrigatorio?>
             </td>
             <td>
               <?
               $x = array('N'=>'NÃO','S'=>'SIM');
               db_select( 'ed44_c_obrigatorio', $x, true, 4 );
               ?>
             </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$Ted44_i_peso?>">
               <span id="peso"  style='visibility:hidden;'>
               <?=@$Led44_i_peso?>
               </span>
             </td>
             <td>
             <?php
               $x = array( '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10' );
               db_select( 'ed44_i_peso', $x, true, $db_opcao, " style='visibility:hidden;'" );
             ?>
             </td>
           </tr>
           <tr>
             <td colspan="2">
               <input type="hidden" name="fobtencao" value="">
               <input type="hidden" name="ed14_c_descr" value="<?=@$ed14_c_descr?>">
               <input type="hidden" name="procedimento" value="<?=@$procedimento?>">
               <input type="hidden" name="forma" value="<?=@$forma?>">
               <input name="<?=( $db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
                      type="submit" 
                      id="db_opcao" 
                      value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>" 
                      <?=( $db_botao == false ? "disabled" : "" )?> >
               <input name="cancelar" type="submit" value="Cancelar" <?=( $db_botao1 == false ? "disabled" : "" )?> >
             </td>
           </tr>
          </table>
        </td>
        <td valign="top">
          <table width="100%">
            <tr>
              <td valign="top">
              <?php
                $sql = "SELECT ed44_i_codigo,
                               ed44_i_procavaliacao,
                               ed09_c_descr,
                               case
                                when ed44_i_codigo>0 then 'AVALIAÇÃO PERIÓDICA' end as ed14_c_descr,
                               ed44_i_peso,
                               ed44_c_minimoaprov,
                               ed44_c_obrigatorio,
                               ed41_i_sequencia
                          FROM avalcompoeres
                               inner join procavaliacao    on procavaliacao.ed41_i_codigo    = avalcompoeres.ed44_i_procavaliacao
                               inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
                         WHERE ed44_i_procresultado = {$ed44_i_procresultado}
                         UNION
                        SELECT ed68_i_codigo,
                               ed68_i_procresultcomp,
                               ed42_c_descr,
                               case
                                   when ed68_i_codigo > 0 
                                   then 'RESULTADO' 
                               end as ed14_c_descr,
                               ed68_i_peso,
                               ed68_c_minimoaprov,
                               'N',
                               ed43_i_sequencia
                          FROM rescompoeres
                               inner join procresultado on procresultado.ed43_i_codigo = rescompoeres.ed68_i_procresultcomp
                               inner join resultado     on resultado.ed42_i_codigo     = procresultado.ed43_i_resultado
                         WHERE ed68_i_procresultado = {$ed44_i_procresultado}
                        ORDER BY ed41_i_sequencia
                       ";
                 $chavepri= array(
                                   "ed44_i_codigo"        => @$ed44_i_codigo,
                                   "ed44_i_procavaliacao" => @$ed44_i_procavaliacao,
                                   "ed09_c_descr"         => @$ed09_c_descr,
                                   "ed14_c_descr"         => @$ed14_c_descr,
                                   "ed44_i_peso"          => @$ed14_i_peso,
                                   "ed44_c_minimoaprov"   => @$ed44_c_minimoaprov,
                                   "ed44_c_obrigatorio"   => @$ed44_c_obrigatorio
                                 );
                 $cliframe_alterar_excluir->chavepri      = $chavepri;
                 @$cliframe_alterar_excluir->sql          = $sql;
                 $cliframe_alterar_excluir->campos        = "ed09_c_descr, ed44_c_obrigatorio, ed44_i_peso, ed44_c_minimoaprov";
                 $cliframe_alterar_excluir->legenda       = "Registros";
                 $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
                 $cliframe_alterar_excluir->textocabec    = "#DEB887";
                 $cliframe_alterar_excluir->textocorpo    = "#444444";
                 $cliframe_alterar_excluir->fundocabec    = "#444444";
                 $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
                 $cliframe_alterar_excluir->iframe_height = "120";
                 $cliframe_alterar_excluir->iframe_width  = "100%";
                 $cliframe_alterar_excluir->tamfontecabec = 9;
                 $cliframe_alterar_excluir->tamfontecorpo = 9;
                 $cliframe_alterar_excluir->formulario    = false;
                 $cliframe_alterar_excluir->iframe_alterar_excluir( $db_opcao );
              ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</form>
<?

if ( @$ed14_c_descr == "RESULTADO" ) {
 ?><script>document.form1.ed44_c_obrigatorio.disabled = true;</script><?
} else {
 ?><script>document.form1.ed44_c_obrigatorio.disabled = false;</script><?
}
$query  = db_query( $sql );
$linhas = pg_num_rows( $query );

if ( $linhas > 0 ) {

  $sep     = "";
  $cod_cad = "";
  
  for ( $c = 0; $c < $linhas; $c++ ) {

    $dados    = pg_fetch_array( $query );
    $cod_cad .= $sep.$dados["ed44_i_procavaliacao"];
    $sep      = ",";
  }
} else {
  $cod_cad = 0;
}
?>
<script>
function js_pesquisaed44_i_procavaliacao( mostra ) {
  
  if ( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_procavaliacao',
                         'func_procavaliacao.php?forma=<?=$forma?>'
                                              +'&elementos=<?=$cod_cad?>'
                                              +'&resultselec=<?=$resultselec?>'
                                              +'&procedimento=<?=$procedimento?>'
                                              +'&funcao_js=parent.js_mostraprocavaliacao1|ed41_i_codigo|ed09_c_descr|ed15_c_nome',
                         'Pesquisa de Elementos do Resultado',
                         true,
                         0,
                         0,
                         770,
                         90
                       );
  } else {
    
    if ( document.form1.ed44_i_procavaliacao.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_procavaliacao',
                           'func_procavaliacao.php?forma=<?=$forma?>'
                                                +'&elementos=<?=$cod_cad?>'
                                                +'&resultselec=<?=$resultselec?>'
                                                +'&procedimento=<?=$procedimento?>'
                                                +'&pesquisa_chave='+document.form1.ed44_i_procavaliacao.value
                                                +'&funcao_js=parent.js_mostraprocavaliacao',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.ed09_c_descr.value = '';
    }
  }
}

function js_mostraprocavaliacao( chave, erro ) {
  
  document.form1.ed09_c_descr.value = chave;
  
  if ( erro == true ) {
    
    document.form1.ed44_i_procavaliacao.focus();
    document.form1.ed44_i_procavaliacao.value = '';
  }
}

function js_mostraprocavaliacao1( chave1, chave2, chave3 ) {
  
  document.form1.ed44_i_procavaliacao.value = chave1;
  document.form1.ed09_c_descr.value         = chave2;
  document.form1.ed14_c_descr.value         = chave3;
  
  if ( chave3 == "RESULTADO" ) {
    document.form1.ed44_c_obrigatorio.disabled = true;
  } else {
    document.form1.ed44_c_obrigatorio.disabled = false;
  }
  
  db_iframe_procavaliacao.hide();
}
</script>
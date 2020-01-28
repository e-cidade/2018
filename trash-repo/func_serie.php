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

//MODULO: educa��o
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory( $HTTP_POST_VARS );
parse_str    ( $HTTP_SERVER_VARS["QUERY_STRING"] );

$clserie = new cl_serie;
$clserie->rotulo->label( "ed11_i_codigo" );
$clserie->rotulo->label( "ed11_c_descr" );

/**
 * Vari�vel para controle das condi��es a serem incrementadas no SQL
 */
$where = "";

/**
 * Array com as etapas equivalentes e menores que a etapa passada por par�metro
 */
$aEtapasAnteriores = array();

/**
 * Array com as etapas equivalentes de uma etapa passada por par�metro
 */
$aEtapasEquivalentes = array();

/**
 * Vari�vel que armazena uma inst�ncia de Curso, quando este for passado por par�metro
 */
$oCurso = "";

/**
 * Vari�vel que armazena uma instancia de Etapa, quando passado o c�digo de uma etapa por par�metro
 */
$oEtapa = "";

if( isset( $iEtapa ) && !empty( $iEtapa ) ) {
  
  $oEtapa               = EtapaRepository::getEtapaByCodigo( $iEtapa );
  $aEtapasEquivalentes  = $oEtapa->buscaEtapaEquivalente();
}

?>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
    db_app::load( "estilos.css" );
    db_app::load( "DBFormularios.css" );
    db_app::load( "scripts.js" );
    db_app::load( "prototype.js" );
  ?>
  </head>
<body>
  <form name="form2" method="post" action="" class="container">
    <table class="form-container">
      <tr>
        <td>
          <fieldset>
            <legend>Filtros:</legend>
            <table class="subtable">
              <tr>
                <td nowrap title="<?=$Ted11_i_codigo?>">
                  <?=$Led11_i_codigo?>
                </td>
                <td nowrap>
                  <?php
                    db_input( "ed11_i_codigo", 10, $Ied11_i_codigo, true, "text", 4, "", "chave_ed11_i_codigo");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=$Ted11_c_descr?>">
                  <?=$Led11_c_descr?>
                </td>
                <td nowrap>
                  <?php
                    db_input( "ed11_c_descr", 30, $Ied11_c_descr, true, "text", 4, "", "chave_ed11_c_descr");
                  ?> 
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="curso" type="hidden" value="<?=isset( $curso ) ? $curso : "";?>">
                  <?php
                    if ( isset( $inicial ) ) {
                  ?>
                      <input name="inicial" type="hidden" value="<?=$inicial?>">
                  <?}?>
                </td>
              </tr>
            </table>
          </fieldset>
          <div style="text-align: center;">
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
            <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_serie.hide();">
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <?php
            /**
             * Vari�vel para controle do m�todo da classe a ser executado
             */
            $sQuery = "sql_query";
            
            if( isset( $inicial ) ) {
  
              $sSqlSerie = $clserie->sql_query( "", "ed11_i_sequencia as seq", "", " ed11_i_codigo = {$inicial}");
              $result1   = $clserie->sql_record( $sSqlSerie );
              db_fieldsmemory($result1,0);
              $where .= " and ed11_i_sequencia >= {$seq}";
            }
            
            /**
             * Altera o m�todo da classe a ser executado, adiciona a condi��o where e instancia Curso
             */
            if( !empty( $curso ) ) {
              
              $sQuery  = "sql_query_curso";
              $where  .= " AND ed29_i_codigo = {$curso}";
              $oCurso  = CursoRepository::getByCodigo( $curso );
            }
            
            /**
             * Caso tenha sido informado Curso e Etapa, e o Ensino destes seja o mesmo, busca as etapas anteriores a
             * etapa informada do mesmo ensino
             * 
             * Caso contr�rio, percorre o array das etapas equivalentes para buscar as etapas anteriores, caso o ensino
             * e codigo sejam os mesmos
             */
            if( !empty( $oCurso ) && !empty( $oEtapa ) && $oCurso->getEnsino()->getCodigo() == $oEtapa->getEnsino()->getCodigo() ) {
              $aEtapasAnteriores = EtapaRepository::getEtapasAnteriores( new Etapa( $iEtapa ) );
            } else {
              
              foreach( $aEtapasEquivalentes as $oEtapaEquivalente ) {
                
                if( $oCurso->getEnsino()->getCodigo() == $oEtapaEquivalente->getEnsino()->getCodigo() ) {
                  $aEtapasAnteriores = EtapaRepository::getEtapasAnteriores( $oEtapaEquivalente );
                }
              }
            }
            
            /**
             * Caso existam etapas inferiores, adiciona uma nova condi��o ao where, buscando as etapas
             * que estejam dentre estas
             */
            if( count( $aEtapasAnteriores ) > 0 ) {
            
              $aCodigoEtapa = array();
              
              /**
               * Percorre as etapas equivalentes e menores
               */
              foreach( $aEtapasAnteriores as $oEtapa ) {
                $aCodigoEtapa[] = $oEtapa->getCodigo();
              }
            
              /**
               * Caso o array das etapas seja maior que zero, adiciona a condi��o where a valida��o pelos c�digos
               * existentes
               */
              if( count( $aCodigoEtapa ) > 0 ) {

                $sCodigoEtapa  = implode( ", ", $aCodigoEtapa );
                $where        .= " AND ed11_i_codigo in( {$sCodigoEtapa} ) ";
              }
            }
            
            if( !isset( $pesquisa_chave ) ) {
  
              if( isset( $campos ) == false ) {
  
                if( file_exists( "funcoes/db_func_serie.php") == true ) {
                  include("funcoes/db_func_serie.php");
                } else {
                  $campos = "serie.*";
                }
              }
              
              if( isset( $chave_ed11_i_codigo ) && ( trim( $chave_ed11_i_codigo ) != "" ) ) {
  
                $sWhere = " ed11_i_codigo = {$chave_ed11_i_codigo} {$where}";
                $sql    = $clserie->{$sQuery}( "", $campos, "ed11_i_sequencia", $sWhere );
              } else if( isset( $chave_ed11_c_descr ) && ( trim( $chave_ed11_c_descr ) != "" ) ) {
                
                $sWhere = " ed11_c_descr like '{$chave_ed11_c_descr}%' {$where}";
                $sql    = $clserie->{$sQuery}( "", $campos, "ed11_i_sequencia", $sWhere );
              } else {
                $sql = $clserie->{$sQuery}( "", $campos, "ed11_i_sequencia", " 1 = 1 {$where}" );
              }
              
              db_lovrot( $sql, 15, "()", "", $funcao_js );
            } else {
  
              if( $pesquisa_chave != null && $pesquisa_chave != "") {
  
                $sWhereSerie = " ed11_i_codigo = {$pesquisa_chave} {$where}";
                $sSqlSerie   = $clserie->{$sQuery}( "", "*", "ed11_i_sequencia", $sWhereSerie );
                $result      = $clserie->sql_record( $sSqlSerie );
                
                if( $clserie->numrows != 0 ) {
                
                  db_fieldsmemory( $result, 0 );
                  echo "<script>".$funcao_js."('{$ed11_c_descr}',false);</script>";
                } else {
                  echo "<script>".$funcao_js."('Chave({$pesquisa_chave}) n�o Encontrado',true);</script>";
                }
              } else {
                echo "<script>".$funcao_js."('',false);</script>";
              }
            }
          ?>
        </td>
      </tr>
    </table>
  </form>
</body>
</html>
<script>
$('chave_ed11_i_codigo').className = 'field-size2';
$('chave_ed11_c_descr').className  = 'field-size9';
</script>
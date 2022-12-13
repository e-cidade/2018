<?php
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
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$cllinhatransporte = new cl_linhatransporte;
$cllinhatransporte->rotulo->label("tre06_sequencial");
$cllinhatransporte->rotulo->label("tre06_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <form name="form2" method="post" action="" >
          <table width="35%" border="0" align="center" cellspacing="0">
            <?php
            if (isset($iEscola) && $iEscola != '') {
              db_input('iEscola', '10', '', true, 'hidden', 3, '', 'iEscola');
            }
            ?>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Ttre06_sequencial?>">
                <?=$Ltre06_sequencial?>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                db_input("tre06_sequencial",10,$Itre06_sequencial,true,"text",4,"","chave_tre06_sequencial");
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Ttre06_nome?>">
                <?=$Ltre06_nome?>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                db_input("tre06_nome",60,$Itre06_nome,true,"text",4,"","chave_tre06_nome");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="button" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_linhatransporte.hide();">
               </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php
        $aWhere = array();

        if( !isset( $pesquisa_chave ) ) {

          if( isset( $campos ) == false ) {

            if( file_exists( "funcoes/db_func_linhatransporte.php") == true ) {
              include("funcoes/db_func_linhatransporte.php");
            } else {
              $campos = "linhatransporte.*";
            }
          }

          if (isset($iEscola) && $iEscola != '') {

            $sSqlWhere  = " select lt.tre06_sequencial from linhatransporte  lt                                                  \n";
            $sSqlWhere .= "        left join linhatransporteitinerario  on tre09_linhatransporte           = lt.tre06_sequencial \n";
            $sSqlWhere .= "        left join itinerariologradouro       on tre10_linhatransporteitinerario = tre09_sequencial    \n";
            $sSqlWhere .= "        left join linhatransportepontoparada on tre11_itinerariologradouro      = tre10_sequencial    \n";
            $sSqlWhere .= "        left join pontoparada                on tre04_sequencial                = tre11_pontoparada   \n";
            $sSqlWhere .= "        left join pontoparadadepartamento    on tre11_pontoparada               = tre05_pontoparada   \n";
            $sSqlWhere .= "        left join pontoparadaescolaproc      on tre13_pontoparada               = tre04_sequencial    \n";

            if ( isset($lEscolaProcedencia) ) {
              $sSqlWhere .= " where tre13_escolaproc = {$iEscola} ";
            } else {
              $sSqlWhere .= " where tre05_db_depart = {$iEscola} ";
            }

            $sSqlWhere .= " and lt.tre06_sequencial = linhatransporte.tre06_sequencial ";
            $aWhere[]   = " exists({$sSqlWhere}) ";
          }

          if( isset( $chave_tre06_sequencial ) && ( trim( $chave_tre06_sequencial ) != "" ) ) {
            $aWhere[] = "tre06_sequencial = {$chave_tre06_sequencial}";
          }

          if( isset( $chave_tre06_nome ) && ( trim( $chave_tre06_nome ) != "" ) ) {
            $aWhere[] = "tre06_nome like '{$chave_tre06_nome}%'";
          }

          $repassa = array();
          if(isset($chave_tre06_nome)){
            $repassa = array( "chave_tre06_sequencial" => $chave_tre06_sequencial, "chave_tre06_nome" => $chave_tre06_nome );
          }

          $sWhere = implode( ' AND ', $aWhere );
          $sql    = $cllinhatransporte->sql_query( null, $campos, "tre06_sequencial", $sWhere );

          db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
        } else {

          if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

            $result = $cllinhatransporte->sql_record( $cllinhatransporte->sql_query( $pesquisa_chave ) );

            if( $cllinhatransporte->numrows != 0 ) {

              db_fieldsmemory( $result, 0 );
              echo "<script>".$funcao_js."('$tre06_nome',false);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          } else {
            echo "<script>".$funcao_js."('',false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_tre06_nome",true,1,"chave_tre06_nome",true);

$('limpar').onclick = function() {
  limpaCampos();
};

function limpaCampos() {

  $('chave_tre06_sequencial').value = '';
  $('chave_tre06_nome').value       = '';
  document.form2.submit();
}
</script>
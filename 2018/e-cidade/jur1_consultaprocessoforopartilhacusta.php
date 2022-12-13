<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
  
  require_once("classes/db_inicial_classe.php");
  require_once("classes/db_processoforopartilha_classe.php");
  require_once("classes/db_processoforopartilhacusta_classe.php");
  
  $clinicial                   = new cl_inicial();
  $clprocessoforopartilha      = new cl_processoforopartilha();
  $clprocessoforopartilhacusta = new cl_processoforopartilhacusta();
  
  $oRotulo = new rotulocampo;
  
  $oRotulo->label("ar37_sequencial");
  $oRotulo->label("ar37_descricao");
  
  $clprocessoforopartilha->rotulo->label();
  $clprocessoforopartilhacusta->rotulo->label();
  
  $nTotal   = 0;
  
  $sSql  = "SELECT ar37_sequencial, ar37_descricao, v76_tipolancamento,                                                                                 ";
  $sSql .= "       v76_dtpagamento, v76_obs       , v77_taxa, v77_valor,                                                                                ";
  $sSql .= "       ar36_sequencial, ar36_descricao, ar36_receita                                                                                        ";
  $sSql .= "  FROM (                                                                                                                                    ";
  $sSql .= "        SELECT DISTINCT                                                                                                                     ";
  $sSql .= "               ( SELECT v76_sequencial                                                                                                      ";
  $sSql .= "                   FROM processoforopartilha                                                                                                ";
  $sSql .= "                  WHERE v76_processoforo = {$v70_sequencial}                                                                                ";
  $sSql .= "                  ORDER BY v76_sequencial desc limit 1                                                                                      ";
  $sSql .= "               ) AS UltimoLancamento,                                                                                                       ";
  $sSql .= "               v76_sequencial,                                                                                                              ";
  $sSql .= "               ar37_sequencial, ar37_descricao, v76_tipolancamento,                                                                         ";
  $sSql .= "               v76_dtpagamento, v76_obs, v77_taxa, v77_valor,                                                                               ";
  $sSql .= "               ar36_sequencial, ar36_descricao, ar36_receita                                                                                ";
  $sSql .= "          FROM processoforopartilhacusta                                                                                                    ";
  $sSql .= "               INNER JOIN taxa                 ON taxa.ar36_sequencial                = processoforopartilhacusta.v77_taxa                  ";
  $sSql .= "               INNER JOIN tabrec               ON tabrec.k02_codigo                   = taxa.ar36_receita                                   ";
  $sSql .= "               INNER JOIN grupotaxa            ON grupotaxa.ar37_sequencial           = taxa.ar36_grupotaxa                                 ";
  $sSql .= "               INNER JOIN processoforopartilha ON processoforopartilha.v76_sequencial = processoforopartilhacusta.v77_processoforopartilha  ";
  $sSql .= "               INNER JOIN processoforo         ON processoforo.v70_sequencial         = processoforopartilha.v76_processoforo               ";
  $sSql .= "               LEFT  JOIN recibopagaboleto     ON recibopagaboleto.k138_numnov        = processoforopartilhacusta.v77_numnov                ";
  $sSql .= "               LEFT  JOIN recibopaga           ON recibopaga.k00_numnov               = recibopagaboleto.k138_numnov                        ";
  $sSql .= "               LEFT  JOIN cancrecibopaga       ON cancrecibopaga.k134_numnov          = recibopagaboleto.k138_numnov                        ";
  $sSql .= "         WHERE v70_sequencial  = {$v70_sequencial}                                                                                          ";
  $sSql .= "           AND k134_sequencial is null                                                                                                      ";
  $sSql .= "       ) as x                                                                                                                               ";
  $sSql .= " WHERE v76_sequencial = UltimoLancamento;                                                                                                   ";
  
  $rsPartilhaCusta   = $clprocessoforopartilhacusta->sql_record($sSql);

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
      db_app::load("widgets/windowAux.widget.js,messageboard.widget.js");
      db_app::load("estilos.css, grid.style.css,tab.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    
    <?php 
      if ( $clprocessoforopartilhacusta->numrows == 0 ) {
        
        /*
         * Realizado desta forma para poder manter o padrão das mensagens quando não é encontrado nenhum registro
         */
        echo "<center>";
        
        $sql = "select 1 where 1=2";
        db_lovrot($sql, 5);
        
        echo "</center>";
        exit;
        
      } else {
        
        $oDados = db_utils::getColectionByRecord($rsPartilhaCusta);
      }
    ?>
    <fieldset>
      <legend><b>Dados da Partilha</b></legend>
      <table>
        <tr>
          <td width="250px"> <?php echo $Lar37_sequencial; ?> </td>
          <td style="background-color: #FFFFFF;" align="left" width="350px"> <?php echo $oDados[0]->ar37_descricao; ?></td>
        </tr>
        <tr>
          <td width="250px"> <?php echo $Lv76_tipolancamento; ?> </td>
          <td style="background-color: #FFFFFF;" align="left" width="350px"> 
            <?php
              switch ($oDados[0]->v76_tipolancamento) {
                case 1: 
                  echo "Automático"; 
                break;
                
                case 2:
                  echo "Manual";
                break;
                
                case 3: 
                  echo "Isento";
                break;
              }
            ?>  
          </td>
        </tr>
        <tr>
          <td width="250px"> <?php echo $Lv76_dtpagamento; ?> </td>
          <td style="background-color: #FFFFFF;" align="left" width="350px">
            <?php echo db_formatar($oDados[0]->v76_dtpagamento, "d"); ?>
          </td>
        </tr>
        <tr>
          <td width="250px"> <?php echo $Lv76_obs; ?> </td>
          <td style="background-color: #FFFFFF;" align="left" width="350px"> 
            <textarea readonly style="width: 100%" ><?php echo $oDados[0]->v76_obs; ?></textarea> 
          </td>
        </tr>
      </table>
    </fieldset> 
    <?php 
      if ($oDados[0]->v76_tipolancamento != 3) {
    ?>
    <fieldset>
      <legend><b>Custas</b></legend>
       <table>
         <?php
           foreach ($oDados as $aRegistros) {
             echo "<tr>";
             echo " <td width=\"250px\"> <b>{$aRegistros->ar36_descricao} </b> </td>";
             echo " <td style=\"background-color: #FFFFFF;\" align=\"left\" width=\"350px\"> ".str_replace(".",",",$aRegistros->v77_valor)."</td>";
             echo "</tr>";
             $nTotal += $aRegistros->v77_valor;
           }
         ?>
         <!-- 
         <tr>
          <td width="250px"><b>Valor do Débito (Original):</b></td>
          <td style="background-color: #FFFFFF;" align="left" width="350px"><?php echo str_replace(".", ",", $nTotalArreforo); ?></td>
         </tr>
         -->
         <tr>
          <td width="250px"><b>Valor Total :</b></td>
          <td style="background-color: #FFFFFF;" align="left" width="350px"><?php echo str_replace(".", ",", $nTotal); ?></td>
         </tr>
       </table>
    </fieldset>
    <?php } ?>
  </body>
</html>
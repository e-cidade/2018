<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  
  $oDaoRegraArredondamentoFaixa     = new cl_regraarredondamentofaixa();
  $oDaoNovaRegraArredondamentoFaixa = new cl_regraarredondamentofaixa();
  $oDaoRegraArredondamento          = new cl_regraarredondamento();
  
  $db_opcao = 22;
  $db_botao = false;
  $lErro    = false;
  $sUrl     = "edu1_regraarredondamentofaixa001.php?ed317_regraarredondamento={$ed317_regraarredondamento}";
  
  if (isset($alterar) || isset($excluir) || isset($incluir)) {
    
    $sqlerro = false;
    $oDaoRegraArredondamentoFaixa->ed317_sequencial          = $ed317_sequencial;
    $oDaoRegraArredondamentoFaixa->ed317_regraarredondamento = $ed317_regraarredondamento;
    $oDaoRegraArredondamentoFaixa->ed317_inicial             = $ed317_inicial;
    $oDaoRegraArredondamentoFaixa->ed317_final               = $ed317_final;
    $oDaoRegraArredondamentoFaixa->ed317_arredondar          = $ed317_arredondar;
  }
  
  if (isset($incluir)) {
    
    if ($sqlerro == false) {
      
        db_inicio_transacao();
        $sWhere                         = " ed317_regraarredondamento = {$ed317_regraarredondamento}";
        $sSqlRegraArredondamentoFaixa   = $oDaoNovaRegraArredondamentoFaixa->sql_query(null, "ed317_inicial, ed317_final, ed317_arredondar", null, $sWhere);
        $rsRegraArredondamentoFaixa     = $oDaoNovaRegraArredondamentoFaixa->sql_record($sSqlRegraArredondamentoFaixa);
        $iLinhaRegraArredondamentoFaixa = $oDaoNovaRegraArredondamentoFaixa->numrows;
        
        try {
          
          for ($iContador = 0; $iContador < $iLinhaRegraArredondamentoFaixa; $iContador++) {
            
            $oDadosRegraArredondamentoFaixa = db_utils::fieldsMemory($rsRegraArredondamentoFaixa, $iContador);
              
              if(empty($ed317_inicial)) {
              
                $erro_msg = "Deve ser informada a faixa inicial da regra";
                throw new Exception ($erro_msg);
              }
              if(empty($ed317_final)) {
              
                $erro_msg = "Deve ser informada a faixa final da regra";
                throw new Exception ($erro_msg);
              }
              
              if ($ed317_inicial > $ed317_final) {
              
                $erro_msg = "Faixa inicial n�o pode ser maior que a final";
                throw new Exception ($erro_msg);
              }
              
              if ($ed317_inicial >= $oDadosRegraArredondamentoFaixa->ed317_inicial && 
                  $ed317_inicial <= $oDadosRegraArredondamentoFaixa->ed317_final) {
                
                $erro_msg  = "Faixa inicial ({$ed317_inicial}) n�o permitida. ";
                $erro_msg .= "Faixa est� entre os valores de uma regra existente";
                throw new Exception ($erro_msg);
              }
              if ($ed317_final >= $oDadosRegraArredondamentoFaixa->ed317_inicial &&
                  $ed317_final <= $oDadosRegraArredondamentoFaixa->ed317_final) {
              
                $erro_msg  = "Faixa final ({$ed317_final}) n�o permitida. ";
                $erro_msg .= "Faixa est� entre os valores de uma regra existente";
                throw new Exception ($erro_msg);
              }
              if ($ed317_arredondar == $oDadosRegraArredondamentoFaixa->ed317_arredondar) {
                
                $erro_msg  = "Tipo de regra de arredondamento j� cadastrado.";
                throw new Exception ($erro_msg);
              }
            
          }
        } catch (Exception $eErro) {
          
          db_msgbox($eErro->getMessage());
          
          $lErro  = true;
          $sUrl  .= "&ed317_inicial={$ed317_inicial}&ed317_final={$ed317_final}&ed317_arredondar={$ed317_arredondar}";
          $sUrl  .= "&iCasasDecimais={$iCasasDecimais}&sDisabled={$sDisabled}";
          
          if ( isset( $chavepesquisa ) ) {
            $sUrl .= "&chavepesquisa={$chavepesquisa}";
          }
          
          db_redireciona( $sUrl );
        }
        
        if ($lErro == false) {
          
          $oDaoRegraArredondamentoFaixa->incluir($ed317_sequencial);
          $erro_msg = $oDaoRegraArredondamentoFaixa->erro_msg;
          if ($oDaoRegraArredondamentoFaixa->erro_status == 0) {
            
            $sqlerro = true;
            db_msgbox($erro_msg);
          }
          db_fim_transacao($sqlerro);
        }
    }
  }
  
  if (isset($alterar)) {
    
    if ($sqlerro == false) {
      
      db_inicio_transacao();
      $sWhere                         = "     ed317_regraarredondamento = {$ed317_regraarredondamento}";
      $sWhere                        .= " AND ed317_sequencial         != {$ed317_sequencial}";
      $sSqlRegraArredondamentoFaixa   = $oDaoNovaRegraArredondamentoFaixa->sql_query(null, "ed317_inicial, ed317_final, ed317_arredondar", null, $sWhere);
      $rsRegraArredondamentoFaixa     = $oDaoNovaRegraArredondamentoFaixa->sql_record($sSqlRegraArredondamentoFaixa);
      $iLinhaRegraArredondamentoFaixa = $oDaoNovaRegraArredondamentoFaixa->numrows;
      
      try {
        
        for ($iContador = 0; $iContador < $iLinhaRegraArredondamentoFaixa; $iContador++) {
      
          $oDadosRegraArredondamentoFaixa = db_utils::fieldsMemory($rsRegraArredondamentoFaixa, $iContador);
      
          if(empty($ed317_inicial)) {
      
            $erro_msg = "Deve ser informada a faixa inicial da regra";
            throw new Exception ($erro_msg);
          }
          if(empty($ed317_final)) {
      
            $erro_msg = "Deve ser informada a faixa final da regra";
            throw new Exception ($erro_msg);
          }
      
          if ($ed317_inicial > $ed317_final) {
      
            $erro_msg = "Faixa inicial n�o pode ser maior que a final";
            throw new Exception ($erro_msg);
          }
      
          if ($ed317_inicial >= $oDadosRegraArredondamentoFaixa->ed317_inicial &&
              $ed317_inicial <= $oDadosRegraArredondamentoFaixa->ed317_final) {
      
            $erro_msg  = "Faixa inicial ({$ed317_inicial}) n�o permitida. ";
            $erro_msg .= "Faixa est� entre os valores de uma regra existente";
            throw new Exception ($erro_msg);
          }
          if ($ed317_final >= $oDadosRegraArredondamentoFaixa->ed317_inicial &&
              $ed317_final <= $oDadosRegraArredondamentoFaixa->ed317_final) {
      
            $erro_msg  = "Faixa final ({$ed317_final}) n�o permitida. ";
            $erro_msg .= "Faixa est� entre os valores de uma regra existente";
            throw new Exception ($erro_msg);
          }
          if ($ed317_arredondar == $oDadosRegraArredondamentoFaixa->ed317_arredondar) {
      
            $erro_msg  = "Tipo de regra de arredondamento j� cadastrado.";
            throw new Exception ($erro_msg);
          }
      
        }
      } catch (Exception $eErro) {
        
        db_msgbox($eErro->getMessage());
        
        $lErro = true;
        $sUrl .= "&ed317_sequencial={$ed317_sequencial}&ed317_inicial={$ed317_inicial}&ed317_final={$ed317_final}";
        $sUrl .= "&ed317_arredondar={$ed317_arredondar}&chavepesquisa={$chavepesquisa}&opcao=alterar";
        $sUrl .= "&sqlerro=false&iCasasDecimais={$iCasasDecimais}&sDisabled={$sDisabled}";
        
        db_redireciona( $sUrl );
      }
      
      if ($lErro == false) {
      
        $oDaoRegraArredondamentoFaixa->alterar($ed317_sequencial);
        $erro_msg = $oDaoRegraArredondamentoFaixa->erro_msg;
        if ($oDaoRegraArredondamentoFaixa->erro_status == 0) {
          
          $sqlerro = true;
          db_msgbox($erro_msg);
        }
        
        db_fim_transacao($sqlerro);
        
        $sUrl .= "&iCasasDecimais={$iCasasDecimais}&sDisabled={$sDisabled}";
        
        db_redireciona( $sUrl );
      }
    }
  }
  if (isset($excluir)) {
    
    if ($sqlerro == false) {
      
      db_inicio_transacao();
      $oDaoRegraArredondamentoFaixa->excluir($ed317_sequencial);
      $erro_msg = $oDaoRegraArredondamentoFaixa->erro_msg;
      if ($oDaoRegraArredondamentoFaixa->erro_status == 0) {
        $sqlerro = true;
      }
      db_fim_transacao($sqlerro);
    }
  }
  if (isset($opcao)) {
     
    $result = $oDaoRegraArredondamentoFaixa->sql_record($oDaoRegraArredondamentoFaixa->sql_query($ed317_sequencial));
    if ($result != false && $oDaoRegraArredondamentoFaixa->numrows > 0) {
      db_fieldsmemory($result,0);
    }
  }
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <?
    	  require_once("forms/db_frmregraarredondamentofaixa.php");
      ?>
    </div>
  </body>
</html>
<?
  if (isset($alterar) || isset($excluir) || isset($incluir)) {
    
    if ($oDaoRegraArredondamentoFaixa->erro_campo != "") {
      echo "<script> document.form1.".$oDaoRegraArredondamentoFaixa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoRegraArredondamentoFaixa->erro_campo.".focus();</script>";
    }
  }
?>
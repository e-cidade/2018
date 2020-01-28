<?php
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
  require_once("classes/db_db_layoutlinha_classe.php");
  require_once("classes/db_db_layoutcampos_classe.php");
  require_once("classes/db_db_layouttxt_classe.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_utils.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  
  $cldb_layoutlinha = new cl_db_layoutlinha;
  $cldb_layoutcampos = new cl_db_layoutcampos;
  $cldb_layouttxt = new cl_db_layouttxt;
  $db_opcao = 22;
  $db_botao = false;

  if (isset($alterar) || isset($excluir) || isset($incluir) || isset($importarcampos)) {
  
    $sqlerro = false;
  }

  if (isset($incluir) || isset($importarcampos)) {
  
    if ($sqlerro == false) {
      
      db_inicio_transacao();
    
      $cldb_layoutlinha->incluir(null);
      
      $erro_msg = $cldb_layoutlinha->erro_msg;
      
      if ($cldb_layoutlinha->erro_status == 0) {
        
        $sqlerro = true;
      } else {
        
        $db51_codigo = $cldb_layoutlinha->db51_codigo;
        $opcao       = "alterar";
      }
      
      if (isset($importarcampos)) {
      
        $sSqlLayoutCampos = $cldb_layoutcampos->sql_query_file(null,
                                                                "db52_nome   , db52_descr   , db52_layoutformat,
                                                                 db52_posicao, db52_default , db52_tamanho,
                                                                 db52_ident  , db52_imprimir, db52_alinha,
                                                                 db52_obs    , db52_quebraapos, 
        		                                                 db52_codigo as codigo_original",
                                                                "db52_posicao",
                                                                " db52_layoutlinha=".$codigoimporta);
        $result_campos_importa  = $cldb_layoutcampos->sql_record($sSqlLayoutCampos);
        $numrows_campos_importa = $cldb_layoutcampos->numrows;
        
        for ($ix = 0; $ix < $numrows_campos_importa; $ix++) {
          
          db_fieldsmemory($result_campos_importa, $ix);
          
          $cldb_layoutcampos->db52_layoutlinha  = $db51_codigo;
          $cldb_layoutcampos->db52_nome         = $db52_nome;
          $cldb_layoutcampos->db52_descr        = $db52_descr;
          $cldb_layoutcampos->db52_layoutformat = $db52_layoutformat;
          $cldb_layoutcampos->db52_posicao      = $db52_posicao;
          $cldb_layoutcampos->db52_default      = $db52_default;
          $cldb_layoutcampos->db52_tamanho      = $db52_tamanho;
          $cldb_layoutcampos->db52_ident        = ($db52_ident == 't' ? "true" : "false");
          $cldb_layoutcampos->db52_imprimir     = ($db52_imprimir == 't' ? "true" : "false");
          $cldb_layoutcampos->db52_alinha       = "$db52_alinha";
          $cldb_layoutcampos->db52_obs          = $db52_obs;
          $cldb_layoutcampos->db52_quebraapos   = $db52_quebraapos;
          $cldb_layoutcampos->incluir(null);
          
          if ($cldb_layoutcampos->erro_status == 0) {
            
            unset($opcao);
            
            $sqlerro  = true;
            $erro_msg = $cldb_layoutcampos->erro_msg;
            break;
          } else {
            
            $oDaoVinculoAvaliacaoLayoutCampo = db_utils::getDao("avaliacaoperguntaopcaolayoutcampo");
            
            $sWhereBuscaVinculos = " ed313_db_layoutcampo = {$codigo_original}";
            $sSqlBuscaVinculos   = $oDaoVinculoAvaliacaoLayoutCampo->sql_query_file(null,
                                                                                          '*',
                                                                                          "ed313_sequencial desc",
                                                                                           $sWhereBuscaVinculos
                                                                                         );
            $rsLayoutCampoImporta  = $oDaoVinculoAvaliacaoLayoutCampo->sql_record($sSqlBuscaVinculos);
            
            $iTotalVinculos = $oDaoVinculoAvaliacaoLayoutCampo->numrows; 
            if ($iTotalVinculos > 0) {
            
              for ($iVinculo = 0; $iVinculo < $iTotalVinculos; $iVinculo++) {
              
                $oDadosVinculo = db_utils::fieldsMemory($rsLayoutCampoImporta, $iVinculo);

                $oDaoVinculoAvaliacaoLayoutCampo->ed313_ano                    = $oDadosVinculo->ed313_ano;
                $oDaoVinculoAvaliacaoLayoutCampo->ed313_db_layoutcampo         = $cldb_layoutcampos->db52_codigo;
                $oDaoVinculoAvaliacaoLayoutCampo->ed313_avaliacaoperguntaopcao = $oDadosVinculo->ed313_avaliacaoperguntaopcao;
                $oDaoVinculoAvaliacaoLayoutCampo->ed313_layoutvalorcampo       = $oDadosVinculo->ed313_layoutvalorcampo;
                $oDaoVinculoAvaliacaoLayoutCampo->incluir(null);
                
                if ($oDaoVinculoAvaliacaoLayoutCampo->erro_status == 0) {
                
                  unset($opcao);
                  $sqlerro  = true;
                  $erro_msg = $oDaoVinculoAvaliacaoLayoutCampo->erro_msg;
                  break;
                }
              }
            }
          }
        }
      }
      db_fim_transacao($sqlerro);
    }
  
  } else if (isset($alterar)) {
  
    if ($sqlerro == false) {
      
      db_inicio_transacao();
      
      $cldb_layoutlinha->alterar($db51_codigo);
      
      $erro_msg = $cldb_layoutlinha->erro_msg;
      
      if ($cldb_layoutlinha->erro_status == 0) {
        
        $sqlerro = true;
      } else {
        
        $opcao = "alterar";
      }
      
      db_fim_transacao($sqlerro);
    }
  } else if (isset($excluir)) {
    
    if ($sqlerro == false) {
      
      db_inicio_transacao();
      
      $cldb_layoutcampos->excluir(null,"db52_layoutlinha = ".$db51_codigo);
      $erro_msg = $cldb_layoutcampos->erro_msg;
      
      if ($cldb_layoutcampos->erro_status == 0) {
        
        $sqlerro = true;
      }
      
      if ($sqlerro == false) {
        
        $cldb_layoutlinha->excluir($db51_codigo);
        
        $erro_msg = $cldb_layoutlinha->erro_msg;
        
        if ($cldb_layoutlinha->erro_status == 0) {
          
          $sqlerro = true;
        }
      }
      
      db_fim_transacao($sqlerro);
    }
  }
  if (isset($opcao)) {
    
    $result = $cldb_layoutlinha->sql_record($cldb_layoutlinha->sql_query($db51_codigo));
    
    if ($result != false && $cldb_layoutlinha->numrows > 0) {
      
      db_fieldsmemory($result, 0);
    }
  }
  
  $importalinha = false;
  
  if (isset($db51_layouttxt) && trim($db51_layouttxt) != "") {
    
    $result_db_layout = $cldb_layouttxt->sql_record($cldb_layouttxt->sql_query_file($db51_layouttxt));
    
    if ($cldb_layouttxt->numrows > 0) {
      
      db_fieldsmemory($result_db_layout, 0);
    }
    
    if (isset($chave_pesquisa) && !isset($opcao) && !isset($excluir)) {
      
      $sSqlLayoutLinha = $cldb_layoutlinha->sql_query($chave_pesquisa,
                                                      "db51_descr, db51_tipolinha, db51_tamlinha,
                                                       db51_obs, db51_linhasantes, db51_linhasdepois,
                                                       db51_codigo as codigoimporta");
      $result_linha = $cldb_layoutlinha->sql_record($sSqlLayoutLinha);
      
      if ($cldb_layoutlinha->numrows > 0) {
        
        db_fieldsmemory($result_linha, 0);
        
        $importalinha = true;
      }
    }
  }
  
  if (isset($db_opcaoal)) {
    
    $db_opcao = 33;
    $db_botao = false;
  } else if (isset($opcao) && $opcao == "alterar") {
    
    $db_botao = true;
    $db_opcao = 2;
  } else if (isset($opcao) && $opcao == "excluir") {
    
    $db_opcao = 3;
    $db_botao = true;
  } else {
      
    $db_opcao = 1;
    $db_botao = true;
    
    if (isset($excluir) && $sqlerro == false) {
      
      $db51_codigo = "";
      $db51_descr = "";
      $db51_tipolinha = "";
      $db51_tamlinha = "";
      $db51_obs = "";
    }
  }
  
  if ($importalinha == true) {
    
    $db_opcao = 1;
  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?php
      
      include("forms/db_frmdb_layoutlinha.php");
    ?>
    </center>
   </td>
  </tr>
</table>
</body>
</html>
<script>
  <?php if ($db_opcao == 1 || $db_opcao == 2) { ?>
  
          js_tabulacaoforms("form1","db51_descr",true,1,"db51_descr",true);
  <?php } else { ?>
  
          js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
  <?php } ?>
</script>
<?php

  if (isset($alterar) || isset($excluir) || isset($incluir) || isset($importarcampos)) {
    
    db_msgbox($erro_msg);
    
    if ($sqlerro == true) {
      
      if($cldb_layoutlinha->erro_campo!=""){
        
        echo "<script> document.form1." . $cldb_layoutlinha->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $cldb_layoutlinha->erro_campo . ".focus();</script>";
      }
    } else {
      
      if (isset($excluir)) {
        
        echo "<script>js_cancelar();</script>";
      }
    }
  }
  
  if ( ( (isset($opcao) && $opcao != "excluir") ||
          ( (isset($alterar) || isset($incluir) || isset($importarcampos) ) && $sqlerro == false) ) &&
         isset($db51_codigo) && trim($db51_codigo) != "") {
    echo "
          <script>
            parent.document.formaba.db_layoutcampos.disabled=false;
            top.corpo.iframe_db_layoutcampos.location.href='con1_db_layoutcampos001.php?db52_layoutlinha=" . @$db51_codigo . "';
         ";
    if (isset($incluir) || isset($alterar) || isset($importarcampos)) {
      
      echo " parent.mo_camada('db_layoutcampos'); ";
    }
    echo " </script> ";
    
  } else {
    
    echo " <script>
              parent.document.formaba.db_layoutcampos.disabled=true;
              top.corpo.iframe_db_layoutcampos.location.href='con1_db_layoutcampos001.php';
            </script> ";
  }
?>
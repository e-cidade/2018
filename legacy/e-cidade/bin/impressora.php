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

$IP = "192.168.0.201";
$PORTA = 5001;

if(isset($_POST["cabecalho"])) {
  im_conectar($IP,$PORTA);  
  im_reset();  
  im_negrito(true);
  im_impln("     PREFEITURA MUNICIPAL DE TESTE");  
  im_negrito(false);
  im_italico(true);         
  im_impln("Rua  Adbull Dabi, 590/12  Bairro  Centro");
  im_impln("Fone:  (051)3212-2637  Porto Alegre - RS");
  im_impln("CNPJ: 93.015.006/0019-42  IE:096/0635920");
  im_italico(false);
  im_expandido(true);
  im_impln("    CUPOM FISCAL");
  im_expandido(false);
  im_condensado(true);
  im_impln("------------------------------------------------------------");
  im_condensado(false);
 /* 
   im_negrito(true);
  im_impln("     PREFEITURA MUNICIPAL DE TESTE");  
  im_negrito(false);
  im_italico(true);         
  im_impln("Rua  Adbull Dabi, 590/12  Bairro  Centro");
  im_impln("Fone:  (051)3212-2637  Porto Alegre - RS");
  im_impln("CNPJ: 93.015.006/0019-42  IE:096/0635920");
  im_italico(false);
  im_expandido(true);
  im_impln("    CUPOM FISCAL");
  im_expandido(false);
  im_condensado(true);
  im_impln("------------------------------------------------------------");
  im_condensado(false);
   im_negrito(true);
  im_impln("     PREFEITURA MUNICIPAL DE TESTE");  
  im_negrito(false);
  im_italico(true);         
  im_impln("Rua  Adbull Dabi, 590/12  Bairro  Centro");
  im_impln("Fone:  (051)3212-2637  Porto Alegre - RS");
  im_impln("CNPJ: 93.015.006/0019-42  IE:096/0635920");
  im_italico(false);
  im_expandido(true);
  im_impln("    CUPOM FISCAL");
  im_expandido(false);
  im_condensado(true);
  im_impln("------------------------------------------------------------");
  im_condensado(false);
   im_negrito(true);
  im_impln("     PREFEITURA MUNICIPAL DE TESTE");  
  im_negrito(false);
  im_italico(true);         
  im_impln("Rua  Adbull Dabi, 590/12  Bairro  Centro");
  im_impln("Fone:  (051)3212-2637  Porto Alegre - RS");
  im_impln("CNPJ: 93.015.006/0019-42  IE:096/0635920");
  im_italico(false);
  im_expandido(true);
  im_impln("    CUPOM FISCAL");
  im_expandido(false);
  im_condensado(true);
  im_impln("------------------------------------------------------------");
  im_condensado(false);
   im_negrito(true);
  im_impln("     PREFEITURA MUNICIPAL DE TESTE");  
  im_negrito(false);
  im_italico(true);         
  im_impln("Rua  Adbull Dabi, 590/12  Bairro  Centro");
  im_impln("Fone:  (051)3212-2637  Porto Alegre - RS");
  im_impln("CNPJ: 93.015.006/0019-42  IE:096/0635920");
  im_italico(false);
  im_expandido(true);
  im_impln("    CUPOM FISCAL");
  im_expandido(false);
  im_condensado(true);
  im_impln("------------------------------------------------------------");
  im_condensado(false);
   im_negrito(true);
  im_impln("     PREFEITURA MUNICIPAL DE TESTE");  
  im_negrito(false);
  im_italico(true);         
  im_impln("Rua  Adbull Dabi, 590/12  Bairro  Centro");
  im_impln("Fone:  (051)3212-2637  Porto Alegre - RS");
  im_impln("CNPJ: 93.015.006/0019-42  IE:096/0635920");
  im_italico(false);
  im_expandido(true);
  im_impln("    CUPOM FISCAL");
  im_expandido(false);
  im_condensado(true);
  im_impln("------------------------------------------------------------");
  im_condensado(false);
  */
  im_fechar();
}
if(isset($_POST["imprimirln"])) {
  if(!im_conectar($IP,$PORTA)) {
    echo "<script>alert('nao ta rodando')</script>\n";
  } else {
    if(($ret = im_impln($_POST["texto"])) != 0) {
      $erro = $ret;
    }  
    im_fechar();
  }
}
if(isset($_POST["imprimir"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_imp($_POST["texto"])) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["autenticar"])) {
  im_conectar($IP,$PORTA);  
  /*
  if(($ret = im_autenticar($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(($ret = im_impln($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(($ret = im_impln($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(($ret = im_autenticar($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(($ret = im_autenticar($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(($ret = im_impln($_POST["texto"])) != 0) {
    $erro = $ret;
  }
  */
  if(($ret = im_autenticar($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(sleep(1) || ($ret = im_autenticar($_POST["texto"])) != 0) {
    $erro = $ret;
  } else if(($ret = im_impln($_POST["texto"])) != 0) {
    $erro = $ret;
  }
  im_fechar();
}
if(isset($_POST["negrito"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_negrito(true)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["sublinhado"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_sublinhado(true)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["italico"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_italico(true)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["negrito_d"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_negrito(false)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["sublinhado_d"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_sublinhado(false)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["italico_d"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_italico(false)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["condensado"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_condensado(true)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["expandido"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_expandido(true)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["condensado_d"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_condensado(false)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["expandido_d"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_expandido(false)) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["normal"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_fonteNormal()) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["elite"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_fonteElite()) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
if(isset($_POST["renicializar"])) {
  im_conectar($IP,$PORTA);  
  if(($ret = im_reset()) != 0) {
    $erro = $ret;
  }  
  im_fechar();
}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="post">
  <table width="94%" border="0" cellspacing="0" cellpadding="3">
    <tr> 
      <td colspan="2" nowrap>
        <input type="text" name="texto" value="0123456789012345687901234567980" size="50">
      </td>
    </tr>
    <tr>
      <label for="cab">    
      <input type="checkbox" id="cab" name="cabecalho" value="sim">
      <strong>imprimir cabeçalho e rodapé</strong>
      </label>
    </tr>
    <tr> 
      <td colspan="2"><strong>tipo de impress&atilde;o</strong>:<br>
        <input name="imprimir" type="submit" id="imprimir" value="Imprimir"> 
        <input name="imprimirln" type="submit" id="imprimirln" value="Imprimir com avan&ccedil;o de linha"> 
        <input name="autenticar" type="submit" id="autenticar" value="Autenticar"></td>
    </tr>
    <tr> 
      <td width="73%"><strong>real&ccedil;e:</strong><br>
        <input name="negrito" type="submit" id="negrito" value="HAB Negrito">
        <input name="sublinhado" type="submit" id="sublinhado" value="HAB Sublinhado">
        <input name="italico" type="submit" id="italico" value="HAB It&aacute;lico">
	<br>
	<input name="negrito_d" type="submit" id="negrito" value="DES Negrito">
        <input name="sublinhado_d" type="submit" id="sublinhado" value="DES Sublinhado">
        <input name="italico_d" type="submit" id="italico" value="DES It&aacute;lico">
      </td>
      <td width="27%">&nbsp;</td>
    </tr>
    <tr> 
      <td><strong>fonte</strong>:<br>
        <input name="normal" type="submit" id="normal" value="Normal">
        <input name="elite" type="submit" id="elite" value="Elite"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><strong>tamanho:</strong><br>
        <input name="condensado" type="submit" id="condensado" value="HAB Condensado">
        <input name="expandido" type="submit" id="expandido" value="HAB Expandido">
	<br>
	 <input name="condensado_d" type="submit" id="condensado" value="DES Condensado">
        <input name="expandido_d" type="submit" id="expandido" value="DES Expandido">
      </td>
      <td align="right"><input name="renicializar" type="submit" id="renicializar" value="Valores Default"></td>
    </tr>
  </table>
</form>
  <?
  if(isset($erro)) {
    switch($erro) {
//      case SEM_CABO:
 //       echo "<script>alert('Provavelmente a impressora esta com o cabo desconectado!')</script>\n";
//	break;
 //     case DESLIGADA:
  //      echo "<script>alert('Impressora desligada!')</script>\n";
//	break;
      case SEM_PAPEL:
        echo "<script>alert('Impressora sem papel!')</script>\n";
	break;
      case OFFLINE:
        echo "<script>alert('Impressora OffLine!')</script>\n";
	break;
      case ERRO:
        echo "<script>alert('Ocorreu um erro indeterminado!')</script>\n";
	break;      
    }
  }
  ?>
</body>
</html>
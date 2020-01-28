<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
//die("teste");
?>
<html>
<head>
<title>DBSeller Informática Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
<?  
      // Cria a janela para visualizacao de todas as inscricoes cadastradas
    $frameListaInscricoes = new janela("frameListaInscricoes","");
	  $frameListaInscricoes->posX=1;
	  $frameListaInscricoes->posY=20;
	  $frameListaInscricoes->largura=785;
	  $frameListaInscricoes->altura=430;
	  $frameListaInscricoes->titulo="Lista das inscrições";
	  $frameListaInscricoes->iniciarVisivel = false;
    $frameListaInscricoes->mostrar();

// Cria a janela para visualizacao dos dados da Inscricao selecionada
    $frameDadosInscricao = new janela("frameDadosInscricao","");
    $frameDadosInscricao->posX=1;
	  $frameDadosInscricao->posY=20;
	  $frameDadosInscricao->largura=785;
	  $frameDadosInscricao->altura=430;
	  $frameDadosInscricao->titulo="Visualização dos dados da inscrição selecionada";
	  $frameDadosInscricao->iniciarVisivel = false;
	  $frameDadosInscricao->mostrar();

 // Cria a janela para visualizacao da lista com os nomes de retornados pela pesquisa pelo nome da razão social 
   $frameListaRazaoSocial = new janela("frameListaRazaoSocial","");
	  $frameListaRazaoSocial->posX=1;
	  $frameListaRazaoSocial->posY=20;
	  $frameListaRazaoSocial->largura=785;
	  $frameListaRazaoSocial->altura=430;
	  $frameListaRazaoSocial->titulo="Lista de Razão Social";
	  $frameListaRazaoSocial->iniciarVisivel = false;
	  $frameListaRazaoSocial->mostrar();

      // Cria a janela para visualizacao da lista com os nomes dos escritorios
      $frameEscritorio = new janela("frameEscritorio","");
	  $frameEscritorio->posX=1;
	  $frameEscritorio->posY=20;
	  $frameEscritorio->largura=785;
	  $frameEscritorio->altura=430;
	  $frameEscritorio->titulo="Lista de escritórios";
	  $frameEscritorio->iniciarVisivel = false;
	  $frameEscritorio->mostrar();

      // Cria a janela para visualizacao da lista com os códigos ou nomes das ruas
      $frameListaRuas = new janela("frameListaRuas","");
	  $frameListaRuas->posX=1;
    $frameListaRuas->posY=20;
	  $frameListaRuas->largura=785;
	  $frameListaRuas->altura=430;
	  $frameListaRuas->titulo="Lista de ruas";
	  $frameListaRuas->iniciarVisivel = false;
	  $frameListaRuas->mostrar();

      // Cria a janela para visualizacao da lista com os códigos ou nome dos bairros
      $frameListaBairros = new janela("frameListaBairros","");
	  $frameListaBairros->posX=1;
	  $frameListaBairros->posY=20;
	  $frameListaBairros->largura=785;
	  $frameListaBairros->altura=430;
	  $frameListaBairros->titulo="Lista de bairros";
	  $frameListaBairros->iniciarVisivel = false;
	  $frameListaBairros->mostrar();

      // Cria a janela para visualizacao da lista de Atividades
      $frameListaAtividades = new janela("frameListaAtividades","");
	  $frameListaAtividades->posX=1;
	  $frameListaAtividades->posY=20;
	  $frameListaAtividades->largura=785;
	  $frameListaAtividades->altura=430;
	  $frameListaAtividades->titulo="Lista de atividades";
	  $frameListaAtividades->iniciarVisivel = false;
	  $frameListaAtividades->mostrar();

      // Cria a janela para visualizacao da lista de sócios
    $frameListaSocios = new janela("frameListaSocios","");
	  $frameListaSocios->posX=1;
	  $frameListaSocios->posY=20;
	  $frameListaSocios->largura=785;
	  $frameListaSocios->altura=430;
	  $frameListaSocios->titulo="Lista de sócios";
	  $frameListaSocios->iniciarVisivel = false;
	  $frameListaSocios->mostrar();

  //Carrega menu na janela

  //Inclui formulario nesta página
  include("forms/db_frmconsultainscricao.php"); 
?>
    </td>
  </tr>
</table>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
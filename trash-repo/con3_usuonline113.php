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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<frameset rows="*,100" frameborder="no" border="0" onLoad="window.focus();" onUnload="window.open('con3_usuonline003.php?id_usuario=<?=$id_usuario?>&usuario=<?=$usuario?>&hora=<?=$hora?>&sairfora=1','','width=1,height=1')" framespacing="0">
  <frame src="con3_usuonline013.php" name="mainFrame" frameborder="no" scrolling="auto" noresize>
  <frame src="con3_usuonline003.php?id_usuario=<?=$id_usuario?>&usuario=<?=$usuario?>&hora=<?=$hora?>&verfusuario=<?=@$verfusuario?>" name="bottomFrame" frameborder="no" scrolling="NO" noresize>
</frameset>
<noframes><body>
</body></noframes>
</html>
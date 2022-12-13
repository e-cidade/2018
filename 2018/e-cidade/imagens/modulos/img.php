<?
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

header("Content-type: image/png");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$img = imagecreatefromjpeg(__DIR__ . "/logomodulo.jpg");
$preto = imagecolorallocate($img,0,0,0);

$nome = urldecode(base64_decode($nome));

if(strpos($nome," ")) {
  $nome = split(" ",$nome);
  $x1 = 50 - ((strlen(trim($nome[0])) / 2) * 7);
  if(sizeof($nome) > 1)
    $x2 = 50 - ((strlen(trim($nome[1])) / 2) * 7);
  else
    $x2 = 0;
  if(sizeof($nome) > 2)
    $x3 = 50 - ((strlen(trim($nome[2])) / 2) * 7);
  else
    $x3 = 0;
//  imagestring($img,3,30,30,"Módulo",$preto);
  imagestring($img,3,$x1,30,@$nome[0],$preto);
  imagestring($img,3,$x2,50,@$nome[1],$preto);
  imagestring($img,3,$x3,70,@$nome[2],$preto);
} else {
  $x = 50 - ((strlen(trim($nome)) / 2) * 7);
//  imagestring($img,3,'30',30,"Módulo",$preto);
  imagestring($img,3,$x,30,"$nome",$preto);
}

imagejpeg($img);
imagedestroy($img);
?>

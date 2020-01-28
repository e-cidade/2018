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

use \ECidade\V3\Extension\Registry;

if (defined('ECIDADE_EXTENSION_PATH') && Registry::has('app.response')) {
  Registry::get('app.response')->setCode(500);
  Registry::get('app.response')->send();
}

$sMensagemErro = empty($_GET['db_erro']) ? '' : $_GET['db_erro'];

// converte para latin1
$encode = mb_detect_encoding($sMensagemErro, "UTF-8, ISO-8859-1, ISO-8859-15", true);
$sMensagemErro = mb_convert_encoding($sMensagemErro, 'ISO-8859-1', $encode);

// troca \n por tag br
$sMensagemErro = str_replace("\n", '<br />', $sMensagemErro);

$sRetorno = null;
$sButtonLabel = null;

$lFechar = empty($_GET['fechar']) ? false : $_GET['fechar'];
if ($lFechar) {

  $sRetorno = 'fechar();';
  $sButtonLabel = 'Fechar';
}

$sPaginaRetorno = empty($_GET['pagina_retorno']) ? false : $_GET['pagina_retorno'];
if ($sPaginaRetorno) {

  $sRetorno = "location.href='".$sPaginaRetorno."'";
  $sButtonLabel = 'Retornar';
}
?>
<html>
<head>
<title>Notificação do Sistema</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1" />

<style type="text/css">
  
@font-face {
  font-family: "KhmerUI";
  src: url("<?php echo defined('ECIDADE_REQUEST_PATH') ? ECIDADE_REQUEST_PATH : '';?>skins/estilos.php?file=fonts/KhmerUI.ttf");
}

* {
  font-family: "KhmerUI"
}

html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  widows: 100%;
  background-color: #e1dede;
}

.container {
  padding: 30px 30px 30px 80px;
  width: 50%;
  height: 50%;
  margin: auto;
  position: relative;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

.title {
  color: #004773;
  font-weight: normal;
  padding: 10px 0px;
  border-bottom: 1px solid #ccc;
}

.notification {
  padding: 15px 0px;
  margin: 0px;
}

.icon-warning {
  position: absolute;
  left: 15px;
  top: 55px;
  height: 30px;
  width: 30px;
  margin: 10px;
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAADSElEQVRIDcWWUYhUVRjHf9+5Z+6sWWmaiUasCPWoCEGIuatBRBmEQRAVipbYQ/QUFLIgvohgj/ruW+BThNFLMbuz5XP1GEQYVBStpi677s6955Nz7s49O3NnZmeKzQPL/X//7/v+/z3nfPcy8ICW/BtfbSZHcPaT0GuyCzKRfzmqzsjG2mAXUptGzHgwU3cDaR2WSX4ZxdyMUhxqk/qZ0tQT4R+ofzyqzkg71pnaPjANYFOnkd7ELO+Xg/zUyfePRtuxyNmqqReXLbj6p/1tqpmhjXXWHgJeqEqsMKKHdNru75vvSgxlrA0sTqZQeaSrP4Y+J2Yq1Ea2LxrKGEmPgpnoq9JOqLyESV9vh4Oeaw6XfscTZOkXIM8FofrOFltfrpE8XOjm8zD3VYul32sokJhZZPGIPM/dQcZ2UDLkWuZtZMXUE5sma+y+COahotUtQLZQ46/PwG/DuQM4cwzc5UHaA49am+wA+06HQLIBJI2Ux56LyyD2Pf2WnZGqooHG5PY0YvZ1tGkGbilSHntu9RLZS8vvuv/qa6zNdA8mOQnaNQddYW9tIbHva5M9vdPQ01gVIdeTwFOVxrA7P0XtlUN2ux3Ep5pxnH0zaEW2RD2NmbUTiDlVVq0G8z9CvhCZfBEWf45xicKIfxi0Si6CirFeJQE5gbAytrG4QA5MLZJm1aBFtkDCRpDjvXZdMWabfRFnjndrlLEfptbfkN8t/jx298p0BTjzFrNjlU9tx6TodTbQql0DUymMggYem4Tk0YLK78CtGf8Cx5IKcg02tl6VZynvqNN4On0D5Gr4EFSa/wPhr9skR2Vi4fO2SmmsDR5H6tcRnm4nez7tZnjyA7BbinR2E367BNk/PctLUs0PJIuH5SC3PBc/mSZ5BV3D1HeM7YLxqVIvgLlrMP99J1eJ3F6y5DXIr/hUGC79mu1gzw51xG4Zlv+Msh57bq3lz9bYM9pkmy8tptrad4Hda/WGvH990u2x1ONBr1SsJJxoHrwQ/WZsHKv+nDavrumLyzveWpRkc8PdcRS8zb2lZ0Rn0osgH0X+f0Canxedqftx7PrVuO7md/wd/7ruNlWDGxa3dAyTngOzA3/967sE3B+4Zf8z+cGs+6O45JteS78YAAAAAElFTkSuQmCC') no-repeat;
}

a {
  padding: 10px 20px;
  box-sizing: padding-box;
  outline: none;
  cursor: pointer;
  font-size: 18px;
  line-height: 38px;
  -moz-user-select: none;
  -webkit-user-select: none;
  font-weight: normal;
  text-decoration: none;
}
a.button{
  border: 1px solid #2f5b8c;
  color: #2f5b8c;
}

a.button[disabled],
a.button[disabled]:active,
a.button[disabled]:hover {
  color: #CCC;
  border-color: #CCC;
  background-color: white;
}

a.button:active{
  color: white;
  background-color: #2f5b8c;
}

a.button:hover{
  border: 1px solid black;
}

@media only screen and (max-width: 960px) {
  .container {
    width: 75%;
  }
}

@media only screen and (max-width: 800px) {
  .container {
    width: 85%;
  }
}

@media only screen and (max-width: 600px) {
  .container {
    width: 95%;
  }
}

</style>

</head>

<body>

<script type="text/javascript">
function fechar() {

  // nao eh um popup e esta usando v3 desktop
  if (!opener && window['top']['ECIDADE_REQUEST_PATH'] && frameElement && frameElement.CurrentWindow) {
    return window.frameElement.CurrentWindow.close();
  }

  return window.close();
}
</script>

<div class="container">
  
  <div class="icon-warning"></div>
  <h1 class="title">Notificação do Sistema</h1>

  <p class="notification">Desculpe, algo inesperado aconteceu:</p>

  <p class="notification"><?php echo $sMensagemErro; ?></p>

  <?php if (!empty($sRetorno)) : ?>
    <a onclick="<?php echo $sRetorno; ?>" class="button"><?php echo $sButtonLabel; ?></a>
  <?php endif; ?>

</div>

</body>
</html>

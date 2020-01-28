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


function int25($xp,$yp,$text) {
$xpos = 0;
$text = strtoupper($text);
$barcodeheight=50;                               // seta a altura das barras
$barcodethinwidth=1;                             // seta a largura da barra estreita
$barcodethickwidth=$barcodethinwidth*3;          // seta a relacao barra larga/barra estreita


// seta os codigos dos caracteres, sendo 0 para estreito e 1 para largo
$codingmap  =  Array(
"0"=>  "00110",  "1"=>  "10001",
"2"=>  "01001",  "3"=>  "11000",
"4"=>  "00101",  "5"=>  "10100",
"6"=>  "01100",  "7"=>  "00011",
"8"=>  "10010",  "9"=>  "01010");


// se no. de caracteres impar adiciona 0 no comeco
if(strlen($text)%2) {
	$text = "0".$text;
}
$textlen  =  strlen($text);


// calcula a largura total da imagem
// SEM USO $barcodewidth  = ($textlen)*(3*$barcodethinwidth + 2*$barcodethickwidth)+($textlen)*(2.5)+(7*$barcodethinwidth + $barcodethickwidth)+3;

$barcodewidth  = ($textlen)*(3*$barcodethinwidth + 2*$barcodethickwidth)+($textlen)*(2.5)+(7*$barcodethinwidth + $barcodethickwidth)+3;
// seta os parametros iniciais da imagem
//$im  =  ImageCreate($barcodewidth,$barcodeheight);
$im  =  ImageCreate($barcodewidth,$barcodeheight);
$black  =  ImageColorAllocate($im,0,0,0);
$white  =  ImageColorAllocate($im,255,255,255);
imagefill($im,0,0,$white);

// imprime na imagem o codigo de inicio
for ($i=0;$i<2;$i++) {
    $elementwidth = $barcodethinwidth;
    imagefilledrectangle($im, $xpos, 0, $xpos + $elementwidth - 1 , $barcodeheight, $black);
    $xpos += $elementwidth;
    $xpos += $barcodethinwidth;
//    $xpos ++;
}

// imprime na imagem o codigo em si
for  ($idx=0;$idx<$textlen;$idx+=2)  {      // a impressao e feita 2 caracteres por vez
    $charimpar  =  substr($text,$idx,1);    // pega o caracter impar, que vai ser impresso em preto
    $charpar  =  substr($text,$idx+1,1);    // pega o caracter par, que vai ser impresso em branco

    // interlacamento
    for  ($baridx=0;$baridx<5;$baridx++)  {  // a cada bit do codigo dos caracteres
        // imprime a barra coresspondente ao bit do caractere impar (preto)
        $elementwidth = (substr($codingmap[$charimpar],$baridx,1)) ?  $barcodethickwidth : $barcodethinwidth;
        imagefilledrectangle($im, $xpos,0, $xpos + $elementwidth - 1,$barcodeheight, $black);
        $xpos += $elementwidth;
        // deixa o espaco correspondente ao bit do caractere par (branco)
        $elementwidth = (substr($codingmap[$charpar],$baridx,1)) ?  $barcodethickwidth : $barcodethinwidth;
        $xpos += $elementwidth;
//        $xpos ++;
    }
}

// imprime o codigo de final
$elementwidth = $barcodethickwidth;
imagefilledrectangle($im, $xpos,0, $xpos + $elementwidth - 1, $barcodeheight, $black);
$xpos += $elementwidth;
$xpos += $barcodethinwidth;
//$xpos++;
$elementwidth = $barcodethinwidth;
//imagefilledrectangle($im, $xpos, 0, $xpos + $elementwidth - 1, $barcodeheight, $black);
imagefilledrectangle($im, $xpos, 0, $xpos + $elementwidth - 1, $barcodeheight, $black);
//imagefilledrectangle($im, 0, 0,imagesx($im),imagesy($im), $black);
//////imagecopyresized($saida,$im,$xp,$yp,0,0,imagesx($im),imagesy($im),imagesx($im),imagesy($im));


//imagecopyresampled()
//imagecopyresized(

// retorna a imagem
//Header(  "Content-type:  image/gif");
Header(  "Content-type:  imagejpeg");
imagejpeg($im);
ImageDestroy($im);
return;
}
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
int25(5,5,$text);
?>
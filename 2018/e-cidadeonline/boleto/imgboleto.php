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


function int25($saida,$xp,$yp,$text) {
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
imagecopyresized($saida,$im,$xp,$yp,0,0,imagesx($im),imagesy($im),imagesx($im),imagesy($im));
}

///////////INICIO///////////

Header("Content-type: image/png");
$str = split("##",base64_decode($argv[0]));
parse_str($str[0]);
$codigo_barras = split("&&",$str[1]);
$valor = $str[2];
$dtvenc = $str[3];
$img = ImageCreateFromPNG("recibo2.png");

$preto = imagecolorallocate($img,0,0,0);

/////////
//DADOS//
/////////

//Banco
ImageString($img,10,125,12,$k00_codbco."-0",$preto);
//Linha Digitável
ImageString($img,4,220,12,$linha_digitavel,$preto);
//Local de Pagamento
ImageString($img,3,12,55,$k15_local,$preto);
//ImageString($img,3,12,55,$str[0],$preto);
//Vencimento
ImageString($img,3,540,55,$dtvenc,$preto);
//Nome doo Cedente
ImageString($img,3,12,85,$nome_ced,$preto);
//codigo do Cedente
ImageString($img,3,520,85,$k15_ageced,$preto);
//Data do Documento
ImageString($img,3,12,109,$dt_hoje,$preto);
//Numero do Dopcumento
ImageString($img,3,125,109,$numero,$preto);
//Especie de Aceite
ImageString($img,3,247,109,$k15_espec,$preto);
//Aceite
ImageString($img,3,340,109,$k15_aceite,$preto);
//Data processamento
ImageString($img,3,410,109,$dt_hoje,$preto);
//Data processamento
ImageString($img,3,530,109,$fc_numbco,$preto);
//Codigo do cedente
ImageString($img,3,12,134,'',$preto);
//carteira
ImageString($img,3,120,134,$k15_carte,$preto);
//Valor
ImageString($img,3,540,134,$valor,$preto);
//Linha 1
ImageString($img,3,12,157,$k00_hist1,$preto);
ImageString($img,3,12,170,$k00_hist2,$preto);
ImageString($img,3,12,183,$k00_hist3,$preto);
ImageString($img,3,12,196,$k00_hist4,$preto);
ImageString($img,3,12,212,$k00_hist5,$preto);
ImageString($img,3,12,222,$k00_hist6,$preto);
ImageString($img,3,12,235,$k00_hist7,$preto);
ImageString($img,3,12,248,$k00_hist8,$preto);

ImageString($img,3,150,358,$codigo_barras[1],$preto);
int25($img,10,332,$codigo_barras[0]);
ImagePNG($img);
imagedestroy($img);
?>
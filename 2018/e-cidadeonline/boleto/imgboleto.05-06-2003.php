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
$identificacao = split("&&",$str[0]);
$dados = split("%%",$str[1]);
$taxabancaria = $str[2];
$numero = $str[3];
$codigo_barras = split("&&",$str[4]);
$dtvenc = $str[5];
$numpre = $str[6];
$valor = $str[7];
parse_str(base64_decode($str[8]));
parse_str($str[9]);
$img = ImageCreateFromPNG("recibo2.png");

$preto = imagecolorallocate($img,0,0,0);

/////////
//DADOS//
/////////

//Informações do contribuinte
//cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_uf,cgm.z01_cep,c.v01_nome,i.q02_compl,i.q02_numero,i.q02_bairro 
ImageString($img,2,35,145,"Nome:      ".$identificacao[0],$preto);
ImageString($img,2,35,158,"Endereço:  ".$identificacao[1],$preto);
ImageString($img,2,35,168,"Município: ".$identificacao[2]." - ".$identificacao[3],$preto);
ImageString($img,2,35,178,"CEP:       ".substr($identificacao[4],0,2).".".substr($identificacao[4],2,3)."-".substr($identificacao[4],3,2),$preto);
ImageString($img,2,35,188,"Data:      ".date("d/m/Y")." Hora: ".date("H:i:s"),$preto);
ImageString($img,2,35,198,"IP:        ".$_SERVER['REMOTE_ADDR'],$preto);
//logradouro numero complemento bairro
ImageString($img,2,460,168,$identificacao[5],$preto);
ImageString($img,2,590,168,$identificacao[6],$preto);
ImageString($img,2,620,168,$identificacao[7],$preto);
ImageString($img,2,455,203,$identificacao[8],$preto);
//Matrucula/Inscricao
ImageString($img,2,530,140,$numero,$preto);

//Banco
ImageString($img,10,125,610,$k00_codbco."-0",$preto);
//Linha Digitável
ImageString($img,4,220,615,$linha_digitavel,$preto);
//Local de Pagamento
ImageString($img,3,12,647,$k15_local,$preto);
//Vencimento
ImageString($img,3,540,647,$dtvenc,$preto);
//Nome doo Cedente
ImageString($img,3,12,680,$nome_ced,$preto);
//codigo do Cedente
ImageString($img,3,520,680,$k15_ageced,$preto);
//Data do Documento
ImageString($img,3,12,706,$dt_hoje,$preto);
//Numero do Dopcumento
ImageString($img,3,125,706,$numero,$preto);
//Especie de Aceite
ImageString($img,3,247,706,$k15_espec,$preto);
//Aceite
ImageString($img,3,340,706,$k15_aceite,$preto);
//Data processamento
ImageString($img,3,410,706,$dt_hoje,$preto);
//Data processamento
ImageString($img,3,530,706,$fc_numbco,$preto);
//Codigo do cedente
ImageString($img,3,12,730,'',$preto);
//carteira
ImageString($img,3,120,730,$k15_carte,$preto);
//Valor
ImageString($img,3,540,730,$valor,$preto);
//Linha 1
ImageString($img,3,12,754,$k00_hist1,$preto);
ImageString($img,3,12,767,$k00_hist2,$preto);
ImageString($img,3,12,780,$k00_hist3,$preto);
ImageString($img,3,12,793,$k00_hist4,$preto);
ImageString($img,3,12,806,$k00_hist5,$preto);
ImageString($img,3,12,819,$k00_hist6,$preto);
ImageString($img,3,12,832,$k00_hist7,$preto);
ImageString($img,3,12,845,$k00_hist8,$preto);

// nome do contribuinte

ImageString($img,2,12,871,"Nome:      ".$identificacao[0],$preto);
ImageString($img,2,12,884,"Endereço:  ".$identificacao[1],$preto);
ImageString($img,2,12,897,"Município: ".$identificacao[2]." - ".$identificacao[3]."  "."CEP:       ".substr($identificacao[4],0,2).".".substr($identificacao[4],2,3)."-".substr($identificacao[4],3,2),$preto);



//numpre
//ImageString($img,3,340,645,$numpre,$preto);
//valor
//ImageString($img,3,540,645,$valor,$preto);


//codigo de barras
//ImageString($img,3,150,680,$codigo_barras[1],$preto);
//int25($img,125,700,$codigo_barras[0]);
ImageString($img,3,150,855,$codigo_barras[1],$preto);
int25($img,10,927,$codigo_barras[0]);
//int25($img,70,700,"81704000001294003502003012400003305179800996");
//Receitas
ImageString($img,2,70,265,"Taxa Bancária",$preto);
ImageString($img,2,577,265,$taxabancaria,$preto);  
//k02_receit k02_descr k02_drecei valor
$c = 0;
for($i = 0;$i < sizeof($dados);$i++) {
  $aux = split("&&",$dados[$i]);
  ImageString($img,2,35,280 + $c,$aux[0],$preto);
  ImageString($img,2,70,280 + $c,$aux[2],$preto);
  ImageString($img,2,380,280 + $c,$aux[1],$preto);  
  $aux = number_format($aux[3],2,".",",");
  $x =  strlen($aux);
  for($j = 600;$x--;$j -= 5) {
    ImageString($img,2,$j,280 + $c,$aux[$x],$preto); 
  }
  $c += 15;
}
/******************************
if(substr($parc,0,4) != "PARC") {
  ImageString($img,2,70,265,"Taxa Bancária",$preto);
  ImageString($img,2,580,265,$taxabancaria,$preto);  
  $rec = split("&",$rec);
  $c=0;
  //ImageString($img,2,35,270,sizeof($rec),$preto);
  for($i = 0;$i < sizeof($rec);$i += 4) {
    $vabs = abs(substr($rec[$i],strpos($rec[$i],"=")+1));
    $vabs = number_format($vabs,2,".",",");	
    if($vabs == "0.00")
      $vabs = "";
    $num_vabs = strlen($vabs);
  
    ImageString($img,2,35,280 + $c,substr($rec[$i + 3],strpos($rec[$i + 3],"=")+1),$preto);
    ImageString($img,2,70,280 + $c,substr($rec[$i + 1],strpos($rec[$i + 1],"=")+1),$preto);
    ImageString($img,2,370,280 + $c,substr($rec[$i + 2],strpos($rec[$i + 2],"=")+1),$preto);
    $f = 610;
    for($w = $num_vabs;$w > -1;$w--) {
      ImageString($img,2,$f,280 + $c,$vabs[$w],$preto);      
      $f -= 6;
    }
    $c += 15;
  }
} else {
  ImageString($img,2,35,280,"Valor",$preto);
  ImageString($img,2,100,280,"Desconto",$preto);
  ImageString($img,2,180,280,"Taxa Bancária",$preto);
  ImageString($img,2,290,280,"Total",$preto);
      
  ImageString($img,2,35,295,$apagar_u,$preto);
  ImageString($img,2,100,295,$desconto_u,$preto);
  ImageString($img,2,180,295,$taxabancaria,$preto);  
  ImageString($img,2,290,295,(($apagar_u - $desconto_u) + $taxabancaria),$preto);
}
***********************************/
//parcelas
//ImageString($img,2,35,390,"Parcelas: ".$parc,$preto);

//outras informaçoes
ImageString($img,$tam1,$posx1,$posy1,$obs1,$preto);
ImageString($img,$tam2,$posx2,$posy2,$obs2,$preto);
ImageString($img,$tam3,$posx3,$posy3,$obs3,$preto);
ImageString($img,$tam4,$posx4,$posy4,$obs4,$preto);

ImagePNG($img);
//imagejpeg($img);
imagedestroy($img);

//int25($img,0,0,"81704000001294003502003012400003305178300990");

?>
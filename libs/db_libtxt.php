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

 function espaco($tamanho='',$conteudo=' '){
     $tm='';
     for($x=0;$x < $tamanho;$x++){
       $tm = $tm .$conteudo;
     }  
     return $tm;
 }
 /**
   prototipo =
   $numero = formatar($numero,10,'n');
   10 = tamanho
   n  = tipo  que pode ser (n,v,c,d)
   tipo  n= numero, alinhado a direta c/ zeros a esquerda
         v= valor, alinha  a direita c/ zero a esqueda e adiciona cadas decimais quando não tem
 	 c= caracter, alinha a esquerda e coloca espaço a direita
 	 c= data, recebe do banco e faz 16/08/2005 = 16082005
  */
 function formatar($field,$size,$tipo=""){
    $field = trim($field);
    if ((strlen($field) > $size ) && $tipo !='d' ){
       $field = substr($field,0,$size);
    }   
    if ($tipo=="c"){
       $field = $field.espaco($size-(strlen($field)));   
       
    } else if ($tipo=="n"){
       $field = str_replace('.','',$field);
       $field = espaco($size-(strlen($field)),'0').$field;
       
    } else if ($tipo=="v"){ 
       $pos = strpos($field,'.');
       if ($pos ==''){
          $field = $field.".00";
       }else{
          if (strlen($field)==$pos+2){
	     $field = $field."0";
	  } 
       }	 
       $field = str_replace('.','',$field);
       $field = espaco($size-(strlen($field)),'0').$field;

    } else if ($tipo =="d"){  
       $dt= split("-",$field);
       $field = "$dt[2]$dt[1]$dt[0]";
    }  
    return $field;
}
?>
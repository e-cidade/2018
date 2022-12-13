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

  $r = "abcdefghijklmn";

  set_time_limit(0);
  ob_implicit_flush();
  //$fp = fsockopen("localhost", 5001, $errno, $errstr, 30);
  //echo im_imp("lkjlkj");    
  im_conectar("localhost",5001);
  echo im_imp("TESTE DE IMP SEM N");
  echo im_imprimir("aaaaaaaaaaaaaaaaaaaa");
  echo im_imprimir("bbbbbbbbbbbbbbbbbbbb");
  echo im_imprimir("cccccccccccccccccccc");
  echo im_imprimir("dddddddddddddddddddd");
  echo im_imprimir("eeeeeeeeeeeeeeeeeeee");
  echo im_imprimir("ffffffffffffffffffff");
  im_fechar();
/*
  if (!$fp)
    echo "$errstr ($errno)'\n";
  else {   
      $j = 10; 
     for($i = 0;$i < 5;$i++) {
       fputs($fp,substr($r,0,$j--));   
       sleep(1);
     }
  }    
  fclose($fp);
*/
?>
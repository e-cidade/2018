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

$x = fsockopen("192.168.1.17",4444);
$modelo = 2;
  if($modelo == 2){
     fputs($x,chr(27).chr(66)."041");
     fputs($x,chr(27).chr(67)."municipio$");
          fputs($x,chr(27).chr(70)."carlos crestani$");
    fputs($x,chr(27).chr(68).'010105');

     fputs($x,chr(27).chr(86)."00000000012299");
 }else{
     fputs($x,chr(27).chr(160)."EMITE_CHEQUE_FAVORECIDO \n");
     fputs($x,chr(27).chr(161)."MUNICIPIO \n");
     fputs($x,chr(27).chr(162)."BANCO \n");
     fputs($x,chr(27).chr(163)."100 \n");
     fputs($x,chr(27).chr(164)."01-01-05 \n");
     fputs($x,chr(27).chr(176)."\n");

   fputs($x," \n");
   fputs($x," \n");
   fputs($x," \n");
   fputs($x," \n");
   fputs($x," \n");
   fputs($x," \n");
   fputs($x," \n");
   fputs($x,"          Prefeito: Chefe Tesoureiro: Mestre"."\n");

}

fclose($x);
?>
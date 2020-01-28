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

class cta_oper {
   var $arq = null;

  function cta_oper($header){
    umask(74);
    $this->arq = fopen("tmp/CTA_OPER.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");


  }  

 function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {  
    
    global $nomeinst,$c60_estrut,$c61_codigo,$c63_banco,$c63_agencia,$c63_conta;
    global $codcla,$c60_codcla;
 

   // $where = " c61_instit in (".str_replace('-',', ',$instit).") ";
   
   // $result = db_planocontassaldo(db_getsession("DB_anousu"),$data_ini,$data_fim,false,$where);

    //db_criatabela($result);exit;

    global $contador;
    $contador=0;


     // teste arquivo nao tem definiчуo pelo tribunal
 
     //  trailer
     $contador = espaco(10-(strlen($contador)),'0').$contador;
     $line = "FINALIZADOR".$contador;
     fputs($this->arq,$line);
     fputs($this->arq,"\r\n");

     fclose($this->arq);


     $teste ="true";
     return $teste;
   }

}

?>
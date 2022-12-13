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


//include("classes/db_conarquivospad_classe.php");

class orgao {
  var $arq = null;
  
  function orgao($header){     
     umask(74);
     $this->arq = fopen("tmp/ORGAO.TXT",'w+');
     fputs($this->arq,$header);
     fputs($this->arq,"\r\n");  
  }  

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {
    global $instituicoes,$contador,$nomeinst;
 
    ///// abre arquivo dos exercícios anteriores
    $exercicios = "";
    $virg = "";

    $clarqpad = new cl_conarquivospad;
    
    $res =$clarqpad->sql_record(
               $clarqpad->sql_query(null,"*",null," c54_nomearq = 'ORGAO.TXT' and c54_anousu=".db_getsession("DB_anousu")."  and c54_codtrib = $tribinst "));
    if($clarqpad->numrows > 0){
      db_fieldsmemory($res,0);
      $rubant = split("\r\n",pg_result($res,0,"c54_arquivo"));
      for($yy=0;$yy<sizeof($rubant);$yy++){
         $contador++;
         $line = $rubant[$yy];
 
         $exercicios .= $virg.substr($rubant[$yy],0,4);
         $virg = ",";

         fputs($this->arq,$line);
         fputs($this->arq,"\r\n");
      }
    }

    //////
    $where = " where o58_instit in ($instit)";
    
    $sql = "select distinct  o40_anousu,
 	                       o40_orgao,
	                       o40_descr as nome
                from orcorgao
	               inner join orcdotacao on o58_orgao  = o40_orgao
		                                          and o58_anousu = o40_anousu
	           $where and o58_anousu <= " . db_getsession("DB_anousu");


	 if (!empty($exercicios))
	   $sql .= "  and not o58_anousu in ($exercicios) ";

    $res=pg_exec($sql);
    $rows = pg_numrows($res);
    for ($x=0;$x < $rows;$x++){
       $anousu = formatar(pg_result($res,$x,"o40_anousu"),4,'n');
       $orgao  = formatar(pg_result($res,$x,"o40_orgao"),2,'n');
       $nome   = formatar(pg_result($res,$x,"nome"),80,'c');

       //-- 
       $line = $anousu.$orgao.$nome; 
       fputs($this->arq,$line);
       fputs($this->arq,"\r\n");
  
       $contador = $contador+1; // incrementa contador global
    }



    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste = "true";
    return $teste;

  }
}
?>
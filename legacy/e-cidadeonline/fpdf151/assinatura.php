<?
class cl_assinatura {
//|00|//assinatura
//|10|//Esta é o construtor da classe. Ele permite que seja impresso a assinatura do usuario corrente ou  
//|10|//de um tipo de assinatura específica a qual será definida nas tabelas db_paragrafos e db_documentos
//|10|//escolhendo o tipo de assinatura de acordo com a tabela db_tipodoc.
//|15|//$classinatura = new cl_assinatura;

  function assinatura_usuario(){
//#00#//assinatura_usuario
//#10#//Este método é usado gerar a assinatura do usuario que gerou o relatório
//#15#//assinatura_usuario()	
    $result = db_query("
     		       select * 
		       from db_usuarios 
		       where id_usuario = ".db_getsession("DB_id_usuario")
		      );
    $nome = pg_result($result,0,"nome");
    return $nome;
  }
function assinatura($codigo,$default='',$pos_paragrafo=""){
 	
//#00#//assinatura
//#10#//Este método é usado gerar a assinatura de acordo com o cadastro de assinaturas
//#15#//assinatura($codigo,$default)	
//#20#//codigo  : codigo do tipo de assinatura da tabela db_tipodoc
//#20#//default : string default que será impressa caso o código não seja encontrado
//#20#//pos_paragrafo : qual será o paragrafo retornado, se retornar somente o primeiro, seria somente o nome da pessoa 
//#20#//, por exemplo  ( nome, carlos).  0 = nome, 1 = cargo , passar este parementro sempre entre aspas simples
//#20#//na tabela.
       $result =  db_query(       
                         "select db_paragrafo.*
      	        	      from db_documento
                      		    inner join db_docparag on db03_docum = db04_docum
                      		    inner join db_paragrafo on db04_idparag = db02_idparag
                 	   	  where db03_tipodoc = $codigo and
                       		         db03_instit  = ".db_getsession('DB_instit')."
                 		  order by db02_descr
                 		");                 	
        //db_criatabela($result);exit;
       $ass =  $default;
       if(pg_numrows($result) > 0){
         $ass = '';
         $ar = "";
         for($i=0;$i<pg_numrows($result);$i++){
             $db02_texto = pg_result($result,$i,"db02_texto");
             $ass .= $ar.$db02_texto;
             $ar = "\r\n";
         }         
          /* paragrafo : qual será o paragrafo retornado, se retornar somente o primeiro, seria somente o nome da pessoa 
           * por exemplo  ( nome, carlos).  0 = nome, 1 = cargo
           * aqui sobrescrevemos as variáveis acima ( codigo pobre )
           */
           if ($pos_paragrafo!=""){           	
           	   $ass = '';               
           	   if (pg_numrows($result) > $pos_paragrafo){
                    $db02_texto = pg_result($result,$pos_paragrafo,"db02_texto"); // retorna da posição paragrafo
                    $ass = $db02_texto;                          
           	   } 
           }
           //------------------------------------------ * -------------------------------------
       }
        return $ass;        
  }

}

?>
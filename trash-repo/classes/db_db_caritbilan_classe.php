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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_caritbilan
class cl_db_caritbilan { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $id_itbi = 0; 
   var $codcaritbi = 0; 
   var $area = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_itbi = int4 = Guia 
                 codcaritbi = int8 = C�digo Caracter�stica 
                 area = float8 = �rea 
                 ";
   //funcao construtor da classe 
   function cl_db_caritbilan() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_caritbilan"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->id_itbi = ($this->id_itbi == ""?@$GLOBALS["HTTP_POST_VARS"]["id_itbi"]:$this->id_itbi);
       $this->codcaritbi = ($this->codcaritbi == ""?@$GLOBALS["HTTP_POST_VARS"]["codcaritbi"]:$this->codcaritbi);
       $this->area = ($this->area == ""?@$GLOBALS["HTTP_POST_VARS"]["area"]:$this->area);
     }else{
       $this->id_itbi = ($this->id_itbi == ""?@$GLOBALS["HTTP_POST_VARS"]["id_itbi"]:$this->id_itbi);
       $this->codcaritbi = ($this->codcaritbi == ""?@$GLOBALS["HTTP_POST_VARS"]["codcaritbi"]:$this->codcaritbi);
     }
   }
   // funcao para inclusao
   function incluir ($id_itbi,$codcaritbi){ 
      $this->atualizacampos();
     if($this->area == null ){ 
       $this->erro_sql = " Campo �rea nao Informado.";
       $this->erro_campo = "area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->id_itbi = $id_itbi; 
       $this->codcaritbi = $codcaritbi; 
     if(($this->id_itbi == null) || ($this->id_itbi == "") ){ 
       $this->erro_sql = " Campo id_itbi nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->codcaritbi == null) || ($this->codcaritbi == "") ){ 
       $this->erro_sql = " Campo codcaritbi nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_caritbilan(
                                       id_itbi 
                                      ,codcaritbi 
                                      ,area 
                       )
                values (
                                $this->id_itbi 
                               ,$this->codcaritbi 
                               ,$this->area 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Caracter�sticas digitadas ($this->id_itbi."-".$this->codcaritbi) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Caracter�sticas digitadas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Caracter�sticas digitadas ($this->id_itbi."-".$this->codcaritbi) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_itbi."-".$this->codcaritbi;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_itbi,$this->codcaritbi));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1032,'$this->id_itbi','I')");
       $resac = db_query("insert into db_acountkey values($acount,2427,'$this->codcaritbi','I')");
       $resac = db_query("insert into db_acount values($acount,182,1032,'','".AddSlashes(pg_result($resaco,0,'id_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,182,2427,'','".AddSlashes(pg_result($resaco,0,'codcaritbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,182,1033,'','".AddSlashes(pg_result($resaco,0,'area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_itbi=null,$codcaritbi=null) { 
      $this->atualizacampos();
     $sql = " update db_caritbilan set ";
     $virgula = "";
     if(trim($this->id_itbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_itbi"])){ 
       $sql  .= $virgula." id_itbi = $this->id_itbi ";
       $virgula = ",";
       if(trim($this->id_itbi) == null ){ 
         $this->erro_sql = " Campo Guia nao Informado.";
         $this->erro_campo = "id_itbi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codcaritbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcaritbi"])){ 
       $sql  .= $virgula." codcaritbi = $this->codcaritbi ";
       $virgula = ",";
       if(trim($this->codcaritbi) == null ){ 
         $this->erro_sql = " Campo C�digo Caracter�stica nao Informado.";
         $this->erro_campo = "codcaritbi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["area"])){ 
       $sql  .= $virgula." area = $this->area ";
       $virgula = ",";
       if(trim($this->area) == null ){ 
         $this->erro_sql = " Campo �rea nao Informado.";
         $this->erro_campo = "area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_itbi!=null){
       $sql .= " id_itbi = $this->id_itbi";
     }
     if($codcaritbi!=null){
       $sql .= " and  codcaritbi = $this->codcaritbi";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_itbi,$this->codcaritbi));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1032,'$this->id_itbi','A')");
         $resac = db_query("insert into db_acountkey values($acount,2427,'$this->codcaritbi','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_itbi"]))
           $resac = db_query("insert into db_acount values($acount,182,1032,'".AddSlashes(pg_result($resaco,$conresaco,'id_itbi'))."','$this->id_itbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcaritbi"]))
           $resac = db_query("insert into db_acount values($acount,182,2427,'".AddSlashes(pg_result($resaco,$conresaco,'codcaritbi'))."','$this->codcaritbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["area"]))
           $resac = db_query("insert into db_acount values($acount,182,1033,'".AddSlashes(pg_result($resaco,$conresaco,'area'))."','$this->area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracter�sticas digitadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_itbi."-".$this->codcaritbi;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracter�sticas digitadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_itbi."-".$this->codcaritbi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_itbi."-".$this->codcaritbi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_itbi=null,$codcaritbi=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_itbi,$codcaritbi));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1032,'$id_itbi','E')");
         $resac = db_query("insert into db_acountkey values($acount,2427,'$codcaritbi','E')");
         $resac = db_query("insert into db_acount values($acount,182,1032,'','".AddSlashes(pg_result($resaco,$iresaco,'id_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,182,2427,'','".AddSlashes(pg_result($resaco,$iresaco,'codcaritbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,182,1033,'','".AddSlashes(pg_result($resaco,$iresaco,'area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_caritbilan
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_itbi != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_itbi = $id_itbi ";
        }
        if($codcaritbi != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codcaritbi = $codcaritbi ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Caracter�sticas digitadas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_itbi."-".$codcaritbi;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Caracter�sticas digitadas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_itbi."-".$codcaritbi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_itbi."-".$codcaritbi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_caritbilan";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>
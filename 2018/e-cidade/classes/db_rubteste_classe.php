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

//MODULO: Recursos Humanos
//CLASSE DA ENTIDADE rubteste
class cl_rubteste { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $rubant = null; 
   var $descrrub = null; 
   var $rubnova = null; 
   var $anomes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rubant = varchar(3) = Rubrica Anterior 
                 descrrub = varchar(40) = Descrição da Rubrica 
                 rubnova = varchar(4) = Rubrica Nova 
                 anomes = int4 = Ano/Mês 
                 ";
   //funcao construtor da classe 
   function cl_rubteste() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rubteste"); 
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
       $this->rubant = ($this->rubant == ""?@$GLOBALS["HTTP_POST_VARS"]["rubant"]:$this->rubant);
       $this->descrrub = ($this->descrrub == ""?@$GLOBALS["HTTP_POST_VARS"]["descrrub"]:$this->descrrub);
       $this->rubnova = ($this->rubnova == ""?@$GLOBALS["HTTP_POST_VARS"]["rubnova"]:$this->rubnova);
       $this->anomes = ($this->anomes == ""?@$GLOBALS["HTTP_POST_VARS"]["anomes"]:$this->anomes);
     }else{
       $this->rubant = ($this->rubant == ""?@$GLOBALS["HTTP_POST_VARS"]["rubant"]:$this->rubant);
       $this->descrrub = ($this->descrrub == ""?@$GLOBALS["HTTP_POST_VARS"]["descrrub"]:$this->descrrub);
     }
   }
   // funcao para inclusao
   function incluir ($rubant,$descrrub){ 
      $this->atualizacampos();
     if($this->rubnova == null ){ 
       $this->erro_sql = " Campo Rubrica Nova nao Informado.";
       $this->erro_campo = "rubnova";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->anomes == null ){ 
       $this->erro_sql = " Campo Ano/Mês nao Informado.";
       $this->erro_campo = "anomes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rubant = $rubant; 
       $this->descrrub = $descrrub; 
     if(($this->rubant == null) || ($this->rubant == "") ){ 
       $this->erro_sql = " Campo rubant nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->descrrub == null) || ($this->descrrub == "") ){ 
       $this->erro_sql = " Campo descrrub nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rubteste(
                                       rubant 
                                      ,descrrub 
                                      ,rubnova 
                                      ,anomes 
                       )
                values (
                                '$this->rubant' 
                               ,'$this->descrrub' 
                               ,'$this->rubnova' 
                               ,$this->anomes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rubteste ($this->rubant."-".$this->descrrub) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rubteste já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rubteste ($this->rubant."-".$this->descrrub) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rubant."-".$this->descrrub;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->rubant,$this->descrrub));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5731,'$this->rubant','I')");
       $resac = db_query("insert into db_acountkey values($acount,5732,'$this->descrrub','I')");
       $resac = db_query("insert into db_acount values($acount,908,5731,'','".AddSlashes(pg_result($resaco,0,'rubant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,908,5732,'','".AddSlashes(pg_result($resaco,0,'descrrub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,908,5733,'','".AddSlashes(pg_result($resaco,0,'rubnova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,908,5734,'','".AddSlashes(pg_result($resaco,0,'anomes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rubant=null,$descrrub=null) { 
      $this->atualizacampos();
     $sql = " update rubteste set ";
     $virgula = "";
     if(trim($this->rubant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rubant"])){ 
       $sql  .= $virgula." rubant = '$this->rubant' ";
       $virgula = ",";
       if(trim($this->rubant) == null ){ 
         $this->erro_sql = " Campo Rubrica Anterior nao Informado.";
         $this->erro_campo = "rubant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descrrub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descrrub"])){ 
       $sql  .= $virgula." descrrub = '$this->descrrub' ";
       $virgula = ",";
       if(trim($this->descrrub) == null ){ 
         $this->erro_sql = " Campo Descrição da Rubrica nao Informado.";
         $this->erro_campo = "descrrub";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rubnova)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rubnova"])){ 
       $sql  .= $virgula." rubnova = '$this->rubnova' ";
       $virgula = ",";
       if(trim($this->rubnova) == null ){ 
         $this->erro_sql = " Campo Rubrica Nova nao Informado.";
         $this->erro_campo = "rubnova";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->anomes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anomes"])){ 
       $sql  .= $virgula." anomes = $this->anomes ";
       $virgula = ",";
       if(trim($this->anomes) == null ){ 
         $this->erro_sql = " Campo Ano/Mês nao Informado.";
         $this->erro_campo = "anomes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  rubant = '$this->rubant'
 and  descrrub = '$this->descrrub'
";
     $resaco = $this->sql_record($this->sql_query_file($this->rubant,$this->descrrub));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5731,'$this->rubant','A')");
       $resac = pg_query("insert into db_acountkey values($acount,5732,'$this->descrrub','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rubant"]))
         $resac = pg_query("insert into db_acount values($acount,908,5731,'".AddSlashes(pg_result($resaco,0,'rubant'))."','$this->rubant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descrrub"]))
         $resac = pg_query("insert into db_acount values($acount,908,5732,'".AddSlashes(pg_result($resaco,0,'descrrub'))."','$this->descrrub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rubnova"]))
         $resac = pg_query("insert into db_acount values($acount,908,5733,'".AddSlashes(pg_result($resaco,0,'rubnova'))."','$this->rubnova',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anomes"]))
         $resac = pg_query("insert into db_acount values($acount,908,5734,'".AddSlashes(pg_result($resaco,0,'anomes'))."','$this->anomes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rubteste nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rubant."-".$this->descrrub;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rubteste nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rubant."-".$this->descrrub;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rubant."-".$this->descrrub;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rubant=null,$descrrub=null) { 
       $resaco = $this->sql_record($this->sql_query_file($rubant,$descrrub));
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5731,'$rubant','E')");
         $resac = db_query("insert into db_acountkey values($acount,5732,'$descrrub','E')");
         $resac = db_query("insert into db_acount values($acount,908,5731,'','".AddSlashes(pg_result($resaco,$iresaco,'rubant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,908,5732,'','".AddSlashes(pg_result($resaco,$iresaco,'descrrub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,908,5733,'','".AddSlashes(pg_result($resaco,$iresaco,'rubnova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,908,5734,'','".AddSlashes(pg_result($resaco,$iresaco,'anomes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rubteste
                    where ";
     $sql2 = "";
        if($rubant != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rubant = '$rubant' ";
        }
        if($descrrub != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " descrrub = '$descrrub' ";
        }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rubteste nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rubant."-".$descrrub;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rubteste nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rubant."-".$descrrub;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rubant."-".$descrrub;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rubteste";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rubant=null,$descrrub=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rubteste ";
     $sql2 = "";
     if($dbwhere==""){
       if($rubant!=null ){
         $sql2 .= " where rubteste.rubant = '$rubant' "; 
       } 
       if($descrrub!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rubteste.descrrub = '$descrrub' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $rubant=null,$descrrub=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rubteste ";
     $sql2 = "";
     if($dbwhere==""){
       if($rubant!=null ){
         $sql2 .= " where rubteste.rubant = '$rubant' "; 
       } 
       if($descrrub!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rubteste.descrrub = '$descrrub' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>
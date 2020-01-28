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

//MODULO: empenho
//CLASSE DA ENTIDADE db_errobanco
class cl_db_errobanco { 
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
   var $e78_codban = null; 
   var $e78_errobanco = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e78_codban = varchar(10) = Código do banco FEBRABAN 
                 e78_errobanco = int4 = Sequencia 
                 ";
   //funcao construtor da classe 
   function cl_db_errobanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_errobanco"); 
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
       $this->e78_codban = ($this->e78_codban == ""?@$GLOBALS["HTTP_POST_VARS"]["e78_codban"]:$this->e78_codban);
       $this->e78_errobanco = ($this->e78_errobanco == ""?@$GLOBALS["HTTP_POST_VARS"]["e78_errobanco"]:$this->e78_errobanco);
     }else{
       $this->e78_codban = ($this->e78_codban == ""?@$GLOBALS["HTTP_POST_VARS"]["e78_codban"]:$this->e78_codban);
       $this->e78_errobanco = ($this->e78_errobanco == ""?@$GLOBALS["HTTP_POST_VARS"]["e78_errobanco"]:$this->e78_errobanco);
     }
   }
   // funcao para inclusao
   function incluir ($e78_codban,$e78_errobanco){ 
      $this->atualizacampos();
       $this->e78_codban = $e78_codban; 
       $this->e78_errobanco = $e78_errobanco; 
     if(($this->e78_codban == null) || ($this->e78_codban == "") ){ 
       $this->erro_sql = " Campo e78_codban nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e78_errobanco == null) || ($this->e78_errobanco == "") ){ 
       $this->erro_sql = " Campo e78_errobanco nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_errobanco(
                                       e78_codban 
                                      ,e78_errobanco 
                       )
                values (
                                '$this->e78_codban' 
                               ,$this->e78_errobanco 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Erros nos arquivos txt ($this->e78_codban."-".$this->e78_errobanco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Erros nos arquivos txt já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Erros nos arquivos txt ($this->e78_codban."-".$this->e78_errobanco) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e78_codban."-".$this->e78_errobanco;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e78_codban,$this->e78_errobanco));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7293,'$this->e78_codban','I')");
       $resac = db_query("insert into db_acountkey values($acount,7292,'$this->e78_errobanco','I')");
       $resac = db_query("insert into db_acount values($acount,1211,7293,'','".AddSlashes(pg_result($resaco,0,'e78_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1211,7292,'','".AddSlashes(pg_result($resaco,0,'e78_errobanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e78_codban=null,$e78_errobanco=null) { 
      $this->atualizacampos();
     $sql = " update db_errobanco set ";
     $virgula = "";
     if(trim($this->e78_codban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e78_codban"])){ 
       $sql  .= $virgula." e78_codban = '$this->e78_codban' ";
       $virgula = ",";
       if(trim($this->e78_codban) == null ){ 
         $this->erro_sql = " Campo Código do banco FEBRABAN nao Informado.";
         $this->erro_campo = "e78_codban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e78_errobanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e78_errobanco"])){ 
       $sql  .= $virgula." e78_errobanco = $this->e78_errobanco ";
       $virgula = ",";
       if(trim($this->e78_errobanco) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "e78_errobanco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e78_codban!=null){
       $sql .= " e78_codban = '$this->e78_codban'";
     }
     if($e78_errobanco!=null){
       $sql .= " and  e78_errobanco = $this->e78_errobanco";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e78_codban,$this->e78_errobanco));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7293,'$this->e78_codban','A')");
         $resac = db_query("insert into db_acountkey values($acount,7292,'$this->e78_errobanco','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e78_codban"]))
           $resac = db_query("insert into db_acount values($acount,1211,7293,'".AddSlashes(pg_result($resaco,$conresaco,'e78_codban'))."','$this->e78_codban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e78_errobanco"]))
           $resac = db_query("insert into db_acount values($acount,1211,7292,'".AddSlashes(pg_result($resaco,$conresaco,'e78_errobanco'))."','$this->e78_errobanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erros nos arquivos txt nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e78_codban."-".$this->e78_errobanco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Erros nos arquivos txt nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e78_codban."-".$this->e78_errobanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e78_codban."-".$this->e78_errobanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e78_codban=null,$e78_errobanco=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e78_codban,$e78_errobanco));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7293,'$e78_codban','E')");
         $resac = db_query("insert into db_acountkey values($acount,7292,'$e78_errobanco','E')");
         $resac = db_query("insert into db_acount values($acount,1211,7293,'','".AddSlashes(pg_result($resaco,$iresaco,'e78_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1211,7292,'','".AddSlashes(pg_result($resaco,$iresaco,'e78_errobanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_errobanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e78_codban != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e78_codban = '$e78_codban' ";
        }
        if($e78_errobanco != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e78_errobanco = $e78_errobanco ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erros nos arquivos txt nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e78_codban."-".$e78_errobanco;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Erros nos arquivos txt nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e78_codban."-".$e78_errobanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e78_codban."-".$e78_errobanco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_errobanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e78_codban=null,$e78_errobanco=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_errobanco ";
     $sql .= "      inner join errobanco  on  errobanco.e92_sequencia = db_errobanco.e78_errobanco";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = db_errobanco.e78_codban";
     $sql2 = "";
     if($dbwhere==""){
       if($e78_codban!=null ){
         $sql2 .= " where db_errobanco.e78_codban = '$e78_codban' "; 
       } 
       if($e78_errobanco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_errobanco.e78_errobanco = $e78_errobanco "; 
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
   // funcao do sql 
   function sql_query_file ( $e78_codban=null,$e78_errobanco=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_errobanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($e78_codban!=null ){
         $sql2 .= " where db_errobanco.e78_codban = '$e78_codban' "; 
       } 
       if($e78_errobanco!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_errobanco.e78_errobanco = $e78_errobanco "; 
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
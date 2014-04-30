<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_estrutura
class cl_db_estrutura { 
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
   var $db77_codestrut = 0; 
   var $db77_estrut = null; 
   var $db77_descr = null; 
   var $db77_permitesintetico = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db77_codestrut = int4 = Código 
                 db77_estrut = varchar(30) = Estrutural 
                 db77_descr = varchar(40) = Decrição 
                 db77_permitesintetico = bool = Permitir Dados Cta Sintetica 
                 ";
   //funcao construtor da classe 
   function cl_db_estrutura() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_estrutura"); 
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
       $this->db77_codestrut = ($this->db77_codestrut == ""?@$GLOBALS["HTTP_POST_VARS"]["db77_codestrut"]:$this->db77_codestrut);
       $this->db77_estrut = ($this->db77_estrut == ""?@$GLOBALS["HTTP_POST_VARS"]["db77_estrut"]:$this->db77_estrut);
       $this->db77_descr = ($this->db77_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db77_descr"]:$this->db77_descr);
       $this->db77_permitesintetico = ($this->db77_permitesintetico == "f"?@$GLOBALS["HTTP_POST_VARS"]["db77_permitesintetico"]:$this->db77_permitesintetico);
     }else{
       $this->db77_codestrut = ($this->db77_codestrut == ""?@$GLOBALS["HTTP_POST_VARS"]["db77_codestrut"]:$this->db77_codestrut);
     }
   }
   // funcao para inclusao
   function incluir ($db77_codestrut){ 
      $this->atualizacampos();
     if($this->db77_estrut == null ){ 
       $this->erro_sql = " Campo Estrutural nao Informado.";
       $this->erro_campo = "db77_estrut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db77_descr == null ){ 
       $this->erro_sql = " Campo Decrição nao Informado.";
       $this->erro_campo = "db77_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db77_permitesintetico == null ){ 
       $this->db77_permitesintetico = "false";
     }
     if($db77_codestrut == "" || $db77_codestrut == null ){
       $result = db_query("select nextval('rhestrutura_r77_codestrut_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestrutura_r77_codestrut_seq do campo: db77_codestrut"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db77_codestrut = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestrutura_r77_codestrut_seq");
       if(($result != false) && (pg_result($result,0,0) < $db77_codestrut)){
         $this->erro_sql = " Campo db77_codestrut maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db77_codestrut = $db77_codestrut; 
       }
     }
     if(($this->db77_codestrut == null) || ($this->db77_codestrut == "") ){ 
       $this->erro_sql = " Campo db77_codestrut nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_estrutura(
                                       db77_codestrut 
                                      ,db77_estrut 
                                      ,db77_descr 
                                      ,db77_permitesintetico 
                       )
                values (
                                $this->db77_codestrut 
                               ,'$this->db77_estrut' 
                               ,'$this->db77_descr' 
                               ,'$this->db77_permitesintetico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Estruturas ($this->db77_codestrut) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Estruturas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Estruturas ($this->db77_codestrut) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db77_codestrut;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db77_codestrut));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5691,'$this->db77_codestrut','I')");
       $resac = db_query("insert into db_acount values($acount,898,5691,'','".AddSlashes(pg_result($resaco,0,'db77_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,898,5692,'','".AddSlashes(pg_result($resaco,0,'db77_estrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,898,5693,'','".AddSlashes(pg_result($resaco,0,'db77_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,898,17958,'','".AddSlashes(pg_result($resaco,0,'db77_permitesintetico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db77_codestrut=null) { 
      $this->atualizacampos();
     $sql = " update db_estrutura set ";
     $virgula = "";
     if(trim($this->db77_codestrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db77_codestrut"])){ 
       $sql  .= $virgula." db77_codestrut = $this->db77_codestrut ";
       $virgula = ",";
       if(trim($this->db77_codestrut) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db77_codestrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db77_estrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db77_estrut"])){ 
       $sql  .= $virgula." db77_estrut = '$this->db77_estrut' ";
       $virgula = ",";
       if(trim($this->db77_estrut) == null ){ 
         $this->erro_sql = " Campo Estrutural nao Informado.";
         $this->erro_campo = "db77_estrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db77_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db77_descr"])){ 
       $sql  .= $virgula." db77_descr = '$this->db77_descr' ";
       $virgula = ",";
       if(trim($this->db77_descr) == null ){ 
         $this->erro_sql = " Campo Decrição nao Informado.";
         $this->erro_campo = "db77_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db77_permitesintetico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db77_permitesintetico"])){ 
       $sql  .= $virgula." db77_permitesintetico = '$this->db77_permitesintetico' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db77_codestrut!=null){
       $sql .= " db77_codestrut = $this->db77_codestrut";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db77_codestrut));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5691,'$this->db77_codestrut','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db77_codestrut"]) || $this->db77_codestrut != "")
           $resac = db_query("insert into db_acount values($acount,898,5691,'".AddSlashes(pg_result($resaco,$conresaco,'db77_codestrut'))."','$this->db77_codestrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db77_estrut"]) || $this->db77_estrut != "")
           $resac = db_query("insert into db_acount values($acount,898,5692,'".AddSlashes(pg_result($resaco,$conresaco,'db77_estrut'))."','$this->db77_estrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db77_descr"]) || $this->db77_descr != "")
           $resac = db_query("insert into db_acount values($acount,898,5693,'".AddSlashes(pg_result($resaco,$conresaco,'db77_descr'))."','$this->db77_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db77_permitesintetico"]) || $this->db77_permitesintetico != "")
           $resac = db_query("insert into db_acount values($acount,898,17958,'".AddSlashes(pg_result($resaco,$conresaco,'db77_permitesintetico'))."','$this->db77_permitesintetico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estruturas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db77_codestrut;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estruturas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db77_codestrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db77_codestrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db77_codestrut=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db77_codestrut));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5691,'$db77_codestrut','E')");
         $resac = db_query("insert into db_acount values($acount,898,5691,'','".AddSlashes(pg_result($resaco,$iresaco,'db77_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,898,5692,'','".AddSlashes(pg_result($resaco,$iresaco,'db77_estrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,898,5693,'','".AddSlashes(pg_result($resaco,$iresaco,'db77_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,898,17958,'','".AddSlashes(pg_result($resaco,$iresaco,'db77_permitesintetico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_estrutura
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db77_codestrut != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db77_codestrut = $db77_codestrut ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estruturas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db77_codestrut;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estruturas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db77_codestrut;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db77_codestrut;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_estrutura";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db77_codestrut=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_estrutura ";
     $sql2 = "";
     if($dbwhere==""){
       if($db77_codestrut!=null ){
         $sql2 .= " where db_estrutura.db77_codestrut = $db77_codestrut "; 
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
   function sql_query_file ( $db77_codestrut=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_estrutura ";
     $sql2 = "";
     if($dbwhere==""){
       if($db77_codestrut!=null ){
         $sql2 .= " where db_estrutura.db77_codestrut = $db77_codestrut "; 
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
<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE controleacessoalunoregistroinvalido
class cl_controleacessoalunoregistroinvalido { 
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
   var $ed304_sequencial = 0; 
   var $ed304_controleacessoalunoregistro = 0; 
   var $ed304_codigoinvalido = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed304_sequencial = int4 = Código Sequencial 
                 ed304_controleacessoalunoregistro = int4 = Código da Leitura 
                 ed304_codigoinvalido = varchar(255) = Código da Leitura Inválido 
                 ";
   //funcao construtor da classe 
   function cl_controleacessoalunoregistroinvalido() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("controleacessoalunoregistroinvalido"); 
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
       $this->ed304_sequencial = ($this->ed304_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed304_sequencial"]:$this->ed304_sequencial);
       $this->ed304_controleacessoalunoregistro = ($this->ed304_controleacessoalunoregistro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed304_controleacessoalunoregistro"]:$this->ed304_controleacessoalunoregistro);
       $this->ed304_codigoinvalido = ($this->ed304_codigoinvalido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed304_codigoinvalido"]:$this->ed304_codigoinvalido);
     }else{
       $this->ed304_sequencial = ($this->ed304_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed304_sequencial"]:$this->ed304_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed304_sequencial){ 
      $this->atualizacampos();
     if($this->ed304_controleacessoalunoregistro == null ){ 
       $this->erro_sql = " Campo Código da Leitura nao Informado.";
       $this->erro_campo = "ed304_controleacessoalunoregistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed304_codigoinvalido == null ){ 
       $this->erro_sql = " Campo Código da Leitura Inválido nao Informado.";
       $this->erro_campo = "ed304_codigoinvalido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed304_sequencial == "" || $ed304_sequencial == null ){
       $result = db_query("select nextval('controleacessoalunoregistroinvalido_ed304_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: controleacessoalunoregistroinvalido_ed304_sequencial_seq do campo: ed304_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed304_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from controleacessoalunoregistroinvalido_ed304_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed304_sequencial)){
         $this->erro_sql = " Campo ed304_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed304_sequencial = $ed304_sequencial; 
       }
     }
     if(($this->ed304_sequencial == null) || ($this->ed304_sequencial == "") ){ 
       $this->erro_sql = " Campo ed304_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into controleacessoalunoregistroinvalido(
                                       ed304_sequencial 
                                      ,ed304_controleacessoalunoregistro 
                                      ,ed304_codigoinvalido 
                       )
                values (
                                $this->ed304_sequencial 
                               ,$this->ed304_controleacessoalunoregistro 
                               ,'$this->ed304_codigoinvalido' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Leituras de acesso invalidas ($this->ed304_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Leituras de acesso invalidas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Leituras de acesso invalidas ($this->ed304_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed304_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed304_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18820,'$this->ed304_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3337,18820,'','".AddSlashes(pg_result($resaco,0,'ed304_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3337,18821,'','".AddSlashes(pg_result($resaco,0,'ed304_controleacessoalunoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3337,18822,'','".AddSlashes(pg_result($resaco,0,'ed304_codigoinvalido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed304_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update controleacessoalunoregistroinvalido set ";
     $virgula = "";
     if(trim($this->ed304_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed304_sequencial"])){ 
       $sql  .= $virgula." ed304_sequencial = $this->ed304_sequencial ";
       $virgula = ",";
       if(trim($this->ed304_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ed304_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed304_controleacessoalunoregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed304_controleacessoalunoregistro"])){ 
       $sql  .= $virgula." ed304_controleacessoalunoregistro = $this->ed304_controleacessoalunoregistro ";
       $virgula = ",";
       if(trim($this->ed304_controleacessoalunoregistro) == null ){ 
         $this->erro_sql = " Campo Código da Leitura nao Informado.";
         $this->erro_campo = "ed304_controleacessoalunoregistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed304_codigoinvalido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed304_codigoinvalido"])){ 
       $sql  .= $virgula." ed304_codigoinvalido = '$this->ed304_codigoinvalido' ";
       $virgula = ",";
       if(trim($this->ed304_codigoinvalido) == null ){ 
         $this->erro_sql = " Campo Código da Leitura Inválido nao Informado.";
         $this->erro_campo = "ed304_codigoinvalido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed304_sequencial!=null){
       $sql .= " ed304_sequencial = $this->ed304_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed304_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18820,'$this->ed304_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed304_sequencial"]) || $this->ed304_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3337,18820,'".AddSlashes(pg_result($resaco,$conresaco,'ed304_sequencial'))."','$this->ed304_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed304_controleacessoalunoregistro"]) || $this->ed304_controleacessoalunoregistro != "")
           $resac = db_query("insert into db_acount values($acount,3337,18821,'".AddSlashes(pg_result($resaco,$conresaco,'ed304_controleacessoalunoregistro'))."','$this->ed304_controleacessoalunoregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed304_codigoinvalido"]) || $this->ed304_codigoinvalido != "")
           $resac = db_query("insert into db_acount values($acount,3337,18822,'".AddSlashes(pg_result($resaco,$conresaco,'ed304_codigoinvalido'))."','$this->ed304_codigoinvalido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Leituras de acesso invalidas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed304_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Leituras de acesso invalidas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed304_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed304_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed304_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed304_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18820,'$ed304_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3337,18820,'','".AddSlashes(pg_result($resaco,$iresaco,'ed304_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3337,18821,'','".AddSlashes(pg_result($resaco,$iresaco,'ed304_controleacessoalunoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3337,18822,'','".AddSlashes(pg_result($resaco,$iresaco,'ed304_codigoinvalido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from controleacessoalunoregistroinvalido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed304_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed304_sequencial = $ed304_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Leituras de acesso invalidas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed304_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Leituras de acesso invalidas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed304_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed304_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:controleacessoalunoregistroinvalido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed304_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistroinvalido ";
     $sql .= "      inner join controleacessoalunoregistro  on  controleacessoalunoregistro.ed101_sequencial = controleacessoalunoregistroinvalido.ed304_controleacessoalunoregistro";
     $sql .= "      inner join controleacessoaluno  on  controleacessoaluno.ed100_sequencial = controleacessoalunoregistro.ed101_controleacessoaluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed304_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistroinvalido.ed304_sequencial = $ed304_sequencial "; 
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
   function sql_query_file ( $ed304_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistroinvalido ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed304_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistroinvalido.ed304_sequencial = $ed304_sequencial "; 
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
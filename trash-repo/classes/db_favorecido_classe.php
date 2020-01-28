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

//MODULO: juridico
//CLASSE DA ENTIDADE favorecido
class cl_favorecido { 
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
   var $v86_sequencial = 0; 
   var $v86_contabancaria = 0; 
   var $v86_numcgm = 0; 
   var $v86_containterna = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v86_sequencial = int8 = Sequencial da Tabela 
                 v86_contabancaria = int8 = Conta Bancária 
                 v86_numcgm = int8 = Num CGM 
                 v86_containterna = varchar(50) = Conta Interna 
                 ";
   //funcao construtor da classe 
   function cl_favorecido() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("favorecido"); 
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
       $this->v86_sequencial = ($this->v86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v86_sequencial"]:$this->v86_sequencial);
       $this->v86_contabancaria = ($this->v86_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["v86_contabancaria"]:$this->v86_contabancaria);
       $this->v86_numcgm = ($this->v86_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["v86_numcgm"]:$this->v86_numcgm);
       $this->v86_containterna = ($this->v86_containterna == ""?@$GLOBALS["HTTP_POST_VARS"]["v86_containterna"]:$this->v86_containterna);
     }else{
       $this->v86_sequencial = ($this->v86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v86_sequencial"]:$this->v86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v86_sequencial){ 
      $this->atualizacampos();
     if($this->v86_contabancaria == null ){ 
       $this->erro_sql = " Campo Conta Bancária nao Informado.";
       $this->erro_campo = "v86_contabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v86_numcgm == null ){ 
       $this->erro_sql = " Campo Num CGM nao Informado.";
       $this->erro_campo = "v86_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v86_sequencial == "" || $v86_sequencial == null ){
       $result = db_query("select nextval('favorecido_v86_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: favorecido_v86_sequencial_seq do campo: v86_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v86_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from favorecido_v86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v86_sequencial)){
         $this->erro_sql = " Campo v86_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v86_sequencial = $v86_sequencial; 
       }
     }
     if(($this->v86_sequencial == null) || ($this->v86_sequencial == "") ){ 
       $this->erro_sql = " Campo v86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into favorecido(
                                       v86_sequencial 
                                      ,v86_contabancaria 
                                      ,v86_numcgm 
                                      ,v86_containterna 
                       )
                values (
                                $this->v86_sequencial 
                               ,$this->v86_contabancaria 
                               ,$this->v86_numcgm 
                               ,'$this->v86_containterna' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "favorecido ($this->v86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "favorecido já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "favorecido ($this->v86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v86_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v86_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18243,'$this->v86_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3219,18243,'','".AddSlashes(pg_result($resaco,0,'v86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3219,18244,'','".AddSlashes(pg_result($resaco,0,'v86_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3219,18245,'','".AddSlashes(pg_result($resaco,0,'v86_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3219,18246,'','".AddSlashes(pg_result($resaco,0,'v86_containterna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v86_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update favorecido set ";
     $virgula = "";
     if(trim($this->v86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v86_sequencial"])){ 
       $sql  .= $virgula." v86_sequencial = $this->v86_sequencial ";
       $virgula = ",";
       if(trim($this->v86_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da Tabela nao Informado.";
         $this->erro_campo = "v86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v86_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v86_contabancaria"])){ 
       $sql  .= $virgula." v86_contabancaria = $this->v86_contabancaria ";
       $virgula = ",";
       if(trim($this->v86_contabancaria) == null ){ 
         $this->erro_sql = " Campo Conta Bancária nao Informado.";
         $this->erro_campo = "v86_contabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v86_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v86_numcgm"])){ 
       $sql  .= $virgula." v86_numcgm = $this->v86_numcgm ";
       $virgula = ",";
       if(trim($this->v86_numcgm) == null ){ 
         $this->erro_sql = " Campo Num CGM nao Informado.";
         $this->erro_campo = "v86_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v86_containterna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v86_containterna"])){ 
       $sql  .= $virgula." v86_containterna = '$this->v86_containterna' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($v86_sequencial!=null){
       $sql .= " v86_sequencial = $this->v86_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v86_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18243,'$this->v86_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v86_sequencial"]) || $this->v86_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3219,18243,'".AddSlashes(pg_result($resaco,$conresaco,'v86_sequencial'))."','$this->v86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v86_contabancaria"]) || $this->v86_contabancaria != "")
           $resac = db_query("insert into db_acount values($acount,3219,18244,'".AddSlashes(pg_result($resaco,$conresaco,'v86_contabancaria'))."','$this->v86_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v86_numcgm"]) || $this->v86_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,3219,18245,'".AddSlashes(pg_result($resaco,$conresaco,'v86_numcgm'))."','$this->v86_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v86_containterna"]) || $this->v86_containterna != "")
           $resac = db_query("insert into db_acount values($acount,3219,18246,'".AddSlashes(pg_result($resaco,$conresaco,'v86_containterna'))."','$this->v86_containterna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "favorecido nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "favorecido nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v86_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v86_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18243,'$v86_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3219,18243,'','".AddSlashes(pg_result($resaco,$iresaco,'v86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3219,18244,'','".AddSlashes(pg_result($resaco,$iresaco,'v86_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3219,18245,'','".AddSlashes(pg_result($resaco,$iresaco,'v86_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3219,18246,'','".AddSlashes(pg_result($resaco,$iresaco,'v86_containterna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from favorecido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v86_sequencial = $v86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "favorecido nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "favorecido nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v86_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:favorecido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from favorecido ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = favorecido.v86_numcgm";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = favorecido.v86_contabancaria";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($v86_sequencial!=null ){
         $sql2 .= " where favorecido.v86_sequencial = $v86_sequencial "; 
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
   function sql_query_file ( $v86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from favorecido ";
     $sql2 = "";
     if($dbwhere==""){
       if($v86_sequencial!=null ){
         $sql2 .= " where favorecido.v86_sequencial = $v86_sequencial "; 
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
  /**
   * SQL query modificado para pegar dados da tabela db_bancos
   * @param $v86_sequencial
   * @param $campos
   * @param $ordem
   * @param $dbwhere
   */ 
   function sql_query_dados ( $v86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= "  from favorecido ";
     $sql .= " inner join cgm           on  cgm.z01_numcgm                = favorecido.v86_numcgm";
     $sql .= " inner join contabancaria on  contabancaria.db83_sequencial = favorecido.v86_contabancaria";
     $sql .= " inner join bancoagencia  on  bancoagencia.db89_sequencial  = contabancaria.db83_bancoagencia";
     $sql .= " inner join db_bancos     on  db_bancos.db90_codban         = bancoagencia.db89_db_bancos";
     
     $sql2 = "";
     if($dbwhere==""){
       if($v86_sequencial!=null ){
         $sql2 .= " where favorecido.v86_sequencial = $v86_sequencial "; 
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
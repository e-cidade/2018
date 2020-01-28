<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_valorreferencia
class cl_lab_valorreferencia { 
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
   var $la27_i_codigo = 0; 
   var $la27_i_unidade = 0; 
   var $la27_i_atributo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la27_i_codigo = int4 = Código 
                 la27_i_unidade = int4 = Unidade de Medida 
                 la27_i_atributo = int4 = Atributo 
                 ";
   //funcao construtor da classe 
   function cl_lab_valorreferencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_valorreferencia"); 
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
       $this->la27_i_codigo = ($this->la27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la27_i_codigo"]:$this->la27_i_codigo);
       $this->la27_i_unidade = ($this->la27_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["la27_i_unidade"]:$this->la27_i_unidade);
       $this->la27_i_atributo = ($this->la27_i_atributo == ""?@$GLOBALS["HTTP_POST_VARS"]["la27_i_atributo"]:$this->la27_i_atributo);
     }else{
       $this->la27_i_codigo = ($this->la27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la27_i_codigo"]:$this->la27_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la27_i_codigo){ 
      $this->atualizacampos();
     if($this->la27_i_unidade == null ){ 
       $this->la27_i_unidade = "null";
     }
     if($this->la27_i_atributo == null ){ 
       $this->erro_sql = " Campo Atributo nao Informado.";
       $this->erro_campo = "la27_i_atributo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la27_i_codigo == "" || $la27_i_codigo == null ){
       $result = db_query("select nextval('lab_valorreferencia_la27_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_valorreferencia_la27_i_codigo_seq do campo: la27_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la27_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_valorreferencia_la27_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la27_i_codigo)){
         $this->erro_sql = " Campo la27_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la27_i_codigo = $la27_i_codigo; 
       }
     }
     if(($this->la27_i_codigo == null) || ($this->la27_i_codigo == "") ){ 
       $this->erro_sql = " Campo la27_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_valorreferencia(
                                       la27_i_codigo 
                                      ,la27_i_unidade 
                                      ,la27_i_atributo 
                       )
                values (
                                $this->la27_i_codigo 
                               ,$this->la27_i_unidade 
                               ,$this->la27_i_atributo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valor Referência ($this->la27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valor Referência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valor Referência ($this->la27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la27_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la27_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16498,'$this->la27_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2901,16498,'','".AddSlashes(pg_result($resaco,0,'la27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2901,16499,'','".AddSlashes(pg_result($resaco,0,'la27_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2901,16560,'','".AddSlashes(pg_result($resaco,0,'la27_i_atributo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la27_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_valorreferencia set ";
     $virgula = "";
     if(trim($this->la27_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la27_i_codigo"])){ 
       $sql  .= $virgula." la27_i_codigo = $this->la27_i_codigo ";
       $virgula = ",";
       if(trim($this->la27_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la27_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la27_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la27_i_unidade"])){ 
        if(trim($this->la27_i_unidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la27_i_unidade"])){ 
           $this->la27_i_unidade = "null" ; 
        } 
       $sql  .= $virgula." la27_i_unidade = $this->la27_i_unidade ";
       $virgula = ",";
     }
     if(trim($this->la27_i_atributo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la27_i_atributo"])){ 
       $sql  .= $virgula." la27_i_atributo = $this->la27_i_atributo ";
       $virgula = ",";
       if(trim($this->la27_i_atributo) == null ){ 
         $this->erro_sql = " Campo Atributo nao Informado.";
         $this->erro_campo = "la27_i_atributo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la27_i_codigo!=null){
       $sql .= " la27_i_codigo = $this->la27_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la27_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16498,'$this->la27_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la27_i_codigo"]) || $this->la27_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2901,16498,'".AddSlashes(pg_result($resaco,$conresaco,'la27_i_codigo'))."','$this->la27_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la27_i_unidade"]) || $this->la27_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2901,16499,'".AddSlashes(pg_result($resaco,$conresaco,'la27_i_unidade'))."','$this->la27_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la27_i_atributo"]) || $this->la27_i_atributo != "")
           $resac = db_query("insert into db_acount values($acount,2901,16560,'".AddSlashes(pg_result($resaco,$conresaco,'la27_i_atributo'))."','$this->la27_i_atributo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valor Referência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valor Referência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la27_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la27_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16498,'$la27_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2901,16498,'','".AddSlashes(pg_result($resaco,$iresaco,'la27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2901,16499,'','".AddSlashes(pg_result($resaco,$iresaco,'la27_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2901,16560,'','".AddSlashes(pg_result($resaco,$iresaco,'la27_i_atributo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_valorreferencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la27_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la27_i_codigo = $la27_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valor Referência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valor Referência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la27_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_valorreferencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_valorreferencia ";
     $sql .= "      left  join lab_undmedida  on  lab_undmedida.la13_i_codigo = lab_valorreferencia.la27_i_unidade";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = lab_valorreferencia.la27_i_atributo";
     $sql2 = "";
     if($dbwhere==""){
       if($la27_i_codigo!=null ){
         $sql2 .= " where lab_valorreferencia.la27_i_codigo = $la27_i_codigo "; 
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
   function sql_query_file ( $la27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_valorreferencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($la27_i_codigo!=null ){
         $sql2 .= " where lab_valorreferencia.la27_i_codigo = $la27_i_codigo "; 
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
   function sql_query_tipos ( $la27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_valorreferencia ";
     $sql .= "      left join lab_undmedida  on  lab_undmedida.la13_i_codigo = lab_valorreferencia.la27_i_unidade";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = lab_valorreferencia.la27_i_atributo";
     $sql .= "      left join lab_tiporeferenciaalnumerico  on lab_tiporeferenciaalnumerico.la30_i_valorref = lab_valorreferencia.la27_i_codigo";
     $sql .= "      left join lab_tiporeferenciaalfa  on lab_tiporeferenciaalfa.la29_i_valorref = lab_valorreferencia.la27_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($la27_i_codigo!=null ){
         $sql2 .= " where lab_valorreferencia.la27_i_codigo = $la27_i_codigo "; 
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
  function sql_query_referencia_numerica($iCodigoAtributo, $sWhere) {

    $sSql  = "select distinct la30_i_codigo, 2 as tipo";
    $sSql .= " from lab_valorreferencia ";
    $sSql .= "      left join lab_undmedida  on  lab_undmedida.la13_i_codigo = lab_valorreferencia.la27_i_unidade";
    $sSql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo   = lab_valorreferencia.la27_i_atributo";
    $sSql .= "      inner join lab_tiporeferenciaalnumerico  on lab_tiporeferenciaalnumerico.la30_i_valorref = lab_valorreferencia.la27_i_codigo";
    $sSql .= "      left  join tiporeferenciaalnumericofaixaidade on la59_tiporeferencialnumerico = la30_i_codigo";
    $sSql .= "      left  join tiporeferenciaalnumericosexo       on la60_tiporeferencialnumerico = la30_i_codigo";
    $sSql .= " where la27_i_atributo = {$iCodigoAtributo} {$sWhere}";
    return $sSql;
  }
}
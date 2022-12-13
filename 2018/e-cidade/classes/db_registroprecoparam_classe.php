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

//MODULO: compras
//CLASSE DA ENTIDADE registroprecoparam
class cl_registroprecoparam { 
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
   var $pc08_instit = 0; 
   var $pc08_incluiritemestimativa = 'f'; 
   var $pc08_alteraabertura = 'f'; 
   var $pc08_percentuquantmax = 0; 
   var $pc08_ordemitensestimativa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc08_instit = int4 = Código da Instituição 
                 pc08_incluiritemestimativa = bool = Permite Incluir Itens na Estimativa 
                 pc08_alteraabertura = bool = Permite a Alteração da Abertura 
                 pc08_percentuquantmax = float8 = Percentual para quantidade máxima 
                 pc08_ordemitensestimativa = int4 = Ordenação dos Itens na Estimativa 
                 ";
   //funcao construtor da classe 
   function cl_registroprecoparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecoparam"); 
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
       $this->pc08_instit = ($this->pc08_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["pc08_instit"]:$this->pc08_instit);
       $this->pc08_incluiritemestimativa = ($this->pc08_incluiritemestimativa == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc08_incluiritemestimativa"]:$this->pc08_incluiritemestimativa);
       $this->pc08_alteraabertura = ($this->pc08_alteraabertura == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc08_alteraabertura"]:$this->pc08_alteraabertura);
       $this->pc08_percentuquantmax = ($this->pc08_percentuquantmax == ""?@$GLOBALS["HTTP_POST_VARS"]["pc08_percentuquantmax"]:$this->pc08_percentuquantmax);
       $this->pc08_ordemitensestimativa = ($this->pc08_ordemitensestimativa == ""?@$GLOBALS["HTTP_POST_VARS"]["pc08_ordemitensestimativa"]:$this->pc08_ordemitensestimativa);
     }else{
       $this->pc08_instit = ($this->pc08_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["pc08_instit"]:$this->pc08_instit);
     }
   }
   // funcao para inclusao
   function incluir ($pc08_instit){ 
      $this->atualizacampos();
     if($this->pc08_incluiritemestimativa == null ){ 
       $this->pc08_incluiritemestimativa = "false";
     }
     if($this->pc08_alteraabertura == null ){ 
       $this->pc08_alteraabertura = "false";
     }
     if($this->pc08_percentuquantmax == null ){ 
       $this->pc08_percentuquantmax = "0";
     }
     if($this->pc08_ordemitensestimativa == null ){ 
       $this->pc08_ordemitensestimativa = "1";
     }
       $this->pc08_instit = $pc08_instit; 
     if(($this->pc08_instit == null) || ($this->pc08_instit == "") ){ 
       $this->erro_sql = " Campo pc08_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecoparam(
                                       pc08_instit 
                                      ,pc08_incluiritemestimativa 
                                      ,pc08_alteraabertura 
                                      ,pc08_percentuquantmax 
                                      ,pc08_ordemitensestimativa 
                       )
                values (
                                $this->pc08_instit 
                               ,'$this->pc08_incluiritemestimativa' 
                               ,'$this->pc08_alteraabertura' 
                               ,$this->pc08_percentuquantmax 
                               ,$this->pc08_ordemitensestimativa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros do registro de Preço ($this->pc08_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros do registro de Preço já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros do registro de Preço ($this->pc08_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc08_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc08_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17496,'$this->pc08_instit','I')");
       $resac = db_query("insert into db_acount values($acount,3092,17496,'','".AddSlashes(pg_result($resaco,0,'pc08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3092,17497,'','".AddSlashes(pg_result($resaco,0,'pc08_incluiritemestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3092,17499,'','".AddSlashes(pg_result($resaco,0,'pc08_alteraabertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3092,17500,'','".AddSlashes(pg_result($resaco,0,'pc08_percentuquantmax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3092,17501,'','".AddSlashes(pg_result($resaco,0,'pc08_ordemitensestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc08_instit=null) { 
      $this->atualizacampos();
     $sql = " update registroprecoparam set ";
     $virgula = "";
     if(trim($this->pc08_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc08_instit"])){ 
       $sql  .= $virgula." pc08_instit = $this->pc08_instit ";
       $virgula = ",";
       if(trim($this->pc08_instit) == null ){ 
         $this->erro_sql = " Campo Código da Instituição nao Informado.";
         $this->erro_campo = "pc08_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc08_incluiritemestimativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc08_incluiritemestimativa"])){ 
       $sql  .= $virgula." pc08_incluiritemestimativa = '$this->pc08_incluiritemestimativa' ";
       $virgula = ",";
     }
     if(trim($this->pc08_alteraabertura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc08_alteraabertura"])){ 
       $sql  .= $virgula." pc08_alteraabertura = '$this->pc08_alteraabertura' ";
       $virgula = ",";
     }
     if(trim($this->pc08_percentuquantmax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc08_percentuquantmax"])){ 
        if(trim($this->pc08_percentuquantmax)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc08_percentuquantmax"])){ 
           $this->pc08_percentuquantmax = "0" ; 
        } 
       $sql  .= $virgula." pc08_percentuquantmax = $this->pc08_percentuquantmax ";
       $virgula = ",";
     }
     if(trim($this->pc08_ordemitensestimativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc08_ordemitensestimativa"])){ 
        if(trim($this->pc08_ordemitensestimativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc08_ordemitensestimativa"])){ 
           $this->pc08_ordemitensestimativa = "0" ; 
        } 
       $sql  .= $virgula." pc08_ordemitensestimativa = $this->pc08_ordemitensestimativa ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc08_instit!=null){
       $sql .= " pc08_instit = $this->pc08_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc08_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17496,'$this->pc08_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc08_instit"]) || $this->pc08_instit != "")
           $resac = db_query("insert into db_acount values($acount,3092,17496,'".AddSlashes(pg_result($resaco,$conresaco,'pc08_instit'))."','$this->pc08_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc08_incluiritemestimativa"]) || $this->pc08_incluiritemestimativa != "")
           $resac = db_query("insert into db_acount values($acount,3092,17497,'".AddSlashes(pg_result($resaco,$conresaco,'pc08_incluiritemestimativa'))."','$this->pc08_incluiritemestimativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc08_alteraabertura"]) || $this->pc08_alteraabertura != "")
           $resac = db_query("insert into db_acount values($acount,3092,17499,'".AddSlashes(pg_result($resaco,$conresaco,'pc08_alteraabertura'))."','$this->pc08_alteraabertura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc08_percentuquantmax"]) || $this->pc08_percentuquantmax != "")
           $resac = db_query("insert into db_acount values($acount,3092,17500,'".AddSlashes(pg_result($resaco,$conresaco,'pc08_percentuquantmax'))."','$this->pc08_percentuquantmax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc08_ordemitensestimativa"]) || $this->pc08_ordemitensestimativa != "")
           $resac = db_query("insert into db_acount values($acount,3092,17501,'".AddSlashes(pg_result($resaco,$conresaco,'pc08_ordemitensestimativa'))."','$this->pc08_ordemitensestimativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do registro de Preço nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc08_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do registro de Preço nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc08_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc08_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc08_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc08_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17496,'$pc08_instit','E')");
         $resac = db_query("insert into db_acount values($acount,3092,17496,'','".AddSlashes(pg_result($resaco,$iresaco,'pc08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3092,17497,'','".AddSlashes(pg_result($resaco,$iresaco,'pc08_incluiritemestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3092,17499,'','".AddSlashes(pg_result($resaco,$iresaco,'pc08_alteraabertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3092,17500,'','".AddSlashes(pg_result($resaco,$iresaco,'pc08_percentuquantmax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3092,17501,'','".AddSlashes(pg_result($resaco,$iresaco,'pc08_ordemitensestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecoparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc08_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc08_instit = $pc08_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do registro de Preço nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc08_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do registro de Preço nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc08_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc08_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecoparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc08_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecoparam ";
     $sql .= "      inner join db_config  on  db_config.codigo = registroprecoparam.pc08_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($pc08_instit!=null ){
         $sql2 .= " where registroprecoparam.pc08_instit = $pc08_instit "; 
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
   function sql_query_file ( $pc08_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecoparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc08_instit!=null ){
         $sql2 .= " where registroprecoparam.pc08_instit = $pc08_instit "; 
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
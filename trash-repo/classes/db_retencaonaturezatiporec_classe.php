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
//CLASSE DA ENTIDADE retencaonaturezatiporec
class cl_retencaonaturezatiporec { 
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
   var $e31_sequencial = 0; 
   var $e31_retencaotiporec = 0; 
   var $e31_retencaonatureza = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e31_sequencial = int4 = Código Sequencial 
                 e31_retencaotiporec = int4 = Código da Retenção 
                 e31_retencaonatureza = int4 = Código  do IRRF 
                 ";
   //funcao construtor da classe 
   function cl_retencaonaturezatiporec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retencaonaturezatiporec"); 
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
       $this->e31_sequencial = ($this->e31_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e31_sequencial"]:$this->e31_sequencial);
       $this->e31_retencaotiporec = ($this->e31_retencaotiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["e31_retencaotiporec"]:$this->e31_retencaotiporec);
       $this->e31_retencaonatureza = ($this->e31_retencaonatureza == ""?@$GLOBALS["HTTP_POST_VARS"]["e31_retencaonatureza"]:$this->e31_retencaonatureza);
     }else{
       $this->e31_sequencial = ($this->e31_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e31_sequencial"]:$this->e31_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e31_sequencial){ 
      $this->atualizacampos();
     if($this->e31_retencaotiporec == null ){ 
       $this->erro_sql = " Campo Código da Retenção nao Informado.";
       $this->erro_campo = "e31_retencaotiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e31_retencaonatureza == null ){ 
       $this->erro_sql = " Campo Código  do IRRF nao Informado.";
       $this->erro_campo = "e31_retencaonatureza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e31_sequencial == "" || $e31_sequencial == null ){
       $result = db_query("select nextval('retencaonaturezatiporec_e31_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: retencaonaturezatiporec_e31_sequencial_seq do campo: e31_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e31_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from retencaonaturezatiporec_e31_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e31_sequencial)){
         $this->erro_sql = " Campo e31_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e31_sequencial = $e31_sequencial; 
       }
     }
     if(($this->e31_sequencial == null) || ($this->e31_sequencial == "") ){ 
       $this->erro_sql = " Campo e31_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retencaonaturezatiporec(
                                       e31_sequencial 
                                      ,e31_retencaotiporec 
                                      ,e31_retencaonatureza 
                       )
                values (
                                $this->e31_sequencial 
                               ,$this->e31_retencaotiporec 
                               ,$this->e31_retencaonatureza 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Natureza da rentecao ($this->e31_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Natureza da rentecao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Natureza da rentecao ($this->e31_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e31_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e31_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12165,'$this->e31_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2113,12165,'','".AddSlashes(pg_result($resaco,0,'e31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2113,12166,'','".AddSlashes(pg_result($resaco,0,'e31_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2113,12167,'','".AddSlashes(pg_result($resaco,0,'e31_retencaonatureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e31_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update retencaonaturezatiporec set ";
     $virgula = "";
     if(trim($this->e31_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e31_sequencial"])){ 
       $sql  .= $virgula." e31_sequencial = $this->e31_sequencial ";
       $virgula = ",";
       if(trim($this->e31_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e31_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e31_retencaotiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e31_retencaotiporec"])){ 
       $sql  .= $virgula." e31_retencaotiporec = $this->e31_retencaotiporec ";
       $virgula = ",";
       if(trim($this->e31_retencaotiporec) == null ){ 
         $this->erro_sql = " Campo Código da Retenção nao Informado.";
         $this->erro_campo = "e31_retencaotiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e31_retencaonatureza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e31_retencaonatureza"])){ 
       $sql  .= $virgula." e31_retencaonatureza = $this->e31_retencaonatureza ";
       $virgula = ",";
       if(trim($this->e31_retencaonatureza) == null ){ 
         $this->erro_sql = " Campo Código  do IRRF nao Informado.";
         $this->erro_campo = "e31_retencaonatureza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e31_sequencial!=null){
       $sql .= " e31_sequencial = $this->e31_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e31_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12165,'$this->e31_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e31_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2113,12165,'".AddSlashes(pg_result($resaco,$conresaco,'e31_sequencial'))."','$this->e31_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e31_retencaotiporec"]))
           $resac = db_query("insert into db_acount values($acount,2113,12166,'".AddSlashes(pg_result($resaco,$conresaco,'e31_retencaotiporec'))."','$this->e31_retencaotiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e31_retencaonatureza"]))
           $resac = db_query("insert into db_acount values($acount,2113,12167,'".AddSlashes(pg_result($resaco,$conresaco,'e31_retencaonatureza'))."','$this->e31_retencaonatureza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Natureza da rentecao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Natureza da rentecao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e31_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e31_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12165,'$e31_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2113,12165,'','".AddSlashes(pg_result($resaco,$iresaco,'e31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2113,12166,'','".AddSlashes(pg_result($resaco,$iresaco,'e31_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2113,12167,'','".AddSlashes(pg_result($resaco,$iresaco,'e31_retencaonatureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from retencaonaturezatiporec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e31_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e31_sequencial = $e31_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Natureza da rentecao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Natureza da rentecao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e31_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:retencaonaturezatiporec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e31_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaonaturezatiporec ";
     $sql .= "      inner join retencaonatureza  on  retencaonatureza.e30_sequencial = retencaonaturezatiporec.e31_retencaonatureza";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaonaturezatiporec.e31_retencaotiporec";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql2 = "";
     if($dbwhere==""){
       if($e31_sequencial!=null ){
         $sql2 .= " where retencaonaturezatiporec.e31_sequencial = $e31_sequencial "; 
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
   function sql_query_file ( $e31_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaonaturezatiporec ";
     $sql2 = "";
     if($dbwhere==""){
       if($e31_sequencial!=null ){
         $sql2 .= " where retencaonaturezatiporec.e31_sequencial = $e31_sequencial "; 
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
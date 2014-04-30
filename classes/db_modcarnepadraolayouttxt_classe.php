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

//MODULO: Caixa
//CLASSE DA ENTIDADE modcarnepadraolayouttxt
class cl_modcarnepadraolayouttxt { 
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
   var $m02_sequencial = 0; 
   var $m02_db_layouttxt = 0; 
   var $m02_modcarnepadrao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m02_sequencial = int4 = Sequencial 
                 m02_db_layouttxt = int4 = Layout TXT 
                 m02_modcarnepadrao = int4 = Modelo Padrão 
                 ";
   //funcao construtor da classe 
   function cl_modcarnepadraolayouttxt() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("modcarnepadraolayouttxt"); 
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
       $this->m02_sequencial = ($this->m02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m02_sequencial"]:$this->m02_sequencial);
       $this->m02_db_layouttxt = ($this->m02_db_layouttxt == ""?@$GLOBALS["HTTP_POST_VARS"]["m02_db_layouttxt"]:$this->m02_db_layouttxt);
       $this->m02_modcarnepadrao = ($this->m02_modcarnepadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["m02_modcarnepadrao"]:$this->m02_modcarnepadrao);
     }else{
       $this->m02_sequencial = ($this->m02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m02_sequencial"]:$this->m02_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m02_sequencial){ 
      $this->atualizacampos();
     if($this->m02_db_layouttxt == null ){ 
       $this->erro_sql = " Campo Layout TXT nao Informado.";
       $this->erro_campo = "m02_db_layouttxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m02_modcarnepadrao == null ){ 
       $this->erro_sql = " Campo Modelo Padrão nao Informado.";
       $this->erro_campo = "m02_modcarnepadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m02_sequencial == "" || $m02_sequencial == null ){
       $result = db_query("select nextval('modcarnepadraolayouttxt_m02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: modcarnepadraolayouttxt_m02_sequencial_seq do campo: m02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from modcarnepadraolayouttxt_m02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m02_sequencial)){
         $this->erro_sql = " Campo m02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m02_sequencial = $m02_sequencial; 
       }
     }
     if(($this->m02_sequencial == null) || ($this->m02_sequencial == "") ){ 
       $this->erro_sql = " Campo m02_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into modcarnepadraolayouttxt(
                                       m02_sequencial 
                                      ,m02_db_layouttxt 
                                      ,m02_modcarnepadrao 
                       )
                values (
                                $this->m02_sequencial 
                               ,$this->m02_db_layouttxt 
                               ,$this->m02_modcarnepadrao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "modcarnepadraolayouttxt ($this->m02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "modcarnepadraolayouttxt já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "modcarnepadraolayouttxt ($this->m02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m02_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12613,'$this->m02_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2202,12613,'','".AddSlashes(pg_result($resaco,0,'m02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2202,12614,'','".AddSlashes(pg_result($resaco,0,'m02_db_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2202,12615,'','".AddSlashes(pg_result($resaco,0,'m02_modcarnepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m02_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update modcarnepadraolayouttxt set ";
     $virgula = "";
     if(trim($this->m02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m02_sequencial"])){ 
       $sql  .= $virgula." m02_sequencial = $this->m02_sequencial ";
       $virgula = ",";
       if(trim($this->m02_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m02_db_layouttxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m02_db_layouttxt"])){ 
       $sql  .= $virgula." m02_db_layouttxt = $this->m02_db_layouttxt ";
       $virgula = ",";
       if(trim($this->m02_db_layouttxt) == null ){ 
         $this->erro_sql = " Campo Layout TXT nao Informado.";
         $this->erro_campo = "m02_db_layouttxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m02_modcarnepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m02_modcarnepadrao"])){ 
       $sql  .= $virgula." m02_modcarnepadrao = $this->m02_modcarnepadrao ";
       $virgula = ",";
       if(trim($this->m02_modcarnepadrao) == null ){ 
         $this->erro_sql = " Campo Modelo Padrão nao Informado.";
         $this->erro_campo = "m02_modcarnepadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m02_sequencial!=null){
       $sql .= " m02_sequencial = $this->m02_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m02_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12613,'$this->m02_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m02_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2202,12613,'".AddSlashes(pg_result($resaco,$conresaco,'m02_sequencial'))."','$this->m02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m02_db_layouttxt"]))
           $resac = db_query("insert into db_acount values($acount,2202,12614,'".AddSlashes(pg_result($resaco,$conresaco,'m02_db_layouttxt'))."','$this->m02_db_layouttxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m02_modcarnepadrao"]))
           $resac = db_query("insert into db_acount values($acount,2202,12615,'".AddSlashes(pg_result($resaco,$conresaco,'m02_modcarnepadrao'))."','$this->m02_modcarnepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "modcarnepadraolayouttxt nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "modcarnepadraolayouttxt nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m02_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m02_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12613,'$m02_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2202,12613,'','".AddSlashes(pg_result($resaco,$iresaco,'m02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2202,12614,'','".AddSlashes(pg_result($resaco,$iresaco,'m02_db_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2202,12615,'','".AddSlashes(pg_result($resaco,$iresaco,'m02_modcarnepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from modcarnepadraolayouttxt
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m02_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m02_sequencial = $m02_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "modcarnepadraolayouttxt nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "modcarnepadraolayouttxt nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:modcarnepadraolayouttxt";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadraolayouttxt ";
     $sql .= "      inner join modcarnepadrao  on  modcarnepadrao.k48_sequencial = modcarnepadraolayouttxt.m02_modcarnepadrao";
     $sql .= "      inner join db_layouttxt  on  db_layouttxt.db50_codigo = modcarnepadraolayouttxt.m02_db_layouttxt";
     $sql .= "      inner join db_config  on  db_config.codigo = modcarnepadrao.k48_instit";
     $sql .= "      inner join cadtipomod  on  cadtipomod.k46_sequencial = modcarnepadrao.k48_cadtipomod";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = modcarnepadrao.k48_cadconvenio";
     $sql .= "      inner join db_layouttxtgrupo  on  db_layouttxtgrupo.db56_sequencial = db_layouttxt.db50_layouttxtgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($m02_sequencial!=null ){
         $sql2 .= " where modcarnepadraolayouttxt.m02_sequencial = $m02_sequencial "; 
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
   function sql_query_file ( $m02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadraolayouttxt ";
     $sql2 = "";
     if($dbwhere==""){
       if($m02_sequencial!=null ){
         $sql2 .= " where modcarnepadraolayouttxt.m02_sequencial = $m02_sequencial "; 
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
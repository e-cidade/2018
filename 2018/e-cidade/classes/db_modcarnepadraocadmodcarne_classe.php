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
//CLASSE DA ENTIDADE modcarnepadraocadmodcarne
class cl_modcarnepadraocadmodcarne { 
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
   var $m01_sequencial = 0; 
   var $m01_cadmodcarne = 0; 
   var $m01_modcarnepadrao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m01_sequencial = int4 = Sequencial 
                 m01_cadmodcarne = int4 = Código do Modelo 
                 m01_modcarnepadrao = int4 = Modelo Padrão 
                 ";
   //funcao construtor da classe 
   function cl_modcarnepadraocadmodcarne() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("modcarnepadraocadmodcarne"); 
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
       $this->m01_sequencial = ($this->m01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m01_sequencial"]:$this->m01_sequencial);
       $this->m01_cadmodcarne = ($this->m01_cadmodcarne == ""?@$GLOBALS["HTTP_POST_VARS"]["m01_cadmodcarne"]:$this->m01_cadmodcarne);
       $this->m01_modcarnepadrao = ($this->m01_modcarnepadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["m01_modcarnepadrao"]:$this->m01_modcarnepadrao);
     }else{
       $this->m01_sequencial = ($this->m01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m01_sequencial"]:$this->m01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m01_sequencial){ 
      $this->atualizacampos();
     if($this->m01_cadmodcarne == null ){ 
       $this->erro_sql = " Campo Código do Modelo nao Informado.";
       $this->erro_campo = "m01_cadmodcarne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m01_modcarnepadrao == null ){ 
       $this->erro_sql = " Campo Modelo Padrão nao Informado.";
       $this->erro_campo = "m01_modcarnepadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m01_sequencial == "" || $m01_sequencial == null ){
       $result = db_query("select nextval('modcarnepadraocadmodcarne_m01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: modcarnepadraocadmodcarne_m01_sequencial_seq do campo: m01_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from modcarnepadraocadmodcarne_m01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m01_sequencial)){
         $this->erro_sql = " Campo m01_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m01_sequencial = $m01_sequencial; 
       }
     }
     if(($this->m01_sequencial == null) || ($this->m01_sequencial == "") ){ 
       $this->erro_sql = " Campo m01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into modcarnepadraocadmodcarne(
                                       m01_sequencial 
                                      ,m01_cadmodcarne 
                                      ,m01_modcarnepadrao 
                       )
                values (
                                $this->m01_sequencial 
                               ,$this->m01_cadmodcarne 
                               ,$this->m01_modcarnepadrao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "modcarnepadraocadmodcarne ($this->m01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "modcarnepadraocadmodcarne já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "modcarnepadraocadmodcarne ($this->m01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m01_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m01_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12616,'$this->m01_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2203,12616,'','".AddSlashes(pg_result($resaco,0,'m01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2203,12617,'','".AddSlashes(pg_result($resaco,0,'m01_cadmodcarne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2203,12618,'','".AddSlashes(pg_result($resaco,0,'m01_modcarnepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update modcarnepadraocadmodcarne set ";
     $virgula = "";
     if(trim($this->m01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m01_sequencial"])){ 
       $sql  .= $virgula." m01_sequencial = $this->m01_sequencial ";
       $virgula = ",";
       if(trim($this->m01_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m01_cadmodcarne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m01_cadmodcarne"])){ 
       $sql  .= $virgula." m01_cadmodcarne = $this->m01_cadmodcarne ";
       $virgula = ",";
       if(trim($this->m01_cadmodcarne) == null ){ 
         $this->erro_sql = " Campo Código do Modelo nao Informado.";
         $this->erro_campo = "m01_cadmodcarne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m01_modcarnepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m01_modcarnepadrao"])){ 
       $sql  .= $virgula." m01_modcarnepadrao = $this->m01_modcarnepadrao ";
       $virgula = ",";
       if(trim($this->m01_modcarnepadrao) == null ){ 
         $this->erro_sql = " Campo Modelo Padrão nao Informado.";
         $this->erro_campo = "m01_modcarnepadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m01_sequencial!=null){
       $sql .= " m01_sequencial = $this->m01_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m01_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12616,'$this->m01_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m01_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2203,12616,'".AddSlashes(pg_result($resaco,$conresaco,'m01_sequencial'))."','$this->m01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m01_cadmodcarne"]))
           $resac = db_query("insert into db_acount values($acount,2203,12617,'".AddSlashes(pg_result($resaco,$conresaco,'m01_cadmodcarne'))."','$this->m01_cadmodcarne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m01_modcarnepadrao"]))
           $resac = db_query("insert into db_acount values($acount,2203,12618,'".AddSlashes(pg_result($resaco,$conresaco,'m01_modcarnepadrao'))."','$this->m01_modcarnepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "modcarnepadraocadmodcarne nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "modcarnepadraocadmodcarne nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m01_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m01_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12616,'$m01_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2203,12616,'','".AddSlashes(pg_result($resaco,$iresaco,'m01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2203,12617,'','".AddSlashes(pg_result($resaco,$iresaco,'m01_cadmodcarne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2203,12618,'','".AddSlashes(pg_result($resaco,$iresaco,'m01_modcarnepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from modcarnepadraocadmodcarne
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m01_sequencial = $m01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "modcarnepadraocadmodcarne nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "modcarnepadraocadmodcarne nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m01_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:modcarnepadraocadmodcarne";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadraocadmodcarne ";
     $sql .= "      inner join cadmodcarne  on  cadmodcarne.k47_sequencial = modcarnepadraocadmodcarne.m01_cadmodcarne";
     $sql .= "      inner join modcarnepadrao  on  modcarnepadrao.k48_sequencial = modcarnepadraocadmodcarne.m01_modcarnepadrao";
     $sql .= "      inner join db_config  on  db_config.codigo = modcarnepadrao.k48_instit";
     $sql .= "      inner join cadtipomod  on  cadtipomod.k46_sequencial = modcarnepadrao.k48_cadtipomod";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = modcarnepadrao.k48_cadconvenio";
     $sql2 = "";
     if($dbwhere==""){
       if($m01_sequencial!=null ){
         $sql2 .= " where modcarnepadraocadmodcarne.m01_sequencial = $m01_sequencial "; 
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
   function sql_query_file ( $m01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadraocadmodcarne ";
     $sql2 = "";
     if($dbwhere==""){
       if($m01_sequencial!=null ){
         $sql2 .= " where modcarnepadraocadmodcarne.m01_sequencial = $m01_sequencial "; 
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
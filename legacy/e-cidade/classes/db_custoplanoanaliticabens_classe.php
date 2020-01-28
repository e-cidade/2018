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

//MODULO: custos
//CLASSE DA ENTIDADE custoplanoanaliticabens
class cl_custoplanoanaliticabens { 
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
   var $cc05_sequencial = 0; 
   var $cc05_bens = 0; 
   var $cc05_custoplanoanalitica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc05_sequencial = int4 = Sequêncial 
                 cc05_bens = int4 = Bens 
                 cc05_custoplanoanalitica = int4 = Custo plano analítico 
                 ";
   //funcao construtor da classe 
   function cl_custoplanoanaliticabens() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoplanoanaliticabens"); 
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
       $this->cc05_sequencial = ($this->cc05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc05_sequencial"]:$this->cc05_sequencial);
       $this->cc05_bens = ($this->cc05_bens == ""?@$GLOBALS["HTTP_POST_VARS"]["cc05_bens"]:$this->cc05_bens);
       $this->cc05_custoplanoanalitica = ($this->cc05_custoplanoanalitica == ""?@$GLOBALS["HTTP_POST_VARS"]["cc05_custoplanoanalitica"]:$this->cc05_custoplanoanalitica);
     }else{
       $this->cc05_sequencial = ($this->cc05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc05_sequencial"]:$this->cc05_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc05_sequencial){ 
      $this->atualizacampos();
     if($this->cc05_bens == null ){ 
       $this->erro_sql = " Campo Bens nao Informado.";
       $this->erro_campo = "cc05_bens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc05_custoplanoanalitica == null ){ 
       $this->erro_sql = " Campo Custo plano analítico nao Informado.";
       $this->erro_campo = "cc05_custoplanoanalitica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc05_sequencial == "" || $cc05_sequencial == null ){
       $result = db_query("select nextval('custoplanoanaliticabens_cc05_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoplanoanaliticabens_cc05_sequencial_seq do campo: cc05_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc05_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoplanoanaliticabens_cc05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc05_sequencial)){
         $this->erro_sql = " Campo cc05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc05_sequencial = $cc05_sequencial; 
       }
     }
     if(($this->cc05_sequencial == null) || ($this->cc05_sequencial == "") ){ 
       $this->erro_sql = " Campo cc05_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoplanoanaliticabens(
                                       cc05_sequencial 
                                      ,cc05_bens 
                                      ,cc05_custoplanoanalitica 
                       )
                values (
                                $this->cc05_sequencial 
                               ,$this->cc05_bens 
                               ,$this->cc05_custoplanoanalitica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo plano analítico dos bens ($this->cc05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo plano analítico dos bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo plano analítico dos bens ($this->cc05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc05_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12574,'$this->cc05_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2195,12574,'','".AddSlashes(pg_result($resaco,0,'cc05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2195,12575,'','".AddSlashes(pg_result($resaco,0,'cc05_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2195,12576,'','".AddSlashes(pg_result($resaco,0,'cc05_custoplanoanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc05_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoplanoanaliticabens set ";
     $virgula = "";
     if(trim($this->cc05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc05_sequencial"])){ 
       $sql  .= $virgula." cc05_sequencial = $this->cc05_sequencial ";
       $virgula = ",";
       if(trim($this->cc05_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequêncial nao Informado.";
         $this->erro_campo = "cc05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc05_bens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc05_bens"])){ 
       $sql  .= $virgula." cc05_bens = $this->cc05_bens ";
       $virgula = ",";
       if(trim($this->cc05_bens) == null ){ 
         $this->erro_sql = " Campo Bens nao Informado.";
         $this->erro_campo = "cc05_bens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc05_custoplanoanalitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc05_custoplanoanalitica"])){ 
       $sql  .= $virgula." cc05_custoplanoanalitica = $this->cc05_custoplanoanalitica ";
       $virgula = ",";
       if(trim($this->cc05_custoplanoanalitica) == null ){ 
         $this->erro_sql = " Campo Custo plano analítico nao Informado.";
         $this->erro_campo = "cc05_custoplanoanalitica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc05_sequencial!=null){
       $sql .= " cc05_sequencial = $this->cc05_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc05_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12574,'$this->cc05_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc05_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2195,12574,'".AddSlashes(pg_result($resaco,$conresaco,'cc05_sequencial'))."','$this->cc05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc05_bens"]))
           $resac = db_query("insert into db_acount values($acount,2195,12575,'".AddSlashes(pg_result($resaco,$conresaco,'cc05_bens'))."','$this->cc05_bens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc05_custoplanoanalitica"]))
           $resac = db_query("insert into db_acount values($acount,2195,12576,'".AddSlashes(pg_result($resaco,$conresaco,'cc05_custoplanoanalitica'))."','$this->cc05_custoplanoanalitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo plano analítico dos bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo plano analítico dos bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc05_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc05_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12574,'$cc05_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2195,12574,'','".AddSlashes(pg_result($resaco,$iresaco,'cc05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2195,12575,'','".AddSlashes(pg_result($resaco,$iresaco,'cc05_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2195,12576,'','".AddSlashes(pg_result($resaco,$iresaco,'cc05_custoplanoanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoplanoanaliticabens
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc05_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc05_sequencial = $cc05_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo plano analítico dos bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo plano analítico dos bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc05_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoplanoanaliticabens";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanoanaliticabens ";
     $sql .= "      inner join bens  				on  bens.t52_bem = custoplanoanaliticabens.cc05_bens";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanoanaliticabens.cc05_custoplanoanalitica";
     $sql .= "      inner join cgm  				on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join custoplano  as a on   a.cc01_sequencial = custoplanoanalitica.cc04_custoplano";
     $sql2 = "";
     if($dbwhere==""){
       if($cc05_sequencial!=null ){
         $sql2 .= " where custoplanoanaliticabens.cc05_sequencial = $cc05_sequencial "; 
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
   function sql_query_file ( $cc05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanoanaliticabens ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc05_sequencial!=null ){
         $sql2 .= " where custoplanoanaliticabens.cc05_sequencial = $cc05_sequencial "; 
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
   * Consulta com left join com a custocriteriorateiobens 
   *
   * @param integer $cc05_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_criteriobens($cc05_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    
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
     
     $sql .= " from custoplanoanaliticabens ";
     $sql .= "      inner join bens  				    on  bens.t52_bem = custoplanoanaliticabens.cc05_bens";
     $sql .= "      inner join custoplanoanalitica      on  custoplanoanalitica.cc04_sequencial = custoplanoanaliticabens.cc05_custoplanoanalitica";
     $sql .= "      inner join cgm  				    on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config                on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart                on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens                  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join custoplano               on   cc01_sequencial = custoplanoanalitica.cc04_custoplano";
     $sql .= "      left  join custocriteriorateiobens  on cc06_custoplanoanaliticabens = cc05_sequencial  ";
     $sql .= "      left  join custocriteriorateio      on cc06_custocriteriorateio     =  cc08_sequencial ";
     $sql2 = "";
     
     if($dbwhere==""){
       if($cc05_sequencial!=null ){
         $sql2 .= " where custoplanoanaliticabens.cc05_sequencial = $cc05_sequencial "; 
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
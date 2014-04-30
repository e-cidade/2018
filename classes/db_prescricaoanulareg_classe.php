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

//MODULO: divida
//CLASSE DA ENTIDADE prescricaoanulareg
class cl_prescricaoanulareg { 
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
   var $k121_sequencial = 0; 
   var $k121_arreprescr = 0; 
   var $k121_prescricaoanula = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k121_sequencial = int4 = Sequencial 
                 k121_arreprescr = int4 = Arreprescr 
                 k121_prescricaoanula = int4 = Prescrição Anula 
                 ";
   //funcao construtor da classe 
   function cl_prescricaoanulareg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prescricaoanulareg"); 
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
       $this->k121_sequencial = ($this->k121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k121_sequencial"]:$this->k121_sequencial);
       $this->k121_arreprescr = ($this->k121_arreprescr == ""?@$GLOBALS["HTTP_POST_VARS"]["k121_arreprescr"]:$this->k121_arreprescr);
       $this->k121_prescricaoanula = ($this->k121_prescricaoanula == ""?@$GLOBALS["HTTP_POST_VARS"]["k121_prescricaoanula"]:$this->k121_prescricaoanula);
     }else{
       $this->k121_sequencial = ($this->k121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k121_sequencial"]:$this->k121_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k121_sequencial){ 
      $this->atualizacampos();
     if($this->k121_arreprescr == null ){ 
       $this->erro_sql = " Campo Arreprescr nao Informado.";
       $this->erro_campo = "k121_arreprescr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k121_prescricaoanula == null ){ 
       $this->erro_sql = " Campo Prescrição Anula nao Informado.";
       $this->erro_campo = "k121_prescricaoanula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k121_sequencial == "" || $k121_sequencial == null ){
       $result = db_query("select nextval('prescricaoanulareg_k121_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prescricaoanulareg_k121_sequencial_seq do campo: k121_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k121_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prescricaoanulareg_k121_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k121_sequencial)){
         $this->erro_sql = " Campo k121_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k121_sequencial = $k121_sequencial; 
       }
     }
     if(($this->k121_sequencial == null) || ($this->k121_sequencial == "") ){ 
       $this->erro_sql = " Campo k121_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prescricaoanulareg(
                                       k121_sequencial 
                                      ,k121_arreprescr 
                                      ,k121_prescricaoanula 
                       )
                values (
                                $this->k121_sequencial 
                               ,$this->k121_arreprescr 
                               ,$this->k121_prescricaoanula 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação de prescricaoanula e arreprescr ($this->k121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação de prescricaoanula e arreprescr já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação de prescricaoanula e arreprescr ($this->k121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k121_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k121_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17630,'$this->k121_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3114,17630,'','".AddSlashes(pg_result($resaco,0,'k121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3114,17631,'','".AddSlashes(pg_result($resaco,0,'k121_arreprescr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3114,17632,'','".AddSlashes(pg_result($resaco,0,'k121_prescricaoanula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k121_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update prescricaoanulareg set ";
     $virgula = "";
     if(trim($this->k121_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k121_sequencial"])){ 
       $sql  .= $virgula." k121_sequencial = $this->k121_sequencial ";
       $virgula = ",";
       if(trim($this->k121_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k121_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k121_arreprescr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k121_arreprescr"])){ 
       $sql  .= $virgula." k121_arreprescr = $this->k121_arreprescr ";
       $virgula = ",";
       if(trim($this->k121_arreprescr) == null ){ 
         $this->erro_sql = " Campo Arreprescr nao Informado.";
         $this->erro_campo = "k121_arreprescr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k121_prescricaoanula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k121_prescricaoanula"])){ 
       $sql  .= $virgula." k121_prescricaoanula = $this->k121_prescricaoanula ";
       $virgula = ",";
       if(trim($this->k121_prescricaoanula) == null ){ 
         $this->erro_sql = " Campo Prescrição Anula nao Informado.";
         $this->erro_campo = "k121_prescricaoanula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k121_sequencial!=null){
       $sql .= " k121_sequencial = $this->k121_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k121_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17630,'$this->k121_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k121_sequencial"]) || $this->k121_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3114,17630,'".AddSlashes(pg_result($resaco,$conresaco,'k121_sequencial'))."','$this->k121_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k121_arreprescr"]) || $this->k121_arreprescr != "")
           $resac = db_query("insert into db_acount values($acount,3114,17631,'".AddSlashes(pg_result($resaco,$conresaco,'k121_arreprescr'))."','$this->k121_arreprescr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k121_prescricaoanula"]) || $this->k121_prescricaoanula != "")
           $resac = db_query("insert into db_acount values($acount,3114,17632,'".AddSlashes(pg_result($resaco,$conresaco,'k121_prescricaoanula'))."','$this->k121_prescricaoanula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação de prescricaoanula e arreprescr nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação de prescricaoanula e arreprescr nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k121_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k121_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17630,'$k121_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3114,17630,'','".AddSlashes(pg_result($resaco,$iresaco,'k121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3114,17631,'','".AddSlashes(pg_result($resaco,$iresaco,'k121_arreprescr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3114,17632,'','".AddSlashes(pg_result($resaco,$iresaco,'k121_prescricaoanula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prescricaoanulareg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k121_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k121_sequencial = $k121_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação de prescricaoanula e arreprescr nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação de prescricaoanula e arreprescr nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k121_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:prescricaoanulareg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prescricaoanulareg ";
     $sql .= "      inner join arreprescr  on  arreprescr.k30_sequencial = prescricaoanulareg.k121_arreprescr";
     $sql .= "      inner join prescricaoanula  on  prescricaoanula.k120_sequencial = prescricaoanulareg.k121_prescricaoanula";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = arreprescr.k30_numcgm";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = arreprescr.k30_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = arreprescr.k30_receit";
     $sql .= "      inner join prescricao  as a on   a.k31_codigo = arreprescr.k30_prescricao";
     $sql .= "      inner join db_config  on  db_config.codigo = prescricaoanula.k120_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prescricaoanula.k120_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k121_sequencial!=null ){
         $sql2 .= " where prescricaoanulareg.k121_sequencial = $k121_sequencial "; 
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
   function sql_query_file ( $k121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prescricaoanulareg ";
     $sql2 = "";
     if($dbwhere==""){
       if($k121_sequencial!=null ){
         $sql2 .= " where prescricaoanulareg.k121_sequencial = $k121_sequencial "; 
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
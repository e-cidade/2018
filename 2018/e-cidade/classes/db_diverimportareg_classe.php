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

//MODULO: diversos
//CLASSE DA ENTIDADE diverimportareg
class cl_diverimportareg { 
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
   var $dv12_sequencial = 0; 
   var $dv12_diversos = 0; 
   var $dv12_diverimporta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 dv12_sequencial = int4 = Código 
                 dv12_diversos = int4 = Código do diversos 
                 dv12_diverimporta = int8 = Código 
                 ";
   //funcao construtor da classe 
   function cl_diverimportareg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diverimportareg"); 
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
       $this->dv12_sequencial = ($this->dv12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv12_sequencial"]:$this->dv12_sequencial);
       $this->dv12_diversos = ($this->dv12_diversos == ""?@$GLOBALS["HTTP_POST_VARS"]["dv12_diversos"]:$this->dv12_diversos);
       $this->dv12_diverimporta = ($this->dv12_diverimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["dv12_diverimporta"]:$this->dv12_diverimporta);
     }else{
       $this->dv12_sequencial = ($this->dv12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv12_sequencial"]:$this->dv12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($dv12_sequencial){ 
      $this->atualizacampos();
     if($this->dv12_diversos == null ){ 
       $this->erro_sql = " Campo Código do diversos nao Informado.";
       $this->erro_campo = "dv12_diversos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv12_diverimporta == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "dv12_diverimporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($dv12_sequencial == "" || $dv12_sequencial == null ){
       $result = db_query("select nextval('diverimportareg_dv12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diverimportareg_dv12_sequencial_seq do campo: dv12_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->dv12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diverimportareg_dv12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $dv12_sequencial)){
         $this->erro_sql = " Campo dv12_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->dv12_sequencial = $dv12_sequencial; 
       }
     }
     if(($this->dv12_sequencial == null) || ($this->dv12_sequencial == "") ){ 
       $this->erro_sql = " Campo dv12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diverimportareg(
                                       dv12_sequencial 
                                      ,dv12_diversos 
                                      ,dv12_diverimporta 
                       )
                values (
                                $this->dv12_sequencial 
                               ,$this->dv12_diversos 
                               ,$this->dv12_diverimporta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "diverimportareg ($this->dv12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "diverimportareg já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "diverimportareg ($this->dv12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv12_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->dv12_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18610,'$this->dv12_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3294,18610,'','".AddSlashes(pg_result($resaco,0,'dv12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3294,18612,'','".AddSlashes(pg_result($resaco,0,'dv12_diversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3294,18611,'','".AddSlashes(pg_result($resaco,0,'dv12_diverimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($dv12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update diverimportareg set ";
     $virgula = "";
     if(trim($this->dv12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv12_sequencial"])){ 
       $sql  .= $virgula." dv12_sequencial = $this->dv12_sequencial ";
       $virgula = ",";
       if(trim($this->dv12_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "dv12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv12_diversos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv12_diversos"])){ 
       $sql  .= $virgula." dv12_diversos = $this->dv12_diversos ";
       $virgula = ",";
       if(trim($this->dv12_diversos) == null ){ 
         $this->erro_sql = " Campo Código do diversos nao Informado.";
         $this->erro_campo = "dv12_diversos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv12_diverimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv12_diverimporta"])){ 
       $sql  .= $virgula." dv12_diverimporta = $this->dv12_diverimporta ";
       $virgula = ",";
       if(trim($this->dv12_diverimporta) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "dv12_diverimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($dv12_sequencial!=null){
       $sql .= " dv12_sequencial = $this->dv12_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->dv12_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18610,'$this->dv12_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv12_sequencial"]) || $this->dv12_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3294,18610,'".AddSlashes(pg_result($resaco,$conresaco,'dv12_sequencial'))."','$this->dv12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv12_diversos"]) || $this->dv12_diversos != "")
           $resac = db_query("insert into db_acount values($acount,3294,18612,'".AddSlashes(pg_result($resaco,$conresaco,'dv12_diversos'))."','$this->dv12_diversos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv12_diverimporta"]) || $this->dv12_diverimporta != "")
           $resac = db_query("insert into db_acount values($acount,3294,18611,'".AddSlashes(pg_result($resaco,$conresaco,'dv12_diverimporta'))."','$this->dv12_diverimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diverimportareg nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diverimportareg nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($dv12_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($dv12_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18610,'$dv12_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3294,18610,'','".AddSlashes(pg_result($resaco,$iresaco,'dv12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3294,18612,'','".AddSlashes(pg_result($resaco,$iresaco,'dv12_diversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3294,18611,'','".AddSlashes(pg_result($resaco,$iresaco,'dv12_diverimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diverimportareg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($dv12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv12_sequencial = $dv12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diverimportareg nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diverimportareg nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diverimportareg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $dv12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diverimportareg ";
     $sql .= "      inner join diversos  on  diversos.dv05_coddiver = diverimportareg.dv12_diversos";
     $sql .= "      inner join diverimporta  on  diverimporta.dv11_sequencial = diverimportareg.dv12_diverimporta";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = diversos.dv05_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = diversos.dv05_instit";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = diversos.dv05_procdiver";
     $sql .= "      inner join db_config  as a on   a.codigo = diverimporta.dv11_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = diverimporta.dv11_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($dv12_sequencial!=null ){
         $sql2 .= " where diverimportareg.dv12_sequencial = $dv12_sequencial "; 
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
   function sql_query_file ( $dv12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diverimportareg ";
     $sql2 = "";
     if($dbwhere==""){
       if($dv12_sequencial!=null ){
         $sql2 .= " where diverimportareg.dv12_sequencial = $dv12_sequencial "; 
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
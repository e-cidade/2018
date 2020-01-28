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

//MODULO: Cadastro
//CLASSE DA ENTIDADE iptutabelasconfigcampocorrecao
class cl_iptutabelasconfigcampocorrecao { 
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
   var $j123_sequencial = 0; 
   var $j123_codcam = 0; 
   var $j123_iptutabelasconfig = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j123_sequencial = int4 = Código Sequencial 
                 j123_codcam = int4 = Código do Campo 
                 j123_iptutabelasconfig = int4 = Código da Tabela de Configuração 
                 ";
   //funcao construtor da classe 
   function cl_iptutabelasconfigcampocorrecao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptutabelasconfigcampocorrecao"); 
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
       $this->j123_sequencial = ($this->j123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j123_sequencial"]:$this->j123_sequencial);
       $this->j123_codcam = ($this->j123_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["j123_codcam"]:$this->j123_codcam);
       $this->j123_iptutabelasconfig = ($this->j123_iptutabelasconfig == ""?@$GLOBALS["HTTP_POST_VARS"]["j123_iptutabelasconfig"]:$this->j123_iptutabelasconfig);
     }else{
       $this->j123_sequencial = ($this->j123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j123_sequencial"]:$this->j123_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j123_sequencial){ 
      $this->atualizacampos();
     if($this->j123_codcam == null ){ 
       $this->erro_sql = " Campo Código do Campo nao Informado.";
       $this->erro_campo = "j123_codcam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j123_iptutabelasconfig == null ){ 
       $this->erro_sql = " Campo Código da Tabela de Configuração nao Informado.";
       $this->erro_campo = "j123_iptutabelasconfig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j123_sequencial == "" || $j123_sequencial == null ){
       $result = db_query("select nextval('iptutabelasconfigcampocorrecao_j123_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptutabelasconfigcampocorrecao_j123_sequencial_seq do campo: j123_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j123_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptutabelasconfigcampocorrecao_j123_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j123_sequencial)){
         $this->erro_sql = " Campo j123_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j123_sequencial = $j123_sequencial; 
       }
     }
     if(($this->j123_sequencial == null) || ($this->j123_sequencial == "") ){ 
       $this->erro_sql = " Campo j123_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptutabelasconfigcampocorrecao(
                                       j123_sequencial 
                                      ,j123_codcam 
                                      ,j123_iptutabelasconfig 
                       )
                values (
                                $this->j123_sequencial 
                               ,$this->j123_codcam 
                               ,$this->j123_iptutabelasconfig 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "iptutabelasconfigcampocorrecao ($this->j123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "iptutabelasconfigcampocorrecao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "iptutabelasconfigcampocorrecao ($this->j123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j123_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j123_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17398,'$this->j123_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3079,17398,'','".AddSlashes(pg_result($resaco,0,'j123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3079,17399,'','".AddSlashes(pg_result($resaco,0,'j123_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3079,17400,'','".AddSlashes(pg_result($resaco,0,'j123_iptutabelasconfig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j123_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptutabelasconfigcampocorrecao set ";
     $virgula = "";
     if(trim($this->j123_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j123_sequencial"])){ 
       $sql  .= $virgula." j123_sequencial = $this->j123_sequencial ";
       $virgula = ",";
       if(trim($this->j123_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "j123_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j123_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j123_codcam"])){ 
       $sql  .= $virgula." j123_codcam = $this->j123_codcam ";
       $virgula = ",";
       if(trim($this->j123_codcam) == null ){ 
         $this->erro_sql = " Campo Código do Campo nao Informado.";
         $this->erro_campo = "j123_codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j123_iptutabelasconfig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j123_iptutabelasconfig"])){ 
       $sql  .= $virgula." j123_iptutabelasconfig = $this->j123_iptutabelasconfig ";
       $virgula = ",";
       if(trim($this->j123_iptutabelasconfig) == null ){ 
         $this->erro_sql = " Campo Código da Tabela de Configuração nao Informado.";
         $this->erro_campo = "j123_iptutabelasconfig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j123_sequencial!=null){
       $sql .= " j123_sequencial = $this->j123_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j123_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17398,'$this->j123_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j123_sequencial"]) || $this->j123_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3079,17398,'".AddSlashes(pg_result($resaco,$conresaco,'j123_sequencial'))."','$this->j123_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j123_codcam"]) || $this->j123_codcam != "")
           $resac = db_query("insert into db_acount values($acount,3079,17399,'".AddSlashes(pg_result($resaco,$conresaco,'j123_codcam'))."','$this->j123_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j123_iptutabelasconfig"]) || $this->j123_iptutabelasconfig != "")
           $resac = db_query("insert into db_acount values($acount,3079,17400,'".AddSlashes(pg_result($resaco,$conresaco,'j123_iptutabelasconfig'))."','$this->j123_iptutabelasconfig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutabelasconfigcampocorrecao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutabelasconfigcampocorrecao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j123_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j123_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17398,'$j123_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3079,17398,'','".AddSlashes(pg_result($resaco,$iresaco,'j123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3079,17399,'','".AddSlashes(pg_result($resaco,$iresaco,'j123_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3079,17400,'','".AddSlashes(pg_result($resaco,$iresaco,'j123_iptutabelasconfig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptutabelasconfigcampocorrecao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j123_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j123_sequencial = $j123_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutabelasconfigcampocorrecao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutabelasconfigcampocorrecao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j123_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptutabelasconfigcampocorrecao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptutabelasconfigcampocorrecao                                                                                              ";
     $sql .= "      inner join db_syscampo        on db_syscampo.codcam                = iptutabelasconfigcampocorrecao.j123_codcam             ";
     $sql .= "      inner join iptutabelasconfig  on iptutabelasconfig.j122_sequencial = iptutabelasconfigcampocorrecao.j123_iptutabelasconfig  ";
     $sql .= "      inner join iptutabelas  as a  on a.j121_sequencial                 = iptutabelasconfig.j122_iptutabelas                     ";
     $sql .= "      inner join db_sysarquivo      on db_sysarquivo.codarq              = a.j121_codarq                                          ";
     $sql2 = "";
     if($dbwhere==""){
       if($j123_sequencial!=null ){
         $sql2 .= " where iptutabelasconfigcampocorrecao.j123_sequencial = $j123_sequencial "; 
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
   function sql_query_file ( $j123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptutabelasconfigcampocorrecao ";
     $sql2 = "";
     if($dbwhere==""){
       if($j123_sequencial!=null ){
         $sql2 .= " where iptutabelasconfigcampocorrecao.j123_sequencial = $j123_sequencial "; 
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
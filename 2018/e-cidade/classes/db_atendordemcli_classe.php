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

//MODULO: atendimento
//CLASSE DA ENTIDADE atendordemcli
class cl_atendordemcli { 
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
   var $at85_seq = 0; 
   var $at85_respcli = 0; 
   var $at85_cliitem = 0; 
   var $at85_prioridade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at85_seq = int4 = Sequencial de ordenação 
                 at85_respcli = int4 = Código do responsável 
                 at85_cliitem = int4 = Sequencial da tarefa 
                 at85_prioridade = int4 = Prioridade 
                 ";
   //funcao construtor da classe 
   function cl_atendordemcli() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendordemcli"); 
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
       $this->at85_seq = ($this->at85_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at85_seq"]:$this->at85_seq);
       $this->at85_respcli = ($this->at85_respcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at85_respcli"]:$this->at85_respcli);
       $this->at85_cliitem = ($this->at85_cliitem == ""?@$GLOBALS["HTTP_POST_VARS"]["at85_cliitem"]:$this->at85_cliitem);
       $this->at85_prioridade = ($this->at85_prioridade == ""?@$GLOBALS["HTTP_POST_VARS"]["at85_prioridade"]:$this->at85_prioridade);
     }else{
       $this->at85_seq = ($this->at85_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at85_seq"]:$this->at85_seq);
     }
   }
   // funcao para inclusao
   function incluir ($at85_seq){ 
      $this->atualizacampos();
     if($this->at85_respcli == null ){ 
       $this->erro_sql = " Campo Código do responsável nao Informado.";
       $this->erro_campo = "at85_respcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at85_cliitem == null ){ 
       $this->erro_sql = " Campo Sequencial da tarefa nao Informado.";
       $this->erro_campo = "at85_cliitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at85_prioridade == null ){ 
       $this->erro_sql = " Campo Prioridade nao Informado.";
       $this->erro_campo = "at85_prioridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at85_seq == "" || $at85_seq == null ){
       $result = @pg_query("select nextval('atendordemcli_at85_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendordemcli_at85_seq_seq do campo: at85_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at85_seq = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from atendordemcli_at85_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $at85_seq)){
         $this->erro_sql = " Campo at85_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at85_seq = $at85_seq; 
       }
     }
     if(($this->at85_seq == null) || ($this->at85_seq == "") ){ 
       $this->erro_sql = " Campo at85_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendordemcli(
                                       at85_seq 
                                      ,at85_respcli 
                                      ,at85_cliitem 
                                      ,at85_prioridade 
                       )
                values (
                                $this->at85_seq 
                               ,$this->at85_respcli 
                               ,$this->at85_cliitem 
                               ,$this->at85_prioridade 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordem de prioridade dos atendimentos ($this->at85_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordem de prioridade dos atendimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordem de prioridade dos atendimentos ($this->at85_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at85_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at85_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,9207,'$this->at85_seq','I')");
       $resac = pg_query("insert into db_acount values($acount,1577,9207,'','".AddSlashes(pg_result($resaco,0,'at85_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1577,9208,'','".AddSlashes(pg_result($resaco,0,'at85_respcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1577,9209,'','".AddSlashes(pg_result($resaco,0,'at85_cliitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1577,9210,'','".AddSlashes(pg_result($resaco,0,'at85_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at85_seq=null) { 
      $this->atualizacampos();
     $sql = " update atendordemcli set ";
     $virgula = "";
     if(trim($this->at85_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at85_seq"])){ 
       $sql  .= $virgula." at85_seq = $this->at85_seq ";
       $virgula = ",";
       if(trim($this->at85_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial de ordenação nao Informado.";
         $this->erro_campo = "at85_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at85_respcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at85_respcli"])){ 
       $sql  .= $virgula." at85_respcli = $this->at85_respcli ";
       $virgula = ",";
       if(trim($this->at85_respcli) == null ){ 
         $this->erro_sql = " Campo Código do responsável nao Informado.";
         $this->erro_campo = "at85_respcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at85_cliitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at85_cliitem"])){ 
       $sql  .= $virgula." at85_cliitem = $this->at85_cliitem ";
       $virgula = ",";
       if(trim($this->at85_cliitem) == null ){ 
         $this->erro_sql = " Campo Sequencial da tarefa nao Informado.";
         $this->erro_campo = "at85_cliitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at85_prioridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at85_prioridade"])){ 
       $sql  .= $virgula." at85_prioridade = $this->at85_prioridade ";
       $virgula = ",";
       if(trim($this->at85_prioridade) == null ){ 
         $this->erro_sql = " Campo Prioridade nao Informado.";
         $this->erro_campo = "at85_prioridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at85_seq!=null){
       $sql .= " at85_seq = $this->at85_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at85_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9207,'$this->at85_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at85_seq"]))
           $resac = pg_query("insert into db_acount values($acount,1577,9207,'".AddSlashes(pg_result($resaco,$conresaco,'at85_seq'))."','$this->at85_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at85_respcli"]))
           $resac = pg_query("insert into db_acount values($acount,1577,9208,'".AddSlashes(pg_result($resaco,$conresaco,'at85_respcli'))."','$this->at85_respcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at85_cliitem"]))
           $resac = pg_query("insert into db_acount values($acount,1577,9209,'".AddSlashes(pg_result($resaco,$conresaco,'at85_cliitem'))."','$this->at85_cliitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at85_prioridade"]))
           $resac = pg_query("insert into db_acount values($acount,1577,9210,'".AddSlashes(pg_result($resaco,$conresaco,'at85_prioridade'))."','$this->at85_prioridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de prioridade dos atendimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at85_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de prioridade dos atendimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at85_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at85_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at85_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at85_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9207,'$at85_seq','E')");
         $resac = pg_query("insert into db_acount values($acount,1577,9207,'','".AddSlashes(pg_result($resaco,$iresaco,'at85_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1577,9208,'','".AddSlashes(pg_result($resaco,$iresaco,'at85_respcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1577,9209,'','".AddSlashes(pg_result($resaco,$iresaco,'at85_cliitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1577,9210,'','".AddSlashes(pg_result($resaco,$iresaco,'at85_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendordemcli
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at85_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at85_seq = $at85_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de prioridade dos atendimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at85_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de prioridade dos atendimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at85_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at85_seq;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:atendordemcli";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at85_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendordemcli ";
     $sql .= "      inner join atendusucliitem  on  atendusucliitem.at81_seq = atendordemcli.at85_cliitem";
     $sql .= "      inner join atendrespcli  on  atendrespcli.at84_seq = atendordemcli.at85_respcli";
     $sql .= "      inner join tipoatend  on  tipoatend.at04_codtipo = atendusucliitem.at81_codtipo";
     $sql .= "      inner join atendusucli  on  atendusucli.at80_codatendcli = atendusucliitem.at81_codatendcli";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendrespcli.at84_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at85_seq!=null ){
         $sql2 .= " where atendordemcli.at85_seq = $at85_seq "; 
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
   function sql_query_file ( $at85_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendordemcli ";
     $sql2 = "";
     if($dbwhere==""){
       if($at85_seq!=null ){
         $sql2 .= " where atendordemcli.at85_seq = $at85_seq "; 
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
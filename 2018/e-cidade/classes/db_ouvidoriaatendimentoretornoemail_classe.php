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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriaatendimentoretornoemail
class cl_ouvidoriaatendimentoretornoemail { 
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
   var $ov13_sequencial = 0; 
   var $ov13_ouvidoriaantendimento = 0; 
   var $ov13_email = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov13_sequencial = int4 = Sequencial 
                 ov13_ouvidoriaantendimento = int4 = Atendimento 
                 ov13_email = varchar(100) = Email 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriaatendimentoretornoemail() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriaatendimentoretornoemail"); 
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
       $this->ov13_sequencial = ($this->ov13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov13_sequencial"]:$this->ov13_sequencial);
       $this->ov13_ouvidoriaantendimento = ($this->ov13_ouvidoriaantendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ov13_ouvidoriaantendimento"]:$this->ov13_ouvidoriaantendimento);
       $this->ov13_email = ($this->ov13_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ov13_email"]:$this->ov13_email);
     }else{
       $this->ov13_sequencial = ($this->ov13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov13_sequencial"]:$this->ov13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov13_sequencial){ 
      $this->atualizacampos();
     if($this->ov13_ouvidoriaantendimento == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "ov13_ouvidoriaantendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov13_sequencial == "" || $ov13_sequencial == null ){
       $result = db_query("select nextval('ouvidoriaatendimentoretornoemail_ov13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ouvidoriaatendimentoretornoemail_ov13_sequencial_seq do campo: ov13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ouvidoriaatendimentoretornoemail_ov13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov13_sequencial)){
         $this->erro_sql = " Campo ov13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov13_sequencial = $ov13_sequencial; 
       }
     }
     if(($this->ov13_sequencial == null) || ($this->ov13_sequencial == "") ){ 
       $this->erro_sql = " Campo ov13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriaatendimentoretornoemail(
                                       ov13_sequencial 
                                      ,ov13_ouvidoriaantendimento 
                                      ,ov13_email 
                       )
                values (
                                $this->ov13_sequencial 
                               ,$this->ov13_ouvidoriaantendimento 
                               ,'$this->ov13_email' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Emails de Retorno do Atendimento da Ouvidoria ($this->ov13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Emails de Retorno do Atendimento da Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Emails de Retorno do Atendimento da Ouvidoria ($this->ov13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14791,'$this->ov13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2603,14791,'','".AddSlashes(pg_result($resaco,0,'ov13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2603,14792,'','".AddSlashes(pg_result($resaco,0,'ov13_ouvidoriaantendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2603,14793,'','".AddSlashes(pg_result($resaco,0,'ov13_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriaatendimentoretornoemail set ";
     $virgula = "";
     if(trim($this->ov13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov13_sequencial"])){ 
       $sql  .= $virgula." ov13_sequencial = $this->ov13_sequencial ";
       $virgula = ",";
       if(trim($this->ov13_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov13_ouvidoriaantendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov13_ouvidoriaantendimento"])){ 
       $sql  .= $virgula." ov13_ouvidoriaantendimento = $this->ov13_ouvidoriaantendimento ";
       $virgula = ",";
       if(trim($this->ov13_ouvidoriaantendimento) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "ov13_ouvidoriaantendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov13_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov13_email"])){ 
       $sql  .= $virgula." ov13_email = '$this->ov13_email' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ov13_sequencial!=null){
       $sql .= " ov13_sequencial = $this->ov13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14791,'$this->ov13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov13_sequencial"]) || $this->ov13_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2603,14791,'".AddSlashes(pg_result($resaco,$conresaco,'ov13_sequencial'))."','$this->ov13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov13_ouvidoriaantendimento"]) || $this->ov13_ouvidoriaantendimento != "")
           $resac = db_query("insert into db_acount values($acount,2603,14792,'".AddSlashes(pg_result($resaco,$conresaco,'ov13_ouvidoriaantendimento'))."','$this->ov13_ouvidoriaantendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov13_email"]) || $this->ov13_email != "")
           $resac = db_query("insert into db_acount values($acount,2603,14793,'".AddSlashes(pg_result($resaco,$conresaco,'ov13_email'))."','$this->ov13_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emails de Retorno do Atendimento da Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emails de Retorno do Atendimento da Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14791,'$ov13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2603,14791,'','".AddSlashes(pg_result($resaco,$iresaco,'ov13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2603,14792,'','".AddSlashes(pg_result($resaco,$iresaco,'ov13_ouvidoriaantendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2603,14793,'','".AddSlashes(pg_result($resaco,$iresaco,'ov13_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriaatendimentoretornoemail
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov13_sequencial = $ov13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emails de Retorno do Atendimento da Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emails de Retorno do Atendimento da Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriaatendimentoretornoemail";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretornoemail ";
     $sql .= "      inner join ouvidoriaatendimento  on  ouvidoriaatendimento.ov01_sequencial = ouvidoriaatendimentoretornoemail.ov13_ouvidoriaantendimento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ouvidoriaatendimento.ov01_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = ouvidoriaatendimento.ov01_depart";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = ouvidoriaatendimento.ov01_tipoprocesso";
     $sql .= "      inner join tipoidentificacao  on  tipoidentificacao.ov05_sequencial = ouvidoriaatendimento.ov01_tipoidentificacao";
     $sql .= "      inner join formareclamacao  on  formareclamacao.p42_sequencial = ouvidoriaatendimento.ov01_formareclamacao";
     $sql .= "      inner join situacaoouvidoriaatendimento  on  situacaoouvidoriaatendimento.ov18_sequencial = ouvidoriaatendimento.ov01_situacaoouvidoriaatendimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ov13_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretornoemail.ov13_sequencial = $ov13_sequencial "; 
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
   function sql_query_file ( $ov13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentoretornoemail ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov13_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentoretornoemail.ov13_sequencial = $ov13_sequencial "; 
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
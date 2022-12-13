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

//MODULO: escola
//CLASSE DA ENTIDADE alunocensotipotransporte
class cl_alunocensotipotransporte { 
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
   var $ed311_sequencial = 0; 
   var $ed311_aluno = 0; 
   var $ed311_censotipotransporte = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed311_sequencial = int4 = Código 
                 ed311_aluno = int4 = Código do Aluno 
                 ed311_censotipotransporte = int4 = Código do Tipo de Transporte 
                 ";
   //funcao construtor da classe 
   function cl_alunocensotipotransporte() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunocensotipotransporte"); 
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
       $this->ed311_sequencial = ($this->ed311_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed311_sequencial"]:$this->ed311_sequencial);
       $this->ed311_aluno = ($this->ed311_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed311_aluno"]:$this->ed311_aluno);
       $this->ed311_censotipotransporte = ($this->ed311_censotipotransporte == ""?@$GLOBALS["HTTP_POST_VARS"]["ed311_censotipotransporte"]:$this->ed311_censotipotransporte);
     }else{
       $this->ed311_sequencial = ($this->ed311_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed311_sequencial"]:$this->ed311_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed311_sequencial){ 
      $this->atualizacampos();
     if($this->ed311_aluno == null ){ 
       $this->erro_sql = " Campo Código do Aluno nao Informado.";
       $this->erro_campo = "ed311_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed311_censotipotransporte == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Transporte nao Informado.";
       $this->erro_campo = "ed311_censotipotransporte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed311_sequencial == "" || $ed311_sequencial == null ){
       $result = db_query("select nextval('alunocensotipotransporte_ed311_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunocensotipotransporte_ed311_sequencial_seq do campo: ed311_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed311_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunocensotipotransporte_ed311_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed311_sequencial)){
         $this->erro_sql = " Campo ed311_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed311_sequencial = $ed311_sequencial; 
       }
     }
     if(($this->ed311_sequencial == null) || ($this->ed311_sequencial == "") ){ 
       $this->erro_sql = " Campo ed311_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunocensotipotransporte(
                                       ed311_sequencial 
                                      ,ed311_aluno 
                                      ,ed311_censotipotransporte 
                       )
                values (
                                $this->ed311_sequencial 
                               ,$this->ed311_aluno 
                               ,$this->ed311_censotipotransporte 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados do tipo de transporte do aluno ($this->ed311_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados do tipo de transporte do aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados do tipo de transporte do aluno ($this->ed311_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed311_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed311_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18912,'$this->ed311_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3362,18912,'','".AddSlashes(pg_result($resaco,0,'ed311_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3362,18913,'','".AddSlashes(pg_result($resaco,0,'ed311_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3362,18914,'','".AddSlashes(pg_result($resaco,0,'ed311_censotipotransporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed311_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update alunocensotipotransporte set ";
     $virgula = "";
     if(trim($this->ed311_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed311_sequencial"])){ 
       $sql  .= $virgula." ed311_sequencial = $this->ed311_sequencial ";
       $virgula = ",";
       if(trim($this->ed311_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed311_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed311_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed311_aluno"])){ 
       $sql  .= $virgula." ed311_aluno = $this->ed311_aluno ";
       $virgula = ",";
       if(trim($this->ed311_aluno) == null ){ 
         $this->erro_sql = " Campo Código do Aluno nao Informado.";
         $this->erro_campo = "ed311_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed311_censotipotransporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed311_censotipotransporte"])){ 
       $sql  .= $virgula." ed311_censotipotransporte = $this->ed311_censotipotransporte ";
       $virgula = ",";
       if(trim($this->ed311_censotipotransporte) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Transporte nao Informado.";
         $this->erro_campo = "ed311_censotipotransporte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed311_sequencial!=null){
       $sql .= " ed311_sequencial = $this->ed311_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed311_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18912,'$this->ed311_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed311_sequencial"]) || $this->ed311_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3362,18912,'".AddSlashes(pg_result($resaco,$conresaco,'ed311_sequencial'))."','$this->ed311_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed311_aluno"]) || $this->ed311_aluno != "")
           $resac = db_query("insert into db_acount values($acount,3362,18913,'".AddSlashes(pg_result($resaco,$conresaco,'ed311_aluno'))."','$this->ed311_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed311_censotipotransporte"]) || $this->ed311_censotipotransporte != "")
           $resac = db_query("insert into db_acount values($acount,3362,18914,'".AddSlashes(pg_result($resaco,$conresaco,'ed311_censotipotransporte'))."','$this->ed311_censotipotransporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do tipo de transporte do aluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed311_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do tipo de transporte do aluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed311_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed311_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed311_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed311_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18912,'$ed311_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3362,18912,'','".AddSlashes(pg_result($resaco,$iresaco,'ed311_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3362,18913,'','".AddSlashes(pg_result($resaco,$iresaco,'ed311_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3362,18914,'','".AddSlashes(pg_result($resaco,$iresaco,'ed311_censotipotransporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunocensotipotransporte
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed311_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed311_sequencial = $ed311_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do tipo de transporte do aluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed311_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do tipo de transporte do aluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed311_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed311_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunocensotipotransporte";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed311_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunocensotipotransporte ";
     $sql .= "      inner join censotipotransporte  on  censotipotransporte.ed312_sequencial = alunocensotipotransporte.ed311_censotipotransporte";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = alunocensotipotransporte.ed311_aluno";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno  as a on   a.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed311_sequencial!=null ){
         $sql2 .= " where alunocensotipotransporte.ed311_sequencial = $ed311_sequencial "; 
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
   function sql_query_file ( $ed311_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunocensotipotransporte ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed311_sequencial!=null ){
         $sql2 .= " where alunocensotipotransporte.ed311_sequencial = $ed311_sequencial "; 
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
  
 function sql_query_transporte_aluno($iAluno, $campos = "*",$ordem=null) { 
     
   $sql = "select ";
   if ($campos != "*" ) {

     $campos_sql = split("#",$campos);
     $virgula = "";
     for ($i = 0; $i < sizeof($campos_sql); $i++) {
       
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from censotipotransporte ";
    $sql .= "     left join alunocensotipotransporte on ed312_sequencial = ed311_censotipotransporte";
    $sql .= "                                       and ed311_aluno = {$iAluno}";
    if ($ordem != null ) {
       
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
         
         $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  function sql_query_tipo_transporte ( $ed311_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from alunocensotipotransporte ";
    $sql .= "      inner join censotipotransporte  on  censotipotransporte.ed312_sequencial = alunocensotipotransporte.ed311_censotipotransporte";
    $sql2 = "";
    if ($dbwhere == "") {

      if($ed311_sequencial != null ) {
        
        $sql2 .= " where alunocensotipotransporte.ed311_sequencial = $ed311_sequencial "; 
      } 
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>
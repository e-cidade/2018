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

//MODULO: issqn
//CLASSE DA ENTIDADE issnotaavulsatomador
class cl_issnotaavulsatomador { 
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
   var $q53_sequencial = 0; 
   var $q53_issnotaavulsa = 0; 
   var $q53_dtservico_dia = null; 
   var $q53_dtservico_mes = null; 
   var $q53_dtservico_ano = null; 
   var $q53_dtservico = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q53_sequencial = int4 = Código Sequencial 
                 q53_issnotaavulsa = int4 = Número da Nota 
                 q53_dtservico = date = Data do  Serviço 
                 ";
   //funcao construtor da classe 
   function cl_issnotaavulsatomador() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issnotaavulsatomador"); 
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
       $this->q53_sequencial = ($this->q53_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q53_sequencial"]:$this->q53_sequencial);
       $this->q53_issnotaavulsa = ($this->q53_issnotaavulsa == ""?@$GLOBALS["HTTP_POST_VARS"]["q53_issnotaavulsa"]:$this->q53_issnotaavulsa);
       if($this->q53_dtservico == ""){
         $this->q53_dtservico_dia = ($this->q53_dtservico_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q53_dtservico_dia"]:$this->q53_dtservico_dia);
         $this->q53_dtservico_mes = ($this->q53_dtservico_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q53_dtservico_mes"]:$this->q53_dtservico_mes);
         $this->q53_dtservico_ano = ($this->q53_dtservico_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q53_dtservico_ano"]:$this->q53_dtservico_ano);
         if($this->q53_dtservico_dia != ""){
            $this->q53_dtservico = $this->q53_dtservico_ano."-".$this->q53_dtservico_mes."-".$this->q53_dtservico_dia;
         }
       }
     }else{
       $this->q53_sequencial = ($this->q53_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q53_sequencial"]:$this->q53_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q53_sequencial){ 
      $this->atualizacampos();
     if($this->q53_issnotaavulsa == null ){ 
       $this->erro_sql = " Campo Número da Nota nao Informado.";
       $this->erro_campo = "q53_issnotaavulsa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q53_dtservico == null ){ 
       $this->erro_sql = " Campo Data do  Serviço nao Informado.";
       $this->erro_campo = "q53_dtservico_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q53_sequencial == "" || $q53_sequencial == null ){
       $result = db_query("select nextval('issnotaavulsatomador_q53_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issnotaavulsatomador_q53_sequencial_seq do campo: q53_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q53_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issnotaavulsatomador_q53_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q53_sequencial)){
         $this->erro_sql = " Campo q53_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q53_sequencial = $q53_sequencial; 
       }
     }
     if(($this->q53_sequencial == null) || ($this->q53_sequencial == "") ){ 
       $this->erro_sql = " Campo q53_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issnotaavulsatomador(
                                       q53_sequencial 
                                      ,q53_issnotaavulsa 
                                      ,q53_dtservico 
                       )
                values (
                                $this->q53_sequencial 
                               ,$this->q53_issnotaavulsa 
                               ,".($this->q53_dtservico == "null" || $this->q53_dtservico == ""?"null":"'".$this->q53_dtservico."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tomador da nota ($this->q53_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tomador da nota já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tomador da nota ($this->q53_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q53_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q53_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10585,'$this->q53_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1828,10585,'','".AddSlashes(pg_result($resaco,0,'q53_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1828,10586,'','".AddSlashes(pg_result($resaco,0,'q53_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1828,10587,'','".AddSlashes(pg_result($resaco,0,'q53_dtservico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q53_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issnotaavulsatomador set ";
     $virgula = "";
     if(trim($this->q53_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q53_sequencial"])){ 
       $sql  .= $virgula." q53_sequencial = $this->q53_sequencial ";
       $virgula = ",";
       if(trim($this->q53_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "q53_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q53_issnotaavulsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q53_issnotaavulsa"])){ 
       $sql  .= $virgula." q53_issnotaavulsa = $this->q53_issnotaavulsa ";
       $virgula = ",";
       if(trim($this->q53_issnotaavulsa) == null ){ 
         $this->erro_sql = " Campo Número da Nota nao Informado.";
         $this->erro_campo = "q53_issnotaavulsa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q53_dtservico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q53_dtservico_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q53_dtservico_dia"] !="") ){ 
       $sql  .= $virgula." q53_dtservico = '$this->q53_dtservico' ";
       $virgula = ",";
       if(trim($this->q53_dtservico) == null ){ 
         $this->erro_sql = " Campo Data do  Serviço nao Informado.";
         $this->erro_campo = "q53_dtservico_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q53_dtservico_dia"])){ 
         $sql  .= $virgula." q53_dtservico = null ";
         $virgula = ",";
         if(trim($this->q53_dtservico) == null ){ 
           $this->erro_sql = " Campo Data do  Serviço nao Informado.";
           $this->erro_campo = "q53_dtservico_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($q53_sequencial!=null){
       $sql .= " q53_sequencial = $this->q53_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q53_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10585,'$this->q53_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q53_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1828,10585,'".AddSlashes(pg_result($resaco,$conresaco,'q53_sequencial'))."','$this->q53_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q53_issnotaavulsa"]))
           $resac = db_query("insert into db_acount values($acount,1828,10586,'".AddSlashes(pg_result($resaco,$conresaco,'q53_issnotaavulsa'))."','$this->q53_issnotaavulsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q53_dtservico"]))
           $resac = db_query("insert into db_acount values($acount,1828,10587,'".AddSlashes(pg_result($resaco,$conresaco,'q53_dtservico'))."','$this->q53_dtservico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tomador da nota nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q53_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tomador da nota nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q53_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q53_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q53_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q53_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10585,'$q53_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1828,10585,'','".AddSlashes(pg_result($resaco,$iresaco,'q53_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1828,10586,'','".AddSlashes(pg_result($resaco,$iresaco,'q53_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1828,10587,'','".AddSlashes(pg_result($resaco,$iresaco,'q53_dtservico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issnotaavulsatomador
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q53_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q53_sequencial = $q53_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tomador da nota nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q53_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tomador da nota nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q53_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q53_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issnotaavulsatomador";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q53_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsatomador ";
     $sql .= "      inner join issnotaavulsa  on  issnotaavulsa.q51_sequencial = issnotaavulsatomador.q53_issnotaavulsa";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($q53_sequencial!=null ){
         $sql2 .= " where issnotaavulsatomador.q53_sequencial = $q53_sequencial "; 
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
   function sql_query_file ( $q53_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsatomador ";
     $sql2 = "";
     if($dbwhere==""){
       if($q53_sequencial!=null ){
         $sql2 .= " where issnotaavulsatomador.q53_sequencial = $q53_sequencial "; 
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
   function sql_query_tomador ( $q53_issnotaavulsa=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = " select "; 
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
     $sql .= " from (select cgm.*,";
     $sql .= "              null as q02_inscr,";
     $sql .= "              q61_numcgm,";
     $sql .= "              q53_sequencial,";
     $sql .= "              q53_dtservico";
     $sql .= "       from   issnotaavulsatomador ";
     $sql .= "                 inner join issnotaavulsatomadorcgm   on q53_sequencial = q61_issnotaavulsatomador";
     $sql .= "                 inner join cgm                       on cgm.z01_numcgm = q61_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q53_issnotaavulsa!=null ){
         $sql2 .= " where issnotaavulsatomador.q53_issnotaavulsa = $q53_issnotaavulsa "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql .= " union ";
   
     $sql .= " select  cgm.*,";
     $sql .= "         q02_inscr,";
     $sql .= "         null as q61_numcgm,";
     $sql .= "         q53_sequencial,";
     $sql .= "         q53_dtservico";
     $sql .= "   from  issnotaavulsatomador ";
     $sql .= "                inner join issnotaavulsatomadorinscr on q53_sequencial = q54_issnotaavulsatomador";
     $sql .= "                inner join issbase                   on q02_inscr      = q54_inscr";
     $sql .= "                inner join cgm                       on q02_numcgm     = z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q53_issnotaavulsa!=null ){
         $sql2 .= " where issnotaavulsatomador.q53_issnotaavulsa = $q53_issnotaavulsa "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql .= " ) as x"; 
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
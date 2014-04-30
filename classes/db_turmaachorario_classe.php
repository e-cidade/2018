<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE turmaachorario
class cl_turmaachorario { 
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
   var $ed270_i_codigo = 0; 
   var $ed270_i_rechumano = 0; 
   var $ed270_i_diasemana = 0; 
   var $ed270_i_periodo = 0; 
   var $ed270_i_turmaac = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed270_i_codigo = int8 = Código 
                 ed270_i_rechumano = int8 = Regente 
                 ed270_i_diasemana = int8 = Dia Semana 
                 ed270_i_periodo = int8 = Período 
                 ed270_i_turmaac = int4 = Turmaac 
                 ";
   //funcao construtor da classe 
   function cl_turmaachorario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaachorario"); 
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
       $this->ed270_i_codigo = ($this->ed270_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed270_i_codigo"]:$this->ed270_i_codigo);
       $this->ed270_i_rechumano = ($this->ed270_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed270_i_rechumano"]:$this->ed270_i_rechumano);
       $this->ed270_i_diasemana = ($this->ed270_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed270_i_diasemana"]:$this->ed270_i_diasemana);
       $this->ed270_i_periodo = ($this->ed270_i_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed270_i_periodo"]:$this->ed270_i_periodo);
       $this->ed270_i_turmaac = ($this->ed270_i_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed270_i_turmaac"]:$this->ed270_i_turmaac);
     }else{
       $this->ed270_i_codigo = ($this->ed270_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed270_i_codigo"]:$this->ed270_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed270_i_codigo){ 
      $this->atualizacampos();
     if($this->ed270_i_rechumano == null ){ 
       $this->erro_sql = " Campo Regente nao Informado.";
       $this->erro_campo = "ed270_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed270_i_diasemana == null ){ 
       $this->erro_sql = " Campo Dia Semana nao Informado.";
       $this->erro_campo = "ed270_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed270_i_periodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "ed270_i_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed270_i_turmaac == null ){ 
       $this->erro_sql = " Campo Turmaac nao Informado.";
       $this->erro_campo = "ed270_i_turmaac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed270_i_codigo == "" || $ed270_i_codigo == null ){
       $result = db_query("select nextval('turmaachorario_ed270_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaachorario_ed270_i_codigo_seq do campo: ed270_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed270_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmaachorario_ed270_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed270_i_codigo)){
         $this->erro_sql = " Campo ed270_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed270_i_codigo = $ed270_i_codigo; 
       }
     }
     if(($this->ed270_i_codigo == null) || ($this->ed270_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed270_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaachorario(
                                       ed270_i_codigo 
                                      ,ed270_i_rechumano 
                                      ,ed270_i_diasemana 
                                      ,ed270_i_periodo 
                                      ,ed270_i_turmaac 
                       )
                values (
                                $this->ed270_i_codigo 
                               ,$this->ed270_i_rechumano 
                               ,$this->ed270_i_diasemana 
                               ,$this->ed270_i_periodo 
                               ,$this->ed270_i_turmaac 
                      )";                                
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Horarios de Turma com Ativ. Comp. ($this->ed270_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Horarios de Turma com Ativ. Comp. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Horarios de Turma com Ativ. Comp. ($this->ed270_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed270_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed270_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13840,'$this->ed270_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2418,13840,'','".AddSlashes(pg_result($resaco,0,'ed270_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2418,13842,'','".AddSlashes(pg_result($resaco,0,'ed270_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2418,13843,'','".AddSlashes(pg_result($resaco,0,'ed270_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2418,13844,'','".AddSlashes(pg_result($resaco,0,'ed270_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2418,15443,'','".AddSlashes(pg_result($resaco,0,'ed270_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed270_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmaachorario set ";
     $virgula = "";
     if(trim($this->ed270_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_codigo"])){ 
       $sql  .= $virgula." ed270_i_codigo = $this->ed270_i_codigo ";
       $virgula = ",";
       if(trim($this->ed270_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed270_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed270_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_rechumano"])){ 
       $sql  .= $virgula." ed270_i_rechumano = $this->ed270_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed270_i_rechumano) == null ){ 
         $this->erro_sql = " Campo Regente nao Informado.";
         $this->erro_campo = "ed270_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed270_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_diasemana"])){ 
       $sql  .= $virgula." ed270_i_diasemana = $this->ed270_i_diasemana ";
       $virgula = ",";
       if(trim($this->ed270_i_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia Semana nao Informado.";
         $this->erro_campo = "ed270_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed270_i_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_periodo"])){ 
       $sql  .= $virgula." ed270_i_periodo = $this->ed270_i_periodo ";
       $virgula = ",";
       if(trim($this->ed270_i_periodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "ed270_i_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed270_i_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_turmaac"])){ 
       $sql  .= $virgula." ed270_i_turmaac = $this->ed270_i_turmaac ";
       $virgula = ",";
       if(trim($this->ed270_i_turmaac) == null ){ 
         $this->erro_sql = " Campo Turmaac nao Informado.";
         $this->erro_campo = "ed270_i_turmaac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed270_i_codigo!=null){
       $sql .= " ed270_i_codigo = $this->ed270_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed270_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13840,'$this->ed270_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_codigo"]) || $this->ed270_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2418,13840,'".AddSlashes(pg_result($resaco,$conresaco,'ed270_i_codigo'))."','$this->ed270_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_rechumano"]) || $this->ed270_i_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,2418,13842,'".AddSlashes(pg_result($resaco,$conresaco,'ed270_i_rechumano'))."','$this->ed270_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_diasemana"]) || $this->ed270_i_diasemana != "")
           $resac = db_query("insert into db_acount values($acount,2418,13843,'".AddSlashes(pg_result($resaco,$conresaco,'ed270_i_diasemana'))."','$this->ed270_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_periodo"]) || $this->ed270_i_periodo != "")
           $resac = db_query("insert into db_acount values($acount,2418,13844,'".AddSlashes(pg_result($resaco,$conresaco,'ed270_i_periodo'))."','$this->ed270_i_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed270_i_turmaac"]) || $this->ed270_i_turmaac != "")
           $resac = db_query("insert into db_acount values($acount,2418,15443,'".AddSlashes(pg_result($resaco,$conresaco,'ed270_i_turmaac'))."','$this->ed270_i_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horarios de Turma com Ativ. Comp. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed270_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Horarios de Turma com Ativ. Comp. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed270_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed270_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed270_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed270_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13840,'$ed270_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2418,13840,'','".AddSlashes(pg_result($resaco,$iresaco,'ed270_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2418,13842,'','".AddSlashes(pg_result($resaco,$iresaco,'ed270_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2418,13843,'','".AddSlashes(pg_result($resaco,$iresaco,'ed270_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2418,13844,'','".AddSlashes(pg_result($resaco,$iresaco,'ed270_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2418,15443,'','".AddSlashes(pg_result($resaco,$iresaco,'ed270_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmaachorario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed270_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed270_i_codigo = $ed270_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horarios de Turma com Ativ. Comp. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed270_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Horarios de Turma com Ativ. Comp. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed270_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed270_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaachorario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed270_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaachorario ";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
     $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = turmaachorario.ed270_i_periodo";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorario.ed270_i_diasemana";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql .= "      left join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
     $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
     $sql .= "      inner join turno  as b on   b.ed15_i_codigo = periodoescola.ed17_i_turno";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = turmaachorario.ed270_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicender and  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
     $sql .= "      left join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql2 = "";
     if($dbwhere==""){
       if($ed270_i_codigo!=null ){
         $sql2 .= " where turmaachorario.ed270_i_codigo = $ed270_i_codigo "; 
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
   function sql_query_file ( $ed270_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaachorario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed270_i_codigo!=null ){
         $sql2 .= " where turmaachorario.ed270_i_codigo = $ed270_i_codigo "; 
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

  function sql_query_rechumano($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from turmaachorario ';
    $sSql .= '   inner join turmaac on turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac';
    $sSql .= '   inner join periodoescola on periodoescola.ed17_i_codigo = turmaachorario.ed270_i_periodo';
    $sSql .= '   inner join diasemana on diasemana.ed32_i_codigo = turmaachorario.ed270_i_diasemana';
    $sSql .= '   inner join escola on escola.ed18_i_codigo = turmaac.ed268_i_escola';
    $sSql .= '   inner join turno on turno.ed15_i_codigo = turmaac.ed268_i_turno';
    $sSql .= '   inner join calendario on calendario.ed52_i_codigo = turmaac.ed268_i_calendario';
    $sSql .= '   inner join periodoaula on periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula';
    $sSql .= '   inner join turno as b on b.ed15_i_codigo = periodoescola.ed17_i_turno';
    $sSql .= '   inner join rechumano on rechumano.ed20_i_codigo = turmaachorario.ed270_i_rechumano';
    $sSql .= '   inner join ((select cgm.*, rechumanopessoal.ed284_i_rechumano as rechumano,';
    $sSql .= '                       1 as tipo ';
    $sSql .= '                  from rechumano as a ';
    $sSql .= '                    inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                    inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal';
    $sSql .= '                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm';
    $sSql .= '                      where rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo)';
    $sSql .= '                                      union ';
    $sSql .= '               (select cgm.*, rechumanocgm.ed285_i_rechumano as rechumano,';
    $sSql .= '                       2 as tipo ';
    $sSql .= '                 from rechumano as a ';
    $sSql .= '                   inner join rechumanocgm on rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                   inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm';
    $sSql .= '                     where rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo)) as reccgm';
    $sSql .= '     on reccgm.rechumano = rechumano.ed20_i_codigo ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where turmaachorario.ed270_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_horario ($ed270_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
     
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
     $sql .= " from turmaachorario ";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
     $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = turmaachorario.ed270_i_periodo";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorario.ed270_i_diasemana";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed270_i_codigo!=null ){
         $sql2 .= " where turmaachorario.ed270_i_codigo = $ed270_i_codigo "; 
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
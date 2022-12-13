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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoitemexecutadoempenho
class cl_acordoitemexecutadoempenho { 
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
   var $ac39_sequencial = 0; 
   var $ac39_acordoitemexecutadoperiodo = 0; 
   var $ac39_numemp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac39_sequencial = int4 = Codigo_sequencial 
                 ac39_acordoitemexecutadoperiodo = int4 = Item 
                 ac39_numemp = int4 = Empenho 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemexecutadoempenho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemexecutadoempenho"); 
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
       $this->ac39_sequencial = ($this->ac39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac39_sequencial"]:$this->ac39_sequencial);
       $this->ac39_acordoitemexecutadoperiodo = ($this->ac39_acordoitemexecutadoperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac39_acordoitemexecutadoperiodo"]:$this->ac39_acordoitemexecutadoperiodo);
       $this->ac39_numemp = ($this->ac39_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["ac39_numemp"]:$this->ac39_numemp);
     }else{
       $this->ac39_sequencial = ($this->ac39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac39_sequencial"]:$this->ac39_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac39_sequencial){ 
      $this->atualizacampos();
     if($this->ac39_acordoitemexecutadoperiodo == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "ac39_acordoitemexecutadoperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac39_numemp == null ){ 
       $this->erro_sql = " Campo Empenho nao Informado.";
       $this->erro_campo = "ac39_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac39_sequencial == "" || $ac39_sequencial == null ){
       $result = db_query("select nextval('acordoitemexecutadoempenho_ac39_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemexecutadoempenho_ac39_sequencial_seq do campo: ac39_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac39_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemexecutadoempenho_ac39_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac39_sequencial)){
         $this->erro_sql = " Campo ac39_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac39_sequencial = $ac39_sequencial; 
       }
     }
     if(($this->ac39_sequencial == null) || ($this->ac39_sequencial == "") ){ 
       $this->erro_sql = " Campo ac39_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemexecutadoempenho(
                                       ac39_sequencial 
                                      ,ac39_acordoitemexecutadoperiodo 
                                      ,ac39_numemp 
                       )
                values (
                                $this->ac39_sequencial 
                               ,$this->ac39_acordoitemexecutadoperiodo 
                               ,$this->ac39_numemp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "acordoitemexecutadoempenho ($this->ac39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "acordoitemexecutadoempenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "acordoitemexecutadoempenho ($this->ac39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac39_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac39_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18053,'$this->ac39_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3190,18053,'','".AddSlashes(pg_result($resaco,0,'ac39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3190,18054,'','".AddSlashes(pg_result($resaco,0,'ac39_acordoitemexecutadoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3190,18055,'','".AddSlashes(pg_result($resaco,0,'ac39_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac39_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemexecutadoempenho set ";
     $virgula = "";
     if(trim($this->ac39_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac39_sequencial"])){ 
       $sql  .= $virgula." ac39_sequencial = $this->ac39_sequencial ";
       $virgula = ",";
       if(trim($this->ac39_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo_sequencial nao Informado.";
         $this->erro_campo = "ac39_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac39_acordoitemexecutadoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac39_acordoitemexecutadoperiodo"])){ 
       $sql  .= $virgula." ac39_acordoitemexecutadoperiodo = $this->ac39_acordoitemexecutadoperiodo ";
       $virgula = ",";
       if(trim($this->ac39_acordoitemexecutadoperiodo) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "ac39_acordoitemexecutadoperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac39_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac39_numemp"])){ 
       $sql  .= $virgula." ac39_numemp = $this->ac39_numemp ";
       $virgula = ",";
       if(trim($this->ac39_numemp) == null ){ 
         $this->erro_sql = " Campo Empenho nao Informado.";
         $this->erro_campo = "ac39_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac39_sequencial!=null){
       $sql .= " ac39_sequencial = $this->ac39_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac39_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18053,'$this->ac39_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac39_sequencial"]) || $this->ac39_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3190,18053,'".AddSlashes(pg_result($resaco,$conresaco,'ac39_sequencial'))."','$this->ac39_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac39_acordoitemexecutadoperiodo"]) || $this->ac39_acordoitemexecutadoperiodo != "")
           $resac = db_query("insert into db_acount values($acount,3190,18054,'".AddSlashes(pg_result($resaco,$conresaco,'ac39_acordoitemexecutadoperiodo'))."','$this->ac39_acordoitemexecutadoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac39_numemp"]) || $this->ac39_numemp != "")
           $resac = db_query("insert into db_acount values($acount,3190,18055,'".AddSlashes(pg_result($resaco,$conresaco,'ac39_numemp'))."','$this->ac39_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemexecutadoempenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemexecutadoempenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac39_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac39_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18053,'$ac39_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3190,18053,'','".AddSlashes(pg_result($resaco,$iresaco,'ac39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3190,18054,'','".AddSlashes(pg_result($resaco,$iresaco,'ac39_acordoitemexecutadoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3190,18055,'','".AddSlashes(pg_result($resaco,$iresaco,'ac39_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemexecutadoempenho
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac39_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac39_sequencial = $ac39_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemexecutadoempenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemexecutadoempenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac39_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemexecutadoempenho";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoempenho ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = acordoitemexecutadoempenho.ac39_numemp";
     $sql .= "      inner join acordoitemexecutadoperiodo  on  acordoitemexecutadoperiodo.ac38_sequencial = acordoitemexecutadoempenho.ac39_acordoitemexecutadoperiodo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql .= "      inner join acordoitemexecutado  as a on   a.ac29_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
     $sql .= "      inner join acordoitemprevisao  on  acordoitemprevisao.ac37_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemprevisao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac39_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoempenho.ac39_sequencial = $ac39_sequencial "; 
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
   function sql_query_file ( $ac39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoempenho ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac39_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoempenho.ac39_sequencial = $ac39_sequencial "; 
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
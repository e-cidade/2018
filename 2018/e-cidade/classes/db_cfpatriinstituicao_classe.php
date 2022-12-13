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

//MODULO: patrimonio
//CLASSE DA ENTIDADE cfpatriinstituicao
class cl_cfpatriinstituicao { 
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
   var $t59_sequencial = 0; 
   var $t59_instituicao = 0; 
   var $t59_dataimplanatacaodepreciacao_dia = null; 
   var $t59_dataimplanatacaodepreciacao_mes = null; 
   var $t59_dataimplanatacaodepreciacao_ano = null; 
   var $t59_dataimplanatacaodepreciacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t59_sequencial = int4 = Sequencial 
                 t59_instituicao = int4 = Instituição 
                 t59_dataimplanatacaodepreciacao = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_cfpatriinstituicao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cfpatriinstituicao"); 
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
       $this->t59_sequencial = ($this->t59_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t59_sequencial"]:$this->t59_sequencial);
       $this->t59_instituicao = ($this->t59_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["t59_instituicao"]:$this->t59_instituicao);
       if($this->t59_dataimplanatacaodepreciacao == ""){
         $this->t59_dataimplanatacaodepreciacao_dia = ($this->t59_dataimplanatacaodepreciacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao_dia"]:$this->t59_dataimplanatacaodepreciacao_dia);
         $this->t59_dataimplanatacaodepreciacao_mes = ($this->t59_dataimplanatacaodepreciacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao_mes"]:$this->t59_dataimplanatacaodepreciacao_mes);
         $this->t59_dataimplanatacaodepreciacao_ano = ($this->t59_dataimplanatacaodepreciacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao_ano"]:$this->t59_dataimplanatacaodepreciacao_ano);
         if($this->t59_dataimplanatacaodepreciacao_dia != ""){
            $this->t59_dataimplanatacaodepreciacao = $this->t59_dataimplanatacaodepreciacao_ano."-".$this->t59_dataimplanatacaodepreciacao_mes."-".$this->t59_dataimplanatacaodepreciacao_dia;
         }
       }
     }else{
       $this->t59_sequencial = ($this->t59_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t59_sequencial"]:$this->t59_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t59_sequencial){ 
      $this->atualizacampos();
     if($this->t59_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "t59_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t59_dataimplanatacaodepreciacao == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "t59_dataimplanatacaodepreciacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t59_sequencial == "" || $t59_sequencial == null ){
       $result = db_query("select nextval('cfpatriinstituicao_t59_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cfpatriinstituicao_t59_sequencial_seq do campo: t59_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t59_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cfpatriinstituicao_t59_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t59_sequencial)){
         $this->erro_sql = " Campo t59_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t59_sequencial = $t59_sequencial; 
       }
     }
     if(($this->t59_sequencial == null) || ($this->t59_sequencial == "") ){ 
       $this->erro_sql = " Campo t59_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfpatriinstituicao(
                                       t59_sequencial 
                                      ,t59_instituicao 
                                      ,t59_dataimplanatacaodepreciacao 
                       )
                values (
                                $this->t59_sequencial 
                               ,$this->t59_instituicao 
                               ,".($this->t59_dataimplanatacaodepreciacao == "null" || $this->t59_dataimplanatacaodepreciacao == ""?"null":"'".$this->t59_dataimplanatacaodepreciacao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros do patrimônio por instituição ($this->t59_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros do patrimônio por instituição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros do patrimônio por instituição ($this->t59_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t59_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t59_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18574,'$this->t59_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3286,18574,'','".AddSlashes(pg_result($resaco,0,'t59_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3286,18575,'','".AddSlashes(pg_result($resaco,0,'t59_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3286,18576,'','".AddSlashes(pg_result($resaco,0,'t59_dataimplanatacaodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t59_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cfpatriinstituicao set ";
     $virgula = "";
     if(trim($this->t59_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t59_sequencial"])){ 
       $sql  .= $virgula." t59_sequencial = $this->t59_sequencial ";
       $virgula = ",";
       if(trim($this->t59_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "t59_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t59_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t59_instituicao"])){ 
       $sql  .= $virgula." t59_instituicao = $this->t59_instituicao ";
       $virgula = ",";
       if(trim($this->t59_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t59_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t59_dataimplanatacaodepreciacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao_dia"] !="") ){ 
       $sql  .= $virgula." t59_dataimplanatacaodepreciacao = '$this->t59_dataimplanatacaodepreciacao' ";
       $virgula = ",";
       if(trim($this->t59_dataimplanatacaodepreciacao) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "t59_dataimplanatacaodepreciacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao_dia"])){ 
         $sql  .= $virgula." t59_dataimplanatacaodepreciacao = null ";
         $virgula = ",";
         if(trim($this->t59_dataimplanatacaodepreciacao) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "t59_dataimplanatacaodepreciacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($t59_sequencial!=null){
       $sql .= " t59_sequencial = $this->t59_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t59_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18574,'$this->t59_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t59_sequencial"]) || $this->t59_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3286,18574,'".AddSlashes(pg_result($resaco,$conresaco,'t59_sequencial'))."','$this->t59_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t59_instituicao"]) || $this->t59_instituicao != "")
           $resac = db_query("insert into db_acount values($acount,3286,18575,'".AddSlashes(pg_result($resaco,$conresaco,'t59_instituicao'))."','$this->t59_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t59_dataimplanatacaodepreciacao"]) || $this->t59_dataimplanatacaodepreciacao != "")
           $resac = db_query("insert into db_acount values($acount,3286,18576,'".AddSlashes(pg_result($resaco,$conresaco,'t59_dataimplanatacaodepreciacao'))."','$this->t59_dataimplanatacaodepreciacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do patrimônio por instituição nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t59_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do patrimônio por instituição nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t59_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t59_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t59_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t59_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18574,'$t59_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3286,18574,'','".AddSlashes(pg_result($resaco,$iresaco,'t59_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3286,18575,'','".AddSlashes(pg_result($resaco,$iresaco,'t59_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3286,18576,'','".AddSlashes(pg_result($resaco,$iresaco,'t59_dataimplanatacaodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cfpatriinstituicao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t59_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t59_sequencial = $t59_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do patrimônio por instituição nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t59_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do patrimônio por instituição nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t59_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t59_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cfpatriinstituicao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t59_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfpatriinstituicao ";
     $sql .= "      inner join db_config  on  db_config.codigo = cfpatriinstituicao.t59_instituicao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($t59_sequencial!=null ){
         $sql2 .= " where cfpatriinstituicao.t59_sequencial = $t59_sequencial "; 
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
   function sql_query_file ( $t59_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfpatriinstituicao ";
     $sql2 = "";
     if($dbwhere==""){
       if($t59_sequencial!=null ){
         $sql2 .= " where cfpatriinstituicao.t59_sequencial = $t59_sequencial "; 
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
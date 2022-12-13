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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE consolidacaodebitos
class cl_consolidacaodebitos { 
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
   var $k161_sequencial = 0; 
   var $k161_datageracao_dia = null; 
   var $k161_datageracao_mes = null; 
   var $k161_datageracao_ano = null; 
   var $k161_datageracao = null; 
   var $k161_usuario = 0; 
   var $k161_filtrosselecionados = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k161_sequencial = int4 = Sequencial 
                 k161_datageracao = date = Data da Geração 
                 k161_usuario = int4 = Usuário 
                 k161_filtrosselecionados = text = Filtros Selecionados 
                 ";
   //funcao construtor da classe 
   function cl_consolidacaodebitos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("consolidacaodebitos"); 
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
       $this->k161_sequencial = ($this->k161_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_sequencial"]:$this->k161_sequencial);
       if($this->k161_datageracao == ""){
         $this->k161_datageracao_dia = ($this->k161_datageracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_datageracao_dia"]:$this->k161_datageracao_dia);
         $this->k161_datageracao_mes = ($this->k161_datageracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_datageracao_mes"]:$this->k161_datageracao_mes);
         $this->k161_datageracao_ano = ($this->k161_datageracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_datageracao_ano"]:$this->k161_datageracao_ano);
         if($this->k161_datageracao_dia != ""){
            $this->k161_datageracao = $this->k161_datageracao_ano."-".$this->k161_datageracao_mes."-".$this->k161_datageracao_dia;
         }
       }
       $this->k161_usuario = ($this->k161_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_usuario"]:$this->k161_usuario);
       $this->k161_filtrosselecionados = ($this->k161_filtrosselecionados == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_filtrosselecionados"]:$this->k161_filtrosselecionados);
     }else{
       $this->k161_sequencial = ($this->k161_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k161_sequencial"]:$this->k161_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k161_sequencial){ 
      $this->atualizacampos();
     if($this->k161_datageracao == null ){ 
       $this->erro_sql = " Campo Data da Geração nao Informado.";
       $this->erro_campo = "k161_datageracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k161_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k161_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k161_filtrosselecionados == null ){ 
       $this->erro_sql = " Campo Filtros Selecionados nao Informado.";
       $this->erro_campo = "k161_filtrosselecionados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k161_sequencial == "" || $k161_sequencial == null ){
       $result = db_query("select nextval('consolidacaodebitos_k161_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: consolidacaodebitos_k161_sequencial_seq do campo: k161_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k161_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from consolidacaodebitos_k161_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k161_sequencial)){
         $this->erro_sql = " Campo k161_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k161_sequencial = $k161_sequencial; 
       }
     }
     if(($this->k161_sequencial == null) || ($this->k161_sequencial == "") ){ 
       $this->erro_sql = " Campo k161_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into consolidacaodebitos(
                                       k161_sequencial 
                                      ,k161_datageracao 
                                      ,k161_usuario 
                                      ,k161_filtrosselecionados 
                       )
                values (
                                $this->k161_sequencial 
                               ,".($this->k161_datageracao == "null" || $this->k161_datageracao == ""?"null":"'".$this->k161_datageracao."'")." 
                               ,$this->k161_usuario 
                               ,'$this->k161_filtrosselecionados' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Consolidação de Débitos ($this->k161_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Consolidação de Débitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Consolidação de Débitos ($this->k161_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k161_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k161_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19716,'$this->k161_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3535,19716,'','".AddSlashes(pg_result($resaco,0,'k161_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3535,19717,'','".AddSlashes(pg_result($resaco,0,'k161_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3535,19718,'','".AddSlashes(pg_result($resaco,0,'k161_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3535,19719,'','".AddSlashes(pg_result($resaco,0,'k161_filtrosselecionados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k161_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update consolidacaodebitos set ";
     $virgula = "";
     if(trim($this->k161_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k161_sequencial"])){ 
       $sql  .= $virgula." k161_sequencial = $this->k161_sequencial ";
       $virgula = ",";
       if(trim($this->k161_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k161_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k161_datageracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k161_datageracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k161_datageracao_dia"] !="") ){ 
       $sql  .= $virgula." k161_datageracao = '$this->k161_datageracao' ";
       $virgula = ",";
       if(trim($this->k161_datageracao) == null ){ 
         $this->erro_sql = " Campo Data da Geração nao Informado.";
         $this->erro_campo = "k161_datageracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k161_datageracao_dia"])){ 
         $sql  .= $virgula." k161_datageracao = null ";
         $virgula = ",";
         if(trim($this->k161_datageracao) == null ){ 
           $this->erro_sql = " Campo Data da Geração nao Informado.";
           $this->erro_campo = "k161_datageracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k161_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k161_usuario"])){ 
       $sql  .= $virgula." k161_usuario = $this->k161_usuario ";
       $virgula = ",";
       if(trim($this->k161_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k161_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k161_filtrosselecionados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k161_filtrosselecionados"])){ 
       $sql  .= $virgula." k161_filtrosselecionados = '$this->k161_filtrosselecionados' ";
       $virgula = ",";
       if(trim($this->k161_filtrosselecionados) == null ){ 
         $this->erro_sql = " Campo Filtros Selecionados nao Informado.";
         $this->erro_campo = "k161_filtrosselecionados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k161_sequencial!=null){
       $sql .= " k161_sequencial = $this->k161_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k161_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19716,'$this->k161_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k161_sequencial"]) || $this->k161_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3535,19716,'".AddSlashes(pg_result($resaco,$conresaco,'k161_sequencial'))."','$this->k161_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k161_datageracao"]) || $this->k161_datageracao != "")
           $resac = db_query("insert into db_acount values($acount,3535,19717,'".AddSlashes(pg_result($resaco,$conresaco,'k161_datageracao'))."','$this->k161_datageracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k161_usuario"]) || $this->k161_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3535,19718,'".AddSlashes(pg_result($resaco,$conresaco,'k161_usuario'))."','$this->k161_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k161_filtrosselecionados"]) || $this->k161_filtrosselecionados != "")
           $resac = db_query("insert into db_acount values($acount,3535,19719,'".AddSlashes(pg_result($resaco,$conresaco,'k161_filtrosselecionados'))."','$this->k161_filtrosselecionados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Consolidação de Débitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k161_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Consolidação de Débitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k161_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k161_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k161_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k161_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19716,'$k161_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3535,19716,'','".AddSlashes(pg_result($resaco,$iresaco,'k161_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3535,19717,'','".AddSlashes(pg_result($resaco,$iresaco,'k161_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3535,19718,'','".AddSlashes(pg_result($resaco,$iresaco,'k161_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3535,19719,'','".AddSlashes(pg_result($resaco,$iresaco,'k161_filtrosselecionados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from consolidacaodebitos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k161_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k161_sequencial = $k161_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Consolidação de Débitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k161_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Consolidação de Débitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k161_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k161_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:consolidacaodebitos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k161_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from consolidacaodebitos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k161_sequencial!=null ){
         $sql2 .= " where consolidacaodebitos.k161_sequencial = $k161_sequencial "; 
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
   function sql_query_file ( $k161_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from consolidacaodebitos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k161_sequencial!=null ){
         $sql2 .= " where consolidacaodebitos.k161_sequencial = $k161_sequencial "; 
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
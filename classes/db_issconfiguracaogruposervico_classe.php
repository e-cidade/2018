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

//MODULO: issqn
//CLASSE DA ENTIDADE issconfiguracaogruposervico
class cl_issconfiguracaogruposervico { 
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
   var $q136_sequencial = 0; 
   var $q136_issgruposervico = 0; 
   var $q136_exercicio = 0; 
   var $q136_tipotributacao = 0; 
   var $q136_valor = 0; 
   var $q136_localpagamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q136_sequencial = int8 = Sequencial 
                 q136_issgruposervico = int4 = Grupo de serviço 
                 q136_exercicio = int4 = Exercício 
                 q136_tipotributacao = int4 = Tipo de tributação 
                 q136_valor = float8 = Valor 
                 q136_localpagamento = int4 = Local de pagamento 
                 ";
   //funcao construtor da classe 
   function cl_issconfiguracaogruposervico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issconfiguracaogruposervico"); 
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
       $this->q136_sequencial = ($this->q136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_sequencial"]:$this->q136_sequencial);
       $this->q136_issgruposervico = ($this->q136_issgruposervico == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_issgruposervico"]:$this->q136_issgruposervico);
       $this->q136_exercicio = ($this->q136_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_exercicio"]:$this->q136_exercicio);
       $this->q136_tipotributacao = ($this->q136_tipotributacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_tipotributacao"]:$this->q136_tipotributacao);
       $this->q136_valor = ($this->q136_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_valor"]:$this->q136_valor);
       $this->q136_localpagamento = ($this->q136_localpagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_localpagamento"]:$this->q136_localpagamento);
     }else{
       $this->q136_sequencial = ($this->q136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q136_sequencial"]:$this->q136_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q136_sequencial){ 
      $this->atualizacampos();
     if($this->q136_issgruposervico == null ){ 
       $this->erro_sql = " Campo Grupo de serviço nao Informado.";
       $this->erro_campo = "q136_issgruposervico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q136_exercicio == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "q136_exercicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q136_tipotributacao == null ){ 
       $this->erro_sql = " Campo Tipo de tributação nao Informado.";
       $this->erro_campo = "q136_tipotributacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q136_valor == null ){ 
       $this->q136_valor = "0";
     }
     if($this->q136_localpagamento == null ){ 
       $this->erro_sql = " Campo Local de pagamento nao Informado.";
       $this->erro_campo = "q136_localpagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q136_sequencial == "" || $q136_sequencial == null ){
       $result = db_query("select nextval('issconfiguracaogruposervico_q136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issconfiguracaogruposervico_q136_sequencial_seq do campo: q136_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issconfiguracaogruposervico_q136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q136_sequencial)){
         $this->erro_sql = " Campo q136_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q136_sequencial = $q136_sequencial; 
       }
     }
     if(($this->q136_sequencial == null) || ($this->q136_sequencial == "") ){ 
       $this->erro_sql = " Campo q136_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issconfiguracaogruposervico(
                                       q136_sequencial 
                                      ,q136_issgruposervico 
                                      ,q136_exercicio 
                                      ,q136_tipotributacao 
                                      ,q136_valor 
                                      ,q136_localpagamento 
                       )
                values (
                                $this->q136_sequencial 
                               ,$this->q136_issgruposervico 
                               ,$this->q136_exercicio 
                               ,$this->q136_tipotributacao 
                               ,$this->q136_valor 
                               ,$this->q136_localpagamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q136_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q136_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19291,'$this->q136_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3430,19291,'','".AddSlashes(pg_result($resaco,0,'q136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3430,19296,'','".AddSlashes(pg_result($resaco,0,'q136_issgruposervico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3430,19299,'','".AddSlashes(pg_result($resaco,0,'q136_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3430,19300,'','".AddSlashes(pg_result($resaco,0,'q136_tipotributacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3430,19301,'','".AddSlashes(pg_result($resaco,0,'q136_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3430,19302,'','".AddSlashes(pg_result($resaco,0,'q136_localpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issconfiguracaogruposervico set ";
     $virgula = "";
     if(trim($this->q136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q136_sequencial"])){ 
       $sql  .= $virgula." q136_sequencial = $this->q136_sequencial ";
       $virgula = ",";
       if(trim($this->q136_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q136_issgruposervico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q136_issgruposervico"])){ 
       $sql  .= $virgula." q136_issgruposervico = $this->q136_issgruposervico ";
       $virgula = ",";
       if(trim($this->q136_issgruposervico) == null ){ 
         $this->erro_sql = " Campo Grupo de serviço nao Informado.";
         $this->erro_campo = "q136_issgruposervico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q136_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q136_exercicio"])){ 
       $sql  .= $virgula." q136_exercicio = $this->q136_exercicio ";
       $virgula = ",";
       if(trim($this->q136_exercicio) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "q136_exercicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q136_tipotributacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q136_tipotributacao"])){ 
       $sql  .= $virgula." q136_tipotributacao = $this->q136_tipotributacao ";
       $virgula = ",";
       if(trim($this->q136_tipotributacao) == null ){ 
         $this->erro_sql = " Campo Tipo de tributação nao Informado.";
         $this->erro_campo = "q136_tipotributacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q136_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q136_valor"])){ 
        if(trim($this->q136_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q136_valor"])){ 
           $this->q136_valor = "0" ; 
        } 
       $sql  .= $virgula." q136_valor = $this->q136_valor ";
       $virgula = ",";
     }
     if(trim($this->q136_localpagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q136_localpagamento"])){ 
       $sql  .= $virgula." q136_localpagamento = $this->q136_localpagamento ";
       $virgula = ",";
       if(trim($this->q136_localpagamento) == null ){ 
         $this->erro_sql = " Campo Local de pagamento nao Informado.";
         $this->erro_campo = "q136_localpagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q136_sequencial!=null){
       $sql .= " q136_sequencial = $this->q136_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q136_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19291,'$this->q136_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q136_sequencial"]) || $this->q136_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3430,19291,'".AddSlashes(pg_result($resaco,$conresaco,'q136_sequencial'))."','$this->q136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q136_issgruposervico"]) || $this->q136_issgruposervico != "")
           $resac = db_query("insert into db_acount values($acount,3430,19296,'".AddSlashes(pg_result($resaco,$conresaco,'q136_issgruposervico'))."','$this->q136_issgruposervico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q136_exercicio"]) || $this->q136_exercicio != "")
           $resac = db_query("insert into db_acount values($acount,3430,19299,'".AddSlashes(pg_result($resaco,$conresaco,'q136_exercicio'))."','$this->q136_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q136_tipotributacao"]) || $this->q136_tipotributacao != "")
           $resac = db_query("insert into db_acount values($acount,3430,19300,'".AddSlashes(pg_result($resaco,$conresaco,'q136_tipotributacao'))."','$this->q136_tipotributacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q136_valor"]) || $this->q136_valor != "")
           $resac = db_query("insert into db_acount values($acount,3430,19301,'".AddSlashes(pg_result($resaco,$conresaco,'q136_valor'))."','$this->q136_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q136_localpagamento"]) || $this->q136_localpagamento != "")
           $resac = db_query("insert into db_acount values($acount,3430,19302,'".AddSlashes(pg_result($resaco,$conresaco,'q136_localpagamento'))."','$this->q136_localpagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q136_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q136_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19291,'$q136_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3430,19291,'','".AddSlashes(pg_result($resaco,$iresaco,'q136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3430,19296,'','".AddSlashes(pg_result($resaco,$iresaco,'q136_issgruposervico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3430,19299,'','".AddSlashes(pg_result($resaco,$iresaco,'q136_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3430,19300,'','".AddSlashes(pg_result($resaco,$iresaco,'q136_tipotributacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3430,19301,'','".AddSlashes(pg_result($resaco,$iresaco,'q136_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3430,19302,'','".AddSlashes(pg_result($resaco,$iresaco,'q136_localpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issconfiguracaogruposervico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q136_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q136_sequencial = $q136_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q136_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issconfiguracaogruposervico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issconfiguracaogruposervico ";
     $sql .= "      inner join issgruposervico  on  issgruposervico.q126_sequencial = issconfiguracaogruposervico.q136_issgruposervico";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = issgruposervico.q126_db_estruturavalor";
     $sql2 = "";
     if($dbwhere==""){
       if($q136_sequencial!=null ){
         $sql2 .= " where issconfiguracaogruposervico.q136_sequencial = $q136_sequencial "; 
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
   function sql_query_file ( $q136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issconfiguracaogruposervico ";
     $sql2 = "";
     if($dbwhere==""){
       if($q136_sequencial!=null ){
         $sql2 .= " where issconfiguracaogruposervico.q136_sequencial = $q136_sequencial "; 
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
   function sql_query_grupoServico($sCampos = '*', $sWhere = '') { 

	 $sSqlGrupoServico = "select ";

	 $sSqlGrupoServico .= $sCampos;

	 $sSqlGrupoServico .= " from issconfiguracaogruposervico                                                                   ";
	 $sSqlGrupoServico .= "      right join issgruposervico                                                                    ";
	 $sSqlGrupoServico .= " 						 on issgruposervico.q126_sequencial = issconfiguracaogruposervico.q136_issgruposervico ";
	 $sSqlGrupoServico .= "      right join db_estruturavalor                                                                  ";
	 $sSqlGrupoServico .= " 						 on db_estruturavalor.db121_sequencial = issgruposervico.q126_db_estruturavalor        ";

	 if ( $sWhere != "" ) {
		 $sSqlGrupoServico .= " where $sWhere";
	 }

	 return $sSqlGrupoServico;
 }
}
?>